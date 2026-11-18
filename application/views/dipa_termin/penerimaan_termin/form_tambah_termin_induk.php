<style type="text/css">
	table .atur_lebar{
		width: 150px !important;
		padding-top: 10px !important;
		padding-bottom: 10px !important;
	}
</style>




<form id="MyForm_TerminInduk">

	<table border="0" width="100%" >
		<tr>
			<td class="atur_lebar">Jenis Termin</td>
			<td width="10px" align="center" style="padding-right:5px;padding-left:5px;">:</td>
			<td>
				<select name="JenisTermin_termin_induk" id="JenisTermin_termin_induk" class="form-control input-sm" required>
					<option value="">-- Pilih salah satu --</option>
					<option value="UM">Uang Muka</option>
					<option value="Termin">Termin</option>
				</select>
			</td>
		</tr>

		<tr>
			<td class="atur_lebar">Tgl Cair</td>
			<td width="10px" align="center" style="padding-right:5px;padding-left:5px;">:</td>
			<td>
				<input type="date" value="<?=date('Y-m-d')?>" name="TglCair_termin_induk" id="TglCair_termin_induk" class="form-control input-sm" required>
			</td>
		</tr>

		<tr>
			<td class="atur_lebar">No .BAP</td>
			<td width="10px" align="center" style="padding-right:5px;padding-left:5px;">:</td>
			<td>
				<input type="text"  name="NoBAP_termin_induk" id="NoBAP_termin_induk" class="form-control input-sm" placeholder="No. BAP" autocomplete="off" required>
			</td>
		</tr>

		<tr>
			<td class="atur_lebar">Uraian</td>
			<td width="10px" align="center" style="padding-right:5px;padding-left:5px;">:</td>
			<td>
				<textarea name="Uraian_termin_induk" id="Uraian_termin_induk" class="form-control input-sm" placeholder="Uraian"></textarea>
			</td>
		</tr>

		<tr>
			<td class="atur_lebar">Bruto BOQ (Rp)</td>
			<td width="10px" align="center" style="padding-right:5px;padding-left:5px;">:</td>
			<td>
				<input type="text"  name="BrutoBOQ_termin_induk" id="BrutoBOQ_termin_induk" onkeypress="return hanyaAngka(event)" class="form-control input-sm" placeholder="0" value="0" autocomplete="off" required>
			</td>
		</tr>
	</table>

	<table border="0" width="100%">

		<tr>
			<td class="atur_lebar">Potongan Uang Muka (Rp)</td>
			<td width="10px" align="center" style="padding-right:5px;padding-left:5px;">:</td>
			<td>
				<input type="text"  name="PotonganUangMuka_termin_induk" id="PotonganUangMuka_termin_induk" class="form-control input-sm" placeholder="0" onkeypress="return hanyaAngka(event)" value="0" autocomplete="off" required>
			</td>
		</tr>

		<tr>
			<td class="atur_lebar">Potongan Retensi (Rp)</td>
			<td width="10px" align="center" style="padding-right:5px;padding-left:5px;">:</td>
			<td>
				<input type="text"  name="PotonganRetensi_termin_induk" id="PotonganRetensi_termin_induk" class="form-control input-sm" placeholder="0" value="0" onkeypress="return hanyaAngka(event)" autocomplete="off" required>
			</td>
		</tr>

		<tr>
			<td class="atur_lebar">Netto BOQ (Rp)</td>
			<td width="10px" align="center" style="padding-right:5px;padding-left:5px;">:</td>
			<td>
				<input type="text"  class="form-control input-sm" id="NettoBOQ_termin_induk" name="NettoBOQ_termin_induk" onkeypress="return hanyaAngka(event)" value="0"  >
			</td>
		</tr>
	</table>

	<table border="0" width="100%">
		<tr>
			<td class="atur_lebar">PPN</td>
			<td width="10px" align="center" style="padding-right:5px;padding-left:5px;">:</td>
			<td>
				<select name="PPN_termin_induk" id="PPN_termin_induk" class="form-control input-sm" required>
					<option value="0">Tanpa PPN</option>
					<option value="11">11%</option>
					<option value="10">10%</option>
				</select>
			</td>
			<td style="padding-left: 5px;padding-right: 5px;">
				<input type="text"  id="tampil_nominal_ppn"  name="PPN_nominal" value="0" class="form-control input-sm" onkeypress="return hanyaAngka(event)">
			</td>
		</tr>
		<tr>
			<td class="atur_lebar">PPH</td>
			<td width="10px" align="center" style="padding-right:5px;padding-left:5px;">:</td>
			<td>
				<select name="PPH_termin_induk" id="PPH_termin_induk" class="form-control input-sm" required>
					<option value="0">Tanpa PPH</option>
					<option value="3">3%</option>
					<option value="2.65">2,65%</option>
				</select>
			</td>
			<td style="padding-left: 5px;padding-right: 5px;">
				<input type="text"  id="tampil_nominal_pph" value="0" name="PPH_nominal" class="form-control input-sm">
			</td>
		</tr>
	</table>

	<table border="0" width="100%">
		<tr>
			<td class="atur_lebar">Netto Penerimaan</td>
			<td width="10px" align="center" style="padding-right:5px;padding-left:5px;">:</td>
			<td>
				<input type="text"  class="form-control input-sm" id="NettoPenerimaan_termin_induk" name="NettoPenerimaan_termin_induk" value="0" placeholder="0" onkeypress="return hanyaAngka(event)">
			</td>
		</tr>
	</table>

	<table border="0" width="100%">
		<tr>
			<td class="atur_lebar">Upload PDF</td>
			<td width="10px" align="center" style="padding-right: 5px;padding-left: 5px;">:</td>
			<td><input type="file" name="file_termin_induk" id="file_termin_induk" class="form-control input-sm" required></td>
		</tr>
	</table>

	<table border="0" width="100%">
		<tr>
			<td>
				<hr>
				<input type="hidden" name="JobNo" value="<?=$JobNo?>">
				<button data-dismiss="modal" aria-label="close" class="btn btn-default btn-md">Batal</button>
				<button type="submit" class="btn btn-md btn-primary pull-right btn_simpan">Simpan <i class="fa fa-save"></i></button>
			</td>
		</tr>
	</table>


</form>

<script type="text/javascript">
	$(function(){

		$('#MyForm_TerminInduk').submit(function(e){
			e.preventDefault();
    		var formData = new FormData(this)
			jQuery.ajax({
				url: '<?=site_url('DipaTermin/PenerimaanTermin/aksi_tambah_termin_induk')?>',
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
					location.reload();
				},
				success: function(data, textStatus, xhr) {
					// console.log(data)
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


	});

	
</script>
