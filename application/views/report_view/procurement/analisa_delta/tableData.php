<style type="text/css">
    #table_Ananlisa {
        font-size: 12px;
        font-family: Arial, Helvetica, sans-serif;
    }

    #table_Ananlisa thead {
        background-color: #ccd4db;
        color: #000;
    }

    #table_Ananlisa thead tr th {
        padding-top: 10px;
        padding-bottom: 10px;
        white-space: nowrap;
        border-bottom: 1px solid black;
    }

    #table_Ananlisa tbody tr td {
        padding-top: 10px;
        padding-bottom: 10px;
        text-align: center;
        white-space: nowrap;
        border: 1px solid;
    }

    #table_Ananlisa tbody tr td:nth-child(3),
    tbody tr td:nth-child(4),
    tbody tr td:nth-child(5) {
        background-color: #80ceff;
    }

    #table_Ananlisa thead tr td:nth-child(4),
    td:nth-child(5) {
        background-color: #ced4da;
    }

    #table_Ananlisa tbody tr td:nth-child(6),
    td:nth-child(7) {
        background-color: #80ff9e;
    }

    #table_Ananlisa tbody tr td:nth-child(8),
    td:nth-child(9) {
        background-color: #fff68f;
    }

    #table_Ananlisa tbody tr td:nth-child(10),
    tbody tr td:nth-child(11) {
        background-color: #b197fc;
    }

    #table_Ananlisa tfoot tr th {
        padding-top: 10px;
        padding-bottom: 10px;
        padding-left: 5px;
        border-top: 1px solid black;
        white-space: nowrap;
    }

    #table_Ananlisa tbody tr td.dt-control .bg {
        background-color: #2b82bd;
        padding: 2px;
        border-radius: 2px;
        color: #fff;
    }

    #table_Ananlisa tbody tr.shown td.dt-control .bg {
        background-color: gray;
        padding: 2px;
        border-radius: 2px;
        color: #000;
    }

    tr input {
        background-color: #fff;
        border-radius: 5px;
        outline: none;
        padding: 5px;
        border: none;
        font-size: 12px;
        font-weight: 500;
        min-width: 10px;
    }

    .cont-seacrh {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .cont-seacrh span {
        width: 20px;
        height: 20px;
        border-radius: 10%;
        background-color: #454444;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 5px;
    }

    .cont-seacrh span:hover {
        cursor: pointer;
    }

    .cont-seacrh span i {
        color: #fff;
        font-size: 12px;
    }

    .modal {
        display: none;
        position: absolute;
        border: 1px solid #ccc;
        padding: 10px;
        background-color: #fff;
        z-index: 1002;
    }

    #overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
    }

    .modaltipeform {
        display: none;
        position: absolute;
        top: 50%;
        left: 50%;
        z-index: 99;
    }

    .modaltipeform .content {
        min-width: 200px;
        min-height: 100px;
        max-height: 300px;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 10px 10px 0 0;
        overflow: scroll;
    }

    .modaltipeform .content table {
        width: 100%;
        margin-bottom: 20px;
        min-height: 50px;
        max-height: 100px;
        overflow: scroll;
    }

    .modaltipeform .content table tr:hover {
        background-color: #ebebeb;
    }

    .modaltipeform .footer {
        background-color: #e8eaeb;
        border-radius: 0 0 10px 10px;
        display: flex;
        justify-content: center;
    }

    .modaltipeform .footer #btnFilter {
        background-color: #2b82bd;
        color: #fff;
        width: 100%;
        padding: 5px 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0 0 0 10px;
        cursor: pointer;
    }

    .modaltipeform .footer #btnFilter:hover {
        background-color: #3ea6ed;
    }

    .modaltipeform .footer #closeModalTipeForm {
        background-color: #c2c2c2;
        color: #fff;
        width: 100%;
        padding: 5px 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0 0 10px 0;
        cursor: pointer;
    }

    @media (max-width:750px) {
        .modaltipeform {
            width: 80%;
        }
    }
