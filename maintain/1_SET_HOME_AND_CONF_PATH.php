#!/usr/local/bin/php -q
<?php

/*
 * 1. conf.php的HOME路径，更新为当前scripts文件夹所在的路径；
 * 2. 所有其它.php中的用于require(conf.php)的语句，如下
 *	  require('D:\scripts_backup\scripts_150210_203021\scripts/library/CONF.php');
 *	  将根据新的HOME路径，作出修正。
 */
echo "\n";
echo "PHP [ " . strtoupper(__file__) . " ]\n";

$cur_scripts_path = dirname(dirname(__file__)) . "\\";
$new_home = str_replace("\\", "/", (dirname(dirname(dirname(__file__))) . "\\"));
echo "PHP new_home={$new_home}\n";
$tmp_txt = ".\\php_paths.txt";

/* get the "scripts" dir's php files' paths */
$command = "(for /r \"{$cur_scripts_path}\" %i in (*.php) do @echo %i) > \"{$tmp_txt}\"";	// just for window cmdz
// echo "PHP command = [{$command}]\n";
$ret_last_line = exec($command, $output, $ret_val);
echo "PHP exec - " . (0 === $ret_val ? "suc." : "failed!") . "\n";
if(0 != $ret_val)	exit(1);


/* get read the paths */
if(!($h = fopen($tmp_txt, "r")))	exit(1);
$c = fread($h, filesize($tmp_txt));
if(!fclose($h))	exit(1);
if(!$c)	exit(1);

$paths = explode("\r\n", $c);	// \r\n is just for win to seperate lines!
// var_dump($paths);
foreach($paths as $index => $path){
	if("" == $path || $path == __file__){
		continue;
	}
	// echo "process = [{$path}]\n";
	
	/* read cont */
	if(!($h = fopen($path, 'r')))	exit(1);
	$c = fread($h, filesize($path));
	if(!fclose($h))	exit(1);
	if(!$c)	exit(1);
	
	$c = preg_replace('/require\(\'.*?scripts\/library\/CONF\.php\'\);/',
		"require('{$new_home}scripts/library/CONF.php');", $c) . "\n";
	$c = preg_replace('/define\(\'HOME\', \'.*?\'\);/', "define('HOME', '{$new_home}');", $c) . "\n";
	
	/* write back */
	if(!($h = fopen($path, "w")))	exit(1);
	if(!fwrite($h, trim($c)))	exit(1);
	if(!fclose($h))	exit(1);
}
// var_dump($paths);

system("del php_paths.txt");

echo "PHP " . __file__ . " - fin.\n\n";

system("echo [ pause ] & pause > nul");

?>