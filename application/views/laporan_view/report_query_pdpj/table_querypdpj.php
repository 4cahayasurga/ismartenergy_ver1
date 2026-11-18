<style>
    /* #tbl_saya {
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        width: 100%;
    }

    #tbl_saya th {
        font-size: 12px;

    }

    #tbl_saya td {
        font-size: 12px;
    } */

    #tbl_saya {
        white-space: nowrap;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 10px !important;
    }

    #tbl_saya th {
        padding-top: 10px !important;
        padding-bottom: 10px !important;
        /* background-color: #3498db; */
        /* color: white; */
    }

    #tbl_saya td {
        padding-top: 10px !important;
        padding-bottom: 10px !important;
    }
</style>
<div class="box">
    <div class="box-header">

        <div class="box-tools pull-right">
            <button class="btn btn-sm btn-warning text-right text-black" style="font-weight: bold;">Grand Total PD : <?= number_format($this->session->flashdata('GrandTotal_PD')) ?></button>
            <button class="btn btn-sm btn-success text-right text-black" style="font-weight: bold;">Grand Total Pembayaran : <?= number_format($this->session->flashdata('GrandTotal_Pembayaran')) ?></button>
            <button class="btn btn-sm btn-primary text-right text-black" style="font-weight: bold;">Grand Total PJ : <?= number_format($this->session->flashdata('GrandTotal_PJ')) ?></button>
        </div>
        <!-- <a href="<?= site_url('Laporan/Report_query_pd_pj/rpt_query_pdpj_pdf/') . $JobNo . '/' . $Alokasi ?>" target="_blank" class="btn btn-sm btn-danger btn_print">Print <i class="fa fa-print"></i></a> -->
        <a class="btn btn-sm btn-danger btn_print">Print <i class="fa fa-print"></i></a>

        <a class="btn btn-sm btn-success btn_export_excel">Export Excel <i class="fa fa-download"></i></a>


    </div>
    <div class="box-body">

        <div class="table-responsive">

            <table id="tbl_saya" class="table table-bordered table-striped table-hover table-condensed" data-JobNo="<?= $JobNo ?>" data-Alokasi="<?= $Alokasi ?>">
                <thead>



                    <tr>
                        <th style="background-color:transparent;" class="text-left" width="50px">
                            <input type="checkbox" id="master">
                        </th>
                        <th class="text-center">No.</th>

                        <th>Job No</th>
                        <th>No PD</th>
                        <th>Tgl PD</th>
                        <th>No Ref.Lapangan</th>
                        <th>Alokasi</th>
                        <th>Tipe Form</th>
                        <th>Deskripsi</th>
                        <th>No. KO</th>
                        <th>Total KO (Rp)</th>
                        <th>No. Tagihan</th>
                        <th>Rekening Penerima</th>
                        <th>Atas Nama</th>
                        <th>Total PD (Rp)</th>

                        <th>Tgl Bayar</th>
                        <th>Via</th>
                        <th>Total Pembayaran</th>
                        <th>Rekening Pengirim</th>

                        <th>No PJ</th>
                        <th>Tgl PJ</th>
                        <th>Total PJ</th>
                        <th>Saldo (Rp)</th>

                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <th style="text-align:right;border:0px solid black;" colspan="15"></th>
                        <th style="text-align:right;border:0px solid black;" colspan="3"></th>
                        <th style="text-align:right;border:0px solid black;" colspan="4"></th>
                        <th style="text-align:right;border:0px solid black;"></th>
                    </tr>
                </tfoot>

            </table>

        </div>

    </div>

</div>
<script>
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
            var JobNo = $('#tbl_saya').attr('data-JobNo');
            var Alokasi = $('#tbl_saya').attr('data-Alokasi');

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

                        var url = "<?= site_url('Laporan/Report_query_pd_pj/print_rpt_querypdpj/') ?>" + JobNo + '/' + Alokasi + '/' + join_selected_values;
                        window.open(url, '_blank');
                        return false;

                    }
                })

                return false;

            }


        });

        $('.btn_export_excel').on('click', function(e) {
            var JobNo = $('#tbl_saya').attr('data-JobNo');
            var Alokasi = $('#tbl_saya').attr('data-Alokasi');

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
                        var url = "<?= site_url('Laporan/Report_query_pd_pj/exp_excel_rpt_querypdpj/') ?>" + JobNo + '/' + Alokasi + '/' + join_selected_values;
                        window.open(url, '_blank');
                        return false;

                    }
                })

                return false;

            }


        });

    })


    $(function() {

        var JobNo = $('#tbl_saya').attr('data-JobNo');
        var Alokasi = $('#tbl_saya').attr('data-Alokasi');

        // alert('ok');

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

        $("#tbl_saya").dataTable({
            // "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            initComplete: function() {
                var api = this.api();
                $('#tbl_saya_filter input')
                    .off('.DT')
                    .on('keyup.DT', function(e) {
                        if (e.keyCode == 13) {
                            api.search(this.value).draw();
                        }
                    });
            },
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api();

                var intVal = function(i) {
                    return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                };



                SubTotal_TotalPD = api
                    .column(14, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                SubTotal_TotalPembayaran = api
                    .column(17, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                SubTotal_TotalPJ = api
                    .column(21, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                $(api.column(14).footer()).html('Total PD (Rp) = ' + ubah_uang_js(SubTotal_TotalPD));
                $(api.column(17).footer()).html('Total Pembayaran (Rp) = ' + ubah_uang_js(SubTotal_TotalPembayaran));
                $(api.column(21).footer()).html('Total PJ (Rp) = ' + ubah_uang_js(SubTotal_TotalPJ));
            },
            processing: true,
            serverSide: true,
            ajax: {
                'url': '<?= site_url("Laporan/Report_query_pd_pj/get_data_query_pdpj") ?>',
                "type": "POST",
                "data": {
                    'JobNo': JobNo,
                    'Alokasi': Alokasi
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
                    "data": "LedgerNo",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-left",

                },

                {
                    "data": "JobNo",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-left",

                },

                {
                    "data": "NoPD",
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
                    "data": "NoRef",
                    "orderable": false,
                    "searchable": false,
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
                    "data": "Deskripsi",
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
                    "data": "",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-left",
                    "render": function(data, type, row) {
                        return 'Total belum di atur';
                    }
                },


                {
                    "data": "NoTagihan",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-left",

                },

                {
                    "data": "NoRek",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-left",

                },

                {
                    "data": "AtasNama",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-left",

                },

                {
                    "data": "TotalPD",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-left",
                    "render": function(data, type, row) {
                        return ubah_uang_js(data);
                    }

                },

                {
                    "data": "TglBayar",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-left",

                },

                {
                    "data": "JenisTrf",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-left",

                },

                {
                    "data": "BLEAmount",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-left",
                    "render": function(data, type, row) {
                        return ubah_uang_js(data);
                    }

                },

                {
                    "data": "RekId",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-left",

                },

                {
                    "data": "NoPJ",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-left",

                },

                {
                    "data": "TglPJ",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-left",

                },



                {
                    "data": "TotalPJ",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-left",
                    "render": function(data, type, row) {
                        return ubah_uang_js(data);
                    }

                },

                {
                    "data": "",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-left",
                    "render": function(data, type, row) {
                        return 'Saldo belum di atur';
                    }
                },




            ],

            order: [
                [1, 'desc']
            ],
            rowCallback: function(row, data, iDisplayIndex) {
                var info = this.fnPagingInfo();
                var page = info.iPage;
                var length = info.iLength;
                var index = page * length + (iDisplayIndex + 1);
                $('td:eq(1)', row).html(index);
            },

        });


    })
</script>