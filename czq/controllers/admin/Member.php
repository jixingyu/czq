<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 * Member
 *
 * @author  Xy Ji
 */
class Member extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('member_model');
        $this->load->library('user_lib');
    }

    public function index()
    {
        $offset = intval($this->input->get('o'));
        $data   = array(
            'member_list' => array(),
        );

        $count = $this->member_model->get_count();
        if ($count) {
            $limit = $this->config->item('page_size');
            $data['member_list'] = $this->member_model->get_list(array(), $limit, $offset);

            $this->load->library('pagination');

            $this->pagination->initialize_admin(array(
                'base_url'    => preg_replace('/(.*)(\?|&)o=.*/', '$1', site_url($this->input->server('REQUEST_URI'))),
                'total_rows'  => $count,
                'per_page'    => $limit,
                'page_query_string'    => true,
                'query_string_segment' => 'o'
            ));
        }

        $this->load->view('admin/member_list', $data);
    }

    public function editMember($user_id = 0)
    {
        $data = array(
            'member' => array(
                'email' => '',
                'real_name' => '',
                'mobile' => '',
                'is_active' => 0,
            ),
            'status' => 0,
        );

        if ($user_id) {
            $data['member'] = $this->member_model->get_one(array('user_id' => $user_id));
        }

        $post = $this->input->post();
        if (!empty($post)) {
            $data['member'] = array_merge($data['member'], $post);

            $set = array(
                'real_name' => $post['real_name'],
                'is_active' => !empty($post['is_active']) ? 1 : 0,
                'update_time' => time(),
            );
            if (!empty($post['mobile']) && !@preg_match('/^1[3-9][0-9]{9}$/', $post['mobile'])) {
                $data['error'] = '请填写正确的手机号！';
            } else {
                $set['mobile'] = $post['mobile'];
            }

            if (empty($data['error']) && !$user_id) {
                if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
                    $data['error'] = '请填写正确的邮箱';
                } else {
                    $set['email'] = $post['email'];

                    if (empty($post['password'])) {
                        $data['error'] = '请填写密码';
                    } elseif ($post['password'] != $post['confirm_password']) {
                        $data['error'] = '两次输入密码不一致';
                    } else {
                        $set['password'] = $this->user_lib->generate_pwd($post['password']);
                    }
                }
                $set['create_time'] = $set['update_time'];
            }

            if (empty($data['error'])) {
                if ($user_id) {
                    $this->member_model->update($set, array('user_id' => $user_id));
                } else {
                    $data['member']['user_id'] = $this->member_model->insert($set);
                }

                $data['status'] = 1;
            }
        }
        $this->load->view('admin/member', $data);
    }

    public function resetPwd($user_id)
    {
        $data = array(
            'member_id' => $user_id,
            'status' => 0,
        );

        $post = $this->input->post();
        if (!empty($post)) {
            if (empty($post['password'])) {
                $data['error'] = '请填写密码';
            } elseif ($post['password'] != $post['confirm_password']) {
                $data['error'] = '两次输入密码不一致';
            } else {
                $password = $this->user_lib->generate_pwd($post['password']);
            }

            if (empty($data['error'])) {
                $this->member_model->update(array(
                    'password' => $password,
                    'update_time' => time(),
                ), array('user_id' => $user_id));

                $data['status'] = 1;
            }
        }
        $this->load->view('admin/reset_pwd', $data);
    }
}
