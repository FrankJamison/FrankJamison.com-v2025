<?php
if (isset($_POST['submit'])) {

	// Email Variables
	$email_to = "frank@frankjamison.com";
	$email_subject = "FrankJamison.com Contact Form Submission";

	// Form Error Function
	function died($error)
	{

		// Error Code
		echo "We are very sorry, but there were error(s) found with the form you submitted. ";
		echo "These errors appear below.<br /><br />";
		echo $error . "<br /><br />";
		echo "Please go back and fix these errors.<br /><br />";
		die();
	}


	// Check for empty fields
	if (
		!isset($_POST['firstName']) ||
		!isset($_POST['lastName']) ||
		!isset($_POST['emailAddress']) ||
		!isset($_POST['comments'])
	) {
		died('We are sorry, but all form fields are required.');
	}

	// Get posted form data
	$first_name = $_POST['firstName']; // required
	$last_name = $_POST['lastName']; // required
	$email_from = $_POST['emailAddress']; // required
	$comments = $_POST['comments']; // required

	// Error message variable
	$error_message = "";

	// Email RegEx
	$email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';

	// Validate Email
	if (!preg_match($email_exp, $email_from)) {
		$error_message .= 'The Email Address you entered does not appear to be valid.<br />';
	}

	// Names RegEx
	$string_exp = "/^[A-Za-z .'-]+$/";

	// Validate First Name
	if (!preg_match($string_exp, $first_name)) {
		$error_message .= 'The First Name you entered does not appear to be valid.<br />';
	}

	// Validate Last Name
	if (!preg_match($string_exp, $last_name)) {
		$error_message .= 'The Last Name you entered does not appear to be valid.<br />';
	}

	// Validate Comment
	if (strlen($comments) < 2) {
		$error_message .= 'The Comments you entered do not appear to be valid.<br />';
	}

	// Display form error messages
	if (strlen($error_message) > 0) {
		died($error_message);
	}

	// Email MessageVariable
	$email_message = "Form details below.\n\n";

	// Clean form input
	function clean_string($string)
	{
		$bad = array("content-type", "bcc:", "to:", "cc:", "href");
		return str_replace($bad, "", $string);
	}

	$email_message .= "First Name: " . clean_string($first_name) . "\n";
	$email_message .= "Last Name: " . clean_string($last_name) . "\n";
	$email_message .= "Email: " . clean_string($email_from) . "\n";
	$email_message .= "Comments: " . clean_string($comments) . "\n";

	// Create email headers
	$headers = 'From: ' . $email_from . "\r\n" .
		'Reply-To: ' . $email_from . "\r\n" .
		'X-Mailer: PHP/' . phpversion();

	// Send Email
	@mail($email_to, $email_subject, $email_message, $headers);
}
?>