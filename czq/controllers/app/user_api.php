<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_api extends App_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('member_model');
    }

    public function register_post()
    {
        $this->load->model('member_model');
        $email = $this->post('email');
        $password = $this->post('password');

        if (!$email || !$password) {
            $this->response(api_error(400), 200);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->response(array(
                'error' => api_error(90001, '请填写正确的邮箱地址！'),
            ), 200);
        }

        $check_email = $this->member_model->find($email, 'email');
        if (!empty($check_email)) {
            $this->response(api_error(50001), 200);
        }
        $reg_active = $this->config->item('reg_active');
        if ($reg_active) {
            $this->member_model->insert(array(
                'email' => $email,
                'password' => md5($password . $this->app_user_lib->salt),
                'is_active' => 0,
            ));
            // TODO 发送激活邮件
        } else {
            $this->member_model->insert(array(
                'email' => $email,
                'password' => md5($password . $this->app_user_lib->salt),
                'is_active' => 1,
            ));
        }
        $this->response(array('code' => 1), 200);
    }

    public function reset_check_post()
    {
        $email = $this->post('email');
        $user = $this->member_model->find($email, 'email');
        if (empty($user)) {
            $this->response(api_error(50003), 200);
        }

        $check_code = base64_encode($user['email'] . '.' . md5($user['email'] . $this->app_user_lib->salt . $user['password']));
    }

    public function login_post()
    {
        $email = $this->post('email');
        $password = $this->post('password');

        if (!$email || !$password) {
            $this->response(api_error(400), 200);
        }

        $params = array(
            'email' => $this->post('email'),
            'password' => $this->post('password')
        );

        $user = $this->app_user_lib->login($params);
        if ($user) {
            if (!$user['is_active']) {
                $this->response(api_error(50002), 200);
            }
            $token  = $user['token'];
            unset($user['token']);
            $response = array(
                'code'   => 1,
                'user'   => $user,
                'token'  => $token
            );
        } else {
            $response = api_error(40102);
        }

        $this->response($response, 200);
    }

    public function logout_get()
    {
        $token   = $this->get('token');
        $result  = $this->app_user_lib->logout($token);
        $this->response(array(
            'code' => 1
        ), 200);
    }

    public function verify_token_get()
    {
        $token  = $this->get('token');
        if (!$token) {
            $this->response(api_error(400), 200);
        }

        $result = $this->app_user_lib->verify_token($token);
        if ($result) {
            $new_token = $result;
            $user      = $this->app_user_lib->get_current_user($new_token);
            $response = array(
                'code'   => 1,
                'user'   => $user,
                'token'  => $token
            );
        } else {
            $response = api_error(40102);
        }
        $this->response($response, 200);
    }

    public function edit_post()
    {
        $user_id = $this->_get_uid();
        $real_name = trim($this->input->post('real_name'));
        if (empty($real_name)) {
            $this->response(api_error(90001, '请填写您的姓名！'), 200);
        }

        $mobile = trim($this->input->post('mobile'));
        if (!@preg_match('/^1[3-9][0-9]{9}$/', $mobile)) {
            $this->response(api_error(90001, '请填写正确的手机号！'), 200);
        }
        $this->member_model->update(array(
            'real_name' => $real_name,
            'mobile' => $mobile,
        ), array('user_id' => $user_id));
        $this->app_user_lib->clear($user_id);
        $this->response(array('code' => 1), 200);
    }
}
