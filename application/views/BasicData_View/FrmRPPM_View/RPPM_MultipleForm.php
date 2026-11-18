<style type="text/css">
	body{
		font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
	}
	.style_untuk_div{
		border:2px solid silver;
		padding-left: 20px;
		padding-top: 20px;
		padding-bottom: 20px;
		padding-right: 20px;
	}
	.huruf13{
		font-size: 13px !important;
	}
	.huruf14{
		font-size: 14px !important;
	}
	input[type="text"]{
		font-size: 14px !important;
	}
	input[type="date"]{
		font-size: 14px !important;
	}
	select{
		font-size: 14px !important;
	}
	textarea{
		font-size: 14px !important;
	}
	.select2-container {
		width: 100% !important;
	}
</style>
<div class="content-wrapper">

	<section class="content-header">
		<h1><?=($judul) ? $judul : 'Judul belum diatur'  ?></h1>
		<ol class="breadcrumb">

		</ol>
	</section>

	<section class="content">

		<?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>

		<table width="100%" border="0" class="font12">
			<tr>

				<td><a href="<?=site_url('BasicData/FrmRPPM_C/index/'.$JobNo.'/'.$Alokasi)?>" class="btn btn-danger" ><i class="fa fa-arrow-left"></i> Back</a></td>
			</tr>

		</table>
		<p></p>
		<form id="myform" >
			<div class="box box-primary">
				<div class="box-header with-border">
					<table width="100%" border="0" class="font12">
						<tr>
							<td class="80px" width="100px" >Job</td>
							<td class="text-center" width="10px">:</td>
							<td width="300px" ><?=$JobNo.' - '.$JobNm?></td>
							<td></td>
						</tr>

					</table>

					<table width="100%" border="0" class="font12" style="margin-top:5px !important;">
						<tr>
							<td class="80px" width="100px" >Alokasi</td>
							<td class="text-center" width="10px">:</td>
							<td width="300px" ><?=$Alokasi.' - '.$KeteranganAlokasi?></td>
							<td></td>
						</tr>	
					</table>

					<table width="100%" border="0" class="font12" style="margin-top:5px !important;">
						<tr>
							<td class="80px" width="100px" >Periode</td>
							<td class="text-center" width="10px">:</td>
							<td width="200px">
								<input type="date" name="PrdAwal" id="PrdAwal" class="form-control input-sm PrdAwalClass">
							</td>
							<td class="text-center" width="30px">s.d</td>
							<td width="200px">
								<input type="date" name="PrdAkhir" id="PrdAkhir" class="form-control input-sm PrdAkhirClass">
							</td>
							<td class="text-center" width="100px">Minggu Ke</td>
							<td class="text-center" width="10px">:</td>
							<td width="100px">
								<input type="text" name="MingguKe" id="MingguKe" class="form-control input-sm text-right MingguKeClass" value="<?=$MingguAutoCreate?>">
							</td>
							<td></td>
						</tr>
					</table>

					<input type="hidden" name="JobNo" value="<?=$JobNo?>">

					<input type="hidden" name="Alokasi" value="<?=$Alokasi?>">

					<table width="100%" border="0" class="font12" style="margin-top:5px !important;">
						<tr>
							<td class="80px" width="100px" >Tgl Pengakuan</td>
							<td class="text-center" width="10px">:</td>
							<td width="200px">
								<input type="date" name="TglPengakuan" id="TglPengakuan" class="form-control input-sm TglPengakuanClass">
							</td>
							<td></td>
						</tr>
					</table>

				</div>

				<div class="box-body">
					<div class="row">
						<div class="col-sm-12">


							<div class="table-responsive">


								<table class="table table-bordered table-striped table-hover" id="tblsaya">
									<thead>
										<tr>
											<th width="30px" class="text-center">No</th>
											<th width="400px" class="text-center">Kode RAP</th>
											<th width="500px" class="text-center" hidden>Bobot RAB</th>
											<th width="500px" class="text-center">Uraian</th>
											<th width="100px" class="text-center">Satuan</th>
											<th width="200px" class="text-center" hidden>Vol</th>
											<th width="200px" class="text-center" hidden>Harga RAB</th>
											<th width="200px" class="text-center" hidden>Harga Satuan</th>


											<th width="200px" class="text-center" hidden>Total Harga Kontrak (Vol * Hrg RAB)</th>
											<th width="200px" class="text-center" hidden>Total Harga Kontrak Satuan (Vol * Hrg Satuan)</th>
											
											<th width="200px" class="text-center">Volume Input</th>
											<th width="200px" class="text-center">Bobot (%)</th>
											

											<th width="200px" class="text-center" hidden>Total Harga (Volume Input * HrgRAB)</th>
											<th width="200px" class="text-center" hidden>Total Harga (Volume Input * HrgSatuan)</th>
											<th width="200px" class="text-center" hidden>Tipe</th>
											<th width="200px" class="text-center" hidden>Header</th>

											<th width="70px" class="text-center"></th>
										</tr>
									</thead>
									<tbody class="field_wrapper">
										<tr >
											<td class="text-center">1.</td>

											<td width="400px">
												<input type="hidden" name="NoUrut[]" id="NoUrut_1" value="1" class="form-control input-sm NoUrutClass" disabled >
												<select name="KdRAP[]" id="KdRAP_1" class="form-control input-sm select2 KdRAPClass" data-NoUrut="1">
													<option value="" >:: Pilih Salah Satu ::</option>
													<?php foreach ($forSelectKdRAP as $row): ?>
														<option value="<?=$row->KdRAP?>" 
															data-Uraian="<?=htmlspecialchars($row->Uraian)?>" 
															data-Uom="<?=$row->Uom?>" 
															data-Vol="<?=$row->Vol?>"  
															data-Tipe="<?=$row->Tipe?>" 
															data-Header="<?=$row->Header?>" 
															data-HrgSatuan="<?=$row->HrgSatuan?>" 
															data-HrgRAB="<?=$row->HrgRAB?>" 
															data-TotalHargaKontrak="<?=$row->TotalHargaKontrak?>"
															data-TotalHargaKontrak_Satuan="<?=$row->TotalHargaKontrak_Satuan?>"
															data-BobotRAB="<?=$row->BobotRAB?>"
															><?=$row->KdRAP?> - <?=htmlspecialchars($row->Uraian)?></option>
														<?php endforeach ?>
													</select>
												</td>

												
												<td><textarea name="Uraian[]" id="Uraian_1" class="form-control" readonly></textarea><td>
													<input type="text" name="Uom[]" id="Uom_1" class="form-control input-sm UomClass" readonly>
												</td>
												
												
												<td hidden><input type="text" name="Vol[]" id="Vol_1" class="form-control input-sm VolClass" readonly></td>

												<td hidden ><input type="text" name="HrgRAB[]" id="HrgRAB_1" class="form-control input-sm HrgRABClass" readonly></td>

												<td hidden><input type="text" name="HrgSatuan[]" id="HrgSatuan_1" class="form-control input-sm HrgSatuanClass" readonly></td>

												<td  hidden><input type="text" name="TotalHargaKontrak[]" id="TotalHargaKontrak_1" class="form-control input-sm TotalHargaKontrakClass" readonly></td>

												<td  hidden><input type="text" name="TotalHargaKontrak_Satuan[]" id="TotalHargaKontrak_Satuan_1" class="form-control input-sm TotalHargaKontrak_SatuanClass" readonly></td>

												<td><input type="text" name="VolumeInputan[]" id="VolumeInputan_1" class="form-control input-sm text-right VolumeInputanClass" data-NoUrut="1" value="0.000"></td>

												<td><input type="text" name="Bobot_Persen[]" id="Bobot_Persen_1" class="form-control input-sm text-right Bobot_PersenClass" data-NoUrut="1" value="0.000" readonly></td>

												<td hidden ><input type="text" name="TotalHarga[]" id="TotalHarga_1" class="form-control input-sm text-right TotalHargaClass" value="0" readonly></td>

												<td hidden ><input type="text" name="TotalHarga_Satuan[]" id="TotalHarga_Satuan_1" class="form-control input-sm text-right TotalHarga_SatuanClass" value="0" readonly></td>

												<td hidden ><input type="text" name="Tipe[]" id="Tipe_1" class="form-control input-sm text-right TipeClass"  readonly></td>

												<td hidden ><input type="text" name="Header[]" id="Header_1" class="form-control input-sm text-right HeaderClass"  readonly></td>

												<!-- <td class="text-center"><button type="button" id="add" class="btn btn-success add_button"><i class="fa fa-plus"></i></button></td> -->
												<td ><input type="text" name="BobotRAB[]" id="BobotRAB_1" class="BobotRABClass " readonly hidden ></td>


											</tr>
										</tbody>
									</table>

								</div>


							</div>
						</div>

						<div class="box-footer">
							<button type="submit" class="btn btn-primary" id="btnSimpan" >SIMPAN</button>
							<button type="button" id="add" class="btn btn-success add_button"><i class="fa fa-plus"></i></button>
						</div>

						<!-- <div class="overlay" style="display:none;" id="loading_halaman">
							<i class="fa fa-refresh fa-spin"></i>
						</div> -->

					</div>
				</form>

			</section>

		</div>
