<?php

    // require common code
    require_once("includes/common.php"); 

    // escape username and password for safety
    $username = mysql_real_escape_string($_POST["username"]);
    $password = mysql_real_escape_string($_POST["password"]);
	$redirect = $_GET["redirect"];
	if (!$redirect)
		$redirect = "index.php";

    // prepare SQL
    $sql = sprintf("SELECT uid FROM users WHERE username LIKE BINARY '%s' AND password LIKE BINARY '%s'",
                   $username, $password);

    // execute query
    $result = mysql_query($sql);

    // if we found a row, remember user and redirect to portfolio
    if (mysql_num_rows($result) == 1)
    {
        // grab row
        $row = mysql_fetch_array($result);

        // cache uid in session
        $_SESSION["uid"] = $row["uid"];
		$_SESSION["name"] = $username;

        // redirect to portfolio
        redirect($redirect);
    }

    // else report error
    else
        apologize("Invalid username and/or password!");

?>
