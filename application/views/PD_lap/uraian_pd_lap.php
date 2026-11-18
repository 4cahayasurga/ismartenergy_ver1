<!-- <div class="row"> -->
<style>
    .table_saya {
        font-size: 10px !important;
    }
</style>
<div class="col-sm-12">

    <div class="box">
        <div class="overlay loadinguraian" style="display: none;">
            <i class="fa fa-refresh fa-spin"></i>
        </div>
        <div class="box-body">
            <?= (!empty($this->session->flashdata('pesan'))) ? $this->session->flashdata('pesan') : ''  ?>
            <div class="table-responsive">
                <table style="width:100%;border : 1px solid silver;background-color:#bdc3c7;">
                    <tr>
                        <td style="text-align: center;">URAIAN PERMINTAAN DANA</td>
                    </tr>
                </table>
                <table style="width:100%;margin-top:3px;" class="table_saya">
                    <tr>
                        <td style="text-align: left;">
                            <button type="button" class="btn btn-sm btn-success btn-tambah-uraian" onclick="btn_tambah_uraian()" id="btn-tambah-uraian">Tambah Uraian</button>
                        </td>
                    </tr>
                </table>

                <table style="width:100%;margin-top:3px;font-size:12px;" class="table table-striped table-hover table-condensed mt-2 table_saya" id="my_table_uraian" data-ini="<?= $NoPD ?>">
                    <thead>
                        <tr class=" bg-info">
                            <th class="text-center" style="padding-top: 3px;padding-bottom: 3px;">#</th>
                            <th class="text-center" style="padding-top: 3px;padding-bottom: 3px;">No.</th>
                            <th style="padding-top: 3px;padding-bottom: 3px;">Kode RAP</th>
                            <th style="padding-top: 3px;padding-bottom: 3px;">Uraian</th>
                            <th style="padding-top: 3px;padding-bottom: 3px;">Vol</th>
                            <th style="padding-top: 3px;padding-bottom: 3px;">Sat</th>
                            <th style="padding-top: 3px;padding-bottom: 3px;">Harga Satuan (Rp)</th>
                            <th style="padding-top: 3px;padding-bottom: 3px;">Jumlah Harga (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $sub_total = 0;
                        if ($mydatauraian->num_rows() == 0) {
                            echo "<tr><td colspan='7'>Data tidak ditemukan</td></tr>";
                        } else {
                            foreach ($mydatauraian->result_array() as $uraian) {
                                $sub_total += $uraian['JumlahHarga'];
                        ?>
                                <tr>
                                    <td style="padding-top: 3px;padding-bottom: 3px;text-align:center;">
                                        <a href="javascript:void(0)" class="btn btn-danger btn-xs hapus_uraian" style="font-size:10px;" data-nourut="<?= $uraian['NoUrut'] ?>"><i class="fa fa-trash"></i> Hapus</a>
                                        <a href="javascript:void(0)" class="btn btn-warning btn-xs edit_uraian" style="font-size:10px;" data-nourut="<?= $uraian['NoUrut'] ?>"><i class="fa fa-edit"></i> Edit</a>

                                    </td>
                                    <td class="text-center" style="padding-top: 3px;padding-bottom: 3px;"><?= $no++ . '.' ?></td>
                                    <td style="padding-top: 3px;padding-bottom: 3px;"><?= $uraian['KdRAP'] ?></td>
                                    <td style="padding-top: 3px;padding-bottom: 3px;"><?= $uraian['Uraian'] ?></td>
                                    <td style="padding-top: 3px;padding-bottom: 3px;"><?= floor($uraian['Vol']) ?></td>
                                    <td style="padding-top: 3px;padding-bottom: 3px;"><?= strtoupper($uraian['Uom']) ?></td>
                                    <td style="padding-top: 3px;padding-bottom: 3px;"><?= UbahGabunganUang($uraian['HrgSatuan']) ?></td>
                                    <td style="padding-top: 3px;padding-bottom: 3px;"><?= UbahGabunganUang($uraian['JumlahHarga']) ?></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                    <!-- <tfoot>
                        <?php
                        $HitungTotalPermintaan =  $sub_total - $SaldoSebelumnya;
                        ?>
                    </tfoot> -->

                    <table style="width: 100%;" class="table_saya">
                        <tr>
                            <th style="text-align: right; padding-right:30px;">Sub Total</th>
                            <td style="width: 180px !important;">
                                <input type="text" class="form-control form-control-sm uang" style="text-align: right;" value="<?= UbahGabunganUang($sub_total) ?>" disabled>

                            </td>
                        </tr>
                    </table>

                    <table style="width: 100%;" class="text-right table_saya">
                        <tr>
                            <th style="text-align: right;padding-right:30px;">Saldo sebelumnya dari No</th>
                            <td style="width: 180px !important;padding-right:5px;">
                                <input type="text" id="saldo_sebelumnya_nomor" class="form-control form-control-sm" value="<?= $NoPD ?>" disabled>
                            </td>
                            <td style="width: 180px !important;">
                                <input type="text" class="form-control form-control-sm uang" value="<?= UbahGabunganUang($SaldoSebelumnya) ?>
                                " readonly>
                            </td>
                        </tr>
                    </table>

                    <table style="width: 100%;" class="table_saya">
                        <tr>
                            <th style="text-align: right;padding-right:30px;">Total Permintaan</th>
                            <td style="width: 180px !important;">
                                <input type="text" style="text-align: right;" class="form-control form-control-sm uang" value="<?= UbahGabunganUang($HitungTotalPermintaan) ?>" disabled>
                                <input type="hidden" name="TotalPD" id="TotalPD" value="<?= $HitungTotalPermintaan ?>">
                                <input type="hidden" id="mypd" value="<?= $NoPD ?>" disabled>
                                <input type="hidden" id="myledger" value="<?= $LedgerNo ?>" disabled>
                            </td>
                        </tr>
                    </table>


                </table>


            </div>

        </div>
    </div>
</div>

<!-- </div> -->

<div class="modal fade" role="dialog" id="modal_saya">
    <div class="modal-dialog modal-md" id="modal_saya_dialog">
        <div class="modal-content">

            <div class="modal-header" id="modal_saya_header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div id="modal_saya_title"></div>
                <!-- <h4 class="modal-title" id="modal_saya_title"></h4> -->

            </div>
            <div class="modal-body" id="modal_saya_body">

            </div>

        </div>
    </div>
</div>

<script>
    $(function() {

        $('.edit_uraian').click(function() {
            var NoUrut = $(this).attr('data-nourut');
            var NoPD = $('#mypd').val();
            var JobNo = $('#JobNo').val();
            var Alokasi = $('#Alokasi').val();

            $.ajax({
                type: "POST",
                url: "<?= site_url('PD_lap/edit_uraian_pd_saat_edit_pd') ?>",
                data: {
                    'NoPD': NoPD,
                    'NoUrut': NoUrut,
                    'JobNo': JobNo,
                    'Alokasi': Alokasi
                },
                dataType: "html",
                beforeSend: function() {
                    $('.loadinguraian').show();
                },
                success: function(response) {
                    $('.loadinguraian').hide();
                    bersihkan_modal();

                    $('#modal_saya').modal('show');
                    $('#modal_saya_title').html('<h6><b>Edit Uraian PD || update</b></h6>');
                    $('#modal_saya_body').html(response);

                },
                error: function() {
                    $('.loadinguraian').hide();
                    $.toast({
                        heading: 'Error',
                        text: 'Terjadi Kesalahan',
                        icon: 'error',
                        loader: true,
                        // loaderBg: '#9EC600'
                    })
                }
            });
        })

        $('.hapus_uraian').click(function() {
            var no_urut = $(this).attr('data-nourut');
            var NoPD = $('#mypd').val();

            Swal({
                title: 'Anda yakin?',
                text: "Apakah yakin ingin menghapus data ini?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yakin',
                cancelButtonColor: '#d33',

            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "<?= site_url('PD_lap/hapus_uraian') ?>",
                        type: "POST",
                        data: {
                            'NoUrut': no_urut,
                            'NoPD': NoPD,
                        },
                        beforeSend: function() {
                            $('.loadinguraian').show();
                        },
                        success: function(response) {
                            $('.loadinguraian').hide();
                            pilihan_pd();

                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            $('.loadinguraian').hide();
                            $.toast({
                                heading: 'Error',
                                text: 'Terjadi Kesalahan',
                                icon: 'error',
                                loader: true,
                                // loaderBg: '#9EC600'
                            })
                        }
                    });
                }
            });
        })
    })

    function bersihkan_modal() {
        // $('#modal_saya_dialog').removeAttr('modal-xs modal-sm modal-md modal-lg modal-xl modal-full-width');
        $('#modal_saya_title').html('');
        $('#modal_saya_body').html('');
    }

    function btn_tambah_uraian() {
        $('.loadinguraian').hide();
        var NoPD = $('#mypd').val();
        var LedgerNo = $('#myledger').val();
        var Alokasi = $('#Alokasi').val();
        var JobNo = $('#JobNo').val();
        // alert(JobNo);

        $.ajax({
            type: "POST",
            url: "<?= site_url('PD_lap/tambah_uraian_pd_saat_edit_pd') ?>",
            data: {
                'NoPD': NoPD,
                'LedgerNo': LedgerNo,
                'Alokasi': Alokasi,
                'JobNo': JobNo
            },
            dataType: "html",
            beforeSend: function() {
                $('.loadinguraian').show();
            },
            success: function(response) {
                $('.loadinguraian').hide();
                bersihkan_modal();

                $('#modal_saya').modal('show');
                $('#modal_saya_title').html('<h6><b>Tambah Uraian PD || update</b></h6>');
                $('#modal_saya_body').html(response);

            },
            error: function() {
                $('.loadinguraian').hide();
                $.toast({
                    heading: 'Error',
                    text: 'Terjadi Kesalahan',
                    icon: 'error',
                    loader: true,
                    // loaderBg: '#9EC600'
                })
            }
        });


    }
</script>