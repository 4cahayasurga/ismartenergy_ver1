<style>
        td.control {
        cursor: pointer;
    }

    table.dataTable.dtr-inline.collapsed > tbody > tr > td.control:before {
        top: 50%;
        left: 5px;
        transform: translateY(-50%);
        color: #3c8dbc;
    }

    #tablesalesSPBB thead th {
        padding-top: 8px !important;
        padding-bottom: 8px !important;
    }

    #tablesalesSPBB th:nth-child(2),
    #tablesalesSPBB td:nth-child(2) {
        min-width: 10px;
    } 

    .btn-download-template{
        margin-top:8px;
        background: linear-gradient(135deg,#28a745,#20c997);
        border:none;
        color:#fff !important;
        font-weight:600;
        border-radius:6px;
        transition:all .3s ease;
    }

    .btn-download-template:hover{
        color:#fff !important;
        transform:translateY(-2px);
        box-shadow:0 5px 15px rgba(40,167,69,.35);
    }


</style>


<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>

		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $judul ?></li>
		</ol>
	</section>

	<section class="content-header">
		<!-- <a href="#" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a> -->
		<!-- <a href="#save" data-toggle="modal" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</a> -->
	</section>

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
                                <div class="container-fluid">
                                    <div class="row dashboard-cards">

                                        <!-- Import -->
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="info-card bg-grey">
                                                <h4>Import Excel SPBB</h4>

                                                <form method="post" action="<?= base_url('Ist/Import_spbb/upload') ?>" enctype="multipart/form-data">
                                                    <input type="file" name="file_excel" class="form-control input-sm" required>

                                                    <button type="submit" class="btn btn-primary btn-sm btn-block mt-2">
                                                        <i class="fa fa-upload"></i> Import Excel
                                                    </button>

                                                    <a href="<?= base_url('assets/template/Template_import_Data_Penjualan_SPBB.xlsx') ?>"
                                                    class="btn btn-success btn-sm btn-block btn-download-template"
                                                    download>
                                                        <i class="fa fa-file-excel-o"></i>
                                                        Download Template
                                                    </a>
                                                </form>


                                                <!-- <form method="post" action="<?= base_url('Ist/Import_spbb/upload') ?>" enctype="multipart/form-data">
                                                    <input type="file" name="file_excel" class="form-control input-sm" required>
                                                    <button type="submit" class="btn btn-primary btn-sm btn-block mt-2">
                                                        Import
                                                    </button>
                                                </form> -->
                                            </div>
                                        </div>

                                        <!-- Total Penjualan -->
                                        <div class="col-lg-3 col-md-6 col-sm-12" style="background-color: #00c0ef; border-radius: 10px; padding: 10px; color: white; margin-right: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                                            <div class="info-card">
                                                <h2>Total Penjualan</h2>
                                                <div class="info-value"><h4><?= number_format($totalLiter[0]['total_liter']) ?> Liter</h4></div>
                                            </div>
                                        </div>

                                        <!-- Jumlah Kapal -->
                                        <div class="col-lg-3 col-md-6 col-sm-12" style="background-color: #ff851b; border-radius: 10px; padding: 10px; color: white; margin-right:15px;  box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                                            <div class="info-card bg-magenta">
                                                <h2>Jumlah Kapal</h2>
                                                <div class="info-value"><h4><?= $JumlahKapal[0]['jumlah_kapal'] ?> Unit</h4></div>
                                            </div>
                                        </div>

                                        <!-- Jumlah Transaksi -->
                                        <div class="col-lg-3 col-md-6 col-sm-12" style="background-color: #D81B60; border-radius: 10px; padding: 10px; color: white;  box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                                            <div class="info-card">
                                                <h2>Jumlah Transaksi</h2>
                                                <div class="info-value"><h4><?= number_format($TotalTransaksi[0]['total_transaksi']) ?> Transaksi</h4></div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <hr>
                                        </div>
                                    </div>


                                </div>

                            </div>
                        </div>
                    </div>
                </div>

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
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive" style="overflow-x:auto;">
                                            <table id="tablesalesSPBB" class="table table-bordered table-striped nowrap" style="width:100%">
                                                <thead class="bg-primary">
                                                    <tr>
                                                        <th></th>
                                                        <th>No Transaksi</th>
                                                        <th>No Quotation</th>
                                                        <th>Nama Kapal</th>
                                                        <th>Jenis Kapal</th>
                                                        <th>Tanggal Pemesanan</th>
                                                        <th>Waktu Pemesanan</th>
                                                        <th>Dipesan (Liter)</th>
                                                        <th>Tanggal Pengiriman</th>
                                                        <th>Waktu Pengiriman</th>
                                                        <th>Realisasi (Liter)</th>
                                                        <th>Jenis BBM</th>
                                                        <th>Titik Serah</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>

                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th><input class="form-control form-control-sm" placeholder="Cari"></th>
                                                        <th></th>
                                                        <th><input class="form-control form-control-sm" placeholder="Cari"></th>
                                                        <th><input class="form-control form-control-sm" placeholder="Cari"></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th><input class="form-control form-control-sm" placeholder="Cari"></th>
                                                        <th></th>
                                                        <th><input class="form-control form-control-sm" placeholder="Cari"></th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <?php foreach($this->db->get('transaksi_spbb')->result() as $row): ?>
                                                    <tr>
                                                        <td></td>
                                                        <td><?= $row->no_transaksi ?></td>
                                                        <td><?= $row->no_quotation ?></td>
                                                        <td><?= $row->nama_kapal ?></td>
                                                        <td><?= $row->jenis_kapal ?></td>
                                                        <td><?= $row->tanggal_pemesanan ?></td>
                                                        <td><?= $row->waktu_pemesanan ?></td>
                                                        <td><?= number_format($row->dipesan_liter) ?></td>
                                                        <td><?= $row->tanggal_pengiriman ?></td>
                                                        <td><?= $row->waktu_pengiriman ?></td>
                                                        <td><?= number_format($row->realisasi_liter) ?></td>
                                                        <td><?= $row->jenis_bbm ?></td>
                                                        <td><?= $row->titik_serah ?></td>
                                                        <td>
                                                            <span class="label label-success"><?= $row->status ?></span>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                            </div>

                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                </div>


            </div>
        </div>
</section>
</div>

<link rel="stylesheet" href="<?= base_url('assets/datatables/responsive/css/responsive.bootstrap.min.css') ?>">

<script src="<?= base_url('assets/datatables/responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/datatables/responsive/js/responsive.bootstrap.min.js') ?>"></script>


<script>
  $(document).ready(function () {

    var table = $('#tablesalesSPBB').DataTable({
                    responsive: true,
                    orderCellsTop: true,
                    fixedHeader: true,
                    pageLength: 10,
                    lengthMenu: [
                        [10, 25, 50, -1],
                        [10, 25, 50, "View All"]
                    ]
                });

                // Pasang event listener pada setiap input kolom
                $('#tablesalesSPBB thead tr:eq(1) th').each(function(i) {
                    $('input', this).on('keyup change', function() {
                        if (table.column(i).search() !== this.value) {
                            table.column(i).search(this.value).draw();
                        }
                    });
                });


    // var table = $('#tablesalesSPBB').DataTable({
    //     responsive: {
    //         details: {
    //             type: 'column',
    //             target: 0
    //         }
    //     },
    //     columnDefs: [
    //         { className: 'control', orderable: false, targets: 0 }
    //     ],
    //     scrollX: true,
    //     autoWidth: false,
    //     dom: 'lBfrtip',
    //     buttons: ['copyHtml5', 'excelHtml5']
    // });

    // // === FIX FILTER FOOTER ===
    // $('#tablesalesSPBB tfoot input').on('keyup change clear', function () {
    //     var colIdx = $(this).closest('th').index();
    //     table.column(colIdx).search(this.value).draw();
    // });

});



</script>



