<?php
	
	/****************************************************************
	 make_auction.php creates a table for that auction with a
	 list of names, prices, etc
	 ***************************************************************/

    // require common code, check for admin
    require_once("includes/common.php");
	admin_check();

	// get the name of the auctions/times and escape them.
	$auction = mysql_real_escape_string($_POST["auction"]);
	$item = mysql_real_escape_string($_POST["item"]);	
    $item_id = mysql_real_escape_string($_POST["item_id"]);	
	$description = mysql_real_escape_string($_POST["description"]);
	
	// obtain old info for the auction
	$sql = sprintf("SELECT * FROM `%s` WHERE item_id=%d", $auction, $item_id);
    $users = mysql_query($sql);   
	$row = @mysql_fetch_array($users);
	if(!$row)
		apologize("There was an error in trying to edit this item");
			
		
	// add the auction info to the table auctions - this also checks that auctions is a valid name.
	$path = "images/" . $auction . "/";
	$sql = sprintf("UPDATE `%s` SET item_name='%s', description='%s' WHERE item_id=%d", $auction, $item, $description, $item_id);	
	if (!mysql_query($sql))
		apologize("This auction name or description was invalid!");
	

	// change the names of the item in auction and auction_bids for the relevant auction
	if (strcmp($item, $row["item_name"]) != 0)
	{
		$sql = sprintf("UPDATE `%s` SET item_name='%s' WHERE item_id=%d", $auction . "_bids" , $item, $item_id);
		//apologize($sql);
		if (!@mysql_query($sql))
		{
			$sql = sprintf("UPDATE `%s` SET item_name='%s', description='%s' WHERE item_id=%d", $auction, $row["item_name"], $row["description"], $item_id);	
			@mysql_query($sql);
			apologize("For some reason, the item named " . $row["item_name"] . " could not be changed.");
		}
	}
	
	$msg =	sprintf('Congratulations, you have made changes to your item in auction %s. The first row is your old item, the next is the new item: 
	<table  cellspacing="0" class="outertable">
				<tr><td>
				<table align="center" cellspacing="0" class="innertable">
					<tr>
						<th>Item name</th>
						<th>Item ID</th>			
						<th>Description</th>
						<th>Mini Picture</th>
						<th>Large Picture</th>	
					</tr>
					<tr>
						<td>%s</td>
						<td>%s</td>
						<td>%s</td>
						<td><image src="%s"/></td>
						<td><image src="%s"/></td>
					</tr>
					<tr>
						<td>%s</td>
						<td>%s</td>
						<td>%s</td>
						<td><image src="%s"/></td>
						<td><image src="%s"/></td>
					</tr>
				</table></td></tr></table>', 
				$auction, $row["item_name"], $row["item_id"], $row["description"], $row["minpic"],$row["bigpic"], $item, $item_id, $description, $row["minpic"], $row["bigpic"]);

	congratulate($msg, "Return to the admin page",
						"admin_index.php");

?>