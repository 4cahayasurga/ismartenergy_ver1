<style>
    .mykonten {
        font-family: Arial, Helvetica, sans-serif !important;
        font-size: x-small !important;
    }

    /* .modal-dialog {
        width: 90%;
        height: 50%;
        margin: 0;
        padding: 0;
    }

    .modal-content {
        height: auto;
        min-height: 100%;
        border-radius: 0;
    } */
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h6>PD & PJ ( LAPANGAN ) </h6>
        <p></p>

        <label for="inputName" class="col-sm col-form-label"><?php echo $pdpj->JobNo ?></label> - <label for="inputName" class="col-sm col-form-label"><?php echo $pdpj->JobNm ?></label>
        <br>
        <small><label for="inputName" class="col-sm col-form-label"><?php echo $pdpj->Lokasi ?></label></small>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('dashboard')  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">PD & PJ</li>
        </ol>
    </section>

    <section class="content-header">

        <a href="<?= site_url('Job/sub_job/' . $pdpj->JobNo) ?>" class="btn btn-xs btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
        <!-- <a href="#save" data-toggle="modal" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</a> -->

    </section>

    <section class="content">
        <?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>
        <div class="box box-success">

            <div class="box-header with-border">
                <h1 class="box-title mykonten">Entry Permintaan Dana ( Lapangan )</h1>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>
            <div class="box-body">

                <div class="row">
                    <div class="col-sm-6">

                        <table border="0" width="100%">
                            <tr>
                                <th style="padding-top:5px;padding-bottom:5px;" width="100px">Alokasi</th>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <select name="Alokasi" id="Alokasi" onchange="alokasi_ku_berganti()" class="form-control form-control-sm" required>
                                        <option value="">-- Pilih Salah Satu</option>
                                        <?php foreach ($data_alokasi as $alokasi) : ?>
                                            <option value="<?= $alokasi->Alokasi ?>"><?= $alokasi->Alokasi ?> - <?= $alokasi->Keterangan ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                        </table>

                        <table border="0" width="100%">
                            <tr>
                                <th style="padding-top:5px;padding-bottom:5px;" width="100px"></th>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <label for="">Dari Tanggal</label>
                                    <input type="date" class="form-control form-control-sm" id="dari_tgl" onchange="alokasi_ku_berganti()" name="dari_tgl" value="<?= $dari_tgl ?>" required>
                                </td>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    s.d
                                </td>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <label for="">Sampai Tanggal</label>
                                    <input type="date" class="form-control form-control-sm" id="sampai_tgl" onchange="alokasi_ku_berganti()" name="sampai_tgl" value="<?= $sampai_tgl ?>" required>
                                </td>
                            </tr>
                        </table>

                        <table border="0" width="100%">
                            <tr>
                                <th style="padding-top:5px;padding-bottom:5px;" width="100px">Pencarian</th>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <input type="text" class="form-control form-control-sm" id="Pencarian" placeholder="No.PD / No.KO" name="Pencarian">
                                </td>
                            </tr>


                        </table>
                        <table border="0" width="100%">
                            <tr>
                                <th style="padding-top:5px;padding-bottom:5px;" width="100px"></th>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <input type="hidden" id="MyJobNo" value="<?= $pdpj->JobNo ?>">
                                    <button id="lakukan_cari" class="btn btn-xs btn-success"><i class="fa fa-search"></i> Cari</button>
                                </td>
                            </tr>
                        </table>



                    </div>

                </div>

                <div class="tampil_table"></div>




            </div>
            <div class="overlay loadingku" style="display: none;">
                <i class="fa fa-refresh fa-spin"></i>
            </div>


        </div>


    </section>


</div>




<script>
    $(function() {
        // alert('ini');
        $('#lakukan_cari').click(function() {
            alokasi_ku_berganti();
        });

        // $('#modal_pd').modal('show');

        // $('.print_pd').click(function() {
        //     alert('ni');
        // })

    })





    function alokasi_ku_berganti() {

        var Alokasi = $('#Alokasi :selected').val();


        var Pencarian = $('#Pencarian').val();
        var MyJobNo = $('#MyJobNo').val();

        var dari_tgl = $('#dari_tgl').val();
        var sampai_tgl = $('#sampai_tgl').val();

        $('.tampil_table').html('');
        // alert(sampai_tgl);
        // return false;

        if (Alokasi == '') {
            $.toast({
                heading: 'Information',
                text: 'Alokasi masih kosong',
                icon: 'info',
                loader: true,
                loaderBg: '#9EC600'
            })

            return false;
        }

        if (dari_tgl == '' || sampai_tgl == '') {
            $.toast({
                heading: 'Information',
                text: 'Tanggal masih kosong',
                icon: 'info',
                loader: true,
                loaderBg: '#9EC600'
            })
            return false;
        }

        $.ajax({
            type: "POST",
            url: "<?= site_url('PD_lap/tampil_table_pd_lapangan') ?>",
            data: {
                'Alokasi': Alokasi,
                'Pencarian': Pencarian,
                'JobNo': MyJobNo,
                'dari_tgl': dari_tgl,
                'sampai_tgl': sampai_tgl,
            },
            dataType: "HTML",
            beforeSend: function() {
                $('.loadingku').show();
            },
            success: function(response) {
                $('.loadingku').hide();
                $('.tampil_table').html(response);
            },
            error: function() {
                $.toast({
                    heading: 'Information',
                    text: 'Terjadi Kesalahan',
                    icon: 'error',
                    loader: true,
                    loaderBg: '#9EC600'
                })
                return false;
            }
        });

    }
</script>