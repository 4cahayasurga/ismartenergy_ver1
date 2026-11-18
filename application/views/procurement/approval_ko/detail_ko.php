<style>
    .atur {
        font-size: 10px;
        /* font-family: 'Courier New', Courier, monospace; */
        font-family: Arial, Helvetica, sans-serif;

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
        <div class="col-sm-4" style="padding-top:10px;">
            <div class="alert alert-success">
                <strong> <i class="fa fa-check"></i> Approved By <b style="color:black;"><?= strtoupper($data_ko['ApprovedBy']) ?></b></strong>
            </div>
        </div>


    <?php } ?>


    <section class="content-header">

        <form id="form_kohdr">

            <div class="row">


                <div class="col-sm-12">

                    <div class="box">

                        <div class="box-header bg-info">
                            <div class="box-tile">
                                <h5>PENERIMA KONTRAK</h5>
                            </div>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                            </div>
                        </div>
                        <div class="box-body">

                            <div class="row">

                                <div class="col-sm-6">

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

                                    </table>




                                </div>

                                <div class="col-sm-6">

                                    <table width="100%" border="0">
                                        <tr>
                                            <th width="100px" style="padding-top:10px;padding-bottom:10px;">Tgl. Kontrak</th>
                                            <td>
                                                <input type="date" class="form-control input-sm" name="TglKO" id="TglKO" value="<?= $data_ko['TglKO'] ?>" disabled>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th style="padding-top:10px;padding-bottom:10px;">No. Kontrak</th>
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


                <div class="col-sm-12">
                    <div class="box">

                        <div class="box-header bg-success">
                            <div class="box-tile">
                                <h5>RINCIAN PEKERJAAN</h5>
                            </div>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                            </div>
                        </div>

                        <div class="box-body">



                            <table>
                                <tr>
                                    <th>
                                        <button type="button" class="btn btn-xs btn-primary disabled" onClick="tambah_rincian()">Tambah Rincian <i class="fa fa-plus"></i></button>
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

                <div class="col-sm-12" style="padding-bottom:20px;">
                    <input type="hidden" id="NoKO" name="NoKO" value="<?= $data_ko['NoKO'] ?>">
                    <input type="hidden" id="LedgerNo" name="LedgerNo" value="<?= $data_ko['LedgerNo'] ?>">
                    <button onclick="history.back()" class="btn btn-sm btn-link pull-left" style="background-color:#34495e;color:white;"><i class="fa fa-arrow-left"></i> Batal</button>
                    <button class="btn btn-md btn-primary pull-right" type="submit" disabled>Simpan</button>

                </div>




            </div>


        </form>
    </section>



</div>


<div class="modal fade" id="modal_uraian" style="font-size: 10px;">
    <div class="modal-dialog modal-xs" id="modal_dialog_uraian">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="modal_title_uraian">Tambah Rincian Pekerjaan<br>
                    <p style="color:#e74c3c"><?= $data_ko['NoKO'] . ' - ' . $data_ko['JobNm'] . ' - ' . $data_ko['VendorNm']  ?> </p>
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
                    <p style="color:#e74c3c"><?= $data_ko['NoKO'] . ' - ' . $data_ko['JobNm'] . ' - ' . $data_ko['VendorNm']  ?></p>
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

    // function hitung_dtl() {
    //     alert('ok');
    //     var subtotal = $('#subtotal').val();
    //     var dikurangi_ko = $('#dikurangi_ko').val();
    //     var total_pengurangan = $('#total_pengurangan').val();
    //     var grand_total = $('#grand_total').val();

    //     var total = parseInt(subtotal) - parseInt(dikurangi_ko);


    //     $('#total_pengurangan').val(total);
    //     $('#grand_total').val(total);

    // }





    function tampil_rincian_pekerjaan() {

        var NoKO = $('#NoKO').val();
        var LedgerNo = $('#LedgerNo').val();
        $.ajax({
            type: "post",
            url: "<?= site_url('Approval_ko/tampil_rincian_pekerjaan') ?>",
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

    function bersihkan_form_tambah() {
        $('#tambah_KdRAP').val('').trigger('change');
        $('#tambah_Uraian').val('');
        $('#tambah_Vol').val('1');
        $('#tambah_Uom').val('');
        $('#tambah_HrgSatuan').val('0');
        $('#tambah_TotalHarga').val('0');
    }

    function bersihkan_form_edit() {
        $('#edit_KdRAP').val('').trigger('change');
        $('#edit_Uraian').val('');
        $('#edit_Vol').val('');
        $('#edit_Uom').val('');
        $('#edit_HrgSatuan').val('');
        $('#edit_TotalHarga').val('');
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

        // alert('ininin');
        // return false;

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
                    url: "<?= site_url('Approval_ko/hapus_uraian') ?>",
                    data: {
                        'NoUrut': NoUrut,
                        'NoKO': NoKO
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

                        tampil_rincian_pekerjaan();

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


    function edit_uraian(NoUrut) {



        var NoKO = $('#NoKO').val();

        $.ajax({
            type: "POST",
            url: "<?= site_url('Approval_ko/dapatkan_data_kodtl') ?>",
            data: {
                'NoUrut': NoUrut,
                'NoKO': NoKO
            },
            dataType: "json",
            beforeSend: function() {
                $('.loadingku').show();
            },
            success: function(data) {
                $('.loadingku').hide();
                bersihkan_form_edit();

                $('#modal_uraian_edit').modal('show');


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

    function hitung_edit() {

        var HrgSatuan = $('#edit_HrgSatuan').val();
        var Vol = $('#edit_Vol').val();

        HrgSatuan = HapusKoma(HrgSatuan);
        Vol = HapusKoma(Vol);

        var total = parseInt(Vol) * parseInt(HrgSatuan);

        $('#edit_TotalHarga').val(addCommas(total));

    }
</script>