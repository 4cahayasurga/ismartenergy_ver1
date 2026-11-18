
<style type="text/css">
	.gaya_huruf{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}

	.swal2-popup {
		font-size: 1.4rem !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}
	.select2-selection { overflow: hidden; }
	.select2-selection__rendered { white-space: normal; word-break: break-all; }

	.padding_lima{
		padding-top: 5px !important;
	}

	.padding_sepuluh{
		padding-top: 10px !important;
	}

</style>
<div class="content-wrapper">



	<section class="content">
		<?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>
		<table style="width:100%;border-collapse: collapse;" class="gaya_huruf">
			<tr>
				<th style="text-decoration:underline;vertical-align: top;"><h3><?=($judul) ? strtoupper($judul) : 'Judul belum diatur'  ?></h3></th>
			</tr>
		</table>

		<div class="box box-widget">

			<div class="box-header bg-aqua-active">

			</div>

			<div class="box-body">
				<div class="row">
					<div class="col-sm-12">
						<div class="table-responsive">

							<form id="frm_outstanding_pc" style="font-size:13px !important;">

								<table style="width: 100%;border-collapse: collapse;">
									<tr>
										<th width="50px">Periode</th>
										<td width="20px" class="text-center">:</td>
										<td width="200px">
											<input type="text" name="PrdAwal" id="PrdAwal" class="form-control startdate" placeholder="Pilih Tanggal" value="<?=$PrdAwal?>" style="font-size:13px !important;" required  />
										</td>
										<td width="30px" class="text-center" style="font-size:13px !important;">s.d</td>
										<td width="200px">
											<input type="text" name="PrdAkhir" id="PrdAkhir" class="form-control startdate" placeholder="Pilih Tanggal" value="<?=$PrdAkhir?>" style="font-size:13px !important;" required  />
										</td>
										<td></td>
									</tr>
								</table>

								<p></p>
								<table style="width: 100%;border-collapse: collapse;" >
									<tr style="margin-top: 10px !important;">
										<th width="50px">By</th>
										<td width="20px" class="text-center">:</td>
										<td width="200px">
											<select name="By" id="By" class="form-control" style="font-size:13px !important;" required>
												<option value="PC">Project Control</option>
												<option value="KEU" >Keuangan</option>
											</select>
										</td>
										<td></td>

									</tr>
								</table>

								<p></p>
								<table style="width: 100%;border-collapse: collapse;">
									<tr>
										<th width="50px">Type</th>
										<td width="20px" class="text-center">:</td>
										<td width="200px">
											<select name="Type" id="Type" class="form-control" style="font-size:13px !important;" required>
												<option value="OutStanding">Outstanding</option>
												<option value="Approved" >Approved</option>
											</select>
										</td>
										<td></td>

									</tr>
								</table>

								<p></p>
								<table style="width: 100%;border-collapse: collapse;">

									<tr >
										<td width="50px"></td>
										<td width="20px" class="text-center"></td>
										<td width="200px">
											<button type="submit" class="btn btn-md bg-yellow btn_tampilkan"><i class="fa fa-search"></i> Tampilkan</button>
											<button class="btn btn-md bg-default btn_proses disabled" style="display:none;"><i class="fa fa-refresh"></i> Loading...</button>
										</td>
										<td></td>
									</tr>

								</table>

							</form>

						</div>
					</div>
				</div>
			</div>

		</div>

		<div class="tampil_table"></div>



	</section>


</div>



<script src="<?=base_url('assets/select2_baru.js')?>"></script>

<script type="text/javascript">

	function setDateRangePicker(input1, input2){
		$(input1).datepicker({
			autoclose: true,
			format: "dd-M-yyyy"
		}).on("change", function(){
			$(input2).val("").datepicker('setStartDate', $(this).val());
		}).attr("readonly", "readonly").css({"cursor":"pointer", "background":"white"});

		$(input2).datepicker({
			autoclose: true,
			format: "dd-M-yyyy",
			orientation: "bottom right"
		}).attr("readonly", "readonly").css({"cursor":"pointer", "background":"white"});
	}


	$(document).ready(function(){
		$('.select2').select2({
			width: '100%'
		});

		setDateRangePicker(".startdate", ".enddate");

		$('#frm_outstanding_pc').submit(function(event){
			event.preventDefault();
			cari_data();
		});


	});

	function cari_data(){

		var PrdAwal = $('#PrdAwal').val();
		var PrdAkhir = $('#PrdAkhir').val();

		var By = $('#By').find(":selected").val();
		var Type = $('#Type').find(":selected").val();

		// alert(By);

		if ((PrdAwal == '' || PrdAwal == null) || (PrdAkhir == '' || PrdAkhir == null) ) {
			Swal.fire('Info?!','Tanggal masih kosong','info');
			return false;
		}

		// if (PrdAwal > PrdAkhir ) {
		// 	Swal.fire('Info?!','Tanggal tidak sesuai','info');
		// 	return false;
		// }

		if (By == '' || By == null) {
			Swal.fire('Info?!','By masih kosong','info');
			return false;
		}

		if (Type == '' || Type == null) {
			Swal.fire('Info?!','Type masih kosong','info');
			return false;
		}

		jQuery.ajax({
			url: '<?=site_url('RptOutstandingPC/panggil_table_outstanding')?>',
			type: 'POST',
			dataType: 'html',
			data: {PrdAwal : PrdAwal, PrdAkhir : PrdAkhir, By : By, Type : Type  },
			cache : false,
			beforeSend : function(){
				$('.tampil_table').html('');
				$('.btn_tampilkan').hide();
				$('.btn_proses').show();
			},
			complete: function(xhr, textStatus) {
				$('.btn_tampilkan').show();
				$('.btn_proses').hide();
			},
			success: function(data, textStatus, xhr) {
				$('.tampil_table').html(data);
			},
			error: function(xhr, textStatus, errorThrown) {
				Swal.fire('Error!','','error');
				return false;
			}
		});



	}   


</script>

