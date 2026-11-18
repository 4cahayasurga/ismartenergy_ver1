<div class="row">
    <div class="col-sm-12">

        <div class="table-responsive">
            <table class="table table-bordered table-condensed table-hover table-striped" id="mytable_lapangan" data-JobNo="<?= $mydata['JobNo'] ?>" data-Alokasi="<?= $mydata['Alokasi'] ?>" data-Pencarian="<?= $mydata['Pencarian'] ?>" data-dari_tgl="<?= $mydata['dari_tgl'] ?>" data-sampai_tgl="<?= $mydata['sampai_tgl'] ?>">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th style="width: 200px;" class="text-center">#</th>
                        <th style="width: 50px;">No PD</th>
                        <th style="width: 50px;">No.Ref Lapangan</th>
                        <th style="width: 50px;">Tgl PD</th>
                        <th style="width: 50px;">Alokasi</th>
                        <th style="width: 50px;">Type Form</th>
                        <th style="width: 50px;">No KO</th>
                        <th>Deskripsi</th>
                        <th>Total PD(Rp)</th>
                        <th>Approved By<p> (Lapangan)</th>
                        <th>Approve On</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

    </div>
</div>

<div class="modal fade bs-example-modal-lg" id="modal_pd">
    <div class="modal-dialog" id="modal_pd_dialog" style="width: 90% !important;">
        <div class="modal-content" id="modal_pd_content">
            <div class="modal-header" id="modal_pd_header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_pd_title"></h4>
            </div>
            <div class="modal-body" id="modal_pd_body">

                <!-- <embed src="" id="disini" frameborder="0" style="width: 100%;"> -->
            </div>

        </div>
    </div>
</div>

