<?php

if(!defined('IN_SHANGTAO')) {
	exit('Access Denied');
}
define('CHARSET', 'utf-8');
define('DBCHARSET', 'utf8');
define('TABLEPRE', 'wst_');

$env_items = array
(
	'os' => array(''),
	'php' => array(''),
	'attachmentupload' => array(),
	'gdversion' => array(),
	'diskspace' => array(),
);
$dir_items = array
(
  'install' => array('path' => '/install'),
  'runtime' => array('path' => '/runtime'),
  'upload' => array('path' => '/upload'),
  'conf' => array('path' => '/config')
);
$func_items = array(
  'scandir'=>array(),
  'mysqli_connect'=>array(),
  'file_get_contents'=>array(),
  'curl_init'=>array(),
  'mb_strlen'=>array(),
  'finfo_open'=>array(),
  'bcmath'=>array()
);
?>