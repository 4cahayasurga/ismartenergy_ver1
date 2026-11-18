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
				<form id="FrmSimpanMOS" >
					<div class="box box-primary">
						<div class="box-header text-right" style="border-bottom:1px solid silver;" >
							<a href="<?=site_url('Mos/MosBarangMasuk/view/'.$JobNo)?>" class="btn btn-danger btn-md">Batal</a>

							<?php if ($nonaktif != 'disabled'): ?>
							
							<?php endif ?>
							<?php if ($nonaktif != 'disabled') { ?>
								<button type="submit" class="btn btn-primary btn-md btn_simpan" <?=$nonaktif?> >Simpan</button>
							<?php }else{ ?>

							<?php } ?>


							
						</div>
						<div class="box-body">


							<div class="row">

								<div class="col-sm-4">

									<div class="table-responsive">
										<table width="100%" border="0" class="font12">
											<tr>
												<td width="100px" class="jarak5">JobNo</td>
												<td width="10px" class="text-center jarak5">:</td>
												<td class="jarak5">
													<input type="text" value="<?=$JobNo.' - '.$JobNm?>" class="form-control input-sm font12" readonly >
													<input type="hidden" name="JobNo" value="<?=$JobNo?>" readonly>
												</td>
											</tr>
											<tr>
												<td width="100px" class="jarak5 font12">No MOS</td>
												<td width="10px" class="text-center jarak5">:</td>
												<td class="jarak5">
													<input type="text" class="form-control input-sm font12 jarak5" value="<?=$dataSJ->NoSJ?>" readonly>
												</td> 
											</tr>
											<tr>
												<td width="150px" class="jarak5 font12">Tgl Terima Material</td>
												<td width="10px" class="text-center jarak5">:</td>
												<td class="jarak5">
													<input type="date" id="TglTerima" name="TglTerima" class="form-control input-sm font12 jarak5" value="<?=$dataSJ->TglSJ?>" <?=$nonaktif?>  required>
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
												<select name="NoKO" id="NoKO" class="form-control input-sm select2 font12" readonly  required>
													<option value="">-- Pilih --</option>
													<?php foreach ($dataKO as $row): ?>
														<option value="<?=$row->NoKO?>" data-TglKO="<?=$row->TglKO?>" data-VendorNm="<?=$row->VendorNm?>" data-VendorId="<?=$row->VendorId?>" data-NoSPR="<?=$row->NoSPR?>" data-NoSJ="<?=$NoSJ?>"   <?=($row->NoKO == $dataSJ->NoKO ) ? 'selected' : ''?> ><?=$row->NoKO?></option>
													<?php endforeach ?>
												</select>

											</td>
											<td style="padding-left:20px;"><button type="button" class="btn btn-md btn-link" onclick="listKoDtl()" title="Refresh Table"><i class="fa fa-refresh" ></i></button></td>
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
												<input type="file" id="BuktiSuratJalan" name="BuktiSuratJalan" class="form-control input-sm font12 jarak5" placeholder="Isi Disini" accept="image/*,application/pdf" <?=$nonaktif?>>
												<span class="text-red font10">*kosongkan jika tidak ingin mengubah file</span>
											</td> 
										</tr>
									</table>
								</div>

								<div class="col-sm-6">
									<button type="button" class="btn btn-default btn-md btn_simpan pull-left" onclick="listKoDtl()" >Refresh Table &nbsp;<i class="fa fa-refresh"></i></button>
									
								</div>
								<!-- <div class="col-sm-5 text-right">
									<a href="<?=site_url('Mos/MosBarangMasuk/view/'.$JobNo)?>" class="btn btn-danger btn-md pull-right">Batal</a>
								</div> -->
								<div class="col-sm-6">
									<input type="hidden" name="NoSJ" value="<?=$dataSJ->NoSJ?>">
									
									<!-- <button type="submit" class="btn btn-primary btn-md btn_simpan pull-right">Simpan</button> -->

									
								</div>


							</div>
							<p></p>
							<span id="loadingsaya" style="display:none;">Loading...<i class="fa fa-spinner fa-spin"></i></span>
							<span class="text-red font12 tulisan_peringatan" id="tulisan_peringatan" style="display:none;">*Volume MOS lebih besar dari Volume PO</span>
							<div id="tampil_listKoDtl"></div>
							<!-- </form> -->




						</div>



					</div>
				</form>

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

			var yourArray = [];
			var flag = 0;
			$('#tblsaya tbody tr td.mytd').each(function() {
				var VolPO = $(this).find('input[name="VolPOTest[]"]').val();
				var VolTerima = $(this).find('input[name="VolTerima[]"]').val();
				var NoUrut = $(this).attr('data-NoUrut');

				VolPO = HapusKoma(VolPO);
				VolTerima = HapusKoma(VolTerima);
				if (VolTerima > VolPO) {	
					flag++;
					$('#VolTerimaID'+NoUrut).css({'border-bottom':'1px solid red', 'border-top' : '1px solid red', 'border-left' : '1px solid red', 'border-right' : '1px solid red'});


				}else{
					$('#VolTerimaID'+NoUrut).css({'border-bottom':'0px solid black', 'border-top' : '0px solid silver', 'border-left' : '0px solid silver', 'border-right' : '0px solid silver'});
				}
			});

			if (flag > 0) {
				$('#tulisan_peringatan').show();
				return false;
			}else{
				$('#tulisan_peringatan').hide();
			}

			

			var formData = new FormData($(this)[0]);
			jQuery.ajax({
				url: '<?=site_url('MOS/MosBarangMasuk/ActionUpdateMOS')?>',
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
		NoSJ = $('#NoKO option:selected').attr('data-NoSJ');

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
			url: '<?=site_url('MOS/MosBarangMasuk/listKoDtl_pageUpdate')?>',
			type: 'POST',
			dataType: 'html',
			data: {NoKO : NoKO,NoSJ : NoSJ},
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


