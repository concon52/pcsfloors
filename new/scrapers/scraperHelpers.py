from bs4 import BeautifulSoup as bs
from time import sleep as sleep
from urllib.parse import urljoin
from unidecode import unidecode
import requests
import json
import pprint
import re
import copy


############# TO DO #############
#


headers = {'user-agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36'}

def scrapeEverything(insertProducts = False, insertProductsWithErrors = False, printProducts = True, printProductErrors = True):
	# Insert all the manually scraped manufacturers
	if insertProducts:
		smp = scrapeManualProducts()
		smp.scrapeEm(showProgress = printProducts)

	# scrape all Capri @ Home products
	# url: http://www.capriathome.com/products.htm
	ca = capriAtHome()
	ca.scrapeProducts(scrapeCurrentURL = False, insertProducts = insertProducts, insertProductsWithErrors = insertProductsWithErrors, printProducts = printProducts, printProductErrors = printProductErrors)

	# scrape all tiles from 'Tile Gallery' on Fritztile
	# url: http://www.fritztile.com/fritz-products/tile-gallery/classic-terrazzo-collection/c520515/
	f = fritzTile()
	f.scrapeProducts(insertProducts = insertProducts, printProducts = printProducts)

	# scrape all custom tiles from 'Tile Gallery' on fritztile
	# url: http://www.fritztile.com/fritz-products/custom-tile-gallery/
	fc = fritzTileCustomTile()
	fc.scrapeProducts(insertProducts = insertProducts, printProducts = printProducts)

	# scrape all van Gelder products
	v = vanGelder()
	v.scrapeProducts(insertProducts = insertProducts, insertProductsWithErrors = insertProductsWithErrors, printProducts = printProducts, printProductErrors = printProductErrors)

	# scrape DuroDesign Cork Flooring
	d = duroDesignCork()
	d.scrapeProducts(insertProducts = insertProducts, insertProductsWithErrors = insertProductsWithErrors, printProducts = printProducts, printProductErrors = printProductErrors)






class baseDomainSpecificScraperClass:
	def __init__(self, urls = ["http://www.capriathome.com/products.htm"]):
		# print('bdssc start urls: ' + urls[0])
		# determine if we were provided a single string or an array of strings
		if isinstance(urls, str):
			self.productURLs = []
			self.url = urls
		elif isinstance(urls, list) and len(urls) > 0 and isinstance(urls[0], str):
			self.productURLs = urls
			self.url = self.productURLs.pop()
		else:
			raise Exception("Error, provided incorrect type for `urls` variable: " + str(type(urls)))

		self.r = requests.get(self.url, headers=headers)
		self.url = self.r.url
		self.soup = bs(self.r.text, 'html5lib')
		self.productInfo = {}


	def changeURL(self, url = None):
		print('[end] scraping: ' + self.url)

		# if a url was provided as a parameter, use that url
		if url:
			self.url = url

		# else if there is a url in self.productURLs, use that url
		elif self.productURLs and isinstance(self.productURLs, list) and isinstance(self.productURLs[0], str):
			self.url = self.productURLs.pop()

		# else, raise an exception
		else:
			raise Exception("Error, no url provided to changeURL() and no url available from self.productURLs")


		self.html = requests.get(self.url, headers=headers).text
		self.soup = bs(self.html, 'html5lib')
		self.productInfo = {}
		self.productURLs = [x for x in self.productURLs if x != self.url]


	def insertProductIntoDB(self):
		print('started posting query...')
		requests.post("http://s497483439.onlinehome.us/queryTemplate.php", self.getProductInfo(jsonEncode = True))
		print('... query post finished')

	def getProductInfo(self, jsonEncode = False, indent = None, force = False):
		# if getProdcutInfo() has already been called for this product and (force == False), just use the info we already have for it
		if self.productInfo and not force:
			r = self.productInfo

		# else, get all the things
		else:
			r = {
				'name' : self.formatStr(self.getTitle()),
				'description' : self.formatStr(self.getDescription()),
				'picture' : self.getProductPictureURLs(),
				'colors' : self.getColors(),
				'manufacturer' : self.formatStr(self.getManufacturer()),
				'type' : self.formatStr(self.getFlooringType()),
				'additionalInfo' : self.getAdditionalData(),
				'manurl' : self.getManURL()	
			}

		r = self.overrideByURL(r)

		self.productInfo = r

		# do we want the information json encoded and possibly readable indents?
		if jsonEncode:
			return json.dumps(r, sort_keys = True, indent = indent)

		return r
		

	def scrapeProducts(self, newURLs = [], scrapeCurrentURL = True, searchForMoreProducts = True, printProducts = True, printProductErrors = True, insertProducts = False, insertProductsWithErrors = False):
		""" 
			scrape all products found in self.productURLs, will also scrape urls found in newURLs if provided

			:param newURLs: List or urls to scrape in addition to those found in self.productURLs.  Default value: []
			:param searchForMoreProducts: Bool for whether or not to use self.getProductURLs to scrape more products, in addition to self.productURLs and newURLs.  Note: if this is true, getProductURLs should be extended/overwritten in the child class.  Default value: True
			:param printProducts: Bool for whether or not to print product information to the command line while scraping.  Default value: True
			:param printProductErrors: Bool for whether or not to print all error information for products scraped.  Only prints errors if (printProducts and printProductErrors).  Default value: True
			:param insertProducts: Bool for whether or not to insert scraped products into the database.  Default Value: False
			:param insertProductsWithErrors: Bool for whether or not to insert products  __that_had_errors_while_scraping__ into the database.  Default value: False 
		"""

		# array to store and display errors.  Only used if (printProductErrors == True)
		errors = {} 
		skipped = []

		# make sure newURLs is either empty or an array of strings, otherwise set it to an empty array so it doesn't cause errors
		if not ( isinstance(newURLs, list) and ( len(newURLs) == 0 or isinstance(newURLs[0], str) ) ):
			newURLs = []


		# should we search the current URL for more product pages?
		if searchForMoreProducts:
			self.productURLs += self.getProductURLs()

		if scrapeCurrentURL:
			self.productURLs += [self.url]

		# append the newURLs to self.productURLs and only keep unique URLs
		self.productURLs = list(set(newURLs + self.productURLs)) 

		# scrape all the products
		for productURL in self.productURLs:
			# change the 
			self.changeURL(productURL)

			# self.allProductInfoProvided() should return either True if all info was found, or a dictionary with error information
			e = self.allProductInfoProvided()

			# insert the product into the database if we're supposed to
			if insertProducts:
				if insertProductsWithErrors or ( e and isinstance(e, bool) ):
					self.insertProductIntoDB()
				else:
					skipped.append(self.url)
			else:
				skipped.append(self.url)

			# print the products to the terminal if we're supposed to
			if printProducts:
				self.printProductInfo()

			# if we're supposed to print the product errors, save all errors to `errors` dictionary based on product URL.  This dictionary is printed out at end of function
			if printProductErrors and isinstance(e, dict):
				url = e.pop('url', None)

				errors[url] = e

			print("\n\n\n")


		if printProductErrors:
			print('****************\n\nERRORS:\n')
			pprint.pprint(errors)


		if insertProducts and skipped:
			print('****************\n\nSkipped:\n')
			pprint.pprint(skipped)



	# checks to make sure that there aren't any missing parts of the product after scraping the product page
	def allProductInfoProvided(self, url = None, checkName = True, checkDescription = True, checkMan = True, checkType = True, checkManurl = True, checkPicture = True, checkColors = True, checkAdditionalInfo = False):
		p = self.getProductInfo()
		errors = {}


		if checkName and len(p['name']) == 0:
			errors['name'] = 'Name/Title is empty'

		if checkDescription and len(p['description']) == 0:
			errors['description'] = 'Description is empty'

		if checkMan and len(p['manufacturer']) == 0:
			errors['manufacturer'] = 'Manufacturer is empty'

		if checkType and len(p['type']) == 0:
			errors['type'] = 'Type is empty'

		if checkManurl and len(p['manurl']) == 0:
			errors['manurl'] = 'Manurl (manufacturer URL) is empty'

		# do a simple check to see if picture is an empty array or None type, if it's populated, verify that it is in fact an array before seeing if the array contains anything other than None types
		if checkPicture and ( len(p['picture']) == 0 or ( isinstance(p['picture'], list) and len([x for x in p['picture'] if x]) == 0 ) ):
			errors['picture'] = 'Picture is empty array'

		if checkColors and ( len(p['colors']) == 0 or ( isinstance(p['colors'], list) and len([x for x in p['colors'] if x]) == 0 ) ):
			errors['colors'] = 'Colors is empty array'

		if checkAdditionalInfo and ( len(p['additionalInfo']) == 0 or ( isinstance(p['additionalInfo'], list) and len([x for x in p['additionalInfo'] if x]) == 0 ) ):
			errors['additionalInfo'] = 'Additional Info was not found'


		# if there are no errors, return True
		if not errors:
			return True
		# else, errors were found.  Include the product url in errors object for manual inspection/entry and return errors object
		else:
			if url is None:
				errors['url'] = 'Product URL: ' + self.url
			else:
				errors['url'] = 'Product URL: ' + url

			return errors




	# manufacturer URL
	def getManURL(self):
		return self.url.split('/')[2]


	def printProductInfo(self):
		print(self.getProductInfo(jsonEncode = True, indent = 4))


	# Convert the scraped url to absolute form
	def urlToAbsolute(self, urlToConvert, baseURL = None):
		if not baseURL:
			baseURL = self.url

		return urljoin(baseURL, urlToConvert)


	def formatStr(self, s):
		return unidecode(s).strip()


	def overrideByURL(self, p):
		print("overrideByURL() stub")
		return p


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
################################# Duro Design Hardwood #####################################
############################################################################################

