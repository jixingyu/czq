<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_api extends App_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('app_user_lib');
    }

    public function register_post()
    {
        $email = $this->post('email');
        $password = $this->post('password');

        if (!$email || !$password) {
            $this->response(api_error(400), 200);
        }

        $check_email = $this->member_model->find($email, 'email');
        if (!empty($check_email)) {
            $this->response(api_error(50001), 200);
        }
        $this->load->model('myconfig_model');
        $config = $this->myconfig_model->get_one(array());
        if ($config['reg_active']) {
            $this->member_model->insert(array(
                'email' => $email,
                'password' => md5($params['password'] . $this->app_user_lib->salt),
                'is_active' => 0,
            ));
            // TODO 发送激活邮件
        } else {
            $this->member_model->insert(array(
                'email' => $email,
                'password' => md5($params['password'] . $this->app_user_lib->salt),
                'is_active' => 1,
            ));
        }
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

    public function logout_post()
    {
        $token   = $this->get('token');
        $user_id = (int) $this->post('user_id');
        $result  = $this->app_user_lib->logout($token, $user_id);
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

    public function save_nick_name_post()
    {
        $user_id = $this->_get_uid();
        $nick_name = trim($this->input->post('nick_name'));
        if (empty($nick_name)) {
            $this->response(api_error(90001, '请填写您的昵称！'), 200);
        }
        $this->member_model->update(array('nick_name' => $nick_name), array('user_id' => $user_id));
        $this->app_user_lib->clear($user_id);
        $this->response(array('code' => 1), 200);
    }

    public function save_phone_post()
    {
        $user_id = $this->_get_uid();
        $phone = trim($this->input->post('phone'));
        if (!@preg_match('/^1[3-9][0-9]{9}$/', $phone)) {
            $this->response(api_error(90001, '请填写正确的手机号！'), 200);
        }
        $this->member_model->update(array('phone' => $phone), array('user_id' => $user_id));
        $this->app_user_lib->clear($user_id);
        $this->response(array('code' => 1), 200);
    }

    public function save_phone_email_post()
    {
        $user_id = $this->_get_uid();
        $phone_email = trim($this->input->post('phone_email'));
        if (!filter_var($phone_email, FILTER_VALIDATE_EMAIL)) {
            $this->response(api_error(90001, '请填写正确的邮箱地址！'), 200);
        }
        $this->member_model->update(array('phone_email' => $phone_email), array('user_id' => $user_id));
        $this->app_user_lib->clear($user_id);
        $this->response(array('code' => 1), 200);
    }

    private function _get_uid($check = true)
    {
        $token = $this->get('token');

        $result = $this->app_user_lib->is_logged_in($token);
        if ($check) {
            if ($result['code'] == 1) {
                return $result['uid'];
            } else {
                $this->response(array(
                    'error' => api_error($result['code']),
                ), 200);
            }
        } else {
            if ($result['code'] == 1) {
                return $result['uid'];
            } else {
                return false;
            }
        }
    }
}
