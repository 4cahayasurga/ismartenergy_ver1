<style type="text/css">
	#tbl_cuti_saya {
		font-size: 12px;
		font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;
	}

	#tbl_cuti_saya thead tr th {
		padding-top: 5px;
		padding-bottom: 5px;
	}
	#tbl_cuti_saya tbody tr td {
		padding-top: 5px;
		padding-bottom: 5px;
	}

	.jarak_atas{
		margin-top: 5px !important;
	}

	.swal2-popup {
		font-size: 1.2rem !important;
		font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;
	}


	/*#tbl_cuti_saya thead tr th{
		font-size: 10px !important;
		font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;
	}*/

	
</style>

<div class="row">
	<div class="col-md-12">
		
		<div class="box">
			<div class="box-header">
				<div class="box-title">Request Cuti/Kehadiran</div>
				<div class="box-tools">
					<button type="button" class="btn btn-md bg-orange" id="new_request" onclick="new_request()">New Request Cuti <i class="fa fa-plus"></i> </button>
					<button type="button" class="btn btn-md btn-default" id="loading_request" style="display:none;" disabled >New Request</button>
				</div>
			</div>
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover table-condensed" id="tbl_cuti_saya" style="white-space: nowrap;">
						<thead style="font-size: 12px !important;">
							<tr >
								<th rowspan="2" style="vertical-align:middle;">No.</th>
								<th rowspan="2" style="vertical-align:middle;" ></th>
								<!-- <th rowspan="2" style="vertical-align:middle;" >NIK</th> -->
								<!-- <th rowspan="2" style="vertical-align:middle;" >Nama</th> -->
								<th rowspan="2" style="vertical-align:middle;" >Dari Tanggal</th>
								<th rowspan="2" style="vertical-align:middle;" >Sampai Tanggal</th>
								<th rowspan="2" style="vertical-align:middle;" >Tipe Request</th>
								<th rowspan="2" style="vertical-align:middle;" >Karyawan Pengganti</th>
								<th rowspan="2" style="vertical-align:middle;" >Deskripsi</th>
								<th class="text-center bg-gray disabled color-palette"colspan="3" style="vertical-align:middle;text-align: center;background-color:#d2d6de;" >Atasan</th>
								<th colspan="3" class="text-center bg-blue disabled color-palette" style="vertical-align:middle;text-align: center;" >SDM</th>
							</tr>
							<tr>
								<th>Status</th>
								<th>Approve/Reject By</th>
								<th>Time Approve/Reject</th>
								<th>Status</th>
								<th>Approve/Reject By</th>
								<th>Time Approve/Reject</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$no = 1;
							foreach ($grid_cuti_karyawan as $row_cuti): ?>
								<tr >
									<td ><?=$no++;?></td>
									<td class="text-center">
										<button class="btn btn-warning btn-sm btn_edit_cuti<?=$row_cuti->LedgerNo?>" onclick="edit_request('<?=$row_cuti->LedgerNo?>')" title="Edit Request Cuti" ><i class="fa fa-pencil"></i></button>

										<button class="btn btn-danger btn-sm btn_hapus_cuti<?=$row_cuti->LedgerNo?>" onclick="hapus_cuti_request('<?=$row_cuti->LedgerNo?>')" title="Hapus Request Cuti" ><i class="fa fa-trash"></i></button>

										<button class="btn btn-default btn-sm loading_cuti<?=$row_cuti->LedgerNo?>" style="display: none;" disabled   ><i class="fa fa-pencil"></i></button>

									</td>
									<td><?=TanggalSaya($row_cuti->DrTgl)?></td>
									<td><?=TanggalSaya($row_cuti->SpTgl)?></td>
									<td><?=$row_cuti->Status?></td>
									<td><?=$row_cuti->KarPeng?></td>
									<td><?=$row_cuti->Remark?></td>
									<td><?=$row_cuti->HeadAction?></td>
									<td><?=$row_cuti->HeadUserAction?></td>
									<td><?=$row_cuti->HeadTimeAction?></td>
									<td><?=$row_cuti->SDMAction?></td>
									<td><?=$row_cuti->SDMUserAction?></td>
									<td><?=$row_cuti->SDMTimeAction?></td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

	</div>
</div>

