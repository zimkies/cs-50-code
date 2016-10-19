<?php

	/****************************************************************
	 place.php is called by placeships.php whenever a player selects
	 a ship, places a ship, or rotates a ship. It checks whether the
	 placement or selection is valid by checking the database, 
	 and then updates the database, lastly returning xml of
	 whether it was successful or not
	 ***************************************************************/
  
	// require common code and variables used throughout file
    require_once("includes/common.php");
	$point = $_GET["point"];	
	$board = $point[0];
	$row[0] = $point[1];
	$column[0] = $point[2];
	$columndig = todigit($column[0]);

	// select my information
    $sql = sprintf("SELECT * FROM users WHERE uid='%s'",$_SESSION["uid"]);
    $return = mysql_query($sql);
	$myinfo = mysql_fetch_array($return);
	$orient = $myinfo["orientation"];
	$boat = $myinfo["ship_selected"];
	$b_length = boat_length($boat);
	
	// get your board as array[0-9][a-j] NOTE: use letters to reference the columns, not integers.
	$sql = sprintf("SELECT * FROM %s ", $myinfo["name"]);
	$result = mysql_query($sql);
	for ($i = 0; $i < 10; $i++)
		$table[$i] = mysql_fetch_array($result);	
	
	// make sure all pieces are not set. If they are set, tell placeships to start the game
	if ($myinfo["ingame"] == 2)
		$status = 'reload';
	
	// if you are selecting a ship, change the database accordingly. Then set status = 'selected'	
	else if ($point[0] == 's')
	{
		$placed = $point[1] . "placed";
		
		// if ship is already placed, return status as 'no_select'
		if ($myinfo[$placed] == 1)
			$status = 'no_select';
		else
		{
			$sql = sprintf("UPDATE users SET ship_selected=%d WHERE uid='%s'", $point[1], $_SESSION["uid"]);
			mysql_query($sql);
			$status = 'selected';
			$b_length = boat_length($point[1]);
		}
	}
	
	// if you are rotating your ship, change the database accordingly. Then set status = rotated.
	else if ($point[0] == 'r')
	{
		switch ($myinfo["orientation"])
		{
			case 'right':
				$neworient = 'down';
				break;
			case 'down':
				$neworient = 'left';
				break;
			case 'left':
				$neworient = 'up';
				break;
			case 'up':
				$neworient = 'right';
				break;
			default:
				break;
		}
		$sql = sprintf("UPDATE users SET orientation='%s' WHERE uid='%s'", $neworient, $_SESSION["uid"]);
		mysql_query($sql);
		$status = 'rotated';
		$orient = $neworient;
	}
	// make sure you have selected a ship
	else if ($boat == 0)
	{
		$status = '';
		$line = "You must select a ship before you place it";
	}
	
	// check to see that it is a valid placement. if not, return message that you can't place your ship
	else if (!valid_move($orient, $boat, $table, $row[0], $columndig))
	{
		$status = '';
		$line = "You cannot order your ship to go there!";
	}
	
	// Else place the boat on the table where it was clicked.
	else if ($board == 'a' || $board == 'b')
	{	
		// if you have placed the ship already, return status as no_place
		$placed = $boat . "placed";
		if ($myinfo[$placed] == 1)
			$status = 'no_place';
		
		// otherwise update the database to place the ship
		else
		{
			$status = 'placed';

			for ($i = 0; $i < $b_length; $i++)
			{
				// This is slightly complicated. Basically, it creates arrays $row and $column which each contain the coordinates
				// of each point of the boat to be placed. It then updates the table with these coordinates.
				switch ($orient)
				{	
					case 'down':
						$sql = sprintf("UPDATE %s SET %s=%d WHERE row=%s", $myinfo["name"], $column[$i], $boat, $row[$i]);
						mysql_query($sql);
						$row[$i + 1] = $row[$i] + 1;
						$column[$i + 1] = $column[$i]; 
						break;
					case 'left':
						$sql = sprintf("UPDATE %s SET %s=%d WHERE row=%s", $myinfo["name"], $column[$i], $boat, $row[$i]);
						mysql_query($sql);
						$column[$i + 1] = $alphabet[todigit($column[$i]) - 1];
						$row[$i + 1] = $row[$i];
						break;
					case 'up':
						$sql = sprintf("UPDATE %s SET %s=%d WHERE row=%s", $myinfo["name"], $column[$i], $boat, $row[$i]);
						mysql_query($sql);
						$row[$i + 1] = $row[$i] - 1;
						$column[$i + 1] = $column[$i]; 
						break;
					case 'right':
						$sql = sprintf("UPDATE %s SET %s=%d WHERE row=%s", $myinfo["name"], $column[$i], $boat, $row[$i]);
						mysql_query($sql);
						$column[$i + 1] = $alphabet[todigit($column[$i]) + 1];
						$row[$i + 1] = $row[$i];
						break;
					default:
						printf("something is not right");
						return false;	
						break;
				}
			}
			
			// update users to show that ship is placed
			$placed = $boat . "placed";
			$sql = sprintf("UPDATE users SET %s=1, ship_selected=0 WHERE uid='%s'",$placed, $_SESSION["uid"]);
			mysql_query($sql);
			
			// if all boats are now placed, set game status to play.		
			$sql = sprintf("SELECT * FROM users WHERE uid='%s'",$_SESSION["uid"]);
			$return = mysql_query($sql);
			$myinfo = mysql_fetch_array($return);
			all_ships_placed($myinfo);
		}
	}
	
	header("Content-type: text/xml");
		
	// write xml	
	
	echo "<info boat='$boat' length='$b_length' orient='$orient' success='$status' line='$line'>\n\t";

		for ($i = 0; $i < $b_length; $i++)
		{
			$position = $board . $row[$i] . $column[$i];
			if ($status || $i == 0)
			echo "<shippart position='$position'></shippart>";
		}
	echo "</info>";
		