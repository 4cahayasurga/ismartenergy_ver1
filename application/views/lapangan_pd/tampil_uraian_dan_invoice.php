<style>
    .no-border {
        border: 0;
        box-shadow: none;
    }
</style>

<!-- untuk perubahan ketika pilih uraian atau invoice -->


<div id="detail_saya">

    <table width="100%">
        <tr>
            <th class="text-center" style="font-size:14px;background-color:Gray;color:white;">URAIAN PERMINTAAN DANA</th>
        </tr>
    </table>
    <br>
    <div class="col-sm-12" style="border:1px solid silver;">
        <br>
        <button class="btn btn-sm btn-danger btn_tambah_detail" id="btn_tambah_detail">Tambah</button>
        <br><br>

        <table id="table_detail" class="table table-bordered table-striped table-hover table-condesed">
            <thead>
                <tr style="background-color:#27ae60;">
                    <th width="5%" class="text-center">No.</th>
                    <th width="13%" class="text-center">#</th>
                    <th width="20%">Kode RAP</th>
                    <th width="25%">Uraian</th>
                    <th width="8%" class="text-center">Vol</th>
                    <th width="8%" class="text-center">Sat</th>
                    <th width="15%" class="text-center">Harga Satuan</th>
                    <th width="15%" class="text-center">Jumlah Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $SubTotal = 0;
                $no = 1;

                foreach ($data_detail as $data_uraian) :
                    $SubTotal += $data_uraian['JumlahHarga'];
                ?>


                    <tr>
                        <td width="5%" class="text-center"><?= $no++ ?></td>
                        <td width="13%" class="text-center">
                            <a href="javascript:void(0)" class="btn btn-xs btn_hapus" data-nopd="<?= $data_uraian['NoPD'] ?>" data-norut="<?= $data_uraian['NoUrut'] ?>" style="background-color: #e74c3c;color:white;"><i class="fa fa-trash"></i> Hapus</a>
                            <a href="javascript:void(0)" class="btn btn-xs btn_ubah_detail" data-KdRAP="<?= $data_uraian['KdRAP'] ?>" data-nopd="<?= $data_uraian['NoPD'] ?>" data-norut="<?= $data_uraian['NoUrut'] ?>" data-Uraian="<?= $data_uraian['Uraian'] ?>" data-Vol="<?= KhususDecimal($data_uraian['Vol']) ?>" data-Uom="<?= $data_uraian['Uom'] ?>" data-HrgSatuan="<?= number_format($data_uraian['HrgSatuan']) ?>" data-TotalHarga="<?= number_format($data_uraian['JumlahHarga']) ?>" style="background-color: #f39c12;color:white;"><i class="fa fa-pencil"></i> Ubah</a>
                        </td>
                        <td width="20%"><?= $data_uraian['KdRAP_detail'] ?></td>
                        <td width="25%"><?= strtoupper($data_uraian['Uraian']) ?></td>
                        <td width="8%" class="text-center"><?= HilangkanDecimal($data_uraian['Vol']) ?></td>
                        <td width="8%" class="text-center"><?= strtoupper($data_uraian['Uom']) ?></td>
                        <td width="15%" class="text-center"><?= number_format($data_uraian['HrgSatuan']) ?></td>
                        <td width="15%" class="text-center"><?= number_format($data_uraian['JumlahHarga']) ?></td>
                    </tr>

                <?php endforeach; ?>


            </tbody>
        </table>

        <?php

        $SaldoSebelumnya = 0;
        $TotalPermintaan = $SubTotal - $SaldoSebelumnya;
        ?>

        <table width="100%" border="0" style="font-size: 12px!important;">
            <tr>
                <th style="font-size: 12px!important;padding-right: 10px;" width="85%" class="text-right">Sub Total</th>
                <th style="font-size: 12px!important;" class="text-right">
                    <input type="text" class="form-control input-sm" style="font-size: 12px!important;text-align: right;" name="TotalPD" value="<?= number_format($SubTotal) ?>" readonly>
                </th>
            </tr>
        </table>

        <table width="100%" border="0">
            <tr>
                <th width="80%" class="text-right" style="padding-right: 10px;">Saldo Sebelumnya</th>
                <th width="10%" class="text-right" style="padding-top: 10px;padding-right: 10px;">
                    <input type="text" class="form-control input-sm" style="font-size: 12px!important;" disabled>
                </th>
                <th class="text-right" style="padding-top: 10px;">
                    <input type="text" class="form-control input-sm" style="font-size: 12px!important;text-align: right;" value="<?= number_format($SaldoSebelumnya) ?>" disabled>
                </th>
            </tr>
        </table>

        <table width="100%" border="0" style="font-size: 12px!important;">
            <tr>
                <th width="70%" class="text-blue"><i>*Terbilang : <?= ucwords(terbilang($TotalPermintaan)) ?> </i></th>
                <th width="10%" style="font-size: 12px!important;padding-right: 10px;" class="text-right">Total Permintaan</th>
                <th style="font-size: 12px!important;padding-top: 10px;padding-bottom: 10px;" class="text-right">
                    <input type="text" class="form-control input-sm" style="font-size: 12px!important;text-align: right;" value="<?= number_format($TotalPermintaan) ?>" disabled>
                </th>
            </tr>
        </table>




    </div>

    <script>
        $(function() {

            $('.uang').mask("#,##0", {
                reverse: true
            });

            $('.select2').select2({
                'theme': 'classic',
                'width': '100%',

            });

            $('#table_detail').DataTable();

            $('.btn_hapus').click(function() {
                var NoPD = $(this).attr('data-nopd');
                var NoUrut = $(this).attr('data-norut');

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
                            url: "<?= site_url('Lapangan_pd/hapus_detail_pd') ?>",
                            data: {
                                'NoUrut': NoUrut,
                                'NoPD': NoPD,
                                'type': 'tambah_pd'
                            },
                            dataType: "html",
                            beforeSend: function() {
                                $('.loading_table_uraian_dan_invoice').show();
                            },
                            success: function(data) {
                                $('.loading_table_uraian_dan_invoice').hide();
                                table_detail_tanpa_ko();

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

                            },
                            error: function() {
                                $('.loading_table_uraian_dan_invoice').hide();
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

                });



            });



            $('#btn_tambah_detail').click(function() {
                bersihkan_form();
                $('#modal_tambah_detail_pd').modal('show');
            });

            $('.btn_ubah_detail').click(function() {
                bersihkan_form();

                var NoPD = $(this).attr('data-nopd');
                var NoUrut = $(this).attr('data-norut');
                var KdRAP = $(this).attr('data-KdRAP');
                var Uraian = $(this).attr('data-Uraian');
                var Vol = $(this).attr('data-Vol');
                var Uom = $(this).attr('data-Uom');
                var HrgSatuan = $(this).attr('data-HrgSatuan');
                var TotalHarga = $(this).attr('data-TotalHarga');

                // alert(JumlahHarga);

                $('#edit_KdRAP').val(KdRAP).trigger('change');
                $('#edit_Uraian').val(Uraian);
                $('#edit_Vol').val(Vol);
                $('#edit_Uom').val(Uom);
                $('#edit_HrgSatuan').val(HrgSatuan);
                $('#edit_TotalHarga').val(TotalHarga);
                $('#nopd_edit').val(NoPD);
                $('#norut_edit').val(NoUrut);



                $('#modal_edit_detail_pd').modal('show');
            });



        });
    </script>

