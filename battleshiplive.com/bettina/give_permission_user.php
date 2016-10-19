<?php

    // require common code
    require_once("includes/common.php");
	admin_check();
	
	// get user info
	$sql = sprintf("SELECT * FROM users WHERE uid=%d", $_GET["uid"]);
    $users = mysql_query($sql);   
	$row = mysql_fetch_array($users);
	if(!$row)
		apologize("There was an error in trying to edit this person");

// give them permission
$sql = sprintf("UPDATE users SET permission=1 WHERE uid=%d", $_GET["uid"]);
if(!mysql_query($sql))   
	apologize("There was an error in trying to give permissions to this person");

// send an email telling them to confirm their credit cards.
//check if the email address is invalid
$mailcheck = spamcheck($row['email']);
// if person was given permission, send them an email.
if ($mailcheck==FALSE)
	apologize("That is not a valid email address");

//send email
	$website = WEBSITE_ADDRESS . "index.php";
	$message = "Dear " . $row['username']. "	\n
You have been granted permission to bid on Bettina Network:
$website
To place a bid, simply log into our site, select an auction, and click on any item you wish 
to place a bid on. You can then enter the amount you wish to bid. 
Thank you for working with us
\n
The Bettina Team";
	mail($row['email'], "Bettina Network: You have been granted bidding priveleges",
	$message, "From: bettina-network@comcast.net" );

$msg_to_admin = "You successfully gave permissions to user " . $row['username']. 
" with id " . $_GET["uid"];
congratulate($msg_to_admin, "Return to Admin page", "admin_index.php");


	
	
?>