<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function appconfig_get()
    {
        $this->config->load('job', TRUE);
        $response = array(
            'degree' => $this->config->item('degree', 'job'),
            'salary' => $this->config->item('salary', 'job'),
            'district' => $this->config->item('district', 'job'),
            'working_years' => $this->config->item('working_years', 'job'),
        );
        return $this->response(array(
            'code' => 1,
            'data' => $response
        ), 200);
    }

    public function about_get()
    {
        $this->load->model('app_config_model');
        $result = $this->app_config_model->get_list(array('module' => 'about'));
        $about = array();
        foreach ($result as $value) {
            $k = $value['cf_key'];
            $about[$k] = $value['cf_value'];
        }

        return $this->response(array(
            'code' => 1,
            'data' => $about,
        ), 200);
    }

    public function job_list_get()
    {
        $this->load->model('job_model');
        $q         = $this->get('q');
        $district  = $this->get('district');
        $page = $this->get('page');
        $page = $page ? $page : 1;
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
            $uid = $this->_get_uid(false);
            if ($uid) {
                $job_ids = array();
                foreach ($jobs as $value) {
                    $job_ids[] = $value['id'];
                }
                $this->load->model('favorite_model');
                $favorites = $this->favorite_model->get_list_in($job_ids, 'job_id', array('user_id' => $uid), 'job_id');
                if (!empty($favorites)) {
                    foreach ($jobs as $k => $value) {
                        $job_id = $value['id'];
                        if (isset($favorites[$job_id])) {
                            $jobs[$k]['is_favorite'] = 1;
                        }
                    }
                }
            }
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

    public function job_get()
    {
        $this->load->model('job_model');
        $id = $this->get('job_id');

        return $this->response(array(
            'code' => 1,
            'data' => $this->job_model->job_detail($id),
        ), 200);
    }

    public function favorite_list_get()
    {
        $this->load->model('favorite_model');
        $page = $this->get('page');
        $page = $page ? $page : 1;
        $uid = $this->_get_uid();

        $limit = $this->config->item('app_page_size');
        $offset = $limit * ($page-1);

        $count = $this->favorite_model->get_count(array('user_id' => $uid));
        if ($count) {
            $favorites = $this->favorite_model->favorite_list(array('user_id' => $uid), $limit, $offset);
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
                'is_favorite' => 0,
            ), 200);
        } else {
            $where['create_time'] = time();
            $this->favorite_model->insert($where);
            $this->response(array(
                'code' => 1,
                'is_favorite' => 0,
            ), 200);
        }
    }

    public function apply_list_get()
    {
        $this->load->model('apply_model');
        $page = $this->get('page');
        $page = $page ? $page : 1;
        $uid = $this->_get_uid();

        $limit = $this->config->item('app_page_size');
        $offset = $limit * ($page-1);

        $count = $this->apply_model->get_count(array('user_id' => $uid));
        if ($count) {
            $applys = $this->apply_model->apply_list(array('user_id' => $uid), $limit, $offset);
        } else {
            $applys = array();
        }

        return $this->response(array(
            'code'          => 1,
            'data'          => $applys,
            'pagination'    => array(
                'page'      => intval($page),
                'pageSize'  => intval($limit),
                'total'     => $count,
            ),
        ), 200);
    }

    public function apply_post()
    {
        $this->load->model(array('job_model', 'apply_model', 'resume_model'));
        $job_id = intval($this->post('job_id'));
        $resume_id = intval($this->post('resume_id'));
        $uid = $this->_get_uid();
        $job_exist = $this->job_model->get_count(array('id' => $job_id, 'is_deleted' => 0));
        if (empty($job_exist)) {
            $this->response(api_error(90001, '您申请的职位不存在！'), 200);
        }
        $resume_exist = $this->resume_model->get_count(array('id' => $resume_id, 'user_id' => $uid));
        if (empty($resume_exist)) {
            $this->response(api_error(90001, '请选择您要投递的简历！'), 200);
        }

        $apply = $this->apply_model->get_list(array(
            'user_id' => $uid,
            'job_id' => $job_id,
            'resume_id' => $resume_id,
        ), 1, 0, 'create_time');
        $interview_expire = false;
        if (!empty($apply) && $apply['status'] == 1) {
            $this->load->model('interview_model');
            $interview = $this->interview_model->find($apply['id'], 'apply_id');
            if ($interview['interview_time'] < time()) {
                $interview_expire = true;
            }
        }

        if (empty($apply) || $interview_expire) {
            $current_time = time();
            $this->apply_model->insert(array(
                'user_id' => $uid,
                'job_id' => $job_id,
                'resume_id' => $resume_id,
                'create_time' => $current_time,
                'update_time' => $current_time,
            ));
        } elseif ($apply[0]['status'] == 0) {
            $this->response(api_error(90001, '您已申请该职位，请等待面试通知'), 200);
        } else {
            $this->response(api_error(90001, '您已申请该职位'), 200);
        }
        $this->response(array('code' => 1), 200);
    }

    public function interview_list_get()
    {
        $this->load->model('interview_model');
        $page = $this->get('page');
        $page = $page ? $page : 1;
        $uid = $this->_get_uid();

        $limit = $this->config->item('app_page_size');
        $offset = $limit * ($page-1);

        $count = $this->interview_model->get_count(array('user_id' => $uid));
        if ($count) {
            $interviews = $this->interview_model->interview_list(array('user_id' => $uid), $limit, $offset);
        } else {
            $interviews = array();
        }

        return $this->response(array(
            'code'          => 1,
            'data'          => $interviews,
            'pagination'    => array(
                'page'      => intval($page),
                'pageSize'  => intval($limit),
                'total'     => $count,
            ),
        ), 200);
    }
}