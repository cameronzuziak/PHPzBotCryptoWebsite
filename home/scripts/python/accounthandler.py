#! C:/Users/%USERPROFILE%/AppData/Local/Programs/Python/Python39/python.exe

from binance.client import Client
from binance.enums import *
import json, pprint
import sys
import mysql.connector 
import sqlhandler

account_id=sys.argv[1]
keys=sqlhandler.get_keys(account_id)
api_key=keys[0]
sec_key=keys[1]
client = Client(api_key, sec_key)
account=client.get_account()
assets=[] 

for coin in account['balances']:
    amt=coin['free']
    amt=float(amt)
    if(amt>0):
        x={ 
        "asset": str(coin['asset']),
        "free": str(coin['free']),
        "locked": float(coin['locked']),
        }
        assets.append(x)

json_obj = json.dumps(assets)
print(json_obj)


