#! C:/Users/%USERPROFILE%/AppData/Local/Programs/Python/Python39/python.exe
import os
import sys
import random
from serverkeys import *
from twilio.rest import Client
import re 

client_phone =  str(sys.argv[1])
account_sid = TWILIO_ACCOUNT_SID
auth_token = TWILIO_AUTH_TOKEN
srvr_phone = TWILIO_PHONE_NUMBER
client = Client(account_sid, auth_token)
phone_number = client.lookups.phone_numbers(client_phone).fetch(type=['carrier'])
print(phone_number.carrier.get('type'))