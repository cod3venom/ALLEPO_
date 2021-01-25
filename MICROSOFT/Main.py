 
from Kernel.Browser import Browser
import sys
class Main:
    def __init__(self, contact,email,password,key,title):
        Browser(contact,email,password,key,title).Login()

if __name__ == "__main__":
    try:
        #print('executed')
        contact,email,password,key,title = sys.argv[1] ,sys.argv[2], sys.argv[3], sys.argv[4], sys.argv[5]
        Main(contact,email, password, key,title) 
    except KeyboardInterrupt:
        print("Terminated by user")