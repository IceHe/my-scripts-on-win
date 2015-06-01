#!/usr/local/bin/php -q
<?php

/* REQUIRE CONF.PHP FIRST! */
define('IS_DEBUG', FALSE);
require('C:/ice_php_envir/scripts/library/CONF.php');

/*
 * Batch post articles to my hexo blog
 * [NOTICE!] Request:
 *	The names of .enex files in enex_path must
 *	be same as their actual evernote titles !!!
 */
echo "\n";
echo "PHP [ " . strtoupper(__file__) . " ]\n";

/* get params from argv */
$reqs = array(
	"%titles_file",
);
$params = proc_param($argv, $reqs, "No tips.", IS_DEBUG);
echo "PHP proc_param(\$argv) - suc.\n";
if(!IS_DEBUG)	var_dump($params);

/* convert params' chinese encoding (from GB2312 to UTF8) */
// $params = convert_str_ary_encoding($params, "GB2312", "UTF-8");

/* get params from cmd keyboard input */
$titles_file = "";
if(!isset($params['titles_file'])){
	/* get the path where the .enex files is */
	echo "titles_file=";
	if("" == ($titles_file = input())){
		echo "Must input the path where the .enex files is!\n";
		system("echo [ pause ] & pause > nul");
		exit(1);
	}
}else{
	$titles_file = $params['titles_file'];
}


if(!($h = fopen($titles_file, "r")))	exit(1);
echo "PHP open the titles_file - suc.\n";

// $note_titles = fscanf($h, "%s\n"); 

$note_titles = array();
while($title = fgets($h)){
	// echo "$title\n";
	$note_titles[] = str_replace("\r", "\0", str_replace("\n", "\0", mb_convert_encoding($title, "GB2312", "UTF-8")));
}
var_dump($note_titles);

if(!fclose($h))	exit(1);
echo "PHP read the titles_file - suc.\n";

/* post_evernote_to_blog */
foreach($note_titles as $i => $title){
	$command = "php \""
		. ENEX . "post_evernote_to_blog.php\""
		. " \"{$title}\""
	;
	echo $command . "\n"; // for debug
	if(0 != ($ret = exec_and_log($command, "PHP php post_evernote_to_blog [{$title}]",
		$ret_last_line, $output, false, __FILE__)))	exit(1);
}

echo "PHP " . __file__ . " - fin.\n\n";
system("echo [ pause ] & pause > nul");
exit(0);

?>