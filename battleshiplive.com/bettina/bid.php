<?php

    // require common code, require login.
    require_once("includes/common.php");
	require_login();
	
	// obtain auction items, and checks to see auction is current. 
	$item = $_GET["item"];
	$auction = $_GET["auction"];
	$default_pic = "images/auctions/bigdefault.jgp";
    
	// This needs to be improved... as in make sure it works, and increase efficiency if possible.
	$sql = sprintf("SELECT `%s`.* FROM `%s` JOIN auctions ON auctions.end >= ADDTIME(NOW(), '3:0:0') AND auctions.name='%s' AND `%s`.item_name='%s'", 
		$auction, $auction, $auction, $auction, $item);

    $item_info = mysql_query($sql);   
	if ((!$item_info) || mysql_num_rows($item_info) == 0)
		apologize("This auction either does not exist, or is no longer available for bidding!");
	
	// for now, we need this to get the end time
	$sql2 = sprintf("SELECT * FROM auctions WHERE name='%s'", $auction);
	$auction_info = mysql_query($sql2); 
	if ((!$auction_info) || mysql_num_rows($auction_info) == 0)
		apologize("This auction does not exist!");

	
	//apologize($sql2);
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
				Auctions
				</div>
			<div align="center"><br />
				<table  cellspacing="0" class="outertable">
				<tr><td>
				<table align="center" cellspacing="0" class="innertable">
					<tr>
						<th>Picture</th>
						<th>Item name</th>
						<th>Item id</th>
						<th>Current price</th>
						<th>End time</th>
						<th>Description</th>
					</tr>
				 <? $row = mysql_fetch_array($item_info);
					$a_row = mysql_fetch_array($auction_info);
					if ($row)
					{ 
						echo "<tr>";
						if ($row["bigpic"])
							echo 	"<td><img src='" . $row["bigpic"]
							. "' alt='" . $row['name'] . 	"' /></td>";
						else
							echo 	"<td><img src='" . $default_pic 
							. "' alt='Default image' /></td>";
						$link = '<a href="bid.php?item=' . $row["item_name"];
						$link = $link . '&amp;auction=' . $auction . '">';
						
						echo 	"<td>" . $link . $row["item_name"] 
							."</a></td>";
						echo 	"<td>" . $row["item_id"] . "</td>";
						echo 	"<td>" . $row["currentprice"] . "</td>";
						
						// calculate time left - to do....
						echo  "<td>" . $a_row["end"] . "</td> ";	
						echo	"<td>" . $row["description"] . "</td>";
						echo   "</tr>";
					} 
					else 
						apologize("The specified item is not available");?>  
				</table> </td></tr> </table>
				<br/>
				<form action="bid2.php" method="post">
				<table>	
				<tr>				
					<td class="field"> How much do you want to bid? 
					(at least $<? echo min_bid($row["currentprice"]); ?>)
					</td>
					<td> <input name="bidprice" type="text" /> </td>
					<td> <input name="auction" type="hidden" 
						value="<?= $auction?>" /> </td>
					<td> <input name="item" 
						value="<?= $item?>" type="hidden"/> </td>
					<td> <input name="item_id" 
						value="<?= $row["item_id"]?>" type="hidden"/> </td>
					</tr>
				</table>
				<div style="margin: 10px;">
					<input type="submit" value="Bid" />
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
