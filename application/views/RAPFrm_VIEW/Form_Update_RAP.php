<style type="text/css">
	body{
		font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
	}
	.huruf12{
		font-size: 12px !important;
	}
	.huruf13{
		font-size: 13px !important;
	}
	.huruf14{
		font-size: 14px !important;
	}
	.huruf15{
		font-size: 15px !important;
	}
	.huruf16{
		font-size: 16px !important;
	}

	.marginatas5{
		margin-top: 5px !important;
	}
	.myFont{
		font-size:12px !important;
	}


</style>


<div class="content-wrapper">

	<section class="content-header">
		<h1><?=$title?></h1>
	</section>

	<section class="content">
		<a href="<?=site_url('Entry/RAPFrm/index/'.$JobNo.'/'.$Alokasi)?>" class="btn btn-md btn-danger" >BATAL</a>
		<p></p>
		<div class="box box-primary"  >
			<div class="box-header">
				
				<div class="row">
					<div class="col-sm-12">
						<div class="table-responsive">
							<form id="Form_CreateRAP" data-JobNo="<?=$JobNo?>" data-Alokasi="<?=$Alokasi?>" data-Versi="<?=$Versi?>" data-TipeRAP="<?=$TipeRAP?>"  >

								<table width="100%" border="0" class="huruf13">
									<tr>
										<td width="150px">Tipe</td>
										<td width="10px" class="text-center">:</td>
										<td width="200px">
											<select name="Tipe" id="Tipe" class="form-control input-sm" readonly <?=$btn_dimatikan?> >
												<option value="">:: Pilih salah satu ::</option>
												<option value="Header" <?=($dataRAP->Tipe=='Header') ? 'selected' : '' ?>>Header</option>
												<option value="Detail" <?=($dataRAP->Tipe=='Detail') ? 'selected' : '' ?>>Detail</option>
											</select>
										</td>
										<td style="padding-left:20px;" width="50px">Header</td>
										<td width="10px" class="text-center">:</td>
										<td width="800px">
											<select name="Header" id="Header" class="form-control input-sm select2" readonly <?=$btn_dimatikan?>>
												<option value="0" <?=($dataRAP->Header=='0') ? 'selected' : '' ?>>Top Header</option>
												<?php foreach ($getRAP_ForHeader as $row): ?>
													<option value="<?=$row->KdRAP?>" <?=($dataRAP->Header==$row->KdRAP) ? 'selected' : '' ?> ><?=$row->KdRAP?> - <?=$row->Uraian?></option>
												<?php endforeach ?>
											</select>
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" border="0" class="huruf13 marginatas5">
									<tr>
										<td width="150px">Alokasi</td>
										<td width="10px" class="text-center">:</td>
										<td width="300px">
											<select name="Alokasi" id="Alokasi" class="form-control input-sm"  disabled>
												<option value="<?=$Alokasi?>"><?=$Alokasi?> - <?=$KeteranganAlokasi?></option>
											</select>
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" border="0" class="huruf13 marginatas5">
									<tr>
										<td width="150px">Kode RAP</td>
										<td width="10px" class="text-center">:</td>
										<td width="200px">
											<input type="text" name="KdRAP" id="KdRAP" class="form-control input-sm" maxlength="10" value="<?=htmlspecialchars($dataRAP->KdRAP)?>" readonly>
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" border="0" class="huruf13 marginatas5">
									<tr>
										<td width="150px" style="vertical-align: top;">Uraian Pekerjaan</td>
										<td width="10px" class="text-center" style="vertical-align: top;">:</td>
										<td width="500px">
											<!-- <input type="text" name="Uraian" id="Uraian" class="form-control input-sm" > -->
											<textarea name="Uraian" id="Uraian" class="form-control input-sm" rows="3" maxlength="200" <?=$btn_dimatikan?>><?=htmlspecialchars($dataRAP->Uraian)?></textarea>
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" border="0" class="huruf13 marginatas5">
									<tr>
										<td width="150px">Volume</td>
										<td width="10px" class="text-center">:</td>
										<td width="200px">
											<input type="text" name="Vol" id="Vol" class="form-control input-sm" value="<?=UbahDecimal_Nol_Tiga($dataRAP->Vol)?>" maxlength="12"  <?=$btn_dimatikan?> disabled >
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" border="0" class="huruf13 marginatas5">
									<tr>
										<td width="150px">Satuan</td>
										<td width="10px" class="text-center">:</td>
										<td width="200px">
											<input type="text" name="Uom" id="Uom" class="form-control input-sm" maxlength="15" value="<?=htmlspecialchars($dataRAP->Uom)?>"  <?=$btn_dimatikan?> disabled >
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" border="0" class="huruf13 marginatas5">
									<tr>
										<td width="150px">Harga Satuan</td>
										<td width="10px" class="text-center">:</td>
										<td width="200px">
											<input type="text" name="HrgSatuan" id="HrgSatuan" class="form-control input-sm" maxlength="15" value="<?=UbahDecimal_Nol_Dua($dataRAP->HrgSatuan)?>" <?=$btn_dimatikan?> disabled >
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" border="0" class="huruf13 marginatas5 table_HrgRAB" style="display:none;">
									<tr>
										<td width="150px">Harga Satuan (RAB)</td>
										<td width="10px" class="text-center">:</td>
										<td width="200px">
											<input type="text" name="HrgRAB" id="HrgRAB" class="form-control input-sm" value="<?=UbahDecimal_Nol_Dua($dataRAP->HrgRAB)?>" maxlength="17" disabled <?=$btn_dimatikan?>>
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" border="0" class="huruf13 marginatas5 " style="display:;">
									<tr>
										<td width="150px"></td>
										<td width="10px" class="text-center"></td>
										<td width="200px">

											<input type="text" name="JobNo" id="JobNo" value="<?=$JobNo?>" style="display: none;" >
											<input type="text" name="Alokasi" id="Alokasi" value="<?=$Alokasi?>" style="display: none;">
											<input type="text" name="Versi" id="Versi" value="<?=$Versi?>" style="display: none;">
											<input type="text" name="flagAksi" id="flagAksi" value="<?=$flagAksi?>" style="display: none;">

											<input type="text" name="LedgerNo" id="LedgerNo" value="<?=$LedgerNo?>" style="display: none;">
											<input type="text" name="KdRAP_Lama" id="KdRAP_Lama" value="<?=$dataRAP->KdRAP?>" style="display: none;">

											<input type="text" name="TipeRAP" id="TipeRAP" value="<?=$TipeRAP?>" style="display: none;">


											<button type="submit" class="btn btn-primary" id="btnSimpan" <?=($btn_dimatikan=='disabled') ? 'style="display:none;"' : '' ?>>Update</button>
											<a href="<?=site_url('Entry/RAPFrm/index/'.$JobNo.'/'.$Alokasi)?>" class="btn btn-md btn-default" >BATAL</a>
										</td>
										<td></td>
									</tr>
								</table>

							</form>
						</div>
					</div>
				</div>

			</div>
			<div class="box-body" >

			</div>
			<div class="overlay loading_halaman" style="display:none;">
				<i class="fa fa-spinner fa-spin"></i>
			</div>
		</div>

		<div class="tampil_table_RAP_disini">
			
		</div>

		

	</section>
</div>

<div class="clearfix"></div>