</div>

<div id="invoice_saya">

    <table width="100%">
        <tr>
            <th class="text-center" style="font-size:14px;background-color:Gray;color:white;">LIST INVOICE</th>
        </tr>
    </table>
    <br>

    <table border="0">
        <tr>
            <th width="80px">Total Invoice : </th>
            <th width="150px">
                <input type="text" class="form-control input-sm" value="0">
            </th>
            <th width="100px" style="padding-left: 15px;">Total Centang : </th>
            <th width="150px">
                <input type="text" class="form-control input-sm" value="0">
            </th>
        </tr>
    </table>

    <br>

    <table width="100%" id="invoice_table" class="table table-bordered table-striped table-hover table-condensed" style="font-size: 11px;">
        <thead>
            <tr style="background-color: #e84393;color:white;">
                <th>#</th>
                <th>No</th>
                <th>No KO</th>
                <th>Vendor</th>
                <th>No Invoice</th>
                <th>Tgl Invoice</th>
                <th>Jatuh Tempo</th>
                <th>Total (Rp)</th>
                <th>PPN (Rp)</th>
                <th>No FP</th>
                <th>Tgl FP</th>
                <th>Total Bayar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $nomor = 1;
            foreach ($data_invoice as $data_invoice) : ?>

                <tr>
                    <td></td>
                    <td><?= $nomor++ ?></td>
                    <td><?= $data_invoice['NoKO'] ?></td>
                    <td>-</td>
                    <td><?= $data_invoice['InvNo'] ?></td>
                    <td><?= TglIndoGarisMiring_tanpa_waktu($data_invoice['InvDate']) ?></td>
                    <td><?= TglIndoGarisMiring_tanpa_waktu($data_invoice['DueDate']) ?></td>
                    <td><?= UbahGabunganUang($data_invoice['Total']) ?></td>
                    <td><?= UbahGabunganUang($data_invoice['PPN']) ?></td>
                    <td><?= $data_invoice['FPNo'] ?></td>
                    <td><?= TglIndoGarisMiring_tanpa_waktu($data_invoice['FPDate']) ?></td>
                    <td><?= UbahGabunganUang($data_invoice['TotalPayment']) ?></td>

                </tr>

            <?php endforeach; ?>
        </tbody>
    </table>




</div>



<script>
    function berubah(type) {
        if (type == 'pd_detail') {
            $('#detail_saya').css("display", "");
            $('#invoice_saya').css("display", "none");
        } else {
            $('#detail_saya').css("display", "none");
            $('#invoice_saya').css("display", "");
        }
    }

    $(function() {

        $('#invoice_table').DataTable();

        $('#detail_saya').css("display", "none");
        $('#invoice_saya').css("display", "none");
        var type_saya = $('input[name="menu_detail"]:checked').val();

        berubah(type_saya);

        $('input[name="menu_detail"]').change(function() {
            berubah(this.value);
        });
    })
</script>