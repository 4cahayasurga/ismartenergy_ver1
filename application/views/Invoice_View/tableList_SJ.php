<style type="text/css">
	
	.atur{
		background-color:#5D7B9D;
		text-align: center;
		font-size: 14px !important;
		font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
		color:white;
	}

	#tableSJ tfoot tr th{
		background-color:#5D7B9D;
		text-align: right;
		font-size: 14px !important;
		font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
		color:white;
	}

	/* Set tinggi tabel */
	.fixed-height-table {
		height: 500px; /* Sesuaikan tinggi sesuai kebutuhan Anda */
		overflow: auto; /* Tambahkan gulir jika konten melebihi tinggi */
		border:1px solid silver;
	}

	/* Atur lebar kolom tabel */
	.fixed-height-table table {
		width: 100%;
		table-layout: fixed;
	}

	/* Atur lebar dan properti lainnya untuk setiap kolom */
	.fixed-height-table th, .fixed-height-table td {
		border: 1px solid silver;
		padding: 8px;
		text-align: left;
		white-space: nowrap; /* Mencegah teks melompat ke baris berikutnya */
		overflow: hidden;
		text-overflow: ellipsis; /* Menambahkan elipsis untuk teks yang melebihi lebar */
	}
</style>




<div class="table-responsive">
	<table width="100%" border="0" style="margin-bottom:10px !important;">
		<tr>
			<td width="100px">Total Centang</td>
			<td width="200px">
				<input type="text" class="TotalCentang" value="<?=number_format($sumTotal_Centang)?>" style="text-align:right;" disabled>
			</td>
			<td width="20px"></td>
			<td width="100px">Total Barang</td>
			<td width="300px">
				<input type="text" class="TotalBarang" value="<?=number_format($sumTotal)?>" style="text-align:right;" disabled>
			</td>
			<td></td>
		</tr>
	</table>
	<div class="fixed-height-table">
		
		<table id="tableSJ">
			<thead>
				<tr>
					<th width="30px" class="text-center"><input type="checkbox" id="select_all"></th>
					<th>Kode RAP</th>
					<th>Uraian</th>
					<th>Uom</th>
					<th>Vol</th>
					<th>Hrg Satuan (Rp)</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$sumTotal = 0;
				foreach ($List_SJ as $row): 

					?>
					<tr>
						<td class="text-center">
							<input type="checkbox" class="emp_checkbox" name="emp_checkbox[<?=$row->LedgerNo?>]" value="<?=$row->LedgerNo?>" 
							data-NoUrut="<?=$row->NoUrut?>" 
							data-Total="<?=number_format($row->Total)?>"
							<?=($row->IsChecked==1) ? 'checked' : ''?>
							>
						</td>
						<td>
							<?=htmlspecialchars($row->KdRAP)?></td>
							<td><?=htmlspecialchars($row->Uraian)?></td>
							<td class="text-center"><?=htmlspecialchars($row->Uom)?></td>
							<td class="text-right"><?=UbahDecimal_Nol_Tiga($row->VolBrgMasuk)?></td>
							<td class="text-right"><?=number_format($row->HrgSatuan)?></td>
							<td hidden>

								<input type="text" name="NoUrut_Multiple[<?=$row->LedgerNo?>]" value="<?=$row->NoUrut?>">
								<input type="text" name="KdRAP_Multiple[<?=$row->LedgerNo?>]" value="<?=$row->KdRAP?>">
								<input type="text" name="Uraian_Multiple[<?=$row->LedgerNo?>]" value="<?=$row->Uraian?>">
								<input type="text" name="Uom_Multiple[<?=$row->LedgerNo?>]" value="<?=$row->Uom?>">
								<input type="text" name="VolBrgMasuk_Multiple[<?=$row->LedgerNo?>]" value="<?=$row->VolBrgMasuk?>">
								<input type="text" name="HrgSatuan_Multiple[<?=$row->LedgerNo?>]" value="<?=$row->HrgSatuan?>">
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			<!-- <tfoot>
				<tr>
					<th colspan="5"></th>
					<th class="text-right">sum=<?=number_format($sumTotal)?></th>
				</tr>
			</tfoot> -->
		</table>
	</div>

	
	<p></p>
