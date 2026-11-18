

<div class="row">
	<div class="col-sm-12">
		<div class="table-responsive">
			<table border="0" width="100%" >
				<tr>
					<td align="center" bgcolor="silver" style="height:30px; font-weight:bold;vertical-align: middle;">O  R  D  E  R &nbsp; &nbsp;L  I  S  T</td>
				</tr>
				<tr>
					<td style="padding-top:10px;">
						<button type="button" class="btn btn-default btn-md btnTambahOrder">TAMBAH</button>
						<button type="button" class="btn btn-default btn-md btnCancelTambahOrder hidden" >CANCEL</button>
					</td>
				</tr>

			</table>
		</div>
	</div>
</div>

<div class="row hidden" id="FormTambah">
	<div id="col-sm-12">
		<fieldset class="scheduler-border" >
			<legend class="scheduler-border" style="margin-top:10px;">FORM ADD NEW ORDER</legend>

			<table width="100%" border="0" id="table_tambah" style="margin-top: 5px;margin-bottom: 5px;" >
				<tr>
					<td width="150px">Alokasi : </td>
					<td width="20px" class="text-center">:</td>
					<td width="150px">
						<select name="Alokasi_Tambah" id="Alokasi_Tambah" class="form-control input-sm" >
							<option value=""></option>
							<?php foreach ($ListAlokasi_SPR as $row): ?>
								<option value="<?=$row->Alokasi?>"><?=$row->Alokasi?> - <?=$row->Keterangan?></option>
							<?php endforeach ?>
						</select>
					</td>
					<td width="20px"></td>
					<td width="100px">Kode RAP : </td>
					<td width="500px">
						<select name="KdRAP_Tambah" id="KdRAP_Tambah" class="form-control input-sm" >
							<option value=""></option>
						</select>
					</td>
					<td></td>
				</tr>
			</table>

			<table width="100%" border="0" style="margin-top: 5px;margin-bottom: 5px;" >
				<tr>
					<td width="150px" style="vertical-align:top;">Uraian</td>
					<td width="20px" class="text-center" style="vertical-align:top;">:</td>
					<td width="500px">
						<textarea name="Uraian_Tambah" id="Uraian_Tambah" class="form-control huruf12" maxlength="255" rows="3"></textarea>
					</td>

					<td></td>
				</tr>
			</table>

			<table width="100%" border="0" style="margin-top: 5px;margin-bottom: 5px;" >
				<tr>
					<td width="150px">Volume</td>
					<td width="20px" class="text-center">:</td>
					<td width="150px">
						<input type="text" name="Vol_Tambah" id="Vol_Tambah" class="form-control huruf12 text-right VolMengetik_Tambah" value="0" maxlength="10">
					</td>

					<td></td>
				</tr>
			</table>

			<table width="100%" border="0" style="margin-top: 5px;margin-bottom: 5px;" >
				<tr>
					<td width="150px">Uom</td>
					<td width="20px" class="text-center">:</td>
					<td width="150px">
						<input type="text" name="Uom_Tambah" id="Uom_Tambah" class="form-control huruf12 " maxlength="15" >
					</td>

					<td></td>
				</tr>
			</table>

			<table width="100%" border="0" style="margin-top: 5px;margin-bottom: 5px;" >
				<tr>
					<td width="150px">Hrg Satuan (Rp)</td>
					<td width="20px" class="text-center">:</td>
					<td width="150px">
						<input type="text" name="HrgSatuan_Tambah" id="HrgSatuan_Tambah" class="form-control huruf12 text-right HrgSatuanMengetik_Tambah" value="0" MaxLength="17">
					</td>

					<td></td>
				</tr>
			</table>

			<table width="100%" border="0" style="margin-top: 5px;margin-bottom: 5px;" >
				<tr>
					<td width="150px">Jumlah Harga(Rp)</td>
					<td width="20px" class="text-center">:</td>
					<td width="150px">
						<input type="text" name="JumlahHarga_Tambah" id="JumlahHarga_Tambah" class="form-control huruf12  text-right" value="0"  readonly>
					</td>

					<td></td>
				</tr>
			</table>

			<table width="100%" border="0" style="margin-top: 5px;margin-bottom: 5px;" >
				<tr>
					<td width="150px">PPN (11%)</td>
					<td width="20px" class="text-center">:</td>
					<td width="150px">
						<input type="checkbox" name="PPNCheck_Tambah" id="PPNCheck_Tambah" onchange="PPNCheck_Berganti_Tambah()" value="1"  >
					</td>

					<td></td>
				</tr>
			</table>

			<table width="100%" border="0" style="margin-top: 5px;margin-bottom: 5px;display: none;" >
				<tr>
					<td width="150px">PPN Nominal (Rp)</td>
					<td width="20px" class="text-center">:</td>
					<td width="150px">
						<input type="text" name="PPN_Nominal_Tambah" id="PPN_Nominal_Tambah" class="form-control huruf12 text-right" value="0" readonly>
					</td>
					<td></td>
				</tr>
			</table>


			<table width="100%" border="0" style="margin-top: 5px;margin-bottom: 5px;" >
				<tr>
					<td width="150px">Total Nominal PPN (Rp)</td>
					<td width="20px" class="text-center">:</td>
					<td width="150px">
						<input type="text" name="TotalNominalPPN_Tambah" id="TotalNominalPPN_Tambah" class="form-control huruf12 text-right" value="0" readonly>
					</td>
					<td></td>
				</tr>
			</table>
			

			<table width="100%" border="0" style="margin-top: 5px;margin-bottom: 5px;">
				<tr>
					<td width="150px">Periode Pengiriman</td>
					<td width="20px" class="text-center">:</td>
					<td width="150px">
						<input type="date" name="PeriodePengiriman1" id="PeriodePengiriman1" class="form-control huruf12 text-right">
					</td>
					<td width="50px" style="text-align: center;">S/D</td>
					<td width="150px">
						<input type="date" name="PeriodePengiriman2" id="PeriodePengiriman2" class="form-control huruf12 text-right">
					</td>
					<td></td>
				</tr>
			</table>

			<table width="100%" border="0" style="margin-top: 5px;margin-bottom: 5px;">
				<tr>
					<td width="150px">Periode Pembayaran</td>
					<td width="20px" class="text-center">:</td>
					<td width="150px">
						<input type="date" name="PeriodePembayaran1" id="PeriodePembayaran1" class="form-control huruf12 text-right" value="0">
					</td>
					<td width="50px" style="text-align: center;">S/D</td>
					<td width="7%">
						<input type="date" name="PeriodePembayaran2" id="PeriodePembayaran2" class="form-control huruf12 text-right" value="0">
					</td>
					<td></td>
				</tr>
			</table>
			<table width="100%" border="0" style="margin-top: 5px;margin-bottom: 5px;">
				<tr>
					<td width="150px"></td>
					<td width="20px" class="text-center"></td>
					<td width="150px">
						<button type="button" class="btn btn-default btn-md btnADD">ADD</button>
					</td>
					<td></td>
				</tr>
			</table>

		</fieldset>
	</div>
