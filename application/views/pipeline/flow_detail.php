<?php $id=(int)$row['RequestID']; $actionUrl=site_url('Pipeline_flow/action/'.$id); ?>
<div class="content-wrapper"><section class="content-header"><h1><?= html_escape($row['RequestNo']) ?></h1></section>
<section class="content"><?php $this->load->view('pipeline/billing_notice'); ?>
<div class="box box-primary"><div class="box-body">
<p>Waktu dibuat: <?= html_escape($row['CreatedAt']??'—') ?></p>
<h3><?= html_escape($letter['VesselName'].' · '.$letter['CompanyName']) ?></h3>
<?php if(isset($currentCompany['CompanyName']) && $currentCompany['CompanyName']!==$letter['CompanyName']): ?>
<p>Nama perusahaan tersimpan pada surat <strong><?= html_escape($letter['CompanyName']) ?></strong>.
Perusahaan Customer saat ini <strong><?= html_escape($currentCompany['CompanyName']) ?></strong>.</p>
<?php endif; ?>
<p><strong><?= html_escape($this->flow->filling_status($row)) ?></strong> · <?= number_format($letter['PlannedLiters'],3,',','.') ?> L</p>
<?php if(!empty($attachmentError)): ?><div class="alert alert-danger">PDF approval belum dapat dilampirkan: <?= html_escape($attachmentError) ?></div><?php endif; ?>
<?php if ($row['Note']): ?><p><?= nl2br(html_escape($row['Note'])) ?></p><?php endif; ?>
<a class="btn btn-default" href="<?= site_url('Pipeline_flow/document/'.$id.'/Bunker') ?>">Lihat / Cetak Permohonan Bunker</a>
<?php $supervisorState=get_instance()->flow->supervisor_state($row); ?>
<?php if (get_instance()->flow->approved_bunker($row)): ?>
<a class="btn btn-success" href="<?= site_url('Pipeline_flow/document/'.$id.'/Bunker?pdf=1') ?>">Unduh Permohonan Disetujui (QR)</a>
<?php elseif ($supervisorState==='PENDING'): ?>
<p class="text-warning">Permohonan sudah masuk antrean Supervisi Customer yang ditugaskan.</p>
<?php elseif ($editor): ?>
<p class="text-warning"><?= $row['Status']==='REVISION'?'Perbaiki permohonan lalu ajukan ulang agar masuk antrean Supervisi Customer.':'Lengkapi permohonan lalu ajukan agar masuk antrean Supervisi Customer.' ?></p>
<a class="btn btn-primary" href="#ajukan-ke-supervisi"><?= $row['Status']==='REVISION'?'Ajukan Ulang ke Supervisi':'Ajukan ke Supervisi' ?></a>
<?php elseif (in_array($supervisorState,array('REJECTED','CANCELLED'),true)): ?>
<p class="text-warning"><?= $supervisorState==='REJECTED'?'Permohonan ditolak Supervisi Customer.':'Permohonan dibatalkan.' ?></p>
<?php elseif (in_array($row['Status'],array('DRAFT','REVISION'),true)): ?>
<p class="text-warning">Menunggu pengajuan dari pembuat permohonan.</p>
<?php endif; ?>
<?php if ($row['CaptureID']): ?><a class="btn btn-default" href="<?= site_url('Pipeline_flow/file/'.$id.'/SPP') ?>">Lihat <?= html_escape($row['SourceKind']??'SPP') ?></a><?php endif; ?>
<?php if ($row['BA'] && !$this->flow->filling_progress($row)): ?><a class="btn btn-default" href="<?= site_url('Pipeline_flow/document/'.$id.'/BA') ?>">Lihat BA Pengisian</a><?php endif; ?>
<?php if (in_array($row['Status'],array('BASTB_APPROVAL','COMPLETED'),true)): ?><a class="btn btn-primary" href="<?= site_url('Pipeline_flow/document/'.$id.'/BASTB') ?>"><?= $row['Status']==='COMPLETED'?'Cetak BASTB':'Tinjau Draf BASTB' ?></a><?php endif; ?>
</div></div>
<?php $stockDate=$this->flow->filling_progress($row)['FillingDate']??(json_decode($row['BA'],true)['FillingDate']??''); ?>
<?php if ($staff && $row['Status']==='COMPLETED' && $stockDate && $this->pipeline_access->allowed('dashboard')): ?>
<p><a class="btn btn-default" href="<?= site_url('Pipanisasi').'?'.http_build_query(array('area'=>(int)$row['SpbbID'],'start'=>$stockDate,'end'=>$stockDate)) ?>">Lihat Stock Movement pada Tanggal Pengisian</a></p>
<?php endif; ?>
<?php $sources=$this->flow->verified_sources($row); ?>
<?php if ($sources): ?>
<div class="box box-primary"><div class="box-header"><h3 class="box-title">Surat Pengantar Pengiriman Terverifikasi</h3></div><div class="box-body">
<p>Permohonan <?= number_format($letter['PlannedLiters'],3,',','.') ?> L · Total dokumen <?= number_format($this->flow->source_total($row),3,',','.') ?> L · Sisa <?= number_format($this->flow->source_remaining($row),3,',','.') ?> L</p>
<ul><?php foreach ($sources as $source): ?><li><a href="<?= site_url('Pipeline_spp/review/'.(int)$source['CaptureID']) ?>"><?= html_escape($source['OriginalName']) ?></a> · <?= number_format($source['OrderedLiters'],3,',','.') ?> L</li><?php endforeach; ?></ul>
</div></div>
<?php endif; ?>
<?php $this->load->view('pipeline/partner_assignment',array('assignment_kind'=>'ORDER','assignment_id'=>$id)); ?>
<div class="box box-primary"><div class="box-header"><h3 class="box-title">Dokumen Pendukung</h3></div><div class="box-body">
<div class="alert alert-info">Setiap file disimpan terpisah. File yang sudah tersimpan tidak dihapus saat pengajuan belum lulus. Cukup lengkapi atau ganti dokumen yang ditandai perlu diperbaiki.</div>
<div id="flow-upload-status" role="status" aria-live="polite"></div>
<?php if ($blockers): ?>
<div class="alert alert-warning"><strong>Syarat sebelum pengisian</strong><ul>
<?php foreach ($blockers as $blocker): ?><li><?= html_escape($blocker) ?></li><?php endforeach; ?>
</ul></div>
<?php endif; ?>
<?php foreach ($labels as $kind=>$label): $file=$files[$kind]??null; $this->load->view('pipeline/flow_file',compact('row','kind','label','file','rules','editor','staff','actionUrl','pipeline_token')); ?>
<?php endforeach; ?>
<?php if ($editor): ?><form id="ajukan-ke-supervisi" action="<?= $actionUrl ?>" method="post"><?php $this->load->view('pipeline/billing_token'); ?>
<div class="form-group"><label for="supervisor-user">Supervisi Customer</label><select id="supervisor-user" name="SupervisorUser" class="form-control" required><option value="">Pilih Supervisi Customer</option>
<?php $savedSigners=json_decode($row['Signers'],true); $assigned=$savedSigners['BUNKER_CHAIN'][0]??$savedSigners['BUNKER_SUPERVISOR']??''; foreach($supervisors as $uid=>$name): ?>
<option value="<?= html_escape($uid) ?>" <?= $uid===$assigned?'selected':'' ?>><?= html_escape($name) ?></option><?php endforeach; ?>
</select></div>
<?php if (count($supervisors)>1): ?>
<details class="form-group"><summary>Tingkat persetujuan tambahan</summary>
<?php $chain=json_decode($row['Signers'],true)['BUNKER_CHAIN']??array();
for ($level=2;$level<=min(5,count($supervisors));$level++): ?>
<label for="approval-level-<?= $level ?>">Supervisi tingkat <?= $level ?></label>
<select id="approval-level-<?= $level ?>" name="SupervisorExtra[]" class="form-control"><option value="">Lewati tingkat ini</option>
<?php foreach ($supervisors as $uid=>$name): ?>
<option value="<?= html_escape($uid) ?>" <?= ($chain[$level-1]??null)===$uid?'selected':'' ?>><?= html_escape($name) ?></option>
<?php endforeach; ?></select>
<?php endfor; ?></details>
<?php endif; ?>
<?php if ($row['Status']==='REVISION'): ?><div class="row"><div class="col-sm-6 form-group"><label for="revised-date">Tanggal rencana</label><input id="revised-date" class="form-control" type="date" name="FillingDate" value="<?= html_escape($letter['FillingDate']) ?>" required></div><div class="col-sm-6 form-group"><label for="revised-volume">Volume rencana (liter)</label><input id="revised-volume" class="form-control" type="number" min="0.001" step="0.001" name="PlannedLiters" value="<?= html_escape($letter['PlannedLiters']) ?>" required></div></div><?php endif; ?>
<?php if (!$supervisors): ?><p>Developer perlu menetapkan akses Approval Permohonan Bunkering untuk akun Supervisi perusahaan.</p><?php endif; ?>
<button class="btn btn-primary" name="action" value="submit" <?= !$supervisors?'disabled':'' ?>><?= $row['Status']==='REVISION'?'Ajukan Ulang ke Supervisi Customer':'Ajukan ke Supervisi Customer' ?></button></form><?php endif; ?>
</div></div>
<?php if ($staff && in_array($row['Status'],array('SUBMITTED','WAIT_TRANSPORT','TRANSPORT_CONFIRMED'),true)): ?>
<div class="box box-warning"><div class="box-header"><h3 class="box-title">Review Admin Cabang</h3></div><div class="box-body">
<form action="<?= $actionUrl ?>" method="post"><?php $this->load->view('pipeline/billing_token'); ?>
<div class="form-group"><label for="flow-note">Catatan pemeriksaan dokumen</label><textarea id="flow-note" class="form-control" name="Note" maxlength="1000"></textarea></div>
<?php if ($row['Status']==='SUBMITTED'): ?><button class="btn btn-primary" name="action" value="check_documents">Dokumen Lengkap. Lanjut Verifikasi Surat Pengantar Pengiriman</button><?php endif; ?>
<button class="btn btn-warning" name="action" value="revise">Kembalikan untuk Perbaikan</button></form></div></div>
<?php endif; ?>
<?php if ($staff && $this->flow->awaiting_source($row)): ?>
<div class="box box-success"><div class="box-header"><h3 class="box-title">Verifikasi Surat Pengantar Pengiriman terhadap Pesanan</h3></div><div class="box-body">
<p id="verifikasi-sumber">Tanggal rencana pengisian <strong><?= html_escape($letter['FillingDate']) ?></strong>. Volume Surat Pengantar Pengiriman paling banyak <?= number_format($letter['PlannedLiters'],3,',','.') ?> L.</p>
<p>Unggah dokumen dari pihak Bunkering. Periksa nama kapal, perusahaan pembeli, tanggal, produk, volume dan nomor referensi sebelum menyimpan hasil bacaan. Untuk DO, isi nomor DO pada kolom Nomor LO jika nomor LO tidak tercantum.</p>
<p><a class="btn btn-default" href="<?= site_url('Pipeline_spp').'?area='.(int)$row['SpbbID'].'&request='.$id ?>">Unggah Surat Pengantar Pengiriman</a></p>
<?php if ($captures): ?>
<form action="<?= $actionUrl ?>" method="post"><?php $this->load->view('pipeline/billing_token'); ?>
<div class="form-group"><label for="flow-source-kind">Jenis dokumen</label><select id="flow-source-kind" name="SourceKind" class="form-control" required><option value="">Pilih jenis</option><option value="SPP" <?= ($sourceKind??null)==='SPP'?'selected':'' ?>>SPP</option><option value="DO" <?= ($sourceKind??null)==='DO'?'selected':'' ?>>DO</option></select></div>
<div class="form-group"><label for="flow-spp">Dokumen terkonfirmasi</label><select id="flow-spp" name="CaptureID" class="form-control" required><option value="">Pilih dokumen</option>
<?php foreach ($captures as $capture): ?><option data-identity="<?= html_escape($capture['VehicleOrVessel']) ?>" value="<?= (int)$capture['CaptureID'] ?>" <?= (int)($prefilled['CaptureID']??0)===(int)$capture['CaptureID']?'selected':'' ?>><?= html_escape(($capture['LONumber']?:$capture['ShipmentNo']).' / '.$capture['OriginalName'].' / '.$capture['DeliveryDate'].' / '.$capture['OrderedLiters'].' L') ?></option><?php endforeach; ?>
</select></div>
<?php if ($sourceMismatches): ?>
<div class="alert alert-warning"><strong>Surat Pengantar Pengiriman pilihan belum sesuai dengan permohonan</strong><ul>
<?php foreach ($sourceMismatches as $mismatch): ?><li><?= html_escape($mismatch) ?></li><?php endforeach; ?>
</ul><a href="<?= site_url('Pipeline_spp/review/'.(int)$prefilled['CaptureID']) ?>">Periksa hasil bacaan SPP</a> atau pilih SPP yang sesuai.</div>
<?php endif; ?>
<?php if(!empty($sourceConflicts)): ?>
<div class="well well-sm">
<strong>Lampiran SPP pada DO yang memakai referensi ini</strong>
<ul><?php foreach($sourceConflicts as $conflict): ?>
<li><a href="<?= site_url('Pipeline_billing/document/'.(int)$conflict['DocumentID']) ?>"><?= html_escape($conflict['DONumber'].' / '.$conflict['ReferenceNo']) ?></a><?= $conflict['Billed']?' · Sudah ditagihkan':'' ?></li>
<?php endforeach; ?></ul>
<div class="form-group"><label>Gunakan DO yang sudah ada</label><select name="LinkedDeliveryID" class="form-control"><option value="">Gunakan DO yang cocok otomatis, jika tersedia</option>
<?php $seenDeliveries=array(); foreach($sourceConflicts as $conflict): if(isset($seenDeliveries[$conflict['DeliveryID']]))continue;$seenDeliveries[$conflict['DeliveryID']]=true; ?>
<option value="<?= (int)$conflict['DeliveryID'] ?>"><?= html_escape($conflict['DONumber']) ?></option>
<?php endforeach; ?></select></div>
<?php if(!empty($resolveKey)): ?>
<label><input type="checkbox" name="ResolveLegacy" value="<?= $resolveKey ?>"> Lepas lampiran DO di atas saat memverifikasi SPP. Arsip SPP dan DO tetap tersedia.</label>
<?php endif; ?>
</div>
<?php endif; ?>
<?php if(!empty($vesselIdentity['RegistrationNo'])): ?>
<p>Nomor registrasi / IMO kapal dari data Customer <strong><?= html_escape($vesselIdentity['RegistrationNo']) ?></strong></p>
<?php endif; ?>
<label><input type="checkbox" name="ConfirmSource" value="1" required> Saya sudah memeriksa dokumen asli dan mencocokkan pembeli, tanggal, dan volume dengan permohonan ini.</label>
<div><button class="btn btn-success" name="action" value="verify_source">Verifikasi Surat Pengantar Pengiriman</button></div></form>
<?php endif; ?></div></div>
<?php endif; ?>
<?php if ($staff && $row['Status']==='TRANSPORT_CONFIRMED' && $verifiedCapture && !$blockers): ?>
<div class="box box-success" id="proses-pengisian"><div class="box-body">
<form action="<?= $actionUrl ?>" method="post"><?php $this->load->view('pipeline/billing_token'); ?>
<button class="btn btn-primary" name="action" value="start_filling">Mulai Pengisian</button></form></div></div>
<?php endif; ?>
<?php if ($staff && $row['Status']==='FILLING' && $verifiedCapture): ?>
<div class="box box-success" id="selesai-pengisian"><div class="box-header"><h3 class="box-title">Selesaikan Pengisian BBM</h3></div><div class="box-body">
<form method="post" action="<?= $actionUrl ?>"><?php $this->load->view('pipeline/billing_token'); ?>
<div class="row">
<div class="col-sm-4 form-group"><label for="finished-date">Tanggal selesai (WITA)</label><input id="finished-date" class="form-control" type="date" name="FillingDate" required></div>
<div class="col-sm-4 form-group"><label for="finished-time">Jam selesai (WITA)</label><input id="finished-time" class="form-control" type="time" name="FillingTime" required></div>
<div class="col-sm-4 form-group"><label for="actual-liters">Volume aktual (liter)</label><input id="actual-liters" class="form-control" type="number" name="VolumeLiters" min="0.001" max="<?= html_escape($verifiedCapture['OrderedLiters']) ?>" step="0.001" required></div>
</div>
<div class="form-group"><label for="filling-pier">Dermaga</label><input id="filling-pier" class="form-control" name="Pier" maxlength="100" required></div>
<div class="form-group"><label for="filling-note">Catatan pengisian</label><textarea id="filling-note" class="form-control" name="FillingNote" maxlength="1000"></textarea></div>
<button class="btn btn-success" name="action" value="finish_filling">Selesaikan Pengisian</button>
</form></div></div>

