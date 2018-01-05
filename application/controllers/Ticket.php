<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ticket extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Ticket_model');
        $this->load->config('recaptcha');
        $this->load->library('recaptcha');
        if (!$this->ion_auth->logged_in()) {
            redirect('/');
        }
    }

    public function book()
    {
        $name = $this->input->post('name');
        $phone = $this->input->post('phone');
        $recaptcha = $this->input->post('g-recaptcha-response');
        /*
                if (!empty($recaptcha)) {
                    $response = $this->recaptcha->verifyResponse($recaptcha);
                    if (!isset($response['success']) || $response['success'] !== true) {
                        $data = array(
                            'status' => '-1',
                            'msg' => '验证失败，请重试！'
                        );
                        echo json_encode($data);
                        return;
                    }
                } else {
                    $data = array(
                        'status' => '-1',
                        'msg' => '请点击验证码！'
                    );
                    echo json_encode($data);
                    return;
                }
        */
        //check if can book
        $userinfo = $this->User_model->userinfo();
        $uid = $userinfo['id'];
        $stu = $this->User_model->authed();

        $can = $this->Ticket_model->canbook($uid, $phone, $stu);
        if ($can['status'] == 1) {
            $result = $this->Ticket_model->book($uid, $name, $phone);
            echo json_encode($result);
            if ($result['status'] == 1 && $this->config->item('sendSMS')) {
                fastcgi_finish_request();
                $this->Ticket_model->sendSMS($phone);
            }
        } else {
            echo json_encode($can);
            return;
        }
    }

    public function getImg($id)
    {
        $this->Ticket_model->getImg($id);
    }
}