</div>

<script type="text/javascript">
	$(document).ready(function(){

		$(document).on('click', '#select_all', function() {          	
			$(".emp_checkbox").prop("checked", this.checked);
			$("#select_count").html($("input.emp_checkbox:checked").length+" Selected");
			cekData();
			// tesaja();
		});

		$(document).on('click', '.emp_checkbox', function() {		
			if ($('.emp_checkbox:checked').length == $('.emp_checkbox').length) {
				$('#select_all').prop('checked', true);

			} else {
				$('#select_all').prop('checked', false);
			}
			$("#select_count").html($("input.emp_checkbox:checked").length+" Selected");
			cekData();
			// tesaja();
			
		});
	});

	function tesaja(){
		// var datasaya = [];
		// var selectedCheckboxes = $('#tableSJ .emp_checkbox:checked').map(function(){
		// 	return $(this).val();
		// }).get();
		// console.log(selectedCheckboxes);
		// return false;

		var selectedCheckboxes = [];

		$('.emp_checkbox:checked').each(function() {
			var LedgerNo = $(this).val();
			var NoUrut = $(this).attr('data-NoUrut');
			var KdRAP = $(this).attr('data-KdRAP');
			var Uraian = $(this).attr('data-Uraian');
			var Uom = $(this).attr('data-Uom');
			var VolBrgMasuk = $(this).attr('data-VolBrgMasuk');
			var HrgSatuan = $(this).attr('data-HrgSatuan');

        	// Menambahkan nilai checkbox dan nilai tambahan ke dalam array
			selectedCheckboxes.push({
				LedgerNo: LedgerNo,
				NoUrut: NoUrut,
				KdRAP: KdRAP,
				Uraian: Uraian,
				Uom: Uom,
				VolBrgMasuk: VolBrgMasuk,
				HrgSatuan: HrgSatuan,
			});
		});

      	// Menampilkan array di console
		console.log(selectedCheckboxes);


		// const arr = ["hello","from","GFG"];

		// console.log("traversing in array using jQuery");
		// jquery.each(arr, function(index,value) {
		// 	console.log('index: ' + index + '   ' + 'value: ' + value);
		// });


		// var chekedValue = [];
		// $('.emp_checkbox:checked').each(function (index,value) {
		// 	chekedValue.push({
		// 		title: value
		// 	});
		// });
		// $('.emp_checkbox:checked').each(function(index){
		// 	// chekedValue.push($(this).val());
		// 	// console.log($(this).val());

		// 	var LedgerNo_Multiple 	= $(this).val();
		// 	// var KdRAP_Multiple 		= $('#KdRAP_multiple_ID_'+LedgerNo_Multiple).val();

		// 	// chekedValue.push("LedgerNo_Multiple");
		// 	// chekedValue.push("KdRAP_Multiple");

		// 	chekedValue.push({"LedgerNo_Multiple" : $(this).val()});
		// });
		// console.log(chekedValue);
		// return false;
	}

	function cekData(){
		var selected = new Array();
		var sumTotal = 0;
		if ($('#tableSJ tbody tr').length > 0) {
			$('#tableSJ tbody tr ').each(function(index,tr) {

				$(this).find('td input:checked').each(function () {
					var Total = ($(this).attr('data-Total'));
					Total = Total.split(",").join("");
					Total = parseFloat(Total);
					// Total = Format_NilaiKontrak(Total);
					sumTotal += Total;
					
				});
			});

		}

		// console.log(Format_NilaiKontrak(sumTotal));
		$('.TotalCentang').val(addCommas(sumTotal));
	}
</script>