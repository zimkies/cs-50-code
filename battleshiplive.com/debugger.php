<?php
	/****************************************************************
	 debugger.php a file used for general debuggin purposes
	 ***************************************************************/
    
	// require common code and variables used throughout file
    require_once("includes/common.php");
	$board = 'chifan';
	$row = 3;
	$row1 = $row;
	$column = 'b';
	$column1 = $column;
	$columndig = todigit($column);

	// select my information
    $sql = sprintf("SELECT * FROM users WHERE uid='%s'",$_SESSION["uid"]);
    $return = mysql_query($sql);
	$myinfo = mysql_fetch_array($return);
	$orient = $myinfo["orientation"];
	$boat = $myinfo["ship_selected"];
	
	// get your board as array[0-9][a-j] NOTE: DO NOT USE INTEGERS TO REFERENCE the a-j part.
	$sql = sprintf("SELECT * FROM %s ", $myinfo["name"]);
	$result = mysql_query($sql);
	for ($i = 0; $i < 10; $i++)
		$table[$i] = mysql_fetch_array($result);
		
		$info = '';
		$row = 1;
		$col = 'b';
		for ($i = 0; $i < 4; $i++)
		{
			switch ($i)
			{
				case 0:
					$orient = 'down';
					break;
				case 1;
					$orient = 'left';
					break;
				case 2:
					$orient = 'up';
					break;
				case 3:
					$orient = 'right';
					break;
				default:
					break;
			}
		
			// check if the adjacent tile is valid
			if (valid_move($orient, 1, $table, $row, todigit($col)))
				$info = $info . 'a';
		}

	
	echo $info;
	
	
	echo "happpy";