<?php endif; ?>
<?php if ($staff && $row['Status']==='FILLED' && $verifiedCapture && !$this->flow->filling_progress($row)): ?>
<div class="box box-success" id="catat-pengisian"><div class="box-header"><h3 class="box-title">Catat Pengisian Aktual dan Input Totalizer Akhir</h3></div><div class="box-body">
<form action="<?= $actionUrl ?>" method="post"><?php $this->load->view('pipeline/billing_token'); ?>
<div class="row">
<?php foreach (array('FillingDate'=>array('Tanggal Pengisian','date',substr((string)($verifiedCapture['DeliveryDate']??''),0,10)),'FillingTime'=>array('Jam Pengisian (WITA)','time',''),
    'Location'=>array('Lokasi','text',$letter['PortName']),'Pier'=>array('Dermaga','text',''),'VolumeLiters'=>array('Volume Aktual (liter)','number',(float)$row['TotalizerEnd']-(float)$row['TotalizerStart'])) as $name=>$field): ?>
<div class="col-sm-4 form-group"><label for="flow-<?= $name ?>"><?= $field[0] ?></label><input id="flow-<?= $name ?>" name="<?= $name ?>" type="<?= $field[1] ?>" class="form-control" value="<?= html_escape($field[2]) ?>" <?= $field[1]==='number'?'min="'.($name==='TotalizerEnd'?'0':'0.001').'" step="0.001"':'' ?> required></div>
<?php endforeach; ?></div>
<div class="row"><?php foreach (array('Before'=>'Sebelum','After'=>'Sesudah') as $prefix=>$title): ?><div class="col-sm-6"><h4>Sounding <?= $title ?></h4>
<?php foreach (array('Main1'=>'Tangki Induk I','Main2'=>'Tangki Induk II','DailyRight'=>'Tangki Harian Kanan','DailyLeft'=>'Tangki Harian Kiri') as $key=>$label): ?>
<div class="form-group"><label for="flow-<?= $prefix.$key ?>"><?= $label ?> (liter)</label><input id="flow-<?= $prefix.$key ?>" name="<?= $prefix.$key ?>" type="number" min="0" step="0.001" class="form-control" required></div>
<?php endforeach; ?></div><?php endforeach; ?></div>
<button class="btn btn-primary" name="action" value="make_ba">Buat BA dan Ajukan Approval</button></form>
</div></div>
<?php endif; ?>
<?php $progress=$this->flow->filling_progress($row); if ($progress): ?>
<div class="box box-primary" id="konfirmasi-kkm"><div class="box-header"><h3 class="box-title">Realisasi dan Konfirmasi KKM</h3></div><div class="box-body">
<dl class="dl-horizontal">
<?php foreach(array('OrderedLiters'=>'Total SPP (L)', 'VolumeLiters'=>'Volume aktual (L)', 'ReceivedLiters'=>'Volume diterima (L)', 'DifferenceLiters'=>'Selisih (L)', 'StartedName'=>'Dimulai oleh', 'StartedAt'=>'Waktu mulai (WITA)', 'FinishedName'=>'Diselesaikan oleh', 'FinishedAt'=>'Waktu pencatatan (WITA)', 'FillingDate'=>'Tanggal selesai (WITA)', 'FillingTime'=>'Jam selesai (WITA)', 'FillingNote'=>'Catatan pengisian', 'KkmNote'=>'Catatan KKM', 'ReviewNote'=>'Tindak lanjut Admin Cabang') as $field=>$label): if(!isset($progress[$field]))continue; ?>
<dt><?= html_escape($label) ?></dt><dd><?= html_escape($progress[$field]) ?></dd>
<?php endforeach; ?>
</dl>
<?php if ($this->flow->kkm_task($row)): ?>
<form method="post" action="<?= $actionUrl ?>"><?php $this->load->view('pipeline/billing_token'); ?>
<div class="form-group"><label for="received-liters">Volume diterima (liter)</label><input id="received-liters" class="form-control" type="number" name="ReceivedLiters" min="0.001" step="0.001" value="<?= html_escape($progress['ReceivedLiters']??$progress['VolumeLiters']) ?>" required></div>
<div class="form-group"><label for="kkm-note">Catatan KKM (wajib jika ada selisih)</label><textarea id="kkm-note" class="form-control" name="KkmNote" maxlength="1000"></textarea></div>
<button class="btn btn-success" name="action" value="confirm_filling">Konfirmasi Volume Diterima</button></form>
<?php elseif ($staff && $progress['BranchWorkflow']==='REVIEW'): ?>
<form method="post" action="<?= $actionUrl ?>"><?php $this->load->view('pipeline/billing_token'); ?>
<div class="form-group"><label for="review-note">Hasil pemeriksaan selisih</label><textarea id="review-note" class="form-control" name="ReviewNote" maxlength="1000" required></textarea></div>
<button class="btn btn-warning" name="action" value="resolve_difference">Ajukan Konfirmasi Final KKM</button></form>
<?php endif; ?>
</div></div>
<?php endif; ?>

