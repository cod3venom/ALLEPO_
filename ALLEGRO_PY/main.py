from Kernel.Browser import Browser
from Kernel.Spider import Spider
from Kernel.FileSystem import FileSystem
from Kernel.SubSystem import SubSystem
from Kernel.Logo import Logo
import sys,json, time, argparse
from colorama import Fore, Back, Style
from colorama import init

 
if __name__ == "__main__":
    Logo().Draw()
    try:
        input = sys.argv[1]
        if input == "install":
            SubSystem().Install()
            Browser().Install()
        if input == "restore":
            SubSystem().Clean()
        if input == "run":
            Spider()
        
    except IndexError:
        input= ''
        print("RUNING WITHOUT PARAMETERS")
        Spider()
    except KeyboardInterrupt:
        print("Terminated by USER")
     
     
 
