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
        $this->load->model(array('resume_model', 'member_model'));
    }

    public function view($id)
    {
        $resume = $this->resume_model->find($id);
        if (empty($resume)) {
            show_404();
        }
        $member = $this->member_model->find($resume['user_id'], 'user_id');

        $this->load->view('admin/view_resume', array(
            'resume' => $resume,
            'member' => $member,
        ));
    }

    public function user_resume_list($user_id)
    {
        $this->load->view('admin/user_resume', array(
            'user_resume' => $this->resume_model->get_list(array('user_id' => $user_id)),
            'member' => $this->member_model->find($user_id, 'user_id'),
        ));
    }
}
