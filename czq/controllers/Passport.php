<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Passport extends Front_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('user_lib');
    }

    public function index()
    {
        echo 'test';exit;
    }

    public function find_pwd()
    {
        $data = array();
        $post = $this->input->post();
        if (!empty($post)) {
            $type = 1;
            $email = $post['email'];
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $data['error'] = '请填写正确的邮箱地址！';
            } else {
                $this->load->model('member_model');
                $exist = $this->member_model->get_count(array('email' => $email));
                if (!$exist) {
                    $data['error'] = '该邮箱不存在！';
                }
            }

            if (empty($data['error'])) {
                $this->load->model('pass_token_model');
                $expires = time() + 3600;//60分钟有效期
                $pass_token = $this->pass_token_model->get_one(array(
                    'email' => $email,
                    'type'  => $type,
                ));
                $date = date('Y-m-d H:i');
                $token = $this->_gen_token($type, $email, $expires);
                $link = site_url('passport/reset_pwd?e=' . urlencode($email) . '&t=' . urlencode($token));
                if (!empty($pass_token)) {
                    if ($pass_token['expires'] < time() + 600) {
                        $this->pass_token_model->update(array(
                            'token'   => $token,
                            'expires' => $expires
                        ), array(
                            'email' => $email,
                            'type'  => $type,
                        ));
                        if (!$this->_mail_reset_pwd($email, $date, $link)) {
                            $data['error'] = '邮件发送失败，请稍微重试或联系客服';
                            $this->pass_token_model->update(array(
                                'expires' => 0
                            ), array(
                                'email' => $email,
                                'type'  => $type,
                            ));
                        }
                    }
                } else {
                    $this->pass_token_model->insert(array(
                        'token'   => $token,
                        'expires' => $expires,
                        'email'   => $email,
                        'type'    => $type,
                    ));
                    if (!$this->_mail_reset_pwd($email, $date, $link)) {
                        $data['error'] = '邮件发送失败，请稍后重试或联系客服';
                        $this->pass_token_model->delete(array(
                            'email' => $email,
                            'type'  => $type,
                        ));
                    }
                }
                if (empty($data['error'])) {
                    $data['msg'] = '请查收邮件，并尽快修改密码！';
                }
            }
        }
        $this->view('find_pwd', $data);
    }

    private function _mail_reset_pwd($email, $date, $link)
    {
        $this->load->library('email');
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'smtp.qq.com';
        $config['smtp_user'] = '445346494@qq.com';
        $config['smtp_pass'] = 'test1111';
        $config['smtp_port'] = '25';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html';
        $this->email->initialize($config);

        $this->email->from('445346494@qq.com', '握手招聘');
        $this->email->to($email);

        $this->email->subject('握手招聘 - 找回密码');
        $msg = <<<EOT
尊敬的 握手招聘APP 用户:
您的握手招聘APP帐号[%s]，于 %s 使用邮箱找回密码功能。如非本人操作，请忽略。
点击 <a href="%s">此处</a> 修改您的密码。
EOT;
        $msg = sprintf($msg, $email, $date, $link);

        $this->email->message($msg);

        return $this->email->send();
    }

    private function _gen_token($type)
    {
        return md5(rand(1,999) . time() . $type);
    }

    public function reset_pwd()
    {
        $this->load->model(array('member_model', 'pass_token_model'));
    	$token = $this->input->get('t');
        $email = $this->input->get('e');
    	$pass_token = $this->pass_token_model->get_one(array(
            'email' => $email,
            'token' => $token,
            'type'  => 1,
        ));
        if (empty($pass_token)) {
            show_404();
        }
        if ($pass_token['expires'] < time()) {
            $data = array(
                'msg' => '链接已过期',
            );
        } else {
            $data = array(
                'reset_url' => site_url('passport/reset_pwd?e=' . urlencode($email) . '&t=' . urlencode($token)),
            );

            $post = $this->input->post();
            if (!empty($post)) {
                $password = $post['password'];
                $confirm = $post['confirm_password'];
                if ($password != $confirm) {
                    $data['error'] = '两次输入密码不一致';
                } else {
                    $this->member_model->update(
                        array('password' => md5($password . $this->user_lib->salt)),
                        array('email' => $pass_token['email'])
                    );
                    $data['success_msg'] = '修改成功';
                    $this->pass_token_model->update(array(
                        'expires' => 0
                    ), array(
                        'email' => $pass_token['email'],
                        'type'  => 1,
                    ));
                }
            }
        }
        $this->view('reset_pwd', $data);
    }
}
