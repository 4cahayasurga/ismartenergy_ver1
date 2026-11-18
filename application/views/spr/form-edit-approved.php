<div class="content-wrapper">

	<section class="content-header">
		<h1>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
			<p></p>
			<!-- <label class="col-sm col-form-label"><?php echo $appspr->JobNo ?></label> - <label><?php echo $appspr->JobNm ?></label>
            <br><small><label class="col-sm col-form-label"><?php echo $appspr->Lokasi ?></label> -->

		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $judul ?></li>
		</ol>
	</section>

	<section class="content-header">

		<a href="<?= site_url('Job/ApproveSpr/' . $detailspr->JobNo) ?>" class="btn btn-warning btn-xs"><i class="fa fa-arrow-left"></i> Kembali</a>
		<!-- <a href="#save" data-toggle="modal" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</a> -->

	</section>

	<section class="content">
		<div class="box">
			<div class="box-body">

				<form id="myform">

					<div class="col-xs-12">

						<div class="box">
							<div class="box-header bg-purple-gradient">
								<h6>INFORMASI DASAR</h6>
							</div>
							<div class="box">

								<div class="col-xs-6 row">
									<label>Job No</label>
									<input type="text" class="form-control form-control-sm" value="<?php echo $detailspr->JobNo ?>" disabled>
									<label>No SPR</label>
									<input type="text" name="NoSpr" id="NoSpr" value="<?= $detailspr->NoSPR ?>" class="form-control form-control-sm" disabled>
									<label>Tgl SPR</label>
									<input type="date" value="<?= $detailspr->TglSPR ?>" name="TglSpr" id="TglSpr" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>" required="required">
								</div>

								<div class="col-xs-6">
									<label>Kepada</label>
									<input type="text" name="Kepada" id="Kepada" class="form-control form-control-sm" value="<?= $detailspr->Kepada ?>" required="required">
									<label>Tgl Penggunaan</label>
									<input type="Date" name="TglPenggunaan" id="TglPenggunaan form-control-sm" value="<?= $detailspr->TglPenggunaan ?>" class="form-control" required="required">
									<label>Untuk Pekerjaan</label>
									<textarea class="form-control form-control-sm" name="UtkPekerjaan" id="UtkPekerjaan "><?= $detailspr->UtkPekerjaan ?></textarea>
								</div>

							</div>
						</div>
					</div>

					<div class="col-xs-12">
						<div class="box">
							<div class="box-header bg-blue-gradient">
								<!-- <h6 class="box-title" style="text-align: center;">Informasi Dasar</h6> -->
								<h6>INFORMASI PENGIRIMAN</h6>
							</div>
							<div class="box">

								<div class="col-xs-6 row">
									<label>Nama PIC Logistik</label>
									<input type="text" name="NamaKirim" id="NamaKirim" class="form-control form-control-sm" value="<?= $detailspr->NamaKirim ?>">
									<label>Telepon / HP</label>
									<input type="text" name="TeleponKirim" id="TeleponKirim" class="form-control form-control-sm" value="<?= $detailspr->TeleponKirim ?>" required="required">
								</div>


								<div class="col-xs-6">
									<label>Alamat Dikirim</label>
									<textarea class="form-control form-control-sm" name="AlamatKirim" id="AlamatKirim "><?= $detailspr->AlamatKirim ?></textarea>
									<label>Waktu Pengiriman</label>
									<input type="Date" name="TglKirim" id="TglKirim" class="form-control form-control-sm" value="<?= $detailspr->TglKirim ?>" required="required">
								</div>

							</div>
						</div>


						<!-- <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal">Close</button> -->
					</div>

					<div class="col-xs-12" style="padding-bottom:10px;padding-top:10px;">
						<input type="hidden" name="JobNo" id="JobNo" value="<?= $detailspr->JobNo ?>">
						<input type="hidden" name="NoSPR" id="NoSPR" value="<?= $detailspr->NoSPR ?>">
						<button type="submit" id="simpan" class="btn btn-primary pull-right">Save</button>
					</div>
				</form>

				<div class="col-xs-12">
					<div class="box">
						<br>
						<a href="javascript:void(0)" class="btn btn-flat btn-danger btn-sm" data-JobNo="<?= $detailspr->JobNo ?>" data-NoSPR="<?= $detailspr->NoSPR ?>" id="tambah-detail" readonly>TAMBAH</a><br><br>
						<span class="text-red my-loading" style="display:none;">Loading...</span>

						<div class="box-body">
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover table-condesed" id="istable" data-Job>
									<thead>
										<tr>
											<th style="background-color: #7ed6df;">No.</th>
											<th style="background-color: #7ed6df;">Kode RAP</th>
											<th style="background-color: #7ed6df;">Uraian</th>
											<th style="background-color: #7ed6df;">Vol</th>
											<th style="background-color: #7ed6df;">Satuan</th>
											<th style="background-color: #7ed6df;" class="text-center">#</th>
										</tr>
									</thead>
									<tbody class="detail-table">
										<?php $no = 1;
										foreach ($PRDtl as $dtl => $value) : ?>
											<tr>
												<td><?php echo $no++ ?></td>
												<td><?php echo $value->KdRAP ?></td>
												<td><?php echo $value->Uraian ?></td>
												<td><?php echo $value->Vol ?></td>
												<td><?php echo $value->Uom ?></td>
												<td></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>


						</div>
					</div>
					<!-- <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal">Close</button> -->
				</div>




			</div>
		</div>
	</section>

