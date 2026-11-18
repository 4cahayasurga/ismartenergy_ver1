 <style type="text/css">
 	#example {
 		/*border: 1px solid;*/
 		/*border-collapse: collapse;*/
 		/*width: 100%;*/

 		white-space: nowrap;
 	}
 </style>
 <div class="table-responsive">

 	<table id="example" class="table table-striped table-bordered table-striped table-condensed" width="100%" >
 		<thead>
 			<tr>
 				<th rowspan="2" style="vertical-align:middle;width: 13% !important;"></th>
 				<th rowspan="2" style="vertical-align:middle;width: 10% !important;" >Dari Tanggal</th>
 				<th rowspan="2" style="vertical-align:middle;" >Sampai Tanggal</th>
 				<th rowspan="2" style="vertical-align:middle;" >Tipe Request</th>
 				<th rowspan="2" style="vertical-align:middle;" >Karyawan Pengganti</th>
 				<th colspan="3" class="text-center bg-gray disabled color-palette" style="background-color:#d2d6de;">Atasan</th>
 				<th colspan="3" class="text-center bg-blue disabled color-palette"  >SDM</th>
 			</tr>
 			<tr>
 				<!-- ==== ATASAN  -->
 				<th>Status</th>
 				<th>Approve/Reject Oleh</th>
 				<th>Waktu Approve/Reject</th>
 				<!-- ==== END ATASAN  -->

 				<!-- ==== SDM  -->
 				<th>Status</th>
 				<th>Approve/Reject Oleh</th>
 				<th>Waktu Approve/Reject</th>
 				<!-- ==== END SDM  -->
 			</tr>
 		</thead>
 		<tbody>
 			<?php foreach($data_cuti as $row_cuti) : ?>

 				<tr>
 					<td class="text-center">
 						<button class="btn btn-warning btn-md btn_edit" onclick="edit_request('<?=$row_cuti->LedgerNo?>')" ><i class="fa fa-pencil"></i> EDIT</button>
 						<button class="btn btn-danger btn-md btn_hapus" onclick="hapus_cuti_request('<?=$row_cuti->LedgerNo?>')" ><i class="fa fa-trash"></i> HAPUS</button>

 					</td>
 					<td><?=TanggalSaya($row_cuti->DrTgl)?></td>
 					<td><?=TanggalSaya($row_cuti->SpTgl)?></td>
 					<td><?=$row_cuti->Status?></td>
 					<td><?=$row_cuti->KarPeng?></td>

 					<!-- ==== ATASAN  -->
 					<td><?=$row_cuti->HeadAction?></td>
 					<td><?=$row_cuti->HeadUserAction?></td>
 					<td><?=atur_null(date('d-M-Y H:i', strtotime($row_cuti->HeadTimeAction)))?></td>
 					<!-- ==== END ATASAN  -->

 					<!-- ==== SDM  -->
 					<td><?=$row_cuti->SDMAction?></td>
 					<td><?=$row_cuti->SDMUserAction?></td>
 					<td><?=atur_null(date('d-M-Y H:i', strtotime($row_cuti->SDMTimeAction)))?></td>
 					<!-- ==== END SDM  -->
 				</tr>

 			<?php endforeach; ?>
 		</tbody>
 	</table>



 </div>

 <script src="<?=base_url('assets/swal2.js')?>"></script>

 <script type="text/javascript">



 	function hapus_cuti_request(LedgerNo){


        if (confirm('Anda yakin ingin menghapus data ini?')) {

            jQuery.ajax({
                url: '<?=site_url('FrmCuti/hapus_cuti_request')?>',
                type: 'POST',
                dataType: 'JSON',
                cache : false,
                data: {LedgerNo : LedgerNo},
                beforeSend : function(){
                    $('.btn_hapus').attr('disabled',true).text('Loading...');
                },
                success: function(data, textStatus, xhr) {
                    $('.btn_hapus').attr('disabled',false).text('HAPUS');

                    if (data.flag == 'berhasil') {
                        swal.fire('Sukses!','data berhasil dihapus','success');
                        return false;
                    }else if(data.flag == 'gagal'){
                        swal.fire('Error!','data gagal dihapus','error');
                        return false;
                    }else{
                        swal.fire('Info!',data.pesan,'info');
                        return false;
                    }

                    location.reload();

                    
                },
                error: function(xhr, textStatus, errorThrown) {
                    swal.fire('Error!','Terjadi Kesalahan','error');
                    return false;
                }
            });

        }




 		// Swal.fire({
 		// 	title: 'Yakin Ingin Dihapus?',
 		// 	text: "Anda tidak akan dapat mengembalikan ini!",
 		// 	icon: 'warning',
 		// 	showCancelButton: true,
 		// 	confirmButtonColor: '#3085d6',
 		// 	cancelButtonColor: '#d33',
 		// 	confirmButtonText: 'Yes, delete it!'
 		// }).then((result) => {
 		// 	if (result.isConfirmed) {

 		// 		jQuery.ajax({
 		// 			url: '<?=site_url('FrmCuti/hapus_cuti_request')?>',
 		// 			type: 'POST',
 		// 			dataType: 'JSON',
 		// 			cache : false,
 		// 			data: {LedgerNo : LedgerNo},
 		// 			beforeSend : function(){
 		// 				$('.btn_hapus').attr('disabled',true).text('Loading...');
 		// 			},
 		// 			success: function(data, textStatus, xhr) {
 		// 				$('.btn_hapus').attr('disabled',false).text('HAPUS');
 		// 				// table_cuti();

 		// 				if (data.flag == 'berhasil') {
 		// 					swal.fire('Sukses!','data berhasil dihapus','success');
 		// 					return false;
 		// 				}else if(data.flag == 'gagal'){
 		// 					swal.fire('Error!','data gagal dihapus','error');
 		// 					return false;
 		// 				}else{
 		// 					swal.fire('Info!',data.pesan,'info');
 		// 					return false;
 		// 				}

   //                      location.reload();


 		// 			},
 		// 			error: function(xhr, textStatus, errorThrown) {
 		// 				swal.fire('Error!','Terjadi Kesalahan','error');
 		// 				return false;
 		// 			}
 		// 		});
 		// 	}
 		// })
 	}

 	function edit_request(LedgerNo=''){
 		$('#modal_body_saya').html('');
 		jQuery.ajax({
 			url: '<?=site_url('FrmCuti/tampilkan_form_edit_cuti')?>',
 			type: 'POST',
 			dataType: 'html',
 			cache : false,
 			data: {LedgerNo : LedgerNo},
 			beforeSend : function(){
 				$('.btn_edit').attr('disabled',true).text('Loading...');
 			},
 			success: function(data, textStatus, xhr) {
 				$('.btn_edit').attr('disabled',false).text('EDIT');
 				$('#modal_body_saya').html(data);
 				$('#modal_saya').modal('show');
 			},
 			error: function(xhr, textStatus, errorThrown) {
 				swal.fire('Error!','Terjadi Kesalahan','error');
 				return false;
 			}
 		});
 	}


 	$(function(){
 		$('#example').DataTable();

 		$("tbody tr").on("click", function() {
 			$(this).find("td").each(function(i) {
 				$(this).toggleClass('active_table');
 			});
 		});
 		

 	})
 </script>