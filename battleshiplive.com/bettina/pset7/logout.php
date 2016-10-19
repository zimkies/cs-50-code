<?php

    // require common code
    require_once("includes/common.php"); 

    // log out current user, if any
    $_SESSION = array();
    if (isset($_COOKIE[session_name()])) 
        setcookie(session_name(), "", time() - 42000, "/");
    session_destroy();

?>

<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

  <head>
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <title>Mupfumi logout</title>
  </head>

  <body>

    <div align="center">
      <a href="index.php"><img alt="Mupfumi" border="0" src="images/logo2.gif" /></a>
    </div>

    <div align="center">
      TTFN
      <br /><br />
      or <a href="login.php">log in</a> again
    </div>

  </body>

</html>
