<div class="row" >
	<div class="col-sm-12">
		<div class="table-responsive">


			<!-- == START ==  -->
			<table border="0" width="100%">
				<tr>
					<th class="text-center bg-gray-active color-palette huruf16" style="padding-top:5px;padding-bottom:5px;">CURRENT ADDENDUM/REVISI</th>
				</tr>
			</table>
			<p></p>
			<table style="width: 80% !important;" class="table table-bordered table-striped table-hover table-condensed huruf14">
				<thead>
					<tr class="bg-light-blue  color-palette">
						<th class="text-center">No. KO</th>
						<th class="text-center" >Tgl KO</th>
						<th class="text-center" >Penerima Kontrak</th>
						<th class="text-center" >Tipe KO</th>
						<th class="text-center" >Addendum/Revisi Ke</th>
						<th class="text-center" >Total KO (Rp)</th>
						<th class="text-center">KO Approved By</th>
						<th class="text-center">KO Approved On</th>
						<th class="text-center" width="200px"></th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$NoUrut=1;
					foreach ($KOAddendum_Current as $row): ?>
						<tr>
							<td class="text-center"><?=$row->NoKO?></td>
							<td class="text-center"><?=tgl_baru($row->TglKO)?></td>
							<td><?=$row->VendorNm?></td>
							<td class="text-center"><?=$row->KategoriId?></td>
							<td class="text-center"><?=$row->AddendumKe?></td>
							<td class="text-right"><?=number_format($row->SubTotal)?></td>
							<td><?=$row->ApprovedBy?></td>
							<td class="text-center"><?=tgl_baru_dengan_waktu($row->TimeApproved)?></td>
							<td class="text-center">
								<?php if ($row->KategoriId=='KONTRAK'): ?>

									<button class="btn btn-md btn-danger text-bold" 
									onclick="btnSelect_KONTRAK('<?=$row->LedgerNo?>')" 
									id="btnSelect_KONTRAK<?=$row->LedgerNo?>" 
									data-NoKO="<?=$row->NoKO?>" 
									data-JobNo="<?=$JobNo?>" 
									data-CanceledBy="<?=$row->CanceledBy?>"
									data-ApprovedBy="<?=$row->ApprovedBy?>"
									data-ClosedBy="<?=$row->ClosedBy?>"
									data-UrlRedirect="<?=site_url('Kontrak_po/detail_ko/'.$row->LedgerNo.'/'.$row->KategoriId.'/'.$row->JobNo)?>"
									title="DETAIL / EDIT">SELECT</button>

								<?php else: ?>	

									<button class="btn btn-md btn-danger text-bold" 
									onclick="btnSelect_PO('<?=$row->LedgerNo?>')" 
									id="btnSelect_PO<?=$row->LedgerNo?>" 
									data-NoKO="<?=$row->NoKO?>" 
									data-JobNo="<?=$row->JobNo?>" 
									data-CanceledBy="<?=$row->CanceledBy?>"
									data-ApprovedBy="<?=$row->ApprovedBy?>"
									data-ClosedBy="<?=$row->ClosedBy?>"
									title="DETAIL / EDIT">SELECT</button>

								<?php endif ?>

							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>

			<!-- == END == -->

			<p></p>
			<!-- == START ==  -->
			<table border="0" width="100%">
				<tr>
					<th class="text-center bg-gray-active color-palette huruf16" style="padding-top:5px;padding-bottom:5px;">HISTORY ADDENDUM/REVISI</th>
				</tr>
			</table>
			<p></p>
			<table style="width: 80% !important;" class="table table-bordered table-striped table-hover table-condensed huruf14">
				<thead>
					<tr class="bg-light-blue  color-palette">
						<th class="text-center">No. KO</th>
						<th class="text-center">Tgl KO</th>
						<th class="text-center">Penerima Kontrak</th>
						<th class="text-center">Tipe KO</th>
						<th class="text-center">Addendum/Revisi Ke</th>
						<th class="text-center">Total KO (Rp)</th>
						<th class="text-center">KO Approved By</th>
						<th class="text-center">KO Approved On</th>
						<th class="text-center">Alasan Addendum</th>
						<th class="text-center"></th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$NoUrut=1;
					foreach ($KOAddendum_History as $row): ?>
						<tr>
							<td class="text-center"><?=$row->NoKO?></td>
							<td class="text-center"><?=tgl_baru($row->TglKO)?></td>
							<td><?=$row->VendorNm?></td>
							<td class="text-center"><?=$row->KategoriId?></td>
							<td class="text-center"><?=$row->AddendumKe?></td>
							<td class="text-right"><?=number_format($row->SubTotal)?></td>
							<td><?=$row->ApprovedBy?></td>
							<td class="text-center"><?=tgl_baru_dengan_waktu($row->TimeApproved)?></td>
							<td><?=$row->AlasanAddendum?></td>
							<td class="text-center">


								<?php if ($row->KategoriId=='KONTRAK'): ?>

									<button class="btn btn-md btn-danger text-bold" 
									onclick="btnSelect_KONTRAK('<?=$row->LedgerNo?>')" 
									id="btnSelect_KONTRAK<?=$row->LedgerNo?>" 
									data-NoKO="<?=$row->NoKO?>" 
									data-JobNo="<?=$JobNo?>" 
									data-CanceledBy="<?=$row->CanceledBy?>"
									data-ApprovedBy="<?=$row->ApprovedBy?>"
									data-ClosedBy="<?=$row->ClosedBy?>"
									data-UrlRedirect="<?=site_url('Kontrak_po/detail_ko/'.$row->LedgerNo.'/'.$row->KategoriId.'/'.$row->JobNo)?>"
									title="DETAIL / EDIT">SELECT</button>

								<?php else: ?>	

									<button class="btn btn-md btn-danger text-bold" 
									onclick="btnSelect_PO('<?=$row->LedgerNo?>')" 
									id="btnSelect_PO<?=$row->LedgerNo?>" 
									data-NoKO="<?=$row->NoKO?>" 
									data-JobNo="<?=$row->JobNo?>" 
									data-CanceledBy="<?=$row->CanceledBy?>"
									data-ApprovedBy="<?=$row->ApprovedBy?>"
									data-ClosedBy="<?=$row->ClosedBy?>"
									title="DETAIL / EDIT">SELECT</button>

								<?php endif ?>

							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>

			<!-- == END == -->


		</div>
	</div>
