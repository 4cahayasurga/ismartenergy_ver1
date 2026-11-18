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

    .huruf_12 {
        font-size: 12px;
        font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
    }
</style>

<div class="content-wrapper">

    <section class="content-header">
        <h6 style="font-weight: bold;font-size:16px;"><?= (isset($judul)) ? strtoupper($judul) : '' ?></h6>

        <ol class="breadcrumb">
            <a type="button" class="btn btn-warning btn-sm" href="<?= site_url('Report/MenuRptPDPJ') ?>"><i class="fa fa-arrow-left"></i> Back</a>
            <!-- <li><a href="<?= site_url('dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"><?= (isset($judul)) ? $judul : '' ?></li> -->
        </ol>
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
                            <th style="padding-top:5px;padding-bottom: 5px;" width="120px">Job. No</th>
                            <td style="text-align: center;padding-top:5px;padding-bottom: 5px">:</td>
                            <td style="padding-top:5px;padding-bottom: 5px;">
                                <select name="JobNo" id="JobNo" class="form-control input-sm select2" required>
                                    <option value="">-- pilih salah satu --</option>
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
                            <th style="padding-top:5px;padding-bottom: 5px;" width="120px">Alokasi</th>
                            <td style="text-align: center;padding-top:5px;padding-bottom: 5px">:</td>
                            <td style="padding-top:5px;padding-bottom: 5px;">
                                <select name="Alokasi" id="Alokasi" class="form-control input-sm select2" required>
                                    <option value="">-- pilih salah satu --</option>
                                    <?php
                                    foreach (list_job_alokasi() as $data_alokasi) :
                                    ?>
                                        <option value="<?= $data_alokasi['Alokasi'] ?>"><?= $data_alokasi['Alokasi'] . ' - ' . $data_alokasi['Keterangan'] ?></option>

                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                    </table>

                    <table width="40%" border="0" class="mykonten">
                        <tr>
                            <th style="padding-top:5px;padding-bottom: 5px;" width="120px">Tgl PD</th>
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
                            <th style="padding-top:5px;padding-bottom: 5px;" width="120px"></th>
                            <td style="text-align: center;padding-top:5px;padding-bottom: 5px"></td>
                            <td style="padding-top:5px;padding-bottom: 5px;padding-left:7px;">
                                <input type="checkbox" value="1" id="prn_detail">
                                <label for="prn_detail">Print include detail PD</label>

                            </td>

                        </tr>
                    </table>

                    <table width="40%" border="0" class="mykonten">
                        <tr>
                            <th style="padding-top:5px;padding-bottom: 5px;" width="120px"></th>
                            <td style="text-align: center;padding-top:5px;padding-bottom: 5px"></td>
                            <td style="padding-top:5px;padding-bottom: 5px;padding-left:5px;">
                                <button type="submit" id="tampil_penyerapan_rap" class="btn btn-sm btn-success">Tampilkan</button>
                                <button id="btn_loading" class="btn btn-sm btn-danger" disabled="disabled" style="display:none;">Loading... <i class="fa fa-spinner"></i></button>
                            </td>

                        </tr>
                    </table>

                </div>


            </div>



        </div>


        <div id="tampil_table"></div>



    </section>

</div>
<script>
    $(function() {

        $('.select2').select2({
            'width': '100%',
            'theme': 'classic',
        });

        $('#tampil_penyerapan_rap').click(function() {

            var JobNo = $('#JobNo').val();
            var Alokasi = $('#Alokasi').val();
            var dari_tgl = ($('#dari_tgl').val()) ? $('#dari_tgl').val() : '';
            var sampai_tgl = ($('#sampai_tgl').val()) ? $('#sampai_tgl').val() : '';
            var checked = $('#prn_detail').is(':checked')

            if (JobNo == '' || Alokasi == '') {
                $.toast({
                    text: 'Pilih Job & Alokasi terlebih dahulu',
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

            jQuery.ajax({

                url: '<?= site_url("Laporan/RptPenyerapanRAP/view_penyerapan_rap") ?>',
                type: 'post',
                dataType: 'html',
                data: {
                    'JobNo': JobNo,
                    'Alokasi': Alokasi,
                    'dari_tgl': dari_tgl,
                    'sampai_tgl': sampai_tgl,
                    'isChecked': checked,
                },
                beforeSend: function() {
                    $('#tampil_penyerapan_rap').hide();
                    $('#btn_loading').show();
                    $('#tampil_table').html('');
                },
                complete: function(xhr, textStatus) {
                    $('#tampil_penyerapan_rap').show();
                    $('#btn_loading').hide();
                },
                success: function(data, textStatus, xhr) {
                    // alert(data);
                    $('#tampil_table').html(data);
                },
                error: function(xhr, textStatus, errorThrown) {
                    $.toast({
                        text: 'terjadi kesalahan',
                        position: 'bottom-right',
                        stack: false,
                        hideAfter: 2000,
                        icon: 'error',

                    });
                    return false;
                }
            });

        });


    });
</script>