<?php $linkedRequest=$this->flow->delivery_request($delivery['DeliveryID']); $requestOptions=$this->flow->source_requests(); ?>
<?php if($linkedRequest): ?>
<div class="box box-info"><div class="box-body"><p>Permohonan <a href="<?= site_url('Pipeline_flow/detail/'.(int)$linkedRequest['RequestID']) ?>"><?= html_escape($linkedRequest['RequestNo']) ?></a> · <?= html_escape($this->flow->statuses()[$linkedRequest['Status']]) ?></p>
<?php if($linkedRequest['Status']==='FILLED'): ?><a class="btn btn-primary" href="<?= site_url('Pipeline_flow/detail/'.(int)$linkedRequest['RequestID'].'#catat-pengisian') ?>">Buat BA Pengisian</a><?php endif; ?></div></div>
<?php elseif($requestOptions && $this->flow->staff()): ?>
<div class="box box-info"><div class="box-header"><h3 class="box-title">Hubungkan DO ke Pesanan Customer</h3></div><div class="box-body">
<form method="post" action="<?= site_url('Pipeline_flow/link_delivery/'.(int)$delivery['DeliveryID']) ?>">
<?php $this->load->view('pipeline/billing_token'); ?>
<div class="form-group"><label>Permohonan Bunkering</label><select class="form-control" name="RequestID" required><option value="">Pilih permohonan</option>
<?php foreach($requestOptions as $request): if((int)$request['SpbbID']!==(int)$delivery['SpbbID'])continue; ?>
<option value="<?= (int)$request['RequestID'] ?>"><?= html_escape($request['RequestNo']) ?></option>
<?php endforeach; ?></select></div>
<label><input type="checkbox" name="ConfirmSource" value="1" required> Saya sudah memeriksa SPP dan mencocokkannya dengan pesanan.</label>
<div><button class="btn btn-primary">Hubungkan dan Mulai Pengisian</button></div>
</form></div></div><?php endif; ?>
<div class="box box-primary"><div class="box-header"><h3 class="box-title">Totalizer &amp; Cetak DO</h3></div><div class="box-body">
<?php if(isset($delivery['TotalizerStart'],$delivery['TotalizerEnd']) && (float)$delivery['TotalizerEnd']-(float)$delivery['TotalizerStart']>(float)$delivery['Quantity']): ?><div class="alert alert-warning">Periksa totalizer. Selisih <?= number_format($delivery['TotalizerEnd']-$delivery['TotalizerStart'],3,',','.') ?> liter melebihi volume DO <?= number_format($delivery['Quantity'],3,',','.') ?> liter. Koreksi angka sebelum menyelesaikan pengisian.</div><?php endif; ?>


<?php if(!$locked && (!$linkedRequest||$linkedRequest['Status']==='FILLING') && $this->pipeline_access->allowed('delivery')): ?>

<form method="post" action="<?= site_url('Pipeline_billing/documents/'.$delivery['DeliveryID']) ?>"><?php $this->load->view('pipeline/billing_token'); ?><input type="hidden" name="action" value="totalizer"><div class="row">

<?php foreach(array('TotalizerStart'=>'TOTALIZER Awal (liter)','TotalizerEnd'=>'TOTALIZER Akhir (liter)') as $key=>$label): ?><div class="col-sm-6 form-group"><label><?= $label ?></label><input type="number" name="<?= $key ?>" min="0" max="999999999999.999" step="0.001" required class="form-control" value="<?= set_value($key,isset($delivery[$key])?$delivery[$key]:'') ?>"></div><?php endforeach; ?></div><button class="btn btn-primary">Simpan Totalizer dan Selesaikan Pengisian</button></form><hr>

<?php else: ?><p>Awal: <?= html_escape(isset($delivery['TotalizerStart'])?$delivery['TotalizerStart']:'Belum diisi') ?> | Akhir: <?= html_escape(isset($delivery['TotalizerEnd'])?$delivery['TotalizerEnd']:'Belum diisi') ?></p><?php endif; ?>

