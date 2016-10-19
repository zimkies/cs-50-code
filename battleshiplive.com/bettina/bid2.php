<?php
	
	//////////////////////////////////////

    // require common code
    require_once("includes/common.php"); 
	permission_check();

	// check if any input at all.
 
    // escape username and password for safety
    $bidprice = mysql_real_escape_string($_POST["bidprice"]);
    $auction = mysql_real_escape_string($_POST["auction"]);
	$item = mysql_real_escape_string($_POST["item"]);
	
	// make sure that this item is still available.
	$sql = sprintf("SELECT * FROM `%s` JOIN auctions ON auctions.end >= NOW() AND auctions.name='%s' AND `%s`.item_name='%s'", 
		$auction, $auction, $auction, $item);
    $item_info = mysql_query($sql);   
	if ((!$item_info) || mysql_num_rows($item_info) == 0)
		apologize("This auction either does not exist, or is no longer available for bidding!");

    // make sure that the bid is as high as it should be
	$row = mysql_fetch_array($item_info);
	if (!$row)
		apologize("The specified item is not available");
	if (min_bid($row["currentprice"]) > $bidprice)
		apologize("You have to bid at least $". min_bid($row["currentprice"]));
	
	// update bidding table
	$sql = sprintf("INSERT INTO `%s_bids` VALUES ('%s', %d, %.2f, NOW(), '%s')", 
		$auction, $item, $row["item_id"], $bidprice, $_SESSION["name"]);
	if(!mysql_query($sql))
		apologize("There was an error in your bid");
	
	// update the table of the auction
	$sql = sprintf("UPDATE `%s` SET currentprice=%f, bids=bids+1, topbidder='%s' WHERE item_name='%s'",
		$auction, $bidprice, $_SESSION["uid"], $item);

	if(!mysql_query($sql))
		apologize("There was an error in your bid");
	
	// send an email telling them what they have bid.
	$sql = sprintf("SELECT email, firstname FROM users WHERE username='%s'", 
		$_SESSION["name"]);
	$row = mysql_query($sql);
	if (!$row)
		apologize("There was an error in finding your information");
	$row = mysql_fetch_array($row);
	if (!$row)
		apologize("There was an error in finding your information");
		
	$message = sprintf("Dear %s\n
Thank you for bidding on Bettina's Network.
The auction you bid under was: %s
The item you bid on was: %s, item # %d
and the price you bid was: $%.2f", 
	$row["firstname"], $auction, $item, $row["item_id"], $bidprice);

$screen_message = sprintf("Dear %s<br /><br />
Thank you for bidding on Bettina's Network.<br />
The auction you bid under was: %s<br />
The item you bid on was: %s, item # %d<br />
and the price you bid was: $%.2f<br /><br />",
	$row["firstname"], $auction, $item, $row["item_id"], $bidprice);
	
	if ($outbid)
		$message = $message . 
"You have requested to be notified by email within 10 minutes of 
the end of the auction if someone outbids you.";
	
	// if ($extend)
		// $message = $message + 
		// "If there is any bidding on this item in the last 10 minutes of the \n
	// auction, the auction on this item will be extended for another 30\n
	// minutes.\n" 
	
	$message =  $message .
"
If you win the auction, we will send another email to congratulate you.
Thanks again for bidding at a Bettina Network Sale!\n
The Bettina Team";

$screen_message = $screen_message . 
"
If you win the auction, we will send another email to congratulate you.<br />
Thanks again for bidding at a Bettina Network Sale!<br /><br />
The Bettina Team";

	mail($row["email"], "You have placed a bid on Bettina's Network",
		$message, "From: " . ADMIN_EMAIL);

    // redirects to index.php
	congratulate($screen_message, "Return to the main page", "index.php");
?>
