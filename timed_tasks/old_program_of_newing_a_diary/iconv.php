#!/usr/local/bin/php -q
<?php

//get custom funcs
$script_path = exec("echo %script%", $unused_output, $r_val);
if(0 != $r_val || "%script%" === $script_path){
	echo "PHP get path - \"%script%\" failed!\n";
	return;
}
require("$script_path\\custom_funcs.php");

/*
 * 文件编码转换：UTF-8 to GB2312
 * 目的：为了让Windows CommandLine 的 cmd 
 *		 能够无乱码地读入转码后的文件。
 */
echo "PHP iconv.php runs.\n";

$file_path = exec("echo %tmpo%", $unused_output, $r_val);
if(0 != $r_val || "%tmpo%" === $file_path){
	echo "PHP get path - \"%tmpo%\" failed!\n";
	return;
}

if(!($file_path = get_env_var("tmpo")))	return;

$file_path .= '\diary_tpl.enex';
// $file_path .= '\tmp.html';	//just for test
echo "PHP \$file_path=$file_path\n";	
//单引号中的字符串不转义

if(!($handler = fopen($file_path, "r")))	return;

$content = fread($handler, filesize($file_path));
if(!fclose($handler))	return;
if(!$content)	return;

if(!($content = mb_convert_encoding($content, "GB2312"/* , "UTF-8" */))){
	echo "PHP mb_convert_encoding() failed!\n";
	return;
}else{
	// echo "PHP mb_convert_encoding(UTF-8 to GB2312) suc.\n";
	echo "PHP mb_convert_encoding to GB2312 suc.\n";
}

if(!($handler = fopen($file_path, "w")))	return;
if(!($r = fwrite($handler, $content)))	return;

fclose($handler);
echo "PHP iconv.php suc.\n";

?>