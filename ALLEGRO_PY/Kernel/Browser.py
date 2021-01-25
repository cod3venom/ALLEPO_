from selenium import webdriver
from selenium.common.exceptions import TimeoutException
from selenium.common.exceptions import NoSuchElementException

from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.options import Options

from Kernel.Settings import Settings
from Kernel.FileSystem import FileSystem
import requests as sock
import time

class Browser:
    def config_browser(self):
        option = Options()
        #option.headless = True
        option.add_argument('--disable-dev-shm-usage')
        option.add_argument('--disable-extensions')
        option.add_argument('--disable-gpu')
        option.add_argument('--log-level=3')
        option.add_argument('--user-data-dir=Selenium')
        #option.add_argument('--no-sandbox')
        return option

    def Install(self):
        chrome = webdriver.Chrome(executable_path=r"/usr/lib/chromium-browser/chromedriver", chrome_options=self.config_browser())
        chrome.get(Settings().BROWSER_LOGIN_URL())
        try:
            WebDriverWait(chrome, 10).until(EC.presence_of_element_located((By.TAG_NAME, "body")))
        except Exception as ex:
            print(str(ex))
        finally:
            Web(chrome).Login()
            chrome.quit()
    
    def FirstConfirmation(self, address):
        chrome = webdriver.Chrome(executable_path=r"/usr/lib/chromium-browser/chromedriver", chrome_options=self.config_browser())
        chrome.get(address)
        Web(chrome).Click(Settings().BROWSER_CONFIRM_BUTTON())
        time.sleep(5)
        chrome.quit()
class Web:
    def __init__(self,chrome):
        self.chrome = chrome
    def Login(self):
        self.Click(Settings().TERMS_KILL_SWITCH())
        self.Input(Settings().BROWSER_EMAIL_INPUT(),Settings().BROWSER_EMAIL())
        self.Input(Settings().BROWSER_PASS_INPUT(),Settings().BROWSER_PASS())
        self.Click(Settings().BROWSER_LOGIN_BUTTON())            
        self.alive()
    def Exists(self,element):
        try:
            self.chrome.find_element_by_css_selector(element)
            return True
        except NoSuchElementException:
            return False
    def Input(self,target, data):
        if self.Exists(target):
            self.chrome.find_element_by_css_selector(target).send_keys(data)
        else:
            print(target + " NOT FOUND")
        

    def Click(self, target):
        if self.Exists(target):
            self.chrome.find_element_by_css_selector(target).send_keys(Keys.RETURN)
        else:
            print(target + " NOT FOUND")
    def readTXT(self, target):
        if self.Exists(target):
            return self.chrome.find_element(By.XPATH, target)

    def alive(self):
        time.sleep(2)
        self.WriteSession()
    def WriteSession(self):
        COOKIES = ''
        for ck in self.chrome.get_cookies():
            COOKIES += '{NAME}={VALUE};\n'.format( NAME = ck['name'],VALUE = ck['value'])
        FileSystem().Write(Settings().CookiesJS(),"w", COOKIES)
        Settings().info(1,"COOKIES HAS BEEN INSTALLED")
        FileSystem().Write(Settings().AccesINI(),"w","LOGGED")
        Settings().info(1,"ACCESS HAS BEEN INSTALLED")
        time.sleep(5)
        self.chrome.close()
        self.chrome.quit()
        self.testck()
    def testck(self):
        ck = FileSystem().cookieToDict()
        send = sock.get(Settings().BROWSER_LOGIN_URL(), cookies = ck).text
        with open("test.html","w", encoding="utf-8") as writer:
            writer.write(send)
            writer.close()
        