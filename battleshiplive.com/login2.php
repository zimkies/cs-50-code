<?php

    // require common code
    require_once("includes/common.php"); 

    // escape username and password for safety. Check that username doesn't have spaces, and contains letters
    $username = mysql_real_escape_string($_POST["name"]);
	
	if (!preg_match("/^[a-zA-Z]+$/", $username))
		apologize("Your name can only contain letters");
	
    // prepare SQL
    $sql = sprintf("INSERT INTO users (name, ingame) VALUES ('%s', false)",
                   $username);

    // if we found a row, tell user to choose a new name
    if ($result = mysql_query($sql) == FALSE)
		apologize("Username is already in use");
	
	// redirects to challenge.php
    $_SESSION["uid"] = mysql_insert_id();
		redirect("challenge.php");
