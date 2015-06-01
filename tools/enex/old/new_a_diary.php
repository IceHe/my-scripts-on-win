#!/usr/local/bin/php -q
<?php

/* REQUIRE CONF.PHP FIRST! */
define('IS_DEBUG', FALSE);
require('C:/ice_php_envir/scripts/library/CONF.php');

/*
 * 在Evernote新建REC_day日志
 */
echo "\n";
echo "PHP [ " . strtoupper(__file__) . " ]\n";

/* get params from argv */
$reqs = array(
	"/d=date",
	"/t=time",
	"/q=qry=query",
);
$params = proc_param($argv, $reqs, "No tips.", IS_DEBUG);
echo "PHP proc_param(\$argv) - suc.\n";
// if(!IS_DEBUG)	var_dump($params);

/*
 * TMP VARS
 */
echo "PHP \tTMP VARs:\n";

/* date time */
date_default_timezone_set("Asia/Shanghai");
$time = time();
if(isset($params['date'])){
	if(isset($params['time'])){
		$time = mktime(
			substr($params["time"], 0, 2),
			substr($params["time"], 2, 2),
			substr($params["time"], 4, 2),
			substr($params["date"], 4, 2),
			substr($params["date"], 6, 2),
			substr($params["date"], 0, 4)
		);
	}else{
		$time = mktime(0, 0, 0,
			substr($params["date"], 4, 2),
			substr($params["date"], 6, 2),
			substr($params["date"], 0, 4)
		);
	}
}

$date_str = date("y/m/d", $time);
$date_str_2 = date("ymd", $time);
$date_str_3 = date("Y/m", $time);
$time_str = date("His", $time);
$now_str = date("YmdHis", $time);

/* note params */
$note_title = "{$date_str} stu";
$notebook_name = "Dairy - {$date_str_3}";
$note_tags = "REC_day";

/* evernote search query */
if(isset($params['query'])){
	$note_qry = $params['query'];
}else{
	$note_qry = "intitle:y/m/d stu tag:TPL";
}

/* tmp file path */
$rand_num = rand();
$tmp_fp = "tmp_4edit_d{$date_str_2}_t{$time_str}_r{$rand_num}.enex";
$cfp = WP(TMPO) . $tmp_fp;	// WP() just for window cmd


/* echo vars - for debug */
if(IS_DEBUG){
	echo "PHP date_str = [{$date_str}]\n";
	echo "PHP time_str = [{$time_str}]\n";
	echo "PHP now_str = [{$now_str}]\n";

	echo "PHP note_title = [{$note_title}]\n";
	echo "PHP notebook_name = [{$notebook_name}]\n";
	echo "PHP note_tags = [{$note_tags}]\n";

	echo "PHP note_qry = [{$note_qry}]\n";
	echo "PHP cfp = [{$cfp}]\n";
}



/*
 * START TO PROCESS
 */
echo "PHP \tPROCESS:\n";

/* export note */
$command = "enscript exportNotes /q \"{$note_qry}\" /f \"{$cfp}\"";
if(0 != exec_and_log($command, "PHP enscript exportNotes [{$tmp_fp}]",
	$ret_last_line, $output, false, __FILE__))	exit(1);
	
/* edit note */
$command = "php \""
	. ENEX . "edit_enex.php\""
	. " \"{$cfp}\""
	. " /i \"{$note_title}\""
	. " /t \"{$note_tags}\""
	. " /c {$now_str}"
	. " /u {$now_str}"
;
echo $command . "\n"; // for debug
if(0 != ($ret = exec_and_log($command, "PHP php edit_enex [{$note_qry}]",
	$ret_last_line, $output, false, __FILE__)))	exit(1);

// echo "----php edit_enex process----------------------\n";
// foreach($output as $index => $str){
	// echo $str . "\n";
// }
// echo "-----------------------------------------------\n";

/* import note */
$command = "enscript importNotes /s \"{$cfp}\" /n \"{$notebook_name}\"";
if(0 != exec_and_log($command, "PHP enscript importNotes [{$tmp_fp}]",
	$ret_last_line, $output, false, __FILE__))	exit(1);
	
/* delete tmp_enex file */
$command = "erase \"{$cfp}\"";
if(0 != exec_and_log($command, "PHP delete tmp_enex file [{$tmp_fp}]",
	$ret_last_line, $output, false, __FILE__))	exit(1);
//无论是否有成功删掉，此处都会返回suc。
//所以请注意上一句的输出，是否输出了类似如下信息：
//Could Not Find C:\Users\IceHe\tmpo\tmp_4edit_d150208_t212313_r10714.enexx

echo "PHP " . __file__ . " - fin.\n\n";

// system("echo [ pause ] & pause > nul");