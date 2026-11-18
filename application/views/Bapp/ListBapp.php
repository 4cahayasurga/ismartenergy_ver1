<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $title ?> </label>
			<p></p>
			<label for="inputName" type="input" name="JobNo" class="col-sm col-form-label" id="JobNo"><?php echo $dipa->JobNo ?></label> - <label for="inputName" name="JobNm" class="col-sm col-form-label" id="JobNo"><?php echo $dipa->JobNm ?></label>
			<br><small><label for="inputName" class="col-sm col-form-label" id="JobNo"><?php echo $dipa->Lokasi ?></label>

		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $title ?></li>
		</ol>
	</section>

	<section class="content-header">
		<a href="<?= site_url('job/sub_job/' . $dipa->JobNo) ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
		<a href="<?= site_url('Bapp/menutambah/' . $dipa->JobNo) ?>" data-toggle="modal" class="btn btn-primary"><i class="fa fa-check"></i> Tambah Data</a>
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
								<table class="table table-hover" border="1" cellspacing="2" width="100%">
									<thead>
										<tr>
											<th rowspan="2">Tgl BAP</th>
											<th rowspan="2">No BAP</th>
											<th rowspan="2">Termin Ke</th>
											<th colspan="5">
												<center>Nilai Pekerjaan </center>
											</th>
											<th colspan="5">
												<center>Potongan - Potongan </center>
											</th>

										</tr>
										<tr>
											<th>% Saat Ini </th>
											<th>Nilai Pekerjaan Saat ini </th>
											<th>% Sebelum nya </th>
											<th>Nilai Pekerjaan Sebelumnya</th>
											<th>Hasil BAP</th>

											<th>% Retensi</th>
											<th>Nilai Retensi</th>
											<th>% Ansuran UM</th>
											<th>Nilai Potongan UM</th>
											<th>Jumlah Potongan</th>

											<th rowspan="2">Total Pemb BAP</th>
											<th rowspan="2">PPn</th>
											<th rowspan="2">DPP</th>
											<th rowspan="2">PPH</th>
											<th rowspan="2">NETTO</th>

											<th rowspan="2">#</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($listBAPP as $lb) : ?>
											<tr>
												<td><?= $lb->TglBAPT; ?></td>
												<td><?= $lb->NoBAPT; ?></td>
												<td><?= $lb->TerminKe; ?></td>
												<td><?= $lb->PersenSekarang; ?></td>
												<td><?= number_format($lb->NilaiPekSekarang); ?></td>
												<td><?= $lb->persenSebelumnya; ?></td>
												<td><?= number_format($lb->NilaiSebelumnya); ?></td>
												<td><?= number_format($lb->JmlBAP); ?></td>
												<td><?= $lb->PersenRetensi; ?></td>
												<td><?= number_format($lb->NilaiRetensi); ?></td>
												<td><?= $lb->PersenUM; ?></td>
												<td><?= number_format($lb->NilaiPotUM); ?></td>
												<td><?= number_format($lb->JmlPotongan); ?></td>
												<td><?= number_format($lb->TotalBAP); ?></td>
												<td><?= number_format($lb->PPN); ?></td>
												<td><?= number_format($lb->DPP); ?></td>
												<td><?= number_format($lb->PPH); ?></td>
												<td><?= number_format($lb->Netto); ?></td>
												<td>
													<a href="<?= site_url('Bapp/EditBAPT/' . $lb->LedgerNo) ?>" class="badge btn-success badge btn-xs" title="SELECT"><i class="fa fa-pencil"></i></a>
													<a href="<?php echo site_url("Bapp/hapusBAP/" . $lb->LedgerNo. '/' .$lb->JobNo); ?>" onclick="return confirm('Hapus data?');" type="submit" class="badge bg-orange-active btn-danger btn-xs"><i class="fa fa-trash"></i></a>
													
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
	</section>



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
