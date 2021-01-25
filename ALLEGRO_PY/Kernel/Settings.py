from base64 import b64encode
import datetime
sandbox = True

class Settings:
    ONLINE = True
    def OurgateOP(self):
        if sandbox:
            return 'http://192.168.1.222/ALLEPOV1/operator.php'
        else:
            return 'REMOTE_CALLBACK_OPERATOR'
    def OurIndex(self):
        if sandbox:
            return 'http://192.168.1.222/ALLEPOV1/?'
        else:
            return 'REMOTE_CALLBACK_GATE'
    def BROWSER_LOGIN_URL(self):
        if sandbox:
            return 'https://allegro.pl.allegrosandbox.pl/login/form?'
        else:
            return 'https://allegro.pl/login/form?'
    
    def TERMS_KILL_SWITCH(self):
        return 'button[data-role="close-and-accept-consent"]'
    def BROWSER_EMAIL_INPUT(self):
        return 'input[name="username"]'
    def BROWSER_PASS_INPUT(self):
        return 'input[type="password"]'
    def BROWSER_LOGIN_BUTTON(self):
        return '#login-button'
    def BROWSER_STORE_NAME(self):
        return Store().name()
    def BROWSER_EMAIL(self):
        return Store().email()
    def BROWSER_PASS(self):
        return Store().password()
    def BROWSER_CURRENT_USER(self):
        return '//span[@data-role="header-username"]/text()'
    def BROWSER_CONFIRM_URL(self, USER_CODE):
        if sandbox:
            return 'https://allegro.pl.allegrosandbox.pl/auth/oauth/headless/authorize?user_code='+str(USER_CODE) +'&prompt=none'
        else:
            return 'https://allegro.pl/auth/oauth/headless/authorize?user_code='+str(USER_CODE)+'&prompt=none'
    def BROWSER_CONFIRM_BUTTON(self):
        return '#confirmationForm > button'
    def ALLEGRO_DEVICE_TOKEN_URL(self,DEVICE_CODE):
        if sandbox:
            return 'https://allegro.pl.allegrosandbox.pl/auth/oauth/token?grant_type=urn%3Aietf%3Aparams%3Aoauth%3Agrant-type%3Adevice_code&device_code='+str(DEVICE_CODE)
        else:
            return 'https://allegro.pl/auth/oauth/token?grant_type=urn%3Aietf%3Aparams%3Aoauth%3Agrant-type%3Adevice_code&device_code='+str(DEVICE_CODE)
    def ALLEGR_FLOW_AUTH_URL(self):
        if sandbox:
           return 'https://allegro.pl.allegrosandbox.pl/auth/oauth/device'
        else:
            return 'https://allegro.pl/auth/oauth/device'

    def ALLEGRO_REFRESH_URL(self, TOKEN):
        if sandbox:
            return 'https://allegro.pl.allegrosandbox.pl/auth/oauth/token?grant_type=refresh_token&refresh_token='+str(TOKEN)
        else:
            return 'https://allegro.pl/auth/oauth/token?grant_type=refresh_token&refresh_token='+str(TOKEN)
    
    def ALLEGRO_WSL_ADDRESS(self):
        if sandbox:
            return 'https://webapi.allegro.pl.allegrosandbox.pl/service.php?wsdl'
        else:
            return 'https://webapi.allegro.pl/service.php?wsdl'

    def ALLEGRO_EVENTS_URL(self):
        if sandbox:
            return 'https://api.allegro.pl.allegrosandbox.pl/order/events'
        else:
            return 'https://api.allegro.pl/order/events'
    def ALLEGRO_API_KEY(self):
        if sandbox:
            return 'XXXXXXXXX'
        else:
            return 'XXXXXXXXX'
    def ALLEGRO_API_SECRET(self):
        if sandbox:
            return 'XXXXXXXXX'
        else:
            return 'XXXXXXXXX'

    def ALLEGRO_API_BOTH(self):
        return self.ALLEGRO_API_KEY()+":"+self.ALLEGRO_API_SECRET()
    def ALLEGRO_API_BOTH_64(self):
        return b64encode(self.ALLEGRO_API_BOTH().encode()).decode('utf-8')

    def HEADER_BASIC(self):
        return {
                    "Authorization":"Basic "+str(self.ALLEGRO_API_BOTH_64()),
                    "Content-type":"application/x-www-form-urlencoded"}
    def HEADER_BEARER(self, TOKEN):
        return {
                'Authorization':'Bearer '+str(TOKEN),
                'accept':'application/vnd.allegro.public.v1+json'}

     
    def FLOW_AUTH_POST_DATA(self):
        return {
            'client_id':self.ALLEGRO_API_KEY()
        }
    def AccesINI(self):
        return 'Storage//Access.ini'
    def CookiesJS(self):
        return 'Storage//Cookies.json'
    def RefreshedTokenLocal(self):
        return "Storage//RefreshToken.ini"
    def SignleToken(self):
        return "Storage//SingleToken.ini"
    def SoldLocal(self):
        return "Storage//Sold.ini"
    def MailFrom(self):
        return Store().email()
    def MailPWD(self):
        return 'PASSWORD'
    def BodyHTML(self):
        return 'Storage//Templates//Email.html'
    def SMTP_GMAIL(self):
        return "smtp.gmail.com"
    def SMTP_PORT(self):
        return 587
    def info(self,status,data):
        Now = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        if status > 1:
            print(Colors().CYAN() +"[+] ["+str(Now) +"] ========> "+ Colors().Success() +str(data))
        else:
            print(Colors().CYAN() +"[+] ["+str(Now) +"] ========> "+Colors().Error() + str(data))
from colorama import Fore, Back, Style
from colorama import init
init()
class Colors:
    def Success(self):
        return Fore.GREEN
    def Error(self):
        return Fore.RED
    def Warrning(self):
        return Fore.YELLOW
    def CYAN(self):
        return Fore.CYAN

import os
class Store:
    def read(self,path):
        if os.path.isfile(path):
            with open(path,'r') as reader:
                return reader.read()
    
    def splitter(self,data,delimiter,index):
        if delimiter in data:
            try:
                return data.split(delimiter)[index]
            except IndexError:
                Settings().info(0,'INDEX NOT FOUND')
    
    def name(self):
        return self.splitter(self.read('Storage//Store.ini'), '_',0)
    def email(self):
        return self.splitter(self.read('Storage//Store.ini'), '_',1)
    def password(self):
        return self.splitter(self.read('Storage//Store.ini'), '_',2)
