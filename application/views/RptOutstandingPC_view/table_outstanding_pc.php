
<style type="text/css">
	.tengah{
		text-align: center;
	}
	#tbl_outstanding_pc{
		white-space: nowrap;
	}
	#tbl_outstanding_pc thead tr th  {
		padding-top: 10px;
		padding-bottom: 10px;
		/*vertical-align: top;*/
		white-space: nowrap;
	}

	#tbl_outstanding_pc tbody tr td  {
		padding-top: 10px;
		padding-bottom: 10px;
	}



</style>

<div class="row">
	<div class="col-sm-12">
		<div class="box">
			<div class="box-header"></div>
			<div class="box-body">
				<div class="table-responsive">
					<form action="<?=site_url('RptOutstandingPC/ExcelExport_Outstanding')?>" method="POST" target="_blank">
						<button type="submit" class="btn btn-success btn-md">Export Excel</button>
						<table id="tbl_outstanding_pc" class="table table-borderless table-striped table-hover table-condensed" width="100%" border="0" data-By="<?=$By?>" data-Type="<?=$Type?>" data-PrdAwal="<?=$PrdAwal?>" data-PrdAkhir="<?=$PrdAkhir?>" >
							<thead>
								<tr class="bg-gray-active">
									<th class="text-left" data-orderable="false" ><input type="checkbox" class="check-AllP text-white">&nbsp;&nbsp;All</th>
									<th class="tengah" data-orderable="false" >No</th>
									<th>Job No</th>
									<th>Job Name</th>
									<th>No PD</th>
									<th>Alokasi</th>
									<th>Tipe Form</th>
									<th>Atas Nama</th>
									<th>Rekening Penerima</th>
									<th>No. KO</th>
									<th style="text-align:right;">Total PD (Rp)</th>
									<th>Tgl PD</th>
									<th>Jth Tempo</th>
									<th>Deskripsi</th>
									<th>Approved By</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$nomor = 1;
								$TtlTrf = 0;
								$Saldo = 0;

								$sum_TotalPD = 0;

								foreach($myquery as $mydata) :


									if ($By == 'KEU' && $Type == 'OutStanding' && ($mydata->Alokasi == 'B' || $mydata->Alokasi == 'C' || $mydata->Alokasi == 'L'  ) ) {

										if ($mydata->ApprovedByKT  == NULL || $mydata->ApprovedByKT == '' ) {
											continue;
										}	

									}

									if (!in_array($mydata->Alokasi, $arrayAlokasi)) {
										continue;
									}

									if (!in_array($mydata->JobNo1, $arrayJob)) {
										continue;
									}


									// cari saldo
									if (  $mydata->TglPD >= '12/12/19' || ($mydata->Alokasi <> 'B'  && $mydata->Alokasi <> 'C') ) {



										//query_no_pj_Sebelumnya
										$query_NoPJSebelumnya = $this->db->query("SELECT NoPJSebelumnya FROM PdHdr WHERE NoPD='$mydata->NoPD' ");
										if ($query_NoPJSebelumnya->num_rows() > 0) {

											$row = $query_NoPJSebelumnya->row();

											if ($row->NoPJSebelumnya == NULL || $row->NoPJSebelumnya == '' ) {
												$Saldo = 0;


											}else{
												$query_saldo = $this->db->query("SELECT Saldo FROM PdHdr WHERE NoPJ='$mydata->NoPJSebelumnya' ");

												if ($query_saldo->num_rows() > 0 ) {
													$row_saldo = $query_saldo->row();
													$Saldo = $row_saldo->Saldo;

												}
											}

											


										}

									}else{

										$ceksaldo_kedua = $this->db->query("
											SELECT TOP 1 NoPJ,Saldo 
											FROM PdHdr 
											WHERE NoPD<'$mydata->NoPD' AND JobNo='$mydata->JobNo1' AND 
											TipeForm='$mydata->TipeForm' AND KSO='$mydata->KSO' AND RejectBy IS NULL 
											ORDER BY NoPD DESC	");
										if ($ceksaldo_kedua->num_rows() > 0 ) {
											$row_cek_saldo = $ceksaldo_kedua->row();
											$Saldo = $row_cek_saldo->Saldo;
											
										}
									}

									$TtlTrf = $mydata->TotalPD - $Saldo;

									$sum_TotalPD += $TtlTrf;



									?>
									<tr>
										<td class="text-left" style="width:10px !important;">
											<input type="checkbox" class="print_checkbox" id="id_stok" name="id_stok[]" value="<?=$mydata->LedgerNo?>" data-id="<?=$mydata->LedgerNo?>" data-By="<?=$By?>">
											<input type="hidden" name="By" value="<?=$By?>" >
											<input type="hidden" name="Type" value="<?=$Type?>" >
											<input type="hidden" name="PrdAwal" value="<?=$PrdAwal?>" >
											<input type="hidden" name="PrdAkhir" value="<?=$PrdAkhir?>" >
										</td>
										<td style="text-align: center;"><?=$nomor++?></td>
										<td  ><?=$mydata->JobNo1?></td>
										<td><?=$mydata->JobNm.' - '.$mydata->CompanyId?></td>
										<td ><?=$mydata->NoPD?></td>
										<td><?=$mydata->Alokasi?></td>
										<td ><?=$mydata->TipeForm?></td>
										<td ><?=$mydata->AtasNama1?></td>
										<td ><?=$mydata->NoRek?></td>
										<td ><?=$mydata->NoKO?></td>
										<td style="text-align:right;" ><?=uang_koma_genap_decimal($TtlTrf)?></td>
										<td ><?=date('d-M-Y',strtotime($mydata->TglPD))?></td>

										<td><?=date('d-M-Y',strtotime($mydata->DueDate))?></td>
										<td><?=$mydata->Deskripsi?></td>
										<td><?=$mydata->ApprovedBy?></td>
									</tr>

								<?php endforeach; ?>
							</tbody>
							<tfoot >
								<tr>

									<th>
										<!-- <button type="button" name="print_all" id="print_all" class="badge badge-success btn-round">
											Cetak
										</button> -->

										<!-- <button type="submit" class="btn btn-success btn-md">Export Excel</button> -->
										<!-- <button type="submit" class="btn btn-danger btn-md">Export PDF</button> -->
									</th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>

									<th colspan="3" style="text-align:right !important;"><?='sum = '.uang_koma_genap_decimal($sum_TotalPD)?></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
								</tr>
							</tfoot>
						</table>

					</form>

				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {

		var table = $('#tbl_outstanding_pc').DataTable( {
			orderable : true,
			paging: false,
			scrollY:    500,
			scrollX:        true,
			scrollCollapse: true,
			fixedColumns:   true,
			"columnDefs": [ 
				// { "targets": 0, "orderable": false, "visible" : true },
				{ "targets": 1, "orderable": true },
				]
		} );

		


		$('.print_checkbox').click(function() {
			if ($(this).is(':checked')) {
				$(this).closest('tr').addClass('removeRowP');
			} else {
				$(this).closest('tr').removeClass('removeRowP');
			}
		});
		$('.check-AllP').click(function() {
			$('.print_checkbox').prop('checked', this.checked);
			if ($(this).is(':checked')) {
				$('.checkP').addClass('removeRowP');
			} else {
				$('.checkP').removeClass('removeRowP');
			}
		});

		$('#print_all').click(function() {

			var checkbox = $('.print_checkbox:checked');
			var By = $('#tbl_outstanding_pc').attr('data-By');
			var Type = $('#tbl_outstanding_pc').attr('data-Type');

			var PrdAwal = $('#tbl_outstanding_pc').attr('data-PrdAwal');
			var PrdAkhir = $('#tbl_outstanding_pc').attr('data-PrdAkhir');


			if (checkbox.length > 0) {

				Swal.fire({
					title: 'Print Data',
					text: `Ada ${checkbox.length} data barang yang akan diprint, yakin?`,
				// icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Ya, Cetak!',
					cancelButtonText: 'Tidak!',
				}).then(function(result) {
					if (result.value) {
						var checkbox_value = [];
						$(checkbox).each(function() {
							checkbox_value.push($(this).val());
						});
						$.ajax({
							url: "<?= base_url('RptOutstandingPC/export_pdf_outstanding'); ?>",
							method: "POST",
							data: {
								checkbox_value: checkbox_value,
								By : By,
								Type : Type,
								PrdAwal : PrdAwal,
								PrdAkhir : PrdAkhir,
							},
							success: function(data) {
							// window.open=
							// if (data) {
							// 	window.open(data, '_blank');
							// }
							// wind
							// console.log(data);
							// alert(data);
							// printWindow = $('.removeRow');
							// printWindow = window.open('');
							// printWindow.document.write(data);
							// printWindow.print();
							},
							error: function(xhr, ajaxOptions, thrownError) {
								alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
							}
						});
					}
				});
			} else {
				Swal.fire('Perhatian','Maaf tidak ada data yang diprint, mohon dicentang','warning');
			}
		});



	} );

	
</script>