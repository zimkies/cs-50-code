<?php

    /***********************************************************************
     * helpers.php
     *
     * Computer Science 50
     * Problem Set 7
     *
     * Helper functions.
     **********************************************************************/
	
	 // global variables
	 $alphabet = 'abcdefghij';

    /*
     * void
     * apologize($message)
     *
     * Apologizes to user by displaying a page with message.
     */

    function apologize($message)
    {
        // require template
        require_once("apology.php");

        // exit immediately since we're apologizing
        exit;
    }


    /*
     * void
     * dump($variable)
     *
     * Facilitates debugging by dumping contents of variable
     * to browser.
     */

    function dump($variable)
    {
        // dump variable using some quick and dirty (albeit invalid) XHTML
        if (!$variable && !is_numeric($variable))
            print("Variable is empty, null, or not even set.");
        else
            print("<pre>" . print_r($variable, true) . "</pre>");

        // exit immediately so that we can see what we printed
        exit;
    }

    /*
     * void
     * redirect($destination)
     * 
     * Redirects user to destination, which can be
     * a URL or a relative path on the local host.
     *
     * Because this function outputs an HTTP header, it
     * must be called before caller outputs any XHTML.
     */

    function redirect($destination)
    {
        // handle URL
        if (preg_match("/^http:\/\//", $destination))
            header("Location: " . $destination);

        // handle absolute path
        else if (preg_match("/^\//", $destination))
        {
            $host = $_SERVER["HTTP_HOST"];
            header("Location: http://$host$destination");
        }

        // handle relative path
        else
        {
            // adapted from http://www.php.net/header
            $host = $_SERVER["HTTP_HOST"];
            $path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
            header("Location: http://$host$path/$destination");
        }

        // exit immediately since we're redirecting anyway
        exit;
    }

	// function lost($player) returns true if $player's board has been conquered.
	
	function lost($player)
	{
		if (sunk($player, 1) && sunk($player, 2) && sunk($player, 3) && sunk($player, 4) && sunk($player, 5))
			return 1;
		else return 0;		
	}
	
	// function sunk($player, $ship) checks if a ship is sunk on a player's board.
	function sunk($player, $ship)
	{
		$alphabet = 'abcdefghij';
		$sql = sprintf("SELECT * FROM %s", $player);
		$result = mysql_query($sql);
		while ($row = mysql_fetch_array($result))
		{
			for ($i = 0; $i < 10; $i++)
			{
				if ($row[$alphabet[$i]] == $ship)
					return 0;
			}
		}
		return 1;
	}
	
	// function todigit() converts a single letter  from a to j into an integer from 0 to 9.
	// WARNING! does not check for argument errors.
	
	function todigit($char)
	{
		$int = '';
		$alphabet = 'abcdefghij';
		for ($i = 0; $i < 10; $i++)
		{
			if ($alphabet[$i] == $char)
				$int = $i;
		}
		return $int;
	}
	
	// function boat_length($boat) gives the length of any boat
	
	function boat_length($boat)
	{
		if ($boat < 3)
			return ($boat + 1);
		else
			return $boat;
	}
	
	// function to check whether a person has placed a valid move or not.
	
	function valid_move($orient, $boat, $table, $row, $columndig)
	{
		$alphabet = 'abcdefghij';
		switch ($orient)
		{	
			case 'down':
				if ($row - 1 + boat_length($boat) > 9)
					return false;
				for ($i = 0; $i < boat_length($boat); $i++)
				{	
					if ($table[$row + $i][$alphabet[$columndig]])
						return false;			
				}
				break;
			case 'left':
				if ($columndig + 1 - boat_length($boat) < 0)
					return false;
				for ($i = 0; $i < boat_length($boat); $i++)
				{	
					if ($table[$row][$alphabet[$columndig - $i]])
						return false;			
				}
				break;
			case 'up':
				if ($row + 1 - boat_length($boat) < 0)
					return false;
				for ($i = 0; $i < boat_length($boat); $i++)
				{	
					if ($table[$row - $i][$alphabet[$columndig]])
						return false;
				}
				break;
			case 'right':
				if ($columndig - 1 + boat_length($boat) > 9)
					return false;
				for ($i = 0; $i < boat_length($boat); $i++)
				{	
					if ($table[$row][$alphabet[$columndig + $i]])
						return false;			
				}
				break;
			default:
				return false;
		}
		return true;
	}
	
	// adj_open checks whether the (unshot) tile has an adjecent tile that can be shot at.	
	function adj_open($table, $tile)
	{
		$alphabet = 'abcdefghij';
		$row = $tile[0];
		$col = $tile[1];
		for ($i = 0; $i < 4; $i++)
		{
			switch ($i)
			{
				case 0:
					if (!in_bounds($row + 1, todigit($col)))
						continue;
					else if (!$table[$row + 1][$col] || $table[$row + 1][$col] < 6)
						return true;
					break;
				case 1;
					if (!in_bounds($row, todigit($col) - 1))
						continue;
					else if (!$table[$row][$alphabet[todigit($col) - 1]] || $table[$row][$alphabet[todigit($col) - 1]] < 6)
						return true;
					break;
				case 2:
					if (!in_bounds($row - 1, todigt($col)))
						continue;
					else if (!$table[$row - 1][$col] || $table[$row - 1][$col] < 6)
						return true;
					break;
				case 3:
					if (!in_bounds($row, todigit($col) + 1))
						continue;
					else if (!$table[$row][$alphabet[todigit($col) + 1]] || $table[$row][$alphabet[todigit($col) + 1]] < 6)
						return true;
					break;
				default:
					break;
			}
		}
		return false;
	}
	
	// function out_bounds checks whether a shot is out of bounds
	function in_bounds($row, $coldig)
	{
		if ($row > 9 || $row < 0)
			return false;
		if ($coldig > 9 || $coldig < 0)
			return false;
		return true;
	}
	
	// function: returns true and updates users ingame to 2 if every ship in a myinfo array is placed
	function all_ships_placed($myinfo)
	{
		for ($i = 1; $i <= 5; $i++)
		{
			$placed = $i . 'placed';
			if (!$myinfo[$placed])
				break;
			else if ($i == 5)
			{
				$success = 'all_placed';
				$sql = sprintf("UPDATE users SET ingame=2, turn=1 WHERE uid='%s'",$_SESSION["uid"]);
				mysql_query($sql);
			}
		}
	}
	
	