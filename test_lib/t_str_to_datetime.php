#!/usr/local/bin/php -q
<?php

echo "\n";

date_default_timezone_set("Asia/Shanghai");
echo date("Ymd\THis\Z", strtotime(19990316000000));
echo "\n";
echo gmdate("Ymd\THis\Z", strtotime(19990316000000));
echo "\n";
echo date("Ymd\THis\Z", strtotime(20001020111111));
echo "\n";
echo gmdate("Ymd\THis\Z", strtotime(20001020111111));
echo "\n";

// system("pause > nul");

?>