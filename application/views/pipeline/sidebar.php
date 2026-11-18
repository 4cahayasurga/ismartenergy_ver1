<?php
$this->load->library('Pipeline_access');$nav=get_instance()->pipeline_access;
$links=$nav->links();$labels=$nav->menus();$current=$nav->current($this->uri->segment(1),$this->uri->segment(2));
$groups=$nav->groups();
$permission=array();
$isCustomer=$this->session->userdata('USER_ROLE')==='CUSTOMER';
if ($isCustomer) {
    $groups=array(
        array('label'=>'Basic Data','icon'=>'fa-database','keys'=>array('customer_logo','customer_vessels','customer_documents','customer_users','customer_team')),
        array('label'=>'Operation','icon'=>'fa-exchange','keys'=>array('customer_create','customer_order','customer_submissions','customer_supervision','customer_kkm','flow_approval')),
        array('label'=>'Laporan','icon'=>'fa-bar-chart','keys'=>array('customer_history')),
    );
    $links+=array('customer_create'=>'Pipeline_flow/create','customer_submissions'=>'Pipeline_flow/submissions');
    $labels=array_replace($labels,array('customer_create'=>'Buat Surat Permohonan Bunkering',
        'customer_order'=>'Pemesanan BBM Pipanisasi','customer_submissions'=>'Pengajuan Bunkering',
        'customer_history'=>'Riwayat Pengisian dan Status'));
    $permission=array('customer_create'=>'customer_order','customer_submissions'=>'customer_order');
    if (strtolower((string)$this->uri->segment(1))==='pipeline_flow') {
        $current=array('create'=>'customer_create','submissions'=>'customer_submissions','detail'=>'customer_order',
            'edit'=>'customer_order','approval'=>$nav->allowed('customer_kkm')?'customer_kkm':'flow_approval')[strtolower((string)$this->uri->segment(2))]??$current;
    }
}
$icons=array('customer_team'=>'fa-users','customer_team_toggle'=>'fa-sliders','customer_kkm'=>'fa-check-square-o','customer_supervision'=>'fa-check-square-o','filling'=>'fa-play-circle','filling_actual'=>'fa-tint','transport_portal'=>'fa-truck','partner_revenue'=>'fa-bar-chart','customer_logo'=>'fa-picture-o','flow_approval'=>'fa-check-square-o','flow_settings'=>'fa-sliders','customer_accounts'=>'fa-users','customer_dashboard'=>'fa-dashboard','customer_order'=>'fa-shopping-cart','customer_vessels'=>'fa-ship','customer_documents'=>'fa-folder-open-o','customer_users'=>'fa-user-plus','customer_history'=>'fa-history','operator_dashboard'=>'fa-dashboard','operator_queue'=>'fa-list','operator_shift'=>'fa-bar-chart','operator_company'=>'fa-building','registration_approval'=>'fa-check-square-o','areas'=>'fa-map-marker','master'=>'fa-ship','rates'=>'fa-tags','pass_rates'=>'fa-ticket','bastb'=>'fa-file-text-o','rdt'=>'fa-tint','port_pass'=>'fa-ticket','orders'=>'fa-shopping-cart','delivery'=>'fa-truck','spp_ocr'=>'fa-camera','bunker'=>'fa-file-text-o','ksop'=>'fa-folder-open-o','invoices'=>'fa-file-text','export'=>'fa-file-excel-o','receivables'=>'fa-list-alt','users'=>'fa-users','access_management'=>'fa-key');
?>
<?php if($nav->allowed('dashboard')): ?><li class="<?= $current==='dashboard'?'active':'' ?>"><a href="<?= site_url($links['dashboard']) ?>"><i class="fa fa-dashboard"></i><span>Stock Movement</span></a></li><?php endif; ?>
<?php if ($isCustomer && $nav->allowed('customer_dashboard')): ?>
<li class="<?= $current==='customer_dashboard'?'active':'' ?>"><a href="<?= site_url($links['customer_dashboard']) ?>"><i class="fa fa-dashboard"></i><span>Dashboard Customer</span></a></li>
<?php endif; ?>
<?php foreach($groups as $group): $visible=array();foreach($group['keys'] as $key)if(isset($links[$key])&&$nav->allowed($permission[$key]??$key)&&!($key==='flow_approval'&&in_array('customer_kkm',$visible,true)))$visible[]=$key;if(!$visible)continue;$active=in_array($current,$visible,true); ?>
<li class="treeview <?= $active?'active menu-open':'' ?>"><a href="#" aria-expanded="<?= $active?'true':'false' ?>"><i class="fa <?= $group['icon'] ?>"></i><span><?= html_escape($group['label']) ?></span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu" <?= $active?'style="display:block"':'' ?>>
<?php foreach($visible as $key): ?><li class="<?= $current===$key?'active':'' ?>"><a href="<?= site_url($links[$key]) ?>" <?= $current===$key?'aria-current="page"':'' ?>><i class="fa <?= $icons[$key]??'fa-file-text-o' ?>"></i><span><?= html_escape($labels[$key]) ?></span></a></li><?php endforeach; ?>
</ul></li><?php endforeach; ?>