<style type="text/css">
	#crud_table{
		font-family: sans-serif;
		font-size: 11px !important;
	}
	#crud_table thead tr th{
		border-top:1px solid silver;
		border-bottom:1px solid silver;
		padding-top: 10px !important;
		padding-bottom: 10px !important;
		vertical-align: middle;
	}

	#crud_table tbody tr td{
		border-top:1px solid silver;
		border-bottom:1px solid silver;
		padding-top: 10px !important;
		padding-bottom: 10px !important;
		vertical-align: middle;
	}
	.border_red{
		border:2px solid red;
	}
</style>

<div class="col-sm-12" >
	<form id="FormUpdateSemua">
		<div class="table-responsive">
			<table width="100%">
				<tr>
					<td></td>
					<td class="text-right">
						<button type="submit" class="btn btn-success btn-md text-right btn_update_semua">UPDATE ALL</button>
					</td>
				</tr>
			</table>
			<br>

			<table class="table table-bordered table-striped table-hover table-condensed" id="crud_table">
				<thead>
					<tr class="bg-gray">
						<th class="text-center" width="3%">No</th>
						<th class="text-left" width="5%">Kode RAP</th>
						<th class="text-left">Uraian</th>
						<th class="text-left" width="3%">Sat</th>
						<!-- <th class="text-right" width="5%">Vol</th> -->
						<th class="text-right" width="5%">Vol Masuk</th>
						<th class="text-right" width="10%">Vol Terpakai</th>
						<th class="text-right" width="10%">Vol Sisa</th>
						<th class="text-right" width="10%">Input Vol</th>
						<!-- <th class="text-center" width="22%">Keterangan</th> -->
						<th class="text-center" width="10%"></th>

					</tr>
				</thead>
				<tbody>
					<?php foreach ($arrayKO as $row) : ?>

						<tr>
							<td class="text-center" widtd="3%"><?=$row->NoUrut?></td>
							<td class="text-left" widtd="7%"><?=$row->KdRAP?></td>
							<td class="text-left"><?=$row->Uraian?></td>
							<td class="text-left" widtd="3%"><?=$row->Uom?></td>
							<td class="text-right" width="10%"><?=VolKetik($row->VolMasuk)?></td>
							<td class="text-right" width="10%"><?=VolKetik($row->VolTerpakai)?></td>
							<td class="text-right" width="10%"><?=VolKetik($row->VolSisa)?></td>
							<td class="text-right" width="10%">

								<input type="text" name="JumlahDipakai[]" class="form-control input-sm JumlahDipakaiClass KetikVol text-right" 
								value="0" id="JumlahDipakaiId<?=$row->NoUrut?>"
								data-NoUrut="<?=$row->NoUrut?>" 
								data-VolSisa="<?=$row->VolSisa?>" 
								autocomplete="off"
								<?=($row->VolSisa == 0) ? 'readonly' : '' ?> 
								>

								<input type="hidden" name="NoUrutText[]" value="<?=$row->NoUrut?>" >
								<input type="hidden" name="AlokasiText[]" value="<?=$row->Alokasi?>" >
								<input type="hidden" name="KdRAPText[]" value="<?=$row->KdRAP?>" >
								<input type="hidden" name="UraianText[]" value="<?=$row->Uraian?>" >

								<input type="hidden" name="UomText[]" value="<?=$row->Uom?>" >
								<input type="hidden" name="HrgSatuanText[]" value="<?=$row->HrgSatuan?>" >
								<input type="hidden" name="VolKOText[]" value="<?=$row->VolKO?>" >
								<input type="hidden" name="Uraian[]Text" value="<?=$row->Uraian?>" >

								<span class="text-red" id="peringatan<?=$row->NoUrut?>" style="display: none;padding-left:7px;font-size:9px;">*VOLUME MELEBIHI SISA</span>
								<input type="text" id="peringatan_aktif<?=$row->NoUrut?>" value="0" name="peringatan_aktif[]" data-NoUrut="<?=$row->NoUrut?>"  style="display: none;" disabled>
							</td>
							<td class="text-center">
								<button type="button" class="btn btn-primary btn-md btn_update_vol" id="btnupdateId<?=$row->NoUrut?>"  data-NoUrut="<?=$row->NoUrut?>"
									data-JobNo="<?=$JobNo?>"
									data-NoMK="<?=$NoMK?>"
									data-NoKO="<?=$NoKO?>"
									data-Alokasi="<?=$row->Alokasi?>"
									data-KdRAP="<?=$row->KdRAP?>"
									data-Uraian="<?=$row->Uraian?>"
									data-Uom="<?=$row->Uom?>"
									data-HrgSatuan="<?=$row->HrgSatuan?>"
									data-VolKO="<?=$row->VolKO?>"

									>UPDATE</button>

									<!-- <a href="javascript:void(0)" type="button" class="btn btn-primary btn-sm" id="btnupdateId<?=$row->NoUrut?>"  data-NoUrut="<?=$row->NoUrut?>"  onClick="btnUbahVol(<?=$row->NoUrut?>)" >Update</a> -->
								</td>

							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</form>
	</div>

	<script type="text/javascript">
		$(document).ready(function(){

			$('#FormUpdateSemua').submit(function(e){
				e.preventDefault();

				var FormSaya = $(this).serializeArray();
				FormSaya.push({name: 'JobNo', value: $('#JobNo').val()});
				FormSaya.push({name: 'NoMK', value: $('#NoMK').val()});
				FormSaya.push({name: 'NoKO', value: $('#NoKO').val()});
				FormSaya.push({name: 'Alokasi', value: $('#Alokasi').val()});

				// 		$NoMK = $this->input->post('NoMK');
				// $NoKO = $this->input->post('NoKO');
				// $NoUrut = $this->input->post('NoUrut');
				// $Alokasi = $this->input->post('Alokasi');
				// $KdRAP = $this->input->post('KdRAP');
				// $Uraian = $this->input->post('Uraian');
				// $Uom = $this->input->post('Uom');
				// $HrgSatuan = $this->input->post('HrgSatuan');
				// $VolKO = $this->input->post('VolKO');

				$.ajax({
					url: '<?=site_url('MOS/FrmReqPemakaianMOS/UpdateSemua')?>',
					type: 'post',
					data: FormSaya,
					dataType : 'html',
					beforeSend : function(){
						// $(this).attr('disabled',true);
						$('.btn_update_semua').attr('disabled',true);
					},complete : function(){
						$('.btn_update_semua').attr('disabled',false);
						refresh_table();
					},
					success: function (data) {
						// alert(data);
						// console.log(data);

						if (data == 'berhasil') {
							$.toast({
								text: 'berhasil mengubah data',
								position: 'right-center',
								icon : 'success'
							});
							return false;

						}else if(data == 'gagal'){
							$.toast({
								text: 'gagal mengubah data',
								position: 'right-center',
								icon : 'error'
							});
							return false;

						}else{
							$.toast({
								text: 'error',
								position: 'right-center',
								icon : 'error'
							});
							return false;
							
						}

					}, error : function(){
						$.toast({
							text: 'error',
							position: 'right-center',
							icon : 'error'
						});
						return false;
					}
				});

			});


			// $('.update_semua').click(function(){
			// 	// $('#crud_table tbody tr td input[name="JumlahDipakai[]"]').each(function(){
			// 	// 	// console.log($(this).val());
			// 	// 	var NoUrut = $(this).attr('data-NoUrut');
			// 	// 	alert(Nourut);
			// 	// });

			// 	$('.JumlahDipakaiClass').each(function(){
			// 		var NoUrut = $(this).attr('data-NoUrut');
			// 		// alert(NoUrut);
			// 		var NoUrut = $(this).attr('data-NoUrut');
			// 		var JumlahDipakai = HapusKomaVol(this.value);
			// 		var VolSisa = $(this).attr('data-VolSisa');

			// 		// alert(JumlahDipakai);
			// 		// console.log('Jumlah dipakai : '+VolSisa,'\n');
			// 		if (JumlahDipakai > parseFloat(VolSisa) ) {
			// 			$(this).addClass('border_red');
			// 			$('#peringatan'+NoUrut).show();
			// 			$('#peringatan_aktif'+NoUrut).val(1);
			// 			$('#btnupdateId'+NoUrut).attr('disabled',true);
			// 		}else{
			// 			$(this).removeClass('border_red');
			// 			$('#peringatan'+NoUrut).hide();
			// 			$('#peringatan_aktif'+NoUrut).val(0);
			// 			$('#btnupdateId'+NoUrut).attr('disabled',false);
			// 		}

			// 		var NoUrut = $(this).attr('data-NoUrut');
			// 		var VolInput = $('#JumlahDipakaiId'+NoUrut).val();
			// 		var NoMK = $('#btnupdateId'+NoUrut).attr('data-NoMK');
			// 		var JobNo = $('#btnupdateId'+NoUrut).attr('data-JobNo');
			// 		var NoKO = $('#btnupdateId'+NoUrut).attr('data-NoKO');

			// 		var Alokasi = $('#btnupdateId'+NoUrut).attr('data-Alokasi');
			// 		var KdRAP = $('#btnupdateId'+NoUrut).attr('data-KdRAP');
			// 		var Uraian = $('#btnupdateId'+NoUrut).attr('data-Uraian');
			// 		var Uom = $('#btnupdateId'+NoUrut).attr('data-Uom');
			// 		var HrgSatuan = $('#btnupdateId'+NoUrut).attr('data-HrgSatuan');
			// 		var VolKO = $('#btnupdateId'+NoUrut).attr('data-VolKO');
			// 	});
			// })

			var formatVol = function(num){
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


			$(".KetikVol").keyup(function(e){
				$(this).val(formatVol($(this).val()));

			});

			$('.JumlahDipakaiClass').focus(function(){
				if (this.value == 0) {
					$(this).val('');
				}

			});


			$('.JumlahDipakaiClass').focusout(function(){
				if (this.value == '') {
					$(this).val(0);
				}

				var NoUrut = $(this).attr('data-NoUrut');
				var JumlahDipakai = HapusKomaVol(this.value);
				var VolSisa = $(this).attr('data-VolSisa');

				if (JumlahDipakai > parseFloat(VolSisa) ) {
					$(this).addClass('border_red');
					$('#peringatan'+NoUrut).show();
					$('#peringatan_aktif'+NoUrut).val(1);
					$('#btnupdateId'+NoUrut).attr('disabled',true);
				}else{
					$(this).removeClass('border_red');
					$('#peringatan'+NoUrut).hide();
					$('#peringatan_aktif'+NoUrut).val(0);
					$('#btnupdateId'+NoUrut).attr('disabled',false);
				}

				$('.btn_update_vol').click(function(){
					var NoUrut = $(this).attr('data-NoUrut');
					var VolInput = $('#JumlahDipakaiId'+NoUrut).val();
					var NoMK = $('#btnupdateId'+NoUrut).attr('data-NoMK');
					var JobNo = $('#btnupdateId'+NoUrut).attr('data-JobNo');
					var NoKO = $('#btnupdateId'+NoUrut).attr('data-NoKO');

					var Alokasi = $('#btnupdateId'+NoUrut).attr('data-Alokasi');
					var KdRAP = $('#btnupdateId'+NoUrut).attr('data-KdRAP');
					var Uraian = $('#btnupdateId'+NoUrut).attr('data-Uraian');
					var Uom = $('#btnupdateId'+NoUrut).attr('data-Uom');
					var HrgSatuan = $('#btnupdateId'+NoUrut).attr('data-HrgSatuan');
					var VolKO = $('#btnupdateId'+NoUrut).attr('data-VolKO');


					$.ajax({
						url: '<?=site_url('MOS/FrmReqPemakaianMOS/AksiUpdatePemakaian_perkolom')?>',
						type: 'post',
						data: {
							'NoUrut' : NoUrut,
							'VolInput' : VolInput,
							'NoMK' : NoMK,
							'JobNo' : JobNo,
							'NoKO' : NoKO,

							'Alokasi' : Alokasi,
							'KdRAP' : KdRAP,
							'Uraian' : Uraian,
							'Uom' : Uom,
							'HrgSatuan' : HrgSatuan,
							'VolKO' : VolKO,



						},
						dataType : 'html',
						beforeSend : function(){
							$('#btnupdateId'+NoUrut).text('Loading..').attr('disabled',true);
						},complete : function(){
							$('#btnupdateId'+NoUrut).text('Update').attr('disabled',false);	
							// table_add_pemakaian_mos();
							refresh_table();
						},
						success: function (data) {
							// alert(data);
							// console.log(data);
							// return false;
							if (data == 'berhasil') {
								$.toast({
									text: 'berhasil mengubah data',
									position: 'mid-center',
									icon : 'success'
								});
								// location.reload();
								return false;
							}else if(data == 'gagal'){
								$.toast({
									text: 'gagal mengubah data',
									position: 'mid-center',
									icon : 'error'
								});
								return false;
							}else{
								$.toast({
									text: 'error',
									position: 'mid-center',
									icon : 'error'
								});
								return false;
							}
						}, error : function(){
							$.toast({
								text: 'error',
								position: 'mid-center',
								icon : 'error'
							});
							return false;
						}
					});

				});


			});

			function refresh_table(){
				table_add_pemakaian_mos();
			}

			function btnUbahVol(NoUrut=0){
				if (NoUrut == 0) {
					$.toast({
						text: 'error',
						position: 'mid-center',
						icon : 'error'
					});
					return false;

				}

			}

			function HapusKomaVol(nilai) {
				return (nilai.replace(/,/g, ''));
			}

		});


	</script>
