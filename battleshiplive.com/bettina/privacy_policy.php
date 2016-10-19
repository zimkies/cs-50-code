<?php

    // require common code
    require_once("includes/common.php"); 
	$default_pic = "images/auctions/default.jgp";
?>
	
<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Bettina's Estate sales</title>
<link href="css/styles1.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
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
				Privacy Statement
				</div>

				<div id="privacy_message">
				<p>What follows is the "Privacy Policy" for the estate sale web site.</P>
<p>
"The Bettina Network, inc. does not collect information about any of its clients.  The information we have is the information you give us in the process of making a purchase and/or making a bid at one of the Bettina Sales.  The Bettina Network, inc. does not share its clients information with anyone.</P>
<p>
That information is not shared, not sold, not rented, not loaned, not given out in anyway to anyone.  It is considered propriety to the Bettina Network.  Most of you who know us know how intense we are about the right to privacy and how large privacy issues are for us.  We treat our mailing lists, our e-mail lists, our customer lists in the same manner and with the same fierce protection.</P>

<p>This Privacy Policy applies to current and former consumer customers of the Bettina Network, inc. in any of its transactions."</p>
				
			<br/> 
			<br/> 
		<div align="center">
			<a href="login.php">Login </a>here to bid in the auction.<br />
			Not registered? <a href="register.php">Register</a> here.						
		</div>
		<div align="right">
			 <a href="admin_index.php">Administration</a> 
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
