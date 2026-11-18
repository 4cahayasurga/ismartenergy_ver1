<?php
$myurl = site_url('Permintaan_dana_keuangan/view').'/'.$JobNo.'/'.$Alokasi;
$myurl .= ($this->uri->segment(5) == '0') ? '/0' : '/'.$this->uri->segment(5);
$myurl .= ($this->uri->segment(6) == '0') ? '/0' : '/'.$this->uri->segment(6);
// $myurl .= '/'

?>
<style type="text/css">
	.row_saya {
		font-size: 10px !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}

	table td th {
		font-size: 10px !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}

	#my_form {
		font-size: 10px !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}

	input[type="text"] {
		font-size: 10px !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}

	select {
		font-size: 10px !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}

	textarea {
		font-size: 10px !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;

	}

	.ukuran_select2 {
		font-size: 10px !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}

	table td{
		padding-top: 5px;
		padding-bottom: 5px;
	}

	.atur_ukuran {
		font-size: 10px !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}

	.swal2-popup {
		font-size: 1.2rem !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}

</style>

<div class="content-wrapper">

	<section class="content-header">
		<table width="100%" border="0">
			<tr>
				<td style="font-size: 16px !important;" class="text-bold atur_ukuran"><?=(isset($judul)) ? $judul : '' ?></td>
				<td style="font-size: 12px !important;" class="text-bold text-right atur_ukuran text-red"><?=$JobNo.' - '.$JobNm.'<br />'.$Lokasi?></td>
			</tr>
			<tr>
				<td>
					
					<a href="<?=$myurl?>" class="btn btn-sm bg-gray atur_ukuran"  ><i class="fa fa-arrow-left"></i> Back</a>

				</td>
				<td></td>
			</tr>
		</table>
	</section>

	<section class="content">

		<?= ($this->session->flashdata('message')) ? $this->session->flashdata('message') : ''    ?>

		<div class="box box-success">

			
			<div class="box-body">


				<form id="form_saya" data-url-back="<?=$myurl?>">

					<div class="row">

						<div class="col-sm-6 row_saya">

							<table width="100%" border="0" class="atur_ukuran">
								<tr>
									<th style="width:80px;" class="atur_ukuran">No. PD</th>
									<td style="width:2%;">:</td>
									<td>
										<input type="text" class="form-control input-sm atur_ukuran" value="<?= $no_urut_pd['KodeUrutPd'] ?>" disabled>
									</td>
									<th style="width:23%;">&nbsp;&nbsp;&nbsp;No. BAP/Ref Lapangan : </th>
									<td>
										<input type="text" class="form-control input-sm atur_ukuran" name="NoRef" id="NoRef" placeholder="No.Ref" readonly>
									</td>
								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;" class="atur_ukuran">Job No</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" class="form-control input-sm atur_ukuran" value="<?= $dapat_judul['JobNo'] ?> - <?= $dapat_judul['JobNm'] ?>" disabled>
									</td>

								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;" class="atur_ukuran">Tgl Permintaan</th>
									<td style="width:10px;">:</td>
									<td style="width: 200px;">
										<input type="date" class="form-control input-sm atur_ukuran" name="TglPD" value="<?= date('Y-m-d') ?>" id="TglPD" required>
									</td>
									<td>
										<div class="form-check form-check-inline">
											&nbsp;&nbsp;&nbsp;<input class="form-check-input" type="checkbox" name="Jaminan" id="Jaminan" value="1">
											<label class="form-check-label atur_ukuran" for="Jaminan">PD Jaminan</label>
										</div>

									</td>

								</tr>
							</table>


							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Deskripsi</th>
									<td style="width:10px;">:</td>
									<td>
										<textarea name="Deskripsi" id="Deskripsi" class="form-control input-sm atur_ukuran" placeholder="Isi Deskripsi"><?=$dataPD['Deskripsi']?></textarea>
									</td>


								</tr>
							</table>


							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Periode</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="date" name="PrdAwal" id="PrdAwal" title="Periode Awal" value="<?= $PrdAwal ?>" class="form-control input-sm atur_ukuran" required>
									</td>
									<td style="width:10px !important;">s.d.</td>
									<td>
										<input type="date" name="PrdAkhir" id="PrdAkhir" title="Periode Akhir" value="<?= $PrdAkhir ?>" class="form-control input-sm atur_ukuran" required>
									</td>
									<td>Minggu Ke: </td>
									<td style="width:50px !important;">
										<input type="text" name="Minggu" id="Minggu" class="form-control input-sm atur_ukuran" value="<?=$Minggu?>">
									</td>
								</tr>
							</table>


							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Alokasi</th>
									<td style="width:10px;">:</td>
									<td>
										<select class="form-control input-sm select2 atur_ukuran" id="AlokasiGabung" disabled="disabled" required>
											<option value="" class="atur_ukuran">-- Pilih Alokasi --</option>

											<?php
											foreach (list_job_alokasi() as $data_alokasi) :
												$pilih = ($data_alokasi['Alokasi'] == $Alokasi) ? 'selected' : '';
												echo "<option value='" . $data_alokasi['Alokasi'] . "' " . $pilih . "  >" . $data_alokasi['AlokasiGabung'] . "</option>";
											endforeach;
											?>

										</select>
									</td>

									<td style="width:70px;" class="ukuran_select2">Tipe Form: </td>
									<td>
										<select name="TipeForm" id="TipeForm" class="form-control input-sm select2 ukuran_select2" onchange="dapatkan_no_ko(this.value)" required>
											<option value="" class="ukuran_select2">--Pilih Salah Satu--</option>
											<?php
											foreach (list_tipe_form($Alokasi) as $data_tipe) :

												echo "<option value='" . $data_tipe['TipeForm'] . "' >" . $data_tipe['tipe_form_gabung'] . "</option>";

											endforeach;
											?>
										</select>
									</td>

								</tr>
							</table>

							<table width="100%" border="0" class="ukuran_select2">
								<tr>
									<th style="width:80px;"></th>
									<td style="width:10px;">&nbsp;&nbsp;</td>
									<td style="font-size:10px !important;text-align: center;background-color:#3498db;color:white;" class="ukuran_select2">
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
											<input class="form-check-input ukuran_select2" type="checkbox" id="INV" value="INV" name="BuktiPendukung[]">
											<label class="form-check-label ukuran_select2" for="INV">Invoice/Kwitansi</label>
										</div><br>

										<div class="form-check form-check-inline" style="font-size: 10px;">
											<input class="form-check-input ukuran_select2" type="checkbox" id="SJ" value="SJ" name="BuktiPendukung[]">
											<label class="form-check-label ukuran_select2" for="SJ">Surat Jalan/Tanda Terima keuangan</label>
										</div><br>

										<div class="form-check form-check-inline" style="font-size: 10px;">
											<input class="form-check-input" type="checkbox" id="PO" value="PO" name="BuktiPendukung[]">
											<label class="form-check-label" for="PO">Copy PO</label>
										</div>


									</td>
									<td>
										<div class="form-check form-check-inline" style="font-size: 10px;">
											<input class="form-check-input ukuran_select2" type="checkbox" id="FP" value="FP" name="BuktiPendukung[]">
											<label class="form-check-label ukuran_select2" for="FP">Faktur Pajak</label>
										</div><br>

										<div class="form-check form-check-inline ukuran_select2" style="font-size: 10px;">
											<input class="form-check-input ukuran_select2" type="checkbox" id="BAP" value="BAP" name="BuktiPendukung[]">
											<label class="form-check-label ukuran_select2" for="BAP">Berita Acara Pembayaran</label>
										</div><br>

										<div class="form-check form-check-inline ukuran_select2" style="font-size: 10px;">
											<input class="form-check-input ukuran_select2" type="checkbox" id="BAOP" value="BAOP" name="BuktiPendukung[]">
											<label class="form-check-label ukuran_select2" for="BAOP">Berita Acara Opname Pekerjaan</label>
										</div>
									</td>
								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Keterangan</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="Keterangan" id="Keterangan" placeholder="Keterangan" class="form-control input-sm ukuran_select2">
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
										<select name="NoKO" id="NoKO" class="form-control input-sm select2 atur_ukuran" required>
											<option value="">-- Pilih salah satu --</option>
										</select>
									</td>

								</tr>
							</table>


							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">No. Tagihan</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="NoTagihan" id="NoTagihan" class="form-control input-sm atur_ukuran" placeholder="No. Tagihan">
									</td>

								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">ID</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" id="VendorId_tiruan" disabled="disabled" class="form-control input-sm atur_ukuran" placeholder="ID">
										<input type="hidden" name="VendorId" id="VendorId" class="form-control input-sm atur_ukuran" placeholder="ID">
									</td>

								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Nama</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="Nama" id="Nama" class="form-control input-sm atur_ukuran" placeholder="Nama">
									</td>

								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Alamat</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="Alamat" id="Alamat" class="form-control input-sm atur_ukuran" placeholder="Alamat">
									</td>

								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Telepon</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="Telepon" id="Telepon" class="form-control input-sm atur_ukuran" placeholder="Telepon">
									</td>

								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">NPWP</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="NPWP" id="NPWP" class="form-control input-sm atur_ukuran" placeholder="NPWP">
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
										<input type="text" name="NoRek" id="NoRek" class="form-control input-sm atur_ukuran" placeholder="No. Rekening">
									</td>
								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Atas Nama</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="AtasNama" id="AtasNama" class="form-control input-sm atur_ukuran" placeholder="Atas Nama">
									</td>
								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Bank</th>
									<td style="width:10px;">:</td>
									<td>
										<select class="form-control input-sm select2 atur_ukuran" id="Bank" onchange="isi_bank(this.value)">
											<option value="">-- Pilih Bank --</option>
											<?php foreach (list_data_bank() as $data_bank) :
												echo '<option value="' . $data_bank['Bank'] . '" >' . $data_bank['Bank'] . '</option>';
											endforeach; ?>

										</select>
										<input type="hidden" id="mybank" name="Bank" class="form-control input-sm atur_ukuran">
									</td>
								</tr>
							</table>


						</div>

						<div class="col-sm-12">
							<hr style="border-top:1px solid black;">

							<input type="hidden" name="NoPD" id="NoPD" value="<?= $no_urut_pd['KodeUrutPd'] ?>">
							<input type="hidden" name="JobNo" id="JobNo" value="<?= $JobNo ?>">
							<input type="hidden" name="Alokasi" id="Alokasi" value="<?= $Alokasi ?>">
							<input type="hidden" id="status_kode" name="status_kode" value="<?= $no_urut_pd['status_kode'] ?>">

							<input type="hidden" id="PD_Dari" name="PD_Dari" value="<?=$PD_Dari?>">

							<button type="submit" id="btn-simpan-pd" class="btn btn-success btn-md pull-right">Simpan</button>
							<a href="<?=$myurl?>" class="btn btn-sm bg-gray atur_ukuran"  ><i class="fa fa-arrow-left"></i> Back</a>

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

					<a href="javascript:void(0)" class="btn btn-sm" style="background-color:#3498db;color:white;" onclick="tambah_uraian()">Tambah Uraian <i class="fa fa-plus"></i></a>
					
					<a href="javascript:void(0)" class="btn btn-sm" style="background-color:#c0392b;color:white;" id="btn-hapus-uraian">Hapus Uraian <i class="fa fa-trash"></i></a>

					<a href="javascript:void(0)" class="btn btn-sm" style="background-color:#7f8c8d;color:white;" onclick="dapat_PdDtl_sementara()">Refresh Table Uraian <i class="fa fa-refresh"></i></a>

					<button type="button" class="btn btn-sm btn-success" id="lihat_invoice">Lihat Invoice</button>
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


