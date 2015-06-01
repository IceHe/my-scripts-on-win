#!/usr/local/bin/php -q
<?php

/*
 * 编辑.enex文件的内容
 */
 
echo "PHP edit_enex.php - runs.\n";
 
//get custom funcs
$script_path = exec("echo %script%", $unused_output, $r_val);
if(0 != $r_val || "%script%" === $script_path){
	echo "PHP get path - \"%script%\" failed!\n";
	exit(1);
}
require("$script_path\\lib\\custom_funcs.php");


//get params from argv
$reqs = array(
	"%file_path",
	"/i=title",
	"/t=tag",
	"/c=ct=created_time",
	"/u=ut=updated_time",
);
$is_debug = FALSE;
$params = proc_param($argv, $reqs, "No tips.", $is_debug);
// if(!$is_debug)	var_dump($params);

//convert params' chinese encoding (from GB2312 to UTF8)
$params = convert_str_ary_encoding($params, "UTF-8", "GB2312");

//open .enex file
if(!isset($params["file_path"])){
	echo "PHP miss the param - file_path!\n";
	exit(1);
}
$cp = $params["file_path"];
// echo "cp=$cp\n";	//for debug

if(!($h = fopen($cp, "r")))	exit(1);
$c = fread($h, filesize($cp));
if(!fclose($h))	exit(1);
if(!$c)	exit(1);


//modify title
if(isset($params["title"]) &&
	NULL === ($c = preg_replace('/<title>.*?<\/title>/', "<title>$params[title]</title>", $c)))	exit(1);
// echo "$params[title]\n";	//for debug


//modify tag
if(isset($params["tag"])){
	$tags = "<tag>" . implode("</tag><tag>", explode(",", $params["tag"])) . "</tag>";
	// echo "$tags\n";	//for debug
	if(NULL === ($c = preg_replace('/<\/updated>.*?<\/note>/', "</updated>$tags</note>", $c)))	exit(1);
}


//set timezone
date_default_timezone_set("Asia/Shanghai");

//modify created_time
if(isset($params["created_time"])){
	if("removed" === $params["created_time"]){
		if(NULL === ($c = preg_replace('/<created>.*?<\/created>/', "<created></created>", $c)))	exit(1);
	}else if("reserved" === $params["created_time"]){
		//do nothing
	}else{
		if(FALSE === ($created_time = mktime(
			substr($params["created_time"], 8, 2),
			substr($params["created_time"], 10, 2),
			substr($params["created_time"], 12, 2),
			substr($params["created_time"], 4, 2),
			substr($params["created_time"], 6, 2),
			substr($params["created_time"], 0, 4)
		))){
			echo "PHP option created_time with a wrong param!\n";
			exit(1);
		}
		
		if(NULL === ($c = preg_replace('/<created>.*?<\/created>/', "<created>" . gmdate("Ymd\THis\Z", $created_time) . "</created>", $c)))	exit(1);
		// echo gmdate("Ymd\THis\Z\n", $created_time);	//for debug - UTC Time
		// echo date("Ymd\THis\Z\n", $created_time);	//for debug	- Shanghai Time
	}
}


//modify updated_time
if(isset($params["updated_time"])){
	if("removed" === $params["updated_time"]){
		if(NULL === ($c = preg_replace('/<updated>.*?<\/updated>/', "<updated></updated>", $c)))	exit(1);
	}else if("reserved" === $params["updated_time"]){
		//do nothing
	}else{
		if(FALSE === ($updated_time = mktime(
			substr($params["updated_time"], 8, 2),
			substr($params["updated_time"], 10, 2),
			substr($params["updated_time"], 12, 2),
			substr($params["updated_time"], 4, 2),
			substr($params["updated_time"], 6, 2),
			substr($params["updated_time"], 0, 4)
		))){
			echo "PHP option updated_time with a wrong param!\n";
			exit(1);
		}
		
		if(NULL === ($c = preg_replace('/<updated>.*?<\/updated>/', "<updated>" . gmdate("Ymd\THis\Z", $updated_time) . "</updated>", $c)))	exit(1);
		// echo gmdate("Ymd\THis\Z\n", $updated_time);	//for debug - UTC Time
		// echo date("Ymd\THis\Z\n", $updated_time);	//for debug	- Shanghai Time
	}
}


if(isset($params["updated_time"]) && "reserved" != $params["updated_time"] && "removed" != $params["updated_time"]){
	$updated_time = mktime(
		substr($params["updated_time"], 8, 2),
		substr($params["updated_time"], 10, 2),
		substr($params["updated_time"], 12, 2),
		substr($params["updated_time"], 4, 2),
		substr($params["updated_time"], 6, 2),
		substr($params["updated_time"], 0, 4)
	);
		
	if(NULL === ($c = preg_replace('/<updated>.*?<\/updated>/', "<updated>" . gmdate("Ymd\THis\Z", $updated_time) . "</updated>", $c)))	exit(1);
		
	// echo gmdate("Ymd\THis\Z\n", $updated_time);	//for debug - UTC Time
	// echo date("Ymd\THis\Z\n", $updated_time);	//for debug	- Shanghai Time
}else if("removed" === $params["updated_time"]){
	if(NULL === ($c = preg_replace('/<updated>.*?<\/updated>/', "<updated></updated>", $c)))	exit(1);
}else if("reserved" != $params["updated_time"]){
	echo "PHP option updated_time with a wrong param!";
	exit(1);
}


//直接去除各种标签，获得纯格式文本内容的尝试
//不完全成功

// $beg = strpos($c, "<en-note ");
// $end = strpos($c, "</en-note>");
// $c = substr($c, $beg, $end - $beg);
// $c = substr($c, strpos($c, "\">") + 2);

// $c = html_entity_decode($c);
// $c = str_replace("<br/>", "\n", $c);
// $c = strip_tags($c);
// echo $c;


//write back to .enex file
if(!($h = fopen($cp/* $p . '\tmp.txt' */, "w")))	exit(1);
if(!($r = fwrite($h, $c)))	exit(1);
if(!fclose($h))	exit(1);

echo "PHP edit_enex.php - suc.\n";

// system("pause > nul");

?>