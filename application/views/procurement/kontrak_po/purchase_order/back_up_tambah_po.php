<style>
    .atur {
        font-size: 10px;
        font-family: Arial, Helvetica, sans-serif;
    }

    input {
        font-size: 10px !important;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-family: Arial, Helvetica, sans-serif;
    }

    select {
        font-size: 10px !important;
        font-family: Arial, Helvetica, sans-serif;
    }

    textarea {
        font-size: 10px !important;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-family: Arial, Helvetica, sans-serif;
    }
</style>

<div class="content-wrapper atur">

    <section class="content-header">
        <h5>
            <label class="col-lg col-form-label"><?php echo $judul ?> </label>
            <small class="text-red"><br><?= $JobNo . ' - ' . $JobNm  ?> </small>
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

                                                        <option value="<?= $data_vendor['VendorId'] ?>"><?= $data_vendor['VendorId'] . ' - ' . $data_vendor['VendorNm'] ?></option>

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
                                                <input type="date" class="form-control input-sm" id="TglKO" name="TglKO" value="<?= date('Y-m-d') ?>" placeholder="Tgl PO">
                                            </td>
                                        </tr>

                                        <tr>
                                            <th style="padding-top:10px;padding-bottom:10px;">No. PO</th>
                                            <td>
                                                <input type="text" class="form-control input-sm" value="" placeholder="No PO" disabled>
                                            </td>
                                        </tr>


                                        <tr>
                                            <th style="padding-top:10px;padding-bottom:10px;">Job No.</th>
                                            <td>
                                                <input type="text" class="form-control input-sm" value="<?= $JobNo . ' - ' . $JobNm  ?>" disabled>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th style="padding-top:10px;padding-bottom:10px;">No. SPR</th>
                                            <td>
                                                <input type="text" class="form-control input-sm" value="" placeholder="No SPR" disabled>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th style="padding-top:10px;padding-bottom:10px;">Kategori</th>
                                            <td>
                                                <input type="text" class="form-control input-sm" id="Kategori" value="" placeholder="Kategori" disabled>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th style="padding-top:10px;padding-bottom:10px;">Bidang Usaha</th>
                                            <td>
                                                <input type="text" class="form-control input-sm" id="BidangUsaha" value="" placeholder="Bidang Usaha" disabled>
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
                                                <input type="text" class="form-control input-sm" name="NamaKirim" id="NamaKirim" value="" placeholder="Nama">
                                            </td>
                                        </tr>

                                        <tr>
                                            <th style="padding-top:10px;padding-bottom:10px;">Alamat</th>
                                            <td>
                                                <textarea id="AlamatKirim" class="form-control input-sm" name="AlamatKirim" placeholder="Alamat"></textarea>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th width="100px" style="padding-top:10px;padding-bottom:10px;">Telepon</th>
                                            <td>
                                                <input type="text" class="form-control input-sm" name="TeleponKirim" id="TeleponKirim" value="" placeholder="Telepon">
                                            </td>
                                        </tr>

                                        <tr>
                                            <th width="100px" style="padding-top:10px;padding-bottom:10px;">Tgl. Pengiriman</th>
                                            <td>
                                                <input type="date" class="form-control input-sm" name="TglPengiriman" id="TglPengiriman" value="" placeholder="Tgl Pengiriman">
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
                                                <label class="checkbox-inline"><input type="checkbox" name="MaterialApproval" id="MaterialApproval" value="1">Material Approval</label>
                                                <label class="checkbox-inline"><input type="checkbox" name="RAP" id="RAP" value="1">RAP</label>

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
                                        <button type="button" class="btn btn-xs btn-danger" onClick="tambah_rincian()">Tambah Order <i class="fa fa-plus"></i></button>
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
                    <div class="box">

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
                                        <input type="checkbox" name="K3" value="1" id="K3" required></label>
                                </div>
                                <div class="form-group">
                                    <label for="">2. Syarat Teknis</label>
                                    <textarea name="SyaratTeknis" id="SyaratTeknis" cols="5" rows="5" class="form-control input-sm" placeholder="Isi Syarat Teknis"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">3. Jadwal Pengiriman</label>
                                    <textarea name="JadwalPengiriman" id="JadwalPengiriman" cols="5" rows="5" class="form-control input-sm" placeholder="Isi Jadwal Pengiriman"><?= date('d-M-Y', strtotime('+5 days')) ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">4. Jadwal Pembayaran</label>
                                    <textarea name="JadwalPembayaran" id="JadwalPembayaran" cols="5" rows="5" class="form-control input-sm"></textarea>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="">5. Syarat Pembayaran</label>
                                </div>



                                <div class="control-group after-add-more3">
                                    <ul class="todo-list">
                                        <?php
                                        $DatasyaratPemb = [
                                            'Invoice/Kwitansi',
                                            'Surat Jalan/Tanda Terima Lapangan',
                                            'Copy PO',
                                            'Faktur Pajak',
                                            'Berita Acara Pembayaran',
                                            'Berita Acara Opname Pekerjaan'
                                        ];
                                        ?>
                                        <div class="control-group after-add-more3">
                                            <?php foreach ($DatasyaratPemb as $row => $value) : ?>

                                                <li>
                                                    <span class="handle">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </span>
                                                    <input type="checkbox" name="SyaratPembayaran[]" value="<?= $value ?>">
                                                    <small class="text"><?= $value ?></small>
                                                </li>
                                            <?php endforeach; ?>
                                        </div>
                                    </ul>

                                </div><br>
                                <div class="form-group">
                                    <label for="">6. Sanksi</label>
                                    <textarea name="Sanksi" id="Sanksi" cols="5" rows="5" class="form-control input-sm" placeholder="Isi Sanksi"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">7. Keterangan Lain</label>
                                    <textarea name="Keterangan" id="Keterangan" cols="5" rows="5" class="form-control input-sm" placeholder="Isi Keterangan Lain"></textarea>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <div class="col-sm-12" style="padding-bottom:20px;">

                    <input type="hidden" name="JobNo" id="JobNo" value="<?= $JobNo ?>" readonly>
                    <input type="hidden" name="NoKO" id="NoKO" value="<?= $NoKO ?>" readonly>

                    <button onclick="history.back()" class="btn btn-sm btn-link pull-left" style="background-color:#34495e;color:white;"><i class="fa fa-arrow-left"></i> Batal</button>
                    <button class="btn btn-md btn-primary pull-right btn_simpan" type="submit">Simpan</button>

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
                    <p style="color:#e74c3c"><?= $JobNo . ' - ' . $JobNm  ?></p>
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

                                        <input type="hidden" name="NoKO" value="<?= $NoKO ?>">
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
                    <p style="color:#e74c3c"><?= $JobNo . ' - ' . $JobNm  ?></p>
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
                url: "<?= site_url('Kontrak_po/aksi_simpan_order_list') ?>",
                data: $(this).serialize(),
                dataType: "html",
                beforeSend: function() {
                    $('.loading_uraian_tambah').show();
                },
                success: function(data) {
                    $('.loading_uraian_tambah').hide();
                    // alert(data);

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
                url: "<?= site_url('Kontrak_po/simpan_po') ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function() {
                    $('.btn_simpan').attr('disabled', true);
                },
                success: function(data) {
                    $('.btn_simpan').attr('disabled', false);
                    // alert(data);


                    if (data.pesan == 'berhasil') {
                        $.toast({
                            text: 'berhasil menambahkan data',
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
                            text: 'gagal menambahkan data',
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

                    if (data.pesan == 'ada_no_ko') {
                        $.toast({
                            text: 'Nomor KO Sudah Ada',
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
                url: "<?= site_url('Kontrak_po/aksi_update_order_list') ?>",
                data: $(this).serialize(),
                dataType: "html",
                beforeSend: function() {
                    $('.loading_uraian_edit').show();
                },
                success: function(data) {
                    $('.loading_uraian_edit').hide();
                    // alert(data);

                    // if (data == 'berhasil') {
                    //     $.toast({
                    //         text: 'data_telah_diubah',
                    //         position: 'bottom-right',
                    //         stack: false,
                    //         hideAfter: 1000,
                    //         icon: 'success',

                    //     })
                    // }

                    // if (data == 'gagal') {
                    //     $.toast({
                    //         text: 'data gagal diubah',
                    //         position: 'bottom-right',
                    //         stack: false,
                    //         hideAfter: 1000,
                    //         icon: 'error',

                    //     })
                    // }



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
            url: "<?= site_url('Kontrak_po/tampil_order_list') ?>",
            data: {
                'JobNo': JobNo,
                'NoKO': NoKO
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
                    url: "<?= site_url('Kontrak_po/hapus_order') ?>",
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
            url: "<?= site_url('Kontrak_po/dapat_data_order') ?>",
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

                $('#modal_uraian_edit').modal('show');

                $('.alokasi_edit').val(data.Alokasi);
                $('.kdrap_edit').val(data.kdrap_detail);

                $('#edit_Uraian').val(data.Uraian);
                $('#edit_Vol').val(data.Vol);
                $('#edit_Uom').val(data.Uom);
                $('#edit_HrgSatuan').val(data.HrgSatuan);
                $('#noko_edit').val(data.NoKO);
                $('#norut_edit').val(data.NoUrut);
                // alert(data.Alokasi);
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
</script>