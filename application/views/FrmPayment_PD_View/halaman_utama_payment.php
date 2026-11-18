<style type="text/css">

	.jarak{
		padding-top: 5px !important;
		padding-bottom: 5px !important;
	}
	.huruf_10{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size: 10px !important;
	}
	.huruf_12{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size: 12px !important;
	}
	.huruf_14{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size: 10px !important;
	}

	.swal2-popup {
		font-size: 1.2rem !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}
</style>



<div class="content-wrapper">

	<div class="content-header">
		<h1 class="text-bold"><?=($judul) ? $judul : '' ?></h1>
	</div>

	<div class="content">
		<?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>
		<div class="row">

			<div class="col-sm-12" style="border:0px solid;">

				<div class="box box-danger">
					<div class="box-header">
						<div class="box-tools pull-right">
							<button class="btn btn-default btn-md" id="btn_segarkanTable" title="Refresh Table"  ><i class="fa fa-refresh"></i></button>
						</div>
					</div>
					<div class="box-body">
						<label class="radio-inline">
							<input type="radio" name="TipePembayaran" value="0" <?=($TipePembayaran == 0) ? 'checked' : '' ?> >Yang Belum Dibayar
						</label>
						<label class="radio-inline">
							<input type="radio" name="TipePembayaran" value="1" <?=($TipePembayaran == 1) ? 'checked' : '' ?> >Yang Sudah Dibayar
						</label>


						<div class="row" style="margin-top:10px !important;display: none;" id="tampil_TglBayar_Validasi" >
							<div class="col-sm-12">
								<table width="100%" border="0" class="huruf_12"  >
									<tr>
										<td width="5%">Tgl Bayar</td>
										<td style="width:1%;padding-left: 5px;padding-right: 5px;text-align: center;">:</td>
										<td width="12%">
											<input type="date" name="DariTgl" id="DariTgl_TglBayar" class="form-control input-sm huruf_12" value="<?=$DariTgl?>" disabled >
										</td>
										<td style="width:2%;padding-left: 5px;padding-right: 5px;">s.d</td>
										<td width="12%">
											<input type="date" name="SampaiTgl" id="SampaiTgl_TglBayar" class="form-control input-sm huruf_12" value="<?=$SampaiTgl?>" disabled >
										</td>
										<td></td>
									</tr>
								</table>
							</div>
						</div>
					</div>

					<div class="box-footer">
						<div id="loading_payment" style="display:none;">Loading... <i class="fa fa-spinner fa-spin"></i></div>
						<div id="show_table_payment"></div>
					</div>

				</div>

			</div>

		</div>

	</div>

</div>




<div id="MyModalPembayaran" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title " id="MyModalPembayaran_Title" style="font-size:16px !important;"></h4>
			</div>
			<div class="modal-body" id="MyModalPembayaran_Body">
				
				

			</div>

		</div>

	</div>
</div>



