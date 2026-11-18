
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

							<form method="POST" action="<?=site_url('BasicData/FrmRekening_C/SimpanRekening')?>">
								<table width="100%" border="0" class="font12">
									<tr>
										<td width="70px">Rekening ID</td>
										<td width="10px" class="text-center">:</td>
										<td width="300px">
											<input type="text" name="RekId" id="RekId" class="form-control input-sm font12" placeholder="Isi Disini" maxlength="50" required>
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas5 font12">
									<tr>
										<td width="70px">Bank</td>
										<td width="10px" class="text-center">:</td>
										<td width="250px">
											<select name="Bank" id="Bank" class="form-control input-sm">
												<option value="">-- Pilih salah satu --</option>
												<?php foreach ($ListBank as $bank): ?>
													<option value="<?=$bank->Bank?>"><?=$bank->Bank?></option>
												<?php endforeach ?>
											</select>
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas5 font12">
									<tr>
										<td width="70px">No. Rekening</td>
										<td width="10px" class="text-center">:</td>
										<td width="300px">
											<input type="text" name="NoRek" id="NoRek" class="form-control input-sm" placeholder="Isi Disini" maxlength="200" >
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas5 font12">
									<tr>
										<td width="70px">Atas Nama</td>
										<td width="10px" class="text-center">:</td>
										<td width="300px">
											<input type="text" name="AtasNama" id="AtasNama" class="form-control input-sm" placeholder="Isi Disini" maxlength="30" >
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas5 font12">
									<tr>
										<td width="70px"></td>
										<td width="10px" class="text-center"></td>
										<td width="300px">
											<input type="checkbox" name="PaymentPD" id="PaymentPD" value="1">
											<label for="PaymentPD">Rekening digunakan untuk pembayaran PD</label>
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
											<input type="hidden" name="RekIdLama" id="RekIdLama">
											<input type="hidden" name="LedgerNo" id="LedgerNo">

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
										<th class="text-center">Kd Rek</th>
										<th class="text-center">Bank</th>
										<th class="text-center">No. Rekening</th>
										<th class="text-center">Atas Nama</th>
										<th class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$Nomor = 1;
									foreach ($ListRekening as $row): 
										$RekId = ShowKodeAman($row->RekId);
										$Bank = ShowKodeAman($row->Bank);
										$NoRek = ShowKodeAman($row->NoRek);
										$AtasNama = ShowKodeAman($row->AtasNama);
										$PaymentPD = ShowKodeAman($row->PaymentPD);
										$LedgerNo = ShowKodeAman($row->LedgerNo);
										?>
										<tr>
											<td><?=$Nomor++?></td>
											<td><?=$RekId?></td>
											<td><?=$Bank?></td>
											<td><?=$NoRek?></td>
											<td><?=$AtasNama?></td>
											<td class="text-center">
												<button type="button" class="btn btn-link btn-md" onclick="EditRekening('<?=$LedgerNo?>')" id="EditRekeningID<?=$LedgerNo?>" data-RekId="<?=$RekId?>" data-Bank="<?=$Bank?>" data-NoRek="<?=$NoRek?>" data-AtasNama="<?=$AtasNama?>" data-PaymentPD="<?=$PaymentPD?>" >SELECT</button>
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

		$('#mytable').DataTable({
			// "pageLength": 10
		});
		$('#Bank').select2();

	});

	function EditRekening(LedgerNo){
		$('#RekId').val('');
		$('#Bank').val('').trigger('change');
		$('#NoRek').val('');
		$('#AtasNama').val('');
		$('#PaymentPD').prop('checked', false);
		$('#LedgerNo').val('');
		$('#RekIdLama').val('');

		$('#typeAksi').val('');
		$('.btnCancel').hide();
		
		var RekId = $('#EditRekeningID'+LedgerNo).attr('data-RekId');
		var Bank = $('#EditRekeningID'+LedgerNo).attr('data-Bank');
		var NoRek = $('#EditRekeningID'+LedgerNo).attr('data-NoRek');
		var AtasNama = $('#EditRekeningID'+LedgerNo).attr('data-AtasNama');
		var PaymentPD = $('#EditRekeningID'+LedgerNo).attr('data-PaymentPD');

		$('#RekId').val(RekId);
		$('#Bank').val(Bank).trigger('change');
		$('#NoRek').val(NoRek);
		$('#AtasNama').val(AtasNama);
		$('#PaymentPD').prop('checked', (PaymentPD == 1) ? true : false  );
		$('#LedgerNo').val(LedgerNo);
		$('#RekIdLama').val(RekId);

		$('#typeAksi').val('Update');
		$('.btnAksi').text('UPDATE');
		$('.btnCancel').show();

	}

	function BatalEdit(){
		$('#RekId').val('');
		$('#Bank').val('').trigger('change');
		$('#NoRek').val('');
		$('#AtasNama').val('');
		$('#PaymentPD').prop('checked', false);
		$('#LedgerNo').val('');
		$('#RekIdLama').val('');

		$('#typeAksi').val('Simpan');
		$('.btnAksi').text('SIMPAN');
		$('.btnCancel').hide();
	}


</script>



