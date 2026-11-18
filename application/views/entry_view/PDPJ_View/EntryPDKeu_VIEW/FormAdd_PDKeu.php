<style type="text/css">
	body{
		font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
	}
	div .content-wrapper{
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
	
	.margin_atas_5{
		margin-top: 5px;
	}

	.tengah_ngambang{
		vertical-align: middle;
	}

	#mytable_uraianPD tr th{
		color:White;
		background-color:#5D7B9D;
		font-weight:bold;
		text-align: center;
	}
	.select2-container {
		width: 100% !important;
		padding: 0;
	}

	#mytable_Invoice thead tr th{
		text-align: center;
	}

</style>
<div class="content-wrapper">

	<section class="content-header">
		<h1><u><?=$title?></u></h1>
	</section>

	<section class="content">
		<?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>

		<table width="100%" border="0" style="margin-top: 5px;margin-bottom: 5px;">
			<tr>
				<td><button type="button" class="btn btn-danger btn-md" onclick="history.back()"><i class="fa fa-arrow-left"></i> BACK</button></td>
			</tr>
		</table>
		<div class="box box-danger">
			<form id="FormPD_Keuangan" data-Alokasi="<?=$Alokasi?>" data-JobNo="<?=$JobNo?>" data-Aksi="<?=$Aksi?>" data-TipePD="<?=$TipePD?>">

				<div class="box-header">

					<div class="row">

						<!-- == FORM KIRI PD -->
						<div class="col-sm-6">

							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px">No PD</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" name="NoPD" id="NoPD" class="form-control input-sm huruf12" readonly>
									</td>
									<td width="10px"></td>
									<td width="130px">No. BAP/Ref Lapangan :</td>
									<td >
										<input type="text" name="NoRef" id="NoRef" class="form-control input-sm huruf12" MaxLength="50" readonly>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px">Job No</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" id="JobNo_Tampil" class="form-control input-sm huruf12" value="<?=$JobNo.' - '.$JobNm?>" readonly>
										<input type="hidden" name="JobNo" id="JobNo" class="form-control input-sm huruf12" value="<?=$JobNo?>" readonly>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px">Tgl Permintaan</td>
									<td width="10px" class="text-center">:</td>
									<td width="250px">
										<input type="date" name="TglPD" id="TglPD" class="form-control input-sm huruf12" value="<?=$TglPD?>" required>
									</td>
									<td width="120px" style="padding-left:20px;">
										<input type="checkbox" id="Jaminan" name="Jaminan" value="1">
										<label for="Jaminan" style="font-weight: normal;" class="huruf13"> PD Jaminan</label>
									</td>
									<td width="120px" >
										<input type="checkbox" id="PD_PPN" name="PD_PPN" value="1">
										<label for="PD_PPN" style="font-weight: normal;" class="huruf13"> PD PPN</label>
									</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px">Deskripsi</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" name="Deskripsi" id="Deskripsi" class="form-control input-sm huruf12" placeholder="Isi Disini" MaxLength="200" >
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px">Periode</td>
									<td width="10px" class="text-center">:</td>
									<td width="150px">
										<input type="date" name="PrdAwal" id="PrdAwal" class="form-control input-sm huruf12" placeholder="Isi Disini" value="<?=$PrdAwal?>">
									</td>
									<td width="50px" class="text-center">s.d</td>
									<td width="150px">
										<input type="date" name="PrdAkhir" id="PrdAkhir" class="form-control input-sm huruf12" placeholder="Isi Disini" value="<?=$PrdAkhir?>">
									</td>
									<td width="10px" class="text-center"></td>
									<td width="80px">Minggu Ke :</td>
									<td width="100px">
										<input type="text" name="Minggu" id="Minggu" class="form-control input-sm huruf12 text-right" value="<?=$Minggu?>">
									</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px">Alokasi</td>
									<td width="10px" class="text-center">:</td>
									<td width="300px">
										<select class="form-control input-sm huruf12" id="AlokasiList" readonly>
											<option value="">:: Pilih Salah Satu ::</option>
											<?php foreach ($ListAlokasi as $row): ?>
												<option value="<?=$row->Alokasi?>" <?=($Alokasi==$row->Alokasi) ? 'selected' : '' ?> ><?=$row->Alokasi?> - <?=$row->Keterangan?></option>
											<?php endforeach ?>
										</select>
										<input type="hidden" name="Alokasi" id="Alokasi" value="<?=$Alokasi?>" readonly>
									</td>
									<td width="10px"></td>
									<td width="100px">Tipe Form</td>
									<td></td>
									<td width="10px" class="text-center">:</td>
									<td width="300px">
										<select name="TipeForm" id="TipeForm" class="form-control input-sm huruf12" required>
											<option value="">:: Pilih Salah Satu ::</option>
											<?php foreach ($ListTipeForm as $row): ?>
												<option value="<?=$row->TipeForm?>" ><?=$row->TipeForm?> - <?=$row->Keterangan?></option>
											<?php endforeach ?>
										</select>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf13">
								<tr>
									<td width="100px"></td>
									<td width="10px" class="text-center"></td>
									<td class="text-center" height="30px" bgcolor="silver">Bukti Pendukung</td>

								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px"></td>
									<td width="10px" class="text-center"></td>
									<td width="200px">
										<input type="checkbox" id="INV" name="INV" value="INV" >
										<label for="INV" style="font-weight: normal;" class="huruf12"> Invoice</label>
									</td>
									<td width="300px">
										<input type="checkbox" id="FP" name="FP" value="FP">
										<label for="FP" style="font-weight: normal;" class="huruf12"> Faktur Pajak</label>
									</td>
									<td></td>
								</tr>

								<tr>
									<td width="100px"></td>
									<td width="10px" class="text-center"></td>
									<td width="220px">
										<input type="checkbox" id="SJ" name="SJ" value="SJ">
										<label for="SJ" style="font-weight: normal;" class="huruf12"> Surat Jalan/Tanda Terima Lapangan </label>
									</td>
									<td width="300px">
										<input type="checkbox" id="BAP" name="BAP" value="BAP">
										<label for="BAP" style="font-weight: normal;" class="huruf12"> Berita Acara Pembayaran</label>
									</td>
									<td></td>
								</tr>

								<tr>
									<td width="100px"></td>
									<td width="10px" class="text-center"></td>
									<td width="200px">
										<input type="checkbox" id="PO" name="PO" value="PO">
										<label for="PO" style="font-weight: normal;" class="huruf12"> Copy PO </label>
									</td>
									<td width="300px">
										<input type="checkbox" id="BAOP" name="BAOP" value="BAOP">
										<label for="BAOP" style="font-weight: normal;" class="huruf12"> Berita Acara Opname Pekerjaan	</label>
									</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px">Keterangan</td>
									<td width="10px" class="text-center">:</td>
									<td width="50%">
										<input type="text" name="Keterangan" id="Keterangan" class="form-control input-sm huruf12" placeholder="Isi Disini" MaxLength="50">
									</td>
									<td></td>
								</tr>
							</table>



						</div>

						<!-- == FORM KANAN PD-->
						<div class="col-sm-4">

							<table width="100%" border="0" class="margin_atas_5 huruf13">
								<tr>
									<td class="text-center" height="30px" bgcolor="silver">Bayar Kepada</td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px">No.Kontrak/PO</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<select name="NoKO" id="NoKO" class="form-control input-sm huruf12 select2">
											<option value="">:: Pilih Salah Satu ::</option>
										</select>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px">No.Tagihan</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" name="NoTagihan" id="NoTagihan" class="form-control input-sm huruf12" placeholder="Isi Disini" MaxLength="100">
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px">ID</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" name="VendorId" id="VendorId" class="form-control input-sm huruf12" readonly>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px">Nama</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" name="Nama" id="Nama" class="form-control input-sm huruf12" placeholder="Isi Disini" MaxLength="100" required>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px">Alamat</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" name="Alamat" id="Alamat" class="form-control input-sm huruf12" placeholder="Isi Disini" MaxLength="255" required>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px">Telepon</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" name="Telepon" id="Telepon" class="form-control input-sm huruf12" placeholder="Isi Disini" MaxLength="20" required>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px">NPWP</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" name="NPWP" id="NPWP" class="form-control input-sm huruf12" placeholder="Isi Disini" MaxLength="20">
									</td>
								</tr>
							</table>


							<table width="100%" border="0" class="margin_atas_5 huruf13">
								<tr>
									<td class="text-center" height="30px" bgcolor="silver">Rekening Penerima</td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px">No.Rekening</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" name="NoRek" id="NoRek" class="form-control input-sm huruf12" placeholder="Isi Disini" MaxLength="30" required>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px">Atas Nama</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" name="AtasNama" id="AtasNama" class="form-control input-sm huruf12" placeholder="Isi Disini" MaxLength="100" required>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px">Bank</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<select name="Bank" id="Bank" class="form-control input-sm huruf12 select2">
											<option value="">:: Pilih Salah Satu ::</option>
											<?php foreach ($ListBank as $row): ?>
												<option value="<?=$row->Bank?>"><?=$row->Bank?></option>
											<?php endforeach ?>
										</select>
									</td>
								</tr>
							</table>


						</div>

						<div class="col-sm-10">
							<hr style="border:1px solid silver;">
							<table width="100%" border="0">
								<tr>
									<td><button type="button" class="btn btn-danger btn-md" onclick="history.back()">BATAL</button></td>
									<td class="text-right"><button type="submit" class="btn btn-primary btn-md" id="btnSimpanPD">SIMPAN</button></td>
								</tr>
							</table>
						</div>

					</div>

				</div>

				<div class="box-body">


					<div class="row col-sm-12">

						<div class="panel panel-default">
							<div class="panel-heading panel-heading-nav">
								<ul class="nav nav-tabs">
									<li role="presentation" class="active">
										<a href="#tab_1" aria-controls="tab_1" role="tab" data-toggle="tab">URAIAN PD</a>
									</li>
									<li role="presentation" style="display: none;" class="tab_invoice">
										<a href="#tab_2" aria-controls="tab_2" role="tab" data-toggle="tab">INVOICE</a>
									</li>
									
								</ul>
							</div>
							<div class="panel-body">
								<div class="tab-content">

									<!-- -- START URAIAN PD -->
									<div role="tabpanel" class="tab-pane fade in active" id="tab_1">
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<table width="100%" border="0">
														<tr>
															<td align="center" bgcolor="silver" style="height:20px; font-weight:bold">U R A I A N&nbsp; &nbsp;P E R M I N T A A N&nbsp; &nbsp;D A N A</td>
														</tr>

													</table>


													<div style="border:1px solid silver;padding-left: 5px;padding-right: 5px;">

														<table width="100%" border="0" class="margin_atas_5">
															<tr>
																<td>
																	<button type="button" class="btn btn-md btn-success" onclick="btnTambahUraian()" id="btnAddUraian">TAMBAH URAIAN <i class="fa fa-plus"></i></button>
																	<button type="button" class="btn btn-md btn-default" onclick="get_tr_UraianPD()">REFRESH URAIAN PD <i class="fa fa-refresh"></i></button>

																	<button type="button" class="btn btn-danger btn-md" id="delete_records">HAPUS URAIAN</button>
																</td>
																<td style="padding-top: 20px;"></td>
															</tr>
														</table>


														<div id="tampil_table">

														</div>

														<table width="100%" border="0" class="margin_atas_5" style="font-size: 14px !important;">
															<tr>
																<td></td>
																<td class="text-center" width="200px"></td>
																<th class="text-center" width="200px">Sub Total</th>
																<td class="text-center" width="250px">
																	<input type="text" name="SubTotal" id="SubTotal" class="form-control input-sm text-right" value="0" style="font-size:16px !important;" readonly>
																</td>
																<td class="text-center" width="150px"></td>
															</tr>
														</table>

														<table width="100%" border="0" class="margin_atas_5" style="font-size: 14px !important;">
															<tr>
																<td></td>
																<th class="text-center" width="200px">Saldo sebelumnya dari No</th>
																<th class="text-center" width="200px" style="padding-right:10px;">
																	<select name="NoPJSebelumnya" id="NoPJSebelumnya" class="form-control input-sm" style="font-size:16px !important;">
																		<option value=""></option>
																	</select>
																</th>
																<td class="text-center" width="250px" >
																	<input type="text" name="Saldo" id="Saldo" class="form-control input-sm text-right" value="0" style="font-size:16px !important;" readonly>
																</td>
																<td class="text-center" width="150px"></td>
															</tr>
														</table>

														<table width="100%" border="0" class="margin_atas_5" style="font-size: 14px !important;">
															<tr>
																<td></td>
																<td class="text-center" width="200px"></td>
																<th class="text-center" width="200px">Total Permintaan</th>
																<td class="text-center" width="250px">
																	<input type="text" name="TotalPermintaan" id="TotalPermintaan" class="form-control input-sm text-right" value="0" style="font-size:16px !important;" readonly>
																</td>
																<td class="text-center" width="150px"></td>
															</tr>
														</table>

														<br>

													</div>



												</div>


											</div>
										</div>
									</div>
									<!-- -- END URAIAN PD -->

									<!-- -- START INVOICE -->
									<div role="tabpanel" class="tab-pane fade" id="tab_2" style="display: none;">
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<table width="100%" border="0">
														<tr>
															<td align="center" bgcolor="silver" style="height:20px; font-weight:bold">L I S T&nbsp; &nbsp;I N V O I C E&nbsp;</td>
														</tr>

													</table>

													<table width="100%" border="0" class="margin_atas_5" style="margin-bottom:10px;">
														<tr>
															<td>
																<button type="button" class="btn btn-md btn-default" onclick="get_Invoice_AddPD()">Refresh Invoice&nbsp;<i class="fa fa-refresh"></i></button>
															</td>
															<td style="padding-top: 20px;"></td>
														</tr>
													</table>

													<div style="border:1px solid silver;padding-left: 5px;padding-right: 5px;">
														<div id="tampil_Invoice">

														</div>
													</div>

												</div>
											</div>
										</div>
									</div>
									<!-- -- END INVOICE -->


								</div>
							</div>
						</div>
					</div>




				</div>
			</form>
			<div class="overlay" style="display:none;" id="loading_halaman">
				<i class="fa fa-refresh fa-spin"></i>
			</div>
		</div>

	</section>

