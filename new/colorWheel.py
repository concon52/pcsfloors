from bs4 import BeautifulSoup
from urllib import request

url = "http://www.allstaterubber.com/products/traditional-tile/traditional-tile-colors/"
response = request.urlopen(url)
raw = response.read()

soup = BeautifulSoup(raw)
gs = soup.find_all('g')
gs = gs[2:-4]

f = open('colors.txt', 'w')

for el in gs:
	f.write(el['id'] + ',' + el['fill'] + '\n')

f.close()