</div>

<div class="row" style="margin-top:20px;">
	<div class="col-sm-12">
		<table class="table table-bordered table-striped table-hover huruf12" border="1" width="100%" id="table_order">
			<thead>
				<tr>
					<th width="20px">No</th>
					<th width="80px">Alokasi</th>
					<th width="80px">Kode RAP</th>
					<th width="500px">Uraian</th>
					<th width="150px">Vol</th>
					<th width="100px">Uom</th>
					<th width="150px">Harga Satuan (Rp)</th>
					<th width="150px">Jumlah Harga (Rp)</th>
					<th width="20px">PPN (11%)</th>
					<th width="200px" style="display:none;">PPN Nominal (Rp)</th>
					<th width="150px">Total PPN Nominal (Rp)</th>
					<th width="80px" colspan="2" >Periode Pengiriman</th>
					<th width="80px" colspan="2">Periode Pmbayaran</th>
					<th width="100px"></th>
				</tr>
			</thead>
			<tbody class="table_order_body">


			</tbody>
			<tfoot style="font-size: 14px;">
				<tr class="bg-gray disabled color-palette">
					<th colspan="6"></th>
					<th class="text-right">SUB TOTAL</th>
					<th class="text-right SubTotalClass">0</th>
					<th ></th>
					<th class="text-right SubTotal_PPN_Nominal" style="display:none;">0</th>
					<th class="text-right SubTotal_TotalNominalPPN">0</th>
					<th ></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>

					
				</tr>
			</tfoot>
		</table>
	</div>
</div>

<div class="row" style="margin-top:20px;">
	<div class="col-sm-10">
		<div class="table-responsive">
			<table class="huruf12 text-right" border="0" width="100%">
				<tr>
					<td></td>
					<td width="100px" class="text-center">DISC (%)</td>
					<td width="200px">
						<input type="text" name="TxtDiscPersen" id="TxtDiscPersen" class="form-control input-sm text-right" value="0" maxlength="6">
					</td>
					<td width="200px" style="padding-left:5px;">
						<input type="text" name="TxtDiscNominal" id="TxtDiscNominal" class="form-control input-sm text-right" value="0" readonly>
					</td>
					<td width="100px" style="vertical-align:middle;padding-left: 10px;">

					</td>
				</tr>
			</table>

			<table class="huruf12" border="0" width="100%" style="margin-top:5px;">
				<tr>
					<td></td>
					<td width="200px" class="text-center">
						PPN
					</td>
					<td width="200px" style="padding-left:5px;">
						<input type="text" name="TxtPPN" id="TxtPPN" class="form-control input-sm text-right" value="0" readonly>
					</td>
					<td width="100px" style="vertical-align:middle;padding-left: 10px;">
						<input type="checkbox" id="CbOverride" name="CbOverride" value="1" class="huruf12" disabled="disabled" style="display: ;">
						<label for="CbOverride" style="font-weight: normal;" class="huruf12" style="display: ;"> Ovveride PPN</label>
					</td>
				</tr>
			</table>

			<table class="huruf12" border="0" width="100%" style="margin-top:5px;">
				<tr>
					<td></td>
					<td width="200px" class="text-center">
						Grand Total
					</td>
					<td width="200px" style="padding-left:5px;">
						<input type="text" name="TxtTotal" id="TxtTotal" class="form-control input-sm text-right" value="0" readonly>
					</td>
					<td width="100px" style="vertical-align:middle;padding-left: 10px;">

					</td>
				</tr>
			</table>

			<table class="huruf12" border="0" width="100%" style="margin-top:5px;">
				<tr>
					<td></td>
					<td width="200px" class="text-center">
						Mengurangi KO
					</td>
					<td width="200px" style="padding-left:5px;">
						<select name="TxtPotongKO" id="TxtPotongKO" class="form-control input-sm">
							<option value=""></option>
							<?php foreach ($ListPotongKO_SPR as $row): ?>
								<option value="<?=$row->NoKO?>"><?=$row->NoKO?></option>
							<?php endforeach ?>
						</select>
					</td>
					<td width="100px" style="vertical-align:middle;padding-left: 10px;">

					</td>
				</tr>
			</table>

		</div>
	</div>
