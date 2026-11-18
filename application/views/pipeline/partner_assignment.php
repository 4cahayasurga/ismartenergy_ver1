<?php
$ci=get_instance();
if(!$ci->pipeline_access->allowed('transport_assign')||!$ci->db->table_exists('PipelineTransportAssignment'))return;
if($assignment_kind==='ORDER' && !$ci->flow->show_transport_assignment())return;
$current=$ci->db->where('TargetKind',$assignment_kind)->where('TargetID',$assignment_id)->get('PipelineTransportAssignment')->row_array();
$accounts=$ci->db->select('UserID,UserName')->where('UserRole','TRANSPORTIR')->where('Active','1')->order_by('UserName')->get('Login')->result_array();
?>
<div class="box box-default"><div class="box-body">
<form class="form-inline" method="post" action="<?= site_url('Pipeline_partner/assign/'.$assignment_kind.'/'.(int)$assignment_id) ?>">
<?php $this->load->view('pipeline/billing_token'); ?>
<label for="transport-user">Transportir</label>
<select class="form-control" name="TransportUser" id="transport-user" required><option value="">Pilih akun Transportir</option>
<?php foreach($accounts as $account): ?><option value="<?= html_escape($account['UserID']) ?>" <?= ($current['UserID']??null)===$account['UserID']?'selected':'' ?>><?= html_escape($account['UserName'].' ('.$account['UserID'].')') ?></option><?php endforeach; ?>
</select> <button class="btn btn-default" <?= !$accounts?'disabled':'' ?>>Simpan Penugasan</button></form>
<?php if(!$accounts): ?><p>Buat akun Transportir aktif di Pengaturan User.</p><?php endif; ?>
</div></div>
