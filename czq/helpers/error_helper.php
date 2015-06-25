<?php
function api_error($error_code, $content = '')
{
    $CI =& get_instance();
    $CI->config->load('error_code');
    $CI->lang->load('error');
    $error = $CI->config->item($error_code, 'error_code');
    $error['error'] = $CI->lang->line($error['error']);
    if ($content) {
        $error['error'] = sprintf($error['error'], $content);
    }
    return $error;
}