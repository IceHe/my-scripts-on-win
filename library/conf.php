<?php

/* 
 * [NOTICE] The standard dirname should not end in a dir seperator ( / or \ ) !
 *	But I declare the path variables ended in dir seperator for convenience~
 */

/* Root & Pri Dir */
define('HOME', 'C:/ice_php_envir/');
define('TMPO', HOME . 'tmpo/');
define('LOGS', HOME . 'logs/');
define('BLOG', HOME . 'blog/');
define('SCRIPTS', HOME . 'scripts/');

/* Scripts */
define('TPLS', SCRIPTS . 'templates/');
define('TOOLS', SCRIPTS . 'tools/');
define('LIBRARY', SCRIPTS . 'library/');
define('LIB_TEST', SCRIPTS . 'test_lib/');
define('MAINTAIN', SCRIPTS . 'maintain/');
define('TIMED_TASK', SCRIPTS . 'timed_tasks/');

/* Enex */
define('ENEX', TOOLS . 'enex/');

/* Blog */
define('BLOG_HEXO', BLOG . 'icehe.blog.hexo/');
define('BLOG_IMG', BLOG . 'ice-blog-img/');
define('BLOG_ATT', BLOG . 'ice-blog-att/');
define('QINIU', BLOG . 'qiniu_tools/');

/* Backup */
define('BACKUP', 'd:/scripts_backup/');
define('BLOG_BACKUP', 'd:/blog_backup/');

/* Others */
define('DESTOP', 'C:/Users/IceHe/Desktop/');
define('IS_WIN', TRUE);	// Influence the path's seperator and the encoding conversion

/* Debug */
if(!defined('IS_DEBUG')){
	define('IS_DEBUG', TRUE);
}

/* Cusutom funcs */
require(LIBRARY . 'custom_funcs.php');

/* Debug Output */
if(IS_DEBUG){
	echo "\n";
	echo "PHP [ " . strtoupper(__file__) . " ]\n";
	echo "PHP GLOBAL VARs:\n";
	echo "PHP HOME = [" . HOME . "]\n";
	echo "PHP SCRIPTS = [" . SCRIPTS . "]\n";
	echo "PHP TMPO = [" . TMPO . "]\n";
	echo "PHP LOGS = [" . LOGS . "]\n";
	echo "PHP DESTOP = [" . DESTOP . "]\n";

	echo "PHP LIBRARY = [" . LIBRARY . "]\n";
	echo "PHP LIB_TEST = [" . LIB_TEST . "]\n";
	echo "PHP IS_DEBUG = [" . IS_DEBUG . "]\n";
}