<?php if($this->pipeline_finance->printable_do($delivery)): ?><a class="btn btn-success" target="_blank" rel="noopener" href="<?= site_url('Pipeline_billing/print_do/'.$delivery['DeliveryID']) ?>">Print DO Sesuai Input</a><p>Volume terukur: <?= number_format($delivery['TotalizerEnd']-$delivery['TotalizerStart'],3,',','.') ?> liter. Volume DO: <?= number_format($delivery['Quantity'],3,',','.') ?> liter.</p><?php else: ?><button class="btn btn-default" disabled>Print DO</button><p>Isi totalizer awal dan akhir terlebih dahulu. Totalizer akhir harus lebih besar dari awal.</p><?php endif; ?>

</div></div>

<div class="box box-primary"><div class="box-body"><p><strong><?= html_escape($delivery['VesselName']) ?></strong> &middot; <?= html_escape($delivery['DeliveryDate']) ?> &middot; <?= number_format($delivery['Quantity'],3,',','.') ?> liter</p>

<div class="alert alert-<?= $errors?'warning':'success' ?>"><?= $errors?html_escape(implode(' • ',$errors)):'SPP sudah diperiksa. Dokumen Surat Pengantar Pengiriman tambahan dapat dilampirkan bila tersedia.' ?></div>

<p>Data diambil dari SPP terikat saat memilih jenis surat. Pilih file untuk membaca OCR dan mengisi textbox otomatis, lalu koreksi hasilnya sebelum upload. File hasil scan harus diperiksa melalui <strong>View / Periksa</strong>; upload saja belum dianggap tervalidasi. Untuk file yang berisi beberapa surat, jelaskan halaman/bagian yang digunakan pada catatan.</p>

<div class="table-responsive"><table class="table table-bordered"><thead><tr><th>Jenis / Referensi</th><th>File</th><th>Volume L</th><th>Status</th><th>Diunggah</th><th>Aksi</th></tr></thead><tbody>

<?php foreach($documents as $doc): ?><tr><td><?= html_escape($doc['DocumentType'].' / '.$doc['ReferenceNo']) ?></td><td><a target="_blank" rel="noopener" href="<?= site_url('Pipeline_billing/file/'.$doc['DocumentID']) ?>"><?= html_escape($doc['OriginalName']) ?></a></td><td><?= number_format($doc['VolumeLiters'],3,',','.') ?></td><td><?= $doc['Reviewed']?'Diperiksa':'Belum diperiksa' ?></td><td><?= html_escape($doc['CreatedAt'].' / '.$doc['CreatedBy']) ?></td><td><a href="<?= site_url('Pipeline_billing/document/'.$doc['DocumentID']) ?>">View / Periksa</a> &middot; <a href="<?= site_url('Pipeline_billing/file/'.$doc['DocumentID'].'?download=1') ?>">Download</a> &middot; <a target="_blank" rel="noopener" href="<?= site_url('Pipeline_billing/file/'.$doc['DocumentID']) ?>">Buka File</a></td></tr><?php endforeach; ?>

<?php if(!$documents): ?><tr><td colspan="6">Belum ada dokumen.</td></tr><?php endif; ?></tbody></table></div></div></div>

<?php if(!$locked && $this->pipeline_access->allowed('delivery')): ?>

<div class="box box-success"><div class="box-header"><h3 class="box-title">Dokumen Surat Pengantar Pengiriman dan Lampiran SPP</h3></div><div class="box-body">

<?php if($this->pipeline_access->allowed('spp_ocr')): ?><p><a class="btn btn-info" href="<?= site_url('Pipeline_spp').'?'.html_escape(http_build_query(array('branch'=>$delivery['SpbbID'],'do'=>$delivery['DeliveryID']))) ?>"><i class="fa fa-camera"></i> Upload / Foto Surat Pengantar Pengiriman — Baca Otomatis</a></p><?php endif; ?>

