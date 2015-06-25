<?php

/**
 * The base controller which is used by the Front and the Admin controllers
 */
class Base_Controller extends CI_Controller
{

    public function __construct()
    {

        parent::__construct();

        $this->load->helper('url');

    }//end __construct()

}//end Base_Controller

class Front_Controller extends Base_Controller
{
    private $meta = array();
    private $title = '';

    public function __construct()
    {
        parent::__construct();
        //load libraries
        $this->load->library(array('session','user_lib'));
    }

    /**
     * This works exactly like the regular $this->load->view()
     * The difference is it automatically pulls in a header and footer.
     */
    public function view($view, $vars = array(), $string = false)
    {
        if ($string) {
            $result	= $this->load->view($view, $vars, true);
            return $result;
        } else {
            $this->load->view($view, $vars);
        }
    }

    public function head_meta($meta)
    {
        if (isset($meta['name'])) {
            $meta = array($meta);
        }
        foreach ($meta as $row) {
            $row['content'] = strip_tags($row['content']);
            if ('description' == $row['name'] && mb_strlen($row['content']) > 100) {
                $row['content'] = mb_substr($row['content'], 0, 100);
            }
            array_push($this->meta, $row);
        }
    }

    public function head_title($title)
    {
        $this->title = $title;
    }

}

class Admin_Controller extends Base_Controller
{
    public function __construct()
    {

        parent::__construct();

        $this->load->library('auth');
        $this->auth->is_logged_in(uri_string());
    }

    public function view($view, $vars = array(), $string = false)
    {
        if ($string) {
            $result = $this->load->view($view, $vars, true);
            return $result;
        } else {
            $this->load->view($view, $vars);
        }
    }
}

// Api_Controller
require(APPPATH.'/libraries/REST_Controller.php');
class Api_Controller extends REST_Controller
{
    public function __construct()
    {

        parent::__construct();

        $this->load->library(array('session','user_lib'));

    }
}

// App Controller
class App_Controller extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();

        // $this->load->library('user_agent');
        // if (!$this->agent->is_mobile()) {
        // if (!$this->agent->is_browser()) { /* for test */
            // show_error('Api Inaccessible.', 500); // cloud only access with mobile
        // }

    }
}
