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
	select[readonly].select2-hidden-accessible + .select2-container {
        pointer-events: none;
        touch-action: none;
    }

    select[readonly].select2-hidden-accessible + .select2-container .select2-selection {
        background: #eee;
        box-shadow: none;
    }

    select[readonly].select2-hidden-accessible + .select2-container .select2-selection__arrow, select[readonly].select2-hidden-accessible + .select2-container .select2-selection__clear {
        display: none;
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

		<p></p>

		<div class="row">
			<div class="col-sm-12">

				<div class="box box-primary">

					<div class="box-body">
						
						<form id="FrmSimpanMOS" >
							<div class="row">

								<div class="col-sm-4">

									<div class="table-responsive">
										<table width="100%" border="0" class="font12">
											<tr>
												<td width="100px" class="jarak5">JobNo</td>
												<td width="10px" class="text-center jarak5">:</td>
												<td class="jarak5">
													<input type="text" value="<?=$JobNo.' - '.$JobNm?>" class="form-control input-sm font12" disabled>
													<input type="hidden" name="JobNo" value="<?=$JobNo?>">
												</td>
											</tr>
											<tr>
												<td width="100px" class="jarak5 font12">No MOS</td>
												<td width="10px" class="text-center jarak5">:</td>
												<td class="jarak5">
													<input type="text" class="form-control input-sm font12 jarak5" id="NoSJ" value="<?=$dataSJ->NoSJ?>" readonly>
													<input type="hidden" name="NoSJ" value="<?=$dataSJ->NoSJ?>">
												</td> 
											</tr>
											<tr>
												<td width="150px" class="jarak5 font12">Tgl Terima Material</td>
												<td width="10px" class="text-center jarak5">:</td>
												<td class="jarak5">
													<input type="date" id="TglTerima" name="TglTerima" class="form-control input-sm font12 jarak5" value="<?=$dataSJ->TglSJ?>"  required <?=$nonaktif?>>
												</td> 
											</tr>
											<tr>
												<td width="100px" class="jarak5 font12">No. Surat Jalan dari Vendor</td>
												<td width="10px" class="text-center jarak5">:</td>
												<td class="jarak5">
													<input type="text" id="NoSJVendor" name="NoSJVendor" class="form-control input-sm font12 jarak5" placeholder="Isi Disini" value="<?=$dataSJ->NoSJVendor?>" <?=$nonaktif?> >
												</td> 
											</tr>
										</table>
									</div>

								</div>


								<div class="col-sm-4">
									<table width="100%" border="0" class="font12">
										<tr>
											<td width="100px" class="jarak5">No PO</td>
											<td width="10px" class="text-center jarak5">:</td>
											<td class="jarak5">
												<select name="NoKO" id="NoKO" class="form-control input-sm select2 font12"  readonly>
													<option value="">-- Pilih --</option>
													<?php foreach ($dataKO as $row): ?>
														<option value="<?=$row->NoKO?>" data-TglKO="<?=$row->TglKO?>" data-VendorNm="<?=$row->VendorNm?>" data-VendorId="<?=$row->VendorId?>" data-NoSPR="<?=$row->NoSPR?>" 
														<?=($row->NoKO == $dataSJ->NoKO) ? 'selected' : '' ?>  ><?=$row->NoKO?></option>
													<?php endforeach ?>
												</select>

											</td>
											<td style="padding-left:20px;"><button type="button" class="btn btn-md btn-link" title="Refresh Table"><i class="fa fa-refresh" onclick="listKoDtl()"></i></button></td>
										</tr>
										<tr>
											<td width="150px" class="jarak5 font12">Tgl PO</td>
											<td width="10px" class="text-center jarak5">:</td>
											<td class="jarak5">
												<input type="date" id="TglPO" name="TglPO" class="form-control input-sm font12 jarak5" readonly>
											</td> 
										</tr>
										<tr>
											<td width="100px" class="jarak5 font12">Vendor</td>
											<td width="10px" class="text-center jarak5">:</td>
											<td class="jarak5">
												<input type="text" id="VendorNm" name="VendorNm" class="form-control input-sm font12 jarak5" placeholder="Isi Disini" readonly>
												<input type="hidden" id="VendorId" name="VendorId" class="form-control input-sm font12 jarak5" placeholder="Isi Disini" readonly>
											</td> 
										</tr>
										<tr>
											<td width="100px" class="jarak5 font12">No SPR</td>
											<td width="10px" class="text-center jarak5">:</td>
											<td class="jarak5">
												<input type="text" id="NoSPR" name="NoSPR" class="form-control input-sm font12 jarak5" placeholder="Isi Disini" readonly>
											</td> 
										</tr>

										<tr>
											<td width="100px" class="jarak5 font12">Upload Surat Jalan</td>
											<td width="10px" class="text-center jarak5">:</td>
											<td class="jarak5">
												<input type="file" id="BuktiSuratJalan" name="BuktiSuratJalan" class="form-control input-sm font12 jarak5" placeholder="Isi Disini" <?=$nonaktif?>>
												<span class="text-red">*kosongkan jika tidak ingin mengubah file</span>
											</td> 
										</tr>
									</table>
								</div>


								<div class="col-sm-12">
									<button type="submit" class="btn btn-primary btn-md btn_simpan pull-right" <?=$nonaktif?>>Simpan</button>
								</div>


								


							</div>
							<p></p>
							<span id="loadingsaya" style="display:none;">Loading...<i class="fa fa-spinner fa-spin"></i></span>
							<div id="tampil_listKoDtl"></div>
						</form>




					</div>



				</div>

			</div>
		</div>



	</section>


</div>

<script src="<?=base_url('assets/select2_baru.js')?>"></script>

<script>
	$(function(){
		DataKO();
		$('.select2').select2({ dropdownCssClass: "font12" });
		$('#NoKO').change(function(){
			DataKO();
		});

		$('#FrmSimpanMOS').submit(function(e){
			e.preventDefault();

			var JobNo = $('input[name="JobNo"]').val();
			// alert(JobNo);
			// return false;
			var formData = new FormData($(this)[0]);
			jQuery.ajax({
				url: '<?=site_url('MOS/MosBarangMasuk/AksiUpdateMOS')?>',
				type: 'POST',
				dataType: 'html',
				data: formData,
				async: false,
				cache: false,
				contentType: false,
				processData: false,
				beforeSend : function(){
					$('.btn_simpan').text('Loading...').attr('disabled',true);
				},
				success: function(data, textStatus, xhr) {
					$('.btn_simpan').text('Simpan').attr('disabled',false);
					// alert(data);
					// console.log(data);
					// return false;

					if (data == 'file_besar') {
						Swal.fire('Info!','File maximal 2mb','info');

					}else if(data == 'berhasil'){
						// Swal.fire('Sukses!','data telah disimpan','success');
						window.location.href="<?=base_url('MOS/MosBarangMasuk/view/')?>"+JobNo;
					}else{
						Swal.fire('Info!','gagal menyimpan data','error');
					}

					
				},
				error: function(xhr, textStatus, errorThrown) {
					$('.btn_simpan').text('Loading...').attr('disabled',false);
					alert('error');
				}
			});
			

		});

	});

	function DataKO(){
		NoKO = $('#NoKO option:selected').val();
		var element = $('#NoKO').find('option:selected'); 
		var TglKO = element.attr("data-TglKO"); 
		var VendorId = element.attr("data-VendorId"); 
		var VendorNm = element.attr("data-VendorNm"); 
		var NoSPR = element.attr("data-NoSPR"); 

		$('#TglPO').val(TglKO);
		$('#VendorId').val(VendorId);
		$('#VendorNm').val(VendorNm);
		$('#NoSPR').val(NoSPR);

		listKoDtl();

	}


	function listKoDtl(){
		NoKO = $('#NoKO option:selected').val();
		NoSJ = $('#NoSJ').val();
		JobNo = $('input[name="JobNo"]').val();

		// alert(NoSJ);
		// return false;

		if (NoKO == '') {
			Swal.fire(
				'Info!',
				'No KO belum diisi',
				'info'
				)
			return false;
		}

		jQuery.ajax({
			url: '<?=site_url('MOS/MosBarangMasuk/listKoDtl_Update')?>',
			type: 'POST',
			dataType: 'html',
			data: {NoKO : NoKO, NoSJ : NoSJ, JobNo : JobNo},
			beforeSend : function(){
				$('#loadingsaya').show();
			},
			success: function(data, textStatus, xhr) {
				$('#loadingsaya').hide();
				$('#tampil_listKoDtl').html('');

				if (data == 'volkosong') {
					Swal.fire(
						'Info!',
						'Belum ada entry volume',
						'info'
						)
					return false;
				}

				
				$('#tampil_listKoDtl').html(data);
			},
			error: function(xhr, textStatus, errorThrown) {
				alert('error');
			}
		});

	}
</script>


