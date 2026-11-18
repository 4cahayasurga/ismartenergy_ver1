
<?php
error_reporting(E_ALL);
?>

<style type="text/css">
	.marginatas5{
		margin-top: 5px;
	}

	.font12{
		font-size: 12px !important;
	}
	.select2-results__option { 
		font-size: 12px !important;
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
			<div class="col-sm-6">
				<div class="box">
					<div class="box-body">
						<div class="table-responsive">

							<form method="POST" action="<?=site_url('BasicData/FrmTipeForm_C/SimpanTipeForm')?>">
								<table width="100%" border="0" class="font12">
									<tr>
										<td width="70px">Tipe Form</td>
										<td width="10px" class="text-center">:</td>
										<td width="80px">
											<input type="text" name="TipeForm" id="TipeForm" class="form-control input-sm font12" placeholder="Isi Disini" maxlength="10" required>
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas5 font12">
									<tr>
										<td width="70px">Alokasi</td>
										<td width="10px" class="text-center">:</td>
										<td width="200px">
											<select name="Alokasi" id="Alokasi" class="form-control input-sm" required >
												<option value="">-- Pilih salah satu --</option>
												<?php foreach ($ListAlokasi as $row): ?>
													<option value="<?=$row->Alokasi?>"><?=$row->GabungAlokasi?></option>
												<?php endforeach ?>
											</select>
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas5 font12">
									<tr>
										<td width="70px">Keterangan</td>
										<td width="10px" class="text-center">:</td>
										<td width="200px">
											<input type="text" name="Keterangan" id="Keterangan" class="form-control input-sm" placeholder="Isi Disini" maxlength="100" required>
										</td>
										<td></td>
									</tr>
								</table>

								

								<table width="100%" border="0" class="marginatas5 font12">
									<tr>
										<td width="70px"></td>
										<td width="10px" class="text-center"></td>
										<td>
											<input type="hidden" id="typeAksi" name="typeAksi" value="Simpan" readonly >
											<input type="hidden" name="TipeFormLama" id="TipeFormLama">

											<button class="btn btn-md btn-primary btnAksi" type="submit">SIMPAN</button>
											<button class="btn btn-md btn-link btnCancel" onclick="BatalEdit()" style="display: none;text-decoration: underline;" type="button">CANCEL UPDATE</button>
										</td>
										<td></td>
									</tr>
								</table>

							</form>

							<hr>

							<table class="table table-bordered table-striped table-hover table-condensed font12" id="mytable">
								<thead>
									<tr>
										<th class="text-center">No</th>
										<th class="text-center">Tipe Form</th>
										<th class="text-center">Alokasi</th>
										<th class="text-center">Keterangan</th>
										<th class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$Nomor = 1;
									foreach ($ListTipeForm as $row): 
										$Nomor = $Nomor++;
										$TipeForm = ShowKodeAman($row->TipeForm);
										$Alokasi = ShowKodeAman($row->Alokasi);
										$Keterangan = ShowKodeAman($row->Keterangan);
										?>
										<tr>
											<td class="text-center" ><?=$Nomor?></td>
											<td><?=$TipeForm?></td>
											<td><?=$Alokasi?></td>
											<td><?=$Keterangan?></td>
											<td class="text-center">
												<button type="button" class="btn btn-link btn-md" onclick="EditTipeForm(<?=$Nomor?>)" id="EditTipeFormID<?=$Nomor++?>" data-TipeForm="<?=$TipeForm?>" data-Alokasi="<?=$Alokasi?>" data-Keterangan="<?=$Keterangan?>"  >SELECT</button>
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
		$('#Alokasi').select2();

	});

	function EditTipeForm(Nomor){
		$('#TipeForm').val('');
		$('#Alokasi').val('').trigger('change');
		$('#Keterangan').val('');
		
		$('#TipeFormLama').val('');

		$('#typeAksi').val('');
		$('.btnCancel').hide();

		// alert(TipeForm);
		
		var TipeForm = $('#EditTipeFormID'+Nomor).attr('data-TipeForm');
		var Alokasi = $('#EditTipeFormID'+Nomor).attr('data-Alokasi');
		var Keterangan = $('#EditTipeFormID'+Nomor).attr('data-Keterangan');

		$('#TipeForm').val(TipeForm);
		$('#Alokasi').val(Alokasi).trigger('change');
		$('#Keterangan').val(Keterangan);
		$('#TipeFormLama').val(TipeForm);

		$('#typeAksi').val('Update');
		$('.btnAksi').text('UPDATE');
		$('.btnCancel').show();

	}

	function BatalEdit(){
		$('#TipeForm').val('');
		$('#Alokasi').val('').trigger('change');
		$('#Keterangan').val('');
		
		$('#TipeFormLama').val('');

		$('#typeAksi').val('Simpan');
		$('.btnAksi').text('SIMPAN');
		$('.btnCancel').hide();
	}

</script>