</div>




<script type="text/javascript">

	$(document).ready(function(){


		$('.VolMengetik').on('input', function(e) 
		{

			var inputValue = $(this).val();

			var filteredValue = inputValue.replace(/[^0-9.-]/g, ''); 


			if (filteredValue.indexOf('-') > 0) { 
				filteredValue = filteredValue.replace(/-/g, '');
				filteredValue = '-' + filteredValue;
			}


			if (filteredValue.indexOf('.') == 0) { 
				filteredValue = '0' + filteredValue;
			}


			var parts = filteredValue.split('.');
			if (parts[1] && parts[1].length > 3) {
				parts[1] = parts[1].substr(0, 3);
				filteredValue = parts.join('.');
			}

			$(this).val(filteredValue);


		});

		$('.VolMengetik').focusin(function()
		{

			if (this.value=='0.000') {
				$(this).val('');
			}


		});

		$('.VolMengetik').focusout(function()
		{

			if (this.value=='') {
				$(this).val('0.000');
			}else{
				$(this).val(FormatVol_Buatan($(this).val()));
			}

			var NoUrut = $(this).attr('data-NoUrut');
			get_JumlahHarga(NoUrut);
			get_SubTotal();

		});

		$('.HrgSatuanMengetik').on('input', function(e) 
		{


			var inputValue = $(this).val();


			var filteredValue = inputValue.replace(/[^0-9.-]/g, ''); 

			if (filteredValue.indexOf('-') > 0) { 
				filteredValue = filteredValue.replace(/-/g, '');
				filteredValue = '-' + filteredValue;
			}


			if (filteredValue.indexOf('.') == 0) {
				filteredValue = '0' + filteredValue;
			}


			var parts = filteredValue.split('.'); 
			if (parts[1] && parts[1].length > 2) {
				parts[1] = parts[1].substr(0, 2);
				filteredValue = parts.join('.');
			}


			$(this).val(filteredValue);


		});

		$('.HrgSatuanMengetik').focusin(function()
		{

			if (this.value=='0') {
				$(this).val('');
			}

		});

		$('.HrgSatuanMengetik').focusout(function()
		{
			if (this.value=='') {
				$(this).val('0');
			}else{
				$(this).val(FormatVol_Buatan($(this).val()));
			}
			var NoUrut = $(this).attr('data-NoUrut');
			get_JumlahHarga(NoUrut);
			get_SubTotal();
		}); 

		$('#TxtDiscPersen').on('input', function(e) 
		{


			var inputValue = $(this).val();

			if (inputValue< -100) {
				$(this).val(-100);
				return false;
			}
			if (inputValue > 100) {
				$(this).val(100);
				return false;
			}

			var filteredValue = inputValue.replace(/[^0-9.-]/g, ''); 

			if (filteredValue.indexOf('-') > 0) { 
				filteredValue = filteredValue.replace(/-/g, '');
				filteredValue = '-' + filteredValue;
			}


			if (filteredValue.indexOf('.') == 0) {
				filteredValue = '0' + filteredValue;
			}


			var parts = filteredValue.split('.'); 
			if (parts[1] && parts[1].length > 2) {
				parts[1] = parts[1].substr(0, 2);
				filteredValue = parts.join('.');
			}


			$(this).val(filteredValue);


		});

		$('#TxtDiscPersen').focusin(function()
		{

			if (this.value=='0') {
				$(this).val('');
			}

		});

		$('#TxtDiscPersen').focusout(function()
		{
			if (this.value=='') {
				$(this).val('0');
			}else{
				$(this).val(FormatVol_Buatan($(this).val()));
			}

			get_DiscNominal();
			get_GrandTotal();

		}); 

		$('.btnTambahOrder').click(function(){	
			$('#FormTambah').removeClass('hidden');
			$(this).addClass('hidden');
			$('.btnCancelTambahOrder').removeClass('hidden');

			$('#FormTambah #Alokasi_Tambah').val(''); 
			$('#FormTambah #KdRAP_Tambah').find('option').remove();
			$('#FormTambah #KdRAP_Tambah').append('<option></option>');
			$('#FormTambah #Uraian_Tambah').val('');
			$('#FormTambah #Vol_Tambah').val(parseFloat(0.000));
			$('#FormTambah #Uom_Tambah').val('');
			$('#FormTambah #HrgSatuan_Tambah').val(0);
			$('#FormTambah #JumlahHarga_Tambah').val(0);
			$('#FormTambah #PPNCheck_Tambah').prop('checked',false);
			$('#FormTambah #PPN_Nominal_Tambah').val(0);
			$('#FormTambah #TotalNominalPPN_Tambah').val(0);

			$('#FormTambah #PeriodePengiriman1').val('');
			$('#FormTambah #PeriodePengiriman2').val('');
			$('#FormTambah #PeriodePembayaran1').val('');
			$('#FormTambah #PeriodePembayaran2').val('');
		});

		$('.btnCancelTambahOrder').click(function()
		{
			$('#FormTambah').addClass('hidden');
			$(this).addClass('hidden');
			$('.btnTambahOrder').removeClass('hidden');

			$('#FormTambah #Alokasi_Tambah').val(''); 
			$('#FormTambah #KdRAP_Tambah').find('option').remove();
			$('#FormTambah #KdRAP_Tambah').append('<option></option>');
			$('#FormTambah #Uraian_Tambah').val('');
			$('#FormTambah #Vol_Tambah').val(parseFloat(0.000));
			$('#FormTambah #Uom_Tambah').val('');
			$('#FormTambah #HrgSatuan_Tambah').val(0);
			$('#FormTambah #JumlahHarga_Tambah').val(0);
			$('#FormTambah #PPNCheck_Tambah').prop('checked',false);
			$('#FormTambah #PPN_Nominal_Tambah').val(0);
			$('#FormTambah #TotalNominalPPN_Tambah').val(0);

			$('#FormTambah #PeriodePengiriman1').val('');
			$('#FormTambah #PeriodePengiriman2').val('');
			$('#FormTambah #PeriodePembayaran1').val('');
			$('#FormTambah #PeriodePembayaran2').val('');
		});


		$('#FormTambah #Alokasi_Tambah').on('change',function()
		{
			$('#FormTambah #KdRAP_Tambah').select2({
				dropdownCssClass : 'huruf12',
			});
			var Alokasi = $('#FormTambah #Alokasi_Tambah option:selected').val();

			$('#FormTambah #KdRAP_Tambah').find('option').remove();
			$('#FormTambah #KdRAP_Tambah').append('<option><option>');

			if (Alokasi=='') {
				return false;
			}

			$.ajax({
				url: "<?=site_url('Entry/Procurement/FrmEntryPO_C/get_KdRAP')?>",
				type: "POST",
				dataType: "HTML",
				data: {Alokasi: Alokasi},
				success: function(data, textStatus, xhr) {
					$('#FormTambah #KdRAP_Tambah').append(data);
				},
				error: function(xhr, textStatus, errorThrown) {
					alert('error');
				}
			});

		});

		$('#FormTambah .VolMengetik_Tambah').on('input', function(e) 
		{
			var inputValue = $(this).val();
			var filteredValue = inputValue.replace(/[^0-9.-]/g, ''); 

			if (filteredValue.indexOf('-') > 0) { 
				filteredValue = filteredValue.replace(/-/g, '');
				filteredValue = '-' + filteredValue;
			}

			if (filteredValue.indexOf('.') == 0) { 
				filteredValue = '0' + filteredValue;
			}

			var parts = filteredValue.split('.');
			if (parts[1] && parts[1].length > 3) {
				parts[1] = parts[1].substr(0, 3);
				filteredValue = parts.join('.');
			}

			$(this).val(filteredValue);
		});

		$('#FormTambah .VolMengetik_Tambah').focusin(function()
		{
			if (this.value=='0.000') {
				$(this).val('');
			}
			if (this.value=='0') {
				$(this).val('');
			}
		});

		$('#FormTambah .VolMengetik_Tambah').focusout(function()
		{

			if (this.value=='') {
				$(this).val('0.000');
			}else{
				$(this).val(FormatVol_Buatan($(this).val()));
			}

			get_JumlahHarga_Tambah();
		});

		$('#FormTambah .HrgSatuanMengetik_Tambah').on('input', function(e) 
		{
			var inputValue = $(this).val();
			var filteredValue = inputValue.replace(/[^0-9.-]/g, ''); 

			if (filteredValue.indexOf('-') > 0) { 
				filteredValue = filteredValue.replace(/-/g, '');
				filteredValue = '-' + filteredValue;
			}

			if (filteredValue.indexOf('.') == 0) {
				filteredValue = '0' + filteredValue;
			}

			var parts = filteredValue.split('.'); 
			if (parts[1] && parts[1].length > 2) {
				parts[1] = parts[1].substr(0, 2);
				filteredValue = parts.join('.');
			}
			$(this).val(filteredValue);
		});

		$('#FormTambah .HrgSatuanMengetik_Tambah').focusin(function()
		{
			if (this.value=='0') {
				$(this).val('');
			}
		});

		$('#FormTambah .HrgSatuanMengetik_Tambah').focusout(function()
		{
			if (this.value=='') {
				$(this).val('0');
			}else{
				$(this).val(FormatVol_Buatan($(this).val()));
			}
			get_JumlahHarga_Tambah();
		});

		$('.btnADD').click(function(){

			var NoUrut = 0;
			NoUrut = $('#table_order > tbody > tr').length;
			NoUrut = parseInt(NoUrut) + 1;

			var Alokasi = $('#FormTambah #Alokasi_Tambah').val(); 
			var KdRAP = $('#FormTambah #KdRAP_Tambah').val(); 

			if (Alokasi=='') {
				alert('belum pilih Alokasi');
				$('#FormTambah #Alokasi_Tambah').focus();
				return false;
			}

			if (KdRAP=='') {
				alert('belum pilih Kode RAP');
				$('#FormTambah #KdRAP_Tambah').select2('open');
				return false;
			}

			var Uraian = $('#FormTambah #Uraian_Tambah').val();
			var Vol = $('#FormTambah #Vol_Tambah').val(); 
			var Uom = $('#FormTambah #Uom_Tambah').val();
			var HrgSatuan = $('#FormTambah #HrgSatuan_Tambah').val();
			var JumlahHarga = $('#FormTambah #JumlahHarga_Tambah').val();
			var PPNCheck_Tambah = '';
			var PPNCheck_Dipilih_Tambah_ID ='0';

			if ($('#FormTambah #PPNCheck_Tambah').is(':checked')==true) 
			{
				PPNCheck_Tambah = 'checked';
				PPNCheck_Dipilih_Tambah_ID ='1';
			}else{
				PPNCheck_Tambah = '';
				PPNCheck_Dipilih_Tambah_ID ='0';
			}
			var PPN_Nominal_Tambah = $('#FormTambah #PPN_Nominal_Tambah').val();


			var TotalNominalPPN_Tambah = $('#FormTambah #TotalNominalPPN_Tambah').val();

			var PeriodePengiriman1 = $('#FormTambah #PeriodePengiriman1').val();
			var PeriodePengiriman2 = $('#FormTambah #PeriodePengiriman2').val();
			var PeriodePembayaran1 = $('#FormTambah #PeriodePembayaran1').val();
			var PeriodePembayaran2 = $('#FormTambah #PeriodePembayaran2').val();


			mydata = '';
			mydata += '<tr class="tdID'+NoUrut+'">';
			mydata += '<td>'+NoUrut+'<input type="text" name="NoUrut_SPR[]" value="'+NoUrut+'" class="hidden"></td>';
			mydata += '<td>'+Alokasi+'<input type="text" name="Alokasi_SPR[]" value="'+Alokasi+'" class="hidden"></td>';
			mydata += '<td>'+KdRAP+'<input type="text" name="KdRAP_SPR[]" value="'+KdRAP+'" class="hidden"></td>';
			mydata += '<td id="td_UraianInput_ID_'+NoUrut+'" ><textarea name="Uraian[]" id="UraianID_'+NoUrut+'" class="form-control huruf12" maxlength="255" rows="3">'+Uraian+'</textarea></td>';

			mydata += '<td id="td_VolInput_ID_'+NoUrut+'" class="text-right" ><input type="text" name="Vol[]" id="VolID_'+NoUrut+'" class="form-control huruf12 text-right VolMengetik " data-NoUrut="'+NoUrut+'" value="'+Vol+'" ></td>';

			mydata +='<td id="td_UomInput_ID_'+NoUrut+'" class="text-center" ><input type="text" name="Uom[]" id="UomID_'+NoUrut+'" class="form-control huruf12" value="'+Uom+'" maxlength="15" ></td>';

			mydata +='<td id="td_HrgSatuanInput_ID_'+NoUrut+'" class="text-right"><input type="text" name="HrgSatuan[]" id="HrgSatuanID_'+NoUrut+'" class="form-control huruf12 text-right HrgSatuanMengetik " data-NoUrut="'+NoUrut+'" value="'+HrgSatuan+'" ></td>';

			mydata +='<td id="td_JumlahHargaInput_ID_'+NoUrut+'"  class="text-right"><input type="text" name="JumlahHarga[]" id="JumlahHargaID_'+NoUrut+'" class="form-control huruf12  text-right JumlahHargaClass" value="'+JumlahHarga+'"  readonly></td>';

			mydata +='<td id="td_PPNCheckInput_ID_'+NoUrut+'"  class="text-right">';
			mydata +='<input type="checkbox" name="PPNCheck[]" id="PPNCheck_ID'+NoUrut+'" onchange="PPNCheck_Berganti('+NoUrut+')" data-NoUrut="'+NoUrut+'" value="1" class="PPNCheck_CLASS"  '+PPNCheck_Tambah+' >';
			mydata +='<input type="text" name="PPNCheck_Dipilih[]" id="PPNCheck_Dipilih_ID'+NoUrut+'" value="'+PPNCheck_Dipilih_Tambah_ID+'" class="hidden">';
			mydata +='</td>';

			mydata += '<td id="td_PPN_Nominal_Input_ID_'+NoUrut+'" class="text-right" style="display:none;"><input type="text" name="PPN_Nominal[]" id="PPN_NominalID_'+NoUrut+'" class="form-control huruf12 text-right PPN_NominalClass" data-NoUrut="'+NoUrut+'" value="'+PPN_Nominal_Tambah+'" readonly ></td>';

			mydata += '<td id="td_TotalNominalPPN_Input_ID_'+NoUrut+'" class="text-right"><input type="text" name="TotalNominalPPN[]" id="TotalNominalPPN_ID_'+NoUrut+'" class="form-control huruf12 text-right TotalNominalPPNClass" data-NoUrut="'+NoUrut+'" value="'+TotalNominalPPN_Tambah+'" readonly></td>';

			mydata += '<td id="td_PeriodePengiriman1_Input_ID_' + NoUrut + '" class="text-right"><input type="date" name="PeriodePengiriman1[]" id="PeriodePengiriman1ID_' + NoUrut + '" class="form-control huruf10 text-right PeriodePengiriman1Class" data-NoUrut="' + NoUrut + '" value="' + PeriodePengiriman1 + '" ></td>';

			mydata += '<td id="td_PeriodePengiriman2_Input_ID_' + NoUrut + '" class="text-right"><input type="date" name="PeriodePengiriman2[]" id="PeriodePengiriman2ID_' + NoUrut + '" class="form-control huruf10 text-right PeriodePengiriman2Class" data-NoUrut="' + NoUrut + '" value="' + PeriodePengiriman2 + '" ></td>';

			mydata += '<td id="td_PeriodePembayaran1_Input_ID_' + NoUrut + '" class="text-right"><input type="date" name="PeriodePembayaran1[]" id="PeriodePembayaran1ID_' + NoUrut + '" class="form-control huruf10 text-right PeriodePembayaran1Class" data-NoUrut="' + NoUrut + '" value="' + PeriodePembayaran1 + '" ></td>';

			mydata += '<td id="td_PeriodePembayaran2_Input_ID_' + NoUrut + '" class="text-right"><input type="date" name="PeriodePembayaran2[]" id="PeriodePembayaran2ID_' + NoUrut + '" class="form-control huruf10 text-right PeriodePembayaran2Class" data-NoUrut="' + NoUrut + '" value="' + PeriodePembayaran2 + '" ></td>';

			mydata +='<td class="text-center"><button class="btn btn-default text-blue text-bold" id="btnDeleteID_'+NoUrut+'" onclick="btnDelete('+NoUrut+')">DELETE</button></td>';

			mydata += '</tr>';

			$(mydata).appendTo('.table_order_body');


			$('.VolMengetik').on('input', function(e) 
			{

				var inputValue = $(this).val();

				var filteredValue = inputValue.replace(/[^0-9.-]/g, ''); 


				if (filteredValue.indexOf('-') > 0) { 
					filteredValue = filteredValue.replace(/-/g, '');
					filteredValue = '-' + filteredValue;
				}


				if (filteredValue.indexOf('.') == 0) { 
					filteredValue = '0' + filteredValue;
				}


				var parts = filteredValue.split('.');
				if (parts[1] && parts[1].length > 3) {
					parts[1] = parts[1].substr(0, 3);
					filteredValue = parts.join('.');
				}

				$(this).val(filteredValue);


			});

			$('.VolMengetik').focusin(function()
			{

				if (this.value=='0.000') {
					$(this).val('');
				}


			});

			$('.VolMengetik').focusout(function()
			{

				if (this.value=='') {
					$(this).val('0.000');
				}else{
					$(this).val(FormatVol_Buatan($(this).val()));
				}

				var NoUrut = $(this).attr('data-NoUrut');
				get_JumlahHarga(NoUrut);
				get_SubTotal();

			});

			$('.HrgSatuanMengetik').on('input', function(e) 
			{


				var inputValue = $(this).val();


				var filteredValue = inputValue.replace(/[^0-9.-]/g, ''); 

				if (filteredValue.indexOf('-') > 0) { 
					filteredValue = filteredValue.replace(/-/g, '');
					filteredValue = '-' + filteredValue;
				}


				if (filteredValue.indexOf('.') == 0) {
					filteredValue = '0' + filteredValue;
				}


				var parts = filteredValue.split('.'); 
				if (parts[1] && parts[1].length > 2) {
					parts[1] = parts[1].substr(0, 2);
					filteredValue = parts.join('.');
				}


				$(this).val(filteredValue);


			});

			$('.HrgSatuanMengetik').focusin(function()
			{

				if (this.value=='0') {
					$(this).val('');
				}

			});

			$('.HrgSatuanMengetik').focusout(function()
			{
				if (this.value=='') {
					$(this).val('0');
				}else{
					$(this).val(FormatVol_Buatan($(this).val()));
				}
				var NoUrut = $(this).attr('data-NoUrut');
				get_JumlahHarga(NoUrut);
				get_SubTotal();
			}); 

			$('#TxtDiscPersen').on('input', function(e) 
			{
				var inputValue = $(this).val();

				if (inputValue< -100) {
					$(this).val(-100);
					return false;
				}
				if (inputValue > 100) {
					$(this).val(100);
					return false;
				}

				var filteredValue = inputValue.replace(/[^0-9.-]/g, ''); 

				if (filteredValue.indexOf('-') > 0) { 
					filteredValue = filteredValue.replace(/-/g, '');
					filteredValue = '-' + filteredValue;
				}


				if (filteredValue.indexOf('.') == 0) {
					filteredValue = '0' + filteredValue;
				}


				var parts = filteredValue.split('.'); 
				if (parts[1] && parts[1].length > 2) {
					parts[1] = parts[1].substr(0, 2);
					filteredValue = parts.join('.');
				}


				$(this).val(filteredValue);


			});

			$('#TxtDiscPersen').focusin(function()
			{

				if (this.value=='0') {
					$(this).val('');
				}

			});

			$('#TxtDiscPersen').focusout(function()
			{
				if (this.value=='') {
					$(this).val('0');
				}else{
					$(this).val(FormatVol_Buatan($(this).val()));
				}

				get_DiscNominal();
				get_GrandTotal();

			}); 

			$('#FormTambah #Alokasi_Tambah').val(''); 
			$('#FormTambah #KdRAP_Tambah').find('option').remove();
			$('#FormTambah #KdRAP_Tambah').append('<option></option>');
			$('#FormTambah #Uraian_Tambah').val('');
			$('#FormTambah #Vol_Tambah').val(parseFloat(0.000));
			$('#FormTambah #Uom_Tambah').val('');
			$('#FormTambah #HrgSatuan_Tambah').val(0);
			$('#FormTambah #JumlahHarga_Tambah').val(0);
			$('#FormTambah #PPNCheck_Tambah').prop('checked',false);
			$('#FormTambah #PPN_Nominal_Tambah').val(0);
			$('#FormTambah #TotalNominalPPN_Tambah').val(0);


			$('#FormTambah').addClass('hidden');
			$('.btnCancelTambahOrder').addClass('hidden');
			$('.btnTambahOrder').removeClass('hidden');

			get_SubTotal();

		});	
});

