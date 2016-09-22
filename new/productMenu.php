<?php
include 'header.php';

	$typecount=1;
	$count=0;

	// connect to database
	$mysqli = new mysqli('mysqlcluster9.registeredsite.com','pcsfloors','Padpimp1','padpimp');

	if ($mysqli->connect_error) 
	{
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	$query = "SELECT name, type, picture, colors, id FROM Products ORDER BY type";

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

<?php 
	outputHeader();
?>	

	<section id="products">
        <div class="container" id="productmenutitle">
        	<div class="row text-center">
        		<h2 class="title-one animated bounceInLeft">Product Types</h2>
        		<p>
        			<a href="productMenuMan.php" class="lightWeight">Browse By Manufacturer</a>
        		</p>
        	</div>
                <div class="col-lg-8 col-lg-offset-2">
                	<?php foreach($products as $key => $value): ?>
                   		<?php if ($products[$key]["type"] != $products[$key-1]["type"]) : ?>
                   			<?php $typecount=$typecount+1 ?>
                   		<?php endif; ?>
                   	<?php endforeach; ?>

                   	<?php foreach($products as $key => $value): ?>

                   		<?php if ($key == 0) : ?>
                   			<?php $count=$count+1; ?>
                   			<div class="row typetitle">
                   				<h2 style="text-transform:capitalize"><?php echo $products[$key]["type"]; ?></h2>
                   			</div>
                   			<div class="row">
                   				<div class="col-lg-12 text-center"  id="menuitem">
               			
						<?php elseif ($products[$key]["type"] != $products[$key-1]["type"]) : ?>
							</div>
							</div>
                   			<div class="row typetitle">
                   				<h2 style="text-transform:capitalize"><?php echo $products[$key]["type"]; ?></h2>
                   			</div>
                   			<div class="row">
                   				<div class="col-lg-12 text-center"  id="menuitem">

                   		<?php endif ?>   

							<div class="col-lg-4 <?php echo $products[$key]["id"]; ?>" id="productslot">
								<div class="row">
									<img src="<?php echo $products[$key]["thumbnail"]; ?>" class="img-thumbnail" alt="No Image Available" width="200" height="200">
								</div>
								<div class="row text-center" id="productname">
									<a href="<?php echo "productTemplate.php?id=" . $products[$key]["id"]; ?>" style="text-transform:capitalize"><?php echo $products[$key]["name"]; ?></a>
								</div>
							</div>

						<?php if ($count==$typecount && $key==count($products)-1): ?>
	                   		</div>
	                   		</div>
	                   	<?php endif ?>

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

