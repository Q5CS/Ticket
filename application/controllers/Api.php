<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Ticket_model');
        $this->load->library('gravatar');
    }

    private function getURL($email)
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('/');
        }
        return $this->gravatar->get($email);
    }
    
    public function gravatar($email)
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('/');
        }
        redirect($this->getURL($email));
    }

    public function qrcode($data)
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('/');
        }
        $this->load->library('ciqrcode');
        
        header("Content-Type: image/png");
        $params['data'] = $data;
        $params['level'] = 'H';
        $params['size'] = 1024;
        $this->ciqrcode->generate($params);
    }
    
    public function getData()
    {
        $this->load->model('Admin_model');
        echo json_encode($this->Admin_model->getData());
    }

    /* 抽奖用，输出用户信息 */
    public function getTicketAward()
    {
        $key = $this->input->get("key"); //防泄露
        if ($key != "qz5z2019") {
            echo "key err";
            return;
        }
        echo json_encode($this->Ticket_model->getTicketAward());
    }
}
