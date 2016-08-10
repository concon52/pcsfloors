<?php

	// connect to database
	$mysqli = new mysqli('mysqlcluster9.registeredsite.com','pcsfloors','Padpimp1','padpimp');

	if ($mysqli->connect_error) 
	{
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	$id = $_POST['id'];
	$pics = "SELECT * FROM Products WHERE id = $id";
	$result = mysqli_query($mysqli, $pics);
	$row = mysqli_fetch_assoc($result);
	$picarray = json_decode($row['picture']);
	$colorarray = json_decode($row['colors']);

	foreach ($picarray as $value) 
	{
		unlink($value);
	}
	foreach ($colorarray as $value)
	{
		unlink($value->{'url'});
	}

	// sql to delete a record
	$sql = "DELETE FROM Products WHERE id=$id";

	if ($mysqli->query($sql) === TRUE) 
	{
		header("Location: databaseSuccess.html");
	    //echo "Record deleted successfully";
	} 
	else 
	{
		header("Location: databaseFailure.html");
	    //echo "Error deleting record: " . $conn->error;
	}

?>