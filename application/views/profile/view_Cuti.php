 <style type="text/css">
 	#tbl_file {
 		font-size: 14px;
 		font-family: TimesNewRoman, "Times New Roman", Times, Baskerville, Georgia, serif;
 		white-space: nowrap;
 		width: 50% !important;
 	}

 	#tbl_file thead th {
 		text-align: center;
 	}
 </style>

 <div class="row">
 	<div class="col-sm-12">

 		<div class="table-responsive">

 			<table width="100%" style="font-size: 12px;">
 				<tr>
 					<td style="padding-bottom:20px;text-align: left;">Sisa Cuti</td>
 					<td style="text-align: left;">:</td>
 					<td style="padding-bottom:20px;text-align: left;">
					     <textarea rows="3" readonly><?= $TtlCuti['TtlCuti'] ?></textarea>
					</td>
 					<td style="text-align: right;">
 						<button type="button" class="btn btn-sm btn-success btn_tambah_file" data-NIK="<?= $NIK ?>">Tambah Pengajuan Cuti <i class="fa fa-plus"></i></button>
 					</td>
 				</tr>
 			</table>

 			<table class="table table-bordered table-striped table-hover table-condensed" width="100%" id="tbl_file">
 				<thead>
 					<tr>
 						<th rowspan="2">No.</th>
 						<th rowspan="2">Dari Tanggal</th>
 						<th rowspan="2">Sampai Tanggal</th>
 						<th rowspan="2">Tipe Request</th>
 						<th rowspan="2">Karyawan Pengganti</th>
 						<th colspan="3">Atasan</th>
 						<th colspan="3">SDM</th>
 					</tr>
 					<tr>
 						<th>Status</th>
 						<th>Approve/Reject Oleh</th>
 						<th>Waktu Approve / Reject </th>
 						<th>Status</th>
 						<th>Approve/Reject Oleh</th>
 						<th>Waktu Approve / Reject </th>
 					</tr>
 				</thead>
 				<tbody>
 					<?php $nomor = 1;
						foreach ($data_file as $mydata_file) : ?>
 						<tr>
 							<td><?= $nomor++; ?></td>
 							<td><?= $mydata_file['DrTgl']; ?></td>
 							<td><?= $mydata_file['SpTgl']; ?></td>
 							<td><?= $mydata_file['Status']; ?></td>
 							<td><?= $mydata_file['KarPeng']; ?></td>
 							<td><?= $mydata_file['HeadAction']; ?></td>
 							<td><?= $mydata_file['HeadUserAction']; ?></td>
 							<td><?= $mydata_file['HeadTimeAction']; ?></td>
 							<td><?= $mydata_file['SDMAction']; ?></td>
 							<td><?= $mydata_file['SDMUserAction']; ?></td>
 							<td><?= $mydata_file['SDMTimeAction']; ?></td>

 						</tr>
 					<?php endforeach; ?>
 				</tbody>
 			</table>

 		</div>

 	</div>
 </div>


 <div class="modal fade" id="modal_file">
 	<div class="modal-dialog modal-lg" role="document">>
 		<div class="modal-content">


 			<div class="modal-body" id="modal_file_body">


 			</div>
 		</div>

 	</div>

 </div>



 <script type="text/javascript">
 	$(function() {




 		$('.btn_tambah_file').click(function() {

 			var NIK = $(this).attr('data-NIK');



 			jQuery.ajax({
 				url: "<?= site_url('Kepegawaian/Form_TambahCuti') ?>",
 				type: 'POST',
 				dataType: 'html',
 				data: {
 					'NIK': NIK
 				},
 				beforeSend: function() {
 					$('#modal_keluarga_body').html('');
 					$('.loadingku').show();
 				},
 				complete: function(xhr, textStatus) {
 					$('.loadingku').hide();
 				},
 				success: function(data, textStatus, xhr) {
 					// alert('sucsss');
 					$('#modal_keluarga').modal('show');
 					$('#modal_keluarga_body').html(data);
 				},
 				error: function(xhr, textStatus, errorThrown) {
 					$.toast({
 						text: 'Terjadi Kesalahan',
 						position: 'bottom-right',
 						stack: false,
 						hideAfter: 1000,
 						icon: 'error',

 					});
 					return false;
 				}
 			});


 		});


 		$('.lihat_file').click(function() {

 			var LedgerNo = $(this).attr('data-LedgerNo');
 			var NIK = $(this).attr('data-NIK');
 			var TipeFile = $(this).attr('data-TipeFile');
 			var NamaFile = $(this).attr('data-NamaFile');

 			jQuery.ajax({
 				url: "<?= site_url('Kepegawaian/lihat_file_full') ?>",
 				type: 'POST',
 				dataType: 'HTML',
 				data: {
 					'LedgerNo': LedgerNo,
 					'NIK': NIK,
 					'TipeFile': TipeFile,
 					'NamaFile': NamaFile
 				},
 				beforeSend: function() {
 					$('#modal_file_body').html('');
 					$('.loadingku').show();
 				},
 				complete: function(xhr, textStatus) {
 					$('.loadingku').hide();
 				},
 				success: function(data, textStatus, xhr) {
 					// alert('sucsss');
 					$('#modal_file').modal('show');
 					$('#modal_file_body').html(data);
 				},
 				error: function(xhr, textStatus, errorThrown) {
 					$.toast({
 						text: 'Terjadi Kesalahan',
 						position: 'bottom-right',
 						stack: false,
 						hideAfter: 1000,
 						icon: 'error',

 					});
 					return false;
 				}
 			});


 		});


 		$('.hapus_file').click(function() {

 			var LedgerNo = $(this).attr('data-LedgerNo');
 			var NIK = $(this).attr('data-NIK');
 			var TipeFile = $(this).attr('data-TipeFile');
 			var NamaFile = $(this).attr('data-NamaFile');

 			// var hapus_file = $()

 			// alert('hapus_file_'+LedgerNo)

 			// $('#hapus_file_'+LedgerNo).attr('disabled',true);
 			// return false;


 			Swal.fire({
 				title: 'Anda Yakin?',
 				text: "data tidak dapat dikembalikan!",
 				icon: 'warning',
 				showCancelButton: true,
 				confirmButtonColor: '#3085d6',
 				cancelButtonColor: '#d33',
 				confirmButtonText: 'Ya, hapus!',
 				cancelButtonText: 'Batal'
 			}).then((result) => {

 				if (result.value) {

 					jQuery.ajax({
 						url: "<?= site_url('Kepegawaian/hapus_file') ?>",
 						type: 'POST',
 						dataType: 'html',
 						data: {
 							'LedgerNo': LedgerNo,
 							'NIK': NIK,
 							'TipeFile': TipeFile,
 							'NamaFile': NamaFile
 						},
 						cache: false,
 						beforeSend: function() {
 							$('.loadingku').show();
 							$('#hapus_file_' + LedgerNo).attr('disabled', true);
 						},
 						complete: function(xhr, textStatus) {
 							$('.loadingku').hide();
 							$('#hapus_file_' + LedgerNo).attr('disabled', false);
 							pilihan_menu();
 						},
 						success: function(data, textStatus, xhr) {
 							// alert(data);
 							// return false;

 							if (data == 'berhasil') {
 								$.toast({
 									text: 'Berhasil menghapus data',
 									position: 'top-right',
 									stack: false,
 									hideAfter: 2000,
 									icon: 'success',

 								});
 								return false;
 							} else {
 								$.toast({
 									text: 'gagal menghapus data',
 									position: 'top-right',
 									stack: false,
 									hideAfter: 2000,
 									icon: 'error',

 								});
 								return false;
 							}

 						},
 						error: function(xhr, textStatus, errorThrown) {
 							$.toast({
 								text: 'Terjadi Kesalahan',
 								position: 'bottom-right',
 								stack: false,
 								hideAfter: 1000,
 								icon: 'error',

 							});
 							return false;
 						}
 					});

 				}
 			});


 		});




 	});
 </script>
