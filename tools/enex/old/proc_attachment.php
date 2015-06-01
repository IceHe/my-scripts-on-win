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

function proc_image($cont, $res, $save_path = './', $img_url = './', $is_debug = TRUE){
	$en_media = base64_decode($res->data);
	$md5 = md5($en_media);
	$file_name = $res->{'resource-attributes'}->{'file-name'};
	
	/* 使用mb_convert_encoding转码文件路径是为了在windows中正确建立文件 */
	if(FALSE === ($h = fopen(mb_convert_encoding("{$save_path}{$file_name}", "GB2312", "UTF-8"), 'w')))	return FALSE;
	if(FALSE === ($c = fwrite($h, $en_media)))	return FALSE;
	if(FALSE === fclose($h))	return FALSE;
	
	if($is_debug){
		echo "resource--\n";
		// echo "data:" . $res->data . "\n";
		echo "mime:" . $res->mime . "\n";
		// echo "suffix:" . $suffix . "\n";
		echo "file-name:{$file_name}" . "\n";
		echo "width:" . $res->width . "\n";
		echo "height:" . $res->height . "\n";
		echo 'md5:' . $md5 . "\n";
		echo "\n";
	}
	
	return preg_replace("/<en-media hash=\"" . $md5 . "\".*?\/>/", "<img width=\"{$res->width}px\" height=\"{$res->height}px\" src=\"{$img_url}{$file_name}\" />", $cont);
}

function proc_others($cont, $res, $save_path = './', $img_url = './', $is_debug = TRUE){
	$en_media = base64_decode($res->data);
	$md5 = md5($en_media);
	$file_name = $res->{'resource-attributes'}->{'file-name'};
	
	/* 使用mb_convert_encoding转码文件路径是为了在windows中正确建立文件 */
	if(FALSE === ($h = fopen(mb_convert_encoding("{$save_path}{$file_name}", "GB2312", "UTF-8"), 'w')))	return FALSE;
	if(FALSE === ($c = fwrite($h, $en_media)))	return FALSE;
	if(FALSE === fclose($h))	return FALSE;
	
	if($is_debug){
		echo "resource--\n";
		// echo "data:" . $res->data . "\n";
		echo "mime:{$res->mime}" . "\n";
		echo "file-name:{$file_name}" . "\n";
		echo 'md5:{$md5}' . "\n";
		echo "\n";
	}
	
	return preg_replace("/<en-media hash=\"" . $md5 . "\".*?\/>/", "<strong>Att - </strong><a title=\"Attachment 附件\" href=\"{$img_url}{$file_name}\" target=\"_blank\">{$file_name}</a>", $cont);
}

?>