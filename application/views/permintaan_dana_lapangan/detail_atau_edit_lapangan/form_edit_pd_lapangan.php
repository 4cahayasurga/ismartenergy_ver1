<style type="text/css">
	.row_saya {
		font-size: 10px !important;
		font-family: Arial, Helvetica, sans-serif;
	}

	table td th {
		font-size: 10px !important;
		

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
	.disabledbutton {
        pointer-events: none;
        opacity: 0.4;
    }



	/*table{
		padding-top: 5px;
	}*/
</style>

<div class="content-wrapper">
	<section class="content-header">

		<h6><?=$judul?></h6>
		<p></p>

		<label for="inputName" class="col-sm col-form-label"><?=$dapat_judul['JobGabung']?></label>
		<br>
		<small><label for="inputName" class="col-sm col-form-label"><?=$dapat_judul['Lokasi']?></label></small>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('dashboard')  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?=$judul?></li>
		</ol>
	</section>

	<section class="content-header">
		<button class="btn btn-xs" style="background-color:#7f8c8d;" onclick="history.back()"><i class="fa fa-arrow-left"></i> Back</button>
	</section>

	<section class="content">

		<?= ($this->session->flashdata('message')) ? $this->session->flashdata('message') : ''    ?>



		

		<?php 
		$text_button_mati='';
        $tidak_hidup = '';


        if (cek_approve_AK($JobNo,$Alokasi,$NoPD) == TRUE) {
        	$text_button_mati='text_button_mati';
        	$tidak_hidup = 'disabled';
        	echo '
        	<div class="row" style="padding-top:5px !important;padding-bottom:5px !important;">
				<div class="col-sm-12">
					<table width="50%"  border="0" style="background-color:#3498db;color:white;"  >
						<tr>
							<th width="10%" >
								<label class="checkbox-inline">
								  <input type="checkbox" checked> Approved AK
								</label>
							</th>
						</tr>
					</table>
				</div>
			</div>';
        }?>

        <?php 
        if(cek_approve_KK_atau_KT($JobNo,$Alokasi,$NoPD,'lapangan')  !=='' ){
        	$text_button_mati='text_button_mati';

        	echo '
        	<div class="row" style="padding-top:5px !important;padding-bottom:5px !important;">
				<div class="col-sm-12">
					<table width="50%" border="0" style="background-color:#3498db;color:white;"  >
						<tr>
							<th width="10%">
								<label class="checkbox-inline">
								  <input type="checkbox" checked> '.cek_approve_KK_atau_KT($JobNo,$Alokasi,$NoPD,'lapangan').'
								</label>
							</th>
						</tr>
					</table>
				</div>
			</div>';
        }
        ?>	

        <?php
        if(cek_sudah_ada_pembayaran_atau_belum($JobNo,$Alokasi,$NoPD) == TRUE ){
        	$text_button_mati='text_button_mati';
        	echo '
        	<div class="row" style="padding-top:5px !important;padding-bottom:5px !important;">
				<div class="col-sm-12">
					<table width="50%" border="0" style="background-color:#3498db;color:white;">
						<tr>
							<th width="10%">
								<label class="checkbox-inline">
								  <input type="checkbox" checked> Sudah ada pembayaran
								</label>
							</th>
						</tr>
					</table>
				</div>
			</div>';
        }

        ?>


		<div class="box box-success">

			<div class="box-header with-border">
				<h1 class="box-title mykonten">Entry Permintaan Dana ( Lapangan )</h1>

				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
				</div>
			</div>
			<div class="box-body">


				<form id="form_saya">

					<div class="row">

						<div class="col-sm-6 row_saya">

							<table width="100%" border="0" class="">
								<tr>
									<th style="width:80px;">No. PD</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" class="form-control input-sm" value="<?=$NoPD?>" disabled>
									</td>
									<th>No. BAP/Ref Lapangan : </th>
									<td>
										<input type="text" class="form-control input-sm" name="NoRef" id="NoRef" placeholder="No.Ref" value="<?=$data_pdhdr['NoRef']?>" readonly>
									</td>
								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Job No</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" class="form-control input-sm" value="<?= $dapat_judul['JobNo'] ?> - <?= $dapat_judul['JobNm'] ?>" disabled>
									</td>

								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Tgl Permintaan</th>
									<td style="width:10px;">:</td>
									<td style="width: 200px;">
										<input type="date" class="form-control input-sm" name="TglPD" value="<?=$data_pdhdr['TglPD']?>" required>
									</td>
									<td>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="checkbox" name="Jaminan" id="Jaminan" value="1" <?= ($data_pdhdr['Jaminan'] == 1) ? 'checked' : ''    ?>  >
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
										<textarea name="Deskripsi" id="Deskripsi" class="form-control input-sm" placeholder="Isi Deskripsi"><?=strtoupper($data_pdhdr['Deskripsi'])?></textarea>
									</td>


								</tr>
							</table>


							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Periode</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="date" name="PrdAwal" id="PrdAwal" title="Periode Awal" value="<?= $data_pdhdr['PrdAwal'] ?>" class="form-control input-sm" required>
									</td>
									<td style="width:10px !important;">s.d.</td>
									<td>
										<input type="date" name="PrdAkhir" id="PrdAkhir" title="Periode Akhir" value="<?= $data_pdhdr['PrdAkhir'] ?>" class="form-control input-sm" required>
									</td>
									<td>Minggu Ke: </td>
									<td style="width:50px !important;">
										<input type="text" name="Minggu" id="Minggu" class="form-control input-sm" value="<?=$data_pdhdr['Minggu']?>" >
									</td>
								</tr>
							</table>


							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Alokasi</th>
									<td style="width:10px;">:</td>
									<td>
										<select class="form-control input-sm select2" id="AlokasiGabung" disabled="disabled" required>
											<option value="">-- Pilih Alokasi --</option>

											<?php
											foreach (list_job_alokasi() as $data_alokasi) :
												$pilih = ($data_alokasi['Alokasi'] == $Alokasi) ? 'selected' : '';
												echo "<option value='" . $data_alokasi['Alokasi'] . "' " . $pilih . "  >" . $data_alokasi['AlokasiGabung'] . "</option>";
											endforeach;
											?>

										</select>
									</td>

									<td style="width:70px;">Tipe Form: </td>
									<td>
										<select name="TipeForm" id="TipeForm" class="form-control input-sm select2" onchange="dapatkan_no_ko(this.value)" <?=$tidak_hidup?> required>
											<option value="">--Pilih Salah Satu--</option>
											<?php
											foreach (list_tipe_form($Alokasi) as $data_tipe) :
												$checked = ($data_pdhdr['TipeForm'] == $data_tipe['TipeForm']) ? 'selected' : '';
												echo "<option value='" . $data_tipe['TipeForm'] . "' " . $checked . " >" . $data_tipe['tipe_form_gabung'] . "</option>";

											endforeach;
											?>
											<!-- <input type="hidden" name="TipeForm" class="form-control form-control-sm" value="<?= $data_pdhdr['TipeForm'] ?>"> -->
										</select>
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
											<input class="form-check-input" type="checkbox" id="INV" value="INV" name="BuktiPendukung[]" <?= (CekBuktiPendukung($data_pdhdr['BuktiPendukung'], 'INV') == TRUE) ? 'checked' : '' ?>>
											<label class="form-check-label" for="INV">Invoice/Kwitansi</label>
										</div><br>

										<div class="form-check form-check-inline" style="font-size: 10px;">
											<input class="form-check-input" type="checkbox" id="SJ" value="SJ" name="BuktiPendukung[]" <?= (CekBuktiPendukung($data_pdhdr['BuktiPendukung'], 'SJ') == TRUE) ? 'checked' : '' ?>>
											<label class="form-check-label" for="SJ">Surat Jalan/Tanda Terima Lapangan</label>
										</div><br>

										<div class="form-check form-check-inline" style="font-size: 10px;">
											<input class="form-check-input" type="checkbox" id="PO" value="PO" name="BuktiPendukung[]" <?= (CekBuktiPendukung($data_pdhdr['BuktiPendukung'], 'PO') == TRUE) ? 'checked' : '' ?>>
											<label class="form-check-label" for="PO">Copy PO</label>
										</div>


									</td>
									<td>
										<div class="form-check form-check-inline" style="font-size: 10px;">
											<input class="form-check-input" type="checkbox" id="FP" value="FP" name="BuktiPendukung[]" <?= (CekBuktiPendukung($data_pdhdr['BuktiPendukung'], 'FP') == TRUE) ? 'checked' : '' ?>>
											<label class="form-check-label" for="FP">Faktur Pajak</label>
										</div><br>

										<div class="form-check form-check-inline" style="font-size: 10px;">
											<input class="form-check-input" type="checkbox" id="BAP" value="BAP" name="BuktiPendukung[]" <?= (CekBuktiPendukung($data_pdhdr['BuktiPendukung'], 'BAP') == TRUE) ? 'checked' : '' ?>>
											<label class="form-check-label" for="BAP">Berita Acara Pembayaran</label>
										</div><br>

										<div class="form-check form-check-inline" style="font-size: 10px;">
											<input class="form-check-input" type="checkbox" id="BAOP" value="BAOP" name="BuktiPendukung[]" <?= (CekBuktiPendukung($data_pdhdr['BuktiPendukung'], 'BAOP') == TRUE) ? 'checked' : '' ?>>
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
										<input type="text" name="Keterangan" id="Keterangan" placeholder="Keterangan" class="form-control input-sm" value="<?= $data_pdhdr['Keterangan'] ?>">
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
							<?php
                            $my_no_ko = $JobNo . '_INT';
                            $hanya_liat = '';
                            $hanya_liat_khusus_bank = '';
                            if ($data_pdhdr['NoKO'] !== $my_no_ko) {
                                $hanya_liat = 'readonly="readonly"';
                                $hanya_liat_khusus_bank = 'disabled="disabled"';
                            }

                            ?>


							<table width="100%" border="0">
                                <tr>
                                    <th style="width:80px;">No. Kontrak/PO</th>
                                    <td style="width:10px;">:</td>
                                    <td class="atur_no_ko">
                                        <input type="text" class="form-control input-sm" name="NoKO" value="<?= $data_pdhdr['NoKO'] ?>" readonly required>

                                    </td>

                                </tr>
                            </table>


							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">No. Tagihan</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="NoTagihan" id="NoTagihan" class="form-control input-sm" placeholder="No. Tagihan" value="<?= $data_pdhdr['NoTagihan'] ?>">
									</td>

								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">ID</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="VendorId" id="VendorId" class="form-control input-sm" placeholder="ID" <?= $hanya_liat ?>>
									</td>

								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Nama</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="Nama" id="Nama" class="form-control input-sm" placeholder="Nama" value="<?= $data_pdhdr['Nama'] ?>" <?= $hanya_liat ?>>
									</td>

								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Alamat</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="Alamat" id="Alamat" class="form-control input-sm" placeholder="Alamat" value="<?= $data_pdhdr['Alamat'] ?>" <?= $hanya_liat ?>>
									</td>

								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Telepon</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="Telepon" id="Telepon" class="form-control input-sm" placeholder="Telepon" value="<?= $data_pdhdr['Telepon'] ?>" <?= $hanya_liat ?>>
									</td>

								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">NPWP</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="NPWP" id="NPWP" class="form-control input-sm" placeholder="NPWP" value="<?= $data_pdhdr['NPWP'] ?>" <?= $hanya_liat ?>>
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
										<input type="text" name="NoRek" id="NoRek" class="form-control input-sm" placeholder="No. Rekening" value="<?= $data_pdhdr['NoRek'] ?>" <?= $hanya_liat ?>>
									</td>
								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Atas Nama</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="AtasNama" id="AtasNama" class="form-control input-sm" placeholder="Atas Nama" value="<?= $data_pdhdr['AtasNama'] ?>" <?= $hanya_liat ?>>
									</td>
								</tr>
							</table>

							<table width="100%" border="0">
                                <tr>
                                    <th style="width:80px;">Bank</th>
                                    <td style="width:10px;">:</td>

                                    <td>

                                        <select class="form-control input-sm select2" id="Bank" onchange="isi_bank(this.value)" <?= $hanya_liat_khusus_bank ?>>
                                            <option value="">-- Pilih Bank --</option>
                                            <?php
                                            foreach (list_data_bank() as $banksaya) { ?>
                                                <?php
                                                $banklist = str_replace(' ', '', $banksaya['Bank']);
                                                $bank_pd = str_replace(' ', '', $data_pdhdr['Bank']);

                                                $coba = '';
                                                if ($bank_pd == $banklist) {
                                                    $coba = 'selected';
                                                } else {
                                                    $coba = '';
                                                }
                                                ?>
                                                <option value="<?= $banksaya['Bank'] ?>" <?= $coba ?>><?= $banksaya['Bank'] ?></option>
                                            <?php } ?>

                                        </select>
                                        <input type="hidden" id="mybank" name="Bank" value="<?= $data_pdhdr['Bank'] ?>" class="form-control form-control-sm">

                                    </td>
                                </tr>
                            </table>


						</div>

						<div class="col-sm-12">
							<hr style="border-top:1px solid black;">

							<input type="hidden" name="NoPD" id="NoPD" value="<?= $NoPD ?>">
                            <input type="hidden" name="JobNo" id="JobNo" value="<?= $JobNo ?>">
                            <input type="hidden" name="Alokasi" id="Alokasi" value="<?= $Alokasi ?>">
                            <input type="hidden" name="LedgerNo" value="<?=$LedgerNo?>">
                            <input type="hidden" name="text_button_mati" id="text_button_mati" value="<?= $text_button_mati ?>">
                            <button type="submit" id="btn-simpan-pd" class="btn btn-success btn-md pull-right <?= $text_button_mati ?>">Update</button>
							<button class="btn btn-xs" style="background-color:#7f8c8d;" onclick="history.back()"><i class="fa fa-arrow-left"></i> Back</button>

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

					<a href="javascript:void(0)" class="btn btn-xs bg-primary <?= $text_button_mati ?>" onclick="tambah_uraian()">Tambah Uraian</a>

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
				<h6 class="modal-title" id="modal_title_uraian">Tambah Uraian Permintaan Dana || MDL UPDATE PD<br>
					<p style="color:#e74c3c"><?= $dapat_judul['JobNo'] ?> - <?= $dapat_judul['JobNm'] ?></p>
				</h6>

				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="modal_body_uraian">
				<div class="box">
					<div class="box-body">
						<form id="form_tambah_uraian_mdl_edit">
							<table class="table" style="font-size: 10px;" width="100%" border="0">
								<tr>
									<th style="width: 10px;">No.</th>
									<td>:</td>
									<td>
										<input type="text" class="form-control input-sm" disabled>
									</td>
								</tr>

								<tr>
									<th>Alokasi</th>
									<td>:</td>
									<td>
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
								</tr>

								<tr>
									<th style="width: 90px;">Kode. RAP</th>
									<td>:</td>
									<td>
										<select class="form-control input-sm select2" name="KdRAP" id="tambah_KdRAP" required>
											<option value="">-- Pilih Salah Satu --</option>
											<?php
											foreach (list_kode_rap($JobNo, $Alokasi) as $data_rap) :

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
										<input type="hidden" name="NoPD" value="<?= $NoPD?>">
										<input type="hidden" name="JobNo" value="<?= $JobNo ?>">
										<input type="hidden" name="Alokasi" value="<?= $Alokasi ?>">
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
				<h6 class="modal-title" id="modal_title_uraian_edit">Edit Uraian Permintaan Dana || MDL UPDATE PD<br>
					<p style="color:#e74c3c"><?= $dapat_judul['JobNo'] ?> - <?= $dapat_judul['JobNm'] ?></p>
				</h6>

				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="modal_body_uraian_edit">
				<div class="box">
					<div class="box-body">
						<form id="form_tambah_uraian_edit_mdl_edit">
							<table class="table" style="font-size: 10px;" width="100%" border="0">
								<tr>
									<th style="width: 10px;">No.</th>
									<td>:</td>
									<td>
										<input type="text" class="form-control form-control-sm" disabled>
									</td>
								</tr>

								<tr>
									<th>Alokasi</th>
									<td>:</td>
									<td>
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
								</tr>

								<tr>
									<th style="width: 90px;">Kode. RAP</th>
									<td>:</td>
									<td>
										<select class="form-control form-control-sm select2" name="KdRAP" id="edit_KdRAP" required>
											<option value="">-- Pilih Salah Satu --</option>
											<?php
											foreach (list_kode_rap($JobNo, $Alokasi) as $data_rap) :

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
										<input type="hidden" name="JobNo" value="<?= $JobNo ?>">
										<input type="hidden" name="Alokasi" value="<?= $Alokasi ?>">
										<input type="hidden" name="NoPD" id="nopd_edit">
										<input type="hidden" name="NoUrut" id="norut_edit">
										<button type="submit" id="tambah_save_edit" class="btn btn-sm bg-info">Simpan Edit</button>
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

		$('.uang').mask("#,##0", {
			reverse: true
		});
		
		$('.select2').select2({
			'theme': 'classic',
			'width': '100%',

		});

		dapatkan_pddtl_edit();

		$('#form_saya').submit(function(e) {
			e.preventDefault();

			var TotalPD = $('#mytotalpd').val();
			var JobNo = $('#JobNo').val();

			// alert(TotalPD);
			// return false;

			$.ajax({
				type: "post",
				url: "<?= site_url('Permintaan_dana_lapangan/simpan_pdhdr_mdl_edit') ?>",
				data: $(this).serialize() + "&TotalPD=" + TotalPD,
				dataType: "html",
				beforeSend: function() {
					$('.loadingku').show();
				},
				success: function(data) {
					$('.loadingku').hide();
                    // alert(data);
                    // return false;

                    if (data == 'pd_nol') {
                    	$.toast({
                    		text: 'Isi Uraian Terlebih Dahulu',
                    		position: 'bottom-right',
                    		stack: false,
                    		hideAfter: 1000,
                    		icon: 'info',

                    	});
                    	return false;
                    }


                    if (data == 'berhasil') {
                    	window.location.href = "<?= site_url('Permintaan_dana_lapangan/view/') ?>"+JobNo;
                    	return false;
                    }

                    if (data == 'gagal') {
                    	$.toast({
                    		text: 'gagal menambahkan data',
                    		position: 'bottom-right',
                    		stack: false,
                    		hideAfter: 1000,
                    		icon: 'error',

                    	});
                    	return false;
                    }



                },
                error: function() {
                	$('.loadingku').hide();
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


		$('#form_tambah_uraian_mdl_edit').submit(function(e) {
			e.preventDefault();

			mydata = $(this).serialize();
			$.ajax({
				type: "post",
				url: "<?= site_url('Permintaan_dana_lapangan/aksi_simpan_uraian_pd_mdl_edit') ?>",
				data: mydata,
				dataType: "html",
				beforeSend: function() {
					$('.loading_uraian_tambah').show();
				},
				success: function(data) {
					$('.loading_uraian_tambah').hide();

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
					dapatkan_pddtl_edit();
				},
				error: function() {
					$('.loading_uraian_tambah').hide();
					$.toast({
						text: 'Terjadi Kesalahan',
						position: 'bottom-right',
						stack: false,
						hideAfter: 1000,
						icon: 'error',

					})
				}
			});

		})

		$('#form_tambah_uraian_edit_mdl_edit').submit(function(e) {
			e.preventDefault();

			mydata = $(this).serialize();
			$.ajax({
				type: "post",
				url: "<?= site_url('Permintaan_dana_lapangan/aksi_edit_uraian_pd_mdl_edit') ?>",
				data: mydata,
				dataType: "html",
				beforeSend: function() {
					$('.loading_uraian_edit').show();
				},
				success: function(data) {
					$('.loading_uraian_edit').hide();

					// alert(data);
					// return false;

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
					dapatkan_pddtl_edit();
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

		})

		$('#tambah_Vol').keyup(function() {
			hitung();
		})

		$('#tambah_HrgSatuan').keyup(function() {
			hitung();
		})

		$('#edit_Vol').keyup(function() {
			hitung_edit();
		})

		$('#edit_HrgSatuan').keyup(function() {
			hitung_edit();
		})

	});


function hitung() {

	var HrgSatuan = $('#tambah_HrgSatuan').val();
	var Vol = $('#tambah_Vol').val();

	HrgSatuan = HapusKoma(HrgSatuan);
	Vol = HapusKoma(Vol);

	var total = parseInt(Vol) * parseInt(HrgSatuan);

	$('#tambah_TotalHarga').val(addCommas(total));

}

function hitung_edit() {

	var HrgSatuan = $('#edit_HrgSatuan').val();
	var Vol = $('#edit_Vol').val();

	HrgSatuan = HapusKoma(HrgSatuan);
	Vol = HapusKoma(Vol);

	var total = parseInt(Vol) * parseInt(HrgSatuan);

	$('#edit_TotalHarga').val(addCommas(total));

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

function dapatkan_pddtl_edit() {
	var NoPD = $('#NoPD').val();
	var text_button_mati = $('#text_button_mati').val();

	$.ajax({
		type: "post",
		url: "<?= site_url('Permintaan_dana_lapangan/dapatkan_pddtl_edit') ?>",
		data: {
			'NoPD': NoPD,
			'text_button_mati': text_button_mati
		},
		dataType: "html",
		beforeSend: function() {
			$('.loadingku').show();
		},
		success: function(data) {
			$('.loadingku').hide();
			$("#isitable").html("");
			$('#isitable').html(data);

		},
		error: function() {
			$('.loadingku').hide();
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

function dapatkan_no_ko(NoKO) {

	var JobNo = $('#JobNo').val();
	var NoPD = $('#NoPD').val();
	var Alokasi = $('#Alokasi').val();

	$.ajax({
		type: "post",
		url: "<?= site_url('Permintaan_dana_lapangan/dapat_no_ko_dan_vendor') ?>",
		data: {
			'NoKO': NoKO,
			'JobNo': JobNo,
			'NoPD': NoPD,
			'Alokasi': Alokasi,
		},
		dataType: "html",
		beforeSend: function() {
			$('.loadingku').show();
		},
		success: function(data) {
			$('.loadingku').hide();
			$('.atur_no_ko').html('');
			$('.atur_no_ko').html(data);
		},
		error: function() {
			$('.loadingku').hide();
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

function dapat_vendor() {
	var NoKO = $('#NoKO').val();
	var VendorId = $('#NoKO :selected').attr('data-vendor');


	$.ajax({
		type: "post",
		url: "<?= site_url('Permintaan_dana_lapangan/dapat_vendor') ?>",
		data: {
			'NoKO': NoKO,
			'VendorId': VendorId,
		},
		dataType: "json",
		beforeSend: function() {
			$('.loadingku').show();
		},
		success: function(data) {
        	// alert(data.Status);
        	$('.loadingku').hide();



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
        		$('#Nama').val(data.Nama).attr('readonly', true);
        		$('#Alamat').val(data.Alamat).attr('readonly', true);
        		$('#Telepon').val(data.Telepon).attr('readonly', true);
        		$('#NPWP').val(data.NPWP).attr('readonly', true);
        		$('#NoRek').val(data.NoRek).attr('readonly', true);
        		$('#AtasNama').val(data.AtasNama).attr('readonly', true);
        		$('#Bank').val(data.Bank).trigger('change').attr('disabled', true);
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
        	$('.loadingku').hide();
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
function isi_bank(isi) {
	$('#mybank').val('');
	$('#mybank').val(isi);
}


function tambah_uraian() {
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

function hapus_uraian_mdl_edit(NoUrut) {
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

			$.ajax({
				type: "POST",
				url: "<?= site_url('Permintaan_dana_lapangan/hapus_uraian_mdl_edit') ?>",
				data: {
					'NoUrut': NoUrut,
					'NoPD': NoPD
				},
				dataType: "html",
				beforeSend: function() {
					$('.loadingku').show();
				},
				success: function(data) {
					$('.loadingku').hide();

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

					dapatkan_pddtl_edit();

				},
				error: function() {
					$('.loadingku').hide();
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


function edit_uraian_mdl_edit(NoUrut) {



	var NoPD = $('#NoPD').val();
	var text_button_mati = $('#text_button_mati').val();

	$.ajax({
		type: "POST",
		url: "<?= site_url('Permintaan_dana_lapangan/dapatkan_data_pddtl_mdl_edit') ?>",
		data: {
			'NoUrut': NoUrut,
			'NoPD': NoPD,
			'text_button_mati' : text_button_mati
		},
		dataType: "json",
		beforeSend: function() {
			$('.loadingku').show();
		},
		success: function(data) {
			$('.loadingku').hide();
			bersihkan_form();

			$('#modal_uraian_edit').modal('show');


			$('#edit_KdRAP').val(data.KdRAP).trigger('change');
			$('#edit_Uraian').val(data.Uraian);
			$('#edit_Vol').val(data.Vol);
			$('#edit_Uom').val(data.Uom);
			$('#edit_HrgSatuan').val(data.HrgSatuan);
			$('#nopd_edit').val(data.NoPD);
			$('#norut_edit').val(data.NoUrut);
			$('#tambah_save_edit').removeClass(text_button_mati).addClass(text_button_mati);

			hitung_edit();



		},
		error: function() {
			$('.loadingku').hide();
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

</script>