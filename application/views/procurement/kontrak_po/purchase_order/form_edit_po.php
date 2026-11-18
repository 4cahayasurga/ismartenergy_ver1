<style type="text/css">
    .atur {
        font-size: 10px;
        /* font-family: 'Courier New', Courier, monospace; */
        font-family: Verdana, Geneva, Tahoma, sans-serif;

        font-family: Arial, Helvetica, sans-serif;
    }

    input {
        font-size: 10px !important;
        font-family: Arial, Helvetica, sans-serif;
    }

    select {
        font-size: 10px !important;
        font-family: Arial, Helvetica, sans-serif;
    }

    textarea {
        font-size: 10px !important;
        font-family: Arial, Helvetica, sans-serif;
    }
</style>

<div class="content-wrapper atur">

    <section class="content-header">
        <h5>
            <label class="col-lg col-form-label"><?php echo $judul ?> </label>
            <small class="text-red"><br><?= $data_po['NoKO'] . ' - ' . $data_po['JobNm'] . ' - ' . $data_po['VendorNm']  ?></small>
            <p></p>
        </h5>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?php echo $judul ?></li>
        </ol>
    </section>


    <section class="content-header atur">
        <button onclick="history.back()" class="btn btn-sm btn-link" style="background-color:#34495e;color:white;"><i class="fa fa-arrow-left"></i> Back</button>
    </section>


    <section class="content-header atur">

        <?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>

        <?php
        $button_app_ko = ($app_ko == 1) ? 'hidden' : '';
        // die;
        ?>

        <?php
        $button_tampil = '';
        if ($data_po['ApprovedBy']  != ''  || $data_po['ApprovedBy']  != NULL) {
            $button_tampil = 'hidden';
            echo '
			<div class="row" style="padding-top:5px !important;padding-bottom:5px !important;">
				<div class="col-sm-12">
					<table width="50%"  border="0"   >
						<tr>
							<th width="10%"  >
								<label class="checkbox-inline">
								  <input type="checkbox" checked disabled="disabled"> Approved By <b style="color:blue;" >' . strtoupper($data_po['ApprovedBy']) . '</b>
								</label>
							</th>
						</tr>
					</table>
				</div>
			</div>

			';
        }

        ?>


        <form id="form_kohdr">

            <div class="row">


                <div class="col-sm-4">

                    <div class="box">
                        <div class="box-header" style="background-color: #1abc9c;color:white;">
                            <div class="box-tile">
                                <small>PENERIMA PO</small>
                            </div>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool text-black" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool text-black" data-widget="remove"><i class="fa fa-remove"></i></button>
                            </div>
                        </div>
                        <div class="box-body">

                            <div class="row">

                                <div class="col-sm-12">

                                    <table width="100%" border="0">
                                        <tr>
                                            <th width="70px" style="padding-top:10px;padding-bottom:10px;">ID</th>
                                            <td>
                                                <select name="VendorId" id="VendorId" class="form-control input-sm select2" onchange="dapat_data_vendor(this.value)" required>
                                                    <option value="">-- Pilih salah satu --</option>
                                                    <?php
                                                    foreach (dapat_list_vendor() as $data_vendor) :
                                                    ?>

                                                        <option value="<?= $data_vendor['VendorId'] ?>" <?= ($data_vendor['VendorId'] == $data_po['VendorId']) ? 'selected' : '';  ?>><?= $data_vendor['VendorId'] . ' - ' . $data_vendor['VendorNm'] ?></option>

                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th style="padding-top:10px;padding-bottom:10px;">Nama</th>
                                            <td>
                                                <input type="text" class="form-control input-sm" id="Nama" placeholder="Nama" disabled>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th style="padding-top:10px;padding-bottom:10px;">Alamat</th>
                                            <td>
                                                <textarea id="Alamat" class="form-control input-sm" value="" placeholder="Alamat" disabled></textarea>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th style="padding-top:10px;padding-bottom:10px;">Telepon</th>
                                            <td>
                                                <input type="text" class="form-control input-sm" id="Telepon1" value="" placeholder="Telepon" disabled>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th style="padding-top:10px;padding-bottom:10px;">NPWP</th>
                                            <td>
                                                <input type="text" class="form-control input-sm" id="NPWP" value="" placeholder="NPWP" disabled>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th style="padding-top:10px;padding-bottom:10px;">UP</th>
                                            <td>
                                                <input type="text" class="form-control input-sm" id="ContactPerson" value="" placeholder="ContactPerson" disabled>
                                            </td>
                                        </tr>

                                    </table>


                                </div>




                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="box">
                        <div class="box-header" style="background-color: #8e44ad;color:white;">
                            <div class="box-tile">
                                <small>DETAIL</small>
                            </div>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool text-black" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool text-black" data-widget="remove"><i class="fa fa-remove"></i></button>
                            </div>
                        </div>
                        <div class="box-body">

                            <div class="row">


                                <div class="col-sm-12">

                                    <table width="100%" border="0">
                                        <tr>
                                            <th width="100px" style="padding-top:10px;padding-bottom:10px;">Tgl. PO</th>
                                            <td>
                                                <input type="date" class="form-control input-sm" id="TglKO" name="TglKO" value="<?= $data_po['TglKO'] ?>" placeholder="Tgl PO">
                                            </td>
                                        </tr>

                                        <tr>
                                            <th style="padding-top:10px;padding-bottom:10px;">No. PO</th>
                                            <td>

                                                <input type="text" class="form-control input-sm" placeholder="No. PO" value="<?= $data_po['NoKO']  ?>" disabled>
                                            </td>
                                        </tr>


                                        <tr>
                                            <th style="padding-top:10px;padding-bottom:10px;">Job No.</th>
                                            <td>
                                                <input type="text" class="form-control input-sm" value="<?= $data_po['JobNo'] . ' - ' . $data_po['JobNm']  ?>" disabled>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th style="padding-top:10px;padding-bottom:10px;">No. SPR</th>
                                            <td>

                                                <select class="form-control input-sm select2 myspr" name="NoSPR" id="NoSPR" onChange="GantiSPR(this.value)"  <?=($data_po['ApprovedBy'] !='') ? 'disabled' : ''   ?>>
                                                    <option value="">-- Pilih Salah Satu --</option>
                                                    <?php
                                                    foreach ($list_no_spr as $list_spr) : ?>
                                                        <option value="<?=$list_spr['NoSPR']?>" <?=($list_spr['NoSPR'] == $data_po['NoSPR']) ? 'selected' : ''  ?>  ><?=$list_spr['NoSPR'].' - '.$list_spr['UtkPekerjaan']?></option>
                                                    <?php endforeach; ?>
                                                </select>

                                                <!-- <input type="text" class="form-control input-sm" placeholder="No SPR" value="<?= $data_po['NoSPR']  ?>" disabled> -->
                                            </td>
                                        </tr>

                                        <tr>
                                            <th style="padding-top:10px;padding-bottom:10px;">Kategori</th>
                                            <td>
                                                <input type="text" class="form-control input-sm" id="Kategori" placeholder="Kategori" disabled>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th style="padding-top:10px;padding-bottom:10px;">Bidang Usaha</th>
                                            <td>
                                                <input type="text" class="form-control input-sm" id="BidangUsaha" placeholder="Bidang Usaha" disabled>
                                            </td>
                                        </tr>
                                    </table>


                                </div>
                            </div>




                        </div>
                    </div>


                </div>

                <div class="col-sm-4">
                    <div class="box">
                        <div class="box-header" style="background-color: #e74c3c;color:white;">
                            <div class="box-tile">
                                <small>LOKASI PENGIRIMAN</small>
                            </div>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool text-black" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool text-black" data-widget="remove"><i class="fa fa-remove"></i></button>
                            </div>
                        </div>
                        <div class="box-body">

                            <div class="row">


                                <div class="col-sm-12">

                                    <table width="100%" border="0">
                                        <tr>
                                            <th width="100px" style="padding-top:10px;padding-bottom:10px;">Nama</th>
                                            <td>
                                                <input type="text" class="form-control input-sm" name="NamaKirim" id="NamaKirim" value="<?= $data_po['NamaKirim'] ?>" placeholder="Nama">
                                            </td>
                                        </tr>

                                        <tr>
                                            <th style="padding-top:10px;padding-bottom:10px;">Alamat</th>
                                            <td>
                                                <textarea id="AlamatKirim" class="form-control input-sm" name="AlamatKirim" placeholder="Alamat"><?= $data_po['AlamatKirim'] ?></textarea>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th width="100px" style="padding-top:10px;padding-bottom:10px;">Telepon</th>
                                            <td>
                                                <input type="text" class="form-control input-sm" name="TeleponKirim" id="TeleponKirim" value="<?= $data_po['TeleponKirim'] ?>" placeholder="Telepon">
                                            </td>
                                        </tr>

                                        <tr>
                                            <th width="100px" style="padding-top:10px;padding-bottom:10px;">Tgl. Pengiriman</th>
                                            <td>
                                                <input type="date" class="form-control input-sm" name="TglPengiriman" id="TglPengiriman" value="<?= $data_po['TglPengiriman'] ?>" placeholder="Tgl Pengiriman">
                                            </td>
                                        </tr>

                                        <tr>
                                            <th></th>
                                            <td>
                                                <hr style="border:1px solid black;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <td style="text-align: right;">
                                                <label class="checkbox-inline"><input type="checkbox" name="MaterialApproval" id="MaterialApproval" value="1" <?= ($data_po['MaterialApproval'] == 1)  ? 'checked' : '' ?>>Material Approval</label>
                                                <label class="checkbox-inline"><input type="checkbox" name="RAP" id="RAP" value="1" <?= ($data_po['RAP'] == 1)  ? 'checked' : '' ?>>RAP</label>

                                            </td>
                                        </tr>

                                    </table>

                                </div>
                            </div>




                        </div>
                    </div>


                </div>


                <div class="col-sm-12">
                    <div class="box">

                        <div class="box-header" style="background-color: #2980b9;color:white;">
                            <div class="box-tile">
                                <samll>ORDER LIST</samll>
                            </div>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool text-black" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool text-black" data-widget="remove"><i class="fa fa-remove"></i></button>
                            </div>
                        </div>

                        <div class="box-body">

                            <table>
                                <tr>
                                    <th>
                                        <button type="button" class="btn btn-xs btn-danger  <?= $button_app_ko ?> <?= $button_tampil ?>" onClick="tambah_rincian()">Tambah Order <i class="fa fa-plus"></i></button>
                                    </th>
                                </tr>
                            </table><br>


                            <div class="tampil_order_list">

                                <div class="table-responsive">
                                    <table id="order_list" class="table table-bordered table-striped table-hover table-condesed">
                                        <thead style="background-color: #27ae60;color:white;">
                                            <tr>
                                                <th style="text-align:center;width:5%;">No.</th>
                                                <th style="text-align:center;width:20%;">#</th>
                                                <th style="text-align:center;width:5%;">Kode RAP</th>
                                                <th style="text-align:center;width:30%;">Uraian</th>
                                                <th style="text-align:center;width:5%;">Vol</th>
                                                <th style="text-align:center;width:5%;">Sat</th>
                                                <th style="text-align:center;width:10%;">Harga Satuan (Rp)</th>
                                                <th style="text-align:center;width:10%;">Jumlah Harga (Rp)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th colspan="7">data tidak ditemukan</th>
                                            </tr>
                                        </tbody>

                                    </table>
                                </div>


                            </div>

                        </div>
                        <div class="overlay loading_rincian" style="display: none;">
                            <i class="fa fa-refresh fa-spin"></i>
                        </div>
                    </div>


                </div>

                <div class="col-sm-12">
                    <div class="box ">
                        <div class="box-header " style="background-color: #f39c12;color:white;">
                            <div class="box-tile">
                                <h5>PERSYARATAN DAN SANKSI</h5>
                            </div>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool text-black" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool text-black" data-widget="remove"><i class="fa fa-remove"></i></button>
                            </div>
                        </div>
                        <div class="box-body">

                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="" class="col-sm-12 col-form-label">1. Wajib memenuhi persyaratan Kesehatan & Keselamatan Kerja (K3)&nbsp
                                        <input type="checkbox" name="K3" value="1" id="K3" <?= ($data_po['K3'] == 1)  ? 'checked' : '' ?> required></label>
                                </div>
                                <div class="form-group">
                                    <label for="">2. Syarat Teknis</label>
                                    <textarea name="SyaratTeknis" id="SyaratTeknis" cols="5" rows="5" class="form-control input-sm" placeholder="Isi Syarat Teknis"><?= $data_po['SyaratTeknis'] ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">3. Jadwal Pengiriman</label>
                                    <textarea name="JadwalPengiriman" id="JadwalPengiriman" cols="5" rows="5" class="form-control input-sm" placeholder="Isi Jadwal Pengiriman"><?= $data_po['JadwalPengiriman'] ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">4. Jadwal Pembayaran</label>
                                    <textarea name="JadwalPembayaran" id="JadwalPembayaran" cols="5" rows="5" class="form-control input-sm"><?= $data_po['JadwalPembayaran'] ?></textarea>
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
                                                <input type="checkbox" name="SyaratPembayaran[]" value="INV" <?= (CekBuktiPendukung($data_po['SyaratPembayaran'], 'INV') == TRUE) ? "checked='checked' " : ''; ?>>
                                                <span class="text">Invoice/Kwitansi</span>
                                            </li>
                                        </div>

                                        <div class="control-group after-add-more3">
                                            <li>
                                                <span class="handle">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </span>
                                                <input type="checkbox" name="SyaratPembayaran[]" value="SJ" <?= (CekBuktiPendukung($data_po['SyaratPembayaran'], 'SJ') == TRUE) ? "checked='checked' " : ''; ?>>
                                                <span class="text">Surat Jalan/Tanda Terima Lapangan</span>
                                            </li>
                                        </div>

                                        <div class="control-group after-add-more3">
                                            <li>
                                                <span class="handle">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </span>
                                                <input type="checkbox" name="SyaratPembayaran[]" value="PO" <?= (CekBuktiPendukung($data_po['SyaratPembayaran'], 'PO') == TRUE) ? "checked='checked' " : ''; ?>>
                                                <span class="text">Copy PO</span>
                                            </li>
                                        </div>

                                        <div class="control-group after-add-more3">
                                            <li>
                                                <span class="handle">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </span>
                                                <input type="checkbox" name="SyaratPembayaran[]" value="FP" <?= (CekBuktiPendukung($data_po['SyaratPembayaran'], 'FP') == TRUE) ? "checked='checked' " : ''; ?>>
                                                <span class="text">Faktur Pajak</span>
                                            </li>
                                        </div>


                                        <div class="control-group after-add-more3">
                                            <li>
                                                <span class="handle">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </span>
                                                <input type="checkbox" name="SyaratPembayaran[]" value="BAP" <?= (CekBuktiPendukung($data_po['SyaratPembayaran'], 'BAP') == TRUE) ? "checked='checked' " : ''; ?>>
                                                <span class="text">Berita Acara Pembayaran</span>
                                            </li>
                                        </div>

                                        <div class="control-group after-add-more3">
                                            <li>
                                                <span class="handle">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </span>
                                                <input type="checkbox" name="SyaratPembayaran[]" value="BAOP" <?= (CekBuktiPendukung($data_po['SyaratPembayaran'], 'BAOP') == TRUE) ? "checked='checked' " : ''; ?>>
                                                <span class="text">Berita Acara Opname Pekerjaan</span>
                                            </li><br>
                                        </div>

                                    </ul>

                                </div>

                                <div class="form-group">
                                    <label for="">6. Sanksi</label>
                                    <textarea name="Sanksi" id="Sanksi" cols="5" rows="5" class="form-control input-sm" placeholder="Isi Sanksi"><?= $data_po['Sanksi'] ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">7. Keterangan Lain</label>
                                    <textarea name="Keterangan" id="Keterangan" cols="5" rows="5" class="form-control input-sm" placeholder="Isi Keterangan Lain"><?= $data_po['Keterangan'] ?></textarea>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <div class="col-sm-12" style="padding-bottom:20px;">

                    <input type="hidden" name="JobNo" id="JobNo" value="<?= $data_po['JobNo'] ?>" readonly>
                    <input type="hidden" name="NoKO" id="NoKO" value="<?= $data_po['NoKO'] ?>" readonly>
                    <input type="hidden" name="LedgerNo" id="LedgerNo" value="<?= $data_po['LedgerNo'] ?>" readonly>

                    <button onclick="history.back()" class="btn btn-sm btn-link pull-left" style="background-color:#34495e;color:white;"><i class="fa fa-arrow-left"></i> Batal</button>
                    <button class="btn btn-md btn-primary pull-right btn_simpan <?= $button_app_ko ?> <?= $button_tampil ?>" type="submit">Simpan</button>

                </div>


            </div>


        </form>
    </section>


