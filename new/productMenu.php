<?php
	
	// connect to database
	$mysqli = new mysqli('mysqlcluster9.registeredsite.com','pcsfloors','Padpimp1','padpimp');

	if ($mysqli->connect_error) 
	{
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	$id = $_GET['id'];
	$query = "SELECT * FROM Products WHERE id = $id";
	$result = mysqli_query($mysqli, $query);
	$row = mysqli_fetch_assoc($result);
	$names = json_decode($row['name']);
	$manufacturer = json_decode($row['manufacturer']);


?>


<!DOCTYPE html>
<html lang="en">
<head> 
	<meta charset="utf-8"> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	<meta name="description" content="PCS Distributors product catalog">
	<title>Product Catalog | PCS Distributors</title> 
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/prettyPhoto.css" rel="stylesheet"> 
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/animate.css" rel="stylesheet"> 
	<link href="css/main.css" rel="stylesheet">
	<link href="css/responsive.css" rel="stylesheet"> 
	<!--[if lt IE 9]> <script src="js/html5shiv.js"></script> 
	<script src="js/respond.min.js"></script> <![endif]--> 
	<link rel="shortcut icon" href="images/ico/pcs_logo.png"> 
	<link rel="pcs_logo" sizes="144x144" href="pcs_logo."> 
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/pcs_logo.png"> 
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/pcs_logo.png"> 
	<link rel="apple-touch-icon-precomposed" href="images/ico/pcs_logo.png">
</head><!--/head-->
<body>
	<div class="preloader">
		<div class="preloder-wrap">
			<div class="preloder-inner"> 
				<div class="ball"></div> 
				<div class="ball"></div> 
				<div class="ball"></div> 
				<div class="ball"></div> 
				<div class="ball"></div> 
				<div class="ball"></div> 
				<div class="ball"></div>
			</div>
		</div>
	</div><!--/.preloader-->
	<header id="navigation"> 
		<div class="navbar navbar-inverse navbar-fixed-top" role="banner"> 
			<div class="container"> 
				<div class="navbar-header"> 
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> 
						<span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> 
					</button> 
					<a class="navbar-brand" href="index.html"><h1><img src="images/pcs_logo.gif" alt="PCS Distributors Logo" height="50px"></h1></a>
				</div> 
				<div class="collapse navbar-collapse"> 
					<ul class="nav navbar-nav navbar-right"> 
						<li class="scroll"><a href="index.html">Home</a></li>
						<li class="scroll"><a href="index.html">About Us</a></li>						
						<li class="scroll"><a href="index.html">Portfolio</a></li> 
						<li class="scroll"><a href="index.html">Testimonials</a></li>
						<li class="scroll active"><a href="catalog.html">Products</a></li> 
						<li><a href="contact.html">Contact</a></li>
					</ul> 
				</div> 
			</div> 
		</div><!--/navbar--> 
	</header> <!--/#navigation--> 

	

	<section id="portfolio">
		<div class="container productpage">
			<div class="row text-center">
				<div class="col-sm-8 col-sm-offset-2" style="margin-top:75px"> 
					<br>
					<h2 class="title-one animated bounceInDown">Catalog</h2>
				</div>
			</div>
			<ul class="portfolio-filter text-center">
				<li><a class="btn btn-default active" href="#" data-filter="*">All</a></li>
				<li><a class="btn btn-default" href="#" data-filter=".wood">Wood</a></li>
				<li><a class="btn btn-default" href="#" data-filter=".carpet">Carpet</a></li>
				<li><a class="btn btn-default" href="#" data-filter=".laminate">Laminate</a></li>
				<li><a class="btn btn-default" href="#" data-filter=".logo">Logo Mats</a></li>
			</ul><!--/#portfolio-filter-->
			<div class="portfolio-items">
<!-- 				<div class="col-sm-3 col-xs-12 portfolio-item wood">
					<div class="view efffect">
						<div class="portfolio-image">
							<a class="portfolioAnchor" href="images/portfolio/big-item1.jpg" rel="image1URL" data-gallery="prettyPhoto" title="description1 description1 description1 description1 description1 ">
								<img src="images/portfolio/1.jpg" alt="title1 ,msdnfskjdf;ljsldjfsdjflkskfjlskdfj"/> 		
							</a>
						</div>
					</div>
				</div>
				<div class="col-sm-3 col-xs-12 portfolio-item laminate">
					<div class="view efffect" >
						<div class="portfolio-image">
							<a class="portfolioAnchor" href="images/portfolio/big-item2.jpg" rel="image2URL" data-gallery="prettyPhoto"  title="description2">
								<img src="images/portfolio/2.jpg" alt="title2"/> 		
							</a>
						</div>
					</div>
				</div>
				<div class="col-sm-3 col-xs-12 portfolio-item carpet">
					<div class="view efffect">
						<div class="portfolio-image">
							<a class="portfolioAnchor" href="images/portfolio/big-item3.jpg" rel="image3URL" data-gallery="prettyPhoto" title="description3">
								<img src="images/portfolio/3.jpg" alt="title3">
							</a>
						</div> 
					</div>
				</div>
				<div class="col-sm-3 col-xs-12 portfolio-item logo">
					<div class="view efffect">
						<div class="portfolio-image">
							<a class="portfolioAnchor" href="images/portfolio/big-item4.jpg" rel="image4URL" data-gallery="prettyPhoto" title="description4">
								<img src="images/portfolio/4.jpg" alt="title4">
							</a>
						</div> 
					</div>
				</div>
				<div class="col-sm-3 col-xs-12 portfolio-item wood">
					<div class="view efffect">
						<div class="portfolio-image">
							<a class="portfolioAnchor" href="images/portfolio/big-item5.jpg" rel="image5URL" data-gallery="prettyPhoto" title="description5">
								<img src="images/portfolio/5.jpg" alt="title5">
							</a>
						</div> 
					</div>
				</div>
				<div class="col-sm-3 col-xs-12 portfolio-item carpet">
					<div class="view efffect">
						<div class="portfolio-image">
							<a class="portfolioAnchor" href="images/portfolio/big-item6.jpg" rel="image6URL" data-gallery="prettyPhoto" title="description6">
								<img src="images/portfolio/6.jpg" alt="title6">
							</a>
						</div> 
					</div>
				</div>
				<div class="col-sm-3 col-xs-12 portfolio-item wood">
					<div class="view efffect">
						<div class="portfolio-image">
							<a class="portfolioAnchor" href="images/portfolio/big-item7.jpg" rel="image7URL" data-gallery="prettyPhoto" title="description7">
								<img src="images/portfolio/7.jpg" alt="title7">
							</a>
						</div> 
					</div>
				</div>
				<div class="col-sm-3 col-xs-12 portfolio-item laminate">
					<div class="view efffect">
						<div class="portfolio-image">
							<a class="portfolioAnchor" href="images/portfolio/big-item8.jpg" rel="image8URL" data-gallery="prettyPhoto" title="description8">
								<img src="images/portfolio/8.jpg" alt="title8">
							</a>
						</div> 
					</div>
				</div>
				<div class="col-sm-3 col-xs-12 portfolio-item wood">
					<div class="view efffect">
						<div class="portfolio-image">
							<a class="portfolioAnchor" href="images/portfolio/big-item9.jpg" rel="image9URL" data-gallery="prettyPhoto" title="description9">
								<img src="images/portfolio/9.jpg" alt="title9">
							</a>
						</div> 
					</div>
				</div>
				<div class="col-sm-3 col-xs-12 portfolio-item carpet">
					<div class="view efffect">
						<div class="portfolio-image">
							<a class="portfolioAnchor" href="images/portfolio/big-item10.jpg" rel="image10URL" data-gallery="prettyPhoto" title="description10">
								<img src="images/portfolio/10.jpg" alt="title10">
							</a>
						</div> 
					</div>
				</div>
				<div class="col-sm-3 col-xs-12 portfolio-item laminate">
					<div class="view efffect">
						<div class="portfolio-image">
							<a class="portfolioAnchor" href="images/portfolio/big-item11.jpg" rel="image11URL" data-gallery="prettyPhoto" title="description11">
								<img src="images/portfolio/11.jpg" alt="title11">
							</a>
						</div> 
					</div>
				</div>
				<div class="col-sm-3 col-xs-12 portfolio-item logo">
					<div class="view efffect">
						<div class="portfolio-image">
							<a class="portfolioAnchor" href="images/portfolio/big-item12.jpg" rel="image12URL" data-gallery="prettyPhoto" title="description12">
								<img src="images/portfolio/12.jpg" alt="title12">
							</a>
						</div> 
					</div>
				</div> -->

			</div> 
		</section> <!--/#portfolio-->



		<footer id="footer"> 
			<div class="container"> 
				<div class="text-center"> 
					<p>Copyright &copy; <script>document.write(new Date().getFullYear())</script> - PCS Distributors | All Rights Reserved
					</p> 
				</div> 
			</div> 
		</footer> <!--/#footer--> 

		<script type="text/javascript" src="js/jquery.js"></script> 
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script type="text/javascript" src="js/smoothscroll.js"></script> 
		<script type="text/javascript" src="js/jquery.isotope.min.js"></script>
		<script type="text/javascript" src="js/jquery.prettyPhoto.js"></script> 
		<script type="text/javascript" src="js/jquery.parallax.js"></script> 
		<script type="text/javascript" src="js/main.js"></script> 
	</body>

</html>

