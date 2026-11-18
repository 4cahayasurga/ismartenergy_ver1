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

    /* ===================== */
    .info-card {
        border-radius: 10px;
        padding: 15px;
        color: #fff;
        box-shadow: 0 6px 15px rgba(0,0,0,0.2);
    }

    .bg-grey {
        background: linear-gradient(135deg, #6c757d, #495057);
    }

    .info-card h4 {
        font-size: 14px;
        margin-bottom: 10px;
    }


    .upload-card {
    transition: 0.3s;
}

.upload-card:hover {
    transform: translateY(-3px);
}

/* Upload area */
.upload-area {
    border: 2px dashed rgba(255,255,255,0.5);
    border-radius: 8px;
    padding: 15px;
    text-align: center;
    cursor: pointer;
    transition: 0.3s;
}

.upload-area:hover {
    background: rgba(255,255,255,0.1);
}

.upload-label i {
    font-size: 24px;
    display: block;
    margin-bottom: 5px;
}

.upload-label span {
    font-size: 12px;
}

/* Progress */
.progress-bar {
    font-size: 11px;
    font-weight: bold;
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
        <?php if (!empty($manual_spbb_list)) : ?>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#manualDeliveryModal">
                <i class="fa fa-plus"></i> Input Penjualan Manual
            </button>
        <?php endif; ?>
	</section>

	<!-- <Section class="content"> -->
	<section class="content-header">
        <?php if ($this->session->flashdata('Berhasil')) : ?><div class="alert alert-success"><?= html_escape($this->session->flashdata('Berhasil')) ?></div><?php endif; ?>
        <?php if ($this->session->flashdata('Gagal')) : ?><div class="alert alert-danger"><?= html_escape($this->session->flashdata('Gagal')) ?></div><?php endif; ?>
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

                                    <div class="info-card bg-grey upload-card">

                                            <h4><i class="fa fa-file-excel-o"></i> Import Penjualan / Stock Keluar</h4>

                                            <!-- DOWNLOAD -->
                                            <a href="<?= base_url('assets/template/Template_Upload_Data_Delivery.xlsx') ?>" 
                                            class="btn btn-success btn-block btn-sm mb-2">
                                                <i class="fa fa-download"></i> Download Template
                                            </a>

                                            <!-- UPLOAD -->
                                            <form method="post" id="uploadForm" 
                                                action="<?= base_url('Ist/Import_spbb/uploadDataDelivery') ?>" 
                                                enctype="multipart/form-data">

                                                <div class="upload-area">
                                                    <input type="file" name="file_excel" id="fileInput" required hidden>
                                                    
                                                    <label for="fileInput" class="upload-label">
                                                        <i class="fa fa-cloud-upload"></i>
                                                        <span id="fileName">Pilih atau drag file Excel</span>
                                                    </label>
                                                </div>

                                                <button type="submit" class="btn btn-primary btn-block btn-sm mt-2">
                                                    <i class="fa fa-upload"></i> Upload File
                                                </button>
                                            </form>

                                            <!-- PROGRESS -->
                                            <div class="progress mt-2" style="height: 18px; display:none;" id="progressBox">
                                                <div id="progressBar" 
                                                    class="progress-bar progress-bar-striped active"
                                                    style="width:0%">0%</div>
                                            </div>

                                            <div id="uploadStatus" class="text-center mt-2 small"></div>

                                        </div>



                                        <!-- Import -->
                                        <!-- <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="info-card bg-grey">
                                                <h4>Import Excel SPBB</h4>
                                                <form method="post" action="<?= base_url('Ist/Import_spbb/uploadDataDelivery') ?>" enctype="multipart/form-data">
                                                    <input type="file" name="file_excel" class="form-control input-sm" required>
                                                    <button type="submit" class="btn btn-primary btn-sm btn-block mt-2">
                                                        Import
                                                    </button>
                                                </form>
                                            </div>
                                        </div> -->

                                        <!-- Total Penjualan -->
                                        <!-- <div class="col-lg-3 col-md-6 col-sm-12" style="background-color: #00c0ef; border-radius: 10px; padding: 10px; color: white; margin-right: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                                            <div class="info-card">
                                                <h2>Total Penjualan</h2>
                                                <div class="info-value"><h4><?= number_format($totalLiter[0]['total_liter']) ?> Liter</h4></div>
                                            </div>
                                        </div> -->

                                        <!-- Jumlah Kapal -->
                                        <!-- <div class="col-lg-3 col-md-6 col-sm-12" style="background-color: #ff851b; border-radius: 10px; padding: 10px; color: white; margin-right:15px;  box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                                            <div class="info-card bg-magenta">
                                                <h2>Jumlah Kapal</h2>
                                                <div class="info-value"><h4><?= $JumlahKapal[0]['jumlah_kapal'] ?> Unit</h4></div>
                                            </div>
                                        </div> -->

                                        <!-- Jumlah Transaksi -->
                                        <!-- <div class="col-lg-3 col-md-6 col-sm-12" style="background-color: #D81B60; border-radius: 10px; padding: 10px; color: white;  box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                                            <div class="info-card">
                                                <h2>Jumlah Transaksi</h2>
                                                <div class="info-value"><h4><?= number_format($TotalTransaksi[0]['total_transaksi']) ?> Transaksi</h4></div>
                                            </div>
                                        </div> -->

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
                                                        <th>SUMBER DATA</th>
                                                        <th>YEAR</th>
                                                        <th>MONTH</th>
                                                        <th>NO. TRANSACTION</th>
                                                        <th>NO. QUOTATION</th>
                                                        <th>NAMA KAPAL</th>
                                                        <th>JENIS KAPAL</th>
                                                        <th>TGL PEMESANAN</th>
                                                        <th>WAKTU PEMESANAN</th>
                                                        <th>VOL PESAN</th>
                                                        <th>DATE REALISASI</th>
                                                        <th>WAKTU PENYALURAN</th>
                                                        <th>VOL REALISASI</th>
                                                        <th>GRADE</th>
                                                        <th>NO SPBB</th>
                                                        <th>STATUS</th>
                                                        <th>SPP</th>
                                                        <th>SPB</th>
                                                        <th>LOGBOOK</th>
                                                        <th>RPT /RPK</th>
                                                    </tr>
                                                </thead>

                                                <thead>
                                                    
                                                    <tr>
                                                        <th></th>
                                                        <th><input class="form-control form-control-sm" placeholder="Cari"></th>
                                                        <th><input class="form-control form-control-sm" placeholder="Cari"></th>
                                                        <th></th>
                                                        <th><input class="form-control form-control-sm" placeholder="Cari"></th>
                                                        <th><input class="form-control form-control-sm" placeholder="Cari"></th>
                                                        <th><input class="form-control form-control-sm" placeholder="Cari"></th>
                                                        <th><input class="form-control form-control-sm" placeholder="Cari"></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th><input class="form-control form-control-sm" placeholder="Cari"></th>
                                                        <th></th>
                                                        <th><input class="form-control form-control-sm" placeholder="Cari"></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <?php foreach($data_delivery as $i => $row) { ?>
                                                    <tr>
                                                        <td><?= $i + 1 ?></td>
                                                        <td><span class="label label-info"><?= html_escape(isset($row['EntrySource']) ? $row['EntrySource'] : 'LEGACY') ?></span></td>
                                                        <td><?= date('Y', strtotime($row['Tgl_Pemesanan'])) ?></td>
                                                        <td><?= date('m', strtotime($row['Tgl_Pemesanan'])) ?></td>
                                                        <td><?= $row['DO_Transaksi'] ?></td>
                                                        <td><?= $row['NoQuotation'] ?></td>
                                                        <td><?= $row['Nm_Kapal'] ?></td>
                                                        <td><?= $row['JenisKapal'] ?></td>
                                                        <td><?= $row['Tgl_Pemesanan'] ?></td>
                                                        <td><?= $row['Waktu_Pemesanan'] ?></td>
                                                        <td><?= $row['Vol_Dipesan'] ?></td>
                                                        <td><?= $row['Tgl_Realisasi'] ?></td>
                                                        <td><?= $row['Waktu_Realisasi'] ?></td>
                                                        <td><?= $row['Vol_Realisasi'] ?></td>
                                                        <td><?= $row['Grade'] ?></td>
                                                        <td><?= $row['No_SPBB'] ?></td>
                                                        <td><?= $row['Status'] ?></td>
                                                        <td><?= $row['SPP'] ?></td>
                                                        <td><?= $row['SPB'] ?></td>
                                                        <td><?= $row['Logbook'] ?></td>
                                                        <td><?= $row['RPT_RI'] ?></td>
                                                    </tr>
                                                    <?php } ?>
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

<?php if (!empty($manual_spbb_list)) : ?>
<div class="modal fade" id="manualDeliveryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="<?= site_url('Ist/Import_spbb/saveManualDelivery') ?>" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Input Penjualan / Stock Keluar</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4"><div class="form-group"><label>SPBB</label><select name="SpbbID" id="manualSpbb" class="form-control" required><option value="">Pilih SPBB</option><?php foreach ($manual_spbb_list as $spbb) : ?><option value="<?= $spbb['SpbbID'] ?>"><?= html_escape($spbb['SpbbName']) ?></option><?php endforeach; ?></select></div></div>
                        <div class="col-md-4"><div class="form-group"><label>Material</label><select name="FuelID" id="manualFuel" class="form-control" required><option value="">Pilih material</option><?php foreach ($manual_fuel_list as $fuel) : ?><option value="<?= $fuel['FuelID'] ?>" data-spbb="<?= $fuel['SpbbID'] ?>"><?= html_escape($fuel['FuelName']) ?></option><?php endforeach; ?></select></div></div>
                        <div class="col-md-4"><div class="form-group"><label>No. Transaksi/DO</label><input name="TransactionNo" class="form-control" required></div></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><div class="form-group"><label>Nama Customer/Kapal</label><input name="CustomerName" class="form-control" required></div></div>
                        <div class="col-md-4"><div class="form-group"><label>Customer Group</label><input name="CustomerGroup" class="form-control" required></div></div>
                        <div class="col-md-4"><div class="form-group"><label>Jenis Customer/Kapal</label><input name="CustomerType" class="form-control"></div></div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"><div class="form-group"><label>Tanggal Pesan</label><input type="date" name="OrderDate" value="<?= date('Y-m-d') ?>" class="form-control" required></div></div>
                        <div class="col-md-3"><div class="form-group"><label>Waktu Pesan</label><input type="time" name="OrderTime" value="<?= date('H:i') ?>" class="form-control"></div></div>
                        <div class="col-md-3"><div class="form-group"><label>Tanggal Realisasi</label><input type="date" name="RealizationDate" value="<?= date('Y-m-d') ?>" class="form-control" required></div></div>
                        <div class="col-md-3"><div class="form-group"><label>Waktu Realisasi</label><input type="time" name="RealizationTime" value="<?= date('H:i') ?>" class="form-control"></div></div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"><div class="form-group"><label>Volume Dipesan</label><input type="number" min="0" step="1" name="OrderedQuantity" class="form-control" required></div></div>
                        <div class="col-md-3"><div class="form-group"><label>Volume Realisasi</label><input type="number" min="1" step="1" name="RealizedQuantity" class="form-control" required></div></div>
                        <div class="col-md-3"><div class="form-group"><label>No. Quotation</label><input name="QuotationNo" class="form-control"></div></div>
                        <div class="col-md-3"><div class="form-group"><label>SPP</label><input name="SPP" class="form-control"></div></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><div class="form-group"><label>SPB</label><input name="SPB" class="form-control"></div></div>
                        <div class="col-md-4"><div class="form-group"><label>Logbook</label><input name="Logbook" class="form-control"></div></div>
                        <div class="col-md-4"><div class="form-group"><label>Referensi RPT/RI</label><input name="ReportReference" class="form-control"></div></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan Penjualan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.getElementById('manualSpbb').addEventListener('change', function () {
    var spbb = this.value, fuel = document.getElementById('manualFuel');
    Array.prototype.forEach.call(fuel.options, function (option) {
        option.hidden = option.value && option.getAttribute('data-spbb') !== spbb;
    });
    fuel.value = '';
});
</script>
<?php endif; ?>

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


                // ===============================

        $('#uploadForm').on('submit', function(e){
            e.preventDefault();

            let formData = new FormData(this);

            $('#progressBox').show();
            $('#progressBar').css('width', '0%').text('0%');
            $('#uploadStatus').text('Uploading...');

            $.ajax({
                xhr: function() {
                    let xhr = new window.XMLHttpRequest();

                    xhr.upload.addEventListener("progress", function(evt){
                        if(evt.lengthComputable){
                            let percent = Math.round((evt.loaded / evt.total) * 100);

                            $('#progressBar')
                                .css('width', percent + '%')
                                .text(percent + '%');
                        }
                    }, false);

                    return xhr;
                },
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(res){

                    $('#uploadStatus').html('<span style="color:green;">Upload berhasil ✅</span>');

                    setTimeout(()=>{
                        location.reload();
                    }, 1500);
                },
                error: function(){
                    $('#uploadStatus').html('<span style="color:red;">Upload gagal ❌</span>');
                }
            });
        }); 

                $('#fileInput').on('change', function(){
                    let fileName = this.files[0]?.name || 'Pilih file';
                    $('#fileName').text(fileName);
                });

                $('#uploadStatus').html(`
                    <span style="color:#00ffcc; font-weight:bold;">
                        ✔ Upload berhasil
                    </span>
                `);


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



