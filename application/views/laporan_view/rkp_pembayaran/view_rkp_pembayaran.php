<style>
    .mykonten {
        font-family: Arial, Helvetica, sans-serif !important;
        font-size: 10px !important;
    }

    .atur_td {
        width: 100px;
    }

    .myFont {
        font-size: 10px !important;
    }

    .select2 {
        font-size: 9px;
    }
</style>

<div class="content-wrapper">

    <section class="content-header">
        <h6 style="font-weight: bold;"><?= (isset($judul)) ? strtoupper($judul) : '' ?></h6>

        <ol class="breadcrumb">
            <li><a href="<?= site_url('dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?= (isset($judul)) ? $judul : '' ?></li>
        </ol>
    </section>

    <section class="content-header">

        <button class="btn btn-xs" style="background-color:#7f8c8d;" onclick="history.back()"><i class="fa fa-arrow-left"></i> Back</button>

    </section>

    <section class="content">
        <?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>

        <div class="box box-success">

            <div class="box-header with-border">

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>
            <div class="box-body">



                <div class="table-responsive">

                    <table width="40%" border="0" class="mykonten">
                        <tr>
                            <th style="padding-top:5px;padding-bottom: 5px;" width="120px">Tgl Pembayaran</th>
                            <td style="text-align: center;padding-top:5px;padding-bottom: 5px">&nbsp;:</td>
                            <td style="padding-top:5px;padding-bottom: 5px;">
                                <input type="date" name="dari_tgl" id="dari_tgl" value="<?= (isset($tgl_pertama)) ? $tgl_pertama : '
								' ?>" class="form-control input-sm mykonten">
                            </td>
                            <td align="center" style="padding-right:5px;padding-left: 5px">s.d</td>
                            <td style="padding-top:5px;padding-bottom: 5px;">
                                <input type="date" name="sampai_tgl" id="sampai_tgl" value="<?= (isset($tgl_terakhir)) ? $tgl_terakhir : '
								' ?>" class="form-control input-sm mykonten">
                            </td>

                        </tr>
                    </table>


                    <table width="40%" border="0" class="mykonten">
                        <tr>
                            <th style="padding-top:5px;padding-bottom: 5px;" width="120px">Job. No</th>
                            <td style="text-align: center;padding-top:5px;padding-bottom: 5px">:</td>
                            <td style="padding-top:5px;padding-bottom: 5px;">
                                <select name="JobNo" id="JobNo" class="form-control input-sm select2" required>
                                    <option value="0">Semua Job</option>
                                    <?php
                                    foreach (dapat_list_job() as $data_job) :
                                    ?>
                                        <option value="<?= $data_job['JobNo'] ?>" <?= ($JobNo == $data_job['JobNo']) ? 'selected' : ''  ?>><?= $data_job['JobNo'] . ' - ' . $data_job['JobNm'] ?></option>

                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                    </table>


                    <table width="40%" border="0" class="mykonten">
                        <tr>
                            <th style="padding-top:5px;padding-bottom: 5px;" width="120px"></th>
                            <td style="text-align: center;padding-top:5px;padding-bottom: 5px"></td>
                            <td style="padding-top:5px;padding-bottom: 5px;padding-left:5px;">
                                <button type="submit" id="tampil_rkp" class="btn btn-sm btn-success">Tampilkan</button>
                            </td>

                        </tr>
                    </table>

                </div>


            </div>

            <div class="overlay loading_rkp" style="display: none;">
                <i class="fa fa-refresh fa-spin"></i>
            </div>

        </div>

        <div id="tampil_table"></div>

    </section>


</div>
<script type="text/javascript">
    $(function() {


        $('.select2').select2({
            'width': '100%',
            'theme': 'classic',
        });


        $('#tampil_rkp').click(function() {

            var JobNo = $('#JobNo').val();

            var dari_tgl = ($('#dari_tgl').val()) ? $('#dari_tgl').val() : '';
            var sampai_tgl = ($('#sampai_tgl').val()) ? $('#sampai_tgl').val() : '';

            if (JobNo == '') {
                $.toast({
                    text: 'Pilih Job terlebih dahulu',
                    position: 'bottom-right',
                    stack: false,
                    hideAfter: 1000,
                    icon: 'info',

                });
                return false;
            }

            if (dari_tgl == '' || sampai_tgl == '') {
                $.toast({
                    text: 'pilih Tanggal terlebih dahulu',
                    position: 'bottom-right',
                    stack: false,
                    hideAfter: 1000,
                    icon: 'info',

                });
                return false;
            }


            $.ajax({
                    url: '<?= site_url("Laporan/Rkp_pembayaran/view_table_rkp_pembayaran") ?>',
                    type: 'post',
                    dataType: 'html',
                    data: {
                        'JobNo': JobNo,
                        'dari_tgl': dari_tgl,
                        'sampai_tgl': sampai_tgl,
                    },
                    beforeSend: function() {
                        $('.loading_rkp').show();
                        $('#tampil_table').html('');
                    }
                })
                .done(function(data) {
                    $('.loading_rkp').hide();
                    // alert(data);

                    $('#tampil_table').html(data);
                })
                .fail(function() {
                    $('.loading_rkp').hide();
                    $.toast({
                        text: 'terjadi kesalahan',
                        position: 'bottom-right',
                        stack: false,
                        hideAfter: 1000,
                        icon: 'error',

                    });
                    return false;
                });

        })




    });
</script>