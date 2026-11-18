
<style type="text/css">
	.content-wrapper{
		font-family: "Segoe UI";
		font-size: 12px;
	}
	.huruf13{
		font-size: 13px !important;
	}
	.huruf12{
		font-size: 12px !important;
	}
	.huruf14{
		font-size: 14px !important;
	}
	.huruf15{
		font-size: 15px !important;
	}
	.huruf16{
		font-size: 16px !important;
	}
	.border_merah{
		border:1px solid red;
	}
	.border_biasa{
		border:1px solid;
	}

	.marginatas3{
		margin-top: 3px;
	}

	.marginatas5{
		margin-top: 5px;
	}
</style>
<div class="content-wrapper">

	<section class="content-header">
		<h1 style="text-decoration: underline;"><?=$judul?></h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?=$judul?></li>
		</ol>
		<br>
		<button onclick="history.back()" class="btn btn-sm btn-danger "><i class="fa fa-arrow-left"></i> BACK</button>
	</section>

	<div class="content">

		<div class="box box-primary">
			<div class="box-header">
				<div class="row">
					<div class="col-sm-12">
						<?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>
						<form id="myform">
							<div class="table-responsive">
								
								<table width="100%" border="0">
									<tr>
										<td width="100px"> Job No </td>
										<td width="10px" class="text-center">:</td>
										<td width="500px">
											<select name="JobNo" id="JobNo" class="form-control input-lg select2 huruf12" required>
												<option value="">:: Pilih Salah Satu ::</option>
												<?php foreach ($ListJob as $row): ?>
													<option value="<?=$row->JobNo?>" <?=($JobNo == $row->JobNo) ? 'selected' : '' ?>  ><?=$row->JobNo.' - '.$row->JobNm?></option>
												<?php endforeach ?>
											</select>
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas3">
									<tr>
										<td width="100px"> Alokasi </td>
										<td width="10px" class="text-center">:</td>
										<td width="500px">
											<select name="Alokasi" id="Alokasi" class="form-control input-lg select2 huruf12" required>
												<option value="">:: Pilih Salah Satu ::</option>
												<?php foreach ($ListAlokasi as $row): ?>
													<option value="<?=$row->Alokasi?>" <?=($Alokasi == $row->Alokasi) ? 'selected' : '' ?>  ><?=$row->Alokasi.' - '.$row->Keterangan?></option>
												<?php endforeach ?>
											</select>
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas3">
									<tr>
										<td width="100px">  </td>
										<td width="10px" class="text-center"></td>
										<td width="500px">
											<button type="submit" class="btn btn-primary btn-md" id="btnTampilkan">TAMPILKAN</button>
											<button type="button" class="btn btn-primary btn-md" id="btnLoading" disabled="disabled" style="display:none;">LOADING...</button>
										</td>
										<td></td>
									</tr>
								</table>

							</div>
						</form>

						
					</div>
				</div>
			</div>
			<div class="box-body">
				<div id="table_RejectPD">
					
				</div>
			</div>
		</div>

	</div>

</div>


<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Reject Confirmation </h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12 text-center" >
						<h4 >Anda yakin ingin REJECT data berikut?</h4>
						<h4  class="detail"></h4>
					</div>
					<div class="col-sm-12">

						<form class="form-horizontal"  action="<?=site_url('Entry/PDPJ/RejectPD_C/aksi_reject')?>" method="POST">

							<div class="form-group">
								<label class="control-label col-sm-3" for="AlasanReject">Alasan Reject:</label>
								<div class="col-sm-9">
									<input type="hidden" class="form-control" name="NoPD" id="NoPD" readonly required>
									<input type="hidden" class="form-control" name="JobNo" id="JobNo" readonly required>
									<input type="hidden" class="form-control" name="Alokasi" id="Alokasi" readonly required>
									<textarea name="AlasanReject" id="AlasanReject" class="form-control input-sm" placeholder="Isi Disini" required></textarea>
								</div>
							</div>

							<div class="form-group text-right">
								<div class="col-sm-offset-3 col-sm-9">
									<button type="submit" class="btn btn-primary">REJECT</button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>
								</div>
							</div>
						</form>


					</div>
				</div>
			</div>

		</div>

	</div>
</div>

<div class="clearfix"></div>


<script src="<?=base_url('assets/select2_baru.js')?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.select2').select2(); 
		// table_RejectPD();

		if ($('#myform #Alokasi option:selected').val()!='' &&  $('#myform #JobNo option:selected').val() !='') {
			table_RejectPD();
		}

		$('#myform').submit(function(e){
			e.preventDefault();
			table_RejectPD();
		})

		$('#myform #JobNo').change(function(){
			if ($('#myform #Alokasi option:selected').val()!='' && this.value !='') {
				table_RejectPD();
			}
		});

		$('#myform #Alokasi').change(function(){
			if ($('#myform #JobNo option:selected').val()!='' && this.value !='') {
				table_RejectPD();
			}
		});

	});

	function table_RejectPD(){
		var JobNo = $('#myform #JobNo option:selected').val();
		var Alokasi = $('#myform #Alokasi option:selected').val();

		$.ajax({
			url: "<?=site_url('Entry/PDPJ/RejectPD_C/table_RejectPD')?>",
			type: "POST",
			dataType: "HTML",
			data: {JobNo : JobNo, Alokasi : Alokasi},
			beforeSend : function(){
				$('#btnTampilkan').hide();
				$('#btnLoading').show();

				$('#myform #JobNo').attr('disabled',true);
				$('#myform #Alokasi').attr('disabled',true);
			}, complete : function(){
				$('#btnTampilkan').show();
				$('#btnLoading').hide();

				$('#myform #JobNo').attr('disabled',false);
				$('#myform #Alokasi').attr('disabled',false);

			}
		})
		.done(function(data) {
			$('#table_RejectPD').html(data);
		})
		.fail(function() {
			alert('Error');
			return false
		});
		

		// alert(JobNo+' - '+Alokasi);
	}

	function btnReject(NoUrut=''){
		if (NoUrut=='') {
			return false;
		}

		var RejectStatus = $('#btnRejectID'+NoUrut).attr('data-RejectStatus');
		if (RejectStatus!='') {
			alert(RejectStatus);
			return false;
		}

		var NoPD = $('#btnRejectID'+NoUrut).attr('data-NoPD');
		var Deskripsi =  $('#btnRejectID'+NoUrut).attr('data-Deskripsi');
		Deskripsi =  (Deskripsi=='' || Deskripsi==null) ? '' : ' - '+Deskripsi;

		var JobNo = $('#myform #JobNo option:selected').val();
		var Alokasi = $('#myform #Alokasi option:selected').val();

		$('#myModal').modal('show');
		$('#myModal .modal-body .detail').text(NoPD+Deskripsi);

		$('#myModal .modal-body #AlasanReject').val('');
		$('#myModal .modal-body input[name="NoPD"]').val(NoPD);

		$('#myModal .modal-body input[name="JobNo"]').val(JobNo);
		$('#myModal .modal-body input[name="Alokasi"]').val(Alokasi);

	}


</script>