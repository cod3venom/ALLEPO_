import smtplib,requests
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from email.mime.base import MIMEBase
from email import encoders
from selenium import webdriver
from selenium.common.exceptions import TimeoutException
from selenium.common.exceptions import NoSuchElementException

from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.options import Options
from datetime import datetime
from Kernel.Settings import Settings
from Kernel.Web import Web
from Kernel.Mail import Mail
import time , sys
class Browser:
    def __init__(self, CONTACT, EMAIL, PASSWORD, CODE, TITLE):
        self.CONTACT, self.EMAIL, self.PASSWORD, self.CODE , self.TITLE = CONTACT, EMAIL, PASSWORD, CODE, TITLE
        self.CHROME = webdriver.Chrome(executable_path=r"/usr/lib/chromium-browser/chromedriver", chrome_options=self.config_browser())
        try:
            WebDriverWait(self.CHROME, 10).until(EC.presence_of_element_located((By.TAG_NAME, "body")))
        except Exception as ex:
            print(str(ex))
        
    def config_browser(self):
        option = Options()
        #option.add_argument('--no-sandbox')
        #option.headless = True
        option.add_argument('--disable-extensions')
        option.add_argument('--log-level=3')
        option.add_experimental_option("detach",True)
        return option
        
    def Login(self):
        self.CHROME.get(Settings.LIVE)
        if self.Exists(Settings.MailInput):
            self.Input(Settings.MailInput, self.EMAIL)
            if self.Exists(Settings.SubmitBtn):
                self.Click(Settings.SubmitBtn)
            else:
                Mail('{}  problem podczas logowania, Browser.py 37 {}'.format(self.CONTACT, self.EMAIL), self.EMAIL).Send()
                print('Contact')
                self.Die(42)

        if self.Exists(Settings.PassInput) and self.Exists(Settings.SubmitBtn):
            self.Input(Settings.PassInput,self.PASSWORD)
            self.Click(Settings.SubmitBtn)
            self.Confirms()
            self.Confirms()
            if self.isLogged():
                self.Activate()
            else:
                Mail('Uzytkownikowi  {}  nie udalo sie aktywowac gre , poniewaz wystapil problem podczas logowania sie do konta microsoft  {}'.format(self.CONTACT, self.EMAIL), self.EMAIL).Send()
                self.Inform()
                print('Activated')
                self.Die(53)
        else:
            Mail('{}  problem podczas logowania, Browser.py 44 {}'.format(self.CONTACT, self.EMAIL), self.EMAIL).Send()
            print('Contact')
            self.Die(56)

    def Confirms(self):
        if self.Exists(Settings.SubmitBtn):
            self.Click(Settings.SubmitBtn)

    def Activate(self):
        self.CHROME.get(Settings.ActivatePage)
        if self.Exists(Settings.CodeInput):
            self.Input(Settings.CodeInput, self.CODE)
            self.Click(Settings.SubmitBtn)
            if self.isActivated():
                Web(self.EMAIL).Update(1)
                self.Inform()
                print('Activated')
                self.Die(69)
            else:
                Web(self.EMAIL).Update(0)
                print('Error')
                self.Die(72)
        else:
            self.Die(74)
    def isActivated(self):
        if self.Exists(Settings.ErrorCode):
            #Settings().Debug(0,"NOT ACTIVE")
            return False
        else:
            #Settings().Debug(1,"ACTIVE")
            return True

    def isLogged(self):
        time.sleep(3)
        if self.CHROME is not None:
            for ck in  self.CHROME.get_cookies():
                while ck['name'] == 'ShCLSessionID':
                    print('LOGGGED IN')
                    return True
            return False
    
    def Exists(self,target):
        try:
            self.CHROME.find_element_by_css_selector(target)
            return True
        except NoSuchElementException:
            return False
    
    def Input(self,target,data):
        time.sleep(2)
        if self.Exists(target):
            self.CHROME.find_element_by_css_selector(target).send_keys(data)
            #Settings().Debug(1,"Entered  into " + str(target))
        else:
            #Settings().Debug(0,'CANT FOUND ' + str(target))
            pass
    
    def Click(self,target):
        time.sleep(2)
        if self.Exists(target):
            self.CHROME.find_element_by_css_selector(target).send_keys(Keys.RETURN)
            #Settings().Debug(1,"Clicked on " + str(target))
        else:
            #Settings().Debug(0,'CANT FOUND ' + str(target))
            pass
    def SEND_ACTIVATION(self):
        hash_req = {'microMachine':'$2y$10$RU0skdxtIaLwzbS3YB90a.y/SCnp.ZEEyDsUuCXZean6R50Wg2PDq',
                    'email':self.EMAIL
        }
        output = Settings.INFO_MAIL
        output = output.replace('ACCESS++;','https://localhost/ALLEPOV1/?activation&long='+requests.post(Settings.Gate,data=hash_req).text + '&title='+self.TITLE)
        return output
        

    def MIME_ACTIVATION(self):
        msg = MIMEMultipart()
        msg ['From'] = Settings.AdminMail
        msg ['To'] = self.CONTACT
        msg ['Subject'] = 'LINK AKTYWACJYNY'
        msg.attach(MIMEText(self.SEND_ACTIVATION(),'html'))
        return msg.as_string()
    
    def Inform(self):
        _server = smtplib.SMTP(Settings.SMTP_host, Settings.SMTP_port)
        _server.starttls()
        _server.login(Settings.AdminMail, Settings.AdminPw)
        _server.sendmail(Settings.AdminMail, self.CONTACT, self.MIME_ACTIVATION())
        #print(self.MIME())
        #Settings().Debug(1, Fore.RED + self.INFO)
        #Settings().Debug(1,Fore.CYAN + "INFORMACJA ZOSTALA PRZEKAZANA")

    def Die(self,line):
        #Settings().Debug(0, 'Killed on line ' + str(line))
        self.CHROME.quit()
        sys.exit()