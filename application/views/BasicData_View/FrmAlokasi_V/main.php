
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

		<div class="box box-primary">
			<div class="box-body">

				<div class="row">
					<div class="col-sm-4 ">

						<div class="table-responsive">

							<form method="POST" action="<?=site_url('BasicData/FrmAlokasi_C/SimpanAlokasi')?>">
								<table width="100%" border="0">
									<tr>
										<td width="70px">Alokasi</td>
										<td width="10px" class="text-center">:</td>
										<td>
											<input type="text" name="Alokasi" id="Alokasi" class="form-control input-sm" placeholder="Isi Disini" maxlength="1" required>
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas5">
									<tr>
										<td width="70px">Keterangan</td>
										<td width="10px" class="text-center">:</td>
										<td>
											<input type="text" name="Keterangan" id="Keterangan" class="form-control input-sm" placeholder="Isi Disini" maxlength="200" required>
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
											<input type="hidden" name="AlokasiLama" id="AlokasiLama">

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
										<th class="text-center">Alokasi</th>
										<th class="text-center">Keterangan</th>
										<th class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$Nomor = 1;
									foreach ($ListAlokasi as $row): 
										$Alokasi = ShowKodeAman($row->Alokasi);
										$Keterangan = ShowKodeAman($row->Keterangan);
										?>
										<tr>
											<td><?=$Nomor++?></td>
											<td><?=ShowKodeAman($Alokasi)?></td>
											<td><?=ShowKodeAman($Keterangan)?></td>
											<td class="text-center">
												<button type="button" class="btn btn-link btn-md" onclick="EditAlokasi('<?=$Alokasi?>')" id="EditAlokasiID<?=$Alokasi?>" data-Keterangan="<?=$Keterangan?>" >SELECT</button>

												<!-- <a href="<?=site_url('BasicData/FrmAlokasi_C/hapus/'.$Alokasi)?>" class="btn btn-link btn-md" onclick="return confirm('Delete Alokasi?')" >HAPUS</button> -->
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

		function EditAlokasi(Alokasi){
			$('#Alokasi').val('');
			$('#Keterangan').val('');
			$('#typeAksi').val('');
			$('.btnCancel').hide();
			$('#AlokasiLama').val('');

			$('#typeAksi').val('Update');
			$('.btnAksi').text('UPDATE');

			var Keterangan = $('#EditAlokasiID'+Alokasi).attr('data-Keterangan');

		// alert(Keterangan);

			$('#Alokasi').val(Alokasi);
			$('#Keterangan').val(Keterangan).focus();
			$('#AlokasiLama').val(Alokasi);
			$('.btnCancel').show();
		}

		function BatalEdit(){
			$('#Alokasi').val('');
			$('#Keterangan').val('');
			$('#typeAksi').val('');
			$('#AlokasiLama').val('');

			$('#typeAksi').val('Simpan');
			$('.btnAksi').text('SIMPAN');
			$('.btnCancel').hide();
		}


	</script>



