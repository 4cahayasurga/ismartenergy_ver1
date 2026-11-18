<style>
	.mykonten {
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
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

	.tbl_set{
		font-size: 10px;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
		width: 100%;
		border: 0px solid;
		border-collapse: collapse;
	}

	.huruf_set{
		font-size: 10px;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}

	.pengaturan_tbl th{
		padding-top: 7px !important;
		padding-bottom: 7px !important;
	}

</style>

<div class="content-wrapper">

	<section class="content-header">
		<h5 style="font-weight: bold;"><?= (isset($judul)) ? strtoupper($judul) : '' ?></h5>

		<ol class="breadcrumb">
			<button class="btn btn-sm bg-gray" onclick="history.back()"><i class="fa fa-arrow-left"></i> Back</button>
		
		</ol>
		
	</section>

	<section class="content">
		<?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>

		<div class="box box-success">

	
			<div class="box-body">



				<div class="table-responsive">

					
					<table width="100%" border="0" class="huruf_set" >
						<tr>
							<td style="width:5%;">Tgl Entry</td>
							<td style="width:1%;text-align:center;" >:</td>
							<td style="width:15%;">
								<input type="date" name="dari_tgl" id="dari_tgl" class="form-control input-sm huruf_set" value="<?=$dari_tgl?>" required >
							</td>
							<td style="width:3%;text-align:center;">s.d</td>
							<td style="width:15%;">
								<input type="date" name="sampai_tgl" id="sampai_tgl" class="form-control input-sm huruf_set" value="<?=$sampai_tgl?>" required >
							</td>
							<td></td>
						</tr>
					</table>

					<table width="100%" border="0" class="huruf_set" style="margin-top:5px;" >
						<tr>
							<td style="width:5%;">Job No</td>
							<td style="width:1%;text-align:center;" >:</td>
							<td style="width:33%;">
								<select name="JobNo" id="JobNo" class="form-control input-sm select2" required>
									<option value="AllJob">All Job</option>
									<?php
									foreach ($dataJob as $data_job) :
										?>
										<option value="<?= $data_job['JobNo'] ?>" <?= ($JobNo == $data_job['JobNo']) ? 'selected' : ''  ?>><?= $data_job['JobNo'] . ' - ' . $data_job['JobNm'] ?></option>

									<?php endforeach; ?>
								</select>
							</td>
							
							<td></td>
						</tr>
					</table>

					<table width="100%" border="0" class="huruf_set" style="margin-top:5px;" >
						<tr>
							<td style="width:5%;">Vendor</td>
							<td style="width:1%;text-align:center;" >:</td>
							<td style="width:33%;">
								<select name="VendorId" id="VendorId" class="form-control input-sm" required>
									<option value="AllVendor">All Vendor</option>
									<?php
									foreach (dapat_list_vendor() as $data_vendor) :
										?>
										<option value="<?= $data_vendor['VendorId'] ?>" ><?= $data_vendor['VendorNm']?></option>

									<?php endforeach; ?>
								</select>
							</td>
							
							<td></td>
						</tr>
					</table>

					<table width="100%" border="0" class="huruf_set" style="margin-top:5px;" >
						<tr>
							<td style="width:5%;">Sort By</td>
							<td style="width:1%;text-align:center;" >:</td>
							<td style="width:33%;">
								<select name="SortBy" id="SortBy" class="form-control input-sm" required>
									<option value="ByVendor">By Vendor</option>
									<option value="ByProject" selected>By Project</option>
									
								</select>
							</td>
							
							<td></td>
						</tr>
					</table>


					<table  width="100%" border="0" class="huruf_set" style="margin-top:5px;" >
						<tr>
							<td style="width:5%;"></td>
							<td style="width:1%;text-align: center;"></td>
							<td style="width:33%;">
								<button id="btn_tampil" class="btn btn-sm btn-success">Tampilkan <i class="fa fa-search"></i> </button>
								<button id="btn_loading" class="btn btn-sm btn-danger" disabled="disabled" style="display:none;">Loading... <i class="fa fa-spinner"></i></button>

							</td>	
							<td></td>
						</tr>

					</table>

					

					

				</div>


			</div>

			
		</div>

		<div id="tampil_table"></div>

	</section>


</div>
<script type="text/javascript">
	$(function() {


		$('.select2').select2({
			'width': '100%',
			'placeholder' : '-- Pilih Salah Satu --'

		});

		$('#VendorId').select2({
			'width': '100%',
			'placeholder' : '-- Pilih Salah Satu --'
		});

		$('#SortBy').select2({
			'width': '50%',
			'placeholder' : '-- Pilih Salah Satu --'
		});

		$('#btn_tampil').click(function(){
			get_rekap_inv_supplier();
		});

	});

	function get_rekap_inv_supplier(){

		var JobNo = $('#JobNo').val();
		var dari_tgl = $('#dari_tgl').val();
		var sampai_tgl = $('#sampai_tgl').val();
		var VendorId = $('#VendorId').val();
		var SortBy = $('#SortBy').val();


		if (JobNo == '') {
			$.toast({
				text: 'Pilih Job terlebih dahulu',
				position: 'top-right',
				stack: false,
				hideAfter: 3000,
				icon: 'info',

			});
			return false;
		}

		if (VendorId == '') {
			$.toast({
				text: 'Pilih Vendor terlebih dahulu',
				position: 'top-right',
				stack: false,
				hideAfter: 3000,
				icon: 'info',

			});
			return false;
		}

		if (dari_tgl == '' || sampai_tgl == '') {
			$.toast({
				text: 'Tgl Masih Kosong',
				position: 'top-right',
				stack: false,
				hideAfter: 3000,
				icon: 'info',

			});
			return false;
		}

		if (dari_tgl > sampai_tgl) {
			$.toast({
				text: 'Tgl tidak sesuai',
				position: 'top-right',
				stack: false,
				hideAfter: 3000,
				icon: 'info',

			});
			return false;
		}

		jQuery.ajax({

			url: '<?= site_url("MyReport/Procurement/RekapInvoice_Supplier/get_rekap_inv_supplier") ?>',
			type: 'post',
			dataType: 'html',
			data: { 
				'JobNo': JobNo,
				'dari_tgl' : dari_tgl,
				'sampai_tgl' : sampai_tgl,
				'VendorId' : VendorId,
				'SortBy' : SortBy
			},
			beforeSend : function(){
				$('#btn_tampil').hide();
				$('#btn_loading').show();
				$('#tampil_table').html('');
			},
			complete: function(xhr, textStatus) {
				$('#btn_tampil').show();
				$('#btn_loading').hide();
			},
			success: function(data, textStatus, xhr) {
				$('#tampil_table').html(data);
			},
			error: function(xhr, textStatus, errorThrown) {
				$.toast({
					text: 'terjadi kesalahan',
					position: 'top-right',
					stack: false,
					hideAfter: 3000,
					icon: 'error',

				});
				return false;
			}
		});
	
	}



</script>