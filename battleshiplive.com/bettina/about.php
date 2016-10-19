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
				About Bettina's Network
				</div>

				<div id="contact_message">
				<ul id="slogan">Respected For Integrity</ul>
				<ul id="slogan">Admired For providing value</ul>
				
				<ul>We have developed a new, streamlined method for converting valuables accumulated over a lifetime into cash</ul>
				<ul>In the case of large estates or downsizing, our specialists will have an initial screening to help clients determine what should be sold, what should be donated to charities, and so on.</ul>
				<ul> Our strategist will contact the most viable potential buyers and appoint a sales date</ul>
				<ul>We handle all of the marketing and advertising.</ul>
				<ul>Items which may be hard to price because of a fast moving market or have an indeterminate market value are put into the sale as silent auction items to gain the best possible price</ul>
				<ul><Where discretion is of paramount importance, we hold a private sale for a carefully selected group of buyers. </ul>
				
				<ul id="slogan" align="right">Our Strengths lay in:</ul>
				<ul id="slogan" align="center">Imagination</ul>
				<ul id="slogan" align="center">Experience</ul>
				<ul id="slogan" align="center">Humanity</ul>
				<ul id="slogan" align="center">Resources</ul>
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
