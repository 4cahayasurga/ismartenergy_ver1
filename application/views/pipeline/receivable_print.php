<!doctype html><html lang="id"><head><meta charset="utf-8"><title>Kartu Piutang <?= html_escape($customer) ?></title><style><?php if(empty($pdf_mode)): ?>@page{size:A4;margin:15mm}<?php endif; ?>body{font-family:Arial,sans-serif;font-size:10px}.ledger td,.ledger th{border:1px solid #555;padding:7px}.num{text-align:right;white-space:nowrap}thead{display:table-header-group}tr{page-break-inside:avoid}@media print{.no-print{display:none}}</style></head><body>
<?php if(empty($pdf_mode)): ?><button class="no-print" onclick="window.print()">Print / Save as PDF</button><?php endif; ?>
<h3 style="text-align:center">KARTU PIUTANG TAHUN <?= html_escape(substr($end,0,4)) ?></h3>
<p style="text-align:center"><strong><?= html_escape($customer) ?></strong> — Cabang <?= (int)$branch ?><br>Periode <?= html_escape($start.' s/d '.$end) ?></p>
<?php $this->load->view('pipeline/receivable_table'); ?>
<p style="text-align:right;margin-top:30px">Dicetak <?= date('d-m-Y') ?><br>Pembuat Kartu Piutang<br><br><br><?= html_escape($this->session->userdata('MIS_LOGGED_NAME')) ?></p>
</body></html>
