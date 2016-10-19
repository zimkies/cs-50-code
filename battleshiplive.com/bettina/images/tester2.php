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

				<div id="logo" align="center">		
					<a href="index.php"><img alt="Bettina's Estate Sales" border="0" 
					src="images/logo2.gif" /></a>
				</div>
			</div>		
			<div id="navcontainer" align="right">
			<ul>
				<li><a href="index.php">Auctions</a></li>
				<li><a href="about.php">About</a></li>
				<li><a href="contact.php">Contact</a></li>
				<li><a href="admin_index.php">Administration</a></li>
			</ul>
			</div>
	
		</div>

		<div id="content">
				<div id="content_title">
				Picture Specifications
				</div>
				<ul id="paragraph"><h3>Specifications</h3>
				<li>Mini pictures should should be set to 70x100 px (portrait), or 150x100 px (landscape)</li>
				<li>Big pictures should be set to 300x200</li>
				<li>If no picture is available yet, simply upload a default picture of the required size</li>
				</ul>

				<div id="contact_message">
				<h3>To resize images:</h3>
				<ul id="paragraph"><h4>PC</h4>
				<li>Right click on an image and open it with Microsoft Office Picture Manager.</li>
				<li>Under Picture, click resize.</li>
				<li>Under the Resize settings (on the right side), click the custom width x height radio button.</li>
				<li>Set the height option (the second input) to be the maximum height required.</li>
				<li>Click OK.</li>
				<li>Under File, click Save As, and save it under a new name.</li>
				<li>You can now upload the new picture onto the web.</li>
				</ul>
				<ul id="paragraph"><h4>Mac</h4>
				<li>Open the pictures in iphoto</li>
				<li>Click export pictures under file</li>
				<li>Select the file export tab.</li>
				<li>Set the height option to be the maximum height required.</li>
				<li>Click OK.</li>
				<li>Save the image under a new name</li>
				<li>You can now upload the new picture onto the web.</li>
				</ul>
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
					<div id="copyright"> &copy;2007 The Bettina Network, Inc. See <a href="user_agreement.php">User Agreement</a> and <a href="privacy_policy.php">Privacy Policy.</a></div>
				</td>
			</tr>
		</table>
	</div>	
</div>
</body>

</html>
