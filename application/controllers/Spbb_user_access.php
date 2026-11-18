<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Spbb_user_access extends CI_Controller {
 public function __construct(){parent::__construct();is_login();$this->load->library('Pipeline_access');$this->pipeline_access->require_menu('users');}
 public function index(){redirect('Pipeline_users/access');}
 public function create_user(){show_error('Gunakan halaman Pengaturan User untuk menyimpan user.',403);}
 public function update(){show_error('Gunakan halaman Akses Management untuk menyimpan akses.',403);}
}
