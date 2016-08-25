<?php

	$success = 1;

	$mysqli = new mysqli('mysqlcluster9.registeredsite.com','pcsfloors','Padpimp1','padpimp');

	if ($mysqli->connect_error) 
	{
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	// function to generate random string
	function generateRandomString($length = 10)
	{
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';

	    for ($i = 0; $i < $length; $i++) 
	    {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }

	    return $randomString;
	}

	if($_POST['identifier'] == "edit")
	{
		$id = $_POST['id'];
		$query = "SELECT * FROM Products WHERE id = $id";
		$result = mysqli_query($mysqli, $query);
		$row = mysqli_fetch_assoc($result);
		$currentcolors = json_decode($row['colors']);
		
		// populate fields with POST
		$fields = array('name' => $_POST['name'], 
						'manufacturer' => $_POST['manufacturer'], 
						'type' => $_POST['type'], 
						'picture' => $_FILES['picture'], 
						'id' => $_POST['id'], 
						'description' => $_POST['description'], 
						'manurl' => $_POST['manurl'],
						'removedPictures' => $_POST['removedPictures'],
						'removedColors' => $_POST['removedColors'],
						'oldColors' => $_POST['oldColors'],
						'oldPictures' => $_POST['oldPictures'],
						'colornames' => $_POST['colornames']
						);
	}
	else
	{
		// populate fields with POST
		$fields = array('name' => $_POST['name'], 
						'manufacturer' => $_POST['manufacturer'], 
						'type' => $_POST['type'], 
						'picture' => $_FILES['picture'], 
						'id' => $_POST['id'], 
						'description' => $_POST['description'], 
						'manurl' => $_POST['manurl'],
						'colornames' => $_POST['colornames']
						);		
	}


	// declare arrays for FILES
	$picarray = array();
	$colorarray = array();

	if($_POST['identifier'] == "edit")
	{

		foreach ($currentcolors as $key => $value) 
		{
			if (array_search($currentcolors[$key]->{'url'}, $fields['removedColors']))
			{
				array_splice($currentcolors, $key, $key);
			}
			else
			{
				array_push($colorarray, $currentcolors[$key]);
			}
		}

		foreach($fields['removedPictures'] as $value)
		{
			unlink($value);
		}
		foreach ($fields['removedColors'] as $value) 
		{
			unlink($value);
		}
		foreach ($fields['oldPictures'] as $value)
		{
			array_push($picarray, $value);
		}
	}


	// Check if image file is a actual image or fake image
	foreach ($fields['picture']["error"] as $key => $error)
	{
		if ($fields["picture"]["name"][$key] != "")
		{
			$target_dir = "pictures/";
			$target_file = $target_dir . basename($_FILES["picture"]["name"][$key]);
			$uploadOk = 1;
			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
			$path = "pictures/" . generateRandomString() . "." . $imageFileType;
			while (file_exists($path))
			{
				$path = "pictures/" . generateRandomString() . "." . $imageFileType;
			}

			if(isset($fields["pictures"][$key])) 
			{
			    $check = getimagesize($_FILES["picture"]["tmp_name"][$key]);
			    if($check !== false) {
			        //echo "File is an image - " . $check["mime"] . ".";
			        $uploadOk = 1;
			    } else {
			        //echo "File is not an image.";
			        $uploadOk = 0;
			    }
			}
			// Check file size
			if ($_FILES["picture"]["size"][$key] > 500000) 
			{
			    //echo "Sorry, your file is too large.";
			    $uploadOk = 0;
			}
			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) 
			{
			    //echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			    $uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) 
			{
			    //echo "Sorry, your file was not uploaded.";
			    $success = 0;
			// if everything is ok, try to upload file
			} 
			else 
			{
			    if (move_uploaded_file($_FILES["picture"]["tmp_name"][$key], $path)) 
			    {
			        //echo "The file " . basename( $_FILES["picture"]["name"][$key]) . " has been uploaded.";
			        array_push($picarray, $path);
			    } 
			    else 
			    {
			        //echo "Sorry, there was an error uploading your file.";
			        $success = 0;
			    }
			}
		}
	}
	unset($value);

// function processPicture(array $files)
// {
// 	// download color pictures from urls
// 	foreach ($files as $key => $value)
// 	{
// 		if ($files[$key] != "")
// 		{
// 			$target_dir = "pictures/";
// 			$target_file = $target_dir . basename($files[$key]);
// 			$uploadOk = 1;
// 			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// 			$path = "pictures/" . generateRandomString() . "." . $imageFileType;
// 			while (file_exists($path))
// 			{
// 				$path = "pictures/" . generateRandomString() . "." . $imageFileType;
// 			}

// 			if(isset($fields["colors"])) 
// 			{
// 			    $check = getimagesize($_FILES["colors"]["tmp_name"][$key]);
// 			    if($check !== false) {
// 			        //echo "File is an image - " . $check["mime"] . ".";
// 			        $uploadOk = 1;
// 			    } else {
// 			        //echo "File is not an image.";
// 			        $uploadOk = 0;
// 			    }
// 			}
// 			// Check file size
// 			if ($_FILES["colors"]["size"][$key] > 500000) 
// 			{
// 			    //echo "Sorry, your file is too large.";
// 			    $uploadOk = 0;
// 			}
// 			// Allow certain file formats
// 			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
// 			&& $imageFileType != "gif" ) 
// 			{
// 			    //echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
// 			    $uploadOk = 0;
// 			}
// 			// Check if $uploadOk is set to 0 by an error
// 			if ($uploadOk == 0) 
// 			{
// 			    //echo "Sorry, your file was not uploaded.";
// 			    $success = 0;
// 			// if everything is ok, try to upload file
// 			} 
// 			else 
// 			{
// 			    if (move_uploaded_file($_FILES["colors"]["tmp_name"][$key], $path)) 
// 			    {
// 			        //echo "The file " . basename( $_FILES["colors"]["name"][$key]) . " has been uploaded.";
// 			        array_push($colorarray, array("name" => $fields['colornames'][$key], "url" => $path));				        
// 			    } 
// 			    else 
// 			    {
// 			        //echo "Sorry, there was an error uploading your file.";
// 			        $success = 0;
// 			    }
// 			}
// 		}
// 	}
// 	unset($value);
// }

	// Check if image file is a actual image or fake image
	if ($_FILES['colors']["name"][0] != "" && $_POST['colorcodes'][0] == "")
	{
		$fields['colors'] = $_FILES['colors'];
		// download color pictures from urls
		foreach ($fields['colors']["error"] as $key => $error)
		{
			if ($fields["colors"]["name"][$key] != "")
			{
				$target_dir = "pictures/";
				$target_file = $target_dir . basename($_FILES["colors"]["name"][$key]);
				$uploadOk = 1;
				$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
				$path = "pictures/" . generateRandomString() . "." . $imageFileType;
				while (file_exists($path))
				{
					$path = "pictures/" . generateRandomString() . "." . $imageFileType;
				}

				if(isset($fields["colors"])) 
				{
				    $check = getimagesize($_FILES["colors"]["tmp_name"][$key]);
				    if($check !== false) {
				        //echo "File is an image - " . $check["mime"] . ".";
				        $uploadOk = 1;
				    } else {
				        //echo "File is not an image.";
				        $uploadOk = 0;
				    }
				}
				// Check file size
				if ($_FILES["colors"]["size"][$key] > 500000) 
				{
				    //echo "Sorry, your file is too large.";
				    $uploadOk = 0;
				}
				// Allow certain file formats
				if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
				&& $imageFileType != "gif" ) 
				{
				    //echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
				    $uploadOk = 0;
				}
				// Check if $uploadOk is set to 0 by an error
				if ($uploadOk == 0) 
				{
				    //echo "Sorry, your file was not uploaded.";
				    $success = 0;
				// if everything is ok, try to upload file
				} 
				else 
				{
				    if (move_uploaded_file($_FILES["colors"]["tmp_name"][$key], $path)) 
				    {
				        //echo "The file " . basename( $_FILES["colors"]["name"][$key]) . " has been uploaded.";
				        array_push($colorarray, array("name" => $fields['colornames'][$key], "url" => $path));				        
				    } 
				    else 
				    {
				        //echo "Sorry, there was an error uploading your file.";
				        $success = 0;
				    }
				}
			}
		}
		unset($value);
	}
	// if no images for colors, use CSS color codes
	else if ($_POST['colorcodes'][0] != "")
	{
		$fields['colors'] = array();
		foreach ($_POST['colorcodes'] as $key => $value) 
		{
			array_push($fields['colors'], array("css" => $_POST['colorcodes'][$key]));
		}
		$fields['colors'] = json_encode($fields['colors']);
	}
	else
	{
		$success = 0;
	}

	$picarray = json_encode($picarray);
	$colorarray = json_encode($colorarray);
	$name = $mysqli->escape_string($fields['name']);
	$manufacturer = $mysqli->escape_string($fields['manufacturer']);
	$type = $mysqli->escape_string($fields['type']);
	$description = $mysqli->escape_string($fields['description']);
	$manurl = $mysqli->escape_string($fields['manurl']);

	if($_POST['identifier'] == "edit")
	{
		$query = "UPDATE Products SET name='$name', manufacturer='$manufacturer', type='$type', picture='$picarray', colors='$colorarray', description='$description', manurl='$manurl' WHERE id=$id";
		print_r($query);

		if($mysqli->query($query))
		{
			//print 'Success! ID of last inserted record is : ' .$query->insert_id .'<br />'; 
			header("Location: databaseSuccess.html");
		}
		else
		{
			header("Location: databaseFailure.html");
			die('Error : ('. $mysqli->errno .') '. $mysqli->error);
		}
	}
	else
	{

		if ($success == 1)
		{
			$query = "INSERT INTO Products (name, manufacturer, type, picture, colors, description, manurl) VALUES (?, ?, ?, ?, ?, ?, ?)";
			$statement = $mysqli->prepare($query);

			if ($_FILES['colors']["name"][0] != "" && $_POST['colorcodes'][0] == "")
			{
				$statement->bind_param('sssssss', $fields['name'], $fields['manufacturer'], $fields['type'], $picarray, $colorarray, $fields['description'], $fields['manurl']);
			}
			else
			{
				$statement->bind_param('sssssss', $fields['name'], $fields['manufacturer'], $fields['type'], $picarray, $fields['colors'], $fields['description'], $fields['manurl']);		
			}
		}
		if($statement->execute())
		{
			//print 'Success! ID of last inserted record is : ' .$statement->insert_id .'<br />'; 
			header("Location: databaseSuccess.html");
		}
		else
		{
			header("Location: databaseFailure.html");
			die('Error : ('. $mysqli->errno .') '. $mysqli->error);
		}
		$statement->close();

	}


?>