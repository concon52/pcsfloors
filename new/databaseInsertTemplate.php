<!DOCTYPE html>

<?php 
	include 'header.php';
	outputHeader();
?>

	<section id="contactInformation">
		<div class="container">
			<div class="text-center">
				<div class="col-sm-8 col-sm-offset-2" style="margin-top:75px;">
					<h2 class="title-one animated bounceInLeft">Insert products to database</h2>
				</div>
			</div>
		</div>
	</section>

	<section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <h1>Query form</h1>
                    <p class="lead">Enter product information to be inserted</p>
						<form id="queryInsert-form" method="post" action="databaseInsertTemplateScript.php" role="form" enctype="multipart/form-data">

						    <div class="messages"></div>

						    <div class="controls">

						        <div class="row">
						            <div class="col-md-6">
						                <div class="form-group">
						                    <label for="form_name">Product name *</label>
						                    <input id="form_name" type="text" name="name" class="form-control" placeholder="Please enter product name" required="required" data-error="Name is required.">
						                    <div class="help-block with-errors"></div>
						                </div>
						            </div>
						            <div class="col-md-6">
						                <div class="form-group">
						                    <label for="form_manufacturer">Manufacturer *</label>
						                    <input id="form_manaufacturer" type="text" name="manufacturer" class="form-control" placeholder="Please enter manufacturer name" required="required" data-error="Manufacturer is required.">
						                    <div class="help-block with-errors"></div>
						                </div>
						            </div>
						        </div>
						        <div class="row">
						            <div class="col-md-6">
						                <div class="form-group">
						                    <label for="form_type">Type *</label>
						                    <input id="form_type" type="text" name="type" class="form-control" placeholder="Please enter product type" required="required" data-error="Type is required.">
						                    <div class="help-block with-errors"></div>
						                </div>
						            </div>
						            <div class="col-md-6">
						                <div class="form-group">
						                    <label for="form_picture">Picture *</label>
						                    <input id="form_picture" type="file" name="picture[]" class="form-control-file fileupload1" required="required" data-error="Pictures are required">
						                    <div class="help-block with-errors"></div>						                    
						                </div>
						            </div>
						        </div>
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
									                    <input id="form_colorname" type="text" name="colornames[]" class="form-control nameinput" placeholder="Name of color">
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
						                <div class="form-group">
						                    <label for="form_type">ID</label>
						                    <input id="form_type" type="number" min="0" max="100000" name="id" class="form-control" placeholder="Please enter product ID" data-error="ID is wrong. Try again.">
						                    <div class="help-block with-errors"></div>
						                </div>
						            </div>
						        </div>
						        <div class="row">
						        	<div class="col-md-6">
						                <div class="form-group">
						                    <label for="form_type">Manufacturer URL *</label>
						                    <input id="form_type" type="text" name="manurl" class="form-control" placeholder="Please enter Manufacturer URL" required="required" data-error="Manufacturer URL is required.">
						                    <div class="help-block with-errors"></div>
						                </div>
						            </div>
						        </div>
						        <div class="row">
						            <div class="col-md-12">
						                <div class="form-group">
						                    <label for="form_description">Description *</label>
						                    <textarea id="form_description" type="text" name="description" class="form-control" placeholder="Please enter product description" rows="4" required="required" data-error="Description is required"></textarea>
						                    <div class="help-block with-errors"></div>
						                </div>
						            </div>
						        </div>
						            <div class="col-md-12">
						                <input type="submit" class="btn btn-success btn-send" value="Insert Product">
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
	$('#queryInsert-form').delegate('input.fileupload1', 'change', function(){
	  $('form input.fileupload1').last().after($('<input type="file" name="picture[]" class="form-control-file fileupload1" />'));
	});
	$('#queryInsert-form').delegate('input.fileupload2', 'change', function(){
	  $('form input.nameinput').last().after($('<input type="file" name="colors[]" class="form-control-file fileupload2" /><input id="form_colorname" type="text" name="colornames[]" class="form-control nameinput" placeholder="Name of color" />'));
	});
	$('#queryInsert-form').delegate('button.colorbutton', 'click', function(){
	  $('form input.colorinput').last().after($('<input id="form_colorcodes" type="text" name="colorcodes[]" class="colorinput form-control" placeholder="Please enter color codes" required="required" data-error="Valid color code is required." />'));
	});
</script>
</html>