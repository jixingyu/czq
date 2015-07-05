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








    public function favorite_list_get()
    {
        $this->load->model('favorite_model');
        $page = $this->get('page') ? $this->get('page') : 1;
        $uid = $this->_get_uid();

        $limit = $this->config->item('app_page_size');
        $offset = $limit * ($page-1);

        $count = $this->favorite_model->get_count(array('user_id' => $uid));
        if ($count) {
            $favorites = $this->favorite_model->get_list(array('user_id' => $uid), $limit, $offset, 'create_time');
        } else {
            $favorites = array();
        }

        return $this->response(array(
            'code'          => 1,
            'data'          => $favorites,
            'pagination'    => array(
                'page'      => intval($page),
                'pageSize'  => intval($limit),
                'total'     => $count,
            ),
        ), 200);
    }

    public function favorite_post()
    {
        $this->load->model('favorite_model');
        $job_id = intval($this->post('job_id'));
        $uid = $this->_get_uid();

        if (!$job_id) {
            $this->response(api_error(400), 200);
        }
        $where = array('user_id' => $uid, 'job_id' => $job_id);
        if ($this->favorite_model->get_count($where)) {
            $this->favorite_model->delete($where);
            $this->response(array(
                'code' => 1,
                'is_favorited' => 0,
            ), 200);
        } else {
            $where['create_time'] = time();
            $this->favorite_model->insert($where);
            $this->response(array(
                'code' => 1,
                'is_favorited' => 0,
            ), 200);
        }
    }
}