class duroDesignHardwood(baseDomainSpecificScraperClass):
	def __init__(self, urls = None):
		if not urls:
			urls = [
				{
					'gallery' : "http://hardwood.duro-design.com/maple-photo-galleries/",
					'colors' : "http://hardwood.duro-design.com/maple/"
				},
				{
					'gallery' : 'http://hardwood.duro-design.com/white-oak-photo-galleries/',
					'colors' : 'http://hardwood.duro-design.com/white-oak/'
				},
				{
					'gallery' : 'http://hardwood.duro-design.com/red-oak-photo-galleries/'
				},
				{
					'gallery' : 'http://hardwood.duro-design.com/ash-photo-galleries/',
					'colors' : 'http://hardwood.duro-design.com/ash/'
				},
				{
					'gallery' : 'http://hardwood.duro-design.com/exotics-photo-galleries/'
				},
				{
					'colors' : 'http://hardwood.duro-design.com/cherry/'
				},
				{
					'gallery' : 'http://hardwood.duro-design.com/walnut-photo-galleries/',
					'colors' : 'http://hardwood.duro-design.com/walnut/'
				},
				{
					'gallery' : 'http://hardwood.duro-design.com/birch-photo-galleries/',
					'colors' : 'http://hardwood.duro-design.com/birch/'
				},
				{
					'gallery' : 'http://hardwood.duro-design.com/hickory-photo-galleries/',
					'colors' : 'http://hardwood.duro-design.com/hickory/'
				}
			]

		# get the first dictionary of gallery & color URLs
		self.productURLs = urls
		urlDict = self.productURLs.pop()

		# set the gallery's url, request, and soup
		if urlDict['gallery']:
			self.urlGallery = urlDict['gallery']
			self.rGallery = requests.get(self.urlGallery, headers=headers)

			# if we get a status code 429, wait 5 sec and retry
			while self.rGallery.status_code != 200:
				print ('self.rGallery status code: ' + str(self.rGallery.status_code) + ' | retrying in 20 sec')
				sleep(20)
				self.rGallery = requests.get(self.urlGallery, headers=headers)

			self.soupGallery = bs(self.rGallery.text)

		else:
			self.urlGallery = None
			self.rGallery = None
			self.soupGallery = None

		# set the colors' url, request, and soup
		if urlDict['colors']:
			self.urlColors = urlDict['colors']
			self.rColors = requests.get(self.urlColors, headers=headers)

			# if we get a status code 429, wait 5 sec and retry
			while self.rColors.status_code != 200:
				print('self.rColors status code: ' + str(self.rColors.status_code) + ' | retrying in 20 sec')
				sleep(20)
				self.rColors = requests.get(self.urlColors, headers=headers)

			self.soupColors = bs(self.rColors.text)
		
		else:
			self.urlColors = None
			self.rColors = None
			self.soupColors = None


		self.productInfo = {}


	def changeURL(self):
		# get the next dictionary of gallery & color URLs
		urlDict = self.productURLs.pop()


		# set the gallery's url, request, and soup
		if 'gallery' in urlDict:
			self.urlGallery = urlDict['gallery']
			self.rGallery = requests.get(self.urlGallery, headers=headers)

			# if we get a status code 429, wait 5 sec and retry
			while self.rGallery.status_code != 200:
				print ('self.rGallery status code: ' + str(self.rGallery.status_code) + ' | retrying in 20 sec')
				sleep(20)
				self.rGallery = requests.get(self.urlGallery, headers=headers)

			self.soupGallery = bs(self.rGallery.text)

		else:
			self.urlGallery = None
			self.rGallery = None
			self.soupGallery = None

		# set the colors' url, request, and soup
		if 'colors' in urlDict:
			self.urlColors = urlDict['colors']
			self.rColors = requests.get(self.urlColors, headers=headers)

			# if we get a status code 429, wait 5 sec and retry
			while self.rColors.status_code != 200:
				print('self.rColors status code: ' + str(self.rColors.status_code) + ' | retrying in 20 sec')
				sleep(20)
				self.rColors = requests.get(self.urlColors, headers=headers)

			self.soupColors = bs(self.rColors.text)
		
		else:
			self.urlColors = None
			self.rColors = None
			self.soupColors = None


		self.productInfo = {}



	def getTitle(self):
		if self.urlGallery:
			return self.soupGallery.find(class_ = 'page-title').text

		if self.urlColors:
			return self.soupColors.find(class_ = 'page-title').text


	def getProductPictureURLs(self):
		if self.urlGallery is None:
			return []

		return [x.get('href') for x in self.soupGallery.findAll('a', class_ = 'image-slide-anchor') if x.get('href')]


	def getColors(self):
		if self.urlColors is None:
			return []

		return [x.img.get('src') for x in self.soupColors.findAll(class_ = 'slide content-fill') if x]


	def getManufacturer(self):
		return "DuroDesign"


	def getFlooringType(self):
		return 'Hardwood'


	def getAdditionalData(self):
		data = {'pdfs' : [
			{
				'text' : 'Custom Hardwood',
				'url' : 'http://hardwood.duro-design.com/s/Durodesign-CustomHardwood-TechnicalSheet.pdf'
			},
			{
				'text' : 'Long and Wide Hardwood',
				'url' : 'http://hardwood.duro-design.com/s/Durodesign-LongWideHardwood-TechnicalSheet.pdf'
			},
			{
				'text' : 'Wide Plank Hardwood',
				'url' : 'http://hardwood.duro-design.com/s/Durodesign-WidePlankHardwood-TechnicalSheet.pdf'
			},
			{
				'text' : 'Standard Hardwood',
				'url' : 'http://hardwood.duro-design.com/s/Durodesign-StandardHardwood-TechnicalSheet.pdf'
			}
		]}

		return data


	def scrapeProducts(self, newURLs = [], searchForMoreProducts = True, printProducts = True, printProductErrors = True, insertProducts = False, insertProductsWithErrors = False):
		""" 
			scrape all products found in self.productURLs, will also scrape urls found in newURLs if provided

			:param newURLs: List or urls to scrape in addition to those found in self.productURLs.  Default value: []
			:param searchForMoreProducts: Bool for whether or not to use self.getProductURLs to scrape more products, in addition to self.productURLs and newURLs.  Note: if this is true, getProductURLs should be extended/overwritten in the child class.  Default value: True
			:param printProducts: Bool for whether or not to print product information to the command line while scraping.  Default value: True
			:param printProductErrors: Bool for whether or not to print all error information for products scraped.  Only prints errors if (printProducts and printProductErrors).  Default value: True
			:param insertProducts: Bool for whether or not to insert scraped products into the database.  Default Value: False
			:param insertProductsWithErrors: Bool for whether or not to insert products  __that_had_errors_while_scraping__ into the database.  Default value: False 
		"""

		# array to store and display errors.  Only used if (printProductErrors == True)
		errors = {} 
		skipped = []

		self.productURLs += [{'gallery' : self.urlGallery, 'colors' : self.urlColors}]

		# scrape all the products
		for urlDict in self.productURLs:
			# change the 
			self.changeURL()

			# self.allProductInfoProvided() should return either True if all info was found, or a dictionary with error information
			if self.urlGallery != None:
				url = self.urlGallery
			elif self.urlColors != None:
				url = self.urlColors
			else:
				url = ''

			e = self.allProductInfoProvided(url = url, checkDescription = False)

			# insert the product into the database if we're supposed to
			if insertProducts:
				if insertProductsWithErrors or ( e and isinstance(e, bool) ):
					self.insertProductIntoDB()
				else:
					skipped.append({'Gallery' : self.urlGallery, 'Colors' : self.urlColors})
			else:
				skipped.append({'Gallery' : self.urlGallery, 'Colors' : self.urlColors})

			# print the products to the terminal if we're supposed to
			if printProducts:
				self.printProductInfo()

			# if we're supposed to print the product errors, save all errors to `errors` dictionary based on product URL.  This dictionary is printed out at end of function
			if printProductErrors and isinstance(e, dict):
				url = e.pop('url', None) 

				errors[url] = e

			print("\n\n\n")


		if printProductErrors:
			print('****************\n\nERRORS:\n')
			pprint.pprint(errors)


		if insertProducts and skipped:
			print('****************\n\nSkipped:\n')
			pprint.pprint(skipped)



	def getManURL(self):
		return 'hardwood.duro-design.com'









