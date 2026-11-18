<style>
    .mykonten {
        font-family: Arial, Helvetica, sans-serif !important;
        font-size: x-small !important;
    }

    .ukuran_select2 {
        font-size: 10px !important;
        font-family: Arial, Helvetica, sans-serif;

    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h4 style="font-weight:bold;"><?= $judul ?></h4>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('dashboard')  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?= $judul ?></li>
        </ol>
    </section>
    <section class="content-header">
        <button class="btn btn-xs" style="background-color:#7f8c8d;" onclick="history.back()"><i class="fa fa-arrow-left"></i> Back</button>
    </section>
    <section class="content">
        <?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>
        <div class="box box-primary">
            <div class="box-header with-border">
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
                                <th style="padding-top:5px;padding-bottom:5px;" width="100px">Periode</th>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <input type="text" class="form-control" name="periode" id="datePeriode">
                                </td>
                            </tr>
                        </table>
                        <table border="0" width="100%">
                            <tr>
                                <th style="padding-top:5px;padding-bottom:5px;" width="100px">Job. No</th>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <select class="form-control input-sm select2" name="JobNo" id="JobNo" required>
                                        <option value="">-- Pilih Job --</option>
                                        <?php foreach ($Job as $row) : ?>
                                            <option value="<?= $row['JobNo'] ?>"><?= $row['JobNo'] . ' - ' . $row['JobNm'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <table border="0" width="100%">
                            <tr>
                                <th style="padding-top:5px;padding-bottom:5px;" width="100px"></th>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <button type="submit" name="btn_tampil" id="btn_tampil" class="btn btn-sm bg-purple"><i class="fa fa-search"></i> Tampilkan</button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="overlay loadingku" style="display: none;">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div>
        <div class="tampil_table"></div>
    </section>
</div>

<script>
    var startDate;
    var endDate;
    $(document).ready(function() {
        $('.select2').select2({
            'theme': 'classic',
            'width': '100%',
        });
        $('#btn_tampil').click(function() {
            getTableList(startDate, endDate);
        })
        $('#datePeriode').daterangepicker();

        $('#datePeriode').on('apply.daterangepicker', function(ev, picker) {
            startDate = picker.startDate.format('YYYY-MM-DD');
            endDate = picker.endDate.format('YYYY-MM-DD');
        });
    });

    function getTableList(startDate, endDate) {
        var JobNo = $('#JobNo').val();
        if (JobNo == '') {
            $.toast({
                text: 'pilih job terlebih dahulu',
                position: 'top-center',
                stack: false,
                hideAfter: 2000,
                icon: 'info',
            })
            return false;
        }
        jQuery.ajax({
            url: '<?= site_url('MyReport/Procurement/PembelianMaterial/search') ?>',
            type: 'POST',
            dataType: 'HTML',
            async: true,
            cache: false,
            data: {
                'JobNo': JobNo,
                'StartDate': startDate,
                'EndDate': endDate,
            },
            beforeSend: function() {
                $('.loadingku').show();
                $('.tampil_table').html('');
            },
            complete: function(xhr, textStatus) {
                $('.loadingku').hide();
            },
            success: function(data, textStatus, xhr) {
                $('.tampil_table').html(data);
            },
            error: function(xhr, textStatus, errorThrown) {
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
    }
</script>