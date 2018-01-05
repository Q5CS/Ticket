<?php
defined('BASEPATH') or exit('No direct script access allowed');

class user extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('User_model');
        $this->load->config('recaptcha');
        $this->load->library('recaptcha');
    }
    public function need_captcha()
    {
        $id = $this->input->post('id');
        if ($this->ion_auth->is_max_login_attempts_exceeded($id)) {
            $data = array(
                'status' => '1',
                'msg' => '需要验证！'
            );
            echo json_encode($data);
        } else {
            $data = array(
                'status' => '0',
                'msg' => '无需验证！'
            );
            echo json_encode($data);
        }
    }
    public function login()
    {
        $id = $this->input->post('id');
        $pw = $this->input->post('pw');
        $rm = $this->input->post('rm');
        if ($rm == 'true') {
            $rm = true;
        } else {
            $rm = false;
        }
        //check captcha when an IP failed too many times on login
        if ($this->ion_auth->is_max_login_attempts_exceeded($id)) {
            $recaptcha = $this->input->post('g-recaptcha-response');
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
                    'status' => '-2',
                    'msg' => '请点击验证码！'
                );
                echo json_encode($data);
                return;
            }
        }

        echo json_encode($this->User_model->login($id, $pw, $rm));
    }
    public function logout()
    {
        echo json_encode($this->User_model->logout());
    }
    public function register()
    {
        $email = $this->input->post('email');
        $phone = $this->input->post('phone');
        $pw = $this->input->post('pw');
        $un = $this->input->post('un');
        $code = $this->input->post('code');

        $this->load->library('session');
        if (isset($_SESSION['LAST_CALL'])) {
            $last = strtotime($_SESSION['LAST_CALL']);
            $curr = strtotime(date("Y-m-d h:i:s"));
            $sec =  abs($last - $curr);
            if ($sec < 6) {
                $t = 8-$sec;
                $data = array(
                    'status' => '-1',
                    'msg' => "请求频率过高，请过 $t 秒再试！"
                );
                return $data;
            }
        }
        $_SESSION['LAST_CALL'] = date("Y-m-d h:i:s");

        echo json_encode($this->User_model->register($email, $pw, $un, $phone, $code));
    }
    public function info()
    {
        echo json_encode($this->User_model->userinfo());
    }
    public function cpw()
    {
        $old_pw = $this->input->post('old_pw');
        $new_pw = $this->input->post('new_pw');
        echo json_encode($this->User_model->change_password($old_pw, $new_pw));
    }

    public function auth()
    {
        $code = $this->input->post("code");
        $t = $this->User_model->getUserToken($code);
        if ($t[0] < 0) {
            echo json_encode(['status' => -1, 'msg' => $t[1]]);
            return;
        }
        $token = $t[1]->access_token;
        //然后拿着token要用户数据
        $t = $this->User_model->getUserData($token);
        echo json_encode($t);
    }

    public function auth2()
    {
        $this->load->library('session');
        if (isset($_SESSION['LAST_CALL'])) {
            $last = strtotime($_SESSION['LAST_CALL']);
            $curr = strtotime(date("Y-m-d h:i:s"));
            $sec =  abs($last - $curr);
            if ($sec <= 5) {
                $data = array(
                    'status' => '-1',
                    'msg' => '请求频率过高，请过 5 秒再试！'
                );
                echo json_encode($data);
                return;
            }
        }
        $_SESSION['LAST_CALL'] = date("Y-m-d h:i:s");
        // $njid = $this->input->post('njid');
        $name = $this->input->post('name');
        $pwd = $this->input->post('pwd');
        $name = urldecode($name);
        $pwd = urldecode($pwd);
        // echo json_encode($this->User_model->auth($njid,$name,$pwd));
        echo json_encode($this->User_model->auth($name, $pwd));
    }
    public function can_not_login()
    {
        $email = $this->input->post('id');
        $recaptcha = $this->input->post('g-recaptcha-response');

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

        $identity = $this->ion_auth->where('email', $email)->users()->row();
        if (!$identity) {
            $data = array(
                'status' => '-1',
                'msg' => '未找到相应账号！请联系管理员处理！'
            );
            echo json_encode($data);
            return;
        }

        $id = $identity->id;
        $active = $identity->active;

        if ($active) {
            //forgot pwd
            echo json_encode($this->User_model->forgot_pwd($email));
        } else {
            //re_active
            echo json_encode($this->ion_auth->re_active($email));
        }
    }

    public function forgot_pwd()
    {
        $id = $this->input->post('id');
        echo json_encode($this->User_model->forgot_pwd($id));
    }
    public function reset_pwd()
    {
        $code = $this->input->post('code');
        $pw = $this->input->post('pw');
        echo json_encode($this->User_model->reset_pwd($code, $pw));
    }
    public function re_active()
    {
        $email = $this->input->post('email');
        echo json_encode($this->ion_auth->re_active($email));
    }

    public function sms_auth()
    {
        $phone = $this->input->post('phone');
        $recaptcha = $this->input->post('g-recaptcha-response');
        
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

        echo json_encode($this->User_model->sms_auth($phone));
    }
}
