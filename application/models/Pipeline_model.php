<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pipeline_model extends CI_Model
{
    private $pipeline_user;
    private $pipeline_role;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Spbb_model');
        $this->pipeline_user = $this->session->userdata('MIS_LOGGED_ID');
        $this->pipeline_role = $this->Spbb_model->get_role($this->pipeline_user);
    }

    public function scope()
    {
        $id = $this->pipeline_user;
        $role = $this->pipeline_role;
        $this->db->where('O.OrderChannel', 'PIPANISASI');
        if ($role['role'] === 'SUPERADMIN' || strtolower((string) $id) === 'admin') return;
        if ($role['role'] === 'CUSTOMER') $this->db->where('O.CustomerID', $id);
        elseif (count($role['spbb_ids'])) $this->db->where_in('O.SpbbID', $role['spbb_ids']);
        else $this->db->where('1 = 0', null, false);
    }

    public function orders($start = null, $end = null, $includeWorkflow = false)
    {
        $this->db->select('O.*')->from('SpbbOrder O');
        $this->scope();
        if ($start) $this->db->where('O.CreatedAt >=', $start)->where('O.CreatedAt <', date('Y-m-d', strtotime($end . ' +1 day')));
        $orders = $this->db->order_by('O.OrderID', 'DESC')->get()->result_array();
        if (!$includeWorkflow || !$this->db->table_exists('PipelineFlowRequest')) return $orders;
        $role = $this->pipeline_role;
        if ($role['role']==='CUSTOMER') {
            $this->db->where('CreatedBy',$this->pipeline_user);
        } else {
            if ($role['role']!=='SUPERADMIN' && strtolower((string)$this->pipeline_user)!=='admin') {
                if (!$role['spbb_ids']) return $orders;
                $this->db->where_in('SpbbID',$role['spbb_ids']);
            }
            $this->db->where('Status !=','DRAFT');
        }
        if ($start) $this->db->where('RequestDate >=',$start)->where('RequestDate <=',$end);
        foreach ($this->db->get('PipelineFlowRequest')->result_array() as $request) {
            if ((json_decode($request['Requirements'],true)['SupervisorState']??'')==='CANCELLED') continue;
            $letter = json_decode($request['Letter'],true);
            $orders[] = array('InvoiceNo'=>$request['RequestNo'],'CreatedAt'=>$request['CreatedAt'],
                'OrderStatus'=>$request['Status'],'Quantity'=>$letter['PlannedLiters']);
        }
        usort($orders,function ($a,$b) { return strcmp($b['CreatedAt'],$a['CreatedAt']); });
        return $orders;
    }

    public function delivery_scope()
    {
        $id=$this->pipeline_user; $role=$this->pipeline_role;
        $this->db->group_start()->where('D.OrderID IS NULL',null,false)->or_where('O.OrderChannel','PIPANISASI')->group_end();
        if ($role['role']==='SUPERADMIN' || strtolower((string)$id)==='admin') return;
        if ($role['role']==='CUSTOMER') $this->db->where('D.CustomerID',$id);
        elseif ($role['spbb_ids']) $this->db->where_in('D.SpbbID',$role['spbb_ids']);
        else $this->db->where('1 = 0',null,false);
    }
    public function delivery_query()
    {
        $this->db->select('D.*, O.InvoiceNo, O.OrderStatus, X.InvoiceID, I.InvoiceNumber, I.GrandTotal, ISNULL(P.Paid,0) AS Paid',false)
            ->from('PipelineDelivery D')->join('SpbbOrder O','O.OrderID=D.OrderID','left')
            ->join('PipelineInvoiceDelivery X','X.DeliveryID=D.DeliveryID','left')
            ->join('PipelineInvoice I','I.InvoiceID=X.InvoiceID','left')
            ->join('(SELECT InvoiceID,SUM(Amount) Paid FROM PipelinePayment GROUP BY InvoiceID) P','P.InvoiceID=I.InvoiceID','left',false);
        $this->delivery_scope();
    }
    public function deliveries($start, $end)
    {
        $this->delivery_query();
        return $this->db->where('D.DeliveryDate >=',$start)->where('D.DeliveryDate <=',$end)
            ->order_by('D.DeliveryDate','DESC')->order_by('D.DeliveryID','DESC')->get()->result_array();
    }

    public function monitoring($start,$end,$branch=0)
    {
        $this->db->select('O.*,V.VesselName,V.RegistrationNo,B.CompanyName,Q.AllocatedQuota,Q.BookedQuota,Q.UsedQuota')
            ->from('SpbbOrder O')->join('JbtVessel V','V.VesselID=O.VesselID','left')
            ->join('JbtBusinessEntity B','B.BusinessEntityID=V.BusinessEntityID','left')
            ->join('JbtVesselQuota Q','Q.VesselQuotaID=O.VesselQuotaID','left');
        $this->scope();
        if($branch)$this->db->where('O.SpbbID',(int)$branch);
        $orders=$this->db->where('O.CreatedAt >=',$start)->where('O.CreatedAt <',date('Y-m-d',strtotime($end.' +1 day')))->get()->result_array();
        $rows=array(); $quotas=array();
        foreach($orders as $order) {
            $key=$order['SpbbID'].'/'.$order['VesselID'];
            if(!isset($rows[$key]))$rows[$key]=array('VesselName'=>$order['VesselName'],'CompanyName'=>$order['CompanyName'],'Purchased'=>0,'Realized'=>0,'Allocated'=>0,'Booked'=>0,'Used'=>0);
            if($order['OrderStatus']!=='DITOLAK')$rows[$key]['Purchased']+=$order['Quantity'];
            if($order['OrderStatus']==='SELESAI')$rows[$key]['Realized']+=isset($order['ActualQuantity'])?$order['ActualQuantity']:$order['Quantity'];
            $quota=$key.'/'.$order['VesselQuotaID'];
            if(!isset($quotas[$quota])) { $quotas[$quota]=true; $rows[$key]['Allocated']+=(float)$order['AllocatedQuota']; $rows[$key]['Booked']+=(float)$order['BookedQuota']; $rows[$key]['Used']+=(float)$order['UsedQuota']; }
        }
        return $rows;
    }
    public function movements($start,$end)
    {
        $this->db->select('M.*,V.VesselName,B.CompanyName,F.FuelName')->from('SpbbStockMovement M')
            ->join('SpbbOrder O','O.OrderID=M.OrderID')->join('JbtVessel V','V.VesselID=O.VesselID','left')
            ->join('JbtBusinessEntity B','B.BusinessEntityID=V.BusinessEntityID','left')->join('SpbbFuelPrice F','F.FuelID=M.FuelID','left');
        $this->scope();
        return $this->db->where('M.CreatedAt >=',$start)->where('M.CreatedAt <',date('Y-m-d',strtotime($end.' +1 day')))
            ->order_by('M.MovementID','DESC')->limit(100)->get()->result_array();
    }
}
