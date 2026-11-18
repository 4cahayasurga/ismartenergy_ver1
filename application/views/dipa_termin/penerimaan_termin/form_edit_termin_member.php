<style type="text/css">
	table .atur_lebar{
		width: 150px !important;
		padding-top: 10px !important;
		padding-bottom: 10px !important;
	}
</style>




<form id="MyForm_TerminMemberEdit" style="font-size:11px;font-family: 'Goudy Bookletter 1911', sans-serif;">

	<table border="0" width="100%" >
		<tr>
			<td class="atur_lebar">Jenis Termin</td>
			<td width="10px" align="center" style="padding-right:5px;padding-left:5px;">:</td>
			<td>
				<select name="JenisTermin_termin_member" id="JenisTermin_termin_member" style="font-size:11px;font-family: 'Goudy Bookletter 1911', sans-serif;" class="form-control input-sm" required>
					<option value="">-- Pilih salah satu --</option>
					<option value="UM" <?=($dataTerminMember['Jenis'] == 'UM' ) ? 'selected' : '' ?>>Uang Muka</option>
					<option value="Termin" <?=($dataTerminMember['Jenis'] == 'Termin' ) ? 'selected' : '' ?>>Termin</option>
				</select>
			</td>
		</tr>

		<tr>
			<td class="atur_lebar">Tgl Cair</td>
			<td width="10px" align="center" style="padding-right:5px;padding-left:5px;">:</td>
			<td>
				<input type="date" name="TglCair_termin_member" id="TglCair_termin_member" class="form-control input-sm" value="<?=$dataTerminMember['TglCair']?>" required>
			</td>
		</tr>

		<tr>
			<td class="atur_lebar">Tgl Setor Ke Rekening Induk</td>
			<td width="10px" align="center" style="padding-right:5px;padding-left:5px;">:</td>
			<td>
				<input type="date"  name="TglSetor_termin_member" id="TglSetor_termin_member" class="form-control input-sm" value="<?=$dataTerminMember['TglSetor']?>" required>
			</td>
		</tr>

		<tr>
			<td class="atur_lebar">No.BAP</td>
			<td width="10px" align="center" style="padding-right:5px;padding-left:5px;">:</td>
			<td>
				<input type="text" name="NoBAP_termin_member" id="NoBAP_termin_member" class="form-control input-sm" placeholder="No. BAP" value="<?=$dataTerminMember['NoBAP']?>">
			</td>
		</tr>

		<tr>
			<td class="atur_lebar">Uraian</td>
			<td width="10px" align="center" style="padding-right:5px;padding-left:5px;">:</td>
			<td>
				<textarea name="Uraian_termin_member" id="Uraian_termin_member" class="form-control input-sm" placeholder="Uraian"><?=$dataTerminMember['Uraian']?></textarea>
			</td>
		</tr>

		<tr>
			<td class="atur_lebar"></td>
			<td width="10px" align="center" style="padding-right:5px;padding-left:5px;"></td>
			<td>
			<p>	</p>
			Netto Share Termin <?='<b>'.$dapat_member_dari_job['Member1'].'</b>'?> (Rp) (Leader)
			<input type="text"  name="NettoShareMember1" id="NettoShareMember1" onkeypress="return hanyaAngka(event)" class="form-control input-sm" placeholder="0" value="<?=new_decimal($dataTerminMember['TerminMember1'])?>" autocomplete="off"  required>
			</td>
		</tr>

		<tr>
			<td class="atur_lebar"></td>
			<td width="10px" align="center" style="padding-right:5px;padding-left:5px;"></td>
			<td>
			<p>	</p>
			Netto Share Termin <?='<b>'.$dapat_member_dari_job['Member2'].'</b>';?> (Rp) (Member)
			<input type="text"  name="NettoShareMember2" id="NettoShareMember2" onkeypress="return hanyaAngka(event)" class="form-control input-sm" placeholder="0" value="<?=new_decimal($dataTerminMember['TerminMember2'])?>" autocomplete="off" required>
			</td>
		</tr>

		<tr>
			<td class="atur_lebar"></td>
			<td width="10px" align="center" style="padding-right:5px;padding-left:5px;"></td>
			<td>
			<p>	</p>
			Cadangan KSO Member 1
			<input type="text"  name="CadanganKSOMember1" id="CadanganKSOMember1" onkeypress="return hanyaAngka(event)" class="form-control input-sm" placeholder="0" value="<?=new_decimal($dataTerminMember['CadanganKSO'])?>" autocomplete="off" required>
			</td>
		</tr>

		<tr>
			<td class="atur_lebar"></td>
			<td width="10px" align="center" style="padding-right:5px;padding-left:5px;"></td>
			<td>
			<p>	</p>
			Cadangan KSO Member 2
			<input type="text"  name="CadanganKSOMember2" id="CadanganKSOMember2" onkeypress="return hanyaAngka(event)" class="form-control input-sm" placeholder="0" value="<?=new_decimal($dataTerminMember['CadanganKSOMember1'])?>" autocomplete="off" required>
			</td>
		</tr>

		
	</table>

	<table border="0" width="100%">
		<tr>
			<?php if($dataTerminMember['PDFFile'] != NULL): ?>
				<td class="atur_lebar">Update PDF</td>
			<?php else: ?>
				<td class="atur_lebar">Upload PDF</td>
			<?php endif; ?>
			<td width="10px" align="center" style="padding-right: 5px;padding-left: 5px;">:</td>
			<td><input type="file" name="file_termin_member" id="file_termin_member" class="form-control input-sm"></td>
		</tr>
	</table>




	<table border="0" width="100%">
		<tr>
			<td>
				<hr>
				<input type="hidden" name="JobNo" value="<?=$JobNo?>">
				<input type="hidden" name="LedgerNo" value="<?=$LedgerNo?>">
				<button data-dismiss="modal" aria-label="close" class="btn btn-default btn-md">Batal</button>
				<button type="submit" class="btn btn-md btn-primary pull-right btn_simpan">Simpan <i class="fa fa-save"></i></button>
			</td>
		</tr>
	</table>

</form>


<script type="text/javascript">
	$('#MyForm_TerminMemberEdit').submit(function(e){
			e.preventDefault();
			var formData = new FormData(this)
			jQuery.ajax({
				url: '<?=site_url('DipaTermin/PenerimaanTermin/update_termin_member')?>',
				type: 'POST',
				data: formData,
				dataType: 'HTML',
				async: true,
				cache: false,
				contentType: false, // Important: Setting this to false
				processData: false, // Important: Setting this to false
				beforeSend : function(){
					$('#btn_simpan').attr('disabled',true).text('Loading...');
				},
				complete: function(xhr, textStatus) {
					$('#btn_simpan').attr('disabled',false).text('Simpan');
					$('#MyModalTerminInduk').modal('hide');
					// location.reload();
				},
				success: function(data, textStatus, xhr) {
					console.log(data);
				},
				error: function(xhr, textStatus, errorThrown) {
					$.toast({
						text: 'terjadi kesalahan',
						position: 'bottom-right',
						stack: false,
						hideAfter: 1000,
						icon: 'error',

					});
					return false;
				}
			});
			
		})
</script>

