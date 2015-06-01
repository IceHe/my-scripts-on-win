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

echo rawurlencode("[00] U盘安装 CentOS 7");
echo "\n";
echo mb_convert_encoding(urldecode("%5B00%5D%20U%E7%9B%98%E5%AE%89%E8%A3%85%20CentOS%207%0D%0A"), "GB2312", "UTF-8");
echo "\n";

echo "PHP " . __file__ . " - fin.\n\n";
system("echo [ pause ] & pause > nul");
exit(0);

?>