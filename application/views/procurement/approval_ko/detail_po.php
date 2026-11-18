<style>
    body {
        font-size: 10px;
        /* font-family: 'Courier New', Courier, monospace; */
        font-family: Verdana, Geneva, Tahoma, sans-serif;

    }

    input {
        font-size: 10px !important;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
    }

    textarea {
        font-size: 10px !important;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
    }
</style>

<div class="content-wrapper atur">

    <section class="content-header">
        <h5>
            <label class="col-lg col-form-label"><?php echo $judul ?> </label>
            <small class="text-red"><br><?= $data_ko['NoKO'] . ' - ' . $data_ko['JobNm'] . ' - ' . $data_ko['VendorNm']  ?> </small>
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

    <?php
    if ($data_ko['ApprovedBy'] !== NULL) { ?>
        <div class="col-sm-12">
            <div class="col-sm-4 row" style="padding-top:10px;">
                <div class="alert alert-success">
                    <strong> <i class="fa fa-check"></i> Approved</strong>
                </div>
            </div>
        </div>


    <?php } ?>

    <section class="content-header">
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
                                                <input type="text" class="form-control input-sm" name="VendorId" id="VendorId" value="<?= $data_ko['VendorId'] . ' - ' . $data_ko['VendorNm'] ?>" disabled>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th style="padding-top:10px;padding-bottom:10px;">Nama</th>
                                            <td>
                                                <input type="text" class="form-control input-sm" name="VendorNm" id="VendorNm" value="<?= $data_ko['VendorNm'] ?>" disabled>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th style="padding-top:10px;padding-bottom:10px;">Alamat</th>
                                            <td>
                                                <textarea name="Alamat" id="Alamat" class="form-control input-sm" disabled><?= $data_ko['Alamat'] ?></textarea>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th style="padding-top:10px;padding-bottom:10px;">Telepon</th>
                                            <td>
                                                <input type="text" class="form-control input-sm" name="Telepon1" id="Telepon1" value="<?= $data_ko['Telepon1'] ?>" disabled>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th style="padding-top:10px;padding-bottom:10px;">NPWP</th>
                                            <td>
                                                <input type="text" class="form-control input-sm" name="NPWP" id="NPWP" value="<?= $data_ko['NPWP'] ?>" disabled>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th style="padding-top:10px;padding-bottom:10px;">UP</th>
                                            <td>
                                                <input type="text" class="form-control input-sm" name="NPWP" id="NPWP" value="<?= $data_ko['ContactPerson'] ?>" disabled>
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
                                                <input type="date" class="form-control input-sm" name="TglKO" id="TglKO" value="<?= $data_ko['TglKO'] ?>" disabled>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th style="padding-top:10px;padding-bottom:10px;">No. PO</th>
                                            <td>
                                                <input type="text" class="form-control input-sm" name="NoKO" id="NoKO" value="<?= $data_ko['NoKO'] ?>" disabled>
                                            </td>
                                        </tr>


                                        <tr>
                                            <th style="padding-top:10px;padding-bottom:10px;">Job No.</th>
                                            <td>
                                                <input type="text" class="form-control input-sm" value="<?= $data_ko['JobNo'] . ' - ' . $data_ko['JobNm'] ?>" disabled>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th style="padding-top:10px;padding-bottom:10px;">No. SPR</th>
                                            <td>
                                                <input type="text" class="form-control input-sm" value="<?= $data_ko['NoSPR'] . ' - ' . $data_ko['SprNama'] ?>" disabled>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th style="padding-top:10px;padding-bottom:10px;">Kategori</th>
                                            <td>
                                                <input type="text" class="form-control input-sm" name="Kategori" id="Kategori" value="<?= $data_ko['Kategori'] ?>" disabled>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th style="padding-top:10px;padding-bottom:10px;">Bidang Usaha</th>
                                            <td>
                                                <input type="text" class="form-control input-sm" name="BidangUsaha" id="BidangUsaha" value="<?= $data_ko['BidangUsaha'] ?>" disabled>
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
                                                <input type="text" class="form-control input-sm" name="NamaKirim" id="NamaKirim" value="<?= $data_ko['NamaKirim'] ?>" disabled>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th style="padding-top:10px;padding-bottom:10px;">Alamat</th>
                                            <td>
                                                <textarea name="AlamatKirim" id="AlamatKirim" class="form-control input-sm" disabled><?= $data_ko['AlamatKirim'] ?></textarea>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th width="100px" style="padding-top:10px;padding-bottom:10px;">Telepon</th>
                                            <td>
                                                <input type="text" class="form-control input-sm" name="TeleponKirim" id="NamaKirim" value="<?= $data_ko['TeleponKirim'] ?>" disabled>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th width="100px" style="padding-top:10px;padding-bottom:10px;">Tgl. Pengiriman</th>
                                            <td>
                                                <input type="text" class="form-control input-sm" name="TglPengiriman" id="TglPengiriman" value="<?= $data_ko['TglPengiriman'] ?>" disabled>
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
                                                <label class="checkbox-inline"><input type="checkbox" name="MaterialApproval" id="MaterialApproval" value="1" <?= ($data_ko['MaterialApproval'] == 1) ? 'checked' : ''   ?>>Material Approval</label>
                                                <label class="checkbox-inline"><input type="checkbox" name="RAP" id="RAP" value="1" <?= ($data_ko['RAP'] == 1) ? 'checked' : ''   ?>>RAP</label>

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
                                        <button type="button" class="btn btn-xs btn-primary disabled" onClick="tambah_rincian()">Tambah <i class="fa fa-plus"></i></button>
                                    </th>
                                </tr>
                            </table><br>

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
                        <div class="box-header" style="background-color: #f39c12;color:white;">
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
                                        <input type="checkbox" name="k3[]" value="1" id="cb1" <?php echo ($data_ko['K3'] === '1') ? 'checked' : ''; ?> disabled required></label>
                                </div>
                                <div class="form-group">
                                    <label for="">2. Syarat Teknis</label>
                                    <textarea name="SyaratTeknis" id="SyaratTeknis" cols="5" rows="5" class="form-control input-sm " disabled><?= $data_ko['SyaratTeknis'] ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">3. Jadwal Pengiriman</label>
                                    <textarea name="JadwalPengiriman" id="JadwalPengiriman" cols="5" rows="5" class="form-control input-sm" disabled><?= $data_ko['JadwalPengiriman'] ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">4. Jadwal Pembayaran</label>
                                    <textarea name="JadwalPembayaran" id="JadwalPembayaran" cols="5" rows="5" class="form-control input-sm" disabled><?= $data_ko['JadwalPembayaran'] ?></textarea>
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
                                                <?php $check = ''; ?>
                                                <?php
                                                foreach ($checkSP as $r => $v) {
                                                    if ($v->item == $value) $check = 'checked';
                                                }
                                                ?>

                                                <li>
                                                    <span class="handle">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </span>
                                                    <input type="checkbox" <?= $check ?> name="SyaratPembayaran[]" value="<?= $value ?>" disabled>
                                                    <span class="text"><?= $value ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        </div>
                                    </ul>

                                </div>
                                <div class="form-group">
                                    <label for="">6. Sanksi</label>
                                    <textarea name="Sanksi" id="Sanksi" cols="5" rows="5" class="form-control input-sm" disabled><?= $data_ko['Sanksi'] ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">7. Keterangan Lain</label>
                                    <textarea name="Keterangan" id="Keterangan" cols="5" rows="5" class="form-control input-sm" disabled><?= $data_ko['Keterangan'] ?></textarea>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <!-- <div class="col-sm-12" style="padding-bottom:20px;">
                    <input type="hidden" id="NoKO" name="NoKO" value="<?= $data_ko['NoKO'] ?>">
                    <input type="hidden" id="LedgerNo" name="LedgerNo" value="<?= $data_ko['LedgerNo'] ?>">
                    <button onclick="history.back()" class="btn btn-sm btn-link pull-left" style="background-color:#34495e;color:white;"><i class="fa fa-arrow-left"></i> Batal</button>
                    <button class="btn btn-md btn-primary pull-right" type="submit" disabled>Simpan</button>

                </div> -->




            </div>


        </form>
    </section>

