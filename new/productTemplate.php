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
	$picarray = json_decode($row['picture']);
	$colorarray = json_decode($row['colors']);

?>

<!DOCTYPE html>
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
</head><!--/head-->
<body>
<!-- 	<div class="preloader">
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
	</div>-->
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
						<li class="scroll active"><a href="#navigation">Home</a></li>
						<li class="scroll"><a href="#about-us">About Us</a></li>
						<li class="scroll"><a href="#portfolio">Portfolio</a></li> 
						<li class="scroll"><a href="#clients">Testimonials</a></li>
						<li><a href="catalog.html">Products</a></li> 
						<li><a href="contact.html">Contact</a></li>
					</ul> 
				</div> 
			</div> 
		</div><!--/navbar--> 
	</header> <!--/#navigation--> 


	<section id="product-template">
		<div class="container">
			<div class="text-center">
				<div class="col-xs-12">
					<br><br>
					<div class="row">
					<h2 class="title-one"><?=$row['name']?></h2>
					</div>
					<div class="row">
						<?php if (!empty($picarray)): ?>
							<div class="col-md-6 col-xs-12">
							<h2 class="title-two">Pictures</h2>
							<?php foreach($picarray as $key => $value): ?>
								<div class="col-xs-12">
									<img vspace="15" style="width:100%" src=<?=$picarray[$key];?>>				
								</div>
							<?php endforeach; ?>
						</div>
						<?php if (!empty($colorarray)): ?>	
						<div class="col-md-6 col-xs-12">
							<h2 class="title-two">Colors</h2>
							<div class="col-xs-12" style="overflow:auto; height:600px;">
								<br><br>
								<?php foreach($colorarray as $key => $value): ?>
									<div class="col-xs-4 image-text pop">
										<div class="effectfront">					
											<img vspace="5" hspace="15" class="imageresource" style="width:80%; height:80%" src=<?=$colorarray[$key]->{'url'};?>>										
											<button type="button" class="btn-primary orderbutton" style="display:none;">Order Sample</button>
											<button type="button" class="btn-primary viewbutton" style="display:none;">View Image</button>
										</div>
										<p class="color-name"><?=$colorarray[$key]->{'name'};?></p>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
						<? else: ?>
							<div class="col-md-6 col-xs-12">
								<h2 class="title-two">Colors</h2>
								<div class="col-xs-12">
									<p>There are no colors to display for this product.</p>
								</div>
							</div>
						<?php endif?>
						<?php else: ?>
							<?php if (!empty($colorarray)): ?>	
							<div class="col-xs-12">
								<h2 class="title-two">Colors</h2>
								<div class="col-xs-12">
									<?php foreach($colorarray as $key => $value): ?>
										<div class="col-md-4 pop">											
											<img class="effectfront imageresource" vspace="15" hspace="15" style="width:70%; height:70%" src=<?=$colorarray[$key]->{'url'};?>>					
										</div>
									<?php endforeach; ?>
								</div>
							</div>
							<?php endif?>
						<?php endif?>
					</div>
					<br><br>
					<div class="col-xs-12 text-center" align="left">
						<h2 class="title-two">Description</h2>
							<div class="col-xs-12" align="left">
								<?=$row['description']?>
							</div>
					</div>
				</div>
			</div>
		</div>

	<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body text-center">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<br><br>
					<img src="" class="imagepreview" style="width:100%;">
				</div>
				<div class="modal-footer">
					<p id="imagenamefooter" class="text-center"></p>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	</section><!--/#about-us-->

	<div id="colorpicker1">
		<a class="color">
			<div class="colorInner"></div>
		</a>
	</div>


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
	<script type="text/javascript" src="js/jquery.isotope.min.js"></script>
	<script type="text/javascript" src="js/jquery.prettyPhoto.js"></script> 
	<script type="text/javascript" src="js/jquery.parallax.js"></script> 
	<script type="text/javascript" src="js/main.js"></script> 
	<script type="text/javascript" src="productTemplate.js"></script> 

</body>

</html>