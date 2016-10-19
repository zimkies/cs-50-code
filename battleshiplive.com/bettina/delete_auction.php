<?php

    // require common code
    require_once("includes/common.php");
	require_once("includes/cronfunctions.php");
	admin_check();
	
	// select all info to do with this table
	$sql = sprintf("SELECT * FROM auctions WHERE name='%s'", $_GET["auction"]);

	$auction_return = mysql_query($sql);
	if(!$auction_return)   
		apologize("There was something wrong with the database query");
	$row = mysql_fetch_array($auction_return);
	if (!$row)
		apologize("This Auction does not exist");
	
	// delete from auctions
    $sql = sprintf("DELETE FROM auctions WHERE name='%s'", $_GET["auction"]);
    if(!mysql_query($sql))   
		apologize("There was an error in trying to delete this auction");
	
	// delete auction table
	$sql = sprintf("DROP TABLE IF EXISTS `%s`", $_GET['auction']);
//	apologize($sql);
	if(!mysql_query($sql))   
		apologize("There was an error in trying to delete this auction table .Contact system Admin, since the database may now be inconsistent");
	
	// delete the auction bids history table
	$sql = sprintf("DROP TABLE IF EXISTS `%s`", $_GET['auction'] . '_bids');
	if(!mysql_query($sql))   
		apologize("There was an error in trying to delete this the bidding history for this table. Contact system Admin, since the database may now be inconsistent");
	
	// delete all the images associated with the auction
	//apologize("rmdir -r '" . HOME_PATH . "images/" . $_GET["auction"] . "'");
	shell_exec("rm -r '" . HOME_PATH . "images/" . $_GET["auction"] . "'");
	shell_exec("rm -f '" . HOME_PATH . $row["minpic"] . "'");
	shell_exec("rm -f '" .  HOME_PATH . $row["bigpic"] . "'");
	
	// finally, delete the scheduled job created by this auction
	remove_scheduled_job($row["jobnum"]);
	
	
	
	$msg_to_admin = "You successfuly deleted the auction with name " . $_GET["auction"];
	congratulate($msg_to_admin, "Return to Admin page", "admin_index.php");
?>