<?php if ($this->pipeline_access->allowed('spp_ocr')):
 $confirmed=get_instance()->db->select('CaptureID,LONumber,OriginalName')->where('SpbbID',(int)$delivery['SpbbID'])
    ->where('Status','CONFIRMED')->where('ProposedRequestID',null)->where('DeliveryID',null)->where('DocumentID',null)
    ->order_by('CaptureID','DESC')->limit(100)->get('PipelineSppCapture')->result_array();
 if ($confirmed): ?>
<form method="post" action="<?= site_url('Pipeline_billing/documents/'.$delivery['DeliveryID']) ?>">
<?php $this->load->view('pipeline/billing_token'); ?>
<div class="form-group"><label for="do-spp-archive">Surat Pengantar Pengiriman dari arsip cabang</label><select id="do-spp-archive" name="CaptureID" class="form-control" required><option value="">Pilih arsip Surat Pengantar Pengiriman</option>
<?php foreach ($confirmed as $item): ?><option value="<?= (int)$item['CaptureID'] ?>"><?= html_escape($item['LONumber'].' / '.$item['OriginalName']) ?></option><?php endforeach; ?>
</select></div><button class="btn btn-default" name="action" value="attach_spp">Lampirkan Surat Pengantar Pengiriman Tersimpan</button>
</form><hr>
<?php endif; endif; ?>
<form class="pipeline-document-upload" data-ocr-url="<?= site_url('Pipeline_billing/document_ocr/'.$delivery['DeliveryID']) ?>" method="post" action="<?= site_url('Pipeline_billing/documents/'.$delivery['DeliveryID']) ?>" enctype="multipart/form-data"><?php $this->load->view('pipeline/billing_token'); ?><div class="row">

<div class="col-sm-4 form-group"><label>Jenis dokumen</label><select name="DocumentType" class="form-control" required><?php foreach(array('DO'=>'Delivery Order','LO'=>'Loading Order / Loading Letter') as $key=>$label): ?><option value="<?= $key ?>" <?= set_select('DocumentType',$key) ?>><?= $label ?></option><?php endforeach; ?></select></div>

<div class="col-sm-8 form-group"><label>File PDF / JPG / PNG (maks. 15 MB)</label><input type="file" name="DocumentFile" accept="application/pdf,image/jpeg,image/png" required class="form-control"></div>

<?php foreach(array('ReferenceNo'=>array('Nomor dokumen / referensi unik',100,$delivery['DONumber']),'LoadingReference'=>array('Nomor LO pada SPP (wajib untuk SPP)',100,''),'VesselName'=>array('Nama kapal penerima sesuai dokumen',200,$delivery['VesselName']),'Volume'=>array('Volume tertulis (liter)',30,$delivery['Quantity']),'Density'=>array('Densitas kg/liter, bila tercantum (opsional)',20,'')) as $key=>$field): ?><div class="col-sm-6 form-group"><label><?= $field[0] ?></label><input class="form-control" name="<?= $key ?>" maxlength="<?= $field[1] ?>" value="<?= set_value($key,$field[2]) ?>" <?= in_array($key,array('LoadingReference','Density'))?'':'required' ?>></div><?php endforeach; ?>

<div class="col-sm-6 form-group"><label>Satuan volume dokumen</label><select class="form-control" name="VolumeUnit"><option value="L">Liter</option><option value="KL" <?= set_select('VolumeUnit','KL') ?>>Kiloliter (1 KL = 1.000 L)</option></select></div>

<div class="col-sm-6 form-group"><label>Tanggal dokumen</label><input type="date" class="form-control" name="DocumentDate" required value="<?= set_value('DocumentDate',substr($delivery['DeliveryDate'],0,10)) ?>"></div>

</div><div class="form-group"><label>Catatan / halaman / bagian dokumen yang digunakan</label><textarea class="form-control" name="ReviewNote" maxlength="1000"><?= set_value('ReviewNote') ?></textarea></div>

<div class="document-ocr-status alert alert-info" role="status" style="margin-top:12px">Pilih jenis surat; data dasar akan diisi dari SPP terikat / DO. Pilih file untuk Auto Fill OCR.</div><button type="button" class="btn btn-default document-read">Baca Ulang File / Auto Fill</button> <button class="btn btn-primary">Upload Dokumen</button></form></div></div>

