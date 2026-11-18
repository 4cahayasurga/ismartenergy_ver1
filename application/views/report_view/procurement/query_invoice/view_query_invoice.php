<style>
	.mykonten {
		font-family: Arial, Helvetica, sans-serif !important;
		font-size: 10px !important;
	}

	.atur_td {
		width: 100px;
	}

	.myFont {
		font-size: 10px !important;
	}

	.select2 {
		font-size: 9px;
	}
</style>

<div class="content-wrapper">

	<section class="content-header">
		<h5 style="font-weight: bold;"><?= (isset($judul)) ? strtoupper($judul) : '' ?></h5>

		<ol class="breadcrumb">
			<li><a href="<?= site_url('dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?= (isset($judul)) ? $judul : '' ?></li>
		</ol>
	</section>

	<section class="content-header">

		<button class="btn btn-xs" style="background-color:#7f8c8d;" onclick="history.back()"><i class="fa fa-arrow-left"></i> Back</button>

	</section>

	<section class="content">
		<?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>

		<div class="box box-danger">

			<div class="box-header with-border">

				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
				</div>
			</div>
			<div class="box-body">

				<div class="table-responsive">

					<table width="100%" border="0" class="mykonten" >
						<tr>
							<td style="width:8%;padding-bottom:20px !important;">Job No</td>
							<td style="width:1%;text-align: center;">:</td>
							<td style="width:28%;">
								<select name="JobNo" id="JobNo" class="form-control input-sm select2">
									<option value="All_Job">All Job</option>
									<?php
									foreach (Data_job_untuk_report() as $data_job) :
										?>
										<option value="<?= $data_job->JobNo ?>" ><?=$data_job->Jobgabung?></option>

									<?php endforeach; ?>
								</select>
							</td>
							<td></td>
						</tr>
					</table>

					<table width="100%" border="0" class="mykonten" >
						<tr>
							<td style="width:8%;padding-bottom:20px !important;">Vendor</td>
							<td style="width:1%;text-align: center;">:</td>
							<td style="width:28%;">
								<select name="VendorId" id="VendorId" class="form-control input-sm select2">
									<option value="All_Vendor">All Vendor</option>
									<?php
									foreach (SemuaVendor() as $data_vendor) :
										?>
										<option value="<?= $data_vendor->VendorId ?>" ><?=$data_vendor->VendorNm?></option>

									<?php endforeach; ?>
								</select>
							</td>
							<td></td>
						</tr>
					</table>

					<table width="100%%" border="0" class="mykonten">
						<tr>
							<td style="width:8%;padding-bottom:20px !important;">Periode Bayar</td>
							<td style="width:1%;text-align: center;">:</td>
							<td style="width:13%;">
								<input type="date" name="PrdAwal" id="PrdAwal"  class="form-control input-sm mykonten">
							</td>
							<td style="width:2%;text-align: center;">s.d</td>
							<td style="width:13%;">
								<input type="date" name="PrdAkhir" id="PrdAkhir" class="form-control input-sm mykonten">
							</td>
							<td>
								<span>&nbsp;&nbsp;&nbsp;*Kosongkan untuk semua periode</span>
								
							</td>

						</tr>
					</table>

					


					


					<table width="100%" border="0" class="mykonten" >
						<tr>
							<td style="width:8%;"></td>
							<td style="width:1%;text-align: center;"></td>
							<td style="width:205;">
								<button id="btn_tampil_query_invoice" class="btn btn-sm btn-success">Tampilkan <i class="fa fa-search"></i> </button>
								<button id="btn_loading" class="btn btn-sm btn-danger" disabled="disabled" style="display:none;">Loading... <i class="fa fa-spinner"></i></button>

							</td>	
							<td></td>
						</tr>

					</table>

					

					

				</div>


			</div>

			
		</div>

		<div id="tampil_tbl_query_invoice"></div>

	</section>


</div>
<script type="text/javascript">
	$(function() {


		$('.select2').select2({
			'width': '100%',
			'theme': 'classic',
		});

		$('#btn_tampil_query_invoice').click(function(){
			tbl_query_invoice();
		});

	});

	

	function tbl_query_invoice(){

		var JobNo = $('#JobNo').val();
		var VendorId = $('#VendorId').val();

		var PrdAwal = $('#PrdAwal').val();
		var PrdAkhir = $('#PrdAkhir').val();

		if (JobNo == '' || VendorId == '') {
			$.toast({
				text: 'Pilih Job / Vendor terlebih dahulu',
				position: 'bottom-right',
				stack: false,
				hideAfter: 3000,
				icon: 'info',

			});
			return false;
		}

		if ((PrdAwal !='' && PrdAkhir=='') || (PrdAwal =='' && PrdAkhir!='') ) {
			$.toast({
				text: 'Periode tidak boleh kosong',
				position: 'bottom-right',
				stack: false,
				hideAfter: 3000,
				icon: 'info',

			});
			return false;
		}

		if (PrdAwal > PrdAkhir  ) {
			$.toast({
				text: 'Periode tidak diterima',
				position: 'bottom-right',
				stack: false,
				hideAfter: 3000,
				icon: 'error',

			});
			return false;
		}

		// alert(JobNo+ ' - '+VendorId);
		// return false;


		jQuery.ajax({

			url: '<?= site_url("MyReport/Procurement/Query_invoice_report/tbl_query_invoice") ?>',
			type: 'post',
			dataType: 'html',
			data: { 
				'JobNo': JobNo,
				'VendorId' : VendorId,
				'PrdAwal' : PrdAwal,
				'PrdAkhir' : PrdAkhir
				
			},
			cache:false,
			beforeSend : function(){
				$('#btn_tampil_query_invoice').hide();
				$('#btn_loading').show();
				$('#tampil_tbl_query_invoice').html('');
			},
			complete: function(xhr, textStatus) {
				$('#btn_tampil_query_invoice').show();
				$('#btn_loading').hide();
			},
			success: function(data, textStatus, xhr) {
				$('#tampil_tbl_query_invoice').html(data);
			},
			error: function(xhr, textStatus, errorThrown) {
				$.toast({
					text: 'terjadi kesalahan',
					position: 'bottom-right',
					stack: false,
					hideAfter: 2000,
					icon: 'error',

				});
				return false;
			}
		});
	
	}



</script>