<style type="text/css">
	.tbl_form tr td {
		padding-bottom: 5px;
		font-size: 12px;
	}

	.tbl_form input,
	textarea {
		font-size: 12px;
	}

	.tbl_form textarea {
		font-size: 12px;
	}

	.tbl_form input[type="date"] {
		font-size: 12px;
	}
</style>
<div class="row">
	<div class="col-sm-12">

		<table border="0" width="100%" style="border-bottom:1px solid silver;margin-bottom:10px;">
			<tr>
				<th style="padding-bottom:10px;" class="huruf_14">Entry Invoice</th>
				<td style="text-align:right;padding-bottom:10px;">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</td>
			</tr>
		</table>

	</div>
</div>

<div class="row">
	<div class="col-sm-12">

		<!-- <form method="POST" action="<?= site_url('Entry/Procurement/Invoice/aksi_simpan_invoice') ?>"> -->
		<form id="form_simpan_invoice">
			<table border="0" width="100%" class="tbl_form huruf_12">
				<tr>
					<td width="15%">No. KO</td>
					<td style="width:2%;padding-left:3px;padding-right:3px;">:</td>
					<td width="83%">
						<select name="NoKO" id="NoKO_cari" class="form-control input-sm select2" required>
							<option value="">-- Pilih --</option>
							<?php foreach ($list_KO as $list_KO) : ?>
								<option value="<?= $list_KO->NoKO ?>"><?= $list_KO->NoKO . ' - ' . $list_KO->TglKO . ' - ' . $list_KO->VendorNm . ' - ' . $list_KO->TotalKO ?></option>
							<?php endforeach ?>

						</select>
						<!-- <input type="text" name="NoKO" id="NoKO_cari" class="form-control input-sm" required> -->
					</td>
					<td></td>
				</tr>
			</table>

			<table border="0" width="100%" class="tbl_form">
				<tr>
					<td width="15%">No. MOS</td>
					<td style="width:2%;padding-left:3px;padding-right:3px;">:</td>
					<td width="70%">
						<input type="text" name="NoMOS" class="form-control input-sm" placeholder="Isi Disini" disabled>
					</td>
					<td></td>
				</tr>
			</table>

			<table border="0" width="100%" class="tbl_form">
				<tr>
					<td width="15%">No. Invoice</td>
					<td style="width:2%;padding-left:3px;padding-right:3px;">:</td>
					<td width="50%">
						<input type="text" name="InvNo" id="InvNo" class="form-control input-sm" placeholder="Isi Disini" required>
					</td>
					<td></td>
				</tr>
			</table>

			<table border="0" width="100%" class="tbl_form">
				<tr>
					<td width="15%">Tgl. Invoice</td>
					<td style="width:2%;padding-left:3px;padding-right:3px;">:</td>
					<td width="15%">
						<input type="date" name="InvDate" id="InvDate" class="form-control input-sm" placeholder="Isi Disini" required>
					</td>
					<td></td>
				</tr>
			</table>

			<table border="0" width="100%" class="tbl_form">
				<tr>
					<td width="15%">Jatuh Tempo</td>
					<td style="width:2%;padding-left:3px;padding-right:3px;">:</td>
					<td width="15%">
						<input type="date" name="DueDate" id="DueDate" class="form-control input-sm" placeholder="Isi Disini" required>
					</td>
					<td></td>
				</tr>
			</table>

			<table border="0" width="100%" class="tbl_form">
				<tr>
					<td width="15%">No. Faktur Pajak</td>
					<td style="width:2%;padding-left:3px;padding-right:3px;">:</td>
					<td width="40%">
						<input type="text" name="FPNo" id="FPNo" class="form-control input-sm FP" placeholder="Isi Disini">
					</td>
					<td></td>
				</tr>
			</table>

			<table border="0" width="100%" class="tbl_form">
				<tr>
					<td width="15%">Tgl. Faktur Pajak</td>
					<td style="width:2%;padding-left:3px;padding-right:3px;">:</td>
					<td width="15%">
						<input type="date" name="FPDate" class="form-control input-sm" placeholder="Isi Disini">
					</td>
					<td></td>
				</tr>
			</table>

			<table border="0" width="100%" class="tbl_form">
				<tr>
					<td width="15%">Total</td>
					<td style="width:2%;padding-left:3px;padding-right:3px;">:</td>
					<td width="20%">
						<input type="text" name="Total" id="Total" class="form-control input-sm" placeholder="0" required>
						<!-- <span class="text-red" style="font-size:9px !important;">*gunakan titik (.) untuk decimal </span> -->
					</td>
					<td></td>
				</tr>
			</table>

			<table border="0" width="100%" class="tbl_form">
				<tr>
					<td width="15%">PPN (Rp)</td>
					<td style="width:2%;padding-left:3px;padding-right:3px;">:</td>
					<td width="20%">
						<input type="text" name="PPN" id="PPN" class="form-control input-sm" placeholder="0">
						<!-- <span class="text-red" style="font-size:9px !important;">*gunakan titik (.) untuk decimal </span> -->
					</td>
					<td></td>
				</tr>
			</table>

			<table border="0" width="100%" class="tbl_form">
				<tr>
					<td width="15%">PPH (Rp)</td>
					<td style="width:2%;padding-left:3px;padding-right:3px;">:</td>
					<td width="20%">
						<input type="text" name="PPH" id="PPH" class="form-control input-sm" placeholder="0">
						<!-- <span class="text-red" style="font-size:9px !important;">*gunakan titik (.) untuk decimal </span> -->
					</td>
					<td></td>
				</tr>
			</table>

			<table border="0" width="100%" class="tbl_form">
				<tr>
					<td width="15%">Keterangan</td>
					<td style="width:2%;padding-left:3px;padding-right:3px;">:</td>
					<td width="40%">
						<textarea name="Keterangan" placeholder="Isi Disini" class="form-control input-sm"></textarea>
					</td>
					<td></td>
				</tr>
			</table>

			<table border="0" width="100%" class="tbl_form">
				<tr>
					<td width="15%"></td>
					<td style="width:2%;padding-left:3px;padding-right:3px;"></td>
					<td width="40%">
						<button type="submit" class="btn btn-sm btn-primary" id="btn_simpan_invoice">Simpan</button>
						<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Batal</button>
					</td>
					<td></td>
				</tr>
			</table>

			<input type="hidden" name="JobNo" value="<?= $JobNo ?>">
			<input type="hidden" class="form-control" id="Total_tanpa_koma">
			<input type="hidden" class="form-control" id="PPN_tanpa_koma">
		</form>

	</div>

