<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Security extends CI_Security
{
    /** Memvalidasi CSRF AJAX melalui header dengan pemeriksaan native CI3. */
    public function csrf_verify()
    {
        if (!isset($_POST[$this->_csrf_token_name]) && isset($_SERVER['HTTP_X_CSRF_TOKEN'])
            && is_string($_SERVER['HTTP_X_CSRF_TOKEN'])) {
            $_POST[$this->_csrf_token_name] = $_SERVER['HTTP_X_CSRF_TOKEN'];
        }
        return parent::csrf_verify();
    }

    /** Mengirim cookie CSRF terpisah dengan atribut PHP 7.3. */
    public function csrf_set_cookie()
    {
        if (config_item('cookie_secure') && !is_https()) return false;
        setcookie($this->_csrf_cookie_name, $this->_csrf_hash, array(
            'expires'=>time()+$this->_csrf_expire, 'path'=>config_item('cookie_path'),
            'domain'=>(string)config_item('cookie_domain'), 'secure'=>(bool)config_item('cookie_secure'),
            'httponly'=>true, 'samesite'=>'Lax'
        ));
        return $this;
    }
}