</div>

<script type="text/javascript">
	function btnSelect_KONTRAK(LedgerNo){

		var NoKO = $('#btnSelect_KONTRAK'+LedgerNo).attr('data-NoKO');
		var JobNo = $('#btnSelect_KONTRAK'+LedgerNo).attr('data-JobNo');
		var CanceledBy = $('#btnSelect_KONTRAK'+LedgerNo).attr('data-CanceledBy');
		var ApprovedBy = $('#btnSelect_KONTRAK'+LedgerNo).attr('data-ApprovedBy');
		var ClosedBy = $('#btnSelect_KONTRAK'+LedgerNo).attr('data-ClosedBy');
		var UrlRedirect = $('#btnSelect_KONTRAK'+LedgerNo).attr('data-UrlRedirect');

		if (ApprovedBy=='' || ApprovedBy==null) {
			alert(NoKO+' belum di-approve');
			return;
		}

		if (CanceledBy!='') {
			alert(NoKO+' sudah di-batalkan');
			return false;
		}

		window.location.href=UrlRedirect;
	}

	function btnSelect_PO(LedgerNo=''){
		var NoKO = $('#btnSelect_PO'+LedgerNo).attr('data-NoKO');
		var JobNo = $('#btnSelect_PO'+LedgerNo).attr('data-JobNo');
		var CanceledBy = $('#btnSelect_PO'+LedgerNo).attr('data-CanceledBy');
		var ApprovedBy = $('#btnSelect_PO'+LedgerNo).attr('data-ApprovedBy');
		var ClosedBy = $('#btnSelect_PO'+LedgerNo).attr('data-ClosedBy');

		if (ApprovedBy=='' || ApprovedBy==null) {
			alert(NoKO+' belum di-approve');
			return;
		}

		if (CanceledBy!='') {
			alert(NoKO+' sudah di-batalkan');
			return false;
		}

		if (ApprovedBy=='') {
			window.location.href="<?=site_url('Entry/Procurement/FrmEntryPO_C/PODetail/UPD/')?>"+JobNo+'/'+NoKO;
		}else{
			window.location.href="<?=site_url('Entry/Procurement/FrmEntryPO_C/PODetail/SEE_KO/')?>"+JobNo+'/'+NoKO;
		}

	}
</script>