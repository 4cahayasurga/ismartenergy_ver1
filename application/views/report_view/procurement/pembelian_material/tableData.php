<style type="text/css">
    #myTable {
        font-size: 12px;
        font-family: Arial, Helvetica, sans-serif;
    }

    #myTable thead {
        background-color: #ccd4db;
        color: #000;
    }

    #myTable thead tr td {
        padding-top: 10px;
        padding-bottom: 10px;
        white-space: nowrap;
        border: 1px solid #000;
        background-color: #ccd4db;
        font-weight: bold;
    }

    #myTable thead tr td div {
        display: flex;
        justify-content: space-between;
    }

    #myTable tbody tr td {
        padding-top: 10px;
        padding-bottom: 10px;
        white-space: wrap;
        border: 1px solid;
    }

    #myTable tbody tr td:nth-child(5),
    #myTable tbody tr td:nth-child(6) {
        text-align: center;
    }

    #myTable tbody tr td:last-child {
        text-align: right;
    }

    #myTable tbody tr td div {
        display: flex;
        justify-content: space-between;
    }

    #myTable tfoot tr th {
        padding-top: 10px;
        padding-bottom: 10px;
        padding-left: 5px;
        border-top: 1px solid black;
        white-space: wrap;
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

    .cont-seacrh input {
        min-width: 80px;
        width: 100%;
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
        max-width: 500px;
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

    .modaltipeform .footer .closeModalTipeForm {
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

    .modalchart {
        position: fixed;
        top: 30px;
        left: 0;
        display: none;
        height: 100vh;
        width: 100%;
        z-index: 999;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modalchart .content {
        background-color: #fff;
        border-radius: 5px;
        max-width: 80%;
        margin-top: 50px;
        position: relative;
    }

    .modalchart .content .closechart {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        position: absolute;
        background: #1f87c4;
        color: #fff;
        top: 0;
        right: 0;
        margin-top: -10px;
        margin-right: -10px;
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
            <a href="<?= base_url('MyReport/Procurement/PembelianMaterial/exportPDF') ?>" target="_blank" class="btn btn-md btn-danger">Print PDF <i class="fa fa-print"></i></a>
            <a href="<?= base_url('MyReport/Procurement/PembelianMaterial/exportExcel') ?>" target="_blank" class="btn btn-success">Export Excel <i class="fa fa-file-excel-o"></i></a>
            <a href="#" class="btn btn-primary btn-chart">Show Chart <i class="fa fa-bar-chart"></i>
            </a>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table data-startdate="<?= $PrdAwal ?>" data-enddate="<?= $PrdAkhir ?>" data-JobNo="<?= $JobNo ?>" id="myTable" class="table table-striped table-hover table-condensed" border="0" width="100%">
                    <thead>
                        <tr>
                            <td colspan="7"></td>
                            <td>
                                <div>
                                    <span>Sum = </span>
                                    <span id="sumTotal">713.432.090</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center" width="20px">No</td>
                            <td class="text-center" width="100px">
                                Kode Barang
                                <div class="cont-seacrh">
                                    <input type="text" id="searchKdBarang" class="searchInput" placeholder="Cari Kode Barang..">
                                    <span class="filterHead" data-head="KdBarang">
                                        <i class="fa fa-filter"></i>
                                    </span>
                                </div>
                            </td>
                            <td class="text-center" width="300px">
                                Nama Barang
                                <div class="cont-seacrh">
                                    <input type="text" id="searchNmBarang" class="searchInput" placeholder="Cari Barang..">
                                    <span class="filterHead" data-head="NmBarang">
                                        <i class="fa fa-filter"></i>
                                    </span>
                                </div>
                            </td>
                            <td class="text-center" width="200px">
                                Nama Vendor
                                <div class="cont-seacrh">
                                    <input type="text" id="searchNmVendor" class="searchInput" placeholder="Cari Vendor..">
                                    <span class="filterHead" data-head="NmVendor">
                                        <i class="fa fa-filter"></i>
                                    </span>
                                </div>
                            </td>
                            <td class="text-center" width="50px">Tgl Pembelian</td>
                            <td class="text-center" width="50px">Vol</td>
                            <td class="text-center" width="100px">Harga Satuan</td>
                            <td class="text-center" width="100px">Total</td>
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
        <div class="closeModalTipeForm">Close</div>
    </div>
</div>

<div class="modalchart" hidden>
    <div class="content">
        <div class="closechart">x</div>
        <canvas id="myChart"></canvas>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
<!-- chart js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var typeHead;

    $(document).ready(function() {
        var myChart;
        showTableData()
        $('.searchInput').on('input', searchTable)
        $('.filterHead').click(function() {
            typeHead = $(this).data('head')
            var modal = $('.modaltipeform');
            var overlay = $('#overlay');
            var valuesMap = {};
            var valuesArray = [];
            $('#myTable tbody tr').each(function() {
                if (typeHead == 'KdBarang') {
                    var valuesTd = $(this).find('td:eq(1)').text() == 'null' ? 'kosong' : $(this).find('td:eq(1)').text()
                    if (!valuesMap[valuesTd]) {
                        valuesMap[valuesTd] = true;
                        valuesArray.push(valuesTd);
                    }
                } else if (typeHead == 'NmBarang') {
                    var valuesTd = $(this).find('td:eq(2)').text()
                    if (!valuesMap[valuesTd]) {
                        valuesMap[valuesTd] = true;
                        valuesArray.push(valuesTd);
                    }
                } else if (typeHead == 'NmVendor') {
                    var valuesTd = $(this).find('td:eq(3)').text() == '' ? 'null' : $(this).find('td:eq(3)').text()
                    if (!valuesMap[valuesTd]) {
                        valuesMap[valuesTd] = true;
                        valuesArray.push(valuesTd);
                    }
                } else {
                    valuesArray = []
                }
            })
            $('#tableTipeForm').empty();
            $.each(valuesArray, function(index, label) {
                if (label == 'null') {
                    var value = ''
                } else {
                    var value = label
                }
                $('#tableTipeForm').append(`
					<tr style="border-bottom:1px solid #ebe6e6; ">
						<td><input style="margin-right:8px; margin-bottom:10px;" type="checkbox" name="isi" class="checkboxtipeform" value="${value}" id="${index}"><label for="${index}">${label}</label></td>
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
        })
        $('.closeModalTipeForm').click(function() {
            var modal = $('.modaltipeform');
            var modal2 = $('.modaltipedate');
            var overlay = $('#overlay');
            modal.hide();
            modal2.hide();
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
        $('.btn-chart').click(function(e) {
            e.preventDefault()
            var modal = $('.modalchart')
            $.ajax({
                url: "<?= site_url('MyReport/Procurement/PembelianMaterial/getTopData') ?>",
                success: function(result) {
                    dataChart = JSON.parse(result)

                    var ctx = document.getElementById('myChart').getContext('2d');
                    myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            datasets: [{
                                label: "Data",
                                data: dataChart,
                                backgroundColor: [
                                    'RGB(0, 204, 255)',
                                    'RGB(0, 250, 154)',
                                    'RGB(123, 104, 238)',
                                    'RGB(255, 246, 143)',
                                    'RGB(238, 99, 99)',
                                    'RGB(0, 154, 205)',
                                    'RGB(193, 255, 193)',
                                    'RGB(188, 238, 104)',
                                    'RGB(255, 185, 15)',
                                    'RGB(0, 139, 139)'
                                ],
                                borderColor: [
                                    'rgb(255, 99, 132)',
                                    'rgb(255, 159, 64)',
                                    'rgb(255, 205, 86)',
                                    'rgb(75, 192, 192)',
                                    'rgb(54, 162, 235)',
                                    'rgb(153, 102, 255)',
                                    'rgb(201, 203, 207)',
                                    'rgb(255, 0, 0)',
                                    'rgb(0, 255, 0)',
                                    'rgb(0, 0, 255)'
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Top 10 Data Dengan Pengeluaran Terbesar Pada Periode Ini'
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {

                                            const tgl = context.dataset.data[context.dataIndex].TglKO;
                                            const total = context.dataset.data[context.dataIndex].Total;
                                            var total2 = new Intl.NumberFormat('id-ID', {
                                                style: 'currency',
                                                currency: 'IDR',
                                                maximumFractionDigits: 0,
                                            }).format(total);
                                            return total2 + '\t\t[ Pada Tanggal : ' + tgl + ']'
                                        },
                                    }
                                }
                            },
                            animations: {
                                tension: {
                                    duration: 1000,
                                    easing: 'linear',
                                    from: 0,
                                    to: 1,
                                    loop: true
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true
                                },
                                x: {
                                    display: false
                                }
                            },
                            parsing: {
                                xAxisKey: 'Uraian',
                                yAxisKey: 'Total'
                            }
                        }
                    });
                }
            })
            modal.show()
        })
        $('.closechart').click(function() {
            myChart.destroy()
            var modal = $('.modalchart')
            modal.hide()
        })
    });

    function showTableData() {
        var JobNo = $('#myTable').attr('data-jobno');
        var StartDate = $('#myTable').attr('data-startdate');
        var EndDate = $('#myTable').attr('data-enddate');
        var table = $('#myTable').DataTable({
            ajax: {
                url: "<?= base_url('MyReport/Procurement/PembelianMaterial/getDataMaterial') ?>",
                type: "POST",
                data: {
                    JobNo,
                    StartDate,
                    EndDate
                },
                dataSrc: function(result) {
                    if (result.data.length < 1) {
                        $('.box-header').hide()
                    }
                    const sumTotal = result.sumTotal
                    $('#sumTotal').html(formatRupiah(sumTotal))
                    return result.data
                }
            },
            pageLength: 50,
            lengthMenu: [50, 100, 200, 500, 1000, -1],
            columns: [{
                    "orderable": false,
                    "render": function(data, type, row, meta) {
                        return meta.row + 1
                    },
                },
                {
                    "orderable": false,
                    "searchable": true,
                    "data": "KdBarang",
                },
                {
                    "data": "Uraian",
                    "orderable": false,
                    "searchable": true,
                },
                {
                    "data": "VendorNm",
                    "orderable": false,
                },
                {
                    "data": "TglKO",
                    "orderable": true,
                },
                {
                    "data": "Vol",
                    "orderable": true,
                    "render": function(data, type, row) {
                        return parseFloat(data).toFixed(3);
                    },
                },
                {
                    "data": "HrgSatuan",
                    "orderable": false,
                    "render": function(data, type, row) {
                        return (`
                        <div>
                        <span>Rp.</span>
                        <span>${formatRupiah(parseFloat(data))}</span>
                        </div>`)
                    },
                },
                {
                    "data": "Total",
                    "orderable": false,
                    "render": function(data, type, row) {
                        return (`
                        <div>
                        <span>Rp.</span>
                        <span id="total">${formatRupiah(parseFloat(data))}</span>
                        </div>`)
                    },
                },
            ],
        })
    }

    function filterTable(values) {
        var sumTotal = 0
        $('#myTable tbody tr').each(function() {
            if (typeHead == 'KdBarang') {
                var txtValueTipe = $(this).find('td:eq(1)').text().toLowerCase()
            } else if (typeHead == 'NmBarang') {
                var txtValueTipe = $(this).find('td:eq(2)').text().toLowerCase()
            } else if (typeHead == 'NmVendor') {
                var txtValueTipe = $(this).find('td:eq(3)').text().toLowerCase()
            }
            var lowerCaseArray = values.map(function(value) {
                return value.toLowerCase();
            });
            if (lowerCaseArray.length === 0 || lowerCaseArray.includes(txtValueTipe)) {
                $(this).show()
                var valueTotal = $(this).find('td:eq(7)').find('#total').text()
                var convInt = sum_string_to_int(valueTotal)
                sumTotal += convInt
            } else {
                $(this).hide()
            }
        })
        $('#sumTotal').html(formatRupiah(sumTotal))
    }

    function searchTable() {
        var filterKdBarang = $('#searchKdBarang').val().toLowerCase()
        var filterNmBarang = $('#searchNmBarang').val().toLowerCase()
        var filterNmVendor = $('#searchNmVendor').val().toLowerCase()
        $('#myTable tbody tr').each(function() {
            var txtValueKdBarang = $(this).find('td:eq(1)').text().toLowerCase()
            var txtValueNmBarang = $(this).find('td:eq(2)').text().toLowerCase()
            var txtValueNmVendor = $(this).find('td:eq(3)').text().toLowerCase()
            if (txtValueKdBarang.indexOf(filterKdBarang) > -1 && txtValueNmBarang.indexOf(filterNmBarang) > -1 && txtValueNmVendor.indexOf(filterNmVendor) > -1) {
                $(this).show()
            } else {
                $(this).hide()
            }
        })
    }

    function formatRupiah(value) {
        const isNegative = value < 0;
        let formattedValue = value.toLocaleString('id-ID', {
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
        // formattedValue = isNegative ? `Rp -${formattedValue}` : `Rp ${formattedValue}`;
        return formattedValue;
    }

    function sum_string_to_int(nilai) {
        nilai = nilai.replace(/[^\d.-]/g, '');
        const integerValue = parseInt(nilai.replace(/\./g, ''), 10);
        if (isNaN(integerValue)) {
            console.error('Invalid input value:', nilai);
            return 0;
        }
        return integerValue;
    }
</script>