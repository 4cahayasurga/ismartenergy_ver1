<style type="text/css">
	.jarak_atas{
		margin-top: 5px !important;
	}
</style>
<div class="row">
	<div class="col-md-12">
		

		<form id="FrmSimpan">
			<div class="table-responsive">


				<table width="100%" border="0">
					<tr>
						<td style="width:15%;">Tipe Request</td>
						<td style="width:1%;padding-left:5px:padding-right:5px;text-align: center;" >:</td>
						<td style="width:30%;">
							<select name="TipeRequest" id="TipeRequest" class="form-control" required>
								<option value="">--- Pilih --</option>
								<!-- <option value="Masuk">Masuk</option> -->
								<option value="Cuti">Cuti</option>
								<!-- <option value="Cuti 1/2 Hari">Cuti 1/2 Hari</option> -->
								<option value="Dinas Proyek">Dinas Proyek</option>
								<option value="Sakit">Sakit</option>
								<option value="Cuti Melahirkan">Cuti Melahirkan</option>
								<option value="Cuti Menikah">Cuti Menikah</option>
							</select>
						</td>
						<td></td>
					</tr>
				</table>

				<table width="100%" border="0" class="jarak_atas" >
					<tr>
						<td style="width:15%;">Periode</td>
						<td style="width:1%;padding-left:5px:padding-right:5px;text-align: center;" >:</td>
						<td style="width:30%;">
							<input type="date" name="DrTgl" id="DrTgl" class="form-control" required>
						</td>
						<td style="width:3%;padding-left:10px:padding-right:10px;text-align: center;" >s.d</td>
						<td style="width:30%;">
							<input type="date" name="SpTgl" id="SpTgl" class="form-control" required>
						</td>
						<td></td>
					</tr>
				</table>

				<table width="100%" border="0" class="jarak_atas">
					<tr>
						<td style="width:15%;">Deskripsi</td>
						<td style="width:1%;padding-left:5px:padding-right:5px;text-align: center;" >:</td>
						<td style="width:30%;">
							<textarea name="Deskripsi" id="Deskripsi" class="form-control" placeholder="Isi Disini"></textarea>
						</td>
						<td></td>
					</tr>
				</table>

				<table width="100%" border="0" class="jarak_atas">
					<tr>
						<td style="width:15%;">Karyawan Pengganti</td>
						<td style="width:1%;padding-left:5px:padding-right:5px;text-align: center;" >:</td>
						<td style="width:30%;">
							<select name="KaryawanPengganti" id="KaryawanPengganti" class="form-control select2" style="font-size: 12px !important;">
								<option value="">-- Pilih --</option>
								<?php foreach($dataKaryawanPengganti as $row) : ?>
									<option value="<?=$row->Nama?>"><?=$row->Nama?></option>
								<?php endforeach; ?>
							</select>
						</td>
						<td></td>
					</tr>
				</table>

				<table width="100%" border="0" class="jarak_atas" style="border-top:1px solid silver;">
					<tr>
						<td style="width:15%;padding-top:10px;"><button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button></td>
						<td style="width:1%;padding-left:5px:padding-right:5px;text-align: center;" ></td>
						<td style="width:30%;text-align: right;">

							<button type="submit" class="btn btn-primary btn-md" id="btn_save">Save</button>

						</td>
						<!-- <td></td> -->
					</tr>
				</table>


			</div>
		</form>

	</div>
</div>

<script type="text/javascript">
	$(function(){
		$('.select2').select2({
			width:'100%'
		});

		$('#FrmSimpan').submit(function(e){
			e.preventDefault();
			
			jQuery.ajax({
				url: '<?=site_url("FrmCuti/aksi_entry_cuti")?>',
				type: 'POST',
				dataType: 'html',
				data: $(this).serialize(),
				success: function(data, textStatus, xhr) {
					alert(data);
				},
				error: function(xhr, textStatus, errorThrown) {
					alert('error');
				}
			});
			

			
		});
	})
</script>