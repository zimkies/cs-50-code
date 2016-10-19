<?php

    // require common code
    require_once("includes/common.php"); 
    
    //make query
    $sql = sprintf("SELECT Shares FROM Portfolios WHERE uid='%s' AND Stock='%s'",
                   $_SESSION["uid"], $_POST["Stock"]);

    // execute query
    $result = mysql_query($sql);

    // if we find a row then process the transaction
    if (mysql_num_rows($result) == 1)
    {
        // grab row
        $row = mysql_fetch_array($result);
        
        // apologize if user owns insufficient shares
        if ($row["Shares"] < $_POST["Shares"] 
            || $_POST["Shares"] <= 0)
            apologize("Invalid number of shares");
        
        //update cash
        $sprice = lookup($_POST["Stock"])->price;
        $sellval = $sprice * $_POST["Shares"];                    
        $sql = sprintf("UPDATE users SET Cash=Cash+%d WHERE uid='%d'",
                        $sellval, $_SESSION["uid"]);       
        mysql_query($sql);
        
        //delete row in table if all shares are sold
        if ($row["Shares"] == $_POST["Shares"])
        {
            $sql=sprintf("DELETE FROM Portfolios WHERE uid='%d' AND Stock='%s'",                        $_SESSION["uid"], $_POST["Stock"]);
    
            mysql_query($sql);
        }
        
        // Else Update shares in tables
        else
        {
            //update shares
            $sql = sprintf("UPDATE Portfolios SET Shares=Shares-%d WHERE uid='%d' AND Stock='%s'", $_POST["Shares"], $_SESSION["uid"], $_POST["Stock"]);
            mysql_query($sql);
        }
        
    // log history
    $sql = sprintf("INSERT INTO history (uid, transaction, stock, shares, price, time) VALUES('%d','sold','%s', %d, %d, NOW())", 
        $_SESSION["uid"], $_POST["Stock"], $_POST["Shares"], $sprice);
    mysql_query($sql);
    
    redirect("index.php");
    }    
    else
        apologize("That symbol was not found in the database");    
?>
