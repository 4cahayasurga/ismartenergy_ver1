
<div class="row">
	<div class="col-sm-12">
		<div class="table-responsive">
			<table width="100%" hidden>
				<tr>
					<td width="100px">
						<!-- <label><a class="btn btn-default btn-md" href="<?=site_url('Entry/PDPJ/FrmInvoiceKO_C/PertanggungjawabanDana_Lapangan_C/Entry_PJLapangan/'.$JobNo.'/'.$Alokasi)?>" >TAMBAH <i class="fa fa-plus"></i> </a></label> -->
					</td>
					<td></td>
				</tr>
			</table>
			<p></p>
			<table id="mytable" class="table table-striped table-hover  table-bordered " style="margin-top: 20px;">
				<thead>
					<tr class="bg-gray color-palette text-bold">
						<th class="text-center text-bold ">No PD</th>
						<th class="text-center text-bold" width="70px">Tgl PD</th>
						<th class="text-center " width="60px">Tipe Form</th>
						<th class="text-center ">Deskripsi</th>
						<th class="text-center " width="70px">Total PD (Rp)</th>
						<th class="text-center " width="70px">No PJ</th>
						<th class="text-center " width="70px">Tgl PJ</th>
						<th class="text-center " width="80px">Total PJ (Rp)</th>
						<th class="text-center " width="80px">Approved By (Lapangan)</th>
						<th class="text-center " width="80px">Approved On</th>
						<th class="text-center " width="300px"></th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$NoUrut = 0;
					$sumTotalPD = 0;
					$sumTotalPJ = 0;
					foreach ($ListPJ as $data): 

						$NoPD = ShowKodeAman($data->NoPD);
						$TglPD = ShowKodeAman(tgl_baru($data->TglPD));
						$TipeForm = ShowKodeAman($data->TipeForm);
						$Deskripsi = ShowKodeAman($data->Deskripsi);
						$TotalPD = ShowKodeAman($data->TotalPD);
						$NoPJ = ShowKodeAman($data->NoPJ);
						$TglPJ = ShowKodeAman(tgl_baru($data->TglPJ));
						$TotalPJ = ShowKodeAman($data->TotalPJ);
						$PjApprovedByAK = ShowKodeAman($data->PjApprovedByAK);
						$PjTimeApprovedAK = ShowKodeAman(tgl_baru_dengan_waktu($data->PjTimeApprovedAK));

						$Txt_PjApprovedByAK = ($PjApprovedByAK=='' || $PjApprovedByAK==NULL ) ? 'APPROVE' : 'UNAPPROVE';
						$TxtRejectBy = ($data->RejectBy == NULL || $data->RejectBy =='') ? '' : $data->RejectBy;
						$TxtNoPJ = ($data->NoPJ == NULL || $data->NoPJ =='') ? '' : $data->NoPJ;
						$PjApprovedByKT = ShowKodeAman($data->PjApprovedByKT);

						$Txt_PjApprovedByKT = ($data->PjApprovedByKT=='' || $PjApprovedByKT==NULL ) ? '' : $data->PjApprovedByKT;
						$NoUrut++;

						$sumTotalPD += $TotalPD;
						$sumTotalPJ += $TotalPJ;

						?>

						<tr >
							<td><?=$NoPD?></td>
							<td><?=$TglPD?></td>
							<td><?=$TipeForm?></td>
							<td><?=$Deskripsi?></td>
							<td class="text-right"><?=number_format($TotalPD)?></td>
							<td><?=$NoPJ?></td>
							<td><?=$TglPJ?></td>
							<td class="text-right"><?=number_format($TotalPJ)?></td>
							<td><?=$PjApprovedByAK?></td>
							<td><?=$PjTimeApprovedAK?></td>
							<td class="text-center"  >

								<!-- <a href="<?=site_url('Entry/PDPJ/PJLap_C/FrmUpdatePJLap/'.$NoPD.'/'.$JobNo.'/'.$Alokasi)?>" class="btn btn-md btn-default text-blue">SELECT</a> -->

								<button type="button" class="btn btn-md btn-default text-blue" id="btnSelectID<?=$NoUrut?>" onClick="btnSelect('<?=$NoUrut?>')"  data-UrlSaya="<?=site_url('Entry/PDPJ/PJLap_C/FrmUpdatePJLap/'.$NoPD.'/'.$JobNo.'/'.$Alokasi)?>"  data-TxtRejectBy="<?=$TxtRejectBy?>" data-TxtNoPJ="<?=$TxtNoPJ?>" data-NoPD="<?=$NoPD?>" data-Txt_PjApprovedByKT="<?=$Txt_PjApprovedByKT?>" data-Txt_PjApprovedByAK="<?=$Txt_PjApprovedByAK?>" data-CountBLE="<?=$data->CountBLE?>" >SELECT</button>

								<button type="button" class="btn btn-md btn-default text-blue" id="btnApproveID<?=$NoUrut?>" onClick="btnApprove('<?=$NoUrut?>')"  data-UrlSaya="<?=site_url('Entry/PDPJ/PJLap_C/AksiApprove/'.$NoPD.'/'.$JobNo.'/'.$Alokasi)?>"  data-TxtRejectBy="<?=$TxtRejectBy?>" data-TxtNoPJ="<?=$TxtNoPJ?>" data-NoPD="<?=$NoPD?>" data-Txt_PjApprovedByKT="<?=$Txt_PjApprovedByKT?>" data-Txt_PjApprovedByAK="<?=$Txt_PjApprovedByAK?>" ><?=$Txt_PjApprovedByAK?></button>

								<button type="button" class="btn btn-md btn-default text-blue" id="btnExportPDFID<?=$NoUrut?>" onClick="btnExportPDF('<?=$NoUrut?>')"  data-UrlSaya="<?=site_url('MY_PD/PD_PDF_ALL/ExpPDF_PD/'.$data->LedgerNo.'/'.$data->JobNo.'/'.$data->Alokasi.'/'.$data->NoPD.'/0/0/'.$PJ_Type_Untuk_PRINT_PD)?>"  data-TxtRejectBy="<?=$TxtRejectBy?>" data-TxtNoPJ="<?=$TxtNoPJ?>" data-NoPD="<?=$NoPD?>" data-Txt_PjApprovedByKT="<?=$Txt_PjApprovedByKT?>" data-Txt_PjApprovedByAK="<?=$Txt_PjApprovedByAK?>" >PRINT <i class="fa fa-print"></i> </button>

							</td>
						</tr>

					<?php endforeach ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4"></td>
						<td>sum=<?=number_format($sumTotalPD)?></td>
						<td colspan="2"></td>
						<td>sum=<?=number_format($sumTotalPJ)?></td>
						<td colspan="3"></td>
					</tr>
				</tfoot>

			</table>
		</div>
	</div>
