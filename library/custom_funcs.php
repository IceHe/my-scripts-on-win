<?php

define('CUS_FUNS', LIBRARY . 'custom_funcs/');

/* the func files's paths needed to require */
$req_func_paths = array(
	CUS_FUNS . 'miscellaneous.php',	//较短的自定义函数
	CUS_FUNS . 'proc_param.php',	//处理传入.php脚本的参数
	CUS_FUNS . 'log.php',	//记录脚本的运行日志
	// CUS_FUNS . '\abandoned.php',	//弃用的
);

foreach($req_func_paths as $index => $path){
	require($path);
}

if(IS_DEBUG){
	echo "\n";
	echo "PHP [ " . strtoupper(__file__) . " ]\n";
	echo "PHP REQUIRE (LIB FUNC FILEs) :\n";
	foreach($req_func_paths as $index => $path){
		echo "PHP path[{$index}] = [{$path}]\n";
	}
}