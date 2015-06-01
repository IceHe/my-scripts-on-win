#!/usr/local/bin/php -q
<?php

/* REQUIRE CONF.PHP FIRST! */
define('IS_DEBUG', FALSE);
require('C:/ice_php_envir/scripts/library/CONF.php');


/*
 * Test whether the path has already existed
 *	in the "PATH" Envir Vars or not?
 */
echo "\n";
echo "PHP [ " . strtoupper(__file__) . " ]\n";


/* get params from argv */
$reqs = array(
	"%path",
);
$params = proc_param($argv, $reqs, "No tips.", IS_DEBUG);
echo "PHP proc_param(\$argv) - suc.\n";
// if(!IS_DEBUG)	var_dump($params);
if(!isset($params['path'])){
	$params['path'] = input();
}

/* preparation */
$params['path'] = trim($params['path']);
$ori_p = strtolower($params['path']);
$long_p = strtolower($params['path'] . "\\");
$short_p = strtolower(substr($params['path'], 0, strlen($params['path']) - 1));
// echo "{$ori_p}\n";
// echo "{$long_p}\n";
// echo "{$short_p}\n";


/* match the PATH */
$is_in = FALSE;
$cur_paths = explode(";", getenv("PATH"));
foreach($cur_paths as $index => $path){
	echo $path . "\n";
	if(strtolower($path) === $ori_p
		|| strtolower($path) === $long_p
		|| strtolower($path) === $short_p){
		$is_in = TRUE;
		break;
	}
}
// echo "is_in = " . ($is_in ? "TRUE" : "FALSE") . "\n";


/* return result */
echo "Is in \"PATH\"? " . ($is_in ? "TRUE" : "FALSE") . "\n";
exit($is_in ? 0 : 1);


// echo "PHP " . __file__ . " - fin.\n\n";

system("echo [ pause ] & pause > nul");

?>