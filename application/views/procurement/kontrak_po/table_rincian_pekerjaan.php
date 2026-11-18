<div class="table-responsive">
	<table id="table_rincian" class="table table-bordered table-striped table-hover table-condesed" data-NoKO="<?= $NoKO ?>">
		<thead>
			<tr>
				<th style="text-align:center;width:5%;">No.</th>
				<th style="text-align:center;width:20%;">#</th>
				<th style="text-align:center;width:5%;">Kode RAP</th>
				<th style="text-align:center;width:30%;">Uraian</th>
				<th style="text-align:center;width:5%;">Vol</th>
				<th style="text-align:center;width:5%;">Sat</th>
				<th style="text-align:center;width:10%;">Harga Satuan (Rp)</th>
				<th style="text-align:center;width:10%;">Jumlah Harga (Rp)</th>
			</tr>
		</thead>
		<tbody>

			<?php
			$SubTotal = 0;
			$no = 1;
			foreach ($data_detail as $data_detail) :
				$SubTotal += $data_detail['JumlahHarga'];
				$NoUrut = $data_detail['NoUrut'];
			?>

				<tr>
					<td style="text-align:center;width:5%;"><?= strtoupper($no++) . '.' ?></td>
					<td style="text-align:center;width:10%;">
						<a href="javascript:void(0)" class="btn btn-xs btn-danger" onclick="hapus_uraian('<?= $NoUrut ?>')"><i class="fa fa-trash"></i> Hapus</a>

						<a href="javascript:void(0)" class="btn btn-xs btn-warning" onclick="edit_uraian('<?= $NoUrut ?>')"><i class="fa fa-pencil"></i> Edit</a>
					</td>
					<td style="text-align:left;width:100px;"><?= strtoupper($data_detail['KdRAP']) ?></td>
					<td style="text-align:left;width"><?= strtoupper($data_detail['Uraian']) ?></td>
					<td style="text-align:right;"><?= strtoupper(number_format($data_detail['Vol'])) ?></td>
					<td style="text-align:center;"><?= strtoupper($data_detail['Uom']) ?></td>
					<td style="text-align:right;"><?= strtoupper(number_format($data_detail['HrgSatuan'])) ?></td>
					<td style="text-align:right;"><?= strtoupper(number_format($data_detail['JumlahHarga'])) ?></td>
				</tr>
			<?php endforeach; ?>

		</tbody>

		<?php
		$PotongKO = NULL;
		$TotalPengurangan = 0;
		$GrandTotal = 0;

		$hitung = $SubTotal - $TotalPengurangan;
		$GrandTotal = $hitung;

		?>


		<tfoot>
			<tr>
				<th colspan="7" style="text-align:right;vertical-align: middle">SUBTOTAL</th>
				<th style="text-align:right;">
					<input type="text" class="form-control input-sm" style="text-align:right;" name="SubTotal" value="<?= number_format($SubTotal) ?>" readonly>
				</th>
			</tr>

			<tr>
				<th colspan="7" style="text-align:right;vertical-align: middle;">DIKURANGI KO</th>
				<th style="text-align:right;">
					<input type="text" class="form-control input-sm" style="text-align:right;" name="PotongKO" value="<?= $PotongKO ?>" readonly>
				</th>
			</tr>

			<tr>
				<th colspan="7" style="text-align:right;vertical-align: middle;">TOTAL PENGURANGAN</th>
				<th style="text-align:right;">
					<input type="text" class="form-control input-sm" style="text-align:right;" value="<?= number_format($TotalPengurangan) ?>" readonly>
				</th>
			</tr>

			<tr>
				<th colspan="4" style="color:blue;"><small><i>Terbilang : <?= ucwords(terbilang($GrandTotal)) ?></i></small></th>
				<th colspan="3" style="text-align:right;vertical-align: middle;">GRAND TOTAL</th>
				<th style="text-align:right;">
					<input type="text" class="form-control input-sm" style="text-align:right;" name="GrandTotal" value="<?= number_format($GrandTotal) ?>" readonly>
				</th>
			</tr>

		</tfoot>

	</table>

</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#table_rincian').DataTable();
	})
</script>