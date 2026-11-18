<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Pipeline_master_model extends CI_Model {
 /** Daftar master dibatasi area; pilihan transaksi hanya memakai data aktif. */
 public function companies($activeOnly=true){
  if(!$this->db->table_exists('PipelineCompany'))return array();$this->load->model('Pipeline_billing_model','billing');$ids=array_column($this->billing->branches(),'SpbbID');if(!$ids)return array();
  $this->db->where_in('SpbbID',$ids);if($activeOnly)$this->db->where('IsActive',1);return $this->db->order_by('CompanyName')->get('PipelineCompany')->result_array();
 }
 public function vessels($activeOnly=true){$ids=array_column($this->companies($activeOnly),'CompanyID');if(!$ids)return array();$this->db->select('V.*,C.CompanyName,C.SpbbID,C.IsActive AS CompanyActive')->from('PipelineVessel V')->join('PipelineCompany C','C.CompanyID=V.CompanyID')->where_in('V.CompanyID',$ids);if($activeOnly)$this->db->where('V.IsActive',1);return $this->db->order_by('V.VesselName')->get()->result_array();}
 /** Cari record melalui daftar yang sudah dibatasi area, termasuk yang nonaktif. */
 public function record($type,$id){if(!in_array($type,array('company','vessel'),true))throw new InvalidArgumentException('Jenis master tidak valid.');$key=$type==='company'?'CompanyID':'VesselID';foreach($type==='company'?$this->companies(false):$this->vessels(false) as $row)if((int)$row[$key]===(int)$id)return $row;return null;}
 /** Simpan tanpa memindahkan area atau mengubah identitas transaksi lama. */
 public function save_record($type,$id,$data){
  $row=$this->record($type,$id);if(!$row)throw new RuntimeException('Data tidak ditemukan atau di luar akses area.');
  $allowed=$type==='company'?array('CompanyName','Address','IsActive'):array('CompanyID','VesselName','VesselCode','Flag','GrossTonnage','VesselType','MainTank','DailyTank','IsActive');
  if(array_diff(array_keys($data),$allowed))throw new InvalidArgumentException('Kolom master tidak valid.');
  if(isset($data['IsActive'])&&!in_array($data['IsActive'],array(0,1),true))throw new InvalidArgumentException('Status tidak valid.');
  if($type==='vessel'&&isset($data['CompanyID'])){$parent=$this->record('company',$data['CompanyID']);if(!$parent||(int)$parent['SpbbID']!==(int)$row['SpbbID'])throw new RuntimeException('Perusahaan kapal harus berada di area yang sama.');}
  $table=$type==='company'?'PipelineCompany':'PipelineVessel';$key=$type==='company'?'CompanyID':'VesselID';$debug=$this->db->db_debug;$this->db->db_debug=false;
  try{if(!$this->db->where($key,(int)$id)->update($table,$data))throw new RuntimeException('Gagal menyimpan. Nama mungkin sudah digunakan pada area/perusahaan tersebut.');}finally{$this->db->db_debug=$debug;}
 }
}
