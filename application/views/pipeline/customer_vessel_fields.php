<div class="row">
<?php foreach ($fields as $key=>$field): ?><div class="col-md-6 form-group">
<label for="vessel-<?= $key ?>"><?= html_escape($field[0]) ?></label>
<input id="vessel-<?= $key ?>" name="<?= $key ?>" class="form-control" value="<?= html_escape($form[$key]??'') ?>"
 <?= !empty($field[2])?'type="number" min="0" step="0.01"':'type="text" maxlength="'.(int)$field[1].'"' ?> <?= in_array($key,$vesselSettings['required'],true)?'required':'' ?>
 <?= $key==='RouteName'?'list="customer-vessel-routes" aria-describedby="route-help"':'' ?>>
<?php if ($key==='RouteName'): ?>
<datalist id="customer-vessel-routes">
<?php foreach ($routes as $route): ?><option value="<?= html_escape($route) ?>"></option><?php endforeach; ?>
</datalist>
<small id="route-help" class="help-block">Pilih lintasan tersimpan atau ketik lintasan baru.</small>
<?php endif; ?>
</div><?php endforeach; ?></div>
