<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <label class="col-lg col-form-label"><?php echo $judul ?> </label>
            <p></p>
            <label for="inputName" type="input" name="JobNo" class="col-sm col-form-label"><?php echo $spr->JobNo ?></label> - <label for="inputName" name="JobNm" class="col-sm col-form-label" "><?php echo $spr->JobNm ?></label>
            <br><small><label for=" inputName" class="col-sm col-form-label"><?php echo $spr->Lokasi ?></label>

        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?php echo $judul ?></li>
        </ol>
    </section>

    <section class="content-header" style="padding-bottom:20px;">
        <a href="<?= site_url('job/sub_job/' . $spr->JobNo) ?>" class="btn btn-sm btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
        <!-- <a href="#save" data-toggle="modal" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</a> -->
    </section>

    <div class="col-md-12">
        <div class="box box-warning">
            <div class="box-header with-border">
                <div class="box-header bg-blue-gradient">
                    <h3 class="box-title">SPR </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
            </div>

            <div class="box-body">

                <div class="table-responsive">



                    <a href="<?= site_url('Spr/add/' . $spr->JobNo) ?>" class="btn bg-primary btn-flat btn-sm "><i class="fa fa-plus"></i> Tambah Data</a><br><br>
                    <table class="table table-bordered table-striped table-hover table-condensed " id="mytable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>No.SPR</th>
                                <th>Tgl SPR</th>
                                <th>Untuk Pekerjaan</th>
                                <th>SPR Approved By</th>
                                <th>SPR Approved On</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>

</div>

<div class="modal fade" tabindex="-1" role="dialog" id="my-modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="my-modal-title"></h4>
            </div>
            <div class="modal-body" id="my-modal-body">
                <div class="col-sm-12 my-loading" style="display: none;">
                    <span class="text-red">Loading...</span>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    var JobNo = '<?= $spr->JobNo ?>'
    $(document).ready(function() {
        my_table(JobNo)
        // $('#JobNo').select2();
        $('#tambah-spr').click(function() {
            show_form_spr(JobNo);
        })

    })

    function my_table() {



        $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
            return {
                "iStart": oSettings._iDisplayStart,
                "iEnd": oSettings.fnDisplayEnd(),
                "iLength": oSettings._iDisplayLength,
                "iTotal": oSettings.fnRecordsTotal(),
                "iFilteredTotal": oSettings.fnRecordsDisplay(),
                "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
            };
        };
        table = $("#mytable").dataTable({
            initComplete: function() {
                var api = this.api();
                $('#mytable_filter input')
                    .off('.DT')
                    .on('keyup.DT', function(e) {
                        if (e.keyCode == 13) {
                            api.search(this.value).draw();
                        }
                    });
            },
            processing: true,
            serverSide: true,
            ajax: {
                "url": "<?= site_url('Spr/get_spr_table/') ?>" + JobNo,
                "type": "POST",

            },
            columns: [{
                    "data": "NoSPR",
                    "orderable": false
                },
                {
                    "data": "NoSPR",
                    "orderable": false
                },
                {
                    "data": "TglSPR",
                    "orderable": false
                },
                {
                    "data": "UtkPekerjaan",
                    "orderable": false
                },

                {
                    "data": "ApprovedBy",
                    "orderable": false
                },
                {
                    "data": "TimeApproved",
                    "orderable": false
                },
                {
                    "data": "view",
                    "orderable": false,
                    "className": "text-center"
                }

            ],
            order: [
                [1, 'asc']
            ],
            rowCallback: function(row, data, iDisplayIndex) {
                var info = this.fnPagingInfo();
                var page = info.iPage;
                var length = info.iLength;
                var index = page * length + (iDisplayIndex + 1);
                $('td:eq(0)', row).html(index);
            }
        });

    }

    function show_form_spr(JobNo) {
        $('#my-modal-title').text('Entry Permintaan Material / Alat');
        $('#my-modal').modal('show');

        $.ajax({
            type: "POST",
            url: "<?= site_url('Spr/getForm') ?>",
            data: {
                JobNo: JobNo
            },
            dataType: "HTML",
            beforeSend: function() {
                $(".my-loading").show();
            },
            success: function(response) {
                $(".my-loading").hide();
                // alert(response);
                $('#my-modal-body').html(response);
            }
        });
    }
</script>