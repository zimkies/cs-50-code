<?
	require_once("common.php"); 

	// add a cronjob to the cronlist. Takes a date time (YY-... HH:MM),
	// php script to be run, and a handler that gets the jobnumber.
	// NOTE!!!! this adds 3 hours to the job because the server is in California
	function add_scheduled_job ($date, $script)
	{
		$TZadd = " - 3 hours "; 
		$php_path = "/usr/local/bin/php";
		$temp = HOME_PATH . "tempjobstore.txt";
		if (!check_time($date))
			return false;
		$datetime = explode(' ', $date);
		$str = "echo '" . $php_path . " " . $script . "' | at " . substr($datetime[1], 0, 5) . " " . $datetime[0] . $TZadd . " >& " . $temp;
		//exit($str);
		mail("kies@fas.harvard.edu", "crontesting", $str, "From: bettina-network@comcast.net" );
		shell_exec($str);
		$info = fopen($temp, 'r');
		if (!$info)
			return false;
		$string = fread($info, filesize($temp));
		fclose($info);
		preg_match('/job ([0-9]*) at/', $string, $match);
		return $match[1];
	}
	
	function remove_scheduled_job($jobnumber)
	{
		return shell_exec("atrm $jobnumber");
	}	
	
	
		