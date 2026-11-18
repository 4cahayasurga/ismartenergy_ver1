
<div class="row">
	<div class="col-sm-12">
		<div class="table-responsive">
			<table width="100%" border="0" style="border-collapse:collapse;margin-bottom: 10px !important;">
				<tr>
					<td>
						<button type="button" class="btn btn-primary" id="btnTambah" data-JobNo="<?=$JobNo?>" data-JobNm="<?=$JobNm?>" data-Alokasi="<?=$Alokasi?>" data-KeteranganAlokasi="<?=$KeteranganAlokasi?>" >Tambah Data</button>
					</td>
				</tr>
			</table>
			<table class="table table-bordered table-striped table-hover"  width="100%" border="0" id="mytable" border="1" style="margin-top:5px !important;">
				<thead>
					<tr>
						<th rowspan="3">No</th>
						<th width="80px" class="text-center" rowspan="3" >Kode RAP</th>
						<th class="text-center"  rowspan="3">Uraian Pekerjaan</th>
						<th width="100px" class="text-center"  rowspan="3">Satuan</th>
						<th width="100px" class="text-center"  rowspan="3">Volume</th>
						<th width="100px" class="text-center"   rowspan="3">Harga Satuan Kontrak (Rp)</th>
						<th width="100px" class="text-center"  rowspan="3">Total Harga Kontrak (Rp)</th>
						<th width="100px" class="text-center" colspan="6">Prestasi Pekerjaan</th>
					</tr>
					<tr>
						<th width="150px" class="text-center" colspan="2">Yang Lalu</th>
						<th width="150px" class="text-center" colspan="2" >Sekarang</th>
						<th width="150px" class="text-center" colspan="2" >S/D Sekarang</th>
					</tr>
					<tr>
						<th class="text-center" >Volume</th>
						<th class="text-center" >Jumlah</th>

						<th class="text-center">Volume</th>
						<th class="text-center" >Jumlah</th>

						<th class="text-center">Volume</th>
						<th class="text-center" >Jumlah</th>
					</tr>

				</thead>
				<tbody>
					<?php 

					$sum_HrgRAB = 0;
					$sum_TotalHrgRAB = 0;
					$sum_VolLalu = 0;
					$sum_JumlahLalu = 0;
					$sum_VolSekarang = 0;
					$sum_JumlahSekarang = 0;
					$sum_VolSampaiDenganSekarang = 0;
					$sum_JumlahSampaiDenganSekarang = 0;

					foreach ($dataRAP as $row): 
						$colspan="";
						// $colspan=($row['Tipe'] == 'Header') ? 

						$sum_HrgRAB += $row['HrgRAB'];
						$sum_TotalHrgRAB += ($row['Vol'] * $row['HrgRAB']);
						$sum_VolLalu += $row['VolLalu'];
						$sum_JumlahLalu += $row['JumlahLalu'];
						$sum_VolSekarang += $row['VolSekarang'];
						$sum_JumlahSekarang += $row['JumlahSekarang'];
						$sum_VolSampaiDenganSekarang += $row['VolSampaiDenganSekarang'];
						$sum_JumlahSampaiDenganSekarang += $row['JumlahSampaiDenganSekarang'];

						?>
						<tr>
							<td ><?=$row['NoUrut']?></td>
							<td ><?=$row['KdRAP']?></td>
							<td><?=$row['Uraian']?></td>
							<td><?=$row['Uom']?></td>
							<td class="text-right" ><?=UbahDecimal_Nol_Tiga($row['Vol'])?></td>
							<td class="text-right"><?=UbahDecimal_Nol_Dua($row['HrgRAB'])?></td>
							<td class="text-right"><?=UbahDecimal_Nol_Dua($row['Vol'] * $row['HrgRAB'])?></td>
							<td class="text-right"><?=UbahDecimal_Nol_Tiga($row['VolLalu'])?></td>
							<td class="text-right"><?=UbahDecimal_Nol_Dua($row['JumlahLalu'])?></td>
							<td class="text-right"><?=UbahDecimal_Nol_Tiga($row['VolSekarang'])?></td>
							<td class="text-right"><?=UbahDecimal_Nol_Dua($row['JumlahSekarang'])?></td>
							<td class="text-right"><?=UbahDecimal_Nol_Tiga($row['VolSampaiDenganSekarang'])?></td>
							<td class="text-right"><?=UbahDecimal_Nol_Dua($row['JumlahSampaiDenganSekarang'])?></td>

						</tr>
					<?php endforeach ?>
				</tbody>
				<tfoot>
					<tr class="text-bold">
						<td colspan="5" class="text-right"><?='sum = '.UbahDecimal_Nol_Tiga($sum_Volume)?></td>
						<td class="text-right" ><?='sum = '.UbahDecimal_Nol_Dua($sum_HrgRAB)?></td>
						<td class="text-right" ><?='sum = '.UbahDecimal_Nol_Dua($sum_TotalHrgRAB)?></td>
						<td class="text-right"><?='sum = '.UbahDecimal_Nol_Tiga($sum_VolLalu)?></td>
						<td class="text-right"><?='sum = '.UbahDecimal_Nol_Dua($sum_JumlahLalu)?></td>
						<td class="text-right"><?='sum = '.UbahDecimal_Nol_Tiga($sum_VolSekarang)?></td>
						<td class="text-right" ><?='sum = '.UbahDecimal_Nol_Dua($sum_JumlahSekarang)?></td>
						<td class="text-right"><?='sum = '.UbahDecimal_Nol_Tiga($sum_VolSampaiDenganSekarang)?></td>
						<td class="text-right"><?='sum = '.UbahDecimal_Nol_Dua($sum_JumlahSampaiDenganSekarang)?></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>

