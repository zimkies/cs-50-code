<?php

	/***********************************************************************
     * delete_item.php
     *
     * Deletes an item from an auction, obtaining the item id and the auction
	 * name by GET method, and then deletes all entries in auction_bids and
	 * auction that have that item id. 
     **********************************************************************/
	 
	 
    // require common code
    require_once("includes/common.php");
	admin_check();
	
	// get the name of the auctions/times and escape them.
	$auction = mysql_real_escape_string($_GET["auction"]);
	$item_uid = mysql_real_escape_string($_GET["item_id"]);		
	
	// delete rows from the auction table
	$sql = sprintf("DELETE FROM `%s` WHERE item_id=%d", 
	$auction, $item_uid);
	// delete from the auction_bids table
	$sql2 = sprintf("DELETE FROM `%s` WHERE item_id=%d", 
	$auction . "_bids", $item_uid);
	if (!@mysql_query($sql))
		apologize("It was not possible to delete the item with id " . $item_uid . " from the auction " . $auction);	
	
	// If this fails then database is inconsistent.
	if (!@mysql_query($sql2))
	{
		$error = "It was not possible to delete the item with id " . $item_uid 
		. " from the auction " . $auction . ". Rows from the table " . $auction . " were deleted, but not from the relavant _bids table. Manually remove the rows from the bidding table.";
		msg_corrupted($error, $_SERVER['REQUEST_URI']);	
	}
	
	$msg_to_admin = "You successfuly deleted the item with id " . $item_uid . " from the auction " . $auction;
	congratulate($msg_to_admin, "Return to Admin page", "admin_index.php");
?>