<style type="text/css">
	#mytable {
		font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
		font-size: 14px;
	}
	#mytable thead tr th{
		text-align: center;
		color:White;
		background-color:#284775;
		font-weight:bold;
		padding-top: 10px;
		padding-bottom: 10px;
	}

</style>
<a href="<?= site_url('Kontrak_po/tambah_kontrak/' . $JobNo) ?>" class="btn btn-md bg-purple active color-palette">TAMBAH KONTRAK</a>

<?php if ($this->session->userdata('MIS_LOGGED_ID')): ?>
	<a href="<?= site_url('Kontrak_po/tambah_kontrak/' . $JobNo.'/new') ?>" class="btn btn-md bg-purple active color-palette">TAMBAH KONTRAK(Versi Lain) </a>
<?php endif ?>


<a href="<?= site_url('Kontrak_po/tambah_po/' . $JobNo) ?>" class="btn btn-md bg-red active color-palette" >TAMBAH PO</a>
<a href="<?= site_url('Kontrak_po/tambah_po/' . $JobNo.'/MIX') ?>" class="btn btn-md bg-gray active color-palette" >TAMBAH KOMIX</a>

<br><br>
<div class="table-responsive">
	<table id="mytable" class="table table-bordered table-striped table-hover table-condesed" data-job="<?= $JobNo ?>">
		<thead>
			<tr>
				<th></th>
				<th>No</th>
				<th>No. KO</th>
				<th>Tgl KO</th>
				<th data-orderable="false">Vendor</th>
				<th data-orderable="false">Tipe KO</th>
				<th data-orderable="false">Add/Revisi Ke</th>
				<th data-orderable="false">Total KO (Rp)</th>
				<th data-orderable="false">Total KO (Rp) (*Include PPN)</th>
				<th data-orderable="false">Approved By</th>
				<th data-orderable="false" >Approved ON</th>
				<th data-orderable="false"></th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$NoUrut = 1;
			foreach ($dataKO as $row): ?>
				<tr>
					<td><?=$row->NoKO?></td>
					<td class="text-center"><?=$NoUrut++?></td>
					<td><?=$row->NoKO?></td>
					<td><?=tgl_baru($row->TglKO)?></td>
					<td><?=$row->VendorNm?></td>
					<td><?=$row->KategoriId?></td>
					<td><?=$row->AddendumKe?></td>
					<td class="text-right"><?=number_format($row->TotalKO)?></td>
					<td class="text-right"><?=number_format($row->TotalPPN)?></td>
					<td><?=$row->ApprovedBy?></td>
					<td><?=tgl_baru_dengan_waktu($row->TimeApproved)?></td>
					<td class="text-center">

						<?php if ($row->KategoriId=='KONTRAK'): ?>

							<!-- <a href="<?=site_url('Kontrak_po/detail_ko/'.$row->LedgerNo.'/'.$row->KategoriId.'/'.$JobNo)?>" class="btn btn-md btn-danger text-bold" OnClick="" title="DETAIL / EDIT"  >SELECT</a> -->

							<button class="btn btn-md btn-danger text-bold" 
							onclick="btnSelect_KONTRAK('<?=$row->LedgerNo?>')" 
							id="btnSelect_KONTRAK<?=$row->LedgerNo?>" 
							data-NoKO="<?=$row->NoKO?>" 
							data-JobNo="<?=$JobNo?>" 
							data-CanceledBy="<?=$row->CanceledBy?>"
							data-ApprovedBy="<?=$row->ApprovedBy?>"
							data-ClosedBy="<?=$row->ClosedBy?>"
							data-UrlRedirect="<?=site_url('Kontrak_po/detail_ko/'.$row->LedgerNo.'/'.$row->KategoriId.'/'.$JobNo)?>"
							title="DETAIL / EDIT">SELECT</button>

						<?php else: ?>	

							<button class="btn btn-md btn-danger text-bold" 
							onclick="btnSelect_PO('<?=$row->LedgerNo?>')" 
							id="btnSelect_PO<?=$row->LedgerNo?>" 
							data-NoKO="<?=$row->NoKO?>" 
							data-JobNo="<?=$JobNo?>" 
							data-CanceledBy="<?=$row->CanceledBy?>"
							data-ApprovedBy="<?=$row->ApprovedBy?>"
							data-ClosedBy="<?=$row->ClosedBy?>"
							title="DETAIL / EDIT">SELECT</button>

							<!-- <a href="<?=site_url('Entry/Procurement/FrmEntryPO_C/PODetail/'.$row->NoKO)?>" class="btn btn-md btn-danger text-bold" title="DETAIL / EDIT"  >SELECT</a> -->

						<?php endif ?>

						

						<a href="javascript:void(0)" class="btn btn-md btn-warning text-bold" OnClick="lakukan_print('<?=$row->LedgerNo?>','<?=$row->ApprovedBy?>','<?=$row->KategoriId?>')"  title="PRINT DATA">PRINT <i class="fa fa-print"></i></a>

					</td>
					
				</tr>
			<?php endforeach ?>
		</tbody>
		<tfoot>
			<tr style="color:White;background-color:#284775;">
				<th colspan="7">SUB TOTAL</th>
				<th class="text-right"><?=$Sum_TotalKO?></th>
				<th class="text-right"><?=$Sum_TotalKO_IncludePPN?></th>
				<th colspan="3"></th>
			</tr>
		</tfoot>
	</table>

</div>


<script type="text/javascript">
	$(document).ready(function() {	
		$('#mytable').DataTable({
			"columnDefs": [{ "visible": false, "targets": 0 }],
			"order": [0, 'desc']
		});
	});

	
</script>