<script type="text/javascript">

	window.onload = function() {
		show_payment_tipe();
	}

	function atur_tglbayar(){
		var TipePembayaran = $('input[name="TipePembayaran"]:checked').val();
		var DariTgl = $('#DariTgl_TglBayar').val();
		var SampaiTgl = $('#SampaiTgl_TglBayar').val();
		if (TipePembayaran == '1') {
			$('#tampil_TglBayar_Validasi').css("display","");
			$('#DariTgl_TglBayar').attr('disabled',false);
			$('#SampaiTgl_TglBayar').attr('disabled',false);
		}else{
			$('#tampil_TglBayar_Validasi').css("display","none");
			$('#DariTgl_TglBayar').attr('disabled',true);
			$('#SampaiTgl_TglBayar').attr('disabled',true);
		}
	}


	$(function(){

		atur_tglbayar();
		var DariTgl = $('#DariTgl_TglBayar').val();
		var SampaiTgl = $('#SampaiTgl_TglBayar').val();

		$('#DariTgl_TglBayar, #SampaiTgl_TglBayar').change(function(){
			show_payment_tipe();
		});

		$('input[type=radio][name=TipePembayaran]').change(function(){
			atur_tglbayar();
			show_payment_tipe();

		});

		$('#btn_segarkanTable').click(function(){
			show_payment_tipe();
		});

	});

	function show_payment_tipe(){

		var TipePembayaran = $('input[type=radio][name=TipePembayaran]:checked').val();

		var DariTgl = $('#DariTgl_TglBayar').val();
		var SampaiTgl = $('#SampaiTgl_TglBayar').val();


		jQuery.ajax({
			url: '<?=site_url("FrmPayment_PD/getTablePayment")?>',
			type: 'POST',
			dataType: 'html',
			data: {TipePembayaran : TipePembayaran, DariTgl : DariTgl, SampaiTgl : SampaiTgl },
			beforeSend : function(){
				$('#loading_payment').show();
				$('#show_table_payment').html('');
			},
			complete: function(xhr, textStatus) {
				$('#loading_payment').hide();
			},
			success: function(data, textStatus, xhr) {
				$('#show_table_payment').html(data);
			},
			error: function(xhr, textStatus, errorThrown) {
				alert('error');
			}
		});
	}

	function entry_payment(){
		$('#modal_entry_payment').modal('show');
	}


	function FrmPembayaran(NoPD=''){
		if (NoPD == '') {
			return false;
		}

		jQuery.ajax({
			url: '<?=site_url('FrmPayment_PD/tampil_FrmPembayaran')?>',
			type: 'POST',
			dataType: 'html',
			data: {NoPD: NoPD},
			beforeSend : function(){
				$('#loading_pd'+NoPD).show();
				$('#data_PD'+NoPD).hide();
				$('#MyModalPembayaran_Body').html('');
				$('#MyModalPembayaran_Title').html('');
			},
			complete: function(xhr, textStatus) {
				$('#loading_pd'+NoPD).hide();
				$('#data_PD'+NoPD).show();
			},
			success: function(data, textStatus, xhr) {
				// console.log(data);
				$('#MyModalPembayaran_Body').html(data);
				$('#MyModalPembayaran_Title').text('Form Entry Pembayaran');
				$('#MyModalPembayaran').modal('show');
			},
			error: function(xhr, textStatus, errorThrown) {
				alert(data);
			}
		});

	}



	function deletePayment(LedgerNo='',NoPD='')
	{
		if (LedgerNo == '') {
			return false;
		}

		var TipePembayaran = $('input[name="TipePembayaran"]:checked').val();

		var del=confirm("Yakin ingin menghapus No "+NoPD+"? ");
		if (del==true){
			jQuery.ajax({
				url: '<?=site_url('FrmPayment_PD/AksiDeletePembayaran')?>',
				type: 'POST',
				dataType: 'HTML',
				data: {LedgerNo : LedgerNo, NoPD: NoPD},
				beforeSend : function(){
					$('#btn_loading'+LedgerNo).show();
					$('#btn_delete'+LedgerNo).hide();
					$('#btn_print'+LedgerNo).hide();;
				},
				complete: function(xhr, textStatus) {
					$('#btn_loading'+LedgerNo).hide();
					$('#btn_delete'+LedgerNo).show();
					$('#btn_print'+LedgerNo).show();
				},
				success: function(data, textStatus, xhr) {
					if (data == 'pj_ada') {
						Swal.fire('Info!','Tidak bisa DELETE karena sudah ada PJ.','info');
						return false;
					}

					if (data == 'gagal') {
						Swal.fire('Error!','Gagal menghapus data','error');
						return false;
					}

					if (data == 'berhasil') {
						Swal.fire('Success!','data telah dihapus','success');
						show_payment_tipe();
						return false;
					}
				},
				error: function(xhr, textStatus, errorThrown) {
					alert('error');
				}
			});
		}




	}

</script>

