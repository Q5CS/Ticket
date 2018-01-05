<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('User_model');
        $this->load->model('Ticket_model');
        $this->load->model('Admin_model');
        if (!$this->ion_auth->is_admin()) {
            redirect('/');
        }
    }

    public function setting()
    {
        $data['add_css'] = array();
        $data['add_js'] = array('laydate/laydate.js', 'admin/setting.js');
        $data['logged'] = $this->ion_auth->logged_in();
        $data['user'] = $this->User_model->userinfo();
        $data["noReg"] = $this->Admin_model->getSetting('noReg') == 1 ? true : false;
        $data["alltnum"] = $this->Admin_model->getSetting('alltnum');
        $data["starttime"] = $this->Admin_model->getSetting('starttime');
        $data["starttime_stu"] = $this->Admin_model->getSetting('starttime_stu');
        $data["finaltime"] = $this->Admin_model->getSetting('finaltime');
        $data["finaltime_stu"] = $this->Admin_model->getSetting('finaltime_stu');
        $data["pertnum"] = $this->Admin_model->getSetting('pertnum');
        $data["pertnum_stu"] = $this->Admin_model->getSetting('pertnum_stu');
        $data["notice"] = $this->Admin_model->getSetting('notice');
        $this->load->view('global/header', $data);
        $this->load->view('admin/setting', $data);
        $this->load->view('global/footer', $data);
    }

    public function enter()
    {
        $data['add_css'] = array('notie.min.css');
        $data['add_js'] = array('notie.min.js', 'admin/instascan.min.js?v=2', 'admin/enter.js');
        $data['logged'] = $this->User_model->logged();
        $data['user'] = $this->User_model->userinfo();

        $this->load->view('global/header', $data);
        $this->load->view('admin/enter', $data);
        $this->load->view('global/footer', $data);
    }

    public function users($uid = 0)
    {
        $data['add_css'] = array();
        $data['add_js'] = array('admin/users.js');
        $data['logged'] = $this->User_model->logged();
        $data['user'] = $this->User_model->userinfo();
        $data['uid'] = $uid;
        $data['groups'] = $this->User_model->get_user_groups();
        
        $this->load->view('global/header', $data);
        $this->load->view('admin/users', $data);
        $this->load->view('global/footer', $data);
    }

    public function tickets($uid = 0)
    {
        $data['add_css'] = array();
        $data['add_js'] = array('admin/tickets.js?v=4');
        $data['logged'] = $this->User_model->logged();
        $data['user'] = $this->User_model->userinfo();
        $data['uid'] = $uid;
        
        $this->load->view('global/header', $data);
        $this->load->view('admin/tickets', $data);
        $this->load->view('global/footer', $data);
    }
    
    public function data()
    {
        $data['add_css'] = array();
        $data['add_js'] = array('admin/data.js');
        $data['logged'] = $this->User_model->logged();
        $data['user'] = $this->User_model->userinfo();
        $data['data'] = $this->Admin_model->getData();

        $this->load->view('global/header', $data);
        $this->load->view('admin/data', $data);
        $this->load->view('global/footer', $data);
    }

    public function printTicket()
    {
        echo json_encode($this->Admin_model->printTicket(), JSON_UNESCAPED_SLASHES);
    }

    //apis
    public function api_enter()
    {
        $num = $this->input->post('num');
        $code = $this->input->post('code');
        echo json_encode($this->Admin_model->enter($num, $code));
    }

    public function api_get_groups()
    {
        echo json_encode($this->User_model->get_user_groups());
    }

    public function api_search_users()
    {
        $key = $this->input->post('key');
        $value = $this->input->post('value');
        echo json_encode($this->Admin_model->search_users($key, $value));
    }

    public function api_user_info()
    {
        $id = $this->input->post('id');
        echo json_encode($this->User_model->userinfo($id));
    }
    
    public function api_user_info_by_email()
    {
        $email = $this->input->post('email');
        echo json_encode($this->User_model->userinfo_by_email($email));
    }
    
    public function api_user_update()
    {
        $data = $this->input->post();
        foreach ($data as &$value) {
            if ($value == '') {
                $value = null;
            }
        }
        $data['groups'] = explode(',', $data['groups']);
        echo json_encode($this->Admin_model->user_info_update($data));
    }

    public function api_user_changepw()
    {
        $id = $this->input->post('id');
        $new_pw = $this->input->post('new_pw');
        echo json_encode($this->Admin_model->user_change_passwd($id, $new_pw));
    }

    public function api_add_auth()
    {
        $id = $this->input->post('id');
        echo json_encode($this->Admin_model->add_auth($id));
    }

    public function api_remove_auth()
    {
        $id = $this->input->post('id');
        echo json_encode($this->Admin_model->remove_auth($id));
    }
    
    public function api_search_tickets()
    {
        $key = $this->input->post('key');
        $value = $this->input->post('value');
        echo json_encode($this->Admin_model->search_tickets($key, $value));
    }

    public function api_ticket_info()
    {
        $id = $this->input->post('id');
        echo json_encode($this->Ticket_model->ticketinfo($id));
    }

    public function api_ticket_update()
    {
        $data = $this->input->post();
        echo json_encode($this->Admin_model->ticket_info_update($data));
    }

    public function api_ticket_add()
    {
        $email = $this->input->post('email');
        $name = $this->input->post('name');
        $phone = $this->input->post('phone');
        $type = $this->input->post('type');
        $class = $this->input->post('class');
        $sms = ($this->input->post('sms') == '1') ? true : false;
        $force = ($this->input->post('force') == '1') ? true : false;

        echo json_encode($this->Admin_model->addTicket($email, $name, $phone, $type, $class, $sms, $force));
    }

    public function api_get_data()
    {
        echo json_encode($this->Admin_model->getData());
    }

    public function api_update_setting()
    {
        $data["noReg"] = $this->input->post('noReg');
        $data["alltnum"] = $this->input->post('alltnum');
        $data["starttime"] = $this->input->post('starttime');
        $data["starttime_stu"] = $this->input->post('starttime_stu');
        $data["finaltime"] = $this->input->post('finaltime');
        $data["finaltime_stu"] = $this->input->post('finaltime_stu');
        $data["pertnum"] = $this->input->post('pertnum');
        $data["pertnum_stu"] = $this->input->post('pertnum_stu');
        $data["notice"] = $this->input->post('notice');
        foreach ($data as $key => $value) {
            if (!$this->Admin_model->updateSetting($key, $value)) {
                $returndata = array(
                    'status' => -1,
                    'msg' => '设置修改失败'
                );
                echo json_encode($returndata);
                return;
            }
        }
        $returndata = array(
            'status' => 1,
            'msg' => '设置修改成功'
        );
        echo json_encode($returndata);
    }
}
