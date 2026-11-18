<br>
<style type="text/css">
	#punyasaya th {
		text-align: center;
		/*font-size: 12px;*/
		font-size: 10px !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
		padding-top: 10px !important;
		padding-bottom: 10px !important;
		vertical-align: middle;
	}

	#punyasaya td {
		/*font-size: 12px;*/
		font-size: 10px !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
		padding-top: 10px !important;
		padding-bottom: 10px !important;
		vertical-align: top;

	}
</style>
<div class="table-responsive">

	<table width="100%">
		<tr>
			<td>
			</td>
		</tr>
	</table>


	<table class="table table-bordered table-striped table-hover table-condensed" id="punyasaya" data-JobNo="<?=$JobNo?>" data-Alokasi="<?=$Alokasi?>">

		<thead>
			<tr style="background-color:#3498db;color:white;">
				<th style="text-align: center;width: 3%;">No</th>
				<th style="width:5%;">Kode RAP</th>
				<th style="width:40%;">Uraian</th>
				<th>Sat</th>

				<th>Vol</th>
				<th>Harga Satuan (Rp)</th>
				<th>Jumlah Harga (Rp)</th>

				<th>PJ<br /> Vol</th>
				<th>PJ<br /> Harga Satuan (Rp)</th>
				<th>PJ<br /> Jumlah Harga (Rp)</th>

				<th>#</th>

			</tr>
		</thead>
		<tbody>
			<?php
			if ($data_detail->num_rows() == 0) { ?>
				<td colspan="11">Data tidak ditemukan</td>
				<?php 
				exit;
			} ?>

			<?php
			$TotalPertanggungJawaban = 0;
			$TotalPermintaanDana = 0;
			foreach($data_detail->result_array() as $data_uraian ): ?>
				<?php

				$Vol = $data_uraian['Vol'];
				$HrgSatuan = $data_uraian['HrgSatuan'];
				$JumlahHarga = $data_uraian['JumlahHarga'];

				$PjVol = $data_uraian['PjVol'];
				$PjHrgSatuan = $data_uraian['PjHrgSatuan'];
				$PjJumlahHarga = $data_uraian['PjJumlahHarga'];

				$TotalPermintaanDana += $data_uraian['JumlahHarga'];
				$TotalPertanggungJawaban += $data_uraian['PjJumlahHarga'];

				$NoUrut = $data_uraian['NoUrut'];
				$NoPD = $data_uraian['NoPD'];

				?>
				<tr>
					<td style="padding-top:10px;padding-bottom:10px;" align="center"><?=$NoUrut?></td>
					<td><?=$data_uraian['KdRAP']?></td>
					<td style="text-align:justify;"><?=$data_uraian['Uraian']?></td>
					<td align="right"><?=$data_uraian['Uom']?></td>

					<td align="right"><?=new_decimal($Vol)?></td>
					<td align="right"><?=UbahGabunganUang($HrgSatuan)?></td>
					<td align="right"><?=UbahGabunganUang($JumlahHarga)?></td>

					<td align="right"><?=new_decimal($PjVol)?></td>
					<td align="right"><?=UbahGabunganUang($PjHrgSatuan)?></td>
					<td align="right"><?=UbahGabunganUang($PjJumlahHarga)?></td>

					<td align="center">
						<a href="javascript:void(0)" onclick="LihatUraian('<?=$NoUrut?>','<?=$NoPD?>','<?=$status_detail_PdDtl?>')" class="btn btn-sm bg-orange" id="myuraian<?=$NoUrut?>" style="background-color: #bdc3c7;color:black;">Lihat</a>
					</td>
				</tr>
			<?php endforeach; ?>

		</tbody>
		<!-- <tfoot>
			<tr style="color:black;">
				<td style="padding-top:5px;padding-bottom:5px;"></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<th align="right" style="width: 20%;">Total Permintaan Dana (Rp)</th>
				<td align="right" style="font-weight: bold;"><?=UbahGabunganUang($TotalPermintaanDana)?> </td>
				<td></td>
				<th align="right" style="width: 20%;">Total Pertanggungjawaban (Rp)</th>
				<td align="right" style="font-weight: bold;"><?=UbahGabunganUang($TotalPertanggungJawaban)?> </td>
				<td></td>
			</tr>

			<input type="hidden" name="TotalPJ" value="<?=$TotalPertanggungJawaban?>">

		</tfoot> -->

	</table>
	<table width="100%" border="0" style="border-collapse:collapse;font-weight: bold;">
		<tr>
			<td></td>
			<!-- <td style="text-align:right;width: 20%;border-bottom:1px solid silver;">TOTAL Pertanggungjawaban (Rp) : <?=UbahGabunganUang($TotalPermintaanDana)?>  </td> -->
			<td width="2%"></td>
			<td style="text-align:right;width: 40%;border-bottom:1px solid silver;">Total Pertanggungjawaban (Rp) : <?=UbahGabunganUang_new($TotalPertanggungJawaban)?> </td>
			
		</tr>
		<input type="hidden" name="TotalPJ" value="<?=$TotalPertanggungJawaban?>">
	</table>

	<br />
	<table width="100%" style="border-collapse: collapse;" border="0">
		<tr>
			<th style="text-align: right;padding-right:20px;font-weight: bold;">Dana Tersedia Di Lapangan</th>
			<td style="text-align: right;width:190px;">
				<input type="text" id="DanaTersediaDiLapangan" value="<?=UbahGabunganUang_new($TotalPermintaanDana)?>" style="text-align: right;width:190px;" class="form-control input-sm"  disabled="disabled">
			</td>
			<td width="80px"></td>
		</tr>
		<?php
		$MySaldo_saya = 0;
		$MySaldo_saya =  $TotalPermintaanDana - $TotalPertanggungJawaban;
		?>
		<tr>
			<th style="text-align: right;padding-right:20px;font-weight: bold;">Saldo</th>
			<td style="text-align: right;width:190px;">
				<input type="text"  value="<?=UbahGabunganUang_new($MySaldo_saya)?>" style="text-align: right;width:190px;" class="form-control input-sm"  disabled="disabled">
			</td>
			<td width="80px"></td>
		</tr>
	</table>

</div>


