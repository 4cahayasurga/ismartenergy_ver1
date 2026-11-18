<style>
	.tbl_khusus_invoice{
		font-size: 10px;
		font-family: Verdana, Geneva, Tahoma, sans-serif;
	}
	
</style>

<!-- <form id="form-simpan-invoice"> -->
	<table width="100%" border="0">
		<tr >
			<td style="width:8%;">Total Invoice : </td>
			<td style="width: 25%;">
				<input type="text" id="TotalInvoice_sum" class="form-control input-sm" value="<?=UangBiasa($TotalInvoice)?>" readonly>
			</td>
			<td style="width: 10%;"></td>
			<td style="width:8%;">Total Centang : </td>
			<td style="width: 25%;">
				<input type="text" id="TotalBayar_sum" class="form-control input-sm" value="<?=UangBiasa($TotalBayar)?>" readonly>
			</td>
			<td></td>
			
		</tr>
	</table>


	<table width="100%" border="0" style="border-collapse:collapse;border-bottom: 1px solid silver;" class="tbl_khusus_invoice">
		<tr >
			<td class="text-center" style="border-bottom: 1px solid silver;">
				<div class="checkbox">
					<label>
						<input type="checkbox" id="centang_semua" class="centang_all" <?=($jumlah_centang === count($mydataInv)) ? 'checked' : ''  ?>  > 
					</label	>
				</div>
			</td>
			<td class="text-center" style="border-bottom: 1px solid silver;">No</td>
			<td style="border-bottom: 1px solid silver;">No. KO</td>
			<td style="border-bottom: 1px solid silver;">Vendor</td>
			<td style="border-bottom: 1px solid silver;">No. Invoice</td>
			<td style="border-bottom: 1px solid silver;">Tgl. Invoice</td>
			<td style="border-bottom: 1px solid silver;">Jatuh Tempo</td>
			<td class="text-right" style="border-bottom: 1px solid silver;" >Total (Rp)</td>
			<td class="text-right" style="border-bottom: 1px solid silver;" >PPN (Rp)</td>
			<td style="border-bottom: 1px solid silver;" >&nbsp;&nbsp;No. FP</td>
			<td style="border-bottom: 1px solid silver;" >Tgl. FP</td>
			<td class="text-right" style="border-bottom: 1px solid silver;" >Total Bayar (Rp)</td>
		</tr>
		<?php
		$NoUrut_Invoice = 1;
		$cek_centang = '';
		$MyLedgerNo = '';
		// echo count($mydataInv);
		foreach($mydataInv as $mydataInv) :
			$cek_centang = ($mydataInv['status_centang'] === 1) ? 'checked="checked"' : '';
			?>

			<tr class="coba" data-row_LedgerNo="<?=$mydataInv['LedgerNo']?>" >
				<td class="text-center">
					<input type="checkbox" class="inv_pilih loading_<?=$mydataInv['LedgerNo']?>" data-LedgerNo="<?=$mydataInv['LedgerNo']?>"  name="inv_pilih"  value="<?=$mydataInv['LedgerNo']?>" data-JobNo="<?=$JobNo?>"
					data-Alokasi="<?=$Alokasi?>"
					data-NoPD="<?=$NoPD?>"
					data-NoKO="<?=$NoKO?>" 
					data-InvNo="<?=$mydataInv['InvNo']?>"
					data-TotalPayment="0"
					<?=$cek_centang?>


					>
					<button class="btn btn-xs btn-danger huruf_ukuran btn_loading_<?=$mydataInv['LedgerNo']?>" style="display: none;"  disabled="disabled"><i class="fa fa-spinner fa-spin"></i> </button>
				</td>
				<td class="text-center"><?=$NoUrut_Invoice;?></td>
				<td><?=$mydataInv['NoKO']?></td>
				<td><?=$mydataInv['VendorNm']?></td>
				<td><?=$mydataInv['InvNo']?></td>
				<td><?=TanggalSaya($mydataInv['InvDate'])?></td>
				<td><?=TanggalSaya($mydataInv['DueDate'])?></td>
				<td class="text-right" ><?=uang_koma_genap_decimal($mydataInv['Total'])?></td>
				<td class="text-right" ><?=new_decimal($mydataInv['PPN'])?></td>
				<td>&nbsp;&nbsp;<?=$mydataInv['FPNo']?></td>
				<td><?=TanggalSaya($mydataInv['FPDate'])?></td>
				<!-- <td class="text-right" ><?=new_decimal($mydataInv['TotalPayment'])?></td> -->
				<!-- <td width="5%"></td> -->
				<td class="text-right" style="padding-left:10px;width:20%;">
					<input type="text"  class="form-control text-right PaymentKetik TotalPayment_LedgerNo<?=$mydataInv['LedgerNo']?>" placeholder="0" value="<?=number_format((float)$mydataInv['TotalPayment'], 0, '.', '')?>" onkeypress="return hanyaAngka(event)" <?=($mydataInv['status_centang'] === 1) ? 'disabled="disabled"' : '' ?> >
				</td>
			</tr>


			<?php
			$NoUrut_Invoice++;
		endforeach; ?>
	</table>
	<table width="100%" border="0">
		<tr>
			<td class="text-left">
				<!-- <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Batal</button> -->
			</td>
			<td class="text-right">
				<!-- <button type="submit" class="btn btn-sm btn-primary btn-simpan-inv">Simpan Invoice</button> -->
				<!-- <button class="btn btn-sm btn-danger huruf_ukuran " id="btn-inv-wait" style="display: none;"  disabled="disabled">Loading... <i class="fa fa-spinner fa-spin"></i> </button> -->

			</td>
		</tr>
	</table>
	<!-- </form> -->


	<div class="row">
		<div class="col-sm-12 sini" >

		</div>
	</div>

	<script>
		$(document).ready(function(){

			if($('#centang_semua').is(':checked')){
				aksi_centang_semua();
			}else{
				$('.inv_pilih').trigger('change');
				hitung_centang();
			}



			$('.inv_pilih').change(function(){

				var status = 0;
				var LedgerNo = this.value;

				if ($(this).is(':checked')) {
					status = 1;
					$('.TotalPayment_LedgerNo'+LedgerNo).attr('disabled',true);

				}else{
					$('.TotalPayment_LedgerNo'+LedgerNo).attr('disabled',false);
				}

				var JobNo = $(this).attr('data-JobNo');
				var Alokasi = $(this).attr('data-Alokasi');
				var NoPD = $(this).attr('data-NoPD');
				var NoKO = $(this).attr('data-NoKO');
				var InvNo = $(this).attr('data-InvNo');

				var TotalPayment = $(this).attr('data-TotalPayment');

				jQuery.ajax({
					url: '<?=site_url('Permintaan_dana_lapangan/simpan_inv_per_row')?>',
					type: 'POST',
					dataType: 'json',
					data: {

						'LedgerNo': LedgerNo,
						'JobNo' : JobNo,
						'Alokasi' : Alokasi,
						'NoPD' : NoPD,
						'NoKO' : NoKO,
						'InvNo' : InvNo,
						'status' : status,
						'TotalPayment' : TotalPayment
					},
					beforeSend : function(){
						$('.loading_'+LedgerNo).hide();
						$('.btn_loading_'+LedgerNo).show();
					},
					complete: function(xhr, textStatus) {
						$('.loading_'+LedgerNo).show();
						$('.btn_loading_'+LedgerNo).hide();
					},
					success: function(data, textStatus, xhr) {


						if (data.kondisi == 'berhasil') {


							$.toast({
								text: 'Invoice berhasil '+data.status_centang ,
								position: 'top-right',
								stack: false,
								hideAfter: 2000,
								icon: (data.status_centang == 'disimpan') ? 'success' : 'info',

							});
							return false;

						}

						$.toast({
							text: 'Gagal menyimpan/menghapus invoice',
							position: 'top-right',
							stack: false,
							hideAfter: 2000,
							icon: 'error',

						});
						return false;

					},
					error: function(xhr, textStatus, errorThrown) {
						$.toast({
							text: 'Terjadi Kesalahan',
							position: 'top-right',
							stack: false,
							hideAfter: 2000,
							icon: 'error',

						});
						return false;
					}
				});

				hitung_centang();
			});



			$('#centang_semua').change(function(){
				aksi_centang_semua();
			});

			$('.inv_pilih').change(function(){
				hitung_centang();
			});

			$('.PaymentKetik').keyup(function(){

				hitung_centang();
			});

		});

		function aksi_centang_semua(){

			var centang_all = $( "input[type='checkbox'].centang_all" );
			if(centang_all.is(':checked')){
				$( "input[type='checkbox'].inv_pilih").prop({
					checked : true,
				});
			}else{
				$( "input[type='checkbox'].inv_pilih").prop({
					checked : false,
				});
			}

			$('.inv_pilih').trigger('change');

			hitung_centang();

		}

		function hitung_centang(){

			var sum_centang = 0;
			var TotalBayar_yang_dicentang = 0;

			$('input[type="checkbox"].inv_pilih:checked').each(function(){
				var LedgerNo = $(this).attr('data-LedgerNo');
				var TotalPayment_LedgerNo = $('.TotalPayment_LedgerNo'+LedgerNo).val();
				$(this).attr('data-TotalPayment',TotalPayment_LedgerNo);

				sum_centang += Number(TotalPayment_LedgerNo);

			});

			$('input[type="checkbox"].inv_pilih').each(function(){
				var LedgerNo = $(this).attr('data-LedgerNo');
				var TotalPayment_LedgerNo = $('.TotalPayment_LedgerNo'+LedgerNo).val();
				$(this).attr('data-TotalPayment',TotalPayment_LedgerNo);

			});

			$('#TotalBayar_sum').val(0);
			$('input[name="TotalBayar"]').val(0);

			$('#TotalBayar_sum').val(ubah_uang_js(sum_centang));
			$('input[name="TotalBayar"]').val(sum_centang);

		}


	</script>