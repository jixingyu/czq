<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('app_user_lib');
    }

    public function job_list_get()
    {
        $this->load->model('job_model');
        $q         = $this->get('q');
        $district  = $this->get('district');
        $page = $this->get('page') ? $this->get('page') : 1;
        $condition = array();
        if (!empty($q)) {
        	$condition['q'] = $q;
        }
        if (!empty($district)) {
        	$condition['district'] = $district;
        }

        $limit = $this->config->item('app_page_size');
        $offset = $limit * ($page-1);

        $count = $this->job_model->search_count($condition);
        if ($count) {
            $jobs = $this->job_model->search_jobs($condition, $limit, $offset);
        } else {
            $jobs = array();
        }

        return $this->response(array(
            'code'          => 1,
            'data'          => $jobs,
            'pagination'    => array(
                'page'      => intval($page),
                'pageSize'  => intval($limit),
                'total'     => $count,
            ),
        ), 200);
    }
}