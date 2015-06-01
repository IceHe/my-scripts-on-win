#!/usr/local/bin/php -q
<?php

/* REQUIRE CONF.PHP FIRST! */
define('IS_DEBUG', FALSE);
require('C:/ice_php_envir/scripts/library/CONF.php');
require(ENEX . 'FUNC_enexdiary_to_blog.php');
require(ENEX . 'FUNC_edit_lifelogs_month.php');

/*
 * post an article from Evernote to hexo according to the note_title
 */
echo "\n";
echo "PHP [ " . strtoupper(__file__) . " ]\n";

/* get params from argv */
$reqs = array(
	"%diary_date",	//yymmdd
);
$params = proc_param($argv, $reqs, "No tips.", IS_DEBUG);
echo "PHP proc_param(\$argv) - suc.\n";
// if(!IS_DEBUG)	var_dump($params);

/* convert params' chinese encoding (from GB2312 to UTF8) */
// $params = convert_str_ary_encoding($params, "GB2312", "UTF-8");

$diary_date = "";
if(!isset($params['diary_date'])){
	/* get ariticle title */
	echo "diary_date=";
	if("" == ($diary_date = input())){
		echo "Must input a note title!\n";
		system("echo [ pause ] & pause > nul");
		exit(1);
	}
}else{
	$diary_date = $params['diary_date'];
}

/* tmp file path */
date_default_timezone_set("Asia/Shanghai");
$time = mktime(0, 0, 0,
	substr($diary_date, 2, 2),
	substr($diary_date, 4, 2),
	'20' . substr($diary_date, 0, 2)
);

$time_str = date("His", time());
$date_str = date("ymd", time());
$rand_num = rand();
$tmp_fp = "tmp_4edit_d{$date_str}_t{$time_str}_r{$rand_num}.enex";
$cfp = WP(TMPO) . $tmp_fp;

$title_prefix = date("y/m/d", $time);
// $note_qry = "\"intitle:\\\"^{$diary_date}$\\\"\"";	// for *nix (did not test it yet)
$note_qry = 'intitle:"^^' . $title_prefix . ' "';	// for win

/* echo vars - for debug */
if(IS_DEBUG){
	echo "PHP diary_date = [{$diary_date}]\n";
	echo "PHP note_qry = [{$note_qry}]\n";
	echo "PHP cfp = [{$cfp}]\n";
}

/* export note */
$command = "enscript exportNotes /q {$note_qry} /f \"{$cfp}\"";
echo "command=$command\n";
if(0 != exec_and_log($command, "PHP enscript exportNotes [{$tmp_fp}]",
	$ret_last_line, $output, false, __FILE__))	exit(1);

/* mkdir to save the diary */
$path = BLOG_HEXO . "source/lifelogs/" . date("Y", $time) . "/" . date("m", $time) . "/";
$command = "mkdir \"" . GB(WP($path)) . "\"";
// echo "command=[{$command}]\n";
if(0 === exec_and_log($command, "PHP mkdir [{$path}]",
	$ret_last_line, $output, false, __FILE__)){
	mk_month_index_md(array('diary_date' => date("Ym", $time)));
}

/* diary to blog */
$params = array(
	'enex_path' => $cfp,
	'dest_path' => $path,
	'test_path' => BLOG,
	'diary_date' => $diary_date,
);
if(IS_DEBUG)	var_dump($params); // for debug
if(!diary_to_blog($params)){
	echo "PHP php enex_to_blog - failed!\n";
	exit(1);
}else{
	echo "PHP php enex_to_blog - suc.\n";
}

/* delete tmp_enex file */
$command = "erase \"{$cfp}\"";
if(0 != exec_and_log($command, "PHP delete tmp_enex file [{$tmp_fp}]",
	$ret_last_line, $output, false, __FILE__))	exit(1);
//无论是否有成功删掉，此处都会返回suc。
//所以请注意上一句的输出，是否输出了类似如下信息：
//Could Not Find C:\Users\IceHe\tmpo\tmp_4edit_d150208_t212313_r10714.enexx

echo "PHP " . __file__ . " - fin.\n\n";
// system("echo [ pause ] & pause > nul");
exit(0);

?>