<?php if ($row['Letter']): ?><div class="box box-primary"><div class="box-header"><h3 class="box-title">Persetujuan Dokumen</h3></div><div class="box-body">
<?php foreach ($slots as $slot=>$meta): if($meta[0]!=='Letter' && (!$row['BA'] || ($progress && $slot!=='BST_KKM')))continue; ?>
<div class="well well-sm"><strong><?= html_escape(($meta[0]==='Letter'?'Permohonan Bunker':$meta[0]).' · '.$meta[2]) ?></strong>
<?php if (isset($approvals[$slot]) && ($slot!=='BUNKER_SUPERVISOR' || get_instance()->flow->approved_bunker($row))): $proof=$approvals[$slot]; ?>
<p><?= html_escape($proof['Approver'].' · '.$proof['ApprovedAt']) ?> · <a href="<?= site_url('Pipeline_verify/index/'.$proof['Token']) ?>">Verifikasi QR</a></p>
<?php elseif (isset($pending[$slot])): ?>
<form action="<?= $actionUrl ?>" method="post"><?php $this->load->view('pipeline/billing_token'); ?>
<input type="hidden" name="Slot" value="<?= $slot ?>"><input type="hidden" name="DocumentHash" value="<?= hash('sha256',$row[$meta[0]]) ?>">
<p><a href="<?= site_url('Pipeline_flow/document/'.$id.'/'.($meta[0]==='Letter'?'Bunker':$meta[0])) ?>">Baca dokumen sebelum menyetujui</a></p>
<label><input type="checkbox" name="Confirm" value="1" required> Saya sudah memeriksa dan menyetujui isi dokumen ini.</label>
<?php if ($slot==='BUNKER_SUPERVISOR'): ?><div class="form-group"><label for="approve-note">Catatan persetujuan</label><textarea id="approve-note" class="form-control" name="SupervisorNote" maxlength="1000"></textarea></div><?php endif; ?>
<button class="btn btn-success" name="action" value="approve">Setujui sebagai <?= html_escape($meta[2]) ?></button></form>
<?php if ($slot==='BUNKER_SUPERVISOR'): ?>
<form action="<?= $actionUrl ?>" method="post"><?php $this->load->view('pipeline/billing_token'); ?>
<input type="hidden" name="DocumentHash" value="<?= hash('sha256',$row['Letter']) ?>">
<div class="form-group"><label for="supervisor-reason">Alasan penolakan atau revisi</label><textarea id="supervisor-reason" class="form-control" name="SupervisorNote" maxlength="1000" required></textarea></div>
<button class="btn btn-warning" name="action" value="revise_supervision">Minta Revisi</button>
<button class="btn btn-danger" name="action" value="reject_supervision">Tolak Permohonan</button></form>
<?php endif; ?>
<?php else: ?><p>Menunggu penandatangan yang ditugaskan.</p><?php endif; ?></div>
<?php endforeach; ?></div></div><?php endif; ?>
</section></div>

<script src="<?= base_url('assets/js/pipeline-flow-files.js').'?v='.filemtime(FCPATH.'assets/js/pipeline-flow-files.js') ?>"></script>
