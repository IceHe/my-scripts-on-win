#!/usr/local/bin/php -q
<?php

/* REQUIRE CONF.PHP FIRST! */
define('IS_DEBUG', FALSE);
require('C:/ice_php_envir/scripts/library/CONF.php');

/*
 * recover the directory from the backup
 * 因为此“备份恢复”脚本也在需要被恢复的目录下，
 * 如果先删掉这个目录，再去将备份的资料拷贝过来，会出现问题，
 * 所以，此备份恢复的特性是：
 * 1.只是将旧备份文件拷贝回来；
 * 2.后来新增的文件不会被删掉。
 */
echo "\n";
echo "PHP [ " . strtoupper(__file__) . " ]\n";

/* get params from argv */
// $reqs = array(
	// "%src"
	// "/d=date",
	// "/t=time",
// );
// $params = proc_param($argv, $reqs, "No tips.", IS_DEBUG);
// echo "PHP proc_param(\$argv) - suc.\n";
// if(!IS_DEBUG)	var_dump($params);


/* empty the dir of "BACKUP/scripts_0_backuped_before_last_recovery/" */
$src_path = rm_suf_dirsep(WP(SCRIPTS));
$dest_path = WP(BACKUP . "scripts_0_backuped_before_last_recovery");
if(file_exists($dest_path)){
	$command = "echo y | rmdir /s \"{$dest_path}\"";
	echo "PHP command = [{$command}]\n";
	if(0 != exec_and_log($command, "PHP empty the cur of \"BACKUP/scripts_0_backuped_before_last_recovery/\" [{$dest_path}]",
		$ret_last_line, $output, false, __FILE__))	exit(1);
}
// system("echo [ pause ] & pause > nul");


/* backup the cur dir "scripts" before recovery */
$command = "robocopy \"{$src_path}\" \"{$dest_path}\" /e";
echo "PHP command = [{$command}]\n";
if(0 != exec_and_log($command, "PHP backup tmp_scripts before recovery from [{$src_path}] to [{$dest_path}]",
	$ret_last_line, $output, false, __FILE__))	exit(1);


/* get the latest backup dir */
$command = "dir \"d:\\scripts_backup\" /on /b";
if(0 != exec_and_log($command, "PHP get the latest backup dir",
	$ret_last_line, $output, false, __FILE__))	exit(1);
$src_path = rm_suf_dirsep(WP(BACKUP . $ret_last_line));
echo "PHP src_path = [{$src_path}]\n";


/* recover the directory "scripts" */
$dest_path = rm_suf_dirsep(WP(SCRIPTS));
$command = "echo a | robocopy \"{$src_path}\" \"{$dest_path}\" /e";
echo "PHP command = [{$command}]\n";
if(0 != exec_and_log($command, "PHP recover dir from [{$src_path}] to [{$dest_path}]",
	$ret_last_line, $output, false, __FILE__))	exit(1);

echo "PHP " . __file__ . " - fin.\n\n";

system("echo [ pause ] & pause > nul");

?>