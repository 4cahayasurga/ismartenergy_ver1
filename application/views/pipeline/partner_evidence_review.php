<?php
$ci=get_instance();
if(!$ci->pipeline_access->allowed('receivables')||!$ci->db->table_exists('PipelineTransportEvidence'))return;
$proofs=$ci->db->query("SELECT E.*, CASE WHEN EXISTS(SELECT 1 FROM PipelinePayment P WHERE P.InvoiceID=E.InvoiceID AND P.ReferenceNo=E.ReferenceNo AND P.Amount=E.Amount) THEN 1 ELSE 0 END Booked
    FROM PipelineTransportEvidence E WHERE E.InvoiceID=? ORDER BY E.EvidenceID DESC",array($invoice['InvoiceID']))->result_array();
if(!$proofs)return;
$ledger=site_url('Pipeline_billing/receivables?'.http_build_query(array('customer'=>$invoice['CustomerID'],'branch'=>$invoice['SpbbID'])));
?>
<h4>Bukti Pembayaran Transportir</h4>
<div class="table-responsive"><table class="table table-striped">
<thead><tr><th>Transportir</th><th>Referensi</th><th>Jumlah</th><th>Status</th></tr></thead>
<tbody><?php foreach($proofs as $proof): ?>
<tr><td><?= html_escape($proof['UserID']) ?></td>
<td><a href="<?= site_url('Pipeline_partner/file/'.(int)$proof['EvidenceID']) ?>"><?= html_escape($proof['ReferenceNo']) ?></a></td>
<td>Rp <?= number_format($proof['Amount'],2,',','.') ?></td>
<td><?= $proof['Booked']?'Dibukukan':'Menunggu pemeriksaan' ?></td></tr>
<?php endforeach; ?></tbody></table></div>
<p><a href="<?= $ledger ?>">Buka Kartu Piutang untuk mencatat pembayaran</a></p>