</div>

<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Form Entry Uraian</h4>
			</div>
			<div class="modal-body">
				<form id="Form_Uraian_ADD" data-AksiUraian="NEW">
					<div class="row">
						<div class="col-sm-12">
							<table width="100%" border="0" class="margin_atas_5">
								<tr>
									<td width="150px">No</td>
									<td width="15px" class="text-center">:</td>
									<td width="400px">
										<input type="hidden" name="AksiUraian" id="AksiUraian" class="form-control input-sm huruf14" value="NEW" >
										<input type="text" name="NoUrut" id="NoUrut" class="form-control input-sm huruf14" readonly>
									</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5">
								<tr>
									<td width="150px">Alokasi</td>
									<td width="15px" class="text-center">:</td>
									<td width="400px">
										<!-- <select name="Alokasi" id="Alokasi" class="form-control input-sm select2">
											<option value="">:: Pilih Salah Satu ::</option>
										</select> -->
										<input type="text" name="Alokasi" id="Alokasi" class="form-control input-sm huruf14" readonly>
									</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5">
								<tr>
									<td width="150px">Kode RAP</td>
									<td width="15px" class="text-center">:</td>
									<td width="400px">
										<select name="KdRAP" id="KdRAP" class="form-control input-sm select2 huruf14">
											<option value="">:: Pilih Salah Satu ::</option>
										</select>
									</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5">
								<tr>
									<td width="150px">Uraian</td>
									<td width="15px" class="text-center">:</td>
									<td width="400px">
										<textarea name="Uraian" id="Uraian" class="form-control input-sm huruf14"></textarea>
									</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5">
								<tr>
									<td width="150px">Volume</td>
									<td width="15px" class="text-center">:</td>
									<td width="400px">
										<input type="text" name="Vol" id="Vol" class="form-control input-sm huruf14" value="0">
									</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5">
								<tr>
									<td width="150px">Satuan</td>
									<td width="15px" class="text-center">:</td>
									<td width="400px">
										<input type="text" name="Uom" id="Uom" class="form-control input-sm huruf14" >
									</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" class="margin_atas_5">
								<tr>
									<td width="150px">Harga Satuan (Rp)</td>
									<td width="15px" class="text-center">:</td>
									<td width="400px">
										<input type="text" name="HrgSatuan" id="HrgSatuan" class="form-control input-sm huruf14">
									</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5">
								<tr><td style="border-top:2px solid black;"></td></tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5">
								<tr>
									<td width="150px"></td>
									<td width="15px" class="text-center"></td>
									<td>
										<button type="button" class="btn btn-default huruf14" data-dismiss="modal">Batal</button>&nbsp;&nbsp;
										<button type="submit" class="btn btn-primary huruf14" >Save</button>
									</td>
								</tr>
							</table>

						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				
			</div>
		</div>

	</div>
