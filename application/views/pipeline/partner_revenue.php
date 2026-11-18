<?php $money=static function($cents){return 'Rp '.number_format($cents/100,2,',','.');}; ?>
<div class="content-wrapper"><section class="content-header"><h1>Dashboard Bagi Hasil Pipanisasi</h1></section><section class="content">
<form class="box box-primary" method="get"><div class="box-body form-inline">
<label for="partner-year">Tahun invoice</label> <input class="form-control" id="partner-year" type="number" name="year" min="2000" max="2099" required value="<?= (int)$year ?>">
<button class="btn btn-primary">Tampilkan</button></div></form>
<p>Dasar pembagian adalah nilai Jasa Fasilitas Pipanisasi sebelum PPN, berdasarkan tanggal invoice dan cabang yang dapat Anda akses.</p>
<?php if(!$months): ?><div class="alert alert-info">Belum ada cabang yang ditetapkan untuk akun ini.</div><?php endif; ?>
<div class="row"><?php foreach($roles as $role): $values=$months?array_combine(range(1,12),array_column($months,$role)):array_fill(1,12,0); ?>
<div class="<?= count($roles)>1?'col-md-6':'col-md-8' ?>"><div class="box box-primary"><div class="box-body"><strong><?= $role ?> · <?= $role==='IFPRO'?'27,5%':'72,5%' ?></strong><h2><?= $money(array_sum($values)) ?></h2></div></div>
<?php $this->load->view('pipeline/partner_monthly_chart',array('title'=>'Bagi hasil bulanan '.$role,'values'=>$values,'format'=>$money)); ?></div>
<?php endforeach; ?></div></section></div>
