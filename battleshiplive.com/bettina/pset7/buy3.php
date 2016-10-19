<?php

    // require common code
    require_once("includes/common.php"); 
    
    // apologize if stock symbol is not valid
    if (lookup($_POST["Stock"])== NULL)
        apolozize("That Stock is not listed under our database");
        
    // apologize if Shares value is not posted
    if ($_POST["Shares"] == NULL)
        apologize("Invalid entry in Shares");
        
    // make query for cash owned and execute query
    $sql = sprintf("SELECT Cash FROM users WHERE uid='%s'",$_SESSION["uid"]);
    $result = mysql_query($sql);

    // grab row, calculate cost of purchase
    $row = mysql_fetch_array($result);
    $buyval = (lookup($_POST["Stock"])->price) * $_POST["Shares"];            

    // apologize if user owns insufficient shares
    if ($row["Cash"] < $buyval)
        apologize("Insufficient credit");
    
    // update users cash account
    $sql = sprintf("UPDATE users SET Cash=Cash-%d WHERE uid='%d'",
        $buyval, $_SESSION["uid"]);       
    
    mysql_query($sql);
    
    // inserts new stock in portfolio or updates old stock
    $sql =sprintf("INSERT INTO Portfolios (uid, Stock, Shares) VALUES(%d, '%s', %s) ON DUPLICATE KEY UPDATE Shares=Shares+VALUES(Shares)",$_SESSION["uid"], $_POST["Stock"], $_POST["Shares"]);
   
    mysql_query($sql);
        
       /******* // Else Update shares in tables
        else
        {
            //update shares
            $sql = sprintf("UPDATE Portfolios SET Shares=Shares+%d WHERE uid='%d' AND Stock='%s'", $_POST["Shares"], $_SESSION["uid"], $_POST["Stock"]);
            mysql_query($sql);
        } **********/
        
    redirect("index.php");

?>
