<?php

/* REQUIRE CONF.PHP FIRST! */
// define('IS_DEBUG', FALSE);
define('IS_DEBUG', TRUE);
require('C:/ice_php_envir/scripts/library/CONF.php');
require(ENEX . 'proc_attachment.php');		


/*
 * 将从Evernote中导出.enex文件，转换为.html文件
 */
echo "\n";
echo "PHP [ " . strtoupper(__file__) . " ]\n";
 
/* get params from argv */
$reqs = array(
	// "/d=date",
	// "/t=time",
	// "/q=qry=query",
	"/c=category",
	"/t=tags",
	"/p=fp=file_path",
	"/o=op=output_path",
);
$params = proc_param($argv, $reqs, "No tips.", IS_DEBUG);
echo "PHP proc_param(\$argv) - suc.\n";
// if(!IS_DEBUG)	var_dump($params);

/* just for dev */
$params['file_path'] = 'C:\Users\IceHe\Desktop\enex\test_attachment.enex';

/* convert params' chinese encoding (from GB2312 to UTF8) */
// $params = convert_str_ary_encoding($params, "GB2312", "UTF-8");

if(!isset($params["file_path"])){
	echo "PHP miss the param - file_path!\n";
	exit(1);
}
$cp = $params["file_path"];
// $cp = mb_convert_encoding($params['file_path'], "GB2312", "UTF-8");
// echo "cp=$cp\n";	//for debug

/* 
 * 绝不能使用中文路径的文件！
 * 否则，无法使用simplexml_load_file函数打开。
 * 因为Windows的文件名等，是带编码为gb2312的，
 * 而PHP脚本文件是用的UTF-8，会读取错误。
 */
if(FALSE === ($xml = simplexml_load_file($cp))){
	echo "PHP simplexml_load_file failed!\n";
	exit(1);
}

// echo $xml->getName() . "\n";
$note = $xml->note;
$cont = $note->content;

if(IS_DEBUG){
	echo "title:" . $note->title . "\n";
	echo "created:" . $note->created . "\n";
	echo "updated:" . $note->updated . "\n";
	foreach($note->tag as $k => $v){
		echo "tag[$k]:" . $v . "\n";
	}
	echo "\n";
	// echo "content:\n" . $cont . "\n";
}

foreach($note->resource as $res){
	$suffix = mimeToSuffix($res->mime);
	switch($suffix){
	case 'png':
	case 'gif':
	case 'jpeg':
		$cont = proc_image($cont, $res);
		break;
	default:
		$cont = proc_others($cont, $res);
		break;
	}
}

if(1 != ($ret = preg_match('/(<en-note .*?<\/en-note>)/', $cont, $matches))){
	echo "preg_match failed\n";
	echo "ret=$ret\n";
	exit(1);
}

$tags = array();
foreach($note->tag as $k => $v){
	$tags[] = $v;
}
if(isset($params['tags']))	$tags[] = $params['tags'];

date_default_timezone_set("Asia/Shanghai");
$matches[0] = str_replace('<en-note ', '<div ', $matches[0]);
$matches[0] = str_replace('</en-note>', '</div>', $matches[0]);
$matches[0] = "title: {$note->title}\n"
	. "date: " . date('Y-m-d H:i:s', time()) . "\n"
	. "tags: [" . implode(", ", $tags) . "]\n"
	. "categories: " .  (isset($params['category']) ? "[{$params['categories']}]" : "") . "\n"
	. "---\n"
	. $matches[0]
;
// print_r($matches[0]);

if(!($h = fopen("{$note->title}.html", "w")))	exit(1);
$c = fwrite($h, $matches[0]);
if(!fclose($h))	exit(1);
if(!$c)	exit(1);

?>