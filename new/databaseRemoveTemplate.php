<!DOCTYPE html>

<?php 
	include 'header.php';
	outputHeader();
?>

	<section id="contactInformation">
		<div class="container">
			<div class="text-center">
				<div class="col-sm-8 col-sm-offset-2" style="margin-top:75px;">
					<h2 class="title-one animated bounceInLeft">Remove products from database</h2>
				</div>
			</div>
		</div>
	</section>

	<section id="contact">
        <div class="container">
            <div class="row col-sm-8 col-sm-offset-2 text-center">
                <div class="col-lg-8 col-lg-offset-2">
                    <h1>Query form</h1>
                    <p class="lead">Enter product information to be removed</p>
						<form id="queryRemove-form" method="post" action="databaseRemoveTemplateScript.php" role="form" enctype="multipart/form-data">

						    <div class="messages"></div>

						    <div class="controls">

						        <div class="row">
						            <div class="col-sm-8 col-sm-offset-2">
						                <div class="form-group">
						                    <label for="form_id">Product ID *</label>
						                    <input id="form_id" type="text" name="id" class="form-control" placeholder="Please enter product ID number" required="required" data-error="ID is required.">
						                    <div class="help-block with-errors"></div>
						                </div>
						            </div>
						        </div>
					            <div class="col-md-12">
						                <input type="submit" class="btn btn-success btn-send" value="Remove Product">
						            </div>
						        <div class="row">
						            <div class="col-md-12"><br>
						                <p class="text-muted"><strong>*</strong> These fields are required.</p>
						            </div>
						        </div>
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
    <script src="validator.js"></script>
    <script src="contact.js"></script>
</body>
<script>
	$('#query-form').delegate('input.fileupload1', 'change', function(){
	  $('form input.fileupload1').last().after($('<input type="file" name="picture[]" class="fileupload1" />'));
	});
	$('#query-form').delegate('input.fileupload2', 'change', function(){
	  $('form input.fileupload2').last().after($('<input type="file" name="colors[]" class="fileupload2" />'));
	});
	$('#query-form').delegate('button.colorbutton', 'click', function(){
	  $('form input.colorinput').last().after($('<input id="form_colorcodes" type="text" name="colorcodes[]" class="colorinput form-control" placeholder="Please enter color codes" required="required" data-error="Valid color code is required." />'));
	});
</script>
</html>