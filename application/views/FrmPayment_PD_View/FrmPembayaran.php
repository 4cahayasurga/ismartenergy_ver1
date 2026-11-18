
<table width="100%" border="0">
	<tr>
		<th>No PD : <?=$NoPD?></th>
	</tr>
</table>
<p></p>

<form id="FrmPembayaranPD">

	<div class="row">

		<div class="col-sm-6">

			<table border="0" width="100%" class="huruf_10">

				<tr >
					<td width="100px" class="jarak">Job No <span class="text-red">*</span></td>
					<td width="5px" style="padding-left:3px;padding-right:3px;" class="text-center jarak" >:</td>
					<td class="jarak">
						<!-- <input type="text" class="form-control input-sm huruf_10" name="JobNo" value="<?=$mydataPD->JobNo.' - '.$mydataPD->JobName?>" readonly> -->
						<select name="JobNo" id="JobNo" class="form-control input-sm huruf_10" disabled required>
							<option value="" >-- Pilih Job --</option>
							<?php foreach ($ListJob as $row): ?>
								<option value="<?=$row->JobNo?>" <?=($mydataPD->JobNo == $row->JobNo) ? 'selected' : ''?> ><?=$row->JobNo.' - '.$row->JobNm?></option>
							<?php endforeach ?>
						</select>
					</td>
				</tr>

				<tr>
					<td width="100px" class="jarak"  >No PD <span class="text-red">*</span></td>
					<td width="5px" style="padding-left:3px;padding-right:3px;" class="text-center jarak">:</td>
					<td class="jarak">
						<input type="text" class="form-control input-sm huruf_10" name="JobNo" value="<?=$NoPD?>" disabled required>
					</td>
				</tr>

				<tr>
					<td width="100px" class="jarak">Alokasi<span class="text-red">*</span></td>
					<td width="5px" style="padding-left:3px;padding-right:3px;" class="text-center jarak">:</td>
					<td class="jarak">
						<!-- <input type="text" class="form-control input-sm huruf_10" name="Alokasi" value="<?=$mydataPD->Alokasi.' - '.$mydataPD->KeteranganAlokasi?>" readonly> -->

						<select name="Alokasi" id="Alokasi" class="form-control input-sm huruf_10" disabled>
							<option value="">-- Pilih Alokasi --</option>
							<?php foreach ($ListAlokasi as $row): ?>
								<option value="<?=$row->Alokasi?>"  <?=($mydataPD->Alokasi == $row->Alokasi) ? 'selected' : ''?> ><?=$row->Alokasi.' - '.$row->Keterangan?></option>
							<?php endforeach ?>
						</select>
					</td>
				</tr>

				<tr>
					<td width="100px" class="jarak" >Tipe Form<span class="text-red">*</span></td>
					<td width="5px" style="padding-left:3px;padding-right:3px;" class="text-center jarak">:</td>
					<td class="jarak">
						<!-- <input type="text" class="form-control input-sm huruf_10" name="TipeForm" value="<?=$mydataPD->TipeForm?>" readonly> -->
						<select name="TipeForm" id="TipeForm" class="form-control input-sm huruf_10 " disabled>
							<option value="">-- Pilih Tipe Form --</option>
							<?php foreach ($ListTipeForm as $row): ?>
								<option value="<?=$row->TipeForm?>" <?=($mydataPD->TipeForm == $row->TipeForm) ? 'selected' : ''?>  ><?=$row->TipeForm.' - '.$row->Keterangan?></option>
							<?php endforeach ?>
						</select>
					</td>
				</tr>

				<tr>
					<td width="100px" class="jarak">Tgl Bayar<span class="text-red">*</span></td>
					<td width="5px" style="padding-left:3px;padding-right:3px;" class="text-center jarak">:</td>
					<td class="jarak">
						<input type="date" name="TglByr" id="TglByr" class="form-control input-sm huruf_10" value="<?=date('Y-m-d')?>" required>
					</td>
				</tr>

				<tr>
					<td width="100px" class="jarak">Keterangan</td>
					<td width="5px" style="padding-left:3px;padding-right:3px;" class="text-center jarak">:</td>
					<td class="jarak">
						<select name="Keterangan" id="Keterangan" class="form-control input-sm huruf_10" >
							<option value="">-- Pilih Keterangan --</option>
							<option value="BIAYA ADMIN" selected>BIAYA ADMIN</option>
							<option value="PINDAH DANA">PINDAH DANA</option>
							<option value="BUNGA">BUNGA</option>
							<option value="LAINNYA">LAINNYA</option>
							<option value="CUT OFF">CUT OFF SALDO</option>
						</select>
					</td>
				</tr>

				<tr>
					<td width="100px" class="text-bold jarak">Total Permintaan</td>
					<td width="5px" style="padding-left:3px;padding-right:3px;" class="text-center text-bold jarak">:</td>
					<td class="text-bold jarak">
						<input type="text" name="TotalPD" id="TotalPD" class="form-control input-sm huruf_10 text-bold" value="<?=UbahUangKhususDecimal($mydataPD->TotalPD)?>">

					</td>
				</tr>


			</table>

		</div>

		<!-- == batas == -->
		<div class="col-sm-6">

			<table border="0" width="100%" class="huruf_10">

				<tr>
					<td width="100px" class="jarak">Via<span class="text-red">*</span></td>
					<td width="5px" style="padding-left:3px;padding-right:3px;" class="text-center jarak">:</td>
					<td class="jarak">
						<select name="Via" id="Via" class="form-control input-sm huruf_10" required>
							<option value="">-- Pilih Via --</option>
							<option value="CASH">Tunai/Setor</option>
							<option value="TRF">Transfer</option>
							<option value="CG">Cheque/Giro</option>
							<option value="NOT_PAID">Tidak Dibayar</option>
						</select>
					</td>
				</tr>

				<tr>
					<td width="100px" class="jarak">Jenis Transfer</td>
					<td width="5px" style="padding-left:3px;padding-right:3px;" class="text-center jarak">:</td>
					<td class="jarak">
						<select name="JenisTransfer" id="JenisTransfer" class="form-control input-sm huruf_10" readonly >
							<option value="">-- Pilih Jenis Transfer --</option>
							<option value="Kliring"	>Kliring</option>
							<option value="Online">Online</option>
							<option value="RTGS">RTGS</option>
							<option value="Sesama BNI">Sesama BNI</option>
							<option value="Auto Debit">Auto Debit</option>
						</select>
					</td>
				</tr>

				<tr>
					<td width="100px" class="jarak">No. Cheque / Giro</td>
					<td width="5px" style="padding-left:3px;padding-right:3px;" class="text-center jarak">:</td>
					<td class="jarak">
						<input type="text" name="NoCG" id="NoCG" class="form-control input-sm huruf_10" placeholder="Isi Disini" value="" readonly >
					</td>
				</tr>

				<tr>
					<td width="100px" class="jarak">Sumber Kas</td>
					<td width="5px" style="padding-left:3px;padding-right:3px;" class="text-center jarak">:</td>
					<td class="jarak">
						<select name="SumberKas" id="SumberKas" class="form-control input-sm huruf_10" readonly >
							<option value="">-- Pilih Sumber Kas --</option>
							<option value="JKT" >JAKARTA</option>
							<option value="MKS">LAPANGAN</option>
							<option value="LAP">RTGS</option>
						</select>
					</td>
				</tr>

				<tr>
					<td width="100px" class="jarak" >Nama Penerima</td>
					<td width="5px" style="padding-left:3px;padding-right:3px;" class="text-center jarak">:</td>
					<td class="jarak">
						<input type="text" name="NamaPenerimaTunai" id="NamaPenerimaTunai" class="form-control input-sm huruf_10" placeholder="Isi Disini" value="" readonly >
					</td>
				</tr>

			</table>

		</div>

	</div>

	<div class="row">

		<div class="col-sm-6">
			<table border="0" width="100%" class="huruf_10">
				<tr>
					<th colspan="3" class="text-center bg-gray">Rekening Pengirim</th>
				</tr>
				<tr>
					<td width="100px" class="jarak">No. Rekening</td>
					<td width="5px" style="padding-left:3px;padding-right:3px;" class="text-center jarak">:</td>
					<td class="jarak">
						<select name="NoRekPengirim" id="NoRekPengirim" class="form-control input-sm huruf_10" readonly >
							<option value=""  >-- Pilih Salah Satu --</option>
							<?php foreach ($ListRekening as $row): ?>
								<option value="<?=$row->RekId?>" data-Bank="<?=$row->Bank?>" data-NoRek="<?=$row->NoRek?>" data-AtasNama="<?=$row->AtasNama?>" ><?=$row->NoRek.' - '.$row->AtasNama.' - '.$row->Bank?></option>
							<?php endforeach ?>
						</select>
					</td>
				</tr>

				<tr>
					<td width="100px" class="jarak">Atas Nama</td>
					<td width="5px" style="padding-left:3px;padding-right:3px;" class="text-center jarak">:</td>
					<td class="jarak">
						<input type="text" name="AtasNamaPengirim" id="AtasNamaPengirim" class="form-control input-sm huruf_10" placeholder="Atas Nama" readonly >
					</td>
				</tr>

				<tr>
					<td width="100px" class="jarak">Bank</td>
					<td width="5px" style="padding-left:3px;padding-right:3px;" class="text-center jarak">:</td>
					<td class="jarak">
						<input type="text" name="BankPengirim" id="BankPengirim" class="form-control input-sm huruf_10" placeholder="Bank" readonly >
					</td>
				</tr>
			</table>
		</div>

		<div class="col-sm-6">
			<table border="0" width="100%" class="huruf_10">
				<tr>
					<th colspan="3" class="text-center bg-gray">Rekening Penerima</th>
				</tr>
				<tr>
					<td width="100px" class="jarak">No. Rekening</td>
					<td width="5px" style="padding-left:3px;padding-right:3px;" class="text-center jarak">:</td>
					<td class="jarak">
						<input type="text" name="NoRekPenerima" id="NoRekPenerima" class="form-control input-sm huruf_10" placeholder="No. Rekening" >
					</td>
				</tr>

				<tr>
					<td width="100px" class="jarak">Atas Nama</td>
					<td width="5px" style="padding-left:3px;padding-right:3px;" class="text-center jarak">:</td>
					<td class="jarak">
						<input type="text" name="AtasNamaPenerima" id="AtasNamaPenerima" class="form-control input-sm huruf_10" placeholder="Atas Nama" >
					</td>
				</tr>

				<tr>
					<td width="100px" class="jarak">Bank</td>
					<td width="5px" style="padding-left:3px;padding-right:3px;" class="text-center jarak">:</td>
					<td class="jarak">
						<!-- <input type="text" name="BankPenerima" id="BankPenerima" class="form-control input-sm huruf_10" placeholder="Bank" > -->
						<select name="BankPenerima" id="BankPenerima" class="form-control input-sm huruf_10">
							<option value="">-- Pilih Salah Satu --</option>
							<?php foreach ($ListBank as $row): ?>
								<option value="<?=$row->Bank?>"><?=$row->Bank?></option>
							<?php endforeach ?>
						</select>
					</td>
				</tr>
			</table>
		</div>

		<div class="col-sm-12">
			<hr>
			<table border="0" width="100%">
				<tr>
					<td></td>
					<td class="text-right" width="50px" style="padding-right:10px !important;">
						<input type="text" name="NoPD" value="<?=$NoPD?>">
						<button type="submit" class="btn btn-md btn-primary pull-right" id="BtnSimpanPembayaran">Simpan</button>
						<button type="button" class="btn btn-default btn-md" id="BtnLoading" style="display:none;">Loading...<i class="fa fa-spinner fa-spin"></i></button>
					</td>
					<td class="text-right" width="50px">
						<button type="button" class="btn btn-danger pull-right" data-dismiss="modal" id="BtnBatalPembayaran">Batal</button>
					</td>


				</tr>
			</table>


		</div>

	</div>

