#!/usr/local/bin/php -q
<?php

$param[0] = "";
$param[1] = FALSE;
$param[2] = NULL;
//$param[3] does not exist!

echo "param=";
var_dump($param);
echo "Remember that param[3] does not exist!\n";
echo "\n";
echo "\n";



echo "empty 和 isset 的区别\n";
echo "empty(\"\")=";
var_dump(empty($param[0]));
echo "empty(FALSE)=";
var_dump(empty($param[1]));
echo "empty(NULL)=";
var_dump(empty($param[2]));
echo "empty(undefined)=";
var_dump(empty($param[3]));
echo "\n";

echo "isset(\"\")=";
var_dump(isset($param[0]));
echo "isset(FALSE)=";
var_dump(isset($param[1]));
echo "isset(NULL)=";
var_dump(isset($param[2]));
echo "isset(undefined)=";
var_dump(isset($param[3]));
echo "\n";
echo "\n";



echo "调用不存在的参数发生的情况：\n";
echo "(undefined param 'tmp')=";
var_dump($tmp);
echo "(param[3])=";
var_dump($param[3]);
echo "以上运行结果，带有PHP的错误报告，是对不存在参数的提醒。\n";
echo "调用不存在的参数实际得到是NULL\n";
echo "\n";

echo "接下来关闭错误报告对于提醒Notice报告的显示。\n";
error_reporting(E_ALL & ~E_NOTICE);
echo "\n";
echo "\n";



echo "空字符串\"\"，布尔否FALSE，空NULL 之间的对比\n";
echo "全等 ===\n";
echo "\"\"===FALSE :";
var_dump(""===FALSE);
echo "\"\"===NULL :";
var_dump(""===NULL);
echo "FALSE===NULL :";
var_dump(FALSE===NULL);
echo "\n";

echo "等于 ==\n";
echo "\"\"==FALSE :";
var_dump(""==FALSE);
echo "\"\"==NULL :";
var_dump(""==NULL);
echo "FALSE==NULL :";
var_dump(FALSE==NULL);
echo "\n";

echo "不全等 !==\n";
echo "\"\"!==FALSE :";
var_dump(""!==FALSE);
echo "\"\"!==NULL :";
var_dump(""!==NULL);
echo "FALSE!==NULL :";
var_dump(FALSE!==NULL);
echo "\n";

echo "不等于 !=\n";
echo "\"\"!=FALSE :";
var_dump(""!=FALSE);
echo "\"\"!=NULL :";
var_dump(""!=NULL);
echo "FALSE!=NULL :";
var_dump(FALSE!=NULL);
echo "\n";
echo "\n";



echo "!== 含义的探索\n";
echo "TRUE!==FALSE :";
var_dump(TRUE!==FALSE);
echo "\"abc\"!==\"xyz\" :";
var_dump("abc"!=="xyz");
echo "由以上结果可知!==含义为\"不全等\"，\n而非\"全不等！\"\n";
echo "\n";

echo "string!==BOOLEAN :\n";
echo "\"abc\"!==TRUE :";
var_dump("abc"!==TRUE);
echo "\"xyz\"!==FALSE :";
var_dump("xyz"!==FALSE);
echo "\n";

echo "others!==NULL :\n";
echo "\"abc\"!==NULL :";
var_dump("abc"!==NULL);
echo "FALSE!==NULL :";
var_dump(FALSE!==NULL);
echo "\n";
echo "\n";



echo "不存在的参数 与 \"\"、FALSE、NULL 对比\n";
echo "全等 ===\n";
echo "(undefined)===\"\" :";
var_dump($param[3]==="");
echo "(undefined)===FALSE :";
var_dump($param[3]===FALSE);
echo "(undefined)===NULL :";
var_dump($param[3]===NULL);
echo "\n";

echo "等于 ==\n";
echo "(undefined)==\"\" :";
var_dump($param[3]=="");
echo "(undefined)==FALSE :";
var_dump($param[3]==FALSE);
echo "(undefined)==NULL :";
var_dump($param[3]==NULL);
echo "\n";

echo "不全等 !==\n";
echo "(undefined)!==\"\" :";
var_dump($param[3]!=="");
echo "(undefined)!==FALSE :";
var_dump($param[3]!==FALSE);
echo "(undefined)!==NULL :";
var_dump($param[3]!==NULL);
echo "\n";

echo "不等于 !=\n";
echo "(undefined)!=\"\" :";
var_dump($param[3]!="");
echo "(undefined)!=FALSE :";
var_dump($param[3]!=FALSE);
echo "(undefined)!=NULL :";
var_dump($param[3]!=NULL);
echo "\n";
echo "\n";



// system("pause");

?>