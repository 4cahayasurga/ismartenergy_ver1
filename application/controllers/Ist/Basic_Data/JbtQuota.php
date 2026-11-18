<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class JbtQuota extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_login();
        $role = $this->session->userdata('USER_ROLE');
        if (!in_array($role, array('SUPERADMIN', 'PEMILIK_SPBB')) &&
            strtolower($this->session->userdata('MIS_LOGGED_ID')) !== 'admin') {
            show_error('Akses ditolak', 403);
        }
    }

    public function index()
    {
        $data['judul'] = 'Basic Data Kuota JBT';
        $data['entities'] = $this->db->order_by('CompanyName')->get('JbtBusinessEntity')->result_array();
        $data['unlinked_customers'] = $this->db->select('UserID,UserName,NIB')
            ->where('UserRole', 'CUSTOMER')->where('BusinessEntityID IS NULL', null, false)
            ->order_by('UserName')->get('Login')->result_array();
        $data['decrees'] = $this->db->select('D.*, B.CompanyName, B.NIB')
            ->from('JbtBphDecree D')->join('JbtBusinessEntity B', 'B.BusinessEntityID=D.BusinessEntityID')
            ->order_by('D.DecreeID', 'DESC')->get()->result_array();
        $data['vessels'] = $this->db->select('V.*, B.CompanyName')
            ->from('JbtVessel V')->join('JbtBusinessEntity B', 'B.BusinessEntityID=V.BusinessEntityID')
            ->order_by('V.VesselName')->get()->result_array();
        $data['spbb'] = $this->db->where('Status', 'Aktif')->order_by('No_SPBB')->get('SPBB')->result_array();
        $data['allocations'] = $this->allocation_rows();
        $data['quota_requests'] = $this->db->select('R.*,B.CompanyName,S.No_SPBB,S.Area')
            ->from('JbtVesselQuotaRequest R')
            ->join('JbtBusinessEntity B', 'B.BusinessEntityID=R.BusinessEntityID')
            ->join('SPBB S', 'S.LedgerNo=R.RequestedSpbbID', 'left')
            ->order_by('R.RequestID', 'DESC')->get()->result_array();
        $data['authorized_accounts'] = $this->db->select('A.*,B.CompanyName')
            ->from('JbtAuthorizedTransferAccount A')
            ->join('JbtBusinessEntity B', 'B.BusinessEntityID=A.BusinessEntityID')
            ->order_by('B.CompanyName')->get()->result_array();
        $data['receiving_accounts'] = $this->db->select('R.*,S.No_SPBB,S.Area')
            ->from('SpbbReceivingAccount R')->join('SPBB S', 'S.LedgerNo=R.SpbbID')
            ->order_by('S.No_SPBB')->get()->result_array();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('Ist/Basic_Data/JbtQuota', $data);
        $this->load->view('templates/footer');
    }

    public function save()
    {
        $decree_id = (int) $this->input->post('DecreeID', true);
        $vessel_id = (int) $this->input->post('VesselID', true);
        $spbb_id = (int) $this->input->post('SpbbID', true);
        $quota = (float) $this->input->post('AllocatedQuota', true);
        $available = $this->decree_available($decree_id);
        if (!$decree_id || !$vessel_id || !$spbb_id || $quota <= 0 || $quota > $available) {
            $this->session->set_flashdata('Gagal', 'Alokasi tidak valid atau melebihi sisa kuota SK BPH.');
        } else {
            $this->db->insert('JbtVesselQuota', array(
                'DecreeID' => $decree_id, 'VesselID' => $vessel_id, 'SpbbID' => $spbb_id,
                'AllocatedQuota' => $quota, 'CreatedBy' => $this->session->userdata('MIS_LOGGED_ID')
            ));
            $this->session->set_flashdata('Berhasil', 'Kuota kapal berhasil dialokasikan.');
        }
        redirect('Ist/Basic_Data/JbtQuota');
    }

    public function save_entity()
    {
        $nib = trim($this->input->post('NIB', true));
        $company_name = trim($this->input->post('CompanyName', true));
        $customer_user_id = trim($this->input->post('CustomerUserID', true));

        if ($nib === '' || $company_name === '') {
            $this->session->set_flashdata('Gagal', 'NIB dan nama badan usaha wajib diisi.');
            redirect('Ist/Basic_Data/JbtQuota');
        }

        $exists = $this->db->where('NIB', $nib)->get('JbtBusinessEntity')->row_array();
        if ($exists) {
            $this->session->set_flashdata('Gagal', 'NIB tersebut sudah terdaftar sebagai badan usaha.');
            redirect('Ist/Basic_Data/JbtQuota');
        }

        $this->db->insert('JbtBusinessEntity', array(
            'NIB' => $nib,
            'CompanyName' => $company_name,
            'CreatedBy' => $this->session->userdata('MIS_LOGGED_ID')
        ));
        $row = $this->db->query('SELECT SCOPE_IDENTITY() AS id')->row_array();
        $business_entity_id = (int) $row['id'];
        if ($customer_user_id !== '') {
            $this->db->where('UserID', $customer_user_id)
                ->where('UserRole', 'CUSTOMER')
                ->update('Login', array('NIB' => $nib, 'BusinessEntityID' => $business_entity_id));
        }

        $this->session->set_flashdata('Berhasil', 'Master badan usaha berhasil ditambahkan.');
        redirect('Ist/Basic_Data/JbtQuota');
    }

    public function save_decree()
    {
        $business_entity_id = (int) $this->input->post('BusinessEntityID', true);
        $decree_no = trim($this->input->post('DecreeNo', true));
        $decree_date = trim($this->input->post('DecreeDate', true));
        $period_year = (int) $this->input->post('PeriodYear', true);
        $total_quota = (float) $this->input->post('TotalQuota', true);

        if (!$business_entity_id || $decree_no === '' || $decree_date === '' || !$period_year || $total_quota <= 0) {
            $this->session->set_flashdata('Gagal', 'Data SK BPH belum lengkap atau kuota tidak valid.');
            redirect('Ist/Basic_Data/JbtQuota');
        }

        $exists = $this->db->where(array(
            'BusinessEntityID' => $business_entity_id,
            'DecreeNo' => $decree_no
        ))->get('JbtBphDecree')->row_array();

        if ($exists) {
            $this->session->set_flashdata('Gagal', 'Nomor SK BPH untuk badan usaha tersebut sudah ada.');
            redirect('Ist/Basic_Data/JbtQuota');
        }

        $this->db->insert('JbtBphDecree', array(
            'BusinessEntityID' => $business_entity_id,
            'DecreeNo' => $decree_no,
            'DecreeDate' => $decree_date,
            'PeriodYear' => $period_year,
            'TotalQuota' => $total_quota,
            'CreatedBy' => $this->session->userdata('MIS_LOGGED_ID')
        ));

        $this->session->set_flashdata('Berhasil', 'Master SK BPH berhasil ditambahkan.');
        redirect('Ist/Basic_Data/JbtQuota');
    }

    public function save_vessel()
    {
        $business_entity_id = (int) $this->input->post('BusinessEntityID', true);
        $vessel_name = trim($this->input->post('VesselName', true));
        $registration_no = trim($this->input->post('RegistrationNo', true));
        $vessel_type = trim($this->input->post('VesselType', true));
        $gross_tonnage = $this->input->post('GrossTonnage', true);

        if (!$business_entity_id || $vessel_name === '') {
            $this->session->set_flashdata('Gagal', 'Data kapal belum lengkap.');
            redirect('Ist/Basic_Data/JbtQuota');
        }

        $exists = $this->db->where(array(
            'BusinessEntityID' => $business_entity_id,
            'VesselName' => $vessel_name
        ))->get('JbtVessel')->row_array();

        if ($exists) {
            $this->session->set_flashdata('Gagal', 'Nama kapal untuk badan usaha tersebut sudah ada.');
            redirect('Ist/Basic_Data/JbtQuota');
        }

        $this->db->insert('JbtVessel', array(
            'BusinessEntityID' => $business_entity_id,
            'VesselName' => $vessel_name,
            'RegistrationNo' => $registration_no,
            'VesselType' => $vessel_type,
            'GrossTonnage' => $gross_tonnage === '' ? null : (float) $gross_tonnage,
            'IsActive' => 1,
            'CreatedBy' => $this->session->userdata('MIS_LOGGED_ID')
        ));

        $this->session->set_flashdata('Berhasil', 'Master kapal berhasil ditambahkan.');
        redirect('Ist/Basic_Data/JbtQuota');
    }

    public function toggle_vessel($vessel_id)
    {
        $vessel = $this->db->where('VesselID', (int) $vessel_id)->get('JbtVessel')->row_array();
        if (!$vessel) {
            $this->session->set_flashdata('Gagal', 'Data kapal tidak ditemukan.');
        } else {
            $this->db->where('VesselID', $vessel_id)->update('JbtVessel', array('IsActive' => $vessel['IsActive'] ? 0 : 1));
            $this->session->set_flashdata('Berhasil', 'Status aktif kapal berhasil diperbarui.');
        }
        redirect('Ist/Basic_Data/JbtQuota');
    }

    public function save_authorized_account()
    {
        $entity_id = (int) $this->input->post('BusinessEntityID', true);
        $account_no = preg_replace('/[^0-9]/', '', (string) $this->input->post('AccountNo', true));
        $data = array(
            'BusinessEntityID' => $entity_id,
            'BankName' => trim($this->input->post('BankName', true)),
            'AccountNo' => $account_no,
            'AccountName' => trim($this->input->post('AccountName', true)),
            'AuthorizedPersonName' => trim($this->input->post('AuthorizedPersonName', true)),
            'AuthorizedPersonPosition' => trim($this->input->post('AuthorizedPersonPosition', true)),
            'VerificationStatus' => 'VERIFIED',
            'IsActive' => 1,
            'CreatedBy' => $this->session->userdata('MIS_LOGGED_ID'),
        );
        if (!$entity_id || $account_no === '' || $data['BankName'] === '' || $data['AccountName'] === '' || $data['AuthorizedPersonName'] === '') {
            $this->session->set_flashdata('Gagal', 'Data rekening dan pihak yang berhak transfer wajib lengkap.');
        } elseif ($this->db->where('BusinessEntityID', $entity_id)->where('AccountNo', $account_no)->get('JbtAuthorizedTransferAccount')->num_rows()) {
            $this->session->set_flashdata('Gagal', 'Rekening tersebut sudah terdaftar.');
        } else {
            $this->db->insert('JbtAuthorizedTransferAccount', $data);
            $this->session->set_flashdata('Berhasil', 'Rekening transfer berwenang berhasil diverifikasi.');
        }
        redirect('Ist/Basic_Data/JbtQuota');
    }

    public function save_receiving_account()
    {
        $spbb_id = (int) $this->input->post('SpbbID', true);
        $channel = strtoupper(trim($this->input->post('OrderChannel', true)));
        $account_no = preg_replace('/[^0-9]/', '', (string) $this->input->post('AccountNo', true));
        $data = array(
            'SpbbID' => $spbb_id, 'OrderChannel' => $channel,
            'BankName' => trim($this->input->post('BankName', true)),
            'AccountNo' => $account_no,
            'AccountName' => trim($this->input->post('AccountName', true)),
            'IsActive' => 1, 'VerificationStatus' => 'VERIFIED',
            'CreatedBy' => $this->session->userdata('MIS_LOGGED_ID'),
        );
        if (!$spbb_id || !in_array($channel, array('SPBB','PIPANISASI'), true) ||
            $data['BankName'] === '' || $account_no === '' || $data['AccountName'] === '') {
            $this->session->set_flashdata('Gagal', 'Data rekening penerima belum lengkap.');
        } elseif ($this->db->where('SpbbID', $spbb_id)->where('OrderChannel', $channel)
            ->where('AccountNo', $account_no)->get('SpbbReceivingAccount')->num_rows()) {
            $this->session->set_flashdata('Gagal', 'Rekening penerima tersebut sudah terdaftar.');
        } else {
            $this->db->insert('SpbbReceivingAccount', $data);
            $this->session->set_flashdata('Berhasil', 'Rekening penerima pembayaran berhasil diverifikasi.');
        }
        redirect('Ist/Basic_Data/JbtQuota');
    }

    public function review_quota_request($request_id)
    {
        $request = $this->db->where('RequestID', (int) $request_id)
            ->where('Status', 'PENDING')->get('JbtVesselQuotaRequest')->row_array();
        $decision = strtoupper($this->input->post('Decision', true));
        $notes = trim($this->input->post('AdminNotes', true));
        if (!$request || !in_array($decision, array('APPROVED', 'REJECTED'), true)) {
            $this->session->set_flashdata('Gagal', 'Pengajuan tidak valid atau sudah diproses.');
            redirect('Ist/Basic_Data/JbtQuota');
        }
        if ($decision === 'REJECTED') {
            $this->db->where('RequestID', $request_id)->update('JbtVesselQuotaRequest', array(
                'Status' => 'REJECTED', 'AdminNotes' => $notes,
                'ReviewedBy' => $this->session->userdata('MIS_LOGGED_ID'), 'ReviewedAt' => date('Y-m-d H:i:s'),
            ));
            $this->session->set_flashdata('Berhasil', 'Pengajuan ditolak.');
            redirect('Ist/Basic_Data/JbtQuota');
        }
        if (empty($request['SubmittedDecreeNo']) || empty($request['SubmittedDecreeDate']) ||
            empty($request['DecreeIssuer']) || empty($request['DecreeDocument']) ||
            (float) $request['SubmittedTotalQuota'] <= 0) {
            $this->session->set_flashdata('Gagal', 'Pengajuan lama belum memiliki lampiran dan data SK resmi. Minta customer mengajukan ulang atau tolak pengajuan ini.');
            redirect('Ist/Basic_Data/JbtQuota');
        }
        $this->db->trans_begin();
        $decree = $this->db->where('BusinessEntityID', $request['BusinessEntityID'])
            ->where('DecreeNo', $request['SubmittedDecreeNo'])
            ->get('JbtBphDecree')->row_array();
        if ($decree) {
            $decree_id = (int) $decree['DecreeID'];
        } else {
            $this->db->insert('JbtBphDecree', array(
                'BusinessEntityID' => $request['BusinessEntityID'],
                'DecreeNo' => $request['SubmittedDecreeNo'],
                'DecreeDate' => $request['SubmittedDecreeDate'],
                'PeriodYear' => date('Y', strtotime($request['SubmittedDecreeDate'])),
                'TotalQuota' => $request['SubmittedTotalQuota'],
                'Issuer' => $request['DecreeIssuer'],
                'DocumentFile' => $request['DecreeDocument'],
                'VerificationSource' => 'CUSTOMER_SUBMISSION',
                'IsActive' => 1,
                'CreatedBy' => $this->session->userdata('MIS_LOGGED_ID'),
                'CreatedAt' => date('Y-m-d H:i:s'),
            ));
            $id = $this->db->query('SELECT SCOPE_IDENTITY() AS id')->row_array();
            $decree_id = (int) $id['id'];
        }
        if ((float) $request['RequestedQuota'] > $this->decree_available($decree_id)) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('Gagal', 'Kuota pada SK tersebut sudah teralokasi atau tidak mencukupi.');
            redirect('Ist/Basic_Data/JbtQuota');
        }
        $vessel = $this->db->where('BusinessEntityID', $request['BusinessEntityID'])
            ->where('RegistrationNo', $request['RegistrationNo'])->get('JbtVessel')->row_array();
        if ($vessel) {
            $vessel_id = (int) $vessel['VesselID'];
        } else {
            $this->db->insert('JbtVessel', array(
                'BusinessEntityID' => $request['BusinessEntityID'], 'VesselName' => $request['VesselName'],
                'RegistrationNo' => $request['RegistrationNo'], 'VesselType' => $request['VesselType'],
                'GrossTonnage' => $request['GrossTonnage'], 'IsActive' => 1,
                'CreatedBy' => $this->session->userdata('MIS_LOGGED_ID'), 'CreatedAt' => date('Y-m-d H:i:s'),
            ));
            $id = $this->db->query('SELECT SCOPE_IDENTITY() AS id')->row_array();
            $vessel_id = (int) $id['id'];
        }
        $this->db->insert('JbtVesselQuota', array(
            'DecreeID' => $decree_id, 'VesselID' => $vessel_id,
            'SpbbID' => $request['RequestedSpbbID'], 'AllocatedQuota' => $request['RequestedQuota'],
            'CreatedBy' => $this->session->userdata('MIS_LOGGED_ID'),
        ));
        $this->db->where('RequestID', $request_id)->update('JbtVesselQuotaRequest', array(
            'VesselID' => $vessel_id, 'DecreeID' => $decree_id, 'Status' => 'APPROVED',
            'AdminNotes' => $notes, 'ReviewedBy' => $this->session->userdata('MIS_LOGGED_ID'),
            'ReviewedAt' => date('Y-m-d H:i:s'),
        ));
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('Gagal', 'Gagal memproses pengajuan.');
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('Berhasil', 'Kapal dan alokasi kuota berhasil disetujui.');
        }
        redirect('Ist/Basic_Data/JbtQuota');
    }

    public function import()
    {
        if (empty($_FILES['file_excel']['tmp_name'])) {
            $this->session->set_flashdata('Gagal', 'Pilih file Excel terlebih dahulu.');
            redirect('Ist/Basic_Data/JbtQuota');
        }
        $ext = strtolower(pathinfo($_FILES['file_excel']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, array('xls', 'xlsx')) || $_FILES['file_excel']['size'] > 10485760) {
            $this->session->set_flashdata('Gagal', 'File harus XLS/XLSX maksimal 10 MB.');
            redirect('Ist/Basic_Data/JbtQuota');
        }
        try {
            $rows = IOFactory::load($_FILES['file_excel']['tmp_name'])->getActiveSheet()->toArray(null, true, true, true);
            $count = 0;
            foreach ($rows as $i => $row) {
                if ($i === 1 || empty($row['A']) || empty($row['D']) || (float) $row['H'] <= 0) continue;
                $entity = $this->find_or_create_entity(trim($row['A']), trim($row['B']));
                $decree = $this->find_or_create_decree($entity, trim($row['C']), $row);
                $vessel = $this->find_or_create_vessel($entity, trim($row['D']), trim($row['E']));
                $spbb = $this->db->where('No_SPBB', trim($row['G']))->get('SPBB')->row_array();
                if (!$spbb || (float) $row['H'] > $this->decree_available($decree)) continue;
                $this->db->insert('JbtVesselQuota', array(
                    'DecreeID'=>$decree, 'VesselID'=>$vessel, 'SpbbID'=>$spbb['LedgerNo'],
                    'AllocatedQuota'=>(float)$row['H'], 'CreatedBy'=>$this->session->userdata('MIS_LOGGED_ID')
                ));
                $count++;
            }
            $this->session->set_flashdata('Berhasil', $count . ' alokasi kuota berhasil diimpor.');
        } catch (Exception $e) {
            log_message('error', 'Import JBT: '.$e->getMessage());
            $this->session->set_flashdata('Gagal', 'File tidak dapat diproses atau datanya tidak valid.');
        }
        redirect('Ist/Basic_Data/JbtQuota');
    }

    private function allocation_rows()
    {
        return $this->db->query("SELECT Q.*,D.DecreeNo,D.PeriodYear,B.CompanyName,V.VesselName,
            S.No_SPBB,S.Area,(Q.AllocatedQuota-Q.BookedQuota-Q.UsedQuota) RemainingQuota,
            CASE WHEN Q.UsedQuota>Q.AllocatedQuota THEN 1 ELSE 0 END IsExceeded
            FROM JbtVesselQuota Q JOIN JbtBphDecree D ON D.DecreeID=Q.DecreeID
            JOIN JbtBusinessEntity B ON B.BusinessEntityID=D.BusinessEntityID
            JOIN JbtVessel V ON V.VesselID=Q.VesselID JOIN SPBB S ON S.LedgerNo=Q.SpbbID
            ORDER BY Q.UsedQuota/NULLIF(Q.AllocatedQuota,0) DESC,V.VesselName")->result_array();
    }

    private function decree_available($id)
    {
        $row = $this->db->query("SELECT D.TotalQuota-ISNULL(SUM(Q.AllocatedQuota),0) Available
            FROM JbtBphDecree D LEFT JOIN JbtVesselQuota Q ON Q.DecreeID=D.DecreeID
            WHERE D.DecreeID=? GROUP BY D.TotalQuota", array($id))->row_array();
        return $row ? (float)$row['Available'] : 0;
    }

    private function find_or_create_entity($nib, $name)
    {
        $row=$this->db->where('NIB',$nib)->get('JbtBusinessEntity')->row_array();
        if ($row) return $row['BusinessEntityID'];
        $this->db->insert('JbtBusinessEntity',array('NIB'=>$nib,'CompanyName'=>$name,'CreatedBy'=>$this->session->userdata('MIS_LOGGED_ID')));
        return $this->identity();
    }
    private function find_or_create_decree($entity, $no, $row)
    {
        $found=$this->db->where(array('BusinessEntityID'=>$entity,'DecreeNo'=>$no))->get('JbtBphDecree')->row_array();
        if ($found) return $found['DecreeID'];
        $this->db->insert('JbtBphDecree',array('BusinessEntityID'=>$entity,'DecreeNo'=>$no,'DecreeDate'=>date('Y-m-d',strtotime($row['F'])),'PeriodYear'=>date('Y',strtotime($row['F'])),'TotalQuota'=>(float)$row['I'],'CreatedBy'=>$this->session->userdata('MIS_LOGGED_ID')));
        return $this->identity();
    }
    private function find_or_create_vessel($entity, $name, $registration)
    {
        $found=$this->db->where(array('BusinessEntityID'=>$entity,'VesselName'=>$name))->get('JbtVessel')->row_array();
        if ($found) return $found['VesselID'];
        $this->db->insert('JbtVessel',array('BusinessEntityID'=>$entity,'VesselName'=>$name,'RegistrationNo'=>$registration,'CreatedBy'=>$this->session->userdata('MIS_LOGGED_ID')));
        return $this->identity();
    }
    private function identity()
    {
        $row=$this->db->query('SELECT SCOPE_IDENTITY() id')->row_array();
        return (int)$row['id'];
    }
}
