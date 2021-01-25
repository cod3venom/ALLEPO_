from Kernel.Settings import Settings
import requests, json


def read(name):
    with open(name,"r" , encoding='utf-8') as reader:
        return reader.read()

def write(name,data):
    with open(name,"a",encoding="utf-8") as writer:
        writer.write(data + '\n')
def refreshToken():
    content = read("Storage//RefreshToken.ini").split("=")
    return str(content[1])


def showProducts():
    host = 'https://api.allegro.pl/offers/listing?seller.id=577090'
    naglowek = {'Accept':'application/vnd.allegro.public.v1+json', 'Authorization':'Bearer '+ refreshToken()}
    data = requests.get(host, headers=naglowek).text
    pack = json.loads(data)
    for i,items in pack.items():
        if 'regular' in items:
            for product in items['regular']:
                #image = product['images'][1]['url']
                name = product['name']
                #full = '{} $ {}'.format(image,name)
                print(name)
                #write('Storage//Products.ini',full)
if __name__ == "__main__":
    showProducts()
