<?php

	// connect to database
	$mysqli = new mysqli('mysqlcluster9.registeredsite.com','pcsfloors','Padpimp1','padpimp');

	if ($mysqli->connect_error) 
	{
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	$id = $_POST['id'];

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