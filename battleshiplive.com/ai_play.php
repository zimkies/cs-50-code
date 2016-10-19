<?php
	
	// require common code and variables used throughout file
    require_once("includes/common.php");
	
	// select my information
    $sql = sprintf("SELECT * FROM users WHERE uid='%s'",$_SESSION["uid"]);
    $return = mysql_query($sql);
	$myinfo = mysql_fetch_array($return);
	
	// get your board as array[0-9][a-j] NOTE: DO NOT USE INTEGERS TO REFERENCE the a-j part.
	$sql = sprintf("SELECT * FROM %s ", $myinfo["name"]);
	$result = mysql_query($sql);
	for ($i = 0; $i < 10; $i++)
		$table[$i] = mysql_fetch_array($result);
	
	// get enemy's information....
	$sql = sprintf("SELECT * FROM users WHERE name='%s'", $myinfo["challenger"]);
    $return = mysql_query($sql);
	$ai_info = mysql_fetch_array($return);
	$hit1 = $ai_info["last_hit1"];
	$hit2 = $ai_info["last_hit2"];
	
	// make sure it is ai's turn
	if ($ai_info["turn"] == 1 && $myinfo["win"] == 0 && $ai_info["win"] == 0)
	{		
		// If the ai has not hit a ship recently, choose a spot, check if it is valid
		if (!$hit1)
		{
			do
			{	
				$row = rand(0,9);
				$col = $alphabet[rand(0,9)];
			}
			while ($table[$row][$col] == 6 || $table[$row][$col] == 7);
		}
		
	
		// if the ai has located a ship, try to hit pieces next to it.
		else if(!($hit2) && (adj_open($table, $hit1)))
		{
			do
			{
				$random = rand(0,3);
				switch ($random)
				{
					case 0:
						$row = $hit1[0] + 1;
						$coldig = $hit1[1];
						break;
					case 1:
						$row = $hit1[0];
						$coldig = todigit($hit1[1]) - 1;
						break;
					case 2:
						$row = $hit1[0] - 1;
						$coldig = $hit1[1];
						break;
					case 3:
						$row = $hit1[0];
						$coldig = todigit($hit1[1]) + 1;
						break;
					default:
						break;
				}
				$col = $alphabet[$coldig];
			}	
			// while the tile is out of bounds, or already hit/missed
			while (!(in_bounds($row, $coldig)) || $table[$row][$col] == 6 || $table[$row][$col] == 7);
		}	
		
		// temporary - deletes the lsat hit	
		else
		{
			// set last_hit1 as empty
			$sql4 = sprintf("UPDATE users SET last_hit1='' WHERE name='%s'", $myinfo["challenger"]);
			mysql_query($sql4);	
			redirect(play.php);
		}
/*			else if ($hit2)
			{
				if ($hit1[0] == $hit2[1])
				{
					if ($hit1[1] > $hit2[1])
					{
						$row = $hit[0];
						$col = $alphabet[todigit($hit2[1]) -1]
							*/
			//	ai
			// if last one hit, find next one, or break if can't
					
			// if next one hit, make direction, and shoot until white on both sides.
					
			// if still no hit, change direction on both sides.
					
			// NB make sure that place has a hittable place next to it.
			
					
					
		// depending on what is located at the place shot at, update table by setting the position
		// to 6 if it is a miss, and 7 if it is a hit.
//		apologize(in_bounds($hit1[0], $alphabet[todigit($hit1[1]) + 1]));
//		apologize($row . 'a' . $col);
		switch ($table[$row][$col])		
		{
			case '':
				$sql2 = sprintf("UPDATE %s SET %s='6' WHERE row=%s", $myinfo["name"], $col, $row);
				mysql_query($sql2);
				break;
			case 1: 
			case 2:
			case 3:
			case 4:
			case 5:				
				// set the tile hit to be 7 in the database
				$sql2 = sprintf("UPDATE %s SET %s='7' WHERE row=%s", $myinfo["name"], $col, $row);
				mysql_query($sql2);	
				
				// let the ai db know that this is the last ship that the ai hit.
					$sql4 = sprintf("UPDATE users SET last_hit1='%s' WHERE name='%s'", $row . $col, $myinfo["challenger"]);
					mysql_query($sql4);
				
				// if you sunk a ship, or won, update your database
				if (sunk($myinfo["name"], $table[$row][$col]))
				{					
					$sql3 = sprintf("UPDATE users SET %s=2 WHERE name='%s'", $table[$row][$col] . 'placed', $myinfo["name"]);
					mysql_query($sql3);
					
					// set last_hit1 as empty
					$sql4 = sprintf("UPDATE users SET last_hit1='' WHERE name='%s'", $myinfo["challenger"]);
					mysql_query($sql4);
					
					$sunk = $table[$row][$col];
					if (lost($myinfo["name"]))
					{					
						$sql3 = sprintf("UPDATE users SET win=1 WHERE name='%s'", $myinfo["challenger"]);
						mysql_query($sql3);
					}
				}
				break;
			case 6:
			case 7:
				break;
			default:
				apologize("Table is wrong");
		}

		mysql_query($sql3);

		// make it you turn again
		$sql3 = sprintf("UPDATE users SET turn=0 WHERE name='%s'", $myinfo["challenger"]);
		mysql_query($sql3);
		$sql3 = sprintf("UPDATE users SET turn=1 WHERE name='%s'", $myinfo["name"]);
		mysql_query($sql3);
	}
	
	redirect("play.php");
	
	header("Content-type: text/xml");
		
	// write xml users table xml
	echo "<users boat='$boat' length='$b_length' status='$status' orient='$orient' win='$win' turn='$turn'";
	for($i = 1; $i <= 5; $i++)
	{
		$placer = $i . 'placed';
		$placed = $myinfo[$placer];
		$bad_placed = $ai_info[$placer];
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
		