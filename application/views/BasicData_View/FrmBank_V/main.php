
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
		<h1 style="text-decoration: underline;" ><?=($judul) ? $judul : 'Judul belum diatur'  ?></h1>
		<ol class="breadcrumb">

		</ol>
	</section>

	<section class="content">
		<?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>

		<div class="box box-primary">
			<div class="box-body">

				<div class="row">
					<div class="col-sm-4">
						<div class="table-responsive">

							<form method="POST" action="<?=site_url('BasicData/FrmBank_C/SimpanBank')?>">
								<table width="100%" border="0">
									<tr>
										<td width="70px">Bank</td>
										<td width="10px" class="text-center">:</td>
										<td >
											<input type="text" name="Bank" id="Bank" class="form-control input-sm " placeholder="Isi Disini" maxlength="20" required>
											<input type="number" name="Bank_Id" id="Bank_Id" class="form-control input-sm" style="display:none;">
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
											<input type="hidden" name="BankLama" id="BankLama">

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
										<th class="text-center">Bank</th>
										<th class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$Nomor = 1;
									foreach ($ListBank as $row): 
										$Bank = ShowKodeAman($row->Bank);
										$Bank_Id = ShowKodeAman($row->Bank_Id);
										?>
										<tr>
											<td class="text-center"><?=$Nomor++?></td>
											<td class="text-left"><?=ShowKodeAman($Bank)?></td>
											<td class="text-center">
												<button type="button" class="btn btn-link btn-md" onclick="EditBank(<?=$Bank_Id?>)" id="EditBankID<?=$Bank_Id?>" data-Bank="<?=$Bank?>" >SELECT</button>

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
			"pageLength": 50
		});

	});

	function EditBank(Bank_Id){
		$('#Bank').val('');
		$('#Bank_Id').val('');
		$('#typeAksi').val('');
		$('.btnCancel').hide();
		$('#BankLama').val('');

		$('#typeAksi').val('Update');
		$('.btnAksi').text('UPDATE');

		var Bank = $('#EditBankID'+Bank_Id).attr('data-Bank');

		$('#Bank').val(Bank).focus();
		$('#Bank_Id').val(Bank_Id).addClass('borderbiru');
		$('#BankLama').val(Bank_Id);
		$('.btnCancel').show();
	}

	function BatalEdit(){
		$('#Bank').val('');
		$('#Bank_Id').val('');
		$('#typeAksi').val('');
		$('#BankLama').val('');

		$('#typeAksi').val('Simpan');
		$('.btnAksi').text('SIMPAN');
		$('.btnCancel').hide();
	}


</script>



