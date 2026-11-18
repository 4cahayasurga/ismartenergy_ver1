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
		<a href="<?= site_url('Report/MenuRptOperasi') ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
	</section>

	<!-- <Section class="content"> -->
	<section class="content-header">
		<div class="row" id="MOD-IN">

			<div class="col-lg-12 connectedSortable">
				<form id="myForm" action="" method="post">
					<div class="box box-warning">
						<div class="box-header box-solid bg-blue-gradient">
							<h3 class="box-title">Monitoring Termin</h3>
							<div class="pull-right box-tools">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
								</button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
								</button>
							</div>
						</div>
						<div class="box-body">
							<label for="">Status Job:</label>
							<div class="input-group col-sm-6">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<select name="" class="form-control" id="statusJob">
									<option value="">--Pilih--</option>
									<?php foreach ($sj as $s) : ?>
										<option><?= $s->StatusJob ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="box-body">
							<label for="">Pilih Periode:</label>
							<div class="row">
								<div class="col-sm-3">
									<label for="">Dari</label>
									<div class="input-group ">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input type="date" class="form-control pull-right" value="" id="startDate">
									</div>
								</div>
								<div class="col-sm-3">
									<label for="">Sampai</label>
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input type="date" class="form-control pull-right" value="" id="endDate">
									</div>
								</div>
							</div>
						</div>
						<div class="box-body">
							<button type="button" class="btn bg-blue-gradient" id="btnSubmit">Tampilkan</button>
						</div>
					</div>
				</form>
			</div>


			<div class="col-md-12" id="boxExport" style="margin-bottom: 20px;" hidden>
				<a href="<?= base_url('report/exportExcel') ?>" class="btn bg-green-gradient" id="btnExport">Export To Excel <i class="fa fa-excel"></i></a>
			</div>

			<div class="col-md-12 connectedSortable" id="tblResult" hidden>
				<div class="box box-warning">
					<div class="box-header box-solid bg-blue-gradient">
						<h3 class="box-title">Tipe Manajerial : JO Integrated</h3>
						<div class="pull-right box-tools">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
							</button>
						</div>
					</div>
					<div class="box-body">
						<table class="table table-bordered table-striped " id="tblJOIntegrated">
							<thead>
								<tr>
									<th>No</th>
									<th>JobNo</th>
									<th>Job Nama</th>
									<th>Tgl Termin</th>
									<th>Netto</th>
									<th>DPP</th>
									<th>PPN</th>
									<th>PPH</th>
									<th>Nilai Bruto</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="col-md-12 connectedSortable" id="tblResult2" hidden>
				<div class="box box-warning">
					<div class="box box-solid">
						<div class="box-header bg-blue-gradient">
							<h3 class="box-title">Tipe Manajerial : JO Split</h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
							</div>
						</div>
						<div class="box-body">
							<table class="table table-bordered table-striped " id="tblJOSplit">
								<thead>
									<tr>
										<th>No</th>
										<th>JobNo</th>
										<th>Job Nama</th>
										<th>Tgl Termin</th>
										<th>Netto</th>
										<th>DPP</th>
										<th>PPN</th>
										<th>PPH</th>
										<th>Nilai Bruto</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-12 connectedSortable" id="tblResult3" hidden>
				<div class="box box-warning">
					<div class="box box-solid">
						<div class="box-header bg-blue-gradient">
							<h3 class="box-title">Tipe Manajerial : JO Single</h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
							</div>
						</div>
						<div class="box-body">
							<table class="table table-bordered table-striped " id="tblJOSingle">
								<thead>
									<tr>
										<th>No</th>
										<th>JobNo</th>
										<th>Job Nama</th>
										<th>Tgl Termin</th>
										<th>Netto</th>
										<th>DPP</th>
										<th>PPN</th>
										<th>PPH</th>
										<th>Nilai Bruto</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-12 connectedSortable" id="tblResult4" hidden>
				<div class="box box-warning">
					<div class="box box-solid">
						<div class="box-header bg-blue-gradient">
							<h3 class="box-title">Tipe Manajerial : JO Partial</h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
							</div>
						</div>
						<div class="box-body">
							<table class="table table-bordered table-striped " id="tblJOPartial">
								<thead>
									<tr>
										<th>No</th>
										<th>JobNo</th>
										<th>Job Nama</th>
										<th>Tgl Termin</th>
										<th>Netto</th>
										<th>DPP</th>
										<th>PPN</th>
										<th>PPH</th>
										<th>Nilai Bruto</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
	</section>

	<script>
		$(document).ready(function() {
			$('#tblResult').hide()
			$('#tblResult2').hide()
			$('#tblResult3').hide()
			$('#tblResult4').hide()
			$('#boxExport').hide()

			$('#statusJob').change(function() {
				$('#tblResult').hide()
				$('#tblJOIntegrated tbody').empty()
				$('#tblResult2').hide()
				$('#tblSplit tbody').empty()
				$('#tblResult3').hide()
				$('#tblSingle tbody').empty()
				$('#tblResult4').hide()
				$('#tblPartial tbody').empty()
				$('#boxExport').hide()
			})

			$('#btnSubmit').click(function(e) {
				e.preventDefault()
				var StatusJob = $('#statusJob').val()
				var startDate = $('#startDate').val()
				var endDate = $('#endDate').val()
				if (StatusJob == '' || startDate == '' || endDate == '') {
					Swal.fire('Info!', 'silahkan lengkapi data terlebih dahulu', 'info');
				} else {
					$.ajax({
						method: 'POST',
						url: '<?= base_url('report/getDataTermin') ?>',
						data: {
							startDate,
							endDate,
							StatusJob
						},
						beforeSend: function() {
							$('#tblResult').hide()
							$('#tblResult2').hide()
							$('#tblResult3').hide()
							$('#tblResult4').hide()
							$('#boxExport').hide()
						},
						success: function(result) {
							const res = JSON.parse(result);
							renderTable(res.JOIntegrated, '#tblJOIntegrated', res.SumJOIntegrated);
							renderTable(res.JOSplit, '#tblJOSplit', res.SumJOSplit);
							renderTable(res.JOSingle, '#tblJOSingle', res.SumJOSingle);
							renderTable(res.JOPartial, '#tblJOPartial', res.SumJOPartial);
							$('#boxExport').show()
							$('#tblResult').show()
							$('#tblResult2').show()
							$('#tblResult3').show()
							$('#tblResult4').show()
						}
					})

				}

			})
		})

		var noUrut = 1

		function renderTable(data, tableId, dataSum) {
			var prevJobNo = null;
			var totalData = data.length
			$(`${tableId} tbody`).empty();
			$.each(data, function(index, item) {
				$(`${tableId} tbody`).append(`
					<tr>
						<td>${noUrut}</td>    
						<td>${item.JobNo}</td>
						<td>${item.JobNm}</td>
						<td>${item.TglTermin}</td>
						<td>${formatAngka(item.Netto)}</td>
						<td>${formatAngka(item.DPP)}</td>
						<td>${formatAngka(item.PPN)}</td>
						<td>${formatAngka(item.PPH)}</td>
						<td>${formatAngka(item.NilaiBruto)}</td>
					</tr>
				`);
				$.each(dataSum, function(index2, item2) {
					if (item.JobNo == item2.JobNo) {
						if (noUrut > item2.JmlJobNo - 1) {
							$(`${tableId} tbody`).append(`
								<tr style="background-color:#e0e0e0; font-weight: bold; border-left: 2px solid #000; border-right: 2px solid #000; border-bottom: 2px solid #000;">
									<td colspan="8" class="text-center">TOTAL</td>
									<td>${formatAngka(item2.Sum)}</td>
								</tr>
							`);
							noUrut = 0
						}
						noUrut += 1
					}
				})
			});
		}

		function formatAngka(nilai) {
			if (nilai <= 0) {
				return 0
			} else {
				var convert = parseInt(nilai)
				var res = convert.toFixed(0)
				return formatRupiah(res)
			}
		}

		function formatRupiah(angka) {
			if (isNaN(angka)) {
				return 'invalid number'
			}
			const formattedNumber = angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
			return `Rp. ${formattedNumber}`
		}

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