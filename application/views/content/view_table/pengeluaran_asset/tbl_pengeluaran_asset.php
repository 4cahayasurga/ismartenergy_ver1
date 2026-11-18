<div class="col-sm-12"><br><br>
	<a href="<?= site_url('Welcome/cetak_pengeluaran_asset/'.$tahun) ?>" target="_blank" class="btn btn-danger btn-md">PDF <i class="fa fa-print"></i></a>
	<!-- <a href="<?= site_url('Welcome/export_excel_pengeluaran_asset/'.$tahun) ?>" target="_blank" class="btn btn-success btn-md">EXPORT EXCEL <i class="fa fa-upload"></i></a> -->
	<br><br>
	<div class="table-responsive ">
		<table class="table table-bordered table-striped table-hover table-condensed" id="my-table">
			<!-- <thead> -->
				<tr>
					<th>NO.</th>
					<th style="padding-top:5px;padding-bottom:5px;">NAMA ASSET</th>
					<th style="padding-top:5px;padding-bottom:5px;">KODE ASSET</th>
					<th style="padding-top:5px;padding-bottom:5px;">JANUARI</th>
					<th style="padding-top:5px;padding-bottom:5px;">FEBRUARI </th>
					<th style="padding-top:5px;padding-bottom:5px;">MARET</th>
					<th style="padding-top:5px;padding-bottom:5px;">APRIL</th>
					<th style="padding-top:5px;padding-bottom:5px;">MEI</th>
					<th style="padding-top:5px;padding-bottom:5px;">JUNI</th>
					<th style="padding-top:5px;padding-bottom:5px;">JULI</th>
					<th style="padding-top:5px;padding-bottom:5px;">AGUSTUS</th>
					<th style="padding-top:5px;padding-bottom:5px;">SEPTEMBER</th>
					<th style="padding-top:5px;padding-bottom:5px;">OKTOBER</th>
					<th style="padding-top:5px;padding-bottom:5px;">NOVEMBER</th>
					<th style="padding-top:5px;padding-bottom:5px;">DESEMBER</th>
					<th style="padding-top:5px;padding-bottom:5px;">TOTAL</th>
				</tr>
			<!-- </thead> -->
