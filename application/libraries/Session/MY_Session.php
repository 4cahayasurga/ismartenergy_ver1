<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Session extends CI_Session
{
    /** Melengkapi konfigurasi cookie session CI3 untuk PHP 7.3. */
    protected function _configure(&$params)
    {
        parent::_configure($params);
        $cookies=session_get_cookie_params();
        $cookies['samesite']='Lax';
        session_set_cookie_params($cookies);
    }

    /** Menjaga SameSite saat CI3 memperbarui cookie session existing. */
    public function __construct($params=array())
    {
        parent::__construct($params);
        if (session_status()===PHP_SESSION_ACTIVE && !headers_sent()) {
            $cookies=session_get_cookie_params();
            setcookie(session_name(),session_id(),array(
                'expires'=>$cookies['lifetime']?time()+$cookies['lifetime']:0,
                'path'=>$cookies['path'],'domain'=>$cookies['domain'],
                'secure'=>$cookies['secure'],'httponly'=>true,'samesite'=>'Lax'
            ));
        }
    }
}
