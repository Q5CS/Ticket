<?php
if (is_file("../application/config/ticket.php")) {
    echo("ticket.php 已存在！如需重新安装，请删除 application/config/ticket.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>安装 - Ticket</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.css" integrity="sha256-UXesixbeLkB/UYxVTzuj/gg3+LMzgwAmg3zD+C4ZASQ="
    crossorigin="anonymous">
  <style>
    body {
      background-color: #EBEBEB;
    }
    .ui.text.container {
      font-size: 1em;
    }
    .tip {
      text-align: center;
    }
    .copyright {
      font-size: 12px;
      text-align: center;
      color: #8f979e;
    }
    .copyright a {
      color: #8f979e;
    }
    .content {
      margin-left: auto;
      margin-right: auto;
      padding: 2em 0;
    }
  </style>
</head>

<body>
  <div class="content">
    <div class="ui raised very padded text container segment">
      <h1 class="ui header">安装 Ticket</h1>
      <div class="ui divider"></div>
      <form class="ui form" action="install.php" method="post">

        <h4 class="tip">基础信息</h4>
        <div class="field">
          <label>网站地址（index.php 所在目录，务必填写协议，末尾加/）</label>
          <input type="text" name="url" placeholder="网站地址" value="http://127.0.0.1/" required>
        </div>

        <div class="ui divider"></div>

        <h4 class="tip">数据库设置</h4>
        <div class="field">
          <label>Mysql 服务器地址</label>
          <input type="text" name="db_ip" placeholder="Mysql 服务器地址" value="localhost" required>
        </div>
        <div class="field">
          <label>Mysql 用户名</label>
          <input type="text" name="db_user" placeholder="Mysql 用户名" value="root" required>
        </div>
        <div class="field">
          <label>Mysql 密码</label>
          <input type="text" name="db_password" placeholder="Mysql 密码" value="">
        </div>
        <div class="field">
          <label>Mysql 数据库名</label>
          <input type="text" name="db_name" placeholder="Mysql 数据库名" value="" required>
        </div>
        <div class="ui divider"></div>

        <h4 class="tip">发信设置</h4>
        <div class="field">
          <label>SMTP 服务器地址</label>
          <input type="text" name="smtp_host" placeholder="SMTP 服务器地址" value="smtpdm.aliyun.com" required>
        </div>
        <div class="field">
          <label>SMTP 服务器端口</label>
          <input type="text" name="smtp_port" placeholder="SMTP 服务器端口" value="465" required>
        </div>
        <div class="field">
          <label>SMTP 加密协议（不加密留空 / tls / ssl）</label>
          <input type="text" name="smtp_crypto" placeholder="SMTP 加密协议（不加密留空 / tls / ssl）" value="ssl">
        </div>
        <div class="field">
          <label>SMTP 邮箱地址</label>
          <input type="text" name="smtp_user" placeholder="SMTP 邮箱地址" value="no-reply@mail.2019.qz5z.ren" required>
        </div>
        <div class="field">
          <label>SMTP 邮箱密码</label>
          <input type="text" name="smtp_password" placeholder="SMTP 邮箱密码" value="" required>
        </div>
        <div class="ui divider"></div>

        <h4 class="tip">第三方平台设置</h4>
        <div class="field">
          <label>阿里云 AccessKey</label>
          <input type="text" name="aliyun_accesskey" placeholder="阿里云 AccessKey" value="" required>
        </div>
        <div class="field">
          <label>阿里云 SecretKey</label>
          <input type="text" name="aliyun_secret" placeholder="阿里云 Secret" value="" required>
        </div>
        <div class="field">
          <label>阿里云短信签名</label>
          <input type="text" name="aliyun_smssign" placeholder="阿里云短信签名" value="泉五换届" required>
        </div>
        <div class="field">
          <label>阿里云短信模板编号（验证码）</label>
          <input type="text" name="aliyun_smsid_reg" placeholder="阿里云短信模板编号（验证码）" value="" required>
        </div>
        <div class="field">
          <label>阿里云短信模板编号（预约成功）</label>
          <input type="text" name="aliyun_smsid_ticket" placeholder="阿里云短信模板编号（预约成功）" value="" required>
        </div>
        <div class="field">
          <label>reCAPTCHA SiteKey</label>
          <input type="text" name="recaptcha_sitekey" placeholder="reCAPTCHA SiteKey" value="" required>
        </div>
        <div class="field">
          <label>reCAPTCHA SecretKey</label>
          <input type="text" name="recaptcha_secret" placeholder="reCAPTCHA SecretKey" value="" required>
        </div>
        <div class="field">
          <label>五中人 Oauth ClientID</label>
          <input type="text" name="qz5z_clientid" placeholder="五中人 Oauth ClientID" value="" required>
        </div>
        <div class="field">
          <label>五中人 Oauth SecretKey</label>
          <input type="text" name="qz5z_secret" placeholder="五中人 Oauth SecretKey" value="" required>
        </div>
        <div class="ui divider"></div>

        <h4 class="tip">初始管理员设置</h4>
        <div class="field">
          <label>管理员邮箱</label>
          <input type="email" name="admin_user" placeholder="管理员邮箱" value="" required>
        </div>
        <div class="field">
          <label>管理员密码</label>
          <input type="text" name="admin_password" placeholder="管理员密码" value="" required>
        </div>
        <div class="ui divider"></div>

        <div class="ui yellow message">
          <div class="header">注意事项</div>
          <p>
            安装成功后，请立即删除 install 文件夹！
          </p>
        </div>
        <button class="ui button" type="submit">安装</button>
      </form>
    </div>
    <div class="copyright">
      Made with <span class="am-icon-heart"></span> by 泉五社联 X <a href="https://www.qz5z.tech" target="_blank">泉五电研</a>
      <br>
      © 2017- 换届实行委员会. All rights reserved.
    </div>
  </div>