<div class="modal fade" id="modal_uraian" style="font-size: 10px;">
	<div class="modal-dialog modal-xs" id="modal_dialog_uraian">
		<div class="modal-content">
			<div class="modal-header">
				<table width="100%" border="0">
					<tr>
						<td style="font-size:12px !important;" class="atur_ukuran">TAMBAH URAIAN PERMINTAAN DANA</td>
						<td class="text-right" style="font-size:11px !important;"><?= strtoupper($dapat_judul['JobNo']) ?> - <?= strtoupper($dapat_judul['JobNm']) ?></td>
					</tr>
				</table>
			</div>
			<div class="modal-body" id="modal_body_uraian">



				<form id="form_tambah_uraian">

					<table width="100%" style="border-collapse: collapse;" border="0" class="atur_ukuran">
						<tr>
							<td class="atur_ukuran" width="18%">No</td>
							<td style="text-align:center;" width="2%">:</td>
							<td width="10%"><input type="text" class="form-control input-sm atur_ukuran" disabled></td>
							<td></td>
						</tr>
					</table>

					<table width="100%" style="border-collapse: collapse;" border="0" class="atur_ukuran">
						<tr>
							<td class="atur_ukuran" width="18%">Alokasi</td>
							<td style="text-align:center;" width="2%">:</td>
							<td width="50%">
								<select class="form-control input-sm select2" disabled="disabled" required>
									<option value="">-- Pilih Salah Satu --</option>

									<?php
									foreach (list_job_alokasi() as $data_alokasi) :
										$pilih = ($data_alokasi['Alokasi'] == $Alokasi) ? 'selected' : '';
										echo "<option value='" . $data_alokasi['Alokasi'] . "' " . $pilih . "  >" . $data_alokasi['AlokasiGabung'] . "</option>";
									endforeach;
									?>

								</select>
							</td>
							<td></td>
						</tr>
					</table>

					<table width="100%" style="border-collapse: collapse;" border="0" class="atur_ukuran">
						<tr>
							<td class="atur_ukuran" width="18%">Kode RAP</td>
							<td style="text-align:center;" width="2%">:</td>
							<td width="80%">
								<select class="form-control input-sm select2" name="KdRAP" id="tambah_KdRAP" required>
									<option value="">-- Pilih Salah Satu --</option>
									<?php
									foreach (list_kode_rap($JobNo, $Alokasi) as $data_rap) :

										echo "<option value='" . $data_rap['KdRAP'] . "'   >" . $data_rap['KdRAP'] . " - " . $data_rap['Uraian'] . "</option>";
									endforeach;
									?>
								</select>
							</td>
							<td></td>
						</tr>
					</table>

					<table width="100%" style="border-collapse: collapse;" border="0" class="atur_ukuran">
						<tr>
							<td class="atur_ukuran" width="18%">Uraian</td>
							<td style="text-align:center;" width="2%">:</td>
							<td width="80%">
								<textarea class="form-control input-sm" name="Uraian" id="tambah_Uraian" placeholder="Isi Uraian" required></textarea>
							</td>
							<td></td>
						</tr>
					</table>

					<table width="100%" style="border-collapse: collapse;" border="0" class="atur_ukuran">
						<tr>
							<td class="atur_ukuran" width="18%">Vol</td>
							<td style="text-align:center;" width="2%">:</td>
							<td width="50%">
								<input type="text" class="form-control input-sm" width="" name="Vol" id="tambah_Vol" value="1" placeholder="Isi Harga Volume" autocomplete="off" required>
								<span class="text-red" style="font-size:9px !important;">*gunakan titik (.) untuk decimal di Harga Satuan & Vol </span>
							</td>
							<td></td>
						</tr>
					</table>

					<table width="100%" style="border-collapse: collapse;" border="0" class="atur_ukuran">
						<tr>
							<td class="atur_ukuran" width="18%">Satuan</td>
							<td style="text-align:center;" width="2%">:</td>
							<td width="15%">
								<input type="text" class="form-control input-sm" name="Uom" id="tambah_Uom" placeholder="Isi Satuan" autocomplete="off" required>
							</td>
							<td></td>
						</tr>
					</table>

					<table width="100%" style="border-collapse: collapse;" border="0" class="atur_ukuran">
						<tr>
							<td class="atur_ukuran" width="18%">Harga Satuan (Rp)</td>
							<td style="text-align:center;" width="2%">:</td>
							<td width="80%">
								<input type="text" class="form-control input-sm uang" name="HrgSatuan" id="tambah_HrgSatuan" value="0" placeholder="Isi Harga Satuan" autocomplete="off" required>
								<span class="text-red" style="font-size:9px !important;">*gunakan titik (.) untuk decimal di Harga Satuan & Vol </span>
							</td>
							<td></td>
						</tr>
					</table>

					<!-- <table width="100%" style="border-collapse: collapse;" border="0" class="atur_ukuran">
						<tr>
							<td class="atur_ukuran" width="18%">Total Harga (Rp)</td>
							<td style="text-align:center;" width="2%">:</td>
							<td width="80%">
								<input type="text" class="form-control input-sm" id="tambah_TotalHarga" value="0" disabled required>
							</td>
							<td></td>
						</tr>
					</table> -->

					<table width="100%" style="border-collapse: collapse;" border="0" class="atur_ukuran">
						<tr>
							<td class="atur_ukuran" width="18%"></td>
							<td style="text-align:center;" width="2%"></td>
							<td width="100%">
								<input type="hidden" name="NoPD" value="<?= $no_urut_pd['KodeUrutPd'] ?>">
								<input type="hidden" name="JobNo" value="<?= $JobNo ?>">
								<input type="hidden" name="Alokasi" value="<?= $Alokasi ?>">
								<button type="submit" id="tambah_save" class="btn btn-sm btn-primary">Save</button>
								<button class="btn btn-sm bg-gray huruf_ukuran disabled" id="btn_loading" style="display: none;">Loading... <i class="fa fa-spinner fa-spin"></i> </button>
								<button type="button" class="btn btn-sm btn-danger  pull-right" data-dismiss="modal">Batal</button>

							</td>
						</tr>
					</table>



				</form>
				<script type="text/javascript">
					$(document).ready(function(){
						$('.select2').select2({
							'width': '100%',
							'dropdownCssClass': "ukuran_select2"
						});
					});
				</script>
			</div>
		</div>

	</div>

