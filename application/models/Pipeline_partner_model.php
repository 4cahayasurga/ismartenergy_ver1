<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pipeline_partner_model extends CI_Model
{
    public function orders($actor)
    {
        return $this->db->query("SELECT R.RequestNo,R.BA FROM PipelineTransportAssignment A
            JOIN PipelineFlowRequest R ON R.RequestID=A.TargetID
            WHERE A.TargetKind='ORDER' AND A.UserID=? AND R.Status='COMPLETED' ORDER BY R.RequestID DESC",array($actor))->result_array();
    }

    public function invoices($actor,$invoiceId=null)
    {
        $sql="SELECT I.InvoiceID,I.InvoiceNumber,I.InvoiceDate,I.DueDate,I.GrandTotal,I.CustomerName,
            ISNULL(P.Paid,0) Paid FROM PipelineTransportAssignment A JOIN PipelineInvoice I ON I.InvoiceID=A.TargetID
            LEFT JOIN (SELECT InvoiceID,SUM(Amount) Paid FROM PipelinePayment GROUP BY InvoiceID) P ON P.InvoiceID=I.InvoiceID
            WHERE A.TargetKind='INVOICE' AND A.UserID=?";
        $args=array($actor);
        if($invoiceId!==null){$sql.=' AND I.InvoiceID=?';$args[]=(int)$invoiceId;}
        return $this->db->query($sql.' ORDER BY I.InvoiceDate DESC,I.InvoiceID DESC',$args)->result_array();
    }

    public function evidence($actor)
    {
        return $this->db->query("SELECT E.EvidenceID,E.InvoiceID,E.ReferenceNo,E.Amount,E.CreatedAt,
            CASE WHEN EXISTS(SELECT 1 FROM PipelinePayment P WHERE P.InvoiceID=E.InvoiceID AND P.ReferenceNo=E.ReferenceNo AND P.Amount=E.Amount)
            THEN 1 ELSE 0 END Booked
            FROM PipelineTransportEvidence E JOIN PipelineTransportAssignment A ON A.TargetKind='INVOICE' AND A.TargetID=E.InvoiceID
            WHERE E.UserID=? AND A.UserID=E.UserID ORDER BY E.EvidenceID DESC",array($actor))->result_array();
    }

    public function revenue($areas,$year,$roles)
    {
        if(!$areas)return array();
        $rows=$this->db->select('MONTH(I.InvoiceDate) MonthNo,I.InvoiceID,SUM(L.Amount) Fee',false)
            ->from('PipelineInvoice I')->join('PipelineInvoiceLine L','L.InvoiceID=I.InvoiceID')
            ->where('I.InvoiceType','INVOICE')->where('L.ServiceCode','FUEL_ADMIN')
            ->where_in('I.SpbbID',$areas)->where('I.InvoiceDate >=',$year.'-01-01')->where('I.InvoiceDate <',($year+1).'-01-01')
            ->group_by('MONTH(I.InvoiceDate),I.InvoiceID',false)->get()->result_array();
        $months=array();
        foreach(range(1,12) as $month)$months[$month]=array_fill_keys($roles,0);
        foreach($rows as $row){
            $fee=(int)round((float)$row['Fee']*100);
            $ifpro=(int)round($fee*0.275);
            if(in_array('IFPRO',$roles,true))$months[$row['MonthNo']]['IFPRO']+=$ifpro;
            if(in_array('ASDP',$roles,true))$months[$row['MonthNo']]['ASDP']+=$fee-$ifpro;
        }
        return $months;
    }
}
