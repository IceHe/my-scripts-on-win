#!/usr/local/bin/php -q
<?php

/* REQUIRE CONF.PHP FIRST! */
define('IS_DEBUG', FALSE);
require('C:/ice_php_envir/scripts/library/CONF.php');

/*
 * backup the directory "scripts"
 */
echo "\n";
echo "PHP [ " . strtoupper(__file__) . " ]\n";

/* get params from argv */
$reqs = array(
	"%dest",
);
$params = proc_param($argv, $reqs, "No tips.", IS_DEBUG);
echo "PHP proc_param(\$argv) - suc.\n";
// if(!IS_DEBUG)	var_dump($params);


/* TMP VARS */
$src_path = rm_suf_dirsep(WP(SCRIPTS));

date_default_timezone_set("Asia/Shanghai");
$now = date("ymd_His", time());

$dest_path = WP(BACKUP . "scripts_" . $now);
if(isset($params['dest'])){
	$dest_path = WP($params['dest'] . "scripts_" . $now);
}

/* backup the directory "scripts" */
$command = "robocopy \"{$src_path}\" \"{$dest_path}\" /e";
echo "PHP command = [{$command}]\n";

system("echo [ pause ] & pause > nul");

if(0 != exec_and_log($command, "PHP backup dir from [{$src_path}] to [{$dest_path}]",
	$ret_last_line, $output, false, __FILE__))	exit(1);

echo "PHP " . __file__ . " - fin.\n\n";

system("echo [ pause ] & pause > nul");

?>