<style type="text/css">
    .ubah_ukuran {
        font-size: 10px !important;
        font-family: Arial, Helvetica, sans-serif;

    }

    table {
        white-space: nowrap;
    }
</style>

<div class="box">

    <div class="box-header">
        <a href="<?= site_url('Laporan/Report_query_pd_pj/pdf_querypdpj/' . $JobNo . '/' . $Alokasi) ?>" target="_blank" class="btn btn-sm btn-danger btn_print">Print <i class="fa fa-print"></i></a>

    </div>

    <div class="box-body">


        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover table-condesed" id="mytable" data-job="<?= $JobNo ?>" data-alokasi="<?= $Alokasi ?>">
                <thead style="font-size:10px !important;font-family: Arial, Helvetica, sans-serif;">
                    <tr>
                        <!-- <th align="center">No</th> -->
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

            </table>

        </div>

    </div>

</div>

<script>
    $(function() {

        var JobNo = $('#mytable').attr('data-job');
        var Alokasi = $('#mytable').attr('data-alokasi');

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
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            // scrollX: true,
            // scrollY: 1000,
            // scrollCollapse: true,
            // scroller: true,
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
                "url": "<?= site_url('Laporan/Report_query_pd_pj/dapat_list_pdpj_json') ?>",
                "type": "POST",
                "data": {
                    'JobNo': JobNo,
                    'Alokasi': Alokasi,

                }
            },
            columns: [


                {
                    "data": "NoPD",
                    "orderable": true,
                    "searchable": false,
                    "className": "text-center",

                },




                {
                    "data": "JobGabung",
                    "orderable": false,
                    "searchable": true,
                    "className": "text-left",

                },

                {
                    "data": "NoPD",
                    "orderable": true,
                    "searchable": true,
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
                    "data": "Deskripsi",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-left",

                },

                {
                    "data": "NoKO",
                    "orderable": false,
                    "searchable": true,
                    "className": "text-left",

                },

                {
                    "data": "TotalKO",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-left",

                },

                {
                    "data": "NoTagihan",
                    "orderable": false,
                    "searchable": true,
                    "className": "text-left",

                },

                {
                    "data": "NoRek",
                    "orderable": false,
                    "searchable": true,
                    "className": "text-left",

                },

                {
                    "data": "Nama",
                    "orderable": false,
                    "searchable": true,
                    "className": "text-left",

                },

                {
                    "data": "TotalPD",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-left",

                },

                {
                    "data": "TglBayar",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-left",

                },

                {
                    "data": "JenisTrf",
                    "orderable": true,
                    "searchable": true,
                    "className": "text-left",

                },

                {
                    "data": "BLEAmount",
                    "orderable": true,
                    "searchable": true,
                    "className": "text-left",

                },

                {
                    "data": "RekId",
                    "orderable": true,
                    "searchable": true,
                    "className": "text-left",

                },

                {
                    "data": "NoPJ",
                    "orderable": false,
                    "searchable": true,
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

                },

                {
                    "data": "Saldo",
                    "orderable": false,
                    "searchable": false,
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



        // $('#mytable').DataTable({
        //     "responsive": true,
        //     "lengthChange": false,
        //     "autoWidth": false,
        //     // scrollX: true,
        //     scrollY: 1000,
        //     scrollCollapse: true,
        //     scroller: true,
        // });
    })
</script>