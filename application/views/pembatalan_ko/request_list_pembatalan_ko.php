
<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover" id="mytable" data-job="<?= $JobNo ?>">
        <thead>
            <tr>
                <th class="text-center">No.</th>
                <th class="text-center">#</th>
                <th>No. KO</th>
                <th>Tgl KO</th>
                <th>Nama Vendor</th>
                <th>Tipe KO</th>
                <th class="text-right">Total KO (Rp)</th>
                <th class="text-right">Total Terbayar (Rp)</th>
                <th>Alasan Batal</th>
                <th>Canceled By</th>
                <th>Canceled On</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script type="text/javascript">
    $(function() {
        var JobNo = $('#mytable').attr('data-job');

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

        var table = $("#mytable").dataTable({
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
                "url": "<?= site_url('Pembatalan_ko/dapat_list_pembatalan_ko') ?>",
                "type": "POST",
                "data": {
                    'JobNo': JobNo
                }

            },
            columns: [{
                "data": "LedgerNo",
                "orderable": false,
                "searchable": false,
            },

            {
                "data": "aksi_baru",
                "orderable": false,
                "searchable": false,
                "className" : "text-center",
                "render" : function(data,type,row){
                    if (data !== '') {
                        return row.batal_cancel+' '+row.detail_baru;
                    }else{
                        return row.lakukan_cancel+' '+row.detail_baru;
                    }
                    // return data;
                }

            },



            {
                "data": "NoKO",
                "orderable": false,
                "searchable": true,
            },



            {
                "data": "TglKO",
                "orderable": false,
                "searchable": true,
            },

            {
                "data": "VendorNm",
                "orderable": false,
                "searchable": true,
            },

            {
                "data": "KategoriId",
                "orderable": false,
                "searchable": false,
            },

            {
                "data": "SubTotal",
                "orderable": false,
                "searchable": false,
                "className" : "text-right",
            },

            {
                "data": "TotalTerbayar",
                "orderable": false,
                "searchable": false,
                "className" : "text-right",
            },

            {
                "data": "CancelReason",
                "orderable": false,
                "searchable": false,
            },

            {
                "data": "CanceledBy",
                "orderable": false,
                "searchable": false,
            },

            {
                "data": "TimeCancel",
                "orderable": false,
                "searchable": false,
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
    })
</script>