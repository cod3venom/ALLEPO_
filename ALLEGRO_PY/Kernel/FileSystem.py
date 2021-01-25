import os,sys, shutil
from Kernel.Settings import Settings
class FileSystem:
    def Write(self, name,mode,data):
        with open(str(name),str(mode), encoding='utf-8') as writer:
            writer.write(str(data))
            writer.close()
    
    def AppendUniq(self, name,data):
        if str(data) not in self.Read(str(name)):
            self.Write(str(name),"a",str(data)+'\r\n')
            
    def Read(self,name):
        if os.path.isfile(name):
            with open(str(name),"r", encoding="utf-8") as reader:
                return reader.read()
    def Delete(self,path,typE):
        if typE == "FILE":
            if os.path.isfile(path):
                os.remove(path)
                Settings().info(1,"DELETED " + str(path))
            else:
                print("NOT FOUND")
        elif typE == "FOLDER":
            if os.path.isdir(path):
                os.umask(0)
                shutil.rmtree(path)
                Settings().info(1,"DELETED " + str(path))
            else:
                print("NOT FOUND")
    def CreateDir(self,name):
        if os.path.isdir(name) == False:
            os.umask(0)
            os.mkdir(name)
            Settings().info(1,"INSTALLED " + str(name))
    def getLocalCookies(self):
        return self.Read(Settings().CookiesJS())
    
    def cookieToDict(self):
        ck = {}
        content = self.getLocalCookies()
        if content is not None:
            on_line = content.split("\n")
            for line in on_line:
                if "=" in line:
                    split = line.split("=")
                    ck[split[0]] = split[1]
            return ck
    def isLogged(self):
        FLAG = self.Read(Settings().AccesINI())
        if FLAG is not None:
            if "LOGGED" in FLAG:
                return True
            elif "NOT" in FLAG:
                return False
        else:
            self.Write(Settings().AccesINI(),"w","NOT")