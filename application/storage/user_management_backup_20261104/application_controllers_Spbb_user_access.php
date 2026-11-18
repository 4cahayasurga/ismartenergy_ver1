<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Spbb_user_access extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_login();
        if ($this->session->userdata('USER_ROLE') !== 'SUPERADMIN' &&
            strtolower($this->session->userdata('MIS_LOGGED_ID')) !== 'admin') {
            show_error('Akses ditolak', 403);
        }
    }

    public function index()
    {
        $data['judul'] = 'Pengaturan Akses User SPBB';
        $data['spbb_list'] = $this->db->where('Status', 'Aktif')
            ->order_by('No_SPBB', 'ASC')
            ->get('SPBB')
            ->result();
        $data['business_entities'] = $this->db->table_exists('JbtBusinessEntity')
            ? $this->db->order_by('CompanyName', 'ASC')->get('JbtBusinessEntity')->result()
            : array();

        $data['users'] = $this->db->query("
            SELECT
                L.UserID,
                L.UserName,
                L.Email,
                L.NIB,
                L.BusinessEntityID,
                L.Active,
                ISNULL(L.UserRole, 'CUSTOMER') AS UserRole,
                L.SpbbID
            FROM Login L
            ORDER BY L.UserName ASC
        ")->result();
        $data['user_spbb_access'] = array();
        $data['user_spbb_names'] = array();
        $access_rows = $this->db->query("
            SELECT UA.UserID, UA.SpbbID, S.No_SPBB, S.Area
            FROM SpbbUserAccess UA
            INNER JOIN SPBB S ON S.LedgerNo = UA.SpbbID
            ORDER BY UA.UserID, S.No_SPBB
        ")->result();
        foreach ($access_rows as $row) {
            if (!isset($data['user_spbb_access'][$row->UserID])) {
                $data['user_spbb_access'][$row->UserID] = array();
            }
            if (!isset($data['user_spbb_names'][$row->UserID])) {
                $data['user_spbb_names'][$row->UserID] = array();
            }
            $data['user_spbb_access'][$row->UserID][] = $row->SpbbID;
            $data['user_spbb_names'][$row->UserID][] = trim($row->No_SPBB . ' - ' . $row->Area);
        }

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('spbb/user_access', $data);
        $this->load->view('templates/footer');
    }

    public function create_user()
    {
        $user_id = trim($this->input->post('UserID', true));
        $user_name = trim($this->input->post('UserName', true));
        $email = trim($this->input->post('Email', true));
        $password = (string) $this->input->post('Password', true);
        $confirm_password = (string) $this->input->post('ConfirmPassword', true);
        $role = $this->input->post('UserRole', true);
        $active = $this->input->post('Active', true) === '0' ? '0' : '1';
        $business_entity_id = (int) $this->input->post('BusinessEntityID', true);
        $spbb_ids = $this->input->post('SpbbID');
        $spbb_ids = is_array($spbb_ids) ? $spbb_ids : array();
        $allowed_roles = array('CUSTOMER', 'PEMILIK_SPBB', 'OPERATOR');

        if ($user_id === '' || $user_name === '' || $password === '') {
            $this->session->set_flashdata('Gagal', 'User ID, nama user, dan password wajib diisi');
            redirect('Spbb_user_access');
        }
        if (!preg_match('/^[A-Za-z0-9_.-]+$/', $user_id)) {
            $this->session->set_flashdata('Gagal', 'User ID hanya boleh berisi huruf, angka, titik, strip, atau underscore');
            redirect('Spbb_user_access');
        }
        if (strlen($password) < 6) {
            $this->session->set_flashdata('Gagal', 'Password minimal 6 karakter');
            redirect('Spbb_user_access');
        }
        if ($password !== $confirm_password) {
            $this->session->set_flashdata('Gagal', 'Konfirmasi password tidak sama');
            redirect('Spbb_user_access');
        }
        if (!in_array($role, $allowed_roles)) {
            $this->session->set_flashdata('Gagal', 'Tipe akses tidak valid');
            redirect('Spbb_user_access');
        }
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->session->set_flashdata('Gagal', 'Format email tidak valid');
            redirect('Spbb_user_access');
        }
        if ($this->db->where('UserID', $user_id)->get('Login')->num_rows()) {
            $this->session->set_flashdata('Gagal', 'User ID sudah terdaftar');
            redirect('Spbb_user_access');
        }
        if (in_array($role, array('PEMILIK_SPBB', 'OPERATOR')) && !count($spbb_ids)) {
            $this->session->set_flashdata('Gagal', 'Akses SPBB wajib dipilih untuk Pemilik SPBB / Operator SPBB');
            redirect('Spbb_user_access');
        }

        $entity = null;
        if ($role === 'CUSTOMER' && $business_entity_id > 0 && $this->db->table_exists('JbtBusinessEntity')) {
            $entity = $this->db->where('BusinessEntityID', $business_entity_id)
                ->get('JbtBusinessEntity')
                ->row_array();
            if (!$entity) {
                $this->session->set_flashdata('Gagal', 'Badan usaha customer tidak ditemukan');
                redirect('Spbb_user_access');
            }
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $this->db->trans_begin();
        $this->db->insert('Login', array(
            'UserID' => $user_id,
            'UserName' => $user_name,
            'Email' => $email,
            'NIB' => $entity ? $entity['NIB'] : null,
            'BusinessEntityID' => $entity ? $entity['BusinessEntityID'] : null,
            'PasswordCI' => $password_hash,
            'Password_dari_CI' => $password_hash,
            'Password' => $password_hash,
            'AksesCompany' => $this->config->item('Company'),
            'Active' => $active,
            'ChangePasswd' => '1',
            'UserRole' => $role,
            'SpbbID' => in_array($role, array('PEMILIK_SPBB', 'OPERATOR')) && count($spbb_ids) ? $spbb_ids[0] : null,
        ));

        if (in_array($role, array('PEMILIK_SPBB', 'OPERATOR'))) {
            foreach ($spbb_ids as $spbb_id) {
                if ($spbb_id === '') {
                    continue;
                }
                $this->db->insert('SpbbUserAccess', array(
                    'UserID' => $user_id,
                    'SpbbID' => $spbb_id,
                    'CreatedBy' => $this->session->userdata('MIS_LOGGED_ID'),
                    'CreatedAt' => date('Y-m-d H:i:s'),
                ));
            }
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('Gagal', 'Gagal menambahkan user baru');
            redirect('Spbb_user_access');
        }

        $this->db->trans_commit();
        $this->session->set_flashdata('Berhasil', 'User baru berhasil ditambahkan dan hak akses sudah diset');
        redirect('Spbb_user_access');
    }

    public function update()
    {
        $user_id = $this->input->post('UserID', true);
        $role = $this->input->post('UserRole', true);
        $spbb_ids = $this->input->post('SpbbID');
        $spbb_ids = is_array($spbb_ids) ? $spbb_ids : array();

        if (!in_array($role, array('CUSTOMER', 'PEMILIK_SPBB', 'OPERATOR'))) {
            $this->session->set_flashdata('Gagal', 'Tipe akses tidak valid');
            redirect('Spbb_user_access');
        }

        if (in_array($role, array('PEMILIK_SPBB', 'OPERATOR')) && !count($spbb_ids)) {
            $this->session->set_flashdata('Gagal', 'Akses SPBB wajib dikorelasikan dengan No SPBB / Nama Cabang');
            redirect('Spbb_user_access');
        }

        $data = array(
            'UserRole' => $role,
            'SpbbID' => in_array($role, array('PEMILIK_SPBB', 'OPERATOR')) && count($spbb_ids) ? $spbb_ids[0] : null,
        );

        $this->db->trans_begin();
        $this->db->where('UserID', $user_id)->update('Login', $data);
        $this->db->where('UserID', $user_id)->delete('SpbbUserAccess');
        if (in_array($role, array('PEMILIK_SPBB', 'OPERATOR'))) {
            foreach ($spbb_ids as $spbb_id) {
                if ($spbb_id === '') {
                    continue;
                }
                $this->db->insert('SpbbUserAccess', array(
                    'UserID' => $user_id,
                    'SpbbID' => $spbb_id,
                    'CreatedBy' => $this->session->userdata('MIS_LOGGED_ID'),
                    'CreatedAt' => date('Y-m-d H:i:s'),
                ));
            }
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('Gagal', 'Gagal memperbarui akses user');
            redirect('Spbb_user_access');
        }

        $this->db->trans_commit();
        $this->session->set_flashdata('Berhasil', 'Akses user berhasil diperbarui');
        redirect('Spbb_user_access');
    }
}
