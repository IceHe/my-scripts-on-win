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



echo "empty �� isset ������\n";
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



echo "���ò����ڵĲ��������������\n";
echo "(undefined param 'tmp')=";
var_dump($tmp);
echo "(param[3])=";
var_dump($param[3]);
echo "�������н��������PHP�Ĵ��󱨸棬�ǶԲ����ڲ��������ѡ�\n";
echo "���ò����ڵĲ���ʵ�ʵõ���NULL\n";
echo "\n";

echo "�������رմ��󱨸��������Notice�������ʾ��\n";
error_reporting(E_ALL & ~E_NOTICE);
echo "\n";
echo "\n";



echo "���ַ���\"\"��������FALSE����NULL ֮��ĶԱ�\n";
echo "ȫ�� ===\n";
echo "\"\"===FALSE :";
var_dump(""===FALSE);
echo "\"\"===NULL :";
var_dump(""===NULL);
echo "FALSE===NULL :";
var_dump(FALSE===NULL);
echo "\n";

echo "���� ==\n";
echo "\"\"==FALSE :";
var_dump(""==FALSE);
echo "\"\"==NULL :";
var_dump(""==NULL);
echo "FALSE==NULL :";
var_dump(FALSE==NULL);
echo "\n";

echo "��ȫ�� !==\n";
echo "\"\"!==FALSE :";
var_dump(""!==FALSE);
echo "\"\"!==NULL :";
var_dump(""!==NULL);
echo "FALSE!==NULL :";
var_dump(FALSE!==NULL);
echo "\n";

echo "������ !=\n";
echo "\"\"!=FALSE :";
var_dump(""!=FALSE);
echo "\"\"!=NULL :";
var_dump(""!=NULL);
echo "FALSE!=NULL :";
var_dump(FALSE!=NULL);
echo "\n";
echo "\n";



echo "!== �����̽��\n";
echo "TRUE!==FALSE :";
var_dump(TRUE!==FALSE);
echo "\"abc\"!==\"xyz\" :";
var_dump("abc"!=="xyz");
echo "�����Ͻ����֪!==����Ϊ\"��ȫ��\"��\n����\"ȫ���ȣ�\"\n";
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



echo "�����ڵĲ��� �� \"\"��FALSE��NULL �Ա�\n";
echo "ȫ�� ===\n";
echo "(undefined)===\"\" :";
var_dump($param[3]==="");
echo "(undefined)===FALSE :";
var_dump($param[3]===FALSE);
echo "(undefined)===NULL :";
var_dump($param[3]===NULL);
echo "\n";

echo "���� ==\n";
echo "(undefined)==\"\" :";
var_dump($param[3]=="");
echo "(undefined)==FALSE :";
var_dump($param[3]==FALSE);
echo "(undefined)==NULL :";
var_dump($param[3]==NULL);
echo "\n";

echo "��ȫ�� !==\n";
echo "(undefined)!==\"\" :";
var_dump($param[3]!=="");
echo "(undefined)!==FALSE :";
var_dump($param[3]!==FALSE);
echo "(undefined)!==NULL :";
var_dump($param[3]!==NULL);
echo "\n";

echo "������ !=\n";
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