</div>



<script type="text/javascript">
	$(function(){

		$('#mytable').DataTable({
			columnDefs: [
				{ targets: [2,3,4,7,8,10], orderable : false},
				],
			order: [[0, 'desc']],
		});
	})

	function btnApprove(NoUrut=''){
		if (NoUrut=='') {
			return false;
		}

		var TxtRejectBy = $('#btnApproveID'+NoUrut).attr('data-TxtRejectBy');
		var TxtNoPJ = $('#btnApproveID'+NoUrut).attr('data-TxtNoPJ');
		var NoPD = $('#btnApproveID'+NoUrut).attr('data-NoPD');
		var Txt_PjApprovedByKT = $('#btnApproveID'+NoUrut).attr('data-Txt_PjApprovedByKT');
		var Txt_PjApprovedByAK = $('#btnApproveID'+NoUrut).attr('data-Txt_PjApprovedByAK');
		var UrlSaya = $('#btnApproveID'+NoUrut).attr('data-UrlSaya');


		if (TxtRejectBy!='') {
			alert(NoPD+' sudah di-reject oleh '+TxtRejectBy);
			return false;
		}

		if (TxtNoPJ=='') {
			alert(NoPD+' belum entry uraian PJ.');
			return false;
		}

		if (Txt_PjApprovedByKT!='') {
			alert(TxtNoPJ+' sudah di-Approved oleh Cost Control.');
			return false;
		}

		if (confirm('Anda yakin ingin '+Txt_PjApprovedByAK+' '+TxtNoPJ+' ini?')) {
			window.location.href=UrlSaya;
			return false;
		}

		// alert(Txt_PjApprovedByKT);

		// alert(TxtRejectBy+' - '+Tx/tNoPJ);

	}

	function btnExportPDF(NoUrut=''){
		if (NoUrut=='') {
			return false;
		}

		var TxtRejectBy = $('#btnExportPDFID'+NoUrut).attr('data-TxtRejectBy');
		var TxtNoPJ = $('#btnExportPDFID'+NoUrut).attr('data-TxtNoPJ');
		var NoPD = $('#btnExportPDFID'+NoUrut).attr('data-NoPD');
		var Txt_PjApprovedByKT = $('#btnExportPDFID'+NoUrut).attr('data-Txt_PjApprovedByKT');
		var Txt_PjApprovedByAK = $('#btnExportPDFID'+NoUrut).attr('data-Txt_PjApprovedByAK');
		var UrlSaya = $('#btnExportPDFID'+NoUrut).attr('data-UrlSaya');

		if (TxtRejectBy!='') {
			alert(NoPD+' sudah di-reject oleh '+TxtRejectBy);
			return false;
		}

		if (TxtNoPJ=='') {
			alert(NoPD+' belum entry uraian PJ.');
			return false;
		}

		window.open(UrlSaya);
		return false;
	}

	function btnSelect(NoUrut=''){
		if (NoUrut=='') {
			return false;
		}



		var TxtRejectBy = $('#btnSelectID'+NoUrut).attr('data-TxtRejectBy');
		var TxtNoPJ = $('#btnSelectID'+NoUrut).attr('data-TxtNoPJ');
		var NoPD = $('#btnSelectID'+NoUrut).attr('data-NoPD');
		var Txt_PjApprovedByKT = $('#btnSelectID'+NoUrut).attr('data-Txt_PjApprovedByKT');
		var Txt_PjApprovedByAK = $('#btnSelectID'+NoUrut).attr('data-Txt_PjApprovedByAK');
		var UrlSaya = $('#btnSelectID'+NoUrut).attr('data-UrlSaya');

		var CountBLE = $('#btnSelectID'+NoUrut).attr('data-CountBLE');


		// // alert(CountBLE);
		// return false;

		if (TxtRejectBy!='') {
			alert(NoPD+' sudah di-reject oleh '+TxtRejectBy);
			return false;
		}

		if (CountBLE==0) {
			alert('Belum ada pembayaran untuk No '+NoPD);
			return false;
		}

		window.location.href=UrlSaya;
		return false;
	}

</script>


