<style type="text/css">
	.mypadding{
		padding-top: 5px;
		padding-bottom: 5px;
	}
	.swal2-popup {
		font-size: 1.4rem !important;
	}
	@media (min-width: 768px) {
		.modal-xl {
			width: 95%;
			max-width:1500px;

			max-height: 1200px;
		}
	}


	.tulisan_14px{
		font-size: 14px !important;
	}

</style>

<div class="content-wrapper">

	<section class="content">
		<?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>

		<div class="row">
			<div class="col-sm-12">
				<table width="100%">
					<tr>
						<th style="border-bottom:1px solid silver;" class="tulisan_14px"><?=($judul) ? $judul : ''?></th>
					</tr>
				</table>
				<div class="box box-primary ">
					<div class="box-header with-border">
						<button class="btn btn-default btn-md btn_add_logo"><i class="fa fa-plus" ></i> ADD LOGO</button>
					</div>
					<div class="box-body">



						<div class="table-responsive">


							

							<table id="mytable" width="100%" border="1" class="table table-striped table-hover table-condensed table-bordered">
								<thead>
									<tr>
										<th>Foto</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$Logo = base_url('assets/logo_kosong.png');
									$LogoID = NULL;
									foreach ($data_logo as $row):
										$LogoID = $row->LogoID;
										$Logo = base_url('LogoKontrak/'.$row->LogoFoto);
										?>
										<tr>
											<td style="padding-top:20px;">
												<a  href="#" onclick="MyLogo('<?=$row->LogoID?>')" class="apa"><img src="<?=$Logo?>"  class="img" width="300px" height="200px" /></a>

												<!-- <button type='button' class='btn btn-info' data-toggle='modal' data-target='#myModal$x' data-id='$x' id='$x'>Open Modal $x</button><br />

												<div id="myModal<?php echo $x?>" class="modal fade" role="dialog">

													<div class="modal-dialog">

														<div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal">&times;</button>
																<h4 class="modal-title">Modal Header</h4>
															</div>
															<div class="modal-body">
																<h1><?=$row->LogoID?></h1>
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
															</div>
														</div>

													</div>
												</div> -->

											</td>
										</tr>
									<?php endforeach ?>
								</tbody>
							</table>
						</div>




						<!-- <table width="100%">
							<tr>
								<td width="5%">Job No</td>
								<td width="1%" class="text-center">:</td>
								<td width="30%" class="mypadding">
									<select name="JobNo" id="JobNo" class="form-control js-example-basic-single">
										<option value="">-- Pilih Job --</option>
										<?php foreach ($dataJob as $dataJob): ?>
											<option value="<?=$dataJob['JobNo']?>"><?=$dataJob['JobNo'].' - '.$dataJob['JobNm']?></option>
										<?php endforeach ?>
									</select>
								</td>
								<td></td>
							</tr>


							<tr>
								<td width="5%"></td>
								<td width="1%" class="text-center"></td>
								<td width="30%">
									<button id="tampilkan" class="btn btn-primary btn-md">TAMPILKAN</button>
								</td>
								<td></td>
							</tr>

						</table>
						<p></p> -->

						<div id="tampil_disini">



						</div>

					</div>
					<div class="overlay loading_box" style="display: none;">
						<i class="fa fa-refresh fa-spin"></i>
					</div>
				</div>
			</div>
		</div>

	</section>


</div>

<div class="modal fade" id="mymodal">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header mymodalheader">

			</div>
			<div class="modal-body mybody">

				<div class="row">
					<div class="col-sm-12">
						<form action="<?=site_url('LogoList/add_logo_action')?>" enctype="multipart/form-data" method="POST">
							<div class="form-group">
								<label for="exampleInputFile">Pilih Foto</label>
								<input type="file" accept="image/*" id="logo_saya" name="LogoFoto" class="form-control" required>

							</div>

							<div class="form-group">
								<img id="blah" src="<?=base_url('assets/logo_kosong.png')?>" width="200px" height="100px" alt="your image" />
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-default">SAVE</button>
							</div>

						</form>
					</div>
				</div>

			</div>


		</div>
	</div>
