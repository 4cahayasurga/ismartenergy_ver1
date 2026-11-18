<?php if(!empty($companyHistory)): ?>
<div class="box box-warning"><div class="box-header"><h3 class="box-title">Nama Perusahaan pada Permohonan Terdahulu</h3></div><div class="box-body table-responsive">
<p>Surat menyimpan nama perusahaan pada saat diterbitkan.</p>
<table class="table table-bordered"><thead><tr><th>Nomor Permohonan</th><th>Nama Tersimpan pada Surat</th><th>Perusahaan Customer Saat Ini</th></tr></thead><tbody>
<?php foreach($companyHistory as $request): ?><tr>
<td><?php if($request['Status']!=='DRAFT'): ?><a href="<?= site_url('Pipeline_flow/detail/'.(int)$request['RequestID']) ?>"><?= html_escape($request['RequestNo']) ?></a><?php else: ?><?= html_escape($request['RequestNo']) ?> (Draf)<?php endif; ?></td>
<td><?= html_escape($request['SavedCompany']) ?></td><td><?= html_escape($request['CurrentCompany']) ?></td></tr><?php endforeach; ?>
</tbody></table></div></div><?php endif; ?>
