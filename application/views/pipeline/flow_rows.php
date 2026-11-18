<?php $this->load->model('Pipeline_flow_model','branch_flow_status'); ?>
<div class="box box-primary"><div class="box-body table-responsive">
<table class="table table-bordered"><thead><tr><th>Nomor Permohonan</th><th>Tanggal / Jam Pengajuan</th><th>Kapal / Perusahaan</th><th>Volume Rencana</th><?php if (isset($creators)): ?><th>Pembuat</th><?php endif; ?><th>Status</th><?php if (!empty($showOrderActions)): ?><th>Aksi</th><?php endif; ?></tr></thead><tbody>
<?php foreach ($rows as $item): $letter=json_decode($item['Letter'],true); ?>
<tr><td><a href="<?= site_url('Pipeline_flow/detail/'.(int)$item['RequestID']).($anchors[$item['Status']]??'') ?>"><?= html_escape($item['RequestNo']) ?></a></td>
<td><?= html_escape($item['RequestDate']) ?><br><small><?= html_escape(isset($item['CreatedAt'])?substr((string)$item['CreatedAt'],11,8):'—') ?></small></td><td><?= html_escape($letter['VesselName'].' / '.$letter['CompanyName']) ?></td>
<td><?= number_format($letter['PlannedLiters'],3,',','.') ?> L</td><?php if (isset($creators)): ?><td><?= html_escape($creators[$item['CreatedBy']]??$item['CreatedBy']) ?></td><?php endif; ?><td><?= html_escape(get_instance()->branch_flow_status->filling_status($item)) ?></td>
<?php if (!empty($showOrderActions)): ?><td>
<?php if (get_instance()->branch_flow_status->may_manage_order($item)): ?>
<a class="btn btn-default btn-xs" href="<?= site_url('Pipeline_flow/edit/'.(int)$item['RequestID']) ?>">Edit</a>
<form method="post" action="<?= site_url('Pipeline_flow/action/'.(int)$item['RequestID']) ?>" style="display:inline" onsubmit="return confirm('Batalkan pesanan ini dari daftar aktif?');">
<input type="hidden" name="pipeline_token" value="<?= html_escape($orderToken) ?>">
<input type="hidden" name="OrderVersion" value="<?= html_escape(get_instance()->branch_flow_status->order_version($item)) ?>">
<button class="btn btn-danger btn-xs" name="action" value="cancel_order">Hapus</button>
</form>
<?php else: ?>—<?php endif; ?>
</td><?php endif; ?></tr>
<?php endforeach; if (!$rows): ?><tr><td colspan="<?= 5+(int)isset($creators)+(int)!empty($showOrderActions) ?>">Belum ada pesanan untuk ditampilkan.</td></tr><?php endif; ?>
</tbody></table></div></div>