$(function(){
	$('#CbOverride').change(function(){
		CekOvveridePPN();
	});

	$(document).on('input','#TxtPPN',function(){
		var inputValue = $(this).val();
		var filteredValue = inputValue.replace(/[^0-9.-]/g, ''); 

		if (filteredValue.indexOf('-') > 0) { 
			filteredValue = filteredValue.replace(/-/g, '');
			filteredValue = '-' + filteredValue;
		}

		if (filteredValue.indexOf('.') == 0) { 
			filteredValue = '0' + filteredValue;
		}

		var parts = filteredValue.split('.');
		if (parts[1] && parts[1].length > 2) {
			parts[1] = parts[1].substr(0, 2);
			filteredValue = parts.join('.');
		}

		$(this).val(filteredValue);

	});

	$(document).on('focusin','#TxtPPN',function(){
		if (this.value=='0.00' || this.value=='0') {
			$(this).val('');
		}else{
			$(this).val(this.value.split(",").join(""));
		}
	});

	$(document).on('focusout','#TxtPPN',function(){
		if (this.value=='') {
			$(this).val(0);
		}else{
			var ValIni = this.value;
			ValIni = ValIni.split(",").join("");
			ValIni = parseFloat(ValIni).toFixed(0);
			ValIni = Format_NilaiKontrak(ValIni);
			$(this).val(addCommas(ValIni));
		}

		// get_NPWP();
		get_SubTotal();

	});
})



