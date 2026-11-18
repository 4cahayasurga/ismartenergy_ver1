
<style type="text/css">
	.row_saya {
		font-size: 10px !important;
		font-family: Arial, Helvetica, sans-serif;
	}

	table td th {
		font-size: 10px !important;;
		

	}

	#my_form {
		font-size: 10px !important;
	}

	input[type="text"] {
		font-size: 10px !important;
	}

	select {
		font-size: 10px !important;
	}

	textarea {
		font-size: 10px !important;
	}

	.ukuran_select2 {
		font-size: 10px !important;
	}

	table td{
		padding-top: 5px;
		padding-bottom: 5px;
	}

	.huruf_modal{
		font-size:12px !important;
		font-family: Geneva, Verdana, 'Lucida Sans', 'Lucida Grande', 'Lucida Sans Unicode', sans-serif;
	}

	.huruf_8_modal{
		font-size:9px !important;
		font-family: Geneva, Verdana, 'Lucida Sans', 'Lucida Grande', 'Lucida Sans Unicode', sans-serif;
	}

</style>

<div class="content-wrapper">

	<section class="content-header">

		<table width="100%" border="0">
			<tr>
				<td style="font-size: 14px !important;" class="text-bold atur_ukuran"><?=(isset($judul)) ? $judul : '' ?></td>
				<td style="font-size: 12px !important;" class="text-bold text-right atur_ukuran"><?=$dataPD['JobNo'].' - '.$dataPD['JobNm'].'<br />'.$dataPD['Lokasi']?></td>
			</tr>
			<tr>
				<td>
					<a href="<?=$atur_url?>" class="btn btn-sm bg-gray"  ><i class="fa fa-arrow-left"></i> Back</a>
				</td>
				<td></td>
			</tr>
		</table>

	</section>

	<section class="content">

		<?php if ($alert_jadi_satu !=''): ?>
			<div class="row"><?=$alert_jadi_satu?></div>
		<?php endif ?>


		<?= ($this->session->flashdata('message')) ? $this->session->flashdata('message') : ''    ?>





		<div class="box box-success">

			<div class="box-body">

				

				<form id="form_saya" data-url_back="<?=$atur_url?>" data-tombol_btn="<?=$tombol_btn?>" data-khusus_inputan="<?=$khusus_inputan?>">

					<div class="row">

						<div class="col-sm-6 row_saya">

							<table width="100%" border="0" class="">
								<tr>
									<th style="width:80px;">No. PD</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" class="form-control input-sm" value="<?=$dataPD['NoPD']?>" disabled="disabled">
									</td>
									<th>No. BAP/Ref keuangan : </th>
									<td>
										<input type="text" class="form-control input-sm" placeholder="No.Ref" value="<?=$dataPD['NoRef']?>" disabled="disabled">
									</td>
								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Job No</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" class="form-control input-sm" value="<?=$dataPD['JobNo'].' - '.$dataPD['JobNm'];?>" disabled="disabled">
									</td>

								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Tgl Permintaan</th>
									<td style="width:10px;">:</td>
									<td style="width: 200px;">
										<input type="date" class="form-control input-sm" name="TglPD" id="TglPD" value="<?=$dataPD['TglPD']?>"  required <?=$khusus_inputan?> >
									</td>
									<td style="padding-left:10px;">
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="checkbox" name="Jaminan" id="Jaminan" value="1" <?=($dataPD['Jaminan'] == 1 ) ? 'checked' : ''?>  <?=$khusus_inputan?> >
											<label class="form-check-label" for="Jaminan">PD Jaminan</label>
										</div>

									</td>

								</tr>
							</table>


							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Deskripsi</th>
									<td style="width:10px;">:</td>
									<td>
										<textarea name="Deskripsi" id="Deskripsi" class="form-control input-sm" placeholder="Isi Deskripsi"  <?=$khusus_inputan?> ><?=$dataPD['Deskripsi']?></textarea>
									</td>


								</tr>
							</table>


							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Periode</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="date" name="PrdAwal" id="PrdAwal" title="Periode Awal" value="<?=$dataPD['PrdAwal']?>" class="form-control input-sm" required <?=$khusus_inputan?> >
									</td>
									<td style="width:10px !important;">s.d.</td>
									<td>
										<input type="date" name="PrdAkhir" id="PrdAkhir" title="Periode Akhir" value="<?=$dataPD['PrdAkhir']?>" class="form-control input-sm" required <?=$khusus_inputan?> >
									</td>
									<td>Minggu Ke: </td>
									<td style="width:50px !important;">
										<input type="text" name="Minggu" id="Minggu" class="form-control input-sm" value="<?=$dataPD['Minggu']?>" <?=$khusus_inputan?> >
									</td>
								</tr>
							</table>


							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Alokasi</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" class="form-control input-sm" value="<?=$dataPD['Alokasi'].' - '.$dataPD['KeteranganAlokasi']?>" disabled="disabled" >
										
									</td>

									<td style="width:70px;">Tipe Form: </td>
									<td>
										<input type="text" class="form-control input-sm" value="<?=$dataPD['TipeForm'].' - '.$dataPD['KeteraganTipeForm']?>" disabled="disabled" >
									</td>

								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;"></th>
									<td style="width:10px;">&nbsp;&nbsp;</td>
									<td style="font-size:10px !important;text-align: center;background-color:#3498db;color:white;">
										BUKTI PENDUKUNG
									</td>
								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;"></th>
									<td style="width:10px;">&nbsp;</td>
									<td>
										<div class="form-check form-check-inline" style="font-size: 10px;">
											<input class="form-check-input" type="checkbox" id="INV" value="INV" name="BuktiPendukung[]" <?= (CekBuktiPendukung($dataPD['BuktiPendukung'], 'INV') == TRUE) ? 'checked' : '' ?>  <?=$khusus_inputan?>  >
											<label class="form-check-label" for="INV">Invoice/Kwitansi</label>
										</div><br>

										<div class="form-check form-check-inline" style="font-size: 10px;">
											<input class="form-check-input" type="checkbox" id="SJ" value="SJ" name="BuktiPendukung[]" <?= (CekBuktiPendukung($dataPD['BuktiPendukung'], 'SJ') == TRUE) ? 'checked' : '' ?> <?=$khusus_inputan?> >
											<label class="form-check-label" for="SJ">Surat Jalan/Tanda Terima keuangan</label>
										</div><br>

										<div class="form-check form-check-inline" style="font-size: 10px;">
											<input class="form-check-input" type="checkbox" id="PO" value="PO" name="BuktiPendukung[]" <?= (CekBuktiPendukung($dataPD['BuktiPendukung'], 'PO') == TRUE) ? 'checked' : '' ?>  <?=$khusus_inputan?> >
											<label class="form-check-label" for="PO">Copy PO</label>
										</div>


									</td>
									<td>
										<div class="form-check form-check-inline" style="font-size: 10px;">
											<input class="form-check-input" type="checkbox" id="FP" value="FP" name="BuktiPendukung[]" <?= (CekBuktiPendukung($dataPD['BuktiPendukung'], 'FP') == TRUE) ? 'checked' : '' ?> <?=$khusus_inputan?>  >
											<label class="form-check-label" for="FP">Faktur Pajak</label>
										</div><br>

										<div class="form-check form-check-inline" style="font-size: 10px;">
											<input class="form-check-input" type="checkbox" id="BAP" value="BAP" name="BuktiPendukung[]" <?= (CekBuktiPendukung($dataPD['BuktiPendukung'], 'BAP') == TRUE) ? 'checked' : '' ?> <?=$khusus_inputan?> >
											<label class="form-check-label" for="BAP">Berita Acara Pembayaran</label>
										</div><br>

										<div class="form-check form-check-inline" style="font-size: 10px;">
											<input class="form-check-input" type="checkbox" id="BAOP" value="BAOP" name="BuktiPendukung[]" <?= (CekBuktiPendukung($dataPD['BuktiPendukung'], 'BAOP') == TRUE) ? 'checked' : '' ?> <?=$khusus_inputan?> >
											<label class="form-check-label" for="BAOP">Berita Acara Opname Pekerjaan</label>
										</div>
									</td>
								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Keterangan</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="Keterangan" id="Keterangan" placeholder="Keterangan" class="form-control input-sm" value="<?=$dataPD['Keterangan']?>"<?=$khusus_inputan?> >

									</td>
								</tr>
							</table>



						</div>

						<div class="col-sm-6 row_saya">

							<table width="100%" border="0">
								<tr>
									<td style="font-size:10px !important;text-align: center;background-color:#e74c3c;color:white;">
										BAYAR KEPADA
									</td>
								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">No. Kontrak/PO</th>
									<td style="width:10px;">:</td>
									<td class="atur_no_ko">
										<input type="text" class="form-control input-sm" value="<?=$dataPD['NoKO']?>" disabled="disabled">
									</td>

								</tr>
							</table>


							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">No. Tagihan</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="NoTagihan" id="NoTagihan" class="form-control input-sm" placeholder="No. Tagihan" value="<?=$dataPD['NoTagihan']?>" <?=$khusus_inputan?> >
									</td>

								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">ID</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="VendorId" id="VendorId" class="form-control input-sm" placeholder="ID" disabled="disabled">
									</td>

								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Nama</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="Nama" id="Nama" class="form-control input-sm" placeholder="Nama" value="<?=$dataPD['Nama']?>"  <?=$khusus_inputan?> >
									</td>

								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Alamat</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="Alamat" id="Alamat" class="form-control input-sm" placeholder="Alamat" value="<?=$dataPD['Alamat']?>" <?=$khusus_inputan?> >
									</td>

								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Telepon</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="Telepon" id="Telepon" class="form-control input-sm" placeholder="Telepon" value="<?=$dataPD['Telepon']?>" <?=$khusus_inputan?> >
									</td>

								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">NPWP</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="NPWP" id="NPWP" class="form-control input-sm" placeholder="NPWP" value="<?=$dataPD['NPWP']?>" <?=$khusus_inputan?> >
									</td>

								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<td style="font-size:11px !important;text-align: center;background-color:#27ae60;color:white;">
										REKENING PENERIMA
									</td>
								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">No. Rekening</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="NoRek" id="NoRek" class="form-control input-sm" placeholder="No. Rekening" value="<?=$dataPD['NoRek']?>" <?=$khusus_inputan?> >
									</td>
								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Atas Nama</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="AtasNama" id="AtasNama" class="form-control input-sm" placeholder="Atas Nama" value="<?=$dataPD['AtasNama']?>" <?=$khusus_inputan?> >
									</td>
								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Bank</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" id="Bank" name="Bank" class="form-control input-sm" value="<?=$dataPD['Bank']?>" placeholder="Bank" <?=$khusus_inputan?> >

									</td>
								</tr>
							</table>


						</div>

						<div class="col-sm-12">
							<hr style="border-top:1px solid black;">

							<input type="hidden" name="NoPD" id="NoPD" value="<?= $dataPD['NoPD'] ?>">
							<input type="hidden" name="JobNo" id="JobNo" value="<?= $dataPD['JobNo'] ?>">
							<input type="hidden" name="Alokasi" id="Alokasi" value="<?= $dataPD['Alokasi'] ?>">
							<input type="hidden" name="LedgerNo" id="LedgerNo" value="<?= $dataPD['LedgerNo'] ?>">
							
							<input type="hidden" id="cek_statusPD" value="<?=($tombol_btn == 'hidden') ? 1 : 0; ?>">

							<button type="submit" id="btn-simpan-pd" class="btn bg-purple btn-md pull-right <?=$tombol_btn?>">UPDATE</button>
							<!-- <button class="btn btn-xs <?=$tombol_btn?>" style="background-color:#7f8c8d;display:none;" onclick="return false;"><i class="fa fa-arrow-left"></i> Back</button> -->
							<!-- <button class="btn btn-md bg-gray" onclick="history.back()" <?=$tombol_btn?> ><i class="fa fa-arrow-left"></i> Back</button> -->
							<a href="<?=$atur_url?>" class="btn btn-sm bg-gray"  ><i class="fa fa-arrow-left"></i> Back</a>

						</div>


					</div>

				</form>	

			</div>

			<div class="box-footer tampil_table">

				<div class="col-12">

					<style>
						.table_pddtl th {
							font-size: 10px !important;
						}
					</style>

					<a href="javascript:void(0)" class="btn btn-sm <?=$tombol_btn?>" style="background-color:#3498db;color:white;" onclick="tambah_uraian()" >Tambah Uraian <i class="fa fa-plus"></i></a>
					
					<a href="javascript:void(0)" class="btn btn-sm <?=$tombol_btn?>" style="background-color:#c0392b;color:white;" id="btn-hapus-uraian" >Hapus Uraian <i class="fa fa-trash"></i></a>

					<a href="javascript:void(0)" class="btn btn-sm <?=$tombol_btn?>" style="background-color:#7f8c8d;color:white;" onclick="dapat_PdDtl_sementara_EditPD()" >Refresh Table Uraian <i class="fa fa-refresh"></i></a>
					<?php
					$atur_tombol_invoice = ($tombol_btn == 'hidden') ? 1 : 0;
					?>

					<button type="button" class="btn btn-sm btn-success" id="lihat_invoice" data-NoKO="<?=$dataPD['NoKO']?>" data-TipeForm="<?=$dataPD['TipeForm']?>" data-tombol-invoice="<?=$atur_tombol_invoice?>" >Lihat Invoice</button>
					<button class="btn btn-sm btn-danger huruf_ukuran disabled" id="btn-loading-inv" style="display: none;">Loading... <i class="fa fa-spinner fa-spin"></i> </button>
					

					
					
					<div class="table-responsive" style="padding-top:10px;" id="isitable">

					</div>

				</div>

			</div>

			<div class="overlay loadingku" style="display: none;">
				<i class="fa fa-refresh fa-spin"></i>
			</div>


		</div>


	</section>