############################################################################################
################################## Duro Design Cork ########################################
############################################################################################

class duroDesignCork(baseDomainSpecificScraperClass):
	def __init__(self, urls = None):
		if not urls:
			urls = ["http://www.duro-design.com/index.cfm/cork-tile-flooring/", "http://www.duro-design.com/index.cfm/floating-cork-flooring/"]

		super().__init__(urls)


	def getTitle(self):
		return self.soup.find('h1').text


	def getDescription(self):
		# get the last string in the body which is a question linking to their contact page so we can remove it
		last = [x for x in self.soup.find(class_ = 'twoColumn').stripped_strings if x][-1]

		# grab the description and strip white space
		d = self.soup.find(class_ = 'twoColumn').text

		# remove the last line in description and strip whitespace
		d = d.replace(last, '').strip()

		# replace any occurances of two or more new lines with just two new lines
		d = re.sub(r'\n\n+', '\n\n', d)

		return d


	def getProductPictureURLs(self):
		images = self.soup.find(class_ = 'twoColumn').findAll('img')

		return [self.urlToAbsolute(image.get('src')) for image in images][:2]


		# slides = self.soup(class_ = "jqb_slide")
		# urls = []

		# for slide in slides:
		# 	urls.append(self.urlToAbsolute(slide.img.get('src')))

		# return urls


	def getColors(self):
		def patternToAJAX(url):
			# remove any trailing '/'
			url = re.sub(re.compile('\/+$'), '', url)

			arr = url.split('/')

			(n, p) = arr[-2:]

			ajaxURL = 'http://www.duro-design.com/index.cfm/page/cork.colors_getGallery/colorSeriesId/' + n + '/pattern/' + p

			return ajaxURL

		def getColorsAJAX(url):
			r = requests.get(url, headers=headers)
			soup = bs(r.text)

			c = []

			for x in soup.findAll('color'):
				c.append({
					'url' : self.urlToAbsolute(x.get('path')),
					'name' : x.get('title'),
					'color' : x.get('color'),
					'pattern' : x.get('pattern')
				})

			return c


		colors = []

		url = 'http://www.duro-design.com/index.cfm/cork-flooring-colors/'
		r = requests.get(url, headers=headers)
		soup = bs(r.text)

		# grab the urls for the different color series
		colorSeriesAnchors = soup.find(id = 'content').find('table').findAll('a')[:-1]

		# for each color series, get all colors in each pattern type
		for anchor in colorSeriesAnchors:
			url = self.urlToAbsolute(anchor.get('href'), baseURL = r.url)
			r = requests.get(url, headers=headers)
			soup = bs(r.text)

			print('color series: ' + anchor.text + '  |  ' + url)

			# for each pattern, get all the colors from the AJAX calls
			for thumbnail in soup.find(class_ = 'box').findAll(class_ = 'thumbnail'):
				a = thumbnail.find('a')
				patternURL = a.get('href')

				ajaxURL = patternToAJAX(patternURL)

				colors += getColorsAJAX(ajaxURL)


				# print('    patternURL: ' + patternURL)	
				# print('    ajaxURL: ' + ajaxURL)


		# colors = [j for j in [i for i in ]]

		# remove duplicates from colors list
		colors = [x for i, x in enumerate(colors) if x not in colors[i+1:]]

		return colors


	def getManufacturer(self):
		return "DuroDesign"


	def getFlooringType(self):
		return 'Cork'


	def getAdditionalData(self):
		r = requests.get('http://www.duro-design.com/index.cfm/page/cork.documentation/', headers=headers)
		soup = bs(r.text)

		data = {'pdfs' : []}

		data['pdfs'] = [{'text' : x.text, 'url' : self.urlToAbsolute(x.get('href'))} for x in soup.findAll('a', href = re.compile('\.pdf$')) if x.text]

		return data


