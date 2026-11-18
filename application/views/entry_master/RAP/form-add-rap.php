<style type="text/css">
	#form_RAP {
		font-size: 12px;
		/*font-weight: bold;*/
	}
</style>
<div class="row">

	<form id="form_RAP">



		<div class="col-sm-12">

			<table border="0" width="100%">
				<tr>
					<td style="font-size:14px;" width="50%">RAP -- FORM TAMBAH DATA</td>
					<td style="font-size: 12px;color:blue;color:blue;text-align:right;" width="50%"><?= $JobNo . ' - ' . $JobNm ?></td>
				</tr>
				<tr>
					<td style="border-bottom:1px solid black;" colspan="2"></td>
				</tr>
			</table>

			<br>

			<table border="0" width="100%">
				<tr>
					<td width="130px">Tipe</td>
					<td width="10px" align="center">:</td>
					<td width="25%">
						<select name="Tipe" id="Tipe" class="form-control input-sm select2" required>
							<option value="">-- Pilih salah satu --</option>
							<option value="Header">Header</option>
							<option value="Detail">Detail</option>
						</select>
					</td>

					<td width="130px" style="padding-left:10px;">Header</td>
					<td width="10px" align="center">:</td>
					<td width="35%">
						<select name="Header" id="Header" class="form-control input-sm select2" required>
							<option value="0">Top Header</option>
							<?php
							foreach ($header_rap as $myheader) : ?>
								<option value="<?= $myheader['KdRAP'] ?>"><?= $myheader['KdRAP'] . ' - ' . $myheader['Uraian'] ?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<td></td>
				</tr>
			</table>

			<table border="0" width="100%" style="margin-top:10px;">
				<tr>
					<td width="130px">Alokasi</td>
					<td width="10px" align="center">:</td>
					<td width="25%">
						<input type="text" class="form-control input-sm" value="<?= $Alokasi . ' - ' . $Keterangan ?>" disabled>

					</td>
					<td></td>
				</tr>
			</table>

			<table border="0" width="100%" style="margin-top:10px;">
				<tr>
					<td width="130px">Kode RAP</td>
					<td width="10px" align="center">:</td>
					<td width="25%">
						<input type="text" class="form-control input-sm" name="KdRAP" id="KdRAP" placeholder="Kode RAP" autocomplete="off" required>
					</td>
					<td></td>
				</tr>
			</table>

			<table border="0" width="100%" style="margin-top:10px;">
				<tr>
					<td width="130px">Uraian Pekerjaan</td>
					<td width="10px" align="center">:</td>
					<td width="25%">
						<textarea class="form-control input-sm" placeholder="Uraian Pekerjaan" name="Uraian" id="Uraian" required></textarea>
						<!-- <input type="text" class="form-control input-sm" name="Uraian" id="Uraian" placeholder="Uraian Pekerjaan" required> -->
					</td>
					<td></td>
				</tr>
			</table>

			<table border="0" width="100%" style="margin-top:10px;">
				<tr>
					<td width="130px">Satuan</td>
					<td width="10px" align="center">:</td>
					<td width="25%">
						<input type="text" class="form-control input-sm" name="Uom" id="Uom" placeholder="Satuan" autocomplete="off">
					</td>
					<td></td>
				</tr>
			</table>

			<table border="0" width="100%" style="margin-top:10px;">
				<tr>
					<td width="130px">Volume</td>
					<td width="10px" align="center">:</td>
					<td width="25%">
						<input type="text" class="form-control input-sm" name="Vol" id="Vol" placeholder="0" autocomplete="off">
					</td>
					<td></td>
				</tr>
			</table>



			<table border="0" width="100%" style="margin-top:10px;">
				<tr>
					<td width="130px">Harga Satuan</td>
					<td width="10px" align="center">:</td>
					<td width="25%">
						<input type="text" class="form-control input-sm " name="HrgSatuan" id="HrgSatuan" placeholder="0" autocomplete="off">
					</td>
					<td></td>
				</tr>
			</table>

			<table border="0" width="100%" style="margin-top:10px;" hidden>
				<tr>
					<td width="130px">PPN Satuan (Rp)</td>
					<td width="10px" align="center">:</td>
					<td width="25%">
						<input type="text" class="form-control input-sm " name="PPN" id="PPN" placeholder="0" autocomplete="off">
						<span style="font-style: italic; color: red;">*Jika Kosong Ketik NOL (0)</span>
					</td>
					<td></td>
				</tr>
			</table>

			<table border="0" width="100%" style="margin-top:10px;">
				<tr>
					<td width="130px">Harga Satuan (RAB)</td>
					<td width="10px" align="center">:</td>
					<td width="25%">
						<input type="text" class="form-control input-sm " name="HrgRAB" id="HrgRAB" placeholder="0" autocomplete="off">
					</td>
					<td></td>
				</tr>
			</table>

			<table border="0" width="100%" style="margin-top:10px;">
				<tr>
					<td width="130px"></td>
					<td width="10px" align="center"></td>
					<td class="text-left text-red" width="30%">Ditambahkan pada Tanggal 18 AGUSTUS 2023</td>
					<td></td>
				</tr>
			</table>

			<table border="0" width="100%" style="margin-top:10px;">
				<tr>
					<td width="130px">Jumlah (Hrg Satuan * Vol)</td>
					<td width="10px" align="center">:</td>
					<td width="25%">
						<input type="text" name="Jumlah_HrgSatuan_kali_Vol" id="Jumlah_HrgSatuan_kali_Vol" value="0" class="form-control" readonly >
					</td>
					<td></td>
				</tr>
			</table>

			<table border="0" width="100%" style="margin-top:10px;">
				<tr>
					<td width="130px">PPN (11%)</td>
					<td width="10px" align="center">:</td>
					<td width="25%">
						<input type="checkbox" name="PPN_New_Checklist" id="PPN_New_Checklist"  value="1" >
					</td>
					<td></td>
				</tr>
			</table>

			<table border="0" width="100%" style="margin-top:10px;">
				<tr>
					<td width="130px">PPN Nominal (Rp)</td>
					<td width="10px" align="center">:</td>
					<td width="25%">
						<input type="text" name="PPN_New_Nominal" id="PPN_New_Nominal" value="0" class="form-control" readonly >
					</td>
					<td></td>
				</tr>
			</table>

			<table border="0" width="100%" style="margin-top:10px;">
				<tr>
					<td width="130px">Total (Jumlah + PPN Nominal)</td>
					<td width="10px" align="center">:</td>
					<td width="25%">
						<input type="text" name="Total_Jumlah_Tambah_PPN_Nominal" id="Total_Jumlah_Tambah_PPN_Nominal" class="form-control" value="0" readonly >
					</td>
					<td></td>
				</tr>
			</table>

			



			<table border="0" width="100%" style="margin-top:10px;">
				<tr>
					<td align="right">



						<input type="hidden" class="form-control input-sm" name="Alokasi" id="Alokasi" placeholder="Alokasi" value="<?= $Alokasi ?>">
						<input type="hidden" class="form-control input-sm" name="JobNo" id="JobNo" placeholder="Job Nomor" value="<?= $JobNo ?>">

						<input type="hidden" class="form-control input-sm" name="Versi" id="Versi" value="<?= $Versi ?>">
						<hr style="border:1px solid black;">

						<button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-md btn-primary btn_simpan_rap" id="btn_simpan_rap">Simpan</button>
					</td>

				</tr>
			</table>


		</div>

	</form>