</div>

<div class="modal fade" id="modal_uraian">
	<div class="modal-dialog modal-xs" id="modal_dialog_uraian">
		<div class="modal-content">
			
			<div class="modal-body" id="modal_body_uraian">

				<div class="table-responsive">
					<table style="border-collapse: collapse;width: 100%;">
						<tr>
							<th class="huruf_modal">ENTRY URAIAN PERMINTAAN DANA</th>
							<td class="text-right huruf_modal"><?= $dataPD['JobNo'] ?> - <?= $dataPD['JobNm'] ?></td>
						</tr>
						<tr>
							<td colspan="2" style="border-top:1px solid;"></td>
						</tr>
						
					</table>
				</div>

				<form id="form_tambah_uraian">
					<div class="table-responsive">


						<table width="100%" border="0" class="huruf_8_modal">
							<tr>
								<th class="huruf_8_modal" style="width: 15%;">No.</th>
								<td class="huruf_8_modal" width="2%" class="text-center" style="text-align: center;" >:</td>
								<td width="10%" class="huruf_8_modal" >
									<input type="text" class="form-control input-sm huruf_8_modal" disabled="disabled">
								</td>
								<td></td>
							</tr>
						</table>

						<table width="100%" border="0" class="huruf_8_modal">
							<tr>
								<th class="huruf_8_modal" style="width: 15%;">Alokasi</th>
								<td class="huruf_8_modal" width="2%" class="text-center" style="text-align: center;" >:</td>
								<td class="huruf_8_modal" >
									
									<select class="form-control input-sm select2 huruf_8_modal" disabled="disabled" required>
										<option value="">-- Pilih Salah Satu --</option>
										<?php
										foreach (list_job_alokasi() as $data_alokasi) :
											$pilih = ($data_alokasi['Alokasi'] == $dataPD['Alokasi']) ? 'selected' : '';
											echo "<option value='" . $data_alokasi['Alokasi'] . "' " . $pilih . "  >" . $data_alokasi['AlokasiGabung'] . "</option>";
										endforeach;
										?>

									</select>
								</td>
							</tr>
						</table>

						<table width="100%" border="0" class="huruf_8_modal">
							<tr>
								<th class="huruf_8_modal" style="width: 15%;">Kode. RAP</th>
								<td class="huruf_8_modal" width="2%" class="text-center" style="text-align: center;" >:</td>
								<td class="huruf_8_modal" >
									<select class="form-control input-sm select2 huruf_8_modal" name="KdRAP" id="tambah_KdRAP" required>
										<option value="">-- Pilih Salah Satu --</option>
										<?php
										foreach (list_kode_rap($dataPD['JobNo'], $dataPD['Alokasi']) as $data_rap) :

											echo "<option value='" . $data_rap['KdRAP'] . "'   >" . $data_rap['KdRAP'] . " - " . $data_rap['Uraian'] . "</option>";
										endforeach;
										?>
									</select>
									
								</td>
							</tr>
						</table>

						<table width="100%" border="0" class="huruf_8_modal">
							<tr>
								<th class="huruf_8_modal" style="width: 15%;">Uraian</th>
								<td class="huruf_8_modal" width="2%" class="text-center" style="text-align: center;" >:</td>
								<td class="huruf_8_modal" >
									<textarea class="form-control input-sm huruf_8_modal" name="Uraian" id="tambah_Uraian" placeholder="Isi Uraian" required></textarea>
								</td>
							</tr>
						</table>

						<table width="100%" border="0" class="huruf_8_modal">
							<tr>
								<th class="huruf_8_modal" style="width: 15%;">Vol</th>
								<td class="huruf_8_modal" width="2%" class="text-center" style="text-align: center;" >:</td>
								<td class="huruf_8_modal" >								
									<!-- <input type="text" class="form-control input-sm" name="Vol" id="edit_Vol" value="0" placeholder="Isi Harga Volume" autocomplete="off" required> -->

									<input type="text" name="Vol" id="tambah_Vol" class="form-control input-sm huruf_8_modal" placeholder="1" value="1" autocomplete="off" required >

									<span class="text-red" style="font-size:9px !important;">*gunakan titik (.) untuk decimal di Harga Satuan & Vol </span>
								</td>
							</tr>
						</table>


						<table width="100%" border="0" class="huruf_8_modal">
							<tr>
								<th class="huruf_8_modal" style="width: 15%;">Satuan</th>
								<td class="huruf_8_modal" width="2%" class="text-center" style="text-align: center;" >:</td>
								<td class="huruf_8_modal" >
									<input type="text" class="form-control input-sm huruf_8_modal" name="Uom" id="tambah_Uom" placeholder="Isi Satuan" autocomplete="off" required>
								</td>
							</tr>
						</table>

						<table width="100%" border="0" class="huruf_8_modal">
							<tr>
								<th class="huruf_8_modal" style="width: 15%;">Harga Satuan</th>
								<td class="huruf_8_modal" width="2%" class="text-center" style="text-align: center;" >:</td>
								<td class="huruf_8_modal" >
									<!-- <input type="text" class="form-control input-sm uang" name="HrgSatuan" id="edit_HrgSatuan" value="0" placeholder="Isi Harga Satuan" autocomplete="off" required> -->
									<input type="text" name="HrgSatuan" id="tambah_HrgSatuan" class="form-control input-sm huruf_8_modal" placeholder="0" autocomplete="off" required>
									<span class="text-red" style="font-size:9px !important;">*gunakan titik (.) untuk decimal di Harga Satuan & Vol </span>
								</td>
							</tr>
						</table>

						<table width="100%" border="0" class="huruf_8_modal">
							<tr>
								<th class="huruf_8_modal" style="width: 15%;"></th>
								<td class="huruf_8_modal" width="2%" class="text-center" style="text-align: center;" ></td>
								<td class="huruf_8_modal" >
									
									<input type="hidden" name="JobNo" value="<?= $dataPD['JobNo'] ?>">
									<input type="hidden" name="Alokasi" value="<?= $dataPD['Alokasi'] ?>">
									<input type="hidden" name="NoPD" value="<?= $dataPD['NoPD'] ?>">

									<button type="button" id="loading_edit_modal" class="btn btn-sm btn-danger" disabled="disabled" style="display:none;"><i class="fa fa-spinner fa-spin"></i></button>
									<button type="submit" id="tambah_save" class="btn btn-sm btn-info">SIMPAN</button>
									<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">BATAL</button>
								</td>
							</tr>
						</table>


					</div>
				</form>

				
				


			</div>
		</div>

	</div>

