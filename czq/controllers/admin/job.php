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
        $this->load->model('job_model');
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
        $data = array(
            'job' => array(
                'name' => '',
            ),
            'status' => 0,
        );

        if ($id) {
            $data['job'] = $this->job_model->get_one(array('id' => $id));
        }

        $post = $this->input->post();
        if (!empty($post)) {
            $data['job']['name'] = $post['name'];

            if (empty($post['name'])) {
                $data['error'] = '请填写公司名';
            }

            if (empty($data['error'])) {
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
