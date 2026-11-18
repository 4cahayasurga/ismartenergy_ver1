<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_profile extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Users');
    }

    public function index()
    {
        $user_id = $this->session->userdata('MIS_LOGGED_ID');
        $user = $this->Users->getUserByUsername($user_id);
        if (!$user) {
            $this->session->set_flashdata('Gagal', 'Data user login tidak ditemukan');
            redirect('Auth/logout');
            return;
        }

        $data['judul'] = 'Profile User';
        $data['user'] = $user;
        $data['available_fields'] = $this->db->list_fields('Login');
        $data['company'] = null;

        if (!empty($user->BusinessEntityID) && $this->db->table_exists('JbtBusinessEntity')) {
            $data['company'] = $this->db->where('BusinessEntityID', $user->BusinessEntityID)
                ->get('JbtBusinessEntity')
                ->row();
        }

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('user_profile/index', $data);
        $this->load->view('templates/footer');
    }

    public function update()
    {
        if (!$this->input->post()) {
            redirect('User_profile');
            return;
        }

        $user_id = $this->session->userdata('MIS_LOGGED_ID');
        $user = $this->Users->getUserByUsername($user_id);
        if (!$user) {
            $this->session->set_flashdata('Gagal', 'Data user login tidak ditemukan');
            redirect('Auth/logout');
            return;
        }

        $fields = $this->db->list_fields('Login');
        $updates = array();
        $user_name = trim($this->input->post('UserName', true));
        $email = trim($this->input->post('Email', true));

        if ($user_name === '') {
            $this->session->set_flashdata('Gagal', 'Nama user wajib diisi');
            redirect('User_profile');
            return;
        }
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->session->set_flashdata('Gagal', 'Format email tidak valid');
            redirect('User_profile');
            return;
        }

        $this->putIfColumnExists($updates, $fields, 'UserName', $user_name);
        $this->putIfColumnExists($updates, $fields, 'Email', $email);

        foreach (array('Phone', 'PhoneNo', 'MobilePhone', 'NoHP', 'Handphone', 'Telp') as $phone_field) {
            if (in_array($phone_field, $fields)) {
                $this->putIfColumnExists($updates, $fields, $phone_field, trim($this->input->post($phone_field, true)));
            }
        }

        foreach (array('Jabatan', 'PositionName') as $position_field) {
            if (in_array($position_field, $fields)) {
                $this->putIfColumnExists($updates, $fields, $position_field, trim($this->input->post($position_field, true)));
            }
        }

        if (!count($updates)) {
            $this->session->set_flashdata('Gagal', 'Tidak ada data profile yang bisa diperbarui');
            redirect('User_profile');
            return;
        }

        $ok = $this->db->where('UserID', $user_id)->update('Login', $updates);
        if (!$ok) {
            $this->session->set_flashdata('Gagal', 'Gagal memperbarui profile');
            redirect('User_profile');
            return;
        }

        $fresh_user = $this->Users->getUserByUsername($user_id);
        $this->session->set_userdata(array(
            'MIS_LOGGED_NAME' => $fresh_user->UserName,
            'MIS_LOGGED_TOKEN' => json_encode($fresh_user),
            'PecahToken' => json_decode(json_encode($fresh_user)),
        ));

        $this->session->set_flashdata('Berhasil', 'Profile berhasil diperbarui');
        redirect('User_profile');
    }

    private function putIfColumnExists(&$updates, $fields, $column, $value)
    {
        if (in_array($column, $fields)) {
            $updates[$column] = $value === '' ? null : $value;
        }
    }
}
