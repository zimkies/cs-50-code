<?php
	/****************************************************************
	 deletes a person's information and table, if any, and logs them out
	 ***************************************************************/
  
	// require common code and variables used throughout file
    require_once("includes/common.php");
	
	// select my information
    $sql = sprintf("SELECT * FROM users WHERE uid='%s'",$_SESSION["uid"]);
    $return = mysql_query($sql);
	$myinfo = mysql_fetch_array($return);
	
	// remove table
	$sql = sprintf("DROP TABLE '%s'", $myinfo["name"]);
	mysql_query($sql);
	
	// remove user from users table
	$sql2 = sprintf("DELETE FROM 'users' WHERE CONVERT( `users`.`uid` USING utf8 )=%s", $myinfo["uid"]);
	mysql_query($sql2);
	apologize($sql . $sql2);
	
	// log user out
	$_SESSION = array();
	if (isset($_COOKIE[session_name()]))
        setcookie(session_name(), "", time() - 42000, "/");
    session_destroy();
	
	redirect("challenge.php");
	
