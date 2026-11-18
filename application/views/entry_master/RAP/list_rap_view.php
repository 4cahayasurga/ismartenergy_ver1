<style type="text/css">
	#mytable {
		font-family: Arial, Helvetica, sans-serif;
	}

	#mytable th {
		padding-top: 10px;
		padding-bottom: 10px;
		white-space: nowrap;
		font-size: 12px;
	}

	#mytable td {
		padding-top: 10px;
		padding-bottom: 10px;
		font-size: 12px;
		/*white-space: nowrap;*/
	}
</style>

<div class="box box-primary">

	<div class="box-header">
		<a class="btn btn-sm btn-danger" target="_blank" href="<?= site_url('Entry_master/ENT_RAP/mypdf_rap/' . $JobNo . '/' . $Alokasi . '/' . $Versi_baru . '/' . $Versi_tipe) ?>">Print <i class="fa fa-print"></i></a>
		<!-- <a class="btn btn-sm btn-info btn_add_new_rap" data-JobNo="<?= $JobNo ?>" data-Alokasi="<?= $Alokasi ?>" data-Versi="<?= $Versi ?>" data-Versi_tipe="<?= $Versi_tipe ?>">Tambah Data <i class="fa fa-plus"></i></a> -->

		<a class="btn btn-sm bg-purple btn_tambah_versi" data-JobNo="<?= $JobNo ?>" data-Alokasi="<?= $Alokasi ?>" data-Versi="<?= $Versi ?>" data-Versi_tipe="<?= $Versi_tipe ?>">Tambah Versi <i class="fa fa-plus"></i></a>


		<button type="button" class="btn btn-md btn-secondary" onclick="segarkan_data()">Refresh Table <i class="fa fa-refresh"></i></button>

		<div class="pull-right">
			<a class="btn btn-sm btn-success btn_export_excel" data-JobNo="<?= $JobNo ?>" data-Alokasi="<?= $Alokasi ?>" data-Versi="<?= $Versi ?>" data-Versi_tipe="<?= $Versi_tipe ?>">IMPORT EXCEL </a>
		</div>


	</div>
	<div class="box-body">

		<div class="table-responsive">



			<table class="table table-hover table-striped table-condensed table-bordered" id="mytable" data-JobNo="<?= $JobNo ?>" data-Alokasi="<?= $Alokasi ?>" data-jumlah_baris="<?= $jumlah_baris ?>">
				<thead class="atur_th">
					<tr>
						<th colspan="5" style="border:1px solid black;"></th>
						<th colspan="5" style="border:1px solid black;text-align: center;">RAP</th>
						<th colspan="3" style="border:1px solid black;text-align: center;">RAB</th>
						<th style="border:1px solid black;text-align: center;" colspan="3"></th>
					</tr>
					<tr style="background-color: #95a5a6;">
						<th style="width:100px;text-align: center;">#</th>
						<th>Kode RAP</th>
						<th>Uraian</th>
						<th>Sat</th>
						<th>Vol</th>

						<!-- //RAP -->
						<th style="background-color:#3498db;">Hrg Satuan (Rp)</th>
						<th style="background-color:#3498db;">PPN Satuan (Rp)</th>
						<th style="background-color:#3498db;">Jumlah Harga (Rp)</th>
						<th style="background-color:#3498db;">Jumlah Harga PPN (Rp)</th>
						<th style="background-color:#3498db;">Bobot(%)</th>

						<!-- //RAB -->
						<th style="background-color: #2ecc71;">Hrg Satuan (Rp)</th>
						<th style="background-color: #2ecc71;">Jumlah Harga (Rp)</th>
						<th style="background-color: #2ecc71;">Bobot(%)</th>
						<th>RAP / RAB</th>
						<th></th>
						<th></th>
					</tr>
				</thead>

				<tbody>
					<?php
					$no = 1;
					$header = "";
					$bobot_RAP = NULL;
					$bobot_RAB = NULL;
					$RAP_RAB_persen = NULL;
					$SubTotalRAP = 0;
					$SubTotalRAB = 0;
					$bold_tulisan = '';
					$LedgerNo = '';
					$NoUrut = 0;
					$MyKdRAP = '';

					// $JumlahHargaPPN = 0;
					// $SubTotal_PPN_HrgSatuan = 0;
					// $SubTotal_PPN_JumlahHarga= 0;

					// $PPN = 0;

					foreach ($mydata as $rap) :
						$NoUrut = $rap['NoUrut'];
						$MyKdRAP = $rap['KdRAP'];

						// $PPN = ($rap['PPN'] == '' || $rap['PPN'] == NULL) ? 0 : $rap['PPN'];

						// $JumlahHargaPPN = ($PPN * $rap['Vol'] );
						// $SubTotal_PPN_HrgSatuan += $PPN;
						// $SubTotal_PPN_JumlahHarga += ($PPN * $rap['Vol'] );


						@$bobot_RAP = ($rap['JumlahHargaRAP'] / $rap['SubTotalRAP']) * 100;
						@$bobot_RAB = ($rap['JumlahHargaRAB'] / $rap['SubTotalRAB']) * 100;

						@$RAP_RAB_persen = ($rap['JumlahHargaRAP'] / $rap['JumlahHargaRAB']) * 100;

						$SubTotalRAP = $rap['SubTotalRAP'];
						$SubTotalRAB = $rap['SubTotalRAB'];
						$bold_tulisan = ($rap['Tipe'] == 'Header') ? 'font-weight:bold;' : '';
						$LedgerNo = $rap['LedgerNo'];




					?>

						<tr>
							<td style="width:100px;text-align: center;">

								<a href="javascript:void(0)" title="Edit" class="btn btn-warning btn-xs" onclick="Dapat_Edit_RAP('<?= $LedgerNo ?>')"><i class="fa fa-pencil"></i></a>

								<a href="javascript:void(0)" title="Hapus" class="btn btn-danger btn-xs" onclick="DapatHapusRAP('<?= $LedgerNo ?>')"><i class="fa fa-trash"></i></a>

							</td>
							<td class="atur_td" style="<?= $bold_tulisan; ?>"><?= $rap['KdRAP'] ?></td>
							<td style="<?= $bold_tulisan; ?>"><?= $rap['Uraian'] ?></td>
							<td style="<?= $bold_tulisan; ?>"><?= $rap['Uom'] ?></td>
							<td style="text-align:right;<?= $bold_tulisan; ?>"><?= ($rap['Tipe'] == 'Header') ? ''  : number_format($rap['Vol'], 3) ?></td>

							<td style="background-color:#74b9ff;text-align:right;<?= $bold_tulisan; ?>"><?= ($rap['HrgSatuanRAP_Custom'] == NULL) ? NULL : new_decimal($rap['HrgSatuanRAP_Custom']) ?></td>
							<td style="background-color:#74b9ff;text-align:right;<?= $bold_tulisan; ?>"><?= ($rap['PPN_New_Nominal'] == NULL) ? NULL : new_decimal($rap['PPN_New_Nominal']) ?></td>
							<td style="background-color:#74b9ff;text-align:right;<?= $bold_tulisan; ?>"><?= number_format($rap['JumlahHargaRAP'], 2) ?></td>
							<td style="background-color:#74b9ff;text-align:right;<?= $bold_tulisan; ?>"><?= number_format($rap['Total_Jumlah_Tambah_PPN_Nominal'], 2) ?></td>
							<td style="background-color:#74b9ff;text-align:right;<?= $bold_tulisan; ?>"><?= (is_nan($bobot_RAP) == TRUE || is_infinite($bobot_RAP) == TRUE) ? '0.00%' : round($bobot_RAP, 2) . '%' ?></td>

							<!-- ini Tambahan PPN -->
							<!-- <td style="background-color:#74b9ff;text-align:right;<?= $bold_tulisan; ?>"><?= number_format($PPN, 2) ?></td>
							<td style="background-color:#74b9ff;text-align:right;<?= $bold_tulisan; ?>"><?= number_format($JumlahHargaPPN, 2) ?></td> -->
							<!--Batas Tambahan PPN -->

							<td style="background-color:#55efc4;text-align:right;<?= $bold_tulisan; ?>"><?= ($rap['HrgSatuanRAB_Custom'] == NULL) ? NULL : new_decimal($rap['HrgSatuanRAB_Custom']) ?></td>
							<td style="background-color:#55efc4;text-align:right;<?= $bold_tulisan; ?>"><?= number_format($rap['JumlahHargaRAB'], 2) ?></td>
							<td style="background-color:#55efc4;text-align:right;<?= $bold_tulisan; ?>"><?= (is_nan($bobot_RAB) == TRUE || is_infinite($bobot_RAB) == TRUE) ? '0.00%' : round($bobot_RAB, 2) . '%' ?></td>

							<td style="text-align:right;<?= $bold_tulisan; ?>"><?= (is_infinite($RAP_RAB_persen) == TRUE || is_nan($RAP_RAB_persen) == TRUE) ? '0.00%' : round($RAP_RAB_persen, 2) . '%' ?></td>
							<td>
								<a href="javascript:void(0)" class="btn btn-sm btn-success" id="BtnUP<?= $NoUrut ?>" data-KdRAP_UP="<?= $MyKdRAP ?>" onclick="atur_posisi_rap('BtnUP','<?= $NoUrut ?>','<?= $MyKdRAP ?>')"><i class="fa fa-arrow-up"></i></a>

							</td>
							<td>
								<a href="javascript:void(0)" class="btn btn-sm btn-success" id="BtnDown<?= $NoUrut ?>" data-KdRAP_Down="<?= $MyKdRAP ?>" onclick="atur_posisi_rap('BtnDown','<?= $NoUrut ?>','<?= $MyKdRAP ?>')"><i class="fa fa-arrow-down"></i></a>
							</td>

						</tr>


					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<?php

					// $RAP_RAB_persen = ($SubTotalRAP / $SubTotalRAB) * 100;
					// @$RAP_RAB_persen = ($SubTotalRAP!=0)?($SubTotalRAP/$SubTotalRAB) * 100:0;
					$SUM_PERSEN_RAP_TERHADAP_RAB = ($SUM_RAP['SUMRAP'] / $SUM_RAP['SUMRAB']) * 100;
					?>
					<tr style="background-color:#bdc3c7">
						<td style="border:0px solid black;text-align:right;font-weight: bold;" colspan="6">Total :</td>
						<td style="border:0px solid black;text-align:right;font-weight: bold;" colspan="0"></td>
						<td style="border:0px solid black;text-align:right;font-weight: bold;" colspan="0"><?= new_decimal($SUM_RAP['SUMRAP']) ?></td>
						<td style="border:0px solid black;text-align:right;font-weight: bold;" colspan="0"><?= new_decimal($SUM_RAP['SUMPPN']) ?></td>

						<td style="border:0px solid black;text-align:right;font-weight: bold;"></td>
						<!-- <td style="border:0px solid black;text-align:right;font-weight: bold;"><?= new_decimal($SubTotal_PPN_HrgSatuan) ?></td>
						<td style="border:0px solid black;text-align:right;font-weight: bold;"><?= new_decimal($SubTotal_PPN_JumlahHarga) ?></td> -->
						<td style="border:0px solid black;text-align:right;font-weight: bold;" colspan="0"></td>
						<td style="border:0px solid black;text-align:right;font-weight: bold;" colspan="0"><?= new_decimal($SUM_RAP['SUMRAB']) ?></td>
						<td style="border:0px solid black;text-align:right;font-weight: bold;" colspan="0"></td>
						<!-- <td style="border:0px solid black;text-align:right;font-weight: bold;" colspan="0"><?= (is_infinite($RAP_RAB_persen) == TRUE || is_nan($RAP_RAB_persen) == TRUE) ? '0.00%' : round($RAP_RAB_persen, 2) . '%' ?></td> -->
						<td style="border:0px solid black;text-align:right;font-weight: bold;" colspan="0"><?= new_decimal($SUM_PERSEN_RAP_TERHADAP_RAB) ?></td>
						<th></th>
						<th></th>
					</tr>
				</tfoot>

			</table>



		</div>



	</div>

	<div class="overlay loading_myrap" style="display: none;">
		<i class="fa fa-refresh fa-spin"></i>
	</div>

