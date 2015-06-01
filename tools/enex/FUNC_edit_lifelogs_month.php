#!/usr/local/bin/php -q
<?php

/* REQUIRE CONF.PHP FIRST! */
// define('IS_DEBUG', FALSE);
// require('C:/ice_php_envir/scripts/library/CONF.php');

/*
 * 编辑 lifelogs 中月份文件夹的index.md内容
 */
function mk_month_index_md($params){
	/* $params = array(
		"diary_date" => 'YYYYmm'
	); */

	echo "\n";
	echo "PHP mk_month_index_md [ " . strtoupper(__file__) . " ]\n";

	/* open .enex file */
	if(!isset($params["diary_date"])){
		echo "PHP miss the param - diary_date!\n";
		return FALSE;
	}
	
	$year = substr($params["diary_date"], 0, 4);
	$month = substr($params["diary_date"], 4, 2);
	
	date_default_timezone_set("Asia/Shanghai");
	// $specified_m = mktime(0, 0, 0,
		// $month, 1,
		// $year
	// );
	$prev_Ym = mktime(0, 0, 0,
		(1 == (int)$month ? 12 : (int)$month - 1), 1,
		(1 == (int)$month ? (int)$year - 1 : (int)$year)
	);
	$next_Ym = mktime(0, 0, 0,
		(12 == (int)$month ? 1 : (int)$month + 1), 1,
		(12 == (int)$month ? (int)$year + 1 : (int)$year)
	);
	$p_Ym = date("Y/m", $prev_Ym);
	$n_Ym = date("Y/m", $next_Ym);
	$p_MY = date("M. Y", $prev_Ym);
	$n_MY = date("M. Y", $next_Ym);
	
	$path = BLOG_HEXO . "source/lifelogs/" . $year . "/" . $month . "/index.md";
	$cp = GB(WP($path));
	// echo "cp=$cp\n";	//for debug

	$cont = "title: {$year}-{$month}
date: {$year}-{$month}-01
toc: false
---
[**< {$p_MY}** - Prev 上一月](/lifelogs/{$p_Ym}/index.html) &nbsp; &nbsp; | &nbsp; &nbsp; [下一月 Next - **{$n_MY} >**](/lifelogs/{$n_Ym}/index.html) &nbsp; &nbsp; |  &nbsp; &nbsp; [返回年历 **Back to Years ^**](/lifelogs/index.html)
<br/>
#### Logs 日志记录
---\n";

	/* create the month's index.md file */
	if(!($h = fopen($cp, "w")))	return FALSE;
	if(!($r = fwrite($h, $cont)))	return FALSE;
	if(!fclose($h))	return FALSE;
	if(!$r)	return FALSE;
	
	echo "PHP mk_month_index_md - " . __file__ . " - fin.\n\n";

	// system("echo [ pause ] & pause > nul");
	return TRUE;
}
?>