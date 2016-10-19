<?php

    // require common code
    require_once("includes/common.php"); 
	$default_pic = "images/auctions/default.jgp";
	admin_check();
	
	// GET, POST variables escaped
	$default_pic = "images/auctions/default.jgp";
	$order_option = mysql_real_escape_string($_POST["order_option"]);
	$page = mysql_real_escape_string($_GET["page"]);
	if (!$page)
		$page = 1;
	
	// obtain auction items, and checks to see auction is current.
	$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM auctions";
	// order the list according to the order option selected
	$sql = $sql . " " . @$G_AUCTION_OPTIONS[$order_option][1];
	// limit the number of items found.
	$sql  = $sql . " " . " LIMIT " . ($page-1) * MAX_DISPLAY_NUMBER . ", " . MAX_DISPLAY_NUMBER;
    $auctions = mysql_query($sql);   
	if (!$auctions)
		apologize("We apologize, but our auctions database is currently unavailable!");
	
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
<title>Bettina's Estate sales</title>
<link href="css/styles1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="includes/js_helpers.js"></script>

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
		<div id="content">
			<div id="content_title">
				Administration: Auctions
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
			<? 
				echo '<div align="center"> The following is a list of all auctions:</div><br />';
				$counter = 0;	
				while ($row = mysql_fetch_array($auctions))
				{
					if ($counter == 0)
					{
						echo '<table  cellspacing="0" align="center" 
						class="outertable"> <tr><td> <table align="center" cellspacing="0"  class="innertable">
					<tr>
						<th>Add Item</th>
						<th>Edit</th>
						<th>Edit Pictures</th>
						<th>Delete</th>
						<th>Picture</th>
						<th>Auction name</th>
						<th>ID</th>
						<th>Start</th>
						<th>End</th>
						<th>Current?</th>
					</tr>';
					}
					echo "<tr>";
					// echo the permission box
					echo 	'<td><a href="auction_add_item.php?auction=' . $row["name"] .
					'"><img alt="Add Item" src="images/give_permission.png" class="icon"/></a></td>';
					//echo the edit box
					echo 	'<td><a href="edit_auction.php?auction=' . $row["name"] .
					'"><img alt="edit" src="images/edit.png" class="icon"/></a></td>';
				   //echo the edit box
					echo 	'<td><a href="edit_auction_picture.php?auction=' . $row["name"] .
					'"><img alt="edit picture" src="images/edit.png" class="icon"/></a></td>';
					//echo the delete box
					$msg = "WARNING!!!!!\\n Are you sure you want to delete auction " . $row["name"] . "?\\nThis will permanently remove all information and images related to this auction.\\n Ideally, you should at least back up the database before doing this.";
					$adr = 'delete_auction.php?auction=' . $row["name"];
					echo '<td><input type="image" '							
					. ' onclick="show_confirm(' . '\'' . $msg . '\',' . '\'' . $adr . '\')" ' 
					. '" alt="delete" src="images/delete.png" '
					. ' class="icon"/></td>';
				

					if ($row["minpic"])
						echo 	"<td><img src='" . $row["minpic"]
						. "' alt='" . $row['name'] . 	"'/></td>";
					else
						echo 	"<td><img src='" . $default_pic 
						. "' alt='Default image' /></td>";
					$link = "admin_items.php?auction=". $row["name"];
					echo '<td> <a href="' . $link . '">' 
						. $row["name"] . '</a></td>';
					echo 	"<td>" . $row["uid"] . "</td>";
					echo 	"<td>" . $row["start"] . "</td>";
					echo 	"<td>" . $row["end"] . "</td>";
					echo 	"<td>" . $row["current"] . "</td>";
					echo 	"</tr>";
					$counter += 1;
				}
				if ($counter == 0)
					echo '<div align="center">There are currently no open auctions</div>';
				else
					echo "</table></td></tr></table>";
			?>
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

			<br/> 
			<br/> 
		<div align="center">
			Make a new <a href="make_auction.php">auction</a>		
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
