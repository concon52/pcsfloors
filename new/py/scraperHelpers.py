from bs4 import BeautifulSoup
from urllib.parse import urljoin
import requests
import json



class baseDomainSpecificScraperClass:
	def __init__(self, url = "http://www.capriathome.com/cork_glue_down_tiles_and_planks.htm"):
		self.url = url
		self.html = requests.get(self.url).text
		self.soup = BeautifulSoup(self.html)


	def getProductInfo(self):
		#get all the things
		return json.dumps({
		'title' : self.getTitle(),
		'description' : self.getDescription(),
		'productPictureURLs' : self.getProductPictureURLs(),
		'colors' : self.getColors(),
		'manufacturer' : self.getManufacturer(),
		'flooringType' : self.getFlooringType(),
		'additionalInfo' : self.getAdditionalData()
		}, sort_keys = True, indent = 4)


	def printProductInfo(self):
		print(self.getProductInfo())


	# Convert the scraped url to absolute form
	def urlToAbsolute(self, urlToConvert):
		return urljoin(self.url, urlToConvert)


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
##################################### Capri At Home ########################################
############################################################################################

class capriAtHome(baseDomainSpecificScraperClass):
	def __init__(self, url = "http://www.capriathome.com/cork_glue_down_tiles_and_planks.htm"):
		super().__init__(url)


	def getTitle(self):
		return self.soup.find(id="title").get_text()


	def getDescription(self):
		info = self.soup.find(class_ = "information") # locate the main description section
		[s.extract() for s in info(['a', 'span'])] # remove span and anchor elements
		info = info.get_text() 
		info = info.strip()
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
				'name' : thumb.get_text(),
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
		data = []

		for pdf in pdfs:
			data.append({
				'url' : self.urlToAbsolute(pdf.get('href')),
				'text' : pdf.get_text()
			})

		return data










