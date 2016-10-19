<?php
	/****************************************************************
	 ch_reload.php called by challenge.php every few seconds to
	 update challengers
	 ***************************************************************/
	
	// require common code and variables used throughout file
    require_once("includes/common.php");
	
	// select my information
    $sql = sprintf("SELECT * FROM users WHERE uid='%s'",$_SESSION["uid"]);
    $return = mysql_query($sql);
	$myinfo = mysql_fetch_array($return);
	$status = '';
	$ourname = $myinfo["name"];
	
	// get all users 
	$sql = sprintf("SELECT name, challenger FROM users WHERE ingame=0", $_SESSION["uid"]);
	$name = mysql_query($sql);
	
	if ($myinfo["ingame"] == 1)
		$status = 'place';
	
	// show that the following is xml
	header("Content-type: text/xml");
		
	// write xml users table xml
	echo "<users status='$status' name='$ourname'>";
	
	// if any challengers exist, create xml data with their names and challengers
	if ($name)
	{
		while ($names = mysql_fetch_array($name))
		{
			$person = $names["name"];
			$challenger = $names["challenger"];
			echo "<people name='$person' challenger='$challenger'></people>\n\t";
		}
	}
	echo "</users>";