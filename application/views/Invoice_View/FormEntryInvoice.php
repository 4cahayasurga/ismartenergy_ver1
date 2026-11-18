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

	#table1 thead tr th{
		color:White;
		background-color:#5D7B9D;
		font-weight:bold;
		text-align: center;
		font-size: 14px !important;
		font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
	}

	.select2{
		width: 100% !important;
		

	}
	.select2-selection__choice__display{
		color:black;
	}

</style>


<div class="content-wrapper">

	<section class="content-header">
		<h1><?=$title?> (<?=$JobNo.' - '.$JobNm?>)</h1>
		<br><a href="<?=site_url('Entry/Procurement/Invoice/index/'.$JobNo)?>" class="btn btn-default btn-md">BATAL</a>
	</section>

	<section class="content">
		<?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : ''?>
		<div class="box box-primary"  >
			<div class="box-header">
				

			</div>

			<div class="box-body">
				
				<form id="myform" data-JobNo="<?=$JobNo?>" data-flag="<?=$Aksi?>" data-Aksi="<?=$Aksi?>">

					<div class="row">

						<div class="col-sm-4 ">
							<table width="100%" border="0">
								<tr>
									<td style="background-color:silver;font-weight: bold;text-align: center;">Detail KO</td>
								</tr>
							</table>

							<table width="100%" border="0" class="marginatas5">
								<tr>
									<td width="100px">No. KO</td>
									<td class="text-center" width="10px">:</td>
									<td>
										<select name="NoKO_txt" id="NoKO_txt" class="form-control input-sm select2 ">
											<option value=""  data-TglKO="" data-VendorNm="" data-TotalKO="0" >:: Pilih Salah Satu :: </option>
											<?php foreach ($ListNoKO as $row): ?>
												<option value="<?=$row->NoKO?>" data-TglKO="<?=htmlspecialchars($row->TglKO)?>" data-VendorNm="<?=htmlspecialchars($row->VendorNm)?>" data-TotalKO="<?=$row->TotalKO?>" ><?=$row->NoKO?></option>
											<?php endforeach ?>
										</select>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="marginatas5">
								<tr>
									<td width="100px">No. SJ</td>
									<td class="text-center" width="10px">:</td>
									<td>
										<select name="NoSJ" id="NoSJ" class="form-control input-sm" readonly>
											<option value="">:: Pilih Salah Satu ::</option>
										</select>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="marginatas5">
								<tr>
									<td width="100px">Tanggal KO</td>
									<td class="text-center" width="10px">:</td>
									<td>
										<input type="text" id="TglKO" class="form-control input-sm" readonly>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="marginatas5">
								<tr>
									<td width="100px">VendorNm</td>
									<td class="text-center" width="10px">:</td>
									<td>
										<input type="text" id="VendorNm" class="form-control input-sm" readonly>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="marginatas5">
								<tr>
									<td width="100px">Total KO (Rp)</td>
									<td class="text-center" width="10px">:</td>
									<td>
										<input type="text" id="TotalKO" class="form-control input-sm" value="0" readonly>
									</td>
								</tr>
							</table>
						</div>

						<div class="col-md-3">

							<table width="100%" border="0">
								<tr >
									<td style="background-color:silver;font-weight: bold;text-align: center;">Detail Invoice </td>
								</tr>
							</table>

							<table width="100%" border="0" class="marginatas5">
								<tr>
									<td width="100px">No. Invoice</td>
									<td class="text-center" width="10px">:</td>
									<td>
										<input type="text" name="InvNo" id="InvNo" class="form-control input-sm" maxlength="50" autocomplete="off" required>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="marginatas5">
								<tr>
									<td width="100px">Tgl. Invoice</td>
									<td class="text-center" width="10px">:</td>
									<td>
										<input type="date" name="InvDate" id="InvDate" class="form-control input-sm" required>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="marginatas5">
								<tr>
									<td width="100px">Jatuh Tempo</td>
									<td class="text-center" width="10px">:</td>
									<td>
										<input type="date" name="DueDate" id="DueDate" class="form-control input-sm" required>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="marginatas5">
								<tr>
									<td width="100px">No. Faktur</td>
									<td class="text-center" width="10px">:</td>
									<td>
										<input type="text" name="FPNo" id="FPNo" class="form-control input-sm" maxlength="30" autocomplete="off">
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="marginatas5">
								<tr>
									<td width="100px">Tgl Faktur Pajak</td>
									<td class="text-center" width="10px">:</td>
									<td>
										<input type="date" name="FPDate" id="FPDate" class="form-control input-sm" >
									</td>
								</tr>
							</table>

						</div>

						<div class="col-sm-3 ">

							<table width="100%" border="0">
								<tr>
									<td style="background-color:silver;font-weight: bold;text-align: center;">Lainnya</td>
								</tr>
							</table>

							<table width="100%" border="0" class="marginatas5">
								<tr>
									<td width="100px">Total (Rp)</td>
									<td class="text-center" width="10px">:</td>
									<td>
										<input type="text" name="Total" id="Total" class="form-control input-sm" autocomplete="off" value="0">
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="marginatas5">
								<tr>
									<td width="100px">PPN (Rp)</td>
									<td class="text-center" width="10px">:</td>
									<td>
										<input type="text" name="PPN" id="PPN" class="form-control input-sm" autocomplete="off" value="0">
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="marginatas5">
								<tr>
									<td width="100px">PPH (Rp)</td>
									<td class="text-center" width="10px">:</td>
									<td>
										<input type="text" name="PPH" id="PPH" class="form-control input-sm" autocomplete="off" value="0">
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="marginatas5">
								<tr>
									<td width="100px" style="vertical-align:top !important;">Keterangan</td>
									<td class="text-center" width="10px" style="vertical-align:top;">:</td>
									<td>
										<textarea name="Keterangan" id="Keterangan" class="form-control input-sm"></textarea>
									</td>
								</tr>
							</table>


						</div>

					</div>
					<hr>
					<div class="row">
						<div class="col-md-3"></div>
						<div class="col-md-3"></div>
						<div class="col-md-4">
							<table width="100%" border="0" class="marginatas5">
								<tr>
									<td style="text-align:right;">
										<a href="<?=site_url('Entry/Procurement/Invoice/index/'.$JobNo)?>" class="btn btn-default btn-md">BATAL</a>
										<input type="hidden" name="JobNo" value="<?=$JobNo?>">
										<button type="submit" class="btn btn-primary btn-md" id="btnSimpan">SIMPAN</button>
									</td>
								</tr>
							</table>
						</div>
					</div>

					
					<div class="row">
						<div class="col-md-10">
							<table width="100%" border="0" style="margin-top:10px !important;">
						<tr>
							<td align="center" bgcolor="silver" style="height:20px; font-weight:bold">L I S T&nbsp; &nbsp;B A R A N G&nbsp; </td>
						</tr>
					</table>
						</div>
					</div>
					
					<div id="listorder"></div>

					<div id="tampil_SisaInvoice"></div>

					<div class="row" style="margin-top:20px;display: none;">
						<div class="col-sm-10" class="text-right">
							<table width="100%" border="0">
								<tr>
									<td></td>
									<td width="100px">Sisa Invoice</td>
									<td class="text-center" width="10px">:</td>
									<td width="200px">
										<select name="SisaInvoice" id="SisaInvoice" class="form-control" disabled>
											<option value="">:: Pilih Salah Satu ::</option>
										</select>
									</td>
								</tr>
							</table>
						</div>
					</div>

					


				</form>
			</div>

		</div>

	</section>
</div>

<div class="clearfix"></div>

