<?php

	/****************************************************************
	 login.php called by index.php provides a page for users to
	 input their battlenames. It then calls challenge.php
	 ***************************************************************/
	 
    // require common code
    require_once("includes/common.php");

    // log current user out, if any
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

	<link href="css/login.css" rel="stylesheet" type="text/css" />
    <title>Battleship</title>
  </head>

  <body>

  <br /><br /><br /><br />
    <div align="center">
      <a href="index.php">
      <img alt="Battleship" border="0" " /></a>
    </div>


      <form action="login2.php" method="post" id= "form">
	  
        <table id= "login">
          <td>
            <tr class="field">			
				<div id="yourboard">Choose your battle name!</div></tr>
            <tr><input name="name" type="text" id="nametext" /></tr>
          </td>
        </table>
		
        <div style="margin: 8px;">
          <input type="submit" value="Log In" id="button" />
        </div>
		
      </form>


  </body>

</html>
