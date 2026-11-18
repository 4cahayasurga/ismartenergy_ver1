<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
			<p></p>
			<!-- <label for="inputName" type="input" name="JobNo" class="col-sm col-form-label" id="JobNo"><?php echo $mos->JobNo ?></label> - <label for="inputName" name="JobNm" class="col-sm col-form-label" id="JobNo"><?php echo $mos->JobNm ?></label>
				<br><small><label for="inputName" class="col-sm col-form-label" id="JobNo"><?php echo $mos->Lokasi ?></label> -->

		</h1>
		<ol class="breadcrumb">
			<!-- <li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
				<li class="active"><?php echo $judul ?></li> -->
		</ol>
	</section>

	<section class="content-header">
		<a href="#" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
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
								<div class="form-group row">
									<label for="" class="col-sm-2 col-form-label">Rekening</label>
									<div class="col-sm-6">
										<select name="Rekening" id="Rekening" class="form-control">
											<option value="">-- Pilih Rekening --</option>
											<?php
											foreach ($rek as $row) { ?>
												<option value="<?= $row->RekId ?>" data-norek="<?= $row->NoRek ?>"><?= $row->RekId ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label for="" class="col-sm-2 col-form-label">Periode</label>
									<div class="col-sm-3">
										<input type="date" name="Periode1" id="Periode1" class="form-control" value="<?=$tgl_skrg?>">
									</div>
									<div class="col-sm-3">
										<input type="date" name="Periode2" id="Periode2" class="form-control" value="<?=$tgl_kemarin?>">
									</div>
								</div>
								<!-- <div class="form-group row">
									<label for="" class="col-sm-2 col-form-label"></label>
									<div class="col-sm-3">
										<button type="submit" class="btn btn-warning"><i class="fa fa-eye-slash"></i>&nbsp Preview</button>
									</div>
								</div> -->
								<div id="tampil"></div>

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

	<script>
		function ShowTgl() {
			var NoRek = $('#Rekening').val();
			if (NoRek == '') {
				$('#Periode1').attr('readonly', true);
				$('#Periode2').attr('readonly', true);
			} else {
				$('#Periode1').attr('readonly', false);
				$('#Periode2').attr('readonly', false);
			}
		}

		function changeTgl(RekId, Periode1, Periode2) {
			if (Periode1 == '' && Periode2 == '') {
				return false;
			}

			$.ajax({
				type: "POST",
				url: "<?= site_url('Report/GetTableRek') ?>",
				data: {
					"RekId": RekId,
					"Periode1": Periode1,
					"Periode2": Periode2
				},
				dataType: "HTML",
				success: function(response) {
					$('#tampil').html(response);
				}
			});

		}

		$(function() {

			ShowTgl()




			$('#Rekening').select2({
				width: '100%'
			})

			$('#Rekening').change(function() {
				var NoRek = $('option:selected', this).attr('data-norek');
				var Periode1 = $('#Periode1').val();
				var Periode2 = $('#Periode2').val();

				// alert(NoRek);

				$.ajax({
					type: "POST",
					url: "<?= site_url('Report/GetTableRek') ?>",
					data: {
						"RekId": this.value,
						"NoRek": NoRek,
						"Periode1": Periode1,
						"Periode2": Periode2
					},
					dataType: "HTML",
					success: function(response) {
						ShowTgl();
						$('#tampil').html(response);
					}
				});
			})

			$('#Periode1,#Periode2').on('change', function() {
				var NoRek = $('#Rekening').val();
				var Periode1 = $('#Periode1').val();
				var Periode2 = $('#Periode2').val();
				changeTgl(NoRek, Periode1, Periode2);
			})






			// $('#Periode1').change(function() {



			// 	var NoRek = $('#Rekening option:selected').attr('data-NoRek');
			// 	var Periode1 = $('#Periode1').val();
			// 	var Periode2 = $('#Periode2').val();
			// 	// alert(Periode1 + ' - ' + Periode2);
			// 	// alert(NoRek);
			// 	$.ajax({
			// 		type: "POST",
			// 		url: "<?= site_url('Report/GetTableRek') ?>",
			// 		data: {
			// 			"RekId": this.value,
			// 			"NoRek": NoRek,
			// 			"Periode1": Periode1,
			// 			"Periode2": Periode2
			// 		},
			// 		dataType: "HTML",
			// 		success: function(response) {
			// 			// console.log(response)
			// 			$('#tampil').html(response);
			// 		}
			// 	});
			// })

			// $('#Periode2').change(function() {


			// 	var NoRek = $('#Rekening option:selected').attr('data-NoRek');
			// 	var Periode1 = $('#Periode1').val();
			// 	var Periode2 = $(this).val();
			// 	// alert(Periode1 + ' - ' + Periode2);
			// 	// alert(NoRek);
			// 	$.ajax({
			// 		type: "POST",
			// 		url: "<?= site_url('Report/GetTableRek') ?>",
			// 		data: {
			// 			"RekId": this.value,
			// 			"NoRek": NoRek,
			// 			"Periode1": Periode1,
			// 			"Periode2": Periode2
			// 		},
			// 		dataType: "HTML",
			// 		success: function(response) {
			// 			// console.log(response)
			// 			$('#tampil').html(response);
			// 		}
			// 	});
			// })


		})
	</script>
</div>


<!-- /.container-fluid -->
</section>

</section>



<!-- </Section> -->