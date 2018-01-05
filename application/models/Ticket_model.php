<?php
class Ticket_model extends CI_Model
{
    public function __construct()
    {
        $this->load->library('ion_auth');
        $this->load->library('user_agent');
        $this->load->model('Admin_model');
        $this->load->model('SMS_model');
        $this->load->model('Log_model');
        $this->load->database();
    }

    public function bookedNum($uid, $type = null)
    {
        $this->db->where('uid', $uid);
        if (isset($type)) {
            $this->db->where('type', $type);
        }
        return $this->db->count_all_results('ticket');
    }

    public function canbook($uid, $phone, $stu = false)
    {
        date_default_timezone_set("Asia/Shanghai");
        $now = time();

        $startTime=$endTime=$now;
        $Num=0;

        if ($stu) {
            $startTime = strtotime($this->Admin_model->getSetting('starttime_stu'));
            $endTime = strtotime($this->Admin_model->getSetting('finaltime_stu'));
            $Num = $this->Admin_model->getSetting('pertnum_stu');
        } else {
            $startTime = strtotime($this->Admin_model->getSetting('starttime'));
            $endTime = strtotime($this->Admin_model->getSetting('finaltime'));
            $Num = $this->Admin_model->getSetting('pertnum');
        }
        
        if ($now<$startTime || $now>$endTime) {
            $returndata = array(
                'status' => -1,
                'msg' => '不在预约时间内！'
            );
            return $returndata;
        }

        $this->db->where('uid', $uid);
        //$this->db->where('type', 1);
        $total = $this->db->count_all_results('ticket');

        if ($total>=$Num) {
            $returndata = array(
                'status' => -1,
                'msg' => '已达到拥有票数上限！'
            );
            return $returndata;
        }

        $remain = $this->Admin_model->getSetting('alltnum') - $this->count();
        if ($remain <= 0) {
            $returndata = array(
                'status' => -2,
                'msg' => '预约名额已全部发放完毕！'
            );
            return $returndata;
        }

        $this->db->where('phone', $phone);
        if ($this->db->count_all_results('ticket')) {
            $returndata = array(
                'status' => -1,
                'msg' => '该手机号已被使用！'
            );
            return $returndata;
        }

        //else
        $returndata = array(
            'status' => 1,
            'msg' => '允许预约',
            'num' => $Num-$total
        );
        return $returndata;
    }

    public function book($uid, $name, $phone)
    {
        date_default_timezone_set("Asia/Shanghai");
        
        $ip = $this->input->ip_address();
        $ua = $this->agent->agent_string();

        $name = $this->security->xss_clean($name);
        $phone = $this->security->xss_clean($phone);
        $ip = $this->security->xss_clean($ip);
        $ua = $this->security->xss_clean($ua);

        $data = array(
            'uid' => $uid,
            'name' => $name,
            'phone' => $phone,
            'checkCode' => mt_rand(100, 999),
            'status' => 1,
            'type' => 1,
            'class' => 1,
            'ip' => $ip,
            'ua' => $ua,
            'time_create' => time()
        );
        if ($this->db->insert('ticket', $data)) {
            $this->Log_model->add_log(4);
            $returndata = array(
                'status' => 1,
                'msg' => '预约成功！'
            );
            return $returndata;
        } else {
            $returndata = array(
                'status' => -1,
                'msg' => '预约失败，请联系管理员处理！'
            );
            return $returndata;
        }
    }

    public function getTicket($uid, $admin = false)
    {
        $this->db->where('uid', $uid);
        $query = $this->db->get('ticket')->result_array();
        return $query;
    }

    public function getImg($ticket_id, $admin = false)
    {
        $this->db->where('id', $ticket_id);
        $query = $this->db->get('ticket')->row();

        $uid = $this->User_model->userinfo()['id'];

        $txt = "";
        if (count($query) == 0) {
            $txt1 = '查无此票';
            $txt2 = '';
        } elseif ($query->uid != $uid && !$admin) {
            $txt1 = '不要看别人的票哦！';
            $txt2 = '';
        } else {
            $tid = sprintf("%04d", $query->id);
            $tcode = $query->checkCode;

            $txt1 = $tid;
            $txt2 = $tcode;
        }
        
        $path = './temp/HuanJieTicketIMG_'.$txt1.'.png';

        // 如果文件存在并且生成时间 <=7d 就直接返回
        if (file_exists($path)) {
            $time = filemtime($path);
            if (time() - $time <= 7*24*3600) {
                header("Content-type: image/png");
                imagepng(imagecreatefrompng($path));
                return;
            }
        }

        // 否则就重新创建
        copy('./assets/img/ticket-bg.png', $path);

        $this->load->library('image_lib');

        // 第一次，加编号
        $config['source_image'] = $path;
        $config['dynamic_output'] = false;
        $config['wm_text'] = $txt1;
        $config['wm_type'] = 'text';
        $config['wm_font_path'] = './assets/fonts/fzxh.ttf';
        $config['wm_font_size'] = '30';
        $config['wm_font_color'] = '000000';
        $config['wm_hor_alignment'] = 'top';
        $config['wm_vrt_alignment'] = 'left';
        $config['wm_hor_offset'] = '724'; //水平偏移(px)
        $config['wm_vrt_offset'] = '120'; //垂直偏移(px)
        $this->image_lib->initialize($config);
        $this->image_lib->watermark();

        // 第二次，加验证码
        $config['wm_text'] = $txt2;
        $config['wm_hor_offset'] = '724'; //水平偏移(px)
        $config['wm_vrt_offset'] = '247'; //垂直偏移(px)
        $this->image_lib->initialize($config);
        $this->image_lib->watermark();

        // 最后输出
        header("Content-type: image/png");
        imagepng(imagecreatefrompng($path));
    }

    public function count($type = 1)
    {
        $this->db->where('type', $type);
        return $this->db->count_all_results('ticket');
    }

    public function ticketinfo($id)
    {
        date_default_timezone_set("Asia/Shanghai");
        
        $ticket = $this->db->where('id', $id)->get('ticket')->row_array();
        return $ticket;
    }

    public function sendSMS($phone)
    {
        $this->db->where('phone', $phone);
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('ticket')->row();

        $arr = array(
            'code' => sprintf("%04d", $query->id),
            'checkNum' => $query->checkCode
        );
        $this->SMS_model->sendSms($this->config->item('aliyun_smssign'), $this->config->item('aliyun_smsid_ticket'), $phone, $arr);
    }

    /* 抽奖用，输出已激活的门票信息 */
    public function getTicketAward()
    {
        $this->db->select("phone, name");
        $this->db->where("status", 2);
        $q = $this->db->get("ticket")->result_array();
        foreach ($q as &$t) {
            $t["url"] = "avatar/favicon.png";
        }
        return $q;
    }
}
