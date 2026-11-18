<style type="text/css" media="screen">
	#tblsaya thead tr th{
		/*font-family: sans-serif;
		font-size: 14px !important;
		border: 0px solid;
		padding-top: 10px;
		padding-bottom: 10px;*/
		font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
		color:White;
		background-color:#507CD1;
		font-weight:bold;
		text-align:center;
		font-size: 16px;
		padding-top: 5px;
		padding-bottom: 5px;

	}

	#tblsaya tbody tr td{
		font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
		font-size: 14px !important;
		border: 0px solid;
		padding-top: 5px;
		padding-bottom: 5px;
	}



	/*.atur{
		font-family: sans-serif;
		font-size: 14px !important;
	}*/
	.font14{
		font-size: 14px !important;
	}
</style>


<div class="box">
	<div class="box-body">
		<ul id="rowTab" class="nav nav-tabs">
			<li class="<?=$TabUraian?>"><a data-toggle="tab" href="#tab1">URAIAN</a></li>
			<li class="<?=$TabInvoice?>" ><a data-toggle="tab" href="#tab2">INVOICE</a></li>
		</ul>
		<div class="tab-content">
			<div id="tab1" class="tab-pane fade <?=$TabUraian?> in">

				<br>

				<div class="col-sm-12">
					<div class="table-responsive">

						<table width="100%">
							<tr>
								<td class="bg-gray text-center atur font14 text-bold" style="padding-top:10px;padding-bottom:10px;">URAIAN  PERMINTAAN  DANA</td>
							</tr>
							<tr>
								<td style="padding-top: 20px;"><button type="button" class="btn btn-danger btn-md" id="delete_records">HAPUS URAIAN</button></td>
							</tr>
						</table>
						<p></p>

						<table class="table table-striped table-bordered table-hover table-condensed" width="100%"  id="tblsaya">
							<thead>
								<tr>
									
									<th width="50px" class="text-center">No</th>
									<th width="50px" class="text-center"><input type="checkbox" id="select_all"></th>
									<th width="120px" class="text-center  atur">#</th>
									<th width="50px" class="text-center  atur">No</th>
									<th width="80px" class="text-center  atur" >Kode RAP</th>
									<th class="text-center atur" width="300px">Uraian</th>
									<th width="80px"class="text-right atur">Vol</th>
									<th width="80px" class="text-center atur">Sat</th>
									<th width="130px" class="text-right atur" >Harga Satuan (Rp)</th>
									<th width="130px" class="text-right  atur" >Jumlah Harga (Rp)</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$NomorUrut = 1;
								$SubTotal = 0;
								foreach ($dataPdDtl as $row ):

									$SubTotal += $row->JumlahHarga;
									?>
									<tr>
										
										<td class="text-center"><?=$row->NoUrut?></td>
										<td class="text-center">
											<input type="checkbox" class="emp_checkbox" data-NoPD="<?=$row->NoPD?>" data-NoUrut="<?=$row->NoUrut?>">
										</td>
										<td class="text-center">
											<button type="button" class="btn btn-link btn-md hapus_detail" data-NoPD="<?=$row->NoPD?>" data-NoUrut="<?=$row->NoUrut?>" style="text-decoration: underline;display: none;font-weight: bold;"  >HAPUS</button>

											<button type="button" class="btn btn-link btn-md" onclick="hapus_detail(<?=$row->NoUrut?>)" id="hapus_detail_ID<?=$row->NoUrut?>" data-NoPD="<?=$row->NoPD?>" data-NoUrut="<?=$row->NoUrut?>" style="text-decoration: underline;font-weight: bold;"  >HAPUS</button>


											<button type="button" class="btn btn-link btn-md edit_detail" 
											data-NoPD="<?=$row->NoPD?>" 
											data-NoUrut="<?=$row->NoUrut?>"
											data-KdRAP="<?=$row->KdRAP?>"
											data-Uraian="<?=$row->Uraian?>"
											data-Vol="<?=VolDecimal_Nol_Tiga($row->Vol)?>" 
											data-Uom="<?=$row->Uom?>"
											data-HrgSatuan="<?=UbahDecimal_Nol_Dua($row->HrgSatuan)?>"

											style="text-decoration: underline;display: none;" >EDIT</button>


											<button type="button" class="btn btn-link btn-md" onclick="edit_detail(<?=$row->NoUrut?>)" id="edit_detail_ID<?=$row->NoUrut?>" 
												data-NoPD="<?=$row->NoPD?>" 
												data-NoUrut="<?=$row->NoUrut?>"
												data-KdRAP="<?=$row->KdRAP?>"
												data-Uraian="<?=$row->Uraian?>"
												data-Vol="<?=VolDecimal_Nol_Tiga($row->Vol)?>" 
												data-Uom="<?=$row->Uom?>"
												data-HrgSatuan="<?=UbahDecimal_Nol_Dua($row->HrgSatuan)?>"

												style="text-decoration: underline;font-weight: bold;" >EDIT</button>

											</td>
											<td class="text-center atur" ><?=$row->NoUrut?></td>
											<td class="atur"><?=$row->KdRAP?></td>
											<td class="atur" width="100px"><?=$row->Uraian?></td>
											<td class="text-right atur"><?=VolDecimal_Nol_Tiga($row->Vol)?></td>
											<td class="text-center atur"><?=$row->Uom?></td>
											<td class="text-right atur"><?=UbahDecimal_Nol_Dua($row->HrgSatuan)?></td>
											<td class="text-right atur"><?=number_format($row->JumlahHarga)?></td>
										</tr>
									<?php endforeach ?>

								</tbody>
							</table>

							<p></p>

						</div>

					</div>

					<p></p>
					<p></p>
					<br>
					<div class="row">

					</div>
					<p></p>

					<div class="row">
						<div class="col-sm-6 text-right" style="padding-top:20px;">

						</div>
						<div class="col-sm-2 text-center">
							<span style="font-size:14px !important;font-weight: bold;">Sub Total : </span>
						</div>
						<div class="col-sm-2">
							<input type="text" name="SubTotal_tampil" id="SubTotal_tampil" class="form-control input-md text-right" style="font-size:14px !important;" value="<?=number_format($SubTotal)?>" disabled >
							<input type="hidden" name="SubTotal" id="SubTotal" class="form-control input-md text-right" value="<?=$SubTotal?>" >
						</div>
					</div>
					<p></p>

					<div class="row">
						<div class="col-sm-6 text-right">
							<span style="font-size:14px !important;font-weight: bold;">Saldo Sebelumnya dari No : </span>
						</div>
						<div class="col-sm-2">
							<select name="NoPJSebelumnya" id="NoPJSebelumnya" class="form-control input-md" style="font-size:14px !important;">
								<option value="" Saldo="0"></option>
								<!-- <option value="aa" Saldo="300000000">aa</option> -->
								<?php foreach($List_NoPJSebelumnya as $row) { ?>
									<option value="<?=$row->NoPJ?>" Saldo="<?=$row->Saldo?>"><?=$row->NoPJ?></option>

								<?php } ?>
							</select>
						</div>
						<div class="col-sm-2">
							<input type="text" id="Saldo_tampil" class="form-control input-md text-right" style="font-size:14px !important;" disabled >
							<input type="hidden" name="Saldo" id="Saldo" class="form-control input-md text-right" >
						</div>
					</div>
					<p></p>

					<div class="row">
						<div class="col-sm-6 text-right">

						</div>
						<div class="col-sm-2 text-center">
							<span style="font-size:14px !important;font-weight: bold;">Total Permintaan : </span>
						</div>
						<div class="col-sm-2">
							<input type="text"  id="TotalPermintaan_tampil" class="form-control input-md text-right" style="font-size:14px !important;" disabled>
							<input type="hidden" name="TotalPermintaan" id="TotalPermintaan" class="form-control input-md text-right" >
						</div>
					</div>
					<br><br>

				</div>
				<!-- ======== TAB INVOICE ================= -->
				<div id="tab2" class="tab-pane fade <?=$TabInvoice?> in">

					<div class="col-sm-12">
						<br>
						<div class="table-responsive">
							<table width="100%">
								<tr>
									<td class="bg-gray text-center atur font14 text-bold" style="padding-top:10px;padding-bottom:10px;">L I S T   I N V O I C E</td>
								</tr>
							</table>
							<p></p>

							<table width="100%" border="0" >
								<tr>
									<td width="100px" style="font-size:14px !important;">Total Invoice</td>
									<td width="5px" class="text-center" style="font-size:14px !important;" >:</td>
									<td width="150px" style="font-size:14px !important;">

										<input type="text" name="TotalInvoice" id="TotalInvoice" class="form-control input-md " value="<?=UbahDecimal_Nol_Dua_Tanpa_Koma($TotalInvoice)?>" style="font-size:13px !important;text-align: right;" readonly>

									</td>
									<td width="120px" style="font-size:14px !important;padding-left:20px;">Total Centang</td>
									<td width="5px" class="text-center" style="font-size:14px !important;" >:</td>
									<td width="150px" style="font-size:14px !important;">


										<input type="text" name="TotalBayar" id="TotalBayar" class="form-control input-md" value="<?=UbahDecimal_Nol_Dua_Tanpa_Koma($TotalBayar)?>" style="font-size:14px !important;text-align: right;" readonly >

									</td>
									<td></td>
								</tr>
							</table>
							<p></p>	

							<table class="table table-striped table-bordered table-hover" width="100%" id="tblinvoice">
								<thead >
									<tr  style="color:White;background-color:#507CD1;font-weight:bold;text-align:center;font-size: 16px;">
										<th class="text-center" width="50px">
											<!-- <input type="checkbox" id="pilihsemua"/> -->
										</th>
										<th>No</th>
										<th>No KO</th>
										<th>Vendor</th>
										<th>No Invoice</th>
										<th>Tgl Invoice</th>
										<th>Jatuh Tempo</th>
										<th>Total (Rp)</th>
										<th>PPN (Rp)</th>
										<th>No.FP</th>
										<th>Tgl FP</th>
										<th>Total Bayar (Rp)</th>
									</tr>
								</thead>

								<tbody style="font-size: 14px !important;">

									<?php 
									$NoUrutInvoice = 1;
									foreach ($InvoiceData as $row): ?>


										<tr>
											<td>
												<input type="checkbox" name="PilihanInvoice[]" id="PilihanInvoiceID<?=$row->LedgerNo?>" class="PilihanInvoiceClass" data-LedgerNo="<?=$row->LedgerNo?>" value="1"  <?=($row->IsChecked == 1) ? 'checked' : ''?>>



											</td>
											<td><?=ShowKodeAman($NoUrutInvoice++)?></td>
											<td><?=ShowKodeAman($row->NoKO)?></td>
											<td><?=ShowKodeAman($row->VendorNm)?></td>
											<td><?=ShowKodeAman($row->InvNo)?></td>
											<td><?=tgl_baru(ShowKodeAman($row->InvDate))?></td>
											<td><?=tgl_baru(ShowKodeAman($row->DueDate))?></td>
											<td class="text-right"><?=UbahDecimal_Nol_Dua_Tanpa_Koma(ShowKodeAman($row->Total))?></td>
											<td class="text-right"><?=UbahDecimal_Nol_Dua_Tanpa_Koma(ShowKodeAman($row->PPN))?></td>

											<td><?=$row->FPNo?></td>
											<td><?=tgl_baru($row->FPDate)?></td>
											<td class="text-right">
												<input type="text" name="PaymentAmount[]" class="TotalPaymentClass text-right form-control input-md" id="TotalPaymentID<?=$row->LedgerNo?>" value="<?=UbahDecimal_Nol_Dua_Tanpa_Koma($row->TotalPayment)?>" data-LedgerNo="<?=$row->LedgerNo?>" style="font-size: 14px !important;" >
												<input type="hidden" name="InvNo[]" class="InvNoClass" id="InvNoID<?=$row->LedgerNo?>" value="<?=$row->InvNo?>">
												<input type="hidden" class="TotalClass" id="TotalID<?=$row->LedgerNo?>" value="<?=$row->Total?>">
											</td>
										</tr>
									<?php endforeach ?>
								</tbody>



							</table>


						</div>

					</div>
				</div>
			</div>
		</div>
	</div>


	<script>


	// ==== JQUERY URAIAN === 



		$(document).ready(function() {

			$(document).on('click', '#select_all', function() {          	
				$(".emp_checkbox").prop("checked", this.checked);
				$("#select_count").html($("input.emp_checkbox:checked").length+" Selected");

			});	
			$(document).on('click', '.emp_checkbox', function() {		
				if ($('.emp_checkbox:checked').length == $('.emp_checkbox').length) {
					$('#select_all').prop('checked', true);
				} else {
					$('#select_all').prop('checked', false);
				}
				$("#select_count").html($("input.emp_checkbox:checked").length+" Selected");
				
			});  

			$('#delete_records').on('click', function(e) { 
				// alert('ss')
				var employee = [];  
				$(".emp_checkbox:checked").each(function() {  
					employee.push($(this).attr('data-NoUrut'));
				});	
				if(employee.length <=0)  {  
					alert("belum di-centang");  
				}else{
					WRN_PROFILE_DELETE = "Anda yakin ingin hapus data ini?";  
					var checked = confirm(WRN_PROFILE_DELETE);  
					if(checked == true) {			
						var selected_values = employee.join(","); 
						// alert(selected_values);
						// return false;
						var NoPD = $('#NoPD').val();
						
						$.ajax({
							url: '<?= site_url('Entry/PDPJ/FrmPDLap/aksi_hapus_detail_multiple') ?>',
							type: 'POST',
							dataType: 'html',
							data: {
								'NoPD': NoPD,
								'NoUrut' : selected_values,
							},
							beforeSend : function(){
								$(this).text('Loading...').attr('disabled',true);
							},
						})
						.done(function(data) {
							// alert(data)
							if(data == 'berhasil'){
								toastr.success('', 'Deleted');
							}else if(data == 'gagal'){
								toastr.info('', 'Failed');
							}else{
								toastr.error('', 'Error');
							}
						})
						.fail(function() {
							alert('Terjadi kesalahan saat menghapus data');
						})
						.always(function() {
							$(this).text('HAPUS URAIAN').attr('disabled',false);
							tampilkan_tbl_PdDtl();
						});
						

					}  
				}

			});

			// alert($("#select_count").html($("input.emp_checkbox:checked").length+);



			var table = $('#tblsaya').DataTable( {
				columnDefs: [
					{ targets: 0, visible: false },
					{ targets: 1, orderable : false},
					{ targets: 2, orderable : true},
					{ targets: 3, orderable : false},
					{ targets: 4, orderable : false},
					{ targets: 5, orderable : false},
					{ targets: 6, orderable : false},
					{ targets: 7, orderable : false},
					{ targets: 8, orderable : false},
					],
				// pageLength :"All",
				paging : false,
				order: [[0, 'asc']],

			});

			HitungTotalPermintaan();


			$('#NoPJSebelumnya').change(function(){
				// var Saldo = $('#NoPJSebelumnya option:selected').attr('Saldo');

				var Saldo = ($('#NoPJSebelumnya option:selected').attr('Saldo') == '' || $('#NoPJSebelumnya option:selected').attr('Saldo') == null) ? 0 : ($('#NoPJSebelumnya option:selected').attr('Saldo')).split(',').join('').split('$').join('');
				Saldo = parseFloat(Saldo);
				$('#Saldo_tampil').val('');

				if(this.value == ''){
					Saldo = 0;
				}

				Saldo = parseFloat(Saldo);
				Saldo = Math.round(Saldo);

				$('#Saldo_tampil').val(UbahKeUang(Saldo));
				$('#Saldo').val('');
				$('#Saldo').val(Saldo);

				HitungTotalPermintaan();
			});


		});


		function hapus_detail(NoUrut=''){
			if (NoUrut == '') {
				return false;
			}

			if( confirm('Yakin ingin hapus data ini?')) {

				var NoPD = $('#hapus_detail_ID'+NoUrut).attr('data-NoPD');
				var NoUrut = $('#hapus_detail_ID'+NoUrut).attr('data-NoUrut');

				jQuery.ajax({
					url: '<?= site_url('Entry/PDPJ/FrmPDLap/aksi_hapus_detail') ?>',
					type: 'POST',
					dataType: 'html',
					data: {
						'NoPD': NoPD,
						'NoUrut' : NoUrut,
					},
					beforeSend : function(){
						$(this).text('Loading...').attr('disabled',true);
					},
					complete: function(xhr, textStatus) {
						$(this).text('HAPUS').attr('disabled',false);
						tampilkan_tbl_PdDtl();
					},
					success: function(data, textStatus, xhr) {
						if(data == 'berhasil'){
							toastr.success('', 'Deleted');
						}else if(data == 'gagal'){
							toastr.info('', 'Failed');
						}else{
							toastr.error('', 'Error');
						}

					},
					error: function(xhr, textStatus, errorThrown) {
						toastr.error('', 'error');
					}
				});
			}



		}

		function edit_detail(NoUrut=''){
			if (NoUrut=='') {
				return false;
			}

			var NoUrut = $('#edit_detail_ID'+NoUrut).attr('data-NoUrut');
			var KdRAP = $('#edit_detail_ID'+NoUrut).attr('data-KdRAP');	
			var Uraian = $('#edit_detail_ID'+NoUrut).attr('data-Uraian');
			var Vol = $('#edit_detail_ID'+NoUrut).attr('data-Vol');
			var Uom = $('#edit_detail_ID'+NoUrut).attr('data-Uom');
			var HrgSatuan = $('#edit_detail_ID'+NoUrut).attr('data-HrgSatuan');

			$('#formEditUraian')[0].reset();

			$('#formEditUraian #NoUrut').val('');
			$('#formEditUraian #KdRAP').val('');
			$('#formEditUraian #Uraian').val('');
			$('#formEditUraian #Vol').val(0);
			$('#formEditUraian #Uom').val('');
			$('#formEditUraian #HrgSatuan').val(0);


			$('#formEditUraian #NoUrut').val(NoUrut);
			$('#formEditUraian #KdRAP').val(KdRAP);
			$('#formEditUraian #Uraian').val(Uraian);
			$('#formEditUraian #Vol').val(Vol);
			$('#formEditUraian #Uom').val(Uom);
			$('#formEditUraian #HrgSatuan').val(HrgSatuan);


			$('#ModalEditUraian').modal('show');


		}

		function HitungTotalPermintaan(){
			// var SubTotal = $('#SubTotal').val();
			// var Saldo = ($('#Saldo').val() == '') ? 0 : $('#Saldo').val();

			var SubTotal = ($('#SubTotal').val() == '' || $('#SubTotal').val() == null) ? 0 : $('#SubTotal').val();
			SubTotal = SubTotal.split(',').join('').split('$').join('');
			SubTotal = parseFloat(SubTotal);

			var Saldo = ($('#Saldo').val() == '' || $('#Saldo').val() == null) ? 0 : ($('#Saldo').val()).split(',').join('').split('$').join('');
			Saldo = parseFloat(Saldo);

			var Hitung = 0;
			Hitung = (SubTotal - Saldo);

			Hitung = parseFloat(Hitung);
			Hitung = Math.round(Hitung);

			$('#TotalPermintaan').val(Hitung);
			$('#TotalPermintaan_tampil').val(UbahKeUang(Hitung));


		}

	// ==== END JQUERY URAIAN === 



	// ==== JQUERY INVOICE === 
		$(function(){
			$('.PilihanInvoiceClass').click(function(){
				var LedgerNo = $(this).attr('data-LedgerNo');
				KalkulasiInvoice(LedgerNo);
			});


			$(".TotalPaymentClass").keyup(function(e){
				$('.btnSimpanPD').attr('disabled',true);
				$(this).val(UbahKeUang($(this).val()));

			});

			$('.TotalPaymentClass').focus(function(){
				if (this.value == 0) {
					$(this).val('');
				}

			});


			$('.TotalPaymentClass').focusout(function(){
				if (this.value == '') {
					$(this).val(0);
				}
				var LedgerNo = $(this).attr('data-LedgerNo');
				KalkulasiInvoice(LedgerNo);
			});

		});

		function KalkulasiInvoice(LedgerNo=''){
			if(LedgerNo == ''){
				toastr.error('Error', '');
				return false;
			}

			var StatusChecked = 0;

			if($('#PilihanInvoiceID'+LedgerNo).is(':checked')){
				StatusChecked = 1;
			}

			var TotalPaymentID = $('#TotalPaymentID'+LedgerNo).val();
			var InvNo = $('#InvNoID'+LedgerNo).val();
			var Total = $('#TotalID'+LedgerNo).val();

			$.ajax({
				url: '<?=site_url("Entry/PDPJ/FrmPDLap/AksiUpdate_Invoice")?>',
				type: 'post',
				dataType : 'html',
				data: {LedgerNo : LedgerNo, StatusChecked : StatusChecked, TotalPaymentID : TotalPaymentID, InvNo : InvNo, Total : Total},
				beforeSend : function(){
					$('#PilihanInvoiceID'+LedgerNo).hide();
				},complete : function(){
					$('#PilihanInvoiceID'+LedgerNo).show();
					$('.btnSimpanPD').attr('disabled',false);
					tampilkan_tbl_PdDtl(1)
				},
				success: function (data) {
					$('.btnSimpanPD').attr('disabled',false);
					if(data == 'kelebihan_bayar'){
						toastr.error('Total bayar invoice '+InvNo+' melebihi total invoice.', 'Info!');
						return false;
					}

					if (data == 'error') {
						toastr.error('terjadi kesalahan', 'Error!');
					}else if(data == 'berhasil'){
						toastr.success('', 'Saved!');
					}else if(data == 'gagal'){
						toastr.info('terjadi kegagalan', 'Gagal!');
					}
				}, error : function(){
					alert('error');
					$('.btnSimpanPD').attr('disabled',false);
				}
			});


		}
	// ==== END JQUERY INVOICE === 


	</script>


