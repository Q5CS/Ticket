<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StaticPage extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }
    public function activate_succ()
    {
        $data['add_css'] = $data['add_js'] = array();
        $data['logged'] = $this->User_model->logged();
        $this->load->view('global/header', $data);
        $this->load->view('staticPage/activate_succ');
        $this->load->view('global/footer', $data);
    }
    public function activate_err()
    {
        $data['add_css'] = $data['add_js'] = array();
        $data['logged'] = $this->User_model->logged();
        $this->load->view('global/header', $data);
        $this->load->view('staticPage/activate_err', $data);
        $this->load->view('global/footer', $data);
    }
    public function reset_succ()
    {
        $data['add_css'] = $data['add_js'] = array();
        $data['logged'] = $this->User_model->logged();
        $this->load->view('global/header', $data);
        $this->load->view('staticPage/reset_succ');
        $this->load->view('global/footer', $data);
    }
    public function reset_err()
    {
        $data['add_css'] = $data['add_js'] = array();
        $data['logged'] = $this->User_model->logged();
        $this->load->view('global/header', $data);
        $this->load->view('staticPage/reset_err', $data);
        $this->load->view('global/footer', $data);
    }
}
