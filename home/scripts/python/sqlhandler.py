import sys
import mysql.connector
import encryptionhandler, msghandler
from mysql.connector import errorcode


def get_keys(ID):
    try:
        con = mysql.connector.connect(user='root', password='mysql', host='localhost', database='demo')
        cursor = con.cursor()
    except mysql.connector.Error as err:
        print(err)
    if(con):
        pass
        #print("connection successful")
    query = ("SELECT Apikey, Seckey FROM loginform WHERE ID=%s")
    cursor.execute(query, (ID,))
    for (key1, key2) in cursor:
        apikey=str(key1)
        seckey=str(key2)
    #decrypt keys
    dec_apikey=encryptionhandler.decr(apikey)
    dec_seckey=encryptionhandler.decr(seckey)
    keys=[dec_apikey,dec_seckey]
    con.close()
    return keys


#make query to get bot settings
def get_trade_settings(ID):
    try:
        con = mysql.connector.connect(user='root', password='mysql', host='localhost', database='demo')
        cursor = con.cursor()
    except mysql.connector.Error as err:
        msghandler.send_msg(err)
        #print(err)
    if(con):
        pass
        #print("connection successful")
    query = ("SELECT Coin, RSIsell, RSIbuy, Running, InPos  FROM loginform WHERE ID=%s")
    cursor.execute(query, (ID,))
    for (coin,sell,buy,running,in_pos) in cursor:
        settings=[coin,sell,buy,running,in_pos] 
    con.close()
    return settings


def update_values(ID):
    try:
        con = mysql.connector.connect(user='root', password='mysql', host='localhost', database='demo')
        cursor = con.cursor()
    except mysql.connector.Error as err:
        msghandler.send_msg(err)
        #print(err)
    if(con):
        print("connection successful")
    query = ("SELECT RSIsell, RSIbuy, Running FROM loginform WHERE ID=%s")
    cursor.execute(query, (ID,))
    for (sell, buy, running) in cursor:
        values=[sell, buy, running]
    con.close()
    return values

