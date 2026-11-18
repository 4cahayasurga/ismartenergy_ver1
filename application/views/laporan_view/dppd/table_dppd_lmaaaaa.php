<style type="text/css">

	#tbl_saya{
		font-size: 10px !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
		/*white-space: nowrap;*/
	}
	th{
		padding-top: 5px !important;
		padding-bottom: 5px !important; 
	}
	/*#tbl_dppd thead tr th{
		padding-top: 7px;
		padding-bottom: 7px;
		white-space: nowrap;
	}
	#tbl_dppd tbody tr td{
		padding-top: 7px;
		padding-bottom: 7px;
	}*/
</style>
<?php
$dari_tgl = ($dari_tgl == '') ? 0 : $dari_tgl;
$sampai_tgl = ($sampai_tgl == '') ? 0 : $sampai_tgl;
?>

<div class="box">
	<div class="box-body">
		<div class="table-responsive">

			<table id="tbl_saya" class="table"  >
				<tr >
					<th class="text-center" width="2%">No</th>
					<th>No PD</th>
					<th>Tgl PD</th>
					<th>Deskripsi</th>
					<th>Prd Awal</th>
					<th>Prd Akhir</th>
					<th>No KO</th>
					<th>Nama</th>
					<th style="text-align:right;">Total PD</th>
					<th>Tgl Bayar</th>
					<th>Via</th>
					<th>Rek Pengirim</th>
					<th>Rek Penerima</th>
					<th>No PJ</th>
					<th>Tgl PJ</th>
					<th style="text-align:right;">Total PJ</th>
					<th>Tipe Form</th>
				</tr>

				<?php
				$mynomor = 1;
				foreach($myarray as $mydata) : ?>
					<tr>
						<td class="text-center" width="2%"><?=$mynomor;?></td>
						<td><?=$mydata['NoPD']?></td>
						<td><?=TanggalSaya($mydata['TglPD'])?></td>
						<td><?=$mydata['Deskripsi']?></td>
						<td><?=TanggalSaya($mydata['PrdAwal'])?></td>
						<td><?=TanggalSaya($mydata['PrdAkhir'])?></td>
						<td><?=$mydata['NoKO']?></td>
						<td><?=$mydata['Nama']?></td>
						<td class="text-right"><?=uang_koma_genap_decimal($mydata['TotalPD'])?></td>
						<td><?=TanggalSaya($mydata['TglBayar'])?></td>
						<td><?=$mydata['CaraBayar']?></td>
						<td><?=$mydata['RekPengirim']?></td>
						<td><?=$mydata['RekPenerima']?></td>
						<td><?=$mydata['NoPJ']?></td>
						<td><?=$mydata['TglPJ']?></td>
						<td class="text-right"><?=uang_koma_genap_decimal($mydata['TotalPJ'])?></td>
						<td><?=$mydata['TipeForm']?></td>
					</tr>
				<?php 
				$mynomor++;
				endforeach; ?>
			</table>

			
		</div>
	</div>
</div>


<script type="text/javascript">
	$(function() {
		// $('.tablesaya').DataTable();
	});
</script>