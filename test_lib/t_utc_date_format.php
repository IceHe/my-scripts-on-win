#!/usr/local/bin/php -q
<?php
date_default_timezone_set("Asia/Shanghai");

echo "\n";
echo "now:\n";

echo gmdate("c");
echo "\n";
echo date("c");
echo "\n";

echo gmdate("Ymd\THis\Z");
echo "\n";
echo date("Ymd\THis\Z");
echo "\n";

// /*===============================*/
echo "\n";
echo "past:\n";

echo gmdate("c", mktime(12, 44, 56, 3, 16, 1992));
echo "\n";
echo date("c", mktime(12, 44, 56, 3, 16, 1992));
echo "\n";
echo gmdate("Ymd\THis\Z", mktime(12, 44, 56, 3, 16, 1992));
echo "\n";
echo date("Ymd\THis\Z", mktime(12, 44, 56, 3, 16, 1992));
echo "\n";
echo "\n";

system("pause");

?>