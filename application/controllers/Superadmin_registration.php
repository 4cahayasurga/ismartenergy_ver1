<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Superadmin_registration extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Spbb_registration_model');
        $this->load->library('Pipeline_access');$this->pipeline_access->require_menu('registration_approval');if(!$this->session->userdata('pipeline_form_token'))$this->session->set_userdata('pipeline_form_token',bin2hex(random_bytes(24)));if(!in_array($this->router->fetch_method(),array('index'),true)){if($this->input->method()!=='post')show_error('Gunakan form approval.',405);$token=$this->input->post('pipeline_token');if(!is_string($token)||!hash_equals($this->session->userdata('pipeline_form_token'),$token))show_error('Form kedaluwarsa.',403);}
        if (!$this->is_superadmin()) {
            show_error('Akses ditolak. Menu ini hanya untuk SuperAdmin.', 403);
        }
    }

    public function index()
    {
        $data['judul'] = 'Approval Registrasi User';
        $data['registrations'] = $this->Spbb_registration_model->all();
        $data['spbb_list'] = $this->db->where('Status', 'Aktif')
            ->order_by('No_SPBB', 'ASC')
            ->get('SPBB')
            ->result();
        $data['spbb_names'] = array();
        foreach ($data['registrations'] as $row) {
            $entity = null;
            if (!empty($row->NIB)) {
                $entity = $this->db->where('NIB', $row->NIB)->get('JbtBusinessEntity')->row();
            }
            $row->DocumentSource = 'REGISTRATION';
            if ($entity) {
                foreach (array('NibFile', 'NpwpFile', 'DeedFile', 'AhuFile') as $document_field) {
                    if (!empty($entity->$document_field)) {
                        $row->$document_field = $entity->$document_field;
                        $row->DocumentSource = 'BUSINESS_ENTITY';
                    }
                }
            }
            $data['spbb_names'][$row->RegistrationID] = $this->Spbb_registration_model->spbb_names(
                $this->Spbb_registration_model->requested_spbb_ids($row)
            );
        }
        $data['customer_user_requests'] = $this->db->select('R.*,B.CompanyName,B.NIB')
            ->from('JbtCustomerUserRequest R')
            ->join('JbtBusinessEntity B', 'B.BusinessEntityID=R.BusinessEntityID')
            ->order_by('R.UserRequestID', 'DESC')->get()->result();

        $data['pipeline_token']=$this->session->userdata('pipeline_form_token');$this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('superadmin/registration_approval', $data);
        $this->load->view('templates/footer');
    }

    public function approve($id)
    {
        $registration = $this->Spbb_registration_model->find($id);
        if (!$registration || $registration->Status !== 'PENDING') {
            $this->session->set_flashdata('Gagal', 'Data registrasi tidak valid atau sudah diproses');
            redirect('Superadmin_registration');
        }

        if ($this->db->where('UserID', $registration->UserID)->get('Login')->num_rows() > 0) {
            $this->session->set_flashdata('Gagal', 'User ID sudah ada di Login');
            redirect('Superadmin_registration');
        }

        $role = $this->input->post('UserRole', true);
        $spbb_ids = $this->input->post('SpbbID');
        $spbb_ids = is_array($spbb_ids) ? $spbb_ids : array();
        if (!in_array($role, array('CUSTOMER', 'PEMILIK_SPBB', 'OPERATOR'))) {
            $this->session->set_flashdata('Gagal', 'Akses user wajib dipilih Admin');
            redirect('Superadmin_registration');
        }
        if (in_array($role, array('PEMILIK_SPBB', 'OPERATOR')) && !count($spbb_ids)) {
            $this->session->set_flashdata('Gagal', 'Akses SPBB wajib memilih minimal satu No SPBB / Nama Cabang');
            redirect('Superadmin_registration');
        }

        $this->db->trans_begin();
        $business_entity_id = null;
        if ($role === 'CUSTOMER') {
            $entity = $this->db->where('NIB', $registration->NIB)->get('JbtBusinessEntity')->row_array();
            if ($entity) {
                $business_entity_id = $entity['BusinessEntityID'];
                $entity_update = array(
                    'CompanyName' => $registration->CompanyName,
                    'CompanyAddress' => $registration->CompanyAddress,
                    'NPWP' => $registration->NPWP,
                    'DeedNo' => $registration->DeedNo,
                    'DeedDate' => $registration->DeedDate,
                    'NotaryName' => $registration->NotaryName,
                    'AhuNo' => $registration->AhuNo,
                    'BusinessLicenseNo' => $registration->BusinessLicenseNo,
                    'BankName' => $registration->BankName,
                    'BankAccountNo' => $registration->BankAccountNo,
                    'BankAccountName' => $registration->BankAccountName,
                );
                foreach (array('NibFile', 'NpwpFile', 'DeedFile', 'AhuFile') as $document_field) {
                    if (!empty($registration->$document_field)) {
                        $entity_update[$document_field] = $registration->$document_field;
                    }
                }
                $this->db->where('BusinessEntityID', $business_entity_id)->update('JbtBusinessEntity', $entity_update);
            } else {
                $this->db->insert('JbtBusinessEntity', array(
                    'NIB' => $registration->NIB,
                    'CompanyName' => $registration->CompanyName,
                    'CompanyAddress' => $registration->CompanyAddress,
                    'NPWP' => $registration->NPWP,
                    'DeedNo' => $registration->DeedNo,
                    'DeedDate' => $registration->DeedDate,
                    'NotaryName' => $registration->NotaryName,
                    'AhuNo' => $registration->AhuNo,
                    'BusinessLicenseNo' => $registration->BusinessLicenseNo,
                    'BankName' => $registration->BankName,
                    'BankAccountNo' => $registration->BankAccountNo,
                    'BankAccountName' => $registration->BankAccountName,
                    'NibFile' => $registration->NibFile,
                    'NpwpFile' => $registration->NpwpFile,
                    'DeedFile' => $registration->DeedFile,
                    'AhuFile' => $registration->AhuFile,
                    'CreatedBy' => $this->session->userdata('MIS_LOGGED_ID'),
                ));
                $row = $this->db->query('SELECT SCOPE_IDENTITY() AS id')->row_array();
                $business_entity_id = (int) $row['id'];
            }
            if ($business_entity_id && $registration->InitialVesselName) {
                $existing_vessel = $this->db
                    ->where('BusinessEntityID', $business_entity_id)
                    ->where('RegistrationNo', $registration->InitialVesselRegistrationNo)
                    ->get('JbtVessel')->num_rows();
                if (!$existing_vessel) {
                    $this->db->insert('JbtVessel', array(
                        'BusinessEntityID' => $business_entity_id,
                        'VesselName' => $registration->InitialVesselName,
                        'RegistrationNo' => $registration->InitialVesselRegistrationNo,
                        'VesselType' => $registration->InitialVesselType,
                        'GrossTonnage' => $registration->InitialVesselGrossTonnage,
                        'IsActive' => 1,
                        'CreatedBy' => $this->session->userdata('MIS_LOGGED_ID'),
                        'CreatedAt' => date('Y-m-d H:i:s'),
                    ));
                }
            }
            if ($business_entity_id && $registration->BankAccountNo) {
                $account_no = preg_replace('/[^0-9]/', '', $registration->BankAccountNo);
                $authorized = $this->db->where('BusinessEntityID', $business_entity_id)
                    ->where('AccountNo', $account_no)->get('JbtAuthorizedTransferAccount')->row_array();
                $account_data = array(
                    'BankName' => $registration->BankName,
                    'AccountNo' => $account_no,
                    'AccountName' => $registration->BankAccountName,
                    'AuthorizedPersonName' => $registration->BankAccountName,
                    'AuthorizedPersonPosition' => 'Pemilik Rekening',
                    'VerificationStatus' => 'VERIFIED',
                    'IsActive' => 1,
                );
                if ($authorized) {
                    $this->db->where('AuthorizedAccountID', $authorized['AuthorizedAccountID'])
                        ->update('JbtAuthorizedTransferAccount', $account_data);
                } else {
                    $account_data['BusinessEntityID'] = $business_entity_id;
                    $account_data['CreatedBy'] = $this->session->userdata('MIS_LOGGED_ID');
                    $this->db->insert('JbtAuthorizedTransferAccount', $account_data);
                }
            }
        }
        $this->db->insert('Login', array(
            'UserID' => $registration->UserID,
            'UserName' => $registration->UserName,
            'Email' => $registration->Email,
            'NIB' => $registration->NIB,
            'BusinessEntityID' => $business_entity_id,
            'PasswordCI' => $registration->PasswordHash,
            'Password_dari_CI' => $registration->PasswordHash,
            'Password' => $registration->PasswordHash,
            'AksesCompany' => $this->config->item('Company'),
            'Active' => '1',
            'ChangePasswd' => '1',
            'UserRole' => $role,
            'SpbbID' => in_array($role, array('PEMILIK_SPBB', 'OPERATOR')) && count($spbb_ids) ? $spbb_ids[0] : null,
            'UserEntry' => $this->session->userdata('MIS_LOGGED_ID'),
            'TimeEntry' => date('Y-m-d H:i:s'),
        ));

        if (in_array($role, array('PEMILIK_SPBB', 'OPERATOR'))) {
            foreach ($spbb_ids as $spbb_id) {
                $this->db->insert('SpbbUserAccess', array(
                    'UserID' => $registration->UserID,
                    'SpbbID' => $spbb_id,
                    'CreatedBy' => $this->session->userdata('MIS_LOGGED_ID'),
                    'CreatedAt' => date('Y-m-d H:i:s'),
                ));
            }
        }

        $message = 'Registrasi akun iSmartEnergi Anda sudah disetujui. User ID: ' . $registration->UserID . '. Silakan login ke aplikasi.';
        $wa_url = $this->Spbb_registration_model->generate_whatsapp_url($registration->Phone, $message);
        $email_sent = $this->send_confirmation_email($registration->Email, $registration->UserName, $message);

        $this->Spbb_registration_model->update($id, array(
            'Status' => 'APPROVED',
            'RequestedRole' => $role,
            'RequestedSpbbIDs' => in_array($role, array('PEMILIK_SPBB', 'OPERATOR')) ? implode(',', $spbb_ids) : '',
            'ApprovalNotes' => $this->input->post('ApprovalNotes', true),
            'ApprovedBy' => $this->session->userdata('MIS_LOGGED_ID'),
            'ApprovedAt' => date('Y-m-d H:i:s'),
            'ConfirmationMessage' => $message,
            'EmailSent' => $email_sent ? 1 : 0,
            'WhatsappUrl' => $wa_url,
        ));

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('Gagal', 'Gagal approve registrasi');
            redirect('Superadmin_registration');
        }

        $this->db->trans_commit();
        $this->session->set_flashdata('Berhasil', 'Registrasi disetujui. Akun login sudah dibuat.');
        redirect('Superadmin_registration');
    }

    public function reject($id)
    {
        $registration = $this->Spbb_registration_model->find($id);
        if (!$registration || $registration->Status !== 'PENDING') {
            $this->session->set_flashdata('Gagal', 'Data registrasi tidak valid atau sudah diproses');
            redirect('Superadmin_registration');
        }

        $message = 'Registrasi akun iSmartEnergi Anda belum dapat disetujui. Catatan: ' . $this->input->post('ApprovalNotes', true);
        $wa_url = $this->Spbb_registration_model->generate_whatsapp_url($registration->Phone, $message);
        $email_sent = $this->send_confirmation_email($registration->Email, $registration->UserName, $message);

        $this->Spbb_registration_model->update($id, array(
            'Status' => 'REJECTED',
            'ApprovalNotes' => $this->input->post('ApprovalNotes', true),
            'ApprovedBy' => $this->session->userdata('MIS_LOGGED_ID'),
            'ApprovedAt' => date('Y-m-d H:i:s'),
            'ConfirmationMessage' => $message,
            'EmailSent' => $email_sent ? 1 : 0,
            'WhatsappUrl' => $wa_url,
        ));

        $this->session->set_flashdata('Berhasil', 'Registrasi ditolak.');
        redirect('Superadmin_registration');
    }

    public function approve_customer_user($id)
    {
        $request = $this->db->where('UserRequestID', (int) $id)
            ->where('Status', 'PENDING')->get('JbtCustomerUserRequest')->row_array();
        if (!$request || $this->db->where('UserID', $request['UserID'])->get('Login')->num_rows()) {
            $this->session->set_flashdata('Gagal', 'Pengajuan tidak valid, sudah diproses, atau User ID telah digunakan.');
            redirect('Superadmin_registration#customer-user-requests');
        }
        $entity = $this->db->where('BusinessEntityID', $request['BusinessEntityID'])
            ->where('IsActive', 1)->get('JbtBusinessEntity')->row_array();
        if (!$entity) {
            $this->session->set_flashdata('Gagal', 'Badan usaha pengaju tidak aktif atau tidak ditemukan.');
            redirect('Superadmin_registration#customer-user-requests');
        }
        $this->db->trans_begin();
        $this->db->insert('Login', array(
            'UserID' => $request['UserID'],
            'UserName' => substr($request['UserName'], 0, 30),
            'Email' => $request['Email'],
            'NIB' => $entity['NIB'],
            'BusinessEntityID' => $entity['BusinessEntityID'],
            'PasswordCI' => $request['PasswordHash'],
            'Password_dari_CI' => $request['PasswordHash'],
            'Password' => $request['PasswordHash'],
            'AksesCompany' => $this->config->item('Company'),
            'Active' => '1',
            'ChangePasswd' => '1',
            'UserRole' => 'CUSTOMER',
            'Jabatan' => substr((string) $request['PositionName'], 0, 50),
            'UserEntry' => $this->session->userdata('MIS_LOGGED_ID'),
            'TimeEntry' => date('Y-m-d H:i:s'),
        ));
        $this->load->library('Pipeline_user_policy',array(),'user_policy');
        $this->db->insert('PipelineUserSecurity',array('UserID'=>$request['UserID'],'AccessVersion'=>1,'UpdatedBy'=>$this->session->userdata('MIS_LOGGED_ID')));
        foreach($this->user_policy->defaults('CUSTOMER') as $menu)$this->db->insert('PipelineMenuAccess',array('UserID'=>$request['UserID'],'MenuKey'=>$menu,'Allowed'=>1));
        $this->db->where('UserRequestID', $id)->update('JbtCustomerUserRequest', array(
            'Status' => 'APPROVED',
            'AdminNotes' => $this->input->post('AdminNotes', true),
            'ReviewedBy' => $this->session->userdata('MIS_LOGGED_ID'),
            'ReviewedAt' => date('Y-m-d H:i:s'),
        ));
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('Gagal', 'Gagal membuat akun customer.');
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('Berhasil', 'Pengajuan user disetujui dan akun customer sudah aktif.');
        }
        redirect('Superadmin_registration#customer-user-requests');
    }

    public function reject_customer_user($id)
    {
        $request = $this->db->where('UserRequestID', (int) $id)
            ->where('Status', 'PENDING')->get('JbtCustomerUserRequest')->row_array();
        if (!$request) {
            $this->session->set_flashdata('Gagal', 'Pengajuan tidak valid atau sudah diproses.');
        } else {
            $this->db->where('UserRequestID', $id)->update('JbtCustomerUserRequest', array(
                'Status' => 'REJECTED',
                'AdminNotes' => $this->input->post('AdminNotes', true),
                'ReviewedBy' => $this->session->userdata('MIS_LOGGED_ID'),
                'ReviewedAt' => date('Y-m-d H:i:s'),
            ));
            $this->session->set_flashdata('Berhasil', 'Pengajuan user ditolak.');
        }
        redirect('Superadmin_registration#customer-user-requests');
    }

    private function send_confirmation_email($email, $name, $message)
    {
        if (!$email) {
            return false;
        }

        $this->load->library('email');
        $this->email->from('no-reply@ismartenergi.local', 'iSmartEnergi');
        $this->email->to($email);
        $this->email->subject('Konfirmasi Registrasi iSmartEnergi');
        $this->email->message('Halo ' . $name . ",\n\n" . $message . "\n\nTerima kasih.");
        return @$this->email->send();
    }

    private function is_superadmin()
    {
        return $this->session->userdata('USER_ROLE') === 'SUPERADMIN'
            || strtolower($this->session->userdata('MIS_LOGGED_ID')) === 'admin';
    }
}
