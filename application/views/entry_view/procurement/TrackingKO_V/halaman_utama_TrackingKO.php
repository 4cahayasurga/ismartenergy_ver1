<?php
error_reporting(E_ALL);
?>
<style type="text/css">
	.huruf8{
		font-size: 9px !important;
		font-family:  sans-serif;
	}
	.huruf10{
		font-size: 10px !important;
		font-family:  sans-serif;
	}

	.huruf11{
		font-size: 11px !important;
		font-family:  sans-serif;
	}

	.huruf12{
		font-size: 12px !important;
	}

	.huruf13{
		font-size: 13px !important;
	}

	.huruf14{
		font-size: 14px !important;
	}
	.huruf15{
		font-size: 15px !important;
	}
	.jarak{
		padding-top: 5px !important;
		padding-bottom: 5px !important;
	}

	.jarak_baru{
		padding-top: 10px !important;
		/* padding-bottom: 5px !important; */
	}

	.select2-container--default .select2-results>.select2-results__options{
		min-height: 300px !important;
		max-height: 400px !important;
	}
	.tengah{
		vertical-align:middle;
	}

	.marginatas5{
		margin-top:5px;
	}

</style>

<div class="content-wrapper">

	<section class="content-header">
		<h1><?=$judul?></h1><br>
		<small><button class="btn btn-danger" onclick="history.back()">BACK</button></small>

	</section>

	<section class="content">


		<div class="box">

			<div class="box-body">
				<div class="row">
					<div class="col-sm-4 "><?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?></div>
				</div>
				<div class="row">

					<div class="col-sm-6" style="border: 0px solid;">

						<form id="Form_TrackingKO">
							<table width="100%" style="border-collapse: collapse;">
								<tr>
									<td width="100px">Job No</td>
									<td width="10px" class="text-center">:</td>
									<td width="400px">
										<select name="JobNo" id="JobNo" class="form-control select2" required>
											<option value="">::PILIH JOB::</option>
											<?php foreach ($ListJob as $row): ?>
												<option value="<?=$row->JobNo?>" <?=($JobNo !='' && ($JobNo == $row->JobNo)) ? 'selected' : '' ?>  ><?=$row->JobNo.' - '.$row->JobNm?></option>
											<?php endforeach ?>
										</select>
									</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" style="border-collapse: collapse;" class="marginatas5">
								<tr>
									<td width="100px">No. KO</td>
									<td width="10px" class="text-center">:</td>
									<td width="400px">
										<input type="text" name="NoKO" id="NoKO" onClick="Pilih_NoKO()" class="form-control" placeholder="Klik Disini">

									</td>
									<td width="10px"></td>
									<td width="50px">Tgl KO :</td>
									<td width="200px">
										<input type="date" id="TglKO" name="TglKO" class="form-control" readonly>
									</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" style="border-collapse: collapse;" class="marginatas5">
								<tr>
									<td class="text-center bg-gray color-pallete text-bold" style="padding-top:3px;padding-bottom: 3px;" width="770px">LOKASI PENGIRIMAN</td>
									<td></td>
									
								</tr>
							</table>

							<table width="100%" style="border-collapse: collapse;" class="marginatas5">
								<tr>
									<td width="100px">Nama</td>
									<td width="10px" class="text-center">:</td>
									<td width="400px">
										<input type="text" id="NamaKirim" name="NamaKirim" class="form-control" placeholder="Isi Disini" readonly >
									</td>
									
									<td></td>
								</tr>
							</table>

							<table width="100%" style="border-collapse: collapse;" class="marginatas5">
								<tr>
									<td width="100px">Telepon</td>
									<td width="10px" class="text-center">:</td>
									<td width="400px">
										<input type="text" id="TeleponKirim" name="TeleponKirim" class="form-control"  placeholder="Isi Disini" readonly>
									</td>
									
									<td></td>
								</tr>
							</table>

							<table width="100%" style="border-collapse: collapse;" class="marginatas5">
								<tr>
									<td width="100px">Alamat</td>
									<td width="10px" class="text-center">:</td>
									<td width="400px">
										<textarea class="form-control" id="AlamatKirim" name="AlamatKirim" placeholder="Isi Disini" readonly></textarea>
									</td>
									
									<td></td>
								</tr>
							</table>

							

							<table width="50%" style="border-collapse: collapse;margin-top: 10px;">
								<tr>
									<td width="100px"></td>
									<td width="10px" class="text-center"></td>
									<td>
										<button type="submit" class="btn btn-primary btn-md btn_cari huruf15" id="btnTampilkan" >TAMPILKAN </button>
									</td>
								</tr>
							</table>


						</form>

						<br>
						


					</div> 



				</div>



			</div>
		</div>

		<div id="table_TrackingKO"></div> 

		

		

	</section>