<?php elseif($locked): ?><div class="alert alert-info">DO sudah ditagihkan. Dokumen dikunci agar bukti invoice tidak berubah.</div><?php endif; ?>


<?php if(!$locked && $this->pipeline_access->allowed('delivery')): ?>
<script>(function(){var scripts=document.querySelectorAll('form.pipeline-document-upload');var form=scripts[scripts.length-1];if(!form||form.dataset.autoFillReady)return;form.dataset.autoFillReady='1';var delivery=<?= json_encode(array_intersect_key($delivery,array_flip(array('DONumber','VesselName','Quantity','DeliveryDate'))),JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) ?>,spp=null,docs=<?= json_encode(array_values(array_filter($documents,function($d){return $d['DocumentType']==='SPP';})),JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) ?>;docs.sort(function(a,b){return Number(b.DocumentID)-Number(a.DocumentID);});if(docs.length)spp=docs[0];var status=form.querySelector('.document-ocr-status'),file=form.elements.DocumentFile,read=form.querySelector('.document-read'),sequence=0,busy=false;
function set(fields){Object.keys(fields).forEach(function(k){var input=form.elements[k];if(input){input.value=fields[k]==null?'':fields[k];if(input.tagName==='SELECT'&&window.jQuery)window.jQuery(input).trigger('change.select2');}});}
function source(){var t=form.elements.DocumentType.value;set({ReferenceNo:t==='DO'?delivery.DONumber:(t==='LO'?(spp?spp.LoadingReference:''):(spp?spp.ReferenceNo:'')),LoadingReference:spp?spp.LoadingReference:'',VesselName:delivery.VesselName,Volume:Number(spp?spp.VolumeLiters:delivery.Quantity),VolumeUnit:'L',Density:spp&&spp.Density?Number(spp.Density):'',DocumentDate:String(spp?spp.DocumentDate:delivery.DeliveryDate).slice(0,10)});status.textContent=spp?'Data diisi dari SPP yang sudah terikat ke DO. Untuk SPP yang sama, cukup gunakan View / Periksa pada daftar di atas; tidak perlu upload ulang.':'Belum ada SPP terikat. Data dasar diisi dari DO; nomor Surat Pengantar Pengiriman menunggu hasil OCR atau input manual.';}
async function recognize(){if(!file.files.length){status.textContent='Pilih file dahulu.';return;}var serial=++sequence,data=new FormData();data.append('pipeline_token',form.elements.pipeline_token.value);data.append('DocumentType',form.elements.DocumentType.value);data.append('DocumentFile',file.files[0]);busy=true;read.disabled=true;status.textContent='Sedang membaca file dan mengisi textbox…';try{var response=await fetch(form.dataset.ocrUrl,{method:'POST',credentials:'same-origin',body:data});if(response.status===403||response.status===404)throw new Error('Akses ditolak atau form kedaluwarsa; muat ulang halaman.');var result=await response.json();if(serial!==sequence)return;if(!result.ok)throw new Error(result.message||'OCR belum berhasil.');form.elements.DocumentType.value=result.type;if(window.jQuery)window.jQuery(form.elements.DocumentType).trigger('change.select2');set(result.fields);status.textContent='Auto Fill selesai. '+result.warnings.join(' ');}catch(error){if(serial===sequence)status.textContent='OCR belum berhasil: '+error.message+' File masih dipilih; periksa dan lengkapi textbox secara manual.';}finally{if(serial===sequence){busy=false;read.disabled=false;}}}
form.elements.DocumentType.addEventListener('change',function(){sequence++;busy=false;read.disabled=false;source();if(file.files.length)recognize();});file.addEventListener('change',function(){sequence++;busy=false;read.disabled=false;if(file.files.length)recognize();else source();});read.addEventListener('click',recognize);form.addEventListener('submit',function(e){if(busy){e.preventDefault();status.textContent='Tunggu pembacaan OCR selesai sebelum upload.';}});source();})();</script>
<?php endif; ?>