function CekOvveridePPN(){
	
	if ($('#CbOverride').is(':checked')==true) {
		$('#TxtPPN').prop('readonly',false);
		$('#TxtPPN').val('0');
		get_NPWP();
	}else{
		$('#TxtPPN').prop('readonly',true);
	}

	if ($('#table_order > tbody > tr').length >0) {
		get_SubTotal();
	} 

	
	get_GrandTotal()


}

function get_JumlahHarga_Tambah()
{

	var Vol = ValidasiAngka(($('#FormTambah #Vol_Tambah').val()).split(',').join('').split('$').join(''));
	var HrgSatuan = ValidasiAngka(($('#FormTambah #HrgSatuan_Tambah').val()).split(',').join('').split('$').join(''));

	var JumlahHarga = 0;
	var JumlahHarga_IncludePPN = 0;
	var PPN_Nominal = 0;
	var TotalNominalPPN = 0;

	Vol = parseFloat(Vol);
	HrgSatuan = parseFloat(HrgSatuan);

	JumlahHarga = Vol * HrgSatuan;
	JumlahHarga = parseFloat(JumlahHarga);

	if ($('#FormTambah #PPNCheck_Tambah').is(':checked')==true) {
		PPN_Nominal = HrgSatuan * 0.11;

		PPN_Nominal = parseFloat(PPN_Nominal);
		TotalNominalPPN = PPN_Nominal * Vol;
	}else{
		PPN_Nominal = 0;
		TotalNominalPPN = 0;
	}

	$('#FormTambah #PPN_Nominal_Tambah').val(FormatVol_Buatan(PPN_Nominal));
	$('#FormTambah #JumlahHarga_Tambah').val(FormatVol_Buatan(JumlahHarga));
	$('#FormTambah #TotalNominalPPN_Tambah').val(FormatVol_Buatan(TotalNominalPPN));
}

