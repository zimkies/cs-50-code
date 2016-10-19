<?php

    // require common code
    require_once("includes/common.php");

    $sql = sprintf("SELECT * FROM history WHERE uid='%s'",$_SESSION["uid"]);
    $return = mysql_query($sql);
    
?>

<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

  <head>
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.5.0/build/reset-fonts/reset-fonts.css" />
    <title>Mufpumi History</title>
  </head>

  <body>

    <div align="center">
      <a href="index.php"><img alt="Mufpumi" border="0" src="images/logo2.gif" /></a>
    </div>

    <div align="center">              
        <br/>
    <table align="center" class="portfolio">
        <tr>
            <th>Bought/sold</th>
            <th>Stock symbol</th>
            <th>Shares</th>
            <th>Price</th>
            <th>Time</th>
        <tr>
     <? while ($row = mysql_fetch_array($return)) { ?>
      
        <tr>
            <td><? print($row["transaction"]); ?> </td>
            <td><? print($row["stock"]); ?> </td>
            <td><? print($row["shares"]); ?> </td>
            <td><? printf("%.2f", $row["price"]); ?> </td>
            <td><? print($row["time"]); ?> </td>            
        </tr>
    <? } ?>   
      
    </table>
    <div>

    <div style="margin: 10px;">
        <a href="index.php">return</a> to your portfolio
    </div>

  </body>

</html>