############################################################################################
##################################### van Gelder ###########################################
############################################################################################

class vanGelder(baseDomainSpecificScraperClass):
	def __init__(self, urls = None):
		if not urls:
			urls = ["http://www.vangelder-inc.com/products/logo-mats/ovation-36/"]

		super().__init__(urls)

	def getProductURLs(self):
		productURLs = set()
		pattern = re.compile('.*overview$')

		for x in self.soup.findAll('a', href = re.compile('\.com\/products\/')):
			if x:
				url = x.get('href').strip('/')
				
				if x.text.lower() != 'overview' and not pattern.match(url.lower()):
					productURLs.add(url)

		return list(productURLs)

	def allProductInfoProvided(self):
		return super().allProductInfoProvided(checkAdditionalInfo = True)


	def getTitle(self):
		t = self.soup.find(class_ = 'p-title')

		if t:
			t = t.text
		else:
			t = ''

		if not t:
			t = self.soup.find(id = re.compile('^p-bodyArea'))


		return self.formatStr(t)


	def getDescription(self):
		# create local soup since we are going to remove a portion of it
		soup = copy.copy(self.soup)

		# remove all text that can cause issues
		for s in soup.findAll(id = 'column-clear'):
			s.clear()

		# remove odf link anchor text
		[x.decompose() for x in soup.findAll(id = 'downloadicon')]

		d = soup.find(class_ = 'p-body')

		if d:
			d = d.text
		else:
			d = soup.find(id = 'prod_content')

			if d:
				d = d.text
			else:
				d = ''



		return self.formatStr(d)


	def getProductPictureURLs(self):
		pictureURLs = []
		imgs = self.soup.find(class_ = 'p-header2').findAll('img')

		# strange transparent image that we don't want to grab
		pattern = re.compile('holder\.gif$')

		for img in imgs:
			url = img.get('src')

			# if this image is the transparent .gif, skip it
			if bool(pattern.search(url)):
				continue

			url = self.urlToAbsolute(url)
			pictureURLs.append(url)

		return list(set(pictureURLs))


	def getColors(self):
		imageAreas = self.soup.findAll(class_ = 'imageareaContent')
		colors = []

		# most product pages colors can be found this way:
		for imageArea in imageAreas:
			for i, caption in enumerate(imageArea.findAll(class_ = 'caption_container')):
				# skip the first image if there are multiple imageAreas because it is used to show the colors of that group
				if i is 0 and len(imageAreas) > 1: 
					continue

				colors.append({
					'url' : self.urlToAbsolute(caption.img.get('src')),
					'name' : caption.text,
					'css' : ''	
				})

		# if we didn't find any colors the other way, remove all images we know aren't the color pictures then assume the remaining images are what we are looking for
		if len(colors) == 0:
			# temp soup variable 
			soup =  copy.copy(self.soup)


			removeSoups = [
				soup.find(id = 'cvglogo'), # remove the logo image
				soup.find(class_ = 'p-header2'), # remove the heater image
				soup.find(class_ = re.compile('^p-bodyarea')), # remove images in the body
				soup.find(id = 'footer'), # remove the footer image
				soup.find(class_ = re.compile('^p-spec')) # remove the images in the .p-spec*
			]

			for i, x in enumerate(removeSoups):
				if x:
					print('alternate color method, index: ' + str(i))
					x.decompose()

			# # remove logo image
			# soup.find(id = 'cvglogo').decompose()

			# # remove header image
			# soup.find(class_ = 'p-header2').decompose()

			# # remove p-bodyarea image
			# soup.find(class_ = re.compile('^p-bodyarea')).decompose()

			# # remove footer image
			# soup.find(id = 'footer').decompose()

			for image in soup.findAll('img'):
				colors.append({
					'url' : self.urlToAbsolute(image.get('src')),
					'name' : '',
					'css' : ''
				})



		return colors


	def getManufacturer(self):
		return "van Gelder"


	def getFlooringType(self):
		vgType = [x for x in self.soup.find(id = "prod_nav_box").stripped_strings][0].lower()

		if ('sheet' in vgType) or ('roll' in vgType):
			return 'Sheet and Roll Goods'

		elif 'carpet tile' in vgType:
			return 'Carpet Tile'

		elif 'vinyl' in vgType:
			return 'Vinyl'

		elif 'rubber' in vgType:
			return 'Rubber'

		elif 'grid' in vgType:
			return 'Grid Systems'

		elif 'industrial' in vgType or 'commercial' in vgType:
			return 'Industrial and Commercial Matting'

		elif 'slip' in vgType:
			return 'Non-Slip'

		elif 'custom' in vgType or 'logo' in vgType:
			return 'Custom and Logo Matting'

		else:
			print('!!!!!!!!! ERROR: ' + vgType + ' uncaught !!!!!!!!!!!!!!!!!!')
			return ''


	def getAdditionalData(self):
		data = {}

		# find all pdfs on the page
		pdfs = self.soup.findAll('a', href = re.compile("\.pdf$"))

		if len(pdfs) > 0:
			data = {'pdfs' : []}

			for pdf in pdfs:
				data['pdfs'].append({
					'url' : self.urlToAbsolute(pdf.get('href')),
					'text' : pdf.text
				})

		text = self.soup.findAll(class_ = 'p-specs')

		if len(text) > 0:
			data = {'text' : []}

			for t in text:
				data['text'].append(t.text)

		return data



	def overrideByURL(self, p):
		if 'logo-custom-matting' in self.url:
			p['description'] = 'vG is proud to offer a line of diverse logo and custom matting solutions for your indoor or outdoor commercial applications. Whether you are looking to increase your brand recognition or retail sales by making a new statement, we can help. Casual or elegant, large or small, high or low traffic, logo and plain mats are a perfect design solution for any commercial space. No matter what your budget, we have a matting solution for you!\nThe following pages are highlights from our top selling plain and logo mats. Should you not find what you are looking for let us know. There are plenty of other plain and logo matting products that are available to you, they are just not shown in this catalogue.'

		return p




