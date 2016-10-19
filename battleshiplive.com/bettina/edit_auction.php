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
			<div id="content_title">
				Administration: Auctions
				</div>
			<div align="center">
				<form action="edit_auction2.php" method="post" 
				  enctype="multipart/form-data">
					<table border="0">
					  <tr>
						<td class="field">Name of auction:</td>
						<td><input name="auction" value="<? echo $row["name"];?>" type="text" />
						<input name="old_auction" value="<? echo $row["name"];?>" type="hidden" /></td>
					  </tr>
					  <tr>
						<td class="field">Start date (YYYY-MM-DD):</td>
						<td><input name="startdate" value="<? echo $start[0];?>" type="text"/>
						</td>
						<td class="field">Start time (hr:min:sec):</td>
						<td><input name="starttime" type="text" value="<? echo $start[1];?>" /></td>
					  </tr>					  
					  <tr>
						<td class="field">End date (YYYY-MM-DD):</td>
						<td><input name="enddate" type="text" value="<? echo $end[0];?>"/></td>
						<td class="field">End time (hr:min:sec):</td>
						<td><input name="endtime" type="text" value="<? echo $end[1];?>"/></td>
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