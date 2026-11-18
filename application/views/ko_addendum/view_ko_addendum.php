<div class="content-wrapper">

    <section class="content-header">
        <h5>
            <label class="col-lg col-form-label"><?php echo $judul ?> </label>
            <small class="text-red"><br><?= $JobNo . ' - ' . $JobNm  ?> </small><br>
            <small><?= $Lokasi ?></small>

        </h5>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?php echo $judul ?></li>
        </ol>
    </section>

    <section class="content-header">
        <button onclick="history.back()" class="btn btn-sm btn-link" style="background-color:#34495e;color:white;"><i class="fa fa-arrow-left"></i> Back</button>

        <button onclick="request_list_ko_addendum()" class="btn btn-sm btn-default">Refresh Table</button>
    </section>

    <section class="content-header">
        <?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>

        <div class="row">

            <div class="table_saya">

            </div>

        </div>

    </section>

</div>

<section>

</section>

<input type="hidden" name="JobNo" id="JobNo" value="<?= $JobNo ?>">


<div class="modal fade" id="modal_addendum" style="font-size: 10px;">
    <div class="modal-dialog modal-lg" id="modal_dialog_addendum">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <small style="font-size:15px;">Addendum Kontrak</small><br>
                <small style="font-size:12px;color:red;"><?= $JobNo . ' - ' . $JobNm  ?></small><br>
                <small style="font-size:12px;color:blue;" id="txt_ko"></small>

            </div>
            <div class="modal-body" id="modal_body_addendum">




            </div>
        </div>

    </div>

</div>

<script>
    $(function() {
        request_list_ko_addendum();
    })

    function request_list_ko_addendum() {

        var JobNo = $('#JobNo').val();
        $.ajax({
            url: '<?= site_url('Ko_addendum/dapat_tampil_table') ?>',
            type: 'post',
            dataType: 'html',
            data: {
                'JobNo': JobNo
            },
            beforeSend: function() {
                $('.loadingsaya').show();
            },
            success: function(data) {
                $('.loadingsaya').hide();
                $('.table_saya').html(data);
            },
            error: function() {
                $('.loadingsaya').hide();
            }
        });

    }

    function frm_addendum(LedgerNo, JobNo, AddendumKe, NoKO) {
        $('#modal_body_addendum').html('');


        $.ajax({
                url: '<?= site_url('Ko_addendum/frm_addendum') ?>',
                type: 'post',
                dataType: 'html',
                data: {
                    'LedgerNo': LedgerNo,
                    'JobNo': JobNo,
                    'AddendumKe': AddendumKe,
                    'NoKO': NoKO

                },
                beforeSend: function() {
                    $('#loadingsaya').show();
                }
            })
            .done(function(data) {
                $('#loadingsaya').hide();
                $('#modal_body_addendum').html(data);
                $('#modal_addendum').modal('show');

            })
            .fail(function() {
                $('#loadingsaya').hide();
                $.toast({
                    text: 'Terjadi Kesalahan',
                    position: 'bottom-right',
                    stack: false,
                    hideAfter: 1000,
                    icon: 'error',

                });
            })
            .always(function() {

            });
    }

    function simpan_addendum() {

        var AddendumKe = $('#AddendumBaru').val();
        var AlasanAddendum = $('#AlasanAddendum').val();
        var JobNo = $('#JobNo').val();
        var NoKO = $('#NoKO').val();
        var LedgerNo = $('#LedgerNo').val();

        $.ajax({
                url: '<?= site_url('Ko_addendum/aksi_simpan_addendum') ?>',
                type: 'post',
                dataType: 'html',
                data: {
                    'AddendumKe': AddendumKe,
                    'AlasanAddendum': AlasanAddendum,
                    'JobNo': JobNo,
                    'NoKO': NoKO,
                    'LedgerNo': LedgerNo
                },
                beforeSend: function() {
                    $('.btn_simpan').attr('disabled', true).text('Loading');
                }
            })
            .done(function(data) {
                $('.btn_simpan').attr('disabled', false).text('Simpan');

                $('#modal_addendum').modal('hide');
                request_list_ko_addendum();

                if (data == 'berhasil') {


                    $.toast({
                        text: 'berhasil menambahkan data',
                        position: 'bottom-right',
                        stack: false,
                        hideAfter: 1000,
                        icon: 'success',

                    });
                    return false;
                }

                if (data == 'gagal') {
                    $.toast({
                        text: 'gagal menambahkan data',
                        position: 'bottom-right',
                        stack: false,
                        hideAfter: 1000,
                        icon: 'error',

                    });
                    return false;
                }
            })
            .fail(function() {
                $('.btn_simpan').attr('disabled', false).text('Simpan');

                $.toast({
                    text: 'Terjadi Kesalahan',
                    position: 'bottom-right',
                    stack: false,
                    hideAfter: 1000,
                    icon: 'error',

                });
                return false;
            })
            .always(function() {

            });

    }
</script>