<?php

    // require common code
    require_once("includes/common.php");
	admin_check();

    $sql = sprintf("SELECT * FROM auctions WHERE name='%s'", $_GET["auction"]);
    $users = mysql_query($sql);   
	$row = @mysql_fetch_array($users);
	if(!$row)
		apologize("There was an error in trying to edit this auctin");
	$start = explode(' ', $row["start"]);
	$end = explode(' ', $row["end"]); 
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
			<div align="center">
				<div id="message">
			<p>To change this auction's pictures, fill in the following fields. See our picture <? echo '<a href="picture_specifications.php">specifications</a>';?> to ensure your pictures are the right size.</p>	
			</div><br />
				  <form action="edit_auction_picture2.php" method="post" 
				  enctype="multipart/form-data">
				  <input type="hidden" id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" value="<? echo MAX_BIG_PICTURE_UPLOAD_SIZE;?>" />
				  <input id="auction" type="hidden" name="auction" value="<? echo $row["name"];?>" />
					<table border="0">
					 <tr>
						<td class="field">Name of auction:</td>
						<td class="field"> <? echo $row["name"];?></td>
					  </tr>
					  <tr>
						<td class="field">Mini-picture of auction</td>
						<td class="field"><input id="minpic" type="file" name="minpic" value="C:\Users\Ben\Pictures\dsmall.bmp" /></td>
					  </tr> 
					  <tr>
						<td class="field">Larger picture of auction</td>
						<td class="field"><input id="bigpic" type="file" name="bigpic" value="C:\Users\Ben\Pictures\dsmall.bmp" /></td>
					  </tr> 
					</table>
					<div style="margin: 10px;">
					  <input type="submit" value="Change Pictures" />
					</div>
					<div style="margin: 10px;">
					 or return to the <a href="admin_index.php">admin</a> page.
					</div>
				</form>
			</div>
		</div>
			<br/> 
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