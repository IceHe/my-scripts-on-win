#!/usr/local/bin/php -q
<?php

echo "\n";

$s = "Tue Feb 10 2015 00:00:00 GMT+0800";
date_default_timezone_set("Asia/Shanghai");
$t = strtotime($s);

echo "$s\n";
echo "$t\n";
echo date("c\n", $t);
echo date("c\n", (1422720000000/1000));
echo date("c\n", 1423497600);

echo "\n";

// system("pause > nul");

?>