<div id="ModalSaya" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Entry BAPP</h4>
			</div>
			<div class="modal-body" id="ModalBodySaya">
				<form id="FormAddBAPP">
					<div class="table-responsive">
						<table border="0" style="border-collapse:collapse;" width="100%" class="jarak_atas">
							<tr>
								<td width="150px">Job No</td>
								<td class="text-center" width="30px">:</td>
								<td width="200px">
									<input type="text" id="JobNo" class="form-control input-sm" disabled>
								</td>
								<td></td>
							</tr>
						</table>

						<table border="0" style="border-collapse:collapse;" width="100%" class="jarak_atas">
							<tr>
								<td width="150px">Alokasi</td>
								<td class="text-center" width="30px">:</td>
								<td width="200px">
									<input type="text" id="Alokasi" class="form-control input-sm" disabled>
								</td>
								<td></td>
							</tr>
						</table>

						<table border="0" style="border-collapse:collapse;" width="100%" class="jarak_atas">
							<tr>
								<td width="150px">Kode RAP</td>
								<td class="text-center" width="30px">:</td>
								<td >
									<select name="KdRAP" id="KdRAP" class="form-control input-sm select2" width="100%" required >
										<option value="" data-Uom="" data-Vol="0" 
										data-HrgRAB="0" 
										data-TotalHargaKontrak="0" 
										data-Header=""
										data-Uraian=""
										data-Tipe=""
										>-- pilih salah satu --</option>
										<?php foreach ($dataRAP as $data): ?>
											<option value="<?=$data['KdRAP']?>" 
												data-Uom="<?=$data['Uom']?>" data-Vol="<?=UbahDecimal_Nol_Tiga($data['Vol'])?>" 
												data-HrgRAB="<?=UbahDecimal_Nol_Dua($data['HrgRAB'])?>" 
												data-HrgRABDUA="<?=$data['HrgRAB']?>" 
												data-TotalHargaKontrak="<?=UbahDecimal_Nol_Dua($data['TotalHargaKontrak'])?>" 
												data-Header="<?=$data['Header']?>"
												data-Uraian="<?=$data['Uraian']?>"
												data-Tipe="<?=$data['Tipe']?>"
												><?=$data['KdRAP'].' - '.$data['Uraian']?></option>
											<?php endforeach ?>
										</select>
									</td>
									<!-- <td></td> -->
								</tr>
							</table>

							<table border="0" style="border-collapse:collapse;" width="100%" class="jarak_atas">
								<tr>
									<td width="150px">Uraian</td>
									<td class="text-center" width="30px">:</td>
									<td >
										<textarea name="Uraian" id="Uraian" class="form-control input-sm" readonly></textarea>
									</td>
									<!-- <td></td> -->
								</tr>
							</table>

							<table border="0" style="border-collapse:collapse;" width="100%" class="jarak_atas">
								<tr>
									<td width="150px">Satuan</td>
									<td class="text-center" width="30px">:</td>
									<td width="200px">
										<input type="text" name="Uom" id="Uom"  class="form-control input-sm" readonly>
									</td>
									<td></td>
								</tr>
							</table>

							<table border="0" style="border-collapse:collapse;" width="100%" class="jarak_atas">
								<tr>
									<td width="150px">Volume</td>
									<td class="text-center" width="30px">:</td>
									<td width="200px">
										<input type="text" name="Vol" id="Vol"  class="form-control input-sm text-right" readonly>
									</td>
									<td></td>
								</tr>
							</table>

							<table border="0" style="border-collapse:collapse;" width="100%" class="jarak_atas">
								<tr>
									<td width="150px">Harga Satuan Kontrak (Rp)</td>
									<td class="text-center" width="30px">:</td>
									<td width="200px">
										<input type="text" name="HrgRAB" id="HrgRAB"  class="form-control input-sm text-right" readonly>
									</td>
									<td></td>
								</tr>
							</table>

							<table border="0" style="border-collapse:collapse;" width="100%" class="jarak_atas">
								<tr>
									<td width="150px">Total Satuan Kontrak (Rp)</td>
									<td class="text-center" width="30px">:</td>
									<td width="200px">
										<input type="text" id="TotalHargaKontrak"  class="form-control input-sm text-right" readonly>
									</td>
									<td></td>
								</tr>
							</table>

							<table border="0" style="border-collapse:collapse;" width="100%" class="jarak_atas">
								<tr>
									<td width="150px">Volume Input</td>
									<td class="text-center" width="30px">:</td>
									<td width="200px">
										<input type="text" name="VolumeInputan" id="VolumeInputan"  class="form-control input-sm text-right" value="0"  required >
									</td>
									<td></td>
								</tr>
							</table>

							<table border="0" style="border-collapse:collapse;" width="100%" class="jarak_atas">
								<tr>
									<td width="150px">Total Harga (Rp)</td>
									<td class="text-center" width="30px">:</td>
									<td width="200px">
										<input type="text" name="TotalHarga" id="TotalHarga"  class="form-control input-sm text-right" value="0" readonly>
									</td>
									<td></td>
								</tr>
							</table>

							<table border="0" style="border-collapse:collapse;" width="100%" class="jarak_atas">
								<tr>
									<td width="150px"></td>
									<td class="text-center" width="30px">:</td>
									<td width="200px">

										<input type="hidden" name="JobNo" value="<?=$JobNo?>">
										<input type="hidden" name="Header" id="Header">
										<input type="hidden" name="Tipe" id="Tipe">
										<input type="hidden" name="Alokasi" value="<?=$Alokasi?>">

										<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										<button type="submit" class="btn btn-primary btnsave">Save</button>
									</td>
									<td></td>
								</tr>
							</table>




						</div>
					</form>
				</div>
				<div class="modal-footer">
					<!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
				</div>
			</div>

		</div>
	</div>

	<script type="text/javascript">
		$(function(){

			$('#mytable').DataTable( {

				columnDefs: [
					{ targets: 0,visible : false },
					{ targets: 1, orderable : false},
					{ targets: 2, orderable : false},
					],
				order: [[0, 'asc']]
			} );


			$('.select2').select2({ dropdownCssClass: "font12", });


			$( "#btnTambah" ).on( "click", function() {
				var JobNo = $(this).attr('data-JobNo');
				var JobNm = $(this).attr('data-JobNm');

				var Alokasi = $(this).attr('data-Alokasi');
				var KeteranganAlokasi = $(this).attr('data-KeteranganAlokasi');
				$('#ModalSaya').modal('show');

				$('#FormAddBAPP #JobNo').val(JobNo+' - '+JobNm);
				$('#FormAddBAPP #Alokasi').val(Alokasi+' - '+KeteranganAlokasi);

				$('#FormAddBAPP #KdRAP').val('');
				$('#FormAddBAPP #Uraian').val('');
				$('#FormAddBAPP #Vol').val(0);
				$('#FormAddBAPP #HrgRAB').val(0);
				$('#FormAddBAPP #TotalHargaKontrak').val(0);

				$('#FormAddBAPP #VolumeInputan').val(0);
				$('#FormAddBAPP #TotalHarga').val(0);

				$('#FormAddBAPP #Header').val('');
				$('#FormAddBAPP #Tipe').val('');

			} );

			$( "#FormAddBAPP #KdRAP" ).on( "change", function() {
				var Uom = $('#FormAddBAPP #KdRAP option:selected').attr('data-Uom');
				var Vol = $('#FormAddBAPP #KdRAP option:selected').attr('data-Vol');
				var HrgRAB = $('#FormAddBAPP #KdRAP option:selected').attr('data-HrgRAB');
				var TotalHargaKontrak = $('#FormAddBAPP #KdRAP option:selected').attr('data-TotalHargaKontrak');
				var Header = $('#FormAddBAPP #KdRAP option:selected').attr('data-Header');
				var Uraian = $('#FormAddBAPP #KdRAP option:selected').attr('data-Uraian');

				var Tipe = $('#FormAddBAPP #KdRAP option:selected').attr('data-Tipe');





				$('#FormAddBAPP #Uraian').val('');
				$('#FormAddBAPP #Vol').val(0);
				$('#FormAddBAPP #HrgRAB').val(0);
				$('#FormAddBAPP #TotalHargaKontrak').val(0);

				$('#FormAddBAPP #VolumeInputan').val(0);
				$('#FormAddBAPP #TotalHarga').val(0);

				$('#FormAddBAPP #Uraian').val(Uraian);
				$('#FormAddBAPP #Uom').val(Uom);
				$('#FormAddBAPP #Vol').val(Vol);
				$('#FormAddBAPP #HrgRAB').val(HrgRAB);
				$('#FormAddBAPP #TotalHargaKontrak').val(TotalHargaKontrak);

				$('#FormAddBAPP #Header').val('');
				$('#FormAddBAPP #Header').val(Header);
				
				$('#FormAddBAPP #Tipe').val('');
				$('#FormAddBAPP #Tipe').val(Tipe);


			} );

			var formatUang_Nol_Tiga = function(num){
				var str = num.toString().replace("", ""), parts = false, output = [], i = 1, formatted = null;
				if(str.indexOf(".") > 0) {
					parts = str.split(".");
					str = parts[0];
				}
				str = str.split("").reverse();
				for(var j = 0, len = str.length; j < len; j++) {
					if(str[j] != ",") {
						output.push(str[j]);
						if(i%3 == 0 && j < (len - 1)) {
							output.push(",");
						}
						i++;
					}
				}
				formatted = output.reverse().join("");
				return("" + formatted + ((parts) ? "." + parts[1].substr(0, 3) : ""));
			};

			$("#FormAddBAPP #VolumeInputan").keyup(function(e){
				$(this).val(formatUang_Nol_Tiga($(this).val()));

			});

			$('#FormAddBAPP #VolumeInputan').focus(function(){
				if (this.value == 0) {
					$(this).val('');
				}

			});


			$('#FormAddBAPP #VolumeInputan').blur(function(){
				if (this.value == '') {
					$(this).val(0);
				}

				HitungTotalHarga();	

			});



			$( "#FormAddBAPP" ).on( "submit", function( event ) {
				event.preventDefault();


				SimpanBAPP();
			});



		})

		function  SimpanBAPP(){

			var Header = $('#FormAddBAPP #KdRAP option:selected').attr('data-Header');
			if (Header == '0') {
				Swal.fire('Alert!','tidak dapat menyimpan Kode RAP Tipe Header','info');
				return false;
			}

			var JobNo = $('#btnTambah').attr('data-JobNo');
			var Alokasi = $('#btnTambah').attr('data-Alokasi');
			
			jQuery.ajax({
				url: "<?=site_url('FrmEntryBAPP/SimpanBAPP')?>",
				type: 'POST',
				dataType: 'HTML',
				data: $('#FormAddBAPP').serialize(),
				beforeSend : function(){
					// $('.loading_box').show();
					$('.btnsave').attr('disabled',true);
				},
				complete: function(xhr, textStatus) {
					// $('.loading_box').hide();
					$('.btnsave').attr('disabled',false);
					$('#ModalSaya').modal('hide');
					// TampilkanTable_RAP();
				},
				success: function(data, textStatus, xhr) {
					
					if (data == 'berhasil') {
						// Swal.fire('Info!','Saved!','success');
						window.location.href="<?=site_url('FrmEntryBAPP/index/')?>"+JobNo+'/'+Alokasi;
					}else{
						Swal.fire('Alert!','terjadi kesalahan','error');
					}

					
				},
				error: function(xhr, textStatus, errorThrown) {
					Swal.fire('Alert!','terjadi kesalahan','error');
				}	
			});
		}

		function HitungTotalHarga(){
			// var HrgRAB = $('#FormAddBAPP #HrgRAB').val();
			var HrgRAB = $('#FormAddBAPP #KdRAP option:selected').attr('data-HrgRABDUA');
			var VolumeInputan = $('#FormAddBAPP #VolumeInputan').val();
			// alert(HrgRAB)


			jQuery.ajax({
				url: "<?=site_url('FrmEntryBAPP/HitungTotalHarga')?>",
				type: 'POST',
				dataType: 'HTML',
				data: {'HrgRAB' : HrgRAB, 'VolumeInputan' : VolumeInputan},
				beforeSend : function(){
					// $('.loading_box').show();
				},
				complete: function(xhr, textStatus) {
					// $('.loading_box').hide();
				},
				success: function(data, textStatus, xhr) {
					$('#TotalHarga').val(data);
				},
				error: function(xhr, textStatus, errorThrown) {
					Swal.fire('Alert!','terjadi kesalahan','error');
				}	
			});
		}
	</script>