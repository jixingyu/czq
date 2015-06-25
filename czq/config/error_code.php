<?php
$config['error_code'   = array(
    '204'   => array(
        'code'    => 204,
        'error'   => '未找到您要查看的内容',
    ),

    '400'   => array(
        'code'    => 204,
        'error'   => '参数错误',
    ),

    '401'   => array(
        'code'    => 401,
        'error'   => '没权限',
    ),
    '40101' => array(
        'code'    => 40101,
        'error'   => '没权限 - 未登录',
    ),
    '40102' => array(
        'code'    => 40102,
        'error'   => '登录出错 - 验证不通过',
    ),

    '90000' => array(
         'code'    => 90000,
         'error'   => '发生错误',
     ),

    '90001' => array(
         'code'    => 90001,
         'error'   => '%s',
     ),
);