</style>
<div class="col-12 ">
    <div class="box box-primary">
        <div class="box-header">
            <button class="btn btn-md btn-danger pdf_QHutangKO" data-Alokasi="<?= $Alokasi ?>" data-JobNo="<?= $JobNo ?>">Print PDF <i class="fa fa-print"></i></button>
            <a href="<?= base_url('MyReport/Procurement/AnalisaDelta/exportExcel') ?>" class="btn btn-success">Export Excel <i class="fa fa-xls"></i></a>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table id="table_Ananlisa" class="table table-striped table-hover table-condensed" border="0" width="100%">
                    <thead>
                        <tr>
                            <td colspan="3" class="text-center" style="border:1px solid;"></td>
                            <td colspan="3" class="text-center" style="border:1px solid;font-weight: bold;">RAP Version <span id="headerVersi"></span></td>
                            <td colspan="2" class="text-center" style="border:1px solid;font-weight: bold;">RAP Berjalan</td>
                            <td colspan="2" class="text-center" style="border:1px solid;font-weight: bold;">SPR</td>
                            <td colspan="2" class="text-center" style="border:1px solid;font-weight: bold;">Realisasi PO</td>
                            <td colspan="3" class="text-center" style="border:1px solid;"></td>
                        </tr>
                        <tr>
                            <th style="border:1px solid;">Kode RAP
                                <div class="cont-seacrh">
                                    <input type="text" id="searchKodeRAP" class="searchInput" placeholder="Cari SPR.." style="min-width:80px;width: 100%;">
                                    <span class="filterHead" data-head="KodeRAP">
                                        <i class="fa fa-filter"></i>
                                    </span>
                                </div>
                            </th>
                            <th hidden style="border:1px solid;">No Urut
                            </th>
                            <th style="border:1px solid; position:relative; text-align:center;">Uraian
                                <div class="cont-seacrh">
                                    <input type="text" id="searchUraian" class="searchInput" placeholder="Cari Uraian.." style="min-width: 50px;width: 100%;">
                                    <span class="filterHead" data-head="Uraian">
                                        <i class="fa fa-filter"></i>
                                    </span>
                                </div>
                            </th>
                            <th style="border:1px solid; background-color: #3399FF; width:80px; text-align:center;">Versi</th>
                            <th style="background-color:#3399FF;width:80px;text-align:center;">Volume</th>
                            <th style="border:1px solid; background-color:#3399FF;width:200px;text-align:center;">Harga Satuan (Rp)</th>
                            <th style="background-color:#33CC33;width:80px;text-align:center;">Volume</th>
                            <th style="border:1px solid; background-color:#33CC33;width:200px;text-align:right;">Harga Satuan (Rp)</th>
                            <th style="border:1px solid; background-color: #ffd43b;text-align:center;">Volume
                            </th>
                            <th style="background-color:#ffd43b;width:200px;text-align:center;">Nilai SPR (Rp)</th>
                            <th style="border:1px solid;text-align:center; background-color: #845ef7">Volume</th>
                            <th style="border:1px solid;text-align:center; background-color: #845ef7">Nilai PO (Rp)</th>
                            <th style="border:1px solid;text-align:center;">∆ RAP-SPR </th>
                            <th style="border:1px solid;text-align:center;">∆ RAP-PO</th>
                            <th style="border:1px solid;text-align:center;">∆ SPR-PO</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <div class="overlay dark loading_table" style="display: none;">
            <i class="fa fa-refresh fa-spin"></i>
        </div>
    </div>
</div>

<div class="modaltipeform" hidden>
    <div class="content">
        <p>Filter Value:</p>
        <hr>
        <table id="tableTipeForm">
        </table>
    </div>
    <div class="footer">
        <div id="btnFilter">Filter</div>
        <div id="closeModalTipeForm">Close</div>
    </div>