</div>

<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				
			</div>

		</div>

	</div>
</div>

<!-- === START MODAL ADD STATUS === -->
<div id="Modal_AddStatus" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">

		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h3>Data Entry</h3>
			</div>
			<div class="modal-body">
				<form id="form_AddStatus" method="POST" class="form-horizontal">
					<div class="form-group">
						<label class="control-label col-sm-2" for="Status">Status:</label>
						<div class="col-sm-4">
							<select name="Status" id="Status" class="form-control" required>
								<option value="0">In Process</option>
								<option value="1">In Delivered</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="Keterangan">Keterangan:</label>
						<div class="col-sm-10">
							<textarea class="form-control" id="Keterangan" name="Keterangan" placeholder="Isi Disini"></textarea>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-2" for="Tanggal">Tanggal:</label>
						<div class="col-sm-4">
							<input type="date" name="Tanggal" id="Tanggal" class="form-control" required>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">

							<input type="hidden" name="NoKO" id="NoKO">
							<input type="hidden" name="JobNo" id="JobNo" >

							<button type="submit" class="btn btn-default pull-left btnSimpan">SIMPAN</button>
							<button type="button" class="btn btn-default pull-right" data-dismiss="modal">BATAL</button>
							
						</div>
					</div>
				</form>
			</div>

		</div>

	</div>
</div>
<!-- === END MODAL ADD STATUS === -->


<!-- === START MODAL EDIT STATUS === -->
<div id="Modal_EditStatus" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">

		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h3>Data Entry</h3>
			</div>
			<div class="modal-body">
				<form id="form_EditStatus"class="form-horizontal">
					<div class="form-group">
						<label class="control-label col-sm-2" for="Status">Status:</label>
						<div class="col-sm-4">
							<select name="Status" id="Status" class="form-control" required>
								<option value="0">In Process</option>
								<option value="1">In Delivered</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="Keterangan">Keterangan:</label>
						<div class="col-sm-10">
							<textarea class="form-control" id="Keterangan" name="Keterangan" placeholder="Isi Disini"></textarea>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-2" for="Tanggal">Tanggal:</label>
						<div class="col-sm-4">
							<input type="date" name="Tanggal" id="Tanggal" class="form-control" required>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">

							<input type="hidden" name="NoKO" id="NoKO">
							<input type="hidden" name="JobNo" id="JobNo" >
							<input type="hidden" name="Trace" id="Trace" >

							<button type="submit" class="btn btn-default pull-left btnSimpan">UPDATE</button>
							<button type="button" class="btn btn-default pull-right" data-dismiss="modal">BATAL</button>
							
						</div>
					</div>
				</form>
			</div>

		</div>

	</div>
</div>
<!-- === END MODAL EDIT STATUS === -->