<script>
    function tampil_pdf_pd(LedgerNo) {
        $('#modal_pd_title').text('');

        $('#modal_pd').modal('show');

        $.ajax({
            type: "POST",
            url: "<?= site_url('PD_lap/print_pd_lap_modal') ?>",
            data: {
                'LedgerNo': LedgerNo
            },
            dataType: "HTML",
            beforeSend: function() {
                $("#loadingku").show();
            },
            success: function(response) {
                $("#loadingku").hide();


                var luaskan = '<a ="<?= site_url('PD_lap/print_pd_lap/') ?>" class="btn btn-sm btn-success" >Luaskan</a><br>';

                var object = "<object data=\"{FileName}\" type=\"application/pdf\" width=\"100%\" height=\"800px\">";
                object += "If you are unable to view file, you can download from <a href=\"{FileName}\">here</a>";
                object += " or download <a target = \"_blank\" href = \"http://get.adobe.com/reader/\">Adobe PDF Reader</a> to view the file.";
                object += "</object>";
                object = object.replace(/{FileName}/g, '<?= site_url('PD_lap/print_pd_lap/') ?>' + response);


                $("#modal_pd_body").html(luaskan + object);


                // $('#modal_pd_body').html('');

                // $('#disini').html(response);


                // $('#disini').attr('src', '');
                // $('#disini').attr('src', '<?= site_url('PD_lap/print_pd_lap/') ?>' + response);



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

        // alert(LedgerNo);
    }

    function aksi_print_pd(NoPD = '', LedgerNo = '') {

        // $('.loadingku').show();
        // return false;

        var JobNo = $('#mytable_lapangan').attr('data-JobNo');
        var Alokasi = $('#mytable_lapangan').attr('data-Alokasi');

        $.ajax({
            type: "POST",
            url: "<?= site_url('PD_lap/print_pd_lap') ?>",
            data: {
                'NoPD': NoPD,
                'JobNo': JobNo,
                'Alokasi': Alokasi,
                'LedgerNo': LedgerNo,
            },
            dataType: "HTML",
            beforeSend: function() {
                $('.loadingku').show();
            },
            success: function(response) {
                $('.loadingku').hide();
                // $('.tampil_table').html(response);
                // alert(response);
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

    function json_table_pd_lapangan() {

        var JobNo = $('#mytable_lapangan').attr('data-JobNo');
        var Alokasi = $('#mytable_lapangan').attr('data-Alokasi');
        var Pencarian = $('#mytable_lapangan').attr('data-Pencarian');
        var dari_tgl = $('#mytable_lapangan').attr('data-dari_tgl');
        var sampai_tgl = $('#mytable_lapangan').attr('data-sampai_tgl');

        var cek_approve = '';

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


        $("#mytable_lapangan").dataTable({
            initComplete: function() {
                var api = this.api();
                $('#mytable_lapangan_filter input')
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
                "url": "<?= site_url('PD_lap/json_table_pd_lapangan') ?>",
                "type": "POST",
                "data": {
                    "JobNo": JobNo,
                    "Alokasi": Alokasi,
                    "Pencarian": Pencarian,
                    'dari_tgl': dari_tgl,
                    'sampai_tgl': sampai_tgl,
                }
            },
            columns: [


                {
                    "data": "LedgerNo",
                    "orderable": true,
                    "searchable": false,
                    "className": "text-center",

                },

                {
                    "data": "cek_app",
                    "orderable": true,
                    "searchable": false,
                    "className": "text-center",
                    "render": function(data, type, row) {

                        if (data == 1) {
                            cek_approve = '<a href="javascript:void(0)" class="btn btn-xs btn-link" onclick="CekAPP(1,\'' + row.LedgerNo + '\')" ><u>UnApprove</u></a>';
                        } else {
                            cek_approve = '<a href="javascript:void(0)" class="btn btn-xs btn-link" onclick="CekAPP(0,\'' + row.LedgerNo + '\')"><u>Approve</u></a>';
                        }
                        return row.aksi + ' ' + cek_approve;
                    }

                },

                {
                    "data": "NoPD",
                    "orderable": false,
                    "searchable": true,
                    "className": "text-left",

                },

                {
                    "data": "NoRef",
                    "orderable": false,
                    "searchable": true,
                    "className": "text-left",

                },

                {
                    "data": "TglPD",
                    "orderable": false,
                    "searchable": true,
                    "className": "text-left",

                },

                {
                    "data": "Alokasi",
                    "orderable": false,
                    "searchable": true,
                    "className": "text-left",

                },

                {
                    "data": "TipeForm",
                    "orderable": false,
                    "searchable": true,
                    "className": "text-left",

                },


                {
                    "data": "NoKO",
                    "orderable": false,
                    "searchable": true,
                    "className": "text-left",

                },

                {
                    "data": "Deskripsi",
                    "orderable": false,
                    "searchable": true,
                    "className": "text-left",

                },

                {
                    "data": "TotalPD",
                    "orderable": false,
                    "searchable": true,
                    "className": "text-right",

                },

                {
                    "data": "ApprovedByAK",
                    "orderable": false,
                    "searchable": true,
                    "className": "text-left",

                },

                {
                    "data": "TimeApprovedAK",
                    "orderable": false,
                    "searchable": true,
                    "className": "text-left",

                },




            ],
            order: [
                [0, 'desc']
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

    function CekAPP(value, LedgerNo) {

        app = (value == 0) ? 'Approve Data Ini' : 'UnApprove data Ini';

        nilai = (value == 0) ? 'belumapprove' : 'sudahapprove';
        var NoPD = $('#NoPD').val();


        Swal({
            title: 'Anda Yakin ingin ' + app + '?',
            // text: "Apakah yakin ingin menghapus data ini?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Lakukan!',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Batal'

        }).then((result) => {
            if (result.value) {

                $.ajax({
                    url: "<?= site_url('PD_lap/lakukan_approve') ?>",
                    type: "POST",
                    dataType: 'html',
                    data: {
                        'status_app': nilai,
                        'LedgerNo': LedgerNo,
                    },
                    beforeSend: function() {
                        $('.loadinguraian').show();
                    },
                    success: function(response) {
                        $('.loadinguraian').hide();

                        // alert(response);
                        // return false;

                        if (response == 'sudah_pembayaran') {
                            $.toast({
                                heading: 'info',
                                text: 'Tidak dapat melakukan UnApprove,Sudah ada pembayaran',
                                icon: 'info',
                                loader: true,
                            })
                            alokasi_ku_berganti();
                            return false;
                        }

                        if (response == 'sudah_app_kk') {
                            $.toast({
                                heading: 'info',
                                text: 'Unapprove KK terlebih dahulu',
                                icon: 'info',
                                loader: true,
                            })
                            alokasi_ku_berganti();
                            return false;

                        }

                        if (response == 'sudah_app_kt') {
                            $.toast({
                                heading: 'info',
                                text: 'Unapprove KK terlebih dahulu',
                                icon: 'info',
                                loader: true,
                            })
                            alokasi_ku_berganti();
                            return false;

                        }

                        if (response == 'berhasil') {
                            $.toast({
                                heading: 'success',
                                text: 'Berhasil',
                                icon: 'success',
                                loader: true,
                            })
                            alokasi_ku_berganti();
                            return false;

                        }

                        if (response == 'gagal') {
                            $.toast({
                                heading: 'error',
                                text: 'Berhasil',
                                icon: 'error',
                                loader: true,
                            })
                            alokasi_ku_berganti();
                            return false;

                        }

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $('.loadinguraian').hide();
                        $.toast({
                            heading: 'Error',
                            text: 'Terjadi Kesalahan',
                            icon: 'error',
                            loader: true,
                        })
                        return false;
                    }
                });

            }
        });


    }

    $(function() {
        json_table_pd_lapangan();



    })
</script>