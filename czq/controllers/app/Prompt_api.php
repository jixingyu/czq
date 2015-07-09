<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Prompt_api extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function usercenter_get()
    {
        $user_id = $this->_get_uid();
        $this->load->model(array('resume_model', 'apply_model', 'favorite_model'));
        return $this->response(array(
            'code' => 1,
            'data' => array(
                'resume_num' => $this->resume_model->get_count(array('user_id' => $user_id)),
                'apply_num' => $this->apply_model->get_count(array('user_id' => $user_id)),
                'favorite_num' => $this->favorite_model->get_count(array('user_id' => $user_id)),
            )
        ), 200);
    }
}