from bs4 import BeautifulSoup as bs
from urllib.parse import urljoin
from unidecode import unidecode
import requests
import json




def scrapeEverything(insertProducts = False, printProducts = True):
	# scrape all Capri @ Home products
	# url: http://www.capriathome.com/products.htm
	ca = capriAtHome()
	ca.scrapeProducts(insertProducts = insertProducts, printProducts = printProducts)

	# scrape all tiles from 'Tile Gallery' on Fritztile
	# url: http://www.fritztile.com/fritz-products/tile-gallery/classic-terrazzo-collection/c520515/
	f = fritzTile()
	f.scrapeProducts(insertProducts = insertProducts, printProducts = printProducts)

	# scrape all custom tiles from 'Tile Gallery' on fritztile
	# url: http://www.fritztile.com/fritz-products/custom-tile-gallery/
	f = fritzTile()
	f.scrapeProducts(insertProducts = insertProducts, printProducts = printProducts)





class baseDomainSpecificScraperClass:
	def __init__(self, urls = ["http://www.capriathome.com/products.htm"]):
		# determine if we were provided a single string or an array of strings
		if isinstance(urls, str):
			self.productURLs = []
			self.url = urls
		elif isinstance(urls, list) and len(urls) > 0 and isinstance(urls[0], str):
			self.productURLs = urls
			self.url = self.productURLs.pop()
		else:
			raise Exception("Error, provided incorrect type for `urls` variable")

		self.r = requests.get(self.url)
		self.url = self.r.url
		self.soup = bs(self.r.text)


	def changeURL(self, url):
		self.url = url
		self.html = requests.get(self.url).text
		self.soup = bs(self.html)


	def insertProductIntoDB(self):
		requests.post("http://s497483439.onlinehome.us/queryTemplate.php", self.getProductInfo(jsonEncode = True))


	def getProductInfo(self, jsonEncode = False, indent = None):
		#get all the things
		r = {
			'name' : formatStr(self.getTitle()),
			'description' : formatStr(self.getDescription()),
			'picture' : self.getProductPictureURLs(),
			'colors' : self.getColors(),
			'manufacturer' : formatStr(self.getManufacturer()),
			'type' : formatStr(self.getFlooringType()),
			'additionalInfo' : self.getAdditionalData(),
			'manurl' : self.getManURL()
		}

		if jsonEncode:
			return json.dumps(r, sort_keys = True, indent = indent)

		return r


	# scrapes all products in self.productURLs
	# if a newURLs array is provided, it will also scrape those URLs
	def scrapeProducts(self, newURLs = [], searchForMoreProducts = True, insertProducts = False, printProducts = True):
		# make sure newURLs is either empty or an array of strings, otherwise set it to an empty array so it doesn't cause errors
		if not ( isinstance(newURLs, list) and ( len(newURLs) == 0 or isinstance(newURLs[0], str) ) ):
			newURLs = []


		# should we search the current URL for more product pages?
		if searchForMoreProducts:
			self.productURLs += self.getProductURLs()

		# append the newURLs to self.productURLs and only keep unique URLs
		self.productURLs = list(set(newURLs + self.productURLs)) 

		# scrape all the products
		for productURL in self.productURLs:
			self.changeURL(productURL)

			if insertProducts:
				self.insertProductIntoDB()

			if printProducts:
				self.printProductInfo()
				print("\n\n\n")


	# manufacturer URL
	def getManURL(self):
		return self.url.split('/')[2]


	def printProductInfo(self):
		print(self.getProductInfo(jsonEncode = True, indent = 4))


	# Convert the scraped url to absolute form
	def urlToAbsolute(self, urlToConvert):
		return urljoin(self.url, urlToConvert)


	def formatStr(self, s):
		return unidecode(s).strip()



	def getProductURLs(self):
		print("getProdutURLs() stub")
		return []

	def getTitle(self):
		print("getTitle() stub")
		return ""

	def getDescription(self):
		print("getDescriontion() stub")
		return ""

	def getProductPictureURLs(self):
		print("getProductPicureURLs() stub")
		return []

	def getColors(self):
		print("getColors() stub")
		return []

	def getManufacturer(self):
		print("getManufacturer() stub")
		return ""


	def getFlooringType(self):
		print("getFlooringType() stub")
		return ""

	def getAdditionalData(self):
		print("getAdditionalData() stub")
		return []




