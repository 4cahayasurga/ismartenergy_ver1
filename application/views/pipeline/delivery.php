<div class="content-wrapper">

    <section class="content-header">

        <h1>Input Surat Pengantar Pengiriman</h1>

        <?php if($this->pipeline_access->allowed('spp_ocr')): ?><p style="margin-top:12px"><a class="btn btn-primary" href="<?= site_url('Pipeline_spp') ?>"><i class="fa fa-camera"></i> Upload / Foto Surat Pengantar Pengiriman — Baca Otomatis</a></p><?php endif; ?>

    </section>

    <section class="content">

        <?php foreach (array('Berhasil' => 'success', 'Gagal' => 'danger') as $key => $style): if ($this->session->flashdata($key)): ?>

            <div class="alert alert-<?= $style ?>"><?= html_escape($this->session->flashdata($key)) ?></div>

        <?php endif; endforeach; ?>

        <?= validation_errors('<div class="alert alert-danger">', '</div>') ?>
        <?php if(!empty($deliveryError)): ?><div class="alert alert-danger"><?= html_escape($deliveryError) ?></div><?php endif; ?>
        <?php if(!empty($do_conflicts)): ?>
        <div class="box box-warning"><div class="box-header"><h3 class="box-title">DO dan lampiran yang sudah tersimpan</h3></div>
        <div class="box-body table-responsive"><table class="table table-bordered"><thead><tr><th>Nomor DO</th><th>Tanggal</th><th>Kapal</th><th>File Surat Pengantar Pengiriman</th><th>Aksi</th></tr></thead><tbody>
        <?php foreach($do_conflicts as $conflict): ?><tr>
        <td><?= html_escape($conflict['DONumber']) ?></td><td><?= html_escape($conflict['DeliveryDate']) ?></td><td><?= html_escape($conflict['VesselName']) ?></td>
        <td><?php if($conflict['DocumentID']): ?><a href="<?= site_url('Pipeline_billing/document/'.(int)$conflict['DocumentID']) ?>"><?= html_escape($conflict['OriginalName'].' / '.$conflict['ReferenceNo']) ?></a><?php else: ?>Belum ada lampiran SPP<?php endif; ?></td>
        <td><a class="btn btn-default btn-sm" href="<?= site_url('Pipeline_billing/documents/'.(int)$conflict['DeliveryID']) ?>">Buka DO &amp; Lampiran</a></td>
        </tr><?php endforeach; ?></tbody></table>
        <p>Periksa dokumen ini sebelum membuat DO baru. Nomor DO dan SPP yang sudah dipakai akan ditolak saat disimpan.</p>
        </div></div><?php endif; ?>


        <div class="box box-primary">

            <div class="box-body">

                <div class="alert alert-info">Simpan data DO, lalu upload Surat Pengantar Pengiriman dan isi totalizer akhir langsung di tabel DO Tersimpan. Pesanan bersifat opsional. Print DO aktif setelah totalizer akhir diisi.</div>

                <?= form_open('Pipanisasi/delivery',array('enctype'=>'multipart/form-data')) ?>

                <?php $this->load->view('pipeline/billing_token'); ?><div class="form-group"><label>SPP Sumber — khusus Pipanisasi (opsional)</label><select name="SourceCaptureID" id="do-source-spp" class="form-control"><option value="">Input DO manual / tanpa SPP sumber</option><?php foreach($spp_sources??array() as $source): ?><option value="<?= (int)$source['CaptureID'] ?>" <?= (int)($selected_spp??0)===(int)$source['CaptureID']?'selected':'' ?>><?= html_escape('#'.$source['CaptureID'].' / LO '.$source['LONumber'].' / '.$source['Buyer'].' / Area '.$source['SpbbID']) ?></option><?php endforeach; ?></select><small>SPP harus sudah dikonfirmasi. SPP terpilih diikat saat DO disimpan. Kapal penerima, perusahaan transportir/penagihan, jam pengisian aktual dan totalizer tetap diperiksa operator.</small></div><div class="form-group"><label>Upload file DO (opsional, terikat SPP terpilih)</label><input type="file" class="form-control" name="DOFile" accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf"><small>JPG / PNG / PDF, maks. 15 MB. Jika tanpa file, DO tetap bisa dibuat dari data SPP dan dicetak setelah totalizer lengkap.</small></div>

                <div class="form-group"><label>Cabang</label><select name="SpbbID" class="form-control" required><?php foreach($branches as $branch): ?><option value="<?= (int)$branch['SpbbID'] ?>" <?= set_select('SpbbID',$branch['SpbbID']) ?>><?= html_escape($branch['SpbbName']) ?></option><?php endforeach; ?></select></div>



                <div class="form-group">

                    <label>Pesanan Pipanisasi (opsional)</label>

                    <select name="OrderID" class="form-control">

                        <option value="">Tanpa pesanan</option>



                        <?php foreach ($orders as $order): if ($order['OrderStatus'] === 'DITOLAK') continue; ?>

                        <option value="<?= (int) $order['OrderID'] ?>" <?= set_select('OrderID', $order['OrderID']) ?>>

                            <?= html_escape($order['InvoiceNo'] . ' - ' . $order['Quantity'] . ' liter') ?>

                        </option>

                        <?php endforeach; ?>

                    </select>

                </div>



                <div class="row">



                    <?php foreach (array

                        ('DONumber' => array('Nomor DO', 'text', 100), 

                        'DeliveryDate' => array('Tanggal DO', 'date', 10), 

                        'Quantity' => array('Volume DO (liter)', 'number', 18),

                        'TotalizerStart' => array('TOTALIZER Awal (liter)', 'number', 18), 

                        'BillingCompany' => array('Nama perusahaan yang ditagih', 'text', 200),

                        'CompanyName' => array('Nama perusahaan transportir', 'text', 200), 

                        'DriverName' => array('Nama sopir', 'text', 150), 

                        'VehicleNumber' => array('Nomor polisi', 'text', 30), 

                        'VesselName' => array('Pengisian BBM ke KMP / kapal', 'text', 200), 

                        'FillingTime' => array('Jam pengisian', 'time', 5), 

                        'ReceiptNumber' => array('Nomor resi', 'text', 100)) as $field => $config): ?>



                        <div class="col-sm-6 form-group">

                            <label for="do-<?= $field ?>"><?= $config[0] ?></label>

                            <input <?= $field==='VesselName'?'list="master-vessels"':(in_array($field,array('BillingCompany','CompanyName'))?'list="master-companies"':'') ?> id="do-<?= $field ?>" name="<?= $field ?>" type="<?= $config[1] ?>" maxlength="<?= $config[2] ?>" class="form-control" value="<?= set_value($field, $field === 'DeliveryDate' ? date('Y-m-d') : '') ?>" <?= in_array($field, array('FillingTime', 'ReceiptNumber')) ? '' : 'required' ?> <?= in_array($field,array('Quantity','TotalizerStart')) ? 'min="0" max="999999999999.999" step="0.001"' : '' ?>></div>

                    <?php endforeach; ?>

                </div>





                <div class="form-group">

                    <label>Catatan</label>

                    <textarea class="form-control" name="Notes" maxlength="1000">

                        <?= set_value('Notes') ?>

                    </textarea>

                </div>



                <button class="btn btn-primary" type="submit">Simpan DO</button>



                <?php if ($this->pipeline_access->allowed('dashboard')): ?><a class="btn btn-default" href="<?= site_url('Pipanisasi') ?>">Dashboard</a><?php endif; ?>



                    <?= form_close() ?>

                </div>

            </div>

        <div id="saved-do" class="box box-primary"><div class="box-header"><h3 class="box-title">DO Tersimpan</h3></div><div class="box-body">

        <form method="get"><?php $this->load->view('pipeline/period'); ?></form>

        <div class="table-responsive"><table class="table table-bordered table-striped"><thead><tr><th>Tanggal / Nomor DO</th><th>Perusahaan / Kapal</th><th>Volume (L)</th><th>Totalizer Awal / Akhir</th><th>Status Invoice / Pembayaran</th><th>Dokumen / Aksi</th></tr></thead><tbody>

        <?php foreach($deliveries as $row): $linkedProgress=$this->flow->delivery_request($row['DeliveryID']);$docs=$billing->documents($row['DeliveryID']); $selected=(int)$this->input->get('do')===(int)$row['DeliveryID']; ?>

        <tr id="do-<?= (int)$row['DeliveryID'] ?>"><td><?= html_escape($row['DeliveryDate']) ?><br><strong><?= html_escape($row['DONumber']) ?></strong></td><td><?= html_escape($row['BillingCompany']) ?><br><?= html_escape($row['VesselName']) ?></td><td><?= number_format($row['Quantity'],3,',','.') ?></td><td><?= isset($row['TotalizerStart'])?number_format($row['TotalizerStart'],3,',','.'):'Belum diisi' ?><br><?= isset($row['TotalizerEnd'])?number_format($row['TotalizerEnd'],3,',','.'):'Belum diisi' ?></td><td>

        <?php if($linkedProgress): ?><p><a href="<?= site_url('Pipeline_flow/detail/'.(int)$linkedProgress['RequestID']) ?>"><?= html_escape($this->flow->statuses()[$linkedProgress['Status']]) ?></a></p><?php elseif($this->pipeline_finance->printable_do($row)): ?><p>Pengisian selesai</p><?php else: ?><p>Pengisian belum selesai</p><?php endif; ?>
        <?php if($row['InvoiceID']): ?><?= html_escape($row['InvoiceNumber']) ?><br><span class="label label-<?= $row['GrandTotal']>$row['Paid']?'warning':'success' ?>"><?= $row['GrandTotal']>$row['Paid']?'Outstanding':'Lunas' ?></span><br>Sisa invoice: Rp <?= number_format(max(0,$row['GrandTotal']-$row['Paid']),2,',','.') ?><?php else: ?><span class="label label-info">Belum Create Invoice</span><?php endif; ?></td><td>

        <a class="btn btn-primary btn-sm" href="<?= site_url('Pipanisasi/delivery').'?'.html_escape(http_build_query(array('start'=>$start,'end'=>$end,'do'=>$row['DeliveryID']))) ?>#do-<?= (int)$row['DeliveryID'] ?>">Upload Surat Pengantar Pengiriman</a>

        <?php if($this->pipeline_access->allowed('spp_ocr') && !$row['InvoiceID']): ?><a class="btn btn-info btn-sm" href="<?= site_url('Pipeline_spp').'?'.html_escape(http_build_query(array('branch'=>$row['SpbbID'],'do'=>$row['DeliveryID']))) ?>">Foto SPP / OCR</a><?php endif; ?>

        <?php if($this->pipeline_finance->printable_do($row)): ?><a target="_blank" rel="noopener" class="btn btn-success btn-sm" href="<?= site_url('Pipeline_billing/print_do/'.$row['DeliveryID']) ?>">Print DO</a><?php else: ?><button class="btn btn-default btn-sm" disabled title="Isi totalizer akhir terlebih dahulu">Print DO</button><?php endif; ?>

        <?php foreach($docs as $doc): ?><div><a target="_blank" rel="noopener" href="<?= site_url('Pipeline_billing/document/'.$doc['DocumentID']) ?>"><?= html_escape($doc['DocumentType'].' - '.$doc['OriginalName']) ?></a></div><?php endforeach; ?></td></tr>

        <?php if($selected): ?><tr><td colspan="6"><?php $this->load->view('pipeline/documents',array('delivery'=>$row,'documents'=>$docs,'locked'=>(bool)$row['InvoiceID'],'errors'=>$this->pipeline_finance->reconcile($row,$billing->current_documents($row['DeliveryID']),array('SPP')))); ?></td></tr><?php endif; ?>

        <?php endforeach; ?><?php if(!$deliveries): ?><tr><td colspan="6">Belum ada DO pada periode ini.</td></tr><?php endif; ?></tbody></table></div></div></div>

        </section>

    </div>