</div>

<section>

</section>

<div class="modal fade" id="modal_uraian" style="font-size: 10px;">
    <div class="modal-dialog modal-xs" id="modal_dialog_uraian">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="modal_title_uraian">Tambah List Order<br>
                    <p style="color:#e74c3c"><?= $data_po['NoKO'] . ' - ' . $data_po['JobNm'] . ' - ' . $data_po['VendorNm']  ?></p>
                </h6>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal_body_uraian">
                <div class="box">
                    <div class="box-body">

                        <form id="form_tambah_uraian">

                            <table style="font-size: 10px;" width="100%" border="0">
                                <tr>
                                    <th style="width: 10px;padding-top:15px;padding-bottom:15px;">No.</th>
                                    <td class="text-center">:</td>
                                    <td>
                                        <input type="text" class="form-control input-sm" disabled>
                                    </td>
                                </tr>

                                <tr>
                                    <th style="width: 90px;padding-top:15px;padding-bottom:15px;">Alokasi</th>
                                    <td class="text-center">:</td>
                                    <td>
                                        <select class="form-control input-sm select2 myalokasi" name="Alokasi" onChange="dapat_KdRAP(this.value)" required>
                                            <option value="">-- Pilih Salah Satu --</option>
                                            <?php
                                            foreach (list_job_alokasi() as $myalokasi) :
                                                echo "<option value='" . $myalokasi['Alokasi'] . "'   >" . $myalokasi['Alokasi'] . " - " . $myalokasi['Keterangan'] . "</option>";
                                            endforeach;
                                            ?>
                                        </select>
                                    </td>
                                </tr>


                                <tr>
                                    <th style="width: 90px;padding-top:15px;padding-bottom:15px;">Kode. RAP</th>
                                    <td class="text-center">:</td>
                                    <td>
                                        <select class="form-control input-sm select2 kdrap" name="KdRAP" id="tambah_KdRAP" required>
                                            <option value="">-- Pilih Salah Satu --</option>

                                        </select>
                                    </td>
                                </tr>


                                <tr>
                                    <th style="padding-top:15px;padding-bottom:15px;">Uraian</th>
                                    <td class="text-center">:</td>
                                    <td>
                                        <textarea class="form-control input-sm" name="Uraian" id="tambah_Uraian" placeholder="Isi Uraian" required></textarea>
                                    </td>
                                </tr>

                                <tr>
                                    <th style="padding-top:15px;padding-bottom:15px;">Vol</th>
                                    <td class="text-center">:</td>
                                    <td>
                                        <input type="text" class="form-control input-sm" name="Vol" id="tambah_Vol" value="0" placeholder="Isi Harga Volume" autocomplete="off" required>
                                    </td>
                                </tr>

                                <tr>
                                    <th style="padding-top:15px;padding-bottom:15px;">Satuan</th>
                                    <td class="text-center">:</td>
                                    <td>
                                        <input type="text" class="form-control input-sm" name="Uom" id="tambah_Uom" placeholder="Isi Satuan" autocomplete="off" required>
                                    </td>
                                </tr>

                                <tr>
                                    <th style="padding-top:15px;padding-bottom:15px;">Harga Satuan</th>
                                    <td class="text-center">:</td>
                                    <td>
                                        <input type="text" class="form-control input-sm uang" name="HrgSatuan" id="tambah_HrgSatuan" value="0" placeholder="Isi Harga Satuan" autocomplete="off" required>
                                    </td>
                                </tr>

                                <tr>
                                    <th style="padding-top:15px;padding-bottom:15px;">Total Harga</th>
                                    <td class="text-center">:</td>
                                    <td>
                                        <input type="text" class="form-control input-sm" id="tambah_TotalHarga" value="0" disabled required>
                                    </td>
                                </tr>
                                <tr>

                                    <td style="text-align: right;" colspan="3">
                                        <hr>

                                        <input type="hidden" name="NoKO" value="<?= $data_po['NoKO'] ?>">
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
                    <p style="color:#e74c3c"><?= $data_po['NoKO'] . ' - ' . $data_po['JobNm'] . ' - ' . $data_po['VendorNm']  ?></p>
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
                                    <th style="width: 10px;padding-top:15px;padding-bottom:15px;">No.</th>
                                    <td>:</td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" disabled>
                                    </td>
                                </tr>

                                <tr>
                                    <th style="width: 90px;padding-top:15px;padding-bottom:15px;">Alokasi</th>
                                    <td class="text-center">:</td>
                                    <td>
                                        <input type="text" class="form-control input-sm alokasi_edit" disabled>

                                    </td>
                                </tr>


                                <tr>
                                    <th style="width: 90px;padding-top:15px;padding-bottom:15px;">Kode. RAP</th>
                                    <td>:</td>
                                    <td>
                                        <input type="text" class="form-control input-sm kdrap_edit" disabled>
                                    </td>
                                </tr>


                                <tr>
                                    <th style="padding-top:15px;padding-bottom:15px;">Uraian</th>
                                    <td>:</td>
                                    <td>
                                        <textarea class="form-control form-control-sm" name="Uraian" id="edit_Uraian" placeholder="Isi Uraian" required></textarea>
                                    </td>
                                </tr>

                                <tr>
                                    <th style="padding-top:15px;padding-bottom:15px;">Vol</th>
                                    <td>:</td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" name="Vol" id="edit_Vol" value="0" placeholder="Isi Harga Volume" autocomplete="off" required>
                                    </td>
                                </tr>

                                <tr>
                                    <th style="padding-top:15px;padding-bottom:15px;">Satuan</th>
                                    <td>:</td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" name="Uom" id="edit_Uom" placeholder="Isi Satuan" autocomplete="off" required>
                                    </td>
                                </tr>

                                <tr>
                                    <th style="padding-top:15px;padding-bottom:15px;">Harga Satuan</th>
                                    <td>:</td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm uang" name="HrgSatuan" id="edit_HrgSatuan" value="0" placeholder="Isi Harga Satuan" autocomplete="off" required>
                                    </td>
                                </tr>

                                <tr>
                                    <th style="padding-top:15px;padding-bottom:15px;">Total Harga</th>
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

