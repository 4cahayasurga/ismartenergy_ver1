<style type="text/css">
    #mytable {
        white-space: nowrap;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 10px !important;
    }

    #mytable th {
        padding-top: 10px !important;
        padding-bottom: 10px !important;
        background-color: #2ecc71;
    }

    #mytable td {
        padding-top: 10px !important;
        padding-bottom: 10px !important;
    }
</style>

<div class="box">

    <div class="box-header">
        <!--         <a href="<?= site_url('Laporan/Report_outstanding_approvedpd/rpt_outstanding_pdf/' . $By_pilihan . '/' . $Type_pilihan . '/' . $dari_tgl . '/' . $sampai_tgl) ?>" target="_blank" class="btn btn-sm btn-danger btn_print">Print <i class="fa fa-print"></i></a>
        <a href="<?= site_url('Laporan/Report_outstanding_approvedpd/export_excel_rpt_outstanding/' . $By_pilihan . '/' . $Type_pilihan . '/' . $dari_tgl . '/' . $sampai_tgl) ?>" target="_blank" class="btn btn-sm btn-success">Export Excel <i class="fa fa-download"></i></a> -->


        <a class="btn btn-sm btn-danger btn_print">Print <i class="fa fa-print"></i></a>
        <a class="btn btn-sm btn-success btn_export_excel">Export Excel <i class="fa fa-download"></i></a>
    </div>

    <div class="box-body">


        <div class="table-responsive">

            <table class="table table-borderless table-striped table-hover table-condensed" id="mytable" data-By_pilihan="<?= $By_pilihan ?>" data-Type_pilihan="<?= $Type_pilihan ?>" data-dari_tgl="<?= $dari_tgl ?>" data-sampai_tgl="<?= $sampai_tgl ?>">
                <thead>
                    <tr>
                        <th border="1" style="background-color:transparent;" colspan="7"><small class="terbilang"></small></th>
                        <th style="background-color:transparent;text-align: right;" colspan="6">
                            <small class="sub_total" style="font-size:12px;font-weight: bold;"></small>
                        </th>
                    </tr>
                    <tr>
                        <th style="background-color:transparent;" class="text-left" width="50px">
                            <!-- <input type="checkbox" name="pilih[]" id="pilih"> -->
                            <input type="checkbox" id="master">
                        </th>
                        <th width="">Job No</th>
                        <th>Job Name</th>
                        <th>No PD</th>
                        <th>Alokasi</th>
                        <th>Tipe Form</th>
                        <th>Atas Nama</th>
                        <th>Rekening Penerima</th>
                        <th>No KO</th>
                        <th>Total PD</th>
                        <th>Tgl PD</th>
                        <th>Jth Tempo</th>
                        <th>Deskripsi</th>
                        <th>Approved By</th>

                    </tr>
                </thead>
                <tbody></tbody>


            </table>

        </div>

    </div>

</div>


