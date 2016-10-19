<?php

    // require common code
    require_once("includes/common.php");
	$default_pic = "images/auctions/default.jgp";
	$order_option = mysql_real_escape_string($_POST["order_option"]);
	$page = mysql_real_escape_string($_GET["page"]);
	$auction = mysql_real_escape_string($_GET["auction"]);
	if (!$page)
		$page = 1;
	
	// if auction is not current, apologize
	$sql = sprintf("SELECT * FROM auctions WHERE end >= ADDTIME(NOW(), '3:0:0') AND name='%s'", $auction);
	//apologize($sql);
    $check = @mysql_query($sql);   
	if ((!$check) || mysql_num_rows($check) == 0)
		apologize("This auction either does not exist or is no longer available for bidding!");
	$auction_info = mysql_fetch_row($check);
	
	// obtain auction items, and checks to see auction is current. 
    $sql = sprintf("SELECT SQL_CALC_FOUND_ROWS * FROM `%s`", $auction);
	// order the list according to the order option selected
	$sql = $sql . " " . @$G_ITEM_OPTIONS[$order_option][1];
	// limit the number of items found.
	$sql  = $sql . " " . " LIMIT " . ($page-1) * MAX_DISPLAY_NUMBER . ", " . MAX_DISPLAY_NUMBER;
    $items = mysql_query($sql);   
	if ((!$items) || mysql_num_rows($items) == 0)
		apologize("This auction has no items, or the page number is outside the range of possible pages!");
	
	// get the total possible rows available.
	$found_rows_array = mysql_fetch_row(mysql_query("SELECT FOUND_ROWS()"));
	$found_rows = $found_rows_array[0];
	
	// make sure that the page is in the range
	if ($page - 1 < 0 || $page - 1 > $found_rows / MAX_DISPLAY_NUMBER)
		apologize("This page could not be loaded since it is outside the range of possible pages");
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
				Items
				</div>
			<div align="right" id="order_options">
				<?
					echo "<form action=\"" . $SERVER['REQUIRE_URI']. "\" method=\"post\"";
					echo '>';
					echo ' <select  name="order_option">';
					for ($i = 0; $i < sizeof($G_ITEM_OPTIONS); $i++)
					{
						echo "<option value=\"" . $i . '"';
						if ($order_option == $i)
							echo " selected";
						echo  ">" . $G_ITEM_OPTIONS[$i]["0"] . "</option>";
					}	
					echo "</select>
					<input type='submit' value=update /></form>";
				?>
			</div>	
			<div align="center">
The following is a list of items we are auctioning under <? echo $auction; ?>. <br />
To place a bid on any item, simply click on its name and follow the instructions:
				<br />
				<table  cellspacing="0" class="outertable">
				<tr><td>
				<table align="center" cellspacing="0" class="innertable">
					<tr>
						<th>Picture</th>
						<th>Item name</th>
						<th>Current price</th>
						<th>Description</th>
					</tr>
				 <? while ($row = mysql_fetch_array($items)) 
					{ 
						echo "<tr>";
						if ($row["minpic"])
							echo 	"<td><img src='" . $row["minpic"]
							. "' alt='" . $row['name'] . 	"' /></td>";
						else
							echo 	"<td><img src='" . $default_pic 
							. "' alt='Default image' /></td>";
						$link = '<a href="bid.php?item=' . $row["item_name"]; 
						$link = $link . '&amp;auction=' . $auction . '">';
						echo 	"<td>" . $link . $row["item_name"] 
							."</a></td>";
						echo 	"<td>" . $row["currentprice"] . "</td>";
						// calculate time left - to do....
						echo  "<td>" . $row["description"] . "</td> ";			
						echo   "</tr>";
					} ?>   
				  
				</table>
				</td></tr></table>
			</div>
			<div id="page_navigator" align="center">
			<form action="<? echo get_append($_SERVER["REQUEST_URI"], "page", $page - 1); ?>" 				method="post">
			<? foreach ($_POST as $key => $value)
			{
				echo '<input type="hidden" value="' . $value . '" name="' . $key . '" />';
			}
				echo '<input type="submit" value="prev" ';
				if ($page <= 1)
				{
					echo " disabled />";
				}
				echo '</form>';
			?>
				<form action="<? echo get_append($_SERVER["REQUEST_URI"], "page", $page + 1); ?>" 				method="post">
			<? foreach ($_POST as $key => $value)
			{
				echo '<input type="hidden" value="' . $value . '" name="' . $key . '" />';
			}
				echo '<input type="submit" value="next" ';
				if ($page >= ceil($found_rows / MAX_DISPLAY_NUMBER))
				{
					echo " disabled />";
				}
				echo '</form>';				
			?>
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

