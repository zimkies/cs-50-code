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
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>Mupfumi Register</title>
  </head>

<body bgcolor="#336633">
	<table bgcolor="#99cc66" align="center" width="80%"> 
		<tr> 
			<td>
				<div align="center">
					<a href="index.php"><img alt="Mupfumi" border="0" src="images/logo2.gif" /></a>
				</div>

				<div align="center">
				  <form action="register2.php" method="post">
					<table border="0">
					  <tr>
						<td class="field">Username:</td>
						<td><input name="username" type="text" /></td>
					  </tr>
					  <tr>
						<td class="field">Password:</td>
						<td><input name="password" type="password" /></td>
					  </tr>
					  <tr>
						<td class="field">Retype Password:</td>
						<td><input name="password2" type="password" /></td>
					  </tr>
					  <tr>
						<td class="field">Email:</td>
						<td><input name="email" type="text" /></td>
					  </tr>
					</table>
					<div style="margin: 10px;">
					  <input type="submit" value="Register" />
					</div>
					<div style="margin: 10px;">
					  or <a href="login.php">login</a>
					</div>
				  </form>
				</div>
			</td>
		</tr>
	</table>