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
	
	// we need to make sure that $auction is ONLY letters and numbers and spaces.
	// check that the email is a valid email format
	if (preg_match("/^[a-zA-Z0-9_ ]+$/", $auction) == 0)
		apologize("The auction name can only have letters, numbers and spaces");
	$start = mysql_real_escape_string($_POST["startdate"]) . 
		' ' . mysql_real_escape_string($_POST["starttime"]);
	$end = mysql_real_escape_string($_POST["enddate"]) . 
		' ' . mysql_real_escape_string($_POST["endtime"]);
	$minpic_raw = "minpic";
	$bigpic_raw = "bigpic";
	
	// check that dates are valid
	if (!check_time($end))
		apologize("The end dates/times that you entered were not valid");
	if (!check_time($start))
		apologize("The start dates/times that you entered were not valid");
	
	// open and escape the pictures that were provided
	$path = "images/auctions/";
	$minpic_name = $auction . "_min.jpg";
	$bigpic_name = $auction . "_big.jpg";
	$failed = upload_picture(HOME_PATH . $path, $minpic_name, $minpic_raw);
	if($failed)
		apologize($failed);
	$failed = upload_picture($path, $bigpic_name, $bigpic_raw);
	if($failed)
	{
		shell_exec("rm -f '" . HOME_PATH . $path . $minpic_name . "'");
		apologize($failed);
	}
	
	// schedule the end of the auction
	$jobnum = add_scheduled_job($end, HOME_PATH . "end_auction.php \"" . $auction . "\" " . AUCTION_PASSWORD);
		
	// add the auction info to the table auctions - this also checks that auctions is a valid name.
	$sql = sprintf("INSERT INTO auctions (name, start, end, jobnum, minpic, bigpic) VALUES ('%s', '%s', '%s', '%s', '%s' , '%s')", 
	$auction,  $start, $end, $jobnum, $path . $minpic_name, $path . $bigpic_name);
	
	if (!mysql_query($sql))
	{
		remove_scheduled_job($jobnum);
		shell_exec("rm -f " . "'" . HOME_PATH . $path . $minpic_name . "'");
		shell_exec("rm -f " .  "'" .HOME_PATH . $path . $bigpic_name . "'");
		apologize("This auction name is either invalid (only alpha-numeric, starting with a letter), or already exists! Make sure you entered the name correctly! Lastly, your picture files may not be valid.");
	}		
	
	// create a directory to store item images - Note I don't error check ~
	$exec = shell_exec("mkdir '" . HOME_PATH . "images/" . $auction . "'"); 
	
	// This gives the column names for the auction
	$columns = '(item_name varchar(255), 
				item_id int AUTO_INCREMENT,
				filename varchar(255), 
				startprice float,
				currentprice float,
				bids int(10) DEFAULT 0,
				topbidder varchar(255),
				description text,
				minpic varchar(55),
				bigpic varchar(55),
				CONSTRAINT ui_item_id PRIMARY KEY (item_id))';

	// create a table for the auction
	$sql = sprintf("CREATE TABLE `%s` %s", $auction, $columns);
	//apologize($sql);
	if (!mysql_query($sql))
	{
		remove_scheduled_job($jobnum);
		$sql = sprintf("DELETE FROM auctions WHERE name='%s'", $auction);		
		mysql_query($sql);
		$exec = shell_exec("rm -r '" . HOME_PATH . "images/" . $auction . "'"); 
		$exec2 = shell_exec("rm -f '" . HOME_PATH . $path . $minpic_name . "'");
		$exec3 = shell_exec("rm -f '" .  HOME_PATH . $path . $bigpic_name . "'");
		apologize("rm -f '" . HOME_PATH . $path . $minpic_name . "'");
		apologize("The database is inconsistent. Either your database name is not valid, or there is already an auction of that name!");
	}
	
	// create a bidding history table for the auction
	$columns = sprintf('(item_name varchar(255),
				item_id int,
				price float, 
				time datetime,
				username varchar(255),
				CONSTRAINT ui_item_id FOREIGN KEY (item_id) 
					REFERENCES `%s`(item_id))', $auction);

	// create a table for the auction
	$sql = sprintf("CREATE TABLE `%s` %s", $auction . "_bids", $columns);
	if (!mysql_query($sql))
	{
		remove_scheduled_job($jobnum);
		$sql = sprintf("DELETE FROM auctions WHERE name='%s'", $auction);		
		mysql_query($sql);
		$exec = shell_exec("rm -r '" . HOME_PATH . "images/" . $auction . "'");
		shell_exec("rm -f '" . HOME_PATH . $path . $minpic_name . "'");
		shell_exec("rm -f '" .  HOME_PATH . $path . $bigpic_name . "'");
		$sql2 = sprintf("DROP TABLE `%s`", $auction);
		mysql_query($sql2);
		apologize("the database is inconsistent - there is already an auction_bid table of that name!");
	}

	$msg =	sprintf('Congratulations, you have made the following auction: <table  cellspacing="0" class="outertable">
				<tr><td>
				<table align="center" cellspacing="0" class="innertable">
					<tr>
						<th>Auction name</th>
						<th>Auction Start time</th>			
						<th>Auction End time</th>
						<th>Current Picture</th>
					</tr>
					<tr>
						<td>%s</td>
						<td>%s</td>
						<td>%s</td>
						<td><image src="%s"/></td>
					</tr>
					<tr>
						<td><image src="%s"/></td>
					</tr>
				</table></td></tr></table>', 
				$auction, $start, $end, $path . $minpic_name, $path . $bigpic_name);

	congratulate($msg, "Return to the admin page",
						"admin_index.php");
?>