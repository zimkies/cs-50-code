<?php

    // require common code
    require_once("includes/common.php");
	admin_check();

    $sql = sprintf("DELETE FROM users WHERE uid=%d", $_GET["uid"]);
    if(!mysql_query($sql))   
		apologize("There was an error in trying to delete this person");
	else
	{
		$msg_to_admin = "You successfuly deleted the user with id " . $_GET["uid"];
		congratulate($msg_to_admin, "Return to Admin page", "admin_index.php");
	}
?>