<input type="hidden" name="pipeline_token" value="<?= html_escape($pipeline_token) ?>">
<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
