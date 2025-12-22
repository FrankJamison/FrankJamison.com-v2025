<?php
// Field Validation Function
function fieldValidation($regex, $my_string)
{
	if (preg_match($regex, $my_string)) {
		return $my_string;
	} else {
		return false;
	}
} // End function fieldValidation($regex, $my_string)

// Clean String
function clean_string($string)
{
	$bad = array("content-type", "bcc:", "to:", "cc:", "href");
	return str_replace($bad, "", $string);
} // End function clean_string($string)



?>