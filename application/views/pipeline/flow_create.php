<div class="content-wrapper"><section class="content-header"><h1>Buat Surat Permohonan Bunkering</h1></section>
<section class="content">
<?php if ($error): ?><div class="alert alert-danger"><?= html_escape($error) ?></div><?php endif; ?>
<?php if (!$profile || !$areas || !$vessels || (count($members)<2 || !$supervisors)): ?>
<div class="alert alert-info"><ul>
<?php if (!$company): ?><li>Hubungkan akun ke badan usaha melalui Admin Kapal / Customers.</li>
<?php elseif (!$profile): ?><li>Lengkapi <a href="<?= site_url('Pipeline_flow/profile') ?>">profil dan logo perusahaan</a>.</li><?php endif; ?>
<?php if (!$areas): ?><li>Administrator perlu memberikan akses cabang pada akun pemesan.</li><?php endif; ?>
<?php if (!$vessels): ?><li>Daftarkan kapal aktif milik perusahaan di Master Kapal &amp; Kuota.</li><?php endif; ?>
<?php if (count($members)<2 || !$supervisors): ?><li>Siapkan Nakhoda dan KKM dengan akses Approval Pengisian, serta akun terpisah dengan akses Approval Permohonan Bunkering.</li><?php endif; ?>
</ul></div>
<?php else: ?>
<div class="box box-primary"><div class="box-body">
<p><?php $automaticNumber=trim((string)$profile['IssuerCode'])!==''; ?><?= $automaticNumber?'Sistem membuat nomor surat saat draf disimpan.':'Masukkan nomor surat yang ditetapkan perusahaan.' ?></p>
<form method="post"><?php $this->load->view('pipeline/billing_token'); ?>
<div class="row">
<?php $options=array('SpbbID'=>array('Cabang',array_column($areas,'SpbbName','SpbbID')),'VesselID'=>array('Kapal',array_column($vessels,'VesselName','VesselID')));
foreach (array('CaptainUser'=>'Nakhoda','KkmUser'=>'Kepala Kamar Mesin','SupervisorUser'=>'Supervisi Pelayaran') as $key=>$title) $options[$key]=array($title,$key==='SupervisorUser'?$supervisors:array_column($members,'UserName','UserID'));
foreach ($options as $name=>$option): ?>
<div class="col-sm-6 form-group"><label for="flow-<?= $name ?>"><?= $option[0] ?></label>
<select class="form-control" id="flow-<?= $name ?>" name="<?= $name ?>" required><option value="">Pilih</option>
<?php foreach ($option[1] as $value=>$label): ?><option value="<?= html_escape($value) ?>" <?= (string)($input[$name]??'')===(string)$value?'selected':'' ?>><?= html_escape($label) ?></option><?php endforeach; ?></select></div>
<?php endforeach; ?>
<?php $fields=($automaticNumber
    ?array('VesselCode'=>array('Kode kapal, misalnya PL2','text',20))
    :array('RequestNo'=>array('Nomor surat','text',100)))+array(
    'Flag'=>array('Bendera','text',100),'GrossTonnage'=>array('DWT/GT','text',100),'VesselType'=>array('Jenis Kapal','text',100),
    'MainTank'=>array('Kapasitas Tangki Utama','text',100),'DailyTank'=>array('Kapasitas Tangki Harian','text',100),
    'RemainingFuel'=>array('Sisa BBM saat ini','text',200),'PlannedLiters'=>array('Rencana pengisian (liter)','number',30),
    'Supplier'=>array('Pemasok','text',200),'OriginPort'=>array('Datang dari','text',200),'ArrivalPort'=>array('Kapal tiba','text',200),
    'FillingDate'=>array('Rencana tanggal pengisian','date',10),'Position'=>array('Posisi kapal','text',200));
foreach ($fields as $name=>$field): ?>
<div class="col-sm-6 form-group"><label for="flow-<?= $name ?>"><?= $field[0] ?></label>
<input id="flow-<?= $name ?>" class="form-control" name="<?= $name ?>" type="<?= $field[1] ?>" maxlength="<?= $field[2] ?>" <?= $field[1]==='number'?'min="0.001" step="0.001"':'' ?> value="<?= html_escape(is_scalar($input[$name]??null)?$input[$name]:'') ?>" required></div>
<?php endforeach; ?></div><button class="btn btn-primary">Simpan Draf Surat</button></form></div></div>
<?php endif; ?></section></div>
