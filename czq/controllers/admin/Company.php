<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 * Company
 *
 * @author  Xy Ji
 */
class Company extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('company_model');
    }

    public function index()
    {
        $offset = intval($this->input->get('o'));
        $data   = array(
            'company_list' => array(),
        );

        $count = $this->company_model->get_count();
        if ($count) {
            $limit = $this->config->item('page_size');
            $data['company_list'] = $this->company_model->get_list(array(), $limit, $offset);

            $this->load->library('pagination');

            $this->pagination->initialize_admin(array(
                'base_url'    => preg_replace('/(.*)(\?|&)o=.*/', '$1', site_url($this->input->server('REQUEST_URI'))),
                'total_rows'  => $count,
                'per_page'    => $limit,
                'page_query_string'    => true,
                'query_string_segment' => 'o'
            ));
        }

        $this->load->view('admin/company_list', $data);
    }

    public function editCompany($id = 0)
    {
        $data = array(
            'company' => array(
                'name' => '',
                'address' => '',
                'industry' => '',
                'number' => '',
                'description' => '',
            ),
            'status' => 0,
        );

        if ($id) {
            $data['company'] = $this->company_model->get_one(array('id' => $id));
        }

        $post = $this->input->post();
        if (!empty($post)) {
            $data['company'] = array_merge($data['company'], $post);

            if (empty($post['name'])) {
                $data['error'] = '请填写公司名';
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
                if ($id) {
                    $post['update_time'] = time();
                    $this->company_model->update($post, array('id' => $id));
                } else {
                    $post['create_time'] = $post['update_time'] = time();
                    $data['company']['id'] = $this->company_model->insert($post);
                }

                $data['status'] = 1;
            }
        }
        $this->load->view('admin/company', $data);
    }

    public function deleteCompany($id = 0)
    {
        $data = array('code' => 0);
        $company = $this->company_model->get_one(array('id' => $id));
        if (!empty($company)) {
        	$this->load->model('job_model');
            $exist = $this->job_model->get_count(array('company_id' => $id, 'is_deleted' => 0));
            if ($exist > 0) {
                $data['message'] = '该公司下有发布的职位，无法删除';
            } else {
                $this->company_model->delete($id);
                $data['code'] = 1;
            }
        }
        echo json_encode($data);
    }
}
