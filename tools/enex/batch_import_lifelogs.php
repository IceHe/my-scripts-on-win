#!/usr/local/bin/php -q
<?php

/* REQUIRE CONF.PHP FIRST! */
define('IS_DEBUG', FALSE);
require('C:/ice_php_envir/scripts/library/CONF.php');

/*
 * desc
 */
echo "\n";
echo "PHP [ " . strtoupper(__file__) . " ]\n";

/* 设置导入日志的起始日期 */
date_default_timezone_set("Asia/Shanghai");
$time = mktime(0, 0, 0,
	'04',
	'01',
	'2015'
);
/* (int) $time + 86400 即 增加一天 */

// echo "If you need to use this script!\nPlease modify it again~\n";
// exit(0);

/* 设置从起始日期起，开始导入之后多少天的日志 */
for($i = 0; $i < 71; ++$i){
	$command = "echo " . date("ymd", $time + 86400 * $i) . " | php post_lifelog_to_blog.php";
	// echo "command=$command\n";
	if(0 != exec_and_log($command, "PHP exec [{$command}]",
		$ret_last_line, $output, false, __FILE__))	break;
}


echo "PHP " . __file__ . " - fin.\n\n";
system("echo [ pause ] & pause > nul");
exit(0);

?>