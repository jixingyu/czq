<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_api extends App_Controller
{

    public function __construct()
    {
        parent::__construct();
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

        $result = $this->user_lib->login($params);
        if ($result) {
            $token  = $result;
            $user   = $this->user_lib->get_current_user($token);
            $response = array(
                'code'   => 1,
                'user'   => $user,
                'token'  => $token
            );
        } else {
            $response = api_error(40102);
        }

        return $this->response($response, 200);
    }

    public function logout_post()
    {
        $token  = $this->post('token');
        $user_id    = (int) $this->post('user_id');
        $result = $this->user_lib->logout($token, $user_id);
        return $this->response(array(
            'code' => 1
        ), 200);
    }

    public function verify_token_get()
    {
        $token  = $this->get('token');
        if (!$token) {
            $this->response(api_error(400), 200);
        }

        $result = $this->user_lib->verify_token($token);
        if ($result) {
            $new_token = $result;
            $user      = $this->user_lib->get_current_user($new_token);
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
        $this->user_lib->clear($user_id);
        $this->response(array('code' => 1), 200);
    }

    public function save_phone_post()
    {
        $user_id = $this->_get_uid();
        $phone = trim($this->input->post('phone'));
        if (!@preg_match('/^1[3-9][0-9]{9}$/', $phone)) {
            $this->response(api_error(90001, '请填写正确的电话号码！'), 200);
        }
        $this->member_model->update(array('phone' => $phone), array('user_id' => $user_id));
        $this->user_lib->clear($user_id);
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
        $this->user_lib->clear($user_id);
        $this->response(array('code' => 1), 200);
    }

    private function _get_uid($check = true)
    {
        $token = $this->get('token');
        if (empty($token)) {
            $token = $this->post('token');
        }
        $result = $this->user_lib->is_logged_in($token);
        if ($check) {
            if ($result['code'] == 1) {
                $this->response(array(
                    'error' => api_error(40041),
                ), 200);
            } elseif ($result['code'] == 2) {
                $this->response(array(
                    'error' => api_error(40040),
                ), 200);
            } else {
                return $result['uid'];
            }
        } else {
            if ($result['code'] == 0) {
                return $result['uid'];
            } else {
                return false;
            }
        }
    }
}
