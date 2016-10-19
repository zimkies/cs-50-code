<?php
    
    // require common code
    require_once("includes/common.php");
?>

<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

  <head>
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <title>Mufpumi Sell</title>
  </head>

  <body>

    <div align="center">
      <a href="index.php">
      <img alt="Mufpumi" border="0" src="images/logo2.gif" /></a>
    </div>
    
    <div align="center">
      <form action="buy2.php" method="post">
        <table border="0">
          <tr>
            <td class="field">Symbol:</td>
            <td><input name="Stock" type="text" /></td> 
          </tr>
          <tr>
            <td class="field">Shares:</td>
            <td><input name="Shares" type="text" /></td>
          </tr>
        </table>
        <div style="margin: 10px;">
          <input type="submit" value="Buy" />
        </div>
        <div style="margin: 10px;">
          or <a href="index.php">return</a> to portfolio.
        </div>
      </form>
    </div>

  </body>

</html>