</div>

<script>
    $(document).ready(function() {
        ShowTableData()
        $('.searchInput').on('input', searchTable)
        var typeHead;
        $('.filterHead').click(function() {
            typeHead = $(this).data('head')
            var modal = $('.modaltipeform');
            var overlay = $('#overlay');
            var valuesMap = {};
            var valuesArray = [];
            $('#table_Ananlisa tbody tr').each(function() {
                if (typeHead == 'Uraian') {
                    var valuesTd = $(this).find('td:eq(1)').text()
                    if (!valuesMap[valuesTd]) {
                        valuesMap[valuesTd] = true;
                        valuesArray.push(valuesTd);
                    }
                } else if (typeHead == 'Versi') {
                    var valuesTd = $(this).find('td:eq(2)').text()
                    if (!valuesMap[valuesTd]) {
                        valuesMap[valuesTd] = true;
                        valuesArray.push(valuesTd);
                    }
                } else if (typeHead == 'KodeRAP') {
                    var valuesTd = $(this).find('td:eq(0)').text()
                    if (!valuesMap[valuesTd]) {
                        valuesMap[valuesTd] = true;
                        valuesArray.push(valuesTd);
                    }
                } else {
                    valuesArray = []
                }
            })
            $('#tableTipeForm').empty();
            $.each(valuesArray, function(index, val) {
                $('#tableTipeForm').append(`
					<tr style="border-bottom:1px solid #ebe6e6; ">
						<td><input style="margin-right:8px; margin-bottom:10px;" type="checkbox" name="isi" class="checkboxtipeform" value="${val}" id="${index}"><label for="${index}">${val}</label></td>
					</tr>
				`)
            })
            var buttonPosition = $(this).offset();
            var modalWidth = modal.outerWidth();
            var modalHeight = modal.outerHeight();
            var screenWidth = $(window).width();
            var screenHeight = $(window).height();
            var left = buttonPosition.left;
            if (buttonPosition.left + modalWidth > screenWidth) {
                left = screenWidth - modalWidth;
            }
            var top = buttonPosition.top + $(this).outerHeight();
            if (buttonPosition.top + $(this).outerHeight() + modalHeight > screenHeight) {
                top = screenHeight - modalHeight - $(this).outerHeight();
            }
            modal.css({
                top: top,
                left: left
            });
            modal.show();
        });
        $('#closeModalTipeForm').click(function() {
            var modal = $('.modaltipeform');
            var overlay = $('#overlay');
            modal.hide();
            overlay.hide();
        })
        $('#btnFilter').click(function() {
            var checkValues = [];
            $('.checkboxtipeform:checked').each(function() {
                checkValues.push($(this).val())
            })
            filterTable(checkValues)
            var modal = $('.modaltipeform');
            var overlay = $('#overlay');
            modal.hide();
            overlay.hide();
        })

        function filterTable(values) {
            $('#table_Ananlisa tbody tr').each(function() {
                if (typeHead == 'Uraian') {
                    var txtValueTipe = $(this).find('td:eq(1)').text().toLowerCase()
                } else if (typeHead == 'KodeRAP') {
                    var txtValueTipe = $(this).find('td:eq(0)').text().toLowerCase()
                } else {
                    return false;
                }
                var lowerCaseArray = values.map(function(value) {
                    return value.toLowerCase();
                });
                if (lowerCaseArray.length === 0 || lowerCaseArray.includes(txtValueTipe)) {
                    $(this).show()
                } else {
                    $(this).hide()
                }
            })
        }
    })

    function searchTable() {
        var filterUraian = $('#searchUraian').val().toLowerCase()
        var filterKodeRAP = $('#searchKodeRAP').val().toLowerCase()
        $('#table_Ananlisa tbody tr').each(function() {
            var txtValueUraian = $(this).find('td:eq(1)').text().toLowerCase()
            var txtValueKodeRAP = $(this).find('td:eq(0)').text().toLowerCase()
            if (txtValueUraian.indexOf(filterUraian) > -1 && txtValueKodeRAP.indexOf(filterKodeRAP) > -1) {
                $(this).show()
            } else {
                $(this).hide()
            }
        })
    }

    function ShowTableData() {
        var JobNo = $('.pdf_QHutangKO').attr('data-JobNo');
        var Alokasi = $('.pdf_QHutangKO').attr('data-Alokasi');
        var table = $("#table_Ananlisa").DataTable({
            ajax: {
                "url": "<?= site_url('MyReport/Procurement/AnalisaDelta/getListData') ?>",
                "type": "POST",
                "data": {
                    'JobNo': JobNo,
                    'Alokasi': Alokasi,
                },
            },
            pageLength: 50,
            language: {
                "emptyTable": "Data tidak ada",
                "loadingRecords": "Loading...",
                "processing": "Processing...",
            },
            columns: [{
                    "data": "KdRAP",
                    "orderable": false,
                    "searchable": true,
                },
                {
                    "data": "NoUrut",
                    "visible": false,
                    "orderable": true,
                },
                {
                    "data": "Uraian",
                    "orderable": false,
                    "searchable": true,
                    "render": function(data, type, row, meta) {
                        return data.length > 60 ?
                            '<div style="text-align: left;">' + data.substr(0, 58) + '..</div>' : '<div style="text-align: left;">' + data + '</div>'
                    }
                },
                {
                    "data": "Versi",
                    "orderable": false,
                    "searchable": true,
                    "render": function(data, type, row, meta) {
                        $('#headerVersi').html(data)
                        return '<div style="text-align: center;">' + data + '</div>';
                    }
                },
                {
                    "data": "Vol",
                    "orderable": false,
                    "searchable": true,
                },
                {
                    "data": "HrgSatuan",
                    "orderable": false,
                    "searchable": true,
                    "render": function(data, type, row, meta) {
                        return '<div style="text-align: right;">' + data + '</div>';
                    }
                },
                {
                    "data": "VolRAP",
                    "orderable": false,
                    "searchable": true,
                },
                {
                    "data": "HrgSatuanRAP",
                    "orderable": false,
                    "searchable": true,
                    "render": function(data, type, row, meta) {
                        return '<div style="text-align: right;">' + data + '</div>';
                    }
                },
                {
                    "data": "VolSPR",
                    "orderable": false,
                    "searchable": true,
                    "render": function(data, type, row, meta) {
                        return '<div style="text-align: right;">' + data + '</div>';
                    }
                },
                {
                    "data": "SPR",
                    "orderable": false,
                    "searchable": true,
                    "render": function(data, type, row, meta) {
                        return '<div style="text-align: right;">' + data + '</div>';
                    }
                },
                {
                    "data": "VolPO",
                    "orderable": false,
                    "searchable": true,
                    "render": function(data, type, row, meta) {
                        return '<div style="text-align: right;">' + data + '</div>';
                    }
                },
                {
                    "data": "Realisasi_PO",
                    "orderable": false,
                    "searchable": true,
                    "render": function(data, type, row, meta) {
                        return '<div style="text-align: right;">' + data + '</div>';
                    }
                },
                {
                    "data": "Delta1",
                    "orderable": false,
                    "searchable": true,
                    "render": function(data, type, row, meta) {
                        return '<div style="text-align: right;">' + data + '</div>';
                    }
                },
                {
                    "data": "Delta2",
                    "orderable": false,
                    "searchable": true,
                    "render": function(data, type, row, meta) {
                        return '<div style="text-align: right;">' + data + '</div>';
                    }
                },
                {
                    "data": "Delta3",
                    "orderable": false,
                    "searchable": true,
                    "render": function(data, type, row, meta) {
                        return '<div style="text-align: right;">' + data + '</div>';
                    }
                },
            ],
        });
        table.order([1, 'asc']);
    }
</script>