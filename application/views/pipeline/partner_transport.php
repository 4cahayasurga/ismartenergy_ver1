<?php
$money=static function($value){return 'Rp '.number_format($value,2,',','.');};
$volume=static function($value){return rtrim(rtrim(number_format($value,3,',','.'),'0'),',').' L';};
$byInvoice=array();
foreach($evidence as $proof)$byInvoice[$proof['InvoiceID']][]=$proof;
?>
<div class="content-wrapper"><section class="content-header"><h1>Portal Transportir</h1></section><section class="content">
<?php $this->load->view('pipeline/billing_notice'); ?>
<form class="box box-primary" method="get"><div class="box-body form-inline">
<label for="partner-year">Tahun</label> <input class="form-control" id="partner-year" type="number" name="year" min="2000" max="2099" required value="<?= (int)$year ?>">
<button class="btn btn-primary">Tampilkan</button></div></form>
<div class="row"><div class="col-md-4"><?php $this->load->view('pipeline/partner_monthly_chart',array('title'=>'Volume pengisian bulanan','values'=>$liters,'format'=>$volume)); ?></div>
<div class="col-md-4"><?php $this->load->view('pipeline/partner_monthly_chart',array('title'=>'Tagihan bulanan','values'=>$billingMonths,'format'=>$money)); ?></div>
<div class="col-md-4"><?php $this->load->view('pipeline/partner_monthly_chart',array('title'=>'Sisa utang per bulan tagihan','values'=>$outstandingMonths,'format'=>$money)); ?></div></div>
<div class="box box-primary"><div class="box-header"><h3 class="box-title">Rekapan Pengisian Selesai</h3></div><div class="box-body table-responsive">
<table class="table table-striped"><thead><tr><th>Permohonan</th><th>Tanggal</th><th>Kapal</th><th>Lokasi</th><th>Volume aktual</th></tr></thead><tbody>
<?php if(!$orders): ?><tr><td colspan="5">Belum ada pengisian selesai pada tahun ini.</td></tr><?php endif; ?>
<?php foreach($orders as $order): ?><tr><td><?= html_escape($order['RequestNo']) ?></td><td><?= html_escape($order['FillingDate']) ?></td><td><?= html_escape($order['VesselName']??'') ?></td><td><?= html_escape($order['Location']??'') ?></td><td><?= $volume($order['VolumeLiters']) ?></td></tr><?php endforeach; ?>
</tbody></table></div></div>
<div class="box box-primary"><div class="box-header"><h3 class="box-title">Kartu Utang</h3></div><div class="box-body table-responsive">
<p>Saldo mengikuti pembayaran yang sudah dibukukan petugas. Daftar ini mencakup seluruh tagihan yang ditugaskan.</p>
<table class="table table-striped"><thead><tr><th>Tagihan</th><th>Jatuh tempo</th><th>Nilai</th><th>Dibayar</th><th>Sisa utang</th><th>Bukti pembayaran</th></tr></thead><tbody>
<?php if(!$invoices): ?><tr><td colspan="6">Belum ada tagihan yang ditugaskan.</td></tr><?php endif; ?>
<?php foreach($invoices as $invoice): $id=(int)$invoice['InvoiceID']; $balance=round((float)$invoice['GrandTotal']-(float)$invoice['Paid'],2); ?>
<tr><td><?= html_escape($invoice['InvoiceNumber']) ?></td><td><?= html_escape($invoice['DueDate']) ?></td><td><?= $money($invoice['GrandTotal']) ?></td><td><?= $money($invoice['Paid']) ?></td><td><?= $money($balance) ?></td><td>
<?php foreach($byInvoice[$id]??array() as $proof): ?><div><a href="<?= site_url('Pipeline_partner/file/'.(int)$proof['EvidenceID']) ?>"><?= html_escape($proof['ReferenceNo']) ?></a> · <?= $money($proof['Amount']) ?> · <?= $proof['Booked']?'Dibukukan':'Menunggu pemeriksaan' ?></div><?php endforeach; ?>
<?php if($balance>0): ?><details><summary>Unggah bukti bayar</summary>
<form action="<?= site_url('Pipeline_partner/upload/'.$id.'?year='.(int)$year) ?>" method="post" enctype="multipart/form-data">
<?php $this->load->view('pipeline/billing_token'); ?>
<div class="form-group"><label>Referensi transaksi <input class="form-control" name="ReferenceNo" maxlength="100" required></label></div>
<div class="form-group"><label>Jumlah bayar (Rp) <input class="form-control" name="Amount" type="number" min="0.01" max="<?= $balance ?>" step="0.01" required></label></div>
<div class="form-group"><label>Bukti PDF, JPG, atau PNG <input type="file" name="Evidence" accept=".pdf,.jpg,.jpeg,.png" required></label></div>
<button class="btn btn-primary">Simpan Bukti</button></form></details><?php endif; ?>
</td></tr><?php endforeach; ?></tbody></table></div></div></section></div>
