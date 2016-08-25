<?php
	
	// connect to database
	$mysqli = new mysqli('mysqlcluster9.registeredsite.com','pcsfloors','Padpimp1','padpimp');

	if ($mysqli->connect_error) 
	{
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	$id = $_POST['id'];
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
						<li><a href="catalog.html">Products</a></li> 
						<li class="scroll active"><a href="contact.html">Contact</a></li>
					</ul> 
				</div> 
			</div> 
		</div><!--/navbar--> 
	</header> <!--/#navigation--> 

	<section id="contactInformation">
		<div class="container">
			<div class="text-center">
				<div class="col-sm-8 col-sm-offset-2" style="margin-top:75px;">
					<h2 class="title-one animated bounceInLeft">Edit product in database</h2>
				</div>
			</div>
		</div>
	</section>

	<section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <h1>Query form</h1>
                    <p class="lead">Edit product information</p>
						<form id="queryEdit-form" method="post" action="databaseInsertTemplate.php" role="form" enctype="multipart/form-data">

						    <div class="messages"></div>

						    <div class="controls">

						        <div class="row">
						            <div class="col-md-6">
						                <div class="form-group">
						                    <label for="form_name">Product name *</label>
						                    <input id="form_name" type="text" name="name" class="form-control" value="<?php echo $row['name'];?>" required="required" data-error="Name is required.">
						                    <div class="help-block with-errors"></div>
						                </div>
						            </div>
						            <div class="col-md-6">
						                <div class="form-group">
						                    <label for="form_manufacturer">Manufacturer *</label>
						                    <input id="form_manaufacturer" type="text" name="manufacturer" class="form-control" value="<?php echo $row['manufacturer'];?>" required="required" data-error="Manufacturer is required.">
						                    <div class="help-block with-errors"></div>
						                </div>
						            </div>
						        </div>
						        <div class="row">
						            <div class="col-md-6">
						                <div class="form-group">
						                    <label for="form_type">Type *</label>
						                    <input id="form_type" type="text" name="type" class="form-control" value="<?php echo $row['type'];?>" required="required" data-error="Type is required.">
						                    <div class="help-block with-errors"></div>
						                </div>
						            </div>
						        </div>
						        <div class="row">
						            <div class="col-md-6">
						                <div class="form-group">
						                    <label for="form_picture">Picture *</label>
						                    <input id="form_picture" type="file" name="picture[]" class="fileupload1">
						                    <div class="help-block with-errors"></div>						                    
						                </div>
						            </div>
					            	<div class="col-md-6">
						            	<?php foreach($picarray as $key => $value): ?>
							            	<div class="row" id="<?php echo ("picture" . $key);?>">
							            		<input type="hidden" name="oldPictures[]" value="<?=$value;?>">
							            			<div class="col-md-10 outerPictureDiv">
								            				<img style="height:100%" src=<?=$value;?>>						            		
								            			<div class="col-md-2 innerPictureDiv">
								            				<input type="button" value="Remove" class="btn btn-primary pictureButton" onclick='removePicture("#<?php echo ("picture" . $key);?>", "<?=$value;?>", "removedPictures")'>
								            				</button>
								            			</div>
							            			</div>
							            	</div>
						            	<?php endforeach; ?>
						            </div>
						        </div>
						        <br><br>
						        <div class="row">
						            <div class="col-md-6">						             
						                    <ul class="nav nav-tabs">
												<li class="active"><a data-toggle="tab" href="#colorimages">Colors (Images)</a></li>
												<li><a data-toggle="tab" href="#colorcode">Color (Codes/Names)</a></li>
											</ul>
											<div class="tab-content">
												<div id="colorimages" class="tab-pane fade in active">
													<div class="form-group">													
									                    <input id="form_colors" type="file" name="colors[]" class="form-control-file fileupload2" placeholder="Please enter colors"></input>
									                    <input id="form_colorname" type="text" name="colornames[]" class="form-control nameinput" placeholder="Name of color"></input>
									                    <div class="help-block with-errors"></div>
													</div>
												</div>
												<div id="colorcode" class="tab-pane fade in">
													<div class="form-group">													
									                    <input id="form_colorcodes" type="text" name="colorcodes[]" class="colorinput form-control" placeholder="Please enter color codes"></input>
									                    <button type="button" class="colorbutton">Add another code</button>
									                    <div class="help-block with-errors"></div>
									                </div>
								            	</div>
											</div>		                							                
						            </div>					  
					            	<div class="col-md-6">
						            	<?php foreach($colorarray as $key => $value): ?>
						            		<div class="row" id="<?php echo ("color" . $key);?>">
						            			<input type="hidden" name="oldColors[]" value="<?=$value->{'url'};?>">
							            			<div class="col-md-10 outerPictureDiv">
								            				<img style="height:100%" src=<?=$colorarray[$key]->{'url'};?>>						            		
								            			<div class="col-md-2 innerPictureDiv">
								            				<input type="button" value="Remove" class="btn btn-primary pictureButton" onclick='removePicture("#<?php echo ("color" . $key);?>", "<?=$value->{'url'};?>", "removedColors")'>								            				
								            			</div>
							            			</div>
						            		</div>
						            	<?php endforeach; ?>
						            </div>
					        	</div>
					        	<br>
						        <div class="row>">
						            <div class="col-md-6">
						                <div class="form-group">
						                    <label for="form_type">ID</label>
						                    <input id="form_type" type="number" min="0" max="100000" name="id" class="form-control" value="<?php echo $row['id'];?>" data-error="ID is wrong. Try again.">
						                    <div class="help-block with-errors"></div>
						                </div>
						            </div>
						        </div>
						        <div class="row">
						        	<div class="col-md-6">
						                <div class="form-group">
						                    <label for="form_type">Manufacturer URL *</label>
						                    <input id="form_type" type="text" name="manurl" class="form-control" value="<?php echo $row['manurl'];?>" required="required" data-error="Manufacturer URL is required.">
						                    <div class="help-block with-errors"></div>
						                </div>
						            </div>
						        </div>
						        <div class="row">
						            <div class="col-md-12">
						                <div class="form-group">
						                    <label for="form_description">Description *</label>
						                    <textarea id="form_description" type="text" name="description" class="form-control" rows="4" required="required" data-error="Description is required"><?php echo strip_tags($row['description']);?></textarea>
						                    <div class="help-block with-errors"></div>
						                </div>
						            </div>
						        </div>
						            <div class="col-md-12">
						                <input type="submit" class="btn btn-success btn-send" value="Update Product">
						            </div>
						        <div class="row">
						            <div class="col-md-12"><br>
						                <p class="text-muted"><strong>*</strong> These fields are required.</p>
						            </div>
						        </div>
						    </div>

						    <div style="display:none;" id="removedPictures">
						    </div>
						    <div syle="display:none;">						    	
						    	<input id="form_identifier" type="hidden" name="identifier" class="form-control" value="edit">
						    </div>

						</form>
                </div>
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
<script>
	$('#queryEdit-form').delegate('input.fileupload1', 'change', function(){
	  $('form input.fileupload1').last().after($('<input type="file" name="picture[]" class="form-control-file fileupload1" />'));
	});
	$('#queryEdit-form').delegate('input.fileupload2', 'change', function(){
	  $('form input.nameinput').last().after($('<input type="file" name="colors[]" class="form-control-file fileupload2" /><input id="form_colorname" type="text" name="colornames[]" class="form-control nameinput" placeholder="Name of color" />'));
	});
	$('#queryEdit-form').delegate('button.colorbutton', 'click', function(){
	  $('form input.colorinput').last().after($('<input id="form_colorcodes" type="text" name="colorcodes[]" class="colorinput form-control" placeholder="Please enter color codes" required="required" data-error="Valid color code is required." />'));
	});
</script>
</html>