
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
		<a href="<?= site_url('Ist/Basic_Data/Basic_Data') ?>" 
		class="btn btn-back">
			<i class="fa fa-arrow-left mr-2"></i> Kembali
		</a>

	</section>

	<!-- <Section class="content"> -->
	<section class="content-header">
		<div class="row" id="MOD-IN">
			<div class="col-md-12">

				<div class="card-custom">

					<!-- HEADER -->
					<div class="card-header-custom d-flex justify-content-between align-items-center">
						<h3><i class="fa fa-edit mr-2"></i> Input Data Quota SPBB</h3>
						

					</div>

					<!-- BODY -->
					<div class="card-body-custom col-md-6">
						<?php
							$quota_edit = isset($quota_edit) ? $quota_edit : NULL;
							$tgl_sk_value = ($quota_edit && $quota_edit->Tgl_SK) ? date('Y-m-d', strtotime($quota_edit->Tgl_SK)) : '';
							$tahun_value = ($quota_edit && $quota_edit->ThnAnggaran) ? date('Y', strtotime($quota_edit->ThnAnggaran)) : '';
						?>
						<form action="<?php echo $form_action ?>" method="post">
							<div class="form-group">
								<label>No SPBB</label>
								<select name="no_spbb" id="" class="form-control">
									<option value="NoSPBB">Pilih No SPBB</option>
										<?php foreach ($no_spbb as $spbb) : ?>
											<option value="<?php echo $spbb->No_SPBB; ?>" <?= ($quota_edit && $quota_edit->No_SPBB == $spbb->No_SPBB) ? 'selected' : '' ?>><?php echo $spbb->No_SPBB; ?></option>
										<?php endforeach; ?>
									<!-- Options will be populated dynamically -->
								</select>
							</div>

							<div class="form-group">
								<label>Lokasi</label>
								<input type="text" name="Area" class="form-control input-modern" placeholder="Masukkan Lokasi" value="<?= $quota_edit ? $quota_edit->Area : '' ?>">
							</div>
							<div class="form-group">
								<label>No SK Pertamina</label>
								<input type="text" name="No_SK_Pertamina" class="form-control input-modern" placeholder="Masukkan No SK Pertamina" value="<?= $quota_edit ? $quota_edit->No_SK : '' ?>">
							</div>
							<div class="form-group">
								<label>Tanggal SK</label>
								<input type="date" name="Tanggal_SK" class="form-control input-modern" value="<?= $tgl_sk_value ?>">
							</div>
							<div class="form-group">
								<label>Tahun Anggaran</label>
								<input type="number" name="Tahun_Anggaran" class="form-control input-modern" placeholder="Masukkan Tahun" value="<?= $tahun_value ?>">
							</div>
							<div class="form-group">
								<label>Volume Quota</label>
								<input type="number" name="Volume_Quota" class="form-control input-modern" placeholder="Masukkan Volume Quota" value="<?= $quota_edit ? $quota_edit->Vol_Quota : '' ?>">
							</div>

							<div class="text-right">
								<button type="submit" class="btn btn-save">
									<i class="fa fa-check mr-2"></i> Simpan
								</button>

								<button type="reset" class="btn btn-cancel">
									<i class="fa fa-times mr-2"></i> Batal
								</button>
							</div>
						</form>
					</div>

					<div class="card-body-custom col-md-6">
						<!-- Placeholder for additional content or instructions -->
						<div class="p-3 bg-blend-color border rounded">
							<h4 class="mb-3"><strong>Petunjuk Pengisian</strong></h4>
							<ul>
								<li>Pilih No SPBB yang sesuai dengan data yang akan diinput.</li>
								<li>Pastikan semua field terisi dengan benar sebelum menyimpan.</li>
								<li>Gunakan format tanggal yang benar untuk Tanggal SK.</li>
								<li>Volume Quota harus diisi dengan angka tanpa tanda baca.</li>
							</ul>
						</div>
					</div>

				</div>

			</div>
		</div>
	</section>
</div>
								
