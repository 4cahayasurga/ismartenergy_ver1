<div class="row">
	<?php foreach ($data_logo as $row_foto): ?>
		<div class="col-md-12 text-center" style="padding-top:20px !important;">
			<button type="button" class="btn_ok" data-OK="<?=$row_foto->LogoID?>" >
				<img src="<?=base_url('LogoKontrak/'.$row_foto->LogoFoto)?>" class="img img-thumbnail" width="700px" height="500px" >
				</button>
			<hr style="border-bottom:2px solid silver;">
		</div>

	<?php endforeach ?>
	
</div>
