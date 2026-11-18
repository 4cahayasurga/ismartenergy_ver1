<div class="box box-primary"><div class="box-header"><h3 class="box-title"><?= html_escape($title) ?></h3></div><div class="box-body">
<?php $peak=max(1,max($values)); $labels=array('Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'); ?>
<table class="table table-condensed" aria-label="<?= html_escape($title) ?>"><thead><tr><th scope="col">Bulan</th><th scope="col">Grafik</th><th scope="col" class="text-right">Nilai</th></tr></thead><tbody>
<?php foreach ($labels as $i=>$month): $value=$values[$i+1]; ?>
<tr><th scope="row"><?= $month ?></th><td style="width:58%;vertical-align:middle"><div aria-hidden="true" style="height:9px;background:#e8eff4;border-radius:6px"><div style="width:<?= (float)(100*$value/$peak) ?>%;height:100%;background:#087faf;border-radius:6px"></div></div></td><td class="text-right"><?= $format($value) ?></td></tr>
<?php endforeach; ?>
</tbody></table></div></div>
