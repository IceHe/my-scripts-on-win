<?php

/* tell a file with or without a suffix "$suffix" */
function is_suffix($suffix, $path){
	$pattern = '/.*\.' . $suffix . '$/';
	if(1 != preg_match($pattern, $path))	return FALSE;
	return TRUE;
}

/* 
 * [NOTICE] The standard dirname should not end in a dir seperator ( / or \ ) !
 *	But I declare the path vars ended in dir seperator for convenience~
 *  So I need a function to Remove the dir seperator at the end of the path vars.
 */
/* Remove the dir seperator at the end of the path vars */
function rm_suf_dirsep($path){
	if(empty($path))	return FALSE;
	if(1 != ($ret = preg_match('/(.*?)[\/|\\\\]*$/', $path, $matches)))	return FALSE;
	return $matches[1];
}

/* test a dir whether is empty */
function empty_dir($path){
	if(!is_dir($path))	return FALSE;
	if(!($dir = opendir($path)))	return FALSE;
	
	while($i = readdir($dir)){
		echo "$i\n";
		if('.' != $i && '..' != $i){
			if(is_file($path . '/' . $i)
				|| is_dir($path . '/' . $i)){
				return FALSE;
			}
		}
	}
	return TRUE;
}

/* PHP_CLI get input (a line or a formated param) from STDIN */
function input($format = ""){
	if("" === $format)	return str_replace("\r\n", "", fgets(STDIN));	// "\r\n" for win
	fscanf(STDIN, $format, $in); 
	return $in;
}

/* convert unix_like path seperator "/" to win path seperator "\" */
function win_path($path){
	return (IS_WIN ? str_replace('/', '\\', $path) : $path);
}
function WP($path){	//short func name of win_path()
	return win_path($path);
}

/* convert windows_like path seperator "\" to win path seperator "/" */
function unix_path($path){
	return str_replace('\\', '/', $path);
}
function UP($path){	//short func name of unix_path()
	return unix_path($path);
}

/* mb_convert_encoding($str) from UTF-8 to GB2312 */
function win_encode($str){
	return (IS_WIN ? mb_convert_encoding($str, "GB2312", "UTF-8") : $str);
}
function GB($str){
	return win_encode($str);
}

/* exec with echo result (win) */
function exec_c($command, $tips, &$ret_last_line, &$output){
	$ret_last_line = exec($command, $output, $ret_val);
	echo "{$tips} - " . (0 === $ret_val ? "suc." : "failed!") . "\n";
	return $ret_val;
}

/* get env vars (win) */
function get_env_var($variable_name){
	$variable = exec("echo %$variable_name%", $unused_output, $r_val);
	if(0 != $r_val || "%$variable_name%" === $variable){
		echo "PHP get path - \"%$variable_name%\" failed!\n";
		return FALSE;
	}
	return $variable;
}

/* convert str ary's encoding */
function convert_str_ary_encoding($str_ary, $to_encoding, $from_encoding = ""){
	if("" === $from_encoding){
		foreach($str_ary as $key => $value){
			if(!($str_ary[$key] = mb_convert_encoding($value, $to_encoding))) return FALSE;
		}
	}else{
		foreach($str_ary as $key => $value){
			if(!($str_ary[$key] = mb_convert_encoding($value, $to_encoding, $from_encoding))) return FALSE;
		}
	}
	return $str_ary;
}