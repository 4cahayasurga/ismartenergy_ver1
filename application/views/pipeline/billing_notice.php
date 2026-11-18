<?php foreach (array('Berhasil'=>'success','Gagal'=>'danger') as $key=>$class): if ($this->session->flashdata($key)): ?>
    <div class="alert alert-<?= $class ?>">
        <?= html_escape($this->session->flashdata($key)) ?>
    </div>
    <?php endif; endforeach; ?>
<?php if (!empty($error)): ?>
    <div class="alert alert-danger">
        <?= html_escape($error) ?>
    </div>
<?php endif; ?>
