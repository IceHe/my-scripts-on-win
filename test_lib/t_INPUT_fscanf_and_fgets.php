#!/usr/local/bin/php -q
<?php

/* REQUIRE CONF.PHP FIRST! */
define('IS_DEBUG', FALSE);
require('C:/ice_php_envir/scripts/library/CONF.php');

/*
 * t_INPUT_fscanf_and_fgets
 */
echo "\n";
echo "PHP [ " . strtoupper(__file__) . " ]\n";

$line = trim(fgets(STDIN)); // reads one line from STDIN
fscanf(STDIN, "%s\n", $line2); 
echo $line . "\n";
echo $line2 . "\n";

echo "PHP " . __file__ . " - fin.\n\n";

system("echo [ pause ] & pause > nul");

?>