</div>

<div class="modal fade" id="MyModal">
	<div class="modal-dialog modal-md ">
		<div class="modal-content">
			<div class="modal-header modal-info">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="MyModalTitle"></h4>
			</div>
			<div class="modal-body" id="MyModalBody">

			</div>

		</div>
	</div>
</div>

<!-- <script> 
    $(function() {
        var myNoSPR = '<?= $row->NoSPR ?>';

        $('#simpan').attr('disabled', 'disabled');
        detail_table_spr(myNoSPR);

        detail_table();

        $('#myform').submit(function(e) {
            e.preventDefault();

            var JobNo = '<?= $spr->JobNo ?>';

            // var tbody = $("#istable tbody");
            // if (tbody.children().length == 0) {
            //     tbody.html("<tr>message foo</tr>");
            //     $.toast({
            //         // heading: 'Berhasil',
            //         text: 'Detail Masih Kosong',
            //         position: 'bottom-right',
            //         showHideTransition: 'slide',
            //         icon: 'error',
            //         hideAfter: 2000
            //     })
            //     return false;
            // }



            var formData = new FormData(this);
            $.ajax({
                url: "<?= site_url('Spr/simpanSpr') ?>",
                type: "POST",
                dataType: "HTML",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    // console.log(response);
                    // if (response.status == 'success') {
                    $.toast({
                        heading: 'Berhasil',
                        // text: 'Data Telah Ditambahkan',
                        position: 'bottom-right',
                        showHideTransition: 'slide',
                        icon: 'success',
                        hideAfter: '1500',
                        afterHidden: function() {
                            window.location.href = "<?= site_url('Job/Spr/') ?>" + JobNo
                        }
                    });
                    return false;


                },
                error: function(jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    console.log(msg);
                },
            })
        })

        $('#tambah-detail').click(function() {

            var JobNo = $(this).attr('data-JobNo');
            var NoSPR = $(this).attr('data-NoSPR');
            $('#MyModal').modal('show');
            $('#MyModalTitle').text('Form Tambah Detail');

            $.ajax({
                type: "POST",
                url: "<?= site_url('Spr/FormTambahDetail') ?>",
                data: {
                    "JobNo": JobNo,
                    "NoSPR": NoSPR
                },
                dataType: "HTML",
                beforeSend: function() {
                    $('#tambah-detail').attr('disabled', 'disabled');
                },
                success: function(response) {
                    $('#tambah-detail').removeAttr('disabled');
                    $('#MyModalBody').html(response);
                }
            });
        })

    })

    function detail_table() {
        var NoSPR = $('#NoSPR').val();
        $.ajax({
            type: "POST",
            url: "<?= site_url('Spr/detail_table') ?>",
            data: {
                'NoSPR': NoSPR
            },
            dataType: "HTML",
            beforeSend: function() {
                $('.my-loading').show();
            },
            success: function(response) {
                $('.my-loading').hide();
                if (response == 'no') {
                    $('#simpan').attr('disabled', 'disabled');
                    return false;
                }

                $('#simpan').removeAttr('disabled');
                $('.detail-table').html(response);
            }
        });
    }


    function detail_table_spr() {

    }
</script>-->
