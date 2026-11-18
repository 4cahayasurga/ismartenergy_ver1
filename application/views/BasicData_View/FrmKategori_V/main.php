
<?php
error_reporting(E_ALL);
?>

<style type="text/css">
	.marginatas5{
		margin-top: 5px;
	}
</style>
<div class="content-wrapper">

	<section class="content-header">
		<h1><?=($judul) ? $judul : 'Judul belum diatur'  ?></h1>
		<ol class="breadcrumb">

		</ol>
	</section>

	<section class="content">
		<?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>

		<div class="row">
			<div class="col-sm-4">
				<div class="box">
					<div class="box-body">
						<div class="table-responsive">

							<form method="POST" action="<?=site_url('BasicData/FrmKategori_C/SimpanKategori')?>">
								<table width="100%" border="0">
									<tr>
										<td width="70px">Kategori</td>
										<td width="10px" class="text-center">:</td>
										<td width="150px">
											<input type="text" name="KategoriId" id="KategoriId" class="form-control input-sm" placeholder="Isi Disini" maxlength="30" required>
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas5">
									<tr>
										<td width="70px">Keterangan</td>
										<td width="10px" class="text-center">:</td>
										<td>
											<input type="text" name="Keterangan" id="Keterangan" class="form-control input-sm" placeholder="Isi Disini" maxlength="255" required>
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas5">
									<tr>
										<td width="70px"></td>
										<td width="10px" class="text-center"></td>
										<td>
											<input type="hidden" id="typeAksi" name="typeAksi" value="Simpan" readonly >
											<input type="hidden" name="KategoriIdLama" id="KategoriIdLama">

											<button class="btn btn-md btn-primary btnAksi" type="submit">SIMPAN</button>
											<button class="btn btn-md btn-link btnCancel" onclick="BatalEdit()" style="display: none;text-decoration: underline;" type="button">CANCEL UPDATE</button>
										</td>
										<td></td>
									</tr>
								</table>

							</form>

							<hr>

							<table class="table table-bordered table-striped table-hover table-condensed" id="mytable">
								<thead>
									<tr>
										<th class="text-center">No</th>
										<th class="text-center">Kategori</th>
										<th class="text-center">Keterangan</th>
										<th class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$Nomor = 1;
									foreach ($ListKategori as $row): 
										$Nomor = $Nomor++;
										$KategoriId = ShowKodeAman($row->KategoriId);
										$Keterangan = ShowKodeAman($row->Keterangan);
										?>
										<tr>
											<td><?=$Nomor++?></td>
											<td><?=ShowKodeAman($KategoriId)?></td>
											<td><?=ShowKodeAman($Keterangan)?></td>
											<td class="text-center">
												<button type="button" class="btn btn-link btn-md" onclick="EditKategori(<?=$Nomor?>)" id="EditKategoriID<?=$Nomor?>" data-KategoriId="<?=$KategoriId?>" data-Keterangan="<?=$Keterangan?>" >SELECT</button>

											</td>
										</tr>
									<?php endforeach ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				
			</div>
		</div>


	</section>

</div>

<script type="text/javascript">
	$(document).ready(function(){

		$('#mytable').DataTable();

	});

	function EditKategori(Nomor){
		$('#KategoriId').val('');
		$('#Keterangan').val('');
		$('#typeAksi').val('');
		$('.btnCancel').hide();
		$('#KategoriIdLama').val('');

		$('#typeAksi').val('Update');
		$('.btnAksi').text('UPDATE');

		var KategoriId = $('#EditKategoriID'+Nomor).attr('data-KategoriId');
		var Keterangan = $('#EditKategoriID'+Nomor).attr('data-Keterangan');

		$('#KategoriId').val(KategoriId);
		$('#Keterangan').val(Keterangan).focus();
		$('#KategoriIdLama').val(KategoriId);
		$('.btnCancel').show();
	}

	function BatalEdit(){
		$('#KategoriId').val('');
		$('#Keterangan').val('');
		$('#typeAksi').val('');
		$('#KategoriIdLama').val('');

		$('#typeAksi').val('Simpan');
		$('.btnAksi').text('SIMPAN');
		$('.btnCancel').hide();
	}


</script>