</div>

<div class="modal fade" id="modal_uraian_edit" style="font-size: 10px;">
	<div class="modal-dialog modal-xs" id="modal_dialog_uraian_edit">
		<div class="modal-content">
			<div class="modal-header">
				<table width="100%" border="0">
					<tr>
						<td style="font-size:12px !important;" class="atur_ukuran">EDIT URAIAN PERMINTAAN DANA</td>
						<td class="text-right" style="font-size:11px !important;"><?= strtoupper($dapat_judul['JobNo']) ?> - <?= strtoupper($dapat_judul['JobNm']) ?></td>
					</tr>
				</table>
			</div>
			<div class="modal-body" id="modal_body_uraian_edit">

				<form id="form_tambah_uraian_edit">

					<table width="100%" style="border-collapse: collapse;" border="0" class="atur_ukuran">
						<tr>
							<td class="atur_ukuran" width="18%">No</td>
							<td style="text-align:center;" width="2%">:</td>
							<td width="10%">
								<input type="text" class="form-control form-control-sm" disabled>
							</td>
							<td></td>
						</tr>
					</table>

					<table width="100%" style="border-collapse: collapse;" border="0" class="atur_ukuran">
						<tr>
							<td class="atur_ukuran" width="18%">Alokasi</td>
							<td style="text-align:center;" width="2%">:</td>
							<td width="50%">
								<select class="form-control form-control-sm select2" disabled="disabled" required>
									<option value="">-- Pilih Salah Satu --</option>

									<?php
									foreach (list_job_alokasi() as $data_alokasi) :
										$pilih = ($data_alokasi['Alokasi'] == $Alokasi) ? 'selected' : '';
										echo "<option value='" . $data_alokasi['Alokasi'] . "' " . $pilih . "  >" . $data_alokasi['AlokasiGabung'] . "</option>";
									endforeach;
									?>

								</select>
							</td>
							<td></td>
						</tr>
					</table>

					<table width="100%" style="border-collapse: collapse;" border="0" class="atur_ukuran">
						<tr>
							<td class="atur_ukuran" width="18%">Kode RAP</td>
							<td style="text-align:center;" width="2%">:</td>
							<td width="80%">
								<select class="form-control form-control-sm select2" name="KdRAP" id="edit_KdRAP" required>
									<option value="">-- Pilih Salah Satu --</option>
									<?php
									foreach (list_kode_rap($JobNo, $Alokasi) as $data_rap) :

										echo "<option value='" . $data_rap['KdRAP'] . "'   >" . $data_rap['KdRAP'] . " - " . $data_rap['Uraian'] . "</option>";
									endforeach;
									?>
								</select>
							</td>
							<td></td>
						</tr>
					</table>

					<table width="100%" style="border-collapse: collapse;" border="0" class="atur_ukuran">
						<tr>
							<td class="atur_ukuran" width="18%">Uraian</td>
							<td style="text-align:center;" width="2%">:</td>
							<td width="80%">
								<textarea class="form-control form-control-sm" name="Uraian" id="edit_Uraian" placeholder="Isi Uraian" required></textarea>
							</td>
							<td></td>
						</tr>
					</table>

					<table width="100%" style="border-collapse: collapse;" border="0" class="atur_ukuran">
						<tr>
							<td class="atur_ukuran" width="18%">Vol</td>
							<td style="text-align:center;" width="2%">:</td>
							<td width="50%">
								<input type="text" class="form-control form-control-sm" name="Vol" id="edit_Vol" value="0" placeholder="Isi Harga Volume" autocomplete="off" required>
								<span class="text-red" style="font-size:9px !important;">*gunakan titik (.) untuk decimal di Harga Satuan & Vol </span>
							</td>
							<td></td>
						</tr>
					</table>

					<table width="100%" style="border-collapse: collapse;" border="0" class="atur_ukuran">
						<tr>
							<td class="atur_ukuran" width="18%">Satuan</td>
							<td style="text-align:center;" width="2%">:</td>
							<td width="15%">
								<input type="text" class="form-control form-control-sm" name="Uom" id="edit_Uom" placeholder="Isi Satuan" autocomplete="off" required>
							</td>
							<td></td>
						</tr>
					</table>

					<table width="100%" style="border-collapse: collapse;" border="0" class="atur_ukuran">
						<tr>
							<td class="atur_ukuran" width="18%">Harga Satuan (Rp)</td>
							<td style="text-align:center;" width="2%">:</td>
							<td width="80%">
								<input type="text" class="form-control form-control-sm uang" name="HrgSatuan" id="edit_HrgSatuan" value="0" placeholder="Isi Harga Satuan" autocomplete="off" required>
								<span class="text-red" style="font-size:9px !important;">*gunakan titik (.) untuk decimal di Harga Satuan & Vol </span>
							</td>
							<td></td>
						</tr>
					</table>

					<!-- <table width="100%" style="border-collapse: collapse;" border="0" class="atur_ukuran">
						<tr>
							<td class="atur_ukuran" width="18%">Total Harga (Rp)</td>
							<td style="text-align:center;" width="2%">:</td>
							<td width="80%">
								<input type="text" class="form-control form-control-sm" id="edit_TotalHarga" value="0" disabled required>
							</td>
							<td></td>
						</tr>
					</table>
 -->
					<table width="100%" style="border-collapse: collapse;" border="0" class="atur_ukuran">
						<tr>
							<td class="atur_ukuran" width="18%"></td>
							<td style="text-align:center;" width="2%"></td>
							<td width="100%">
								<input type="hidden" name="JobNo" value="<?= $JobNo ?>">
								<input type="hidden" name="Alokasi" value="<?= $Alokasi ?>">
								<input type="hidden" name="NoPD" id="nopd_edit">
								<input type="hidden" name="NoUrut" id="norut_edit">
								<button type="submit" id="update_save" class="btn btn-sm btn-primary">Update</button>
								<button class="btn btn-sm bg-gray huruf_ukuran disabled" id="btn_loading_edit" style="display: none;">Loading... <i class="fa fa-spinner fa-spin"></i> </button>
								<button type="button" class="btn btn-sm btn-danger  pull-right" data-dismiss="modal">Batal</button>

							</td>
						</tr>
					</table>

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


