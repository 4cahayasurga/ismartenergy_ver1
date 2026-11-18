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
		height: 700px; /* Sesuaikan tinggi sesuai kebutuhan Anda */
		overflow: auto; /* Tambahkan gulir jika konten melebihi tinggi */
		border:1px solid;
	}

	/* Atur lebar kolom tabel */
	.fixed-height-table table {
		width: 100%;
		table-layout: fixed;
	}

	/* Atur lebar dan properti lainnya untuk setiap kolom */
	.fixed-height-table th, .fixed-height-table td {
		border: 1px solid #ddd;
		padding: 8px;
		text-align: left;
		white-space: nowrap; /* Mencegah teks melompat ke baris berikutnya */
		overflow: hidden;
		text-overflow: ellipsis; /* Menambahkan elipsis untuk teks yang melebihi lebar */
	}
</style>
</style>




<div class="table-responsive">

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
					<!-- Tambahkan kolom header sesuai kebutuhan -->
				</tr>
			</thead>
			<tbody>
				<?php 
				$sumTotal = 0;
				foreach ($List_SJ as $row): 

					$VolBrgMasuk 	= 0;
					$HrgSatuan 	 	= 0;
					$Total 			= 0;

					$VolBrgMasuk 	= CekNull_Angka_Dan_Bikin_Float($row->VolBrgMasuk);
					$HrgSatuan 		= CekNull_Angka_Dan_Bikin_Float($row->HrgSatuan);
					$Total 			= $VolBrgMasuk * $HrgSatuan;

					$sumTotal += $Total;
					?>
					<tr>
						<td class="text-center">
							<input type="checkbox" class="emp_checkbox" value="<?=$row->LedgerNo?>" data-LedgerNo="<?=$row->LedgerNo?>" data-Total="<?=number_format($Total)?>">
						</td>
						<td><?=htmlspecialchars($row->KdRAP)?></td>
						<td><?=htmlspecialchars($row->Uraian)?></td>
						<td class="text-center"><?=htmlspecialchars($row->Uom)?></td>
						<td class="text-right"><?=UbahDecimal_Nol_Tiga($row->VolBrgMasuk)?></td>
						<td class="text-right"><?=number_format($row->HrgSatuan)?></td>
					</tr>
				<?php endforeach ?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="5"></th>
					<th class="text-right">sum=<?=number_format($sumTotal)?></th>
				</tr>
			</tfoot>
		</table>
	</div>

	<table width="100%" border="0">
		<tr>
			<td width="100px">Total Centang</td>
			<td width="100px">
				<input type="text" class="TotalCentang" value="0" style="text-align:right;" disabled>
			</td>
			<td></td>
		</tr>
	</table>
	<p></p>

	<table class="table table-bordered table-striped table-hover" id="tableSJ">
		<thead>
			<tr>
				<th class="atur text-center"><input type="checkbox" id="select_all"></th>
				<th class="atur">Kode RAP</th>
				<th class="atur" >Uraian</th>
				<th class="atur" >Uom</th>
				<th class="atur" >Vol</th>
				<th class="atur" >Harga (Rp)</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$sumTotal = 0;
			foreach ($List_SJ as $row): 

				$VolBrgMasuk 	= 0;
				$HrgSatuan 	 	= 0;
				$Total 			= 0;

				$VolBrgMasuk 	= CekNull_Angka_Dan_Bikin_Float($row->VolBrgMasuk);
				$HrgSatuan 		= CekNull_Angka_Dan_Bikin_Float($row->HrgSatuan);
				$Total 			= $VolBrgMasuk * $HrgSatuan;

				$sumTotal += $Total;
				?>
				<tr>
					<td class="text-center">
						<input type="checkbox" class="emp_checkbox" value="<?=$row->LedgerNo?>" data-LedgerNo="<?=$row->LedgerNo?>" data-Total="<?=number_format($Total)?>">
					</td>
					<td><?=htmlspecialchars($row->KdRAP)?></td>
					<td><?=htmlspecialchars($row->Uraian)?></td>
					<td class="text-center"><?=htmlspecialchars($row->Uom)?></td>
					<td class="text-right"><?=UbahDecimal_Nol_Tiga($row->VolBrgMasuk)?></td>
					<td class="text-right"><?=number_format($row->HrgSatuan)?></td>
				</tr>
			<?php endforeach ?>
		</tbody>
		<tfoot>
			<tr style="background-color:#5D7B9D;">
				
			</tr>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th>sum=<?=number_format($sumTotal)?></th>
		</tfoot>
	</table>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$(document).on('click', '#select_all', function() {          	
			$(".emp_checkbox").prop("checked", this.checked);
			$("#select_count").html($("input.emp_checkbox:checked").length+" Selected");
			cekData()
		});

		$(document).on('click', '.emp_checkbox', function() {		
			if ($('.emp_checkbox:checked').length == $('.emp_checkbox').length) {
				$('#select_all').prop('checked', true);
			} else {
				$('#select_all').prop('checked', false);
			}
			$("#select_count").html($("input.emp_checkbox:checked").length+" Selected");
			cekData();
		});
	});

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