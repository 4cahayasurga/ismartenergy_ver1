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
		<h1><?=$title?></h1>
	</section>

	<section class="content">
		<?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>

		<table width="100%" border="0" style="margin-top: 5px;margin-bottom: 5px;">
			<tr>
				<td>
					<a href="<?=site_url($Url_Batal)?>"  class="btn btn-danger btn-md" ><i class="fa fa-arrow-left"></i> BACK</a>
					
				</td>
			</tr>
		</table>

		<form id="FormPJ_Keuangan" data-Alokasi="<?=$Alokasi?>" data-Url_Batal="<?=site_url($Url_Batal)?>">
			<div class="box box-danger">
				<div class="box-header">
					<div class="row">


						<div class="col-sm-6">
							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px">No PD</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" name="NoPD" id="NoPD" class="form-control input-sm huruf12" value="<?=$rowPD->NoPD?>" disabled>
									</td>
									<td width="10px"></td>
									<td width="130px">No. PJ :</td>
									<td >
										<input type="text" name="NoPJ" id="NoPJ" class="form-control input-sm huruf12" MaxLength="50" value="<?=$rowPD->NoPJ?>" disabled>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf12">

								<tr>
									<td width="100px">Job No</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" id="JobNo_Tampil" class="form-control input-sm huruf12" value="<?=$rowPD->JobNo.' - '.$rowPD->JobNm?>" disabled>
									</td>
									<td width="10px"></td>
									<td width="130px">No. BAP/Ref Lapangan :</td>
									<td >
										<input type="text" name="NoRef" id="NoRef" class="form-control input-sm huruf12" MaxLength="50" value="<?=$rowPD->NoRef?>" disabled>
									</td>
								</tr>

								<!-- <tr>
									<td width="100px">Job No</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" id="JobNo_Tampil" class="form-control input-sm huruf12" value="<?=$rowPD->JobNo.' - '.$rowPD->JobNm?>" disabled>
									</td -->
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px">Tgl Permintaan</td>
									<td width="10px" class="text-center">:</td>
									<td width="250px">
										<input type="date" name="TglPD" id="TglPD" class="form-control input-sm huruf12" value="<?=$rowPD->TglPD?>" disabled>
									</td>
									<td width="120px" style="padding-left:20px;">
										<input type="checkbox" id="Jaminan" name="Jaminan" value="1" <?=($rowPD->Jaminan==1) ? 'checked' : '' ?> disabled>
										<label for="Jaminan" style="font-weight: normal;" class="huruf13"> PD Jaminan</label>
									</td>
									<td width="120px" hidden>
										<input type="checkbox" id="PD_PPN" name="PD_PPN" value="1" <?=($rowPD->PD_PPN==1) ? 'checked' : '' ?> disabled>
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
										<input type="text" name="Deskripsi" id="Deskripsi" class="form-control input-sm huruf12" placeholder="Isi Disini" MaxLength="200" value="<?=$rowPD->Deskripsi?>" disabled >
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px">Periode</td>
									<td width="10px" class="text-center">:</td>
									<td width="150px">
										<input type="date" name="PrdAwal" id="PrdAwal" class="form-control input-sm huruf12" placeholder="Isi Disini" value="<?=$rowPD->PrdAwal?>" disabled>
									</td>
									<td width="50px" class="text-center">s.d</td>
									<td width="150px">
										<input type="date" name="PrdAkhir" id="PrdAkhir" class="form-control input-sm huruf12" placeholder="Isi Disini" value="<?=$rowPD->PrdAkhir?>" disabled>
									</td>
									<td width="10px" class="text-center"></td>
									<td width="80px">Minggu Ke :</td>
									<td width="100px">
										<input type="text" name="Minggu" id="Minggu" class="form-control input-sm huruf12 text-right" value="<?=$rowPD->Minggu?>" disabled>
									</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px">Alokasi</td>
									<td width="10px" class="text-center">:</td>
									<td width="300px">
										<select class="form-control input-sm huruf12" id="AlokasiList" disabled>
											<option value="<?=$rowPD->Alokasi?>"><?=$rowPD->Alokasi?> - <?=$rowPD->KeteranganAlokasi?></option>

										</select>
									</td>
									<td width="10px"></td>
									<td width="100px">Tipe Form</td>
									<td></td>
									<td width="10px" class="text-center">:</td>
									<td width="300px">
										<select name="TipeForm" id="TipeForm" class="form-control input-sm huruf12" disabled>
											<option value="<?=$rowPD->TipeForm?>"><?=$rowPD->TipeForm?> - <?=$rowPD->KeteranganTipeForm?></option>

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
										<input type="checkbox" id="INV" name="INV" value="INV" <?=(CekBuktiPendukung_Kedua($rowPD->BuktiPendukung, 'INV') == TRUE) ? 'checked' : '' ?> >
										<label for="INV" style="font-weight: normal;" class="huruf12" > Invoice</label>
									</td>
									<td width="300px">
										<input type="checkbox" id="FP" name="FP" value="FP" <?=(CekBuktiPendukung_Kedua($rowPD->BuktiPendukung, 'FP') == TRUE) ? 'checked' : '' ?> >
										<label for="FP" style="font-weight: normal;" class="huruf12"> Faktur Pajak</label>
									</td>
									<td></td>
								</tr>

								<tr>
									<td width="100px"></td>
									<td width="10px" class="text-center"></td>
									<td width="220px">
										<input type="checkbox" id="SJ" name="SJ" value="SJ" <?=(CekBuktiPendukung_Kedua($rowPD->BuktiPendukung, 'SJ') == TRUE) ? 'checked' : '' ?> >
										<label for="SJ" style="font-weight: normal;" class="huruf12"> Surat Jalan/Tanda Terima Lapangan </label>
									</td>
									<td width="300px">
										<input type="checkbox" id="BAP" name="BAP" value="BAP" <?=(CekBuktiPendukung_Kedua($rowPD->BuktiPendukung, 'BAP') == TRUE) ? 'checked' : '' ?> >
										<label for="BAP" style="font-weight: normal;" class="huruf12"> Berita Acara Pembayaran</label>
									</td>
									<td></td>
								</tr>

								<tr>
									<td width="100px"></td>
									<td width="10px" class="text-center"></td>
									<td width="200px">
										<input type="checkbox" id="PO" name="PO" value="PO" <?=(CekBuktiPendukung_Kedua($rowPD->BuktiPendukung, 'PO') == TRUE) ? 'checked' : '' ?> >
										<label for="PO" style="font-weight: normal;" class="huruf12"> Copy PO </label>
									</td>
									<td width="300px">
										<input type="checkbox" id="BAOP" name="BAOP" value="BAOP" <?=(CekBuktiPendukung_Kedua($rowPD->BuktiPendukung, 'BAOP') == TRUE) ? 'checked' : '' ?> >
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
										<input type="text" name="Keterangan" id="Keterangan" class="form-control input-sm huruf12" placeholder="Isi Disini" MaxLength="50" value="<?=$rowPD->Keterangan?>" 	disabled>
									</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px">Tgl PJ</td>
									<td width="10px" class="text-center">:</td>
									<td width="50%">
										<input type="date" name="TglPJ" id="TglPJ" class="form-control input-sm huruf12" value="<?=$rowPD->TglPJ?>" required>
									</td>
									<td></td>
								</tr>
							</table>
						</div>

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
										<select name="NoKO" id="NoKO" class="form-control input-sm huruf12" disabled>
											<option value="<?=$rowPD->NoKO?>"><?=$rowPD->NoKO?></option>
										</select>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px">No.Tagihan</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" name="NoTagihan" id="NoTagihan" class="form-control input-sm huruf12" placeholder="Isi Disini" MaxLength="100" value="<?=$rowPD->NoTagihan?>" disabled>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px">ID</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" name="VendorId" id="VendorId" class="form-control input-sm huruf12" disabled>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px">Nama</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" name="Nama" id="Nama" class="form-control input-sm huruf12" placeholder="Isi Disini" MaxLength="100" value="<?=$rowPD->Nama?>" disabled>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px">Alamat</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" name="Alamat" id="Alamat" class="form-control input-sm huruf12" placeholder="Isi Disini" MaxLength="255" value="<?=$rowPD->Alamat?>" disabled>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px">Telepon</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" name="Telepon" id="Telepon" class="form-control input-sm huruf12" placeholder="Isi Disini" MaxLength="20" value="<?=$rowPD->Telepon?>" disabled>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px">NPWP</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" name="NPWP" id="NPWP" class="form-control input-sm huruf12" placeholder="Isi Disini" MaxLength="20" value="<?=$rowPD->NPWP?>" disabled>
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
										<input type="text" name="NoRek" id="NoRek" class="form-control input-sm huruf12" placeholder="Isi Disini" MaxLength="30" value="<?=$rowPD->NoRek?>" disabled>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px">Atas Nama</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" name="AtasNama" id="AtasNama" class="form-control input-sm huruf12" placeholder="Isi Disini" MaxLength="100" value="<?=$rowPD->AtasNama?>" disabled>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="margin_atas_5 huruf12">
								<tr>
									<td width="100px">Bank</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<select name="Bank" id="Bank" class="form-control input-sm huruf12 select2" disabled>
											<option value="<?=$rowPD->Bank?>"><?=$rowPD->Bank?></option>
										</select>
									</td>
								</tr>
							</table>


						</div>

						<div class="col-sm-10">
							<hr style="border:1px solid silver;">
							<table width="100%" border="0">
								<tr>
									<td>
										<a href="<?=site_url($Url_Batal)?>" class="btn btn-danger btn-md" >BATAL</a>
									</td>
									<td class="text-right">
										<button type="submit" class="btn btn-primary btn-md " id="btnUpdatePD" <?=($btn_dimatikan=='disabled') ? 'style="display:none;" disabled' : ''  ?> >UPDATE</button>
									</td>
								</tr>
							</table>
						</div>

					</div>


				</div>
			</div>

			<div class="box-body">

				<div class="row">
					<div class="col-sm-12">

						<div class="panel panel-default">
							<div class="panel-heading panel-heading-nav">
								<ul class="nav nav-tabs">
									<li role="presentation" class="active">
										<a href="#tab_1" aria-controls="tab_1" role="tab" data-toggle="tab">URAIAN PD</a>
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


													<div style="border:1px solid silver;padding-left: 5px;padding-right: 5px;display: ;" id="card_Uraian">

														<table width="100%" border="0" class="margin_atas_5" style="margin-bottom:5px;">
															<tr>
																<td>
																	<button type="button" class="btn btn-md btn-default text-bold" id="btnAddUraian" <?=($btn_dimatikan=='disabled') ? 'style="display:none;" disabled' : ''  ?>>Tambah Uraian <i class="fa fa-plus"></i></button>

																	<button type="button" class="btn btn-md btn-default text-bold" id="btnUraianSamaDenganPD" <?=($btn_dimatikan=='disabled') ? 'style="display:none;" disabled' : ''  ?>>Isi Uraian Apabila = PD </button>

																	<button type="button" class="btn btn-md btn-default text-bold" onclick="tablePJLap()" <?=($btn_dimatikan=='disabled') ? 'style="display:none;" disabled' : ''  ?>>Refresh <i class="fa fa-refresh"></i></button>
																</td>
																<td style="padding-top: 20px;"></td>
															</tr>
														</table>
														<hr>


														<div id="tampil_table">

														</div>

														<table width="100%" border="0" class="margin_atas_5" style="font-size: 14px !important;">
															<tr>
																<td></td>
																<td class="text-center" width="200px"></td>
																<th class="text-center" width="200px">Dana Tersedia Di Lapangan</th>
																<td class="text-center" width="250px">
																	<input type="text" name="DanaDiLapangan" id="DanaDiLapangan" class="form-control input-sm text-right" value="<?=number_format($rowPD->TotalPD)?>" style="font-size:16px !important;" readonly>
																</td>
																<td class="text-center" width="150px"></td>
															</tr>
														</table>



														<table width="100%" border="0" class="margin_atas_5" style="font-size: 14px !important;">
															<tr>
																<td></td>
																<td class="text-center" width="200px"></td>
																<th class="text-center" width="200px">Saldo</th>
																<td class="text-center" width="250px">
																	<input type="text" name="Saldo" id="Saldo" class="form-control input-sm text-right" value="0" style="font-size:16px !important;" readonly>
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




								</div>
							</div>

						</div>
					</div>

					<!-- </div> -->
					<div class="overlay" style="display:none;" id="loading_halaman">
						<i class="fa fa-refresh fa-spin"></i>
					</div>
				</div>
			</form>


		</section>

	</div>


	<!-- Modal -->
	<div class="modal fade" id="modalAdd">
		<div class="modal-dialog">
			<div class="modal-content">
				<!-- header-->

				<form id="formAddUraian">
					<div class="modal-header">
						<!-- <button class="close" data-dismiss="modal"><span>&times;</span></button> -->
						<h4 class="modal-title" id="myModalLabel">Data Entry</h4>
					</div>
					<!--body-->

					<div class="modal-body">


						<div class="row">
							<div class="col-sm-12">

								<table width="100%" style="display: none;">
									<tr>
										<td width="100px">Action </td>
										<td width="15px" class="text-center">:</td>
										<td width="50px">
											<input type="text" name="Action" id="Action" class="form-control input-sm" readonly>
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" >
									<tr>
										<td width="100px">No</td>
										<td width="15px" class="text-center">:</td>
										<td width="50px">
											<input type="text" name="No" id="No" class="form-control input-sm"  readonly>
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" class="margin_atas_5">
									<tr>
										<td width="100px">Kode RAP</td>
										<td width="15px" class="text-center">:</td>
										<td >
											<select name="KdRAP" id="KdRAP" class="form-control input-sm" required>
												<option value=""></option>
											</select>
										</td>
									</tr>
								</table>

								<table width="100%" class="margin_atas_5">
									<tr>
										<td width="100px">Uraian</td>
										<td width="15px" class="text-center">:</td>
										<td >
											<textarea name="Uraian" id="Uraian" class="form-control input-sm" rows="4"></textarea>
										</td>
									</tr>
								</table>

								<table width="100%" class="margin_atas_5">
									<tr>
										<td width="100px">Volume</td>
										<td width="15px" class="text-center">:</td>
										<td >
											<input type="text" name="Vol" id="Vol" class="form-control input-sm"  readonly>
										</td>
									</tr>
								</table>

								<table width="100%" class="margin_atas_5">
									<tr>
										<td width="100px">Satuan</td>
										<td width="15px" class="text-center">:</td>
										<td >
											<input type="text" name="Uom" id="Uom" class="form-control input-sm" readonly>
										</td>
									</tr>
								</table>

								<table width="100%" class="margin_atas_5">
									<tr>
										<td width="100px">Hrg Satuan (Rp)</td>
										<td width="15px" class="text-center">:</td>
										<td >
											<input type="text" name="HrgSatuan" id="HrgSatuan" class="form-control input-sm" >
										</td>
									</tr>
								</table>



							</div>
						</div>

					</div>
					<!--footer-->
					<div class="modal-footer">
						<button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-primary" id="btnSimpanUraian" <?=($btn_dimatikan=='disabled') ? 'style="display:none;" disabled' : ''  ?>>Simpan</button>
					</div>

				</div>
			</form>

		</div>
	</div>

	<div class="modal fade" id="modalUpdate">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<!-- header-->

				<form id="formUpdateUraian">
					<div class="modal-header">
						<!-- <button class="close" data-dismiss="modal"><span>&times;</span></button> -->
						<h4 class="modal-title" id="myModalLabel">Data Entry</h4>
					</div>
					<!--body-->

					<div class="modal-body">


						<div class="row">


							<input type="text" name="Action" id="Action" class="form-control input-sm hidden"  disabled>

							<div class="col-sm-6">

								<table width="100%" style="display: ;">
									<tr>
										<td width="100px" style="font-size:18px;font-weight: bold;text-decoration: underline;">Permintaan Dana </td>

									</tr>
								</table>

								

								<table width="100%" class="margin_atas_5">
									<tr>
										<td width="100px">No</td>
										<td width="15px" class="text-center">:</td>
										<td width="50px">
											<input type="text" id="No" class="form-control input-sm"  disabled>
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" class="margin_atas_5">
									<tr>
										<td width="100px">Kode RAP</td>
										<td width="15px" class="text-center">:</td>
										<td >
											<input type="text" id="KdRAP" class="form-control input-sm" disabled>
										</td>
									</tr>
								</table>

								<table width="100%" class="margin_atas_5">
									<tr>
										<td width="100px">Uraian</td>
										<td width="15px" class="text-center">:</td>
										<td >
											<textarea id="Uraian" class="form-control input-sm" rows="4" disabled></textarea>
										</td>
									</tr>
								</table>

								<table width="100%" class="margin_atas_5">
									<tr>
										<td width="100px">Volume</td>
										<td width="15px" class="text-center">:</td>
										<td >
											<input type="text"  id="Vol" class="form-control input-sm" disabled>
										</td>
									</tr>
								</table>

								<table width="100%" class="margin_atas_5">
									<tr>
										<td width="100px">Satuan</td>
										<td width="15px" class="text-center">:</td>
										<td >
											<input type="text" id="Uom" class="form-control input-sm" disabled>
										</td>
									</tr>
								</table>

								<table width="100%" class="margin_atas_5">
									<tr>
										<td width="100px">Hrg Satuan (Rp)</td>
										<td width="15px" class="text-center">:</td>
										<td >
											<input type="text"  id="HrgSatuan" class="form-control input-sm" disabled>
										</td>
									</tr>
								</table>

							</div>

							<div class="col-sm-6">

								<table width="100%" style="display: ;">
									<tr>
										<td width="100px" style="font-size:18px;font-weight: bold;text-decoration: underline;">Pertanggungjawaban Dana </td>

									</tr>
								</table>


								<table width="100%" class="margin_atas_5">
									<tr>
										<td width="100px">No</td>
										<td width="15px" class="text-center">:</td>
										<td width="50px">
											<input type="text" name="No" id="No" class="form-control input-sm"  readonly>
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" class="margin_atas_5">
									<tr>
										<td width="100px">Kode RAP</td>
										<td width="15px" class="text-center">:</td>
										<td >
											<input type="text" id="KdRAP" class="form-control input-sm" disabled>
										</td>
									</tr>
								</table>

								<table width="100%" class="margin_atas_5">
									<tr>
										<td width="100px">Uraian</td>
										<td width="15px" class="text-center">:</td>
										<td >
											<textarea name="PjUraian" id="PjUraian" class="form-control input-sm" rows="4"></textarea>
										</td>
									</tr>
								</table>

								<table width="100%" class="margin_atas_5">
									<tr>
										<td width="100px">Volume</td>
										<td width="15px" class="text-center">:</td>
										<td >
											<input type="text" name="PjVol" id="PjVol" class="form-control input-sm" value="0.000">
										</td>
									</tr>
								</table>

								<table width="100%" class="margin_atas_5">
									<tr>
										<td width="100px">Satuan</td>
										<td width="15px" class="text-center">:</td>
										<td >
											<input type="text" id="Uom" class="form-control input-sm" disabled>
										</td>
									</tr>
								</table>

								<table width="100%" class="margin_atas_5">
									<tr>
										<td width="100px">Hrg Satuan (Rp)</td>
										<td width="15px" class="text-center">:</td>
										<td >
											<input type="text" name="PjHrgSatuan" id="PjHrgSatuan" class="form-control input-sm"  value="0">
										</td>
									</tr>
								</table>

							</div>

						</div>

					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-primary" id="btnUpdateUraian" <?=($btn_dimatikan=='disabled') ? 'style="display:none;" disabled' : ''  ?>>Update</button>
					</div>

				</div>
			</form>

		</div>
	</div>


