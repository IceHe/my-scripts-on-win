#!/usr/local/bin/php -q
<?php

//get custom funcs
$script_path = exec("echo %script%", $unused_output, $r_val);
if(0 != $r_val || "%script%" === $script_path){
	echo "PHP get path - \"%script%\" failed!\n";
	return;
}
require("$script_path\\custom_funcs.php");

$reqs = array(
	"%p1",
	"%p2",
	// "/a",
	"/i=title",
	"/n=nb=notebook",
	// ".x=ext",
	// ".b",
	".v=of=overflow",
);

$is_debug = TRUE;
$params = proc_param($argv, $reqs, "tips is here.", $is_debug);

if(!$is_debug){
	echo "params=";
	var_dump($params);
}
system("pause > nul");

?>