#!/usr/local/bin/php -q
<?php

/* REQUIRE CONF.PHP FIRST! */
define('IS_DEBUG', FALSE);
require('C:/ice_php_envir/scripts/library/CONF.php');

/*
 * Copy the template of .bat file to current directory.
 */
echo "\n";
echo "PHP [ " . strtoupper(__file__) . " ]\n";

/* get file name */
echo "file_name=";
$file_name = input();
// echo "file_name=[$file_name]\n";

$src_fname = "TPL.bat";
$src_fp = WP(TPLS) . $src_fname;


$des_fname = ("" == $file_name ? "UNTITLED" : $file_name) . ".bat";
$des_path = "./";
$des_fp = WP($des_path) . $des_fname;

echo "PHP src_fp = [{$src_fp}]\n";
echo "PHP des_fp = [{$des_fp}]\n";

/* copy TPL.bat file to cur dir */
// $command = "for /f %z in (\"{$des_fp}\") do (for /f %i in (\"{$src_fp}\") do copy /y %~fi %~fz)";	// just for window cmd
$command = "copy /y \"{$src_fp}\" \"{$des_fp}\"";
// echo "PHP command = [{$command}]\n";

if(0 != exec_and_log($command, "PHP new .bat file [{$des_fp}]",
	$ret_last_line, $output, false, __FILE__))	exit(1);
//无论是否有成功删掉，此处都会返回suc。
//所以请注意上一句的输出，是否输出了类似如下信息：
//Could Not Find C:\Users\IceHe\tmpo\tmp_4edit_d150208_t212313_r10714.enexx

echo "PHP " . __file__ . " - fin.\n\n";

// system("echo [ pause ] & pause > nul");

?>