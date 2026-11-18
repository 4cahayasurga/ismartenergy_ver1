<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Register extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Spbb_registration_model');
    }

    public function index()
    {
        $data['judul'] = 'Registrasi Akun';
        $this->load->view('register', $data);
    }

    public function submit()
    {
        if (!$this->input->post()) {
            redirect('Register');
        }

        $user_id = trim($this->input->post('UserID', true));
        $password = $this->input->post('Password', true);
        $confirm = $this->input->post('ConfirmPassword', true);
        $company_name = trim($this->input->post('CompanyName', true));
        $nib = trim($this->input->post('NIB', true));
        $bank_account_no = $this->normalize_account($this->input->post('BankAccountNo', true));

        if ($password !== $confirm) {
            $this->session->set_flashdata('Gagal', 'Konfirmasi password tidak sesuai');
            redirect('Register');
        }

        if ($company_name === '') {
            $this->session->set_flashdata('Gagal', 'Nama perusahaan wajib diisi');
            redirect('Register');
        }
        if (!preg_match('/^[0-9]{13}$/', $nib)) {
            $this->session->set_flashdata('Gagal', 'NIB wajib diisi 13 digit.');
            redirect('Register');
        }

        $escaped_company_name = $this->db->escape(trim($company_name));
        $registered_company = $this->db
            ->group_start()
                ->where("LOWER(LTRIM(RTRIM(CompanyName))) = LOWER($escaped_company_name)", null, false)
                ->or_where('NIB', $nib)
            ->group_end()
            ->get('JbtBusinessEntity')
            ->row_array();
        $pending_company = $this->db
            ->group_start()
                ->where("LOWER(LTRIM(RTRIM(CompanyName))) = LOWER($escaped_company_name)", null, false)
                ->or_where('NIB', $nib)
            ->group_end()
            ->where('Status', 'PENDING')
            ->get('SpbbRegistration')
            ->row_array();
        if ($registered_company) {
            $this->session->set_flashdata(
                'Gagal',
                'Perusahaan atau NIB tersebut sudah terdaftar. Silakan meminta user perusahaan yang sudah aktif untuk mengajukan akun Anda melalui menu Pengajuan User di dalam aplikasi.'
            );
            redirect('Register');
        }
        if ($pending_company) {
            $this->session->set_flashdata(
                'Gagal',
                'Perusahaan atau NIB tersebut sudah memiliki registrasi yang sedang menunggu approval Admin. Tidak perlu membuat registrasi perusahaan baru.'
            );
            redirect('Register');
        }
        if ($bank_account_no === '') {
            $this->session->set_flashdata('Gagal', 'Nomor rekening pembayaran perusahaan wajib diisi.');
            redirect('Register');
        }

        $required = array(
            'CompanyAddress' => 'Alamat perusahaan',
            'NPWP' => 'NPWP',
            'DeedNo' => 'Nomor akta pendirian/perubahan terakhir',
            'DeedDate' => 'Tanggal akta',
            'NotaryName' => 'Nama notaris',
            'AhuNo' => 'Nomor SK Kemenkumham/AHU',
            'BusinessLicenseNo' => 'Nomor izin usaha',
            'BankName' => 'Nama bank',
            'BankAccountName' => 'Nama pemilik rekening',
        );
        foreach ($required as $field => $label) {
            if (trim($this->input->post($field, true)) === '') {
                $this->session->set_flashdata('Gagal', $label . ' wajib diisi.');
                redirect('Register');
            }
        }

        $uploaded = $this->upload_legal_files();
        if (isset($uploaded['error'])) {
            $this->session->set_flashdata('Gagal', $uploaded['error']);
            redirect('Register');
        }

        $exists_login = $this->db->where('UserID', $user_id)->get('Login')->num_rows();
        $exists_pending = $this->db->where('UserID', $user_id)
            ->where('Status', 'PENDING')
            ->get('SpbbRegistration')
            ->num_rows();
        if ($exists_login || $exists_pending) {
            $this->session->set_flashdata('Gagal', 'User ID sudah terdaftar atau sedang menunggu approval');
            redirect('Register');
        }

        $this->Spbb_registration_model->create(array(
            'UserID' => $user_id,
            'UserName' => $this->input->post('UserName', true),
            'NIB' => $nib,
            'Email' => $this->input->post('Email', true),
            'Phone' => $this->input->post('Phone', true),
            'CompanyName' => $company_name,
            'CompanyAddress' => $this->input->post('CompanyAddress', true),
            'NPWP' => $this->input->post('NPWP', true),
            'DeedNo' => $this->input->post('DeedNo', true),
            'DeedDate' => $this->input->post('DeedDate', true),
            'NotaryName' => $this->input->post('NotaryName', true),
            'AhuNo' => $this->input->post('AhuNo', true),
            'BusinessLicenseNo' => $this->input->post('BusinessLicenseNo', true),
            'BankName' => $this->input->post('BankName', true),
            'BankAccountNo' => $bank_account_no,
            'BankAccountName' => $this->input->post('BankAccountName', true),
            'NibFile' => $uploaded['NibFile'],
            'NpwpFile' => $uploaded['NpwpFile'],
            'DeedFile' => $uploaded['DeedFile'],
            'AhuFile' => $uploaded['AhuFile'],
            'InitialVesselName' => $this->input->post('InitialVesselName', true),
            'InitialVesselRegistrationNo' => $this->input->post('InitialVesselRegistrationNo', true),
            'InitialVesselType' => $this->input->post('InitialVesselType', true),
            'InitialVesselGrossTonnage' => (float) $this->input->post('InitialVesselGrossTonnage', true),
            'RequestedRole' => 'PENDING',
            'RequestedSpbbIDs' => '',
            'PasswordHash' => password_hash($password, PASSWORD_DEFAULT),
            'Status' => 'PENDING',
            'CreatedAt' => date('Y-m-d H:i:s'),
        ));

        $this->session->set_flashdata('Berhasil', 'Registrasi berhasil dikirim. Akun akan aktif setelah disetujui SuperAdmin.');
        redirect('Register');
    }

    private function upload_legal_files()
    {
        $path = FCPATH . 'uploads/company_legal/';
        if (!is_dir($path) && !mkdir($path, 0755, true)) {
            return array('error' => 'Folder dokumen legalitas tidak dapat dibuat.');
        }
        $map = array('NibFile' => 'NibFile', 'NpwpFile' => 'NpwpFile', 'DeedFile' => 'DeedFile', 'AhuFile' => 'AhuFile');
        $result = array();
        foreach ($map as $input => $column) {
            if (empty($_FILES[$input]['name'])) {
                $result[$column] = null;
                continue;
            }
            $config = array('upload_path' => $path, 'allowed_types' => 'pdf|jpg|jpeg|png', 'max_size' => 5120, 'encrypt_name' => true);
            $this->load->library('upload');
            $this->upload->initialize($config);
            if (!$this->upload->do_upload($input)) {
                return array('error' => strip_tags($this->upload->display_errors()));
            }
            $result[$column] = $this->upload->data('file_name');
        }
        return $result;
    }

    private function normalize_account($number)
    {
        return preg_replace('/[^0-9]/', '', (string) $number);
    }
}
