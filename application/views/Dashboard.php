<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/non-cartesian-zoom.js"></script>
<script src="https://code.highcharts.com/modules/mouse-wheel-zoom.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="https://code.highcharts.com/themes/adaptive.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap.min.css">

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap.min.js"></script>


<style>
    body { background:#f5f7fa; font-family: "Segoe UI", Arial; }
    .card { border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,0.06); }


    body {
        font-family:
            -apple-system,
            BlinkMacSystemFont,
            "Segoe UI",
            Roboto,
            Helvetica,
            Arial,
            "Apple Color Emoji",
            "Segoe UI Emoji",
            "Segoe UI Symbol",
            sans-serif;
        background: var(--highcharts-background-color);
        color: var(--highcharts-neutral-color-100);
    }

    .highcharts-figure,
    .highcharts-data-table table {
        min-width: 310px;
        max-width: 100%;
        margin: 1em auto;
    }

    #container {
        height: 600px;
    }

    .highcharts-data-table table {
        font-family: Verdana, sans-serif;
        border-collapse: collapse;
        border: 1px solid var(--highcharts-neutral-color-10, #e6e6e6);
        margin: 10px auto;
        text-align: center;
        width: 100%;
        max-width: 500px;
    }

    .highcharts-data-table caption {
        padding: 1em 0;
        font-size: 1.2em;
        color: var(--highcharts-neutral-color-60, #666);
    }

    .highcharts-data-table th {
        font-weight: 600;
        padding: 0.5em;
    }

    .highcharts-data-table td,
    .highcharts-data-table th,
    .highcharts-data-table caption {
        padding: 0.5em;
    }

    .highcharts-data-table thead tr,
    .highcharts-data-table tbody tr:nth-child(even) {
        background: var(--highcharts-neutral-color-3, #f7f7f7);
    }

    .highcharts-description {
        margin: 0.3rem 10px;
    }


</style>


<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
			
		</h1>
		
	
	</section>

	<!-- <section class="content-header">
		<a href="#" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
		<a href="#save" data-toggle="modal" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</a> --
	</section> -->

	<!-- <Section class="content"> -->
	<section class="content-header">
		<div class="row" id="MOD-IN">
			<div class="col-md-12">
				<div class="box box-warning">
					<div class="box-header with-border">
						<div class="box box-solid">
							<div class="box-header with-border">
								<div class="box-header bg-blue-gradient">
									<h3 class="box-title"></h3>
									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
										<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
									</div>
								</div>
							</div>

							<div class="box-body">
                <!-- <div class="content-wrapper">
                </div> -->

    <!-- Judul Halaman -->
    <section class="content-header">
        <h1 class="text-bold">Dashboard Monitoring SPBU</h1>
    </section>

    <section class="content">
        <div class="col-md-12">
            <form method="get" id="formFilter">
                <table class="table" border="0">
                <tr>
                    <td><strong>Tahun :</strong></td>
                    <td>
                        <?php
                            $tahunAktif = isset($_GET['tahun']) && $_GET['tahun'] != ''
                                ? $_GET['tahun']
                                : date('Y');
                        ?>
                        <select name="tahun" class="form-control" onchange="this.form.submit()">
                            <option value="">All</option>

                            <?php for ($i = 2020; $i <= date('Y'); $i++): ?>
                                <option value="<?= $i; ?>"
                                    <?= ($tahunAktif == $i) ? 'selected' : '' ?>>
                                    <?= $i; ?>
                                </option>
                            <?php endfor; ?>
                        </select>

                    </td>

                    <td><strong>Provinsi:</strong></td>
                    <td>
                        <select name="provinsi" class="form-control" onchange="this.form.submit()">
                            <option value="">All</option>
                            <?php foreach ($list_provinsi as $p): ?>
                                <option value="<?= $p['Regional']; ?>"
                                    <?= (@$_GET['provinsi'] == $p['Regional']) ? 'selected' : '' ?>>
                                    <?= $p['Regional']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                    </td>

                    <td><strong>Area:</strong></td>
                    <td>
                        <select name="area" class="form-control" onchange="this.form.submit()">
                            <option value="">All</option>
                            <?php foreach($list_area as $a): ?>
                                <option value="<?= $a['SalesDistrict']; ?>"
                                    <?= (@$_GET['area'] == $a['SalesDistrict']) ? 'selected' : '' ?>>
                                    <?= $a['SalesDistrict']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>

                    <td><strong>SPBU:</strong></td>
                    <td>
                        <select name="spbu" class="form-control" onchange="this.form.submit()">
                            <option value="">All</option>
                            <?php foreach($list_spbu as $s): ?>
                                <option value="<?= $s['NoSPBU']; ?>"
                                    <?= (@$_GET['spbu'] == $s['NoSPBU']) ? 'selected' : '' ?>>
                                    <?= $s['NoSPBU']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                </table>
                </form>

        </div>

        <div class="row">

            <!-- BOX 1 -->
            <div class="col-md-2 col-sm-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?=  number_format($total_kuota)?></h3>
                        <p><strong>Total Kuota</strong></p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-building"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        Lihat Detail <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- BOX 2 -->
            <div class="col-md-2 col-sm-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3><?= number_format($total_volume) ?></h3>
                        <p><strong>Total Volume / Realisasi</strong></p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-check-circle"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        Lihat Detail <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- BOX 3 -->
            <div class="col-md-2 col-sm-6">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3><?= ($persentase) ? number_format($persentase, 2) . '%' : '0%' ?></h3>
                        <p><strong>Realisasi (%)</strong></p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-warning"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        Lihat Detail <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

             <!-- BOX 4 -->
            <div class="col-md-2 col-sm-6">
                <div class="small-box bg-blue-gradient">
                    <div class="inner">
                        <h3><?= number_format($sisa_kuota) ?></h3>
                        <p><strong>Sisa Kuota</strong></p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-warning"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        Lihat Detail <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- BOX 5 -->
            <div class="col-md-2 col-sm-6">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>4</h3>
                        <p><strong>Year - End Forcast</strong></p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-times-circle"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        Lihat Detail <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- BOX 6 -->
            <div class="col-md-2 col-sm-6">
                <div class="small-box bg-purple">
                    <div class="inner">
                        <h3>4</h3>
                        <p><strong>Forcast (%)</strong></p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-times-circle"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        Lihat Detail <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

        </div>

        <!-- AREA 2: CHART + TABLE -->
        <div class="row">

        <figure class="highcharts-figure">
            <div id="container12"></div>
            <p class="highcharts-description">
               
            </p>
        </figure>

            <!-- GRAFIK -->
            <div class="col-md-8">
                <!-- <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Grafik Penjualan BBM (Monthly)</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="salesChart" style="width:100%; height:260px"></canvas>
                    </div>
                </div> -->

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Grafik Kuota vs Volume BBM (Bulanan)</h3>
                    </div>
                    <div class="box-body">
                        <!-- <canvas id="chartKuotaVolume"></canvas> -->
                         <canvas id="comboChart" height="120"></canvas>

                    </div>
                </div>

                <div class="box box-primary">
                    <div class="box-header with-border bg-blue">
                      <h6 class="text-uppercase"><center>Konsumen Pengguna</center></h6>
                    </div>
                    <div class="box-body">
                        

                        <table id="tblkonsumen" class="table table-bordered table-hover table-responsive bg-white">
                            <thead>
                                <tr class="bg-blue text-white">
                                    <th style="width: fit-content;">Provinsi</th>
                                    <th style="width: min-content;">Area</th>
                                    <th style="width: 50%;">Penyalur</th>
                                    <th>Kuota (kl)</th>
                                    <th>Realisasi (kl)</th>
                                    <th>Sisa (kl)</th>
                                    <th>Realisasi (%)</th>
                                </tr>
                                <tr>
                                    <th><input type="text" name="filter_provinsi" id="filter_provinsi" class="form-control"></th>
                                    <th><input type="text" name="filter_area" id="filter_area" class="form-control"></th>
                                    <th><input type="text" name="filter_penyalur" id="filter_penyalur" class="form-control"></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                    <?php foreach ($datakonsumen as $row): ?>
                                    <tr>
                                        <td style="text-align: left;"><?= htmlspecialchars($row->Regional) ?></td>
                                        <td style="text-align: left;"><?= htmlspecialchars($row->SalesDistrict) ?></td>
                                        <td style="text-align: left;"><?= htmlspecialchars($row->NoSPBU) ?></td>
                                        <td><?= number_format($row->Kuota) ?></td>
                                        <td><?= number_format($row->Volume) ?></td>
                                        <td><?= number_format($row->Kuota - $row->Volume) ?></td>
                                        <td>
                                            <?php
                                                $realisasiPercent = ($row->Kuota > 0) ? ($row->Volume / $row->Kuota) * 100 : 0;
                                                echo number_format($realisasiPercent, 2) . '%';
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                            </tbody>
                        </table>

                    </div>
                </div>

                
            </div>

            <!-- TABEL -->
            <div class="col-md-4">
                <figure class="highcharts-figure">
                    <div id="container"></div>
                    <p class="highcharts-description">
                        
                    </p>
                </figure>

                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Status Penyalur</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>SPBU</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>34.123.01</td>
                                    <td><span class="label label-success">Normal</span></td>
                                </tr>
                                <tr>
                                    <td>34.567.89</td>
                                    <td><span class="label label-warning">Warning</span></td>
                                </tr>
                                <tr>
                                    <td>34.901.12</td>
                                    <td><span class="label label-danger">Error</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                

                
                
                </div>

                


            </div>

        </div>

        <!-- <div class="table">
            <div class="table-responsive">
                <table class="table table-bordered" id="Tblforcast">
                    <thead>
                        <tr class="bg-blue text-white">
                            <th>Bulan</th>
                            <th>Kuota</th>
                            <th>Total Realisasi</th>
                            <th>Forecast</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($Forecast as $row): ?>
                        <tr>
                            <td><?= getBulan($row->bulan) ?></td>
                            <td><?= number_format($row->Kuota) ?></td>
                            <td><?= number_format($row->total_realisasi) ?></td>
                            <td><?= number_format($row->forecast, 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div> -->


        

        




    </section>
</div>





  <!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/series-label.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/export-data.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>
        <script src="https://code.highcharts.com/themes/adaptive.js"></script>

<script>


    $(document).ready(function () {


        Highcharts.chart('container12', {
            title: {
                text: 'Sales Forecast & Realisasi',
                style: {
                    fontSize: '18px',
                    fontweight: 'bold'
                }
            },

           xAxis: {
                categories: <?= $categoriesForecast ?>,
                labels: {
                    style: {
                        fontSize: '18px',
                        fontWeight: '500'
                    }
                }
            },

            yAxis: {
                title: {
                    text: 'Volume',
                    style: {
                        fontSize: '15px',
                        fontWeight: '600'
                    }
                },
                labels: {
                    style: {
                        fontSize: '13px'
                    }
                }
            },

            tooltip: {
                shared: true
            },

            plotOptions: {
                column: {
                    borderRadius: 8
                }
            },

            legend: {
                itemStyle: {
                    fontSize: '14px',
                    fontWeight: '500'
                }
            },

            series: [
                // ===== COLUMN REALISASI =====
                {
                    type: 'column',
                    name: 'Realisasi',
                    data: <?= $dataRealisasi ?>,
                    zIndex: 1
                },

                // ===== LINE FORECAST =====
                {
                    type: 'line',
                    name: 'Forecast',
                    data: <?= $dataForecast ?>,
                    step: 'center',
                    marker: {
                        lineWidth: 2,
                        fillColor: '#fff'
                    },
                    zIndex: 2
                },
                {
                    type: 'column',
                    name: 'Kuota',
                    data: <?= $dataKuota ?>,
                    dashStyle: 'Dash',
                    lineWidth: 2,
                    marker: {
                        enabled: false
                    },
                    color: '#FF9800',
                    zIndex: 1
                },


                // ===== PIE TOTAL =====
                
                {
                    type: 'pie',
                    name: 'Summary',
                    innerSize: '65%',
                    size: 200,
                    center: ['15%', '30%'],
                    data: [
                        {
                            name: 'Realisasi',
                            y: <?= $totalRealisasi ?>,
                            color: <?= $isOverKuota ? "'#E53935'" : "'#2196F3'" ?>
                        },
                        {
                            name: 'Sisa Kuota',
                            y: <?= $sisaKuota ?>,
                            color: '#4CAF50'
                        },
                        {
                            name: 'Total Kuota',
                            y: <?= $totalKuota ?>,
                            color: '#FF9800'
                        }
                    ],
                    dataLabels: {
                        enabled: false
                    }
                }



            ]
        });


        $('#tblkonsumen').DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            lengthChange: true,
            ordering: true,
            searching: true,
            info: true,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                paginate: {
                    previous: "Prev",
                    next: "Next"
                }
            },
            columnDefs: [
                { targets: [1,2,3,4], className: "text-right" }
            ]
        });
    });


    

const labels = <?= $bulan ?>;
const dataKuota = <?= $kuota ?>;
const dataVolume = <?= $volume ?>;

const ctx = document.getElementById('comboChart').getContext('2d');

new Chart(ctx, {
    data: {
        labels: labels,
        datasets: [
            {
                type: 'line',
                label: 'Kuota',
                data: dataKuota,
                yAxisID: 'yLeft',
                borderWidth: 1
            },
            {
                type: 'bar',
                label: 'Realisasi Volume',
                data: dataVolume,
                yAxisID: 'yRight',
                borderWidth: 2,
                tension: 0.3,
                fill: false
            },
            {
                type: 'line',
                label: 'Trend Realisasi Volume',
                data: dataVolume,
                yAxisID: 'yRight',
                borderWidth: 2,
                tension: 0.3,
                fill: false
            }
        ]
    },
    options: {
        responsive: true,
        interaction: {
            mode: 'index',
            intersect: false
        },
        plugins: {
            legend: {
                position: 'top'
            }
        },
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Bulan'
                }
            },
            yLeft: {
                type: 'linear',
                position: 'left',
                title: {
                    display: true,
                    text: 'Kuota (kl)'
                },
                beginAtZero: true
            },
            yRight: {
                type: 'linear',
                position: 'right',
                title: {
                    display: true,
                    text: 'Realisasi (kl)'
                },
                beginAtZero: true,
                grid: {
                    drawOnChartArea: false // grid kanan tidak tumpuk kiri
                }
                }
            }
        }
    });
    
        Highcharts.chart('container', {
    chart: {
        type: 'pie'
    },
    title: {
        text: 'Perbandingan Kuota vs Realisasi Volume (%)'
    },
    tooltip: {
        pointFormat: '<b>{point.percentage:.1f}%</b> ({point.y:,.0f} KL)'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '{point.name}: {point.percentage:.1f}%',
                style: {
                    fontSize: '12px'
                }
            }
        }
    },
    series: [{
        name: 'BBM',
        colorByPoint: true,
        data: [
            {
                name: 'Realisasi Volume',
                y: <?= (float) $total_volume ?>,
                sliced: true,
                selected: true
            },
            {
                name: 'Sisa Kuota',
                y: <?= (float) ($total_kuota - $total_volume) ?>
            }
        ]
    }]

});





</script>




	<script>
		$(document).idle({
			onIdle: function() {
				window.location = "/logout.php";
			},
			idle: 10000
		});
	</script>
</div>


<!-- /.container-fluid -->
</section>

</section>



<!-- </Section> -->

