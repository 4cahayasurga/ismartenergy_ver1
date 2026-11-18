<style type="text/css">
	.atur {
		font-size: 12px;
		font-family: sans-serif;
	}

	input {
		font-size: 12px !important;
	}

	select {
		font-size: 12px !important;
	}

	textarea {
		font-size: 12px !important;
	}
</style>

<?php
$NoKO = $data_ko['NoKO'];
$button_app_ko = ($app_ko == 1) ? 'hidden' : '';
// die;
?>


<div class="content-wrapper atur">

	<section class="content-header">
		<h5>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
			<small class="text-red"><br><?= $data_ko['JobNo'] . ' - ' . $data_ko['JobNm']  ?> </small>
			<p></p>
		</h5>

		<ol class="breadcrumb">
			<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $judul ?></li>
		</ol>
	</section>

	<section class="content-header">
		<button onclick="history.back()" class="btn btn-sm btn-link" style="background-color:#34495e;color:white;"><i class="fa fa-arrow-left"></i> Back</button>
	</section>


	<section class="content-header">

		<?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>


		<?php


		if ($data_ko['ApprovedBy']  != ''  || $data_ko['ApprovedBy']  != NULL) {
			echo '
			<div class="row" style="padding-top:5px !important;padding-bottom:5px !important;">
				<div class="col-sm-12">
					<table width="50%"  border="0"   >
						<tr>
							<th width="10%"  >
								<label class="checkbox-inline">
								  <input type="checkbox" checked disabled="disabled"> Approved By <b style="color:blue;" >' . strtoupper($data_ko['ApprovedBy']) . '</b>
								</label>
							</th>
						</tr>
					</table>
				</div>
			</div>

			';
		}

		?>

		<div class="row" id="MOD-IN">


			<form id="form_saya" action="" method="POST">

				<div class="col-sm-12">

					<div class="box">

						<div class="box-header bg-success">
							<div class="box-tile">
								<h5>PENERIMA KONTRAK</h5>
							</div>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
							</div>
						</div>

						<div class="box-body">

							<div class="col-sm-6">



								<table width="100%" border="0">

									<tr>
										<th style="width:10%;padding-top:5px;padding-bottom:5px;">ID</th>
										<td style="width:10px;text-align: center;padding-top:5px;padding-bottom:5px;">:</td>
										<td style="padding-left:5px;padding-top:5px;padding-bottom:5px;">
											<select name="VendorId" id="VendorId" class="form-control input-sm select2" onchange="dapat_data_vendor(this.value)" <?= (cek_approve_kohdr($NoKO) == 1) ? 'disabled="disabled"' : ''  ?> required>
												<option value="">-- Pilih salah satu --</option>
												<?php
												foreach (dapat_list_vendor() as $data_vendor) : ?>

													<option value="<?= $data_vendor['VendorId'] ?>" <?= ($data_vendor['VendorId'] == $data_ko['VendorId']) ? 'selected' : '' ?>><?= $data_vendor['VendorId'] . ' - ' . $data_vendor['VendorNm'] ?></option>

												<?php endforeach; ?>
											</select>
										</td>
									</tr>

								</table>

								<table width="100%" border="0">

									<tr>
										<th style="width:10%;padding-top:5px;padding-bottom:5px;">Nama</th>
										<td style="width:10px;text-align: center;padding-top:5px;padding-bottom:5px;">:</td>
										<td style="padding-left:5px;padding-top:5px;padding-bottom:5px;">
											<input type="text" name="Nama" id="Nama" class="form-control input-sm" placeholder="Nama" disabled>
										</td>
									</tr>

								</table>

								<table width="100%" border="0">

									<tr>
										<th style="width:10%;padding-top:5px;padding-bottom:5px;">Alamat</th>
										<td style="width:10px;text-align: center;padding-top:5px;padding-bottom:5px;">:</td>
										<td style="padding-left:5px;padding-top:5px;padding-bottom:5px;">
											<textarea name="Alamat" id="Alamat" class="form-control input-sm" placeholder="Alamat" disabled></textarea>
										</td>
									</tr>

								</table>


								<table width="100%" border="0">

									<tr>
										<th style="width:10%;padding-top:5px;padding-bottom:5px;">Telepon</th>
										<td style="width:10px;text-align: center;padding-top:5px;padding-bottom:5px;">:</td>
										<td style="padding-left:5px;padding-top:5px;padding-bottom:5px;">
											<input type="text" name="Telepon" id="Telepon" class="form-control input-sm" placeholder="Telepon" disabled>
										</td>
									</tr>

								</table>

								<table width="100%" border="0">

									<tr>
										<th style="width:10%;padding-top:5px;padding-bottom:5px;">NPWP</th>
										<td style="width:10px;text-align: center;padding-top:5px;padding-bottom:5px;">:</td>
										<td style="padding-left:5px;padding-top:5px;padding-bottom:5px;">
											<input type="text" name="NPWP" id="NPWP" class="form-control input-sm" placeholder="NPWP" disabled>
										</td>
									</tr>

								</table>


							</div>

							<div class="col-sm-6">


								<table width="100%" border="0">

									<tr>
										<th style="width:15%;padding-top:5px;padding-bottom:5px;">Tgl Kontrak</th>
										<td style="width:10px;text-align: center;padding-top:5px;padding-bottom:5px;">:</td>
										<td style="padding-left:5px;padding-top:5px;padding-bottom:5px;">
											<input type="date" name="TglKO" id="TglKO" class="form-control input-sm" value="<?= $data_ko['TglKO'] ?>" <?= (cek_approve_kohdr($NoKO) == 1) ? 'disabled="disabled"' : ''  ?> required>
										</td>
									</tr>

								</table>

								<table width="100%" border="0">

									<tr>
										<th style="width:15%;padding-top:5px;padding-bottom:5px;">No. Kontrak</th>
										<td style="width:10px;text-align: center;padding-top:5px;padding-bottom:5px;">:</td>
										<td style="padding-left:5px;padding-top:5px;padding-bottom:5px;">
											<input type="text" class="form-control input-sm" placeholder="No. Kontrak" value="<?= $data_ko['NoKO'] ?>" disabled>
										</td>
									</tr>

								</table>

								<table width="100%" border="0">

									<tr>
										<th style="width:15%;padding-top:5px;padding-bottom:5px;">Job. No</th>
										<td style="width:10px;text-align: center;padding-top:5px;padding-bottom:5px;">:</td>
										<td style="padding-left:5px;padding-top:5px;padding-bottom:5px;">
											<input type="text" class="form-control input-sm" placeholder="Job.No" value="<?= $data_ko['JobNo'] . ' - ' . $data_ko['JobNm']  ?>" disabled>
										</td>
									</tr>

								</table>

								<table width="100%" border="0">

									<tr>
										<th style="width:15%;padding-top:5px;padding-bottom:5px;">Kategori</th>
										<td style="width:10px;text-align: center;padding-top:5px;padding-bottom:5px;">:</td>
										<td style="padding-left:5px;padding-top:5px;padding-bottom:5px;">
											<input type="text" name="Kategori" id="Kategori" class="form-control input-sm" placeholder="Kategori" disabled>
										</td>
									</tr>

								</table>

								<table width="100%" border="0">

									<tr>
										<th style="width:15%;padding-top:5px;padding-bottom:5px;">Bidang Usaha</th>
										<td style="width:10px;text-align: center;padding-top:5px;padding-bottom:5px;">:</td>
										<td style="padding-left:5px;padding-top:5px;padding-bottom:5px;">
											<input type="text" name="BidangUsaha" id="BidangUsaha" class="form-control input-sm" placeholder="Bidang Usaha" disabled>
										</td>
									</tr>

								</table>


							</div>


						</div>


						<div class="overlay loadingsaya" style="display: none;">
							<i class="fa fa-refresh fa-spin"></i>
						</div>


					</div>


				</div>

				<div class="col-sm-12">

					<div class="box">

						<div class="box-header bg-info">
							<div class="box-tile">
								<h5>RINCIAN PEKERJAAN</h5>
							</div>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
							</div>

						</div>

						<div class="box-body">
							<a href="javascript:void(0)" class="btn btn-sm <?= $button_app_ko ?>" onclick="tambah_rincian()" style="background-color:#636e72;color:white;<?= (cek_approve_kohdr($NoKO) == 1) ? 'display:none;' : ''  ?>">Tambah Rincian</a>
							<hr>
							<div class="tampil_rincian_pekerjaan">

							</div>
						</div>
						<div class="overlay loading_rincian" style="display: none;">
							<i class="fa fa-refresh fa-spin"></i>
						</div>

					</div>

				</div>


				<div class="col-sm-12">
					<div class="box">
						<div class="box-header bg-danger">
							<div class="box-tile">
								<h5>PERSYARATAN DAN SANKSI</h5>
							</div>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
							</div>
						</div>
						<div class="box-body">

							<div class="col-sm-6">

								<div class="form-group row">
									<label for="" class="col-sm-12 col-form-label">1. Wajib memenuhi persyaratan Kesehatan & Keselamatan Kerja (K3)&nbsp
										<input type="checkbox" name="K3" value="1" <?= ($data_ko['K3'] == 1) ? 'checked="checked"' : ''  ?> <?= (cek_approve_kohdr($NoKO) == 1) ? 'disabled="disabled"' : ''  ?> required>
									</label>
								</div>

								<div class="form-group">
									<label for="">2. Syarat Teknis</label>
									<textarea name="SyaratTeknis" id="SyaratTeknis" cols="5" rows="5" class="form-control input-sm" <?= (cek_approve_kohdr($NoKO) == 1) ? 'disabled="disabled"' : ''  ?>><?= $data_ko['SyaratTeknis'] ?></textarea>
								</div>
								<div class="form-group">
									<label for="">3. Jadwal Pengiriman</label>
									<textarea name="JadwalPengiriman" id="JadwalPengiriman" cols="5" rows="5" class="form-control input-sm" <?= (cek_approve_kohdr($NoKO) == 1) ? 'disabled="disabled"' : ''  ?>><?= $data_ko['JadwalPengiriman'] ?></textarea>
								</div>
								<div class="form-group">
									<label for="">4. Jadwal Pembayaran</label>
									<textarea name="JadwalPembayaran" id="JadwalPembayaran" cols="5" rows="5" class="form-control input-sm" <?= (cek_approve_kohdr($NoKO) == 1) ? 'disabled="disabled"' : ''  ?>><?= $data_ko['JadwalPembayaran'] ?></textarea>
								</div>
							</div>


							<div class="col-md-6">

								<div class="form-group row">
									<label for="">5. Syarat Pembayaran</label>
								</div>



								<div class="control-group after-add-more3">

									<ul class="todo-list">
										<div class="control-group after-add-more3">
											<li>
												<span class="handle">
													<i class="fa fa-ellipsis-v"></i>
													<i class="fa fa-ellipsis-v"></i>
												</span>
												<input type="checkbox" name="SyaratPembayaran[]" value="INV" <?= (CekBuktiPendukung($data_ko['SyaratPembayaran'], 'INV') == TRUE) ? "checked='checked' " : ''; ?> <?= (cek_approve_kohdr($NoKO) == 1) ? 'disabled="disabled"' : ''  ?>>
												<span class="text">Invoice/Kwitansi</span>
											</li>
										</div>

										<div class="control-group after-add-more3">
											<li>
												<span class="handle">
													<i class="fa fa-ellipsis-v"></i>
													<i class="fa fa-ellipsis-v"></i>
												</span>
												<input type="checkbox" name="SyaratPembayaran[]" value="SJ" <?= (CekBuktiPendukung($data_ko['SyaratPembayaran'], 'SJ') == TRUE) ? "checked='checked' " : ''; ?> <?= (cek_approve_kohdr($NoKO) == 1) ? 'disabled="disabled"' : ''  ?>>
												<span class="text">Surat Jalan/Tanda Terima Lapangan</span>
											</li>
										</div>

										<div class="control-group after-add-more3">
											<li>
												<span class="handle">
													<i class="fa fa-ellipsis-v"></i>
													<i class="fa fa-ellipsis-v"></i>
												</span>
												<input type="checkbox" name="SyaratPembayaran[]" value="PO" <?= (CekBuktiPendukung($data_ko['SyaratPembayaran'], 'PO') == TRUE) ? "checked='checked' " : ''; ?> <?= (cek_approve_kohdr($NoKO) == 1) ? 'disabled="disabled"' : ''  ?>>
												<span class="text">Copy PO</span>
											</li>
										</div>

										<div class="control-group after-add-more3">
											<li>
												<span class="handle">
													<i class="fa fa-ellipsis-v"></i>
													<i class="fa fa-ellipsis-v"></i>
												</span>
												<input type="checkbox" name="SyaratPembayaran[]" value="FP" <?= (CekBuktiPendukung($data_ko['SyaratPembayaran'], 'FP') == TRUE) ? "checked='checked' " : ''; ?> <?= (cek_approve_kohdr($NoKO) == 1) ? 'disabled="disabled"' : ''  ?>>
												<span class="text">Faktur Pajak</span>
											</li>
										</div>


										<div class="control-group after-add-more3">
											<li>
												<span class="handle">
													<i class="fa fa-ellipsis-v"></i>
													<i class="fa fa-ellipsis-v"></i>
												</span>
												<input type="checkbox" name="SyaratPembayaran[]" value="BAP" <?= (CekBuktiPendukung($data_ko['SyaratPembayaran'], 'BAP') == TRUE) ? "checked='checked' " : ''; ?> <?= (cek_approve_kohdr($NoKO) == 1) ? 'disabled="disabled"' : ''  ?>>
												<span class="text">Berita Acara Pembayaran</span>
											</li>
										</div>

										<div class="control-group after-add-more3">
											<li>
												<span class="handle">
													<i class="fa fa-ellipsis-v"></i>
													<i class="fa fa-ellipsis-v"></i>
												</span>
												<input type="checkbox" name="SyaratPembayaran[]" value="BAOP" <?= (CekBuktiPendukung($data_ko['SyaratPembayaran'], 'BAOP') == TRUE) ? "checked='checked' " : ''; ?> <?= (cek_approve_kohdr($NoKO) == 1) ? 'disabled="disabled"' : ''  ?>>
												<span class="text">Berita Acara Opname Pekerjaan</span>
											</li><br>
										</div>

									</ul>

								</div>
								<div class="form-group">
									<label for="">6. Sanksi</label>
									<textarea name="Sanksi" id="Sanksi" cols="5" rows="5" class="form-control input-sm" <?= (cek_approve_kohdr($NoKO) == 1) ? 'disabled="disabled"' : ''  ?>><?= $data_ko['Sanksi'] ?></textarea>
								</div>
								<div class="form-group">
									<label for="">7. Keterangan Lain</label>
									<textarea name="Keterangan" id="Keterangan" cols="5" rows="5" class="form-control input-sm" <?= (cek_approve_kohdr($NoKO) == 1) ? 'disabled="disabled"' : ''  ?>><?= $data_ko['Keterangan'] ?></textarea>
								</div>
							</div>


						</div>
					</div>
				</div>

				<div class="col-sm-12" style="padding-bottom:20px;">

					<input type="hidden" name="JobNo" id="JobNo" value="<?= $data_ko['JobNo'] ?>" readonly>
					<input type="hidden" name="NoKO" id="NoKO" value="<?= $data_ko['NoKO'] ?>" readonly>
					<input type="hidden" name="LedgerNo" id="LedgerNo" value="<?= $data_ko['LedgerNo'] ?>" readonly>

					<button onclick="history.back()" class="btn btn-sm btn-link pull-left" style="background-color:#34495e;color:white;"><i class="fa fa-arrow-left"></i> Batal</button>

					<button class="btn btn-md btn-primary pull-right btn_simpan <?= $button_app_ko ?>" type="submit" <?= (cek_approve_kohdr($NoKO) == 1) ? 'style="display:none;"' : ''  ?>>Simpan</button>

				</div>

			</form>

		</div>
	</section>