</div>

<script>
	$(function() {

		$('.money').mask('000,000,000,000,000.00', {
			reverse: true
		});

		$('.uang').mask("#,##0", {
			reverse: true
		});

		$('.select2').select2({
			'width': '100%'
		});
		// $("#Header").select2({ dropdownCssClass: "myFont" });

		$('#Vol').attr('readonly', true);
		$('#Uom').attr('readonly', true);
		$('#HrgSatuan').attr('readonly', true);
		$('#HrgRAB').attr('readonly', true);
		$('#PPN').attr('readonly', true);



		$('#Tipe').change(function() {
			var Tipe = this.value;

			// == ditambahkan 18 AGUSTUS 2023

				$('#form_RAP #Vol').val(0);
				$('#form_RAP #HrgSatuan').val(0);
				$('#form_RAP #PPN_New_Nominal').val(0);
				$('#form_RAP #PPN_New_Checklist').prop('checked', false);
				$('#form_RAP #Jumlah_HrgSatuan_kali_Vol').val(0);
				$('#form_RAP #Total_Jumlah_Tambah_PPN_Nominal').val(0);

				// == END ditambahkan 18 AGUSTUS 2023

			if (Tipe == 'Detail') {
				$('#Vol').attr('readonly', false);
				$('#Uom').attr('readonly', false);
				$('#HrgSatuan').attr('readonly', false);
				$('#HrgRAB').attr('readonly', false);
				$('#PPN').attr('readonly', false);

				


				return false;
			}

			if (Tipe == 'Header') {
				$('#Vol').attr('readonly', true);
				$('#Uom').attr('readonly', true);
				$('#HrgSatuan').attr('readonly', true);
				$('#HrgRAB').attr('readonly', true);
				$('#PPN').attr('readonly', true);
				return false;
			}

			if (Tipe == '') {
				$('#Vol').attr('readonly', true);
				$('#Uom').attr('readonly', true);
				$('#HrgSatuan').attr('readonly', true);
				$('#HrgRAB').attr('readonly', true);
				$('#PPN').attr('readonly', true);
			}
		});

		$('#form_RAP').submit(function(e) {

			e.preventDefault();



			jQuery.ajax({
				url: '<?= site_url('Entry_master/ENT_RAP/aksi_simpan_RAP') ?>',
				type: 'POST',
				dataType: 'HTML',
				data: $(this).serialize(),
				beforeSend: function() {
					$('#btn_simpan_rap').attr('disabled', true).text('Loading...');
				},
				complete: function() {
					$('#modal_add_rap').hide();
					$("#modal_add_rap").data('bs.modal').isShown = false;
					$('body').removeClass('modal-open');
					$('.modal-backdrop').remove();
					$('#modal_add_rap').removeClass("in");
					$('#modal_add_rap').attr('aria-hidden', "true");
					segarkan_data();
				},
				success: function(data, textStatus, xhr) {
					$('#btn_simpan_rap').attr('disabled', false).text('Simpan');
					// console.log(data);
					// alert(data);
					// return false;

					// console.log(data);
					// return false;

					if (data == 'berhasil') {
						$.toast({
							text: 'data telah ditambahkan',
							position: 'bottom-right',
							stack: false,
							hideAfter: 3000,
							icon: 'success',

						});
						return false;
					}

					if (data == 'gagal') {
						$.toast({
							text: 'terjadi kesalahan',
							position: 'bottom-right',
							stack: false,
							hideAfter: 3000,
							icon: 'error',

						});
						return false;
					}


					if (data == 'rap_sudah_ada') {
						$.toast({
							text: 'Kode RAP sudah terpakai',
							position: 'bottom-right',
							stack: false,
							hideAfter: 3000,
							icon: 'info',

						});
						return false;
					}


					if (data == 'detail_dilarang_top_header') {
						$.toast({
							text: 'Tipe Detail tidak dapat menggunakan Top Header',
							position: 'bottom-right',
							stack: false,
							hideAfter: 3000,
							icon: 'info',

						});
						return false;
					}




				},
				error: function(xhr, textStatus, errorThrown) {
					$('#btn_simpan_rap').attr('disabled', false).text('Simpan');
					$.toast({
						text: 'terjadi kesalahan',
						position: 'bottom-right',
						stack: false,
						hideAfter: 3000,
						icon: 'error',

					});
					return false;
				}
			});

		});


		var formatUang_Nol_Tiga = function(num){
			var str = num.toString().replace("", ""), parts = false, output = [], i = 1, formatted = null;
			if(str.indexOf(".") > 0) {
				parts = str.split(".");
				str = parts[0];
			}
			str = str.split("").reverse();
			for(var j = 0, len = str.length; j < len; j++) {
				if(str[j] != ",") {
					output.push(str[j]);
					if(i%3 == 0 && j < (len - 1)) {
						output.push(",");
					}
					i++;
				}
			}
			formatted = output.reverse().join("");
			return("" + formatted );
		};

		$('#form_RAP #HrgSatuan').keyup(function(){
			if (this.value == '' ) {
				// $(this).val(0);
			}
			Perhitungan_PPN();

			$(this).val(formatUang_Nol_Tiga(this.value));
			// Perhitungan();

		});

		$('#form_RAP #Vol').keyup(function(){
			if (this.value == '' ) {
				// $(this).val(0);
			}

			// $(this).val(formatUang_Nol_Tiga(this.value));

			Perhitungan_PPN();
		});

		$('#form_RAP #PPN_New_Checklist').change(function(){
			Perhitungan_PPN();
		})

	});

