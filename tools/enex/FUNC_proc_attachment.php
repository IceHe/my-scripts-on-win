<?php

/*
 * process different types of attachments
 */

function mimeToSuffix($mime){
	static $mime2suffix = array(
		'image/png' => 'png',
		'image/gif' => 'gif',
		'image/jpeg' => 'jpeg',
		// 'text/plain' => 'txt',
		// 'application/octet-stream' => 'octet-stream',
	);
	return (isset($mime2suffix["{$mime}"]) ? $mime2suffix["{$mime}"] : FALSE);
}

function proc_image($cont, $res, $save_path = './', $img_url = './', $is_debug = FALSE){
	$en_media = base64_decode($res->data);
	$md5 = md5($en_media);
	$file_name = $res->{'resource-attributes'}->{'file-name'};
	if("" == $file_name)	$file_name = "{$md5}." . (mimeToSuffix($res->mime) ? mimeToSuffix($res->mime) : 'jpeg');
	$rawurlencoded_name = rawurlencode($file_name);
	
	/* 使用mb_convert_encoding转码文件路径是为了在windows中正确建立文件 */
	if(!($h = fopen(GB("{$save_path}{$file_name}"), 'w')))	return FALSE;
	if(!($c = fwrite($h, $en_media)))	return FALSE;
	if(!fclose($h))	return FALSE;
	
	if($is_debug){
		echo "resource--\n";
		// echo "data:" . $res->data . "\n";
		echo "mime:" . $res->mime . "\n";
		// echo "suffix:" . $suffix . "\n";
		echo "file-name:{$file_name}" . "\n";
		echo "rawurlencoded_name:{$rawurlencoded_name}" . "\n";
		echo "width:" . $res->width . "\n";
		echo "height:" . $res->height . "\n";
		echo "md5:{$md5}\n";
		echo "\n";
	}
	
	return preg_replace("/<en-media .*?hash=\"" . $md5 . "\".*?(\/>|><\/en-media>)/", "<img width=\"{$res->width}px\" height=\"{$res->height}px\" src=\"{$img_url}{$rawurlencoded_name}\" />", $cont);
}

function proc_others($cont, $res, $save_path = './', $att_url = './', $is_debug = FALSE){
	$en_media = base64_decode($res->data);
	$md5 = md5($en_media);
	$file_name = $res->{'resource-attributes'}->{'file-name'};
	if("" == $file_name)	$file_name = "{$md5}." . (mimeToSuffix($res->mime) ? mimeToSuffix($res->mime) : 'jpeg');
	$rawurlencoded_name = rawurlencode($file_name);
	
	/* 使用mb_convert_encoding转码文件路径是为了在windows中正确建立文件 */
	if(!($h = fopen(GB("{$save_path}{$file_name}"), 'w')))	return FALSE;
	if(!($c = fwrite($h, $en_media)))	return FALSE;
	if(!fclose($h))	return FALSE;
	
	if($is_debug){
		echo "resource--\n";
		// echo "data:" . $res->data . "\n";
		echo "mime:{$res->mime}" . "\n";
		echo "file-name:{$file_name}" . "\n";
		echo "rawurlencoded_name:{$rawurlencoded_name}" . "\n";
		echo "md5:{$md5}\n";
		echo "\n";
	}
	
	return preg_replace("/<en-media .*?hash=\"" . $md5 . "\".*?(\/>|><\/en-media>)/", "<strong>Att - </strong><a title=\"Attachment 附件\" href=\"{$att_url}{$rawurlencoded_name}\" target=\"_blank\">{$file_name}</a>", $cont);
}

function proc_image_loc($cont, $res, $img_url = './', $is_debug = FALSE){
	$en_media = base64_decode($res->data);
	$md5 = md5($en_media);
	$file_name = $res->{'resource-attributes'}->{'file-name'};
	if("" == $file_name)	$file_name = "{$md5}." . (mimeToSuffix($res->mime) ? mimeToSuffix($res->mime) : 'jpeg');
	$rawurlencoded_name = rawurlencode($file_name);
	
	//return preg_replace("/<en-media hash=\"" . $md5 . "\".*?(\/>|><\/en-media>)/", "<img width=\"{$res->width}px\" height=\"{$res->height}px\" src=\"{$img_url}{$rawurlencoded_name}\" />", $cont);
	return preg_replace("/<en-media .*?hash=\"" . $md5 . "\".*?(\/>|><\/en-media>)/", "<img width=\"{$res->width}px\" height=\"{$res->height}px\" src=\"{$img_url}{$rawurlencoded_name}\" />", $cont);
}

function proc_others_loc($cont, $res, $att_url = './', $is_debug = FALSE){
	$en_media = base64_decode($res->data);
	$md5 = md5($en_media);
	$file_name = $res->{'resource-attributes'}->{'file-name'};
	if("" == $file_name)	$file_name = "{$md5}." . (mimeToSuffix($res->mime) ? mimeToSuffix($res->mime) : 'jpeg');
	$rawurlencoded_name = rawurlencode($file_name);
	
	//return preg_replace("/<en-media hash=\"" . $md5 . "\".*?((\/>)|(><\/en-media>))/", "<strong>Att - </strong><a title=\"Attachment 附件\" href=\"{$att_url}{$rawurlencoded_name}\" target=\"_blank\">{$file_name}</a>", $cont);
	return preg_replace("/<en-media .*?hash=\"" . $md5 . "\".*?((\/>)|(><\/en-media>))/", "<strong>Att - </strong><a title=\"Attachment 附件\" href=\"{$att_url}{$rawurlencoded_name}\" target=\"_blank\">{$file_name}</a>", $cont);
}

?>