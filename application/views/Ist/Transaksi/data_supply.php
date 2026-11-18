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
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="info-card bg-grey upload-card">

                                            <h4><i class="fa fa-file-excel-o"></i> Import Stock Masuk (Supply)</h4>

                                            <!-- DOWNLOAD -->
                                            <a href="<?= base_url('assets/template/template_Upload_data_Suppy.xlsx') ?>" 
                                            class="btn btn-success btn-block btn-sm mb-2">
                                                <i class="fa fa-download"></i> Download Template
                                            </a>

                                            <!-- UPLOAD -->
                                            <form method="post" id="uploadForm" 
                                                action="<?= base_url('Ist/Import_spbb/uploadDataSuply') ?>" 
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
                                                        <th>YEAR</th>
                                                        <th>MONTH</th>
                                                        <th>D.O. NUMBER</th>
                                                        <th>VEHICLE NO</th>
                                                        <th>DATE</th>
                                                        <th>TIME</th>
                                                        <th>DRIVER NAME</th>
                                                        <th>SUPPLIER</th>
                                                        <th>TANK</th>
                                                        <th>SUPPLY POINT NAME</th>
                                                        <th>NO SPBB</th>
                                                        <th>GRADE</th>
                                                        <th>BEGIN STOCK</th>
                                                        <th>D.O. VOL.(L)</th>
                                                        <th>DELIVERY VOL. (L)</th>
                                                        <th>END STOCK</th>
                                                        <th>SALES VOL.(L)</th>
                                                        <th>DIFF. (L)</th>
                                                        <th>DIFF. (%)</th>
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
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <?php foreach($data_supply as $i => $row) { ?>
                                                    <tr>
                                                        <td><?= $i + 1 ?></td>
                                                        <td><?= date('Y', strtotime($row['Tgl_Pengisian'])) ?></td>
                                                        <td><?= date('m', strtotime($row['Tgl_Pengisian'])) ?></td>
                                                        <td><?= $row['DO_Number'] ?></td>
                                                        <td><?= $row['Nokendaraan'] ?></td>
                                                        <td><?= $row['Tgl_Pengisian'] ?></td>
                                                        <td><?= date('H:i:s', strtotime($row['Jam'])) ?></td>
                                                        <td><?= $row['Nm_Driver'] ?></td>
                                                        <td><?= $row['Supplier'] ?></td>
                                                        <td><?= $row['Tank'] ?></td>
                                                        <td><?= $row['Supply_Point_Name'] ?></td>
                                                        <td><?= $row['No_SPBB'] ?></td>
                                                        <td><?= $row['Grade'] ?></td>
                                                        <td><?= $row['Stock_Awal'] ?></td>
                                                        <td><?= $row['DO_Vol'] ?></td>
                                                        <td><?= $row['Delivery_Vol'] ?></td>
                                                        <td><?= $row['Stock_Akhir'] ?></td>
                                                        <td><?= $row['Sales_Vol'] ?></td>
                                                        <td><?= $row['Diff'] ?></td>
                                                        <td><?= $row['Diff_Percent'] ?></td>
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



