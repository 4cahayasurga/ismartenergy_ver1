<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Security_headers
{
    public function apply()
    {
        $ci=get_instance();
        if(isset($ci->session)&&$ci->session->userdata('IS_LOGIN')){
            $role=$ci->session->userdata('USER_ROLE');
            if(in_array($role,array('TRANSPORTIR','ASDP','IFPRO'),true)){
                $controller=strtolower($ci->router->fetch_class());
                if(!in_array($controller,array('auth','pipeline_partner','pipeline_verify','notification_center'),true)
                    && !($controller==='pipanisasi'&&$ci->router->fetch_method()==='no_access')){
                    show_error('Menu ini berada di luar akses akun mitra.',403);
                }
            }
        }
        if (headers_sent()) {
            return;
        }

        $is_https = !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off';
        $host = isset($_SERVER['HTTP_HOST']) ? strtolower(preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST'])) : '';
        $is_localhost = in_array($host, array('localhost', '127.0.0.1', '::1'), true);

        if ($is_https || $is_localhost) {
            header('X-Content-Type-Options: nosniff');
        }
        header('X-Frame-Options: SAMEORIGIN');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Permissions-Policy: camera=(self), microphone=(), geolocation=()');
        header("Content-Security-Policy: frame-ancestors 'self'; object-src 'none'; base-uri 'self'; form-action 'self'");

        if ($is_https || $is_localhost) {
            header('Cross-Origin-Opener-Policy: same-origin');
        }
        if ($is_https) {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        }
    }
}
