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

$path = GB(WP(BLOG_HEXO . "source/lifelogs/2013/11/"));

$command = "mkdir \"{$path}\"";
echo "command=[{$command}]\n";
if(0 != exec_and_log($command, "PHP mkdir [{$path}]",
	$ret_last_line, $output, false, __FILE__))	exit(1);

echo "PHP " . __file__ . " - fin.\n\n";
system("echo [ pause ] & pause > nul");
exit(0);

?>