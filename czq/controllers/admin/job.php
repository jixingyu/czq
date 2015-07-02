<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 * Job
 *
 * @author  Xy Ji
 */
class Job extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('job_model', 'company_model'));
    }

    public function index()
    {
        $offset = intval($this->input->get('o'));
        $data   = array(
            'job_list' => array(),
        );

        $count = $this->job_model->get_count();
        if ($count) {
            $limit = $this->config->item('page_size');
            $data['job_list'] = $this->job_model->get_list(array(), $limit, $offset);

            $this->load->library('pagination');

            $this->pagination->initialize_admin(array(
                'base_url'    => preg_replace('/(.*)(\?|&)o=.*/', '$1', site_url($this->input->server('REQUEST_URI'))),
                'total_rows'  => $count,
                'per_page'    => $limit,
                'page_query_string'    => true,
                'query_string_segment' => 'o'
            ));
        }

        $this->load->view('admin/job_list', $data);
    }

    public function editJob($id = 0)
    {
        $this->config->load('job', TRUE);
        $data = array(
            'job' => array(
                'id' => 0,
                'name' => '',
                'degree' => '',
                'salary' => '',
                'company_id' => 0,
            ),
            'sel_degree' => false,
            'sel_salary' => false,
            'degree_list' => $this->config->item('degree', 'job'),
            'salary_list' => $this->config->item('salary', 'job'),
            'company_list' => $this->company_model->get_list(),
            'status' => 0,
        );

        if ($id) {
            $data['job'] = $this->job_model->get_one(array('id' => $id));
        }

        $post = $this->input->post();
        if (!empty($post)) {
            $data['job']['name'] = $post['name'];
            $data['job']['company_id'] = $post['company_id'];
            if ($post['degree'] == '-1') {
                $data['job']['degree'] = $post['degree'] = $post['custom_degree'];
            }
            if ($post['salary'] == '-1') {
                $data['job']['salary'] = $post['salary'] = $post['custom_salary'];
            }

            if (empty($post['name'])) {
                $data['error'] = '请填写职位名称';
            }

            if (empty($data['error'])) {
                unset($post['custom_degree'], $post['custom_salary']);
                if ($id) {
                    $post['update_time'] = time();
                    $this->job_model->update($post, array('id' => $id));
                } else {
                    $post['create_time'] = $post['update_time'] = time();
                    $data['job']['id'] = $this->job_model->insert($post);
                }

                $data['status'] = 1;
            }
        }
        $this->load->view('admin/job', $data);
    }

    public function deleteJob($id = 0)
    {
        $data = array('code' => 0);
        $job = $this->job_model->get_one(array('id' => $id));
        if (!empty($job)) {
            $this->job_model->delete($id);
            $data['code'] = 1;
        }
        echo json_encode($data);
    }
}
