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
    <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.5.0/build/reset-fonts/reset-fonts.css" />
    <title>Mupfumi</title>
  </head>

  <body>

    <div align="center">
      <a href="index.php"><img alt="Mupfumi" border="0" 
      src="images/logo2.gif" /></a>
    </div>

    <div align="center">
        
        <? 
            $sql = sprintf("SELECT username FROM users WHERE uid=%d", 
                           $_SESSION["uid"]);
            $usernameq = mysql_query($sql); 
            $username = mysql_fetch_array($usernameq);        
        ?>
        
        Hi there <? print($username["username"]); ?>! Here is your current portfolio:
        <br/> 
        <br/> 
    </div>

    <div align="center">
        
        <? 
            $sql = sprintf("SELECT Stock, Shares FROM Portfolios WHERE uid=%d",
                            $_SESSION["uid"]);   
            $result = mysql_query($sql); 
        
            $sql2 = sprintf("SELECT Cash FROM users WHERE uid=%d",
                            $_SESSION["uid"]);
            $result2 = mysql_query($sql2);
            $row2 = mysql_fetch_array($result2);
        ?>
        
        <table align="center" class="portfolio">
            <tr>
                <th>Stock</th>
                <th>Shares</th>
                <th>Price</th>
                <th>Value</th>            
            </tr>
        
        <?
            $total = 0.0;
            
            while ($row = mysql_fetch_array($result)) 
        { ?>
    
            <?  
                $s = lookup($row["Stock"]);
                $value = ($row["Shares"]*($s->price));
                $total = $total + $value;
            ?>
                <tr>
                    <td><? print($s->name); ?></td>
                    <td><? print($row["Shares"]);?></td>
                    <td>$<? print($s->price); ?></td>
                    <td>$<? print($value); ?></td>
                </tr>
        <? } ?>        
                <tr>
                    <td colspan="3">Cash</td>
                    <td>$<? printf("%d", $row2["Cash"]); ?></td>
                </tr>
                <tr>
                    <td colspan="3">TOTAL</td>
                    <? $total = $total + $row2["Cash"]; ?>
                    <td>$<? printf("%d", $total); ?></td>
                </tr>

            </table>            
        
        <br/>
    </div>

    <div align="center">
        Get more <a href="quote.php">quotes</a>, 
        or <a href="buy.php">buy</a> and <a href="sell.php">sell</a> stock.
        <br/>
        You can also check your transaction <a href="history.php">history</a>.
        <br/>
        <a href="logout.php">Logout</a> if you are done.
    </div>
  </body>

</html>
