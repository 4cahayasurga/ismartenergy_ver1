<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Spbb extends CI_Controller
{
    private $user_id;
    private $role;
    private $spbb_id;
    private $spbb_ids;

    public function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Spbb_model');
        $this->user_id = $this->session->userdata('MIS_LOGGED_ID');
        $role = $this->Spbb_model->get_role($this->user_id);
        $this->role = $role['role'];
        $this->spbb_id = $role['spbb_id'];
        $this->spbb_ids = isset($role['spbb_ids']) ? $role['spbb_ids'] : array();
        $this->load->library('Pipeline_access');if(!$this->session->userdata('pipeline_form_token'))$this->session->set_userdata('pipeline_form_token',bin2hex(random_bytes(24)));
        $method=strtolower($this->router->fetch_method());$map=array('customer_dashboard'=>'customer_dashboard','customer'=>'customer_order','customer_order'=>'customer_order','customer_pipeline'=>'customer_order','create_order'=>'customer_order','customer_vessels'=>'customer_vessels','save_customer_vessel'=>'customer_vessels','toggle_customer_vessel'=>'customer_vessels','request_vessel_quota'=>'customer_vessels','customer_documents'=>'customer_documents','upload_customer_documents'=>'customer_documents','customer_users'=>'customer_users','request_customer_user'=>'customer_users','customer_history'=>'customer_history','confirm_receipt'=>'customer_history','upload_payment'=>'customer_history');
        if(isset($map[$method])){$this->pipeline_access->require_menu($map[$method]);if($this->role!=='CUSTOMER')show_error('Menu khusus akun Customer.',403);if(!in_array($method,array('customer_dashboard','customer','customer_order','customer_pipeline','customer_vessels','customer_documents','customer_users','customer_history'),true)){if($this->input->method()!=='post')show_error('Gunakan form yang tersedia.',405);$token=$this->input->post('pipeline_token');if(!is_string($token)||!hash_equals($this->session->userdata('pipeline_form_token'),$token))show_error('Form kedaluwarsa. Muat ulang halaman.',403);}}

    }

    public function index()
    {
        if ($this->role === 'PEMILIK_SPBB') {
            redirect('Spbb/owner');
        }
        if ($this->role === 'OPERATOR') {
            redirect('Spbb_operator');
        }
        redirect('Spbb/customer_dashboard');
    }

    public function customer()
    {
        redirect('Spbb/customer_order');
    }

    public function customer_dashboard()
    {
        $this->customer_only();
        $orders = $this->Spbb_model->order_summary(array('O.CustomerID' => $this->user_id));
        $summary = array(
            'total' => count($orders),
            'pending' => 0,
            'need_payment' => 0,
            'ready' => 0,
            'rejected' => 0,
        );

        foreach ($orders as $order) {
            if ($order['OrderStatus'] === 'MENUNGGU_BUKTI_BAYAR') {
                $summary['need_payment']++;
            }
            if ($order['OwnerApprovalStatus'] === 'PENDING' || $order['OrderStatus'] === 'MENUNGGU_APPROVAL') {
                $summary['pending']++;
            }
            if (in_array($order['OrderStatus'], array('SIAP_PENGISIAN', 'SEDANG_PENGISIAN'))) {
                $summary['ready']++;
            }
            if ($order['OrderStatus'] === 'DITOLAK') {
                $summary['rejected']++;
            }
        }

        $data = array(
            'judul' => 'Dashboard Customer',
            'orders' => array_slice($orders, 0, 8),
            'summary' => $summary,
            'onboarding' => $this->Spbb_model->customer_onboarding($this->user_id),
        );
        $this->render('spbb/customer_dashboard', $data);
    }

    public function customer_order($channel = 'SPBB')
    {
        $this->customer_only();
        redirect('Pipeline_flow');
    }

    public function customer_pipeline()
    {
        $this->customer_order('PIPANISASI');
    }

    public function customer_vessels()
    {
        $this->customer_only();
        $role = $this->Spbb_model->get_role($this->user_id);
        $data = array(
            'judul' => 'Master Data Kapal',
            'vessels' => $this->Spbb_model->vessel_realization($this->user_id),
            'active_vessels' => $this->Spbb_model->customer_vessels($this->user_id),
            'spbb_list' => $this->Spbb_model->spbb_list(),
            'requests' => $this->db->where('RequestedBy', $this->user_id)->order_by('RequestID', 'DESC')->get('JbtVesselQuotaRequest')->result_array(),
            'business_entity_id' => $role['business_entity_id'],
        );
        $this->load->model('Pipeline_customer_vessel_model','customer_vessel');
        $data['fields']=$this->customer_vessel->fields();
        $data['routes']=$this->customer_vessel->route_options();
        $data['vesselSettings']=$this->customer_vessel->settings();
        $this->render('spbb/customer_vessels', $data);
    }

    public function request_vessel_quota()
    {
        $this->customer_only();
        $this->load->model('Pipeline_customer_vessel_model','customer_vessel');
        if (!$this->customer_vessel->settings()['show_quota']) show_error('Pengajuan kuota sedang dinonaktifkan.',403);
        $role = $this->Spbb_model->get_role($this->user_id);
        $entity_id = isset($role['business_entity_id']) ? (int) $role['business_entity_id'] : 0;
        $vessel_id = (int) $this->input->post('VesselID', true);
        $vessel = $this->db->where('VesselID', $vessel_id)->where('BusinessEntityID', $entity_id)
            ->where('IsActive', 1)->get('JbtVessel')->row_array();
        $quota = (float) $this->input->post('SubmittedTotalQuota', true);
        $spbb_id = (int) $this->input->post('RequestedSpbbID', true);
        $decree_no = trim($this->input->post('SubmittedDecreeNo', true));
        $decree_date = trim($this->input->post('SubmittedDecreeDate', true));
        $issuer = trim($this->input->post('DecreeIssuer', true));
        if (!$entity_id) {
            $this->session->set_flashdata('Gagal', 'Akun customer belum terhubung ke badan usaha/NIB. Admin harus menghubungkan akun ini melalui Basic Data Kuota JBT sebelum kapal dapat diajukan.');
        } elseif (!$vessel) {
            $this->session->set_flashdata('Gagal', 'Pilih kapal aktif milik perusahaan Anda.');
        } elseif (!$spbb_id) {
            $this->session->set_flashdata('Gagal', 'Lokasi SPBB/Fuel Terminal wajib dipilih.');
        } elseif ($quota <= 0) {
            $this->session->set_flashdata('Gagal', 'Jumlah kuota sesuai SK harus lebih dari 0 liter.');
        } elseif ($decree_no === '' || $decree_date === '' || !in_array($issuer, array('BPH MIGAS', 'PERTAMINA'), true)) {
            $this->session->set_flashdata('Gagal', 'Nomor, tanggal, dan penerbit SK kuota wajib diisi.');
        } elseif (empty($_FILES['DecreeDocument']['name'])) {
            $this->session->set_flashdata('Gagal', 'Dokumen SK kuota dari Pertamina/BPH wajib dilampirkan.');
        } else {
            $path = FCPATH . 'uploads/jbt_decrees/';
            if (!is_dir($path)) @mkdir($path, 0755, true);
            $this->load->library('upload');
            $this->upload->initialize(array(
                'upload_path' => $path, 'allowed_types' => 'pdf|jpg|jpeg|png',
                'max_size' => 5120, 'encrypt_name' => true,
            ));
            if (!$this->upload->do_upload('DecreeDocument')) {
                $this->session->set_flashdata('Gagal', strip_tags($this->upload->display_errors()));
                redirect('Spbb/customer_vessels');
            }
            $this->db->insert('JbtVesselQuotaRequest', array(
                'BusinessEntityID' => $entity_id, 'RequestedBy' => $this->user_id,
                'VesselID' => $vessel_id, 'VesselName' => $vessel['VesselName'],
                'RegistrationNo' => $vessel['RegistrationNo'], 'VesselType' => $vessel['VesselType'],
                'GrossTonnage' => $vessel['GrossTonnage'],
                'RequestedQuota' => $quota, 'RequestedSpbbID' => $spbb_id,
                'SubmittedDecreeNo' => $decree_no, 'SubmittedDecreeDate' => $decree_date,
                'DecreeIssuer' => $issuer, 'DecreeDocument' => $this->upload->data('file_name'),
                'SubmittedTotalQuota' => $quota,
            ));
            $this->session->set_flashdata('Berhasil', 'SK kuota berhasil diajukan untuk diverifikasi Admin.');
        }
        redirect('Spbb/customer_vessels');
    }

    public function save_customer_vessel()
    {
        $this->customer_only();
        $this->load->model('Pipeline_customer_vessel_model','customer_vessel');
        try {
            $this->customer_vessel->save(0,$this->input->post());
            $this->session->set_flashdata('Berhasil','Data kapal dan log tersimpan.');
        } catch (Exception $e) {
            $this->session->set_flashdata('Gagal',$e->getMessage());
        }
        redirect('Spbb/customer_vessels');
    }

    public function toggle_customer_vessel($vessel_id)
    {
        $this->customer_only();
        $role = $this->Spbb_model->get_role($this->user_id);
        $vessel = $this->db->where('VesselID', (int) $vessel_id)
            ->where('BusinessEntityID', (int) $role['business_entity_id'])->get('JbtVessel')->row_array();
        if (!$vessel) {
            $this->session->set_flashdata('Gagal', 'Kapal tidak ditemukan atau bukan milik perusahaan Anda.');
        } else {
            $this->db->where('VesselID', $vessel_id)->update('JbtVessel', array('IsActive' => $vessel['IsActive'] ? 0 : 1));
            $this->session->set_flashdata('Berhasil', 'Status kapal berhasil diperbarui.');
        }
        redirect('Spbb/customer_vessels');
    }

    public function confirm_receipt($order_id)
    {
        $this->customer_only();
        $ok = $this->Spbb_model->confirm_receipt((int) $order_id, $this->user_id, $this->input->post('ReceiptNotes', true));
        $this->session->set_flashdata($ok ? 'Berhasil' : 'Gagal', $ok ? 'Penerimaan pengisian berhasil dikonfirmasi.' : 'Pesanan tidak dapat dikonfirmasi.');
        redirect('Spbb/customer_history');
    }

    public function customer_documents()
    {
        $this->customer_only();
        $data = array(
            'judul' => 'Dokumen Legalitas Perusahaan',
            'document_status' => $this->Spbb_model->customer_document_status($this->user_id),
            'entity' => $this->db->select('B.*')->from('Login L')
                ->join('JbtBusinessEntity B', 'B.BusinessEntityID=L.BusinessEntityID', 'left')
                ->where('L.UserID', $this->user_id)->get()->row_array(),
        );
        $this->render('spbb/customer_documents', $data);
    }

    public function customer_users()
    {
        $this->customer_only();
        if ($this->pipeline_access->allowed('customer_team')) {
            redirect('Pipeline_customer_team');
            return;
        }
        $role = $this->Spbb_model->get_role($this->user_id);
        $entity_id = (int) $role['business_entity_id'];
        $data = array(
            'judul' => 'Pengajuan User Perusahaan',
            'business_entity_id' => $entity_id,
            'company' => $entity_id ? $this->db->where('BusinessEntityID', $entity_id)->get('JbtBusinessEntity')->row_array() : null,
            'users' => $entity_id ? $this->db->select('UserID,UserName,Email,Active')
                ->where('BusinessEntityID', $entity_id)->order_by('UserName')->get('Login')->result_array() : array(),
            'requests' => $entity_id ? $this->db->where('BusinessEntityID', $entity_id)
                ->order_by('UserRequestID', 'DESC')->get('JbtCustomerUserRequest')->result_array() : array(),
        );
        $this->render('spbb/customer_users', $data);
    }

    public function request_customer_user()
    {
        $this->customer_only();
        $role = $this->Spbb_model->get_role($this->user_id);
        $entity_id = (int) $role['business_entity_id'];
        $user_id = trim($this->input->post('UserID', true));
        $user_name = trim($this->input->post('UserName', true));
        $email = trim($this->input->post('Email', true));
        $password = (string) $this->input->post('Password', true);
        $confirm = (string) $this->input->post('ConfirmPassword', true);
        if (!$entity_id) {
            $this->session->set_flashdata('Gagal', 'Akun belum terhubung ke badan usaha.');
        } elseif (!preg_match('/^[A-Za-z0-9._-]{4,30}$/', $user_id)) {
            $this->session->set_flashdata('Gagal', 'User ID harus 4–30 karakter dan hanya boleh berisi huruf, angka, titik, garis bawah, atau strip.');
        } elseif ($user_name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->session->set_flashdata('Gagal', 'Nama user dan alamat email yang valid wajib diisi.');
        } elseif (strlen($password) < 6 || $password !== $confirm) {
            $this->session->set_flashdata('Gagal', 'Password minimal 6 karakter dan konfirmasinya harus sama.');
        } elseif ($this->db->where('UserID', $user_id)->get('Login')->num_rows() ||
            $this->db->where('UserID', $user_id)->where('Status', 'PENDING')->get('JbtCustomerUserRequest')->num_rows()) {
            $this->session->set_flashdata('Gagal', 'User ID sudah digunakan atau masih menunggu approval.');
        } else {
            $this->db->insert('JbtCustomerUserRequest', array(
                'BusinessEntityID' => $entity_id, 'RequestedBy' => $this->user_id,
                'UserID' => $user_id, 'UserName' => $user_name, 'Email' => $email,
                'Phone' => $this->input->post('Phone', true),
                'PositionName' => $this->input->post('PositionName', true),
                'PasswordHash' => password_hash($password, PASSWORD_DEFAULT),
            ));
            $this->session->set_flashdata('Berhasil', 'Pengajuan user dikirim dan menunggu approval Admin.');
        }
        redirect('Spbb/customer_users');
    }

    public function upload_customer_documents()
    {
        $this->customer_only();
        $role = $this->Spbb_model->get_role($this->user_id);
        $entity_id = (int) $role['business_entity_id'];
        if (!$entity_id) {
            $this->session->set_flashdata('Gagal', 'Akun belum terhubung ke badan usaha.');
            redirect('Spbb/customer_documents');
        }
        $path = FCPATH . 'uploads/company_legal/';
        if (!is_dir($path)) @mkdir($path, 0755, true);
        $fields = array('NibFile', 'NpwpFile', 'DeedFile', 'AhuFile');
        $updates = array();
        foreach ($fields as $field) {
            if (empty($_FILES[$field]['name'])) continue;
            $this->load->library('upload');
            $this->upload->initialize(array(
                'upload_path' => $path, 'allowed_types' => 'pdf|jpg|jpeg|png',
                'max_size' => 5120, 'encrypt_name' => true,
            ));
            if (!$this->upload->do_upload($field)) {
                $this->session->set_flashdata('Gagal', strip_tags($this->upload->display_errors()));
                redirect('Spbb/customer_documents');
            }
            $updates[$field] = $this->upload->data('file_name');
        }
        if (!count($updates)) {
            $this->session->set_flashdata('Gagal', 'Pilih minimal satu dokumen untuk diupload.');
        } else {
            $this->db->where('BusinessEntityID', $entity_id)->update('JbtBusinessEntity', $updates);
            $this->session->set_flashdata('Berhasil', 'Dokumen legalitas berhasil diperbarui.');
        }
        redirect('Spbb/customer_documents');
    }

    public function customer_history()
    {
        $this->customer_only();
        $this->load->model('Pipeline_flow_model','flow');
        $pipeline_history=$this->pipeline_access->allowed('customer_order')
            ?array_values(array_filter($this->flow->listing(),function($row){
                return in_array($row['Status'],array('FILLING','FILLED','BA_APPROVAL','BASTB_APPROVAL','COMPLETED'),true);
            })):array();
        $data = array(
            'judul' => 'Riwayat Pengisian dan Status',
            'pipeline_history'=>$pipeline_history,
            'orders' => $this->Spbb_model->order_summary(array('O.CustomerID' => $this->user_id)),
            'authorized_accounts' => $this->Spbb_model->authorized_transfer_accounts($this->user_id),
        );
        $this->render('spbb/customer_history', $data);
    }

    public function owner($channel = 'SPBB')
    {
        $this->owner_only();
        $channel = strtoupper($channel) === 'PIPANISASI' ? 'PIPANISASI' : 'SPBB';
        $where = array('O.OrderChannel' => $channel);
        $global_admin = $this->is_global_admin();
        if (!$global_admin && count($this->spbb_ids)) {
            $where['O.SpbbID'] = $this->spbb_ids;
        }
        $effective_spbb_ids = $this->spbb_ids;
        if ($global_admin) {
            $effective_spbb_ids = array_map(function ($row) {
                return (int) $row['LedgerNo'];
            }, $this->db->select('LedgerNo')->where('Status', 'Aktif')->get('SPBB')->result_array());
        }

        $data = array(
            'judul' => 'Approval Pesanan Customer',
            'spbb_list' => $this->Spbb_model->spbb_list_by_ids($effective_spbb_ids),
            'fuel_list' => $this->Spbb_model->fuel_list($effective_spbb_ids),
            'orders' => $this->Spbb_model->order_summary($where),
            'purchases' => $this->Spbb_model->purchases($effective_spbb_ids),
            'quota_summary' => $this->Spbb_model->quota_summary($effective_spbb_ids),
            'terminal_summary' => $this->Spbb_model->jbt_terminal_summary($effective_spbb_ids),
            'vessel_ranking' => $this->Spbb_model->jbt_vessel_ranking($effective_spbb_ids),
            'owner_spbb_id' => $this->spbb_id,
            'owner_spbb_ids' => $this->spbb_ids,
            'order_channel' => $channel,
        );
        $this->render('spbb/owner', $data);
    }

    public function owner_pipeline()
    {
        $this->owner('PIPANISASI');
    }

    public function create_order()
    {
        redirect('Pipeline_flow/create');
    }

    public function upload_payment($order_id)
    {
        $this->customer_only();
        $order = $this->Spbb_model->find_order((int) $order_id);
        if (!$order || $order['CustomerID'] !== $this->user_id ||
            !in_array($order['OrderStatus'], array('MENUNGGU_BUKTI_BAYAR', 'BUKTI_TIDAK_SESUAI'), true)) {
            $this->session->set_flashdata('Gagal', 'Pesanan tidak ditemukan atau tidak dapat menerima bukti bayar');
            redirect('Spbb/customer_history');
        }

        $origin_account_no = preg_replace('/[^0-9]/', '', (string) $this->input->post('OriginAccountNo', true));
        $origin_bank_name = trim((string) $this->input->post('OriginBankName', true));
        $origin_account_name = trim((string) $this->input->post('OriginAccountName', true));
        $authorized_accounts = $this->Spbb_model->authorized_transfer_accounts($this->user_id);
        $matched_account = null;
        foreach ($authorized_accounts as $authorized_account) {
            if ($origin_account_no !== '' && $origin_account_no === preg_replace('/[^0-9]/', '', $authorized_account['AccountNo'])) {
                $matched_account = $authorized_account;
                break;
            }
        }
        if (!$matched_account) {
            $this->session->set_flashdata('Gagal', 'Nomor rekening asal bukan rekening transfer yang aktif dan telah diverifikasi Admin.');
            redirect('Spbb/customer_history');
        }
        if (strcasecmp($origin_bank_name, trim($matched_account['BankName'])) !== 0) {
            $this->session->set_flashdata('Gagal', 'Nama bank asal tidak sesuai dengan data rekening yang telah diverifikasi.');
            redirect('Spbb/customer_history');
        }
        if (strcasecmp(preg_replace('/\s+/', ' ', $origin_account_name), preg_replace('/\s+/', ' ', trim($matched_account['AccountName']))) !== 0) {
            $this->session->set_flashdata('Gagal', 'Nama pemilik rekening asal tidak sesuai dengan data rekening yang telah diverifikasi.');
            redirect('Spbb/customer_history');
        }

        $payment_upload_path = FCPATH . 'uploads/spbb_payments/';
        if (!is_dir($payment_upload_path) && !mkdir($payment_upload_path, 0755, true)) {
            $this->session->set_flashdata('Gagal', 'Folder penyimpanan bukti pembayaran tidak dapat dibuat. Hubungi administrator.');
            redirect('Spbb/customer_history');
        }
        if (!is_writable($payment_upload_path)) {
            $this->session->set_flashdata('Gagal', 'Folder bukti pembayaran tidak memiliki izin tulis. Hubungi administrator.');
            redirect('Spbb/customer_history');
        }
        $resolved_upload_path = realpath($payment_upload_path);
        if ($resolved_upload_path === false) {
            $this->session->set_flashdata('Gagal', 'Lokasi penyimpanan bukti pembayaran tidak valid. Hubungi administrator.');
            redirect('Spbb/customer_history');
        }
        $payment_upload_path = rtrim(str_replace('\\', '/', $resolved_upload_path), '/') . '/';

        $config = array(
            'upload_path' => $payment_upload_path,
            'allowed_types' => 'jpg|jpeg|png',
            'max_size' => 4096,
            'encrypt_name' => true,
        );
        $this->load->library('upload');
        $this->upload->initialize($config, true);

        if (!$this->upload->do_upload('ProofFile')) {
            $this->session->set_flashdata('Gagal', strip_tags($this->upload->display_errors()));
            redirect('Spbb/customer_history');
        }

        $file = $this->upload->data('file_name');
        $ocr = $this->validate_payment_proof_ocr(
            $payment_upload_path . $file,
            $matched_account['AccountNo'],
            $matched_account['AccountName'],
            (float) $order['TotalAmount'],
            $this->Spbb_model->receiving_accounts($order['SpbbID'], $order['OrderChannel'])
        );
        $ocr['reference_duplicate'] = false;
        if (!empty($ocr['reference_no'])) {
            $ocr['reference_duplicate'] = $this->db->where('OcrReferenceNo', $ocr['reference_no'])
                ->where('OrderID !=', (int) $order_id)->get('SpbbPayment')->num_rows() > 0;
            if ($ocr['reference_duplicate']) $ocr['status'] = 'AUTOMATIC_MISMATCH';
        }
        list($ok, $message) = $this->Spbb_model->upload_payment_proof_with_account((int) $order_id, $file, $this->user_id, array(
            'OriginBankName' => $this->input->post('OriginBankName', true),
            'OriginAccountNo' => $origin_account_no,
            'OriginAccountName' => $this->input->post('OriginAccountName', true),
        ), $ocr);
        if (!$ok && is_file($payment_upload_path . $file)) @unlink($payment_upload_path . $file);
        if ($ok && !empty($order['ProofFile']) && $order['ProofFile'] !== $file &&
            is_file($payment_upload_path . $order['ProofFile'])) {
            @unlink($payment_upload_path . $order['ProofFile']);
        }
        $flash_type = ($ok && $ocr['status'] === 'AUTOMATIC_MATCH') ? 'Berhasil' : 'Gagal';
        $this->session->set_flashdata($flash_type, $message);
        redirect('Spbb/customer_history');
    }

    private function validate_payment_proof_ocr($file_path, $account_no, $account_name, $expected_amount, $receiving_accounts)
    {
        $script = APPPATH . 'scripts/payment_ocr.ps1';
        $ocr_file_path = $file_path;
        $extension = pathinfo($file_path, PATHINFO_EXTENSION);
        $temporary_ocr_file = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR .
            'ismartenergy_payment_' . uniqid('', true) . '.' . $extension;
        if (@copy($file_path, $temporary_ocr_file)) {
            $ocr_file_path = $temporary_ocr_file;
        }
        $command = 'powershell.exe -NoProfile -ExecutionPolicy Bypass -File ' .
            escapeshellarg($script) . ' -ImagePath ' . escapeshellarg($ocr_file_path) . ' 2>&1';
        $text = '';
        $ocr_error = '';
        for ($attempt = 1; $attempt <= 3; $attempt++) {
            $output = trim((string) @shell_exec($command));
            $is_error = $output === '' ||
                stripos($output, 'payment_ocr.ps1 :') !== false ||
                stripos($output, 'MethodInvocationException') !== false ||
                stripos($output, 'Exception calling') !== false;
            if (!$is_error) {
                $text = $output;
                break;
            }
            $ocr_error = $output;
            if ($attempt < 3) usleep(350000);
        }
        if ($text === '') {
            if ($ocr_file_path !== $file_path && is_file($ocr_file_path)) @unlink($ocr_file_path);
            return array(
                'text' => $ocr_error !== '' ? $ocr_error : 'OCR tidak menghasilkan teks.',
                'account_no_found' => false, 'account_name_found' => false,
                'amount_found' => false, 'status' => 'OCR_ERROR',
                'expected_amount' => $expected_amount,
                'detected_amount' => null,
                'destination_account_found' => false, 'destination_name_found' => false,
                'transfer_success_found' => false, 'reference_found' => false,
                'source_account_masked' => false, 'validation_method' => 'OCR_ERROR',
                'reference_no' => '', 'reference_duplicate' => false,
            );
        }
        if ($ocr_file_path !== $file_path && is_file($ocr_file_path)) @unlink($ocr_file_path);

        $digits = preg_replace('/[^0-9]/', '', $text);
        $expected_account = preg_replace('/[^0-9]/', '', (string) $account_no);
        $amount_digits = preg_replace('/[^0-9]/', '', number_format($expected_amount, 0, '.', ''));
        $account_no_found = $expected_account !== '' && strpos($digits, $expected_account) !== false;
        $amount_found = $amount_digits !== '' && strpos($digits, $amount_digits) !== false;
        $detected_amount = null;
        $amount_text = preg_replace('/(?<=\d)\s+(?=\d)/', '', $text);
        $amount_candidates = array();
        if (preg_match_all('/(?:IDR|RP)\s*([0-9][0-9.,]*)/i', $amount_text, $amount_matches)) {
            foreach ($amount_matches[1] as $raw_amount) {
                $candidate = null;
                if (strpos($raw_amount, ',') !== false && strpos($raw_amount, '.') !== false) {
                    $candidate = strrpos($raw_amount, '.') > strrpos($raw_amount, ',')
                        ? (float) str_replace(',', '', $raw_amount)
                        : (float) str_replace(array('.', ','), array('', '.'), $raw_amount);
                } else {
                    $candidate = (float) preg_replace('/[^0-9]/', '', $raw_amount);
                }
                if ($candidate > 0) $amount_candidates[] = $candidate;
            }
        }
        if (count($amount_candidates)) {
            usort($amount_candidates, function ($a, $b) use ($expected_amount) {
                $difference_a = abs($a - $expected_amount);
                $difference_b = abs($b - $expected_amount);
                if ($difference_a == $difference_b) return 0;
                return $difference_a < $difference_b ? -1 : 1;
            });
            $detected_amount = $amount_candidates[0];
        }

        $normalized_text = strtoupper(preg_replace('/[^A-Za-z0-9]+/', ' ', $text));
        $name_tokens = array_filter(explode(' ', strtoupper(preg_replace('/[^A-Za-z0-9]+/', ' ', $account_name))), function ($token) {
            return strlen($token) >= 3;
        });
        $matched_tokens = 0;
        foreach ($name_tokens as $token) {
            if (strpos($normalized_text, $token) !== false) $matched_tokens++;
        }
        $account_name_found = count($name_tokens) > 0 && $matched_tokens === count($name_tokens);

        $source_context = '';
        if (preg_match('/DARI\s+REKENING(.{0,120})/i', $text, $source_match)) $source_context = $source_match[1];
        $source_prefix = substr($expected_account, 0, min(3, strlen($expected_account)));
        $source_suffix = substr($expected_account, -2);
        $source_account_masked = !$account_no_found && $source_prefix !== '' &&
            strpos(preg_replace('/[^0-9]/', '', $source_context), $source_prefix) !== false;
        if ($source_account_masked && $source_suffix !== '' && preg_match('/\*+/', $source_context)) {
            $context_digits = preg_replace('/[^0-9]/', '', $source_context);
            $source_account_masked = strpos($context_digits, $source_suffix) !== false || strlen($context_digits) <= 6;
        }

        $destination_account_found = false;
        $destination_name_found = false;
        foreach ($receiving_accounts as $receiving) {
            $destination_no = preg_replace('/[^0-9]/', '', $receiving['AccountNo']);
            if ($destination_no !== '' && strpos($digits, $destination_no) !== false) {
                $destination_account_found = true;
                $destination_tokens = array_filter(explode(' ', strtoupper(preg_replace('/[^A-Za-z0-9]+/', ' ', $receiving['AccountName']))), function ($token) {
                    return strlen($token) >= 3;
                });
                $destination_name_found = count($destination_tokens) > 0;
                foreach ($destination_tokens as $token) {
                    if (strpos($normalized_text, $token) === false) $destination_name_found = false;
                }
                if ($destination_name_found) break;
            }
        }
        $transfer_success_found = preg_match('/TRANSFER\s+BERHASIL|TRANSAKSI\s+BERHASIL|PEMBAYARAN\s+BERHASIL|SUCCESS(?:FUL)?/i', $text) === 1;
        $reference_no = '';
        $reference_text = preg_replace('/\s*-\s*/', '-', $text);
        if (preg_match('/[A-Z0-9]{4,}(?:-[A-Z0-9]{4,}){2,}/i', $reference_text, $reference_match)) {
            $reference_no = strtoupper($reference_match[0]);
        }
        $reference_found = preg_match('/REFERENSI|REFERENCE|\bREF\b/i', $text) === 1 && $reference_no !== '';
        $source_valid = $account_no_found || $source_account_masked;
        $destination_valid = count($receiving_accounts) > 0 && $destination_account_found && $destination_name_found;
        $all_match = $source_valid && $destination_valid && $amount_found && $transfer_success_found && $reference_found;
        $status = $all_match ? 'AUTOMATIC_MATCH' : (count($receiving_accounts) ? 'AUTOMATIC_MISMATCH' : 'RECEIVER_NOT_CONFIGURED');
        $validation_method = $account_no_found ? 'FULL_SOURCE_AND_DESTINATION' :
            ($source_account_masked ? 'MASKED_SOURCE_AND_DESTINATION' : 'DESTINATION_AND_METADATA');

        return array(
            'text' => $text, 'account_no_found' => $account_no_found,
            'account_name_found' => $account_name_found, 'amount_found' => $amount_found,
            'status' => $status, 'expected_amount' => $expected_amount,
            'detected_amount' => $detected_amount,
            'destination_account_found' => $destination_account_found,
            'destination_name_found' => $destination_name_found,
            'transfer_success_found' => $transfer_success_found,
            'reference_found' => $reference_found,
            'source_account_masked' => $source_account_masked,
            'validation_method' => $validation_method,
            'reference_no' => $reference_no, 'reference_duplicate' => false,
        );
    }

    public function approve_order($order_id)
    {
        $this->owner_only();
        $order = $this->Spbb_model->find_order($order_id);
        if (!$this->is_global_admin() && $this->role === 'PEMILIK_SPBB' && (!count($this->spbb_ids) || !$order || !in_array((int) $order['SpbbID'], $this->spbb_ids))) {
            $this->session->set_flashdata('Gagal', 'Akses approval pesanan ditolak');
            redirect('Spbb/owner');
        }
        if ($order && strtoupper($this->input->post('Status', true)) === 'APPROVED' &&
            in_array($order['PaymentMethod'], array('TRANSFER', 'QRIS'), true) &&
            $order['OcrValidationStatus'] !== 'AUTOMATIC_MATCH') {
            $this->session->set_flashdata('Gagal', 'Pesanan tidak dapat disetujui karena bukti pembayaran belum lolos validasi otomatis rekening, nama pemilik, dan nominal.');
            redirect($order['OrderChannel'] === 'PIPANISASI' ? 'Spbb/owner_pipeline' : 'Spbb/owner');
        }

        $ok = $this->Spbb_model->approve_order(
            $order_id,
            $this->user_id,
            $this->input->post('Status', true),
            $this->input->post('OwnerNotes', true)
        );
        $this->session->set_flashdata($ok ? 'Berhasil' : 'Gagal', $ok ? 'Status pesanan berhasil diperbarui' : 'Gagal memperbarui status');
        redirect('Spbb/owner');
    }

    public function complete_order($order_id)
    {
        $this->owner_only();
        $this->session->set_flashdata('Gagal', 'Penyelesaian pengisian hanya dapat dilakukan oleh Operator SPBB');
        redirect('Spbb/owner');
    }

    public function pending_notifications()
    {
        if ($this->role !== 'PEMILIK_SPBB') {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array('count' => 0, 'orders' => array())));
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array(
                'count' => $this->Spbb_model->pending_approval_count($this->spbb_ids),
                'orders' => $this->Spbb_model->pending_approval_orders($this->spbb_ids),
                'approval_url' => site_url('Spbb/owner#approval-pesanan'),
            )));
    }

    public function create_purchase()
    {
        redirect('Spbb_pertamina/save');
    }

    private function render($view, $data)
    {
        $data['pipeline_token']=$this->session->userdata('pipeline_form_token');$this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view($view, $data);
        $this->load->view('templates/footer');
    }

    private function customer_only()
    {
        if ($this->role !== 'CUSTOMER') {
            redirect($this->role === 'OPERATOR' ? 'Spbb_operator' : 'Spbb/owner');
        }
    }

    private function owner_only()
    {
        if ($this->role !== 'PEMILIK_SPBB' && !$this->is_global_admin()) {
            redirect($this->role === 'OPERATOR' ? 'Spbb_operator' : 'Spbb/customer_dashboard');
        }
    }

    private function is_global_admin()
    {
        return $this->role === 'SUPERADMIN' || strtolower((string) $this->user_id) === 'admin';
    }
}
