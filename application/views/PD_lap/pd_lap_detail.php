<style type="text/css">
    #my_form {
        font-size: 10px !important;
    }


    table td {
        padding-top: 3px;
        padding-bottom: 3px;
    }

    table td th {
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
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h6><b>DETAIL PD ( LAPANGAN ) </b></h6>
        <p></p>

        <label for="inputName" class="col-sm col-form-label"><?= $detail['JobNo'] ?></label> - <label for="inputName" class="col-sm col-form-label"><?= $detail['JobNm'] ?></label>
        <br>
        <small><label for="inputName" class="col-sm col-form-label"><?= $detail['Lokasi'] ?></label></small>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('dashboard')  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">PD & PJ</li>
        </ol>
    </section>

    <section class="content-header">
        <button class="btn btn-xs btn-warning" onclick="history.back()"><i class="fa fa-arrow-left"></i> Kembali</button>
        <!-- <a href="<?= site_url('Job/sub_job/' . $detail['JobNo']) ?>" class="btn btn-xs btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a> -->
        <!-- <a href="#save" data-toggle="modal" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</a> -->

    </section>

    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>

                <div class="box">

                    <div class="overlay loadingku" style="display: none;">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>

                    <div class="box-body">

                        <form id="my_form">

                            <div class="col-sm-6 row_saya">

                                <table width="100%" border="1">
                                    <tr>
                                        <th style="width:80px;">No. PD</th>
                                        <td style="width:10px;">:</td>
                                        <td>
                                            <input type="text" class="form-control input-sm" style="" value="<?= $data_pd['NoPD'] ?>" disabled>
                                        </td>
                                        <th>No. BAP/Ref Lapangan : </th>
                                        <td>
                                            <input type="text" class="form-control input-sm" name="NoRef" id="NoRef" placeholder="No.Ref" value="<?= $data_pd['NoRef'] ?>">
                                        </td>
                                    </tr>
                                </table>

                                <table width="100%" border=" 1">
                                    <tr>
                                        <th style="width:80px;">Job No</th>
                                        <td style="width:10px;">:</td>
                                        <td>
                                            <input type="text" class="form-control input-sm judul_job" value="<?= $detail['JobNo'] ?> - <?= $detail['JobNm'] ?>" disabled>
                                        </td>

                                    </tr>
                                </table>

                                <table width="100%" border=" 1">
                                    <tr>
                                        <th style="width:80px;">Tgl Permintaan</th>
                                        <td style="width:10px;">:</td>
                                        <td style="width: 200px;">
                                            <input type="date" class="form-control input-sm" name="TglPD" value="<?= $data_pd['TglPD'] ?>" required>
                                        </td>
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input " type="checkbox" name="Jaminan" id="Jaminan" value="1" <?= ($data_pd['Jaminan'] == 1) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="Jaminan">PD Jaminan</label>
                                            </div>

                                        </td>

                                    </tr>
                                </table>

                                <table width="100%" border=" 1">
                                    <tr>
                                        <th style="width:80px;">Deskripsi</th>
                                        <td style="width:10px;">:</td>
                                        <td>
                                            <textarea name="Deskripsi" id="Deskripsi" class="form-control input-sm"><?= $data_pd['Deskripsi']  ?></textarea>
                                        </td>


                                    </tr>
                                </table>

                                <table width="100%" border=" 1">
                                    <tr>
                                        <th style="width:80px;">Periode</th>
                                        <td style="width:10px;">:</td>
                                        <td>
                                            <input type="date" name="PrdAwal" id="PrdAwal" title="Periode Awal" value="<?= $data_pd['PrdAwal'] ?>" class="form-control input-sm" required>
                                        </td>
                                        <td style="width:10px !important;">s.d.</td>
                                        <td>
                                            <input type="date" name="PrdAkhir" id="PrdAkhir" title="Periode Akhir" value="<?= $data_pd['PrdAkhir'] ?>" class="form-control input-sm" required>
                                        </td>
                                        <td>Minggu Ke: </td>
                                        <td style="width:50px !important;">
                                            <input type="text" name="Minggu" id="Minggu" class="form-control input-sm" value="<?= $data_pd['Minggu'] ?>">
                                        </td>
                                    </tr>
                                </table>

                                <table width="100%" border=" 1">
                                    <tr>
                                        <th style="width:80px;">Alokasi</th>
                                        <td style="width:10px;">:</td>
                                        <td>
                                            <input type="text" value="<?= AlokasiBiasa($data_pd['Alokasi'])  ?>" class="form-control input-sm" disabled>
                                            <input type="hidden" name="Alokasi" id="Alokasi" value="<?= $data_pd['Alokasi'] ?>">

                                        </td>

                                        <td style="width:70px;">Tipe Form: </td>
                                        <td>
                                            <input type="text" class="form-control input-sm" value="<?= dapatkan_tipeform($data_pd['TipeForm']); ?>" disabled>
                                            <input type="hidden" name="TipeForm" id="TipeForm" class="form-control input-sm" value="<?= $data_pd['TipeForm'] ?>">

                                        </td>
                                    </tr>
                                </table>

                                <table width="100%" border=" 1">
                                    <tr>
                                        <th style="width:80px;"></th>
                                        <td style="width:10px;"></td>
                                        <td style="font-size:11px !important;text-align: center;" class="bg-info">
                                            BUKTI PENDUKUNG
                                        </td>
                                    </tr>
                                </table>

                                <table width="100%" border=" 1">
                                    <tr>
                                        <th style="width:80px;"></th>
                                        <td style="width:10px;"></td>
                                        <td>
                                            <div class="form-check form-check-inline" style="font-size: 10px;">
                                                <input class="form-check-input" type="checkbox" id="INV" value="INV" name="BuktiPendukung[]" <?= (CekBuktiPendukung($data_pd['BuktiPendukung'], 'INV') == TRUE) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="INV">Invoice/Kwitansi</label>
                                            </div><br>

                                            <div class="form-check form-check-inline" style="font-size: 10px;">
                                                <input class="form-check-input" type="checkbox" id="SJ" value="SJ" name="BuktiPendukung[]" <?= (CekBuktiPendukung($data_pd['BuktiPendukung'], 'SJ') == TRUE) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="SJ">Surat Jalan/Tanda Terima Lapangan</label>
                                            </div><br>

                                            <div class="form-check form-check-inline" style="font-size: 10px;">
                                                <input class="form-check-input" type="checkbox" id="PO" value="PO" name="BuktiPendukung[]" <?= (CekBuktiPendukung($data_pd['BuktiPendukung'], 'PO') == TRUE) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="PO">Copy PO</label>
                                            </div>


                                        </td>
                                        <td>
                                            <div class="form-check form-check-inline" style="font-size: 10px;">
                                                <input class="form-check-input" type="checkbox" id="FP" value="FP" name="BuktiPendukung[]" <?= (CekBuktiPendukung($data_pd['BuktiPendukung'], 'FP') == TRUE) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="FP">Faktur Pajak</label>
                                            </div><br>

                                            <div class="form-check form-check-inline" style="font-size: 10px;">
                                                <input class="form-check-input" type="checkbox" id="BAP" value="BAP" name="BuktiPendukung[]" <?= (CekBuktiPendukung($data_pd['BuktiPendukung'], 'BAP') == TRUE) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="BAP">Berita Acara Pembayaran</label>
                                            </div><br>

                                            <div class="form-check form-check-inline" style="font-size: 10px;">
                                                <input class="form-check-input" type="checkbox" id="BAOP" value="BAOP" name="BuktiPendukung[]" <?= (CekBuktiPendukung($data_pd['BuktiPendukung'], 'BAOP') == TRUE) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="BAOP">Berita Acara Opname Pekerjaan</label>
                                            </div>
                                        </td>
                                    </tr>
                                </table>

                                <table width="100%" border=" 1">
                                    <tr>
                                        <th style="width:80px;">Keterangan</th>
                                        <td style="width:10px;">:</td>
                                        <td>
                                            <input type="text" name="Keterangan" id="Keterangan" placeholder="Keterangan" class="form-control input-sm" style="width: 250px !important;" value="<?= $data_pd['Keterangan'] ?>">
                                        </td>
                                    </tr>
                                </table>


                            </div>

                            <div class="col-sm-6 row_saya">

                                <table width="100%" border="1">
                                    <tr>
                                        <td style="font-size:11px !important;text-align: center;" class="bg-danger">
                                            BAYAR KEPADA
                                        </td>
                                    </tr>
                                </table>

                                <table width="100%" border=" 1">

                                    <tr>
                                        <th style="width:80px;">No. Kontrak/PO</th>
                                        <td style="width:10px;">:</td>
                                        <td id="my_no_ko">
                                            <input type="text" class="form-control input-sm" value="<?= $data_pd['NoKO'] ?>" disabled>
                                            <input type="hidden" class="form-control input-sm" id="" name="NoKO" value="<?= $data_pd['NoKO'] ?>">

                                            <!-- <select name="NoKO" id="NoKO" class="form-control input-sm" required>
                                            <option value="">-- Pilih salah satu --</option>
                                        </select> -->
                                        </td>

                                    </tr>
                                </table>

                                <table width="100%" border=" 1">
                                    <tr>
                                        <th style="width:80px;">No. Tagihan</th>
                                        <td style="width:10px;">:</td>
                                        <td>
                                            <input type="text" name="NoTagihan" id="NoTagihan" class="form-control input-sm" placeholder="No. Tagihan" value="<?= $data_pd['NoTagihan'] ?>">
                                        </td>

                                    </tr>
                                </table>

                                <table width="100%" border=" 1">
                                    <?php
                                    $myvendorID = '';
                                    $myNoKO = $data_pd['NoKO'];
                                    if (cek_INT($myNoKO) !== 'INT') {
                                        $myvendorID = dapatkan_id_vendor($myNoKO);
                                    }


                                    ?>
                                    <tr>
                                        <th style="width:80px;">ID</th>
                                        <td style="width:10px;">:</td>
                                        <td>
                                            <input type="text" name="VendorId" id="VendorId" class="form-control input-sm" placeholder="ID" value="<?= $myvendorID ?>" readonly>
                                        </td>

                                    </tr>
                                </table>

                                <table width="100%" border=" 1">
                                    <tr>
                                        <th style="width:80px;">Nama</th>
                                        <td style="width:10px;">:</td>
                                        <td>
                                            <input type="text" name="Nama" id="Nama" class="form-control input-sm" placeholder="Nama" value="<?= $data_pd['Nama'] ?>" readonly>
                                        </td>

                                    </tr>
                                </table>

                                <table width="100%" border=" 1">
                                    <tr>
                                        <th style="width:80px;">Alamat</th>
                                        <td style="width:10px;">:</td>
                                        <td>
                                            <input type="text" name="Alamat" id="Alamat" class="form-control input-sm" placeholder="Alamat" value="<?= $data_pd['Alamat'] ?>" readonly>
                                        </td>

                                    </tr>
                                </table>



                                <table width="100%" border=" 1">
                                    <tr>
                                        <th style="width:80px;">Telepon</th>
                                        <td style="width:10px;">:</td>
                                        <td>
                                            <input type="text" name="Telepon" id="Telepon" class="form-control input-sm" placeholder="Telepon" value="<?= $data_pd['Telepon'] ?>" readonly>
                                        </td>

                                    </tr>
                                </table>

                                <table width="100%" border=" 1">
                                    <tr>
                                        <th style="width:80px;">NPWP</th>
                                        <td style="width:10px;">:</td>
                                        <td>
                                            <input type="text" name="NPWP" id="NPWP" class="form-control input-sm" placeholder="NPWP" value="<?= $data_pd['NPWP'] ?>" readonly>
                                        </td>

                                    </tr>
                                </table>


                                <table width="100%" border=" 1">
                                    <tr>
                                        <td style="font-size:11px !important;text-align: center;" class="bg-success">
                                            REKENING PENERIMA
                                        </td>
                                    </tr>
                                </table>

                                <table width="100%" border=" 1">
                                    <tr>
                                        <th style="width:80px;">No. Rekening</th>
                                        <td style="width:10px;">:</td>
                                        <td>
                                            <input type="text" name="NoRek" id="NoRek" class="form-control input-sm" placeholder="No. Rekening" value="<?= $data_pd['NoRek'] ?>" readonly>
                                        </td>
                                    </tr>
                                </table>

                                <table width="100%" border=" 1">
                                    <tr>
                                        <th style="width:80px;">Atas Nama</th>
                                        <td style="width:10px;">:</td>
                                        <td>
                                            <input type="text" name="AtasNama" id="AtasNama" class="form-control input-sm" placeholder="Atas Nama" value="<?= $data_pd['AtasNama'] ?>" readonly>
                                        </td>
                                    </tr>
                                </table>

                                <table width="100%" border=" 1">
                                    <tr>
                                        <th style="width:80px;">Bank</th>
                                        <td style="width:10px;">:</td>
                                        <td>
                                            <input type="text" class="form-control input-sm" value="<?= $data_pd['Bank'] ?>" disabled>
                                            <input type="hidden" id="Bank" name="Bank" class="form-control input-sm" value="<?= $data_pd['Bank'] ?>">

                                        </td>
                                    </tr>
                                </table>





                            </div>


                            <div class="col-sm-12 text-right">
                                <hr style="border:1px solid black;">
                                <input type="hidden" id="LedgerNo" name="LedgerNo" value="<?= $data_pd['LedgerNo'] ?>">
                                <input type="hidden" id="NoPD" name="NoPD" value="<?= $data_pd['NoPD'] ?>">
                                <input type="hidden" id="JobNo" name="JobNo" value="<?= $data_pd['JobNo'] ?>">

                                <button type="submit" class="btn btn-sm btn-primary btn-simpan">Simpan <i class="fa fa-save"></i></button>
                            </div>

                        </form>

                        <div class="col-sm-12">

                            <table width="100%" border="0" style="margin-top:10px !important;">
                                <tr>
                                    <th>
                                        <label class="radio-inline"><input type="radio" name="pilihan_pd" class="pilihan_pd" value="uraian_pd" style="font-size: 10px;" checked>Uraian PD</label>
                                        <label class="radio-inline"><input type="radio" name="pilihan_pd" class="pilihan_pd" value="invoice_pd" style="font-size: 10px;">Invoice</label>


                                    </th>

                                </tr>
                            </table>

                        </div>

                        <div class="tampil_pilihan"></div>


                    </div>
                </div>


            </div>
        </div>



    </section>


</div>
<script type="text/javascript">
    $(function() {
        pilihan_pd()
        hapus_border()

        $('.pilihan_pd').change(function() {
            pilihan_pd();
        });


        $('#my_form').submit(function(e) {
            e.preventDefault();
            $('.loadingku').hide();

            var TotalPD = $('#TotalPD').val();
            // alert(TotalPD);
            // return false;



            $.ajax({
                    url: '<?= site_url('PD_lap/edit_pdhdr') ?>',
                    type: 'POST',
                    dataType: 'JSON',
                    data: $(this).serialize() + "&TotalPD=" + TotalPD,
                    beforeSend: function() {
                        $('.loadingku').show();
                    }
                })
                .done(function(response) {
                    $('.loadingku').hide();

                    console.log(response);


                    // if (response.pesan == 'no_pd_sudah_terpakai') {
                    //     toastr.info('No. PD sudah terpakai');
                    //     return false;
                    // }

                    if (response.pesan == 'uraian_kosong') {

                        $.toast({
                            heading: 'Info',
                            text: 'Uraian masih kosong',
                            icon: 'info',
                            loader: true,
                            // loaderBg: '#9EC600'
                        })
                        return false;

                    }

                    if (response.pesan == 'gagal') {
                        $.toast({
                            heading: 'Error',
                            text: 'Gagal, silahkan ulangi',
                            icon: 'error',
                            loader: true,
                            // loaderBg: '#9EC600'
                        })
                        return false;
                    }

                    if (response.pesan == 'berhasil') {
                        window.location.href = "<?= site_url('PD_lap/view/') ?>" + response.JobNo;
                        return false;

                    }



                })
                .fail(function() {
                    $('.loadingku').hide();
                    $.toast({
                        heading: 'Error',
                        text: 'Terjadi Kesalahan',
                        icon: 'error',
                        loader: true,
                        // loaderBg: '#9EC600'
                    })
                    return false;
                })
                .always(function() {

                });



        })

    })

    function pilihan_pd() {
        var type = $('input[name=pilihan_pd]:checked').val();
        var NoPD = $('#NoPD').val();
        var JobNo = $('#JobNo').val();
        var Alokasi = $('#Alokasi').val();
        var LedgerNo = $('#LedgerNo').val()


        // var Alokasi = $('#Alokasi').val();
        // var LedgerNo = $('#LedgerNo').val();
        // alert(LedgerNo);
        // return false;
        $('.tampil_pilihan').html('');

        $.ajax({
            type: "POST",
            url: "<?= site_url('PD_lap/tampil_uraian_atau_invoice') ?>",
            data: {
                'type': type,
                'NoPD': NoPD,
                'JobNo': JobNo,
                'Alokasi': Alokasi,
                'LedgerNo': LedgerNo
            },
            dataType: "html",
            beforeSend: function() {
                $('.loadingku').show();
            },
            success: function(response) {
                $('.loadingku').hide();
                $('.tampil_pilihan').html(response);
                // console.log(response);
            },
            error: function() {
                $('.loadingku').hide();
                $.toast({
                    heading: 'Error',
                    text: 'Terjadi Kesalahan',
                    icon: 'error',
                    loader: true,
                    // loaderBg: '#9EC600'
                })
                return false;
            }
        });

    }

    function hapus_border() {
        $('.row_saya table').removeAttr('border');
    }
</script>