<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Phase2_security
{
    /** Membatasi permukaan endpoint sebelum constructor legacy dijalankan. */
    public function routes()
    {
        $router=load_class('Router','core');
        $controller=strtolower($router->fetch_class());
        $method=strtolower($router->fetch_method());
        $allowed=array('auth','register','pipanisasi','pipeline_billing','pipeline_customer_vessels',
            'pipeline_customers','pipeline_customer_team','pipeline_legal','pipeline_documents','pipeline_flow','pipeline_operator_company',
            'pipeline_partner','pipeline_setup','pipeline_spp','pipeline_users','pipeline_verify',
            'notification_center','superadmin_registration','spbb','spbb_operator','spbb_user_access',
            'spbb_material','spbb_pertamina','spbb_receipt','api_spbb','user_profile');
        if (!in_array($controller,$allowed,true)) show_404();
        if ($controller==='auth' && !in_array($method,array('index','logout','updatepassword'),true)) show_404();
        if (in_array($controller,array('pipeline_verify','register'),true)
            && !in_array($method,$controller==='pipeline_verify'?array('index'):array('index','submit'),true)) show_404();
        if ($controller==='api_spbb') {
            $read=in_array($method,array('catalog','orders'),true);
            $write=in_array($method,array('login','create_order','approve_order','upload_payment'),true);
            if (!$read && !$write) show_404();
            if (($read && $_SERVER['REQUEST_METHOD']!=='GET') || ($write && $_SERVER['REQUEST_METHOD']!=='POST')) {
                set_status_header(405); header('Allow: '.($read?'GET':'POST')); exit;
            }
        }
    }

    /** Melindungi halaman privat tanpa menggantikan otorisasi objek existing. */
    public function identity()
    {
        $ci=get_instance();
        $controller=strtolower($ci->router->fetch_class());
        $method=strtolower($ci->router->fetch_method());
        $ci->output->set_header('Cache-Control: no-store, private');
        if (in_array($controller,array('register','pipeline_verify','api_spbb'),true)
            || ($controller==='auth' && $method==='index') || ($controller==='pipeline_customer_team' && $method==='activate')) return;
        $ci->load->library('Pipeline_identity');
        if (!$ci->pipeline_identity->valid()) {
            $ci->session->sess_destroy(); redirect('Auth','location',$ci->input->method()==='post'?303:302); return;
        }
        if ($controller==='auth' && $method==='updatepassword' && $ci->input->method()!=='post') show_error('Gunakan formulir ganti password.',405);
    }

    /** Menyisipkan token pada form HTML tanpa memodifikasi PDF atau respons JSON. */
    public function display()
    {
        $ci=get_instance(); $html=$ci->output->get_output();
        if (stripos($ci->output->get_content_type(),'text/html')!==false && stripos($html,'<form')!==false) {
            $name=html_escape($ci->security->get_csrf_token_name());
            $value=html_escape($ci->security->get_csrf_hash());
            $hidden='<input type="hidden" name="'.$name.'" value="'.$value.'">';
            $html=preg_replace_callback('/<form\b[^>]*>/i',function($match)use($hidden){
                $form=$match[0];
                if (!preg_match('/\bmethod\s*=\s*[\x22\x27]?post\b/i',$form)) return $form;
                if (preg_match('/\baction\s*=\s*([\x22\x27])(.*?)\1/i',$form,$action)
                    && preg_match('#^(https?:)?//#i',$action[2])) {
                    $url=parse_url(strpos($action[2],'//')===0?'http:'.$action[2]:$action[2]);
                    $base=parse_url(base_url());
                    if (!$url || strcasecmp($url['host']??'',$base['host']??'')!==0
                        || ($url['port']??80)!==($base['port']??80)) return $form;
                }
                return $form.$hidden;
            },$html);
            $meta='<meta name="ismart-csrf-name" content="'.$name.'"><meta name="ismart-csrf-token" content="'.$value.'">';
            $script='<script src="'.base_url('assets/js/ver11-security.js').'"></script>';
            $html=str_ireplace('</head>',$meta.$script.'</head>',$html);
        } elseif (stripos($ci->output->get_content_type(),'text/html')!==false && stripos($html,'</head>')!==false) {
            $meta='<meta name="ismart-csrf-name" content="'.html_escape($ci->security->get_csrf_token_name()).'"><meta name="ismart-csrf-token" content="'.html_escape($ci->security->get_csrf_hash()).'">';
            $html=str_ireplace('</head>',$meta.'<script src="'.base_url('assets/js/ver11-security.js').'"></script></head>',$html);
        }
        $ci->output->_display($html);
    }
}
