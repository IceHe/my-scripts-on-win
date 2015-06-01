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

$path = 'C:/ice_php_envir/blog/ice-blog-att/Effective C++ - Reading Note 1';
$dir = opendir($path);
while($i = readdir($dir)){
	echo "$i\n";
	if('.' != $i && '..' != $i){
		echo $path . $i . "\n";
		if(is_file($path . '/' . $i)
			|| is_dir($path . '/' . $i)){
			echo "The dir is not empty!\n";
			exit(1);
		}
	}
}
echo "The dir is empty.\n";
// return FALSE;

echo "PHP " . __file__ . " - fin.\n\n";

system("echo [ pause ] & pause > nul");

?>