from Kernel.FileSystem import FileSystem
from Kernel.Settings import Settings
from Kernel.Browser import Browser
import requests as sock
import json,os,time
class Spider:
    def __init__(self):
        self.preCheck()
    
    def preCheck(self):
        ''' IF ACCESS EXISTS THEN RUN WHEEL IN WHILE LOPP
            AND LISTEN FOR NEW EVENTS, BUT IF NOT THEN
            INSTALL COOKIES FROM THE BROWSER AND RECHECK
            THE ACCESS USING self.CheckAccess()
        '''
        if Auth().isLogged() == True:
            self.Wheel()
        else:
            Browser().Install()
            self.CheckAccess()

    def CheckAccess(self):
        '''UPDATING ACCESS.INI '''
        self.preCheck()
    
    def Wheel(self):
        if Allegro().singleRefreshToken() == True:
            if Allegro().GetRefreshedToken() == True:
                print('LOGGED IN SUCCESSFULLY')
                while Settings.ONLINE:
                    time.sleep(5)
                    self.Transact_AUX()
    def Transact_AUX(self):
        Events = Allegro().ListEvents()
        if Events != False:
            for i, items in Events.items():
                for item in items:
                    item_id = item['order']['lineItems'][0]['id']
                    if item_id not in FileSystem().Read(Settings().SoldLocal()):
                        FileSystem().AppendUniq(Settings().SoldLocal(),item_id)
                        item_name = item['order']['lineItems'][0]['offer']['name']
                        item_user = item['order']['buyer']['login']
                        item_mail = item['order']['buyer']['email']
                        item_price = item['order']['lineItems'][0]['price']['amount']
                        item_currency = item['order']['lineItems'][0]['price']['currency']
                        Customer(item_name,item_user,item_mail, item_price,item_currency)
         
    def checkCookieHTML(self):
        ck = FileSystem().cookieToDict()
        send = sock.get(Settings().BROWSER_LOGIN_URL(), cookies = ck).text
        with open("test.html","w", encoding="utf-8") as writer:
            writer.write(send)
            writer.close()

class Auth:
    def isLogged(self):
        ACC = FileSystem().Read(Settings().AccesINI())
        if ACC is not None or ACC != "":
            if ACC == "LOGGED":
                return True
            else:
                return False
        else:
            return False

class Allegro:
    def flowPARAMS(self):
        Response = sock.post(Settings().ALLEGR_FLOW_AUTH_URL(),
                                headers = Settings().HEADER_BASIC(),
                                data = Settings().FLOW_AUTH_POST_DATA()).text
        if 'ERROR' in Response:
            Settings().info(0,"COLLISION IN Allegro.flowPARAMS()")
        else:
            return json.loads(Response)

    def singleRefreshToken(self):
            flow = self.flowPARAMS()
            sock.get(Settings().BROWSER_CONFIRM_URL(flow['user_code']), 
                                cookies = FileSystem().cookieToDict()).text
            Response = sock.post(Settings().ALLEGRO_DEVICE_TOKEN_URL(flow['device_code']),
                                            headers=Settings().HEADER_BASIC()).text
            
            pack = json.loads(Response)
            if "ERROR" not in Response and 'refresh_token' in Response:
                    #print(Response)
                    FileSystem().Write(Settings().SignleToken(),"w","token=" + str(pack['refresh_token']))
                    return True
            else:
                Settings().info(0,"KEY NOT FOIND IN Spider.Allegro.flowLogin 69")
                if 'error' in pack:
                    Browser().FirstConfirmation(Settings().BROWSER_CONFIRM_URL(flow['user_code']))
                return False
            
         
    
    def GetRefreshedToken(self):
        Response = sock.post(Settings().ALLEGRO_REFRESH_URL(self.ReadToken('single')),
                                headers = Settings().HEADER_BASIC()).text
        pack = json.loads(Response)
        if Response is not None and "ERROR" not in Response and "error" not in Response:
            FileSystem().Write(Settings().RefreshedTokenLocal(),"w","token="+ str(pack["access_token"]))
            return True
        else:
            print(pack)
            return False
            
    def ListEvents(self):
        Response = sock.get(Settings().ALLEGRO_EVENTS_URL(),
                            headers=Settings().HEADER_BEARER(self.ReadToken('refresh'))).text
        if "ERROR" not in Response and "error" not in Response:
            return json.loads(Response)
        else: 
            print(Response)
            return False
    def ReadToken(self,which):
        path = ''
        if which == 'single':
            path = Settings().SignleToken()
        if which == 'refresh':
            path = Settings().RefreshedTokenLocal()
        if os.path.isfile(path):
            data = FileSystem().Read(path)
            if data is not None and "=" in data:
                if len(data) > 40:
                    return data.split("=")[1]

from Kernel.Mail import Mail
class Customer:
    def __init__(self, product,user,email,amount, currency):
        self.PRODUCT = product
        self.USER = user
        self.EMAIL = email
        self.PRICE = amount
        self.CURRENCY = currency
        Mail(self.PRODUCT,self.EMAIL,self.USER).Send()
        Operator().Addcustomer(self.PRODUCT, self.USER, self.EMAIL,self.PRICE,self.CURRENCY)


class Operator:

    def DoPost(self, direction, data):
        return sock.post(direction, data=data).text

    def Addcustomer(self, title,name,email,price,currency):
        obj = {'allegroMachine': '$2y$10$RU0skdxtIaLwzbS3YB90a.y/SCnp.ZEEyDsUuCXZean6R50Wg2PDq',
                'storename':Settings().BROWSER_STORE_NAME(),'title':title, 'username':name,'email':email,'price':price,'currency':currency}
        resp = self.DoPost(Settings().OurgateOP() ,obj)
        #print("RESPONS EIS " + resp)