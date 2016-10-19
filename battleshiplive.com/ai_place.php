<?php
	/****************************************************************
	 ai_place.php called by challenge.php, it creates and initializes
	 the boards, places the ai_ships, and then redirects to play.php
	 ***************************************************************/
    
	// require common code
    require_once("includes/common.php");
	
	// get your own name
	$sql = sprintf("SELECT name FROM users WHERE uid='%d'", $_SESSION["uid"]);
	$name = mysql_query($sql);	
	$name = mysql_fetch_array($name);
	$name = $name["name"];
	
	// update challengers in table users
	$sql = sprintf("UPDATE users SET challenger='%s', ingame='1' WHERE name='%s'", 
		'ai_' . $name, $name);
	mysql_query($sql);
	

	//set ai's challenger to be you
	$sql = sprintf("INSERT INTO users (name, ingame, challenger) VALUES ('%s', '2', '%s')",
                   'ai_' . $name, $name);
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
	$sql = sprintf("CREATE TABLE %s %s", $name, $columns);
	mysql_query($sql);
	
	// create ai's table
	$sq2 = sprintf("CREATE TABLE %s %s", 'ai_' . $name, $columns);
	mysql_query($sq2);
	
	// fill tables with rows
	$sql = sprintf("INSERT INTO %s (row) VALUES (0),(1),(2),(3),(4),(5),(6),(7),(8),(9)", $name);
	$sq2 = sprintf("INSERT INTO %s (row) VALUES (0),(1),(2),(3),(4),(5),(6),(7),(8),(9)", 'ai_' . $name);	
	mysql_query($sql);
	mysql_query($sq2);
	
	
	// set up the enemy's table
	$table;
	$orient = array('down', 'left', 'right', 'up');

	for ($boat = 1; $boat <= 5; $boat++)
	{
		
		// randomize numbers while they are not valid
		do	
		{
			$col = rand(0,9);
			$row = rand(0,9);
			$orient_num = rand(0,3);
		}
		while (!valid_move($orient[$orient_num], $boat, $table, $row, $col));
		
		// put the numbers into an array	
		for ($i = 0; $i < boat_length($boat); $i++)
		{
			switch ($orient[$orient_num])
			{	
				case 'down':
					$table[$row][$alphabet[$col]] = $boat;
					$row += 1;
					break;
				case 'left':
					$table[$row][$alphabet[$col]] = $boat;
					$col -= 1;
					break;
				case 'up':
					$table[$row][$alphabet[$col]] = $boat;
					$row -= 1;
					break;
				case 'right':
					$table[$row][$alphabet[$col]] = $boat;
					$col += 1;
					break;
				default:
					return false;	
					break;
			}
		}
	}
	
	// add each row to the mysql database table.
	for ($i = 0; $i < 10; $i++)
	{		
		$rowQ = '';
		for ($j = 0; $j < 10; $j++)
		{
			if ($table[$i][$alphabet[$j]])
				$rowQ = $rowQ . $alphabet[$j] . '=' . $table[$i][$alphabet[$j]] . ',';
		}
		
		// remove the last comma in the string
		if ($rowQ)
		{	
			$rowQ = substr_replace($rowQ,"",-1);		
			$sql = sprintf("UPDATE %s SET %s WHERE row=%s", 'ai_' . $name, $rowQ, $i);
			mysql_query($sql);
		}
	}
	
	// redirect you to start up your table
	redirect("placeships.php");
