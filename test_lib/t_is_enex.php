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

function is_suffix($suffix, $path){
	$pattern = '/.*\.' . $suffix . '$/';
	if(1 != preg_match($pattern, $path))
		return FALSE;
	else
		return TRUE;
}

echo (is_suffix('php', 'C:\ice_php_envir\scripts\tools\t_is_enex.php')?"y":"n") . "\n";
echo (is_suffix('php', 'C:\Users\IceHe\Desktop\enex\test_attachment.enex')?"y":"n") . "\n";

echo "PHP " . __file__ . " - fin.\n\n";
system("echo [ pause ] & pause > nul");
exit(0);

?>