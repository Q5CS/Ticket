<?php
include('ticket.php');

$config['protocol'] = 'smtp';
$config['smtp_host'] = $Tconfig['smtp_host'];
$config['smtp_user'] = $Tconfig['smtp_user'];
$config['smtp_pass'] = $Tconfig['smtp_password'];
$config['smtp_port'] = $Tconfig['smtp_port'];
$config['smtp_crypto'] = $Tconfig['smtp_crypto'];
$config['smtp_timeout'] = 10;
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['wordwrap'] = true;
$config['newline'] = "\r\n";
$config['crlf'] = "\r\n";
