<?php 
error_reporting(0);
$foto = '';
if (isset($row['Foto'])) {
	
	if ($foto !== NULL || $foto !== '') {
		$foto = 'assets/foto_karyawan/'.$row['Foto'];
	}else{
		$foto = 'assets/dist/img/default_foto.jpg';
	}

}else{
	$foto = 'assets/dist/img/default_foto.jpg';
}

?>
<div class="row">
	<div class="col-12">
		<div class="box" style="border:none;">
			<div class="box-body text-center">




				<img src="<?php echo base_url($foto) ?>" id="myfoto" style="max-width: 300px !important;min-width: 100px !important;" class="img img-fluid img-thumbnail">
			</div>
			<div class="box-footer">
				<form id="myform" action="<?=site_url('Kepegawaian/aksi_ubah_foto')?>" method="POST" enctype="multipart/form-data">
					<div class="col-12">
						<label>Ganti Foto</label>
						<input type="file" name="foto_saya" class="form-control form-control-sm" id="imgInp" accept="image/*" required  >
						<br>
						<input type="hidden" name="NIK" value="<?=$row['NIK']?>">
						<button type="submit" class="btn btn-sm btn-primary pull-right">Simpan</button>
					</div>
					<!-- <div class="col-12">
						<button type="submit" class="btn btn-sm btn-primary pull-right">Simpan</button>
					</div> -->
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	imgInp.onchange = evt => {
		const [file] = imgInp.files
		if (file) {
			myfoto.src = URL.createObjectURL(file)
		}
	}
	
</script>