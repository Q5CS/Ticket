<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Ticket_model');
        $this->load->model('Admin_model');
        $this->load->library('gravatar');
    }

    public function index()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('/main/login');
        }
        $data['add_css'] = array();
        $data['add_js'] = array('main.js');
        $data['logged'] = $this->User_model->logged();
        $data['user'] = $this->User_model->userinfo();
        $data['authed'] = $this->User_model->authed();

        if ($data['authed']) {
            $data['startTime'] = $this->Admin_model->getSetting('starttime_stu');
            $data['endTime'] = $this->Admin_model->getSetting('finaltime_stu');
            $data['num'] = $this->Admin_model->getSetting('pertnum_stu');
        } else {
            $data['startTime'] = $this->Admin_model->getSetting('starttime');
            $data['endTime'] = $this->Admin_model->getSetting('finaltime');
            $data['num'] = $this->Admin_model->getSetting('pertnum');
        }
        
        // 获取剩余门票并计算百分比
        $remain = $this->Admin_model->getSetting('alltnum') - $this->Ticket_model->count();
        $data['remainPercent'] = round((double)$remain / $this->Admin_model->getSetting('alltnum'), 4) * 100;

        // 通知信息
        $data["notice"] = $this->Admin_model->getSetting('notice');

        // 获取认证用户可以提前取票的时间差
        $time_stu = strtotime($this->Admin_model->getSetting('starttime_stu'));
        $time_normal = strtotime($this->Admin_model->getSetting('starttime'));
        $data["delta_days"] = round(($time_normal - $time_stu)/3600/24);

        $this->load->view('global/header', $data);
        $this->load->view('main/main', $data);
        $this->load->view('global/footer', $data);
    }

    public function login()
    {
        if ($this->ion_auth->logged_in()) {
            redirect('/');
        }
        $data['add_css'] = array();
        $data['add_js'] = array('login.js');
        $data['logged'] = $this->User_model->logged();
        $data['retaptcha_sitekey'] = $this->config->item('recaptcha_sitekey');
        $this->load->view('global/header', $data);
        $this->load->view('main/login', $data);
        $this->load->view('global/footer', $data);
    }

    public function profile()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('/main/login');
        }
        $data['add_css'] = array();
        $data['add_js'] = array('profile.js');
        $data['logged'] = $this->User_model->logged();
        $data['user'] = $this->User_model->userinfo();
        $this->load->view('global/header', $data);
        $this->load->view('main/profile');
        $this->load->view('global/footer', $data);
    }

    public function authenticate()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('/main/login');
        }
        $data['add_css'] = array();
        $data['add_js'] = array('authenticate.js');
        $data['logged'] = $this->User_model->logged();
        $data['user'] = $this->User_model->userinfo();
        $data['authed'] = $this->User_model->authed();
        $data['auth_link'] = "https://open.qz5z.ren/oauth2/authorize?response_type=code&client_id=". $this->config->item("qz5z_clientid") ."&state=auth&scope=phone";
        $this->load->view('global/header', $data);
        $this->load->view('main/authenticate', $data);
        $this->load->view('global/footer', $data);
    }

    public function auth_callback()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('/main/login');
        }
        $data['add_css'] = array();
        $data['add_js'] = array('auth_callback.js');
        $data['logged'] = $this->User_model->logged();
        $data['user'] = $this->User_model->userinfo();
        $data['authed'] = $this->User_model->authed();
        $this->load->view('global/header', $data);
        $this->load->view('main/auth_callback', $data);
        $this->load->view('global/footer', $data);
    }

    public function register()
    {
        $noReg = $this->Admin_model->getSetting('noReg') == 1 ? true : false;
        
        if ($this->ion_auth->logged_in()) {
            redirect('/');
        }
        
        $data['add_css'] = array();
        $data['add_js'] = array('register.js');
        $data['logged'] = $this->User_model->logged();
        $data['retaptcha_sitekey'] = $this->config->item('recaptcha_sitekey');
        $this->load->view('global/header', $data);
        if (!$noReg) {
            $this->load->view('main/register', $data);
        } else {
            $this->load->view('staticPage/no_reg');
        }
        $this->load->view('global/footer', $data);
    }

    public function forgot_pwd($id = '')
    {
        if ($this->ion_auth->logged_in()) {
            redirect('/main/profile');
        }
        $data['add_css'] = array();
        $data['add_js'] = array('forgot_pwd.js');
        $data['logged'] = $this->User_model->logged();
        $data['id'] = $this->security->xss_clean($id);
        $data['retaptcha_sitekey'] = $this->config->item('recaptcha_sitekey');
        $this->load->view('global/header', $data);
        $this->load->view('main/forgot_pwd', $data);
        $this->load->view('global/footer', $data);
    }

    public function reset_pwd($code)
    {
        if ($this->ion_auth->logged_in()) {
            redirect('/main/profile');
        }
        $data['add_css'] = array();
        $data['add_js'] = array('reset_pwd.js');
        $data['logged'] = $this->User_model->logged();
        $data['code'] = $code;
        $this->load->view('global/header', $data);
        $this->load->view('main/reset_pwd');
        $this->load->view('global/footer', $data);
    }

    public function book()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('/main/login');
        }
        $data['add_css'] = array();
        $data['add_js'] = array('ticket_book.js?v=2');
        $data['logged'] = $this->User_model->logged();
        $data['user'] = $this->User_model->userinfo();

        $bookedNum = $this->Ticket_model->bookedNum($data['user']['id']);
        $stu = $this->User_model->authed();

        $this->load->view('global/header', $data);

        date_default_timezone_set("Asia/Shanghai");
        $now = time();
        $startTime=$endTime=$now;
        if ($stu) {
            $startTime = strtotime($this->Admin_model->getSetting('starttime_stu'));
            $endTime = strtotime($this->Admin_model->getSetting('finaltime_stu'));
        } else {
            $startTime = strtotime($this->Admin_model->getSetting('starttime'));
            $endTime = strtotime($this->Admin_model->getSetting('finaltime'));
        }

        if (($stu && $bookedNum >= $this->Admin_model->getSetting('pertnum_stu')) || (!$stu && $bookedNum >= $this->Admin_model->getSetting('pertnum'))) {
            $this->load->view('staticPage/booked');
        } elseif ($now<$startTime || $now>$endTime) {
            $this->load->view('staticPage/book_time_err');
        } elseif ($this->Admin_model->getSetting('alltnum') - $this->Ticket_model->count() <= 0) {
            $this->load->view('staticPage/book_no_remain');
        } else {
            $this->load->view('ticket/book');
        }

        $this->load->view('global/footer', $data);
    }

    public function myTicket()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('/main/login');
        }
        $data['add_css'] = array();
        $data['add_js'] = array('myTicket.js');
        $data['logged'] = $this->User_model->logged();
        $data['user'] = $this->User_model->userinfo();
        $data['ticket'] = array_reverse($this->Ticket_model->getTicket($data['user']['id'])); //反过来，先预约的先输出

        $this->load->view('global/header', $data);
        $this->load->view('ticket/myTicket', $data);
        $this->load->view('global/footer', $data);
    }
}
