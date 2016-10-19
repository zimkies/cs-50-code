<?php

    // require common code
    require_once("includes/common.php"); 

    // define $s apologize if the symbol cannot be found in database
    if (($s = lookup($_POST["stocksymbol"])) == NULL)      
        apologize("That symbol is not recognized in our database");
?>

<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">   
    <head>
        <link href="css/styles.css" rel="stylesheet" type="test/css" />
        <title>Mupfumi Quotes</title>
    <head>

    <body>
       
        <div align="center">
            <a href="index.php"><img border="0" src="images/logo2.gif" alt="Mupfumi" /></a>
        </div>
        
        <div align ="center">
            The stock <? print($s->name); ?> 
            is currently selling at $<? print($s->price); ?> per share.
            <br/>
            Want to <a href="buy.php">buy</a> or <a href="sell.php">sell</a> stock?
            Alternatively return to your <a href="index.php">portfolio</a>?
        </div>
    </body>
</html>