</div>

<div class="modal fade" id="modal_uraian_edit">
	<div class="modal-dialog modal-xs" id="modal_dialog_uraian_edit">
		<div class="modal-content">
			
			<div class="modal-body" id="modal_body_uraian_edit">

				<div class="table-responsive">
					<table style="border-collapse: collapse;width: 100%;">
						<tr>
							<th class="huruf_modal">EDIT URAIAN PERMINTAAN DANA</th>
							<td class="text-right huruf_modal"><?= $dataPD['JobNo'] ?> - <?= $dataPD['JobNm'] ?></td>
						</tr>
						<tr>
							<td colspan="2" style="border-top:1px solid;"></td>
						</tr>
						
					</table>
				</div>

				<form id="form_tambah_uraian_edit">
					<div class="table-responsive">
						<table width="100%" border="0" class="huruf_8_modal">
							<tr>
								<th class="huruf_8_modal" style="width: 15%;">No.</th>
								<td class="huruf_8_modal" width="2%" class="text-center" style="text-align: center;" >:</td>
								<td width="10%" class="huruf_8_modal" >
									<input type="text" class="form-control input-sm" disabled>
								</td>
								<td></td>
							</tr>
						</table>

						<table width="100%" border="0" class="huruf_8_modal">
							<tr>
								<th class="huruf_8_modal" style="width: 15%;">Alokasi</th>
								<td class="huruf_8_modal" width="2%" class="text-center" style="text-align: center;" >:</td>
								<td class="huruf_8_modal" >
									<select class="form-control input-sm select2" disabled="disabled" required>
										<option value="">-- Pilih Salah Satu --</option>

										<?php
										foreach (list_job_alokasi() as $data_alokasi) :
											$pilih = ($data_alokasi['Alokasi'] == $dataPD['Alokasi']) ? 'selected' : '';
											echo "<option value='" . $data_alokasi['Alokasi'] . "' " . $pilih . "  >" . $data_alokasi['AlokasiGabung'] . "</option>";
										endforeach;
										?>

									</select>
								</td>
							</tr>
						</table>

						<table width="100%" border="0" class="huruf_8_modal">
							<tr>
								<th class="huruf_8_modal" style="width: 15%;">Kode. RAP</th>
								<td class="huruf_8_modal" width="2%" class="text-center" style="text-align: center;" >:</td>
								<td class="huruf_8_modal" >
									<select class="form-control input-sm select2 huruf_8_modal" name="KdRAP" id="edit_KdRAP" required>
										<option value="">-- Pilih Salah Satu --</option>
										<?php
										foreach (list_kode_rap($dataPD['JobNo'], $dataPD['Alokasi']) as $data_rap) :

											echo "<option value='" . $data_rap['KdRAP'] . "'   >" . $data_rap['KdRAP'] . " - " . $data_rap['Uraian'] . "</option>";
										endforeach;
										?>
									</select>
								</td>
							</tr>
						</table>

						<table width="100%" border="0" class="huruf_8_modal">
							<tr>
								<th class="huruf_8_modal" style="width: 15%;">Uraian</th>
								<td class="huruf_8_modal" width="2%" class="text-center" style="text-align: center;" >:</td>
								<td class="huruf_8_modal" >
									<textarea class="form-control input-sm huruf_8_modal" name="Uraian" id="edit_Uraian" placeholder="Isi Uraian" required></textarea>
								</td>
							</tr>
						</table>

						<table width="100%" border="0" class="huruf_8_modal">
							<tr>
								<th class="huruf_8_modal" style="width: 15%;">Vol</th>
								<td class="huruf_8_modal" width="2%" class="text-center" style="text-align: center;" >:</td>
								<td class="huruf_8_modal" >								
									<!-- <input type="text" class="form-control input-sm" name="Vol" id="edit_Vol" value="0" placeholder="Isi Harga Volume" autocomplete="off" required> -->
									<input type="text" class="form-control input-sm huruf_8_modal" name="Vol" id="edit_Vol" value="0" placeholder="Isi Harga Volume" autocomplete="off" required>

									<span class="text-red" style="font-size:9px !important;">*gunakan titik (.) untuk decimal di Harga Satuan & Vol </span>
								</td>
							</tr>
						</table>


						<table width="100%" border="0" class="huruf_8_modal">
							<tr>
								<th class="huruf_8_modal" style="width: 15%;">Satuan</th>
								<td class="huruf_8_modal" width="2%" class="text-center" style="text-align: center;" >:</td>
								<td class="huruf_8_modal" >
									<!-- <input type="text" class="form-control input-sm" name="Uom" id="edit_Uom" placeholder="Isi Satuan" autocomplete="off" required> -->
									<input type="text" class="form-control input-sm huruf_8_modal" name="Uom" id="edit_Uom" placeholder="Isi Satuan" autocomplete="off" required>
								</td>
							</tr>
						</table>

						<table width="100%" border="0" class="huruf_8_modal">
							<tr>
								<th class="huruf_8_modal" style="width: 15%;">Harga Satuan</th>
								<td class="huruf_8_modal" width="2%" class="text-center" style="text-align: center;" >:</td>
								<td class="huruf_8_modal" >
									<input type="text" class="form-control input-sm uang huruf_8_modal" name="HrgSatuan" id="edit_HrgSatuan" value="0" placeholder="Isi Harga Satuan" autocomplete="off" required>
									<span class="text-red" style="font-size:9px !important;">*gunakan titik (.) untuk decimal di Harga Satuan & Vol </span>
								</td>
							</tr>
						</table>

						<table width="100%" border="0" class="huruf_8_modal">
							<tr>
								<th class="huruf_8_modal" style="width: 15%;"></th>
								<td class="huruf_8_modal" width="2%" class="text-center" style="text-align: center;" ></td>
								<td class="huruf_8_modal" >

									<input type="hidden" name="JobNo" value="<?= $dataPD['JobNo'] ?>">
									<input type="hidden" name="Alokasi" value="<?= $dataPD['Alokasi'] ?>">
									<input type="hidden" name="NoPD" id="nopd_edit">
									<input type="hidden" name="NoUrut" id="norut_edit">

									<button type="button" id="loading_edit_modal" class="btn btn-sm btn-danger" disabled="disabled" style="display:none;"><i class="fa fa-spinner fa-spin"></i></button>
									<button type="submit" id="tambah_save" class="btn btn-sm btn-info">UPDATE</button>
									<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">BATAL</button>
								</td>
							</tr>
						</table>
					</div>
				</form>



			</div>
		</div>

	</div>

