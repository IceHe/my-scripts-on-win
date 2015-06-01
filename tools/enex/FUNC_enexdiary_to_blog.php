<?php

require(ENEX . 'FUNC_proc_attachment.php');

/*
 * 将从Evernote中导出.enex文件，转换为.html文件
 */

function diary_to_blog($params){
	/* $params = array(
		"enex_path" => ...,
		"dest_path" => ...,
		"test_path" => ...,
		"diary_date" => 'yymmdd'
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
	
	if(!last_proc_diary($cont, $note, $params, $params["dest_path"])){
		return FALSE;
	}
	if(!last_proc_diary($t_cont, $note, $params, $params["test_path"])){
		return FALSE;
	}
	return TRUE;
}

function last_proc_diary($cont, $note, $params, $dest_path){
/* 	if(1 != ($ret = preg_match('/(<en-note [\s\S]*?<\/en-note>)/', $cont, $matches))){
		echo "preg_match failed\n";
		echo "ret=$ret\n";
		return FALSE;
	}

	date_default_timezone_set("Asia/Shanghai");
	$matches[0] = str_replace('<en-note ', '<div ', $matches[0]);
	$matches[0] = str_replace('</en-note>', '</div>', $matches[0]);
	// $matches[0] = "title: {$note->title}\n"
		// . "date: " . date('Y-m-d H:i:s', time()) . "\n"
		// . "---\n"
		// . $matches[0]
	// ;
	// print_r($matches[0]);
	
	$c2 = $matches[0]; */
	
	$cont = str_replace('<en-todo checked="false"', '<input type="checkbox" ', $cont);
	$cont = str_replace('<en-todo checked="true"', '<input type="checkbox" checked="true" ', $cont);

	if(1 != ($ret = preg_match(
		'/((?:<div>)?Pri Tasks:[\s\S]*废[\s\S]*?总[\d\s\.， ]*(?:<br\/>|<\/div>)?)(?:<div>)?/',
		// '/((?:<div>)?\d\d-\d\d[\s\S]*废[\s\S]*?总[\d\s\.， ]*(?:<br\/>|<\/div>)?)(?:<div>)?/',
		html_entity_decode($cont), $matches))){
		echo "preg_match failed\n";
		echo "ret=$ret\n";
		
		if(/* IS_DEBUG */TRUE){
			if(!($h = fopen(GB(DESTOP . "error_output.md"), "w")))	return FALSE;
			$ret = fwrite($h, html_entity_decode($cont));
			if(!fclose($h))	return FALSE;
			if(!$ret)	return FALSE;
		}
		
		return FALSE;
	}
	
	date_default_timezone_set("Asia/Shanghai");
	$today = mktime(0, 0, 0,
		substr($params['diary_date'], 2, 2),
		substr($params['diary_date'], 4, 2),
		'20' . substr($params['diary_date'], 0, 2)
	);
	$c_Ym = date('Y/m', $today);
	$prev_day = $today - 86400;
	$next_day = $today + 86400;
	$p_MdY = date('M. d, Y', $prev_day);
	$n_MdY = date('M. d, Y', $next_day);
	$p_Ymd = date('Y/m/\dd', $prev_day);
	$n_Ymd = date('Y/m/\dd', $next_day);
	
	$matches[1] = "title: {$note->title}\n"
		. "date: " . date('Y-m-d H:i:s', $today) . "\n"
		. "toc: false\n"
		. "---\n"
		. "[**< {$p_MdY}** - Prev 上一天](/lifelogs/{$p_Ymd}.html) &nbsp; &nbsp; | &nbsp; &nbsp; [下一天 Next - **{$n_MdY} >**](/lifelogs/{$n_Ymd}.html) &nbsp; &nbsp; |  &nbsp; &nbsp; [返回月历 **Back to Month ^**](/lifelogs/{$c_Ym}/index.html)
<br/>"
		. str_replace("\n", "", str_replace("\r", "", $matches[1]))	// remove the characters - \n & \r 
	;

	// $dest_file_name = str_replace("/", "", str_replace("\\", "", $note->title));
	$dest_file_name = "d" . date("d", $today);
	if(!($h = fopen(GB("{$dest_path}{$dest_file_name}.md"), "w")))	return FALSE;
	$ret = fwrite($h, $matches[1]);
	if(!fclose($h))	return FALSE;
	if(!$ret)	return FALSE;
	
	/* add link to the month's index.md file */
	$append_str = date("j", $today) . ". [{$note->title}](/lifelogs/" . date('Y/m/\dd', $today) . ".html)\n";
	if(!($h = fopen(GB("{$dest_path}index.md"), "a")))	return FALSE;
	$ret = fwrite($h, $append_str);
	if(!fclose($h))	return FALSE;
	if(!$ret)	return FALSE;
	
	return TRUE;
}