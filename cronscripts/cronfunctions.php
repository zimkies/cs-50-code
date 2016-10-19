<?
	//require_once("includes/common.php"); 
	

	
	// add a cronjob to the cronlist. Takes a date time (YY-... HH:MM),
	// php script to be run, and a handler that gets the jobnumber.
	function add_scheduled_job ($date, $script)
	{
		$php_path = "/usr/local/bin/php";
		$temp = "tempjobstore.txt";
		//if (!check_time($date))
			//return false;
		$datetime = explode(' ', $date);
		$str = $php_path . " " . $script . " | at " . $datetime[1] . " " . $datetime[0] . ">& " . $temp;

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
	
	
		