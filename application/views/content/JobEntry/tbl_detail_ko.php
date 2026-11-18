<div class="box">
	<div class="box-body">
		<div class="box-body table-responsive no-padding">
			<table class="table table-hover table-bordered table-striped table-condensed">
				<thead>
					<tr>
						<th style="text-align: center;width:50px;">No.</th>
						<th>Kode RAP</th>
						<th>Uraian</th>
						<th>Volume</th>
						<th>Satuan</th>
						<th>Vol Yang Sudah Di Terima</th>
						<th>Volume Barang Masuk</th>
						<th style="text-align: right;">Outstanding</th>
						<th hidden>Harga Satuan</th>
						<th style="text-align: right;" hidden>Total</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$no = 1;
					$subtotal = 0;
					if ($mydata->num_rows() == 0) {
						echo '<tr><th colspan="7" class="text-center">Data Tidak Ditemukan<th></tr>';
					} else {
						foreach ($mydata->result_array() as $ko_dtl) {
							$subtotal += $ko_dtl['total'];
					?>
							<tr>
								<td><?= $no++ . '.' ?></td>
								<td><?= $ko_dtl['KdRAP'] ?></td>
								<td><?= $ko_dtl['Uraian'] ?></td>
								<td><?= floor($ko_dtl['Vol']) ?></td>
								<td><?= strtoupper($ko_dtl['Uom']) ?></td>
								<td></td>
								<td><input type="text" name="qtymasuk" id="qtymasuk" class="form-control"></td>
								<td style="text-align: right;"><input type="text" name="Outstanding" id="Outstanding" class="form-control"></td>
								<td hidden><?= UbahGabunganUang($ko_dtl['HrgSatuan']) ?></td>
								<td style="text-align: right;" hidden><?= UbahGabunganUang($ko_dtl['total']) ?></td>

							</tr>

						<?php } ?>
					<?php } ?>
				</tbody>
				<tfoot hidden>
					<tr>
						<th colspan="6" style="text-align: right;">Sub Total</th>
						<th style="text-align: right;"><?= UbahGabunganUang($subtotal) ?></th>
					</tr>
				</tfoot>


			</table>
		</div>
	</div>
</div>
