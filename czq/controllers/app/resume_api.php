<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Resume_api extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('app_user_lib');
        $this->load->model('resume_model');
    }

    public function resume_list_get()
    {
        $page = $this->get('page') ? $this->get('page') : 1;
        $uid = $this->_get_uid();

        $limit = $this->config->item('app_page_size');
        $offset = $limit * ($page-1);

        $count = $this->resume_model->get_count(array('user_id' => $uid));
        if ($count) {
            $resumes = $this->resume_model->get_list(array('user_id' => $uid), $limit, $offset, 'create_time');
        } else {
            $resumes = array();
        }

        return $this->response(array(
            'code'          => 1,
            'data'          => $resumes,
            'pagination'    => array(
                'page'      => intval($page),
                'pageSize'  => intval($limit),
                'total'     => $count,
            ),
        ), 200);
    }

    public function resume_get()
    {
        $resume_id = intval($this->get('resume_id'));
        $uid = $this->_get_uid();

        if (!$resume_id) {
            $this->response(api_error(400), 200);
        }

        $resume = $this->resume_model->get_one(array('user_id' => $uid, 'resume_id' => $resume_id));
        return $this->response(array(
            'code' => 1,
            'data' => $resume,
        ), 200);
    }
}