<!--  -->
			<!-- <tbody> -->
				<?php 
				$no = 1;
				$total = 0;
				
				
				$query = $this->db->query("SELECT JobNo,JobNm FROM Job  WHERE TipeJob='NonProject' AND Company='AST' AND JobNo IN (SELECT JobNo FROM PdHdr WHERE YEAR(TglPJ)='$tahun' )  ");
				foreach ($query->result() as $data) {
					$JobNo = $data->JobNo;
					?>

					<tr>
						<th><?=$no++.'.';?></th>
						<th><?= $data->JobNm ?></th>
						<th colspan="14"><?= $data->JobNo ?></th>
					</tr>


					<?php 
					$subtotal_Januari = 0;
					$subtotal_Februari = 0;
					$subtotal_Maret = 0;
					$subtotal_April = 0;
					$subtotal_Mei = 0;
					$subtotal_Juni = 0;
					$subtotal_Juli = 0;
					$subtotal_Agustus = 0;
					$subtotal_September = 0;
					$subtotal_Oktober = 0;
					$subtotal_November = 0;
					$subtotal_Desember = 0;
					$subtotal = 0;
					$query = $this->db->query("
						SELECT 
						NoPD,NoPJ,Deskripsi,
						(CASE WHEN MONTH(TglPJ)='01' THEN TotalPJ ELSE '' END) as 'Januari',
						(CASE WHEN MONTH(TglPJ)='02' THEN TotalPJ ELSE '' END) as 'Februari',
						(CASE WHEN MONTH(TglPJ)='03' THEN TotalPJ ELSE '' END) as 'Maret',
						(CASE WHEN MONTH(TglPJ)='04' THEN TotalPJ ELSE '' END) as 'April',
						(CASE WHEN MONTH(TglPJ)='05' THEN TotalPJ ELSE '' END) as 'Mei',
						(CASE WHEN MONTH(TglPJ)='06' THEN TotalPJ ELSE '' END) as 'Juni',
						(CASE WHEN MONTH(TglPJ)='07' THEN TotalPJ ELSE '' END) as 'Juli',
						(CASE WHEN MONTH(TglPJ)='08' THEN TotalPJ ELSE '' END) as 'Agustus',
						(CASE WHEN MONTH(TglPJ)='09' THEN TotalPJ ELSE '' END) as 'September',
						(CASE WHEN MONTH(TglPJ)='10' THEN TotalPJ ELSE '' END) as 'Oktober',
						(CASE WHEN MONTH(TglPJ)='11' THEN TotalPJ ELSE '' END) as 'November',
						(CASE WHEN MONTH(TglPJ)='12' THEN TotalPJ ELSE '' END) as 'Desember'
						FROM PdHdr WHERE JobNo='$JobNo' AND YEAR(TglPJ)='$tahun' AND NoPJ IS NOT NULL
						");
					foreach($query->result() as $row){

						$total = $row->Januari + $row->Februari + $row->Maret + $row->April + $row->Mei + $row->Juni + $row->Juli + $row->Agustus + $row->September + $row->Oktober + $row->November + $row->Desember ;

						$subtotal_Januari += $row->Januari;
						$subtotal_Februari += $row->Februari;
						$subtotal_Maret += $row->Maret;
						$subtotal_April += $row->April;
						$subtotal_Mei += $row->Mei;
						$subtotal_Juni += $row->Juni;
						$subtotal_Juli += $row->Juli;
						$subtotal_Agustus += $row->Agustus;
						$subtotal_September += $row->September;
						$subtotal_Oktober += $row->Oktober;
						$subtotal_November += $row->November;
						$subtotal_Desember += $row->Desember;
						$subtotal += $total;


						?>
						<tr>
							<td></td>
							<td><?=$row->Deskripsi?></td>
							<td >-</td>
							<td><?= UbahGabunganUang($row->Januari) ?></td>
							<td><?= UbahGabunganUang($row->Februari) ?></td>
							<td><?= UbahGabunganUang($row->Maret) ?></td>
							<td><?= UbahGabunganUang($row->April) ?></td>
							<td><?= UbahGabunganUang($row->Mei) ?></td>
							<td><?= UbahGabunganUang($row->Juni) ?></td>
							<td><?= UbahGabunganUang($row->Juli) ?></td>
							<td><?= UbahGabunganUang($row->Agustus) ?></td>
							<td><?= UbahGabunganUang($row->September) ?></td>
							<td><?= UbahGabunganUang($row->Oktober) ?></td>
							<td><?= UbahGabunganUang($row->November) ?></td>
							<td><?= UbahGabunganUang($row->Desember) ?></td>
							<th><?= UbahGabunganUang($total) ?></th>

						</tr>

					<?php } ?>
					<tr>
					<th colspan="3">Total Pengeluaran </th>
					<!-- <td>-</td>
					<td>-</td> -->
					<th><?= UbahGabunganUang($subtotal_Januari) ?></th>
					<th><?= UbahGabunganUang($subtotal_Februari) ?></th>
					<th><?= UbahGabunganUang($subtotal_Maret) ?></th>
					<th><?= UbahGabunganUang($subtotal_April) ?></th>
					<th><?= UbahGabunganUang($subtotal_Mei) ?></th>
					<th><?= UbahGabunganUang($subtotal_Juni) ?></th>
					<th><?= UbahGabunganUang($subtotal_Juli) ?></th>
					<th><?= UbahGabunganUang($subtotal_Agustus) ?></th>
					<th><?= UbahGabunganUang($subtotal_September) ?></th>
					<th><?= UbahGabunganUang($subtotal_Oktober) ?></th>
					<th><?= UbahGabunganUang($subtotal_November) ?></th>
					<th><?= UbahGabunganUang($subtotal_Desember) ?></th>
					<th><?= UbahGabunganUang($subtotal) ?></th>

				</tr>
				<tr>
					<td colspan="16" style="text-align:center;background-color:#e74c3c;"></td>
				</tr>

				<?php } ?>


			<!-- </tbody> -->

		</table>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		$('#my-table').DataTable();
	})
</script>
