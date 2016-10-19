<?php

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
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <title>Mufpumi Log In</title>
  </head>

  <body>

    <div align="center">
      <a href="index.php">
      <img alt="Mufpumi" border="0" src="images/logo2.gif" /></a>
    </div>

    <div align="center">
      <form action="login2.php" method="post">
        <table border="0">
          <tr>
            <td class="field">Username:</td>
            <td><input name="username" type="text" /></td>
          </tr>
          <tr>
            <td class="field">Password:</td>
            <td><input name="password" type="password" /></td>
          </tr>
        </table>
        <div style="margin: 10px;">
          <input type="submit" value="Log In" />
        </div>
        <div style="margin: 10px;">
          or <a href="register.php">register</a> for an account
        </div>
      </form>
    </div>

  </body>

</html>
