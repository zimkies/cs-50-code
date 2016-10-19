<?php

	// require common stuff
	require_once("includes/common.php");
	
	// reset the table
	for ($i = 0; $i < 9; $i++)
	{
		$sql = sprintf("UPDATE bang SET a='7', b='7', c='7', d='7', e='7', f='7', g='7', h='7', i='7', j='7' WHERE row=%d", $i);
		mysql_query($sql);
	}
			$sql = "UPDATE bang SET a='1', b='7', c='7', d='7', e='7', f='7', g='7', h='7', i='7', j='7' WHERE row=9";
		mysql_query($sql);
