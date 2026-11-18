<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Invoice extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('MIS_LOGGED_NAME') == '') {
            $this->session->sess_destroy();
            redirect(base_url('auth'));
        }
        $this->load->model('Spr_model');
    }

    


   
}

/* End of file Controllername.php */