function PPNCheck_Berganti_Tambah()
{
	get_JumlahHarga_Tambah();
}


function PPNCheck_Berganti(NoUrut)
{

	if ($('#PPNCheck_ID'+NoUrut).is(':checked')==true) {
		$('#PPNCheck_Dipilih_ID'+NoUrut).val('1');
	}else{
		$('#PPNCheck_Dipilih_ID'+NoUrut).val('0');
	}
	get_JumlahHarga(NoUrut);
	get_SubTotal();
}

function FormatVol_Buatan(num)
{
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
	return("" + formatted + ((parts) ? "." + parts[1].substr(0, 3) : ""));
}

function ValidasiAngka(data)
{
	return (data==0 || data==''|| data=='0') ? 0 : data;
}

function addCommas(nStr)
{
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

function btnDelete(NoUrut)
{
	$('#table_order > tbody > tr.tdID'+NoUrut).remove();
	get_SubTotal();
}

function get_JumlahHarga(NoUrut)
{

	var Vol = ValidasiAngka(($('#VolID_'+NoUrut).val()).split(',').join('').split('$').join(''));
	var HrgSatuan = ValidasiAngka(($('#HrgSatuanID_'+NoUrut).val()).split(',').join('').split('$').join(''));

	var JumlahHarga = 0;
	var JumlahHarga_IncludePPN = 0;
	var PPN_Nominal = 0;
	var TotalNominalPPN = 0;

	Vol = parseFloat(Vol);
	HrgSatuan = parseFloat(HrgSatuan);

	JumlahHarga = Vol * HrgSatuan;
	// JumlahHarga = JumlahHarga.toFixed(2);
	JumlahHarga = parseFloat(JumlahHarga);

	if ($('#PPNCheck_ID'+NoUrut).is(':checked')==true) {
		PPN_Nominal = HrgSatuan * 0.11;
		// PPN_Nominal = PPN_Nominal.toFixed(2);	
		PPN_Nominal = parseFloat(PPN_Nominal);
		TotalNominalPPN = PPN_Nominal * Vol;
	}else{
		PPN_Nominal = 0;
		TotalNominalPPN = 0;
	}

	$('#PPN_NominalID_'+NoUrut).val(FormatVol_Buatan(PPN_Nominal));
	$('#JumlahHargaID_'+NoUrut).val(FormatVol_Buatan(JumlahHarga));
	$('#TotalNominalPPN_ID_'+NoUrut).val(FormatVol_Buatan(TotalNominalPPN));


}

function get_SubTotal()
{

	// alert('aaa');
	var SubTotal = 0;
	var SubTotal_PPN_Nominal = 0;
	var TxtPPN = 0;
	var TxtDiscNominal = 0;
	var SubTotal_TotalNominalPPN = 0;

	var TxtDiscPersen = ($('#TxtDiscPersen').val()).split(',').join('').split('$').join('');
	TxtDiscPersen = parseFloat(TxtDiscPersen);


	$('#table_order > tbody > tr').each(function(index,tr){

		var JumlahHarga = 0;
		var PPN_NominalClass = 0 ;
		var TotalNominalPPNClass = 0;


		JumlahHarga = ($(this).find(".JumlahHargaClass").val()).split(',').join('').split('$').join('');
		if ($(this).find('.PPNCheck_CLASS').is(':checked')==true) {
			PPN_NominalClass = ($(this).find('.PPN_NominalClass').val()).split(',').join('').split('$').join('');
			TotalNominalPPNClass = ($(this).find('.TotalNominalPPNClass').val()).split(',').join('').split('$').join('');
		}else{
			PPN_NominalClass = 0;
			TotalNominalPPNClass = 0;
		}


		JumlahHarga = parseFloat(JumlahHarga);
		PPN_NominalClass = parseFloat(PPN_NominalClass);
		TotalNominalPPNClass = parseFloat(TotalNominalPPNClass);

		SubTotal += JumlahHarga;
		SubTotal_PPN_Nominal += PPN_NominalClass;
		SubTotal_TotalNominalPPN += TotalNominalPPNClass;
	})

	TxtDiscNominal = SubTotal * (TxtDiscPersen / 100);
	TxtDiscNominal = parseFloat(TxtDiscNominal);

	$('#TxtDiscNominal').val(FormatVol_Buatan(TxtDiscNominal));
	$('.SubTotalClass').text(FormatVol_Buatan(SubTotal));
	$('.SubTotal_PPN_Nominal').text(FormatVol_Buatan(SubTotal_PPN_Nominal));
	$('.SubTotal_TotalNominalPPN').text(FormatVol_Buatan(SubTotal_TotalNominalPPN));
	// $('#TxtPPN').val(FormatVol_Buatan(SubTotal_PPN_Nominal));

	// if ($('#CbOverride').is(':checked')==false ||   ) {}
	// alert($('#CbOverride').is(':checked'));
	if ($('#CbOverride').is(':checked')==true) {
		var TxtPPN_SaatIni = ($('#TxtPPN').val()).split(',').join('').split('$').join('');
		TxtPPN_SaatIni = parseFloat(TxtPPN_SaatIni);

		SubTotal_TotalNominalPPN = TxtPPN_SaatIni;
	}

	$('#TxtPPN').val(FormatVol_Buatan(SubTotal_TotalNominalPPN));

	get_NPWP();
	get_GrandTotal();

}

function get_DiscNominal()
{
	var TxtDiscNominal = 0;
	var TxtDiscPersen = 0;
	var SubTotal = 0;

	var TxtDiscPersen = ($('#TxtDiscPersen').val()).split(',').join('').split('$').join('');
	var SubTotal = ($('.SubTotalClass').text()).split(',').join('').split('$').join('');

	TxtDiscPersen = parseFloat(TxtDiscPersen);
	SubTotal = parseFloat(SubTotal);

	TxtDiscNominal = SubTotal * (TxtDiscPersen / 100);
	TxtDiscNominal = parseFloat(TxtDiscNominal);
	$('#TxtDiscNominal').val(FormatVol_Buatan(TxtDiscNominal));
}

function get_GrandTotal()
{

	var SubTotal = 0;
	var TxtPPN = 0;
	var TxtDiscNominal = 0;
	var TxtTotal = 0;

	SubTotal = ($('.SubTotalClass').text()).split(',').join('').split('$').join('');
	TxtPPN = ($('#TxtPPN').val()).split(',').join('').split('$').join('');
	TxtDiscNominal = ($('#TxtDiscNominal').val()).split(',').join('').split('$').join('');

	SubTotal = parseFloat(SubTotal);
	TxtPPN = parseFloat(TxtPPN);
	TxtDiscNominal = parseFloat(TxtDiscNominal);

	TxtTotal = (SubTotal - TxtDiscNominal) + TxtPPN;
	TxtTotal = parseFloat(TxtTotal);
	$('#TxtTotal').val(FormatVol_Buatan(TxtTotal));

}

function addCommas2(nStr)
{
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

function Format_NilaiKontrak(num)
{
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
}



</script>