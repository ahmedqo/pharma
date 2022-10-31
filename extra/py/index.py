from googletrans import Translator
import json
import time
import re

file = open('data.json')
T = Translator()

data = json.load(file)


def t(txt):
    # return txt
    return T.translate(txt, dest="ar").text


def w(dict, id):
    _file = open('translate.json')
    data = json.load(_file)
    data["pro-"+str(id)] = dict
    json_object = json.dumps(data)
    with open("translate.json", "w") as outfile:
        outfile.write(json_object)


def c(txt):
    txt = re.sub('\r', ' ', txt)
    txt = re.sub('\n', ' ', txt)
    txt = re.sub('\r\n', ' ', txt)
    txt = re.sub(' +', ' ', txt)
    return txt


def b(lst, n):
    return [lst[i:i + n] for i in range(0, len(lst), n)]


nd = b(data, 10)

for a in nd:
    for x in a:
        w({
            "title": c(t(x['title'])),
            "desc": c(t(x['desc'])),
            "description": c(t(x['description']))
        }, x['id'])
        print("product: " + str(x['id']))
    time.sleep(100)


file.close()
