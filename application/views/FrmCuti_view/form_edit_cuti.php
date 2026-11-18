<style type="text/css">
	.jarak_atas{
		margin-top: 5px !important;
	}
</style>
<div class="row">
	<div class="col-md-12">
		 
		<form id="FrmEditCuti">
			<div class="table-responsive">

				<table width="100%" border="0">
					<tr>
						<td class="bg-blue"><h4>&nbsp;Entry Request Cuti</h4></td>
					</tr>
				</table>
				<p></p>
				<table width="100%" border="0">
					<tr>
						<td style="width:15%;">Tipe Request</td>
						<td style="width:1%;padding-left:5px:padding-right:5px;text-align: center;" >:</td>
						<td >
							<select name="TipeRequest" id="TipeRequest" class="form-control" required>
								<option value="">--- Pilih --</option>
								<!-- <option value="Masuk">Masuk</option> -->
								<option value="Cuti" <?=($row_cuti->Status == 'Cuti') ? 'selected' : ''?> >Cuti</option>
								<!-- <option value="Cuti 1/2 Hari">Cuti 1/2 Hari</option> -->
								<option value="Dinas Proyek" <?=($row_cuti->Status == 'Dinas Proyek') ? 'selected' : ''?> >Dinas Proyek</option>
								<option value="Sakit" <?=($row_cuti->Status == 'Sakit') ? 'selected' : ''?> >Sakit</option>
								<option value="Cuti Melahirkan" <?=($row_cuti->Status == 'Cuti Melahirkan') ? 'selected' : ''?> >Cuti Melahirkan</option>
								<option value="Cuti Menikah" <?=($row_cuti->Status == 'Cuti Menikah') ? 'selected' : ''?> >Cuti Menikah</option>
							</select>
						</td>
					</tr>
				</table>

				<table width="100%" border="0" class="jarak_atas" >
					<tr>
						<td style="width:15%;">Periode</td>
						<td style="width:1%;padding-left:5px:padding-right:5px;text-align: center;" >:</td>
						<td >
							<input type="date" name="DrTgl" id="DrTgl" class="form-control" value="<?=$row_cuti->DrTgl?>" required>
						</td>
						<td style="width:3%;padding-left:10px:padding-right:10px;text-align: center;" >s.d</td>
						<td>
							<input type="date" name="SpTgl" id="SpTgl" class="form-control" value="<?=$row_cuti->SpTgl?>" required>
						</td>
					</tr>
				</table>

				<table width="100%" border="0" class="jarak_atas">
					<tr>
						<td style="width:15%;">Deskripsi</td>
						<td style="width:1%;padding-left:5px:padding-right:5px;text-align: center;" >:</td>
						<td >
							<textarea name="Deskripsi" id="Deskripsi" class="form-control" placeholder="Isi Disini"><?=$row_cuti->Remark?></textarea>
						</td>
					</tr>
				</table>

				<table width="100%" border="0" class="jarak_atas">
					<tr>
						<td style="width:15%;">Karyawan Pengganti</td>
						<td style="width:1%;padding-left:5px:padding-right:5px;text-align: center;" >:</td>
						<td>
							<select name="KaryawanPengganti" id="KaryawanPengganti" class="form-control select2" style="font-size: 12px !important;">
								<option value="">-- Pilih --</option>
								<?php foreach($dataKaryawanPengganti as $row) : ?>
									<option value="<?=$row->Nama?>" <?=($row_cuti->KarPeng == $row->Nama ) ? 'selected' : '' ?> ><?=$row->Nama?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
				</table>

				<table width="100%" border="0" class="jarak_atas" style="border-top:1px solid silver;">
					<tr>
						<td style="width:15%;padding-top:10px;"><button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button></td>
						<td style="width:1%;padding-left:5px:padding-right:5px;text-align: center;" ></td>
						<td style="text-align: right;">
							<input type="hidden" name="LedgerNo" value="<?=$row_cuti->LedgerNo?>">
							<button type="submit" class="btn btn-primary btn-md" id="btn_save" data-LedgerNo="<?=$row_cuti->LedgerNo?>">Update</button>

						</td>
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

		$('#FrmEditCuti').submit(function(e){
			e.preventDefault();

			jQuery.ajax({
				url: '<?=site_url('FrmCuti/aksi_edit_cuti')?>',
				type: 'POST',
				dataType: 'html',
				cache : false,
				data: $(this).serialize(),
				beforeSend : function(){
					$(this).attr('disabled',true).text('Loading...');
				},
				success: function(data, textStatus, xhr) {
					$(this).attr('disabled',false).text('Update');

					$('#modal_body_saya').html('');
					$('#modal_saya').modal('hide');

					if (data == 'berhasil') {

						swal.fire('Success','Data telah di update','success');
						setTimeout("halaman_url()",1500);

					}else if(data == 'tidak_pending'){
						swal.fire('Info!','Data tidak dapat diubah','info');

					}else{
						swal.fire('Error!','Gagal mengupdate data','error');
						
					}

					
				},
				error: function(xhr, textStatus, errorThrown) {
					swal.fire('Error!','Terjadi Kesalahan','error');
					return false;
				}
			});
		});
	});
	function halaman_url(){
		window.location.href="<?=site_url('Kepegawaian/view_profile/Cuti')?>"
	}
</script>