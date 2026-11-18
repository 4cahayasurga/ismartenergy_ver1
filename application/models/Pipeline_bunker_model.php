<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Pipeline_bunker_model extends CI_Model {
 public function __construct(){parent::__construct();$this->load->library('Pipeline_bunker_number');$this->load->model('Pipeline_billing_model','billing');}
 private function config($branch,$vessel){
  if(!$this->billing->branch_allowed($branch))throw new RuntimeException('Area di luar akses Anda.');
  $row=$this->db->select('V.VesselCode,V.VesselName,S.BunkerIssuerCode')->from('PipelineVessel V')->join('PipelineCompany C','C.CompanyID=V.CompanyID')->join('SPBB S','S.LedgerNo=C.SpbbID')->where('V.VesselID',(int)$vessel)->where('C.SpbbID',(int)$branch)->where('S.Status','Aktif')->where('C.IsActive',1)->where('V.IsActive',1)->get()->row_array();
  if(!$row)throw new RuntimeException('Kapal tidak ditemukan pada area terpilih.');
  $code=trim((string)$row['VesselCode']);if($code==='')$code=$this->pipeline_bunker_number->known_vessel($row['VesselName']);
  if($code==='')throw new RuntimeException('Kode kapal belum diatur. Lengkapi kode pada Basic Data Kapal / Perusahaan.');
  if(trim((string)$row['BunkerIssuerCode'])==='')throw new RuntimeException('Kode penerbit area belum diatur. Administrator dapat mengisinya pada Basic Data Area.');
  return array('vessel'=>$this->pipeline_bunker_number->code($code,20),'issuer'=>$this->pipeline_bunker_number->code($row['BunkerIssuerCode'],30));
 }
 private function last_number($branch,$period){
  $row=$this->db->where('SpbbID',(int)$branch)->where('NumberingYear',$period['year'])->where('NumberingMonth',$period['month'])->get('PipelineBunkerSequence')->row_array();$last=$row?(int)$row['LastNumber']:0;
  $rows=$this->db->select('RequestNo')->from('PipelineBunker')->where('SpbbID',(int)$branch)->group_start()->group_start()->where('RequestDate >=',$period['start'])->where('RequestDate <',$period['next'])->group_end()->or_like('RequestNo','/'.$period['roman'].'/'.$period['year'],'before')->group_end()->get()->result_array();
  foreach($rows as $r)if(preg_match('/^([0-9]{1,10})\/BBM\//i',$r['RequestNo'],$m))$last=max($last,(int)$m[1]);
  if($last>=2147483646)throw new RuntimeException('Urutan surat melewati batas.');return $last;
 }
 public function preview($branch,$vessel,$date){$p=$this->pipeline_bunker_number->period($date);$c=$this->config((int)$branch,(int)$vessel);$next=$this->last_number($branch,$p)+1;return $this->pipeline_bunker_number->format($next,$c['vessel'],$c['issuer'],$date);}
 public function create($data){
  $snapshot=json_decode(isset($data['Snapshot'])?$data['Snapshot']:'',true);if(!is_array($snapshot))throw new RuntimeException('Isi surat tidak valid.');
  $branch=(int)$data['SpbbID'];$p=$this->pipeline_bunker_number->period($data['RequestDate']);$debug=$this->db->db_debug;$this->db->db_debug=false;$this->db->trans_begin();
  try{
   $lock=$this->db->query("DECLARE @r int;EXEC @r=sp_getapplock @Resource=?,@LockMode='Exclusive',@LockOwner='Transaction',@LockTimeout=10000;SELECT @r AS LockResult",array('pipeline-bunker/'.$branch.'/'.$p['year'].'-'.$p['month']));if(!$lock||(int)$lock->row()->LockResult<0)throw new RuntimeException('Nomor surat sedang diproses. Coba simpan kembali.');
   $c=$this->config($branch,(int)$data['VesselID']);$next=$this->last_number($branch,$p)+1;
   $number=$this->pipeline_bunker_number->format($next,$c['vessel'],$c['issuer'],$data['RequestDate']);
   $counter=$this->db->query('UPDATE PipelineBunkerSequence SET LastNumber=? WHERE SpbbID=? AND NumberingYear=? AND NumberingMonth=?;IF @@ROWCOUNT=0 INSERT INTO PipelineBunkerSequence(SpbbID,NumberingYear,NumberingMonth,LastNumber) VALUES(?,?,?,?)',array($next,$branch,$p['year'],$p['month'],$branch,$p['year'],$p['month'],$next));if(!$counter)throw new RuntimeException('Urutan surat gagal disimpan.');
   $snapshot['VesselCode']=$c['vessel'];$snapshot['BunkerIssuerCode']=$c['issuer'];$snapshot['RequestNo']=$number;
   $data['Snapshot']=json_encode($snapshot,JSON_UNESCAPED_UNICODE);$data['RequestNo']=$number;$data['RequestSequence']=$next;$data['NumberingYear']=$p['year'];$data['NumberingMonth']=$p['month'];
   $columns=array_map(function($key){return '['.$key.']';},array_keys($data));$q=$this->db->query('INSERT INTO PipelineBunker ('.implode(',',$columns).') OUTPUT INSERTED.BunkerID VALUES ('.implode(',',array_fill(0,count($data),'?')).')',array_values($data),true);if(!$q||!$this->db->trans_status())throw new RuntimeException('Surat gagal disimpan; nomor belum diterbitkan.');$id=(int)$q->row()->BunkerID;$this->db->trans_commit();return $id;
  }catch(Exception $e){$this->db->trans_rollback();throw $e;}finally{$this->db->db_debug=$debug;}
 }
}