</div>


<div class="modal fade" id="modal_foto">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header mymodalheader">

			</div>
			<div class="modal-body body_foto">

				

			</div>


		</div>
	</div>
</div>


<script src="<?=base_url('assets/select2_baru.js')?>"></script>
<script type="text/javascript">
	$(document).ready(function(){

		$('#mytable').DataTable();

		$('#tampilkan').click(function(){
			tampilkan_table();
		});

		$('.js-example-basic-single').select2();


		$('.btn_add_logo').click(function(){
			$('#mymodal').modal('show');
		});

		logo_saya.onchange = evt => {
			const [file] = logo_saya.files
			if (file) {
				blah.src = URL.createObjectURL(file)
			}
		}


		$('.apa').click(function(){
			lihat();
		})


	});

	function MyLogo(LogoID){
		jQuery.ajax({
			url: '<?=site_url("LogoList/LihatFoto")?>',
			type: 'POST',
			dataType: 'html',
			data: {
				LogoID : LogoID,
			},
			beforeSend : function(){
				$('.loading_box').show();
			},
			success: function(data, textStatus, xhr) {
				$('.loading_box').hide();

				$('.body_foto').html(data);
				$('#modal_foto').modal('show');
			},
			error: function(xhr, textStatus, errorThrown) {
				alert('error');
			}
		});
	}

	// function LihatFoto(LogoID){
	// 	$('.body_foto').html('');
	// 	alert('aaa');
	// 	jQuery.ajax({
	// 		url: '<?=site_url("LogoList/LihatFoto")?>',
	// 		type: 'POST',
	// 		dataType: 'html',
	// 		data: {
	// 			LogoID : LogoID,
	// 		},
	// 		beforeSend : function(){
	// 			$('.loading_box').show();
	// 		},
	// 		success: function(data, textStatus, xhr) {
	// 			$('.loading_box').hide();

	// 			$('.body_foto').html(data);
	// 			$('#modal_foto').modal('show');
	// 		},
	// 		error: function(xhr, textStatus, errorThrown) {
	// 			alert('error');
	// 		}
	// }


	function AddLogo(){
		$('.mybody').html('');
		jQuery.ajax({
			url: '<?=site_url("LogoList/form_add_logo/")?>',
			type: 'POST',
			dataType: 'html',
			data: {
				flag : 'logo',
			},
			beforeSend : function(){
				$('.btn_add_logo').attr('disabled',true).text('Loading...');
			},
			success: function(data, textStatus, xhr) {
				$('.btn_add_logo').attr('disabled',true).text('ADD LOGO');
				$('.mybody').html(data);
				$('#mymodal').modal('show');
			},
			error: function(xhr, textStatus, errorThrown) {
				alert('error');
			}
		});
	}


	function tampilkan_table(){
		var JobNo = $('#JobNo :selected').val();

		$('#tampil_disini').html('');

		if (JobNo == '') {
			Swal.fire({
				position: 'top-end',
				icon: 'info',
				title: 'Job masih kosong',
				showConfirmButton: false,
				timer: 1000
			});
			return false;
		}
		jQuery.ajax({
			url: '<?=site_url("LogoList/table_logo/")?>',
			type: 'POST',
			dataType: 'html',
			data: {
				JobNo : JobNo,
			},
			cache:false,
			beforeSend : function(){
				$('.loading_box').show();
				$('#tampilkan').attr('disabled',true);
			},
			complete : function(){
				$('.loading_box').hide();
			},
			success: function(data, textStatus, xhr) {
				$('#tampilkan').attr('disabled',false);

				$('#tampil_disini').html(data);
			},
			error: function(xhr, textStatus, errorThrown) {
				Swal.fire('Fail!','Terjadi Kesalahan','error');
			}
		});


	}  




</script>