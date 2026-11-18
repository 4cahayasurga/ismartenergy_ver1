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

    <section class="content-header">
        <button onclick="history.back()" class="btn btn-sm btn-link" style="background-color:#34495e;color:white;"><i class="fa fa-arrow-left"></i> Back</button>
    </section>

    <section class="content-header">
        <?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>
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
                                        <th style="width:10%;padding-top:5px;padding-bottom:5px;">Tgl Kontrak</th>
                                        <td style="width:10px;text-align: center;padding-top:5px;padding-bottom:5px;">:</td>
                                        <td style="padding-left:5px;padding-top:5px;padding-bottom:5px;">
                                            <input type="date" name="TglKO" id="TglKO" class="form-control input-sm" value="<?= date('Y-m-d') ?>" required>
                                        </td>
                                    </tr>

                                </table>

                                <table width="100%" border="0">

                                    <tr>
                                        <th style="width:10%;padding-top:5px;padding-bottom:5px;">No. Kontrak</th>
                                        <td style="width:10px;text-align: center;padding-top:5px;padding-bottom:5px;">:</td>
                                        <td style="padding-left:5px;padding-top:5px;padding-bottom:5px;">
                                            <input type="text" class="form-control input-sm" placeholder="No. Kontrak" disabled>
                                        </td>
                                    </tr>

                                </table>

                                <table width="100%" border="0">

                                    <tr>
                                        <th style="width:10%;padding-top:5px;padding-bottom:5px;">Job. No</th>
                                        <td style="width:10px;text-align: center;padding-top:5px;padding-bottom:5px;">:</td>
                                        <td style="padding-left:5px;padding-top:5px;padding-bottom:5px;">
                                            <input type="text" class="form-control input-sm" placeholder="Job.No" value="<?= $JobNo . ' - ' . $JobNm  ?>" disabled>
                                        </td>
                                    </tr>

                                </table>

                                <table width="100%" border="0">

                                    <tr>
                                        <th style="width:10%;padding-top:5px;padding-bottom:5px;">Kategori</th>
                                        <td style="width:10px;text-align: center;padding-top:5px;padding-bottom:5px;">:</td>
                                        <td style="padding-left:5px;padding-top:5px;padding-bottom:5px;">
                                            <input type="text" name="Kategori" id="Kategori" class="form-control input-sm" placeholder="Kategori" disabled>
                                        </td>
                                    </tr>

                                </table>

                                <table width="100%" border="0">

                                    <tr>
                                        <th style="width:10%;padding-top:5px;padding-bottom:5px;">Bidang Usaha</th>
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
                            <a href="javascript:void(0)" class="btn btn-sm" onclick="tambah_rincian()" style="background-color:#636e72;color:white;">Tambah Rincian</a>
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
                                        <input type="checkbox" name="K3" value="1" required>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label for="">2. Syarat Teknis</label>
                                    <textarea name="SyaratTeknis" id="SyaratTeknis" cols="5" rows="5" class="form-control input-sm"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">3. Jadwal Pengiriman</label>
                                    <textarea name="JadwalPengiriman" id="JadwalPengiriman" cols="5" rows="5" class="form-control input-sm"></textarea>
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
                                        <div class="control-group after-add-more3">
                                            <li>
                                                <span class="handle">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </span>
                                                <input type="checkbox" name="SyaratPembayaran[]" value="INV">
                                                <span class="text">Invoice/Kwitansi</span>
                                            </li>
                                        </div>

                                        <div class="control-group after-add-more3">
                                            <li>
                                                <span class="handle">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </span>
                                                <input type="checkbox" name="SyaratPembayaran[]" value="SJ">
                                                <span class="text">Surat Jalan/Tanda Terima Lapangan</span>
                                            </li>
                                        </div>

                                        <div class="control-group after-add-more3">
                                            <li>
                                                <span class="handle">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </span>
                                                <input type="checkbox" name="SyaratPembayaran[]" value="PO">
                                                <span class="text">Copy PO</span>
                                            </li>
                                        </div>

                                        <div class="control-group after-add-more3">
                                            <li>
                                                <span class="handle">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </span>
                                                <input type="checkbox" name="SyaratPembayaran[]" value="FP">
                                                <span class="text">Faktur Pajak</span>
                                            </li>
                                        </div>


                                        <div class="control-group after-add-more3">
                                            <li>
                                                <span class="handle">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </span>
                                                <input type="checkbox" name="SyaratPembayaran[]" value="BAP">
                                                <span class="text">Berita Acara Pembayaran</span>
                                            </li>
                                        </div>

                                        <div class="control-group after-add-more3">
                                            <li>
                                                <span class="handle">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </span>
                                                <input type="checkbox" name="SyaratPembayaran[]" value="BAOP">
                                                <span class="text">Berita Acara Opname Pekerjaan</span>
                                            </li><br>
                                        </div>

                                    </ul>

                                </div>


                                <div class="form-group">
                                    <label for="">6. Sanksi</label>
                                    <textarea name="Sanksi" id="Sanksi" cols="5" rows="5" class="form-control input-sm"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">7. Keterangan Lain</label>
                                    <textarea name="Keterangan" id="Keterangan" cols="5" rows="5" class="form-control input-sm"></textarea>
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
                                            foreach (list_kode_rap($JobNo, 'B') as $data_rap) :

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
                                            foreach (list_kode_rap($JobNo, 'B') as $data_rap) :

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