<script src="<?=base_url('assets/select2_baru.js')?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.select2').select2();

		toastr.options = {
			"debug": false,
			"positionClass": "toast-bottom-full-width",
			"onclick": null,
			"fadeIn": 300,
			"fadeOut": 1000,
			"timeOut": 5000,
			"extendedTimeOut": 1000
		}


		$('#Form_TrackingKO #JobNo').change(function(){
			$('#table_TrackingKO').html('');
			$('#Form_TrackingKO #NoKO').val('');
			$('#Form_TrackingKO #TglKO').val('');
			$('#Form_TrackingKO #NamaKirim').val('');
			$('#Form_TrackingKO #TeleponKirim').val('');
			$('#Form_TrackingKO #AlamatKirim').val('');
		});

		$('#Form_TrackingKO').submit(function(event){
			event.preventDefault();
			// alert('aa')
			table_TrackingKO();
		})


		$('#form_AddStatus').submit(function(event){
			event.preventDefault();
			jQuery.ajax({
				url: "<?=site_url('Entry/Procurement/TrackingKO_C/Aksi_AddStatus')?>",
				type: 'POST',
				dataType: 'html',
				data: $(this).serialize(),
				beforeSend : function(){
					$('.btnSimpan').attr('disabled',true);
				},
				complete: function(xhr, textStatus) {
					$('.btnSimpan').attr('disabled',false);
				},
				success: function(data, textStatus, xhr) {
					table_TrackingKO();
					$('#Modal_AddStatus').modal('hide');
					if (data=='berhasil') {
						toastr.success('','Success');
						return false;
					}else if(data=='gagal'){
						toastr.info('','Failed!');
						return false;
					}else{
						toastr.error('','Error');
						return false;
					}
					
				},
				error: function(xhr, textStatus, errorThrown) {
					toastr.error('Terjadi Kesalahan','INFO');
					return false;
				}
			});
		})


		$('#form_EditStatus').submit(function(event){
			event.preventDefault();
			jQuery.ajax({
				url: "<?=site_url('Entry/Procurement/TrackingKO_C/Aksi_EditStatus')?>",
				type: 'POST',
				dataType: 'html',
				data: $(this).serialize(),
				beforeSend : function(){
					$('.btnSimpan').attr('disabled',true);
				},
				complete: function(xhr, textStatus) {
					$('.btnSimpan').attr('disabled',false);
				},
				success: function(data, textStatus, xhr) {
					table_TrackingKO();
					$('#Modal_EditStatus').modal('hide');
					if (data=='berhasil') {
						toastr.success('','Updated');
						return false;
					}else if(data=='gagal'){
						toastr.info('','Failed!');
						return false;
					}else{
						toastr.error('','Error');
						return false;
					}
					
				},
				error: function(xhr, textStatus, errorThrown) {
					toastr.error('Terjadi Kesalahan','INFO');
					return false;
				}
			});
		})

	});


	function Pilih_NoKO(){
		var JobNo = $('#Form_TrackingKO #JobNo option:selected').val();

		if (JobNo == '') {
			toastr.info('Belum pilih Job','INFO');
			return false;
		}

		jQuery.ajax({
			url: "<?=site_url('Entry/Procurement/TrackingKO_C/Pilih_NoKO')?>",
			type: 'POST',
			dataType: 'html',
			data: {JobNo : JobNo},
			beforeSend : function(){
				// $('#btnPilih_NoKO').attr('disabled',true);
			},
			complete: function(xhr, textStatus) {
				// $('#btnPilih_NoKO').attr('disabled',false);
			},
			success: function(data, textStatus, xhr) {
				// alert(data);
				$('#myModal .modal-body').html(data);

			},
			error: function(xhr, textStatus, errorThrown) {
				toastr.error('Terjadi Kesalahan','INFO');
				return false;
			}
		});
		

		$('#myModal').modal('show');
	}

	function NoKO_Berganti(NoKO=''){
		var JobNo = $('#Form_TrackingKO #JobNo option:selected').val();
		if (JobNo == '') {
			toastr.info('Belum pilih Job','INFO');
			return false;
		}

		var VendorNm = $('#btnNoKO_Berganti'+NoKO).attr('data-VendorNm');
		VendorNm = '('+VendorNm+')';

		jQuery.ajax({
			url: "<?=site_url('Entry/Procurement/TrackingKO_C/NoKO_Berganti')?>",
			type: 'POST',
			dataType: 'json',
			data: {JobNo : JobNo, NoKO : NoKO},
			beforeSend : function(){
				$('#btnNoKO_Berganti'+NoKO).attr('disabled',true);
			},
			complete: function(xhr, textStatus) {
				$('#btnNoKO_Berganti'+NoKO).attr('disabled',false);
			},
			success: function(data, textStatus, xhr) {
				// alert(data.NamaKirim);
				$('#Form_TrackingKO #NoKO').val(NoKO+' '+VendorNm);
				$('#Form_TrackingKO #TglKO').val(data.TglKO);
				$('#Form_TrackingKO #NamaKirim').val(data.NamaKirim);
				$('#Form_TrackingKO #TeleponKirim').val(data.TeleponKirim);
				$('#Form_TrackingKO #AlamatKirim').val(data.AlamatKirim);

				$('#myModal').modal('hide');
				
				table_TrackingKO();
			},
			error: function(xhr, textStatus, errorThrown) {
				toastr.error('Terjadi Kesalahan','INFO');
				return false;
			}
		});
	}

	
	function table_TrackingKO(){
		var JobNo = $('#Form_TrackingKO #JobNo option:selected').val();
		var NoKO = $('#Form_TrackingKO #NoKO').val();

		if (JobNo=='') {
			toastr.info('Job masih kosong', 'INFO');
			return false;
		}

		if (NoKO =='') {
			toastr.info('No. KO masih kosong','INFO');
			return false;
		}

		// alert('ss')

		jQuery.ajax({
			url: "<?=site_url('Entry/Procurement/TrackingKO_C/table_TrackingKO')?>",
			type: 'POST',
			dataType: 'html',
			data: {JobNo : JobNo, NoKO : NoKO},
			beforeSend : function(){
				$('#btnTampilkan').attr('disabled',true).text('LOADING...');
				// $('#table_CLosingKO').html('');
			},
			complete: function(xhr, textStatus) {
				$('#btnTampilkan').attr('disabled',false).text('TAMPILKAN');
			},
			success: function(data, textStatus, xhr) {
				// alert(data);
				$('#table_TrackingKO').html(data);
				
			},
			error: function(xhr, textStatus, errorThrown) {
				toastr.error('error', 'Error');
				return false;
			}
		});


	}

	function Update_TraceKO(){
		alert('aaa')
	}

	function AddStatus(){

		$('#form_AddStatus #Keterangan').val('');
		$('#form_AddStatus #Tanggal').val('');
		$('#form_AddStatus #NoKO').val('');
		$('#form_AddStatus #JobNo').val('');
		// alert('aa')
		var JobNo = $('#Form_TrackingKO #JobNo option:selected').val();
		var NoKO = $('#Form_TrackingKO #NoKO').val();

		

		$('#form_AddStatus #NoKO').val(NoKO);
		$('#form_AddStatus #JobNo').val(JobNo);

		// alert(JobNo+' - '+NoKO);

		$('#Modal_AddStatus').modal('show');
	}

	function EditStatus(Trace=''){
		if (Trace=='') {
			toastr.error('Terjadi Kesalahan','INFO');
			return false;
		}

		// == HAPUS DULU BIAR TIDAK ADA DATA LAMA ==
		$('#form_EditStatus #Keterangan').val('');
		$('#form_EditStatus #Tanggal').val('');
		$('#form_EditStatus #NoKO').val('');
		$('#form_EditStatus #JobNo').val('');
		// == END HAPUS DULU BIAR TIDAK ADA DATA LAMA ==
		var NoKO = $('#btn_EditStatus'+Trace).attr('data-NoKO');
		var JobNo = $('#btn_EditStatus'+Trace).attr('data-JobNo');
		var Keterangan = $('#btn_EditStatus'+Trace).attr('data-Keterangan');
		var Status = $('#btn_EditStatus'+Trace).attr('data-Status');
		var Tanggal = $('#btn_EditStatus'+Trace).attr('data-Tanggal');

		$('#form_EditStatus #Status').val(Status).trigger('change');
		$('#form_EditStatus #Keterangan').val(Keterangan);
		$('#form_EditStatus #Tanggal').val(Tanggal);
		$('#form_EditStatus #NoKO').val(NoKO);
		$('#form_EditStatus #JobNo').val(JobNo);
		$('#form_EditStatus #Trace').val(Trace);
		$('#Modal_EditStatus').modal('show');
	}

</script>


