import os
import sys
from serverkeys import *
from cryptography.fernet import Fernet

#encryptionkey
key = E_KEY
# declare fernet instance
f = Fernet(key)

# encrypt message
def encr(message):
    message = os.fsencode(message)
    message = f.encrypt(message)
    message = os.fsdecode(message)
    return message
    
#decrypt message
def decr(message):
    message = os.fsencode(message)
    message = f.decrypt(message)
    message = os.fsdecode(message)
    return message

