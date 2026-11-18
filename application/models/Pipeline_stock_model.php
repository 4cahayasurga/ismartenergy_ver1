<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Pipeline_stock_model extends CI_Model {
 public function summary($start,$end,$branch=0){
  $this->load->model('Pipeline_billing_model','billing');$ids=array_column($this->billing->branches(),'SpbbID');if($branch)$ids=array_values(array_intersect($ids,array($branch)));
  $result=array('opening'=>0,'in'=>0,'out'=>0,'closing'=>0,'journal'=>array(),'daily'=>array(),'outstanding'=>0);
  if(!$ids)return $result;
  $receipts=$this->db->where_in('SpbbID',$ids)->where('ReceiptDate <=',$end)->get('PipelineReceipt')->result_array();
  $this->Pipeline_model->delivery_query();$do=$this->db->where_in('D.SpbbID',$ids)->where('D.DeliveryDate <=',$end)->get()->result_array();
  foreach($receipts as $r){$q=(float)$r['Quantity'];$date=substr($r['ReceiptDate'],0,10);if($date<$start){$result['opening']+=$q;continue;}$result['in']+=$q;$result['journal'][]=array('date'=>$date,'area'=>$r['SpbbID'],'type'=>$r['MovementType'],'reference'=>$r['ReferenceNo'],'company'=>'','vessel'=>'','quantity'=>$q,'note'=>$r['Notes']);}
  foreach($do as $r){if($this->db->where('DeliveryID',$r['DeliveryID'])->count_all_results('PipelineFlowRequest'))continue;if($r['TotalizerEnd']===null||$r['TotalizerStart']===null||$r['TotalizerEnd']<=$r['TotalizerStart']||round((float)$r['TotalizerEnd']-(float)$r['TotalizerStart'],3)>(float)$r['Quantity'])continue;$q=(float)$r['TotalizerEnd']-(float)$r['TotalizerStart'];$date=substr($r['DeliveryDate'],0,10);if($date<$start){$result['opening']-=$q;continue;}$result['out']+=$q;$result['daily'][$date]=isset($result['daily'][$date])?$result['daily'][$date]+$q:$q;$result['journal'][]=array('date'=>$date,'area'=>$r['SpbbID'],'type'=>'OUT','reference'=>$r['DONumber'],'company'=>$r['BillingCompany'],'vessel'=>$r['VesselName'],'quantity'=>-$q,'note'=>'DO / totalizer');}
  if($this->db->table_exists('PipelineOperation')){
   $role=$this->Spbb_model->get_role($this->session->userdata('MIS_LOGGED_ID'));
   if($role['role']!=='CUSTOMER'){$operations=$this->db->where_in('SpbbID',$ids)->where('Kind','RDT')->where('COALESCE(MovementDate,DocumentDate) <=',$end)->get('PipelineOperation')->result_array();foreach($operations as $r){$q=(float)$r['VolumeLiters'];$date=substr($r['MovementDate']?:$r['DocumentDate'],0,10);if($date<$start){$result['opening']-=$q;continue;}$snap=json_decode($r['Snapshot'],true);$result['out']+=$q;$result['daily'][$date]=($result['daily'][$date]??0)+$q;$result['journal'][]=array('date'=>$date,'area'=>$r['SpbbID'],'type'=>'OUT','reference'=>$r['DocumentNo'],'company'=>$r['CompanyName'],'vessel'=>$snap['VesselName'],'quantity'=>-$q,'note'=>'BA / RDT');}}
  }
  if($this->session->userdata('USER_ROLE')!=='CUSTOMER' && $this->db->table_exists('PipelineFlowRequest')){
   $completed=$this->db->select('SpbbID,BA')->where_in('SpbbID',$ids)->where('Status','COMPLETED')->get('PipelineFlowRequest')->result_array();
   foreach($completed as $r){
    $ba=json_decode($r['BA'],true);$date=$ba['FillingDate'];$q=(float)$ba['VolumeLiters'];
    if($date>$end)continue;
    if($date<$start){$result['opening']-=$q;continue;}
    $result['out']+=$q;$result['daily'][$date]=($result['daily'][$date]??0)+$q;
    $result['journal'][]=array('date'=>$date,'area'=>$r['SpbbID'],'type'=>'OUT','reference'=>$ba['DocumentNo'],
        'company'=>$ba['CompanyName'],'vessel'=>$ba['VesselName'],'quantity'=>-$q,'note'=>'BASTB disetujui');
   }
  }
  foreach($this->billing->invoices() as $r)if(in_array($r['SpbbID'],$ids))$result['outstanding']+=max(0,$r['GrandTotal']-$r['Paid']);
  $result['closing']=$result['opening']+$result['in']-$result['out'];ksort($result['daily']);usort($result['journal'],function($a,$b){return strcmp($b['date'],$a['date']);});return $result;
 }
}
