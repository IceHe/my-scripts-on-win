#!/usr/local/bin/php -q
<?php
$reqs = array(
	"%p1",
	"%p2",
	// "/a",
	// "/i=title",
	"/n=nb=notebook",
	// ".x=ext",
	// ".b",
	".v=of=overflow",
);

// $reg_exps = array(
	// "p" => "/^\%(\w+)$/",	//str param
	// "w" => "/^\/(\w+)(?:\=\w+)*$/",	//option with a param
	// "n" => "/^\.(\w+)(?:\=\w+)*$/",	//option with no param
	// "a" => "/(?<=\=)\w+\b/",	//get option's aliases
// );

// if(empty($reg_exps["x"])){
	// echo "EMPTY!!!!!!!!\n";
// }

// $str = "/tab$";
// $str = "/tab";

// $reg_exp = "/^\/(\w+)$/";
// echo "str=" . $str . "\n";
// echo "reg=" . $reg_exp . "\n";
// echo "match=" . preg_match($reg_exp, $str, $matches) . "\n";
// print_r($matches);
// echo "\n\n";

// $str = "fdfee/fdf";

// $reg_exp = "/^[^\/].*$/";
// echo "str=" . $str . "\n";
// echo "reg=" . $reg_exp . "\n";
// echo "match=" . preg_match($reg_exp, $str, $matches) . "\n";
// print_r($matches);
// echo "\n\n";

// $str = $reqs[3];

// $reg_exp = $reg_exps["a"];
// echo "str=" . $str . "\n";
// echo "reg=" . $reg_exp . "\n";
// echo "match=" . preg_match_all($reg_exp, $str, $matches) . "\n";
// print_r($matches);
// echo "\n\n";

// $str = $reqs[2];

// $reg_exp = "/(?<=\=)\w+\b/";
// echo "reg=" . $reg_exp . "\n";
// echo "match=" . preg_match_all($reg_exp, $str, $matches) . "\n";
// print_r($matches);

// echo "\n";

function proc_param($argv, $reqs){
	$a_cnt = count($argv);
	$r_cnt = count($reqs);
	if(0 === $r_cnt && $a_cnt > 1){
		echo "PHP get redundant params:";
		print_r($argv);
		return FALSE;
	}
	
	//BELOW for debug
	// shw_argv_reqs($argv, $reqs);
	//ABOVE for debug
	
	if(!($std_reqs = translate_reqs($reqs, $params))){
		echo "PHP reqs' format is incorrect!\n";
		return FALSE;
	}
	
	if(!get_params($argv, $std_reqs, $params)){
		echo "PHP argv's format is incorrect!\n";
		return FALSE;
	}else{
		return $params;
	}
}

function get_params($argv, $std_reqs, &$params){
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
			if(NULL === $params[$mat[1]]){
				echo "Option \"$mat[1]\" doesn't exist in this command!\n";
				$is_ok = FALSE;
				break;
			}
			
			elseif($std_reqs[$params[$mat[1]]] === FALSE){
				$std_reqs[$params[$mat[1]]] = TRUE;
				continue;
			}elseif($std_reqs[$params[$mat[1]]] === TRUE){
				echo "Option \"$mat[1]\" or synonymous option appeared repeatedly!\n";
				$is_ok = FALSE;
				break;
			}
			
			elseif($std_reqs[$params[$mat[1]]] === NULL){
				if("" === $prev_option){
					$prev_option = $mat[1];
					continue;
				}else{
					echo "Option \"$mat[1]\" must have a param!\n";
					$is_ok = FALSE;
					break;
				}
			}elseif($std_reqs[$params[$mat[1]]] !== NULL){
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
				if(NULL !== $params["%$str_param_cnt"]){
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

	foreach($params as $key => $val){
		$params[$key] = $std_reqs[$val];
	}
	
	//BELOW for debug
	echo "std_reqs=";
	var_dump($std_reqs);
	echo "params=";
	var_dump($params);
	//ABOVE for debug
	
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

	//BELOW for debug
	// echo "params:";
	// var_dump($params);
	// echo "std_reqs:";
	// var_dump($std_reqs);
	//ABOVE for debug
	
	return ($is_ok ? $std_reqs : FALSE);
}

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

proc_param($argv, $reqs);

?>