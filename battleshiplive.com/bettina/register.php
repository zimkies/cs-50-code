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
    <link href="css/styles1.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />	
    <title>Bettina's Auctions Register</title>
  </head>
			
<body>
	<div id="page">
		<div id="menu">
			<div id="loginbar">
			<div id="welcome">
			 Welcome, 
				<? if (isset($_SESSION["uid"]))
						echo $_SESSION["name"];
					else 
						echo "guest";
				?>
			</div>
			<ul>
				<? if (isset($_SESSION["uid"]))
				{
					echo '<li><a href="logout.php">logout</a></li>';
					echo '<li><a href="register.php">re-register</a></li>';
				}
				else 
				{
					echo '<li><a href="login.php">login</a></li>';
					echo '<li><a href="register.php">register</a></li>';
				}
				?>
			</ul>
			</div>
			<div id="navcontainer" align="right">
			<ul>
				<li><a href="admin_index.php">Administration</a></li>
				<li><a href="contact.php">Contact</a></li>
				<li><a href="about.php">About</a></li>
				<li><a href="index.php">Auctions</a></li>
				<li><a href="index.php">Home</a></li>
			</ul>
			</div>			
			
		</div>

		<div id="content">	
			<div id="content_title">
			Register				
			</div>
			<div align="center">
				 <form action="register2.php" method="post">
					<table border="0">
					  <tr>
						<td class="field">First name:</td>
						<td><input name="firstname" type="text" /></td>
					  </tr>
					  <tr>
						<td class="field">Last name:</td>
						<td><input name="lastname" type="text" /></td>
					  </tr>					  
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
					  <tr>
						<td class="field">Phone number</td>
						<td><input name="phone" type="text" /></td>
					  </tr>
					  <tr>
						<td class="field">Receive infrequent emails about our auctions</td>
						<td><input name="receive_email" type="checkbox" /></td>
					  </tr>
					</table>
					<div style="margin: 10px;">
					  <input type="submit" value="Register" />
					</div>
				 </form>
			</div>
		</div>
	
		<div id="footer" >
		<table width="100%">
			<tr>
				<td>
					<a href="http://jigsaw.w3.org/css-validator/check/referer">
					<img style="border:0;width:88px;height:31px"
					src="http://jigsaw.w3.org/css-validator/images/vcss-blue"
					alt="Valid CSS!" />
					</a>
					<a href="http://validator.w3.org/check?uri=referer"><img
					style="border:0;width:88px;height:31px"
					src="http://www.w3.org/Icons/valid-xhtml10-blue"
					alt="Valid XHTML 1.0 Transitional"/></a>
					Site by <a href="mailto:kies@fas.harvard.edu">Benjamin Kies</a>
				</td>
				<td>
					<div id="copyright"> &copy;1993-2009 The Bettina Network, Inc. See <a href="user_agreement.php">User Agreement</a> and <a href="privacy_policy.php">Privacy Policy.</a></div>
				</td>
			</tr>
		</table>
		</div>	
	</div>
</body>
</html>
