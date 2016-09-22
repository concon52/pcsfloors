<?php
include 'header.php';
?>

<!DOCTYPE html>

<?php outputHeader(); ?>

	<section id="contactInformation">
		<div class="container">
			<div class="text-center">
				<div class="col-sm-8 col-sm-offset-2" style="margin-top:75px;">
					<h2 class="title-one animated bounceInLeft">Contact Information</h2>
					<p class="animated bounceInLeft">(408) 436-7940</p>
					<p class="animated bounceInRight">3283 De La Cruz Blvd Suite J., Santa Clara, Ca 95054</p>
					<p class="animated bounceInLeft">Toll Free (800) 238-4727</p>
					<p class="animated bounceInRight">Fax: (408) 436-7943</p>
					<p class="animated bounceInLeft">info@pcsdistributors.com</p>
				</div>
			</div>
		</div>
	</section>

	<section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <h1>Contact form</h1>
                    <p class="lead">Enter your contact information to contact PCSDistributors</p>
						<form id="contact-form" method="post" action="contactScript.php" role="form">

						    <div class="messages"></div>

						    <div class="controls">

						        <div class="row">
						            <div class="col-md-6">
						                <div class="form-group">
						                    <label for="form_name">Firstname *</label>
						                    <input id="form_name" type="text" name="name" class="form-control" placeholder="Please enter your firstname *" required="required" data-error="Firstname is required.">
						                    <div class="help-block with-errors"></div>
						                </div>
						            </div>
						            <div class="col-md-6">
						                <div class="form-group">
						                    <label for="form_lastname">Lastname *</label>
						                    <input id="form_lastname" type="text" name="surname" class="form-control" placeholder="Please enter your lastname *" required="required" data-error="Lastname is required.">
						                    <div class="help-block with-errors"></div>
						                </div>
						            </div>
						        </div>
						        <div class="row">
						            <div class="col-md-6">
						                <div class="form-group">
						                    <label for="form_email">Email *</label>
						                    <input id="form_email" type="email" name="email" class="form-control" placeholder="Please enter your email *" required="required" data-error="Valid email is required.">
						                    <div class="help-block with-errors"></div>
						                </div>
						            </div>
						            <div class="col-md-6">
						                <div class="form-group">
						                    <label for="form_phone">Phone</label>
						                    <input id="form_phone" type="tel" name="phone" class="form-control" placeholder="Please enter your phone">
						                    <div class="help-block with-errors"></div>
						                </div>
						            </div>
						        </div>
						        <div class="row">
						            <div class="col-md-12">
						                <div class="form-group">
						                    <label for="form_message">Message *</label>
						                    <textarea id="form_message" name="message" class="form-control" placeholder="Message *" rows="4" required="required" data-error="Please leave us a message."></textarea>
						                    <div class="help-block with-errors"></div>
						                </div>
						            </div>
						            <div class="col-md-12">
						                <input type="submit" class="btn btn-success btn-send" value="Send message">
						            </div>
						        </div>
						        <div class="row">
						            <div class="col-md-12">
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
</html>