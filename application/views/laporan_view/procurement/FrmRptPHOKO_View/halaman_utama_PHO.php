<?php
// error_reporting(E_ALL);
?>
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

	.myFont {
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size:11px;
	}

</style>
<div class="content-wrapper">

	<section class="content">
		<?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>
		<table style="width:100%;border-collapse: collapse;" class="gaya_huruf">
			<tr>
				<th style="text-decoration:underline;vertical-align: top;font-size:22px !important;"><?=($judul) ? strtoupper($judul) : 'Judul belum diatur'  ?></th>
			</tr>
		</table>

		<div class="box box-widget">

			<div class="box-header bg-aqua-active">

			</div>

			<div class="box-body">
				<div class="row">
					<div class="col-sm-12">
						<div class="table-responsive">

							<form id="FrmHutangKO_PHO" style="font-size:13px !important;">

								<table style="width: 100%;border-collapse: collapse;" >
									<tr style="margin-top: 10px !important;">
										<th width="50px">Job No</th>
										<td width="20px" class="text-center">:</td>
										<td width="530px">
											<select name="JobNo" id="JobNo" class="form-control select2" style="font-size:13px !important;" required>
												<?php foreach($ListJob as $ListJob){ ?>


													<option value="<?=$ListJob->JobNo?>"><?=$ListJob->JobNo.' - '.$ListJob->JobNm?></option>
												<?php } ?>
											</select>
										</td>
										<td></td>

									</tr>
								</table>

								<p></p>

								<table style="width: 100%;border-collapse: collapse;">

									<tr>
										<th width="50px">Periode</th>
										<td width="20px" class="text-center">:</td>
										<td width="250px">
											<input type="date" class="form-control input-sm" name="DariTgl" id="DariTgl" value="<?=$DariTgl?>" required  >
										</td>
										<th style="padding-top:5px;padding-bottom:5px;padding-left:5px;padding-right:5px;" width="2px">s.d.</th>
										<td width="250px">
											<input type="date" class="form-control input-sm" name="SampaiTgl" id="SampaiTgl" value="<?=$SampaiTgl?>" required  >
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
											<button type="submit" class="btn btn-md btn-default text-bold" id="btn_tampil"><i class="fa fa-search"></i> Tampilkan</button>
											<button class="btn btn-md bg-default disabled" id="btn_loading" style="display:none;"><i class="fa fa-spinner fa-spin"></i> &nbsp;Loading...</button>
										</td>
										<td></td>
									</tr>

								</table>

							</form>

						</div>

						<div></div>

					</div>
				</div>



			</div>



		</div>
		<div class="col-sm-12">
			<div id="tampilkan_disini"></div>
			<p></p>
		</div>

	</section>


</div>



<script src="<?=base_url('assets/select2_baru.js')?>"></script>

<script type="text/javascript">

	$(document).ready(function(){
		$('.select2').select2({
			width: '100%',
			dropdownCssClass: "myFont"
		});


		$('#FrmHutangKO_PHO').submit(function(event){
			event.preventDefault();
			table_HutangKOPHO();
		});

	});

	function table_HutangKOPHO(){
		var JobNo = $('#JobNo').val();
		var DariTgl = $('#DariTgl').val();
		var SampaiTgl = $('#SampaiTgl').val();

		jQuery.ajax({
			url: "<?=site_url('Laporan/Procurement/FrmRptPHOKO/table_HutangKOPHO')?>",
			type: 'POST',
			dataType: 'html',
			data: {JobNo: JobNo, DariTgl : DariTgl, SampaiTgl : SampaiTgl},
			beforeSend : function(){
				$('#btn_tampil').hide();
				$('#btn_loading').show();
				$('#tampilkan_disini').html('');
			},
			complete: function(xhr, textStatus) {
				$('#btn_tampil').show();
				$('#btn_loading').hide();
			},
			success: function(data, textStatus, xhr) {
				// console.log(data);
				$('#tampilkan_disini').html(data);
			},
			error: function(xhr, textStatus, errorThrown) {
				alert('error');
			}
		});
		
	}

</script>

