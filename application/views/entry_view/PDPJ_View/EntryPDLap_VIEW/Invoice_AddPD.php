
<table width="100%" border="0" class="margin_atas_5">
	<tr>
		<th width="100px">Total Invoice</th>
		<td width="15px" class="text-center">:</td>
		<th width="300px" >
			<input type="text" name="TotalInvoice" id="TotalInvoice" class="form-control" value="<?=number_format($TotalInvoice)?>" readonly>
		</th>

		<th width="110px" style="padding-left:10px !important;">Total Centang</th>
		<td width="15px" class="text-center">:</td>
		<th width="300px">
			<input type="text" name="TotalCentang" id="TotalCentang" class="form-control" value="<?=number_format($TotalCentang)?>" readonly>
		</th>
		<td></td>
	</tr>
</table>

<table width="100%" class="table table-bordered table-striped table-hover margin_atas_5" id="mytable_Invoice">
	<thead>
		<tr style="color:White;background-color:#5D7B9D;font-weight:bold;text-align: center;" class="huruf14">
			<th width="50px" class="text-center">
				<input type="checkbox" id="select_all_Invoice">
			</th>
			<th width="20px">No</th>
			<th width="120px">No. KO</th>
			<th>Vendor</th>
			<th width="180px">No. Invoice</th>
			<th width="100px">Tgl Invoice</th>
			<th width="120px">Jatuh Tempo</th>
			<th width="180px">Total (Rp)</th>
			<th width="180px">PPN (Rp)</th>
			<th width="180px">No. FP</th>
			<th width="120px">Tgl FP</th>
			<th width="250px">Total Bayar (Rp)</th>
			<th style="display:none;">Total Yang Sudah Terbayar</th>
			<th style="display:none;" >Original Payment</th>
			<th style="display:none;" >Total</th>
		</tr>
	</thead>
	<tbody>
		<?php 

		foreach ($List_Invoice as $rowInvoice): ?>
			<tr>
				<td class="text-center">
					
					<!-- <input type="text" class="form-control" name="NoUrutInvoice[]" value="<?=$rowInvoice->NoUrutInvoice?>" > -->

					<input type="checkbox" class="emp_checkbox_Invoice" name="checkbox_tercentang[<?=$rowInvoice->NoUrutInvoice?>]" value="<?=$rowInvoice->NoUrutInvoice?>"  data-InvNo="<?=htmlspecialchars($rowInvoice->InvNo)?>" data-NoUrutInvoice="<?=$rowInvoice->NoUrutInvoice?>">
				</td>
				<td><?=$rowInvoice->NoUrutInvoice?></td>
				<td><?=$rowInvoice->NoKO?></td>
				<td><?=$rowInvoice->VendorNm?></td>
				<td class="InvNo_Class"><?=htmlspecialchars($rowInvoice->InvNo)?></td>
				<td><?=tgl_baru($rowInvoice->InvDate)?></td>
				<td><?=tgl_baru($rowInvoice->DueDate)?></td>
				<td class="text-right Total_DariTblInvoice"><?=number_format($rowInvoice->Total)?></td>
				<td class="text-right"><?=number_format($rowInvoice->PPN)?></td>
				<td><?=htmlspecialchars($rowInvoice->FPNo)?></td>
				<td><?=tgl_baru($rowInvoice->FPDate)?></td>
				<td class="text-right">

					<input type="hidden" name="InvNo[<?=$rowInvoice->NoUrutInvoice?>]" class="form-control input-sm huruf14" value="<?=htmlspecialchars($rowInvoice->InvNo)?>" readonly>

					<input type="text" name="TotalBayar[<?=$rowInvoice->NoUrutInvoice?>]" id="TotalBayar" class="form-control input-sm huruf14 text-right TotalBayar_Class" value="<?=number_format($rowInvoice->TotalBayar)?>">
				</td>
				<td style="display:none;">
					<!-- == total yang sudah terbayar -->
					<input type="text" name="TotalPayment[<?=$rowInvoice->NoUrutInvoice?>]" class="form-control input-sm huruf14 text-right TotalPayment_Class" value="<?=number_format($rowInvoice->TotalPayment)?>">
				</td>
				<td style="display:none;">
					<input type="text" name="OriginalPayment[<?=$rowInvoice->NoUrutInvoice?>]" class="form-control input-sm huruf14 text-right OriginalPayment_Class" value="<?=number_format($rowInvoice->OriginalPayment)?>">
				</td>

				<td style="display:none;">
					<input type="text" name="Total[<?=$rowInvoice->NoUrutInvoice?>]" class="form-control input-sm huruf14 text-right" value="<?=number_format($rowInvoice->Total)?>">
				</td>
			</tr>
		<?php endforeach ?>	
	</tbody>

</table>

<script type="text/javascript">
	$(function(){
		$(".TotalBayar_Class").on('input', function(e) 
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

			$('#btnSimpanPD').attr('disabled',true);
		});

		$(".TotalBayar_Class").focusin(function()
		{
			if (this.value=='0.00' || this.value=='0') {
				$(this).val('');
			}else{
				TotalBayar_Class = this.value.split(',').join('').split('$').join('');
				$(this).val(TotalBayar_Class);
			}


			$('#btnSimpanPD').attr('disabled',true);
		});

		$(".TotalBayar_Class").focusout(function()
		{
			if (this.value=='') {
				$(this).val('0');
			}else{
				
				TotalBayar_Class = parseFloat(this.value);
				TotalBayar_Class = FormatUang_TotalBayar_Class(TotalBayar_Class);
				$(this).val(TotalBayar_Class);
			}

			$('#btnSimpanPD').attr('disabled',false);
			Hitung_Invoice_TotalTercentang();


		});
	})
</script>
