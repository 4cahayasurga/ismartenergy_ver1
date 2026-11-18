<style>
	table tbody td {
		text-align: right;
	}

	table tfoot th {
		text-align: right;
	}
</style>
<div class="box-body table-responsive no-no-padding">
	<a href="<?= site_url('Welcome/cetak_assets_penerimaan/' . $tahun) ?>" target="_blank" class="btn btn-danger btn-md">PDF <i class="fa fa-print"></i></a>
	<a href="<?= site_url('Welcome/cetak_assets_penerimaan_print/' . $tahun) ?>" target="_blank" class="btn btn-default btn-md">Print <i class="fa fa-print"></i></a>
	<a href="<?= site_url('Welcome/export_excel_asset_penerimaan/' . $tahun) ?>" target="_blank" class="btn btn-success btn-md">EXPORT EXCEL <i class="fa fa-upload"></i></a>

	<br><br>

	<table id="RptProyeksiDana" class="table table-bordered table-striped table-hover text-center" border="1" cellspacing="2" width="100%">
		<thead>
			<tr>
				<th>NO.</th>
				<th>NAMA ASSET</th>
				<th>KODE ASSET</th>
				<th>JANUARI</th>
				<th>FEBRUARI </th>
				<th>MARET</th>
				<th>APRIL</th>
				<th>MEI</th>
				<th>JUNI</th>
				<th>JULI</th>
				<th>AGUSTUS</th>
				<th>SEPTEMBER</th>
				<th>OKTOBER</th>
				<th>NOVEMBER</th>
				<th>DESEMBER</th>
				<th>TOTAL</th>
			</tr>
		</thead>

		<tbody>
			<?php
			$no = 1;
			$query = $this->db->query("
			SELECT 
			JobNo ,
			SUM(CASE
				WHEN MONTH(IncomeDate)='01' THEN Netto+BiayaLain
				ELSE 0
			END) AS Januari,

			SUM(CASE
				WHEN MONTH(IncomeDate)='02' THEN Netto+BiayaLain
				ELSE 0
			END) AS Februari,

			SUM(CASE
				WHEN MONTH(IncomeDate)='03' THEN Netto+BiayaLain
				ELSE 0
			END) AS Maret,

			SUM(CASE
				WHEN MONTH(IncomeDate)='04' THEN Netto+BiayaLain
				ELSE 0
			END) AS April,

			SUM(CASE
				WHEN MONTH(IncomeDate)='05' THEN Netto+BiayaLain
				ELSE 0
			END) AS Mei,

			SUM(CASE
				WHEN MONTH(IncomeDate)='06' THEN Netto+BiayaLain
				ELSE 0
			END) AS Juni,

			SUM(CASE
				WHEN MONTH(IncomeDate)='07' THEN Netto+BiayaLain
				ELSE 0
			END) AS Juli,

			SUM(CASE
				WHEN MONTH(IncomeDate)='08' THEN Netto+BiayaLain
				ELSE 0
			END) AS Agustus,

			SUM(CASE
				WHEN MONTH(IncomeDate)='09' THEN Netto+BiayaLain
				ELSE 0
			END) AS September,

			SUM(CASE
				WHEN MONTH(IncomeDate)='10' THEN Netto+BiayaLain
				ELSE 0
			END) AS Oktober,

			SUM(CASE
				WHEN MONTH(IncomeDate)='11' THEN Netto+BiayaLain
				ELSE 0
			END) AS November,

			SUM(CASE
				WHEN MONTH(IncomeDate)='12' THEN Netto+BiayaLain
				ELSE 0
			END) AS Desember,

			SUM(CASE
				WHEN YEAR(IncomeDate)='$tahun' THEN Netto+BiayaLain
				ELSE 0
			END) AS Total,

			(SELECT JobNm FROM Job WHERE JobNo=a.JobNo
			) as JobNama
			FROM OtherIncome a WHERE JobNo=JobNo AND YEAR(IncomeDate)='$tahun' GROUP BY JobNo
			
			")->result();

			$sum_januari = 0;
			$sum_februari = 0;
			$sum_maret = 0;
			$sum_april = 0;
			$sum_mei = 0;
			$sum_juni = 0;
			$sum_juli = 0;
			$sum_agustus = 0;
			$sum_september = 0;
			$sum_oktober = 0;
			$sum_november = 0;
			$sum_desember = 0;
			$total_penerimaan_seluruhnya = 0;
			foreach ($query as $data) {
				$sum_januari += $data->Januari;
				$sum_februari += $data->Februari;
				$sum_maret += $data->Maret;
				$sum_april += $data->April;
				$sum_mei += $data->Mei;
				$sum_juni += $data->Juni;
				$sum_juli += $data->Juli;
				$sum_agustus += $data->Agustus;
				$sum_september += $data->September;
				$sum_oktober += $data->Oktober;
				$sum_november += $data->November;
				$sum_desember += $data->Desember;
				$total_penerimaan_seluruhnya += $data->Total;


			?>
				<tr>
					<td><?= $no++ ?></td>
					<td><?= $data->JobNama ?></td>
					<td><?= $data->JobNo ?></td>
					<td><?= UbahGabunganUang($data->Januari) ?></td>
					<td><?= UbahGabunganUang($data->Februari) ?></td>
					<td><?= UbahGabunganUang($data->Maret) ?></td>
					<td><?= UbahGabunganUang($data->April) ?></td>
					<td><?= UbahGabunganUang($data->Mei) ?></td>
					<td><?= UbahGabunganUang($data->Juni) ?></td>
					<td><?= UbahGabunganUang($data->Juli) ?></td>
					<td><?= UbahGabunganUang($data->Agustus) ?></td>
					<td><?= UbahGabunganUang($data->September) ?></td>
					<td><?= UbahGabunganUang($data->Oktober) ?></td>
					<td><?= UbahGabunganUang($data->November) ?></td>
					<td><?= UbahGabunganUang($data->Desember) ?></td>
					<td><?= UbahGabunganUang($data->Total) ?></td>
				</tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<th colspan="3">TOTAL PENERIMAAN SELURUHNYA</th>
			<th><?= UbahGabunganUang($sum_januari) ?></th>
			<th><?= UbahGabunganUang($sum_februari) ?></th>
			<th><?= UbahGabunganUang($sum_maret) ?></th>
			<th><?= UbahGabunganUang($sum_april) ?></th>
			<th><?= UbahGabunganUang($sum_mei) ?></th>
			<th><?= UbahGabunganUang($sum_juni) ?></th>
			<th><?= UbahGabunganUang($sum_juli) ?></th>
			<th><?= UbahGabunganUang($sum_agustus) ?></th>
			<th><?= UbahGabunganUang($sum_september) ?></th>
			<th><?= UbahGabunganUang($sum_oktober) ?></th>
			<th><?= UbahGabunganUang($sum_november) ?></th>
			<th><?= UbahGabunganUang($sum_desember) ?></th>
			<th><?= UbahGabunganUang($total_penerimaan_seluruhnya) ?></th>
		</tfoot>
	</table>
</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {

		$('#RptProyeksiDana').DataTable({

		});
	});
</script>