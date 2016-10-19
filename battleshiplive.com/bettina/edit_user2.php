<?php

    // require common code
    require_once("includes/common.php"); 
	admin_check();
    //makes sure each input was put in
    if ($_POST["username"] == NULL
            || $_POST["firstname"] == NULL || $_POST["uid"] == NULL 
			|| $_POST["lastname"] == NULL || $_POST["phone"] == NULL
			|| $_POST["permission"] == NULL || $_POST["old_permission"] == NULL)
        apologize("You must fill in each field");
	
	// check that the email is a valid email format
	if((preg_match("/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/",                   
        trim($_POST["email"])) == 0))
		apologize("That is not a valid email address");
    
    // escape username and password for safety
	$uid = mysql_real_escape_string($_POST["uid"]);
    $username = mysql_real_escape_string($_POST["username"]);
	$email = mysql_real_escape_string($_POST["email"]);
	$firstname = mysql_real_escape_string($_POST["firstname"]);
	$lastname = mysql_real_escape_string($_POST["lastname"]);
	$phone = mysql_real_escape_string($_POST["phone"]);
	$permission = mysql_real_escape_string($_POST["permission"]);
	$old_permission = mysql_real_escape_string($_POST["old_permission"]);

    
    // prepare SQL
    $sql = sprintf("UPDATE users SET username='%s', email='%s', firstname='%s', lastname='%s', phone='%s', permission=%d WHERE uid=%d", $username, $email, $firstname, $lastname, $phone, $permission, $uid);

    // execute query
    if ($result = mysql_query($sql) == FALSE)
        apologize("Problem editing user info. Either the new username is invalid or already taken, or the permission value is wrong");
	
	// send an email telling them credit cards have been confirmed
	//check if the email address is invalid
	$mailcheck = spamcheck($_POST['email']);
	// if person was given permission, send them an email.
	if ($old_permission != 0 || $permission != 1);
	else if ($mailcheck==FALSE)
		apologize("That is not a valid email address");
	else
	{//send email
	$website = WEBSITE_ADDRESS . "index.php";
	$message = "Dear $username \n
You have been granted permission to bid on Bettina Network:
$website

To place a bid, simply log into our site, select an auction, and click on any item you wish to place a bid on. 
You can then enter the amount you wish to bid. 
Thanks again for being a part of our  community
\n
The Bettina Team";
	$msg_to_admin = "You have granted bidding privileges to user $username \n";
	mail($email, "Bettina Network: You have been granted bidding priveleges",
	$message, "From: bettina-network@comcast.net" );
	}
	

	$msg_to_admin = sprintf($msg_to_admin . '%s now has the following information: 
	
<table  cellspacing="0" class="outertable">
				<tr><td>
				<table align="center" cellspacing="0" class="innertable">
					<tr>
						<th>Uid</th>
						<th>Username</th>			
						<th>Email</th>
						<th>Firstname</th>
						<th>Lastname</th>			
						<th>Phone</th>
						<th>Permission (1 for yes, 0 for no)</th>
						
					</tr>
					<tr>
						<td>%s</td>
						<td>%s</td>
						<td>%s</td>
						<td>%s</td>
						<td>%s</td>
						<td>%s</td>
						<td>%s</td>
					</tr>				
				</table></td></tr></table>', $username,
				$uid, $username, $email, $firstname, $lastname, $phone, $permission);
	congratulate($msg_to_admin, "Return to Admin page", "admin_index.php");

?>