<script src="<?=base_url('assets/select2_baru.js')?>"></script>

<script type="text/javascript">

	$(document).ready(function(){


		$('#lihat_invoice').click(function(){
			lihat_invoice();
		});

		function lihat_invoice(){

			var NoPD = $('#NoPD').val();
			var JobNo = $('#JobNo').val();
			var Alokasi = $('#Alokasi').val();
			var NoKO = ($('#NoKO option:selected').val()) ? $('#NoKO option:selected').val() : 'kosong'; 
			var TipeForm = ($('#TipeForm option:selected').val()) ? $('#TipeForm option:selected').val() : 'kosong';


			if (TipeForm == 'kosong' ) {

				swal.fire('info','Pilih Tipe Form Telebih Dahulu','info');
				return false;
			}

			if (NoKO == 'kosong' ) {
				swal.fire('info','Pilih No Kontrak/PO terlebih dahulu','info');
				return false;
			}

			jQuery.ajax({
				url: '<?= site_url('Permintaan_dana_keuangan/lihat_invoice') ?>',
				type: 'POST',
				dataType: 'HTML',
				data: {
					'JobNo': JobNo,
					'Alokasi' : Alokasi,
					'NoPD': NoPD,
					'NoKO' : NoKO,
					'TipeForm' : TipeForm

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
						swal.fire('error','Terjadi Kesalahan','error');
						return false;
					}

				},
				error: function(xhr, textStatus, errorThrown) {
					swal.fire('error','Terjadi Kesalahan','error');
					return false;
				}
			});

		}

	});