</div>

<div class="modal fade" id="modal-inv" style="font-size: 10px;">
	<div class="modal-dialog modal-lg" id="modal-dialog-inv">
		<div class="modal-content">
			
			<div class="modal-body" id="modal-body-inv">
				
				


			</div>
		</div>

	</div>

</div>

<script type="text/javascript">
	//khusus invoice
	$(document).ready(function(){

		$('#lihat_invoice').click(function(){
			lihat_invoice();
		});

		function lihat_invoice(){

			var NoPD = $('#NoPD').val();
			var JobNo = $('#JobNo').val();
			var Alokasi = $('#Alokasi').val();
			
			var NoKO = ($('#lihat_invoice').attr('data-NoKO')) ? $('#lihat_invoice').attr('data-NoKO') : 'kosong'; 
			var TipeForm = ($('#lihat_invoice').attr('data-TipeForm')) ? $('#lihat_invoice').attr('data-TipeForm') : 'kosong';
			var tombol_invoice = $('#lihat_invoice').attr('data-tombol-invoice');

			// alert(tombol_invoice+' -'+JobNo+' -'+JobNo+' -'+Alokasi+' -'+NoKO+' -'+TipeForm);
			// return false;

			jQuery.ajax({
				url: '<?= site_url('Cadangan_PermintaanDanaLapangan/lihat_invoice') ?>',
				type: 'POST',
				dataType: 'HTML',
				data: {
					'JobNo': JobNo,
					'Alokasi' : Alokasi,
					'NoPD': NoPD,
					'NoKO' : NoKO,
					'TipeForm' : TipeForm,
					'tombol_invoice' : tombol_invoice

				},
				async: true,
				cache : false,
				beforeSend : function(){
					$('#btn-loading-inv').css('display','');
					$('#lihat_invoice').css('display','none');

					$('#lihat_invoice').attr('disabled',true);

					$('#modal-body-inv').html('');
				},
				complete: function(xhr, textStatus) {
					$('#btn-loading-inv').css('display','none');
					$('#lihat_invoice').css('display','');

					$('#lihat_invoice').attr('disabled',false);
					$('#modal-inv').modal('show');
				},
				success: function(data, textStatus, xhr) {
					$('#modal-body-inv').html(data);

					if (data == 'error') {
						$.toast({
							text: 'Terjadi Kesalahan',
							position: 'top-right',
							stack: false,
							hideAfter: 3000,
							icon: 'error',

						});
						return false;
					}

				},
				error: function(xhr, textStatus, errorThrown) {

					$.toast({
						text: 'Terjadi Kesalahan',
						position: 'top-right',
						stack: false,
						hideAfter: 3000,
						icon: 'error',

					});
					return false;
				}
			});

		}

	});