</div>


</section>

</section>

<div class="modal fade" id="modal_uraian" style="font-size: 10px;">
	<div class="modal-dialog modal-xs" id="modal_dialog_uraian">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="modal_title_uraian">Tambah Rincian Pekerjaan<br>
					<p style="color:#e74c3c"><?= $data_ko['JobNo'] . ' - ' . $data_ko['JobNm']  ?></p>
				</h6>

				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="modal_body_uraian">
				<div class="box">
					<div class="box-body">

						<form id="form_tambah_uraian">

							<table class="table" style="font-size: 10px;" width="100%" border="0">
								<tr>
									<th style="width: 10px;">No.</th>
									<td>:</td>
									<td>
										<input type="text" class="form-control input-sm" disabled>
									</td>
								</tr>

								<tr>
									<th style="width: 90px;">Kode. RAP</th>
									<td>:</td>
									<td>
										<select class="form-control input-sm select2" name="KdRAP" id="tambah_KdRAP" required>
											<option value="">-- Pilih Salah Satu --</option>
											<?php
											foreach (list_kode_rap($data_ko['JobNo'], 'B') as $data_rap) :

												echo "<option value='" . $data_rap['KdRAP'] . "'   >" . $data_rap['KdRAP'] . " - " . $data_rap['Uraian'] . "</option>";
											endforeach;
											?>
										</select>
									</td>
								</tr>


								<tr>
									<th>Uraian</th>
									<td>:</td>
									<td>
										<textarea class="form-control input-sm" name="Uraian" id="tambah_Uraian" placeholder="Isi Uraian" required></textarea>
									</td>
								</tr>

								<tr>
									<th>Vol</th>
									<td>:</td>
									<td>
										<input type="text" class="form-control input-sm" name="Vol" id="tambah_Vol" value="0" placeholder="Isi Harga Volume" autocomplete="off" required>
									</td>
								</tr>

								<tr>
									<th>Satuan</th>
									<td>:</td>
									<td>
										<input type="text" class="form-control input-sm" name="Uom" id="tambah_Uom" placeholder="Isi Satuan" autocomplete="off" required>
									</td>
								</tr>

								<tr>
									<th>Harga Satuan</th>
									<td>:</td>
									<td>
										<input type="text" class="form-control input-sm uang" name="HrgSatuan" id="tambah_HrgSatuan" value="0" placeholder="Isi Harga Satuan" autocomplete="off" required>
									</td>
								</tr>

								<tr>
									<th>Total Harga</th>
									<td>:</td>
									<td>
										<input type="text" class="form-control input-sm" id="tambah_TotalHarga" value="0" disabled required>
									</td>
								</tr>
								<tr>

									<td style="text-align: right;" colspan="3">
										<hr>

										<input type="hidden" name="NoKO" value="<?= $data_ko['NoKO'] ?>">
										<button type="submit" id="tambah_save" class="btn btn-sm bg-info">Save</button>
									</td>
								</tr>

							</table>
						</form>

					</div>

					<div class="overlay loading_uraian_tambah" style="display:none;">
						<i class="fa fa-refresh fa-spin"></i>
					</div>
				</div>



			</div>
		</div>

	</div>

