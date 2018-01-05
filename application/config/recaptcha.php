<?php
defined('BASEPATH') or exit('No direct script access allowed');
include('ticket.php');

// To use reCAPTCHA, you need to sign up for an API key pair for your site.
// link: http://www.google.com/recaptcha/admin
$config['recaptcha_site_key'] = $Tconfig['recaptcha_sitekey'];
$config['recaptcha_secret_key'] = $Tconfig['recaptcha_secret'];

// reCAPTCHA supported 40+ languages listed here:
// https://developers.google.com/recaptcha/docs/language
$config['recaptcha_lang'] = 'zh-CN';

/* End of file recaptcha.php */
/* Location: ./application/config/recaptcha.php */
