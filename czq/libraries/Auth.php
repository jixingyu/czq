<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth
{
    private $CI;

    //this is the expiration for a non-remember session
    public $session_expire	= 600;

    private $salt = 'qe&2*34$a';

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->CI->load->library('encrypt');
        $this->CI->load->model('admin_member_model');

        $admin_session_config = array(
            'sess_cookie_name' => 'admin_session_config',
            'sess_expiration'  => 0
        );
        $this->CI->load->library('session', $admin_session_config, 'admin_session');

        $this->CI->load->helper('url');
    }

    /**
     * Get current user
     *
     * @return array
     */    
    public function get_current_user()
    {
        $admin = $this->CI->admin_session->userdata('admin');

        return $admin;
    }

    /**
     * Check if logged in
     *
     * @param bool $redirect
     * @param bool $default_redirect
     * @return bool
     */    
    public function is_logged_in($redirect = false, $default_redirect = true)
    {
        $admin = $this->CI->admin_session->userdata('admin');

        if (!$admin) {
            if ($redirect) {
                $this->CI->admin_session->set_flashdata('redirect', $redirect);
            }

            if ($default_redirect) {
                redirect('admin/login');
            }

            return false;
        } else {

            //check if the session is expired if not reset the timer
            if ($admin['expire'] && $admin['expire'] < time()) {

                $this->logout();
                if ($redirect) {
                    $this->CI->admin_session->set_flashdata('redirect', $redirect);
                }

                if ($default_redirect) {
                    redirect('admin/login');
                }

                return false;
            } else {

                //update the session expiration to last more time if they are not remembered
                if ($admin['expire']) {
                    $admin['expire'] = time()+$this->session_expire;
                    $this->CI->admin_session->set_userdata(array('admin'=>$admin));
                }

            }

            return true;
        }
    }

    /**
     * this function does the logging in.
     *
     * @param string $username
     * @param string $password
     * @param bool $remember
     * @return bool
     */    
    public function login($username, $password, $remember=false)
    {
        $this->CI->db->select('*');
        $this->CI->db->where('username', $username);
        $this->CI->db->where('password', sha1($password . $this->salt));
        $this->CI->db->limit(1);
        $result = $this->CI->db->get('admin_member');
        $result	= $result->row_array();

        if (sizeof($result) > 0) {
            $admin = array();
            $admin['admin']			= array();
            $admin['admin']['uid']	    = $result['uid'];
            $admin['admin']['username']	= $result['username'];

            if (!$remember) {
                $admin['admin']['expire'] = time()+$this->session_expire;
            } else {
                $admin['admin']['expire'] = false;
            }

            $this->CI->admin_session->set_userdata($admin);

            return true;
        } else {
            return false;
        }
    }

    /**
     * this function does the logging out.
     *
     * @return void
     */
    public function logout()
    {
        $this->CI->admin_session->unset_userdata('admin');
        $this->CI->admin_session->sess_destroy();
    }

    /**
     *takes admin array and inserts/updates it to the database
     *
     * @param array $admin
     * @return void
     */    
    public function save($admin)
    {
        if (isset($admin['password'])) {
            $admin['password'] = sha1($admin['password'] . $this->salt);
        } 
        if (!empty($admin['uid'])) {
            $this->CI->db->where('uid', $admin['uid']);
            $this->CI->db->update('admin_member', $admin);
        } else {
            $this->CI->db->insert('admin_member', $admin);
        }
    }
}
