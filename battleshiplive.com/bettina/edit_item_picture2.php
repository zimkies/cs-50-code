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
	$item_id = mysql_real_escape_string($_POST["item_id"]);
	$minpic_raw = "minpic";
	$bigpic_raw = "bigpic";
	
	// obtain old info for the auction
	$sql = sprintf("SELECT * FROM `%s` WHERE item_id=%d", $auction, $item_id);
	//dump($_FILES);
	//apologize($sql);
    $users = mysql_query($sql);   
	$row = @mysql_fetch_array($users);
	if(!$row)
		apologize("There was an error in trying to edit this item");
	
	// temporarily change the name of the old picture files
	$path = "images/" . $auction . "/";
	$exec1= shell_exec("mv '". HOME_PATH . $row["minpic"] . "' '" . HOME_PATH . $path . "temp.jpg'");
	$exec2= shell_exec("mv '". HOME_PATH . $row["bigpic"] . "' '" . HOME_PATH . $path . "temp2.jpg'");
	
	// open and escape the pictures that were provided
	$error = upload_picture(HOME_PATH . $path, $item_id . "_min.jpg", $minpic_raw);
	$error2 = upload_picture(HOME_PATH . $path, $item_id . "_big.jpg", $bigpic_raw);
	if($error || $error2)
	{
		//change the old pictures back.
		$exec1= shell_exec("mv '". HOME_PATH . $path . "temp.jpg' '" . HOME_PATH . $row["minpic"] . "'");
		$exec2= shell_exec("mv '". HOME_PATH . $path . "temp2.jpg' '" . HOME_PATH . $row["bigpic"] . "'");
		apologize($error);
	}
	
	// delete the old images now.
	$exec = shell_exec("rm -f '" .  HOME_PATH . $path . "temp.jpg'");
	$exec1 = shell_exec("rm -f '" . HOME_PATH . $path . "temp2.jpg'");

	$msg =	sprintf('Congratulations, you have Changed the pictures associated with your item in auction %s: <table  cellspacing="0" class="outertable">
				<tr><td>
				<table align="center" cellspacing="0" class="innertable">
					<tr>
						<th>Item name</th>
						<th>Item id</th>			
						<th>Picture 1</th>
						<th>Picture 2</th>
					</tr>
					<tr>
						<td>%s</td>
						<td>%s</td>
						<td><image src="%s"/></td>
						<td><image src="%s"/></td>
					</tr>
				</table></td></tr></table>', 
				$auction, $row["item_name"], $row["item_id"], $row["minpic"], $row["bigpic"]);

	congratulate($msg, "Return to the admin page",
						"admin_index.php");
?>