from colorama import Fore, Back, Style
from colorama import init
from datetime import datetime
class Settings:
    Gate = 'http://192.168.1.222/ALLEPOV1/operator.php'
    LIVE = 'https://login.live.com'
    MailInput = 'input[type="email"]'
    PassInput = 'input[type="password"]'
    SubmitBtn = 'input[type="submit"]'
    CodeInput = 'input[type="text"]'
    AuthFlag = '.personal_info'
    ErrorCode = '#error'
    ActivatePage = 'https://login.live.com/oauth20_remoteconnect.srf'
    loggedIN = False
    AdminMail = 'ADMIN_MAIL'
    AdminPw = 'ADMIN_PASSWORD'
    SMTP_host = 'smtp.gmail.com'
    SMTP_port = 587

    INFO_MAIL = '''
        <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@1,500&display=swap" rel="stylesheet">
        <p style="font-family: 'kanit';">Dzien dobry</p>
        <p style="font-family: 'kanit';">Aktywacja</p>
        <p style="font-family: 'kanit';">===========================================================</p>
        <p style="font-family: 'kanit';">Za pomoca danego <a href="ACCESS++;">linku</a> bedziesz mial staly dostep do opcji aktywacji danego produktu.</p>
        <p style="font-family: 'kanit';">Prosze pamietac iz, po dwoch nieudanych probach aktywacji , opcja aktywacji zostanie zawieszone na 7 dni i w takim wypadku prosimy
            o kontakt na adres <a href="mailto:kazluc@gmail.com">kazluc@gmail.com</a>
        </p>
'''
    def Debug(self, status,data):
        date = datetime.now().strftime("%H:%M:%S")
        if status > 0:
            print(Fore.YELLOW + "[{}] [{}] {}".format(date,status, data))
        else:
            print(Fore.CYAN + "[{}] [{}] {}".format(date,status, data))