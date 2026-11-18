<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_ko_addendum extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('Datatables');
    }

    function dapat_list_ko_addendum($JobNo)
    {

        $this->datatables->select("
			a.LedgerNo,
			a.NoKO,
			a.JobNo,
			a.TglKO,
			b.VendorId,
			b.VendorNm,
			a.KategoriId,
			a.AddendumKe,
			a.SubTotal,
			a.ApprovedBy,
			a.TimeApproved,
            a.Keterangan,
            a.TimeEntry,
            a.AlasanAddendum

			");
        $this->datatables->from('KoHdr a');
        $this->datatables->join('Vendor b', 'a.VendorId=b.VendorId', 'LEFT');
        $this->datatables->where('a.JobNo', $JobNo);
        $this->datatables->where('a.ClosedBy IS NULL', NULL, TRUE);
        $this->datatables->where('a.CanceledBy IS NULL', NULL, TRUE);
        $this->datatables->where('a.ClosedBy IS NULL', NULL, TRUE);

        $this->datatables->edit_column('SubTotal', '$1', 'UbahGabunganUang(SubTotal)');
        $this->datatables->edit_column('TimeApproved', '$1', 'TglIndoGarisMiring_dengan_waktu(TimeApproved)');
        $this->datatables->edit_column('TglKO', '$1', 'TglIndoGarisMiring_tanpa_waktu(TglKO)');

        $this->datatables->add_column(
            'aksi',
            '<button type="button"  class="btn btn-xs btn-info" onClick=\'frm_addendum("$1","$2","$3","$4")\' title="Form Addendum">Addendum</button>',
            'LedgerNo,JobNo,AddendumKe,NoKO'
        );

         $this->datatables->add_column('detail_baru', '<a href="' . site_url('Kontrak_po/detail_ko/$1/$3/$4/1') . '" class="btn btn-xs btn-danger" title="DETAIL / EDIT"  ><i class="fa fa-eye"></i></a>', 'LedgerNo,ApprovedBy,KategoriId,JobNo');

        return $this->datatables->generate();
    }
}

/* End of file ModelName.php */
