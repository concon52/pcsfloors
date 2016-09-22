<!DOCTYPE html>

<?php 
	include 'header.php';
	outputHeader();
?>

	<section id="home">
		<div class="home-pattern"></div>
		<div id="main-carousel" class="carousel slide" data-ride="carousel"> 
			<ol class="carousel-indicators">
				<li data-target="#main-carousel" data-slide-to="0" class="active"></li>
				<li data-target="#main-carousel" data-slide-to="1"></li>
				<li data-target="#main-carousel" data-slide-to="2"></li>
			</ol><!--/.carousel-indicators--> 
			<div class="carousel-inner">
				<div class="item active" style="background-image: url(images/homePageSlide1.jpg)"> 
					<div class="carousel-caption"> 
						<div> 
							<h2 class="heading animated bounceInRight outlineText">PCS Distributors</h2> 
							<p class="animated bounceInLeft outlineText">Providing Northern California since 1971</p> 
							<a class="btn btn-default slider-btn animated bounceInUp" href="productMenu.php">Browse</a> 
						</div> 
					</div> 
				</div>
				<div class="item" style="background-image: url(images/homePageSlide2.jpg)"> 
					<div class="carousel-caption"> <div> 
						<h2 class="heading animated bounceInRight outlineText">PCS Distributors</h2> 
						<p class="animated bounceInLeft outlineText">Order a sample today!</p> 
						<a class="btn btn-default slider-btn animated bounceInUp" href="productMenu.php">Browse</a> 
					</div> 
				</div> 
			</div> 
			<div class="item" style="background-image: url(images/homepageSlide3.jpg)"> 
				<div class="carousel-caption"> 
					<div> 
						<h2 class="heading animated bounceInRight outlineText">PCS Distributors</h2> 
						<p class="animated bounceInLeft outlineText">Supplying compelling niche products</p> 
						<a class="btn btn-default slider-btn animated bounceInUp" href="productMenu.php">Browse</a> 
					</div> 
				</div> 
			</div>
		</div><!--/.carousel-inner-->

		<a class="carousel-left member-carousel-control hidden-xs" href="#main-carousel" data-slide="prev"><i class="fa fa-angle-left"></i></a>
		<a class="carousel-right member-carousel-control hidden-xs" href="#main-carousel" data-slide="next"><i class="fa fa-angle-right"></i></a>
	</div> 

</section><!--/#home-->

	<section id="about-us">
		<div class="container">
			<div class="text-center">
				<div class="col-sm-8 col-sm-offset-2">
					<h2 class="title-one">About Us</h2>
					<p>Bringing compelling niche products to Northern California Flooring Market since 1971.</p>
					<p>Since January of 2016 PCS is under the new direction led by Doug Wilson and the rest of the PCS team.</p>
					<p>Our Sales Manager for the North Bay (North of Hwy 92) is Vickie Haussmann.</p>
					<p>Our Sales manager in the South Bay is Nihad Sahmanovic.</p>
				</div>
			</div>
		</div>
	</section><!--/#about-us-->

<!-- 	<section id="services" class="parallax-section">
		<div class="container">
			<div class="row text-center">
				<div class="col-sm-8 col-sm-offset-2">
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="our-service">
						<div class="services row">
							<div class="col-sm-3">
								<div class="single-service">
									<img src="images/services/woodFlooring.jpg">
									<h2>Wood Flooring</h2>
									<p>Placeholder</p>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="single-service">
									<img src="images/services/carpeting.jpg">
									<h2>Carpet</h2>
									<p>Placeholder</p>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="single-service">
									<img src="images/services/laminateFlooring.jpg">
									<h2>Laminate </h2>
									<p>Placeholder</p>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="single-service">
									<img src="images/services/logoMats.jpg">
									<h2>Logo Mats</h2>
									<p>Placeholder</p>
								</div>
							</div></div>
						</div>
					</div>
				</div>
			</div>
		</section> --><!--/#service-->


<!-- 	<section id="portfolio">
		<div class="container">
			<div class="row text-center">
				<div class="col-sm-8 col-sm-offset-2"> 
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
			</ul>
			<div class="portfolio-items">
				<div class="col-sm-3 col-xs-12 portfolio-item wood">
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
				</div>

			</div> 
		</section> --> <!--/#portfolio-->

					<section id="clients" class="parallax-section">
						<div class="container">
							<div class="clients-wrapper">
								<div class="row text-center">
									<div class="col-sm-8 col-sm-offset-2">
										<h2 class="title-one">Customer Testimonials</h2>
									</div>
								</div>
								<div id="clients-carousel" class="carousel slide" data-ride="carousel"> <!-- Indicators -->
									<ol class="carousel-indicators">
										<li data-target="#clients-carousel" data-slide-to="0" class="active"></li>
										<!-- <li data-target="#clients-carousel" data-slide-to="1"></li>
										<li data-target="#clients-carousel" data-slide-to="2"></li> -->
									</ol> <!-- Wrapper for slides -->
									<div class="carousel-inner">
										<div class="item active">
											<div class="single-client">
												<div class="media">
<!-- 													<img class="pull-left" src="images/clients/StevenMiller.jpg" alt="No Picture Available"> -->
													<div class="media-body">
														<blockquote><p>We have dealt with PCS for many years. They are our source for area rug padding. They are always there for us and deliver on time. We feel we a valuable partnership with PCS.</p><small>Steven Miller Gallery of Menlo Park- Virginia</small></blockquote>
													</div>
												</div>
											</div>
										</div>
<!-- 										<div class="item">
											<div class="single-client">
												<div class="media">
													<img class="pull-left" src="images/clients/client3.jpg" alt="">
													<div class="media-body">
														<blockquote><p>"PCS distributors got me the samples I needed and they were extremely high quality!"</p><small>George Bush</small></blockquote>
													</div>
												</div>
											</div>
										</div>
										<div class="item">
											<div class="single-client">
												<div class="media">
													<img class="pull-left" src="images/clients/client2.jpg" alt="">
													<div class="media-body">
														<blockquote><p>"PCS distributors is the best!"</p><small>Michael Jordan</small></blockquote>
													</div>
												</div>
											</div>
										</div> -->
									</div>
								</div>
							</div>
						</div>
					</section><!--/#clients-->

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