<?php
	/****************************************************************
	 challenge2.php called by challenge.php when the player challenges
	 a person. It reloads the page and updates the database with 
	 the new challenger
	 ***************************************************************/
    
	// require common code
    require_once("includes/common.php");
	
	// get your own name
	$sql = sprintf("SELECT name FROM users WHERE uid='%d'", $_SESSION["uid"]);
	$name = mysql_query($sql);	
	$name = mysql_fetch_array($name);
	$name = $name["name"];
	
	// update challengers in table users
	$sql = sprintf("UPDATE users SET challenger='%s' WHERE name='%s'", 
		$name, $_POST["challenged"]);
	mysql_query($sql);
	
	// redirect back to challenge.php
	redirect("challenge.php");
?>