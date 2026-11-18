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

		<p></p>

		<form id="FrmSimpanMOS" data-NoSJOtomatis="<?=$NoSJ?>">

			<div class="row">

				<div class="col-sm-12">

					<div class="box box-primary">

						<div class="box-header with-border text-right">
							<button class="btn btn-danger btn-md" type="button" onclick="window.history.go(-1); return false;">BATAL</button>
							<button type="submit" class="btn btn-success btn-md <?=($see == 'see') ? 'disabled hidden' : '' ?> " id="BtnSimpanMOS"  >SIMPAN</button>
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
													<input type="text" value="<?=$JobNo.' - '.$JobNm?>" class="form-control input-sm font12" disabled>
													<input type="hidden" name="JobNo" value="<?=$JobNo?>">
													<input type="hidden" name="see" value="<?=$see?>">
												</td>
											</tr>
											<tr>
												<td width="100px" class="jarak5 font12">No MOS</td>
												<td width="10px" class="text-center jarak5">:</td>
												<td class="jarak5">
													<input type="text" class="form-control input-sm font12 jarak5" name="NoSJ" value="<?=$NoSJ?>" readonly>
												</td> 
											</tr>
											<tr>
												<td width="150px" class="jarak5 font12">Tgl Terima Material</td>
												<td width="10px" class="text-center jarak5">:</td>
												<td class="jarak5">
													<input type="date" id="TglTerima" name="TglTerima" class="form-control input-sm font12 jarak5" value="<?=date('Y-m-d')?>" required>
												</td> 
											</tr>
											<tr>
												<td width="100px" class="jarak5 font12">No. Surat Jalan dari Vendor</td>
												<td width="10px" class="text-center jarak5">:</td>
												<td class="jarak5">
													<input type="text" id="NoSJVendor" name="NoSJVendor" class="form-control input-sm font12 jarak5" placeholder="Isi Disini">
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
												<select name="NoKO" id="NoKO" class="form-control input-sm select2 font12"  required>
													<option value="">-- Pilih --</option>
													<?php foreach ($dataKO as $row): ?>
														<option value="<?=$row->NoKO?>" data-TglKO="<?=$row->TglKO?>" data-VendorNm="<?=$row->VendorNm?>" data-VendorId="<?=$row->VendorId?>" data-NoSPR="<?=$row->NoSPR?>" data-JumlahKO="<?=$row->JumlahKO?>"  <?=($AdaKO == $row->NoKO) ? 'selected' : '' ?>   ><?=$row->NoKO?></option>
													<?php endforeach ?>
												</select>
												<span class="text-blue loadingko" style="display: none;">Loading... <i class="fa fa-spinner fa-spin"></i></span>
											</td>
											<td style="padding-left:20px;">
												<button type="button" class="btn btn-md btn-link refresh_table" title="Refresh Table"><i class="fa fa-refresh" ></i></button>
											</td>
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
												<input type="file" id="BuktiSuratJalan" name="BuktiSuratJalan" class="form-control input-sm font12 jarak5" placeholder="Isi Disini" accept="image/*,application/pdf">
											</td> 
										</tr>
									</table>
								</div>


								<div class="col-sm-12 tbl_detail"></div>




							</div>



						</div>

					</div>

				</div>
			</div>

		</form>



	</section>


</div>

<script src="<?=base_url('assets/swal2_terbaru.js')?>"></script>
<script src="<?=base_url('assets/select2_baru.js')?>"></script>

<script>

	$(function(){

		if ($('#NoKO option:selected').val() !='') {
			DataKO();
			Tampilkan_Table_Detail();
		}

		// DataKO();

		// if ($('input[name="see"]').val() == 'see') {
		// 	DataKO();
		// }

		$('.select2').select2({ dropdownCssClass: "font12" });
			// $('.loadingko').show();


		$('#FrmSimpanMOS').submit(function(e){
			e.preventDefault();

			var flag = 0;

			$("input[name='peringatan_aktif[]']").each(function(){
				if (this.value == 1) {
					flag++;
				}
			});

			if (flag > 0) {
				Swal.fire('Alert!','Volume melebihi KO','warning');
				return false;
			}
			var JobNo = $('input[name="JobNo"]').val();

			var formData = new FormData($(this)[0]);
			jQuery.ajax({
				url: "<?=site_url('MOS/MosBarangMasuk/SaveMOS')?>",
				type: "post",
				data: formData,
				async: false,
				cache: false,
				contentType: false,
				processData: false,
				dataType : "html",
				beforeSend : function(){
					$(this).text('Loading...').attr('disbaled',true);
				},
				complete: function(xhr, textStatus) {
					$(this).text('Simpan').attr('disbaled',false);	
				},
				success: function(data, textStatus, xhr) {

					// alert(data);
					// console.log(data);
					// return false;

					if (data == 'file_besar') {
						Swal.fire('Info!','File maximal 2mb','info');

					}else if(data == 'berhasil'){
						window.location.href="<?=base_url('MOS/MosBarangMasuk/view/')?>"+JobNo;
					}else if(data == 'NoSJ_ada'){
						location.reload();
					}else{
						Swal.fire('Info!','gagal menyimpan data','error');
					}
				},
				error: function(xhr, textStatus, errorThrown) {

					Swal.fire('error','Terjadi Kesalahan','error');
					return false;
				}
			});


		});

		$('#NoKO').change(function(){
			DataKO();
			Tampilkan_Table_Detail();
		});

		$('.refresh_table').click(function(){
			Tampilkan_Table_Detail();
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
		}


		function Tampilkan_Table_Detail(){
			var NoKO = $('#NoKO option:selected').val();
			var JobNo = $('input[name="JobNo"]').val();

			var NoSJOtomatis = $('#FrmSimpanMOS').attr('data-NoSJOtomatis');
			// alert(NoSJOtomatis);

			if (NoKO == '') {
				Swal.fire(
					'Info!',
					'No KO belum diisi',
					'info'
					)
				return false;
			}


			jQuery.ajax({
				url: "<?=site_url('MOS/MosBarangMasuk/Tampilkan_Table_Detail')?>",
				type: "post",
				data : {NoKO : NoKO, JobNo : JobNo},
				dataType : "html",
				beforeSend : function(){
					$('#NoKO').next(".select2-container").hide();
					$('.loadingko').show();
					$('.tbl_detail').html('');
				},
				complete: function(xhr, textStatus) {
					$('#NoKO').next(".select2-container").show();
					$('.loadingko').hide();
				},
				success: function(data, textStatus, xhr) {
					// alert(data);
					$('.tbl_detail').html(data);
					NoSJSebelumnyaAda();
				},
				error: function(xhr, textStatus, errorThrown) {
					Swal.fire('error','Terjadi Kesalahan','error');
					return false;
				}
			});


		}


		function HapusKoma(nilai) {
			return (nilai.replace(/,/g, ''));
		}


	});




</script>


