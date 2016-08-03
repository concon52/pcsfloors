<?php

	$filetype;

	// function to grab picture from url
	function imageCreateFromFile( $filename ) 
	{
		global $filetype;
		if (strpos($filename, '?'))
		{
			$filetype = strtolower( array_pop( explode('.', substr($filename, 0, strpos($filename, '?')))));
		}
		else
		{
			$filetype = strtolower( array_pop( explode('.', $filename)));
		}
	    switch ($filetype)
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

	// function to output picture to save location
	function imageOutputToFile( $filename, $path ) 
	{
		global $filetype;
	    switch ($filetype)
	    {
	        case 'jpeg':
	        case 'jpg':
	         	header("Content-type: image/jpeg");
	            return imagejpeg($filename, $path, 100);
	        	break;

	        case 'png':
	        	header("Content-type: image/png");
	            return imagepng($filename, $path, 100);
	        	break;

	        case 'gif':
	         	header("Content-type: image/gif");
	            return imagegif($filename, $path, 100);
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

	$jsonobj = json_decode(file_get_contents('php://input'));
	print_r($jsonobj);

	// download pictures from urls
	foreach ($jsonobj->{'picture'} as &$value)
	{
		echo($value . "prestrip");
		str_replace("&quot;", "", $value);
		echo($value . "poststrip");
		$picture = imageCreateFromFile($value);
		$path = "/pictures/" . generateRandomString() . ".jpg";
		while (file_exists($path))
		{
			$path = "pictures/" . generateRandomString() . ".jpg";
		}
		imageOutputToFile($picture, $path);
		$value = $path;
	}
	unset($value);

	// download color pictures from urls
	foreach ($jsonobj->{'colors'} as &$value)
	{
		$colorpicture = imageCreateFromFile($value->{'url'});
		$colorpath = "pictures/" . generateRandomString() . ".jpg";
		while (file_exists($colorpath))
		{
			$colorpath = "/pictures/" . generateRandomString() . ".jpg";
		}
		imageOutputToFile($colorpicture, $colorpath);
		$value->{'url'} = $colorpath;
	}
	unset($value);

	// connect to database
	$mysqli = new mysqli('mysqlcluster9.registeredsite.com','pcsfloors','Padpimp1','padpimp');

	if ($mysqli->connect_error) 
	{
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	// create query
	$query = "INSERT INTO Products (name, manufacturer, type, picture, colors, description, manurl) VALUES (?, ?, ?, ?, ?, ?, ?)";
	$statement = $mysqli->prepare($query);

	var_dump($statement);
	$statement->bind_param('sssssss', $jsonobj->{'name'}, $jsonobj->{'manufacturer'}, $jsonobj->{'type'}, json_encode($jsonobj->{'picture'}), json_encode($jsonobj->{'colors'}), $jsonobj->{'description'}, $jsonobj->{'manurl'});

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