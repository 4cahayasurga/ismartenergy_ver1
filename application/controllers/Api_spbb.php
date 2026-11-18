<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api_spbb extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Users', 'Spbb_model'));
    }

    public function login()
    {
        $input = $this->json_input();
        $username = isset($input['username']) ? $input['username'] : $this->input->post('username', true);
        $password = isset($input['password']) ? $input['password'] : $this->input->post('password', true);
        $user = $this->Users->getUserByUsername($username);

        if (!$user || $user->Active != '1' || !password_verify($password, $user->PasswordCI)) {
            return $this->json(array('success' => false, 'message' => 'Username atau password tidak sesuai'), 401);
        }

        $token = $this->Spbb_model->create_token($user->UserID);
        $role = $this->Spbb_model->get_role($user->UserID);
        return $this->json(array(
            'success' => true,
            'token' => $token,
            'user' => array(
                'UserID' => $user->UserID,
                'UserName' => $user->UserName,
                'Role' => $role['role'],
                'SpbbID' => $role['spbb_id'],
                'SpbbIDs' => $role['spbb_ids'],
            ),
        ));
    }

    public function catalog()
    {
        $user = $this->require_token();
        if (!$user) {
            return;
        }
        $role = isset($user['UserRole']) ? $user['UserRole'] : '';
        return $this->json(array(
            'success' => true,
            'spbb' => $this->Spbb_model->spbb_list(),
            'fuel' => $this->Spbb_model->fuel_list(isset($user['SpbbIDs']) ? $user['SpbbIDs'] : (isset($user['SpbbID']) ? $user['SpbbID'] : null)),
            'payment_methods' => $this->Spbb_model->payment_methods(),
            'vessels' => $role === 'CUSTOMER' ? $this->Spbb_model->customer_vessels($user['UserID']) : array(),
            'company_account' => $role === 'CUSTOMER' ? $this->Spbb_model->customer_company_account($user['UserID']) : null,
            'authorized_accounts' => $role === 'CUSTOMER' ? $this->Spbb_model->authorized_transfer_accounts($user['UserID']) : array(),
            'document_status' => $role === 'CUSTOMER' ? $this->Spbb_model->customer_document_status($user['UserID']) : null,
            'onboarding' => $role === 'CUSTOMER' ? $this->Spbb_model->customer_onboarding($user['UserID']) : null,
        ));
    }

    public function orders()
    {
        $user = $this->require_token();
        if (!$user) {
            return;
        }
        $where = $user['UserRole'] === 'PEMILIK_SPBB' && isset($user['SpbbIDs']) && count($user['SpbbIDs'])
            ? array('O.SpbbID' => $user['SpbbIDs'])
            : array('O.CustomerID' => $user['UserID']);
        return $this->json(array('success' => true, 'orders' => $this->Spbb_model->order_summary($where)));
    }

    public function create_order()
    {
        $user = $this->require_token();
        if (!$user) {
            return;
        }
        if ($user['UserRole'] !== 'CUSTOMER') {
            return $this->json(array('success' => false, 'message' => 'Hanya customer yang dapat membuat pesanan'), 403);
        }
        return $this->json(array('success'=>false,'message'=>'Pemesanan baru menggunakan menu Pesanan BBM Pipanisasi pada aplikasi Ver.1.'),409);
    }

    public function approve_order($order_id)
    {
        $user = $this->require_token();
        if (!$user) {
            return;
        }
        if ($user['UserRole'] !== 'PEMILIK_SPBB') {
            return $this->json(array('success' => false, 'message' => 'Akses ditolak'), 403);
        }
        $order = $this->Spbb_model->find_order($order_id);
        $spbb_ids = isset($user['SpbbIDs']) ? $user['SpbbIDs'] : array();
        if (!$order || !count($spbb_ids) || !in_array((int) $order['SpbbID'], $spbb_ids)) {
            return $this->json(array('success' => false, 'message' => 'Akses SPBB tidak sesuai'), 403);
        }
        $input = $this->json_input();
        $status = isset($input['Status']) ? strtoupper($input['Status']) : 'APPROVED';
        if ($status === 'APPROVED' &&
            in_array($order['PaymentMethod'], array('TRANSFER', 'QRIS'), true) &&
            $order['OcrValidationStatus'] !== 'AUTOMATIC_MATCH') {
            return $this->json(array(
                'success' => false,
                'message' => 'Pesanan tidak dapat disetujui karena bukti pembayaran belum lolos validasi otomatis rekening, nama pemilik, nominal, status transfer, dan referensi.',
            ), 422);
        }
        $ok = $this->Spbb_model->approve_order(
            $order_id,
            $user['UserID'],
            $status,
            isset($input['OwnerNotes']) ? $input['OwnerNotes'] : ''
        );
        return $this->json(array('success' => $ok));
    }

    public function upload_payment($order_id)
    {
        $user = $this->require_token();
        if (!$user) {
            return;
        }
        if ($user['UserRole'] !== 'CUSTOMER') {
            return $this->json(array('success' => false, 'message' => 'Hanya customer yang dapat upload bukti bayar'), 403);
        }
        $order = $this->Spbb_model->find_order((int) $order_id);
        if (!$order || $order['CustomerID'] !== $user['UserID'] ||
            !in_array($order['OrderStatus'], array('MENUNGGU_BUKTI_BAYAR', 'BUKTI_TIDAK_SESUAI'), true)) {
            return $this->json(array('success' => false, 'message' => 'Pesanan tidak ditemukan atau tidak dapat menerima bukti bayar'), 404);
        }

        $origin_account_no = preg_replace('/[^0-9]/', '', (string) $this->input->post('OriginAccountNo', true));
        $origin_bank_name = trim((string) $this->input->post('OriginBankName', true));
        $origin_account_name = trim((string) $this->input->post('OriginAccountName', true));
        $authorized_accounts = $this->Spbb_model->authorized_transfer_accounts($user['UserID']);
        $matched_account = null;
        foreach ($authorized_accounts as $authorized_account) {
            if ($origin_account_no !== '' && $origin_account_no === preg_replace('/[^0-9]/', '', $authorized_account['AccountNo'])) {
                $matched_account = $authorized_account;
                break;
            }
        }
        if (!$matched_account) {
            return $this->json(array('success' => false, 'message' => 'Nomor rekening asal bukan rekening transfer yang aktif dan telah diverifikasi Admin.'), 422);
        }
        if (strcasecmp($origin_bank_name, trim($matched_account['BankName'])) !== 0) {
            return $this->json(array('success' => false, 'message' => 'Nama bank asal tidak sesuai dengan data rekening yang telah diverifikasi.'), 422);
        }
        if (strcasecmp(preg_replace('/\s+/', ' ', $origin_account_name), preg_replace('/\s+/', ' ', trim($matched_account['AccountName']))) !== 0) {
            return $this->json(array('success' => false, 'message' => 'Nama pemilik rekening asal tidak sesuai dengan data rekening yang telah diverifikasi.'), 422);
        }

        $payment_upload_path = FCPATH . 'uploads/spbb_payments/';
        if (!is_dir($payment_upload_path)) @mkdir($payment_upload_path, 0755, true);
        $config = array(
            'upload_path' => $payment_upload_path,
            'allowed_types' => 'jpg|jpeg|png',
            'max_size' => 4096,
            'encrypt_name' => true,
        );
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('ProofFile')) {
            return $this->json(array('success' => false, 'message' => strip_tags($this->upload->display_errors())), 422);
        }
        $file = $this->upload->data('file_name');
        $ocr = $this->Spbb_model->validate_payment_proof_ocr(
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
        list($ok, $message) = $this->Spbb_model->upload_payment_proof_with_account((int) $order_id, $file, $user['UserID'], array(
            'OriginBankName' => $origin_bank_name,
            'OriginAccountNo' => $origin_account_no,
            'OriginAccountName' => $origin_account_name,
        ), $ocr);
        if (!$ok && is_file($payment_upload_path . $file)) @unlink($payment_upload_path . $file);
        return $this->json(array(
            'success' => $ok && $ocr['status'] === 'AUTOMATIC_MATCH',
            'uploaded' => $ok,
            'file' => $file,
            'ocr_status' => $ocr['status'],
            'message' => $message,
        ), $ok ? 200 : 422);
    }

    private function require_token()
    {
        $header = $this->input->get_request_header('Authorization', true);
        $token = trim(str_replace('Bearer', '', $header));
        $user = $token ? $this->Spbb_model->token_user($token) : null;
        if (!$user) {
            $this->json(array('success' => false, 'message' => 'Token tidak valid'), 401);
            return false;
        }
        if (in_array($user['UserRole'], array('TRANSPORTIR','ASDP','IFPRO'), true)) {
            $this->json(array('success' => false, 'message' => 'Gunakan Portal Pipanisasi.'), 403);
            return false;
        }
        return $user;
    }

    private function json_input()
    {
        $raw = $this->input->raw_input_stream;
        $json = json_decode($raw, true);
        return is_array($json) ? $json : array();
    }

    private function json($payload, $status = 200)
    {
        return $this->output
            ->set_status_header($status)
            ->set_content_type('application/json')
            ->set_output(json_encode($payload));
    }
}
