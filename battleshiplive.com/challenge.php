<?php
	/****************************************************************
	 challenge.php is called after a person logs in, and gives them 
	 information about who they can play
	 ***************************************************************/
	
	
    // require common code
    require_once("includes/common.php");
    $return = mysql_query("SELECT * FROM users WHERE ingame=0");
    
?>

<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

  <head>
    <link href="css/challenge.css" rel="stylesheet" type="text/css" />

    <title>Battleship</title>
	
	<script type="text/javascript">
		//<![CDATA[
		
		// reloads data every second
		function load()
		{
			loadXMLDoc('ch_reload.php', chal_loader);
			setInterval("loadXMLDoc('ch_reload.php', chal_loader)", 1000);
		}
		
		// gets xml from chal_reload.php
		function chal_loader()
		{
			if (xmlhttp1.readyState==4)
			{
				// when ready, call function
				if (xmlhttp1.status==200)
				{ 
					// get xml and put information in variables
					var request = xmlhttp1.responseXML;
					var users_tag = request.getElementsByTagName("users");
					var people_tag = request.getElementsByTagName("people");
					var users = users_tag[0];
					var status = users.getAttribute("status");
					var ourname = users.getAttribute("name");
					
					// redirect to play.php if you have placed all pieces
					if (status == 'place')
						window.location = 'placeships.php';
					
					// delete the table
					var listparent = document.getElementById("list");
					var parent = listparent.parentNode;
					parent.removeChild(listparent);
					
					// make a new table in the same spot
					var newtable = document.createElement("table");
					newtable.setAttribute("align", "center");
					newtable.setAttribute("class", "portfolio");
					newtable.setAttribute("id", "list");
					var row = document.createElement("tr");
					var column = document.createElement("th");
					column.appendChild(document.createTextNode("Currently Online"));
					var column2 = document.createElement("th");
					column2.appendChild(document.createTextNode("Challenger"));
					row.appendChild(column);
					row.appendChild(column2);
					newtable.appendChild(row);
					parent.appendChild(newtable);
					
					// put names and their challengers in the table
					for (var count = 0; count < people_tag.length; count++)
					{
						var name = people_tag[count].getAttribute("name");
						var challenger = people_tag[count].getAttribute("challenger");
						var row = document.createElement("tr");
						var column = document.createElement("td");
						column.appendChild(document.createTextNode(name));
						var column2 = document.createElement("td");
						
						// if the you have been challenged, provide a link to initialize.php to start the game.
						if (name == ourname)
						{
							var link = document.createElement("a");
							link.setAttribute("href", "initialize.php");
							if (challenger)
								link.appendChild(document.createTextNode("Accept challenge from " + challenger));
							column2.appendChild(link);
						}						
						else
							column2.appendChild(document.createTextNode(challenger));
						row.appendChild(column);
						row.appendChild(column2);
						document.getElementById("list").appendChild(row);
					}				
				}			
			}      
		}	
		
		
		// Connects to url
		function loadXMLDoc(url, myfunc)
		{
			// code for all new browsers
			if (window.XMLHttpRequest)
			{
				xmlhttp1=new XMLHttpRequest();
			}
			// code for IE5 and IE6
			else if (window.ActiveXObject)
			{
				xmlhttp1=new ActiveXObject("Microsoft.XMLHTTP");
			}
			
			// on ready calls state_Change
			if (xmlhttp1!=null)
			{
				xmlhttp1.onreadystatechange=myfunc;
				xmlhttp1.open("GET", url, true);
				xmlhttp1.send(null);
			}
			// alerts if browser is not supported
			else
			{
				alert("Your browser does not support XMLHTTP.");
			}
		}
		//]]>
	</script>
  </head>

  <body onload="load()">

    <div align="center">
      <a href="index.php"><img alt="Battleship" border="0" src="images/logo2.gif" /></a>
    </div>
	
	<div align="center">
		<br/>
		<a href="ai_place.php">Do battle against an AI?</a>
		<br/>
	</div>
	
	<div align="center">
		<br/>
		Or challenge an online player?
		<br/>
		<br/>
		<form action="challenge2.php" method="post">
			<table border="0">
				<tr>
					<td>Username:</td>
					<td><input name="challenged" type="text" /></td>
					<td><input type="submit" value="challenge"></td>
				</tr>
			</table>
		</form>
	</div>
	
	<div align="center" id='listparent'>              
        <br/>
    <table align="center" class="portfolio" id="list">
        <tr>
            <th>Currently Online</th>
			<th>Challenger</th>
        </tr>
     <? while ($row = mysql_fetch_array($return)) { ?>
        
		<tr>
            <td><? print($row["name"]); ?> </td>
			<td><? 
					if ($row["uid"] == $_SESSION["uid"] && 
						$row["challenger"] != '')
						{
						echo "<a href='initialize.php'>" . "Accept challenge from " . $row["challenger"] . '</a>';
					 } 		
					else if ($row["challenger"]) 
						{print ($row["challenger"]);}
				?>
            </td>         
        </tr>
    <? } ?>   
      
    </table>
    <div>
  </body>

</html>
