<?php
class User_model extends CI_Model
{
    public function __construct()
    {
        $this->load->library('ion_auth');
        $this->load->model('Log_model');
    }
    
    public function logged()
    {
        return $this->ion_auth->logged_in();
    }

    public function login($id, $pw, $rm)
    {
        if ($this->ion_auth->login($id, $pw, $rm)) {
            $data = array(
                'status' => '1',
                'msg' => '登录成功'
            );
            $this->Log_model->add_log(2, $id);
        } else {
            $data = array(
                'status' => '-1',
                'msg' => '登录失败，可能是账号或密码错误！'
            );
        }
        return $data;
    }

    public function logout()
    {
        $this->ion_auth->logout();
        $data = array(
            'status' => '1',
            'msg' => '注销成功！'
        );
        return $data;
    }

    public function register($email, $pw, $un, $phone, $code)
    {
        $email = $this->security->xss_clean($email);
        $un = $this->security->xss_clean($un);
        $phone = $this->security->xss_clean($phone);
        
        $additional_data = array(
            'phone' => $phone
        );
        $group = array('2'); // Sets user to group "member".
        if (strlen($pw)<6 || strlen($pw)>20) {
            $data = array(
                'status' => '-1',
                'msg' => '密码必须为6-20位的数字和字母的组合'
            );
            return $data;
        }
        if ($this->ion_auth->username_check($un)) {
            $data = array(
                'status' => '-1',
                'msg' => '用户名已存在，请更换用户名注册'
            );
            return $data;
        }
        if ($this->ion_auth->email_check($email)) {
            $data = array(
                'status' => '-1',
                'msg' => '邮箱地址已存在，请更换邮箱注册'
            );
            return $data;
        }

        $this->db->where('phone', $phone);
        if ($this->db->count_all_results('users') > 0) {
            $data = array(
                'status' => -1,
                'msg' => '手机号已存在，请更换手机号注册'
            );
            return $data;
        }
        
        if ($this->session->sms_verify_code != $code) {
            $data = array(
                'status' => -1,
                'msg' => '手机验证码错误！'
            );
            return $data;
        }

        if ($this->ion_auth->register($un, $pw, $email, $additional_data, $group) != false) {
            $this->Log_model->add_log(0, $email, null, null, $phone);
            $data = array(
                'status' => '1',
                //'msg' => '系统已经发送了一封激活邮件到您的邮箱，请进入邮箱查收并激活！'
                'msg' => '注册成功，请登录！'
            );
            $this->session->sms_verify_code = mt_rand(100000, 999999);
        } else {
            $data = array(
                'status' => '-1',
                'msg' => '注册失败，请检查注册信息是否正确！'
            );
        }
        return $data;
    }
    public function userinfo($id = null)
    {
        date_default_timezone_set("Asia/Shanghai");
        $user = null;
        
        if (is_null($id)) {
            $user = $this->ion_auth->user()->row();
        } else {
            $user = $this->ion_auth->where('id', $id)->users()->row();
        }
        
        $data = array(
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'created_on' => date('Y-m-d H:i:s', $user->created_on),
            'last_login' => date('Y-m-d H:i:s', $user->last_login),
            'phone' => $user->phone,
            'uid' => $user->uid,
            'name' => $user->name,
            'grade' => $user->grade,
            'class' => $user->class,
            'number' => $user->number,
            'admin' => $this->ion_auth->is_admin(),
            'authed' => $this->User_model->authed($user->id),
            'groups' => $this->ion_auth->get_users_groups($user->id)->result()
        );
        return $data;
    }
    
    public function userinfo_by_email($id = null)
    {
        date_default_timezone_set("Asia/Shanghai");
        $user = null;

        $user = $this->ion_auth->where('email', $id)->users()->row();
        
        $data = array(
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'created_on' => date('Y-m-d H:i:s', $user->created_on),
            'last_login' => date('Y-m-d H:i:s', $user->last_login),
            'phone' => $user->phone,
            'uid' => $user->uid,
            'name' => $user->name,
            'grade' => $user->grade,
            'class' => $user->class,
            'number' => $user->number,
            'admin' => $this->ion_auth->is_admin(),
            'authed' => $this->User_model->authed($user->id),
            'groups' => $this->ion_auth->get_users_groups($user->id)->result()

        );
        return $data;
    }
    
    public function get_user_groups()
    {
        return $this->ion_auth->groups()->result();
    }

    public function getUserToken($code)
    {
        $this->load->library('qz5z_oauth');
        $client_id = "2019";
        $client_secret = $this->config->item("qz5z_secret");
        $redirect_uri = $this->config->item("base_url") . "main/auth_callback";
        $grant_type = "authorization_code";
        $scope = "phone";
        $t = $this->qz5z_oauth->getUserToken($code, $client_id, $client_secret, $redirect_uri, $grant_type, $scope);
        if (isset($t->error)) {
            return [-1,$t];
        }
        return [1,$t];
    }

    /**
     * 对象 转 数组
     *
     * @param object $obj 对象
     * @return array
     */
    private function object_to_array($obj)
    {
        $obj = (array)$obj;
        foreach ($obj as $k => $v) {
            if (gettype($v) == 'resource') {
                return;
            }
            if (gettype($v) == 'object' || gettype($v) == 'array') {
                $obj[$k] = (array)object_to_array($v);
            }
        }
    
        return $obj;
    }

