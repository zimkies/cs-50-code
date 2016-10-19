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
    <title>Mupfumi Quotes</title>
  </head>

  <body>

    <div align="center">
      <a href="index.php"><img alt="Mupfumi" border="0" 
      src="images/logo2.gif" /></a>
    </div>
    
    <div align="center">
        Enter the the symbol of the stock that you wish to look up:
    </div>
    
    <div align="center">
      <form action="quote2.php" method="post">
        <table border="0">
          <tr>
            <td class="field">Stock Symbol</td>
            <td><input name="stocksymbol" type="text" /></td>
          </tr>
        </table>
        <div style="margin: 10px;">
          <input type="submit" value="Find Stock info" />
        </div>
      </form>
    </div>

    <div align="center">
        You can also return to your <a href="index.php">portfolio</a>.
    </div>

  </body>

</html>
