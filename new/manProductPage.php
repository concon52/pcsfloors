<?php
	
	// connect to database
	$mysqli = new mysqli('mysqlcluster9.registeredsite.com','pcsfloors','Padpimp1','padpimp');

	if ($mysqli->connect_error) 
	{
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	$man = $_GET['man'];
	$query = "SELECT * FROM Products WHERE manufacturer = '$man' ORDER BY type";
	$result = mysqli_query($mysqli, $query);
	$products = array();


	if ($result)
	{
		while ($row = mysqli_fetch_assoc($result))
		{
			$temppic = json_decode($row['picture']);
			$tempcolor = json_decode($row['colors']);
			$temptype = $row['type'];
			$tempname = $row['name'];
			$tempid = $row['id'];

			if (empty($temppic))
			{
				array_push($products, array("thumbnail" => $tempcolor[0]->{'url'}, "type" => $temptype, "name" => $tempname, "id" => $tempid));
			}
			else
			{
				array_push($products, array("thumbnail" => $temppic[0], "type" => $temptype, "name" => $tempname, "id" => $tempid));
			}
			
		}
	}
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
<!-- 						<li class="scroll"><a href="index.html">Portfolio</a></li> 
 -->						<li class="scroll"><a href="index.html">Testimonials</a></li>
						<li class="scroll active"><a href="productMenu.php">Products</a></li> 
						<li class="scroll"><a href="contact.html">Contact</a></li>
					</ul> 
				</div> 
			</div> 
		</div><!--/navbar--> 
	</header> <!--/#navigation--> 		

	<section id="products">
        <div class="container" id="productmenutitle">
        	<div class="row text-center">
        		<h2 class="title-one animated bounceInLeft">Products</h2>
        		<p id="manufacturer">By <?=$man?></p>
        		<p>
        			<a href="productMenu.php" id="manufacturer">Back To Flooring Types</a>
        		</p>
        		<p>
        			<a href="productMenuMan.html" id="manufacturer">Back To Flooring Manufacturers</a>
        		</p>
        	</div>
                <div class="col-lg-8 col-lg-offset-2">
                   	<?php foreach($products as $key => $value): ?>
                   		<?php if ($products[$key]["type"] != $products[$key-1]["type"] || $key == 0) : ?>
                   			<div class="col-lg-12 typetitle">
                   				<h2 style="text-transform:capitalize"><?php echo $products[$key]["type"]; ?></h2>
                   			</div>
                   		<?php endif ?>
							<div class="col-lg-4 <?php echo $products[$key]["id"]; ?>">
								<div class="row text-center">
									<img src="<?php echo $products[$key]["thumbnail"]; ?>" class="img-thumbnail" alt="No Image Available" width="200" height="200">
								</div>
								<div class="row text-center" id="productname">
									<a href="<?php echo "productTemplate.php?id=" . $products[$key]["id"]; ?>" style="text-transform:capitalize"><?php echo $products[$key]["name"]; ?></a>
								</div>
							</div>
					<?php endforeach; ?>
                </div>
        </div>
	</section>

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
    <script type="text/javascript" src="databaseEditTemplate.js"></script> 
</body>
</html>

