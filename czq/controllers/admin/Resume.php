<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 * Resume
 *
 * @author  Xy Ji
 */
class Resume extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('resume_model', 'member_model', 'work_experience_model'));
    }

    public function view($id)
    {
        $resume = $this->resume_model->find($id);
        if (empty($resume)) {
            show_404();
        }

        $this->load->view('admin/view_resume', array(
            'resume' => $resume,
            'member' => $this->member_model->find($resume['user_id'], 'user_id'),
            'experiences' => $this->work_experience_model->get_list(array('resume_id' => $id), false, false, 'start_time', 'asc')
        ));
    }

    public function userResume($user_id)
    {
        $this->load->view('admin/user_resume', array(
            'user_resume' => $this->resume_model->get_list(array('user_id' => $user_id), false, false, 'update_time'),
            'member' => $this->member_model->find($user_id, 'user_id'),
        ));
    }
}