</div>


<div class="modal fade" id="modal_uraian_edit" style="font-size: 10px;">
	<div class="modal-dialog modal-xs" id="modal_dialog_uraian_edit">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="modal_title_uraian_edit">Edit Rincian Pekerjaan<br>
					<p style="color:#e74c3c"><?= $data_ko['JobNo'] . ' - ' . $data_ko['JobNm']  ?></p>
				</h6>

				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="modal_body_uraian_edit">
				<div class="box">
					<div class="box-body">
						<form id="form_tambah_uraian_edit">
							<table class="table" style="font-size: 10px;" width="100%" border="0">
								<tr>
									<th style="width: 10px;">No.</th>
									<td>:</td>
									<td>
										<input type="text" class="form-control form-control-sm" disabled>
									</td>
								</tr>


								<tr>
									<th style="width: 90px;">Kode. RAP</th>
									<td>:</td>
									<td>
										<select class="form-control form-control-sm select2" name="KdRAP" id="edit_KdRAP" required>
											<option value="">-- Pilih Salah Satu --</option>
											<?php
											foreach (list_kode_rap($data_ko['JobNo'], 'B') as $data_rap) :

												echo "<option value='" . $data_rap['KdRAP'] . "'   >" . $data_rap['KdRAP'] . " - " . $data_rap['Uraian'] . "</option>";
											endforeach;
											?>
										</select>
									</td>
								</tr>


								<tr>
									<th>Uraian</th>
									<td>:</td>
									<td>
										<textarea class="form-control form-control-sm" name="Uraian" id="edit_Uraian" placeholder="Isi Uraian" required></textarea>
									</td>
								</tr>

								<tr>
									<th>Vol</th>
									<td>:</td>
									<td>
										<input type="text" class="form-control form-control-sm" name="Vol" id="edit_Vol" value="0" placeholder="Isi Harga Volume" autocomplete="off" required>
									</td>
								</tr>

								<tr>
									<th>Satuan</th>
									<td>:</td>
									<td>
										<input type="text" class="form-control form-control-sm" name="Uom" id="edit_Uom" placeholder="Isi Satuan" autocomplete="off" required>
									</td>
								</tr>

								<tr>
									<th>Harga Satuan</th>
									<td>:</td>
									<td>
										<input type="text" class="form-control form-control-sm uang" name="HrgSatuan" id="edit_HrgSatuan" value="0" placeholder="Isi Harga Satuan" autocomplete="off" required>
									</td>
								</tr>

								<tr>
									<th>Total Harga</th>
									<td>:</td>
									<td>
										<input type="text" class="form-control form-control-sm" id="edit_TotalHarga" value="0" disabled required>
									</td>
								</tr>
								<tr>

									<td style="text-align: right;" colspan="3">
										<hr>

										<input type="hidden" name="NoKO" id="noko_edit">
										<input type="hidden" name="NoUrut" id="norut_edit">
										<button type="submit" id="tambah_save" class="btn btn-sm bg-info">Simpan Edit</button>
									</td>
								</tr>

							</table>
						</form>

					</div>
					<div class="overlay loading_uraian_edit" style="display:none;">
						<i class="fa fa-refresh fa-spin"></i>
					</div>
				</div>



			</div>
		</div>

	</div>

