<?php

class Passport extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('Auth');
        $this->load->helper('admin');
    }

    /**
     * Check if users are logged in
     *
     * @return void
     */
    public function login()
    {
        $islogin = $this->auth->is_logged_in(false, false);

        if ($islogin) {
            go_dashboard();
        }

        $post = $this->input->post();
        if (!empty($post)) {

            $username     = $this->input->post('username');
            $password     = $this->input->post('password');
            $redirect     = $this->input->post('redirect');

            $login = $this->auth->login($username, $password);
            if ($login) {
                if ($redirect == '') {
                    go_dashboard();
                } else {
                    redirect($redirect);
                }
            } else {
                //this adds the redirect back to flash data if they provide an incorrect credentials
                $this->admin_session->set_flashdata('redirect', $redirect);
                $post['error'] = '用户名或密码错误！';
                unset($post['passport']);
                $this->load->view('admin/login', $post);
            }
        } else {
            $this->load->view('admin/login', array(
                'redirect' => $this->admin_session->flashdata('redirect'),
                'username' => '',
            ));
        }
    }

    /**
     * Logout
     *
     * @return void
     */
    public function logout()
    {
        $this->auth->logout();

        redirect('admin/login');
    }

    public function resetPwd()
    {
        $this->auth->is_logged_in(uri_string());
        $user = $this->auth->get_current_user();
        $this->load->vars(array('username' => $user['username']));

        $data = array('status' => 0);
        $post = $this->input->post();
        if (!empty($post)) {
            $password     = trim($post['password']);
            $confirm      = $post['confirm'];
            if (empty($password)) {
                $data['error'] = '请输入密码';
            } elseif ($password != $confirm) {
                $data['error'] = '确认密码不一致';
            } else {
                $this->auth->save(array(
                    'uid'      => $user['uid'],
                    'password' => $password,
                ));
                $data['status'] = 1;
            }
        }
        $this->load->view('admin/resetPwd', $data);
    }
}
