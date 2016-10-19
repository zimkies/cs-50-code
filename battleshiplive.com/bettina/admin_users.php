<?php

    // require common code
    require_once("includes/common.php");
	admin_check();
	
	$order_option = mysql_real_escape_string($_POST["order_option"]);
	$page = mysql_real_escape_string($_GET["page"]);
	if (!$page)
		$page = 1;
    
	// get the list of users for this page
	$sql = sprintf("SELECT SQL_CALC_FOUND_ROWS * FROM users");
	// order the list according to the order option selected
	$sql = $sql . " " . @$G_USER_OPTIONS[$order_option][1];
	// limit the number of items found.
	$sql  = $sql . " " . " LIMIT " . ($page-1) * MAX_DISPLAY_NUMBER . ", " . MAX_DISPLAY_NUMBER;
    $users = @mysql_query($sql);
	if (!$users)
		apologize("This page could not be loaded correctly - maybe it was out of range");
	
	// get the total possible rows available.
	$found_rows_array = mysql_fetch_row(mysql_query("SELECT FOUND_ROWS()"));
	$found_rows = $found_rows_array[0];
	
	// make sure that the page is in the range
	$offset = ($page - 1) * MAX_DISPLAY_NUMBER;
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
   <script type="text/javascript" src="includes/js_helpers.js"></script>
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
				<li><a href="index.php">Auctions</a></li>
				<li><a href="about.php">About</a></li>
				<li><a href="contact.php">Contact</a></li>
				<li><a href="admin_index.php">Administration</a></li>
			</ul>
			</div>
			
		</div>

		<div id="content">	
		<div id="content_title">
				Administration: Users
				</div>
			<div align="right" id="order_options">
				<?
					echo "<form action=\"" . $SERVER['REQUIRE_URI']. "\" method=\"post\"";
					echo '>';
					echo ' <select  name="order_option">';
					for ($i = 0; $i < sizeof($G_USER_OPTIONS); $i++)
					{
						echo "<option value=\"" . $i . '"';
						if ($order_option == $i)
							echo " selected";
						echo  ">" . $G_USER_OPTIONS[$i]["0"] . "</option>";
					}	
					echo "</select>
					<input type='submit' value=update /></form>";
				?>
			</div>
			<div align="center">              
					<br/>
					<p>To edit, delete, or grant users bidding access, click on the buttons to the left of their names</p>
				<table  cellspacing="0" class="outertable">
				<tr><td>
				<table cellspacing="0" class="innertable">
					<tr>
						<th>Give permission</th>
						<th>edit</th>
						<th>delete</th>
						<th>uid</th>
						<th>username</th>
						<th>password</th>
						<th>email</th>
						<th>firstname</th>
						<th>lastname</th>
						<th>permission</th>
						<th>phone</th>
					</tr>
				 <? while ($row = mysql_fetch_array($users)) 
					{ 
						echo 	"<tr>";
						
						// echo the permission box
						if ($row["permission"] == 0)
						{
							echo 	'<td><a href="give_permission_user.php?uid=' . $row["uid"] .
						'"><img alt="give_permission" src="images/give_permission.png" /></a></td>';
						}
						else
							echo '<td></td>';
						//echo the edit box
						echo 	'<td><a href="edit_user.php?uid=' . $row["uid"] .
						'"><img alt="edit" src="images/edit.png" class="icon"/></a></td>';
						//echo the delete box
						$msg = "Are you sure you want to delete user " . $row["username"] . "?\\nA better way to do this is just to remove their bidding priveleges.";
						$adr = 'delete_user.php?uid=' . $row["uid"];
						echo '<td><input type="image" '							
						. ' onclick="show_confirm(' . '\'' . $msg . '\',' . '\'' . $adr . '\')" ' 
						. '" alt="delete" src="images/delete.png" '
					    . ' class="icon"/></td>';
						
						echo 	"<td>" . $row["uid"] . "</td>";
						echo  	"<td>" . $row["username"] . "</td>";
						echo 	"<td>" . $row["password"] . "</td>";
						echo 	"<td>" . $row["email"] . "</td>";
						echo  "<td>" . $row['firstname'] . "</td> ";  
						echo  "<td>" . $row['lastname'] . "</td> ";			
						echo  	"<td>" . $row["permission"] . "</td>";
						echo  	"<td>" . $row["phone"] . "</td>";
						echo   "</tr>";
					} ?>   
				</table>
						</td>
				</tr>
			</table>
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
			<br />			
			</div>
			<div style="margin: 10px;" align="right">
					 or return to the <a href="admin_index.php">admin</a> page.
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
