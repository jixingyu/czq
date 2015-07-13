<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 * App config
 *
 * @author  Xy Ji
 */
class App_config extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('app_config_model');
    }

    public function about()
    {
        $data = array();
        $module = 'about';
        $result = $this->app_config_model->get_list(array('module' => $module));
        $about = array();
        foreach ($result as $value) {
            $k = $value['cf_key'];
            $about[$k] = $value['cf_value'];
        }

        $post = $this->input->post();
        if (!empty($post)) {
            $insert = $update = array();
            $trans = array(
                'introduction' => '关于我们的介绍',
                'phone' => '客服电话',
                'qq' => '客服QQ',
            );
            foreach (array('introduction', 'phone', 'qq') as $key) {
                if (empty($post[$key])) {
                    $data['error'] = '请填写' . $trans[$key];
                    break;
                }
                if (!isset($about[$key])) {
                    $insert[$key] = $post[$key];
                } elseif ($about[$key] != $post[$key]) {
                    $update[$key] = $post[$key];
                }
            }

            if (empty($data['error'])) {
                if (!empty($insert)) {
                    foreach ($insert as $key => $value) {
                        $this->app_config_model->insert(array(
                            'module' => $module,
                            'cf_key' => $key,
                            'cf_value' => $value,
                        ));
                    }
                }
                if (!empty($update)) {
                    foreach ($update as $key => $value) {
                        $this->app_config_model->update(array(
                            'cf_value' => $value,
                        ), array(
                            'module' => $module,
                            'cf_key' => $key,
                        ));
                    }
                }

                $data['status'] = 1;
            }
            $about = array_merge($about, $post);
        }
        $data['about'] = $about;
        $this->load->view('admin/about', $data);
    }
}
