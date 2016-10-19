<?php

	header("Content-type: text/xml:");	
    
	// require common code
    require_once("includes/common.php");
	$board = $_GET["board"];
	$row = $_GET["row"];
	$column = $_GET["column"];
	$sunk = '';

	// select my information
    $sql = sprintf("SELECT * FROM users WHERE uid='%s'",$_SESSION["uid"]);
    $return = mysql_query($sql);
	$myinfo = mysql_fetch_array($return);
	
	// get table information
	$sql = sprintf("SELECT %s FROM %s WHERE row='%s'",$column, $myinfo["challenger"], $row);
	$return = mysql_query($sql);
	$status = mysql_fetch_array($return);
	$status = $status[$column];
	$turn = $myinfo["turn"];
	
	// make sure it is your turn. If not, prevent moves;
	if ($turn == 0)
	{
		$line = "You can't play yet!";
		$status = 'no_turn';
	}
	
	// make sure game is not already won. If so, prevent any moves
	else if ($myinfo["win"] == 1)
	{
		$line = "You have won!";
		$status = 'won';
	}	
	
	// else update table depending on which tile was shot at
	else
	{		
		// set your turn to 0 and opponent to 1;
		$sql3 = sprintf("UPDATE users SET turn=1 WHERE name='%s'", $myinfo["challenger"]);
		mysql_query($sql3);
		$turn = 0;
		$sql3 = sprintf("UPDATE users SET turn=0 WHERE name='%s'", $myinfo["name"]);
		mysql_query($sql3);
		
		// depending on what is located at the place shot at, update table by setting the position
		// to 6 if it is a miss, and 7 if it is a hit.
		switch ($status)
		{
			case 0:
				$sql2 = sprintf("UPDATE %s SET %s='6' WHERE row=%s", $myinfo["challenger"], $column, $row);
				mysql_query($sql2);
				break;
			case 1: 
				$sql2 = sprintf("UPDATE %s SET %s='7' WHERE row=%s", $myinfo["challenger"], $column, $row);
				mysql_query($sql2);
				if (sunk($myinfo["challenger"], 1))
				{
					$sql3 = sprintf("UPDATE users SET %s=2 WHERE name='%s'", '1placed', $myinfo["challenger"]);
					mysql_query($sql3);
					$sunk = 1;
					$line = "You sunk the Patrol Boat!";
					if (won($myinfo["challenger"]))
					{
						$line = $line . " CONGRATULATIONS! YOU WON!";
						$sql3 = sprintf("UPDATE users SET win='1' WHERE uid=%s", $myinfo["uid"]);
					}
				}
				break;
			case 2:
				$sql2 = sprintf("UPDATE %s SET %s='7' WHERE row=%s", $myinfo["challenger"], $column, $row);
				mysql_query($sql2);
				
				if (sunk($myinfo["challenger"], 2))
				{
					$sql3 = sprintf("UPDATE users SET %s=2 WHERE name='%s'", '2placed', $myinfo["challenger"]);
					mysql_query($sql3);
					$sunk = 2;
					$line = "You sunk the Submarine!";
					if (won($myinfo["challenger"]))
					{	
						$line = $line . " CONGRATULATIONS! YOU WON!";
						$sql3 = sprintf("UPDATE users SET win='1' WHERE uid=%s", $myinfo["uid"]);			
					}
				}		
				break;			
			case 3:
				$sql2 = sprintf("UPDATE %s SET %s='7' WHERE row=%s", $myinfo["challenger"], $column, $row);				
				mysql_query($sql2);
				
				if (sunk($myinfo["challenger"], 3))
				{
					$sql3 = sprintf("UPDATE users SET %s=2 WHERE name='%s'", '3placed', $myinfo["challenger"]);
					mysql_query($sql3);
					$line = "You sunk the Destoyer!";
					$sunk = 3;
					if (won($myinfo["challenger"]))
					{
						$line = $line . " CONGRATULATIONS! YOU WON!";
						$sql3 = sprintf("UPDATE users SET win='1' WHERE uid=%s", $myinfo["uid"]);
					}		
				}
				break;		
			case 4:
				
				$sql2 = sprintf("UPDATE %s SET %s='7' WHERE row=%s", $myinfo["challenger"], $column, $row);
				mysql_query($sql2);

				if (sunk($myinfo["challenger"], 4))
				{
					$sql3 = sprintf("UPDATE users SET %s=2 WHERE name='%s'", '4placed', $myinfo["challenger"]);
					mysql_query($sql3);
					$line = "You sunk the Battleship!";
					$sunk = 4;
					if (won($myinfo["challenger"]))
					{
						$line = $line . " CONGRATULATIONS! YOU WON!";
						$sql3 = sprintf("UPDATE users SET win='1' WHERE uid=%s", $myinfo["uid"]);
					}
				}
				break;		
			case 5:				
				$sql2 = sprintf("UPDATE %s SET %s='7' WHERE row=%s", $myinfo["challenger"], $column, $row);
				mysql_query($sql2);
			
				if (sunk($myinfo["challenger"], 5))
				{
					$sql3 = sprintf("UPDATE users SET %s=2 WHERE name='%s'", '5placed', $myinfo["challenger"]);
					mysql_query($sql3);
					$sunk = 5;
					$line = "You sunk the Aircraft Carrier!";
					if (won($myinfo["challenger"]))
					{
						$sql3 = sprintf("UPDATE users SET win='1' WHERE uid=%s", $myinfo["uid"]);
					}
				}
				break;		
			case 6:
			case 7:
				break;
			default:
				apologize("Table is wrong");
		}
	}

	mysql_query($sql3);
	

	// write xml
    echo "<info board='$board' row='$row' column='$column' sunk='$sunk' turn='$turn' status='$status' line='$line'></info>";
	