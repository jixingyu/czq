<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Resume_api extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('resume_model');
    }

    public function resume_list_get()
    {
        $page = $this->get('page');
        $page = $page ? $page : 1;
        $uid = $this->_get_uid();

        $limit = $this->config->item('app_page_size');
        $offset = $limit * ($page-1);

        $count = $this->resume_model->get_count(array('user_id' => $uid));
        if ($count) {
            $resumes = $this->resume_model->resume_list(array('user_id' => $uid), $limit, $offset);
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

    public function resume_post()
    {
        $uid = $this->_get_uid();
        $resume_id = intval($this->post('resume_id'));
        if (!$resume_id) {
            $count = $this->resume_model->get_count(array('user_id' => $uid));
            $limit = $this->config->item('resume_limit');
            if ($count >= $limit) {
                $this->response(api_error(90001, '最多可保存' . $limit . '份简历！'), 200);
            }
        }

        $set = array('personal_info_completed' => 1);
        $set['real_name'] = trim($this->post('real_name'));
        if (empty($set['real_name'])) {
            $this->response(api_error(90001, '请填写您的姓名！'), 200);
        }

        $set['gender'] = $this->post('gender');
        $set['gender'] = $gender ? 1 : 0;

        $birthday = intval($this->post('birthday'));
        if ($birthday) {
            $set['birthday'] = $birthday;
        } else {
            $set['personal_info_completed'] = 0;
        }

        $native_place = trim($this->post('native_place'));
        if ($native_place) {
            $set['native_place'] = $native_place;
        } else {
            $set['personal_info_completed'] = 0;
        }

        $political_status = trim($this->post('political_status'));
        if ($political_status) {
            $set['political_status'] = $political_status;
        } else {
            $set['personal_info_completed'] = 0;
        }

        $working_years = intval($this->post('working_years'));
        if ($working_years) {
            $set['working_years'] = $working_years;
        } else {
            $set['personal_info_completed'] = 0;
        }

        $mobile = trim($this->post('mobile'));
        if (!@preg_match('/^1[3-9][0-9]{9}$/', $mobile)) {
            $this->response(array(
                'error' => api_error(90001, '请填写正确的手机号！'),
            ), 200);
        } else {
            $set['mobile'] = $mobile;
        }

        $email = trim($this->post('email'));
        if ($email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->response(array(
                    'error' => api_error(90001, '请填写正确的邮箱地址！'),
                ), 200);
            } else {
                $set['email'] = $email;
            }
        } else {
            $set['personal_info_completed'] = 0;
        }

        $school = trim($this->post('school'));
        if ($school) {
            $set['school'] = $school;
        } else {
            $set['personal_info_completed'] = 0;
        }

        $major = trim($this->post('major'));
        if ($major) {
            $set['major'] = $major;
        } else {
            $set['personal_info_completed'] = 0;
        }

        if ($resume_id) {
            $set['update_time'] = time();
            $this->resume_model->update($set, array(
                'id' => $resume_id,
                'user_id' => $user_id
            ));
            $this->response(array('code' => 1), 200);
        } else {
            $set['create_time'] = $set['update_time'] = time();
            $set['user_id'] = $user_id;
            $resume_id = $this->resume_model->insert($set);
            $this->response(array('code' => 1, 'data' => $resume_id), 200);
        }
    }

    public function experience_list_get()
    {
        $this->load->model('work_experience_model');
        $page = $this->get('page');
        $page = $page ? $page : 1;
        $uid = $this->_get_uid();
        $resume_id = intval($this->get('resume_id'));
        if (!$resume_id) {
            $this->response(api_error(400), 200);
        }
        $exist = $this->resume_model->get_count(array('id' => $resume_id, 'user_id' => $uid));
        if (empty($exist)) {
            $this->response(api_error(90001, '该简历不存在！'), 200);
        }

        $limit = $this->config->item('app_page_size');
        $offset = $limit * ($page-1);

        $count = $this->work_experience_model->get_count(array('resume_id' => $resume_id));
        if ($count) {
            $work_experiences = $this->work_experience_model->get_list(array('resume_id' => $resume_id), $limit, $offset);
        } else {
            $work_experiences = array();
        }

        return $this->response(array(
            'code'          => 1,
            'data'          => $work_experiences,
            'pagination'    => array(
                'page'      => intval($page),
                'pageSize'  => intval($limit),
                'total'     => $count,
            ),
        ), 200);
    }

    public function experience_post()
    {
        $this->load->model('work_experience_model');
        $uid = $this->_get_uid();
        $experience_id = intval($this->post('experience_id'));
        $resume_id = intval($this->post('resume_id'));
        if (!$resume_id) {
            $this->response(api_error(400), 200);
        }
        $resume = $this->resume_model->get_one(array('id' => $resume_id, 'user_id' => $uid));
        if (empty($resume)) {
            $this->response(api_error(90001, '该简历不存在！'), 200);
        }

        $set = array();
        $set['company'] = $this->post('company');
        if (empty($set['company'])) {
            $this->response(api_error(90001, '请填写公司名称！'), 200);
        }

        $set['start_time'] = $this->post('start_time');
        if (empty($set['start_time'])) {
            $this->response(api_error(90001, '请填写开始时间！'), 200);
        }

        $set['end_time'] = $this->post('end_time') ?: 0;

        $set['description'] = $this->post('description');
        if (empty($set['description'])) {
            $this->response(api_error(90001, '请填写工作内容！'), 200);
        }
        $set['update_time'] = time();

        if ($experience_id) {
            $this->work_experience_model->update(
                $set,
                array('id' => $experience_id, 'resume_id' => $resume_id)
            );
        } else {
            $set['create_time'] = $set['update_time'];
            $this->work_experience_model->insert($set);
            if (!$resume['experience_completed']) {
                $this->resume_model->update(array(
                    'experience_completed' => 1,
                    'update_time' => time()
                ), array('id' => $resume_id));
            }
        }
        $this->response(array('code' => 1), 200);
    }

    public function evaluation_post()
    {
        $uid = $this->_get_uid();
        $resume_id = intval($this->post('resume_id'));
        if (!$resume_id) {
            $this->response(api_error(400), 200);
        }
        $resume = $this->resume_model->get_one(array('id' => $resume_id, 'user_id' => $uid));
        if (empty($resume)) {
            $this->response(api_error(90001, '该简历不存在！'), 200);
        }
        $set = array('evaluation' => $this->post('evaluation'), 'update_time' => time());
        if (empty($set['evaluation'])) {
            $this->response(api_error(90001, '请填写自我评价！'), 200);
        }

        if (!$resume['evaluation_completed']) {
            $set['evaluation_completed'] = 1;
        }
        $this->resume_model->update($set, array('id' => $resume_id));
        $this->response(array('code' => 1), 200);
    }
}