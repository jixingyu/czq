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
            ),
            'status' => 0,
        );

        if ($user_id) {
            $data['member'] = $this->member_model->get_one(array('user_id' => $user_id));
        }

        $post = $this->input->post();
        if (!empty($post)) {
            $data['member'] = array_merge($data['member'], $post);

            if (!$user_id) {
                if (empty($post['email'])) {
                    $data['error'] = '请填写邮箱';
                }
            }

            if (empty($post['address'])) {
                $data['error'] = '请填写公司地址';
            }

            if (empty($post['industry'])) {
                $data['error'] = '请填写公司所属行业';
            }

            if (empty($post['number'])) {
                $data['error'] = '请填写公司人数';
            }

            if (empty($post['description'])) {
                $data['error'] = '请填写公司描述';
            }

            if (empty($data['error'])) {
                if ($user_id) {
                    $post['update_time'] = time();
                    $this->member_model->update($post, array('user_id' => $user_id));
                } else {
                    $post['create_time'] = $post['update_time'] = time();
                    $data['member']['user_id'] = $this->member_model->insert($post);
                }

                $data['status'] = 1;
            }
        }
        $this->load->view('admin/member', $data);
    }
}
