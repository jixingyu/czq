<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('Asia/ShangHai');
$config['size_limit'] = intval(ini_get('upload_max_filesize')) * 1024;
$config['page_size'] = 10;
$config['app_page_size'] = 5;

$config['resume_limit'] = 3;
$config['apply_days'] = 1;

$config['reg_active'] = 0;
