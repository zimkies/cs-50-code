<?php

    /***********************************************************************
     * helpers.php
     *
     * Computer Science 50
     * Problem Set 7
     *
     * Helper functions.
     **********************************************************************/


    /*
     * void
     * apologize($message)
     *
     * Apologizes to user by displaying a page with message.
     */

    function apologize($message)
    {
        $return_msg = "back";
		$type = "Sorry!";
		// require template
        require_once("apology.php");

        // exit immediately since we're apologizing
        exit;
    }
	
	function congratulate($message, $return_msg, $return_address)
    {
		$type = "Thank you!";
		// require template
        require_once("apology.php");

        // exit immediately since we're apologizing
        exit;
    }
	
	function msg_corrupted($message, $script)
	{
		$type = "Database Error";
		
		// email the admin that an error occured
		$email_message = 
		"Dear Bettina Network Admin.\n
Recent activity from Bettina's network auctioning site resulted in 
inconsistencies in the database. Please contact the systems administrator ASAP to fix this.\n
This error arose while executing the script " . $script . ", and output the 
following error:\n\n"
. $message . "\n"
. "thanks \n
The Bettina Team";
		mail(ADMIN_EMAIL, "Bettina Network Error", $email_message, "from: " . ADMIN_EMAIL);
		
		// now just apologize to the screen
		$message = $message 
		. "<br/><br/>The last query may have left the database inconsistent.<br/>
			Please contact the systems administrator ASAP to fix this. <br/>";
		require_once("apology.php");
	}
	


    /*
     * void
     * dump($variable)
     *
     * Facilitates debugging by dumping contents of variable
     * to browser.
     */

    function dump($variable)
    {
        // dump variable using some quick and dirty (albeit invalid) XHTML
        if (!$variable && !is_numeric($variable))
            print("Variable is empty, null, or not even set.");
        else
            print("<pre>" . print_r($variable, true) . "</pre>");

        // exit immediately so that we can see what we printed
        exit;
    }

    /*
     * void
     * redirect($destination)
     * 
     * Redirects user to destination, which can be
     * a URL or a relative path on the local host.
     *
     * Because this function outputs an HTTP header, it
     * must be called before caller outputs any XHTML.
     */

    function redirect($destination)
    {
        $host = $_SERVER["HTTP_HOST"];
		$path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
		
		// handle URL
        if (preg_match("/^http:\/\//", $destination))
            header("Location: " . $destination);

        // handle absolute path
        else if (preg_match("/^\//", $destination))
            header("Location: http://$host$destination");

        // handle relative path
        else
            // adapted from http://www.php.net/header
            header("Location: http://$host$path/$destination");

        // exit immediately since we're redirecting anyway
        exit;
    }
	
	// boolean function, escapes an email, and checks if it is valid.
	function spamcheck($field)
	  {
	  //filter_var() sanitizes the e-mail 
	  //address using FILTER_SANITIZE_EMAIL
	  $field=filter_var($field, FILTER_SANITIZE_EMAIL);
	  
	  //filter_var() validates the e-mail
	  //address using FILTER_VALIDATE_EMAIL
	  if(filter_var($field, FILTER_VALIDATE_EMAIL))
		{
		return TRUE;
		}
	  else
		{
		return FALSE;
		}
	  }
	  
	// require authentication
    function require_login($redirect = "index.php")
	{
		if (!preg_match("/(:?log(:?in|out)|register)\d*\.php$/", $_SERVER["PHP_SELF"]))
		{
			if (!isset($_SESSION["uid"]))
				redirect("/" . HOME_FOLDER . "login.php?redirect=" . $redirect);
		}
	}
	
	// check if person has permission to bid
	function permission_check($redirect = "index.php")
	{
		// make sure they are logged in
		require_login($redirect);
		
		// makes sure they have permissions
		$sql = sprintf("SELECT permission FROM users WHERE uid=%d", 
		$_SESSION["uid"]);
		
		// apologize if they are not.
		$perm = mysql_fetch_row(mysql_query($sql));
		if ($perm[0] == 0)
			apologize("You must be logged in and have bidding priveleges in order to do this");
	}
		
	
	// check if person is admin
	function admin_check($redirect = "index.php")
	{
		// makes sure they are logged in:
		require_login($redirect);
		
		// makes sure they are admin
		$sql = sprintf("SELECT admin FROM users WHERE uid=%d", 
		$_SESSION["uid"]);
		
		// apologize if they are not.
		$admin = mysql_fetch_row(mysql_query($sql));
		if ($admin[0] == 0)
			apologize("You do not have administrator priveleges");
	}
	
	// check if password is correct
	function password($pass)
	{
		if (strcmp($pass, AUCTION_PASSWORD))
			exit("Wrong password");
	}
	
	// calculate the minimum bid
	function min_bid($price)
	{
		return min($price + 10, max(ceil(1.1*$price),1));
	}

	// checks if a string is a date of type "YYYY-MM-DD HH:MM:SS"
	function check_time($time)
	{
		$components = explode(' ', $time);
		$date = explode('-', $components[0]);
		$clock = explode(':', $components[1]);

		// This is really annoying, but we have to check whether each array has the right number of digits, since the next line converts each number to an int.
		if ((strlen($date[0]) != 4) || (strlen($date[1]) != 2) || (strlen($date[2]) != 2) ||
			(strlen($clock[0]) != 2) || (strlen($clock[1]) != 2) || (strlen($clock[2]) != 2))
			return false;
		
		// now make sure each part of the datetime is valid.
		else if (!checkdate((int)$date[1], (int)$date[2],(int)$date[0]))
			return false;
		else if ((int)$clock[0] > 24 || (int)$clock[0] < 0)
			return false;
		else if ((int)$clock[1] > 60 || (int)$clock[1] < 0)
			return false;
		else if ((int)$clock[2] > 60 || (int)$clock[2] < 0)
			return false;
		else 
			return true;
	}
	
	// takes a full path and a file name, and the fieldname of the picture from
	// a web form and uploads the picture, or redirecting to apology 
	// if failure.
	function upload_picture($path, $name, $fieldname)
	{
		// possible PHP upload errors
		$errors = array(1 => 'php.ini max file size exceeded',
						2 => 'html form max file size exceeded',
						3 => 'file upload was only partial',
						4 => 'no file was attached');
					
		// check for PHP's built-in uploading errors
		if ($_FILES[$fieldname]['error'] != 0)
			return $errors[$_FILES[$fieldname]['error']];
			
		// check that the file we are working on really was the subject 
		//of an HTTP upload
		if (!@is_uploaded_file($_FILES[$fieldname]['tmp_name']))
			return 'not an HTTP upload';
			
		// validation... since this is an image upload script we should run a check  
		// to make sure the uploaded file is in fact an image. Here is a simple check:
		// getimagesize() returns false if the file tested is not an image.
		if (!@getimagesize($_FILES[$fieldname]['tmp_name']))
			return 'only image uploads are allowed';
			
		// make a unique filename for the uploaded file and check it is not already
		// taken... if it is already taken keep trying until we find a vacant one
		// sample filename: 1140732936-filename.jpg

		$uploadFilename = $path . $name;
		if (file_exists($uploadFilename))
			return "A picture for this file already exists as " . $uploadFilename;

		// now let's move the file to its final location and allocate the new filename to it
		if (!@move_uploaded_file($_FILES[$fieldname]['tmp_name'], $uploadFilename))
			return 'receiving directory insuffiecient permission';
		
		return false;
	}
	
	// get_append($adr, $var, $val) appends the $var with the $val to an address, 
	// replacing the $var if it is already present
	function get_append($adr, $var, $val)
	{
		 $pattern = "/(\?|\&)" . $var . "=.*?(\&|$)/";
		$erased = preg_replace($pattern, '' , $adr);
		//apologize($erased);
		$remove_end = preg_replace("/(\.php)\?.*$/", '$1' , $adr);
		// if there are no Get variables in the url
		if ($erased == $remove_end)
			$erased = $erased . "?";
		else 
			$erased = $erased . "&";
		return $erased . $var . "=" . $val; 
	}
?>
