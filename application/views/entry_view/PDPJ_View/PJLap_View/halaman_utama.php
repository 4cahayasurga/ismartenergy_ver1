<?php
error_reporting(E_ALL);
?>
<style type="text/css">
	.huruf8{
		font-size: 9px !important;
		font-family:  Arial, "Helvetica Neue", Helvetica, sans-serif;
	}
	.huruf10{
		font-size: 10px !important;
		font-family:  Arial, "Helvetica Neue", Helvetica, sans-serif;
	}

	.huruf11{
		font-size: 11px !important;
		font-family:  Arial, "Helvetica Neue", Helvetica, sans-serif;
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
	}

	.tengah{
		vertical-align:middle;
	}

	#Form_PJLapangan{
		font-size: 13px !important;
	}

	#mytable thead tr th{
		font-size: 12px !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-weight: bold;
	}
	#mytable tbody tr td{
		font-size: 12px !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}

	#mytable tfoot{
		font-size: 13px !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-weight: bold;
	}
	

</style>

<div class="content-wrapper">

	<section class="content-header">
		<h1>
			<?=$judul?><br>
			<!-- <small><button class="btn btn-md btn-default" onClick="history.back()"><i class="fa fa-arrow-left"></i> BACK</button></small> -->
			<small><a class="btn btn-md btn-default" href="<?=site_url('Job/sub_job/'.$JobNo)?>" ><i class="fa fa-arrow-left"></i> BACK</a></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">Pertanggungjawaban Dana (Lapangan)</li>
		</ol>
	</section>

	<section class="content">
		<?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>
		<div class="box box-primary">
			<div class="box-header">
				<div class="row">
					<div class="col-sm-6">

						<form id="Form_PJLapangan">

							<table width="100%" border="0" class="huruf13">

								<tr>
									<td width="70px" class="text-left">Job No</td>
									<td width="10px" class="text-center">:</td>
									<td width="500px">
										<select name="JobNo" id="JobNo" class="form-control huruf13 select2" required>
											<option value="">:: PILIH JOB ::</option>
											<?php foreach ($ListJob as $row): ?>
												<option value="<?=$row->JobNo?>" <?=($JobNo !='' && ($JobNo == $row->JobNo)) ? 'selected' : '' ?>  ><?=$row->JobNo.' - '.$row->JobNm?></option>
											<?php endforeach ?>
										</select>
									</td>
									<td></td>
								</tr>

							</table>

							<table width="100%" border="0" style="margin-top:5px;" class="huruf13">

								<tr>
									<td width="70px">Alokasi</td>
									<td width="10px" class="text-center">:</td>
									<td width="300px">
										<select name="Alokasi" id="Alokasi" class="form-control huruf13" required>
											<option value="">:: PILIH ALOKASI ::</option>
											<?php foreach ($ListAlokasi as $row): ?>
												<option value="<?=$row->Alokasi?>" <?=($Alokasi !='' && ($Alokasi == $row->Alokasi)) ? 'selected' : '' ?> ><?=$row->Alokasi.' - '.$row->Keterangan?></option>
											<?php endforeach ?>
										</select>
									</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" style="margin-top:5px;" class="huruf14">
								<tr>
									<td width="70px"></td>
									<td width="10px" class="text-center"></td>
									<td width="300px">
										<button type="submit" class="btn btn-primary" id="btnTampilkan" >TAMPILKAN</button>
									</select>
								</td>
								<td></td>
							</tr>

						</table>

					</form>

				</div>
			</div>

			<!-- ==START BOX BODY -->
			<div class="box-body">
				<div id="tablePJLapangan"></div>
			</div>
			<!-- END BOX BODY -->

			<div class="overlay" id="GlobalLoading" style="display:none;">
				<i class="fa fa-refresh fa-spin"></i>
			</div>

		</div>
	</div>
</section>


</div>


<div id="ModalPJ" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title text-bold">Approval Confirmation?</h4>
			</div>
			<div class="modal-body">
				<form action="<?=site_url('Entry/PDPJ/PJLap_C/AksiApprove')?>" method="POST" id="FormModalPJ">
					<div class="table-responsive">
						<table width="100%" border="1">
							<tr>
								<th class="teksdisini"></th>
								<td>
									<input type="NoPD" name="NoPD">
								</td>
							</tr>
						</table>
					</div>
				</form>
			</div>

		</div>

	</div>
