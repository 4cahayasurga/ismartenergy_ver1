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

					<form id="FormSearch">

						<div class="box-body">

							<div class="table-responsive">

								<table border="0" width="100%" class="font12" id="tbl_search">
									<tr>
										<td width="5%" >Job No</td>
										<td width="1%" class="text-center">:</td>
										<td width="30%" >

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
										<td style="padding-left:20px !important;">
											<button type="submit" class="btn btn-sm bg-gray font12" id="btn_search" >SEARCH <i class="fa fa-search"></i></button>
											<button type="button" class="btn btn-sm btn-default disabled hidden" id="btn_loading" >LOADING... <i class="fa fa-spinner fa-spin"></i></button>
										</td>
									</tr>
									

								</table>
								<p></p>
								


							</div>

						</div>

					</form>

					<div id="tableMOSBarangMasuk_tampil">

					</div>

				</div>

			</div>
		</div>

		
		
	</section>


</div>

<script src="<?=base_url('assets/select2_baru.js')?>"></script>

<script type="text/javascript">
	$(function(){

		$('.select2').select2({ dropdownCssClass: "font11" });

		if ( $('#JobNo').val() !='' ) {
			ListMOS();
		}

		$( "#FormSearch" ).submit(function( event ) {
			event.preventDefault();
			ListMOS();
		});

		$('.btn_approve').click(function(){
			LedgerNo = $(this).attr('data-LedgerNo');
			NoSJ = $(this).attr('data-NoSJ');
			ApprovedBy = $(this).attr('data-ApprovedBy');
			Nomor = $(this).attr('data-Nomor');

			// alert(LedgerNo+' - '+NoSJ);


			Swal.fire({
				title: 'Yakin '+ApprovedBy+' '+NoSJ+'?',
				
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yakin',
				cancelButtonText: 'Batal'
			}).then((result) => {

				jQuery.ajax({
					url: '<?=site_url('MOS/MosBarangMasuk/aksi_approve')?>',
					type: 'POST',
					dataType: 'html',
					data: {LedgerNo : LedgerNo, NoSJ : NoSJ, ApprovedBy : ApprovedBy },
					beforeSend : function(){
						$('#btn_approveID'+Nomor).attr('disabled',true);
					},
					complete: function(xhr, textStatus) {
						$('#btn_approveID'+Nomor).attr('disabled',false);
						ListMOS();
					},
					success: function(data, textStatus, xhr) {
						if (data == 'file_besar') {
							Swal.fire('Info!','File maximal 2mb','info');

						}else if(data == 'berhasil'){
							Swal.fire('Info!','Berhasil','success');
						}else{
							Swal.fire('Info!','gagal','error');
						}
					},
					error: function(xhr, textStatus, errorThrown) {
						alert('error');
					}
				});
				

			})
		});

		


	});

	function ListMOS(){

		var JobNo = $('#JobNo').val();

		if (JobNo == '') {
			PesanPemberitahuan('info','Job belum diisi');
			return false;
		}

		jQuery.ajax({
			url: '<?=site_url("MOS/MosBarangMasuk/tableMOSBarangMasuk")?>',
			type: 'POST',
			dataType: 'html',
			data: $('#FormSearch').serializeArray(),
			beforeSend : function(){
				$('#btn_search').hide();
				// $('#btn_loading').show();
			},
			success: function(data, textStatus, xhr) {
				// $('#btn_loading').hide();

				$('#tableMOSBarangMasuk_tampil').html('');
				$('#tableMOSBarangMasuk_tampil').html(data);

				$('#btn_search').show();
				

			},
			error: function(xhr, textStatus, errorThrown) {
				alert('error');

				$('#btn_search').show();
				// $('#btn_loading').hide();
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
