<style type="text/css">
	.table-hover tbody tr:hover td {
		background: #95a5a6;
	}

	.font14{
		font-size: 14px !important;
	}
</style>
<div class="col-12" style="font-size:14px;">
	<div class="box">
		<div class="box-header">
			<!-- <a href="<?=site_url('MyReport/Operasi/RekapProyek/getPDF_RP')?>" class="btn btn-sm btn-danger" target="_blank" >PRINT PDF <i class="fa fa-print"></i></a> -->
			<button type="button" class="btn btn-md btn-danger btn_print_pdf">PRINT PDF <i class="fa fa-print"></i></button>
			<button type="button" class="btn btn-md btn-success btn_export_excel">Export Excel <i class="fa fa-download"></i></button>
		</div>
		<div class="box-body">
			

			<div class="table-responsive">

				<table  id="tableRP" width="100%" border="0" class="table table-striped table-hover table-condensed table-bordered">
					<thead>
						<tr style="padding-top: 10px;padding-bottom: 10px;border-bottom: 1px solid black !important;color:White;background-color:#5D7B9D;font-weight:bold;">
							<th style="text-align:center;">No</th>
							<th style="text-align:left;padding-top: 10px;padding-bottom: 10px;">Job No</th>
							<th style="text-align:left;width:12% !important;">Job Name</th>
							<th style="text-align:right;width:12% !important;">Netto Kontrak Induk (Rp)</th>
							<th style="text-align:right;">%Share</th>
							<th style="text-align:right;">Netto Share (Rp)</th>
							<th style="text-align:right;">Total Alokasi A (Rp)</th>
							<th style="text-align:right;">Total Alokasi B (Rp)</th>
							<th style="text-align:right;">Total Alokasi C (Rp)</th>
							<th style="text-align:right;">Termin Induk(Rp)</th>
							<th style="text-align:right;">Termin Member (Rp)</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						$SUM_Netto = 0;
						$SUM_NettoShare = 0;
						$SUM_AlokasiA = 0;
						$SUM_AlokasiB = 0;
						$SUM_AlokasiC = 0;
						$SUM_TerminInduk = 0;
						$SUM_TerminMember = 0;
						?>
						<?php foreach($dapatRP as $mydata): ?>

							<?php
							$SUM_Netto += $mydata['Netto']; 
							$SUM_NettoShare += $mydata['NettoShare'];
							$SUM_AlokasiA += $mydata['AlokasiA'];
							$SUM_AlokasiB += $mydata['AlokasiB'];
							$SUM_AlokasiC += $mydata['AlokasiC'];
							$SUM_TerminInduk += $mydata['TerminInduk'];
							$SUM_TerminMember += $mydata['TerminMember'];
							?>

							<tr>
								<td style="padding-top: 5px;padding-bottom: 5px;text-align:center;" ><?=$no++?></td>
								<td ><?=$mydata['JobNo']?></td>
								<td><?=$mydata['JobNm']?></td>
								<td style="text-align:right;"><?=uang_koma_genap_decimal($mydata['Netto'])?></td>
								<td style="text-align:right;"><?=new_decimal($mydata['PersenKSO'])?></td>
								<td style="text-align:right;"><?=uang_koma_genap_decimal($mydata['NettoShare'])?></td>
								<td style="text-align:right;"><?=uang_koma_genap_decimal($mydata['AlokasiA'])?></td>
								<td style="text-align:right;"><?=uang_koma_genap_decimal($mydata['AlokasiB'])?></td>
								<td style="text-align:right;"><?=uang_koma_genap_decimal($mydata['AlokasiC'])?></td>
								<td style="text-align:right;"><?=uang_koma_genap_decimal($mydata['TerminInduk'])?></td>
								<td style="text-align:right;"><?=uang_koma_genap_decimal($mydata['TerminMember'])?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
					<tfoot style="border-top: 1px solid black;">
						<tr style="color:White;background-color:#5D7B9D;font-weight:bold;" >
							<td colspan="4" style="text-align:right;font-weight: bold;padding-top: 20px;padding-bottom: 20px;"><?='SUM = '.uang_koma_genap_decimal($SUM_Netto)?></td>
							<td></td>
							<td colspan="1" style="text-align:right;font-weight: bold;padding-top: 20px;padding-bottom: 20px;"><?='SUM = '.uang_koma_genap_decimal($SUM_NettoShare)?></td>
							<td style="text-align:right;font-weight: bold;padding-top: 20px;padding-bottom: 20px;"><?='SUM = '.uang_koma_genap_decimal($SUM_AlokasiA)?></td>
							<td style="text-align:right;font-weight: bold;padding-top: 20px;padding-bottom: 20px;"><?='SUM = '.uang_koma_genap_decimal($SUM_AlokasiB)?></td>
							<td style="text-align:right;font-weight: bold;padding-top: 20px;padding-bottom: 20px;"><?='SUM = '.uang_koma_genap_decimal($SUM_AlokasiC)?></td>
							<td style="text-align:right;font-weight: bold;padding-top: 20px;padding-bottom: 20px;"><?='SUM = '.uang_koma_genap_decimal($SUM_TerminInduk)?></td>
							<td style="text-align:right;font-weight: bold;padding-top: 20px;padding-bottom: 20px;"><?='SUM = '.uang_koma_genap_decimal($SUM_TerminMember)?></td>
						</tr>
					</tfoot>
				</table>




			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		$('#tableRP').DataTable({
			"ordering": true,
			"pageLength": 50,
			"lengthMenu": [50,100,200,300,500,1000],
		});

		$('.btn_print_pdf').click(function(){
			var Tahun = $('#Tahun').val();

			var url = "<?=site_url('MyReport/Operasi/RekapProyek/getPDF_RP/')?>"+Tahun;
			
			window.open(url, '_blank');
		});

		$('.btn_export_excel').click(function(){
			var Tahun = $('#Tahun').val();

			var url = "<?=site_url('MyReport/Operasi/RekapProyek/RP_export_excel/')?>"+Tahun;
			
			window.open(url, '_blank');
		});

	});
</script>