############################################################################################
##################################### Fritztile ############################################
############################################################################################

class fritzTile(baseDomainSpecificScraperClass):
	def __init__(self, urls = None):
		if not urls:
			urls = ["http://www.fritztile.com/fritz-products/tile-gallery/classic-terrazzo-collection/c520515/"]

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

	# checks to make sure that there aren't any missing parts of the product after scraping the product page
	def allProductInfoProvided(self, checkPicture = False):
		return super().allProductInfoProvided(checkPicture = checkPicture)





############################################################################################
################################ Fritztile Custom Tile #####################################
############################################################################################

class fritzTileCustomTile(fritzTile):
	def __init__(self, urls = None):
		if not urls:
			urls = ["http://www.fritztile.com/fritz-products/custom-tile-gallery/"]

		super().__init__(urls)


	# this is a gutted version of other scrapeProducts() because Fritztile's Custom Tiles are basically one product with a lot of colors
	def scrapeProducts(self, newURLs = [], searchForMoreProducts = False, insertProducts = False, printProducts = True):
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
		# grab all colors for a specific id using the ajax request found on the page
		def ajaxColors(ajaxID):
			r = requests.get("http://www.fritztile.com/custom-tile-list/?id=" + ajaxID, headers=headers)
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
	def __init__(self, urls = None):
		if not urls:
			urls = ["http://www.capriathome.com/products.htm"]
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
		try:
			info = self.soup.find(class_ = "information") # locate the main description section
			[s.extract() for s in info(['a', 'span'])] # remove span and anchor elements
			info = info.text 
			info = info.strip() # strip white space at start and end of string
			info = info.replace('\n', '</br>') 
			return info
		except:
			print("error on url: " + str(self.url))
			raise Exception('RIP')

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
			return 'Cork'
		elif 'rubber' in title:
			return 'Rubber'
		elif 'linoleum' in title:
			return 'Linoleum'
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





