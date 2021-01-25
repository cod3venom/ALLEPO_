import requests as sock
from Kernel.Settings import Settings


class Web:
    def __init__(self, email):
        self.EMAIL = email

    def Post(self,data):
        #print('sent')
        return sock.post(Settings.Gate,data=data).text
    
    def Update(self,status):
        if status > 0:
            self.Post(self.statusQuery('Aktywny'))
        else:
            self.Post(self.statusQuery('Nie-Aktywny'))
    
    def statusQuery(self, status):
        data = { 'microMachine':'$2y$10$RU0skdxtIaLwzbS3YB90a.y/SCnp.ZEEyDsUuCXZean6R50Wg2PDq',
                'product':'', 'update':status, 'email':self.EMAIL}
        #print("STATUS QUERY VALUE = > " + str(status))
        return data
    