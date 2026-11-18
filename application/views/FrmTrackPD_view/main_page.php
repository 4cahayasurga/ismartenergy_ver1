<style type="text/css">
	.font10{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size : 10px;
	}
	.font11{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size : 11px;
	}
	.font13{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}
	.font12{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size : 12px;
	}
	.font14{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size : 14px;
	}
	.font16{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size : 16px;
	}
	.jarak5{
		padding-top: 5px;
		padding-bottom: 5px;
	}
	#tbl_search tr td{
		padding-top: 5px;
		padding-bottom: 5px;
	}
	.swal2-popup {
		font-size: 1.4rem !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}
	.swal-wide{
		width:550px !important;
	}
</style>

<div class="content-wrapper">

	<section class="content">
		<?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>
		<table>
			<tr>
				<td class="font16 text-bold"><?=($judul) ? strtoupper($judul) : 'Judul belum diatur'  ?></td>
			</tr>
		</table>

		<br>

		<div class="row">
			<div class="col-sm-12">

				<div class="box box-primary">
					<div class="box-body">
						<form id="FormSearch">

							<div class="table-responsive">
								<table border="0" width="100%" class="font12" id="tbl_search">
									<tr>
										<td width="5%" >Job No</td>
										<td width="1%" class="text-center">:</td>
										<td width="20%" >

											<?php 
											$selectDisabled = '';

											if ($JobNo !=''): ?>
												<input type="hidden" name="JobNo" id="JobNo" value="<?=$JobNo?>" >
												<?php
												$selectDisabled = 'disabled';
												?>
											<?php endif ?>

											<select class="form-control font12 select2 " name="JobNo" id="JobNo" <?=$selectDisabled?> >
												<option value="">-- Pilih Job --</option>
												<?php foreach ($ListJob as $key): ?>
													<option value="<?=$key->JobNo?>" <?=($key->JobNo == $JobNo) ? 'selected' : ''   ?>  ><?=$key->JobNo.' - '.$key->JobNm?></option>
												<?php endforeach ?>
											</select>

											

										</td>
										<td></td>
									</tr>
									<tr>
										<td width="5%">Alokasi</td>
										<td width="1%" class="text-center">:</td>
										<td width="20%" >
											<select class="form-control font12 select2" name="Alokasi" id="Alokasi">
												<option value="0">Semua Alokasi</option>
												<?php foreach ($ListAlokasi as $key): ?>
													<option value="<?=$key['Alokasi']?>"  <?=($key['Alokasi'] == $Alokasi) ? 'selected' : ''   ?> ><?=$key['Alokasi'].' - '.$key['Keterangan']?></option>
												<?php endforeach ?>
											</select>
										</td>
										<td></td>
									</tr>
									<tr>
										<td width="5%"></td>
										<td width="1%" class="text-center"></td>
										<td width="20%" >



											<button type="submit" class="btn btn-sm btn-defautl font12" id="btn_search" >SEARCH <i class="fa fa-search"></i></button>
											<button type="button" class="btn btn-sm btn-defautl disabled hidden" id="btn_loading" >LOADING... <i class="fa fa-spinner fa-spin"></i></button>
										</td>
										<td></td>
									</tr>
								</table>
							</div>

						</form>

					</div>
				</div>
			</div>
		</div>

		<div id="tableTrackPD_tampil">
			
		</div>
		
	</section>


</div>

<!-- <script src="https://datatables.net/release-datatables/extensions/FixedHeader/js/dataTables.fixedHeader.js"></script> -->
<script src="<?=base_url('assets/select2_baru.js')?>"></script>

<script type="text/javascript">
	$(function(){
		$('.select2').select2({ dropdownCssClass: "font11" });
		$('#Alokasi').select2({ dropdownCssClass: "font11" });

		if ( $('#JobNo').val() !='' && $('#Alokasi').val() !=''   ) {
			ListTrackPD();
		}


		$( "#FormSearch" ).submit(function( event ) {
			event.preventDefault();
			ListTrackPD();
		});

	});

	function ListTrackPD(){

		var JobNo = $('#JobNo').val();
		var Alokasi = $('#Alokasi').val();

		if (JobNo == '') {
			PesanPemberitahuan('info','Job belum diisi');
			return false;
		}

		if (Alokasi == '') {
			PesanPemberitahuan('info','Alokasi belum diisi');
			return false;
		}

		jQuery.ajax({
			url: '<?=site_url("FrmTrackPD/tableTrackPD")?>',
			type: 'POST',
			dataType: 'html',
			data: $('#FormSearch').serializeArray(),
			beforeSend : function(){
				$('#btn_search').hide();
				$('#btn_loading').show();
			},
			complete : function(){
				$('#btn_search').show();
				$('#btn_loading').hide();
			},
			success: function(data, textStatus, xhr) {


				$('#tableTrackPD_tampil').html('');
				$('#tableTrackPD_tampil').html(data);

			},
			error: function(xhr, textStatus, errorThrown) {
				alert('error');
			}
		});
		
	}


	function PesanPemberitahuan(type='',isi_pesan=''){
		if (type == '') {
			type = 'error';
		}
		if (isi_pesan == '') {
			isi_pesan = 'isi pesan kosong';
		}

		Command: toastr[type](isi_pesan)

		toastr.options = {
			"closeButton": false,
			"debug": false,
			"newestOnTop": false,
			"progressBar": false,
			"positionClass": "bottom-right",
			"preventDuplicates": false,
			"onclick": null,
			"showDuration": "300",
			"hideDuration": "1000",
			"timeOut": "1000",
			"extendedTimeOut": "1000",
			"showEasing": "swing",
			"hideEasing": "linear",
			"showMethod": "fadeIn",
			"hideMethod": "fadeOut"
		}

		
	}




