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

$path = 'C:\ice_php_envir\scripts\tools\\\\';
if(empty($path))	return FALSE;

$pattern = '/(.*?)[\/|\\\\]*$/';
echo "pattern = [{$pattern}]\n";
if(1 != ($ret = preg_match($pattern, $path, $matches))){
	echo "ret=$ret\n";
	return FALSE;
}
echo "ret=$ret\n";
echo "match0 = [{$matches[0]}]\n";
echo "match1 = [{$matches[1]}]\n";

echo "PHP " . __file__ . " - fin.\n\n";
system("echo [ pause ] & pause > nul");
exit(0);

?>