
<style>
	.custom-header {
		background: linear-gradient(135deg, #f6f9fc, #eef1f7);
		padding: 15px 20px;
		border-radius: 12px;
		margin-bottom: 15px;
		box-shadow: 0 4px 10px rgba(0,0,0,0.05);
	}

	.header-title {
		font-size: 20px;
		font-weight: 600;
		color: #344767;
		margin: 0;
	}

	.btn-back {
		background: linear-gradient(135deg, #f6c23e, #dda20a);
		border: none;
		color: #fff;
		border-radius: 8px;
		padding: 8px 16px;
		transition: all 0.3s ease;
	}

	.btn-back:hover {
		transform: translateY(-2px);
		box-shadow: 0 6px 15px rgba(221, 162, 10, 0.4);
		color: #fff;
	}


	/* ================================ */

	.card-custom {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    overflow: hidden;
}

.card-header-custom {
    background: linear-gradient(135deg, #4e73df, #224abe);
    color: #fff;
    padding: 15px 20px;
}

.card-header-custom h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
}

.btn-tool {
    background: transparent;
    border: none;
    color: #fff;
    margin-left: 5px;
    cursor: pointer;
}

.card-body-custom {
    padding: 20px;
}

/* Input modern */
.input-modern {
    border-radius: 8px;
    border: 1px solid #d1d3e2;
    transition: all 0.3s;
}

.input-modern:focus {
    border-color: #4e73df;
    box-shadow: 0 0 8px rgba(78, 115, 223, 0.2);
}

/* Button */
.btn-save {
    background: linear-gradient(135deg, #1cc88a, #17a673);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 8px 18px;
    margin-right: 5px;
    transition: 0.3s;
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(28, 200, 138, 0.4);
}

.btn-cancel {
    background: #858796;
    color: #fff;
    border-radius: 8px;
    padding: 8px 18px;
    border: none;
    transition: 0.3s;
}

.btn-cancel:hover {
    background: #6c757d;
}

/* =============================================================== */
/* MODAL BASE */
.modal-modern .modal-content {
    border: none;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.2);
}

/* HEADER */
.modern-modal-header {
    background: linear-gradient(135deg, #4e73df, #224abe);
    color: #fff;
    border-bottom: none;
}

.btn-close-modern {
    color: #fff;
    font-size: 22px;
    opacity: 0.8;
}

.btn-close-modern:hover {
    opacity: 1;
}

/* BODY */
.modal-body {
    padding: 25px;
}

/* FLOATING INPUT */
.floating-group {
    position: relative;
    margin-bottom: 25px;
}

.floating-group input {
    width: 100%;
    border: none;
    border-bottom: 2px solid #ccc;
    padding: 10px 5px;
    outline: none;
    background: transparent;
}

.floating-group label {
    position: absolute;
    top: 10px;
    left: 5px;
    color: #888;
    transition: 0.3s;
}

.floating-group input:focus + label,
.floating-group input:valid + label {
    top: -10px;
    font-size: 12px;
    color: #4e73df;
}

/* FOOTER */
.modern-footer {
    border-top: none;
    padding: 15px 25px;
}

/* BUTTON */
.btn-save-modern {
    background: linear-gradient(135deg, #1cc88a, #17a673);
    border: none;
    padding: 8px 18px;
    border-radius: 20px;
    color: #fff;
    transition: 0.3s;
}

.btn-save-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(28,200,138,0.4);
}

.btn-cancel-modern {
    background: #858796;
    border: none;
    padding: 8px 18px;
    border-radius: 20px;
    color: #fff;
}

/* ANIMASI MODAL */
.modal.fade .modal-dialog {
    transform: translateY(-30px);
    transition: all 0.3s ease;
}

.modal.show .modal-dialog {
    transform: translateY(0);
}


</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<!-- <label class="col-lg col-form-label"> </label> -->
			 <h1 class="header-title">
				<i class="fa fa-database mr-2"></i> <?php echo $judul ?>
			</h1>
			<p></p>
			<!-- <label for="inputName" type="input" name="JobNo" class="col-sm col-form-label" id="JobNo"><?php echo $invoice->JobNo ?></label> - <label for="inputName" name="JobNm" class="col-sm col-form-label" id="JobNo"><?php echo $invoice->JobNm ?></label>
            <br><small><label for="inputName" class="col-sm col-form-label" id="JobNo"><?php echo $invoice->Lokasi ?></label> -->

		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $judul ?></li>
		</ol>
	</section>

	<section class="content-header custom-header d-flex justify-content-between align-items-center">  
		<!-- <a href="<?= site_url('Ist/Basic_Data/Basic_Data') ?>" class="btn btn-back"><i class="fa fa-arrow-left mr-2"></i> Kembali</a> -->
		<button class="btn btn-back" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus mr-2"></i> Tambah SPBB</button>
	</section>

	<!-- <Section class="content"> -->
	<section class="content-header">
		<div class="row" id="MOD-IN">
			<div class="col-md-12">

				<div class="card-custom">

					<!-- HEADER -->
					<div class="card-header-custom d-flex justify-content-between align-items-center">
						<h3><i class="fa fa-edit mr-2"></i> List Data SPBB</h3>
					</div>

					<!-- BODY -->
					<div class="card-body-custom">
						<table class="table table-bordered table-striped">
							
								<thead>
									<tr>
										<th>No</th>
										<th>No SPBB</th>
										<th>Area / Lokasi</th>
										<th>PIC</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<thead>
									<tr>
										<th></th>
										<th><input type="text" name="no_spbb" id="no_spbb" class="form-control"></th>
										<th><input type="text" name="area" id="area" class="form-control"></th>
										<th><input type="text" name="pic" id="pic" class="form-control"></th>
										<th><input type="text" name="status" id="status" class="form-control"></th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($data_spbb as $index => $spbb) : ?>
										<tr>
											<td><?= $index + 1 ?></td>
											<td><?= $spbb->No_SPBB ?></td>
											<td><?= $spbb->Area ?></td>
											<td><?= $spbb->Pic ?></td>
											<td><?= $spbb->Status ?></td>
											<td><button class="btn btn-sm btn-primary">Edit</button></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
						</table>
					</div>

				</div>

			</div>
		</div>
	</section>
</div>


<!-- Modal -->
<div class="modal fade modal-modern" id="exampleModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- HEADER -->
      <div class="modal-header modern-modal-header">
        <h5 class="modal-title">
          <i class="fa fa-plus-circle mr-2"></i> Tambah Data SPBB
        </h5>
        <button type="button" class="close btn-close-modern" data-dismiss="modal">
          &times;
        </button>
      </div>

      <!-- BODY -->
      <div class="modal-body">
		<form action="<?php echo site_url('Ist/Basic_Data/DataSPBB/add	') ?>" method="post">

        <div class="floating-group">
          <input type="text" name="no_spbb" required>
          <label>No SPBB</label>
        </div>

        <div class="floating-group">
          <input type="text" name="area" required>
          <label>Area / Lokasi</label>
        </div>

        <div class="floating-group">
          <input type="text" name="pic" required>
          <label>PIC</label>
        </div>

      </div>

      <!-- FOOTER -->
      <div class="modal-footer modern-footer">
        <button type="button" class="btn-cancel-modern" data-dismiss="modal">
          <i class="fa fa-times mr-1"></i> Batal
        </button>

        <button type="submit" class="btn-save-modern">
          <i class="fa fa-check mr-1"></i> Simpan
        </button>
      </div>

	  </form>

    </div>
  </div>
</div>
								