</div>


<div class="modal fade" id="modal_add_rap">

	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body" id="modal_add_rap_body">

			</div>

		</div>

	</div>

</div>

<div class="modal fade" id="modal_versi">

	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-body" id="modal_versi_body">

			</div>

		</div>

	</div>

</div>

<div class="modal fade" id="ModalExportExcel">

	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-body" id="ModalExportExcel_Body">

			</div>

		</div>

	</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>


<script type="text/javascript">
	$(function() {

		var JobNo = $('#mytable').attr('data-JobNo');
		var Alokasi = $('#mytable').attr('data-Alokasi');


		$('#mytable').DataTable({
			"ordering": false,
			"paging": false
		});


		$('.btn_add_new_rap').click(function() {
			var JobNo = $(this).attr('data-JobNo');
			var Alokasi = $(this).attr('data-Alokasi');
			var Versi = $(this).attr('data-Versi');
			var Versi_tipe = $(this).attr('data-Versi_tipe');

			jQuery.ajax({
				url: '<?= site_url('Entry_master/ENT_RAP/add_new_RAP') ?>',
				type: 'POST',
				dataType: 'html',
				data: {
					'JobNo': JobNo,
					'Alokasi': Alokasi,
					'Versi': Versi,
					'Versi_tipe': Versi_tipe
				},
				beforeSend: function() {
					$('.loading_myrap').show();

				},
				complete: function(xhr, textStatus) {
					$('.loading_myrap').hide();
				},
				success: function(data, textStatus, xhr) {


					if (data == 'versi_lama') {
						$.toast({
							text: 'Hanya bisa tambah data untuk RAP Versi Terbaru',
							position: 'bottom-right',
							stack: false,
							hideAfter: 3000,
							icon: 'info',

						});
						return false;
					}

					// dapat_modal_add_rap();

					$('#modal_add_rap').modal('show');
					$('#modal_add_rap_body').html(data);



				},
				error: function(xhr, textStatus, errorThrown) {
					$('.loading_myrap').hide();
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


		});


		$('.btn_tambah_versi').click(function() {
			var JobNo = $(this).attr('data-JobNo');
			var Alokasi = $(this).attr('data-Alokasi');
			var Versi = $(this).attr('data-Versi');
			var Versi_tipe = $(this).attr('data-Versi_tipe');

			jQuery.ajax({
				url: '<?= site_url('Entry_master/ENT_RAP/dapat_form_tambah_versi') ?>',
				type: 'POST',
				dataType: 'html',
				data: {
					'JobNo': JobNo,
					'Alokasi': Alokasi,
					'Versi': Versi,
					'Versi_tipe': Versi_tipe
				},
				beforeSend: function() {
					$('.loading_myrap').show();

				},
				complete: function(xhr, textStatus) {
					$('.loading_myrap').hide();
				},
				success: function(data, textStatus, xhr) {


					if (data == 'versi_lama') {
						$.toast({
							text: 'Hanya bisa tambah data untuk RAP Versi Terbaru',
							position: 'bottom-right',
							stack: false,
							hideAfter: 3000,
							icon: 'info',

						});
						return false;
					}

					// dapat_modal_add_rap();

					$('#modal_versi').modal('show');
					$('#modal_versi_body').html(data);



				},
				error: function(xhr, textStatus, errorThrown) {
					$('.loading_myrap').hide();
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


		});


		$('.atur_posisi_rap').click(function() {



			var JobNo = $('#mytable').attr('data-JobNo');
			var Alokasi = $('#mytable').attr('data-Alokasi');

			var NoUrut = $(this).attr('data-NoUrut');
			var KdRAP = $(this).attr('data-KdRAP');
			var TipeBtn = $(this).attr('data-TipeBtn');

			var nomor_baris = (TipeBtn == 'BtnUP') ? parseInt(NoUrut) - 1 : parseInt(NoUrut) + 1;

			if (nomor_baris == 0 && TipeBtn == 'BtnUP') {
				return false;
			}



			alert(nomor_baris + ' - ' + NoUrut);
			return false;


			jQuery.ajax({
				url: '<?= site_url("Entry_master/ENT_RAP/atur_posisi_RAP") ?>',
				type: 'POST',
				dataType: 'html',
				async: true,
				cache: false,
				data: {
					'JobNo': JobNo,
					'Alokasi': Alokasi,
					'NoUrut': NoUrut,
					'KdRAP': KdRAP,
					'TipeBtn': TipeBtn,

				},
				beforeSend: function() {
					$('.loading_myrap').show();
				},
				complete: function(xhr, textStatus) {
					$('.loading_myrap').hide();
				},
				success: function(data, textStatus, xhr) {
					alert(data);
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

		});

		$('.btn_export_excel').click(function() {

			var JobNo = $(this).attr('data-JobNo');
			var Alokasi = $(this).attr('data-Alokasi');
			var Versi = $(this).attr('data-Versi');
			var Versi_tipe = $(this).attr('data-Versi_tipe');

			jQuery.ajax({
				url: '<?= site_url('Entry_master/ENT_RAP/btn_export_excel') ?>',
				type: 'POST',
				dataType: 'html',
				data: {
					'JobNo': JobNo,
					'Alokasi': Alokasi,
					'Versi': Versi,
					'Versi_tipe': Versi_tipe
				},
				beforeSend: function() {
					$('.loading_myrap').show();
					$('#ModalExportExcel_Body').html('');

				},
				complete: function(xhr, textStatus) {
					$('.loading_myrap').hide();
				},
				success: function(data, textStatus, xhr) {


					if (data == 'versi_lama') {
						$.toast({
							text: 'Hanya bisa tambah data untuk RAP Versi Terbaru',
							position: 'bottom-right',
							stack: false,
							hideAfter: 3000,
							icon: 'info',

						});
						return false;
					}


					$('#ModalExportExcel').modal('show');
					$('#ModalExportExcel_Body').html(data);



				},
				error: function(xhr, textStatus, errorThrown) {
					$('.loading_myrap').hide();
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


		});







	});

	function Dapat_Edit_RAP(LedgerNo) {
		$.ajax({
			url: "<?= site_url('Entry_master/ENT_RAP/get_form_edit') ?>",
			type: 'post',
			dataType: 'HTML',
			data: {
				'LedgerNo': LedgerNo
			},
			beforeSend: function() {
				$('.loading_myrap').show();
			},
			complete: function() {
				$('.loading_myrap').hide();
				$('#modal_add_rap').modal('show');
			},
			success: function(data) {
				$('#modal_add_rap_body').html('');
				$('#modal_add_rap_body').html(data);


			},
			error: function(xhr, textStatus, errorThrown) {
				$('.loading_myrap').hide();
				$.toast({
					text: 'terjadi kesalahan',
					position: 'bottom-right',
					stack: false,
					hideAfter: 3000,
					icon: 'error',

				});
				return false;
			}
		});
	}


	function DapatHapusRAP(LedgerNo) {

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

				$.ajax({
					type: "POST",
					url: "<?= site_url('Entry_master/ENT_RAP/AksiHapusRAP') ?>",
					data: {
						'LedgerNo': LedgerNo
					},
					dataType: "html",
					beforeSend: function() {
						$('.loading_myrap').show();
					},
					success: function(data) {
						$('.loading_myrap').hide();
						segarkan_data();

						// alert(data);
						// return false;

						if (data == 'ada_ko') {
							$.toast({
								text: 'Data sudah terpakai KO',
								position: 'bottom-right',
								stack: false,
								hideAfter: 3000,
								icon: 'info',

							});
							return false;
						}

						if (data == 'ada_pd') {
							$.toast({
								text: 'Data sudah memiiki PD',
								position: 'bottom-right',
								stack: false,
								hideAfter: 3000,
								icon: 'info',

							});
							return false;
						}

						if (data == 'header_memiliki_detail') {
							$.toast({
								text: 'Header sudah memiliki detail',
								position: 'bottom-right',
								stack: false,
								hideAfter: 3000,
								icon: 'info',

							});
							return false;
						}

						if (data == 'berhasil') {
							$.toast({
								text: 'Data telah diubah',
								position: 'bottom-right',
								stack: false,
								hideAfter: 3000,
								icon: 'success',

							});
							return false;
						}

						if (data == 'gagal') {
							$.toast({
								text: 'gagal mengubah data',
								position: 'bottom-right',
								stack: false,
								hideAfter: 1000,
								icon: 'error',

							});
							return false;
						}


					},
					error: function() {
						$('.loading_myrap').hide();
						$.toast({
							text: 'Terjadi Kesalahan',
							position: 'bottom-right',
							stack: false,
							hideAfter: 1000,
							icon: 'error',

						});

					}
				});


			}
		})





	}

	function atur_posisi_rap(TipeBtn = '', NoUrut = '', KdRAP = '') {

		var JobNo = $('#mytable').attr('data-JobNo');
		var Alokasi = $('#mytable').attr('data-Alokasi');
		var Versi = $('.btn_tambah_versi').attr('data-Versi');
		var Versi_tipe = $('.btn_tambah_versi').attr('data-Versi_tipe');

		if (Versi_tipe == 'H') {
			$.toast({
				text: 'Hanya bisa di versi terbaru',
				position: 'bottom-right',
				stack: false,
				hideAfter: 3000,
				icon: 'info',

			});
			return false;
		}


		var JumlahBaris = $('#mytable').attr('data-jumlah_baris');
		JumlahBaris = parseInt(JumlahBaris) + 1;

		var NomorBaris = (TipeBtn == 'BtnUP') ? parseInt(NoUrut) - 1 : parseInt(NoUrut) + 1;

		if (NomorBaris == 0 && TipeBtn == 'BtnUP') {
			return false;
		}

		if (TipeBtn == 'BtnDown' && (JumlahBaris == NoUrut)) {
			return false;
		}

		var KdRAP_UP = $('#BtnUP' + NomorBaris).attr('data-KdRAP_UP');
		var KdRAP_Down = $('#BtnDown' + NoUrut).attr('data-KdRAP_Down');


		jQuery.ajax({
			url: '<?= site_url("Entry_master/ENT_RAP/atur_posisi_RAP") ?>',
			type: 'POST',
			dataType: 'html',
			async: true,
			cache: false,
			data: {
				'JobNo': JobNo,
				'Alokasi': Alokasi,
				'Versi': Versi,

				'TipeBtn': TipeBtn,
				'NoUrut': NoUrut,
				'KdRAP': KdRAP,



				'NoUrutAtas': NomorBaris,
				'KdRAPAtas': KdRAP_UP,
				'NoUrutAtas_ubah_ke': NoUrut,

				'NoUrut_ingin_turun': NoUrut,
				'KdRAP_ingin_turun': KdRAP_Down,
				'NoUrut_turun_ke': NomorBaris,

				'NoUrut_dibawahnya': NomorBaris,
				'KdRAP_dibawahnya': KdRAP_UP

			},
			beforeSend: function() {
				$('.loading_myrap').show();
			},
			complete: function(xhr, textStatus) {
				segarkan_data();
			},
			success: function(data, textStatus, xhr) {

				if (data == 'berhasil') {
					$.toast({
						text: 'berhasil mengubah data',
						position: 'bottom-right',
						stack: false,
						hideAfter: 1000,
						icon: 'success',

					});
					return false;
				}

				if (data == 'gagal') {
					$.toast({
						text: 'gagal mengubah data',
						position: 'bottom-right',
						stack: false,
						hideAfter: 1000,
						icon: 'error',

					});
					return false;
				}
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



	}
</script>
