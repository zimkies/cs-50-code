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
<link href="css/styles.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
</head>

 <body>
	<div id="page">
		<div id="menu">
			 Welcome, 
				<? if (isset($_SESSION["uid"]))
						echo $_SESSION["name"];
					else 
						echo "guest";
				?>
		</div>
		<div id="logo" align="center">		
		  <a href="index.php"><img alt="Bettina's Estate Sales" border="0" 
		  src="images/logo2.gif" /></a>
		</div>

		<div id="content">	
			<? 
				$auctions = mysql_query("SELECT * FROM auctions WHERE current=TRUE");
				echo '<br /> <div align="center"> We currently have the following auctions:</div><br />';
				$counter = 0;	
				while ($row = mysql_fetch_array($auctions))
				{
					if ($counter == 0)
					{
						echo '<table  cellspacing="0" align="center" 
						class="outertable"> <tr><td> <table align="center" cellspacing="0"  class="innertable">
					<tr>
						<th>Picture</th>
						<th>Auction name</th>
						<th>Auction End time</th>
					</tr>';
					}
					
					echo "<tr>";
					if ($row["minpic"])
						echo 	"<td><img src='" . $row["minpic"]
						. "' alt='" . $row['name'] . 	"'/></td>";
					else
						echo 	"<td><img src='" . $default_pic 
						. "' alt='Default image' /></td>";
					$link = "auction_items.php?auction=". $row["name"];
					echo '<td> <a href="' . $link . '">' 
						. $row["name"] . '</a></td>';
					echo 	"<td>" . $row["end"] . "</td>";
					echo 	"</tr>";
					$counter += 1;
				}
				if ($counter == 0)
					echo "There are currently no open auctions";
				else
					echo "</table></td></tr></table>";
			?>

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
	
	<div id="footer">
	<a href="http://jigsaw.w3.org/css-validator/check/referer">
	<img style="border:0;width:88px;height:31px"
	src="http://jigsaw.w3.org/css-validator/images/vcss-blue"
	alt="Valid CSS!" />
	</a>
	<a href="http://validator.w3.org/check?uri=referer"><img
	style="border:0;width:88px;height:31px"
	src="http://www.w3.org/Icons/valid-xhtml10-blue"
	alt="Valid XHTML 1.0 Transitional"/></a>
	</div>
</div>
</body>

</html>
