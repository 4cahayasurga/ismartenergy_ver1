
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_kontrak_po extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('Datatables');
    }

    function dapat_data_json_kontrak_po($JobNo)
    {
        // SELECT A.*, B.VendorNm FROM KoHdr A JOIN Vendor B ON A.VendorId=B.VendorId WHERE JobNo='1328'  ORDER BY NoKO DESC
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
			a.TimeApproved

			");
        $this->datatables->from('KoHdr a');
        $this->datatables->join('Vendor b', 'a.VendorId=b.VendorId', 'LEFT');
        $this->datatables->where('JobNo', $JobNo);

        // $this->datatables->add_column(
        //     'aksi',

        //     '<a href="' . site_url('Kontrak_po/detail_ko/$1') . '" class="btn btn-xs btn-danger" title="DETAIL / EDIT"  >SELECT</a>
        //     <a href="' . site_url('Kontrak_po/detail_ko/$1/$3/$4') . '" class="btn btn-xs btn-danger" title="DETAIL / EDIT"  >SELECT</a>
        //     <a href="javascript:void(0)" class="btn btn-xs btn-warning" onClick=\'lakukan_print("$1","$2")\' title="PRINT DATA">PRINT <i class="fa fa-print"></i></a>

        // 	',
        //     'LedgerNo,ApprovedBy,KategoriId,JobNo'
        // );

        $this->datatables->add_column(
            'aksi',
            '<a href="' . site_url('Kontrak_po/detail_ko/$1/$3/$4') . '" class="btn btn-md btn-danger" title="DETAIL / EDIT"  >SELECT</a>
            <a href="javascript:void(0)" class="btn btn-md btn-warning" onClick=\'lakukan_print("$1","$2","$3")\' title="PRINT DATA">PRINT <i class="fa fa-print"></i></a>

			',
            'LedgerNo,ApprovedBy,KategoriId,JobNo'
        );



        // $this->datatables->add_column('btn_approve','$1','LedgerNo,ApprovedBy');

        $this->datatables->edit_column('SubTotal', '$1', 'UbahGabunganUang(SubTotal)');
        $this->datatables->edit_column('TimeApproved', '$1', 'TglIndoGarisMiring_dengan_waktu(TimeApproved)');
        $this->datatables->edit_column('TglKO', '$1', 'TglIndoGarisMiring_tanpa_waktu(TglKO)');
        return $this->datatables->generate();
    }

    function aksi_simpan_uraian_ko($data)
    {
        $this->db->trans_begin();

        $this->db->insert('KoDtl_sementara', $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    function hapus_uraian_kontrak($where)
    {
        $this->db->trans_begin();

        $this->db->where($where)->delete('KoDtl_sementara');

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

        $this->db->set($data)->where($where)->update('KoDtl_sementara');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }


    function simpan_kohdr_kontrak($data, $JobNo)
    {
        $this->db->trans_begin();
        $NoKO = $data['NoKO'];

        $pindah_ke_KoDtl_yang_asli =  $this->db->query("
            INSERT INTO KoDtl (NoKO,NoUrut,Alokasi,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry,JobNo)
            SELECT NoKO,NoUrut,Alokasi,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry,JobNo FROM KoDtl_sementara
            WHERE NoKO='$NoKO' ");

        $this->db->insert('KoHdr', $data);
        ubah_counter_ko($JobNo, 'B');

        $hapus_kodtl_sementara = $this->db->query(" DELETE FROM KoDtl_sementara WHERE NoKO='$NoKO' ");

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    function dapat_data_ko($LedgerNo)
    {
        return $this->db->query(" 
        SELECT a.*,(ISNULL(a.SubTotal,0) + ISNULL(a.PPN,0)) As Total, b.VendorNm,
        b.Alamat,b.Telepon1,b.Telepon2,b.Telepon3,b.NPWP,b.BidangUsaha,
        c.JobNm,b.ContactPerson,b.Kategori,
        CASE
        WHEN NoSPR IS NOT NULL THEN (SELECT CONCAT_WS(' - ',NoSpr,UtkPekerjaan) FROM PRHdr WHERE NoSPR=a.NoSPR)
        ELSE ''

        END SprNama,
        c.Logo, c.Logo_baru

        FROM KoHdr a 
        LEFT JOIN Vendor b ON a.VendorId=b.VendorId
        LEFT JOIN Job c ON a.JobNo=c.JobNo
        WHERE a.LedgerNo='$LedgerNo' ")->row_array();
    }

    //aksi detail
    function aksi_simpan_uraian_ko_aksi_detail($data)
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

    function hapus_uraian_kontrak_aksi_detail($where)
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

    function aksi_update_uraian_ko_aksi_detail($data, $where)
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

    function update_data_kontrak($data, $LedgerNo)
    {
        $this->db->trans_begin();

        $this->db->set($data)->where('LedgerNo', $LedgerNo)->update('KoHdr');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    } //berkahir aksi detail


    //bagian po
    function aksi_simpan_order_list($data)
    {
        $this->db->trans_begin();

        $this->db->insert('KoDtl_sementara', $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    function hapus_order($where)
    {
        $this->db->trans_begin();

        $this->db->where($where)->delete('KoDtl_sementara');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    function aksi_update_order_list($data, $where)
    {
        $this->db->trans_begin();

        $this->db->set($data)->where($where)->update('KoDtl_sementara');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    function simpan_po($data)
    {
        $this->db->trans_begin();

        // $this->db->insert('KoHdr', $data);

        $NoKO = $data['NoKO'];
        $JobNo = $data['JobNo'];


        $pindah_ke_KoDtl_yang_asli =  $this->db->query("
            INSERT INTO KoDtl (NoKO,NoUrut,Alokasi,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry,JobNo)
            SELECT NoKO,NoUrut,Alokasi,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry,JobNo FROM KoDtl_sementara
            WHERE NoKO='$NoKO' ");

        $this->db->insert('KoHdr', $data);

        ubah_counter_ko($JobNo, 'B');

        $hapus_kodtl_sementara = $this->db->query(" DELETE FROM KoDtl_sementara WHERE NoKO='$NoKO' ");

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }



    // edit po atau detwail
    function aksi_simpan_order_list_aksi_detail($data)
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

    function hapus_order_aksi_detail($where)
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


    function aksi_update_order_list_aksi_detail($data, $where)
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

    function simpan_po_aksi_detail($data, $where)
    {
        $this->db->trans_begin();

        $this->db->set($data)->where($where)->update('KoHdr');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }


    //berkahir aksi detail
    //akhir po

    //module perubahan SPR
    function pindah_dari_spr_ke_kodtl_sementara($NoSPR,$NoKO,$JobNo=''){

        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');

        $this->db->trans_begin();
        

    
        $this->db->query("TRUNCATE TABLE KoDtl_sementara
        INSERT INTO KoDtl_sementara (NoKO,NoUrut,Alokasi,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry, JobNo)
        SELECT '$NoKO',NoUrut,Alokasi,KdRAP,Uraian,Vol,Uom,HrgSatuan,'$UserEntry','$TimeEntry', '$JobNo' FROM PRDtl WHERE NoSPR='$NoSPR' ");

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return 'gagal';
        } else {
            $this->db->trans_commit();
            return 'berhasil';
        }
    }

    function pindah_dari_spr_ke_kodtl($NoSPR,$NoKO,$JobNo){

        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');

        $this->db->trans_begin();

        
        
        $this->db->query("DELETE FROM KoDtl WHERE NoKO='$NoKO'
        INSERT INTO KoDtl (NoKO,NoUrut,Alokasi,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry,JobNo)
        SELECT '$NoKO',NoUrut,Alokasi,KdRAP,Uraian,Vol,Uom,HrgSatuan,'$UserEntry','$TimeEntry','$JobNo' FROM PRDtl WHERE NoSPR='$NoSPR' ");

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return 'gagal';
        } else {
            $this->db->trans_commit();
            return 'berhasil';
        }
    }

}

/* End of file M_kontrak_po.php */
/* Location: ./application/models/M_kontrak_po.php */