    public function getUserData($token)
    {
        $this->load->library('qz5z_oauth');
        $t = $this->qz5z_oauth->getUserData($token, "phone");
        $t = $this->object_to_array($t);
        return $this->log_user_auth($t);
    }

    private function log_user_auth($userdata)
    {
        if ($this->ion_auth->where('uid', $userdata['uid'])->users()->row() != null) {
            return array(
                'status' => '-1',
                'uid' => $userdata['uid'],
                'msg' => '认证失败，该校园网账号已被其他用户绑定！'
            );
        }
        $identity = $this->ion_auth->user()->row()->id;
        if ($this->ion_auth->update($identity, $userdata)) {
            $this->ion_auth->add_to_group(3, $identity);
            $this->Log_model->add_log(3, $userdata['uid']);
            return array(
                'status' => '1',
                'msg' => '认证成功！'
            );
        } else {
            return array(
                'status' => '-1',
                'msg' => '认证失败，请联系管理员！'
            );
        }
    }
    public function authed($id = null)
    {
        if ($id == null) {
            return $this->ion_auth->in_group(3);
        } else {
            return $this->ion_auth->in_group(3, $id);
        }
    }
    public function change_password($old_pw, $new_pw)
    {
        if ((strlen($new_pw)<6) || (strlen($new_pw)>20)) {
            $data = array(
                'status' => '-1',
                'msg' => '更改密码失败，密码必须为 6-20 位的数字和字母的组合'
            );
        } else {
            $identity = $this->session->userdata('identity');
            $change = $this->ion_auth->change_password($identity, $old_pw, $new_pw);
            if ($change) {
                //if the password was successfully changed
                $this->Log_model->add_log(5, $identity, 1);
                $data = array(
                    'status' => '1',
                    'msg' => '更改密码成功，请重新登录'
                );
                $this->logout();
            } else {
                $data = array(
                    'status' => '-1',
                    'msg' => '更改密码失败，请检查旧密码是否正确！'
                );
            }
        }
        return $data;
    }
    public function forgot_pwd($id)
    {
        $identity_column = $this->config->item('identity', 'ion_auth');
        //var_dump($id);
        $identity = $this->ion_auth->where($identity_column, $id)->users()->row();
        //var_dump($identity);
        if (empty($identity)) {
            $data = array(
                'status' => '-1',
                'msg' => '更改密码失败，未找到对应的账号！'
            );
            return $data;
        }

        // run the forgotten password method to email an activation code to the user
        $forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

        if ($forgotten) {
            // if there were no errors
            $data = array(
                'status' => '1',
                'msg' => '一封邮件已发送到您的注册邮箱，请进入邮箱进行下一步操作！'
            );
            return $data;
        } else {
            $data = array(
                'status' => '-1',
                'msg' => '更改密码失败！未知错误，请联系管理员处理！'
            );
            return $data;
        }
    }

    public function reset_pwd($code, $pw)
    {
        $user = $this->ion_auth->forgotten_password_check($code);
        if (!$user) {
            $data = array(
                'status' => '-1',
                'msg' => '更改密码失败！校验码无效！'
            );
            return $data;
        }
        // finally change the password
        $identity = $user->{$this->config->item('identity', 'ion_auth')};
        
        $change = $this->ion_auth->reset_password($identity, $pw);

        if ($change) {
            $this->Log_model->add_log(5, $identity, 2);
            $data = array(
                'status' => '1',
                'msg' => '更改密码成功！请使用新密码登录！'
            );
            return $data;
        } else {
            $data = array(
                'status' => '-1',
                'msg' => '更改密码失败！请联系管理员处理！'
            );
            return $data;
        }
    }

    public function sms_auth($phone)
    {
        $this->db->where('phone', $phone);
        if ($this->db->count_all_results('users')) {
            $returndata = array(
                'status' => -1,
                'msg' => '该手机号已被注册！'
            );
            return $returndata;
        }

        $this->load->library('session');
        if (isset($_SESSION['LAST_CALL'])) {
            $last = strtotime($_SESSION['LAST_CALL']);
            $curr = strtotime(date("Y-m-d h:i:s"));
            $sec =  abs($last - $curr);
            if ($sec < 55) {
                $t = 60-$sec;
                $data = array(
                    'status' => '-1',
                    'msg' => "请求频率过高，请过 $t 秒再试！"
                );
                return $data;
            }
        }
        $_SESSION['LAST_CALL'] = date("Y-m-d h:i:s");

        $this->load->model('SMS_model');
        $this->session->sms_verify_code = mt_rand(100000, 999999);

        $arr = array(
            'code' => $this->session->sms_verify_code
        );
        $this->SMS_model->sendSms($this->config->item('aliyun_smssign'), $this->config->item('aliyun_smsid_reg'), $phone, $arr);
        $this->Log_model->add_log(16, $phone);

        $data = array(
            'status' => '1',
            'msg' => '短信验证码请求成功！'
        );
        return $data;
    }
}