</div>

<div id="ModalSaya" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- header-->
			<div class="modal-header">
				<button class="close" data-dismiss="modal"><span>&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Approval Confirmation?</h4>
			</div>
			<!--body-->
			<div class="modal-body">
				<form id="FormModalSaya" class="form-horizontal" method="POST" action="<?=site_url('Entry/PDPJ/PJLap_C/AksiApprove')?>">
					<label class="control-label col-sm-2 pesan_disini"></label>
					<div class="form-group">
						<label class="control-label col-sm-2" for="NoPD"></label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="NoPD">
						</div>
					</div>
					
				</form>
			</div>
			<!--footer-->
			<div class="modal-footer">
				<button class="btn btn-danger" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>

<div id="ModalPesan" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- header-->
			<div class="modal-header">
				<button class="close" data-dismiss="modal"><span>&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"></h4>
			</div>
			<!--body-->
			<div class="modal-body">
				
			</div>
			<!--footer-->
			<div class="modal-footer">
				<button class="btn btn-danger" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>

<div id="ModalPesanPrint" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<!-- <p class="pesan_teks"></p> -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>

<div class="clearfix"></div>


<script src="<?=base_url('assets/select2_baru.js')?>"></script>
<script type="text/javascript">
	$(document).ready(function(){


		$('#JobNo').select2();
		
		toastr.options = {
			"debug": false,
			"positionClass": "toast-bottom-right",
			"onclick": null,
			"fadeIn": 300,
			"fadeOut": 1000,
			"timeOut": 5000,
			"extendedTimeOut": 1000
		}


		if ($('#JobNo option:selected').val() !='' && $('#Alokasi option:selected').val() !='') {
			tablePJLapangan();
		}

		$('#Form_PJLapangan').submit(function(event){
			event.preventDefault();
			tablePJLapangan();
		})

		$('#Form_PJLapangan #JobNo').on('change',function(){
			if ($('#Alokasi option:selected').val() !='') {
				tablePJLapangan();
			}	
		});

		$('#Form_PJLapangan #Alokasi').on('change',function(){
			if ($('#JobNo option:selected').val() !='') {
				tablePJLapangan();
			}	
		});

	});


	function tablePJLapangan()
	{

		var JobNo = $('#Form_PJLapangan  #JobNo option:selected').val();
		var Alokasi = $('#Form_PJLapangan #Alokasi option:selected').val();

		if (JobNo=='') {
			toastr.info('Info', 'Job masih kosong');
			return false;
		}

		if (Alokasi=='') {
			toastr.info('Info', 'Alokasi masih kosong');
			return false;
		}

		jQuery.ajax({
			url: "<?=site_url('Entry/PDPJ/PJLap_C/tablePJLapangan')?>",
			type: 'POST',
			dataType: 'html',
			data: $('#Form_PJLapangan').serialize(),
			cache : false,
			beforeSend : function(){
				$('#btnTampilkan').attr('disabled',true).text('Loading...');
			},
			complete: function(xhr, textStatus) {
				$('#btnTampilkan').attr('disabled',false).text('TAMPILKAN');
			},
			success: function(data, textStatus, xhr) {
				$('#tablePJLapangan').html('');
				$('#tablePJLapangan').html(data);
				
			},
			error: function(xhr, textStatus, errorThrown) {
				toastr.error('error', 'Error');
				return false;
			}
		});


	}

	function btnExportPDF(NoUrut='')
	{
		if (NoUrut=='') {
			return false;
		}

		var TxtRejectBy = $('#btnExportPDFID'+NoUrut).attr('data-TxtRejectBy');
		var TxtNoPJ = $('#btnExportPDFID'+NoUrut).attr('data-TxtNoPJ');
		var NoPD = $('#btnExportPDFID'+NoUrut).attr('data-NoPD');

		if (TxtRejectBy!='') {
			$('#ModalPesanPrint .modal-body').html('<h3>'+NoPD+' sudah di-reject oleh '+TxtRejectBy+'</h3>');
			$('#ModalPesanPrint').modal('show');
		}

		if (TxtNoPJ=='') {
			$('#ModalPesanPrint .modal-body').html('<h3>'+NoPD+' belum entry uraian PJ.</h3>');
			$('#ModalPesanPrint').modal('show');
		}

	}

	
</script>


