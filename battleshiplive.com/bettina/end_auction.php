<?php

    // require common code
    require_once("includes/common.php");
	
	// ensure valid syntax
	if ($argc != 3)
		exit("syntax: end_auction.php auction password\n");
	// exit if password is wrong
	password($argv[2]);
	$auction = $argv[1];
	
	// find all items in that auction
		$sql = sprintf("SELECT * FROM `%s` JOIN users ON users.uid=`%s`.topbidder AND `%s`.topbidder IS NOT NULL", 
		$auction, $auction, $auction);
	
	$item_info = mysql_query($sql); 
	if ((!$item_info) || mysql_num_rows($item_info) == 0)
		exit("Error in end_auction.php: Either no-one bid on anything, 
		or the auction that was supposed to end doesn't exist anymore!");	
	
	

	
	$admin_email = "Dear Bettina Network\n
	The auction with name " . $auction . " has ended.
	The following is a list of the people who won items, their item, and the price \n\n";
	
	// for each item, find the max bidder, and send him an email.
	while($row = mysql_fetch_array($item_info))
	{
		$msg = sprintf("Dear %s\n
Congratulations! You have won a bid on Bettina's Network.
The auction you bid under was: %s
The item you bid on was: %s, item # %s
and the price you bid was: $%.2f.

To pick up your purchase, You can either come to pick it up from the 
auctioning site, or you can call Bettina's Network
at %s to come arrange shipping to an address of your choosing.

Thank you for working with us
The Bettina team", $row["username"], $auction, $row["item_name"], $row["item_id"], $row["currentprice"], ADMIN_PHONE);
	
	// mail the user 
	mail($row["email"], "You have won a bid",
		$msg, "From: bettina-network@comcast.net" );
	
	$admin_email = $admin_email . sprintf("%s \t %s \t #%s \t $%.2f \n", $row["username"], $row["item_name"], $row["item_id"], $row["currentprice"]);
	}
	
	// send admin an email with a list of people who won auction items
	$admin_email = $admin_email . "\n thanks \n \n the Bettina Team";
	mail(ADMIN_EMAIL, "Auction " . $auction . " has ended.",
		$admin_email, "From: bettina-network@comcast.net" );

	// provide output so that the activity is logged on the server.
exit("The following auction has ended: $auction \n All winners of items have been notified." . $msg);  
