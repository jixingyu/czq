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
            'resume_limit'  => $this->config->item('resume_limit'),
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
        if (!empty($district) && $district != '全城') {
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
                foreach ($jobs as $k => $value) {
                    $job_ids[] = $value['job_id'];
                    $jobs[$k]['is_favorite'] = 0;
                }
                $this->load->model('favorite_model');
                $favorites = $this->favorite_model->get_list_in($job_ids, 'job_id', array('user_id' => $uid), 'job_id');
                if (!empty($favorites)) {
                    foreach ($jobs as $k => $value) {
                        $job_id = $value['job_id'];
                        if (isset($favorites[$job_id])) {
                            $jobs[$k]['is_favorite'] = 1;
                        }
                    }
                }
            } else {
                foreach ($jobs as $k => $value) {
                    $jobs[$k]['is_favorite'] = 0;
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
        $uid = $this->_get_uid(false);
        $job = $this->job_model->job_detail($id);
        if (empty($job)) {
            $this->response(api_error(400), 200);
        }
        $job['is_favorite'] = $job['applied'] = 0;
        if ($uid) {
            $this->load->model(array('favorite_model', 'apply_model'));
            $exist = $this->favorite_model->get_count(array('user_id' => $uid, 'job_id' => $id));
            if ($exist) {
                $job['is_favorite'] = 1;
            }

            if (!$this->_check_apply($uid, $id)) {
                $job['applied'] = 1;
            }
        }

        return $this->response(array(
            'code' => 1,
            'data' => $job,
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

            $job_ids = array();
            foreach ($favorites as $k => $value) {
                $favorites[$k]['is_favorite'] = 1;
            }
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
                'is_favorite' => 1,
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

            $job_ids = array();
            foreach ($applys as $k => $value) {
                $job_ids[] = $value['job_id'];
                $applys[$k]['is_favorite'] = 0;
            }
            $this->load->model('favorite_model');
            $favorites = $this->favorite_model->get_list_in($job_ids, 'job_id', array('user_id' => $uid), 'job_id');
            if (!empty($favorites)) {
                foreach ($applys as $k => $value) {
                    $job_id = $value['job_id'];
                    if (isset($favorites[$job_id])) {
                        $applys[$k]['is_favorite'] = 1;
                    }
                }
            }

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
        $resume_exist = $this->resume_model->get_count(array('id' => $resume_id, 'user_id' => $uid, 'is_deleted' => 0));
        if (empty($resume_exist)) {
            $this->response(api_error(90001, '请选择您要投递的简历！'), 200);
        }

        if ($this->_check_apply($uid, $job_id)) {
            $current_time = time();
            $this->apply_model->insert(array(
                'user_id' => $uid,
                'job_id' => $job_id,
                'resume_id' => $resume_id,
                'create_time' => $current_time,
                'update_time' => $current_time,
            ));
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

        $count = $this->interview_model->interview_count(array('user_id' => $uid));
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

    public function _check_apply($uid, $job_id)
    {
        $apply = $this->apply_model->check_apply(array(
            'user_id' => $uid,
            'job_id' => $job_id,
        ));
        if (!empty($apply) && ($apply['create_time'] > time() - 86400 * $this->config->item('apply_days'))) {// 指定天数之内申请过
            return false;
        } else {
            return true;
        }
    }
}