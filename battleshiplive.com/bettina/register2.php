<?php

    // require common code
    require_once("includes/common.php"); 

    // checks that username exists and passwords exist and are equal
    if ($_POST["password"] != $_POST["password2"])
        apologize("You inputed two different passwords!");
    
    if ($_POST["username"] == NULL || $_POST["password"] == NULL
            || $_POST["password2"] == NULL || $_POST["firstname"] == NULL 
			|| $_POST["lastname"] == NULL || $_POST["phone"] == NULL)
        apologize("You must fill in each field");
	
	// check that the email is a valid email format
	if((preg_match("/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/",                   
        trim($_POST["email"])) == 0))
		apologize("That is not a valid email address");
	$mailcheck = spamcheck($_REQUEST['email']);
	if ($mailcheck==FALSE)
		apologize("That is not a valid email address");    
    // escape username and password for safety
    $username = mysql_real_escape_string($_POST["username"]);
    $password = mysql_real_escape_string($_POST["password"]);
	$email = mysql_real_escape_string($_POST["email"]);
	$firstname = mysql_real_escape_string($_POST["firstname"]);
	$lastname = mysql_real_escape_string($_POST["lastname"]);
	$phone = mysql_real_escape_string($_POST["phone"]);
	$receive_email = mysql_real_escape_string($_POST["receive_email"]);
	if ($receive_email)
		$receive_email = 1;
	//apologize($receive_email);

    // prepare SQL
    $sql = sprintf("INSERT INTO users (username, password, email, firstname, lastname, phone, receive_email) 
        VALUES('%s', '%s', '%s', '%s', '%s','%s', '%s')", $username, $password, $email, $firstname, $lastname, $phone, $receive_email);
	//apologize($sql);

    // execute query
    if ($result = mysql_query($sql) == FALSE)
        apologize("username already in use");
	
	$contact = WEBSITE_ADDRESS . "contact.php";
	$oursite = WEBSITE_ADDRESS . "index.php";
	// send an email telling them to confirm their credit cards.
	$message = "Welcome to Bettina's Network! 
		
Your username is $username, and your password is $password. 
In order to help maintain your privacy, we request that you contact Bettina's Network directly to supply your credit card information. 
Our contact information can be found here:
$contact. 
Once your information has been confirmed, you will be sent a confirmation email that grants you permission to bid in the auctions.
You can revist our website at any time, by going to:
$oursite.
	
Thank you for being a part of our community.

sincerely

The Bettina Team";

	mail($email, "Welcome to Bettina's Network",
	$message, "From: bettina-network@comcast.net" );

    // redirects to index.php if it can find uid
    $_SESSION["uid"] = mysql_insert_id();
	$_SESSION["name"] = $username;
	
		$screen_message = "Welcome to Bettina's Network, $username! <br/><br/> 

In order to help maintain your privacy, we request that you <a href=\"contact.php\">contact</a> Bettina's Network directly to supply your credit card information. <br/>
Once your information has been confirmed, you will be sent a confirmation email that grants you permission to bid in the auctions
<br/><br/>
sincerly
<br/><br/>
The Bettina Team";
	
	$msg = $screen_message . "<br/><br/> Note that we have also sent this information to you via email";
	congratulate($msg, "Return to main page", "index.php");

    // else report error
    // else
        // apologize("Invalid username and/or password!");

?>
