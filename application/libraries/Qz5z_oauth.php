<?php (! defined('BASEPATH')) and exit('No direct script access allowed');

class Qz5z_oauth
{
    public function __construct()
    {
    }

    public function getUserToken($code, $client_id, $client_secret, $redirect_uri, $grant_type, $scope)
    {
        $url   = 'https://open.qz5z.ren/oauth2/api/getUserToken';
        $data = array(
                    'code' => $code,
                    'client_id' => $client_id,
                    'client_secret' => $client_secret,
                    'redirect_uri' => $redirect_uri,
                    'grant_type' => $grant_type,
                    'scope' => $scope
                );
        $json = $this->GoCurl($url, 'POST', $data, $error_msg);
        return json_decode($json);
    }

    public function getUserData($token, $scope)
    {
        $url   = 'https://open.qz5z.ren/oauth2/api/getUserData';
        $data = array(
                    'access_token' => $token,
                    'scope' => $scope
                );
        $json = $this->GoCurl($url, 'POST', $data, $error_msg);
        return json_decode($json);
    }

    private function GoCurl($url, $type, $data = false, &$err_msg = null, $timeout = 8, $cert_info = array())
    {
        
        $type = strtoupper($type);
        $data = http_build_query($data);
        /* if ($type == 'GET' && is_array($data)) {
            $data = http_build_query($data);
        } */
    
        $option = array();
    
        if ($type == 'POST') {
            $option[CURLOPT_POST] = 1;
        }
        if ($data) {
            if ($type == 'POST') {
                $option[CURLOPT_POSTFIELDS] = $data;
            } elseif ($type == 'GET') {
                $url = strpos($url, '?') !== false ? $url.'&'.$data :  $url.'?'.$data;
            }
        }
    
        $option[CURLOPT_HTTPHEADER]     = ['Content-Type' => 'application/x-www-form-urlencoded'];
        $option[CURLOPT_URL]            = $url;
        $option[CURLOPT_FOLLOWLOCATION] = true;
        $option[CURLOPT_MAXREDIRS]      = 4;
        $option[CURLOPT_RETURNTRANSFER] = true;
        $option[CURLOPT_TIMEOUT]        = $timeout;
    
        //设置证书信息
        if (!empty($cert_info) && !empty($cert_info['cert_file'])) {
            $option[CURLOPT_SSLCERT]       = $cert_info['cert_file'];
            $option[CURLOPT_SSLCERTPASSWD] = $cert_info['cert_pass'];
            $option[CURLOPT_SSLCERTTYPE]   = $cert_info['cert_type'];
        }
    
        //设置CA
        if (!empty($cert_info['ca_file'])) {
            // 对认证证书来源的检查，0表示阻止对证书的合法性的检查。1需要设置CURLOPT_CAINFO
            $option[CURLOPT_SSL_VERIFYPEER] = 1;
            $option[CURLOPT_CAINFO] = $cert_info['ca_file'];
        } else {
            // 对认证证书来源的检查，0表示阻止对证书的合法性的检查。1需要设置CURLOPT_CAINFO
            $option[CURLOPT_SSL_VERIFYPEER] = 0;
        }
    
        $ch = curl_init();
        curl_setopt_array($ch, $option);
        $response = curl_exec($ch);
        $curl_no  = curl_errno($ch);
        $curl_err = curl_error($ch);
        curl_close($ch);
    
        // error_log
        if ($curl_no > 0) {
            if ($err_msg !== null) {
                $err_msg = '('.$curl_no.')'.$curl_err;
            }
        }
        return $response;
    }
}
