<?php
$this->load->library('Pipeline_access');
// A library loaded during view rendering is attached to the controller first.
// CI_Loader copies controller properties only before entering a view.
$pipeline_access = get_instance()->pipeline_access;
$pipeline_links = array('areas'=>'Pipeline_setup/areas','master'=>'Pipeline_setup/master', 'bunker'=>'Pipeline_setup/bunker', 'ksop'=>'Pipeline_setup/ksop', 'rates'=>'Pipeline_billing/rates', 'invoices'=>'Pipeline_billing/invoices', 'receivables'=>'Pipeline_billing/receivables', 'dashboard' => 'Pipanisasi', 'spp_ocr'=>'Pipeline_spp', 'delivery' => 'Pipanisasi/delivery', 'export' => 'Pipanisasi/export');
$pipeline_role = $this->session->userdata('USER_ROLE');
if ($pipeline_role === 'CUSTOMER') $pipeline_links['orders'] = 'Pipanisasi/orders';
elseif ($pipeline_role !== 'OPERATOR') $pipeline_links['orders'] = 'Pipanisasi/orders';
$pipeline_visible = array();
foreach ($pipeline_access->menus() as $key => $label) {
    if (isset($pipeline_links[$key]) && $pipeline_access->allowed($key)) $pipeline_visible[$key] = $label;
}
$pipeline_active = in_array(strtolower($this->uri->segment(1)),array('pipanisasi','pipeline_spp')) || in_array($this->uri->segment(2), array('customer_pipeline', 'owner_pipeline'));
?>
<?php if ($pipeline_visible): ?>
<li class="treeview <?= $pipeline_active ? 'active menu-open' : '' ?>">
<a href="#"><i class="fa fa-exchange"></i><span>Pipanisasi</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
<ul class="treeview-menu">
<?php foreach ($pipeline_visible as $key => $label): ?><li><a href="<?= site_url($pipeline_links[$key]) ?>"><i class="fa fa-circle-o"></i> <?= html_escape($label) ?></a></li><?php endforeach; ?>
</ul></li>
<?php endif; ?>
