<?php

/*
 * 功能：从 $argv 中获取 .php 运行所需的参数
 * 额外说明：我调用自己写的需要传参的脚本[script_name].php时，
 *			用此格式：php [script_name].php [options & params]
 *				  例：php t.php haha "has nbsp" /a app /title name /v /overflow
 * 参数：$argv —— 调用.php时得到的参数
 *		$is_debug —— 设置是否显示 得到的参数、对参数的要求、最后得到的格式化参数数组
 *		$tips —— 脚本所需参数的提示文字。当输入的参数是 /? 时，输出它，内容如下文中的
 *				内容类同于下文的几行之后的FINDSTR。
 *		$reqs —— 对参数的要求。它是一个以默认数字为key、字符串为value的数组。
 *			其中value的字符串，形如"%param_name"、"/option_name"、".option_name"。
 *			以上字符串第一个字符：% 代表不带描述名的参数；/ 代表带参数的option；
 *							. 代表不带参数的option
 *			如下例，/ 类同其中的 [/G:file]，% 类同 [/M] [/O] [/P]，%
 *					. 类同 strings [[drive:][path]filename[ ...]]。
 FINDSTR [/B] [/E] [/L] [/R] [/S] [/I] [/X] [/V] [/N] [/M] [/O] [/P] [/F:file]
        [/C:string] [/G:file] [/D:dir list] [/A:color attributes] [/OFF[LINE]]
        strings [[drive:][path]filename[ ...]]
 * 		不理解可参见样例："[C:\Users\IceHe]\script\past_archives\test_proc_param.php"
 */

function proc_param($argv, $reqs, $tips = "", $is_debug = FALSE){
	if(isset($argv[1]) && "/?" === $argv[1] && "" != $tips){
		echo "$tips\n";
		return FALSE;
	}
	
	$a_cnt = count($argv);
	$r_cnt = count($reqs);
	if(0 === $r_cnt && $a_cnt > 1){
		echo "PHP get redundant params:";
		print_r($argv);
		return FALSE;
	}
	
	/* BELOW for debug */
	if($is_debug) shw_argv_reqs($argv, $reqs);
	/* ABOVE for debug */
	
	if(!($std_reqs = translate_reqs($reqs, $params))){
		echo "PHP reqs' format is incorrect!\n";
		return FALSE;
	}
	
	if(!get_params($argv, $std_reqs, $params)){
		echo "PHP argv's format is incorrect!\n";
		if($is_debug)	shw_result($std_reqs, $params);
		return FALSE;
	}else{
		if($is_debug)	shw_result($std_reqs, $params);
		return $params;
	}
}

function get_params($argv, &$std_reqs, &$params){
	$reg_option = "/^\/(\w+)$/";
	$reg_param = "/^[^\/].*$/";
	
	$is_ok = TRUE;
	$str_param_cnt = 0;
	$prev_option = "";
	
	$a_cnt = count($argv);
	for($i = 1; $i < $a_cnt; ++$i){
		if(FALSE === ($ret = preg_match($reg_option, $argv[$i], $mat))){
			$is_ok = FALSE;
			break;
		}elseif($ret > 0){
			if("" != $prev_option){
				echo "Option \"$prev_option\" must have a param!\n";
				$is_ok = FALSE;
				break;
			}
			
			if(!isset($params[$mat[1]])){
				echo "Option \"$mat[1]\" doesn't exist in this command!\n";
				$is_ok = FALSE;
				break;
			}
			
			elseif(FALSE === $std_reqs[$params[$mat[1]]]){
				$std_reqs[$params[$mat[1]]] = TRUE;
				continue;
			}elseif(TRUE === $std_reqs[$params[$mat[1]]]){
				echo "Option \"$mat[1]\" or synonymous option appeared repeatedly!\n";
				$is_ok = FALSE;
				break;
			}
			
			elseif(NULL === $std_reqs[$params[$mat[1]]]){
				$prev_option = $mat[1];
				continue;
			}elseif(NULL !== $std_reqs[$params[$mat[1]]]){
				echo "Option \"$mat[1]\" or synonymous option appears repeatedly!\n";
				$is_ok = FALSE;
				break;
			}
			
			else{
				echo "Unknown option appears!";
				// echo "Unknown type param appears!\n";
				$is_ok = FALSE;
				break;
			}
			
		}elseif(FALSE === ($ret = preg_match($reg_param, $argv[$i], $mat))){
			$is_ok = FALSE;
			break;
		}elseif($ret > 0){
			if("" === $prev_option){
				if(isset($params["%$str_param_cnt"])){
					$std_reqs[$params["%$str_param_cnt"]] = $mat[0];
					$str_param_cnt++;
				}else{
					echo "Unkown anonymous param \"$$str_param_cnt\" appears!\n";
					$is_ok = FALSE;
					break;
				}
			}else{
				$std_reqs[$params[$prev_option]] = $mat[0];
				$prev_option = "";
			}
		}
	}
	
	/* 暂时无用 */
	// foreach($std_reqs as $key => $val){
		// echo "[" . $std_reqs[$key] . "]" . mb_detect_encoding($val) . "\n";
		// $std_reqs[$key] = mb_convert_encoding($val, "UTF-8");
		// echo "[" . $std_reqs[$key] . "]" . mb_detect_encoding($val) . "\n";
	// }

	foreach($params as $key => $val){
		$params[$key] = $std_reqs[$val];
	}
	
	/* BELOW for debug */
	// echo "std_reqs=";
	// var_dump($std_reqs);
	// echo "params=";
	// var_dump($params);
	/* ABOVE for debug */
	
	return $is_ok;
}

