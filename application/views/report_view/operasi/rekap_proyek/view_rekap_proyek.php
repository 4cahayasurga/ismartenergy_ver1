<style>
	.mykonten {
		font-family: Arial, Helvetica, sans-serif !important;
		font-size: 14px !important;
	}

	.ukuran_select2 {
		font-size: 12px !important;
		font-family: Arial, Helvetica, sans-serif;

	}
	.select2-results__option { 
		font-family: Arial, Helvetica, sans-serif !important;
		font-size: 14px !important;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h3 style="font-weight:bold;"><?=$judul?></h3>

		<ol class="breadcrumb">
			<li><a href="<?= site_url('dashboard')  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?=$judul?></li>
		</ol>
	</section>

	<section class="content-header">

		<button class="btn btn-md btn-danger text-white "  onclick="history.back()"><i class="fa fa-arrow-left"></i> Back</button>


	</section>

	<section class="content">
		<?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>

		<div class="box box-primary">


			<div class="box-body">

				<div class="row">

					<div class="col-sm-6" style="font-size:14px;">

							
							<table border="0" width="100%">
								<tr>
									<th style="padding-top:5px;padding-bottom:5px;" width="10%">Tahun</th>
									<td style="padding-top:5px;padding-bottom:5px;" width="15%">
										<input type="number" name="Tahun" id="Tahun" maxlength="4" minlength="1" class="form-control input-md"value="<?=date('Y')?>" required>
									</td>
									<td></td>
								</tr>
							</table>

							<table border="0" width="100%">
								<tr>
									<th style="padding-top:5px;padding-bottom:5px;" width="10%"></th>
									<td style="padding-top:5px;padding-bottom:5px;" width="10%">
										<button type="button" name="btn_tampil" id="btn_tampil" class="btn btn-md bg-purple"><i class="fa fa-search"></i> Tampilkan</button>
									</td>
									<td></td>
								</tr>
							</table>
						



					</div>

				</div>






			</div>
			<div class="overlay loadingku" style="display: none;">
				<i class="fa fa-refresh fa-spin"></i>
			</div>


		</div>

		<div class="tampil_table"></div>


	</section>


</div>


<script>



	$(function(){

		$('#btn_tampil').click(function(){
			getRekapProyek();
		});

	})


	function getRekapProyek(){

		var Tahun = $('#Tahun').val();

		jQuery.ajax({
			url: '<?=site_url('MyReport/Operasi/RekapProyek/getData')?>',
			type: 'POST',
			dataType: 'HTML',
			cache : false,
			data: {
				'Tahun' : Tahun
			},
			beforeSend : function(){
				$('.loadingku').show();
				$('.tampil_table').html('');
			},
			success: function(data, textStatus, xhr) {
				// alert(data);
				$('.loadingku').hide();
				$('.tampil_table').html(data);
			},
			error: function(xhr, textStatus, errorThrown) {
				$.toast({
					text: 'Terjadi Kesalahan',
					position: 'bottom-right',
					stack: false,
					hideAfter: 1000,
					icon: 'error',

				});
				return false;
			}
		});



	}




</script>