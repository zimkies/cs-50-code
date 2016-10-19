<?php
	
	/****************************************************************
	 initialize.php is called when a player accepts a challenge.
	 it then creates and initializes a table for each player
	 in the database.
	 ***************************************************************/

    // require common code
    require_once("includes/common.php");

	// select my information
    $sql = sprintf("SELECT * FROM users WHERE uid='%s'",$_SESSION["uid"]);
    $return = mysql_query($sql);
	$myinfo = mysql_fetch_array($return);
	
	// relay to table that we are in a game
	$sql = sprintf("UPDATE users SET ingame='1' WHERE uid='%s' OR name='%s'",  $_SESSION['uid'], $myinfo["challenger"]);
	mysql_query($sql);
	
	//set your opponents challenger to be you
	$sql = sprintf("UPDATE users SET challenger='%s' WHERE name='%s'",
		$myinfo["name"], $myinfo["challenger"]);
	mysql_query($sql);
	
	// a list of columns for the table, from 'row', and then a-j.
	$columns = '(row int, ';
	for ($i = 0; $i < 10; $i++)
	{	
		$columns = $columns . $alphabet[$i] . ' int';
		
		if ($i != 9)
			$columns = $columns . ', ';
	}
	
	$columns = $columns . ')';
	
	// create your own table
	$sql = sprintf("CREATE TABLE %s %s", $myinfo["name"], $columns);
	mysql_query($sql);
	
	// create opponent's table
	$sq2 = sprintf("CREATE TABLE %s %s", $myinfo["challenger"], $columns);
	mysql_query($sq2);
	
	// fill your table with rows
	$sql = sprintf("INSERT INTO %s (row) VALUES (0),(1),(2),(3),(4),(5),(6),(7),(8),(9)", $myinfo["name"]);
	$sq2 = sprintf("INSERT INTO %s (row) VALUES (0),(1),(2),(3),(4),(5),(6),(7),(8),(9)", $myinfo["challenger"]);
	
	mysql_query($sql);
	mysql_query($sq2);
	
	redirect("placeships.php");
	

?>