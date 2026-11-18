<style type="text/css">
	#tblutamaSPR{
		font-size: 12px;
		font-family: Arial, Helvetica, sans-serif;
	}
</style>
<div class="row" >
	<div class="col-sm-12" style="padding-bottom: 10px;">
		<a href="<?=site_url('Spr/add/'.$JobNo)?>" class="btn btn-default btn-md text-bold">TAMBAH DATA</a>
		<a href="<?=site_url('FrmSPR/ExportExcel_SPR/'.$JobNo)?>" class="btn btn-default btn-md text-bold">EXPORT TO EXCEL <i class="fa fa-file-excel-o"></i></a>
	</div>
	<hr>
</div>
<div class="row">

	<!-- <p></p> -->
	<!-- <p></p> -->
	<div class="col-sm-12">

		<div class="table-responsive">
			<table class="table table-bordered table-striped table-hover" id="tblutamaSPR">
				<thead class="bg-gray">
					<tr>
						<th class="text-center" >No</th>
						<th class="text-center" >No SPR</th>
						<th class="text-center" >Tgl SPR</th>
						<th class="text-center">Untuk Pekerjaan</th>
						<th class="text-center">SPR Approved By</th>
						<th class="text-center">SPR Approved On</th>
						<th class="text-center" width="50px"></th>
						<th class="text-center" width="50px"></th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$NoUrut = 1;
					foreach ($dataPRHdr as $row): ?>
						<tr>
							<td><?=$NoUrut++?></td>
							<td><?=ShowKodeAman($row->NoSPR)?></td>
							<td><?=ShowKodeAman(tgl_baru($row->TglSPR))?></td>
							<td><?=ShowKodeAman($row->UtkPekerjaan)?></td>
							<td><?=ShowKodeAman($row->ApprovedBy)?></td>
							<td><?=tgl_baru_tahun_pendek_dengan_waktu(ShowKodeAman($row->TimeApproved))?></td>
							<td class="text-center">
								<a href="<?=site_url('Spr/myedit/'.ShowKodeAman($row->NoSPR).'/'.ShowKodeAman($row->JobNo))?>" class="btn btn-link btn-md btn_select" style="text-decoration: underline;" >SELECT</a>
							</td>
							<td class="text-center">
								
								<button type="button" class="btn btn-link btn-md btn_print" 
								data-NoSPR="<?=ShowKodeAman($row->NoSPR)?>" data-StatusApprove="<?=(ShowKodeAman($row->ApprovedBy) == NULL || ShowKodeAman($row->ApprovedBy) == '') ? 0 : 1 ?>" data-myurl="<?=site_url('FrmSPR/mypdf/'.ShowKodeAman($row->NoSPR))?>" style="text-decoration: underline;"  >PRINT</button>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		var table = $('#tblutamaSPR').DataTable();

		$('.btn_print').click(function(){

			var NoSPR = $(this).attr('data-NoSPR');
			var StatusApprove = $(this).attr('data-StatusApprove');
			var myurl = $(this).attr('data-myurl');

			if (StatusApprove == 0 ) {
				Swal.fire(NoSPR+' belum di-Approved', '','info');
				return false;
			}

			window.open(myurl, '_blank');

		});
	})
</script>