function Perhitungan_PPN(){

	var Vol = $('#form_RAP #Vol').val().replace(/,/g, '');
	var HrgSatuan = $('#form_RAP #HrgSatuan').val().replace(/,/g, '');

	if (Vol == '' || Vol == null) {
		Vol = 0;
	}

	if (HrgSatuan == '' || HrgSatuan == null) {
		HrgSatuan = 0;
	}

	Vol = parseFloat(Vol);
	HrgSatuan = parseFloat(HrgSatuan);

	

	// alert(Vol);

	PPN_New_Checklist = 0;
	if ($('#form_RAP #PPN_New_Checklist').is(':checked')) {
		PPN_New_Checklist = 1;
	}else{
		PPN_New_Checklist = 0;
	}



	

	var Jumlah_HrgSatuan_kali_Vol = 0;
	var PPN_New_Nominal = 0;
	var Total_Jumlah_Tambah_PPN_Nominal = 0;
	

	Jumlah_HrgSatuan_kali_Vol = Vol * HrgSatuan;


	if(PPN_New_Checklist==1){
		var PPN_New_Nominal = (HrgSatuan * 11) / 100;
	}

	Total_Jumlah_Tambah_PPN_Nominal = Jumlah_HrgSatuan_kali_Vol + PPN_New_Nominal;

	$('#form_RAP #Jumlah_HrgSatuan_kali_Vol').val(addCommas(Jumlah_HrgSatuan_kali_Vol));
	$('#form_RAP #PPN_New_Nominal').val(addCommas(PPN_New_Nominal));
	$('#form_RAP #Total_Jumlah_Tambah_PPN_Nominal').val(addCommas(Total_Jumlah_Tambah_PPN_Nominal));
	// alert(Jumlah_HrgSatuan_kali_Vol);
	// return false;

	
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

function pembulatan(data){
	return (Math.round(data * 100)/100).toFixed(2);
}

function HapusKoma(nilai) {
	return (nilai.replace(/,/g, ''));
}

function validasi_inputan() {
	var Tipe = $('#Tipe').val();
	var Header = $('#Header').val();
	var KdRAP = $('#KdRAP').val();

	var Uraian = $('#Uraian').val();
	var Uom = $('#Uom').val();
	var Vol = $('#Vol').val();

	var HrgSatuan = $('#HrgSatuan').val();
	var PPN = $('#PPN').val();
	var HrgRAB = $('#HrgRAB').val();





		// alert(Header);
}
</script>
