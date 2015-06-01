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

$str = '&#35201;&#26377;&#25152;&#20934;&#22791;&#65292;';
echo "$str\n";
$str = mb_convert_encoding(html_entity_decode($str), "GB2312", "UTF-8");
echo "$str\n";

echo "PHP " . __file__ . " - fin.\n\n";
system("echo [ pause ] & pause > nul");
exit(0);

?>