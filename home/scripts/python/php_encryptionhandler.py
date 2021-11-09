import os
import sys
from serverkeys import *
from cryptography.fernet import Fernet

#encryptionkey
key = E_KEY

#inputted api key
message = str(sys.argv[1])
#bool to decide whether to decrypt or encrypt, 0 to decrypt, 1 to encrypt
decorenc = int(sys.argv[2])

# declare fernet instance
f = Fernet(key)

# encrypt message
def encr(message):
    message = os.fsencode(message)
    message = f.encrypt(message)
    message = os.fsdecode(message)
    print(message)

    
#decrypt message
def decr(message):
    message = os.fsencode(message)
    message = f.decrypt(message)
    message = os.fsdecode(message)
    print(message)

if(decorenc==1):
    encr(message)

if(decorenc==0):
    decr(message)
    