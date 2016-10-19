<?php
	
	/****************************************************************
	 make_auction.php creates a table for that auction with a
	 list of names, prices, etc
	 ***************************************************************/

    // require common code
    require_once("includes/common.php");
	admin_check();

	// get item info, real escape string. 
	$auction = mysql_real_escape_string($_POST["auction"]);
	$name = mysql_real_escape_string($_POST["name"]);
	$filename = mysql_real_escape_string($_POST["filename"]);
	$startprice = mysql_real_escape_string($_POST["startprice"]);
	$description = mysql_real_escape_string($_POST["description"]);
	$minpic_raw = "minpic";
	$bigpic_raw = "bigpic";
	
//add the auction info to the table auctions. Makes sure that auction and name are valid. 
	$sql = sprintf("INSERT INTO `%s` (item_name, startprice, currentprice, description) VALUES ('%s', '%d', '%d', '%s')", 
	$auction, $name, $startprice, $startprice, $description);
	
	if (!mysql_query($sql))
		apologize("Either that item is invalid/already exists in this auction, or the auction you specified doesn't exist.");
	
	// upload the pictures. If there is a problem, remove the job and the item entry.
	$item_id = mysql_insert_id ();
	$min_path = "images/" . $auction . "/" ;
	$big_path = "images/" . $auction . "/" ;
	$error = upload_picture(HOME_PATH . $min_path, $item_id . "_min.jpg", $minpic_raw);
	$error2 = upload_picture(HOME_PATH . $big_path, $item_id . "_big.jpg", $bigpic_raw);
	
	// if failed to upload picture, delete the entry, and apologize.
	if($error || $error2)
	{
		$sql = sprintf("DELETE FROM `%s` WHERE item_id=%d", $auction, $item_id);
		if (!mysql_query($sql))
			apologize($error . "\n" . $error2 . "\nThere was also a problem in the database - The item in question was uploaded but the images failed to upload. Contact web developer for further assistance, to maintain the security of the site.");
		else
			apologize($error . "\n" . $error2);
	}
	
	//Lastly, store the image location
	$sql = sprintf("UPDATE `%s` SET minpic='%s', bigpic='%s' WHERE item_id=%d", 
	$auction, $min_path . $item_id . "_min.jpg", $big_path . $item_id . "_big.jpg" , $item_id);
	if (!mysql_query($sql))
		apologize("The image paths were not stored in the database, so they will not be used.
		Contact web developer for assistance if this is a problem.");	
	
	// Congratulate showing the new item in the table
	$msg_to_admin = sprintf('You have added the following item to auction %s: 
	
<table  cellspacing="0" class="outertable">
				<tr><td>
				<table align="center" cellspacing="0" class="innertable">
					<tr>
						<th>Item name</th>
						<th>Item ID</th>			
						<th>Startprice</th>
						<th>Description</th>			
						<th>Picture</th>
						<th>Big Picture</th>
					</tr>
					<tr>
						<td>%s</td>
						<td>%s</td>
						<td>%s</td>
						<td>%s</td>
						<td><img src="%s"/></td>
						<td><img src="%s"/></td>
					</tr>
				</table></td></tr></table>',$auction,  $name,
				$item_id, $startprice,  $description, $min_path . $item_id . '_min.jpg' ,
				$big_path . $item_id . '_min.jpg');
	congratulate($msg_to_admin, "Return to Admin page", "admin_index.php");
;
	

?>