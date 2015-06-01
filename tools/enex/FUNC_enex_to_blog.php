<?php

require(ENEX . 'FUNC_proc_attachment.php');

/*
 * 将从Evernote中导出.enex文件，转换为.html文件
 */

function enex_to_blog($params){
	/* $params = array(
		"enex_path" => ...,
		"dest_path" => ...,
		"test_path" => ...,
		"tags" => 'tag0, tag1, ...',
		"category" => 'category0', // please input only one!
	); */
	echo "\n";
	echo "PHP [ " . strtoupper(__file__) . " ]\n";

	if(!isset($params["enex_path"])){
		echo "PHP miss the param - enex_path!\n";
		return FALSE;
	}
	$cp = $params["enex_path"];
	// $cp = GB($params['enex_path']);
	// echo "cp=$cp\n";	//for debug

	/* 
	 * 绝不能使用中文路径的文件！
	 * 否则，无法使用simplexml_load_file函数打开。
	 * 因为Windows的文件名等，是带编码为gb2312的，
	 * 而PHP脚本文件是用的UTF-8，会读取错误。
	 */
	if(!($xml = simplexml_load_file($cp))){
		echo "PHP simplexml_load_file failed!\n";
		return FALSE;
	}

	// echo $xml->getName() . "\n";
	$note = $xml->note;
	$cont = $note->content;
	$t_cont = $cont;

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

	$rawurlencoded_title = rawurlencode($note->title);
	echo "rawurlencoded_title:{$rawurlencoded_title}" . "\n";
	
	$img_path = BLOG_IMG . "{$note->title}/";
	$img_url = "http://7vzp68.com1.z0.glb.clouddn.com/{$rawurlencoded_title}/";
	$img_url_loc = "./ice-blog-img/{$rawurlencoded_title}/";
	
	$att_path = BLOG_ATT . "{$note->title}/";
	$att_url = "http://7vzp67.com1.z0.glb.clouddn.com/{$rawurlencoded_title}/";
	$att_url_loc = "./ice-blog-att/{$rawurlencoded_title}/";
	
	system("mkdir \"" . GB(WP($img_path)) . "\"");
	system("mkdir \"" . GB(WP($att_path)) . "\"");
	
	foreach($note->resource as $res){
		$suffix = mimeToSuffix($res->mime);
		switch($suffix){
		case 'png':
		case 'gif':
		case 'jpeg':
			$cont = proc_image($cont, $res, $img_path, $img_url);
			$t_cont = proc_image_loc($t_cont, $res, $img_url_loc);
			break;
		default:
			$cont = proc_others($cont, $res, $att_path, $att_url);
			$t_cont = proc_others_loc($t_cont, $res, $att_url_loc);
			break;
		}
	}
	
	if(empty_dir($img_path))	system("rmdir /s /q \"" . GB(WP($img_path)) . "\"");
	if(empty_dir($att_path))	system("rmdir /s /q \"" . GB(WP($att_path)) . "\"");
	
	if(!last_proc($cont, $note, $params, $params["dest_path"])){
		return FALSE;
	}
	if(!last_proc($t_cont, $note, $params, $params["test_path"])){
		return FALSE;
	}
	return TRUE;
}

function last_proc($cont, $note, $params, $dest_path){
	if(1 != ($ret = preg_match('/(<en-note[\s\S]*?<\/en-note>)/', $cont, $matches))){
		echo "preg_match failed\n";
		echo "ret=$ret\n";
		return FALSE;
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
		. "tags: " . (isset($params['category']) ? ('[' . implode(", ", $tags) . ']') : '') . "\n"
		. "categories: " .  (isset($params['category']) ? "[{$params['categories']}]" : "") . "\n"
		. "---\n"
		. $matches[0]
	;
	// print_r($matches[0]);

	$dest_file_name = str_replace("/", "", str_replace("\\", "", $note->title));
	if(!($h = fopen(GB("{$dest_path}{$dest_file_name}.html"), "w")))	return FALSE;
	$c = fwrite($h, $matches[0]);
	if(!fclose($h))	return FALSE;
	if(!$c)	return FALSE;
	
	return TRUE;
}