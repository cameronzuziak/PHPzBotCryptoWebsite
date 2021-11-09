import websocket, json, pprint, talib, numpy
from binance.client import Client
from binance.enums import *
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
import datetime
import mysql.connector 
import math
import sqlhandler, msghandler
import sys

#set default values
PERIOD = 14
RSI_Sell = 70
RSI_Buy = 30
TICKER = 'ADA'
TRADE_SYMBOL = 'ADAUSDT'
closes = []
in_position = True
is_running = True
SOCKET = "wss://stream.binance.com:9443/ws/dogeusdt@kline_1m"

account_id=sys.argv[1]
keys=sqlhandler.get_keys(account_id)
api_key=keys[0]
sec_key=keys[1]
client = Client(api_key, sec_key)

def set_variables(id):
    global SOCKET, RSI_Sell, RSI_Buy, TICKER, in_position, TRADE_SYMBOL, is_running
    # grab clients bot settings, returns [coinpairing,rsi-sell,rsi-buy,is-running,is-in_pos] 
    bot_settings=sqlhandler.get_trade_settings(id)
    coinpair=bot_settings[0]
    coinpair=coinpair.split('/')
    TICKER=coinpair[0]
    TRADE_SYMBOL=TICKER + coinpair[1]
    x=TRADE_SYMBOL.lower()
    SOCKET = ("wss://stream.binance.com:9443/ws/%s@kline_1m" % (x,))
    RSI_Sell = int(bot_settings[1])
    RSI_Buy = int(bot_settings[2])
    if(bot_settings[4]==1):
        in_position=True
    elif(bot_settings[4]==0):
        in_position=False
    if(bot_settings[3]==1):
        is_running=True
    elif(bot_settings[3]==0):
        is_running=False
    msghandler.send_msg(SOCKET)

def update_values(id):
    global RSI_Sell, RSI_Buy, is_running
    # updated returns [buy, sell, running]
    # buy means oversold, sell means overbought
    updated=sqlhandler.update_values(id)
    RSI_Buy = int(updated[1])
    RSI_Sell = int(updated[0])
    if(updated[2]==1):
        is_running=True
    elif(updated[2]==0):
        is_running=False


def getBuyQuantity(TRADE_SYMBOL):
    last = client.get_symbol_ticker(symbol=TRADE_SYMBOL)
    last = float(last['price'])
    print(last)
    balance = client.get_asset_balance('USDT')
    balance = float(balance['free'])
    # .9 to account for slippage and rounding errors. 
    quantity = (balance / last) * .9
    quantity = round(quantity, 0)
    return quantity


def order(side, quantity, symbol, order_type=ORDER_TYPE_MARKET):
    global client
    try:
        print("sending order")
        order = client.create_order(symbol=symbol, side=side, type=order_type, quantity=quantity)
        print(order)

    except Exception as e:
        print("an exception occured - {}".format(e))
        return False
    
    return True


def on_open(ws):
    print('opened connection')
    #msghandler.send_msg('opened connection')


def on_close(ws):
    print('closed connection')
    #msghandler.send_msg("Connection Closed")



def on_message(ws, message):
    global closes, in_position, is_running, account_id
    #print('received message')
    json_message = json.loads(message)
    #pprint.pprint(json_message)
    candle = json_message['k']
    candle_closed = candle['x']
    close = candle['c']

    if candle_closed:

        update_values(account_id)
        # check to kill proccess
        if not is_running:
            ws.close()
            msghandler.send_msg("Connection Closed")
            sys.exit() 

        print("candle closed at {}".format(close))
        closes.append(float(close))


        if len(closes) > PERIOD:
            np_closes = numpy.array(closes)
            rsi = talib.RSI(np_closes, PERIOD)
            last_rsi = rsi[-1]
            prev_rsi = rsi[-2]

            #check if over sell thresh hold
            if last_rsi > RSI_Sell:
                # check for position status and retracement on RSI
                if in_position and last_rsi < prev_rsi:
                    # put binance sell logic here
                    balanceS = client.get_asset_balance(TICKER)
                    balanceS = balanceS['free']
                    balanceS = float(balanceS) 
                    balanceS = math.floor(balanceS)
                    balanceS = round(balanceS, 0)
                    order_succeeded = order(SIDE_SELL, balanceS, TRADE_SYMBOL)
                    if order_succeeded:
                        in_position = False
                        lastS = client.get_symbol_ticker(symbol=TRADE_SYMBOL)
                        lastS = lastS['price']
                        message = "sold %f of %s at %s" % (balanceS, TICKER, lastS)
                        #print(message)

            #check if oversold
            if last_rsi < RSI_Buy:
                if not in_position and last_rsi > prev_rsi:
                    # put binance buy order logic here
                    quant = getBuyQuantity(TRADE_SYMBOL)
                    order_succeeded = order(SIDE_BUY, quant, TRADE_SYMBOL)
                    if order_succeeded:
                        in_position = True
                        lastB = client.get_symbol_ticker(symbol=TRADE_SYMBOL)
                        lastB = lastB['price']
                        message = "bought %f of %s at %s" % (quant, TICKER, lastB)
                        #print(message)



set_variables(account_id)
ws = websocket.WebSocketApp(SOCKET, on_open=on_open, on_close=on_close, on_message=on_message)
ws.run_forever()