</script>

<script type="text/javascript">

	function PilihPD(LedgerNo=''){

		if (LedgerNo == '') {
			return false;
		}

		NoPD = $('#SelectBtn_'+LedgerNo).attr('data-NoPD');
		RejectBy = $('#SelectBtn_'+LedgerNo).attr('data-RejectBy');

		jQuery.ajax({
			url: '<?=site_url('FrmTrackPD/CekSelectPD')?>',
			type: 'POST',
			dataType: 'html',
			data: {'NoPD': NoPD, LedgerNo : LedgerNo},
			beforeSend : function(){
				$('#SelectBtn_'+LedgerNo).text('Loading...').attr('disabled',true);
			},
			complete : function(){
				$('#SelectBtn_'+LedgerNo).text('SELECT').attr('disabled',false);
				// ListTrackPD();
			},
			success: function(data, textStatus, xhr) {

				if (data == 'PD_kosong') {

					Swal.fire(
						'Info!',
						NoPD+' tidak ditemukan',
						'info'
						)

				}else if (data == 'sudah_reject') {

					Swal.fire(
						'Info!',
						'sudah di reject '+RejectBy,
						'info'
						)

				}else if (data == 'belum_bayar_kasir') {
					Swal.fire(
						'Info!',
						'Belum ada pembayaran untuk No '+NoPD,
						'info'
						)
				}else{
					window.location.href="<?=site_url('FrmTrackPD/EntryPJ/')?>"+NoPD+'/'+LedgerNo;
				}

				


			},
			error: function(xhr, textStatus, errorThrown) {
				alert('error');
			}
		});

	}

	function PrintPD(LedgerNo=''){

		if (LedgerNo == '') {
			return false;
		}

		linkSaya = $('.btnPrint_'+LedgerNo).attr('data-link');
		RejectBy = $('#ApproveBtn_'+LedgerNo).attr('data-RejectBy');

		jQuery.ajax({
			url: '<?=site_url('FrmTrackPD/CekPdHdr')?>',
			type: 'POST',
			dataType: 'html',
			data: {LedgerNo : LedgerNo},
			beforeSend : function(){
				$('.btnPrint_'+LedgerNo).text('Loading...').attr('disabled',true);
			},
			complete : function(){
				$('.btnPrint_'+LedgerNo).text('PRINT').attr('disabled',false);
			},
			success: function(data, textStatus, xhr) {

				if (data == 'berhasil') {

					window.open(linkSaya);

				}else if (data == 'PD_kosong') {

					Swal.fire(
						'Info!',
						NoPD+' tidak ditemukan',
						'info'
						)

				}else if (data == 'sudah_reject') {

					Swal.fire(
						'Info!',
						'sudah di reject '+RejectBy,
						'info'
						)

				}else if (data == 'NoPJ_kosong') {

					Swal.fire(
						'Info!',
						'belum entry uraian PJ',
						'info'
						)

				}else{
					alert('error');
				}

				

			},
			error: function(xhr, textStatus, errorThrown) {
				alert('error');
			}
		});

		

	}

	function ApprovePD(LedgerNo=''){

		if (LedgerNo == '') {
			return false;
		}

		NoPD = $('#ApproveBtn_'+LedgerNo).attr('data-NoPD');
		ApproveTxt = $('#ApproveBtn_'+LedgerNo).attr('data-ApproveTxt');
		RejectBy = $('#ApproveBtn_'+LedgerNo).attr('data-RejectBy');

		swal({
			title: "Yakin ingin "+ApproveTxt+" "+NoPD+" ?",
			// icon: "warning",
			// buttons: true,
			// dangerMode: true,
			customClass: 'swal-wide',
		})
		.then((willDelete) => {
			if (willDelete) {
				
				jQuery.ajax({
					url: '<?=site_url('FrmTrackPD/ApprovePD')?>',
					type: 'POST',
					dataType: 'html',
					data: {LedgerNo : LedgerNo, NoPD: NoPD, ApproveTxt : ApproveTxt},
					beforeSend : function(){
						$('.approve'+NoPD).text('Loading...').attr('disabled',true);
					},
					complete : function(){
						$('.approve'+NoPD).text('Approve').attr('disabled',false);
						// ListTrackPD();
					},
					success: function(data, textStatus, xhr) {

						if (data == 'berhasil') {

							Swal.fire(
								'Success',
								'Sukses '+ApproveTxt+' '+NoPD,
								'success'
								)

						}else if (data == 'gagal') {

							Swal.fire(
								'Failed',
								'Gagal '+ApproveTxt+' '+NoPD,
								'error'
								)

						}else if (data == 'PD_kosong') {

							Swal.fire(
								'Info!',
								NoPD+' tidak ditemukan',
								'info'
								)

						}else if (data == 'sudah_reject') {

							Swal.fire(
								'Info!',
								'sudah di reject '+RejectBy,
								'info'
								)

						}else if (data == 'NoPJ_kosong') {

							Swal.fire(
								'Info!',
								'belum entry uraian PJ',
								'info'
								)
							
						}else if (data == 'sudah_approve_cost_control') {

							Swal.fire(
								'Info!',
								'sudah di-Approved oleh Cost Control',
								'info'
								)

						}else{
							alert('error');
						}

					},
					error: function(xhr, textStatus, errorThrown) {
						alert('error');
					}
				});

			} 
		});

		
		
	}
</script>