############################################################################################
##################################### van Gelder ###########################################
############################################################################################

class vanGelder(baseDomainSpecificScraperClass):
	def __init__(self, urls = ["http://www.vangelder-inc.com/products/logo-mats/ovation-36/"]):
		super().__init__(urls)

	def getProductURLs(self):
		productURLs = []
		anchors = self.soup.find(id = 'tileBar').findAll('a')
		
		for anchor in anchors:
			url = anchor.get("href")
			url = self.urlToAbsolute(url)
			productURLs.append(url)

		return productURLs


	def getTitle(self):
		t = self.soup.find(class_ = 'p-title').p.text
		return self.formatStr(t)

	def getDescription(self):
		# create local soup since we are going to remove a portion of it
		soup = self.soup

		# remove all text that can cause issues
		for s in soup.findAll(id = 'column-clear'):
			s.clear()

		d = soup.find(class_ = 'p-body').text
		return self.formatStr(d)


	def getProductPictureURLs(self):
		productURLs = []
		imgs = self.soup.find(class_ = 'p-header2').findAll('img')

		for img in imgs:
			url = img.get('src')
			url = self.urlToAbsolute(url)
			productURLs.append(url)

		return productURLs


	def getColors(self):
		imageAreas = self.soup.findAll(class_ = 'imageareaContent')
		colors = []

		for imageArea in imageAreas:
			for i, caption in enumerate(imageArea.findAll(class_ = 'caption_container')):
				# skip the first image if there are multiple imageAreas because it is used to show the colors of that group
				if len(imageAreas) is 1 and i is 0: 
					continue

				colors.append({
					'url' : self.urlToAbsolute(caption.img.get('src')),
					'name' : caption.text,
					'css' : ''	
				})

		return colors


	def getManufacturer(self):
		return "Fritztile"


	def getFlooringType(self):
		return "tile"

	def getAdditionalData(self):
		pdfs = self.soup.find(class_ = 'orange').findAll('a')
		data = {'pdfs' : []}

		for pdf in pdfs:
			data['pdfs'].append({
				'url' : self.urlToAbsolute(pdf.get('href')),
				'text' : pdf.text
			})

		return data




############################################################################################
##################################### Fritztile ############################################
############################################################################################

class fritzTile(baseDomainSpecificScraperClass):
	def __init__(self, urls = ["http://www.fritztile.com/fritz-products/tile-gallery/classic-terrazzo-collection/c520515/"]):
		super().__init__(urls)

	def getProductURLs(self):
		productURLs = []
		anchors = self.soup.find(id = 'tileBar').findAll('a')
		
		for anchor in anchors:
			url = anchor.get("href")
			url = self.urlToAbsolute(url)
			productURLs.append(url)

		return productURLs


	def getTitle(self):
		return self.soup.find(id = "tileBar").find(class_ = "selected").text

	def getDescription(self):
		return self.soup.find(id = "contentarea").findAll('div')[1].div.text

	def getProductPictureURLs(self):
		return []

	def getColors(self):
		thumbs = self.soup.findAll(class_ = 'slide')
		colors = []

		for thumb in thumbs:
			colors.append({
				'url' : self.urlToAbsolute(thumb.img.get('src')),
				'name' : thumb.text.strip().replace('\n', ' '),
				'css' : ''
			})

		return colors

	def getManufacturer(self):
		return "Fritztile"


	def getFlooringType(self):
		return "tile"

	def getAdditionalData(self):
		pdfs = self.soup.find(class_ = 'orange').findAll('a')
		data = {'pdfs' : []}

		for pdf in pdfs:
			data['pdfs'].append({
				'url' : self.urlToAbsolute(pdf.get('href')),
				'text' : pdf.text
			})

		return data




