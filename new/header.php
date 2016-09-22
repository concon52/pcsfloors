<?php

function outputHeader()
{

$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

echo '
<html lang="en">
	<head> 
		<meta charset="utf-8"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<meta name="description" content="PCS Distributors">
		<meta name="title" content="PCS Distributors">
		<title>PCS Distributors</title> 
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

		<script type="text/javascript" src="js/jquery.js"></script> 
	</head><!--/head-->
	<body>
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
						<ul class="nav navbar-nav navbar-right">';
						
						

						if (strpos($url,"index") !== false) 
						{
							echo '<li class="scroll active"><a href="#navigation">Home</a></li>
							<li class="scroll"><a href="#about-us">About Us</a></li>
							<li class="scroll"><a href="#clients">Testimonials</a></li>
							<li><a href="productMenu.php">Products</a></li> 
							<li><a href="contact.php">Contact</a></li>';
						}

						if (strpos($url,"contact") !== false) 
						{
							echo '<li class="scroll"><a href="index.php">Home</a></li>
							<li class="scroll"><a href="index.php">About Us</a></li>
							<li class="scroll"><a href="index.php">Testimonials</a></li>
							<li><a href="productMenu.php">Products</a></li> 
							<li class="active"><a href="contact.php">Contact</a></li>';
						}

						else if (strpos(strtolower($url),"product") !== false) 
						{
							echo '<li class="scroll"><a href="index.php">Home</a></li>
							<li class="scroll"><a href="index.php">About Us</a></li>
							<li class="scroll"><a href="index.php">Testimonials</a></li>
							<li class="active"><a href="productMenu.php">Products</a></li> 
							<li><a href="contact.php">Contact</a></li>';
						}

						echo "
						</ul> 
					</div> 
				</div> 
			</div><!--/navbar--> 
		</header> <!--/#navigation-->";
}



?>