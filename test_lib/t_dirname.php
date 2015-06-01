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

echo "dirname=[" . __dir__ . "]\n";

echo "PHP " . __file__ . " - fin.\n\n";
system("echo [ pause ] & pause > nul");
exit(0);

?>