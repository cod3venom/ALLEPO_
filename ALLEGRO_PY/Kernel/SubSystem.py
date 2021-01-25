from Kernel.FileSystem import FileSystem
from Kernel.Settings import Settings
from Kernel.Settings import Store
import os , requests
FOLDERS_RM = ["Storage//",'Selenium//']
FOLDERS_MK = ["Storage//", 'Storage//Templates']

FILES = ['Storage//Access.ini','Storage//Cookies.json','Storage//Sold.ini', 'Storage//Store.ini' ,
        'Storage//SingleToken.ini', 'Storage//RefreshToken.ini','Storage//Templates//Email.html']

class SubSystem:

    def Clean(self):
        for folder in FOLDERS_RM:
            FileSystem().Delete(folder,"FOLDER")

    def Install(self):
        for folder in FOLDERS_MK:
            if os.path.isdir(folder) == False:
                os.umask(0)
                FileSystem().CreateDir(folder)
                Settings().info(1,"INSTALLED " + str(folder))
        self.store_setup()
        for file in FILES:
            if os.path.isfile(file) == False:
                os.umask(0)
                FileSystem().Write(file,"w","")
                Settings().info(1,"INSTALLED " + str(file))

    def store_setup(self):
        _name = input('Enter store name : ')
        _email = input('Enter store email or username : ')
        _password = input('Enter store password : ')
        _repeat = input('Repeat store password : ')
        _store = '{}_{}_{}'.format(_name,_email,_password)
        FileSystem().Write('Storage//Store.ini','w',_store)
        self.create_store(_name,_email,_password, _repeat)
        print('Setup finished successfully')

    
    def create_store(self, _name,_email,_password, _repeat):
        pdata = {'allegroMachine':'$2y$10$RU0skdxtIaLwzbS3YB90a.y/SCnp.ZEEyDsUuCXZean6R50Wg2PDq',
                    'addStore':'','name':_name,'email':_email,'pass':_password, 'repeat':_repeat}
        requests.post(Settings().OurgateOP(),data=pdata).text
        