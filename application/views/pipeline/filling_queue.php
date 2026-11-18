<div class="content-wrapper"><section class="content-header"><h1><?= html_escape($judul) ?></h1></section>
<section class="content">
<?php $anchors=array('WAIT_TRANSPORT'=>'#verifikasi-sumber','TRANSPORT_CONFIRMED'=>'#proses-pengisian','FILLING'=>'#selesai-pengisian','FILLED'=>'#catat-pengisian'); $this->load->view('pipeline/flow_rows',compact('rows','statuses','anchors')); ?>
</section></div>