</script>


<script type="text/javascript">

	$(document).ready(function(){

		PindahData_PdDtl_ke_PdDtl_sementara_EditPD();

		$('.select2').select2({
			'theme': 'classic',
			'width': '100%',

		});


		$('#form_saya').submit(function(e) {
			e.preventDefault();


			var JobNo = $('#JobNo').val();
			var SubTotal = $('#SubTotal').val();
			var Saldo = $('#Saldo').val();
			var TotalPermintaan = $('#SubTotal').val();
			var Alokasi = $('#Alokasi').val();

			var NoKO = ($('#lihat_invoice').attr('data-NoKO')) ? $('#lihat_invoice').attr('data-NoKO') : 'kosong'; 
			var NoPD = $('#NoPD').val();
			var NoPJSebelumnya = $('#pilih_NoPJSebelumnya').val();

			// alert(NoPJSebelumnya);
			// return false;


			var url_back = $(this).attr('data-url_back');
			// alert(url_back);
			// return false;


			var data = $(this).serializeArray();
			data.push({name: 'JobNo', value: JobNo});
			data.push({name: 'SubTotal', value: SubTotal});
			data.push({name: 'Saldo', value: Saldo});
			data.push({name: 'TotalPermintaan', value: TotalPermintaan});
			data.push({name: 'Alokasi', value: Alokasi});

			data.push({name: 'NoKO', value: NoKO});
			data.push({name: 'NoPD', value: NoPD});
			data.push({name: 'NoPJSebelumnya', value: NoPJSebelumnya});

			jQuery.ajax({
				url: '<?=site_url('Permintaan_dana_lapangan/AksiSimpanUraian_EditPD')?>',
				type: 'POST',
				dataType: 'HTML',
				data : data,
				async: true,
				cache : false,
				beforeSend : function(){
					$('.loadingku').show();
				},
				complete: function(xhr, textStatus) {
					$('.loadingku').hide();
				},
				success: function(data, textStatus, xhr) {
					// alert(data);
					// return false;
					// console.log(data);
					// return false;
					
					if (data == 'pd_detail_kosong') {

						$.toast({
							text: 'isi uraian terlebih dahulu',
							position: 'top-right',
							stack: false,
							hideAfter: 3000,
							icon: 'info',

						});
						return false;
					}

					window.location.href=url_back;
					return false;

				},
				error: function(xhr, textStatus, errorThrown) {
					$.toast({
						text: 'Terjadi Kesalahan',
						position: 'bottom-right',
						stack: false,
						hideAfter: 1000,
						icon: 'error',

					});
					return false;
				}

			});
			

		})


		$('#form_tambah_uraian').submit(function(e) {
			e.preventDefault();

			mydata = $(this).serialize();
			$.ajax({
				type: "post",
				url: "<?= site_url('Permintaan_dana_lapangan/AksiSimpanPdDtl_EditPD') ?>",
				data: mydata,
				async: true,
				cache : false,
				dataType: "html",
				beforeSend: function() {
					// $('.loading_uraian_tambah').show();
					$('#loading_edit_modal').show();
					$('#tambah_save').hide();
				},
				complete : function(){
					$('#loading_edit_modal').hide();
					$('#tambah_save').show();
					
					// $('.loading_uraian_tambah').hide();

					$('#modal_uraian').modal('hide');
					dapat_PdDtl_sementara_EditPD();
				},
				success: function(data) {

					// alert(data);
					// return false;

					if (data == 'berhasil') {
						$.toast({
							text: 'berhasil menambahkan data',
							position: 'bottom-right',
							stack: false,
							hideAfter: 1000,
							icon: 'success',

						})
					}

					if (data == 'gagal') {
						$.toast({
							text: 'gagal menambahkan data',
							position: 'bottom-right',
							stack: false,
							hideAfter: 1000,
							icon: 'error',

						})
					}

				},
				error: function() {
					$.toast({
						text: 'Terjadi Kesalahan',
						position: 'bottom-right',
						stack: false,
						hideAfter: 1000,
						icon: 'error',

					})
				}
			});

		});

		$('#form_tambah_uraian_edit').submit(function(e) {
			e.preventDefault();

			jQuery.ajax({
				url: '<?= site_url('Permintaan_dana_lapangan/AksiEditUraian_EditPD') ?>',
				type: 'POST',
				dataType: 'html',
				data: $(this).serialize(),
				async: true,
				cache : false,
				beforeSend: function() {
					// $('.loading_uraian_edit').show();

					$('#loading_edit_modal').show();
					$('#tambah_save').hide();
				},
				complete: function(xhr, textStatus) {
					$('#loading_edit_modal').hide();
					$('#tambah_save').show();

					// $('.loading_uraian_edit').hide();

					$('#modal_uraian_edit').modal('hide');
					dapat_PdDtl_sementara_EditPD();
				},
				success: function(data, textStatus, xhr) {
					if (data == 'berhasil') {
						$.toast({
							text: 'berhasil',
							position: 'bottom-right',
							stack: false,
							hideAfter: 1000,
							icon: 'success',
						});
						return false;
					}

					if (data == 'gagal') {
						$.toast({
							text: 'data gagal diubah',
							position: 'bottom-right',
							stack: false,
							hideAfter: 1000,
							icon: 'error',

						});
						return false;
					}
				},
				error: function(xhr, textStatus, errorThrown) {
					$.toast({
						text: 'Terjadi Kesalahan',
						position: 'bottom-right',
						stack: false,
						hideAfter: 1000,
						icon: 'error',
					});
					return false;
				}
			});
			


		});

		$('#tambah_Vol').keyup(function() {
			// hitung();
		})

		$('#tambah_HrgSatuan').keyup(function() {
			// hitung();
		})

		$('#edit_Vol').keyup(function() {
			// hitung_edit();
		})

		$('#edit_HrgSatuan').keyup(function() {
			// hitung_edit();
		});

		$('#btn-hapus-uraian').click(function(){

			var allVals = [];
			$(".check-item:checked").each(function() {

				allVals.push($(this).attr('data-NoUrut'));

			});

			if (allVals.length <= 0) {
				Swal.fire('pilih uraian yang ingin di hapus terlebih dahulu');
			} else {
				Swal.fire({
					title: 'Anda Yakin?',
					icon: 'info',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Ya, Lakukan!',
					cancelButtonText: 'Batal'
				}).then((result) => {

					var NoPD = $('#NoPD').val();

					jQuery.ajax({
						url: '<?=site_url('Permintaan_dana_lapangan/HapusBanyakUraian_EditPD')?>',
						type: 'POST',
						dataType: 'HTML',
						data: {
							'NoUrut' : allVals,
							'NoPD' : NoPD
						},
						beforeSend : function(){
							$('.loadingku').show();
						},
						complete: function(xhr, textStatus) {
							$('.loadingku').hide();
							dapat_PdDtl_sementara_EditPD();
						},
						success: function(data, textStatus, xhr) {
							// alert(data);
							if (data == 'berhasil') {
								$.toast({
									text: 'data telah dihapus',
									position: 'bottom-right',
									stack: false,
									hideAfter: 1000,
									icon: 'success',

								});
								return false;
							}

							if (data == 'gagal') {
								$.toast({
									text: 'data gagal dihapus',
									position: 'bottom-right',
									stack: false,
									hideAfter: 1000,
									icon: 'error',

								})
							}
							return false;
						},
						error: function(xhr, textStatus, errorThrown) {
							$.toast({
								text: 'Terjadi Kesalahan',
								position: 'bottom-right',
								stack: false,
								hideAfter: 1000,
								icon: 'error',

							});
							return false;
						}
					});


				})

				return false;

			}

		});

		$('#TglPD').change(function(){
			HitungMinggu_PrdAawl_dan_PrdAkhir();
		});

	});