function translate_reqs($reqs, &$params){
	$reg_exps = array(
		"p" => "/^\%(\w+)$/",	//str param
		"w" => "/^\/(\w+)(?:\=\w+)*$/",	//option with a param
		"n" => "/^\.(\w+)(?:\=\w+)*$/",	//option with no param
		// "a" => "/(?<=\=)\w+\b/",	//get option's aliases
	);
	$reg_get_alias = "/(?<=\=)\w+\b/";	//get option's aliases
	
	$std_reqs = array();
	$is_ok = TRUE;
	$str_param_cnt = 0;
	$std_reqs_key = 0;
	foreach($reqs as $key => $req){
		$is_matched = FALSE;
		foreach($reg_exps as $type => $exp){
			// if("a" != $type)	continue;
			
			if(0 != preg_match($exp, $req, $mat)){
				$param_fisrt_name = $mat[1];
				
				switch($type){
					case "p":
					$std_reqs["$std_reqs_key"] = NULL;
					$params[$param_fisrt_name] = "$std_reqs_key";
					$params["%$str_param_cnt"] = "$std_reqs_key";
					++$str_param_cnt;
					++$std_reqs_key;
					break;
					
					case "w":
					$std_reqs["$std_reqs_key"] = NULL;
					$params[$param_fisrt_name] = "$std_reqs_key";
					$res = preg_match_all($reg_get_alias, $req, $aliases);
					if(FALSE === $res){
						$is_ok = FALSE;
						break;
					}elseif(0 != $res){
						foreach($aliases[0] as $k => $alias){
							$params[$alias] = "$std_reqs_key";
						}
					}
					++$std_reqs_key;
					break;
					
					case "n":
					$std_reqs["$std_reqs_key"] = FALSE;
					$params[$param_fisrt_name] = "$std_reqs_key";
					$res = preg_match_all($reg_get_alias, $req, $aliases);
					if(FALSE === $res){
						$is_ok = FALSE;
						break;
					}elseif(0 != $res){
						foreach($aliases[0] as $k => $alias){
							$params[$alias] = "$std_reqs_key";
						}
					}
					++$std_reqs_key;
					break;
					
					default:
					break;
				}
				$is_matched = TRUE;
				break;
			}
		}
		
		if($is_ok && !$is_matched){
			$is_ok = FALSE;
			break;
		}
	}

	/* BELOW for debug */
	// echo "params:";
	// var_dump($params);
	// echo "std_reqs:";
	// var_dump($std_reqs);
	/* ABOVE for debug */
	
	return ($is_ok ? $std_reqs : FALSE);
}

/* for debug */
function shw_argv_reqs($argv, $reqs){
	echo "argv:\n";
	foreach($argv as $key => $val){
		echo "$key=$val\n";
	}
	echo "\n";
	
	echo "reqs:\n";
	foreach($reqs as $key => $val){
		echo "$key=$val\n";
	}
	echo "\n";
}

/* for debug */
function shw_result($std_reqs, $params){
	echo "std_reqs=";
	var_dump($std_reqs);
	echo "params=";
	var_dump($params);
}

?>