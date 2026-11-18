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

	@media (min-width: 768px) {
		#myModal .modal-xl {
			width: 90%;
			max-width:1200px;
		}
	}
</style>


<!-- <div class="row">	 -->
	<div class="col-sm-12">
		<table class="table table-hover table-striped table-condensed table-bordered" id="mytable">
			<thead>
				<tr class=" bg-gray">
					<th></th>
					<th class="text-center" width="17%">Action</th>
					<th>No SJ</th>
					<th>Tgl SJ</th>
					<th>NoKO</th>
					<th>No SJ Vendor</th>
					<th>Total Vol Masuk</th>
					<th>Approved By</th>
					<th>Approved On</th>
					<th width="5%" class="text-center">File History</th>
					<!-- <th>Total Jumlah </th> -->
				</tr>
			</thead>
			<tbody>
				<?php 
				$TotalVolBrgMasuk = 0;
				foreach ($mydata as $row): 

					$TxtApprove = ($row->ApprovedBy == NULL) ? 'Approve' : 'Unapprove';
					// $TotalVolBrgMasuk += $row->VolBrgMasuk;
					$query = $this->db->query("SELECT SUM(VolBrgMasuk) as 'TotalVolBrgMasuk' FROM tblBarangMasuk_Detail WHERE NoSJ='$row->NoSJ' AND NoKO='$row->NoKO' GROUP BY NoSJ, NoKO  ")->row();
					$TotalVolBrgMasuk = $query->TotalVolBrgMasuk;

					$FileMOS = '';
					$CekFile = $this->db->query("SELECT * FROM tbl_FileMOS WHERE NoSJ='$row->NoSJ'  ");
					if ($CekFile->num_rows() > 0) {
						$mydata = $CekFile->row();
						$FileMOS = 'assets/MosFile/'.$mydata->NamaFile;
					}else{
						$FileMOS = '';
					}

					?>
					<tr>
						<td></td>
						<td class="text-center">
							<button class="btn btn-link btn-sm btn_approve"  id="btnApproveId<?=$row->LedgerNo?>" data-LedgerNo="<?=$row->LedgerNo?>" data-TxtApprove="<?=$TxtApprove?>" data-NoSJ="<?=$row->NoSJ?>" data-NoKO="<?=$row->NoKO?>" ><u><?=strtoupper($TxtApprove)?></u></button>

							<a class="btn btn-link btn-sm" href="<?=site_url('MOS/FrmEntryMOS/sj_detail/'.$JobNo.'/'.$row->NoSJ)?>" ><u>SELECT</u></a>
						</td>
						<td><?=$row->NoSJ?></td>
						<td><?=tgl_baru($row->TglSJ)?></td>
						<td><?=$row->NoKO?></td>
						<td><?=$row->NoSJVendor?></td>
						<td><?=$TotalVolBrgMasuk?></td>
						<td><?=$row->ApprovedBy?></td>
						<td><?=tgl_baru_tahun_pendek_dengan_waktu($row->TimeApproved)?></td>
						<td class="text-center">
							<?php if ($FileMOS !='' && $TxtApprove == 'Unapprove'): ?>
								<button class="btn btn-link btn-md btn_lihat_file" data-NoSJ="<?=$row->NoSJ?>" data-sumber="<?=base_url($FileMOS)?>" style="text-decoration: underline;">LIHAT FILE</button>
							<?php endif ?>
							
						</td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
	<!-- </div> -->

	<div id="myModal" class="modal fade" role="dialog">
		<div class="modal-dialog modal-xl">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body" id="modal_body_saya">
					<iframe id="iframeSaya" src="" width="100%" height="1200"></iframe>
				</div>
				
			</div>

		</div>
	</div>



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

				if( confirm('Yakin '+TxtApprove+' '+NoSJ+'?')) {
					$.ajax({
						url: '<?=site_url("MOS/FrmApproveMOS/AksiSimpanApprove")?>',
						type: 'post',
						dataType : 'html',
						data: {'LedgerNo' : LedgerNo, 'TxtApprove' : TxtApprove, 'NoSJ' : NoSJ, 'NoKO' : NoKO},
						async: false,
						cache: false,
						beforeSend : function(){
							$(this).attr('display','none');
						},
						complete : function(){
							$(this).attr('display','');
						},
						success: function (data) {

							if (data == 'berhasil') {
								alert('Success');
								TampilTableMOS();
								return false;
							}

							if (data == 'sudah_ada') {
								alert('No SJ sudah ada pemakaian');
								return false;
							}

							if (data == 'error') {
								alert('error');
								return false;
							}

							if (data == 'gagal') {
								alert('terjadi kesalahan');
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

				


			});

			$('.btn_lihat_file').click(function(){
				var NoSJ = $(this).attr('data-NoSJ');
				var sumber = $(this).attr('data-sumber');

				$('#myModal').modal('show');
				$('#iframeSaya').attr('src','');
				$('#iframeSaya').attr('src',sumber);
				
			});

		});


	</script>