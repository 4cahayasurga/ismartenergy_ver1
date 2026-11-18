<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Spbb_model extends CI_Model
{
    const PAYMENT_TRANSFER = 'TRANSFER';
    const PAYMENT_QRIS = 'QRIS';
    const PAYMENT_TEMPO = 'TEMPO';
    const PAYMENT_OTHER = 'LAINNYA';

    public function __construct()
    {
        parent::__construct();
    }

    public function payment_methods()
    {
        return array(
            self::PAYMENT_TRANSFER => 'Transfer',
            self::PAYMENT_QRIS => 'QRIS',
            self::PAYMENT_TEMPO => 'Tempo',
            self::PAYMENT_OTHER => 'Selain Transfer/QRIS',
        );
    }

    public function get_role($user_id)
    {
        $user = $this->db->select('UserRole, SpbbID, BusinessEntityID')
            ->where('UserID', $user_id)
            ->get('Login')
            ->row_array();

        $spbb_ids = $this->user_spbb_ids($user_id);
        if (!count($spbb_ids) && isset($user['SpbbID']) && $user['SpbbID']) {
            $spbb_ids = array((int) $user['SpbbID']);
        }

        return array(
            'role' => isset($user['UserRole']) && $user['UserRole'] ? $user['UserRole'] : 'CUSTOMER',
            'spbb_id' => count($spbb_ids) ? $spbb_ids[0] : null,
            'spbb_ids' => $spbb_ids,
            'business_entity_id' => isset($user['BusinessEntityID']) ? (int) $user['BusinessEntityID'] : null,
        );
    }

    public function user_spbb_ids($user_id)
    {
        if (!$this->db->table_exists('SpbbUserAccess')) {
            return array();
        }

        $rows = $this->db->select('SpbbID')
            ->where('UserID', $user_id)
            ->get('SpbbUserAccess')
            ->result_array();

        $ids = array();
        foreach ($rows as $row) {
            if ($row['SpbbID']) {
                $ids[] = (int) $row['SpbbID'];
            }
        }

        return $ids;
    }

    public function spbb_list()
    {
        $rows = $this->db->where('Status', 'Aktif')
            ->order_by('No_SPBB', 'ASC')
            ->get('SPBB')
            ->result_array();

        foreach ($rows as &$row) {
            $row['SpbbID'] = $row['LedgerNo'];
            $row['SpbbName'] = trim($row['No_SPBB'] . ' - ' . $row['Area']);
        }

        return $rows;
    }

    public function spbb_list_by_ids($spbb_ids)
    {
        if (!is_array($spbb_ids) || !count($spbb_ids)) {
            return array();
        }

        $rows = $this->db->where('Status', 'Aktif')
            ->where_in('LedgerNo', $spbb_ids)
            ->order_by('No_SPBB', 'ASC')
            ->get('SPBB')
            ->result_array();

        foreach ($rows as &$row) {
            $row['SpbbID'] = $row['LedgerNo'];
            $row['SpbbName'] = trim($row['No_SPBB'] . ' - ' . $row['Area']);
        }

        return $rows;
    }

    public function fuel_list($spbb_id = null)
    {
        if (is_array($spbb_id)) {
            if (count($spbb_id)) {
                $this->db->where_in('SpbbID', $spbb_id);
            } else {
                $this->db->where('1 = 0');
            }
        } elseif ($spbb_id) {
            $this->db->where('SpbbID', $spbb_id);
        }

        $rows = $this->db->where('IsActive', 1)
            ->order_by('FuelName', 'ASC')
            ->get('SpbbFuelPrice')
            ->result_array();

        if (!count($rows) && $spbb_id && !is_array($spbb_id)) {
            $this->db->insert('SpbbFuelPrice', array(
                'SpbbID' => $spbb_id,
                'FuelCode' => 'BBM',
                'FuelName' => 'BBM',
                'UnitPrice' => 0,
                'IsActive' => 1,
                'CreatedAt' => date('Y-m-d H:i:s'),
            ));
            return $this->fuel_list($spbb_id);
        }

        return $rows;
    }

    public function material_list()
    {
        $this->db->select("F.*, (ISNULL(S.No_SPBB, '') + ' - ' + ISNULL(S.Area, '')) AS SpbbName");
        $this->db->from('SpbbFuelPrice F');
        $this->db->join('SPBB S', 'S.LedgerNo = F.SpbbID', 'inner');
        $this->db->where('S.Status', 'Aktif');
        $this->db->order_by('S.No_SPBB, F.FuelName');
        return $this->db->get()->result_array();
    }

    public function create_material_price($data)
    {
        $payload = array(
            'SpbbID' => $data['SpbbID'],
            'FuelCode' => $data['FuelCode'],
            'FuelName' => $data['FuelName'],
            'UnitPrice' => $data['UnitPrice'],
            'EffectiveDate' => $data['EffectiveDate'],
            'IsActive' => $data['IsActive'],
            'CreatedAt' => date('Y-m-d H:i:s'),
            'UpdatedBy' => $data['CreatedBy'],
            'UpdatedAt' => date('Y-m-d H:i:s'),
        );

        $this->db->trans_begin();
        $this->db->insert('SpbbFuelPrice', $payload);
        $fuel_id = $this->insert_id();
        $this->insert_price_history($fuel_id, $data['SpbbID'], $data['FuelName'], 0, $data['UnitPrice'], $data['EffectiveDate'], $data['CreatedBy']);

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }

    public function update_material_price($fuel_id, $data)
    {
        $old = $this->get_fuel($fuel_id);
        if (!$old) {
            return false;
        }

        $this->db->trans_begin();
        $this->db->where('FuelID', $fuel_id)->update('SpbbFuelPrice', array(
            'SpbbID' => $data['SpbbID'],
            'FuelCode' => $data['FuelCode'],
            'FuelName' => $data['FuelName'],
            'UnitPrice' => $data['UnitPrice'],
            'EffectiveDate' => $data['EffectiveDate'],
            'IsActive' => $data['IsActive'],
            'UpdatedBy' => $data['UpdatedBy'],
            'UpdatedAt' => date('Y-m-d H:i:s'),
        ));

        if ((float) $old['UnitPrice'] != (float) $data['UnitPrice'] || $old['EffectiveDate'] != $data['EffectiveDate']) {
            $this->insert_price_history($fuel_id, $data['SpbbID'], $data['FuelName'], $old['UnitPrice'], $data['UnitPrice'], $data['EffectiveDate'], $data['UpdatedBy']);
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }

    public function price_history()
    {
        $this->db->select("H.*, (ISNULL(S.No_SPBB, '') + ' - ' + ISNULL(S.Area, '')) AS SpbbName");
        $this->db->from('SpbbPriceHistory H');
        $this->db->join('SPBB S', 'S.LedgerNo = H.SpbbID', 'inner');
        $this->db->order_by('H.HistoryID', 'DESC');
        return $this->db->get()->result_array();
    }

    private function insert_price_history($fuel_id, $spbb_id, $fuel_name, $old_price, $new_price, $effective_date, $created_by)
    {
        return $this->db->insert('SpbbPriceHistory', array(
            'FuelID' => $fuel_id,
            'SpbbID' => $spbb_id,
            'FuelName' => $fuel_name,
            'OldPrice' => $old_price,
            'NewPrice' => $new_price,
            'EffectiveDate' => $effective_date,
            'CreatedBy' => $created_by,
            'CreatedAt' => date('Y-m-d H:i:s'),
        ));
    }

    public function get_fuel($fuel_id)
    {
        return $this->db->where('FuelID', $fuel_id)
            ->get('SpbbFuelPrice')
            ->row_array();
    }

    public function customer_vessels($customer_id)
    {
        return $this->db->select('V.VesselID,V.VesselName,V.RegistrationNo,V.VesselType,B.CompanyName')
            ->from('Login L')
            ->join('JbtBusinessEntity B', 'B.BusinessEntityID=L.BusinessEntityID')
            ->join('JbtVessel V', 'V.BusinessEntityID=B.BusinessEntityID')
            ->where('L.UserID', $customer_id)->where('V.IsActive', 1)
            ->order_by('V.VesselName')->get()->result_array();
    }

    public function customer_company_account($customer_id)
    {
        return $this->db->select('B.BankName,B.BankAccountNo,B.BankAccountName,B.CompanyName')
            ->from('Login L')
            ->join('JbtBusinessEntity B', 'B.BusinessEntityID=L.BusinessEntityID')
            ->where('L.UserID', $customer_id)
            ->get()->row_array();
    }

    public function customer_document_status($customer_id)
    {
        $row = $this->db->select('B.BusinessEntityID,B.CompanyName,B.NIB,B.NibFile,B.NpwpFile,B.DeedFile,B.AhuFile')
            ->from('Login L')->join('JbtBusinessEntity B', 'B.BusinessEntityID=L.BusinessEntityID', 'left')
            ->where('L.UserID', $customer_id)->get()->row_array();
        $row = is_array($row) ? $row : array();
        $documents = array(
            'NIB' => !empty($row['NibFile']),
            'NPWP' => !empty($row['NpwpFile']),
            'Akta' => !empty($row['DeedFile']),
            'AHU' => !empty($row['AhuFile']),
        );
        return array(
            'has_entity' => !empty($row['BusinessEntityID']),
            'documents' => $documents,
            'complete' => !in_array(false, $documents, true),
            'missing' => array_keys(array_filter($documents, function ($value) { return !$value; })),
        );
    }

    public function customer_onboarding($customer_id)
    {
        $document = $this->customer_document_status($customer_id);
        $vessels = $this->customer_vessels($customer_id);
        $quota_count = 0;
        if (count($vessels)) {
            $ids = array_column($vessels, 'VesselID');
            $quota_count = $this->db->where_in('VesselID', $ids)->count_all_results('JbtVesselQuota');
        }
        $steps = array(
            array('title' => 'Akun disetujui Admin', 'complete' => true, 'url' => site_url('Spbb/customer_dashboard')),
            array('title' => 'Profil badan usaha terhubung', 'complete' => $document['has_entity'], 'url' => site_url('Spbb/customer_vessels')),
            array('title' => 'Dokumen legalitas lengkap', 'complete' => $document['complete'], 'url' => site_url('Spbb/customer_documents')),
            array('title' => 'Minimal satu kapal aktif', 'complete' => count($vessels) > 0, 'url' => site_url('Spbb/customer_vessels')),
            array('title' => 'Kuota kapal disetujui', 'complete' => $quota_count > 0, 'url' => site_url('Spbb/customer_vessels')),
        );
        $done = count(array_filter($steps, function ($step) { return $step['complete']; }));
        return array('steps' => $steps, 'percent' => (int) round(100 * $done / count($steps)), 'document' => $document);
    }

    public function authorized_transfer_accounts($customer_id)
    {
        return $this->db->select('A.*')
            ->from('Login L')
            ->join('JbtAuthorizedTransferAccount A', 'A.BusinessEntityID=L.BusinessEntityID')
            ->where('L.UserID', $customer_id)
            ->where('A.IsActive', 1)
            ->where('A.VerificationStatus', 'VERIFIED')
            ->order_by('A.BankName')->get()->result_array();
    }

    public function receiving_accounts($spbb_id, $channel)
    {
        if (!$this->db->table_exists('SpbbReceivingAccount')) return array();
        return $this->db->where('SpbbID', (int) $spbb_id)
            ->where('OrderChannel', $channel)
            ->where('IsActive', 1)->where('VerificationStatus', 'VERIFIED')
            ->order_by('ReceivingAccountID', 'DESC')->get('SpbbReceivingAccount')->result_array();
    }

    public function validate_payment_proof_ocr($file_path, $account_no, $account_name, $expected_amount, $receiving_accounts)
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
        if ($ocr_file_path !== $file_path && is_file($ocr_file_path)) @unlink($ocr_file_path);
        if ($text === '') {
            return array(
                'text' => $ocr_error !== '' ? $ocr_error : 'OCR tidak menghasilkan teks.',
                'account_no_found' => false, 'account_name_found' => false,
                'amount_found' => false, 'status' => 'OCR_ERROR',
                'expected_amount' => $expected_amount, 'detected_amount' => null,
                'destination_account_found' => false, 'destination_name_found' => false,
                'transfer_success_found' => false, 'reference_found' => false,
                'source_account_masked' => false, 'validation_method' => 'OCR_ERROR',
                'reference_no' => '', 'reference_duplicate' => false,
            );
        }

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

    public function has_unconfirmed_receipt($customer_id)
    {
        return $this->db->where('CustomerID', $customer_id)
            ->where('OrderStatus', 'SELESAI')
            ->group_start()
                ->where('ReceiptConfirmationStatus IS NULL', null, false)
                ->or_where('ReceiptConfirmationStatus !=', 'CONFIRMED')
            ->group_end()
            ->count_all_results('SpbbOrder') > 0;
    }

    public function confirm_receipt($order_id, $customer_id, $notes)
    {
        $this->db->where('OrderID', (int) $order_id)
            ->where('CustomerID', $customer_id)
            ->where('OrderStatus', 'SELESAI')
            ->group_start()
                ->where('ReceiptConfirmationStatus IS NULL', null, false)
                ->or_where('ReceiptConfirmationStatus !=', 'CONFIRMED')
            ->group_end()
            ->update('SpbbOrder', array(
                'ReceiptConfirmationStatus' => 'CONFIRMED',
                'ReceiptConfirmedBy' => $customer_id,
                'ReceiptConfirmedAt' => date('Y-m-d H:i:s'),
                'ReceiptNotes' => $notes,
                'UpdatedBy' => $customer_id,
                'UpdatedAt' => date('Y-m-d H:i:s'),
            ));
        return $this->db->affected_rows() === 1;
    }

    public function vessel_realization($customer_id)
    {
        return $this->db->query("SELECT V.VesselID,V.VesselName,V.RegistrationNo,V.IsActive,B.CompanyName,
            SUM(ISNULL(Q.AllocatedQuota,0)) AllocatedQuota,
            SUM(ISNULL(Q.BookedQuota,0)) BookedQuota,
            SUM(ISNULL(Q.UsedQuota,0)) UsedQuota,
            SUM(ISNULL(Q.AllocatedQuota,0)-ISNULL(Q.BookedQuota,0)-ISNULL(Q.UsedQuota,0)) RemainingQuota,
            ISNULL(R.RealizedQuantity,0) RealizedQuantity,
            ISNULL(R.TransactionCount,0) TransactionCount,
            R.LastRealizationAt
            FROM Login L JOIN JbtBusinessEntity B ON B.BusinessEntityID=L.BusinessEntityID
            JOIN JbtVessel V ON V.BusinessEntityID=B.BusinessEntityID
            LEFT JOIN JbtVesselQuota Q ON Q.VesselID=V.VesselID
            LEFT JOIN (
                SELECT VesselID,SUM(ISNULL(ActualQuantity,0)) RealizedQuantity,
                    COUNT(OrderID) TransactionCount,MAX(CompletedAt) LastRealizationAt
                FROM SpbbOrder WHERE OrderStatus='SELESAI' GROUP BY VesselID
            ) R ON R.VesselID=V.VesselID
            WHERE L.UserID=?
            GROUP BY V.VesselID,V.VesselName,V.RegistrationNo,V.IsActive,B.CompanyName,
                R.RealizedQuantity,R.TransactionCount,R.LastRealizationAt
            ORDER BY V.VesselName", array($customer_id))->result_array();
    }

    public function jbt_terminal_summary($spbb_ids)
    {
        if (!is_array($spbb_ids) || !count($spbb_ids)) return array();
        $marks = implode(',', array_fill(0, count($spbb_ids), '?'));
        return $this->db->query("SELECT S.LedgerNo,S.No_SPBB,S.Area,
            COUNT(DISTINCT Q.VesselID) VesselCount,SUM(Q.AllocatedQuota) AllocatedQuota,
            SUM(Q.BookedQuota) BookedQuota,SUM(Q.UsedQuota) UsedQuota,
            SUM(Q.AllocatedQuota-Q.BookedQuota-Q.UsedQuota) RemainingQuota
            FROM JbtVesselQuota Q JOIN SPBB S ON S.LedgerNo=Q.SpbbID
            WHERE Q.SpbbID IN ($marks) GROUP BY S.LedgerNo,S.No_SPBB,S.Area
            ORDER BY SUM(Q.UsedQuota) DESC", $spbb_ids)->result_array();
    }

    public function jbt_vessel_ranking($spbb_ids)
    {
        if (!is_array($spbb_ids) || !count($spbb_ids)) return array();
        $marks = implode(',', array_fill(0, count($spbb_ids), '?'));
        return $this->db->query("SELECT V.VesselName,V.RegistrationNo,B.CompanyName,S.No_SPBB,S.Area,
            Q.AllocatedQuota,Q.BookedQuota,Q.UsedQuota,
            Q.AllocatedQuota-Q.BookedQuota-Q.UsedQuota RemainingQuota,
            100.0*Q.UsedQuota/NULLIF(Q.AllocatedQuota,0) UsagePercent
            FROM JbtVesselQuota Q JOIN JbtVessel V ON V.VesselID=Q.VesselID
            JOIN JbtBusinessEntity B ON B.BusinessEntityID=V.BusinessEntityID
            JOIN SPBB S ON S.LedgerNo=Q.SpbbID WHERE Q.SpbbID IN ($marks)
            ORDER BY 100.0*Q.UsedQuota/NULLIF(Q.AllocatedQuota,0) DESC", $spbb_ids)->result_array();
    }

    public function order_summary($where = array())
    {
        $this->db->select("O.*, (ISNULL(S.No_SPBB, '') + ' - ' + ISNULL(S.Area, '')) AS SpbbName, S.No_SPBB, F.FuelName, P.PaymentStatus, P.ProofFile,
            V.VesselName,V.RegistrationNo,V.VesselType,V.GrossTonnage,
            Q.AllocatedQuota,Q.BookedQuota,Q.UsedQuota,(Q.AllocatedQuota-Q.BookedQuota-Q.UsedQuota) AS VesselRemainingQuota,
            B.CompanyName,B.NIB,B.BankName,B.BankAccountNo,B.BankAccountName,
            P.OriginBankName,P.OriginAccountNo,P.OriginAccountName,P.AccountValidationStatus,
            P.OcrAccountNoFound,P.OcrAccountNameFound,P.OcrAmountFound,P.OcrExpectedAmount,P.OcrDetectedAmount,
            P.OcrValidationStatus,P.OcrValidatedAt,P.OcrExtractedText,
            P.OcrDestinationAccountFound,P.OcrDestinationNameFound,P.OcrTransferSuccessFound,
            P.OcrReferenceFound,P.OcrSourceAccountMasked,P.OcrValidationMethod,
            P.OcrReferenceNo,P.OcrReferenceDuplicate");
        $this->db->from('SpbbOrder O');
        $this->db->join('SPBB S', 'S.LedgerNo = O.SpbbID', 'left');
        $this->db->join('SpbbFuelPrice F', 'F.FuelID = O.FuelID', 'left');
        $this->db->join('SpbbPayment P', 'P.OrderID = O.OrderID', 'left');
        $this->db->join('JbtVessel V', 'V.VesselID=O.VesselID', 'left');
        $this->db->join('JbtVesselQuota Q', 'Q.VesselQuotaID=O.VesselQuotaID', 'left');
        $this->db->join('JbtBusinessEntity B', 'B.BusinessEntityID=V.BusinessEntityID', 'left');
        foreach ($where as $field => $value) {
            if (is_array($value)) {
                if (count($value)) {
                    $this->db->where_in($field, $value);
                } else {
                    $this->db->where('1 = 0');
                }
            } else {
                $this->db->where($field, $value);
            }
        }
        $this->db->order_by('O.OrderID', 'DESC');
        return $this->db->get()->result_array();
    }

    public function find_order($order_id)
    {
        $rows = $this->order_summary(array('O.OrderID' => $order_id));
        return count($rows) ? $rows[0] : null;
    }

    public function upload_payment_proof_with_account($order_id, $file, $user_id, $account, $ocr)
    {
        $origin_no = preg_replace('/[^0-9]/', '', (string) $account['OriginAccountNo']);
        $authorized = $this->authorized_transfer_accounts($user_id);
        $matched = null;
        foreach ($authorized as $row) {
            if ($origin_no !== '' && $origin_no === preg_replace('/[^0-9]/', '', $row['AccountNo'])) {
                $matched = $row;
                break;
            }
        }
        if (!$matched) {
            return array(false, 'Rekening asal bukan rekening transfer yang telah diverifikasi dan diberi kewenangan.');
        }
        $this->db->where('OrderID', $order_id)->update('SpbbPayment', array(
            'ProofFile' => $file,
            'OriginBankName' => $matched['BankName'],
            'OriginAccountNo' => $origin_no,
            'OriginAccountName' => $matched['AccountName'],
            'AccountValidationStatus' => 'SESUAI',
            'AccountValidatedAt' => date('Y-m-d H:i:s'),
            'OcrExtractedText' => $ocr['text'],
            'OcrAccountNoFound' => $ocr['account_no_found'] ? 1 : 0,
            'OcrAccountNameFound' => $ocr['account_name_found'] ? 1 : 0,
            'OcrAmountFound' => $ocr['amount_found'] ? 1 : 0,
            'OcrDestinationAccountFound' => $ocr['destination_account_found'] ? 1 : 0,
            'OcrDestinationNameFound' => $ocr['destination_name_found'] ? 1 : 0,
            'OcrTransferSuccessFound' => $ocr['transfer_success_found'] ? 1 : 0,
            'OcrReferenceFound' => $ocr['reference_found'] ? 1 : 0,
            'OcrSourceAccountMasked' => $ocr['source_account_masked'] ? 1 : 0,
            'OcrValidationMethod' => $ocr['validation_method'],
            'OcrReferenceNo' => $ocr['reference_no'],
            'OcrReferenceDuplicate' => $ocr['reference_duplicate'] ? 1 : 0,
            'OcrExpectedAmount' => $ocr['expected_amount'],
            'OcrDetectedAmount' => $ocr['detected_amount'],
            'OcrValidationStatus' => $ocr['status'],
            'OcrValidatedAt' => date('Y-m-d H:i:s'),
            'PaymentStatus' => $ocr['status'] === 'AUTOMATIC_MATCH' ? 'MENUNGGU_VERIFIKASI' : 'BUKTI_TIDAK_SESUAI',
            'UpdatedAt' => date('Y-m-d H:i:s'),
        ));
        if ($this->db->affected_rows() !== 1) return array(false, 'Data pembayaran tidak berhasil diperbarui.');
        $this->db->where('OrderID', $order_id)->update('SpbbOrder', array(
            'OrderStatus' => $ocr['status'] === 'AUTOMATIC_MATCH' ? 'MENUNGGU_VERIFIKASI' : 'BUKTI_TIDAK_SESUAI',
            'OwnerApprovalStatus' => $ocr['status'] === 'AUTOMATIC_MATCH' ? 'PENDING' : 'MENUNGGU_BUKTI_BAYAR',
            'UpdatedBy' => $user_id,
            'UpdatedAt' => date('Y-m-d H:i:s'),
        ));
        if ($ocr['status'] === 'AUTOMATIC_MATCH') {
            $message = 'Bukti bayar sesuai: rekening asal terverifikasi' .
                ($ocr['source_account_masked'] ? ' (nomor pada struk disamarkan)' : '') .
                ', rekening dan nama penerima sesuai, nominal sesuai invoice, status berhasil, dan nomor referensi ditemukan.';
        } elseif ($ocr['status'] === 'OCR_ERROR') {
            $message = 'Bukti berhasil diupload, tetapi teks pada gambar tidak dapat dibaca oleh OCR. Gunakan gambar yang lebih jelas, tidak buram, tidak terpotong, dan pastikan nomor rekening, nama pemilik, serta nominal terlihat.';
        } else {
            $mismatches = array();
            if ($ocr['status'] === 'RECEIVER_NOT_CONFIGURED') $mismatches[] = 'rekening penerima SPBB belum dikonfigurasi Admin';
            if (!$ocr['account_no_found'] && !$ocr['source_account_masked']) $mismatches[] = 'rekening asal tidak dapat dicocokkan, termasuk pola nomor yang disamarkan';
            if (!$ocr['destination_account_found']) $mismatches[] = 'nomor rekening tujuan tidak sesuai';
            if (!$ocr['destination_name_found']) $mismatches[] = 'nama penerima tidak sesuai';
            if (!$ocr['amount_found']) {
                $mismatches[] = $ocr['detected_amount'] !== null
                    ? 'nominal pada bukti Rp ' . number_format($ocr['detected_amount'], 0, ',', '.') .
                        ' tidak sama dengan invoice Rp ' . number_format($ocr['expected_amount'], 0, ',', '.')
                    : 'nominal transfer tidak ditemukan';
            }
            if (!$ocr['transfer_success_found']) $mismatches[] = 'status transfer berhasil tidak ditemukan';
            if (!$ocr['reference_found']) $mismatches[] = 'nomor referensi transaksi tidak ditemukan';
            if ($ocr['reference_duplicate']) $mismatches[] = 'nomor referensi sudah pernah digunakan pada pembayaran lain';
            $message = 'Validasi otomatis gagal: ' . implode('; ', $mismatches) .
                '. Silakan periksa bukti pembayaran dan upload ulang.';
        }
        return array(true, $message);
    }

    public function pending_approval_orders($spbb_ids, $limit = 10)
    {
        if (!is_array($spbb_ids) || !count($spbb_ids)) {
            return array();
        }

        $this->db->select("O.OrderID, O.InvoiceNo, O.CustomerID, O.Quantity, O.TotalAmount, O.PaymentMethod, O.CreatedAt, F.FuelName, (ISNULL(S.No_SPBB, '') + ' - ' + ISNULL(S.Area, '')) AS SpbbName");
        $this->db->from('SpbbOrder O');
        $this->db->join('SPBB S', 'S.LedgerNo = O.SpbbID', 'left');
        $this->db->join('SpbbFuelPrice F', 'F.FuelID = O.FuelID', 'left');
        $this->db->where_in('O.SpbbID', $spbb_ids);
        $this->db->where('O.OwnerApprovalStatus', 'PENDING');
        $this->db->order_by('O.OrderID', 'DESC');
        if ($limit) {
            $this->db->limit((int) $limit);
        }
        return $this->db->get()->result_array();
    }

    public function pending_approval_count($spbb_ids)
    {
        if (!is_array($spbb_ids) || !count($spbb_ids)) {
            return 0;
        }
        return $this->db->where_in('SpbbID', $spbb_ids)
            ->where('OwnerApprovalStatus', 'PENDING')
            ->count_all_results('SpbbOrder');
    }

    public function create_order($customer_id, $data)
    {
        if ($this->has_unconfirmed_receipt($customer_id)) {
            return array(false, 'Masih ada pengisian selesai yang belum dikonfirmasi penerimaannya. Konfirmasi dahulu sebelum membuat pesanan baru.');
        }
        $fuel = $this->get_fuel($data['FuelID']);
        if (!$fuel) {
            return array(false, 'Jenis BBM tidak ditemukan');
        }

        $qty = (float) $data['Quantity'];
        if ($qty <= 0) {
            return array(false, 'Quantity harus lebih dari 0');
        }

        $spbb = $this->get_spbb_by_id($fuel['SpbbID']);
        if (!$spbb) {
            return array(false, 'SPBB tidak ditemukan');
        }

        $allocation = $this->customer_vessel_allocation($customer_id, (int)$data['VesselID'], (int)$fuel['SpbbID']);
        if (!$allocation) {
            return array(false, 'Kapal tidak memiliki alokasi kuota JBT pada fuel terminal yang dipilih.');
        }
        $quota = (float)$allocation['RemainingQuota'];
        if ($quota < $qty) {
            return array(false, 'Pesanan melebihi kuota kapal/perusahaan. Sisa kuota kapal: ' . number_format($quota, 2) . ' liter');
        }

        $payment_method = strtoupper($data['PaymentMethod']);
        $needs_payment_proof = in_array($payment_method, array(self::PAYMENT_TRANSFER, self::PAYMENT_QRIS));
        $needs_owner_approval = !$needs_payment_proof;
        $invoice_no = 'INV-SPBB-' . date('YmdHis') . '-' . substr(md5($customer_id . microtime()), 0, 4);
        $total = $qty * (float) $fuel['UnitPrice'];

        $this->db->trans_begin();
        $this->db->insert('SpbbOrder', array(
            'InvoiceNo' => $invoice_no,
            'CustomerID' => $customer_id,
            'SpbbID' => $fuel['SpbbID'],
            'FuelID' => $fuel['FuelID'],
            'VesselID' => $allocation['VesselID'],
            'VesselQuotaID' => $allocation['VesselQuotaID'],
            'Quantity' => $qty,
            'UnitPrice' => $fuel['UnitPrice'],
            'TotalAmount' => $total,
            'PaymentMethod' => $payment_method,
            'OrderChannel' => isset($data['OrderChannel']) && in_array($data['OrderChannel'], array('SPBB', 'PIPANISASI'), true) ? $data['OrderChannel'] : 'SPBB',
            'OrderStatus' => $needs_payment_proof ? 'MENUNGGU_BUKTI_BAYAR' : 'MENUNGGU_APPROVAL',
            'OwnerApprovalStatus' => $needs_owner_approval ? 'PENDING' : 'MENUNGGU_BUKTI_BAYAR',
            'PaymentProofRequired' => $needs_payment_proof ? 1 : 0,
            'CreatedBy' => $customer_id,
            'CreatedAt' => date('Y-m-d H:i:s'),
        ));
        $order_id = $this->insert_id();

        $this->db->insert('SpbbPayment', array(
            'OrderID' => $order_id,
            'PaymentMethod' => $payment_method,
            'PaymentStatus' => $needs_payment_proof ? 'MENUNGGU_BUKTI_BAYAR' : 'MENUNGGU_APPROVAL',
            'CreatedAt' => date('Y-m-d H:i:s'),
        ));

        if (!$this->reserve_vessel_quota($allocation['VesselQuotaID'], $qty)) {
            $this->db->trans_rollback();
            return array(false, 'Kuota baru saja digunakan transaksi lain. Silakan muat ulang dan coba lagi.');
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return array(false, 'Gagal membuat pesanan');
        }

        $this->db->trans_commit();
        return array(true, $this->find_order($order_id));
    }

    public function upload_payment_proof($order_id, $file_name, $user_id)
    {
        $owned_order = $this->db->where('OrderID', (int) $order_id)
            ->where('CustomerID', $user_id)
            ->where('OrderStatus', 'MENUNGGU_BUKTI_BAYAR')
            ->count_all_results('SpbbOrder');
        if (!$owned_order) {
            return false;
        }

        $this->db->trans_begin();
        $this->db->where('OrderID', $order_id)->update('SpbbPayment', array(
            'ProofFile' => $file_name,
            'PaymentStatus' => 'MENUNGGU_APPROVAL',
            'UpdatedAt' => date('Y-m-d H:i:s'),
        ));
        $this->db->where('OrderID', $order_id)->update('SpbbOrder', array(
            'OrderStatus' => 'MENUNGGU_APPROVAL',
            'OwnerApprovalStatus' => 'PENDING',
            'UpdatedBy' => $user_id,
            'UpdatedAt' => date('Y-m-d H:i:s'),
        ));

        if ($this->db->trans_status() === false) {
            return false;
        }

        $this->db->trans_commit();
        return true;
    }

    public function approve_order($order_id, $owner_id, $status, $notes)
    {
        $status = strtoupper($status) === 'REJECTED' ? 'REJECTED' : 'APPROVED';
        $order_status = $status === 'APPROVED' ? 'SIAP_PENGISIAN' : 'DITOLAK';
        $payment_status = $status === 'APPROVED' ? 'APPROVED' : 'REJECTED';

        $this->db->trans_begin();
        $this->db->where('OrderID', $order_id)->update('SpbbOrder', array(
            'OrderStatus' => $order_status,
            'OwnerApprovalStatus' => $status,
            'OwnerApprovalBy' => $owner_id,
            'OwnerApprovalAt' => date('Y-m-d H:i:s'),
            'OwnerNotes' => $notes,
            'UpdatedBy' => $owner_id,
            'UpdatedAt' => date('Y-m-d H:i:s'),
        ));
        $this->db->where('OrderID', $order_id)->update('SpbbPayment', array(
            'PaymentStatus' => $payment_status,
            'ApprovedBy' => $owner_id,
            'ApprovedAt' => date('Y-m-d H:i:s'),
            'UpdatedAt' => date('Y-m-d H:i:s'),
        ));

        if ($status !== 'APPROVED') {
            $order = $this->find_order($order_id);
            if ($order && isset($order['No_SPBB'])) {
                $this->release_vessel_quota($order['VesselQuotaID'], $order['Quantity']);
            }
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }

    public function operator_orders($spbb_ids, $statuses)
    {
        $rows = $this->order_summary(array('O.SpbbID' => $spbb_ids));
        return array_values(array_filter($rows, function ($row) use ($statuses) {
            return $row['OrderChannel']==='SPBB'&&in_array($row['OrderStatus'], $statuses, true);
        }));
    }

    public function start_filling($order_id, $operator_id, $spbb_ids)
    {
        $order = $this->find_order($order_id);
        if (!$order || !in_array((int) $order['SpbbID'], $spbb_ids) ||
            $order['OrderStatus'] !== 'SIAP_PENGISIAN' ||
            $order['OwnerApprovalStatus'] !== 'APPROVED') {
            return array(false, 'Pesanan tidak tersedia untuk mulai pengisian');
        }

        $this->db->where('OrderID', $order_id)
            ->where('OrderStatus', 'SIAP_PENGISIAN')
            ->update('SpbbOrder', array(
                'OrderStatus' => 'SEDANG_PENGISIAN',
                'OperatorStartedBy' => $operator_id,
                'OperatorStartedAt' => date('Y-m-d H:i:s'),
                'UpdatedBy' => $operator_id,
                'UpdatedAt' => date('Y-m-d H:i:s'),
            ));

        return $this->db->affected_rows() === 1
            ? array(true, 'Pengisian dimulai')
            : array(false, 'Pesanan sudah diambil operator lain');
    }

    public function complete_order($order_id, $operator_id, $actual_quantity, $shift_code)
    {
        $order = $this->find_order($order_id);
        $actual_quantity = (float) $actual_quantity;
        $allowed_shifts = array('SHIFT_1', 'SHIFT_2', 'SHIFT_3');
        if (!$order || $order['OrderStatus'] !== 'SEDANG_PENGISIAN' ||
            $order['OwnerApprovalStatus'] !== 'APPROVED' ||
            $order['OperatorStartedBy'] !== $operator_id) {
            return array(false, 'Pesanan belum dimulai oleh operator ini atau sudah diselesaikan');
        }
        if ($actual_quantity <= 0 || $actual_quantity > (float) $order['Quantity']) {
            return array(false, 'Volume aktual harus lebih dari 0 dan tidak melebihi volume pesanan');
        }
        if (!in_array($shift_code, $allowed_shifts, true)) {
            return array(false, 'Shift pengisian tidak valid');
        }

        $this->db->trans_begin();
        $already_posted = $this->db->where('OrderID', $order_id)
            ->where('MovementType', 'OUT')
            ->count_all_results('SpbbStockMovement');
        if ($already_posted) {
            $this->db->trans_rollback();
            return array(false, 'Penjualan sudah tercatat pada stock movement');
        }

        $this->insert_stock_movement(array(
            'SpbbID' => $order['SpbbID'],
            'FuelID' => $order['FuelID'],
            'OrderID' => $order_id,
            'MovementType' => 'OUT',
            'Quantity' => $actual_quantity,
            'ReferenceNo' => $order['InvoiceNo'],
            'Notes' => 'Penjualan/pengisian ' .
                ($order['CompanyName'] ? $order['CompanyName'] : $order['CustomerID']) . ' / ' .
                ($order['VesselName'] ? $order['VesselName'] : '-'),
            'CreatedBy' => $operator_id,
        ));
        $spbb = $this->get_spbb_by_id($order['SpbbID']);
        if (!$this->insert_sales_records(array(
            'SourceOrderID' => $order_id,
            'EntrySource' => 'CUSTOMER_APP',
            'TransactionNo' => $order['InvoiceNo'],
            'CustomerName' => $order['VesselName'] ? $order['VesselName'] : $order['CustomerID'],
            'CustomerType' => $order['CompanyName'] ? $order['CompanyName'] : 'CUSTOMER',
            'CustomerGroup' => $order['CompanyName'] ? $order['CompanyName'] : $order['CustomerID'],
            'SpbbID' => $order['SpbbID'],
            'NoSPBB' => $spbb['No_SPBB'],
            'Area' => $spbb['Area'],
            'FuelID' => $order['FuelID'],
            'FuelName' => $order['FuelName'],
            'OrderDate' => date('Y-m-d', strtotime($order['CreatedAt'])),
            'OrderTime' => date('H:i:s', strtotime($order['CreatedAt'])),
            'RealizationDate' => date('Y-m-d'),
            'RealizationTime' => date('H:i:s'),
            'OrderedQuantity' => $order['Quantity'],
            'RealizedQuantity' => $actual_quantity,
            'Status' => 'Completed',
            'SPP' => $order['InvoiceNo'],
            'SPB' => $spbb['No_SPBB'],
            'Logbook' => 'CUSTOMER APP',
            'ReportReference' => $order['InvoiceNo'],
            'CreatedBy' => $operator_id,
        ))) {
            $this->db->trans_rollback();
            return array(false, 'Gagal mengintegrasikan penjualan ke Data Delivery dan Sales Realization');
        }
        $this->db->where('OrderID', $order_id)->update('SpbbOrder', array(
            'OrderStatus' => 'SELESAI',
            'ReceiptConfirmationStatus' => 'PENDING',
            'ActualQuantity' => $actual_quantity,
            'ShiftCode' => $shift_code,
            'CompletedBy' => $operator_id,
            'CompletedAt' => date('Y-m-d H:i:s'),
            'UpdatedBy' => $operator_id,
            'UpdatedAt' => date('Y-m-d H:i:s'),
        ));
        $this->consume_vessel_quota($order['VesselQuotaID'], $actual_quantity);
        if ($actual_quantity < (float) $order['Quantity']) {
            $this->release_vessel_quota($order['VesselQuotaID'], (float) $order['Quantity'] - $actual_quantity);
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return array(false, 'Gagal mencatat penjualan ke stock movement');
        }
        $this->db->trans_commit();
        return array(true, 'Pengisian selesai dan penjualan masuk ke Data Delivery, Sales Realization, dan Stock Movement');
    }

    public function operator_shift_recap($spbb_ids, $date_from, $date_to)
    {
        if (!count($spbb_ids)) {
            return array();
        }
        $this->db->select("CONVERT(DATE, O.CompletedAt) AS SalesDate, O.ShiftCode, O.SpbbID,
            (ISNULL(S.No_SPBB, '') + ' - ' + ISNULL(S.Area, '')) AS SpbbName,
            O.CompletedBy, COUNT(O.OrderID) AS TransactionCount,
            SUM(ISNULL(O.ActualQuantity, O.Quantity)) AS TotalQuantity,
            SUM(ISNULL(O.ActualQuantity, O.Quantity) * O.UnitPrice) AS TotalAmount");
        $this->db->from('SpbbOrder O');
        $this->db->join('SPBB S', 'S.LedgerNo = O.SpbbID', 'left');
        $this->db->where_in('O.SpbbID', $spbb_ids);
        $this->db->where('O.OrderStatus', 'SELESAI')->where('O.OrderChannel','SPBB');
        $this->db->where('O.CompletedAt >=', $date_from . ' 00:00:00');
        $this->db->where('O.CompletedAt <=', $date_to . ' 23:59:59');
        $this->db->group_by("CONVERT(DATE, O.CompletedAt), O.ShiftCode, O.SpbbID, S.No_SPBB, S.Area, O.CompletedBy");
        $this->db->order_by('SalesDate', 'DESC');
        $this->db->order_by('O.ShiftCode', 'ASC');
        return $this->db->get()->result_array();
    }

    public function create_manual_sale($user_id, $data)
    {
        if ($this->db->where('DO_Transaksi', $data['TransactionNo'])
            ->where('No_SPBB', $data['NoSPBB'])
            ->count_all_results('DataDelivery')) {
            return array(false, 'Nomor transaksi sudah digunakan pada SPBB tersebut');
        }

        $this->db->trans_begin();
        if (!$this->insert_sales_records($data)) {
            $this->db->trans_rollback();
            return array(false, 'Gagal menyimpan Data Penjualan dan Sales Realization');
        }
        $this->insert_stock_movement(array(
            'SpbbID' => $data['SpbbID'],
            'FuelID' => $data['FuelID'],
            'MovementType' => 'OUT',
            'Quantity' => $data['RealizedQuantity'],
            'ReferenceNo' => $data['TransactionNo'],
            'Notes' => 'Penjualan manual / ' . $data['CustomerName'],
            'CreatedBy' => $user_id,
        ));

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return array(false, 'Gagal menyimpan transaksi penjualan manual');
        }
        $this->db->trans_commit();
        return array(true, 'Penjualan manual tersimpan dan terintegrasi ke seluruh laporan');
    }

    private function insert_sales_records($data)
    {
        if (!empty($data['SourceOrderID']) &&
            $this->db->where('SourceOrderID', $data['SourceOrderID'])->count_all_results('DataDelivery')) {
            return false;
        }

        $delivery = array(
            'Year' => (int) date('Y', strtotime($data['RealizationDate'])),
            'Month' => date('M', strtotime($data['RealizationDate'])),
            'DO_Transaksi' => $data['TransactionNo'],
            'NoQuotation' => isset($data['QuotationNo']) ? $data['QuotationNo'] : null,
            'Nm_Kapal' => $data['CustomerName'],
            'JenisKapal' => isset($data['CustomerType']) ? $data['CustomerType'] : 'CUSTOMER',
            'Tgl_Pemesanan' => $data['OrderDate'],
            'Waktu_Pemesanan' => $data['OrderTime'] ? $data['OrderTime'] : null,
            'Vol_Dipesan' => (int) round($data['OrderedQuantity']),
            'Tgl_Realisasi' => $data['RealizationDate'],
            'Waktu_Realisasi' => $data['RealizationTime'] ? $data['RealizationTime'] : null,
            'Vol_Realisasi' => (int) round($data['RealizedQuantity']),
            'Grade' => $data['FuelName'],
            'No_SPBB' => $data['NoSPBB'],
            'Status' => $data['Status'],
            'SPP' => $data['SPP'],
            'SPB' => $data['SPB'],
            'Logbook' => $data['Logbook'],
            'RPT_RI' => $data['ReportReference'],
            'UserEntry' => $data['CreatedBy'],
            'TimeEntry' => date('Y-m-d H:i:s'),
            'SourceOrderID' => !empty($data['SourceOrderID']) ? $data['SourceOrderID'] : null,
            'EntrySource' => $data['EntrySource'],
        );
        if (!$this->db->insert('DataDelivery', $delivery)) {
            return false;
        }
        $delivery_id = $this->insert_id();

        if (!$this->db->insert('SalesRealization', array(
            'Material' => $data['FuelName'],
            'TransactionDate' => $data['RealizationDate'],
            'TBBM' => $data['Area'],
            'Regional' => $data['Area'],
            'SalesDistrict' => $data['Area'],
            'NoSPBU' => $data['NoSPBB'],
            'CustomerGroup' => $data['CustomerGroup'],
            'Kuota' => (int) round($data['OrderedQuantity']),
            'Volume' => (int) round($data['RealizedQuantity']),
            'UserEntry' => $data['CreatedBy'],
            'TimeEntry' => date('Y-m-d H:i:s'),
            'SourceOrderID' => !empty($data['SourceOrderID']) ? $data['SourceOrderID'] : null,
            'DeliveryLedgerNo' => $delivery_id,
            'EntrySource' => $data['EntrySource'],
        ))) {
            return false;
        }
        if (!$this->db->where('no_transaksi', $data['TransactionNo'])->count_all_results('transaksi_spbb')) {
            if (!$this->db->insert('transaksi_spbb', array(
                'no_transaksi' => $data['TransactionNo'],
                'no_quotation' => isset($data['QuotationNo']) ? $data['QuotationNo'] : null,
                'nama_kapal' => $data['CustomerName'],
                'jenis_kapal' => isset($data['CustomerType']) ? $data['CustomerType'] : 'CUSTOMER',
                'tanggal_pemesanan' => $data['OrderDate'],
                'waktu_pemesanan' => $data['OrderTime'] ? $data['OrderTime'] : null,
                'dipesan_liter' => (int) round($data['OrderedQuantity']),
                'tanggal_pengiriman' => $data['RealizationDate'],
                'waktu_pengiriman' => $data['RealizationTime'] ? $data['RealizationTime'] : null,
                'realisasi_liter' => (int) round($data['RealizedQuantity']),
                'jenis_bbm' => $data['FuelName'],
                'titik_serah' => $data['NoSPBB'],
                'status' => $data['Status'],
                'sp' => substr($data['SPP'], 0, 10),
                'logbook' => substr($data['Logbook'], 0, 20),
                'rpt_ri' => substr($data['ReportReference'], 0, 20),
                'created_at' => date('Y-m-d H:i:s'),
            ))) {
                return false;
            }
        }
        return true;
    }

    public function sync_delivery_to_sales_realization()
    {
        return $this->db->query("
            INSERT INTO SalesRealization
                (Material, TransactionDate, TBBM, Regional, SalesDistrict, NoSPBU,
                 CustomerGroup, Kuota, Volume, UserEntry, TimeEntry,
                 SourceOrderID, DeliveryLedgerNo, EntrySource)
            SELECT
                LEFT(ISNULL(D.Grade, 'BBM'), 30),
                D.Tgl_Realisasi,
                LEFT(ISNULL(S.Area, D.No_SPBB), 50),
                LEFT(ISNULL(S.Area, ''), 20),
                LEFT(ISNULL(S.Area, ''), 30),
                LEFT(D.No_SPBB, 25),
                LEFT(ISNULL(D.Nm_Kapal, 'CUSTOMER'), 50),
                ISNULL(D.Vol_Dipesan, 0),
                ISNULL(D.Vol_Realisasi, 0),
                LEFT(ISNULL(D.UserEntry, 'SYSTEM'), 30),
                ISNULL(D.TimeEntry, GETDATE()),
                D.SourceOrderID,
                D.LedgerNo,
                ISNULL(D.EntrySource, 'LEGACY_IMPORT')
            FROM DataDelivery D
            LEFT JOIN SPBB S ON LTRIM(RTRIM(S.No_SPBB)) = LTRIM(RTRIM(D.No_SPBB))
            WHERE NOT EXISTS (
                SELECT 1 FROM SalesRealization SR
                WHERE SR.DeliveryLedgerNo = D.LedgerNo
            )
        ");
    }

    public function create_pertamina_purchase($owner_id, $data)
    {
        $payload = array(
            'SpbbID' => $data['SpbbID'],
            'FuelID' => $data['FuelID'],
            'PoNumber' => $data['PoNumber'],
            'PurchaseDate' => $data['PurchaseDate'],
            'RequestedDeliveryDate' => $data['RequestedDeliveryDate'] ? $data['RequestedDeliveryDate'] : null,
            'Supplier' => $data['Supplier'] ? $data['Supplier'] : 'PERTAMINA Patra Niaga',
            'PaymentTerms' => $data['PaymentTerms'],
            'Quantity' => (float) $data['Quantity'],
            'UnitPrice' => (float) $data['UnitPrice'],
            'TotalAmount' => (float) $data['Quantity'] * (float) $data['UnitPrice'],
            'PurchaseStatus' => isset($data['PurchaseStatus']) ? $data['PurchaseStatus'] : 'DIPESAN',
            'Notes' => isset($data['Notes']) ? $data['Notes'] : '',
            'CreatedBy' => $owner_id,
            'CreatedAt' => date('Y-m-d H:i:s'),
        );
        $this->db->insert('SpbbPertaminaPurchase', $payload);
        return $this->insert_id();
    }

    public function purchases($spbb_id = null)
    {
        $this->db->select("P.*, (ISNULL(S.No_SPBB, '') + ' - ' + ISNULL(S.Area, '')) AS SpbbName, F.FuelName, ISNULL(R.ReceivedQty, 0) AS ReceivedQty, P.Quantity - ISNULL(R.ReceivedQty, 0) AS RemainingQty");
        $this->db->from('SpbbPertaminaPurchase P');
        $this->db->join('SPBB S', 'S.LedgerNo = P.SpbbID', 'left');
        $this->db->join('SpbbFuelPrice F', 'F.FuelID = P.FuelID', 'left');
        $this->db->join('(SELECT PurchaseID, SUM(ReceivedQuantity) AS ReceivedQty FROM SpbbFuelReceipt GROUP BY PurchaseID) R', 'R.PurchaseID = P.PurchaseID', 'left', false);
        if (is_array($spbb_id)) {
            if (count($spbb_id)) {
                $this->db->where_in('P.SpbbID', $spbb_id);
            } else {
                $this->db->where('1 = 0');
            }
        } elseif ($spbb_id) {
            $this->db->where('P.SpbbID', $spbb_id);
        }
        return $this->db->order_by('P.PurchaseID', 'DESC')->get()->result_array();
    }

    public function open_purchases($spbb_ids)
    {
        $rows = $this->purchases($spbb_ids);
        return array_values(array_filter($rows, function ($row) {
            return (float) $row['RemainingQty'] > 0 && $row['PurchaseStatus'] !== 'DIBATALKAN';
        }));
    }

    public function receipts($spbb_ids)
    {
        $this->db->select("R.*, P.PoNumber, P.Quantity AS OrderedQuantity, (ISNULL(S.No_SPBB, '') + ' - ' + ISNULL(S.Area, '')) AS SpbbName, F.FuelName");
        $this->db->from('SpbbFuelReceipt R');
        $this->db->join('SpbbPertaminaPurchase P', 'P.PurchaseID = R.PurchaseID');
        $this->db->join('SPBB S', 'S.LedgerNo = R.SpbbID', 'left');
        $this->db->join('SpbbFuelPrice F', 'F.FuelID = R.FuelID', 'left');
        if (count($spbb_ids)) {
            $this->db->where_in('R.SpbbID', $spbb_ids);
        } else {
            $this->db->where('1 = 0');
        }
        return $this->db->order_by('R.ReceiptID', 'DESC')->get()->result_array();
    }

    public function create_receipt($user_id, $spbb_ids, $data)
    {
        $purchase = $this->db->where('PurchaseID', $data['PurchaseID'])->get('SpbbPertaminaPurchase')->row_array();
        if (!$purchase || !in_array((int) $purchase['SpbbID'], $spbb_ids)) {
            return array(false, 'PO PERTAMINA Patra Niaga tidak ditemukan atau tidak dapat diakses');
        }
        if (in_array($purchase['PurchaseStatus'], array('DITERIMA', 'DIBATALKAN'))) {
            return array(false, 'PO PERTAMINA Patra Niaga sudah selesai atau dibatalkan');
        }
        if (!$data['ReceiptNo'] || !$data['DeliveryNoteNo'] || !$data['ReceiptDate'] || $data['ReceivedQuantity'] <= 0) {
            return array(false, 'Nomor penerimaan, surat jalan, tanggal, dan volume diterima wajib diisi');
        }
        if ($data['RejectedQuantity'] < 0) {
            return array(false, 'Volume ditolak tidak boleh bernilai negatif');
        }
        if ($this->db->where('ReceiptNo', $data['ReceiptNo'])->count_all_results('SpbbFuelReceipt')) {
            return array(false, 'Nomor penerimaan sudah digunakan');
        }

        $received = $this->db->select_sum('ReceivedQuantity', 'Total')
            ->where('PurchaseID', $purchase['PurchaseID'])
            ->get('SpbbFuelReceipt')->row_array();
        $remaining = (float) $purchase['Quantity'] - (float) $received['Total'];
        if ($data['ReceivedQuantity'] > $remaining) {
            return array(false, 'Volume diterima melebihi sisa PO ' . number_format($remaining, 2) . ' liter');
        }

        $payload = $data;
        unset($payload['PurchaseID']);
        $payload['PurchaseID'] = $purchase['PurchaseID'];
        $payload['SpbbID'] = $purchase['SpbbID'];
        $payload['FuelID'] = $purchase['FuelID'];
        $payload['ReceiptTime'] = $data['ReceiptTime'] ? $data['ReceiptTime'] : null;
        $payload['Temperature'] = $data['Temperature'] !== '' ? (float) $data['Temperature'] : null;
        $payload['Density'] = $data['Density'] !== '' ? (float) $data['Density'] : null;
        $payload['CreatedBy'] = $user_id;
        $payload['CreatedAt'] = date('Y-m-d H:i:s');

        $this->db->trans_begin();
        $this->db->insert('SpbbFuelReceipt', $payload);
        $receipt_id = $this->insert_id();
        $new_total = (float) $received['Total'] + $data['ReceivedQuantity'];
        $status = $new_total >= (float) $purchase['Quantity'] ? 'DITERIMA' : 'DITERIMA_SEBAGIAN';
        $this->db->where('PurchaseID', $purchase['PurchaseID'])->update('SpbbPertaminaPurchase', array(
            'PurchaseStatus' => $status,
            'UpdatedBy' => $user_id,
            'UpdatedAt' => date('Y-m-d H:i:s'),
        ));
        $this->insert_stock_movement(array(
            'SpbbID' => $purchase['SpbbID'],
            'FuelID' => $purchase['FuelID'],
            'PurchaseID' => $purchase['PurchaseID'],
            'MovementType' => 'IN',
            'Quantity' => $data['ReceivedQuantity'],
            'ReferenceNo' => $data['ReceiptNo'],
            'Notes' => 'Penerimaan BBM #' . $receipt_id . ' / Surat Jalan ' . $data['DeliveryNoteNo'],
            'CreatedBy' => $user_id,
        ));

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return array(false, 'Gagal menyimpan penerimaan BBM');
        }
        $this->db->trans_commit();
        return array(true, 'Penerimaan BBM berhasil dicatat dan stok masuk diperbarui');
    }

    public function insert_stock_movement($data)
    {
        $movement_type = strtoupper($data['MovementType']);
        $qty = (float) $data['Quantity'];
        if ($movement_type === 'OUT') {
            $qty = abs($qty) * -1;
        }

        $payload = array(
            'SpbbID' => $data['SpbbID'],
            'FuelID' => $data['FuelID'],
            'OrderID' => isset($data['OrderID']) ? $data['OrderID'] : null,
            'PurchaseID' => isset($data['PurchaseID']) ? $data['PurchaseID'] : null,
            'MovementType' => $movement_type,
            'Quantity' => $qty,
            'ReferenceNo' => isset($data['ReferenceNo']) ? $data['ReferenceNo'] : '',
            'Notes' => isset($data['Notes']) ? $data['Notes'] : '',
            'CreatedBy' => $data['CreatedBy'],
            'CreatedAt' => date('Y-m-d H:i:s'),
        );
        $this->db->insert('SpbbStockMovement', $payload);
        return $this->insert_id();
    }

    public function stock_summary($spbb_id = null)
    {
        $this->db->select("(ISNULL(S.No_SPBB, '') + ' - ' + ISNULL(S.Area, '')) AS SpbbName, F.FuelName, F.UnitPrice, ISNULL(SUM(M.Quantity), 0) AS StockQty");
        $this->db->from('SpbbFuelPrice F');
        $this->db->join('SPBB S', 'S.LedgerNo = F.SpbbID', 'left');
        $this->db->join('SpbbStockMovement M', 'M.FuelID = F.FuelID', 'left');
        if ($spbb_id) {
            $this->db->where('F.SpbbID', $spbb_id);
        }
        $this->db->group_by('S.No_SPBB, S.Area, F.FuelName, F.UnitPrice');
        $this->db->order_by('S.No_SPBB, F.FuelName');
        return $this->db->get()->result_array();
    }

    public function stock_movements($spbb_id = null)
    {
        $this->db->select("M.*, (ISNULL(S.No_SPBB, '') + ' - ' + ISNULL(S.Area, '')) AS SpbbName, F.FuelName");
        $this->db->from('SpbbStockMovement M');
        $this->db->join('SPBB S', 'S.LedgerNo = M.SpbbID', 'left');
        $this->db->join('SpbbFuelPrice F', 'F.FuelID = M.FuelID', 'left');
        if ($spbb_id) {
            $this->db->where('M.SpbbID', $spbb_id);
        }
        return $this->db->order_by('M.MovementID', 'DESC')->get()->result_array();
    }

    public function quota_summary($spbb_id = null)
    {
        $spbb_numbers = null;
        $force_empty = false;

        if (is_array($spbb_id)) {
            if (!count($spbb_id)) {
                $force_empty = true;
            } else {
                $spbb_numbers = $this->spbb_numbers_by_ids($spbb_id);
                if (!count($spbb_numbers)) {
                    $force_empty = true;
                }
            }
        } elseif ($spbb_id) {
            $spbb = $this->get_spbb_by_id($spbb_id);
            $spbb_numbers = $spbb ? array($spbb['No_SPBB']) : array();
            if (!count($spbb_numbers)) {
                $force_empty = true;
            }
        }

        $this->db->select("Q.*, ISNULL(Q.Vol_Terpakai, 0) AS Vol_Terpakai, ISNULL(Q.Vol_Booking, 0) AS Vol_Booking, (ISNULL(Q.Vol_Quota, 0) - ISNULL(Q.Vol_Terpakai, 0) - ISNULL(Q.Vol_Booking, 0)) AS Sisa_Quota");
        $this->db->from('Data_Quota Q');

        if ($force_empty) {
            $this->db->where('1 = 0');
        } elseif (is_array($spbb_numbers)) {
            $this->db->where_in('Q.No_SPBB', $spbb_numbers);
        }

        $this->db->order_by('Q.LedgerNo', 'DESC');
        return $this->db->get()->result_array();
    }

    public function spbb_numbers_by_ids($spbb_ids)
    {
        if (!is_array($spbb_ids) || !count($spbb_ids)) {
            return array();
        }

        $rows = $this->db->select('No_SPBB')
            ->where_in('LedgerNo', $spbb_ids)
            ->get('SPBB')
            ->result_array();

        $numbers = array();
        foreach ($rows as $row) {
            $numbers[] = $row['No_SPBB'];
        }

        return $numbers;
    }

    public function get_spbb_by_id($spbb_id)
    {
        return $this->db->where('LedgerNo', $spbb_id)->get('SPBB')->row_array();
    }

    public function quota_available($no_spbb)
    {
        $row = $this->db->query(
            "SELECT TOP 1 (ISNULL(Vol_Quota, 0) - ISNULL(Vol_Terpakai, 0) - ISNULL(Vol_Booking, 0)) AS SisaQuota
            FROM Data_Quota
            WHERE No_SPBB = ?
            ORDER BY LedgerNo DESC",
            array($no_spbb)
        )->row_array();

        return $row ? (float) $row['SisaQuota'] : 0;
    }

    private function customer_vessel_allocation($customer_id, $vessel_id, $spbb_id)
    {
        return $this->db->query("SELECT TOP 1 Q.VesselQuotaID,Q.VesselID,
            (Q.AllocatedQuota-Q.BookedQuota-Q.UsedQuota) RemainingQuota
            FROM Login L JOIN JbtVessel V ON V.BusinessEntityID=L.BusinessEntityID
            JOIN JbtVesselQuota Q ON Q.VesselID=V.VesselID
            JOIN JbtBphDecree D ON D.DecreeID=Q.DecreeID
            WHERE L.UserID=? AND V.VesselID=? AND Q.SpbbID=? AND D.IsActive=1
              AND D.PeriodYear=YEAR(GETDATE())
            ORDER BY D.DecreeDate DESC,Q.VesselQuotaID DESC",
            array($customer_id,$vessel_id,$spbb_id))->row_array();
    }

    private function reserve_vessel_quota($id, $qty)
    {
        $this->db->query("UPDATE JbtVesselQuota SET BookedQuota=BookedQuota+?,UpdatedAt=GETDATE()
            WHERE VesselQuotaID=? AND AllocatedQuota-BookedQuota-UsedQuota>=?", array($qty,$id,$qty));
        if ($this->db->affected_rows() !== 1) {
            $this->db->trans_rollback();
            return false;
        }
        return true;
    }

    private function consume_vessel_quota($id, $qty)
    {
        return $this->db->query("UPDATE JbtVesselQuota
            SET BookedQuota=CASE WHEN BookedQuota>=? THEN BookedQuota-? ELSE 0 END,
                UsedQuota=UsedQuota+?,UpdatedAt=GETDATE() WHERE VesselQuotaID=?",
            array($qty,$qty,$qty,$id));
    }

    private function release_vessel_quota($id, $qty)
    {
        return $this->db->query("UPDATE JbtVesselQuota
            SET BookedQuota=CASE WHEN BookedQuota>=? THEN BookedQuota-? ELSE 0 END,UpdatedAt=GETDATE()
            WHERE VesselQuotaID=?", array($qty,$qty,$id));
    }

    private function correct_vessel_quota($id, $qty)
    {
        return $this->db->query("UPDATE JbtVesselQuota
            SET UsedQuota=CASE WHEN UsedQuota>=? THEN UsedQuota-? ELSE 0 END,UpdatedAt=GETDATE()
            WHERE VesselQuotaID=?", array($qty,$qty,$id));
    }

    private function reserve_quota($no_spbb, $qty)
    {
        return $this->db->query(
            "UPDATE Data_Quota
            SET Vol_Booking = ISNULL(Vol_Booking, 0) + ?
            WHERE LedgerNo = (
                SELECT TOP 1 LedgerNo FROM Data_Quota WHERE No_SPBB = ? ORDER BY LedgerNo DESC
            )",
            array((float) $qty, $no_spbb)
        );
    }

    private function consume_reserved_quota($no_spbb, $qty)
    {
        return $this->db->query(
            "UPDATE Data_Quota
            SET Vol_Booking = CASE WHEN ISNULL(Vol_Booking, 0) - ? < 0 THEN 0 ELSE ISNULL(Vol_Booking, 0) - ? END,
                Vol_Terpakai = ISNULL(Vol_Terpakai, 0) + ?
            WHERE LedgerNo = (
                SELECT TOP 1 LedgerNo FROM Data_Quota WHERE No_SPBB = ? ORDER BY LedgerNo DESC
            )",
            array((float) $qty, (float) $qty, (float) $qty, $no_spbb)
        );
    }

    private function release_reserved_quota($no_spbb, $qty)
    {
        return $this->db->query(
            "UPDATE Data_Quota
            SET Vol_Booking = CASE WHEN ISNULL(Vol_Booking, 0) - ? < 0 THEN 0 ELSE ISNULL(Vol_Booking, 0) - ? END
            WHERE LedgerNo = (
                SELECT TOP 1 LedgerNo FROM Data_Quota WHERE No_SPBB = ? ORDER BY LedgerNo DESC
            )",
            array((float) $qty, (float) $qty, $no_spbb)
        );
    }

    private function correct_consumed_quota($no_spbb, $qty)
    {
        return $this->db->query(
            "UPDATE Data_Quota
            SET Vol_Terpakai = CASE WHEN ISNULL(Vol_Terpakai, 0) - ? < 0 THEN 0 ELSE ISNULL(Vol_Terpakai, 0) - ? END
            WHERE LedgerNo = (
                SELECT TOP 1 LedgerNo FROM Data_Quota WHERE No_SPBB = ? ORDER BY LedgerNo DESC
            )",
            array((float) $qty, (float) $qty, $no_spbb)
        );
    }

    public function create_token($user_id)
    {
        $token = bin2hex(openssl_random_pseudo_bytes(32));
        $this->db->insert('SpbbApiToken', array(
            'UserID' => $user_id,
            'Token' => $token,
            'ExpiredAt' => date('Y-m-d H:i:s', strtotime('+30 days')),
            'CreatedAt' => date('Y-m-d H:i:s'),
        ));
        return $token;
    }

    public function token_user($token)
    {
        $this->db->select('T.UserID, L.UserName, L.UserRole, L.SpbbID');
        $this->db->from('SpbbApiToken T');
        $this->db->join('Login L', 'L.UserID = T.UserID', 'left');
        $this->db->where('T.Token', $token);
        $this->db->where('T.ExpiredAt >=', date('Y-m-d H:i:s'));
        $user = $this->db->get()->row_array();
        if ($user) {
            $spbb_ids = $this->user_spbb_ids($user['UserID']);
            if (!count($spbb_ids) && isset($user['SpbbID']) && $user['SpbbID']) {
                $spbb_ids = array((int) $user['SpbbID']);
            }
            $user['SpbbIDs'] = $spbb_ids;
        }
        return $user;
    }

    private function insert_id()
    {
        $row = $this->db->query('SELECT SCOPE_IDENTITY() AS id')->row_array();
        return isset($row['id']) ? (int) $row['id'] : (int) $this->db->insert_id();
    }
}
