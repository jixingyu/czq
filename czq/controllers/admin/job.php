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

        $count = $this->job_model->get_count(array('is_deleted' => 0));
        if ($count) {
            $limit = $this->config->item('page_size');
            $data['job_list'] = $this->job_model->get_list(array('is_deleted' => 0), $limit, $offset);

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
                'district' => '',
                'company_id' => 0,
                'working_years' => '',
                'recuit_number' => 0,
                'job_type' => '',
                'benefit' => array(),
                'requirement' => '',
            ),
            'sel_degree' => false,
            'sel_salary' => false,
            'degree_list' => $this->config->item('degree', 'job'),
            'salary_list' => $this->config->item('salary', 'job'),
            'district_list' => $this->config->item('district', 'job'),
            'working_years_list' => $this->config->item('working_years', 'job'),
            'job_type_list' => $this->config->item('job_type', 'job'),
            'benefit_list' => $this->config->item('benefit', 'job'),
            'company_list' => $this->company_model->get_list(),
            'status' => 0,
        );

        if ($id) {
            $data['job'] = $this->job_model->get_one(array('id' => $id, 'is_deleted' => 0));
            if (!empty($data['job']['benefit'])) {
                $data['job']['benefit'] = json_decode($data['job']['benefit'], true);
            }
        }

        $post = $this->input->post();
        if (!empty($post)) {
            if ($post['degree'] == '-1') {
                $post['degree'] = $post['custom_degree'];
            }
            if ($post['salary'] == '-1') {
                $post['salary'] = $post['custom_salary'];
            }

            if (empty($post['name'])) {
                $data['error'] = '请填写职位名称';
            }
            $data['job'] = array_merge($data['job'], $post);

            if (empty($post['benefit'])) {
                $post['benefit'] = '';
            } else {
                $post['benefit'] = json_encode($post['benefit']);
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
        $job = $this->job_model->get_one(array('id' => $id, 'is_deleted' => 0));
        if (!empty($job)) {
            $this->job_model->update(array('is_deleted' => 1), array('id' => $id));
            $data['code'] = 1;
        }
        echo json_encode($data);
    }

    public function apply_list()
    {
        $this->load->model('apply_model');
        $offset = intval($this->input->get('o'));
        $data   = array(
            'apply_list' => array(),
        );

        $count = $this->apply_model->get_count();
        if ($count) {
            $limit = $this->config->item('page_size');
            $data['apply_list'] = $this->apply_model->apply_list_admin(array(), $limit, $offset);

            $this->load->library('pagination');

            $this->pagination->initialize_admin(array(
                'base_url'    => preg_replace('/(.*)(\?|&)o=.*/', '$1', site_url($this->input->server('REQUEST_URI'))),
                'total_rows'  => $count,
                'per_page'    => $limit,
                'page_query_string'    => true,
                'query_string_segment' => 'o'
            ));
        }

        $this->load->view('admin/apply_list', $data);
    }

    public function add_interview($apply_id = 0)
    {
        $this->load->model('interview_model');
        $address = $this->input->post('address');
        $interview_time = $this->input->post('interview_time');
        if (empty($address)) {
            echo json_encode(array(
                'code' => 0,
                'data' => '请填写面试地点',
            ));
            exit;
        }
        if (empty($interview_time)) {
            echo json_encode(array(
                'code' => 0,
                'data' => '请填写面试时间',
            ));
            exit;
        }
        if (!empty($apply_id)) {
            $apply = $this->interview_model->find($apply_id, 'apply_id');
            if (empty($apply)) {
                echo json_encode(array(
                    'code' => 0,
                    'data' => '参数出错'
                ));
                exit;
            }
        }
        $interview_time = strtotime($interview_time);

        if (empty($apply_id)) {
            $this->interview_model->insert(array(
                'apply_id' => $apply_id,
                'address' => $address,
                'interview_time' => $interview_time,
                'create_time' => time(),
                'update_time' => time(),
            ));
        } else {
            $this->interview_model->update(array(
                'address' => $address,
                'interview_time' => $interview_time,
                'update_time' => time(),
            ), array('apply_id' => $apply_id));
        }
        echo json_encode(array(
            'code' => 1,
        ));
        exit;
    }
}