</script>


<script src="<?=base_url('assets/select2_baru.js')?>"></script>

<script type="text/javascript">

	$(document).ready(function() {

		$('#AlokasiGabung').select2({
			'placeholder' : '-- Pilih Alokasi --',
			'width': '100%',
			'dropdownCssClass': "ukuran_select2"
		});

		$('#TipeForm').select2({
			'width': '100%',
			'dropdownCssClass': "ukuran_select2"
		});

		$('#NoKO').select2({
			'placeholder' : '-- Pilih No. KO --',
			'width': '100%',
			'dropdownCssClass': "ukuran_select2"
		});

		$('#Bank').select2({
			'placeholder' : '-- Pilih Bank --',
			'width': '100%',
			'dropdownCssClass': "ukuran_select2"
		});

		// $('#tambah_HrgSatuan,#tambah_Vol').mask("#,##0.00", {reverse: true});
		// $('#edit_HrgSatuan,#edit_Vol').mask("#,##0.00", {reverse: true});

		$('#form_saya').submit(function(e) {
			e.preventDefault();


			var JobNo = $('#JobNo').val();
			var SubTotal = $('#SubTotal').val();
			var Saldo = $('#Saldo').val();
			var TotalPermintaan = $('#SubTotal').val();
			var Alokasi = $('#Alokasi').val();
			var myurl = $('#form_saya').attr('data-url-back');

			var NoPJSebelumnya = $('#pilih_NoPJSebelumnya').val();

			jQuery.ajax({
				url: '<?=site_url('Permintaan_dana_keuangan/Aksi_Simpan_PdHdr_tambah_pd')?>',
				type: 'POST',
				dataType: 'HTML',
				data: $(this).serialize()+"&SubTotal="+SubTotal+"&Saldo="+Saldo+"&TotalPermintaan="+TotalPermintaan+"&NoPJSebelumnya="+NoPJSebelumnya,
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
					// console.log(data);
					// return false;

					if (data == 'pd_detail_kosong') {
						swal.fire('info','isi uraian terlebih dahulu','info');
						return false;
					}

					if (data == 'pd_sudah_ada') {
						swal.fire('info','No PD sudah terpakai','info');
						return false;
					}

					if (data == 'gagal') {
						swal.fire('error','Gagal menambahkan data','error');
						return false;
					}

					Swal.fire({
						title: "Success!",
						html: "Berhasil menambahkan data",
						type: "success",
						timer: 1500,
						timerProgressBar: true,
						didOpen: () => {
							Swal.showLoading()
							
						},
						willClose: () => {
							Swal.hideLoading()
						},
						showConfirmButton: false,
					}).then(function() {
						window.location.href=myurl;
					});
					return false;


				},
				error: function(xhr, textStatus, errorThrown) {
					swal.fire('error','Terjadi Kesalahan','error');
					return false;
				}

			});
			

		});

		$('#form_tambah_uraian').submit(function(e) {
			e.preventDefault();

			mydata = $(this).serialize();
			$.ajax({
				type: "post",
				url: "<?= site_url('Permintaan_dana_keuangan/aksi_simpan_uraian_pd') ?>",
				data: mydata,
				async: true,
				cache : false,
				dataType: "html",
				beforeSend: function() {
					$('#btn_loading').show();
					$('#tambah_save').hide();
				},
				complete : function(){
					$('#btn_loading').hide();
					$('#tambah_save').show();

					$('#modal_uraian').modal('hide');
					dapat_PdDtl_sementara();
				},
				success: function(data) {


					if (data == 'berhasil') {
						// swal.fire('success','berhasil menambahkan data','success');
						// return false;
						toastr.success('berhasil');
						return false;
					}

					if (data == 'gagal') {
						toastr.error('gagal');
						return false;
						// swal.fire('error','gagal menambahkan data','error');
						// return false;
					}
				},
				error: function() {
					toastr.error('Terjadi Kesalahan');
					return false;
					// swal.fire('error','Terjadi Kesalahan','error');
					// return false;
				}
			});

		});

		$('#form_tambah_uraian_edit').submit(function(e) {
			e.preventDefault();

			jQuery.ajax({
				url: '<?= site_url('Permintaan_dana_keuangan/aksi_edit_uraian_di_tambah_pd') ?>',
				type: 'POST',
				dataType: 'html',
				data: $(this).serialize(),
				async: true,
				cache : false,
				beforeSend: function() {
					$('#btn_loading_edit').show();
					$('#update_save').hide();
				},
				complete: function(xhr, textStatus) {
					$('#btn_loading_edit').hide();
					$('#update_save').show();

					$('#modal_uraian_edit').modal('hide');
					dapat_PdDtl_sementara();
				},
				success: function(data, textStatus, xhr) {

					

					if (data == 'berhasil') {
						swal.fire('success','data berhasil diubah','success');
						return false;
					}

					if (data == 'gagal') {
						swal.fire('error','data gagal diubah','error');
					}
				},
				error: function(xhr, textStatus, errorThrown) {
					swal.fire('error','Terjadi Kesalahan','error');
					return false;
				}
			});
			
		});

		$('#tambah_Vol').keyup(function() {
			// hitung();
		});

		$('#tambah_HrgSatuan').keyup(function() {
			// hitung();
		});

		$('#edit_Vol').keyup(function() {
			// hitung_edit();
		});

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
						url: '<?=site_url('Permintaan_dana_keuangan/hapus_multiple_uraian')?>',
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
							dapat_PdDtl_sementara();
						},
						success: function(data, textStatus, xhr) {

							if (data == 'berhasil') {
								swal.fire('success','data berhasil dihapus','success');
								return false;
							}

							if (data == 'gagal') {
								swal.fire('error','data gagal dihapus','error');
								return false;
								
							}

							return false;
						},
						error: function(xhr, textStatus, errorThrown) {
							swal.fire('error','Terjadi Kesalahan','error');
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
		url: '<?= site_url('Permintaan_dana_keuangan/dapat_data_ketika_TglPD_berubah_js') ?>',
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
			toastr.error('Terjadi Kesalahan');
			return false;
		}
	});
}

