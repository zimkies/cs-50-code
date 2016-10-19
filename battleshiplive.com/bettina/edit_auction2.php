<?php
	
	/****************************************************************
	 make_auction.php creates a table for that auction with a
	 list of names, prices, etc
	 ***************************************************************/

    // require common code, check for admin
    require_once("includes/common.php");
	require_once("includes/cronfunctions.php");
	admin_check();

	// get the name of the auctions/times and escape them.
	$auction = mysql_real_escape_string($_POST["auction"]);	
    $old_auction = mysql_real_escape_string($_POST["old_auction"]);	

	$start = mysql_real_escape_string($_POST["startdate"]) . 
		' ' . mysql_real_escape_string($_POST["starttime"]);
	$end = mysql_real_escape_string($_POST["enddate"]) . 
		' ' . mysql_real_escape_string($_POST["endtime"]);
	
	// check that dates are valid
	if (!check_time($end))
		apologize("The end dates/times that you entered were not valid");
	if (!check_time($start))
		apologize("The start dates/times that you entered were not valid");
	
	// obtain old info for the auction
	$sql = sprintf("SELECT * FROM auctions WHERE name='%s'", $old_auction);
    $users = mysql_query($sql);   
	$row = @mysql_fetch_array($users);
	if(!$row)
		apologize("There was an error in trying to edit this auction");
			
	// schedule the end of the auction
	$jobnum = add_scheduled_job($end, HOME_PATH . "end_auction.php \"" . $auction . "\" " . AUCTION_PASSWORD);
		// schedule the end of the auction
	
		
	// add the auction info to the table auctions - this also checks that auctions is a valid name.
	$path = "images/auctions/";
	$sql = sprintf("UPDATE auctions SET name='%s', start='%s', end='%s', jobnum='%s', minpic='%s', bigpic='%s' WHERE name='%s'", 
	$auction, $start, $end, $jobnum, $path . $auction . "_min.jpg", $path . $auction . "_big.jpg",  $row["name"]);	
	if (!mysql_query($sql))
	{
		remove_scheduled_job($jobnum);
		apologize("This auction name is either invalid (should be only alpha-numeric, starting with a letter), or the name is already being used! Make sure you entered the name correctly!");
	}
	

	// change the names of the auction and auction bid tables
	// this was a real bitch to think about
	if (strcmp($auction, $row["name"]) != 0)
	{
		$sql = sprintf("RENAME TABLE `%s` TO `%s`, `%s` TO `%s`", $row["name"], $auction, $row["name"] . "_bids" , $auction . "_bids");
		if (!@mysql_query($sql))
		{
			remove_scheduled_job($jobnum);
			$sql = sprintf("UPDATE auctions SET name='%s', start='%s', end='%s', jobnum='%s', minpic='%s', bigpic='%s' WHERE name='%s'", 
			$row["name"], $row["start"], $row["end"], $row["jobnum"], $row["minpic"], $row["bigpic"], $auction);
			@mysql_query($sql);
			apologize("For some reason, the tables associated with auction " . $row["name"] . " could not have their names changed. To maintain coherence in the database, please ask the system administrator to manually check/change the names of relavant auction table and the relevant auction_bids table!");
		}
	
		//change the image folders
		$exec = shell_exec("mv '". HOME_PATH . "images/" . $row["name"] . "' '" . HOME_PATH . "images/" . $auction . "'");
		$exec2 = shell_exec("mv '". HOME_PATH . $row["minpic"] . "' '" . HOME_PATH . "images/auctions/" . $auction . "_min.jpg" . "'");
		$exec3 = shell_exec("mv '". HOME_PATH . $row["bigpic"] . "' '" . HOME_PATH . "images/auctions/" . $auction . "_big.jpg'");
		if ($exec || $exec2 || $exec3)
		{
			remove_scheduled_job($jobnum);
			$sql = sprintf("UPDATE auctions SET name='%s', start='%s', end='%s', jobnum='%s', minpic='%s', bigpic='%s' WHERE name='%s'", 
			$row["name"], $row["start"], $row["end"], $row["jobnum"], $row["minpic"], $row["bigpic"], $auction);
			$sq2 = sprintf("RENAME TABLE `%s` TO `%s`",  $auction, $row["name"]);
			$sql3 = sprintf("RENAME TABLE `%s` TO `%s`", $auction . "_bids", $row["name"] . "_bids");
			@mysql_query($sql);
			@mysql_query($sql2);
			@mysql_query($sql3);
			apologize("For some reason, the images folders associated with auction " . $row["name"] . " could not have their names changed. To maintain coherence in the database, please ask the system administrator to manually check/change the anmes of all images and image folders associated with this auction!");
		}
		
		// change the locations of each image in the items in the auction database.
		$sql =  sprintf("UPDATE `%s` SET minpic=REPLACE(`%s`.minpic, '%s', '%s'), bigpic=REPLACE(`%s`.bigpic, '%s', '%s')", $auction, $auction, $old_auction, $auction, $auction, $old_auction, $auction);
		if (!@mysql_query($sql))
		{
			remove_scheduled_job($jobnum);
			$sql = sprintf("UPDATE auctions SET name='%s', start='%s', end='%s', jobnum='%s' WHERE name='%s'", 
			$row["name"], $row["start"], $row["end"], $row["jobnum"], $auction);
			$sq2 = sprintf("RENAME TABLE '%s' TO '%s'",  $auction, $row["name"]);
			$sql3 = sprintf("RENAME TABLE '%s' TO '%s'", $auction . "_bids", $row["name"] . "_bids");
			@mysql_query($sql);
			@mysql_query($sql2);
			@mysql_query($sql3);
			apologize("For some reason, the image locations in the database associated with auction " . $row["name"] . " could not be changed. To maintain coherence in the database, please ask the system administrator to manually check/change the nanmes of all images and image folders associated with this auction! They have probably been changed to include the name " .  $auction);
		}
	}
		
	// finally, we can remove the old job	
	remove_scheduled_job($row["jobnum"]);
	
	$msg =	sprintf('Congratulations, you have made changes to your auction. The first row is your old auction, the next is the new auction: <table  cellspacing="0" class="outertable">
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
					<tr>
						<td>%s</td>
						<td>%s</td>
						<td>%s</td>
						<td><image src="%s"/></td>
						<td><image src="%s"/></td>
					</tr>
				</table></td></tr></table>', 
				$row["name"], $row["start"], $row["end"], $path . $auction . "_min.jpg", $path . $auction . "_big.jpg", $auction, $start, $end, $path . $auction . "_min.jpg", $path . $auction . "_big.jpg");

	congratulate($msg, "Return to the admin page",
						"admin_index.php");

?>