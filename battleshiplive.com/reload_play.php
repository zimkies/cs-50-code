<?php

	// require common code and variables used throughout file
    require_once("includes/common.php");
	
	// select my information
    $sql = sprintf("SELECT * FROM users WHERE uid='%s'",$_SESSION["uid"]);
    $return = mysql_query($sql);
	$myinfo = mysql_fetch_array($return);
	
	// give names to all information from users
	$win = $myinfo["win"];
	$turn = $myinfo["turn"];	
	
	// get your board as array[0-9][a-j] NOTE: DO NOT USE INTEGERS TO REFERENCE the a-j part.
	$sql = sprintf("SELECT * FROM %s ", $myinfo["name"]);
	$result = mysql_query($sql);
	for ($i = 0; $i < 10; $i++)
		$table[$i] = mysql_fetch_array($result);
	
	// get enemy's information....
	$sql = sprintf("SELECT * FROM users WHERE name='%s'", $myinfo["challenger"]);
    $return = mysql_query($sql);
	$bad_info = mysql_fetch_array($return);	
		
	// get your enemy's board as an array...
	$sql = sprintf("SELECT * FROM %s ", $myinfo["challenger"]);
	$result = mysql_query($sql);
	for ($i = 0; $i < 10; $i++)
		$bad_table[$i] = mysql_fetch_array($result);
		
	// check the status of the game
	if ($myinfo["ingame"] == 1)
		$status = 'place';
	else if ($myinfo["ingame"] == 2)
		$status = 'play';
	if ($myinfo["win"] == 1)
		$status = 'win';
	if	($bad_info["win"] == 1)
		$status = "lost";
	
	header("Content-type: text/xml");
		
	// write xml users table xml
	echo "<users boat='$boat' length='$b_length' status='$status' orient='$orient' win='$win' turn='$turn'";
	for($i = 1; $i <= 5; $i++)
	{
		$placer = $i . 'placed';
		$placed = $myinfo[$placer];
		$bad_placed = $bad_info[$placer];
		echo " placed$i='$placed'";
		echo " bad_placed$i='$bad_placed'";
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
			$bad_position = 'a' . $i . $alphabet[$j];
			$bad_ship = $bad_table[$i][$alphabet[$j]];
			echo "<boardxml position='$position' ship='$ship'";
			echo " bad_position='$bad_position' bad_ship='$bad_ship'>";
			echo "</boardxml>\n\t";
		}
	}
	echo "</users>";
		