function HitungMinggu_PrdAawl_dan_PrdAkhir(TglPD=''){
	if (TglPD == '') {
		TglPD = $('#TglPD').val();
	}

	var JobNo = $('#JobNo').val();

	jQuery.ajax({
		url: '<?= site_url('Permintaan_dana_lapangan/dapat_data_ketika_TglPD_berubah_js') ?>',
		type: 'POST',
		dataType: 'JSON',
		data: {
			'JobNo': JobNo,
			'TglPD' : TglPD,
		},
		cache : false,
		beforeSend : function(){
			$('.loadingku').show();
		},
		complete: function(xhr, textStatus) {
			$('.loadingku').hide();
		},
		success: function(data, textStatus, xhr) {
			if (data.status == 'berhasil') {

				$('#PrdAwal').val(data.PrdAwal);
				$('#PrdAkhir').val(data.PrdAkhir);
				$('#Minggu').val(data.Minggu);

			}
		},
		error: function(xhr, textStatus, errorThrown) {
			$.toast({
				text: 'Terjadi Kesalahan',
				position: 'bottom-right',
				stack: false,
				hideAfter: 1000,
				icon: 'error',

			});
			return false;
		}
	});
}

function hitung(){

	var HrgSatuan = $('#tambah_HrgSatuan').val();
	var Vol = $('#tambah_Vol').val();

	HrgSatuan = HapusKoma(HrgSatuan);
	Vol = HapusKoma(Vol);



	var total = parseFloat(Vol) * parseFloat(HrgSatuan);
	total = addCommas(total);
	$('#tambah_TotalHarga').val(total);
}