############################################################################################
################################ Fritztile Custom Tile #####################################
############################################################################################

class fritzTileCustomTile(fritzTile):
	def __init__(self, urls = ["http://www.fritztile.com/fritz-products/custom-tile-gallery/"]):
		super().__init__(urls)


	# this is a gutted version of other scrapeProducts() because Fritztile's Custom Tiles are basically one product with a lot of colors
	def scrapeProducts(self, newURLs = [], searchForMoreProducts = False, insertProducts = True, printProducts = False):
		if insertProducts:
			self.insertProductIntoDB()

		if printProducts:
			self.printProductInfo()
			print("\n\n\n")


	def getProductURLs(self):
		productURLs = []
		ids = self.soup.find(id = 'tileBar').findAll('a')
		
		for anchor in anchors:
			url = anchor.get("href")
			url = self.urlToAbsolute(url)
			productURLs.append(url)

		return productURLs


	def getTitle(self):
		return "Custom Tile"

	def getDescription(self):
		return list(self.soup.find(id = "contentarea").findAll('div')[1].div.stripped_strings)[1]


	def getColors(self):
		def ajaxColors(ajaxID):
			r = requests.get("http://www.fritztile.com/custom-tile-list/?id=" + ajaxID)
			c = []

			for soup in bs(r.text).findAll('div'):
				c.append({
					'url' : self.urlToAbsolute(soup.a.get('href')),
					'name' : soup.a.get('title'),
					'css' : ''
				})

			print('ajaxID: ' + ajaxID, c, 'n: ' + str(len(c)), '\n\n\n')

			return c

		colors = []
		thumbs = self.soup.find(id = 'hue-bar').findAll('li')

		for thumb in thumbs:
			ajaxID = thumb.a.get('onclick').split("'")[1]

			colors += ajaxColors(ajaxID)	

		return colors



	def getAdditionalData(self):
		return []





############################################################################################
##################################### Capri At Home ########################################
############################################################################################

class capriAtHome(baseDomainSpecificScraperClass):
	def __init__(self, urls = ["http://www.capriathome.com/products.htm"]):
		super().__init__(urls)


	def getProductURLs(self):
		productURLs = []
		mainThumbs = self.soup.findAll(id = "mainthumbs")
		
		for thumb in mainThumbs:
			url = thumb.a.get("href")
			url = self.urlToAbsolute(url)
			productURLs.append(url)

		return productURLs


	def getTitle(self):
		return self.soup.find(id="title").text


	def getDescription(self):
		info = self.soup.find(class_ = "information") # locate the main description section
		[s.extract() for s in info(['a', 'span'])] # remove span and anchor elements
		info = info.text 
		info = info.strip() # strip white space at start and end of string
		info = info.replace('\n', '</br>') 
		return info


	def getProductPictureURLs(self):
		slides = self.soup(class_ = "jqb_slide")
		urls = []

		for slide in slides:
			urls.append(self.urlToAbsolute(slide.img.get('src')))

		return urls


	def getColors(self):
		thumbs = self.soup.findAll(id = "thumbs")
		colors = []

		for thumb in thumbs:
			colors.append({
				'url' : self.urlToAbsolute(thumb.a.get('href')),
				'name' : thumb.text,
				'css' : ''
			})

		return colors


	def getManufacturer(self):
		return "Capri @ Home"


	def getFlooringType(self):
		title = self.getTitle().lower()

		if 'cork' in title:
			return 'cork'
		elif 'rubber' in title:
			return 'rubber'
		elif 'linoleum' in title:
			return 'linoleum'
		else:
			return ''


	def getAdditionalData(self):
		pdfs = self.soup.findAll(class_ = 'pdf')
		data = {'pdfs' : []}

		for pdf in pdfs:
			data['pdfs'].append({
				'url' : self.urlToAbsolute(pdf.get('href')),
				'text' : pdf.text
			})

		return data










