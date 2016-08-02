<?php

	// 	HTML Template
	// $fields = array('name' => $_POST['name'], 
	// 				'manufacturer' => $_POST['manufacturer'], 
	// 				'type' => $_POST['type'], 
	// 				'picture' => $_POST['picture'], 
	// 				'colors' => $_POST['colors'], 
	// 				'id' => $_POST['id'], 
	// 				'description' => $_POST['description'], 
	// 				'manurl' => $_POST['manurl']);

	//	TEST
	// $fields = array('name' => 'test1', 
	// 				'manufacturer' => 'test2', 
	// 				'type' => 'test3', 
	// 				'picture' => 'test4', 
	// 				'colors' => 'test5', 
	// 				'id' => 100, 
	// 				'description' => 'test7', 
	// 				'manurl' => 'test8');


	// function to grab picture from url
	function imageCreateFromFile( $filename ) 
	{
	    {
	        case 'jpeg':
	        case 'jpg':
	            return imagecreatefromjpeg($filename);
	        	break;

	        case 'png':
	            return imagecreatefrompng($filename);
	        	break;

	        case 'gif':
	            return imagecreatefromgif($filename);
	        	break;

	        default:
	            throw new InvalidArgumentException('File "'.$filename.'" is not valid jpg, png or gif image.');
	       		break;
	    }
	}

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

	// grab json obj from post (/scrapers/scraperHelpers.py)
	//$jsonobj = json_decode($_POST['json']);
	$jsonobj = json_decode(file_get_contents('php://input'));
	print_r($jsonobj);

	// download pictures from urls
	foreach ($jsonobj['picture'] as &$value)
	{
		$picture = imageCreateFromFile($value);
		$path = "/pictures/" . generateRandomString() . ".jpg";
		while (file_exists($path))
		{
			$path = "/pictures/" . generateRandomString() . ".jpg";
		}
		imagejpeg($picture, $path);
		$value = $path;
	}
	unset($value);


	foreach ($jsonobj['colors'] as &$value)
	{
		$colorpicture = imageCreateFromFile($value['url']);
		$colorpath = "/pictures/" . generateRandomString() . ".jpg";
		while (file_exists($colorpath))
		{
			$colorpath = "/pictures/" . generateRandomString() . ".jpg";
		}
		imagejpeg($colorpicture, $colorpath);
		$value['url'] = $colorpath;
	}
	unset($value);

	// connect to database
	$mysqli = new mysqli('mysqlcluster9.registeredsite.com','pcsfloors','Padpimp1','padpimp');

	if ($mysqli->connect_error) 
	{
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	// create query
	$query = "INSERT INTO Products VALUES(?, ?, ?, ?, ?, ?, ?)";
	$statement = $mysqli->prepare($query);

	$statement->bind_param('sssssss', $jsonobj['name'], $jsonobj['manufacturer'], $jsonobj['type'], $jsonobj['picture'], $jsonobj['colors'], $jsonobj['description'], $jsonobj['manurl']);

	// sumbit query
	if($statement->execute())
	{
    	print 'Success! ID of last inserted record is : ' .$statement->insert_id .'<br />'; 
	}
	else
	{
    	die('Error : ('. $mysqli->errno .') '. $mysqli->error);
    }
	$statement->close();

?>