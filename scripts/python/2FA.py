#! C:/Users/%USERPROFILE%/AppData/Local/Programs/Python/Python39/python.exe
import os
import sys
import random
from serverkeys import *
from twilio.rest import Client
import re


client_phone =  sys.argv[1]
random_id = ''.join([str(random.randint(0, 999)).zfill(3) for _ in range(2)])  
account_sid = TWILIO_ACCOUNT_SID
auth_token = TWILIO_AUTH_TOKEN
srvr_phone = TWILIO_PHONE_NUMBER
client = Client(account_sid, auth_token)
phone_number = client.lookups.phone_numbers(client_phone).fetch(type=['carrier'])


if phone_number.carrier.get('type') == 'viop':
    print('null')


elif phone_number.carrier.get('type') == 'mobile':
    client.messages.create(
                            body=random_id,
                            from_=srvr_phone,
                            to=client_phone
                          )
    print(random_id)

else:
    print('null')
