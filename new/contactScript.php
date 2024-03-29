<?php

$from = 'Contact form from PCS Website <info@pcsdistributors.com>';
$sendTo = 'info <info@pcsdistributors.com>';
$subject = 'New message from pcsdistributors.com';
$fields = array('name' => 'Customer name', 'surname' => 'Surname', 'phone' => 'Phone', 'email' => 'Email', 'message' => 'Message');

$okMessage = 'Contact form successfully submitted. Thank you, we will get back to you soon!';
$errorMessage = 'There was an error while submitting the form. Please try again later';

try
{
	$emailText = "You have a new message from pcsdistributors.com.\n=============================\n";
	foreach($_POST as $key => $value)
	{
		if (isset($fields[$key]))
		{
			$emailText .= "$fields[$key]: $value\n";
		}
	}

	mail($sendTo, $subject, $emailText, "From: " . $from);

	$responseArray = array('type' => 'success', 'message' => $okMessage);
}

catch (Exception $e)
{
	$responseArray = array('type' => 'danger', 'message' => $errorMessage);
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
{
    $encoded = json_encode($responseArray);
    
    header('Content-Type: application/json');
    
    echo $encoded;
}

else 
{
    echo $responseArray['message'];
}
