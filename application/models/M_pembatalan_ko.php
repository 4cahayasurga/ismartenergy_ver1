<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_pembatalan_ko extends CI_Model
{


    public function __construct()
    {
        parent::__construct();
        $this->load->library('Datatables');
    }

    function dapat_list_pembatalan_ko($JobNo)
    {

        $this->datatables->select("
        a.LedgerNo,
        a.JobNo,

        a.NoKO,
        a.TglKO,
        b.VendorId,
        b.VendorNm,
        a.KategoriId,
        a.SubTotal,
        a.TotalTerbayar,
        a.CanceledBy,
        a.TimeCancel,
        a.CancelReason,
        a.ApprovedBy
        ");
        $this->datatables->from('KoHdr a');
        $this->datatables->join('Vendor b', 'a.VendorId=b.VendorId');
        $this->datatables->where('a.JobNo', $JobNo);

        $this->datatables->edit_column('SubTotal', '$1', 'UbahGabunganUang(SubTotal)');
        $this->datatables->edit_column('TotalTerbayar', '$1', 'UbahGabunganUang(TotalTerbayar)');
        $this->datatables->edit_column('TimeCancel', '$1', 'TglIndoGarisMiring_dengan_waktu(TimeCancel)');
        $this->datatables->edit_column('TglKO', '$1', 'TglIndoGarisMiring_tanpa_waktu(TglKO)');

        $this->datatables->add_column('aksi_baru','$1','CanceledBy');

        $this->datatables->add_column(
            'lakukan_cancel',
            '<button type="button"  class="btn btn-xs btn-success" onClick=\'btn_cancel("$1","$2","$3","$4","$5")\' title="Form Cancel">Cancel</button>',
            'LedgerNo,JobNo,NoKO,VendorNm,SubTotal'
        );

        $this->datatables->add_column(
            'batal_cancel',
            '<button type="button"  class="btn btn-xs btn-danger" onClick=\'hapus_cancel("$1","$2","$3","$4","$5")\' title="Form Hapus Cancel">Batal Cancel</button>',
            'LedgerNo,JobNo,NoKO,VendorNm,SubTotal'
        );

        $this->datatables->add_column('detail_baru', '<a href="' . site_url('Kontrak_po/detail_ko/$1/$3/$4/1') . '" class="btn btn-xs btn-warning" title="DETAIL / EDIT"  ><i class="fa fa-eye"></i> </a>', 'LedgerNo,ApprovedBy,KategoriId,JobNo');


        //  $this->datatables->add_column('detail_baru', '<a href="' . site_url('Kontrak_po/detail_ko/$1/$3/$4/1') . '" class="btn btn-xs btn-danger" title="DETAIL / EDIT"  ><i class="fa fa-eye"></i></a>', 'LedgerNo,ApprovedBy,KategoriId,JobNo');

        return $this->datatables->generate();
    }
}

/* End of file M_pembatalan_ko.php */
