<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_approval_ko extends CI_Model
{

    var $Company;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Datatables');
        $this->Company = $this->config->item('Company');
    }

    function dataKOBelumApprove($PrdAwal,$PrdAkhir){
        $query = $this->db->query("SELECT A.*, B.VendorNm, C.JobNm, (ISNULL(A.SubTotal,0) - ISNULL(A.DiscAmount,0) + ISNULL(A.PPN,0)) as TotalKO
            FROM KoHdr A JOIN Vendor B ON A.VendorId=B.VendorId
            JOIN Job C ON A.JobNo=C.JobNo
            WHERE A.TglKO>='$PrdAwal' AND A.TglKO<='$PrdAkhir' AND 
            A.TimeApproved IS NULL AND A.CanceledBy IS NULL AND A.ClosedBy IS NULL 
            AND C.Company='$this->Company'
            ORDER BY A.NoKO DESC");

        $sum_TotalKO = 0;
        if ($query->num_rows()>0) {
            foreach ($query->result() as $row) {
                $SubTotal   = ($row->SubTotal=='' || $row->SubTotal==NULL ) ? 0 : $row->SubTotal;
                $DiscAmount = ($row->DiscAmount=='' || $row->DiscAmount==NULL ) ? 0 : $row->DiscAmount;
                $PPN        = ($row->PPN=='' || $row->PPN==NULL ) ? 0 : $row->PPN;

                $sum_TotalKO += ($SubTotal - $DiscAmount + $PPN);
            }
        }

        return array(
            'dataKO' => $query->result(),
            'TotalKO' => $sum_TotalKO,
        );
    }

    function dataKOSudahApprove($PrdAwal,$PrdAkhir){
        $query = $this->db->query("SELECT A.*, B.VendorNm, C.JobNm, (ISNULL(A.SubTotal,0) - ISNULL(A.DiscAmount,0) + ISNULL(A.PPN,0)) as TotalKO FROM KoHdr A JOIN Vendor B ON A.VendorId=B.VendorId JOIN Job C ON A.JobNo=C.JobNo WHERE A.TglKO>='$PrdAwal' AND A.TglKO<='$PrdAkhir' AND A.TimeApproved IS NOT NULL AND A.CanceledBy IS NULL AND A.ClosedBy IS NULL AND C.Company='$this->Company' ");

        $sum_TotalKO = 0;
        if ($query->num_rows()>0) {
            foreach ($query->result() as $row) {
                $SubTotal   = ($row->SubTotal=='' || $row->SubTotal==NULL ) ? 0 : $row->SubTotal;
                $DiscAmount = ($row->DiscAmount=='' || $row->DiscAmount==NULL ) ? 0 : $row->DiscAmount;
                $PPN        = ($row->PPN=='' || $row->PPN==NULL ) ? 0 : $row->PPN;

                $sum_TotalKO += ($SubTotal - $DiscAmount + $PPN);
            }
        }

        return array(
            'dataKO' => $query->result(),
            'TotalKO' => $sum_TotalKO,
        );
    }

    function data_json_menunggu_approve()
    {

        $this->datatables->select("
            a.JobNo,
            c.JobNm,
            a.NoKO,
            a.TglKO,
            b.VendorNm,
            a.KategoriId,
            (a.SubTotal - a.DiscAmount + a.PPN) AS SubTotal,
            a.ApprovedBy,
            a.TimeApproved,
            c.Company,
            a.LedgerNo,
            (CASE
            WHEN a.KategoriId='KONTRAK' THEN (SELECT SUM(ISNULL(PPN_Nominal,0)) FROM KoDtl WHERE NoKO=a.NoKO GROUP BY NoKO) 
            ELSE 0
            END) AS TotalPPN,

            a.CanceledBy,
            a.ClosedBy,
            ");
        $this->datatables->from('KoHdr a');
        $this->datatables->join('Vendor b', 'a.VendorId=b.VendorId');
        $this->datatables->join('Job c', 'a.JobNo=c.JobNo');
        $this->datatables->where('a.TimeApproved IS NULL', NULL, TRUE);
        $this->datatables->where('a.CanceledBy IS NULL', NULL, TRUE);
        $this->datatables->where('a.ClosedBy IS NULL', NULL, TRUE);
        $this->datatables->where('c.Company', $this->Company);

        $this->datatables->add_column('aksi', '', 'NoKO');
        // $this->datatables->add_column('detail_ko', '<a href="' . site_url('Approval_ko/detail_ko/$1/$2') . '"  class="btn btn-warning btn-xs badge" title="SELECT / DETAIL KO" ><i class="fa fa-solid fa-align-justify"></i></a>', 'LedgerNo,KategoriId');

        // $this->datatables->add_column('detail_baru', '<a href="' . site_url('Kontrak_po/detail_ko/$1/$3/$4/1') . '" class="btn btn-xs btn-danger" title="DETAIL / EDIT"  ><i class="fa fa-eye"></i></a>', 'LedgerNo,ApprovedBy,KategoriId,JobNo');

        $this->datatables->add_column('detail_baru', '<button type="button" id="btnSelect_PO$1" class="btn btn-danger btn-md" onclick="btnSelect_PO($1)" data-NoKO="$2" data-JobNo="$3" data-CanceledBy="$4" data-ApprovedBy="$5" data-ClosedBy="$6" data-KategoriId="$7" ><i class="fa fa-eye"></i></button>','LedgerNo,NoKO,JobNo,CanceledBy,ApprovedBy,ClosedBy,KategoriId');

        // $this->datatables->add_column('detail_kontrak', '<button type="button" id="btnSelect_KONTRAK$1" class="btn btn-danger btn-md" onclick="btnSelect_KONTRAK($1)" data-NoKO="$2" data-JobNo="$3" data-CanceledBy="$4" data-ApprovedBy="$5" data-ClosedBy="$6" data-KategoriId="$7" ><i class="fa fa-eye"></i></button>','LedgerNo,NoKO,JobNo,CanceledBy,ApprovedBy,ClosedBy,KategoriId');


        $this->datatables->edit_column('SubTotal', '$1', 'UbahGabunganUang(SubTotal)');
        // $this->datatables->edit_column('SubTotal', '$1', 'UbahGabunganUang(TotalPPN)');
        $this->datatables->edit_column('TglKO', '$1', 'TglIndoGarisMiring_tanpa_waktu(TglKO)');
        $this->datatables->edit_column('TimeApproved', '$1', 'TglIndoGarisMiring_dengan_waktu(TimeApproved)');

        // $this->db->order_by('a.NoKO', 'DESC');
        return $this->datatables->generate();
    }

    function update_lakukan_approve($data, $NoKO)
    {

        $this->db->trans_begin();

        $this->db->set($data)->where('NoKO', $NoKO)->update('KoHdr');


        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    //approved
    function data_json_approved()
    {

        // SELECT A.*, B.VendorNm, C.JobNm FROM KoHdr A JOIN Vendor B ON A.VendorId=B.VendorId JOIN Job C ON A.JobNo=C.JobNo 
        // WHERE A.TimeApproved IS NOT NULL AND A.CanceledBy IS NULL AND A.ClosedBy IS NULL AND C.Company='MDH' ORDER BY A.NoKO DESC

        $this->db->query("
            DROP TABLE IF EXISTS #temp_sementara_approved
            create table #temp_sementara_approved(
                JobNo nvarchar(10),
                JobNm nvarchar(100),
                NoKO nvarchar(15),
                TglKO date,
                VendorNm nvarchar(100),
                KategoriId nvarchar(30),
                SubTotal money,
                ApprovedBy nvarchar(30) NULL,
                TimeApproved datetime,
                Company nvarchar(3),
                LedgerNo bigint,
                CanceledBy nvarchar(30) NULL,
                ClosedBy nvarchar(30) NULL,
                );

            INSERT INTO #temp_sementara_approved (JobNo,JobNm,NoKO,TglKO,VendorNm,KategoriId,SubTotal,ApprovedBy,TimeApproved,Company,LedgerNo,CanceledBy,ClosedBy)
            SELECT a.JobNo,c.JobNm,a.NoKO,a.TglKO,b.VendorNm,a.KategoriId, (ISNULL(a.SubTotal,0) - ISNULL(a.DiscAmount,0) + ISNULL(a.PPN,0)  ) as SubTotal,a.ApprovedBy,a.TimeApproved,c.Company,a.LedgerNo, a.CanceledBy, a.ClosedBy FROM KoHdr a JOIN Vendor b ON a.VendorId=b.VendorId JOIN Job c ON a.JobNo=c.JobNo 
            WHERE a.TimeApproved IS NOT  NULL AND a.CanceledBy IS NULL AND a.ClosedBy IS NULL AND c.Company='$this->Company' ORDER BY A.NoKO DESC

            ");

$this->datatables->select("
    JobNm,
    NoKO,
    TglKO,
    VendorNm,
    KategoriId,
    SubTotal,
    ApprovedBy,
    TimeApproved,
    Company,
    JobNo,
    LedgerNo,
    CanceledBy,
    ClosedBy,
    ");
$this->datatables->from('#temp_sementara_approved');
$this->datatables->add_column('aksi', '', 'NoKO');

        // $this->datatables->add_column('detail_ko', '<a href="' . site_url('Approval_ko/detail_ko/$1/$2') . '"  class="btn btn-warning btn-xs badge" title="SELECT / DETAIL KO" ><i class="fa fa-solid fa-align-justify"></i></a>', 'LedgerNo,KategoriId');
        // $this->datatables->add_column('detail_baru', '<a href="' . site_url('Kontrak_po/detail_ko/$1/$3/$4/1') . '" class="btn btn-md btn-danger" title="DETAIL / EDIT"  ><i class="fa fa-eye"></i></a>', 'LedgerNo,ApprovedBy,KategoriId,JobNo');

$this->datatables->add_column('detail_baru', '<button type="button" id="btnSelect_PO$1" class="btn btn-danger btn-md" onclick="btnSelect_PO($1)" data-NoKO="$2" data-JobNo="$3" data-CanceledBy="$4" data-ApprovedBy="$5" data-ClosedBy="$6" data-KategoriId="$7" ><i class="fa fa-eye"></i></button>','LedgerNo,NoKO,JobNo,CanceledBy,ApprovedBy,ClosedBy,KategoriId');

$this->datatables->edit_column('SubTotal', '$1', 'UbahGabunganUang(SubTotal)');
$this->datatables->edit_column('TglKO', '$1', 'TglIndoGarisMiring_tanpa_waktu(TglKO)');
$this->datatables->edit_column('TimeApproved', '$1', 'TglIndoGarisMiring_dengan_waktu(TimeApproved)');

        // $this->db->order_by('a.NoKO', 'DESC');
return $this->datatables->generate();
}

function update_lakukan_unapprove($data, $NoKO)
{

    $this->db->trans_begin();

    $this->db->set($data)->where('NoKO', $NoKO)->update('KoHdr');


    if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        return false;
    } else {
        $this->db->trans_commit();
        return true;
    }
}


    //kontrak
function dapat_data_ko($LedgerNo)
{
    return $this->db->query(" 
        SELECT a.*, b.VendorNm,
        b.Alamat,b.Telepon1,b.Telepon2,b.Telepon3,b.NPWP,b.BidangUsaha,
        c.JobNm,b.ContactPerson,b.Kategori

        FROM KoHdr a 
        LEFT JOIN Vendor b ON a.VendorId=b.VendorId
        LEFT JOIN Job c ON a.JobNo=c.JobNo
        WHERE a.LedgerNo='$LedgerNo' ")->row_array();
}

function aksi_simpan_uraian_ko($data)
{
    $this->db->trans_begin();

    $this->db->insert('KoDtl', $data);

    if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        return false;
    } else {
        $this->db->trans_commit();
        return true;
    }
}

function hapus_uraian($where)
{
    $this->db->trans_begin();

    $this->db->where($where)->delete('KoDtl');

    if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        return false;
    } else {
        $this->db->trans_commit();
        return true;
    }
}

function aksi_update_uraian_ko($data, $where)
{
    $this->db->trans_begin();

    $this->db->set($data)->where($where)->update('KoDtl');

    if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        return false;
    } else {
        $this->db->trans_commit();
        return true;
    }
}

function checkSP($LedgerNo = null)
{

    $query = "SELECT 
    case
    when a.item = 'INV' then 'Invoice/Kwitansi'
    when a.item = 'SJ' then 'Surat Jalan/Tanda Terima Lapangan'
    when a.item = 'PO' then 'Copy PO'
    when a.item = 'FP' then 'Faktur Pajak'
    when a.item = 'BAP' then 'Berita Acara Pembayaran'
    when a.item = 'BAOP' then 'Berita Acara Opname Pekerjaan'
    end as item
    from 
    (select item from
        dbo.SplitString(
            (select SyaratPembayaran from KoHdr where LedgerNo = '$LedgerNo') 
            , ',')
    ) as a";

    return $this->db->query($query)->result_object();
}

function dapat_data_po($LedgerNo)
{
    return $this->db->query("
        SELECT a.*, b.VendorNm,
        b.Alamat,b.Telepon1,b.Telepon2,b.Telepon3,b.NPWP,b.BidangUsaha,
        c.JobNm,b.ContactPerson,b.Kategori,
        CASE
        WHEN NoSPR IS NOT NULL THEN (SELECT UtkPekerjaan FROM PRHdr WHERE NoSPR=a.NoSPR)
        ELSE ''

        END SprNama


        FROM KoHdr a 
        LEFT JOIN Vendor b ON a.VendorId=b.VendorId
        LEFT JOIN Job c ON a.JobNo=c.JobNo
        WHERE a.LedgerNo='$LedgerNo' ")->row_array();
}



}

/* End of file M_approval_ko.php */
