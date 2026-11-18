<style>
/* Header table gradient + 3D */
.table-header-3d {
    background: linear-gradient(145deg, #2196F3, #0d6efd);
    color: white !important;
    font-weight: bold;
    box-shadow: inset 0 -3px 5px rgba(0,0,0,0.2);
}

/* Baris filter input */
.table-filter-row input {
    background: #f9fbff;
    border: 1px solid #cfd8dc;
    box-shadow: inset 0 2px 3px rgba(0,0,0,0.1);
    transition: 0.2s;
}
.table-filter-row input:focus {
    border-color: #2196F3;
    box-shadow: 0 0 6px rgba(33,150,243,0.5);
}

/* Efek 3D pada tubuh tabel */
.table tbody tr {
    background: #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
.table tbody tr:hover {
    background: #e3f2fd;
}

/* Border lebih modern */
.table {
    border-radius: 10px;
    overflow: hidden;
}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
			<!-- <p></p>
			<label for="inputName" type="input" name="JobNo" class="col-sm col-form-label" id="JobNo"><?php echo $invoice->JobNo ?></label> - <label for="inputName" name="JobNm" class="col-sm col-form-label" id="JobNo"><?php echo $invoice->JobNm ?></label>
            <br><small><label for="inputName" class="col-sm col-form-label" id="JobNo"><?php echo $invoice->Lokasi ?></label> -->

		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $judul ?></li>
		</ol>
	</section>

	<section class="content-header">
		<a href="#" class="btn btn-warning"><i class="fa fa-plus"></i> Entries Data</a>
		<!--a href="#save" data-toggle="modal" class="btn btn-primary"><i class="fa fa-file-excel-o"></i> Import Excel</a-->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Import Excel</button>
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
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                            <table id="tableSalesRealization" class="table table-bordered table-striped table-responsive">
                                                <thead class="table-header-3d">
                                                    <tr>
                                                        <th>No</th>
                                                        <th>#</th>
                                                        <th>Material</th>
                                                        <th>Calender day</th>
                                                        <th>TBBM</th>
                                                        <th>Regional</th>
                                                        <th>Sales District</th>
                                                        <th>Nomor SPBU</th>
                                                        <th>Customer Group</th>
                                                        <th>Kuota</th>
                                                        <th>Volume</th>
                                                    </tr>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th><input type="text" name="" id="" class="form-control"></th>
                                                        <th><input type="text" name="" id="" class="form-control"></th>
                                                        <th><input type="text" name="" id="" class="form-control"></th>
                                                        <th><input type="text" name="" id="" class="form-control"></th>
                                                        <th><input type="text" name="" id="" class="form-control"></th>
                                                        <th><input type="text" name="" id="" class="form-control"></th>
                                                        <th><input type="text" name="" id="" class="form-control"></th>
                                                        <th><input type="text" name="" id="" class="form-control"></th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $no = 1;
                                                    foreach ($sales_data as $data) {
                                                        echo "<tr>";
                                                        echo "<td>" . $no++ . "</td>";
                                                        echo '<td>
                                                                <button 
                                                                    type="button" 
                                                                    class="btn btn-primary btn-sm btn-edit"
                                                                    data-ledger="'.$data->LedgerNo.'"
                                                                    data-material="'.$data->Material.'"
                                                                    data-date="'.$data->TransactionDate.'"
                                                                    data-tbbm="'.$data->TBBM.'"
                                                                    data-regional="'.$data->Regional.'"
                                                                    data-district="'.$data->SalesDistrict.'"
                                                                    data-spbu="'.$data->NoSPBU.'"
                                                                    data-customer="'.$data->CustomerGroup.'"
                                                                    data-kuota="'.$data->Kuota.'"
                                                                    data-volume="'.$data->Volume.'"
                                                                >
                                                                    Edit
                                                                </button>
                                                            </td>';

                                                        echo "<td>" . $data->Material . "</td>";
                                                        echo "<td>" . $data->TransactionDate . "</td>";
                                                        echo "<td>" . $data->TBBM . "</td>";
                                                        echo "<td>" . $data->Regional . "</td>";
                                                        echo "<td>" . $data->SalesDistrict . "</td>";
                                                        echo "<td>" . $data->NoSPBU . "</td>";
                                                        echo "<td>" . $data->CustomerGroup . "</td>";
                                                        echo "<td>" . $data->Kuota . "</td>";
                                                        echo "<td>" . $data->Volume . "</td>";
                                                        echo "</tr>";
                                                    }
                                                    ?>
                                                </tbody>

                                            </table>

                                            <div class="modal fade" id="modalEdit" tabindex="-1">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <form action="<?= site_url('Ist/Report/update') ?>" method="post">
                                                            
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Edit Sales Realization</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>

                                                            <div class="modal-body">

                                                                <input type="hidden" name="LedgerNo" id="LedgerNo">

                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label>Material</label>
                                                                        <input type="text" name="Material" id="Material" class="form-control" required>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <label>Transaction Date</label>
                                                                        <input type="date" name="TransactionDate" id="TransactionDate" class="form-control" required>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <label>TBBM</label>
                                                                        <input type="text" name="TBBM" id="TBBM" class="form-control">
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <label>Regional</label>
                                                                        <input type="text" name="Regional" id="Regional" class="form-control">
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <label>Sales District</label>
                                                                        <input type="text" name="SalesDistrict" id="SalesDistrict" class="form-control">
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <label>No SPBU</label>
                                                                        <input type="text" name="NoSPBU" id="NoSPBU" class="form-control">
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <label>Customer Group</label>
                                                                        <input type="text" name="CustomerGroup" id="CustomerGroup" class="form-control">
                                                                    </div>

                                                                    <div class="col-md-3">
                                                                        <label>Kuota</label>
                                                                        <input type="number" name="Kuota" id="Kuota" class="form-control">
                                                                    </div>

                                                                    <div class="col-md-3">
                                                                        <label>Volume</label>
                                                                        <input type="number" name="Volume" id="Volume" class="form-control">
                                                                    </div>
                                                                </div>

                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-success">Update</button>
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                                            </div>

                                                        </form>
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
                </div>
            </div>
        </div>

    </section>
</div>

    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Import Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo site_url('Ist/Report/import'); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="file">Pilih file Excel</label>
                            <input type="file" class="form-control" name="file" id="file" required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>                                      
            </div>
            
        </div>
    </div>



<script>

    $(document).on('click', '.btn-edit', function () {

    $('#LedgerNo').val($(this).data('ledger'));
    $('#Material').val($(this).data('material'));
    $('#TransactionDate').val($(this).data('date'));
    $('#TBBM').val($(this).data('tbbm'));
    $('#Regional').val($(this).data('regional'));
    $('#SalesDistrict').val($(this).data('district'));
    $('#NoSPBU').val($(this).data('spbu'));
    $('#CustomerGroup').val($(this).data('customer'));
    $('#Kuota').val($(this).data('kuota'));
    $('#Volume').val($(this).data('volume'));

    $('#modalEdit').modal('show');
});



     $(document).ready(function() {
           var table = $('#tableSalesRealization').DataTable({
        "paging": true,
        "lengthChange": true,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "columnDefs": [
            { "type": "date", "targets": 4 } // Pastikan index kolom periode sesuai
        ],

        dom: 'lBfrtip',
        buttons: ['copyHtml5', 'excelHtml5'
        //  'pdfHtml5', 'print'
        ]
        });
        } );
</script>


