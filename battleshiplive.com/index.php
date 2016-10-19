<?php
	/****************************************************************
	 index.php logs any old players out and redirects them 
	 to the login.php login page
	 ***************************************************************/

    // require common code
    require_once("includes/common.php");

    // log current user out, if any
    $_SESSION = array();
    if (isset($_COOKIE[session_name()]))
        setcookie(session_name(), "", time() - 42000, "/");
    session_destroy();
	
	redirect("login.php");

?>
