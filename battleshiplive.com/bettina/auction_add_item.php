<?php

    // require common code, check for admin
    require_once("includes/common.php");
	admin_check();

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
				Administration: Items
			</div>
			<div id="message">
			<p>To create a new item, fill in the following fields. See our picture <? echo '<a href="picture_specifications.php">specifications</a>';?> to ensure your pictures are the right size.</p>	
			</div>
			<div align="center">
				  <form action="auction_add_item2.php" method="post" enctype="multipart/form-data">
					<table border="0">
					  <tr>
						<td class="field">Name of auction to be sold in:</td>
						<td><input name="auction" value="<?echo $_GET['auction'];?>" type="text" />
						<input type="hidden" name="MAX_FILE_SIZE" value="<? echo MAX_BIG_PICTURE_UPLOAD_SIZE;?>"/> </td>
					  </tr>
					  <tr>
						<td class="field">The name of the item:</td>
						<td><input name="name" type="text" /></td>
					</tr>
					<tr>
						<td class="field">Start price (in dollars):</td>
						<td><input name="startprice" type="text" /></td>
					</tr>					  
					<tr>
						<td class="field">Description of item:</td>
						<td><input name="description" type="text" /></td>
					</tr>
					<tr>
						<td class="field">Mini-picture of item</td>
						<td><input id="minpic" type="file" name="minpic" /></td>
					  </tr> 
					  <tr>
						<td class="field">Larger picture of item</td>
						<td><input id="bigpic" type="file" name="bigpic"  /></td>
					  </tr> 
					</table>
					<div style="margin: 10px;">
					  <input type="submit" value="Add item" />
					</div>
					<div style="margin: 10px;">
					 or return to the <a href="admin_index.php">admin</a> page.
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
