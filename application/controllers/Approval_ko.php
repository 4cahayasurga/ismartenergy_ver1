<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Approval_ko extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('M_approval_ko');
        $this->load->helper('bantu');
    }


    public function index()
    {
        $data['PrdAwal'] = date('Y-m-01');
        $data['PrdAkhir'] = date('Y-m-d');
        $data['judul'] = "APPROVAL KONTRAK/PURCHASE ORDER ";

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('procurement/approval_ko/view_approval_ko', $data);
        $this->load->view('templates/footer');
    }

    function getDataApprovalKO(){
        if (!$this->input->post()) {
            echo '';
            die;
        }

        $PrdAwal    = $this->input->post('PrdAwal');
        $PrdAkhir   = $this->input->post('PrdAkhir');
        $Tipe       = $this->input->post('Tipe');

        $data['PrdAwal']    = $PrdAwal;
        $data['PrdAkhir']   = $PrdAkhir;
        $data['Tipe']       = $Tipe;

        if ($Tipe=='belum_approve') {
            $getKOBelumApprove = $this->M_approval_ko->dataKOBelumApprove($PrdAwal,$PrdAkhir);
            $data['dataKO'] = $getKOBelumApprove['dataKO'];
            $data['TotalKO'] = $getKOBelumApprove['TotalKO'];
            $this->load->view('procurement/approval_ko/table_waiting_approve',$data);
        }else{
            $getKOSudahApprove = $this->M_approval_ko->dataKOSudahApprove($PrdAwal,$PrdAkhir);
            $data['dataKO'] = $getKOSudahApprove['dataKO'];
            $data['TotalKO'] = $getKOSudahApprove['TotalKO'];

            $this->load->view('procurement/approval_ko/table_approved',$data);
        }
    }

    function CekStatusKO(){
        if (!$this->input->post()) {
            echo '';
            die;
        }

        $LedgerNo = $this->input->post('LedgerNo');
        $NoKO = $this->input->post('NoKO');
        $Aksi = $this->input->post('Aksi');

        $query = $this->db->query("SELECT ApprovedBy, CanceledBy, ClosedBy FROM KoHdr WHERE NoKO='$NoKO' ");
        if ($query->num_rows() < 1) {
            echo json_encode(
                array(
                    'pesan' => 'No KO : '.$NoKO.' tidak ditemukan',
                    'flag' => 'KO tidak ada'
                )
            );
            return false;
        }

        $row = $query->row_array();

        if ($Aksi=='Approve') {

            if ($row['ApprovedBy'] !='' || $row['ApprovedBy'] !=NULL  ) {
                echo json_encode(
                    array(
                        'pesan' => $NoKO.' sudah di-Approve',
                        'flag' => 'sudah_approve',
                    )
                );
                return false;
            }

            if ($row['CanceledBy'] !='' || $row['CanceledBy'] !=NULL  ) {
                echo json_encode(
                    array(
                        'pesan' => $NoKO.' sudah di-batalkan',
                        'flag' => 'sudah_batal',
                    )
                );
                return false;
            }

            if ($row['ClosedBy'] !='' || $row['ClosedBy'] !=NULL  ) {
                echo json_encode(
                    array(
                        'pesan' => $NoKO.' sudah di-tutup',
                        'flag' => 'sudah_tutup',
                    )
                );
                return false;
            }

            echo json_encode(
                array(
                    'flag' => 'lanjut',
                )
            );
        }else{

            if ($row['ApprovedBy'] =='' || $row['ApprovedBy'] ==NULL  ) {
                echo json_encode(
                    array(
                        'pesan' => $NoKO.' sudah di-UnApprove',
                        'flag' => 'sudah_UnApprove',
                    )
                );
                return false;
            }

            // -- CEK PD
            $queryPD = $this->db->query("SELECT NoPD FROM PdHdr WHERE NoKO='$NoKO' ");
            if ($queryPD->num_rows() > 0) {
                $row = $queryPD->row();
                echo json_encode(
                    array(
                        'pesan' => $NoKO.' sudah digunakan untuk '.$row->NoPD,
                        'flag' => 'sudah_digunakanPD',
                    )
                );
                return false;
            }


            echo json_encode(
                array(
                    'flag' => 'lanjut',
                )
            );


        }

        




    }

    function AksiApproveUnapprove(){
        if (!$this->input->post()) {
            echo '';
            die;
        }

        $LedgerNo = $this->input->post('LedgerNo');
        $NoKO = $this->input->post('NoKO');
        $Aksi = $this->input->post('Aksi');

        $set['ApprovedBy'] = ($Aksi=='Approve') ? $this->session->userdata('MIS_LOGGED_NAME') : NULL;
        $set['TimeApproved'] = ($Aksi=='Approve') ? date('Y-m-d H:i:s') : NULL;
        $where['NoKO'] = $NoKO;

        $this->db->trans_begin();

        // -- update approve dan unapprove
        $this->db->set($set)->where($where)->update('KoHdr');

        // --pemotongan sub total dan lainnya
        $query_lanjutan = $this->db->query("SELECT PotongKO, SubTotal, DiscAmount, PPN FROM KoHdr WHERE NoKO='$NoKO' ");
        if ($query_lanjutan->num_rows() > 0) {
            $row = $query_lanjutan->row();
            if ($row->PotongKO!='' || $row->PotongKO!=NULL) {

                $SubTotal   = ($row->SubTotal=='' || $row->SubTotal==NULL ) ? 0 : $row->SubTotal;
                $DiscAmount = ($row->DiscAmount=='' || $row->DiscAmount==NULL ) ? 0 : $row->DiscAmount;
                $PPN        = ($row->PPN=='' || $row->PPN==NULL ) ? 0 : $row->PPN;

                $Total = ($SubTotal - $DiscAmount + $PPN);
                
                if ($Aksi=='Approve') {
                    $TxtQuery = "UPDATE KoHdr SET SubTotal=SubTotal-".$Total." WHERE NoKO='$row->PotongKO' ";
                    $this->db->query($TxtQuery);
                }else{
                    $TxtQuery = "UPDATE KoHdr SET SubTotal=SubTotal+".$Total." WHERE NoKO='$row->PotongKO' ";
                    $this->db->query($TxtQuery);
                }

            }
        }

        if ($this->db->trans_status()===FALSE) {
            $this->db->trans_rollback();
            echo 'gagal';
            return false;
        }else{
            $this->db->trans_commit();
            echo 'berhasil';
            return false;
        }
    }

    function pilihan_ko()
    {
        if (!$this->input->post()) {
            exit;
        }

        $pilihan_ko = $this->input->post('pilihan_ko', TRUE);

        if ($pilihan_ko == 'waiting_approve') {
            // $data['']
            $this->load->view('procurement/approval_ko/table_waiting_approve');
        }

        if ($pilihan_ko == 'approve') {
            $this->load->view('procurement/approval_ko/table_approved');
        }
    }

    function data_json_menunggu_approve()
    {

        header('Content-Type: application/json');
        echo $this->M_approval_ko->data_json_menunggu_approve();

    }

    function peringatan_approve()
    {
        if (!$this->input->post()) {
            exit;
        }

        $NoKO = $this->input->post('NoKO', TRUE);
        $VendorNm = $this->input->post('VendorNm', TRUE);
        $SubTotal = $this->input->post('SubTotal', TRUE);

        echo '
        
        <table width="100%" border="0">
        <tr>
        <td>
        <center>
        <h4>Anda yakin Approve data berikut ?</h4>
        <br>
        ' . $NoKO . ' - ' . $VendorNm . '<br>
        <h5>Total KO = Rp. ' . $SubTotal . '</h5>
        </center>
        </td>
        </tr>

        <tr>
        <td>
        <br>
        <input type="hidden" name="NoKO" value="' . $NoKO . '">
        <button type="submit" class="btn btn-md btn-success pull-right">Yakin</button>&nbsp;
        <button type="button" class="btn btn-md btn-danger pull-left" data-dismiss="modal">Batal</button>
        </td>
        </tr>


        </table>


        ';
    }

    function lakukan_approve()
    {
        if (!$this->input->post()) {
            exit;
        }

        $NoKO = $this->input->post('NoKO', TRUE);
        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');

        $data = array(
            'ApprovedBy' => $UserEntry,
            'TimeApproved' => $TimeEntry
        );

        $update = $this->M_approval_ko->update_lakukan_approve($data, $NoKO);
        if ($update == TRUE) {
            echo 'berhasil';
            die;
        }

        echo 'gagal';
        die;
    }


    //approved
    function data_json_approved()
    {
        header('Content-Type: application/json');
        echo $this->M_approval_ko->data_json_approved();
    }

    function peringatan_unapprove()
    {
        if (!$this->input->post()) {
            exit;
        }

        $NoKO = $this->input->post('NoKO', TRUE);
        $VendorNm = $this->input->post('VendorNm', TRUE);
        $SubTotal = $this->input->post('SubTotal', TRUE);

        echo '
        
        <table width="100%" border="0">
        <tr>
        <td>
        <center>
        <h4>Anda yakin UnApprove data berikut ?</h4>
        <br>
        ' . $NoKO . ' - ' . $VendorNm . '<br>
        <h5>Total KO = Rp. ' . $SubTotal . '</h5>
        </center>
        </td>
        </tr>

        <tr>
        <td>
        <br>
        <input type="hidden" name="NoKO" value="' . $NoKO . '">
        <button type="submit" class="btn btn-md btn-success pull-right">Yakin</button>&nbsp;
        <button type="button" class="btn btn-md btn-danger pull-left" data-dismiss="modal">Batal</button>
        </td>
        </tr>


        </table>


        ';
    }

    function lakukan_unapprove()
    {
        if (!$this->input->post()) {
            exit;
        }

        $NoKO = $this->input->post('NoKO', TRUE);
        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');

        $data = array(
            'ApprovedBy' => NULL,
            'TimeApproved' => NULL
        );

        $update = $this->M_approval_ko->update_lakukan_unapprove($data, $NoKO);
        if ($update == TRUE) {
            echo 'berhasil';
            die;
        }

        echo 'gagal';
        die;
    }


    /// detail_ko, bisa di gabung untuk lihat detail dari waiting approve atau approved

    function detail_ko($LedgerNo = '', $tipe)
    {

        if (!$this->db->field_exists('LedgerNo', 'KoHdr')) {

            is_pesan('danger', 'laci masih kosong');
            redirect('Approval_ko', 'refresh');
            die;
        }

        // echo 'ok';

        // $cek_tipe = $this->db->query("SELECT KategoriId FROM KoHdr WHERE LedgerN='$LedgerNo' ")->row_array();

        if ($tipe == 'KONTRAK') {
            $data['judul'] = "DETAIL / KONTRAK ";
            $data['bodyclass'] = 'sidebar-collapse skin-blue';

            $dapat_data_ko = $this->M_approval_ko->dapat_data_ko($LedgerNo);
            $data['data_ko'] = $dapat_data_ko;
            $data['checkSP'] = $this->M_approval_ko->checkSP($LedgerNo);
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar');
            $this->load->view('procurement/approval_ko/detail_ko', $data);
            $this->load->view('templates/footer');
            // exit;
        }

        if ($tipe == 'PO') {
            $data['judul'] = "DETAIL / PURCHASE ORDER ";
            $data['bodyclass'] = 'sidebar-collapse skin-blue';

            $dapat_data_ko = $this->M_approval_ko->dapat_data_po($LedgerNo);
            $data['data_ko'] = $dapat_data_ko;
            $data['checkSP'] = $this->M_approval_ko->checkSP($LedgerNo);
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar');
            $this->load->view('procurement/approval_ko/detail_po', $data);
            $this->load->view('templates/footer');
            // exit;
        }
    }

    function tampil_rincian_pekerjaan()
    {
        if (!$this->input->post()) {
            exit;
        }
        $NoKO = $this->input->post('NoKO', TRUE);
        $LedgerNo = $this->input->post('LedgerNo', TRUE);
        // echo $LedgerNo;
        $dapat_ko_dtl = $this->db->query(" SELECT *,(HrgSatuan * Vol) as JumlahHarga FROM KoDtl WHERE NoKO='$NoKO' ORDER BY NoUrut DESC ");
        $table = '';
        if ($dapat_ko_dtl->num_rows() == 0) {
            $table = '
            <table id="table_uraian" class="table table-bordered table-striped table-hover">
            <thead>
            <tr>
            <th>No.</th>
            <th>#</th>
            <th>Kode RAP</th>
            <th>Uraian</th>
            <th>Vol</th>
            <th>Sat</th>
            <th>Harga Satuan (Rp)</th>
            <th>Jumlah Harga (Rp)</th>
            </tr>
            </thead>
            <tbody>
            <tr>
            <td colspan="8">data tidak ditemukan</td>
            </tr>
            </tbody>

            </table>';
            echo $table;
            die;
        }
        // $data_dtl = $dapat_ko_dtl->row_array();
        $table .= '
        <table id="table_uraian"  border="1" class="table table-bordered table-striped table-hover" >
        <thead>
        <tr>
        <th style="text-align:center;width:30px;" >No.</th>
        <th style="text-align:center;width:70px;">#</th>
        <th>Kode RAP</th>
        <th>Uraian</th>
        <th>Vol</th>
        <th>Sat</th>
        <th>Harga Satuan (Rp)</th>
        <th>Jumlah Harga (Rp)</th>
        </tr>
        </thead>
        <tbody>';
        $subtotal = 0;
        foreach ($dapat_ko_dtl->result_array() as $data_dtl) :
            $subtotal += $data_dtl['JumlahHarga'];
            $table .= '
            <tr>
            <td style="text-align:center;">' . $data_dtl['NoUrut'] . '</td>
            <td style="text-align:center;">
            <button type="button"  class="btn btn-xs btn-danger disabled" onclick="hapus_uraian(\'' . $data_dtl['NoUrut'] . '\')"   title="Hapus"><i class="fa fa-trash"></i></button>
            <button type="button"  class="btn btn-xs btn-warning disabled" onclick="edit_uraian(\'' . $data_dtl['NoUrut'] . '\')"   title="SELECT / DETAIL URAIAN"><i class="fa fa-pencil"></i></button>

            </td>
            <td style="text-align:left;">' . strtoupper($data_dtl['KdRAP']) . '</td>
            <td style="text-align:left;">' . strtoupper($data_dtl['Uraian']) . '</td>
            <td style="text-align:right;">' . number_format($data_dtl['Vol']) . '</td>
            <td style="text-align:center;">' . strtoupper($data_dtl['Uom']) . '</td>
            <td style="text-align:right;">' . number_format($data_dtl['HrgSatuan']) . '</td>
            <td style="text-align:right;">' . number_format($data_dtl['JumlahHarga']) . '</td>
            </tr>';
        endforeach;

        $dikurangi_ko = 0;
        $total_pengurangan = 0;
        $grand_total = 0;

        $total = $subtotal - $dikurangi_ko;

        // $total_pengurangan = $total;
        $grand_total = $subtotal - $total_pengurangan;





        $table .= '</tbody>';
        $table .= '
        <tfoot>
        <tr>
        <th colspan="7" style="text-align:right;">Sub Total</th>
        <th style="text-align:right;">
        <input type="text" class="form-control input-sm uang" id="subtotal" value="' . number_format($subtotal) . '" disabled >
        </th>
        </tr>
        <tr>
        <th colspan="7" style="text-align:right;">Dikurangi KO</th>
        <th style="text-align:right;">
        <input type="text" class="form-control input-sm uang" id="dikurangi_ko" value="' . number_format($dikurangi_ko) . '" disabled>
        </th>
        </tr>
        <tr>
        <th colspan="7" style="text-align:right;">Total Pengurangan</th>
        <th style="text-align:right;">
        <input type="text" class="form-control input-sm" id="total_pengurangan" value="' . number_format($total_pengurangan) . '" disabled>
        </th>
        </tr>
        <tr>
        <th colspan="4" style="color:blue;">*terbilang : ' . strtoupper(terbilang($grand_total)) . '</th>
        <th colspan="3" style="text-align:right;">Grand Total</th>
        <th style="text-align:right;">
        <input type="text" class="form-control input-sm" id="grand_total" value="' . number_format($grand_total) . '" name="grand_total" readonly >
        </th>
        </tr>
        </tfoot>';

        $table .= '</table>';

        $table .= '<script>
        
        $("#table_uraian").DataTable({
            "order": [
            [0, "desc"]
            ]
            });
            </script>';

            echo $table;
        }

        function aksi_simpan_uraian_ko()
        {
            if (!$this->input->post()) {
                exit;
            }




            $NoKO = $this->input->post('NoKO', TRUE);
            $KdRAP = $this->input->post('KdRAP', TRUE);
            $Uraian = $this->input->post('Uraian', TRUE);
            $Vol = HapusFormatUang($this->input->post('Vol', TRUE));
            $Uom = $this->input->post('Uom', TRUE);
            $HrgSatuan = HapusFormatUang($this->input->post('HrgSatuan', TRUE));

            $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
            $TimeEntry = date('Y-m-d H:i:s');

            $query_nourut = $this->db->query(" SELECT TOP 1 NoUrut FROM KoDtl WHERE NoKO='$NoKO' ORDER BY NoUrut DESC  ")->row_array();

            $NoUrut =  (int) $query_nourut['NoUrut'] + 1;

            $data = array(
                'NoKO' => $NoKO,
                'NoUrut' => $NoUrut,
                'Alokasi' => 'B',
                'KdRAP' => $KdRAP,
                'Uraian' => $Uraian,
                'Vol' => $Vol,
                'Uom' => $Uom,
                'HrgSatuan' => $HrgSatuan,
                'UserEntry' => $UserEntry,
                'TimeEntry' => $TimeEntry,

            );

            $simpan = $this->M_approval_ko->aksi_simpan_uraian_ko($data);
            if ($simpan == TRUE) {
                echo 'berhasil';
                die;
            }

            echo 'gagal';
            die;

        // print_rr($_POST);
        // print_rr($data);


        }

        function hapus_uraian()
        {
            if (!$this->input->post()) {
                exit;
            }

            $NoUrut = $this->input->post('NoUrut', TRUE);
            $NoKO = $this->input->post('NoKO', TRUE);

            $where = array(
                'NoUrut' => $NoUrut,
                'NoKO' => $NoKO
            );

            $hapus = $this->M_approval_ko->hapus_uraian($where);
            if ($hapus == TRUE) {
                echo 'berhasil';
                die;
            }

            echo 'gagal';
            die;
        }

        function dapatkan_data_kodtl()
        {
            if (!$this->input->post()) {
                exit;
            }

            $NoUrut = $this->input->post('NoUrut', TRUE);
            $NoKO = $this->input->post('NoKO', TRUE);

            $query = $this->db->query("SELECT TOP 1 *, (Vol * HrgSatuan) as TotalHarga FROM KoDtl WHERE NoKO='$NoKO' AND NoUrut='$NoUrut'  ORDER BY NoUrut DESC")->row_array();

            $Vol = PecahAngka($query['Vol']);

            $data = array(
                'KdRAP' => htmlentities($query['KdRAP']),
                'Uraian' => htmlentities($query['Uraian']),
                'Vol' => htmlentities($Vol),
                'Uom' => htmlentities($query['Uom']),
                'HrgSatuan' => htmlentities(number_format($query['HrgSatuan'])),
                'TotalHarga' => htmlentities($query['TotalHarga']),
                'NoKO' => $NoKO,
                'NoUrut' => $NoUrut

            );

            echo json_encode($data);
        }

        function aksi_edit_uraian_ko()
        {
            if (!$this->input->post()) {
                exit;
            }


            $NoUrut = $this->input->post('NoUrut', TRUE);
            $NoKO = $this->input->post('NoKO', TRUE);

            $KdRAP = $this->input->post('KdRAP', TRUE);
            $Uraian = $this->input->post('Uraian', TRUE);
            $Vol = HapusFormatUang($this->input->post('Vol', TRUE));
            $Uom = $this->input->post('Uom', TRUE);
            $HrgSatuan = HapusFormatUang($this->input->post('HrgSatuan', TRUE));

            $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
            $TimeEntry = date('Y-m-d H:i:s');

            $data = array(
                'KdRAP' => $KdRAP,
                'Uraian' => $Uraian,
                'Vol' => $Vol,
                'Uom' => $Uom,
                'HrgSatuan' => $HrgSatuan,
                'UserEntry' => $UserEntry,
                'TimeEntry' => $TimeEntry,

            );

            $where = array(
                'NoKO' => $NoKO,
                'NoUrut' => $NoUrut
            );

            $simpan = $this->M_approval_ko->aksi_update_uraian_ko($data, $where);
            if ($simpan == TRUE) {
                echo 'berhasil';
                die;
            }

            echo 'gagal';
            die;

        // print_rr($_POST);
        // print_rr($data);


        }


    /// tipe po
        function tampil_order_list()
        {
            if (!$this->input->post()) {
                exit;
            }
            $NoKO = $this->input->post('NoKO', TRUE);
            $LedgerNo = $this->input->post('LedgerNo', TRUE);
        // echo $LedgerNo;
            $dapat_ko_dtl = $this->db->query(" SELECT *,(HrgSatuan * Vol) as JumlahHarga FROM KoDtl WHERE NoKO='$NoKO' ORDER BY NoUrut DESC ");
            $table = '';
            if ($dapat_ko_dtl->num_rows() == 0) {
                $table = '
                <table id="table_uraian" class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                <th>No.</th>
                <th>#</th>
                <th>Kode RAP</th>
                <th>Uraian</th>
                <th>Vol</th>
                <th>Sat</th>
                <th>Harga Satuan (Rp)</th>
                <th>Jumlah Harga (Rp)</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                <td colspan="8">data tidak ditemukan</td>
                </tr>
                </tbody>

                </table>';
                echo $table;
                die;
            }
        // $data_dtl = $dapat_ko_dtl->row_array();
            $table .= '
            <table id="table_uraian"  border="1" class="table table-bordered table-striped table-hover" >
            <thead>
            <tr>
            <th style="text-align:center;width:30px;" >No.</th>
            <th style="text-align:center;width:70px;">#</th>
            <th>Kode RAP</th>
            <th>Uraian</th>
            <th>Vol</th>
            <th>Sat</th>
            <th>Harga Satuan (Rp)</th>
            <th>Jumlah Harga (Rp)</th>

            </tr>
            </thead>
            <tbody>';
            $subtotal = 0;
            foreach ($dapat_ko_dtl->result_array() as $data_dtl) :
                $subtotal += $data_dtl['JumlahHarga'];
                $table .= '
                <tr>
                <td style="text-align:center;">' . $data_dtl['NoUrut'] . '</td>
                <td style="text-align:center;">
                <button type="button"  class="btn btn-xs btn-danger disabled" onclick="hapus_uraian(\'' . $data_dtl['NoUrut'] . '\')"   title="Hapus"><i class="fa fa-trash"></i></button>
                <button type="button"  class="btn btn-xs btn-warning disabled" onclick="edit_uraian(\'' . $data_dtl['NoUrut'] . '\')"   title="SELECT / DETAIL URAIAN"><i class="fa fa-pencil"></i></button>

                </td>
                <td style="text-align:left;">' . strtoupper($data_dtl['KdRAP']) . '</td>
                <td style="text-align:left;">' . strtoupper($data_dtl['Uraian']) . '</td>
                <td style="text-align:right;">' . number_format($data_dtl['Vol']) . '</td>
                <td style="text-align:center;">' . strtoupper($data_dtl['Uom']) . '</td>
                <td style="text-align:right;">' . number_format($data_dtl['HrgSatuan']) . '</td>
                <td style="text-align:right;">' . number_format($data_dtl['JumlahHarga']) . '</td>
                </tr>';
            endforeach;

            $data_ko_sub = $this->db->query(" SELECT DiscPercentage,DiscAmount,PPN,OverridePPN, 
                CASE
                WHEN OverridePPN=1 THEN 0
                ELSE PPN
                END TotalPPN
                FROM KoHdr WHERE NoKO='$NoKO'  ")->row_array();

            $check = '';
            if ($data_ko_sub['OverridePPN'] == 1) {
                $check = 'checked';
            }
            $DiscAmount = $data_ko_sub['DiscAmount'];
            $TotalPPN = $data_ko_sub['TotalPPN'];
            $grand_total = ($subtotal - $DiscAmount) + $TotalPPN;


            $table .= '</tbody>';
            $table .= '
            <tfoot>
            <tr>
            <th colspan="6" style="text-align:right;">Sub Total</th>
            <th style="text-align:right;">
            <input type="text" class="form-control input-sm uang text-right" id="subtotal" value="' . number_format($subtotal) . '" disabled >
            </th>
            <th></th>
            </tr>

            <tr>
            <th style="text-align:right;" colspan="5">Disc (%)</th>
            <th style="text-align:right;">
            <input type="text" class="form-control input-sm uang text-right" id="DiscPercentage" value="' . UbahUangKhususDecimal($data_ko_sub['DiscPercentage']) . '" disabled >
            </th>
            <th style="text-align:right;">
            <input type="text" class="form-control input-sm uang text-right" id="DiscAmount" value="' . number_format($DiscAmount) . '" disabled >
            </th>
            <th style="text-align:left;">

            </th>
            </tr>

            <tr>
            <th style="text-align:right;" colspan="6">PPN</th>
            <th style="text-align:right;">
            <input type="text" class="form-control input-sm uang text-right" id="PPN" value="' . number_format($TotalPPN) . '" disabled >
            </th>
            <th style="text-align:left;">
            <input type="checkbox" id="OverridePPN" value="1" ' . $check . ' disabled> Override PPN
            </th>
            </tr>

            <tr>
            <th colspan="3" style="color:blue;">*terbilang : ' . strtoupper(terbilang($grand_total)) . '</th>
            <th style="text-align:right;" colspan="3">Grand Total</th>
            <th style="text-align:right;">
            <input type="text" class="form-control input-sm uang text-right" id="grand_total" value="' . number_format($grand_total) . '" disabled >
            </th>
            <th style="text-align:left;">

            </th>
            </tr>

            <tr>
            <th style="text-align:right;" colspan="6">Mengurangi KO</th>
            <th style="text-align:right;">
            <input type="text" class="form-control input-sm uang" id="MengurangiKO" disabled >
            </th>
            <th style="text-align:left;">

            </th>
            </tr>



            </tfoot>';
        // $table .= '
        // <tfoot>
        //     <tr>
        //         <th colspan="7" style="text-align:right;">Sub Total</th>
        //         <th style="text-align:right;">
        //             <input type="text" class="form-control input-sm uang" id="subtotal" value="' . number_format($subtotal) . '" disabled >
        //         </th>
        //     </tr>
        //     <tr>
        //         <th colspan="7" style="text-align:right;">Dikurangi KO</th>
        //         <th style="text-align:right;">
        //             <input type="text" class="form-control input-sm uang" id="dikurangi_ko" value="' . number_format($dikurangi_ko) . '" disabled>
        //         </th>
        //     </tr>
        //     <tr>
        //         <th colspan="7" style="text-align:right;">Total Pengurangan</th>
        //         <th style="text-align:right;">
        //             <input type="text" class="form-control input-sm" id="total_pengurangan" value="' . number_format($total_pengurangan) . '" disabled>
        //         </th>
        //     </tr>
        //     <tr>
        //         <th colspan="4" style="color:blue;">*terbilang : ' . strtoupper(terbilang($grand_total)) . '</th>
        //         <th colspan="3" style="text-align:right;">Grand Total</th>
        //         <th style="text-align:right;">
        //             <input type="text" class="form-control input-sm" id="grand_total" value="' . number_format($grand_total) . '" name="grand_total" readonly >
        //         </th>
        //     </tr>
        // </tfoot>';

        // $table .= '</table>';

            $table .= '<script>

            $("#table_uraian").DataTable({
                "order": [
                [0, "desc"]
                ]
                });
                </script>';

                echo $table;
            }
        }

        /* End of file Controllername.php */
