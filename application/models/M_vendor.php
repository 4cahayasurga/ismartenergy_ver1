<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_vendor extends CI_Model
{

	function dapat_list_data_vendor()
	{
		$this->load->library('Datatables');


		$this->datatables->select("VendorId,VendorNm,Alamat,Kota,Propinsi,Kategori,BidangUsaha,TimeEntry");
		$this->datatables->from('Vendor');
		$this->datatables->add_column('aksi', '
			<button type="button" class="btn btn-xs btn-warning"  ><i class="fa fa-edit"></i> Edit</button>', 'VendorId');


		$this->datatables->edit_column('VendorNm', '$1', 'strtoupper(VendorNm)');
		$this->datatables->edit_column('Alamat', '$1', 'strtoupper(Alamat)');
		$this->datatables->edit_column('Kota', '$1', 'strtoupper(Kota)');
		$this->datatables->edit_column('Propinsi', '$1', 'strtoupper(Propinsi)');
		$this->datatables->edit_column('Kategori', '$1', 'strtoupper(Kategori)');
		$this->datatables->edit_column('BidangUsaha', '$1', 'strtoupper(BidangUsaha)');

		return $this->datatables->generate();
	}

	function simpan_vendor($data)
	{
		$this->db->trans_begin();

		$this->db->insert('Vendor', $data);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

	function edit_vendor($data, $VendorId)
	{
		$this->db->trans_begin();

		$this->db->set($data)->where('VendorId', $VendorId)->update('Vendor');


		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}
}

/* End of file M_vendor.php */
/* Location: ./application/models/M_vendor.php */