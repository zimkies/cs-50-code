<?php

    // require common code
    require_once("includes/common.php");
	admin_check();
	
	// Get variables
	$auction = mysql_real_escape_string($_GET["auction"]);
	$item_id = mysql_real_escape_string($_GET["item_id"]);

    $sql = sprintf("SELECT * FROM `%s` WHERE item_id=%d", $auction, $item_id);
    $users = mysql_query($sql);   
	$row = @mysql_fetch_array($users);
	if(!$row)
		apologize("There was an error in trying to edit this item");
?>

<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

  <head>
   <link href="css/styles1.css" rel="stylesheet" type="text/css" />
   <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

    <title>Bettina's estate sales</title>
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
			<div align="center">
				  <form action="edit_item2.php" method="post" 
				  enctype="multipart/form-data">
					<input name="item_id" value="<? echo $row["item_id"];?>" type="hidden" />
					<input name="auction" value="<? echo $auction;?>" type="hidden" />
					<table border="0">
					  <tr>
						<td class="field">Name of item:</td>
						<td><input name="item" value="<? echo $row["item_name"];?>" type="text" /></td>
					  </tr>
					  <tr>
						<td class="field">Description:</td>
						<td><input name="description" type="text" value="<? echo $row["description"];?>"/>
						</td>
					  </tr>					  
					</table>
					<div style="margin: 10px;">
					  <input type="submit" value="Change auction" />
					</div>
					<div style="margin: 10px;">
					 or return to the <a href="admin_index.php">admin</a> page.
					</div>
				 </form>
			</div>
			<br/> 
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