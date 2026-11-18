<form method="POST" action="<?=site_url('FrmMemo/update_memo')?>" >
	<div class="table-responsive">

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 10px;">
			<tr>
				<td width="100px">No Memo</td>
				<td style="width:1%;padding-left: 3px;padding-right: 3px;">:</td>
				<td width="350px" >
					<span style="font-size:14px;font-weight: bold;"><?=$data_memo->NoMemo?></span>
				</td>
				<td></td>
			</tr>
		</table>
		<table width="100%" border="0" style="border-collapse:collapse;">
			<tr>
				<td width="100px">Tanggal</td>
				<td style="width:1%;padding-left: 3px;padding-right: 3px;">:</td>
				<td width="350px" >
					<input type="date" name="TglMemo" class="form-control input-sm" value="<?=$data_memo->TglMemo?>" required>
				</td>

				<td style="text-align:right;" >Image Company&nbsp;&nbsp;&nbsp;</td>
				<td style="width:1%;padding-left: 3px;padding-right: 3px;">:</td>
				<td width="250px">
					<select name="ImageCompany" class="form-control input-sm select2" data-width="100%">
						<option value="">-- Pilih -- </option>
						<option value="MDH.jpg" <?=($data_memo->ImageCompany == 'MDH.jpg') ? 'selected' : '' ?>>MDH</option>
						<option value="KIP.jpg" <?=($data_memo->ImageCompany == 'KIP.jpg') ? 'selected' : '' ?> >KIP</option>
						<option value="KSB.jpg" <?=($data_memo->ImageCompany == 'KSB.jpg') ? 'selected' : '' ?> >KSB</option>
						<option value="KIB.jpg" <?=($data_memo->ImageCompany == 'KIB.jpg') ? 'selected' : '' ?> >KIB</option>
						<option value="KGS.jpg" <?=($data_memo->ImageCompany == 'KGS.jpg') ? 'selected' : '' ?> >KGS</option>
						<option value="KSC.jpg" <?=($data_memo->ImageCompany == 'KSC.jpg') ? 'selected' : '' ?> >KSC</option>
						<option value="REN.jpg" <?=($data_memo->ImageCompany == 'REN.jpg') ? 'selected' : '' ?> >REN</option>
						<option value="AMK.jpg" <?=($data_memo->ImageCompany == 'AMK.jpg') ? 'selected' : '' ?> >AMK</option>
					</select>
				</td>
			</tr>

		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 10px;">
			<tr>
				<td width="100px">Dari</td>
				<td style="width:1%;padding-left: 3px;padding-right: 3px;">:</td>
				<td width="350px" >
					<input type="text" class="form-control input-sm" readonly placeholder="Isi Disini">
					<input type="hidden" name="Pembuat" class="form-control input-sm" value="<?=$data_memo->Pembuat?>">
				</td>
				<td></td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;padding-top: 10px;margin-top: 10px;">
			<tr>
				<td width="100px">Kepada</td>
				<td style="width:1%;padding-left: 3px;padding-right: 3px;">:</td>
				<td width="350px" >
					<select name="Kepada" data-width="100%" class="form-control input-sm select2" required>
						<option value="0">-- Pilih -- </option>
						<?php foreach ($ListKaryawan as $rowkaryawan): ?>
							<option value="<?=$rowkaryawan->NIK?>"  <?=($data_memo->Kepada == $rowkaryawan->NIK) ? 'selected' : '' ?> ><?=$rowkaryawan->Nama?></option>
						<?php endforeach ?>
					</select>
				</td>
				<td></td>
			</tr>

		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 10px;">
			<tr>
				<td width="100px">Perihal</td>
				<td style="width:1%;padding-left: 3px;padding-right: 3px;">:</td>
				<td width="350px" >
					<input type="text" name="Perihal" class="form-control input-sm" placeholder="Isi Disini" value="<?=$data_memo->Perihal?>">
				</td>
				<td></td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 10px;">
			<tr>
				<td width="100px" style="vertical-align: top;">Remark</td>
				<td style="width:1%;padding-left: 3px;padding-right: 3px;">:</td>
				<td width="350px" >
					<textarea name="Remark" class="form-control input-sm" rows="10" cols="30" placeholder="Isi Disini"><?=$data_memo->Remark?></textarea>
				</td>
				<td></td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 10px;">
			<tr>
				<td width="100px">Tembusan</td>
				<td style="width:1%;padding-left: 3px;padding-right: 3px;">:</td>
				<td width="350px" >
					<select class="js-example-basic-multiple" data-width="100%" name="Tembusan[]" multiple="multiple">
						<?php foreach ($ListKaryawan as $rowkaryawan): ?>
							<?php if ($rowkaryawan->Golongan >= '3'): ?>

								<?php
								$array = explode(',', $data_memo->Tembusan);
								foreach($array as $value)  { ?>
									<option value="<?=$rowkaryawan->NIK?>" <?=($rowkaryawan->NIK == $value) ? 'selected' : '' ?> ><?=$rowkaryawan->Nama?></option> 
								<?php } ?>

								

							<?php endif ?>
						<?php endforeach ?>


					</select>
				</td>
				<td></td>
			</tr>
		</table>
		<hr>
		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 10px;">
			<tr>
				<td width="100px">
					<button class="btn btn-danger " data-dismiss="modal">Tutup</button>
				</td>
				<td class="text-right">
					<input type="hidden" name="NIK" value="<?=$NIK?>" readonly>
					<input type="hidden" name="LedgerNo" value="<?=$data_memo->LedgerNo?>" readonly>
					<input type="hidden" name="NoMemo" value="<?=$data_memo->NoMemo?>" readonly>

					<button type="submit" class="btn btn-primary btn-md" style="display: <?=$button_hilang?>;">Update</button>
				</td>
			</tr>

		</table>



	</div>
</form>

<script type="text/javascript">
	$(document).ready(function(){
		$('.select2').select2();
		$('.js-example-basic-multiple').select2();
	});
</script>