<div class="modal modal-default fade" id="modal_entry_cuti">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-blue">
				<h4>Entry Request Cuti</h4>
			</div>
			<div class="modal-body">
				<form method="POST" action="<?=site_url('FrmCuti/add_cuti_profile')?>" >
					<div class="table-responsive">


						<table width="100%" border="0">
							<tr>
								<td style="width:15%;">Tipe Request</td>
								<td style="width:1%;padding-left:5px:padding-right:5px;text-align: center;" >:</td>
								<td>
									<select name="TipeRequest" id="TipeRequest" class="form-control" required>
										<option value="">--- Pilih --</option>
										<!-- <option value="Masuk">Masuk</option> -->
										<option value="Cuti">Cuti</option>
										<!-- <option value="Cuti 1/2 Hari">Cuti 1/2 Hari</option> -->
										<!-- <option value="Dinas Proyek">Dinas Proyek</option> -->
										<option value="Sakit">Sakit</option>
										<option value="Cuti Melahirkan">Cuti Melahirkan</option>
										<option value="Cuti Menikah">Cuti Menikah</option>
									</select>
								</td>
								<!-- <td></td> -->
							</tr>
						</table>

						<table width="100%" border="0" class="jarak_atas" >
							<tr>
								<td style="width:15%;">Periode</td>
								<td style="width:1%;padding-left:5px:padding-right:5px;text-align: center;" >:</td>
								<td >
									<input type="date" name="DrTgl" id="DrTgl" class="form-control" required>
								</td>
								<td style="width:3%;padding-left:10px:padding-right:10px;text-align: center;" >s.d</td>
								<td >
									<input type="date" name="SpTgl" id="SpTgl" class="form-control" required>
								</td>
							</tr>
						</table>

						<table width="100%" border="0" class="jarak_atas">
							<tr>
								<td style="width:15%;">Deskripsi</td>
								<td style="width:1%;padding-left:5px:padding-right:5px;text-align: center;" >:</td>
								<td >
									<textarea name="Deskripsi" id="Deskripsi" class="form-control" placeholder="Isi Disini"></textarea>
								</td>
							</tr>
						</table>

						<table width="100%" border="0" class="jarak_atas">
							<tr>
								<td style="width:15%;">Karyawan Pengganti</td>
								<td style="width:1%;padding-left:5px:padding-right:5px;text-align: center;" >:</td>
								<td >
									<select name="KaryawanPengganti" id="KaryawanPengganti" class="form-control select2" style="font-size: 12px !important;">
										<option value="">-- Pilih --</option>
										<?php foreach($dataKaryawanPengganti as $row) : ?>
											<option value="<?=$row->Nama?>"><?=$row->Nama?></option>
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

									<input type="hidden" name="NIK" value="<?=$NIK?>">
									<button type="submit" class="btn btn-primary btn-md" id="btn_save">Save</button>

								</td>
							</tr>
						</table>


					</div>
				</form>
			</div>
		</div>
	</div>
</div>


<div class="modal modal-default fade" id="modal_saya">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body" id="modal_body_saya">

			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){

		$('.select2').select2({
			'width':'100%'
		});

		var table = $('#tbl_cuti_saya').DataTable( {
			scrollY			: "500px",
			scrollX			: true,
			scrollCollapse	: true,
			paging			: false,
			fixedColumns	: {
				left: 1,
				right: 1
			}
		});


		$('#new_request').click(function(){
			bersihkan_isi_form();
			$('#modal_entry_cuti').modal('show');
		});


	});

	function page_segarkan(){
		window.location.href="<?=site_url('Kepegawaian/view_profile/Cuti')?>"
	}

	function hapus_cuti_request(LedgerNo){


		if (confirm('Anda yakin ingin menghapus data ini?')) {

			jQuery.ajax({
				url: '<?=site_url('FrmCuti/hapus_cuti_request')?>',
				type: 'POST',
				dataType: 'JSON',
				cache : false,
				data: {LedgerNo : LedgerNo},
				beforeSend : function(){
					$('.btn_hapus_cuti'+LedgerNo).hide();
					$('.loading_cuti'+LedgerNo).show();
				},
				complete : function(){
					$('.btn_hapus_cuti'+LedgerNo).show();
					$('.loading_cuti'+LedgerNo).hide();
				},
				success: function(data, textStatus, xhr) {


					if (data.flag == 'berhasil') {
						swal.fire('Sukses!','data berhasil dihapus','success');
						setTimeout("page_segarkan()",1500);
						return false;
					}else if(data.flag == 'gagal'){
						swal.fire('Error!','data gagal dihapus','error');
						return false;
					}else{
						swal.fire('Info!',data.pesan,'info');
						return false;
					}

				},
				error: function(xhr, textStatus, errorThrown) {
					swal.fire('Error!','Terjadi Kesalahan','error');
					return false;
				}
			});

		}

	}

	function bersihkan_isi_form(){
		$("select[name=TipeRequest]").val('');
		$("input[name=DrTgl]").val('');
		$("input[name=SpTgl]").val('');

		$("textarea[name=Deskripsi]").val('');
		$("select[name=KaryawanPengganti]").val('');

	}

	function edit_request(LedgerNo=''){
		
		$('#modal_body_saya').html('');

		jQuery.ajax({
			url: '<?=site_url('FrmCuti/tampilkan_form_edit_cuti')?>',
			type: 'POST',
			dataType: 'html',
			cache : false,
			data: {LedgerNo : LedgerNo},
			beforeSend : function(){
				$('.btn_edit_cuti'+LedgerNo).hide();
				$('.loading_cuti'+LedgerNo).show();
			},
			success: function(data, textStatus, xhr) {
				$('.btn_edit_cuti'+LedgerNo).show();
				$('.loading_cuti'+LedgerNo).hide();

				$('#modal_body_saya').html(data);
				$('#modal_saya').modal('show');
			},
			error: function(xhr, textStatus, errorThrown) {
				swal.fire('Error!','Terjadi Kesalahan','error');
				return false;
			}
		});
	}




</script>