<datalist id="master-vessels"><?php foreach(isset($master_vessels)?$master_vessels:array() as $v): ?><option value="<?= html_escape($v['VesselName']) ?>"><?= html_escape($v['CompanyName'].' / Area '.$v['SpbbID']) ?></option><?php endforeach; ?></datalist><datalist id="master-companies"><?php foreach(isset($master_companies)?$master_companies:array() as $v): ?><option value="<?= html_escape($v['CompanyName']) ?>"><?= html_escape('Area '.$v['SpbbID']) ?></option><?php endforeach; ?></datalist>
<script>(function(){var select=document.getElementById('do-source-spp');if(!select)return;var sources=<?= json_encode(array_column($spp_sources??array(),null,'CaptureID'),JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) ?>;function fill(){var s=sources[select.value];if(!s)return;var form=select.form,area=form.elements.SpbbID;area.value=String(s.SpbbID);area.dispatchEvent(new Event('change',{bubbles:true}));if(window.jQuery)window.jQuery(area).trigger('change.select2');[['DriverName','DriverName'],['VehicleNumber','VehicleOrVessel'],['Quantity','OrderedLiters'],['DeliveryDate','DeliveryDate'],['ReceiptNumber','LONumber']].forEach(function(pair){var field=form.elements[pair[0]];if(field)field.value=pair[1]==='DeliveryDate'?String(s[pair[1]]||'').slice(0,10):(s[pair[1]]||'');});}select.addEventListener('change',fill);if(select.value && <?= $this->input->method()==='post'?'false':'true' ?>)fill();})();</script>