</form>

<script type="text/javascript">

	$(function(){

		var format = function(num){
			var str = num.toString().replace("$", ""), parts = false, output = [], i = 1, formatted = null;
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
			return(formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
		};

		$('#Via').change(function(){
			kosongkan_awal_page();
			ValidasiPembayaran_Via();
		});

		

		$('select[name="NoRekPengirim"]').change(function(){

			var NoRekPengirim = $('option:selected', this).attr('data-NoRek');
			var AtasNamaPengirim = $('option:selected', this).attr('data-AtasNama');
			var BankPengirim = $('option:selected', this).attr('data-Bank');

			$('#AtasNamaPengirim').val('');
			$('#BankPengirim').val('');

			$('#AtasNamaPengirim').val(AtasNamaPengirim);
			$('#BankPengirim').val(BankPengirim);
		});

		$("#TotalPD").keyup(function(e){
			$(this).val(format($(this).val()));
		});

		$('#FrmPembayaranPD').submit(function(e){
			e.preventDefault();

			var MyForm = $(this).serialize();

			jQuery.ajax({
				url: '<?=site_url('FrmPayment_PD/AksiPembayaran')?>',
				type: 'POST',
				dataType: 'HTML',
				data: MyForm,
				beforeSend : function(){
					$('#BtnLoading').show();
					$('#BtnSimpanPembayaran').hide();
					$('#BtnBatalPembayaran').hide();
				},
				complete: function(xhr, textStatus) {
					$('#BtnLoading').hide();
					$('#BtnSimpanPembayaran').show();
					$('#BtnBatalPembayaran').show();
				},
				success: function(data, textStatus, xhr) {
					console.log(data);
				},
				error: function(xhr, textStatus, errorThrown) {
					alert('error');
				}
			});
			

		});


	});

	function kosongkan_awal_page(){
		$('select[id="JenisTransfer"]').val('');
		$('#NoCG').val('');
		$('select[id="SumberKas"]').val('');

		$('#NamaPenerimaTunai').val('');

		//Rekening Pengirim
		$('select[id="NoRekPengirim"]').val('');
		$('select[id="AtasNamaPengirim"]').val('');
		$('select[id="BankPengirim"]').val('');


	}

	function ValidasiPembayaran_Via(){

		var Via = $("#Via option:selected").val();

		if (Via == 'CASH') {

			$('select[id="JenisTransfer"]').val('').change().attr('readonly',true).attr('required',false);
			$('#NoCG').val('').attr('readonly',true).attr('required',false);

			$('select[id="SumberKas"]').val('').attr('readonly',false).attr('required',true);
			$('#NamaPenerimaTunai').val('').attr('readonly',false).attr('required',true);

			$('select[id="NoRekPengirim"]').val('').attr('readonly',true).attr('required',false);
			$('input[id="AtasNamaPengirim"]').val('').attr('readonly',true);
			$('input[id="BankPengirim"]').val('').attr('readonly',true);

			// $('#TotalPD').val(0);
		}

		if (Via == 'TRF') {
			$('select[id="JenisTransfer"]').val('Online').change().attr('readonly',false).attr('required',true);
			$('#NoCG').val('').attr('readonly',true).attr('required',false);

			$('select[id="SumberKas"]').val('').attr('readonly',true).attr('required',false);
			$('#NamaPenerimaTunai').val('').attr('readonly',true).attr('required',false);

			$('select[id="NoRekPengirim"]').val('').attr('readonly',false).attr('required',true);
			$('input[id="AtasNamaPengirim"]').val('').attr('readonly',true);
			$('input[id="BankPengirim"]').val('').attr('readonly',true);

			// $('#TotalPD').val(0);
		}

		if (Via == 'CG') {
			$('select[id="JenisTransfer"]').val('').change().attr('readonly',true).attr('required',false);
			$('#NoCG').val('').attr('readonly',false).attr('required',true);

			$('select[id="SumberKas"]').val('').attr('readonly',true).attr('required',false);
			$('#NamaPenerimaTunai').val('').attr('readonly',true).attr('required',false);

			$('select[id="NoRekPengirim"]').val('').attr('readonly',false).attr('required',false);
			$('input[id="AtasNamaPengirim"]').val('').attr('readonly',true);
			$('input[id="BankPengirim"]').val('').attr('readonly',true);

			// $('#TotalPD').val(0);
		}

		if (Via == 'NOT_PAID' || Via == '') {
			$('select[id="JenisTransfer"]').val('').change().attr('readonly',true).attr('required',false);
			$('#NoCG').val('').attr('readonly',true).attr('required',false);

			$('select[id="SumberKas"]').val('').attr('readonly',true).attr('required',false);
			$('#NamaPenerimaTunai').val('').attr('readonly',true).attr('required',false);

			$('select[id="NoRekPengirim"]').val('').attr('readonly',true).attr('required',false);
			$('input[id="AtasNamaPengirim"]').val('').attr('readonly',true);
			$('input[id="BankPengirim"]').val('').attr('readonly',true);

			// $('#TotalPD').val(0);
		}




	}
</script>