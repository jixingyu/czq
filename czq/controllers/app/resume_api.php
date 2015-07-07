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

    public function add_resume_post()
    {
        $uid = $this->_get_uid();
        $count = $this->resume_model->get_count(array('user_id' => $uid));
        $limit = $this->config->item('resume_limit');
        if ($count >= $limit) {
            $this->response(api_error(90001, '最多可保存' . $limit . '份简历！'), 200);
        }

        $resume_id = $this->resume_model->insert(array(
            'user_id' => $uid,
            'create_time' => time(),
            'update_time' => time()
        ));
        $this->response(array(
            'code' => 1,
            'data' => $resume_id
        ), 200);
    }

    public function real_name_post()
    {
        $uid = $this->_get_uid();
        $resume_id = intval($this->post('resume_id'));
        if (!$resume_id) {
            $this->response(api_error(400), 200);
        }
        $real_name = trim($this->post('real_name'));
        if (empty($real_name)) {
            $this->response(api_error(90001, '请填写您的姓名！'), 200);
        }
        $this->resume_model->update(
            array('real_name' => $real_name, 'update_time' => time()),
            array('id' => $resume_id,'user_id' => $user_id)
        );
        $this->response(array('code' => 1), 200);
    }

    public function gender_post()
    {
        $uid = $this->_get_uid();
        $resume_id = intval($this->post('resume_id'));
        if (!$resume_id) {
            $this->response(api_error(400), 200);
        }
        $gender = $this->post('gender');
        $gender = $gender ? 1 : 0;

        $this->resume_model->update(
            array('gender' => $gender, 'update_time' => time()),
            array('id' => $resume_id,'user_id' => $user_id)
        );
        $this->response(array('code' => 1), 200);
    }

    public function birthday_post()
    {
        $uid = $this->_get_uid();
        $resume_id = intval($this->post('resume_id'));
        if (!$resume_id) {
            $this->response(api_error(400), 200);
        }
        $birthday = intval($this->post('birthday'));
        if (empty($birthday)) {
            $this->response(api_error(90001, '请填写您的生日！'), 200);
        }
        $this->resume_model->update(
            array('birthday' => $birthday, 'update_time' => time()),
            array('id' => $resume_id,'user_id' => $user_id)
        );
        $this->response(array('code' => 1), 200);
    }

    public function native_place_post()
    {
        $uid = $this->_get_uid();
        $resume_id = intval($this->post('resume_id'));
        if (!$resume_id) {
            $this->response(api_error(400), 200);
        }
        $native_place = trim($this->post('native_place'));
        if (empty($native_place)) {
            $this->response(api_error(90001, '请填写您的籍贯！'), 200);
        }
        $this->resume_model->update(
            array('native_place' => $native_place, 'update_time' => time()),
            array('id' => $resume_id,'user_id' => $user_id)
        );
        $this->response(array('code' => 1), 200);
    }

    public function political_status_post()
    {
        $uid = $this->_get_uid();
        $resume_id = intval($this->post('resume_id'));
        if (!$resume_id) {
            $this->response(api_error(400), 200);
        }
        $political_status = trim($this->post('political_status'));
        if (empty($political_status)) {
            $this->response(api_error(90001, '请填写您的政治面貌！'), 200);
        }
        $this->resume_model->update(
            array('political_status' => $political_status, 'update_time' => time()),
            array('id' => $resume_id,'user_id' => $user_id)
        );
        $this->response(array('code' => 1), 200);
    }

    public function working_years_post()
    {
        $uid = $this->_get_uid();
        $resume_id = intval($this->post('resume_id'));
        if (!$resume_id) {
            $this->response(api_error(400), 200);
        }
        $working_years = intval($this->post('working_years'));
        if (empty($working_years)) {
            $this->response(api_error(90001, '请选择您的工作年龄！'), 200);
        }
        $this->resume_model->update(
            array('working_years' => $working_years, 'update_time' => time()),
            array('id' => $resume_id,'user_id' => $user_id)
        );
        $this->response(array('code' => 1), 200);
    }

    public function mobile_post()
    {
        $uid = $this->_get_uid();
        $resume_id = intval($this->post('resume_id'));
        if (!$resume_id) {
            $this->response(api_error(400), 200);
        }
        $mobile = trim($this->post('mobile'));
        if (!@preg_match('/^1[3-9][0-9]{9}$/', $mobile)) {
            $this->response(api_error(90001, '请填写正确的手机号！'), 200);
        }
        $this->resume_model->update(
            array('mobile' => $mobile, 'update_time' => time()),
            array('id' => $resume_id,'user_id' => $user_id)
        );
        $this->response(array('code' => 1), 200);
    }

    public function email_post()
    {
        $uid = $this->_get_uid();
        $resume_id = intval($this->post('resume_id'));
        if (!$resume_id) {
            $this->response(api_error(400), 200);
        }
        $email = trim($this->post('email'));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->response(array(
                'error' => api_error(90001, '请填写正确的邮箱地址！'),
            ), 200);
        }
        $this->resume_model->update(
            array('email' => $email, 'update_time' => time()),
            array('id' => $resume_id,'user_id' => $user_id)
        );
        $this->response(array('code' => 1), 200);
    }

    public function school_post()
    {
        $uid = $this->_get_uid();
        $resume_id = intval($this->post('resume_id'));
        if (!$resume_id) {
            $this->response(api_error(400), 200);
        }
        $school = trim($this->post('school'));
        if (empty($school)) {
            $this->response(api_error(90001, '请填写您的毕业学校！'), 200);
        }
        $this->resume_model->update(
            array('school' => $school, 'update_time' => time()),
            array('id' => $resume_id,'user_id' => $user_id)
        );
        $this->response(array('code' => 1), 200);
    }

    public function major_post()
    {
        $uid = $this->_get_uid();
        $resume_id = intval($this->post('resume_id'));
        if (!$resume_id) {
            $this->response(api_error(400), 200);
        }
        $major = trim($this->post('major'));
        if (empty($major)) {
            $this->response(api_error(90001, '请填写您的专业！'), 200);
        }
        $this->resume_model->update(
            array('major' => $major, 'update_time' => time()),
            array('id' => $resume_id,'user_id' => $user_id)
        );
        $this->response(array('code' => 1), 200);
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
        $exist = $this->resume_model->get_count(array('id' => $resume_id, 'user_id' => $uid));
        if (empty($exist)) {
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
        }
        $this->response(array('code' => 1), 200);
    }
}