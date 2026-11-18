<style type="text/css">
	.font12{
		font-size: 12px !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}
	.font14{
		font-size: 14px;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}
	.font15{
		font-size: 15px;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;	
	}
	.margin3{
		margin-top:5px !important;
	}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
			<p></p>
			<label for="inputName" type="input" name="JobNo" class="col-sm col-form-label" id="JobNo"><?php echo $dipa->JobNo ?></label> - <label for="inputName" name="JobNm" class="col-sm col-form-label" id="JobNo"><?php echo $dipa->JobNm ?></label>
			<br><small><label for="inputName" class="col-sm col-form-label" id="JobNo"><?php echo $dipa->Lokasi ?></label></small>

		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $judul ?></li>
		</ol>
	</section>

	<section class="content-header">
		<div class="row">
			<div class="col-sm-12">
				<div class="table-responsive">

					<table width="100%" border="0" class="font12" style="margin-bottom: 3px;" >
						<tr>
							<td>
								<a href="<?= site_url('job/sub_job/' . $dipa->JobNo) ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
							</td>
						</tr>
					</table>

				</div>

			</div>
			<div class="col-sm-4">
				<?=($this->session->flashdata('message')) ? $this->session->userdata('message') : '' ?>
			</div>
		</div>


		<div class="box">
			<div class="box-header">
				<table width="100%" border="0" style="margin-bottom:10px;">
					<tr>
						<td ><button type="button" class="btn btn-danger btnTambahAhsp" ><i class="fa fa-plus-circle"></i> Tambah Data</button></td>
					</tr>
				</table>
			</div>
			<div class="box-body">


				<?php if (count($Array_TipeHeader_Nol) > 0): ?>

					<?php foreach ($Array_TipeHeader_Nol as $data): ?>


						<div class="row">
							<div class="col-sm-12">
								<div class="table-responsive" >
									<table class="font15">
										<tr>
											<th><?=$data['Nomor']?>. Analisa</th>
											<td>:</td>
											<td style="font-size: medium;">&nbsp;<?=$data['Analisa']?></td>
										</tr>
										<tr>
											<th>Nama Pekerjaan</th>
											<td>:</td>
											<td style="font-size: medium;">&nbsp;<?=$data['KdAlokasiHeader'].' - '.$data['NamaPekerjaan']?></td>
										</tr>
										<tr>
											<th>Satuan</th>
											<td>:</td>
											<td style="font-size: medium;">&nbsp;<?=$data['Uom']?></td>
										</tr>

									</table>



								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-12">
								<div class="table-responsive">

									<table width="100%" border="0" style="border-collapse: collapse;" class="table">
										<thead class="font15">
											<tr>
												<th>No</th>
												<th>Kode AHSP</th>
												<th>Uraian</th>
												<th width="150px">Satuan</th>
												<th class="text-center" width="100px">Kuantitas</th>
												<th class="text-center" width="150px" >Harga Satuan (Rp)</th>
												<th class="text-center" width="150px">Jumlah (Rp)</th>
												<th class="text-center" width="150px">PPN (Rp)</th>
												<th class="text-center" width="150px">Total (*include ppn)(Rp)</th>
												<th class="text-center font14" width="150px">#</th>
											</tr>
										</thead>
										<tbody>
											<?php 
											$sumVol = 0;
											$sumHrgSatuan = 0;
											$sumJumlah = 0;
											$sumPPN_Nominal = 0;
											$sumTotal_IncludePPN= 0;


											$BiayaUmum_HrgSatuan = 0;
											$BiayaUmum_Jumlah = 0;
											$BiayaUmum_PPN_Nominal = 0;
											$BiayaUmum_Total_IncludePPN = 0;


											$Total_HrgSatuan = 0;
											$Total_Jumlah = 0;
											$Total_PPN_Nominal = 0;
											$Total_Total_IncludePPN = 0;


											if ($data['JumlahDetail'] > 0) :
													// code...


												foreach ($data['ArrayDetail'] as $detail): 
													$sumVol += $detail->Vol;
													$sumHrgSatuan += $detail->HrgSatuan;
													$sumJumlah += $detail->Jumlah;
													$sumPPN_Nominal += $detail->PPN_Nominal;
													$sumTotal_IncludePPN += $detail->Total;
													?>
													<tr class="font15">
														<td><?=$detail->NomorUrut?></td>
														<td><?=$detail->KdAHSP?></td>
														<td><?=$detail->NamaPekerjaan?></td>
														<td><?=$detail->Uom?></td>
														<td class="text-right"><?=$detail->Vol?></td>
														<td class="text-right"><?=number_format($detail->HrgSatuan)?></td>
														<td class="text-right"><?=number_format($detail->Jumlah)?></td>
														<td class="text-right"><?=number_format($detail->PPN_Nominal)?></td>
														<td class="text-right"><?=number_format($detail->Total)?></td>
														<td class="text-center">
															<!-- <button type="button" class="btn btn-link btn-md" id="btnEdit<?=$detail->LedgerNo?>" ><u>EDIT</u></button> -->
														</td>
													</tr>
												<?php endforeach ?>


												<?php
												$BiayaUmum_HrgSatuan = (($sumHrgSatuan * 10)/100);
												$BiayaUmum_Jumlah = (($sumJumlah * 10)/100);
												$BiayaUmum_PPN_Nominal = (($sumPPN_Nominal * 10)/100);
												$BiayaUmum_Total_IncludePPN = (($sumTotal_IncludePPN * 10)/100);

												$Total_HrgSatuan = $sumHrgSatuan + $BiayaUmum_HrgSatuan;
												$Total_Jumlah = $sumJumlah + $BiayaUmum_Jumlah;
												$Total_PPN_Nominal = $sumPPN_Nominal + $BiayaUmum_PPN_Nominal;
												$Total_Total_IncludePPN = $sumTotal_IncludePPN + $BiayaUmum_Total_IncludePPN;
												?>

											</tbody>
											<tfoot class="font15">
												<tr class="font15">
													<td colspan="4" class="text-left text-bold">SUM (Rp)</td>
													<td class="text-right"><?=$sumVol?></td>
													<td class="text-right"><?=number_format($sumHrgSatuan)?></td>
													<td class="text-right"><?=number_format($sumJumlah)?></td>
													<td class="text-right"><?=number_format($sumPPN_Nominal)?></td>
													<td class="text-right"><?=number_format($sumTotal_IncludePPN)?></td>
													<td></td>
												</tr>

												<tr class="font15">
													<td colspan="5" class="text-left text-bold">Biaya Umum Dan Keuntungan Lain (10%)</td>
													<td class="text-right"><?=number_format($BiayaUmum_HrgSatuan )?></td>
													<td class="text-right"><?=number_format($BiayaUmum_Jumlah)?></td>
													<td class="text-right"><?=number_format($BiayaUmum_PPN_Nominal)?></td>
													<td class="text-right"><?=number_format($BiayaUmum_Total_IncludePPN)?></td>
													<td></td>
												</tr>

												<tr class="font15">
													<td colspan="5" class="text-left text-bold">Total</td>
													<td class="text-right"><?=number_format($Total_HrgSatuan )?></td>
													<td class="text-right"><?=number_format($Total_Jumlah)?></td>
													<td class="text-right"><?=number_format($Total_PPN_Nominal)?></td>
													<td class="text-right"><?=number_format($Total_Total_IncludePPN)?></td>
													<td></td>
												</tr>

												<tr class="font14">
													<td colspan="3" class="text-left text-bold">Quantity</td>
													<td class="text-right">
														<input type="text" id="Quantity_ID_Vol<?=$detail->NomorUrut?>" class="text-right font15 Quantity_Class_Vol form-control" value="1" data-NomorUrut="<?=$detail->NomorUrut?>" data-Total_Jumlah="<?=$Total_Jumlah?>" data-Total_Total_IncludePPN="<?=$Total_Total_IncludePPN?>" >
													</td>
													<td colspan="2" class="text-left"></td>
													<td class="text-right">
														<input type="text" id="Quantity_ID_Jumlah<?=$detail->NomorUrut?>" class="text-right font15 Quantity_Class_Jumlah form-control" value="<?=number_format($Total_Jumlah)?>" readonly >
													</td>
													<td></td>
													<td  class="text-right">
														<input type="text" id="Quantity_ID_Total<?=$detail->NomorUrut?>" class="text-right font15 Quantity_Class_Total form-control" value="<?=number_format($Total_Total_IncludePPN)?>" readonly >
													</td>
												</tr>

											</tfoot>
										<?php endif; ?>
									</table>

								</div>
							</div>
						</div>

						<hr>

					<?php endforeach; ?>
				<?php endif ?>


			</div>
		</div>



	</section>

	<div id="ModalSaya" class="modal fade" role="dialog">
		<div class="modal-dialog">

			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Entry AHSP</h4>
				</div>

				<div class="modal-body">

					<form id="FormSaya" action="<?= site_url('Ahsp/AddAhsp') ?>" method="post" >

						<div class="col-sm-12" >

							<div class="table-responsive">

								<input type="text" name="JobNo" id="JobNo" value="<?= $datapaket->JobNo ?>" hidden>

								<table width="100%" border="0" class="font12">
									<tr>
										<td width="150px" class="font12" >Type Input Data</td>
										<td width="10px" class="text-center">:</td>
										<td>
											<select name="type" id="type" class="form-control font12" required>
												<option value="">--- pilih nama inputan ---</option>
												<option value="NamaPekerjaan">Nama Pekerjaan</option>
												<option value="Header">Header</option>
												<option value="Detail">Detail</option>
											</select>
										</td>
									</tr>
								</table>

								<table width="100%" border="0" class="font12 margin3">
									<tr>
										<td width="150px" class="font12" >Header</td>
										<td width="10px" class="text-center">:</td>
										<td>
											<select name="Header" id="Header" class="form-control Header" >
												<option value="0">--- Nama Pekerjaan --- </option>
												<?php foreach ($header as $dth) : ?>
													<option value="<?= $dth->KdAHSP ?>" ><?= $dth->KdAHSP ?> - <?= $dth->NamaPekerjaan ?></option>
												<?php endforeach; ?>
											</select>
										</td>
									</tr>
								</table>

								<table width="100%" border="0" class="font12 margin3">
									<tr>
										<td width="150px" class="font12" >Kd Alokasi Header</td>
										<td width="10px" class="text-center">:</td>
										<td>
											<input type="text" name="KdAlokasiHeader" id="KdAlokasiHeader" class="form-control" required  >
										</td>
									</tr>
								</table>

								<table width="100%" border="0" class="font12 margin3">
									<tr>
										<td width="150px" class="font12" >KdAHSP</td>
										<td width="10px" class="text-center">:</td>
										<td>
											<input type="text" name="KdAHSP" id="KdAHSP" class="form-control"  >
										</td>
									</tr>
								</table>

								<table width="100%" border="0" class="font12 margin3">
									<tr>
										<td width="150px" class="font12" >Analisa</td>
										<td width="10px" class="text-center">:</td>
										<td>
											<input type="text" name="Analisa" id="Analisa" class="form-control"  >
										</td>
									</tr>
								</table>

								<table width="100%" border="0" class="font12 NamaPekerjaan margin3" >
									<tr>
										<td width="150px" class="font12" >Nama Pekerjaan</td>
										<td width="10px" class="text-center">:</td>
										<td>
											<input type="text" name="Uraian" id="Uraian" class="form-control"  >
										</td>
									</tr>
								</table>

								<table width="100%" border="0" class="font12 NamaPekerjaan1 margin3" style="display: none;">
									<tr>
										<td width="150px" class="font12" >Nama Pekerjaan</td>
										<td width="10px" class="text-center">:</td>
										<td>

											<select name="Uraian1" id="Uraian1" class="form-control margin3"   >
												<option value="">-- Pilih Detail Dari Huba --</option>
												<?php foreach ($datahuba as $hu) : ?>
													<option value="<?= $hu->Uraian ?>" 
														data-Uom="<?= $hu->Uom ?>" 
														data-Harga="<?= number_format($hu->Harga) ?>" 
														data-PPN="<?=$hu->PPN?>" 
														data-PPN_Nominal="<?= ($hu->PPN_Nominal == NULL || $hu->PPN_Nominal==0 || $hu->PPN_Nominal == '') ? 0 : number_format($hu->PPN_Nominal) ?>"  
														data-Total="<?= ($hu->Total == NULL) ? 0 : number_format($hu->Total) ?>" ><?= $hu->KdHuba ?> - <?= $hu->Uraian ?></option>
													<?php endforeach ?>
												</select>
											</td>
										</tr>
									</table>

									<table width="100%" border="0" class="font12 margin3">
										<tr>
											<td width="150px" class="font12" >Satuan</td>
											<td width="10px" class="text-center">:</td>
											<td>
												<input type="text" name="Satuan" id="Satuan" class="form-control"  >
											</td>
										</tr>
									</table>

									<table width="100%" border="0" class="font12 margin3">
										<tr>
											<td width="150px" class="font12" >Koef</td>
											<td width="10px" class="text-center">:</td>
											<td>
												<input type="text" name="Vol" id="Vol" class="form-control" value="1" readonly >
											</td>
										</tr>
									</table>

									<table width="100%" border="0" class="font12 margin3">
										<tr>
											<td width="150px" class="font12" >Harga Satuan</td>
											<td width="10px" class="text-center">:</td>
											<td>
												<input type="text" name="harga" id="harga" class="form-control" value="0" readonly>
											</td>
										</tr>
									</table>

									<table width="100%" border="0" class="font12 margin3">
										<tr>
											<td width="150px" class="font12" >Jumlah</td>
											<td width="10px" class="text-center">:</td>
											<td>
												<input type="text" name="Jumlah" id="Jumlah" class="form-control" value="0" readonly>
											</td>
										</tr>
									</table>

									<table width="100%" border="0" class="font12 margin3">
										<tr>
											<td width="150px" class="font12" >PPN</td>
											<td width="10px" class="text-center">:</td>
											<td>
												<input type="checkbox" name="PPN" id="PPN" value="1" disabled >
											</td>
										</tr>
									</table>

									<table width="100%" border="0" class="font12 margin3">
										<tr>
											<td width="150px" class="font12" >PPN Nominal (Rp)</td>
											<td width="10px" class="text-center">:</td>
											<td>
												<!-- <input type="text" id="PPN_Nominal_Tampil" class="form-control" value="0" readonly > -->
												<input type="text" name="PPN_Nominal" id="PPN_Nominal" class="form-control" value="0" readonly >
											</td>
										</tr>
									</table>

									<table width="100%" border="0" class="font12 margin3">
										<tr>
											<td width="150px" class="font12" >Total (Jumlah + PPN Nominal)</td>
											<td width="10px" class="text-center">:</td>
											<td>
												<!-- <input type="text" id="Total_Tampil" class="form-control" value="0" readonly> -->
												<input type="text" name="Total" id="Total" class="form-control" value="0" readonly>
											</td>
										</tr>
									</table>

									<table width="100%" border="0" class="font12 margin3">
										<tr>
											<td  class="font12 text-right" >
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
												<button type="submit" class="btn btn-primary btnSimpan"><i class="fa fa-check"></i> Simpan</button>
											</td>

										</tr>
									</table>

								</div>
							</div>

						</form>
					</div>

				</div>

			</div>
		</div>

		<div class="clearfix"></div>
		<div class="clearfix"></div>

	</div>



	




	<script src="<?= base_url('assets/plugins/numeral/numeral.js') ?>"></script>
	<script src="<?= base_url() ?>assets/jquery-latest.min.js"></script>
	<script src="<?= base_url() ?>assets/jquery-2.1.4.js"></script>


	<script>
		$(document).idle({
			onIdle: function() {
				window.location.href = "/logout.php";
			},
			idle: 10000
		});
	</script>

	<script type="text/javascript">

		$(document).ready(function(){
			$('#example').DataTable();
				// === di tambah pada 02 agustus 2023
			$('.btnTambahAhsp').click(function(){
				kosongkan_FormTambahAHSP();

				$('#ModalSaya').modal('show');
			})

			$('#FormSaya #type').change(function(){
				kosongkan_FormTambahAHSP()
				TypeBerganti();
			})

			$('#FormSaya #Header').change(function(){
				if ($('#FormSaya #type') !='NamaPekerjaan') {
					$('#FormSaya #KdAlokasiHeader').val(this.value).keyup();

				}

			});

			$('#FormSaya #KdAlokasiHeader').keyup(function(){
				$('#FormSaya #KdAHSP').val(this.value);


			});

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
				return("" + formatted );
			};

			var untukVolume = function(num){
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

			var untukHargaSatuan = function(num){
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
				return("" + formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
			};

			$('#FormSaya #Vol').keyup(function(){
				
				$(this).val(untukVolume(this.value));
				// Perhitungan();

			});

			$('#FormSaya #Vol').blur(function(){
				if (this.value == '' ) {
					$(this).val(0);
				}

				$(this).val(untukVolume(this.value));

				Perhitungan();
			});

			$('#FormSaya #harga').keyup(function(){
				
				$(this).val(untukHargaSatuan(this.value));
				// Perhitungan();

			});

			$('#FormSaya #harga').blur(function(){
				if (this.value == '' ) {
					$(this).val(0);
				}

				$(this).val(untukHargaSatuan(this.value));
				Perhitungan();

			});

			


			$('#FormSaya #Uraian1 ').change(function(){
				var Uom = $('#FormSaya #Uraian1 option:selected').attr('data-Uom');
				var Harga = $('#FormSaya #Uraian1 option:selected').attr('data-Harga');
				var PPN= $('#FormSaya #Uraian1 option:selected').attr('data-PPN');
				var PPN_Nominal = $('#FormSaya #Uraian1 option:selected').attr('data-PPN_Nominal');
				var Total = $('#FormSaya #Uraian1 option:selected').attr('data-Total');

					// alert(Harga+' - '+PPN+' - '+PPN_Nominal+' - '+Total);

					// Harga = parseFloat(Harga);
					// PPN_Nominal = parseFloat(PPN_Nominal);
					// Total = parseFloat(Total);

				$('#FormSaya #Satuan').val('').val(Uom);
					// $('#FormSaya #Vol').val('').val(Vol);
				$('#FormSaya #harga').val(0).val(Harga);

				if (PPN == 1) {
					$('#PPN').prop('checked', true);
				}else{
					$('#PPN').prop('checked', false);
				}

				$('#FormSaya #PPN_Nominal').val(0).val(PPN_Nominal);
				$('#FormSaya #Total').val('').val(Total);

				Perhitungan();
			});

				// === END di tambah pada 02 agustus 2023



				// == DITAMBAHKAN 21 AGUSTUS 2023

			$('#FormSaya #PPN').on('change',function(){
				Perhitungan();
			});

			$('.Quantity_Class_Vol').on('keyup',function(){
				$(this).val(formatUang_Nol_Tiga(this.value));
			});

			$(".Quantity_Class_Vol").focusout(function(){

				var nilai_ini = HapusKoma(this.value);
				nilai_ini = parseFloat(nilai_ini);

				if ($.isNumeric(nilai_ini) == false) {
					nilai_ini = parseFloat(1);
				}

				var NomorUrut = $(this).attr('data-NomorUrut');
				var Total_Jumlah = parseFloat($(this).attr('data-Total_Jumlah'));
				var Total_Total_IncludePPN = parseFloat($(this).attr('data-Total_Total_IncludePPN'));

				Hitung = 0;
				Hitung_Total_IncludePPN = 0;

				Hitung_Jumlah = (Total_Jumlah / nilai_ini);
				Hitung_Total_IncludePPN = (Total_Total_IncludePPN / nilai_ini);

				$('#Quantity_ID_Jumlah'+NomorUrut).val(addCommas(Hitung_Jumlah.toFixed(2)));
				$('#Quantity_ID_Total'+NomorUrut).val(addCommas(Hitung_Total_IncludePPN.toFixed(2)));

					// alert(Hitung);
			});

				// == END DITAMBAHKAN 21 AGUSTUS 2023
		});

function format_fixed(nilai){
				// nilai =  0.333;
	if (nilai=='' || nilai == null) {
		nilai=0;
	}else{
		nilai=nilai.toFixed(3);
	}

	return nilai;
}

function kosongkan_FormTambahAHSP(){
	$('#FormSaya #KdAlokasiHeader').val('');
	$('#FormSaya #KdAHSP').val('');

	$('#FormSaya #Analisa').val('');
	$('#FormSaya #Uraian').val('');
	$('#FormSaya #Uraian1').val('');
	$('#FormSaya #Satuan').val('');
	$('#FormSaya #Vol').val(1);
	$('#FormSaya #harga').val(0);

	$('#FormSaya #PPN').removeAttr("checked");
	$('#FormSaya #PPN_Nominal_Tampil').val(0);
	$('#FormSaya #PPN_Nominal').val(0);
	$('#FormSaya #Total_Tampil').val(0);
	$('#FormSaya #Total').val(0);

	$('#FormSaya #Jumlah').val(0);
	// $('#FormSaya select[name="type"]').val('');
	$('#FormSaya select[name="Header"]').val('');

}


function TypeBerganti(){

	var type = $('#FormSaya #type').find(":selected").val();

	if (type == 'NamaPekerjaan') {

		$('#FormSaya #Header').val('0').trigger('change');
		$('#FormSaya #Header').attr('readonly',true);

					// == validasi Uraian ==
		$('#FormSaya .NamaPekerjaan').css('display','');
		$('#FormSaya #Uraian').attr('required',true);

		$('#FormSaya .NamaPekerjaan1').css('display','none');
		$('#FormSaya #Uraian1').attr('required',false);
					// == validasi Uraian ==

		$('#FormSaya #Analisa').attr('required',true);

		$('#FormSaya #harga').attr('readonly',true);
		$('#FormSaya #Vol').attr('readonly',true);

		$('#PPN').attr('disabled',true);
		$('#PPN_Nominal').attr('readonly',true);
		$('#Total').attr('readonly',true);


		$('#FormSaya #KdAlokasiHeader').attr('readonly',false);


					// == DI TAMBAHKAN PADA 21 AGUSTUS 2023
		$('#FormSaya #Analisa').attr('readonly',false);
		$('#FormSaya #PPN').attr('disabled',true);
					// == END TAMBAHKAN PADA 21 AGUSTUS 2023

	}else if(type == 'Header'){
					// kosongkan_FormTambahAHSP()
		$('#FormSaya #Header').val('').trigger('change');
		$('#FormSaya #Header').attr('readonly',false);

		$('#FormSaya #Analisa').attr('required',false);

					// == validasi Uraian ==
		$('#FormSaya .NamaPekerjaan').css('display','');
		$('#FormSaya #Uraian').attr('required',true);

		$('#FormSaya .NamaPekerjaan1').css('display','none');
		$('#FormSaya #Uraian1').attr('required',false);

		$('#FormSaya #harga').attr('readonly',false);
		$('#FormSaya #Vol').attr('readonly',false);


		$('#PPN').attr('disabled',true);
		$('#PPN_Nominal').attr('readonly',true);
		$('#Total').attr('readonly',true);
					// == validasi Uraian ==

		$('#FormSaya #KdAlokasiHeader').attr('readonly',true);

					// == DI TAMBAHKAN PADA 21 AGUSTUS 2023
		$('#FormSaya #Analisa').attr('readonly',false);
		$('#FormSaya #PPN').attr('disabled',true);
					// == END TAMBAHKAN PADA 21 AGUSTUS 2023


	}else if(type == 'Detail'){
					// kosongkan_FormTambahAHSP()
		$('#FormSaya #Header').val('').trigger('change');
		$('#FormSaya #Header').attr('readonly',false);
		$('#FormSaya #Analisa').attr('required',false);

					// == validasi Uraian ==
		$('#FormSaya .NamaPekerjaan').css('display','none');
		$('#FormSaya #Uraian').attr('required',false);

		$('#FormSaya .NamaPekerjaan1').css('display','');
		$('#FormSaya #Uraian1').attr('required',true);

		$('#FormSaya #KdAlokasiHeader').attr('readonly',true);
					// == validasi Uraian ==

					// 18 AGUSTUS 2023
		$('#FormSaya #harga').attr('readonly',false);
		$('#FormSaya #Vol').attr('readonly',false);

		$('#PPN').attr('disabled',false);
		$('#PPN_Nominal').attr('readonly',false);
		$('#Total').attr('readonly',false);

					// == DI TAMBAHKAN PADA 21 AGUSTUS 2023
		$('#FormSaya #Analisa').attr('readonly',true);
		$('#FormSaya #PPN').attr('disabled',false);
					// == END TAMBAHKAN PADA 21 AGUSTUS 2023

	}else{

		$('#FormSaya #Header').attr('readonly',false);
		$('#FormSaya #Analisa').attr('required',false);

					// == validasi Uraian ==
		$('#FormSaya .NamaPekerjaan').css('display','none');
		$('#FormSaya #Uraian').attr('required',false);

		$('#FormSaya .NamaPekerjaan1').css('display','');
		$('#FormSaya #Uraian1').attr('required',true);
		$('#FormSaya #harga').attr('readonly',true);
		$('#FormSaya #Vol').attr('readonly',true);
					// == validasi Uraian ==

					// == DI TAMBAHKAN PADA 21 AGUSTUS 2023
		$('#FormSaya #Analisa').attr('readonly',false);
		$('#FormSaya #PPN').attr('disabled',true);
					// == END TAMBAHKAN PADA 21 AGUSTUS 2023
	}

	if ($('#FormSaya #Header').val() == '0') {
		$('#FormSaya #KdAHSP').attr('readonly',true);
	}else{
		$('#FormSaya #KdAHSP').attr('readonly',false);
	}

}

function HapusKoma(nilai) {
	return (nilai.replace(/,/g, ''));
}

function addCommas(nStr) {
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

function Perhitungan(){
	var type = $('#FormSaya #type option:selected').val();
	if ($('#FormSaya #type option:selected').val() == 'Detail') {
		var Vol = $('#FormSaya #Vol').val();
		var Harga = $('#FormSaya #harga').val();
		var PPN_Nominal = $('#FormSaya #PPN_Nominal').val();
						// var PPN = ()$('#FormSaya #PPN').val();

		PPN = 0;
		if ($('#FormSaya #PPN').is(':checked')) {
			PPN = 1;
		}else{
			PPN = 0;
		}

		Vol = HapusKoma(Vol);
		Harga = HapusKoma(Harga);
		PPN_Nominal = HapusKoma(PPN_Nominal);

		Vol = parseFloat(Vol);
		Harga = parseFloat(Harga);
		PPN_Nominal = parseFloat(PPN_Nominal);

		var Jumlah = 0;
		var HitungPPN = 0;
		var Total_Jumlah_Ditambah_PPN = 0;

		Jumlah = Vol * Harga;
		if(PPN==1){
							// HitungPPN = Harga * ;
			HitungPPN = (Harga * 11) / 100;
		}


		Jumlah = parseFloat(Jumlah);
		// Jumlah = Jumlah.toFixed();
		HitungPPN = parseFloat(HitungPPN);
		Total_Jumlah_Ditambah_PPN = (Harga + HitungPPN) * Vol;

		// HitungPPN = HitungPPN.tofixed();
		// Total_Jumlah_Ditambah_PPN = Total_Jumlah_Ditambah_PPN.tofixed();
						// alert(Jumlah+' - '+HitungPPN+' - '+Total_Jumlah_Ditambah_PPN);

		$('#FormSaya #Jumlah').val(addCommas(Jumlah.toFixed()));
		$('#FormSaya #PPN_Nominal').val(addCommas(HitungPPN.toFixed()));
		$('#FormSaya #Total').val(addCommas(Total_Jumlah_Ditambah_PPN.toFixed()));

						// alert(Vol+' - '+Harga+' - '+PPN);

						// var Vol = $('#FormSaya #Vol').val();
						// var Vol = $('#FormSaya #Vol').val();
						// var Vol = $('#FormSaya #Vol').val();
	}
}



			// == DITAMBAHKAN 21 AGUSTUS 2023
$(document).ready(function(){
				// $(".Quantity_Class_Vol").focus(function(){
				// 	if (this.value == 0) {
				// 		$(this).val('');
				// 	}
				// });




});


</script>



