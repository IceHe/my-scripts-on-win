#!/usr/local/bin/php -q
<?php

/* REQUIRE CONF.PHP FIRST! */
define('IS_DEBUG', FALSE);
require('C:/ice_php_envir/scripts/library/CONF.php');
require(ENEX . 'FUNC_enex_to_blog.php');

/*
 * post .enex files to hexo according to the complete path
 */
echo "\n";
echo "PHP [ " . strtoupper(__file__) . " ]\n";

/* get params from argv */
$reqs = array(
	"%enex_dir_path",
);
$params = proc_param($argv, $reqs, "No tips.", IS_DEBUG);
echo "PHP proc_param(\$argv) - suc.\n";
// if(!IS_DEBUG)	var_dump($params);

/* convert params' chinese encoding (from GB2312 to UTF8) */
// $params = convert_str_ary_encoding($params, "GB2312", "UTF-8");

$enex_dir_path = "";
if(!isset($params['enex_dir_path'])){
	/* get ariticle title */
	echo "enex_dir_path=";
	if("" == ($enex_dir_path = input())){
		echo "Must input a dir where .enex files is!\n";
		system("echo [ pause ] & pause > nul");
		exit(1);
	}
}else{
	$enex_dir_path = $params['enex_dir_path'];
}

if(!is_dir($enex_dir_path)){
	echo "PHP enex_dir_path [$enex_dir_path] doesn't exist!\n";
	exit(1);
}
if(!($dir = opendir($enex_dir_path))){
	echo "PHP enex_dir_path [$enex_dir_path] couldn't be open!\n";
	exit(1);
}

$enex_paths = array();
while($p = readdir($dir)){
	echo "$p\n";
	if('.' != $p && '..' != $p){
		echo "{$enex_dir_path}/{$p}\n";
		if(is_file("{$enex_dir_path}/{$p}")
			&& is_suffix('enex', $p)){
			$enex_paths[] = UP("{$enex_dir_path}/{$p}");
			echo "{$enex_dir_path}/{$p} is enex~\n";
		}
	}
}
print_r($enex_paths);

foreach($enex_paths as $i => $path){
	/* echo vars - for debug */
	if(IS_DEBUG){
		echo "PHP enex_dir_path = [{$enex_dir_path}]\n";
		echo "PHP path = [{$path}]\n";
	}

	/* enex to blog */
	$params = array(
		'enex_dir_path' => $path,
		'dest_path' => BLOG_HEXO . "source/_posts/",
		'test_path' => BLOG,
	);
	var_dump($params); // for debug
	
	
	if(!enex_to_blog($params)){
		echo "PHP php enex_to_blog [{}]- failed!\n";
	}else{
		echo "PHP php enex_to_blog [{}]- suc.\n";
		/* delete enex file which has already been posted */
		$command = "erase \"{$path}\"";
		if(0 != exec_and_log($command, "PHP delete .enex file [{$path}]",
			$ret_last_line, $output, false, __FILE__))	exit(1);
		/* 无论是否有成功删掉，此处都会返回suc。
		所以请注意上一句的输出，是否输出了类似如下信息：
		Could Not Find C:\Users\IceHe\tmpo\tmp_4edit_d150208_t212313_r10714.enexx */
	}
}

echo "PHP " . __file__ . " - fin.\n\n";
system("echo [ pause ] & pause > nul");
exit(0);

?>