<script>
    function test_data() {
        $.ajax({
                url: '<?= site_url('Laporan/Report_outstanding_approvedpd/test_data') ?>',
                type: 'post',
                dataType: 'json',
            })
            .done(function(data) {
                $('.terbilang').html('<i>' + data.terbilang + '</i>');
                $('.sub_total').html('Sub Total : ' + ubah_uang_js(data.angka));
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });

    }

    $(function() {

        $('#master').on('click', function(e) {

            if ($(this).is(':checked', true))

            {
                $(".sub_chk").prop('checked', true);
            } else {
                $(".sub_chk").prop('checked', false);
            }

        });


        $('.btn_print').on('click', function(e) {
            var By_pilihan = $('#mytable').attr('data-By_pilihan');
            var Type_pilihan = $('#mytable').attr('data-Type_pilihan');
            var dari_tgl = $('#mytable').attr('data-dari_tgl');
            var sampai_tgl = $('#mytable').attr('data-sampai_tgl');

            var allVals = [];
            $(".sub_chk:checked").each(function() {

                allVals.push($(this).attr('data-id'));

            });

            if (allVals.length <= 0) {
                Swal.fire('Pilih data terlebih dahulu');
            } else {
                Swal.fire({
                    title: 'Anda Yakin?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Lakukan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.value) {

                        var join_selected_values = allVals.join("-");
                        var url = "<?= site_url('Laporan/Report_outstanding_approvedpd/rpt_outstanding_pdf/') ?>" + By_pilihan + '/' + Type_pilihan + '/' + dari_tgl + '/' + sampai_tgl + '/' + join_selected_values;
                        window.open(url, '_blank');
                        return false;

                    }
                })

                return false;

            }


        });

        $('.btn_export_excel').on('click', function(e) {
            var By_pilihan = $('#mytable').attr('data-By_pilihan');
            var Type_pilihan = $('#mytable').attr('data-Type_pilihan');
            var dari_tgl = $('#mytable').attr('data-dari_tgl');
            var sampai_tgl = $('#mytable').attr('data-sampai_tgl');

            var allVals = [];
            $(".sub_chk:checked").each(function() {
                allVals.push($(this).attr('data-id'));
            });

            if (allVals.length <= 0) {
                Swal.fire('Pilih data terlebih dahulu');
            } else {
                Swal.fire({
                    title: 'Anda Yakin?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Lakukan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.value) {

                        var join_selected_values = allVals.join("-");
                        var url = "<?= site_url('Laporan/Report_outstanding_approvedpd/export_excel_rpt_outstanding/') ?>" + By_pilihan + '/' + Type_pilihan + '/' + dari_tgl + '/' + sampai_tgl + '/' + join_selected_values;
                        window.open(url, '_blank');
                        return false;

                    }
                })

                return false;

            }


        });

    })



    $(function() {

        var By_pilihan = $('#mytable').attr('data-By_pilihan');
        var Type_pilihan = $('#mytable').attr('data-Type_pilihan');
        var dari_tgl = $('#mytable').attr('data-dari_tgl');
        var sampai_tgl = $('#mytable').attr('data-sampai_tgl');


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

        $("#mytable").dataTable({
            // "responsive": true,
            // "lengthChange": true,
            // "autoWidth": true,
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
                'url': '<?= site_url("Laporan/Report_outstanding_approvedpd/dapat_list_outstanding") ?>',
                "type": "POST",
                "data": {
                    'By_pilihan': By_pilihan,
                    'Type_pilihan': Type_pilihan,
                    'dari_tgl': dari_tgl,
                    'sampai_tgl': sampai_tgl
                },

            },
            columns: [

                {
                    "data": "LedgerNo",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-left",
                    "render": function(data, type, row) {
                        // return '<input type="checkbox" name="pilih[]" id="pilih" value="'+data+'">';
                        // return data;
                        return '<input type="checkbox" class="sub_chk" data-id="' + data + '">';
                    }

                },


                {
                    "data": "JobNo",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-left",

                },

                {
                    "data": "JobNm",
                    "orderable": false,
                    "searchable": true,
                    "className": "text-left",

                },

                {
                    "data": "NoPD",
                    "orderable": false,
                    "searchable": true,
                    "className": "text-left",

                },

                {
                    "data": "Alokasi",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-left",

                },

                {
                    "data": "TipeForm",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-left",

                },

                {
                    "data": "AtasNama",
                    "orderable": false,
                    "searchable": true,
                    "className": "text-left",

                },

                {
                    "data": "NoRek",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-left",

                },

                {
                    "data": "NoKO",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-left",

                },

                {
                    "data": "TotalPD",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-left",

                },

                {
                    "data": "TglPD",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-left",

                },

                {
                    "data": "DueDate",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-left",

                },

                {
                    "data": "Deskripsi",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-left",

                },

                {
                    "data": "ApprovedBy",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-left",

                },





            ],

            // order: [
            //     [0, 'desc']
            // ],
            // rowCallback: function(row, data, iDisplayIndex) {
            //     var info = this.fnPagingInfo();
            //     var page = info.iPage;
            //     var length = info.iLength;
            //     var index = page * length + (iDisplayIndex + 1);
            //     $('td:eq(0)', row).html(index);
            // }
        });
    })
</script>