<div class="row">



    <div class="col-md-12">

        <div class="box box-primary">
            <div class="box-header ">
                <h3 class="box-title"><a href="<?= site_url('ApprovePd/prn/' . $JobNo . '/' . $alokasi . '/' . $cari) ?>" class="btn btn-danger btn-sm" target="_blank"><i class="fa fa-print"></i> PDF</a></h3>
                <div class="pull-right box-tools">
                    <button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped table-condensed wrap" id="mytable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>No.PD</th>
                                <th>Tipe Form</th>
                                <th>Deskripsi</th>
                                <th>Total PD (Rp)</th>
                                <!-- <th>No.PJ</th>
                                <th>Tgl PJ</th>
                                <th>Total PJ (Rp)</th> -->
                                <th>PD Approved By (Keuangan)</th>
                                <th>PD Approve On</th>
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

<script type="text/javascript">
    var JobNo = '<?= $JobNo ?>';
    var alokasi = '<?= $alokasi ?>';
    var cari = '<?= $cari ?>';
    $(document).ready(function() {
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
            "responsive": true,
            // "lengthChange": false,
            "autoWidth": false,
            scrollX: true,
            // scrollY: 200,
            scrollCollapse: true,
            scroller: true,
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
                "url": "<?= site_url('ApprovePd/get_json_pd/') ?>" + JobNo + '/' + alokasi + '/' + cari,
                "type": "POST",
            },
            columns: [{
                    "data": "NoPD",
                    "orderable": false,
                    "searchable": false,
                },

                {
                    "data": "NoPD",
                    "orderable": false,
                    "searchable": true,
                },

                {
                    "data": "TipeForm",
                    "orderable": false,
                    "searchable": true,
                },

                {
                    "data": "Deskripsi",
                    "orderable": false,
                    "searchable": true,
                },

                {
                    "data": "TotalPD",
                    "orderable": false
                },

                // {
                //     "data": "NoPJ",
                //     "orderable": false
                // },

                // {
                //     "data": "TglPj",
                //     "orderable": false,
                //     "searchable": true,
                // },

                // {
                //     "data": "TotalPJ",
                //     "orderable": false
                // },

                {
                    "data": "ApprovedByAK",
                    "orderable": false
                },

                {
                    "data": "TimeApprovedAK",
                    "orderable": false
                },

                {
                    "data": "view",
                    'className': 'text-center',
                    "orderable": false,
                    "searchable": false,
                    "render": function(data, type, row) {
                        return data + ' ' + row.view_detail;
                        // if (row.PjApprovedByAK != '' && row.PjTimeApprovedAK != '' ) {
                        // 	return '<button onclick="ApproveUnapprove('+row.NoPD+')" class="btn btn-sm btn-secondary text-bold">UNAPPROVE</button>';
                        // } else {
                        // 	return '<button onclick="ApproveUnapprove('+data+')" class="btn btn-sm btn-primary text-bold">APPROVE</button>';
                        // }
                    }
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
    })

    // function ApproveUnapprove(NoPD){
    //        alert('ok');
    //        return false;
    //    }
</script>
