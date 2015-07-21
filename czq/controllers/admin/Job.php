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

    public function view($id)
    {
        $data = $this->job_model->get_one(array('id' => $id, 'is_deleted' => 0));
        if (empty($data)) {
            show_404();
        }
        $company = $this->company_model->get_one(array(
            'id' => $data['company_id'],
            'is_deleted' => 0,
        ));
        $data['company'] = $company['name'];
        if (!empty($data['benefit'])) {
            $data['benefit'] = json_decode($data['benefit'], true);
        }

        $this->load->view('admin/view_job', $data);
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
                'recruit_number' => 0,
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
            'company_list' => $this->company_model->get_list(array('is_deleted' => 0)),
            'status' => 0,
        );
        array_shift($data['district_list']);

        if ($id) {
            $data['job'] = $this->job_model->get_one(array('id' => $id, 'is_deleted' => 0));
            if (empty($data['job'])) {
                show_404();
            }
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

    public function interview_list()
    {
        $this->load->model('interview_model');
        $offset = intval($this->input->get('o'));
        $data   = array(
            'interview_list' => array(),
        );

        $count = $this->interview_model->get_count();
        if ($count) {
            $limit = $this->config->item('page_size');
            $data['interview_list'] = $this->interview_model->interview_list_admin(array(), $limit, $offset);

            $this->load->library('pagination');

            $this->pagination->initialize_admin(array(
                'base_url'    => preg_replace('/(.*)(\?|&)o=.*/', '$1', site_url($this->input->server('REQUEST_URI'))),
                'total_rows'  => $count,
                'per_page'    => $limit,
                'page_query_string'    => true,
                'query_string_segment' => 'o'
            ));
        }

        $this->load->view('admin/interview_list', $data);
    }

    public function editInterview($apply_id)
    {
        $this->load->model(array('apply_model', 'interview_model'));
        $apply = $this->apply_model->find($apply_id);
        if (empty($apply)) {
            $data['error'] = '参数出错！';
        } else {
            $interview = $this->interview_model->find($apply_id, 'apply_id');
            $data = array(
                'edit_interview' => empty($interview) ? false : true,
                'apply_id' => $apply_id,
                'interview' => $interview ?: array(),
                'status' => 0,
            );

            $post = $this->input->post();
            if (!empty($post)) {
                if (empty($post['interview_time'])) {
                    $data['error'] = '请填写面试时间';
                } elseif (empty($post['address'])) {
                    $data['error'] = '请填写面试地点';
                } else {
                    $post['interview_time'] = strtotime($post['interview_time']);
                    if (empty($interview)) {
                        $this->interview_model->insert(array(
                            'apply_id' => $apply_id,
                            'address' => $post['address'],
                            'interview_time' => $post['interview_time'],
                            'create_time' => time(),
                            'update_time' => time(),
                        ));
                        $this->apply_model->update(array(
                            'status' => 1,
                            'update_time' => time(),
                        ), array('id' => $apply_id));
                    } else {
                        $this->interview_model->update(array(
                            'address' => $post['address'],
                            'interview_time' => $post['interview_time'],
                            'update_time' => time(),
                        ), array('apply_id' => $apply_id));
                    }

                    $data['status'] = 1;
                }
                $data['interview'] = array_merge($data['interview'], $post);
            }
            $this->load->view('admin/interview', $data);
        }
    }
}