function hitung() {

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


function tampil_table_dari_NoKO(){
	var NoPD = $('#NoPD').val();
	var JobNo = $('#JobNo').val();
	var Alokasi = $('#Alokasi').val();
	var NoKO = $('#NoKO').val();

	if (NoKO == '') {
		swal.fire('info','No KO belum dipilih','info');
		return false;
		
	}

	jQuery.ajax({
		url: '<?= site_url('Permintaan_dana_keuangan/tampil_table_dari_NoKO') ?>',
		type: 'POST',
		dataType: 'HTML',
		data: {
			'NoPD': NoPD,
			'NoKO' : NoKO,
			'JobNo': JobNo,
			'Alokasi' : Alokasi
		},
		async: true,
		cache : false,
		beforeSend : function(){
			$('.loadingku').show();
			$("#isitable").html("");
		},
		complete: function(xhr, textStatus) {
			$('.loadingku').hide();
			dapat_PdDtl_sementara();
		},
		success: function(data, textStatus, xhr) {
		},
		error: function(xhr, textStatus, errorThrown) {
			swal.fire('error','Terjadi Kesalahan','error');
			return false;
			
		}
	});
	
}

function dapat_PdDtl_sementara(){

	var NoPD = $('#NoPD').val();
	var JobNo = $('#JobNo').val();
	var Alokasi = $('#Alokasi').val();
	var NoKO = $('#NoKO').val();

	var TipeForm = $('#TipeForm').val();
	// alert(TipeForm);
	// return false;

	if (NoKO == '') {
		swal.fire('info','No KO belum dipilih','info');
		return false;
	}

	jQuery.ajax({
		url: '<?= site_url('Permintaan_dana_keuangan/dapat_PdDtl_sementara') ?>',
		type: 'POST',
		dataType: 'HTML',
		data: {
			'NoPD': NoPD,
			'NoKO' : NoKO,
			'JobNo': JobNo,
			'Alokasi' : Alokasi,
			'TipeForm' : TipeForm
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
			Swal.fire('error','Terjadi Kesalahan','error');
			return false;
		}
	});
	
}


function dapatkan_no_ko(NoKO) {

	var JobNo = $('#JobNo').val();
	var NoPD = $('#NoPD').val();
	var Alokasi = $('#Alokasi').val();
	var TipeForm = $('#TipeForm :selected').val();

	var type = 'keuangan';

	$.ajax({
		type: "post",
		url: "<?= site_url('Permintaan_dana_keuangan/dapat_no_ko_dan_vendor') ?>",
		data: {
			'NoKO': NoKO,
			'JobNo': JobNo,
			'NoPD': NoPD,
			'Alokasi': Alokasi,
			'type' : type,
			'TipeForm' : TipeForm,
		},
		async: true,
		cache : false,
		dataType: "html",
		beforeSend: function() {
			$('.loadingku').show();
			$('.atur_no_ko').html('');
		},
		complete : function(){
			$('.loadingku').hide();

		},
		success: function(data) {
			$('.atur_no_ko').html(data);
		},
		error: function() {
			swal.fire('error','Terjadi Kesalahan','error');
			return false;
		}
	});

}

function dapat_vendor() {

	var NoKO = $('#NoKO').val();
	
	var VendorId = $('#NoKO :selected').attr('data-vendor');
	var JobNo = $('#NoKO :selected').attr('data-JobNo');
	var Alokasi = $('#NoKO :selected').attr('data-Alokasi');

	$.ajax({
		type: "post",
		url: "<?= site_url('Permintaan_dana_keuangan/dapat_vendor') ?>",
		data: {
			'NoKO': NoKO,
			'VendorId': VendorId,
			'JobNo' : JobNo,
			'Alokasi' : Alokasi
		},
		dataType: "JSON",
		async: true,
		cache : false,
		beforeSend: function() {
			$('.loadingku').show();
		},
		complete : function(){
			$('.loadingku').hide();
			tampil_table_dari_NoKO();
		},
		success: function(data) {

			$('#VendorId').val('');
			$('#Nama').val('');
			$('#Alamat').val('');
			$('#Telepon').val('');
			$('#NPWP').val('');

			$('#NoRek').val('');
			$('#AtasNama').val('');
			$('#Bank').val('').trigger('change');

			$('#mybank').val('');

			if (data.Status == 'ada') {
				$('#VendorId').val(data.VendorId).attr('readonly', true);
				$('#Nama').val(data.Nama).attr('readonly', false);
				$('#Alamat').val(data.Alamat).attr('readonly', false);
				$('#Telepon').val(data.Telepon).attr('readonly', false);
				$('#NPWP').val(data.NPWP).attr('readonly', false);
				$('#NoRek').val(data.NoRek).attr('readonly', false);
				$('#AtasNama').val(data.AtasNama).attr('readonly', false);
				$('#Bank').val(data.Bank).trigger('change').attr('disabled', false);
				$('#mybank').val(data.Bank).attr('readonly', true);
			} else {

				$('#VendorId').val(data.VendorId).attr('readonly', false);
				$('#Nama').val(data.Nama).attr('readonly', false);
				$('#Alamat').val(data.Alamat).attr('readonly', false);
				$('#Telepon').val(data.Telepon).attr('readonly', false);
				$('#NPWP').val(data.NPWP).attr('readonly', false);
				$('#NoRek').val(data.NoRek).attr('readonly', false);
				$('#AtasNama').val(data.AtasNama).attr('readonly', false);
				$('#Bank').val(data.Bank).trigger('change').attr('disabled', false);
				$('#mybank').val(data.Bank).attr('readonly', false);


			}

		},
		error: function() {
			swal.fire('error','Terjadi Kesalahan','error');
			return false;

		}
	});

}

function isi_bank(isi) {
	$('#mybank').val('');
	$('#mybank').val(isi);
}


function tambah_uraian() {

	var NoKO = $('#NoKO').val();

	if (NoKO == '') {
		swal.fire('info','No KO belum dipilih','info');
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

function hapus_uraian(NoUrut='') {
	if (NoUrut == '') {
		swal.fire('error','Terjadi Kesalahan','error');
		return false;
	}

	var NoPD = $('#NoPD').val();

	Swal.fire({
		title: 'Anda Yakin?',
		text: "data tidak dapat dikembalikan!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Ya, hapus!',
		cancelButtonText: 'Batal'
	}).then((result) => {
		if (result.value) {

			jQuery.ajax({
				url: '<?= site_url('Permintaan_dana_keuangan/hapus_uraian') ?>',
				type: 'POST',
				dataType: 'HTML',
				data: {
					'NoUrut': NoUrut,
					'NoPD': NoPD
				},
				async: true,
				cache : false,
				beforeSend : function(){
					$('.loadingku').show();
				},
				complete: function(xhr, textStatus) {
					$('.loadingku').hide();
					dapat_PdDtl_sementara();
				},
				success: function(data, textStatus, xhr) {

					if (data == 'berhasil') {
						swal.fire('success','data berhasil dihapus','success');
						return false;
						
					}

					if (data == 'gagal') {

						swal.fire('error','data gagal dihapus','error');
						return false;
					}

					return false;

				},
				error: function(xhr, textStatus, errorThrown) {
					swal.fire('error','Terjadi Kesalahan','error');
					return false;
					
				}

			});
			

		}
	})
}


function edit_uraian(NoUrut) {

	var NoPD = $('#NoPD').val();

	jQuery.ajax({
		url: '<?=site_url('Permintaan_dana_keuangan/dapatkan_form_edit_uraian')?>',
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
			swal.fire('error','Terjadi Kesalahan','error');
			return false;
			
		}
	});
	
}

</script>