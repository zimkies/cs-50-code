<?php

    // require common code
    require_once("includes/common.php"); 

    // checks that username exists and passwords exist and are equal
    if ($_POST["password"] != $_POST["password2"])
        apologize("You inputed two different passwords!");
    
    if ($_POST["username"] == NULL || $_POST["password"] == NULL
            || $_POST["password2"] == NULL)
        apologize("You must fill in each field");
    
    // escape username and password for safety
    $username = mysql_real_escape_string($_POST["username"]);
    $password = mysql_real_escape_string($_POST["password"]);
    
    // prepare SQL
    $sql = sprintf("INSERT INTO users (username, password, cash) 
        VALUES('%s', '%s',10000.00)",$username, $password);

    // execute query
    if ($result = mysql_query($sql) == FALSE)
        apologize("Username already in use");

    // redirects to index.php if it can find uid
    $_SESSION["uid"] = mysql_insert_id();
	if ($_SESSION["uid"] != 0)
    {
		redirect("index.php");
    }
    // else report error
    else
        apologize("Invalid username and/or password!");

?>
