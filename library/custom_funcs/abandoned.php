<?php

/*
 * safe file oper func
 * 后来发现我写的这些函数毫无必要，
 * 一旦fopen/fclose/fread/fwrite出错，
 * Error_reporting的错误消息都提示错误发生在这里，
 * 不方便定位到调用该函数具体文件及其行数。
 * 所以，不如直接使用原函数。
 * 
 * 不过日后要添加日志，可以考虑以下，
 * 不过PHP本身自带日志功能，不需要自己再写。
 */
// function fopen_c($path, $mode,
	// $ok_str = "", $fail_str = "PHP fopen() failed!\n"){
	// $handler = fopen($path, $mode);
	// if(!$handler){
		// echo $fail_str;
		// return FALSE;
	// }else{
		// echo $ok_str;
		// return $handler;
	// }
// }
// function fclose_c($handler,
	// $ok_str = "", $fail_str = "PHP fclose() failed!\n"){
	// $result = fclose($handler);
	// if(!$result){
		// echo $fail_str;
		// return FALSE;
	// }else{
		// echo $ok_str;
		// return TRUE;
	// }
// }
// function fread_c($handler, $length = 4096,
	// $ok_str = "", $fail_str = "PHP fread() failed!\n"){
	// $content = fread($handler, $length);
	// if(!$handler){
		// echo $fail_str;
		// return FALSE;
	// }else{
		// echo $ok_str;
		// return $content;
	// }
// }
// function fwrite_c($handler, $content, $length = -1,
	// $ok_str = "", $fail_str = "PHP fwrite() failed!\n"){
	// $result = FALSE;
	// if(-1 === $length){
		// $result = fwrite($handler, $content);
	// }else{
		// $result = fwrite($handler, $content, $length);
	// }
	
	// if(!$result){
		// echo $fail_str;
		// return FALSE;
	// }else{
		// echo $ok_str;
		// return $result;
	// }
// }

?>