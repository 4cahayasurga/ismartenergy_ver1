<style type="text/css">
	#tblJob{
		font-family: sans-serif;
		font-size: 12px;
	}

	.font12{
		font-size: 12px;
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

	#tblBuatan1{
		font-family: sans-serif;
		font-size: 12px !important;
	}

	#tblBuatan1 input[type="text"]{
		font-family: sans-serif;
		font-size: 12px !important;
	}

	#tblBuatan1 tr td	{
		padding-top : 5px !important;
	}

	#tblBuatan2{
		font-family: sans-serif;
		font-size: 12px !important;
	}

	#tblBuatan2{
		font-family: sans-serif;
		font-size: 12px !important;
	}

	#tblBuatan2 tr td	{
		padding-top : 5px !important;
	}

	input[type="text"]{
		font-family: sans-serif;
		font-size: 12px !important;
	}

	input[type="date"]{
		font-family: sans-serif;
		font-size: 12px !important;
	}

	select{
		font-family: sans-serif;
		font-size: 12px !important;
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
	.swal2-popup {
		font-size: 1.6rem !important;
		font-family: sans-serif;
	
</style>

<div class="content-wrapper">

	<section class="content-header">
		<h1>
			<?=$judul?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('Dashboard')?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">Form Tambah Material On Site</li>
		</ol>
	</section>

	<section class="content">

		<div class="row">

			<form id="FrmSave">

				<div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
							
							<div class="box-tools pull-right">

								<a type="button" onclick="history.back()" class="btn btn-md btn-danger">Back</a>
								<button type="submit" class="btn btn-md btn-success btn_simpan">Simpan</button>
							</div>
						</div>
						<div class="box-body">

							<div class="col-lg-4">
								<div class="table-responsive">

									<table width="100%" id="tblBuatan1">
										<tr>
											<td >Job No : </td>
											<td>
												<input type="text" class="form-control input-sm" value="<?=$JobNo.' - '.$JobNm?>" readonly>
												<input type="hidden" name="JobNo" value="<?=$JobNo?>"  readonly>
											</td>
										</tr>
										<tr>
											<td>No SJ : </td>
											<td>
												<input type="text" name="NoSJ" class="form-control input-sm" value="<?=$NoSJ?>" readonly>
											</td>
										</tr>
										<tr>
											<td>Tgl Terima Material : </td>
											<td>
												<input type="date" name="TglSJ" value="<?=date('Y-m-d')?>" class="form-control input-sm" >
											</td>
										</tr>
										<tr>
											<td>No SJ dari Vendor : </td>
											<td>
												<input type="text" name="NoSJVendor" class="form-control input-sm" placeholder="Isi Disini" >
											</td>
										</tr>
									</table>
								</div>
							</div>




							<div class="col-sm-4">

								<table width="100%" id="tblBuatan2">
									<tr>
										<td >No PO : </td>
										<td>
											<select name="NoKO" id="NoKO" class="form-control" required >
												<option value="">-- Pilih --</option>
												<?php foreach ($dataKO as $row): ?>
													<option value="<?=$row->NoKO?>" data-TglKO="<?=$row->TglKO?>" data-NoSPR="<?=$row->NoSPR?>" data-VendorNm="<?=$row->VendorNm?>" ><?=$row->NoKO?></option>
												<?php endforeach ?>
											</select>
										</td>
										<td colspan="4">
											<button type="button" class="btn btn-md btn-link refresh_table" title="Refresh Table"><i class="fa fa-refresh" ></i></button>
										</td>
									</tr>
									<tr>
										<td>Tgl PO : </td>
										<td>
											<input type="date" class="form-control input-sm TglKO" >
										</td>
									</tr>
									<tr>
										<td>Vendor : </td>
										<td>
											<input type="text" class="form-control input-sm Vendor" >
										</td>
									</tr>
									<tr>
										<td>No SPR :</td>
										<td>
											<input type="text" class="form-control input-sm NoSPR">
										</td>
									</tr>
									<tr>
										<td>Upload Surat Jalan :</td>
										<td>
											<input type="file" name="BuktiSuratJalan" accept="image/*, application/pdf" class="form-control input-sm" >
										</td>
									</tr>
								</table>
							</div>


							<div class="col-xs-12 text-center" style="margin-top:10px !important">
								<p class="bg-primary" style="padding-top:2px;padding-bottom:2px;">ORDER LIST</p>
							</div>

							<div class="col-lg-10 tampilkan_disini" >
								
							</div>

							
						</div>
					</div>
				</div>


			</form>
		</div>


	</section>



</div>

<script src="<?=base_url('assets/select2_baru.js')?>"></script>
<script type="text/javascript">


	$(document).ready(function(){

		$('#NoKO').select2({ dropdownCssClass: "font12", width : "100%" });
		$('.refresh_table').click(function(){

			// tbl_order_list();
		});

		$('#NoKO').change(function(){
			NoKO = $('#NoKO option:selected').val();
			var element = $('#NoKO').find('option:selected'); 
			var TglKO = element.attr("data-TglKO"); 
			var VendorId = element.attr("data-VendorId"); 
			var VendorNm = element.attr("data-VendorNm"); 
			var NoSPR = element.attr("data-NoSPR"); 

			$('.TglKO').val(TglKO);
			$('.Vendor').val(VendorNm);
			$('.NoSPR').val(NoSPR);

			// tbl_order_list();

		});



	});
</script>