</div>

<script type="text/javascript">
	$(document).ready(function() {

		dapat_data_vendor();
		tampil_rincian_pekerjaan();

		$('.uang').mask("#,##0", {
			reverse: true
		});

		$('.select2').select2({
			'theme': 'classic',
			'width': '100%',
		});

		$('#tambah_Vol').keyup(function() {
			hitung();
		});

		$('#tambah_HrgSatuan').keyup(function() {
			hitung();
		});

		$('#form_tambah_uraian').submit(function(e) {
			e.preventDefault();

			mydata = $(this).serialize();
			$.ajax({
				type: "post",
				url: "<?= site_url('Kontrak_po/aksi_simpan_uraian_ko_aksi_detail') ?>",
				data: mydata,
				dataType: "html",
				beforeSend: function() {
					$('.loading_uraian_tambah').show();
				},
				success: function(data) {
					$('.loading_uraian_tambah').hide();
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



					$('#modal_uraian').modal('hide');
					tampil_rincian_pekerjaan();
				},
				error: function() {
					$('.loading_uraian_tambah').hide();
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

		$('#form_tambah_uraian_edit').submit(function(e) {
			e.preventDefault();

			mydata = $(this).serialize();
			$.ajax({
				type: "post",
				url: "<?= site_url('Kontrak_po/aksi_edit_uraian_ko_aksi_detail') ?>",
				data: mydata,
				dataType: "html",
				beforeSend: function() {
					$('.loading_uraian_edit').show();
				},
				success: function(data) {
					$('.loading_uraian_edit').hide();

					if (data == 'berhasil') {
						$.toast({
							text: 'data_telah_diubah',
							position: 'bottom-right',
							stack: false,
							hideAfter: 1000,
							icon: 'success',

						})
					}

					if (data == 'gagal') {
						$.toast({
							text: 'data gagal diubah',
							position: 'bottom-right',
							stack: false,
							hideAfter: 1000,
							icon: 'error',

						})
					}



					$('#modal_uraian_edit').modal('hide');
					tampil_rincian_pekerjaan();
				},
				error: function() {
					$('.loading_uraian_edit').hide();
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

		$('#edit_Vol').keyup(function() {
			hitung_edit();
		})

		$('#edit_HrgSatuan').keyup(function() {
			hitung_edit();
		})


		$('#form_saya').submit(function(e) {
			e.preventDefault();

			$.ajax({
				type: "post",
				url: "<?= site_url('Kontrak_po/simpan_kohdr_kontrak_aksi_detail') ?>",
				data: $(this).serialize(),
				dataType: "JSON",
				beforeSend: function() {
					$('.btn_simpan').attr('disabled', true);
				},
				success: function(data) {
					$('.btn_simpan').attr('disabled', false);


					if (data.pesan == 'berhasil') {
						$.toast({
							text: 'berhasil mengubah data',
							position: 'bottom-right',
							stack: false,
							hideAfter: 1000,
							icon: 'success',

						});
						window.location.href = "<?= site_url('Kontrak_po/index/') ?>" + data.JobNo;
						return false;
					}

					if (data.pesan == 'gagal') {
						$.toast({
							text: 'gagal mengubah data',
							position: 'bottom-right',
							stack: false,
							hideAfter: 1000,
							icon: 'error',

						});
						return false;
					}

				},
				error: function() {
					$('.btn_simpan').attr('disabled', false);
					$.toast({
						text: 'Terjadi Kesalahan',
						position: 'bottom-right',
						stack: false,
						hideAfter: 1000,
						icon: 'error',

					});
				}
			});

		});


	});

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

	function hitung() {

		var HrgSatuan = $('#tambah_HrgSatuan').val();
		var Vol = $('#tambah_Vol').val();

		HrgSatuan = HapusKoma(HrgSatuan);
		Vol = HapusKoma(Vol);

		var total = parseInt(Vol) * parseInt(HrgSatuan);

		$('#tambah_TotalHarga').val(addCommas(total));

	}


	function dapat_data_vendor(VendorId = '') {

		if (VendorId == '') {
			var VendorId = $('#VendorId').val();
		}

		$.ajax({
				url: '<?= site_url('Kontrak_po/dapat_data_vendor') ?>',
				type: 'post',
				dataType: 'json',
				data: {
					'VendorId': VendorId
				},
				beforeSend: function() {
					$('.loadingsaya').show();
				}
			})
			.done(function(data) {
				$('.loadingsaya').hide();

				$('#Nama').val('');
				$('#Alamat').val('');
				$('#Telepon').val('');
				$('#NPWP').val('');

				$('#Kategori').val('');
				$('#BidangUsaha').val('');



				$('#Nama').val(data.VendorNm);
				$('#Alamat').val(data.Alamat);
				$('#Telepon').val(data.Telepon);
				$('#NPWP').val(data.NPWP);

				$('#Kategori').val(data.Kategori);
				$('#BidangUsaha').val(data.BidangUsaha);

			})
			.fail(function() {
				$('.loadingsaya').hide();
				$.toast({
					text: 'Terjadi Kesalahan',
					position: 'bottom-right',
					stack: false,
					hideAfter: 1000,
					icon: 'error',

				});
			})
			.always(function() {

			});

	}

	function tampil_rincian_pekerjaan() {

		var JobNo = $('#JobNo').val();
		var NoKO = $('#NoKO').val();

		$.ajax({
			type: "post",
			url: "<?= site_url('Kontrak_po/tampil_rincian_pekerjaan_aksi_detail') ?>",
			data: {
				'JobNo': JobNo,
				'NoKO': NoKO,
				'button_app_ko': '<?= $button_app_ko ?>'
			},
			dataType: "html",
			beforeSend: function() {
				$('.loading_rincian').show();
			},
			success: function(data) {
				$('.loading_rincian').hide();
				// alert(data);
				$('.tampil_rincian_pekerjaan').html('');
				$('.tampil_rincian_pekerjaan').html(data);

			},
			error: function() {
				$('.loading_rincian').hide();
				$.toast({
					text: 'Terjadi Kesalahan',
					position: 'bottom-right',
					stack: false,
					hideAfter: 1000,
					icon: 'error',

				});
			}
		});
	}

	function bersihkan_form_edit() {
		$('#edit_KdRAP').val('').trigger('change');
		$('#edit_Uraian').val('');
		$('#edit_Vol').val('');
		$('#edit_Uom').val('');
		$('#edit_HrgSatuan').val('');
		$('#edit_TotalHarga').val('');
	}

	function bersihkan_form_tambah() {
		$('#tambah_KdRAP').val('').trigger('change');
		$('#tambah_Uraian').val('');
		$('#tambah_Vol').val('1');
		$('#tambah_Uom').val('');
		$('#tambah_HrgSatuan').val('0');
		$('#tambah_TotalHarga').val('0');
	}


	function tambah_rincian() {
		bersihkan_form_tambah();
		$('#modal_uraian').modal('show');
	}

	function hapus_uraian(NoUrut) {

		var NoKO = $('#NoKO').val();

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

				$.ajax({
					type: "POST",
					url: "<?= site_url('Kontrak_po/hapus_uraian_kontrak_aksi_detail') ?>",
					data: {
						'NoUrut': NoUrut,
						'NoKO': NoKO
					},
					dataType: "html",
					beforeSend: function() {
						$('.loading_rincian').show();
					},
					success: function(data) {
						$('.loading_rincian').hide();

						if (data == 'berhasil') {
							$.toast({
								text: 'data telah dihapus',
								position: 'bottom-right',
								stack: false,
								hideAfter: 1000,
								icon: 'success',

							})
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

						tampil_rincian_pekerjaan();

					},
					error: function() {
						$('.loading_rincian').hide();
						$.toast({
							text: 'Terjadi Kesalahan',
							position: 'bottom-right',
							stack: false,
							hideAfter: 1000,
							icon: 'error',

						})
					}
				});


			}
		})
	}

	function edit_uraian(NoUrut) {

		var NoKO = $('#NoKO').val();

		$.ajax({
			type: "POST",
			url: "<?= site_url('Kontrak_po/dapatkan_data_kodtl_aksi_detail') ?>",
			data: {
				'NoUrut': NoUrut,
				'NoKO': NoKO
			},
			dataType: "json",
			beforeSend: function() {
				$('.loading_rincian').show();
			},
			success: function(data) {
				$('.loading_rincian').hide();
				bersihkan_form_edit();

				$('#modal_uraian_edit').modal('show');
				// alert(data);

				$('#edit_KdRAP').val(data.KdRAP).trigger('change');
				$('#edit_Uraian').val(data.Uraian);
				$('#edit_Vol').val(data.Vol);
				$('#edit_Uom').val(data.Uom);
				$('#edit_HrgSatuan').val(data.HrgSatuan);
				$('#noko_edit').val(data.NoKO);
				$('#norut_edit').val(data.NoUrut);

				hitung_edit();



			},
			error: function() {
				$('.loading_rincian').hide();
				$.toast({
					text: 'Terjadi Kesalahan',
					position: 'bottom-right',
					stack: false,
					hideAfter: 1000,
					icon: 'error',

				})
			}
		});

	}

	function hitung_edit() {

		var HrgSatuan = $('#edit_HrgSatuan').val();
		var Vol = $('#edit_Vol').val();

		HrgSatuan = HapusKoma(HrgSatuan);
		Vol = HapusKoma(Vol);

		var total = parseInt(Vol) * parseInt(HrgSatuan);

		$('#edit_TotalHarga').val(addCommas(total));

	}
</script>