function hitung_edit() {

	var HrgSatuan = $('#edit_HrgSatuan').val();
	var Vol = $('#edit_Vol').val();

	HrgSatuan = HapusKoma(HrgSatuan);
	Vol = HapusKoma(Vol);

	var total = parseFloat(Vol) * parseFloat(HrgSatuan);
	total = addCommas(total);

	$('#edit_TotalHarga').val(total);

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

function HapusKoma(nilai) {
	return (nilai.replace(/,/g, ''));
}

function PindahData_PdDtl_ke_PdDtl_sementara_EditPD(){

	var NoPD = $('#NoPD').val();
	var JobNo = $('#JobNo').val();
	var Alokasi = $('#Alokasi').val();
	var LedgerNo = $('#LedgerNo').val();
	jQuery.ajax({
		url: '<?= site_url('Permintaan_dana_lapangan/PindahData_PdDtl_ke_PdDtl_sementara_EditPD  ') ?>',
		type: 'POST',
		dataType: 'HTML',
		data: {
			'NoPD': NoPD,
			'JobNo': JobNo,
			'Alokasi' : Alokasi,
			'LedgerNo' : LedgerNo,
		},
		async: true,
		cache : false,
		beforeSend : function(){
			$('.loadingku').show();
			$("#isitable").html("");
		},
		complete: function(xhr, textStatus) {
			$('.loadingku').hide();
			dapat_PdDtl_sementara_EditPD();
		},
		success: function(data, textStatus, xhr) {

		},
		error: function(xhr, textStatus, errorThrown) {
			$.toast({
				text: 'Terjadi Kesalahan',
				position: 'bottom-right',
				stack: false,
				hideAfter: 1000,
				icon: 'error',

			});
			return false;
		}
	});

}




function dapat_PdDtl_sementara_EditPD(){

	var NoPD = $('#NoPD').val();
	var JobNo = $('#JobNo').val();
	var Alokasi = $('#Alokasi').val();
	var LedgerNo = $('#LedgerNo').val();
	var tombol_btn = $('#form_saya').attr('data-tombol_btn');
	var khusus_inputan = $('#form_saya').attr('data-khusus_inputan');


	jQuery.ajax({
		url: '<?= site_url('Permintaan_dana_lapangan/dapat_PdDtl_sementara_EditPD  ') ?>',
		type: 'POST',
		dataType: 'HTML',
		data: {
			'NoPD': NoPD,
			'JobNo': JobNo,
			'Alokasi' : Alokasi,
			'LedgerNo' : LedgerNo,
			'khusus_inputan' : khusus_inputan
		},
		async: true,
		cache : false,
		beforeSend : function(){
			$('.loadingku').show();
			$("#isitable").html("");
		},
		complete: function(xhr, textStatus) {
			$('.loadingku').hide();
		},
		success: function(data, textStatus, xhr) {
			$('#isitable').html(data);
		},
		error: function(xhr, textStatus, errorThrown) {
			$.toast({
				text: 'Terjadi Kesalahan',
				position: 'bottom-right',
				stack: false,
				hideAfter: 1000,
				icon: 'error',

			});
			return false;
		}
	});

}

function tambah_uraian() {

	var NoKO = $('#NoKO').val();

	if (NoKO == '') {
		$.toast({
			text: 'Pilih NO.KO Terlebih Dahulu',
			position: 'top-right',
			stack: false,
			hideAfter: 1000,
			icon: 'info',
		});
		return false;
	}

	$('#modal_uraian').modal('show');
	bersihkan_form();
}

function bersihkan_form() {
	$('#tambah_KdRAP').val('').trigger('change');
	$('#tambah_Uraian').val('');
	$('#tambah_Vol').val('1');
	$('#tambah_Uom').val('');
	$('#tambah_HrgSatuan').val('0');
	$('#tambah_TotalHarga').val('0');

	$('#edit_KdRAP').val('').trigger('change');
	$('#edit_Uraian').val('');
	$('#edit_Vol').val('');
	$('#edit_Uom').val('');
	$('#edit_HrgSatuan').val('');
	$('#edit_TotalHarga').val('');



}


function edit_uraian(NoUrut) {

	var NoPD = $('#NoPD').val();

	jQuery.ajax({
		url: '<?=site_url('Permintaan_dana_lapangan/dapatFormUraian_EditPD')?>',
		type: 'POST',
		dataType: 'JSON',
		data: {
			'NoUrut': NoUrut,
			'NoPD': NoPD
		},
		async: true,
		cache : false,
		beforeSend: function() {
			$('.loadingku').show();
		},
		complete: function(xhr, textStatus) {
			$('.loadingku').hide();
			$('#modal_uraian_edit').modal('show');
		},
		success: function(data, textStatus, xhr) {

				// alert(data)
				// return false;

				bersihkan_form();
				$('#edit_KdRAP').val(data.KdRAP).trigger('change');
				$('#edit_Uraian').val(data.Uraian);
				$('#edit_Vol').val(data.Vol);
				$('#edit_Uom').val(data.Uom);
				$('#edit_HrgSatuan').val(data.HrgSatuan);
				$('#nopd_edit').val(data.NoPD);
				$('#norut_edit').val(data.NoUrut);
				$('#edit_TotalHarga').val(data.TotalHarga);
			},
			error: function(xhr, textStatus, errorThrown) {
				$.toast({
					text: 'Terjadi Kesalahan',
					position: 'bottom-right',
					stack: false,
					hideAfter: 1000,
					icon: 'error',
				});
				return false;
			}
		});

}

</script>