</div>


<div id="Modal_EditUraian" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Form Update Uraian</h4>
			</div>
			<div class="modal-body">
				<form id="Form_Uraian_EDIT" data-AksiUraian="UPD" class="huruf14">
					<div class="row">
						<div class="col-sm-12">
							<table width="100%" border="0" class="margin_atas_5">
								<tr>
									<td width="150px">No</td>
									<td width="15px" class="text-center">:</td>
									<td width="400px">
										<input type="hidden" name="AksiUraian" id="AksiUraian" class="form-control input-sm huruf14" value="UPD" >
										<input type="text" name="NoUrut" id="NoUrut" class="form-control input-sm huruf14" readonly>
									</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5">
								<tr>
									<td width="150px">Alokasi</td>
									<td width="15px" class="text-center">:</td>
									<td width="400px">
										<!-- <select name="Alokasi" id="Alokasi" class="form-control input-sm select2">
											<option value="">:: Pilih Salah Satu ::</option>
										</select> -->
										<input type="text" name="Alokasi" id="Alokasi" class="form-control input-sm huruf14" readonly>
									</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5">
								<tr>
									<td width="150px">Kode RAP</td>
									<td width="15px" class="text-center">:</td>
									<td width="400px">
										<select name="KdRAP" id="KdRAP" class="form-control input-sm select2 huruf14">
											<option value="">:: Pilih Salah Satu ::</option>
										</select>
									</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5">
								<tr>
									<td width="150px">Uraian</td>
									<td width="15px" class="text-center">:</td>
									<td width="400px">
										<textarea name="Uraian" id="Uraian" class="form-control input-sm huruf14"></textarea>
									</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5">
								<tr>
									<td width="150px">Volume</td>
									<td width="15px" class="text-center">:</td>
									<td width="400px">
										<input type="text" name="Vol" id="Vol" class="form-control input-sm huruf14" value="0">
									</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5">
								<tr>
									<td width="150px">Satuan</td>
									<td width="15px" class="text-center">:</td>
									<td width="400px">
										<input type="text" name="Uom" id="Uom" class="form-control input-sm huruf14" >
									</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" class="margin_atas_5">
								<tr>
									<td width="150px">Harga Satuan (Rp)</td>
									<td width="15px" class="text-center">:</td>
									<td width="400px">
										<input type="text" name="HrgSatuan" id="HrgSatuan" class="form-control input-sm huruf14">
									</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5">
								<tr><td style="border-top:2px solid black;"></td></tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5">
								<tr>
									<td width="150px"></td>
									<td width="15px" class="text-center"></td>
									<td>
										<button type="button" class="btn btn-default huruf14" data-dismiss="modal">Batal</button>&nbsp;&nbsp;
										<button type="submit" class="btn btn-primary huruf14" >Update</button>
									</td>
								</tr>
							</table>

						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				
			</div>
		</div>

	</div>
</div>


<div class="clearfix"></div>