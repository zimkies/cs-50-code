<?	
	require_once("includes/cronfunctions.php");
	echo "aa";
	$s = add_scheduled_job("2009-05-08 05:00:00", "index.php");
	echo $s;
	echo "dd";
	//remove_scheduled_job($s);
	