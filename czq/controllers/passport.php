<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Passport extends Front_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('user_lib');
    }

    public function reset_pwd()
    {
    	$p = $this->input->get('p');
    	$p = explode('.', base64_decode($p));
    	$user = $this->member_model->find($p[0], 'email');
    	$checkCode = md5($p[0] . $this->user_lib->salt . $user['password']);

    	if ($p['1'] == $checkCode) {
    		$email = $this->post('email');
    		if (empty($email)) {
        		$this->view('reset_pwd', array(
        			'email' => $p[0],
    			));
    		} else {
		        $password = $this->post('password');
		        $confirm = $this->post('confirm_password');
		        if ($password != $confirm) {
	        		$this->view('reset_pwd', array(
	        			'email' => $p[0],
	        			'error' => '两次输入密码不一致',
	    			));
	    			return;
		        }
		        $this->member_model->update(
		        	array('password' => md5($password . $this->user_lib->salt)),
		        	array('email' => $email)
	        	);
        		$this->view('reset_pwd_success');
    		}
    	}
    }
}
