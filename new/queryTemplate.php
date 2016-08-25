<?php

	$filetype;

	ini_set("user_agent", 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36');

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
	        	echo "\n\n\n" . $filetype . "\n\n\n";
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
	function imageOutputToFile( $filename, $path, $type ) 
	{
	    switch ($type)
	    {
	        case 'jpeg':
	        case 'jpg':
	         	header('Content-Type: image/jpeg');
	            return imagejpeg($filename, $path, 100);
	        	break;

	        case 'png':
	        	header('Content-Type: image/png');
	            return imagepng($filename, $path, 0);
	        	break;

	        case 'gif':
	         	header('Content-Type: image/gif');
	            return imagegif($filename, $path);
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
		str_replace("&quot;", "", $value);
		$picture = imageCreateFromFile($value);
		$path = "pictures/" . generateRandomString() . "." . $filetype;
		while (file_exists($path))
		{
			$path = "pictures/" . generateRandomString() . "." . $filetype;
		}
		imageOutputToFile($picture, $path, $filetype);
		$value = $path;
	}
	unset($value);

	// download color pictures from urls
	foreach ($jsonobj->{'colors'} as &$value)
	{
		$colorpicture = imageCreateFromFile($value->{'url'});
		$colorpath = "pictures/" . generateRandomString() . "." . $filetype;
		while (file_exists($colorpath))
		{
			$colorpath = "pictures/" . generateRandomString() . "." . $filetype;
		}
		imageOutputToFile($colorpicture, $colorpath, $filetype);
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