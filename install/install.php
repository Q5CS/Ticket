<?php

if (is_file("../application/config/ticket.php.lock")) {
    echo("ticket.php 已存在！如需重新安装，请删除 application/config/ticket.php.lock");
    exit();
}

function array_to_object($arr)
{
    if (gettype($arr) != 'array') {
        return;
    }
    foreach ($arr as $k => $v) {
        if (gettype($v) == 'array' || getType($v) == 'object') {
            $arr[$k] = (object)array_to_object($v);
        }
    }
 
    return (object)$arr;
}

$data = array(
    'url' => $_POST['url'],
    'db_ip' => $_POST['db_ip'],
    'db_name' => $_POST['db_name'],
    'db_user' => $_POST['db_user'],
    'db_password' => $_POST['db_password'],
    'smtp_host' => $_POST['smtp_host'],
    'smtp_port' => $_POST['smtp_port'],
    'smtp_crypto' => $_POST['smtp_crypto'],
    'smtp_user' => $_POST['smtp_user'],
    'smtp_password' => $_POST['smtp_password'],
    'aliyun_accesskey' => $_POST['aliyun_accesskey'],
    'aliyun_secret' => $_POST['aliyun_secret'],
    'aliyun_smssign' => $_POST['aliyun_smssign'],
    'aliyun_smsid_reg' => $_POST['aliyun_smsid_reg'],
    'aliyun_smsid_ticket' => $_POST['aliyun_smsid_ticket'],
    'admin_user' => $_POST['admin_user'],
    'admin_password' => $_POST['admin_password'],
    'recaptcha_sitekey' => $_POST['recaptcha_sitekey'],
    'recaptcha_secret' => $_POST['recaptcha_secret'],
    'qz5z_secret' => $_POST['qz5z_secret'],
    'qz5z_clientid' => $_POST['qz5z_clientid']
);
$admin_password_hashed = password_hash($_POST['admin_password'], PASSWORD_BCRYPT);
$data = array_to_object($data);

// var_dump($data);

$_sql = file_get_contents('import.sql');
$_arr = explode(';', $_sql);
$_mysqli = new mysqli($data->db_ip, $data->db_user, $data->db_password);
if (mysqli_connect_errno()) {
    exit('连接数据库出错，请检查信息是否正确！');
}
//执行sql语句
$_mysqli->query("CREATE DATABASE IF NOT EXISTS ".$data->db_name." DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;");
$_mysqli->query("USE ".$data->db_name);
foreach ($_arr as $_value) {
    $_mysqli->query($_value.';');
}
//写入管理员信息
$_mysqli->query("INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`, `schoolid`, `grade`, `class`, `name`) VALUES
(1, '127.0.0.1', 'administrator', '$admin_password_hashed', '', '$data->admin_user', '', NULL, NULL, '', 1268889823, 1491136766, 1, 'Admin', 'istrator', 'ADMIN', '0', 0, '', '', '');");
$_mysqli->query(" COMMIT;");
$_mysqli->close();
$_mysqli = null;

$writecontent = "
<?php
defined(\"BASEPATH\") or exit(\"No direct script access allowed\");
\$Tconfig['base_url'] = '$data->url';
\$Tconfig['sendSMS'] = true;
\$Tconfig['enableLog'] = true;
\$Tconfig['accessKeyId'] = '$data->aliyun_accesskey';
\$Tconfig['accessKeySecret'] = '$data->aliyun_secret';
\$Tconfig['aliyun_smssign'] = '$data->aliyun_smssign';
\$Tconfig['aliyun_smsid_reg'] = '$data->aliyun_smsid_reg';
\$Tconfig['aliyun_smsid_ticket'] = '$data->aliyun_smsid_ticket';
\$Tconfig['db_ip'] = '$data->db_ip';
\$Tconfig['db_name'] = '$data->db_name';
\$Tconfig['db_user'] = '$data->db_user';
\$Tconfig['db_password'] = '$data->db_password';
\$Tconfig['smtp_host'] = '$data->smtp_host';
\$Tconfig['smtp_port'] = '$data->smtp_port';
\$Tconfig['smtp_user'] = '$data->smtp_user';
\$Tconfig['smtp_password'] = '$data->smtp_password';
\$Tconfig['smtp_crypto'] = '$data->smtp_crypto';
\$Tconfig['recaptcha_sitekey'] = '$data->recaptcha_sitekey';
\$Tconfig['recaptcha_secret'] = '$data->recaptcha_secret';
\$Tconfig['qz5z_secret'] = '$data->qz5z_secret';
\$Tconfig['qz5z_clientid'] = '$data->qz5z_clientid';
";

$myfile = fopen("../application/config/ticket.php", "w") or die("配置文件不可写，请手动复制 install 目录下的 ticket.example.php 到 application/config 文件夹并编辑。");
fwrite($myfile, $writecontent);
fclose($myfile);

$myfile = fopen("../application/config/ticket.php.lock", "w") or die("配置文件不可写，请手动写入 ticket.php.lock 到 application/config 文件夹。");
fwrite($myfile, 'installed');
fclose($myfile);

//写出配置文件

echo("安装成功！<br> 管理员账号：$data->admin_user <br> 管理员密码：$data->admin_password <br> <p style=\"color: red\">请立即删除 install 文件夹！！！</p>");
