
<style type="text/css">
	#example{
		font-size: 14px;
		font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;
	}

	#example thead tr th{
		padding-top: 15px;
		padding-bottom: 15px;
		background-color: #95a5a6;
		vertical-align: middle;
		/*font-size: 12px !important;*/
	}

	#example tbody tr td{
		padding-top: 10px;
		padding-bottom: 10px;
		/*font-size: 12px !important;*/
	}

	table.dataTable thead > tr > th.sorting_asc, table.dataTable thead > tr > th.sorting_desc, table.dataTable thead > tr > th.sorting, table.dataTable thead > tr > td.sorting_asc, table.dataTable thead > tr > td.sorting_desc, table.dataTable thead > tr > td.sorting{
		background-color: #95a5a6;
	}



	input[type="date"]{
		font-size: 12px;
		font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;
	}

	input[type="text"]{
		font-size: 12px;
		font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;
	}
	select{
		font-size: 12px;
		font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;
	}
	.select2-container--default .select2-selection--multiple .select2-selection__choice {
		background-color: #28a745;
		border-color: #3d9970;
	}

	span.select2-selection__choice__remove{
		color: black;
	}




	.swal2-popup {
		font-size: 1.3rem !important;
		font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;
	}


</style>
<div class="row konten">
	<div class="col-sm-12">

		<div class="nav-tabs-custom">

			<ul class="nav nav-tabs">
				<li class="active"><a href="#Inbox" data-toggle="tab">Inbox</a></li>
				<li class=""><a href="#Sent" data-toggle="tab">Sent</a></li>
			</ul>

			<div class="tab-content">

				<div class="active tab-pane" id="Inbox">

					<div class="table-responsive">

						
						<table style="width:100%;" class="table table-striped table-hover table-condensed table-bordered stripe row-border order-column" id="example_inbox">
							<thead>
								<tr>
									<th style="width: 100px;text-align: center;" data-orderable="false">#</th>
									<th style="width: 120px;">No Memo</th>
									<th style="width: 90px;">Tgl Memo</th>
									<th style="width: 140px;">Dari</th>
									<th width="250px">Perihal</th>
									<th style="width: 300px;">Remark</th>
									<th>Waktu Penerima Menyelesaikan Memo</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$LedgerNo = 0;
								foreach($MemoInbox as $row_Sent) : 
									$LedgerNo = $row_Sent->LedgerNo;
									?>
									<tr>
										<td style="text-align:center;">

											<?php if ($row_Sent->UserApproval == NULL || $row_Sent->UserApproval == ''): ?>
												<button class="btn btn-md btn-link text-black text-bold approve_selesai<?=$LedgerNo?>" onclick="aksi_approve('<?=$LedgerNo?>','selesai')" title="Selesai" ><i class="fa fa-check"></i></button>
											<?php else : ?>
												<button class="btn btn-md btn-link text-black text-bold approve_batal_selesai<?=$LedgerNo?>" onclick="aksi_approve('<?=$LedgerNo?>','batal_selesai')" title="Batal Selesai" ><i class="fa fa-undo"></i></button>
											<?php endif ?>

											<button class="btn btn-link btn-md text-black text-bold btn_view_inbox<?=$LedgerNo?>" onclick="view_memo('<?=$row_Sent->LedgerNo?>')" title="View" ><i class="fa fa-eye"></i></button>

											<button class="btn btn-default btn-sm btn_view_inbox_loading<?=$LedgerNo?>" style="display: none;" hidden><i class="fa fa-refresh"></i></button>

										</td>
										<td><?=$row_Sent->NoMemo?></td>
										<td><?=TanggalSaya($row_Sent->TglMemo)?></td>
										<td><?=$row_Sent->NmKaryawan?></td>
										<td><?=$row_Sent->Perihal?></td>
										<td><?=nl2br(substr($row_Sent->Remark,0,80).'.....')?></td>
										<td><?=tgl_indo_waktu($row_Sent->TimeApproval)?></td>
									</tr>

								<?php endforeach; ?>
							</tbody>
						</table>
					</div>

				</div>

				<div class="tab-pane" id="Sent">

					<div class="table-responsive">

						<table width="100%" border="0" style="margin-bottom:10px !important;">
							<tr>
								<td>
									
									<button class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal_new_memo">
										<i class="fa fa-plus-circle"> New Memo</i>
									</button>
								</td>
							</tr>
							
						</table>
						<hr style="border:1px solid gray;">
						<table style="width:100%;" class="table table-striped table-hover table-condensed table-bordered stripe row-border order-column" id="example">
							<thead>
								<tr>
									<th style="width: 100px;text-align: center;" data-orderable="false">#</th>
									<th style="width: 120px;">No Memo</th>
									<th style="width: 90px;">Tgl Memo</th>
									<th style="width: 140px;">Kepada</th>
									<th width="250px">Perihal</th>
									<th style="width: 300px;">Remark</th>
									<th >Waktu Penerima Menyelesaikan Memo</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$LedgerNo = 0;
								foreach($MemoSent as $row_Sent) : 
									$LedgerNo = $row_Sent->LedgerNo;
									?>
									<tr>
										<td style="text-align:center;white-space: nowrap;">
											<button class="btn btn-warning btn-sm btn_edit<?=$LedgerNo?>" onclick="form_edit_memo('<?=$row_Sent->LedgerNo?>')" ><i class="fa fa-edit"></i> EDIT</button>

											<button class="btn btn-danger btn-sm btn_delete<?=$LedgerNo?>" onclick="btn_delete('<?=$row_Sent->LedgerNo?>')" ><i class="fa fa-trash"></i> HAPUS</button>

											<a href="<?=site_url('FrmMemo/export_MemoPDF/'.$LedgerNo)?>" class="btn btn-sm btn-success" target="_blank	"><i class="fa fa-print"></i> PRINT</a>

											<button class="btn btn-default btn-sm btn_loading_edit<?=$LedgerNo?>" style="display: none;" hidden><i class="fa fa-refresh"></i></button>

										</td>
										<td><?=$row_Sent->NoMemo?></td>
										<td><?=TanggalSaya($row_Sent->TglMemo)?></td>
										<td><?=$row_Sent->NmKaryawan?></td>
										<td><?=$row_Sent->Perihal?></td>
										<td><?=nl2br(substr($row_Sent->Remark,0,80).'.....')?></td>
										<td><?=tgl_indo_waktu($row_Sent->TimeApproval)?></td>
									</tr>

								<?php endforeach; ?>
							</tbody>
						</table>
					</div>

				</div>

				
			</div>

		</div>

	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="modal_new_memo" style="font-size:12px;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-blue">
				<button class="close" data-dismiss="modal"><span class="text-white">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Entry Memo</h4>
			</div>
			<div class="modal-body">

				<form method="POST" action="<?=site_url('FrmMemo/simpan_memo')?>" >
					<div class="table-responsive">
						<table width="100%" border="0" style="border-collapse:collapse;">
							<tr>
								<td width="100px">Tanggal</td>
								<td style="width:1%;padding-left: 3px;padding-right: 3px;">:</td>
								<td width="350px" >
									<input type="date" name="TglMemo" class="form-control input-sm" value="<?=date('Y-m-d')?>" required>
								</td>

								<td style="text-align:right;" >Image Company&nbsp;&nbsp;&nbsp;</td>
								<td style="width:1%;padding-left: 3px;padding-right: 3px;">:</td>
								<td width="250px">
									<select name="ImageCompany" class="form-control input-sm select2" data-width="100%">
										<option value="">-- Pilih -- </option>
										<option value="MDH.jpg">MDH</option>
										<option value="KIP.jpg">KIP</option>
										<option value="KSB.jpg">KSB</option>
										<option value="KIB.jpg">KIB</option>
										<option value="KGS.jpg">KGS</option>
										<option value="KSC.jpg">KSC</option>
										<option value="REN.jpg">REN</option>
										<option value="AMK.jpg">AMK</option>
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
									<input type="hidden" name="Pembuat" class="form-control input-sm" value="<?=$NIK?>">
								</td>
								<td></td>
							</tr>
						</table>

						<table width="100%" border="0" style="border-collapse:collapse;padding-top: 10px;margin-top: 10px;">
							<tr>
								<td width="100px">Kepada</td>
								<td style="width:1%;padding-left: 3px;padding-right: 3px;">:</td>
								<td width="350px" >
									<!-- <select name="Kepada" data-width="100%" class="form-control input-sm select2" required>
										<option value="0">-- Pilih -- </option>
										<?php foreach ($ListKaryawan as $rowkaryawan): ?>
											<option value="<?=$rowkaryawan->NIK?>"><?=$rowkaryawan->Nama?></option>
										<?php endforeach ?>
									</select> -->

									<select name="Kepada[]" data-width="100%" class="form-control input-sm select2" multiple="multiple" required>
										<option value="0">-- Pilih -- </option>
										<?php foreach ($ListKaryawan as $rowkaryawan): ?>
											<option value="<?=$rowkaryawan->NIK?>"><?=$rowkaryawan->Nama?></option>
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
									<input type="text" name="Perihal" class="form-control input-sm" placeholder="Isi Disini">
								</td>
								<td></td>
							</tr>
						</table>

						<table width="100%" border="0" style="border-collapse:collapse;margin-top: 10px;">
							<tr>
								<td width="100px" style="vertical-align: top;">Remark</td>
								<td style="width:1%;padding-left: 3px;padding-right: 3px;">:</td>
								<td width="350px" >
									<textarea name="Remark" class="form-control input-sm" rows="10" cols="30" placeholder="Isi Disini">Dengan hormat,</textarea>
								</td>
								<td></td>
							</tr>
						</table>

						<table width="100%" border="0" style="border-collapse:collapse;margin-top: 10px;">
							<tr>
								<td width="100px">Tembusan</td>
								<td style="width:1%;padding-left: 3px;padding-right: 3px;">:</td>
								<td width="350px" >
									<select class="form-control input-sm select2" data-width="100%" name="Tembusan[]" multiple="multiple">
										<?php foreach ($ListKaryawan as $rowkaryawan): ?>
											<?php if ($rowkaryawan->Golongan >= '3'): ?>
												<option value="<?=$rowkaryawan->NIK?>"><?=$rowkaryawan->Nama?></option>

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
									<input type="hidden" name="NIK" value="<?=$NIK?>">
									<button type="submit" class="btn btn-primary btn-md ">Simpan</button>
								</td>
							</tr>

						</table>



					</div>
				</form>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="modal_edit_memo" style="font-size:12px;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-blue">
				<button class="close" data-dismiss="modal"><span class="text-white">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Form Update Memo</h4>
			</div>
			<div class="modal-body" id="modal_body_memo">

				
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_view" style="font-size:12px;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-blue">
				<button class="close" data-dismiss="modal"><span class="text-white">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">View Memo</h4>
			</div>
			<div class="modal-body" id="body_view">

				
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?=base_url('assets/swal2.js')?>"></script>
<script type="text/javascript">
	$(function(){

		$('.select2').select2();
		$('.js-example-basic-multiple').select2();

		
		$('#example').DataTable();
		$('#example_inbox').DataTable();

	});

	function form_edit_memo(LedgerNo){
		
		jQuery.ajax({
			url: "<?=site_url('FrmMemo/tampil_form_edit_memo')?>",
			type: 'POST',
			dataType: 'html',
			data: {LedgerNo: LedgerNo},
			beforeSend : function(){
				$('.btn_loading_edit'+LedgerNo).show();
				$('.btn_edit'+LedgerNo).hide();
				$('#modal_body_memo').html('');
			},
			complete: function(xhr, textStatus) {
				$('.btn_loading_edit'+LedgerNo).hide();
				$('.btn_edit'+LedgerNo).show();
			},
			success: function(data, textStatus, xhr) {
				$('#modal_body_memo').html(data);
				$('#modal_edit_memo').modal('show');
			},
			error: function(xhr, textStatus, errorThrown) {
				Swal.fire(
					'Error!',
					'',
					'error'
					)
			}
		});
		
	}

	function refresh_halaman(){
		window.location.href="<?=site_url('Kepegawaian/view_profile/Memo')?>";
	}

	function btn_delete(LedgerNo){
		
		jQuery.ajax({
			url: "<?=site_url('FrmMemo/cek_status_memo')?>",
			type: 'POST',
			dataType: 'html',
			data: {LedgerNo: LedgerNo},
			beforeSend : function(){
				$('.btn_loading_edit'+LedgerNo).show();
				$('.btn_delete'+LedgerNo).hide();
			},
			complete: function(xhr, textStatus) {
				$('.btn_loading_edit'+LedgerNo).hide();
				$('.btn_delete'+LedgerNo).show();
			},
			success: function(data, textStatus, xhr) {
				// alert(data);

				if (data == 'kosong') {
					Swal.fire('Error!','Memo tidak ditemukan','error');
					return false;
				}

				if (data == 'user_approval_ada') {
					Swal.fire('Error!','Tidak bisa delete, memo sudah diselesaikan oleh penerima.','error');
					return false;
				}


				if (data == 'berhasil') {
					Swal.fire('Sukses!','Memo telah terhapus','success');
					setTimeout(refresh_halaman(), 2000);
					return false;
				}

				if (data == 'gagal') {
					Swal.fire('Error!','Memo gagal terhapus','error');
					return false;
				}
				
			},
			error: function(xhr, textStatus, errorThrown) {
				Swal.fire(
					'Error!',
					'',
					'error'
					)
			}
		});
		
	}

	function view_memo(LedgerNo){
		
		jQuery.ajax({
			url: "<?=site_url('FrmMemo/tampil_form_edit_memo')?>",
			type: 'POST',
			dataType: 'html',
			data: {LedgerNo: LedgerNo,'view_memo' : 'view_memo'},
			beforeSend : function(){
				$('.btn_view_inbox_loading'+LedgerNo).show();
				$('.btn_view_inbox'+LedgerNo).hide();
				$('#body_view').html('');
			},
			complete: function(xhr, textStatus) {
				$('.btn_view_inbox_loading'+LedgerNo).hide();
				$('.btn_view_inbox'+LedgerNo).show();
			},
			success: function(data, textStatus, xhr) {
				$('#body_view').html(data);
				$('#modal_view').modal('show');
			},
			error: function(xhr, textStatus, errorThrown) {
				Swal.fire(
					'Error!',
					'',
					'error'
					)
			}
		});
		
	}

	function aksi_approve(LedgerNo='',flag=''){
		if (LedgerNo == '' || flag == '' ) {
			Swal.fire('Error!','','error');
			return false;
		}

		var nama_flag = (flag == 'selesai') ? 'Selesai' : 'Batal Selesai';

		Swal.fire({
			title: 'Konfirmasi '+nama_flag+'?',
			showDenyButton: false,
			showCancelButton: true,
			confirmButtonText: 'Yes',
			denyButtonText: 'No',

		}).then((result) => {
			if (result.isConfirmed) {

				jQuery.ajax({
					url: "<?=site_url('FrmMemo/aksi_approve')?>",
					type: 'POST',
					dataType: 'html',
					data: {LedgerNo: LedgerNo, flag : flag},
					beforeSend : function(){
						$('.btn_view_inbox_loading'+LedgerNo).show();

						if (flag=='batal_selesai') {
							$('.approve_batal_selesai'+LedgerNo).hide();
						}else{
							$('.approve_selesai'+LedgerNo).hide();
						}
					},
					complete: function(xhr, textStatus) {
						$('.btn_view_inbox_loading'+LedgerNo).hide();

						if (flag=='batal_selesai') {
							$('.approve_batal_selesai'+LedgerNo).show();
						}else{
							$('.approve_selesai'+LedgerNo).show();
						}
					},
					success: function(data, textStatus, xhr) {

						refresh_halaman();

					},
					error: function(xhr, textStatus, errorThrown) {
						Swal.fire(
							'Error!',
							'',
							'error'
							)
					}
				});

			} 
		})


		
	}

</script>