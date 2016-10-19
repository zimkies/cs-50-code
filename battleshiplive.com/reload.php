<?php

	/****************************************************************
	 reload.php gets information from the database about position of 
	 a players ships, and returns it as xml to placeships
	 ***************************************************************/
   
	// require common code and variables used throughout file
    require_once("includes/common.php");
	
	// select my information
    $sql = sprintf("SELECT * FROM users WHERE uid='%s'",$_SESSION["uid"]);
    $return = mysql_query($sql);
	$myinfo = mysql_fetch_array($return);
	
	// give names to all information from users
	$orient = $myinfo["orientation"];
	$boat = $myinfo["ship_selected"];
	$b_length = boat_length($boat);
	$win = $myinfo["win"];
	$turn = $myinfo["turn"];	
	for ($i = 1; $i <= 5; $i++)
	{
		$placed = $i . 'placed';
		$placed[$i] = $myinfo[$placed];
	}
	
	// get enemy's information....
	$sql = sprintf("SELECT * FROM users WHERE name='%s'", $myinfo["challenger"]);
    $return = mysql_query($sql);
	$bad_info = mysql_fetch_array($return);	
	
	// get your board as array[0-9][a-j] NOTE: DO NOT USE INTEGERS TO REFERENCE the a-j part.
	$sql = sprintf("SELECT * FROM %s ", $myinfo["name"]);
	$result = mysql_query($sql);
	for ($i = 0; $i < 10; $i++)
		$table[$i] = mysql_fetch_array($result);	
	
	// if all pieces set, wait for opponent, then set set status to play to start game
	if ($myinfo["ingame"] == 2)
	{
		$status = 'wait';
		if ($bad_info["ingame"] == 2)
			$status = 'play';
	}
	
	// if ships not all placed, set status to 'place';
	if ($myinfo["ingame"] == 1)
		$status = 'place';
	
	header("Content-type: text/xml");
		
	// write xml users table xml
	echo "<users boat='$boat' length='$b_length' status='$status' orient='$orient' win='$win' turn='$turn'";
	for($i = 1; $i <= 5; $i++)
	{
		$placed = $i . 'placed';
		$placed = $myinfo[$placed];
		echo " placed$i='$placed'";
	}
	echo ">\n\t";
	
	//write board information xml
	$alphabet = 'abcdefghij';
	for ($i = 0; $i < 10; $i++)
	{
		for($j = 0; $j < 10; $j++)
		{
			$position = 'b' . $i . $alphabet[$j];
			$ship = $table[$i][$alphabet[$j]];
			echo "<boardxml position='$position' ship='$ship'></boardxml>\n\t";
		}
	}
	echo "</users>";
		