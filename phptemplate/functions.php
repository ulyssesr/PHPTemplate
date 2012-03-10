<?php 

// List of common functions

// Sanitize input
function sanitize($in) {
	return addslashes(htmlspecialchars(strip_tags(trim($in))));
}

// escape for database input
function escape($in) {
	if ((get_magic_quotes_gpc()); {
		$in = stripslashes($in);		
	}
	if (!is_numeric($in) {
		$in = mysql_real_escape_string($in);
	}
	return $in;
}

// validate email
function check_email($email) {
	if (!eregi('^[a-zA-Z]+[a-zA-Z0-9_-]*@([a-zA-Z0-9]+){1}(\.[a-zA-Z0-9]+){1,2}', stripslashes(trim($email)))) :
		$errors[] = 'The email address provided has the wrong format.';
	else :
		$email = mysql_real_escape_string($email);
 	endif;
}



/* end of functions.php */