</div>

<script type="text/javascript" src="<?= base_url('assets/jquery.mask.min.js') ?>"></script>
<script type="text/javascript">
	function HapusKoma(nilai) {
		return (nilai.replace(/,/g, ''));
	}

	function addCommas(nStr) {
		nStr += '';
		x = nStr.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		return x1 + x2;
	}

	function cari_jumlah_PPN() {
		var Total = $('#Total').val();

		Total = HapusKoma(Total);
		Total = Total * ppn_baru_angka_js();
		$('#PPN').val(addCommas(Total));
	}


	$(document).ready(function() {

		table_invoice();

		// $( "#Total" ).keyup(function() {

		// 	cari_jumlah_PPN();


		// });

		// $( "#Total" ).focusout(function() {
		// 	cari_jumlah_PPN();
		// }).blur(function() {
		// 	cari_jumlah_PPN();
		// });



		// $('#Total').keyup(function(){
		// 	$('.total_saat_ngetik').val(HapusKoma(this.value));
		// 	$('#PPN').val(ubah_uang_js( (HapusKoma(this.value) * ppn_baru_angka_js()))  );
		// });

		$('.FP').mask('099.099-99.09999999', {
			placeholder: "___.___-__.________"
		});

		// $('#Total').maskMoney();
		$('#Total').mask('000,000,000,000,000,000,000,000,000', {
			reverse: true
		});
		$('#PPN').mask('000,000,000,000,000,000,000,000,000', {
			reverse: true
		});
		$('#PPH').mask('000,000,000,000,000,000,000,000,000', {
			reverse: true
		});

		$('#NoKO_cari').select2({
			'width': '100%',
			'dropdownCssClass': "huruf_10"
		});

		$('#form_simpan_invoice').on('submit', function(e) {
			e.preventDefault();

			var InvDate = $('#InvDate').val();
			var DueDate = $('#DueDate').val();

			var FPNo = $('#FPNo').val();
			var PPN = $('#PPN').val();
			var Total = $('#Total').val();

			if (DueDate < InvDate) {
				swal("Error!", "Jatuh Tempo < Tgl Invoice.", "error");
				return false;
			}

			// if (FPNo != '' || FPNo !='___.___-__.________'  ) {
			// 	if (PPN == 0) {
			// 		swal("Alert!","PPN belum diisi.","info");
			// 		return false;
			// 	}	
			// }

			if (Total == 0) {
				swal("Alert!", "Total belum diisi.", "info");
				return false;
			}

			jQuery.ajax({
				url: '<?= site_url('Entry/Procurement/Invoice/aksi_simpan_invoice') ?>',
				type: 'POST',
				dataType: 'HTML',
				data: $(this).serialize(),
				cache: false,
				beforeSend: function() {
					$('#btn_simpan_invoice').attr('disabled', true).text('Loading...');
				},
				complete: function(xhr, textStatus) {
					$('#btn_simpan_invoice').attr('disabled', false).text('Simpan');
				},
				success: function(data, textStatus, xhr) {
					alert(data);
					console.log(data);
					return false;
					swal(data.teks_label, data.teks_pesan, data.teks_icon);
					table_invoice();

					if (data.teks_pesan == 'data telah disimpan') {
						tutup_modal();
					}

					return false;

				},
				error: function(xhr, textStatus, errorThrown) {
					swal("Oops!", "Something wrong!", "error");
					return false;
				}
			});


		});

	});
</script>