</div>

<script>
    $(document).ready(function() {
        // alert('ok');
        tampil_rincian_pekerjaan();


        $('.select2').select2({
            'theme': 'classic',
            'width': '100%',
        });

        $('.uang').mask("#,##0", {
            reverse: true
        });

        $('#tambah_Vol').keyup(function() {
            hitung();
        })

        $('#tambah_HrgSatuan').keyup(function() {
            hitung();
        })

        $('#form_tambah_uraian').submit(function(e) {
            e.preventDefault();

            mydata = $(this).serialize();
            $.ajax({
                type: "post",
                url: "<?= site_url('Approval_ko/aksi_simpan_uraian_ko') ?>",
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

                    })
                }
            });

        })


        $('#form_tambah_uraian_edit').submit(function(e) {
            e.preventDefault();

            mydata = $(this).serialize();
            $.ajax({
                type: "post",
                url: "<?= site_url('Approval_ko/aksi_edit_uraian_ko') ?>",
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

        })



        $('#edit_Vol').keyup(function() {
            hitung_edit();
        })

        $('#edit_HrgSatuan').keyup(function() {
            hitung_edit();
        })

        // $('#dikurangi_ko').keyup(function() {
        //     hitung_dtl();
        // })


    });


    function tampil_rincian_pekerjaan() {

        var NoKO = $('#NoKO').val();
        var LedgerNo = $('#LedgerNo').val();
        $.ajax({
            type: "post",
            url: "<?= site_url('Approval_ko/tampil_order_list') ?>",
            data: {
                'NoKO': NoKO,
                'LedgerNo': LedgerNo
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
            }
        });
    }
</script>