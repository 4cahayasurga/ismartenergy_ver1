<style type="text/css">
	#tblMOS thead tr th{
		text-align: center;
		font-size: 11px !important;
		padding-top:10px;
		padding-bottom: 10px;
	}

	#tblMOS tbody tr td{
		font-size: 10px !important;
		padding-top:10px !important; 
		padding-bottom: 10px !important;
		font-size: 11px !important;
	}

	table#tblMOS.dataTable tbody tr:hover {
		background-color: #ffa;
		font-size: 11px !important;
	}

	table#tblMOS.dataTable tbody tr:hover > .sorting_1 {
		background-color: #ffa;
		font-size: 11px !important;
	}

	.ukuran12{
		font-size: 12px !important;
	}
	.ukuran13{
		font-size: 13px !important;
	}



</style>
<div class="row">
	
	<div class="col-sm-12 ">

		<table width="100%" border="0">
			<tr>
				<td  style="padding-left:10px;" >
					<a href="<?=site_url('MOS/MosBarangMasuk/FrmEntryMOS/'.$JobNo)?>" class="btn btn-default btn-md">ENTRY MOS <i class="fa fa-plus"></i></a>
					<!-- <a href="<?=site_url('MOS/MosBarangMasuk/addMOS/'.$JobNo)?>" class="btn btn-default btn-md">ENTRY MOS <i class="fa fa-plus"></i></a> -->
				</td>
			</tr>
		</table>
		<hr>

		<div class="table-responsive" style="padding-left:10px;padding-right:10px;">
			<table class="table table-striped table-hover table-bordered table-condensed" id="tblMOS"  >

				<thead>
					<tr class="bg-gray">
						<th class="text-center ukuran12" width="150px" orderable="false"></th>
						<th class="ukuran12">No. MOS</th>
						<th class="ukuran12">Tgl <br>Terima Barang</th>
						<th class="ukuran12">Nama<br>Vendor</th>
						<th class="ukuran12">No. Kontrak</th>
						<th class="text-right ukuran12">Total Vol KO/PO</th>
						<th class="text-right ukuran12">Total Vol Terima</th>
						<th class="text-right ukuran12">Total OutStanding</th>
						<th class="	ukuran12">Approve By</th>
						<th class="	ukuran12">Approve On</th>
					</tr>
				</thead>

				<tbody>
					<?php 
					$TotalOutStanding = 0;
					$ApprovedTxt = '';
					foreach ($mydata as $data) { 
						$TotalOutStanding = $data->Total_Volume_PO - $data->Total_Volume_MOS;
						if ( ($data->ApprovedBy == '' ||  $data->TimeApproved == '') ||  ($data->ApprovedBy == NULL ||  $data->TimeApproved == NULL)) {
							$ApprovedTxt = 'Approve';
						}else{
							$ApprovedTxt = 'UnApprove';
						}

						?>
						<tr>
							<td class="text-center ">
								<button class="btn btn-link btn-sm ukuran13 lakukan_approve" data-ApproveTxt="<?=$ApprovedTxt?>" data-NoSJ="<?=$data->NoSJ?>" type="button" style="text-decoration: underline;" title="<?=$ApprovedTxt?> MOS"><?=strtoupper($ApprovedTxt)?></button>

								<a href="<?=site_url('MOS/MosBarangMasuk/FrmEntryMOS/'.$data->JobNo.'/'.$data->NoSJ.'/'.$data->NoKO.'/see')?>" class="btn btn-sm btn-link ukuran13 " style="text-decoration: underline;" >SELECT</a>
								
							</td>
							<td><?=$data->NoSJ?></td>
							<td><?=tgl_baru($data->TglSJ)?></td>
							<td><?=$data->VendorNm?></td>
							<td><?=$data->NoKO?></td>

							<td class="text-right"><?=VolKetik($data->Total_Volume_PO)?></td>
							<td class="text-right"><?=VolKetik($data->Total_Volume_MOS)?></td>
							<td class="text-right"><?=VolKetik($TotalOutStanding)?></td>

							<td><?=$data->ApprovedBy?></td>
							<td><?=$data->TimeApproved?></td>
						</tr>

					<?php } ?>
					
				</tbody>

				<!-- <tbody>
					<?php 
					$OutStanding = 0;
					$ApproveBY = '';
					$Nomor = 1;
					foreach ($mydata as $data) { 

						$OutStanding = $data->Vol_PO - $data->Vol;

						if ($data->ApproveBY == '' || $data->ApproveBY == NULL ) {
							$ApproveBY = 'APPROVE';
						}else{
							$ApproveBY = 'UNAPPROVE';
						}

						$Nomor++;

						?>
						<tr>
							<td class="text-center" width="180px">
								<a href="<?=site_url('MOS/MosBarangMasuk/FrmUpdateMOS/'.$data->LedgerNo1.'/'.$JobNo.'/'.$data->NoSJ)?>" class="btn btn-sm btn-link" title="Edit MOS"><u>EDIT</u></a>
								<button class="btn btn-sm btn-link btn_approve" data-LedgerNo="<?=$data->LedgerNo1?>" data-NoSJ="<?=$data->NoSJ?>" data-ApprovedBy="<?=$ApproveBY?>"  title="<?=$ApproveBY?>" data-Nomor="<?=$Nomor;?>" id="btn_approveID<?=$Nomor;?>"  ><u><?=$ApproveBY?></u></button>
							</td>
							<td><?=$data->No_MOS?></td>
							<td><?=$data->TglTerima?></td>
							<td><?=$data->VendorNm?></td>
							<td><?=$data->No_Kontrak?></td>
							<td><?=$data->NoSJ?></td>
							<td><?=$data->NoUrut?></td>
							<td><?=$data->Uraian?></td>
							<td><?=UangTerbaru($data->Vol_PO)?></td>
							<td><?=UangTerbaru($data->Vol)?></td>
							<td><?=UangTerbaru($OutStanding)?></td>
							<td><?=$data->Uom?></td>
							<td><?=$data->ApproveBY?></td>
							<td><?=tgl_baru_tahun_pendek_dengan_waktu($data->ApproveOn)?></td>
						</tr>
					<?php } ?>
				</tbody>
			-->
		</table>
	</div>

