<?php
class MY_Pagination extends CI_Pagination
{
    public $CI;

    public function __construct()
    {
        $this->CI = & get_instance();
        parent::__construct();
    }

    public function initialize_admin($option)
    {
        $config['uri_segment']     = 4;

        $config['full_tag_open']    = '<div>'
                                    . '<ul class="pagination">';
        $config['full_tag_close']   = '</ul></div>';

        $config['first_link']       = '首页';
        $config['first_tag_open']   = '<li>';
        $config['first_tag_close']  = '</li>';
        $config['last_link']        = '尾页';
        $config['last_tag_open']    = '<li>';
        $config['last_tag_close']   = '</li>';

        $config['next_link']        = '&gt;';
        $config['next_tag_open']    = '<li>';
        $config['next_tag_close']   = '</li>';

        $config['prev_link']        = '&lt;';
        $config['prev_tag_open']    = '<li>';
        $config['prev_tag_close']   = '</li>';

        $config['cur_tag_open']     = '<li class="active"><a href="#">';
        $config['cur_tag_close']    = '</a></li>';

        $config['num_tag_open']     = '<li>';
        $config['num_tag_close']    = '</li>';

        parent::initialize(array_merge($config, $option));
    }
}
