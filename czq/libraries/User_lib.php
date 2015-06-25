<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * User Libraries For Front
 *
 * @copyright       2014 © Moore8. ALL Rights Reserved
 * @author          Moore8 Team
 */
class User_lib
{
    public $CI;
    protected $_adapter = 'web';
    private $_salt = 'qQ#9+=~kK-';

    public function __construct($adapter = false)
    {
        $this->CI =& get_instance();
        $this->CI->load->database();
        // load cache driver
        $cache_adapter = $this->CI->config->item('cache_adapter');
        $this->CI->load->driver('cache', $cache_adapter);
        $this->CI->load->model(array(
            'member_model'
        ));
        if ($adapter) {
            $this->set_adapter($adapter);
        }
    }

    /**
     * Set adapter
     *
     */
    public function set_adapter($adapter)
    {
        $this->_adapter = $adapter;
    }


    /**
     * Check if user logged
     *
     * @return bool
     */
    public function is_logged_in()
    {
        switch ($this->_adapter) {
            case 'app':

            default:
                $user = $this->CI->session->userdata('app_auth');
                $result = empty($user) ? false : true;
                break;
        }
        return $result;
    }

    /**
     * Require auth; while user not logged in, start login process
     *
     * @param array $params
     * @return bool
     */
    public function login($params)
    {
        $result = false;
        switch ($this->_adapter) {
            case 'app':

            default:
                $result = $this->CI->member_model->get_one(array(
                    'email' => $params['email'],
                    'password' => md5($params['password'] . $this->_salt),
                ));

                break;
        }
        return $result;
    }

    /**
     * Logout
     *
     * @return void
     */
    public function logout()
    {
        switch ($this->_adapter) {
            case 'app':
                $this->CI->session->unset_userdata('app_auth');
                $this->CI->session->sess_destroy();
                break;

            default:
                $this->CI->sso->logout(array(
                    'ReturnTo' => '/'
                ));
                break;
        }
        return;
    }

    /**
     * Get current user(formated)
     *
     * @param string $key
     * @return null or array or string
     */
    public function get_current_user($key = '')
    {
        if (!$this->is_logged_in()) {
            return null;
        }

        switch ($this->_adapter) {
            case 'app':
                $user = $this->CI->session->userdata('app_auth');
                $uid  = $user['id'];
                if (empty($uid)) {
                    return null;
                }

                $member = $this->CI->cache->get('member_' . $uid);
                if (empty($member)) {
                    $member = $this->CI->member_model->find($uid, 'user_id');
                    $member = $this->format_member($member);
                    $this->CI->cache->save('member_' . $uid, $member, 3600);
                }
                if (!empty($key)) {
                    $result = isset($member[$key]) ? $member[$key] : null;
                } else {
                    $result = $member;
                }
                break;

            default:
                $sso_user = $this->CI->sso->getAttributes();//$this->CI->sso->getAttributes()
                if (!empty($key) && in_array($key, array('user_id', 'user_name', 'email'))) {
                    $result = isset($sso_user[$key]) ? $sso_user[$key] : null;
                } else {
                    $uid = $sso_user['user_id'];

                    $member = $this->CI->cache->get('member_' . $uid);
                    if (empty($member)) {
                        $member = $this->CI->member_model->find($uid, 'user_id');
                        $member = $this->format_member($member);
                        $this->CI->cache->save('member_' . $uid, $member, 3600);
                    }
                    if (!empty($key)) {
                        $result = isset($member[$key]) ? $member[$key] : null;
                    } else {
                        $result = $member;
                    }
                }
                break;
        }
        return $result;
    }

    /**
     * Clear cache
     *
     * @param int $uid
     */
    public function clear_cache($uid)
    {
        @$this->CI->cache->delete('member_' . $uid);

        return true;
    }
}