</div>

</div>


<script src="<?=base_url('assets/swal2_terbaru.js')?>"></script>
<script type="text/javascript">
	$(function(){
		$('#tblMOS').DataTable({
			pageLength: 50,
			lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
			fixedColumns:   true,
			pagging : false,
			scrollY:        "500px",
			scrollX:        true,
			scrollCollapse: true,
			order: [[1, 'desc']],
			// ordering : false,

		});

		$('.lakukan_approve').click(function(){

			var ApprovedTxt = $(this).attr('data-ApproveTxt');
			var NoSJ = $(this).attr('data-NoSJ');

			Swal.fire({
				title: 'Anda Yakin?',
				text: ApprovedTxt+" "+NoSJ,
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya',
				cancelButtonText : 'Batal',
			}).then((result) => {
				if (result.isConfirmed) {
					jQuery.ajax({
						url: '<?=site_url('MOS/MosBarangMasuk/lakukan_approve')?>',
						type: 'POST',
						dataType: 'html',
						data: {NoSJ : NoSJ, ApprovedTxt : ApprovedTxt},
						beforeSend : function(){
							$(this).text('Loading...').attr('disabled',true);
						},
						complete: function(xhr, textStatus) {
							$(this).text(ApprovedTxt).attr('disabled',true);
						},
						success: function(data, textStatus, xhr) {
							if (data == 'berhasil') {
								Swal.fire({
									position: 'top-end',
									icon: 'success',
									title: 'data berhasil di update',
									showConfirmButton: false,
									timer: 1000
								});
								ListMOS();
							}else{
								Swal.fire({
									position: 'top-end',
									icon: 'error',
									title: 'terjadi kesalahan',
									showConfirmButton: false,
									timer: 1000
								})
							}

						},
						error: function(xhr, textStatus, errorThrown) {
							alert('Error');
						}
					});

				}
			})
		});


		$('.btn_approve').click(function(){
			LedgerNo = $(this).attr('data-LedgerNo');
			NoSJ = $(this).attr('data-NoSJ');
			ApprovedBy = $(this).attr('data-ApprovedBy');
			Nomor = $(this).attr('data-Nomor');

			// alert(LedgerNo+' - '+NoSJ);


			Swal.fire({
				title: 'Yakin '+ApprovedBy+' '+NoSJ+'?',
				
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yakin',
				cancelButtonText: 'Batal'
			}).then((result) => {

				jQuery.ajax({
					url: '<?=site_url('MOS/MosBarangMasuk/aksi_approve')?>',
					type: 'POST',
					dataType: 'html',
					data: {LedgerNo : LedgerNo, NoSJ : NoSJ, ApprovedBy : ApprovedBy },
					beforeSend : function(){
						$('#btn_approveID'+Nomor).attr('disabled',true);
					},
					complete: function(xhr, textStatus) {
						$('#btn_approveID'+Nomor).attr('disabled',false);
						ListMOS();
					},
					success: function(data, textStatus, xhr) {
						// alert(data);

						if (data == 'file_besar') {
							Swal.fire('Info!','File maximal 2mb','info');

						}else if(data == 'berhasil'){
							Swal.fire('Info!','Berhasil','success');
						}else{
							Swal.fire('Info!','gagal','error');
						}
					},
					error: function(xhr, textStatus, errorThrown) {
						alert('error');
					}
				});
				

			})
		});



	});		


</script>