class scrapeManualProducts():

	def insertProductIntoDB(self, productInfo):
		requests.post("http://s497483439.onlinehome.us/queryTemplate.php", json.dumps(productInfo, sort_keys = True))


	def scrapeEm(self, showProgress = True):
		if showProgress: print('Manually Scraped  [ start ]')

		# insert Future Foam Products
		if showProgress: print('    Future Foam  [ start ]')
		ff = self.futureFoam()
		for p in ff:
			self.insertProductIntoDB(p)
		if showProgress: print('    Future Foam  [ end ]')

		# insert Jade  Industries Products
		if showProgress: print('    Jade  [ start ]')
		j = self.jade()
		for p in j:
			self.insertProductIntoDB(p)
		if showProgress: print('    Jade  [ end ]')

		# insert Jade  Industries Products
		if showProgress: print('    Teebaud  [ start ]')
		j = self.teebaud()
		for p in j:
			self.insertProductIntoDB(p)
		if showProgress: print('    Teebaud  [ end ]')

		# insert Jade  Industries Products
		if showProgress: print('    American Fiber  [ start ]')
		j = self.americanFiber()
		for p in j:
			self.insertProductIntoDB(p)
		if showProgress: print('    American Fiber  [ end ]')



		if showProgress: print('Manually Scraped   [ end ]')



	def futureFoam(self):
		return [
			{
				"name": "Residential Carpet Cushion",
				"manufacturer": "Future Foam",
				"type": "Carpet Cushion",
				"picture": ["http://www.futurefoam.com/wordpress/wp-content/uploads/2015/09/hero-flooring-residential-1.jpg"],
				"colors": [],
				"manurl": "www.futurefoam.com",
				"description": """
					RESIDENTIAL CARPET CUSHION
					<br>
					Our premium carpet cushion provides insulation, sound dampening and superior comfort for your home. And with available Moisture Barrier technology, you can protect against pet odors and spills while preventing lingering smells and making clean-up easier.
					<br><br>
					STANDARD CARPET CUSHION
					<br>
					Designed with your lifestyle in mind, our range of high-quality carpet cushion provides a comfortable walk underfoot, dampens sound for a quieter home, acts as insulation and is resilient for years of use. 100% recyclable and certified as CRI Green Label Plus, you can be sure you're choosing the best material for your home and family.
					<br><br>
					MEMORY FOAM
					<br>
					As a leading producer of memory foam, we've taken our experience and created a line of carpet cushion that provides superior comfort and pressure relief all while maintaining its structure and enhancing the life of your new carpet. Manufactured from 90% recycled materials, LEED and CRI Green Label Plus certified and 100% recyclable after use, our memory foam carpet cushion, like all of our re-bonded cushion, is better for the environment and perfect for your home.
					<br><br>
					PRIME CARPET CUSHION
					<br>
					Our one-of-a-kind prime cushion offers superior comfort and luxurious feel underfoot without sacrificing the durability you need. It has a double-sided moisture barrier and is crafted with 100% recyclable material, LEED and CRI Green Label Certified and designed for maximum insulation to reduce energy costs and absorb sound."""
			},
			{
				"name": "Commercial Carpet Cushion",
				"manufacturer": "Future Foam",
				"type": "Carpet Cushion",
				"picture": ["http://www.futurefoam.com/wordpress/wp-content/uploads/2015/09/hero-flooring-commericial1.jpg"],
				"colors": [],
				"manurl": "www.futurefoam.com",
				"description": """COMMERCIAL CARPET CUSHION
					<br>
					Future Foam's commercial carpet cushion is developed for any application where extra sturdiness is required, like high-traffic areas, so you can protect the life of your carpet without sacrificing comfort. All our commercial products are LEED and CRI Green Label certified."""
			},
			{
				"name": "Underlayment",
				"manufacturer": "Future Foam",
				"type": "Underlayment",
				"picture": ["http://www.futurefoam.com/wordpress/wp-content/uploads/2015/09/hero-flooring-underlay.jpg"],
				"colors": [],
				"manurl": "www.futurefoam.com",
				"description": """
					UNDERLAYMENT
					<br>
					Our premium underlayments provides insulation, better balance and more cushioning to your new hard floors. It's designed to enhance energy efficiency, dampen sound throughout your space, help level subfloor imperfections and work well with radiant heat surfaces."""

			}
		]

	def jade(self):
		return [
			{
				"name": "Rubber Anchor/Rubber Anchor II",
				"manufacturer": "JADE Industries",
				"type": "Rug Pad",
				"picture": ["http://rugpads.com/cms/Portals/0/Jade/images/Rubber-Anchor.jpg"],
				"colors": [],
				"manurl": "www.rugpads.com",
				"description": """
					Rubber Anchor/Rubber Anchor II
					<br>
					Simply stated, Rubber Anchor and Rubber Anchor II are the very best pads for area rugs. Made with open cell natural rubber, Rubber Anchor's comfort, resilience, performance in protecting against rug wear and preventing slipping on both wood and tile floors is unsurpassed by any other rug pads. Rubber Anchor's smooth surface eliminates the possibility of texture showing through even the finest rugs. Rubber Anchor II was designed to enhance all the qualities of Rubber Anchor. Not only is it 40% lighter in weight, it is tougher, springier, grippier and longer lasting. The finest area rugs deserve the Rubber Anchor."""
			},
			{
				"name": "UltiMat/ OptiMat",
				"manufacturer": "JADE Industries",
				"type": "Rug Pad",
				"picture": ["http://rugpads.com/cms/Portals/0/Jade/images/Ultimat-Optimat.jpg"],
				"colors": [],
				"manurl": "www.rugpads.com",
				"description": """
					UltiMat/ OptiMat
					<br>
					Finally, an area rug pad that performs as well on wall-to-wall carpet as it does on hard surfaces. With twice the number of uses, UltiMat is literally the ultimate in pad products. It enables you to reduce your inventory, plus it is competitively priced. OptiMat performs similarly and is optimally priced below the competition. Place the reverse waffle "tentacle grip" side face down on hard floors and you have more gripping power than any other pad. Turn UltiMat or OptiMat over - rubber side up - and you have a pad that keeps area rugs securely in place over wall- to-wall carpeting. The surface is specially treated to provide extra grip and prevent fuzzing and fraying. UltiMat is made with 28 ounces of fiber and 20 ounces of washable rubber and 5 ounces of latex face coat per square yard. OptiMat has 22 ounces of felt and 16 ounces of non-washable rubber per square yard with a heat fused face Both UltiMat and OptiMat are available in 6' and 12' rolls as well as pre-packaged in standard and custom sizes."""
			},
			{
				"name": "ECONET",
				"manufacturer": "JADE Industries",
				"type": "Rug Pad",
				"picture": ["http://rugpads.com/cms/Portals/0/Jade/images/Econet-Image.jpg"],
				"colors": [],
				"manurl": "www.rugpads.com",
				"description": """
					ECONET
					<br>
					EcoNet is the world's most earth friendly rug pad. Made with natural rubber and jute. For use over laminated wood, wood, tile and other hard floors. Open grid design lets rug breathe, eases vacuuming, and protects your floors and rug. It will not stain, mar or discolor standard flooring. Through its partnership with Trees for the Future, Jade plants a tree for every EcoNet pad sold.
					<br><br>
					RubberNet
					<br>
					For those who appreciate the benefits of natural rubber and prefer an open weave design, RubberNet is the best line of open-weave natural latex area rug pads available. RubberNet protects your customers' investment against wear and prevents accidents by keeping rugs from slipping with natural rubber's superior grip strength.
					<br><br>
					RubberNet products are available in two roll widths and pre-packaged in standard and custom sizes."""
			},
			{
				"name": "SECURENET",
				"manufacturer": "JADE Industries",
				"type": "Rug Pad",
				"picture": ["http://rugpads.com/cms/Portals/0/Jade/images/Securenet-Image.jpg"],
				"colors": [],
				"manurl": "www.rugpads.com",
				"description": """
					SECURENET
					<br>
					SecureNet is made with soft pillows of rubber to provide a comfortable cushion that holds rugs tight to the floor.tight spots.
					<br><br>
					RubberNet
					<br>
					For those who appreciate the benefits of natural rubber and prefer an open weave design, RubberNet is the best line of open-weave natural latex area rug pads available. RubberNet protects your customers' investment against wear and prevents accidents by keeping rugs from slipping with natural rubber's superior grip strength.
					<br><br>
					RubberNet products are available in two roll widths and pre-packaged in standard and custom sizes."""
			},
			{
				"name": "MAGNET",
				"manufacturer": "JADE Industries",
				"type": "Rug Pad",
				"picture": ["http://rugpads.com/cms/Portals/0/Jade/images/Magnet-Image.jpg"],
				"colors": [],
				"manurl": "www.rugpads.com",
				"description": """
					MAGNET
					<br>
					MagNet is the thickest and toughest natural open weave available to protect your rugs and customers.
					<br><br>
					RubberNet
					<br>
					For those who appreciate the benefits of natural rubber and prefer an open weave design, RubberNet is the best line of open-weave natural latex area rug pads available. RubberNet protects your customers' investment against wear and prevents accidents by keeping rugs from slipping with natural rubber's superior grip strength.
					<br><br>
					RubberNet products are available in two roll widths and pre-packaged in standard and custom sizes."""

			},
			{
				"name": "PADLOCK",
				"manufacturer": "JADE Industries",
				"type": "Rug Pad",
				"picture": ["http://rugpads.com/cms/Portals/0/Jade/images/Padlock-Image.jpg"],
				"colors": [],
				"manurl": "www.rugpads.com",
				"description": """
					PADLOCK
					<br>
					PadLock is a slim pad that 'locks' area rugs to all hard surfaces. PadLock is so sturdy it can be washed in a washing machine yet trims easily with household scissors.
					<br><br>
					LockSet
					<br>
					LockSet provides a complete range of synthetic rubber non-slip area rug pad options for price sensitive customers. There is a Lockset product that is a precise match for every rug type, traffic condition and price point.
					<br><br>
					Lock-Set products are available in 6' and 12' wide full rolls and pre-packaged in standard sizes."""
			},
			{
				"name": "GRIDLOCK",
				"manufacturer": "JADE Industries",
				"type": "Rug Pad",
				"picture": ["http://rugpads.com/cms/Portals/0/Jade/images/Gridlock-Image.jpg"],
				"colors": [],
				"manurl": "www.rugpads.com",
				"description": """
					GRIDLOCK
					<br>
					GridLock is a medium thick area rug pad with superior cushioning ability and grip strength. Its open grid design allows rugs to 'breathe' and facilitates vacuuming by allowing air to penetrate to the floor.GridLock is a medium thick area rug pad with superior cushioning ability and grip strength. Its open grid design allows rugs to 'breathe' and facilitates vacuuming by allowing air to penetrate to the floor.
					<br><br>
					LockSet
					<br>
					LockSet provides a complete range of synthetic rubber non-slip area rug pad options for price sensitive customers. There is a Lockset product that is a precise match for every rug type, traffic condition and price point.
					<br><br>
					Lock-Set products are available in 6' and 12' wide full rolls and pre-packaged in standard sizes."""
			},
			{
				"name": "WEDLOCK",
				"manufacturer": "JADE Industries",
				"type": "Rug Pad",
				"picture": ["http://rugpads.com/cms/Portals/0/Jade/images/Wedlock-Image.jpg"],
				"colors": [],
				"manurl": "www.rugpads.com",
				"description": """
					WEDLOCK
					<br>
					WedLock invisibly 'weds' rugs to all natural and synthetic carpets without creating a raised look. Made of non-allergenic, bonded fibers, WedLock's adhesive will not transfer to carpet or rug. It trims easily with household scissors.
					<br><br>
					LockSet
					<br>
					LockSet provides a complete range of synthetic rubber non-slip area rug pad options for price sensitive customers. There is a Lockset product that is a precise match for every rug type, traffic condition and price point.
					<br><br>
					Lock-Set products are available in 6' and 12' wide full rolls and pre-packaged in standard sizes."""
			} 
		]

	def teebaud(self):
		return [
			{
				"name": "WEDLOCK",
				"manufacturer": "Teebaud",
				"type": "Underlayment",
				"picture": ["http://teebaud.com/wp-content/uploads/2013/06/banner-product.jpg"],
				"colors": [],
				"manurl": "www.teebaud.com",
				"description": """
					ONE PRODUCT – ANY FLOOR!
					<br>
					TEEBAUD® has been developed and engineered to obtain the best nonskid performance for a rug pad. The product is made of two different polyester fibers needle-punched together and coated, on both sides, with a specially formulated water-based pressure sensitive dry adhesive which will outperform pads coated with a plain polymeric-acrylic resin. TEEBAUD®'s two components, containing 12 ounces of fiber per square yard, gives to the pad enough body and density to resist the horizontal movement that creates the buckling effect of the underlay under the rug.
					<br><br>
					This is why TEEBAUD® has almost one pound of fiber per yard, compared to most competing products which commonly have only 3 to 4 ounces of fiber. The light cushioning effect allows the pad and the rug to breathe together with no risk of oxidation or discoloration to any flooring surface. TEEBAUD® will not give a permanent bond to the floor. It is a repositionable underlay: harmless to carpeting, floors and rugs."""
			}
		]


	def americanFiber(self):
		return [
			{
				"name": "ULTRA",
				"manufacturer": "American Fiber Cushion",
				"type": "Carpet Cushion",
				"picture": ["http://www.americanfibercushion.net/images/ultra%20flat-crop-u3362.jpg", "http://www.americanfibercushion.net/images/hi%20res%20ultra%20flat.jpg", "http://www.americanfibercushion.net/images/ultra%201-crop-u3353.jpg"],
				"colors": [],
				"manurl": "www.americanfibercushion.net",
				"description": """
					ULTRA
					<br><br>
					Great comfort and feel. Under foot improves acoustics and reduces noise. Very consistent thickness and density. The new generation of carpet cushion. Great pet friendly product with no VOC's. 
					<br><br>
					Comes in 24oz, 28oz, 32oz, & 32oz double stick."""

			},
			{
				"name": "FORTITUDE",
				"manufacturer": "American Fiber Cushion",
				"type": "Carpet Cushion",
				"picture": ["http://www.americanfibercushion.net/images/3%20fort%20rolls%20white%20bg-crop-u3475.jpg", "http://www.americanfibercushion.net/images/hi%20res%20fort%20samples-crop-u3553.jpg", "http://www.americanfibercushion.net/images/fort%20samples-crop-u3571.jpg", "http://www.americanfibercushion.net/images/hi%20res%20fort%20samples%202-crop-u3562.jpg"],
				"colors": [],
				"manurl": "www.americanfibercushion.net",
				"description": """
					FORTITUDE
					<br><br>
					Consistent thickness and density. Provides thermal insulation. May be used with radiant heating. Meets or exceeds HUD-FHA requirements. Meets the CARPET CUSHION COUNCIL requirements.
					<br><br>
					Comes in 20oz, 24oz, 28oz, 32 oz and 40oz."""
			},
			{
				"name": "DOUBLE STICK",
				"manufacturer": "American Fiber Cushion",
				"type": "Carpet Cushion",
				"picture": ["http://www.americanfibercushion.net/images/ds%20flat.jpg"],
				"colors": [],
				"manurl": "www.americanfibercushion.net",
				"description": """
					DOUBLE STICK
					<br><br>
					Consistent thickness and density. Provides thermal insulation. May be used with radiant heating. Meets or exceeds HUD-FHA requirements. Meets the CARPET CUSHION COUNCIL requirements.
					<br><br>
					Comes in 32oz and 40oz."""
			},
			{
				"name": "PREMIER",
				"manufacturer": "American Fiber Cushion",
				"type": "Carpet Cushion",
				"picture": ["http://www.americanfibercushion.net/images/ez%20glide%20flat-crop-u3638.jpg", "http://www.americanfibercushion.net/images/ez%20glide%201.jpg", "http://www.americanfibercushion.net/images/hi%20res%20ez%20glide-crop-u3593.jpg"],
				"colors": [],
				"manurl": "www.americanfibercushion.net",
				"description": """
					PREMIER
					<br><br>
					Premier dramatically reduces carpet wear. Excellent dimensional stability. Ideal for carts and rolling traffic. Meets the CARPET CUSHION COUNCIL requirements.
					<br><br>
					Comes in 28oz, 32oz, 40oz, and 50oz. """
			},
			{
				"name": "Platinum Slide-Stop",
				"manufacturer": "American Fiber Cushion",
				"type": "Rug Pad",
				"picture": ["https://upload.wikimedia.org/wikipedia/commons/2/26/512pxIcon-sunset_photo_not_found.png"],
				"colors": [],
				"manurl": "www.americanfibercushion.net",
				"description": """
					Platinum Slide-Stop
					<br><br>
					Maximum Gripping Power Does not contain Latex or PVC. The best product to keep your area rug from sliding. Contains a moisture barrier to help keep a floor dry when pets have an accident. Available in most standard area rugs sizes. Adds cushion under foot 0.250". Extends the life of your area rug for use on all surfaces. 
					<br><br>
					Limited Lifetime Warranty. """

			},
			{
				"name": "Ultimate Slide-Stop",
				"manufacturer": "American Fiber Cushion",
				"type": "Rug Pad",
				"picture": ["https://upload.wikimedia.org/wikipedia/commons/2/26/512pxIcon-sunset_photo_not_found.png"],
				"colors": [],
				"manurl": "www.americanfibercushion.net",
				"description": """
					Ultimate Slide-Stop
					<br><br>
					Superior Gripping Power Contains a latex non-slip side for superior performance. The industry standard to keep your area rug from sliding. Available in most standard area rugs sizes. Adds cushion under foot 0.250". Extends the life of your area rug for use on all surfaces even carpet. 
					<br><br>
					Limited 15 Year Warranty. """
			},
			{
				"name": "All Surface Slide-Stop",
				"manufacturer": "American Fiber Cushion",
				"type": "Rug Pad",
				"picture": ["https://upload.wikimedia.org/wikipedia/commons/2/26/512pxIcon-sunset_photo_not_found.png"],
				"colors": [],
				"manurl": "www.americanfibercushion.net",
				"description": """
					All Surface Slide-Stop
					<br><br>
					Superior Gripping Power Contains a latex non-slip side for superior performance. The industry standard to keep your area rug from sliding. Available in most standard area rugs sizes. Adds cushion under foot 0.160" lower profile for thick rugs. Extends the life of your area rug for use on all surfaces even carpet.
					<br><br>
					Limited 15 Year Warranty."""
			},
			{
				"name": "Premium Slide-Stop",
				"manufacturer": "American Fiber Cushion",
				"type": "Rug Pad",
				"picture": ["https://upload.wikimedia.org/wikipedia/commons/2/26/512pxIcon-sunset_photo_not_found.png"],
				"colors": [],
				"manurl": "www.americanfibercushion.net",
				"description": """
					Premium Slide-Stop
					<br><br>
					Superior Gripping Power. Keeps rugs secure. Prevents slipping. Makes vacuuming easier. For use on all hard floors. 
					<br><br>
					Extra Cushioning. Adds extra comfort underfoot.
					<br><br>
					Environmentally Friendly. Made from plant-based products. Supports earth-smart greener living. """
			},
			{
				"name": "Basic Slide-Stop",
				"manufacturer": "American Fiber Cushion",
				"type": "Rug Pad",
				"picture": ["https://upload.wikimedia.org/wikipedia/commons/2/26/512pxIcon-sunset_photo_not_found.png"],
				"colors": [],
				"manurl": "www.americanfibercushion.net",
				"description": """
					Premium Slide-Stop
					<br><br>
					Superior Gripping Power. Keeps rugs secure. Prevents slipping. Makes vacuuming easier. For use on all hard floors. 
					<br><br>
					Extra Cushioning. Adds extra comfort underfoot.
					<br><br>
					Environmentally Friendly. Made from plant-based products. Supports earth-smart greener living. """
			},
			{
				"name": "Silent Moisture Guard",
				"manufacturer": "American Fiber Cushion",
				"type": "Underlayment",
				"picture": ["https://upload.wikimedia.org/wikipedia/commons/2/26/512pxIcon-sunset_photo_not_found.png"],
				"colors": [],
				"manurl": "www.americanfibercushion.net",
				"description": """
					Silent Moisture Guard Meets or exceeds new standards for Laminate Underlayment. Made with 100% Recycled Fibers. Easy to install on grade or above grade. Made with Patent Applied for Technology. Meets the CRI V.O.C. emissions testing requirements. For use with all brands of interlocking and laminate flooring. Superior Sound deadening properties. Moisture vapor blocking. 
					<br><br>
					Specifications:
					<br>
					Thickness 0.165"
					<br>
					Density lbs/cu.ft. 9
					<br>
					Tensile Strength Length 125
					<br>
					Tensile Strength Width 141
					<br>
					Pill Test PASS
					<br>
					Radiant panel- ASTME 648 Class 1
					<br>
					Water Vapor Transmission 0.28 PERMS
					<br>
					Compression Resistance 4.6PSI
					<br>
					IIC 70
					<br>
					STC 65
					<br>
					Roll Width 3'/6'
					<br>
					Roll Length 33.3'/60'"""
			}
		]


