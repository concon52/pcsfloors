<?php

	$fields = array('name' => $_POST['name'], 
					'manufacturer' => $_POST['manufacturer'], 
					'type' => $_POST['type'], 
					'picture' => $_POST['picture'], 
					'colors' => $_POST['colors'], 
					'id' => $_POST['id'], 
					'description' => $_POST['description'], 
					'manurl' => $_POST['manurl']);

	//	 TEST
	// $fields = array('name' => 'test1', 
	// 				'manufacturer' => 'test2', 
	// 				'type' => 'test3', 
	// 				'picture' => 'test4', 
	// 				'colors' => 'test5', 
	// 				'id' => 100, 
	// 				'description' => 'test7', 
	// 				'manurl' => 'test8');

	$mysqli = new mysqli('mysqlcluster9.registeredsite.com','pcsfloors','Padpimp1','padpimp');

	if ($mysqli->connect_error) 
	{
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	$query = "INSERT INTO Products VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
	$statement = $mysqli->prepare($query);

	$statement->bind_param('sssssdss', $fields['name'], $fields['manufacturer'], $fields['type'], $fields['picture'], $fields['colors'], $fields['id'], $fields['description'], $fields['manurl']);

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