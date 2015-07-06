<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class App_user_lib
{
    public $CI;
    public $salt = 'qQ#9+=~kK-';

    public function __construct()
    {
        $this->CI =& get_instance();
        $cache_adapter = $this->CI->config->item('cache_adapter');
        $this->CI->load->driver('cache', $cache_adapter);

        $this->CI->load->model('app_token_model');
    }

    public function is_logged_in($token = '')
    {
        $token = $this->_get_token($token);
        if ($token) {
            $result = $this->CI->app_token_model->get_one(array(
                'token' => $token
            ));
            if ($result) {
                if ($result['expires'] > time()) {
                    return array(
                        'code' => 1,
                        'uid'  =>$result['user_id'],
                    );
                } else {
                    $this->logout();
                    return array('code' => 40104);
                }
            } else {
                $this->logout();
                return array('code' => 40104);
            }
        } else {
            $this->logout();
            return array('code' => 40103);
        }
    }

    public function verify_token($token)
    {
        $result = $this->CI->app_token_model->get_one(array(
            'token'     => $token
        ));
        if (!$result) {
            return false;
        } else if ($result['expires'] < time()) {
            // delete unused token
            $this->CI->app_token_model->delete(array(
                'user_id' => $result['user_id']
            ));
            // logout moore8
            $this->logout();
            return false;
        } else {
            // update token
            $uid     = $result['user_id'];
            $token   = $this->_gen_token($uid);
            $expires = strtotime('1 month');
            $this->CI->app_token_model->update(array(
                'token'     => $token,
                'expires'   => $expires
            ), array(
                'user_id'   => $uid
            ));

            $this->CI->session->set_userdata(array(
                'app_auth' => array(
                    'id'     => $uid,
                    'token'  => $token
                )
            ));
            return $token;
        }
    }

    public function login($params = array())
    {
        $this->CI->load->model('member_model');
        $user = $this->CI->member_model->get_one(array(
            'email' => $params['email'],
            'password' => md5($params['password'] . $this->salt),
        ));
        if (!empty($user)) {
            unset($user['password']);
            if ($user['is_active'] == 0) {
                $reg_active = $this->CI->config->item('reg_active');
                if (!$reg_active) {
                    $this->CI->member_model->update(
                        array('is_active' => 1),
                        array('user_id' => $user['user_id'])
                    );
                    $user['is_active'] = 1;
                } else {
                    return $user;
                }
            }
            // save token
            $uid     = $user['user_id'];
            $token   = $this->_gen_token($uid);
            $expires = strtotime('1 month');
            $this->CI->app_token_model->replace(array(
                'user_id' => $uid,
                'token'   => $token,
                'create_time' => time(),
                'expires' => $expires
            ), array(
                'user_id' => $uid
            ));

            $this->CI->session->set_userdata(array(
                'app_auth' => array(
                    'id'     => $uid,
                    'token'  => $token
                )
            ));
            $this->CI->cache->save('app_user_' . $uid, $user, 3600);
            $user['token'] = $token;
            return $user;
        } else {
            return false;
        }
    }

    public function logout($token = '', $user_id = 0)
    {
        $token = $this->_get_token($token);
        // clear token
        if ($token) {
            $this->CI->app_token_model->delete(array(
                'token' => $token
            ));
        }
        // clear token by user
        if ($user_id) {
            $this->CI->app_token_model->delete(array(
                'user_id' => $user_id
            ));
        }
        $this->CI->session->unset_userdata('app_auth');
        $this->CI->session->sess_destroy();
    }

    public function clear($uid)
    {
        @$this->CI->cache->delete('app_user_' . $uid);
    }

    public function get_current_user($token = '', $key = '')
    {
        $uid = $this->is_logged_in($token);
        if ($uid['code'] != 1) {
            return null;
        } else {
            $uid = $uid['uid'];
        }

        $user = $this->CI->cache->get('app_user_' . $uid);
        if (empty($user)) {
            $this->CI->load->model('member_model');
            $user = $this->CI->member_model->find($uid, 'user_id');

            $this->CI->cache->save('app_user_' . $uid, $user, 3600);
        }
        if (!empty($key)) {
            $result = isset($user[$key]) ? $user[$key] : null;
        } else {
            $result = $user;
        }
        return $result;
    }

    private function _gen_token($uid) {
        return md5(rand(1,9999) . time() . $uid);
    }

    private function _get_token($token = '') {
        if (!$token) {
            $sess_user = $this->CI->session->userdata('app_auth');
            $token = !empty($sess_user['token']) ? $sess_user['token'] : '';
        }
        return $token;
    }
}