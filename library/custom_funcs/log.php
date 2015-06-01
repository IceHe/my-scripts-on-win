<?php

/* exec with log (win) */
function exec_and_log($command, $tips, &$ret_last_line, &$output, $is_debug = false, $cal_file = ""){
	/* reset $output to a empty array */
	/* 保证每次调用exec_and_log后，返回的$output不带有上一次调用的残余 */
	$output = array();
	
	date_default_timezone_set("Asia/Shanghai");
	$now = date("c", time());
	$date = date("Ymd", time());
	$log_fname = "{$date}.log";
	$log_fpath = WP(LOGS . "history/{$log_fname}");
	if($is_debug){
		echo "PHP [ LOG.PHP ]\n";
		echo "PHP TMP VARs:\n";
		echo "PHP date = [" . $date . "]\n";
		echo "PHP log_fname = [" . $log_fname . "]\n";
		echo "PHP log_fpath = [" . $log_fpath . "]\n";
	}
	
	// $cmd_str = "({$command}) >> {$log_fpath}";
	$cmd_str = "{$command}";
	if($is_debug){
		echo "PHP command = [" . $command . "]\n";
		echo "PHP cmd_str = [" . $cmd_str . "]\n";
		echo "\n";
	}
	
	$ret_last_line = exec($cmd_str, $output, $ret_val);
	echo "{$tips} - " . (0 === $ret_val ? "suc." : "failed!") . "\n";
	
	/* log rec */
	$log_created_str = "";
	if(!file_exists($log_fpath)){
		$log_created_str = "[  {$now}  ] CREATED\n";
	}
	
	if(FALSE === ($h = fopen($log_fpath, 'a')))	return FALSE;
	/* init new log */
	if("" != $log_created_str){
		if(FALSE === fwrite($h, $log_created_str)){
			fclose($h);
			return FALSE;
		}
	}
	
	/* write exec() command */
	$where_calling = (("" != $cal_file) ? (" in <{$cal_file}>") : "");
	if(FALSE === fwrite($h, "[  {$now}  ] EXEC(({$cmd_str}))"
		.  "{$where_calling}" . "\n")){
		fclose($h);
		return FALSE;
	}
	
	/* write exec() result */
	foreach($output as $index => $line){
		echo $line . "\n";
		if(FALSE === fwrite($h, $line . "\n"))	break;
	}
	
	/* write exec() end */
	if(FALSE === fclose($h))	return FALSE;
	
	return $ret_val;
}

?>