<script>
    $(document).ready(function() {

        dapat_data_vendor();
        tampil_order_list();


        $('.uang').mask("#,##0", {
            reverse: true
        });

        $('.select2').select2({
            'theme': 'classic',
            'width': '100%',
        });

        $('#tambah_Vol').keyup(function() {
            hitung();
        })

        $('#tambah_HrgSatuan').keyup(function() {
            hitung();
        })

        $('#form_tambah_uraian').submit(function(e) {
            e.preventDefault();

            $.ajax({
                type: "post",
                url: "<?= site_url('Kontrak_po/aksi_simpan_order_list_aksi_detail') ?>",
                data: $(this).serialize(),
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
                    tampil_order_list();
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

        $('#form_kohdr').submit(function(e) {
            e.preventDefault();

            $.ajax({
                type: "post",
                url: "<?= site_url('Kontrak_po/simpan_po_aksi_detail') ?>",
                data: $(this).serialize(),
                // dataType: "json",
                dataType: "json",
                beforeSend: function() {
                    $('.btn_simpan').attr('disabled', true);
                },
                success: function(data) {
                    $('.btn_simpan').attr('disabled', false);

                    // alert(data);
                    // return false;


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

                    if (data.pesan == 'kodtl_kosong') {
                        $.toast({
                            text: 'Order List masih kosong',
                            position: 'bottom-right',
                            stack: false,
                            hideAfter: 1000,
                            icon: 'info',
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


        $('#edit_Vol').keyup(function() {
            hitung_edit();
        })

        $('#edit_HrgSatuan').keyup(function() {
            hitung_edit();
        })


        $('#form_tambah_uraian_edit').submit(function(e) {
            e.preventDefault();

            $.ajax({
                type: "post",
                url: "<?= site_url('Kontrak_po/aksi_update_order_list_aksi_detail') ?>",
                data: $(this).serialize(),
                dataType: "html",
                beforeSend: function() {
                    $('.loading_uraian_edit').show();
                },
                success: function(data) {
                    $('.loading_uraian_edit').hide();
                    // alert(data);

                    if (data == 'berhasil') {
                        $.toast({
                            text: 'data berhasil di ubah',
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
                    tampil_order_list();
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


    });

    function tampil_order_list() {

        var JobNo = $('#JobNo').val();
        var NoKO = $('#NoKO').val();

        // alert(NoKO);

        $.ajax({
            type: "post",
            url: "<?= site_url('Kontrak_po/tampil_order_list_aksi_detail') ?>",
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
                // alert(NoKO);
                $('.tampil_order_list').html('');
                $('.tampil_order_list').html(data);

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

    function hitung() {

        var HrgSatuan = $('#tambah_HrgSatuan').val();
        var Vol = $('#tambah_Vol').val();

        HrgSatuan = HapusKoma(HrgSatuan);
        Vol = HapusKoma(Vol);

        var total = parseInt(Vol) * parseInt(HrgSatuan);

        $('#tambah_TotalHarga').val(addCommas(total));

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
                $('#Telepon1').val(data.Telepon1);
                $('#NPWP').val(data.NPWP);
                $('#ContactPerson').val(data.ContactPerson);

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




    function tambah_rincian() {
        bersihkan_form_tambah();
        $('#modal_uraian').modal('show');
    }

    function dapat_KdRAP(Alokasi = '') {
        if (Alokasi == '') {
            var Alokasi = $('.myalokasi').val();
        }
        var JobNo = $('#JobNo').val();

        $.ajax({
            type: "post",
            url: "<?= site_url('Kontrak_po/dapat_KdRAP') ?>",
            data: {
                'JobNo': JobNo,
                'Alokasi': Alokasi
            },
            dataType: "html",
            beforeSend: function() {
                $('.loadingsaya').show();
            },
            success: function(data) {
                $('.loadingsaya').hide();
                // alert(data);

                $('.kdrap').html('');
                $('.kdrap').html(data);

            },
            error: function() {
                $('.loadingsaya').hide();
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
                    url: "<?= site_url('Kontrak_po/hapus_order_aksi_detail') ?>",
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

                        tampil_order_list();

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
        var JobNo = $('#JobNo').val();

        $.ajax({
            type: "POST",
            url: "<?= site_url('Kontrak_po/dapat_data_order_aksi_detail') ?>",
            data: {
                'NoUrut': NoUrut,
                'NoKO': NoKO,
                'JobNo': JobNo
            },
            dataType: "json",
            beforeSend: function() {
                $('.loading_rincian').show();
            },
            success: function(data) {
                $('.loading_rincian').hide();
                bersihkan_form_edit();


                // alert(data.Alokasi);
                $('.alokasi_edit').val(data.Alokasi);
                $('.kdrap_edit').val(data.kdrap_detail);


                $('#edit_Uraian').val(data.Uraian);
                $('#edit_Vol').val(data.Vol);
                $('#edit_Uom').val(data.Uom);
                $('#edit_HrgSatuan').val(data.HrgSatuan);
                $('#noko_edit').val(data.NoKO);
                $('#norut_edit').val(data.NoUrut);
                hitung_edit();

                $('#modal_uraian_edit').modal('show');

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


    function bersihkan_form_tambah() {
        $('.myalokasi').val('').trigger('change');
        $('#tambah_KdRAP').val('').trigger('change');
        $('#tambah_Uraian').val('');
        $('#tambah_Vol').val('1');
        $('#tambah_Uom').val('');
        $('#tambah_HrgSatuan').val('0');
        $('#tambah_TotalHarga').val('0');
    }

    function bersihkan_form_edit() {
        // $('.myalokasi').val('').trigger('change');
        // $('#edit_KdRAP').val('').trigger('change');

        $('.alokasi_edit').val('');
        $('.kdrap_edit').val('');
        $('#edit_Uraian').val('');
        $('#edit_Vol').val('');
        $('#edit_Uom').val('');
        $('#edit_HrgSatuan').val('');
        $('#edit_TotalHarga').val('');
    }

    function hitung_edit() {

        var HrgSatuan = $('#edit_HrgSatuan').val();
        var Vol = $('#edit_Vol').val();

        HrgSatuan = HapusKoma(HrgSatuan);
        Vol = HapusKoma(Vol);

        var total = parseInt(Vol) * parseInt(HrgSatuan);

        $('#edit_TotalHarga').val(addCommas(total));

    }



function GantiSPR(NoSPR=''){

    var JobNo = $('#JobNo').val();
    var NoKO = $('#NoKO').val();

    // alert(NoSPR+' - '+JobNo);
    // return false;


    jQuery.ajax({
        url: '<?= site_url('Kontrak_po/pilih_SPR_edit_po') ?>',
        type: 'POST',
        dataType: 'html',
        data: {
            'JobNo': JobNo,
            'NoSPR': NoSPR,
            'NoKO' : NoKO
        },
        beforeSend: function() {
            $('.loading_rincian').show();
        },
        success: function(data, textStatus, xhr) {
            $('.loading_rincian').hide();
            tampil_order_list();
            // alert(data);
        },
        error: function(xhr, textStatus, errorThrown) {
            $('.loading_rincian').hide();
            $.toast({
                text: 'terjadi kesalahan',
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