<script>
    $(document).ready(function() {

        tampil_rincian_pekerjaan();

        $('.uang').mask("#,##0", {
            reverse: true
        });


        $('.select2').select2({
            'theme': 'classic',
            'width': '100%',
        });


        $('#form_tambah_uraian').submit(function(e) {

            e.preventDefault();

            mydata = $(this).serialize();

            $.ajax({
                type: "post",
                url: "<?= site_url('Kontrak_po/aksi_simpan_uraian_ko') ?>",
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

            $.ajax({
                type: "post",
                url: "<?= site_url('Kontrak_po/aksi_edit_uraian_ko') ?>",
                data: $(this).serialize(),
                dataType: "html",
                beforeSend: function() {
                    $('.loading_uraian_edit').show();
                },
                success: function(data) {
                    $('.loading_uraian_edit').hide();

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

        $('#form_saya').submit(function(e) {

            e.preventDefault();

            // var SubTotal = $('#SubTotal').val();

            $.ajax({
                type: "post",
                url: "<?= site_url('Kontrak_po/simpan_kohdr_kontrak') ?>",
                data: $(this).serialize(),
                dataType: "JSON",
                beforeSend: function() {
                    $('.btn_simpan').attr('disabled', true);
                },
                success: function(data) {
                    $('.btn_simpan').attr('disabled', false);

                    // console.log(data);
                    // return false;

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
                            text: 'Isi Rincian Pekerjaan',
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
            },
            success: function(data) {
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


    function tampil_rincian_pekerjaan() {

        var JobNo = $('#JobNo').val();
        var NoKO = $('#NoKO').val();

        $.ajax({
            type: "post",
            url: "<?= site_url('Kontrak_po/tampil_rincian_pekerjaan') ?>",
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
                    url: "<?= site_url('Kontrak_po/hapus_uraian_kontrak') ?>",
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


    function bersihkan_form_edit() {
        $('#edit_KdRAP').val('').trigger('change');
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

    function edit_uraian(NoUrut) {

        var NoKO = $('#NoKO').val();
        var JobNo = $('#JobNo').val();

        $.ajax({
            type: "POST",
            url: "<?= site_url('Kontrak_po/dapatkan_data_kodtl_sementara') ?>",
            data: {
                'NoUrut': NoUrut,
                'NoKO': NoKO,
            },
            dataType: "json",
            beforeSend: function() {
                $('.loading_rincian').show();
            },
            success: function(data) {
                $('.loading_rincian').hide();
                bersihkan_form_edit();

                $('#edit_KdRAP').val(data.KdRAP).trigger('change');
                $('#edit_Uraian').val(data.Uraian);
                $('#edit_Vol').val(data.Vol);
                $('#edit_Uom').val(data.Uom);
                $('#edit_HrgSatuan').val(data.HrgSatuan);
                $('#noko_edit').val(data.NoKO);
                $('#norut_edit').val(data.NoUrut);
                // console.log("%j", data);

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
</script>