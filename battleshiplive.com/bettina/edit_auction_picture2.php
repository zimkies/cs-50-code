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
	$minpic_raw = "minpic";
	$bigpic_raw = "bigpic";
	
	// get the data from the auction
	$sql = sprintf("SELECT * FROM auctions WHERE name='%s'", $auction);
    $users = mysql_query($sql);   
	$row = @mysql_fetch_array($users);
	if(!$row)
		apologize("There was an error in trying to edit this auctin");
	
	// temporarily change the name of the old picture files
	$exec1= shell_exec("mv '". HOME_PATH . $row["minpic"] . "' '" . HOME_PATH . "images/auctions/temp.jpg". "'");
	$exec2= shell_exec("mv '". HOME_PATH . $row["bigpic"] . "' '" . HOME_PATH . "images/auctions/temp2.jpg" . "'");
	
	// open and escape the pictures that were provided
	$path = "images/auctions/";
	$minpic_name = $auction . "_min.jpg";
	$bigpic_name = $auction . "_big.jpg";
	$failed = upload_picture(HOME_PATH . $path, $minpic_name, $minpic_raw);
	if($failed)
	{
		//change the old pictures back.
		$exec1= shell_exec("mv '". HOME_PATH . "images/auctions/temp.jpg' '" . HOME_PATH . $row["minpic"] . "'");
		$exec2= shell_exec("mv '". HOME_PATH . "images/auctions/temp2.jpg' '" . HOME_PATH . $row["bigpic"] . "'");
		apologize($failed);
	}
	$failed = upload_picture($path, $bigpic_name, $bigpic_raw);
	if($failed)
	{
		// remove the one image just uploaded before and then change the old pictures back
		$exec1= shell_exec("mv '". HOME_PATH . "images/auctions/temp.jpg' '" . HOME_PATH . $row["minpic"] . "'");
		$exec1= shell_exec("mv '". HOME_PATH . "images/auctions/temp2.jpg' '" . HOME_PATH . $row["bigpic"] . "'");
		apologize($failed);
	}
			
	// add the auction info to the table auctions - this also checks that auctions is a valid name.
	$sql = sprintf("UPDATE auctions SET minpic='%s', bigpic='%s' WHERE name='%s'", $path . $minpic_name, $path . $bigpic_name, $auction);
	if (!mysql_query($sql))
		apologize("The picture locations were not recorded in the database. Contact system admin to remove uploaded images or change the database and remove the old images !");
	
	// delete the old images now.
	$exec = shell_exec("rm -f " .  HOME_PATH . "images/auctions/temp.jpg");
	$exec1 = shell_exec("rm -f " .  HOME_PATH . "images/auctions/temp2.jpg");

	$msg =	sprintf('Congratulations, you have Changed the pictures associated with your auction: <table  cellspacing="0" class="outertable">
				<tr><td>
				<table align="center" cellspacing="0" class="innertable">
					<tr>
						<th>Auction name</th>
						<th>Auction Start time</th>			
						<th>Auction End time</th>
						<th>Current Mini Picture</th>
						<th>Current Big Picture</th>
					</tr>
					<tr>
						<td>%s</td>
						<td>%s</td>
						<td>%s</td>
						<td><image src="%s"/></td>
						<td><image src="%s"/></td>
					</tr>
				</table></td></tr></table>', 
				$row["name"], $row["start"], $row["end"], $row["minpic"], $row["bigpic"]);

	congratulate($msg, "Return to the admin page",
						"admin_index.php");
?>