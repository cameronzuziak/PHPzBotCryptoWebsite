# this script is for debugging, to replace print statements
# as scripts are being run asynchronously with no output. 

from serverkeys import *
from twilio.rest import Client

def send_msg(msg):
    client_phone='MyPhoneNumberHere'
    account_sid = TWILIO_ACCOUNT_SID
    auth_token = TWILIO_AUTH_TOKEN
    srvr_phone = TWILIO_PHONE_NUMBER
    client = Client(account_sid, auth_token)
    client.messages.create( body=msg, from_=srvr_phone, to=client_phone)
