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
		padding-top: 10px !important;
		padding-bottom: 10px !important;
	}

	.tbl_harga{
		font-size: 10px;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
		width: 100%;

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



					<table  width="100%" border="0" class="huruf_set" style="margin-top:5px;" >
						<tr>
							<td style="width:33%;">
								<button id="btn_tampil" class="btn btn-sm btn-success">Tampilkan Data <i class="fa fa-search"></i> </button>
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


		$('#btn_tampil').click(function(){
			dapatkan_list_data_harga();
		});

	});

	function dapatkan_list_data_harga(){

		jQuery.ajax({

			url: '<?= site_url("MyReport/Procurement/DaftarHarga/dapatkan_list_data_harga") ?>',
			type: 'post',
			dataType: 'html',
			data: { 
				'flag' : 'daftar_harga'
			},
			beforeSend : function(){
				$('#btn_tampil').hide();
				$('#btn_loading').show();
				$('#tampil_table').html('');
			},
			complete: function(xhr, textStatus) {
				$('#btn_tampil').show();
				$('#btn_loading').hide();
				list_harga_json();
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