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

echo "diary_date(YYYYmm)=";
if("" == ($diary_date = input())){
	echo "Must input a note title!\n";
	system("echo [ pause ] & pause > nul");
	exit(1);
}

$year = substr($diary_date, 0, 4);
$month = substr($diary_date, 4, 2);

date_default_timezone_set("Asia/Shanghai");
$specified_m = mktime(0, 0, 0,
	$month, 1,
	$year
);

$prev_m = mktime(0, 0, 0,
	(1 == (int)$month ? 12 : (int)$month - 1), 1,
	(1 == (int)$month ? (int)$year - 1 : (int)$year)
);
$next_m = mktime(0, 0, 0,
	(12 == (int)$month ? 1 : (int)$month + 1), 1,
	(12 == (int)$month ? (int)$year + 1 : (int)$year)
);

echo "prev_m[" . date("Ym", $prev_m) . "]\n";
echo "spec_m[" . date("Ym", $specified_m) . "]\n";
echo "next_m[" . date("Ym", $next_m) . "]\n";

echo "PHP " . __file__ . " - fin.\n\n";
system("echo [ pause ] & pause > nul");
exit(0);

?>