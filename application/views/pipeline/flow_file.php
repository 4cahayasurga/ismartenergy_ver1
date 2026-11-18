
<?php $id=(int)$row['RequestID']; $check=$file?json_decode($file['VerificationJson']??'null',true):null; ?>
<div class="well well-sm" data-flow-file-kind="<?= html_escape($kind) ?>"><strong><?= html_escape($label) ?></strong> · <?= $rules[$kind]?'Wajib':'Opsional' ?>
<?php $manual=$check['manual']??array(); ?>
<p><?php if ($file): ?><span class="label label-info">Terunggah</span> <a href="<?= site_url('Pipeline_flow/file/'.$id.'/'.$kind) ?>"><?= html_escape($file['OriginalName']) ?></a>
 · <strong><?= ($manual['status']??'')==='REJECTED'?'Ditolak':($file['Reviewed']?'Terverifikasi manual':'Menunggu verifikasi manual') ?></strong>
<?php if (!empty($file['ReviewedBy'])): ?> · <?= html_escape($manual['name']??$file['ReviewedBy']) ?> · <?= html_escape($file['ReviewedAt']??'') ?><?php endif; ?>
<?php else: ?><span class="label label-default">Belum diunggah</span><?php endif; ?></p>
<?php if (!empty($manual['note'])): ?><p>Catatan pemeriksaan: <?= html_escape($manual['note']) ?></p><?php endif; ?>
<?php if ($file && $kind==='Notice'): ?><p class="text-muted">OCR tidak diperlukan.</p><?php endif; ?>
<?php if($kind==='Payment' && $file): $matched=($check['status']??'')==='MATCH'; ?>
<div class="alert" style="<?= $matched?'background-color:#eaf6ee;border-color:#a7d6b5;color:#183b27':'background-color:#fff4df;border-color:#e6bd72;color:#49330e' ?>"><strong>OCR Rekening: <?= html_escape(array('MATCH'=>'Cocok','PARTIAL_MATCH'=>'Cocok sebagian. Dapat dikirim ke cabang','MISMATCH'=>'Tidak cocok','REVIEW'=>'Perlu diperiksa')[$check['status']??'REVIEW']) ?></strong><p><?= html_escape(str_ireplace('Petugas Cabang','Petugas Bunkering / Pipanisasi',$check['message']??'Bukti lama belum diperiksa OCR; unggah ulang untuk pemeriksaan rekening.')) ?></p>
<?php if(!empty($check['extracted'])): $details=$check['extracted']; ?><div class="table-responsive"><table class="table table-condensed" style="background-color:#fff;color:#1f2937"><tbody><?php foreach(array('format'=>'Format bank','sender_name'=>'Nama pengirim terbaca','sender_bank'=>'Bank pengirim terbaca','sender_account'=>'Digit rekening pengirim terbaca','account_visibility'=>'Cakupan nomor (FULL/lengkap, MASKED/samar, PREFIX/awal, SUFFIX/akhir)','amount'=>'Nominal transfer (Rp)','reference'=>'Referensi transaksi','transaction_time'=>'Waktu transaksi terbaca') as $key=>$title): ?><tr><th><?= html_escape($title) ?></th><td><?= html_escape($details[$key]??'') ?: 'Belum terbaca / tidak tercantum' ?></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?>
<?php if($check): ?><small>Dicocokkan dengan <?= html_escape(($check['expected']['BankName']??'').' / '.($check['expected']['BankAccountName']??'').' / '.($check['expected']['BankAccountNumber']??'')) ?>. Pemeriksaan <?= html_escape($check['checked_at']??'') ?>.</small><details><summary>Teks yang terbaca</summary><pre style="white-space:pre-wrap;overflow-wrap:anywhere;background-color:#fff;color:#1f2937"><?= html_escape($check['text']??'') ?></pre></details><?php endif; ?><p>Hasil OCR membantu pencocokan identitas; Petugas Bunkering / Pipanisasi tetap memverifikasi bukti bayar.</p></div>
<?php endif; ?>
<?php if(in_array($kind,array('Supervision','Signed'),true) && $file): ?><div class="alert alert-<?= ($check['status']??'')==='MATCH'?'success':'warning' ?>"><strong>Verifikasi <?= $kind==='Signed'?'QR / File Approval':'OCR' ?>: <?= ($check['status']??'')==='MATCH'?'Lulus':'Perlu diperiksa' ?></strong><p><?= html_escape($check['message']??'Belum diperiksa; unggah ulang dokumen.') ?></p></div><?php endif; ?>
<?php if($kind==='Signed'): ?><p>PDF ber-QR dari approval sah dilampirkan otomatis saat halaman ini dibuka. Unduh/unggah ulang tidak wajib. Jika mengganti file, gunakan PDF asli dari tombol <strong>Unduh Permohonan Disetujui (QR)</strong>. Sistem memeriksa file dan approval terhadap nomor pengajuan ini. Foto/scan atau PDF yang diubah belum dapat diluluskan otomatis.</p><?php endif; ?>
<?php if ($editor): ?>
<form data-flow-upload action="<?= $actionUrl ?>" method="post" enctype="multipart/form-data"><?php $this->load->view('pipeline/billing_token'); ?>
<input type="hidden" name="Kind" value="<?= $kind ?>"><input type="file" name="Document" accept=".pdf,.jpg,.jpeg,.png" required>
<p class="text-muted">Pilih file untuk menyimpan dokumen ini. Jika unggah otomatis belum berjalan, klik <?= $file?'Ganti File':'Unggah File' ?>.</p><button class="btn btn-default" name="action" value="upload"><?= $file?'Ganti File':'Unggah File' ?></button></form>
<?php if($file && in_array($kind,array('Supervision','Payment'),true)): ?><form data-flow-recheck action="<?= $actionUrl ?>" method="post"><?php $this->load->view('pipeline/billing_token'); ?><input type="hidden" name="Kind" value="<?= $kind ?>"><button class="btn btn-info" name="action" value="recheck">Baca Ulang OCR</button></form><?php endif; ?>
<?php elseif ($staff && get_instance()->flow->can_review_file($row,$file)): ?>
<form action="<?= $actionUrl ?>" method="post"><?php $this->load->view('pipeline/billing_token'); ?>
<input type="hidden" name="Kind" value="<?= html_escape($kind) ?>">
<input type="hidden" name="FileHash" value="<?= html_escape($file['FileHash']) ?>">
<input type="hidden" name="FileVersion" value="<?= hash('sha256',$file['StoredName']) ?>">
<div class="form-group"><label for="review-<?= html_escape($kind) ?>">Catatan pemeriksaan (wajib untuk penolakan)</label>
<textarea class="form-control" id="review-<?= html_escape($kind) ?>" name="ReviewNote" maxlength="1000" rows="2"></textarea></div>
<button class="btn btn-success" name="action" value="review">Verifikasi</button>
<button class="btn btn-warning" name="action" value="reject_file">Tolak / Minta Perbaikan</button></form>
<?php endif; ?><div data-flow-file-status role="status" aria-live="polite"></div></div>
