import smtplib,requests as sock
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from email.mime.base import MIMEBase
from email import encoders
from Kernel.FileSystem import FileSystem
from Kernel.Settings import Settings
from colorama import Fore, Back, Style
from colorama import init
class Mail:
    def __init__(self,PRODUCT, EMAIL, USERNAME):
        self.PRODUCT = PRODUCT
        self.EMAIL = EMAIL
        self.USERNAME = USERNAME
        print(Fore.GREEN + "{} ({}) KUPIŁ {}".format(USERNAME,EMAIL,PRODUCT))
    def getHash(self):
        pdata = {'allegroMachine':'$2y$10$RU0skdxtIaLwzbS3YB90a.y/SCnp.ZEEyDsUuCXZean6R50Wg2PDq',
                    'getHash':self.PRODUCT}
        resp = sock.post(Settings().OurgateOP(), data=pdata).text
        print('RESPONSE IS' +resp)
        return resp
    def GetBody(self):

        content = FileSystem().Read(Settings().BodyHTML())
        content = content.replace("PRODUCT_NAME++;",self.PRODUCT)
        content = content.replace("ACCESS++;", Settings().OurIndex()+'activation&long='+self.getHash()+'&title='+self.PRODUCT)
        return content
    def MIME(self):
        msg = MIMEMultipart()
        msg ['From'] = Settings().MailFrom()
        msg ['To'] = self.EMAIL
        msg ['Subject'] = self.PRODUCT
        msg.attach(MIMEText(self.GetBody(),'html'))
        return msg.as_string()
    
    def Send(self):
        _server = smtplib.SMTP(Settings().SMTP_GMAIL(), Settings().SMTP_PORT())
        _server.starttls()
        _server.login(Settings().MailFrom(), Settings().MailPWD())
        _server.sendmail(Settings().MailFrom(), self.EMAIL, self.MIME())
        #print(self.MIME())
        print(Fore.CYAN + "["+self.PRODUCT+"]WIADOMOSC ZOSTAŁ WYSŁANY NA ADRES " + self.EMAIL)


