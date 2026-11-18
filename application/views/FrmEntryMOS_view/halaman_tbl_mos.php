<style type="text/css">
	#mytable{
		font-family: sans-serif;
		font-size: 12px;
		width: 80%;
	}
	#mytable thead tr th{
		font-family: sans-serif;
		font-size: 12px;
		border-top: 1px solid;
		border-bottom: 1px solid;
		padding-top: 7px;
		padding-bottom: 7px;
	}
	#mytable tbody tr td{
		font-family: sans-serif;
		font-size: 12px;
		padding-top: 7px;
		padding-bottom: 7px;
	}
	.ukuran12{
		font-size: 12px;
	}
	.swal2-popup {
		font-size: 1.6rem !important;
		font-family: sans-serif;
	}
	.my-actions { margin: 0 2em; }
	.order-1 { order: 1; }
	.order-2 { order: 2; }
	.order-3 { order: 3; }

	.right-gap {
		margin-right: auto;
	}
</style>


	<div class="col-sm-9">
		<a href="<?=site_url('MOS/FrmEntryMOS/addNewMOS/'.$JobNo)?>" type="button" title="Buat MOS Baru" class="btn btn-success btn-md" style="margin-bottom:10px !important;" >Add MOS <i class="fa fa-plus"></i></a>
		<table class="table table-hover table-striped table-condensed table-bordered" id="mytable">
			<thead>
				<tr class=" bg-gray">
					<th></th>
					<th class="text-center" width="50px">Action</th>
					<th class="text-center" width="100px">No SJ</th>
					<th class="text-center" width="100px">Tgl SJ</th>
					<th class="text-center" width="100px">NoKO</th>
					<th class="text-center" width="100px">No SJ Vendor</th>
					<th class="text-center" width="100px">Total Vol Masuk</th>
					<th class="text-center">Approved By</th>
					<th class="text-center">Approved On</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$TotalVolBrgMasuk = 0;
				foreach ($mydata as $row): 

					$TxtApprove = ($row->ApprovedBy == NULL) ? 'Approve' : 'Unapprove';
					// $TotalVolBrgMasuk += $row->VolBrgMasuk;
					$query = $this->db->query("SELECT COALESCE(SUM(VolBrgMasuk),0) as 'TotalVolBrgMasuk' FROM tblBarangMasuk_Detail WHERE NoSJ='$row->NoSJ' AND NoKO='$row->NoKO' GROUP BY NoSJ, NoKO  ");
					if ($query->num_rows() == 0) {
						continue;
					}

					$query = $query->row();
					$TotalVolBrgMasuk = $query->TotalVolBrgMasuk;
					?>
					<tr>
						<td></td>
						<td class="text-center">
							<button class="btn btn-link btn-sm btn_approve" style="display:none;">  id="btnApproveId<?=$row->LedgerNo?>" data-LedgerNo="<?=$row->LedgerNo?>" data-TxtApprove="<?=$TxtApprove?>" data-NoSJ="<?=$row->NoSJ?>" data-NoKO="<?=$row->NoKO?>" ><u><?=strtoupper($TxtApprove)?></u></button>

							<a class="btn btn-link btn-sm" href="<?=site_url('MOS/FrmEntryMOS/sj_detail/'.$JobNo.'/'.$row->NoSJ)?>" ><u>SELECT</u></a>
						</td>
						<td><?=$row->NoSJ?></td>
						<td><?=tgl_baru($row->TglSJ)?></td>
						<td><?=$row->NoKO?></td>
						<td><?=$row->NoSJVendor?></td>
						<td class="text-right"><?=ShowKodeAman(UbahDecimal_Nol_Tiga($TotalVolBrgMasuk))?></td>
						<td><?=$row->ApprovedBy?></td>
						<td><?=tgl_baru_tahun_pendek_dengan_waktu($row->TimeApproved)?></td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
	<!-- </div> -->

	<script src="<?=base_url('assets/swal2_terbaru.js')?>"></script>
	<script type="text/javascript">

		$(document).ready(function(){
			$('#mytable').DataTable({
				fixedColumns:   true,
				paging : false,
				scrollY:        "500px",
				scrollX:        true,
				scrollCollapse: true,
				columnDefs: [
					{ targets: 0, visible: false},
					{ targets: 1, orderable : false},
					]
			});


			$('.btn_approve').click(function(){
				var TxtApprove = $(this).attr('data-TxtApprove');
				var NoSJ = $(this).attr('data-NoSJ');
				var LedgerNo = $(this).attr('data-LedgerNo');

				var NoKO = $(this).attr('data-NoKO');

				Swal.fire({
					title: 'Yakin '+TxtApprove+' '+NoSJ+'?',
					showDenyButton: true,
					showCancelButton: true,
					confirmButtonText: 'Yes',
					denyButtonText: 'No',
					customClass: {
						actions: 'my-actions',
						cancelButton: 'order-1 right-gap',
						confirmButton: 'order-2',
						denyButton: 'order-3',
					}
				}).then((result) => {

					if (result.isConfirmed) {

						$.ajax({
							url: '<?=site_url("MOS/FrmEntryMOS/AksiSimpanApprove")?>',
							type: 'post',
							dataType : 'html',
							data: {'LedgerNo' : LedgerNo, 'TxtApprove' : TxtApprove, 'NoSJ' : NoSJ, 'NoKO' : NoKO},
							async: false,
							cache: false,
							beforeSend : function(){
								$('#btnApproveId'+LedgerNo).addClass('hidden');
							},
							complete : function(){
								$('#btnApproveId'+LedgerNo).removeClass('hidden');
							},
							success: function (data) {

								if (data == 'berhasil') {
									Swal.fire('Saved!', '', 'success');
									TampilTableMOS();
									return false;
								}

								if (data == 'sudah_ada') {
									$.toast({
										text: 'No SJ sudah ada pemakaian',
										position: 'mid-center',
										icon : 'info'
									});
									return false;
								}

								if (data == 'error') {
									$.toast({
										text: 'error',
										position: 'top-right',
										icon : 'error'
									});

									return false;
								}

								if (data == 'gagal') {
									$.toast({
										text: 'terjadi kesalahan',
										position: 'top-right',
										icon : 'error'
									});

									return false;
								}

							},error : function(){
								$.toast({
									text: 'error',
									position: 'top-right',
									icon : 'error'
								});
							}
						});


					}
				})


			});

		});


	</script>