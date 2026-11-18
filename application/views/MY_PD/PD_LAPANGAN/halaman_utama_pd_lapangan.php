<style type="text/css">
	#content_wrap{
		font-size: 10px;
		font-family: Roboto, Tahoma, sans-serif;
	}

	.judul{
		font-size: 12px;
		font-family: Roboto, Tahoma, sans-serif;
	}
	.ukuran{
		font-size: 10px !important;
		font-family: Roboto, Tahoma, sans-serif;
	}
	.ukuran_select2{
		font-size: 10px !important;
		font-family: Roboto, Tahoma, sans-serif;
	}
	input[type="text"]{
		font-size: 10px !important;
		font-family: Roboto, Tahoma, sans-serif;
	}
	input[type="date"]{
		font-size: 10px !important;
		font-family: Roboto, Tahoma, sans-serif;
	}
	input[type="checkbox"]{
		font-size: 10px !important;
		font-family: Roboto, Tahoma, sans-serif;
	}
	input[type="radio"]{
		font-size: 10px !important;
		font-family: Roboto, Tahoma, sans-serif;
	}
	textarea{
		font-size: 10px !important;
		font-family: Roboto, Tahoma, sans-serif;
	}
	select {
		font-size: 10px !important;
		font-family: Roboto, Tahoma, sans-serif;
	}
	.swal2-popup {
		font-size: 1.2rem !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}
</style>
<div class="content-wrapper" id="content_wrap">

	<section class="content">

		<table style="width:100%;border-collapse: collapse;margin-bottom: 10px !important;" border="0" >
			<tr>
				<th class="judul"><?=($judul) ? $judul : ''   ?></th>
				<th class="judul text-right text-primary"><?=($job_detail) ? $job_detail : ''   ?></th>
			</tr>
		</table>


		<div class="box box-body box-widget">
			<table width="50%" border="0">
				<tr>
					<td width="10%">Job No</td>
					<td width="2%" class="text-center">:</td>
					<td>
						<select class="form-control input-sm select2 job_pilih" disabled="disabled">
							<option value="">-- pilih salah satu --</option>
							<?php foreach (dapat_list_job() as $data_job) : ?>

								<option value="<?= $data_job['JobNo'] ?>" data-JobNm="<?=$data_job['JobNm']?>"  <?= ($JobNo == $data_job['JobNo']) ? 'selected' : ''  ?>><?= $data_job['JobNo'] . ' - ' . $data_job['JobNm'] ?></option>

							<?php endforeach; ?>
						</select>
						<input type="hidden" name="JobNo" id="JobNo" value="<?=$JobNo?>" readonly >
					</td>
				</tr>
			</table>

			<table width="50%" border="0" style="margin-top:5px;">
				<tr>
					<td width="10%">Alokasi</td>
					<td width="2%" class="text-center">:</td>
					<td>
						<select class="form-control input-sm select2" id="Alokasi" name="Alokasi">
							<option value="">-- Pilih Salah Satu --</option>
							<?php foreach (list_job_alokasi() as $data_alokasi) : ?>

								<option value="<?= $data_alokasi['Alokasi'] ?>" <?= ($Alokasi == $data_alokasi['Alokasi']) ? 'selected' : ''  ?>><?= $data_alokasi['Alokasi'] . ' - ' . $data_alokasi['Keterangan'] ?></option>

							<?php endforeach; ?>
						</select>
					</td>
				</tr>
			</table>

			<table width="50%" border="0" style="margin-top:5px;">
				<tr>
					<td width="10%">Periode</td>
					<td width="2%" class="text-center">:</td>
					<td>
						<input type="text" id="dari_tgl" name="dari_tgl" class="form-control input-sm" placeholder="Dari Tgl" autocomplete="off" >
					</td>
					<td style="text-align:center;width:5%;">s.d</td>
					<td>
						<input type="text" id="sampai_tgl" name="sampai_tgl" class="form-control input-sm" placeholder="Sampai Tgl" autocomplete="off" >
					</td>
				</tr>
			</table>

			<table width="50%" border="0" style="margin-top:5px;">
				<tr>
					<td width="10%"></td>
					<td width="2%" class="text-center"></td>
					<td>
						<button type="button" class="btn btn-sm bg-gray ukuran btn_go" id="btn_go">GO <i class="fa fa-search"></i></button>
						<button class="btn btn-sm btn-danger ukuran disabled" id="btn_loading" style="display: none;">Loading... <i class="fa fa-spinner fa-spin"></i> </button>
					</td>
				</tr>
			</table>

		</div>

		<div id="loading_data" style="display:none;"><p class=" text-red">Loading... <i class="fa fa-spinner fa-spin"></i> </p></div>
		<div id="tampil_disini" style="display:none;"></div>


	</section>

</div>

<script src="<?=base_url('assets/select2_baru.js')?>"></script>

<script type="text/javascript">

	$(document).ready(function(){

		if ($('#Alokasi :selected').val() !='') {
			list_pd_lapangan();
		}

		$( "#dari_tgl, #sampai_tgl" ).datepicker({
			autoclose:true,
			todayHighlight:true,
			format: 'dd-M-yyyy',
			viewMode: "months", 
			language: 'id'
		});

		$('.select2').select2({
			'width': '100%',
			'dropdownCssClass': "ukuran_select2"
		});

		$('#btn_go').click(function(){
			list_pd_lapangan();
		});

		$('#Alokasi').change(function(){
			list_pd_lapangan();
		});

		$('#dari_tgl').change(function(){
			
			if ($('#sampai_tgl').val() == '' || $('#sampai_tgl').val() == null) {
				return false;
			}

			list_pd_lapangan();
		});

		$('#sampai_tgl').change(function(){
			
			if ($('#dari_tgl').val() == '' || $('#dari_tgl').val() == null) {
				return false;
			}
			
			list_pd_lapangan();
		});




	});

	function list_pd_lapangan(){
		var JobNo = $('#JobNo').val();
		var Alokasi = $('#Alokasi :selected').val();
		var dari_tgl = $('#dari_tgl').val();
		var sampai_tgl = $('#sampai_tgl').val();

		if (Alokasi =='' ) {
			swal.fire('','Alokasi masih kosong','info');
			return false;
		}

		jQuery.ajax({
			url: "<?=site_url('MY_PD/PD_LAPANGAN/list_pd_lapangan')?>",
			type: 'POST',
			dataType: 'html',
			cache : false,
			data: {
				'JobNo' : JobNo,
				'Alokasi' : Alokasi,
				'dari_tgl' : dari_tgl,
				'sampai_tgl' : sampai_tgl,
			},
			beforeSend : function(){
				$('#btn_go').attr('disabled',true);
				// $('#btn_loading').show();

				$('#loading_data').show();
				$('#tampil_disini').hide();
			},
			complete: function(xhr, textStatus) {
				$('#btn_go').attr('disabled',false);
				// $('#btn_go').show();
				// $('#btn_loading').hide();

				$('#loading_data').hide();
				$('#tampil_disini').show();
			},
			success: function(data, textStatus, xhr) {
				// alert(data);
				$('#tampil_disini').html(data);
				if (data == 'tgl_salah') {
					swal.fire('Error!','Periode tidak sesuai','error');
					return false;
				}

				if (data == 'gagal') {
					swal.fire('Gagal!','Gagal saat mengolah data','error');
					return false;
				}
			},
			error: function(xhr, textStatus, errorThrown) {
				swal.fire('Error!','Terjadi Kesalahan','error');
				return false;
			}
		});
		
	}

	function aksi_approve(LedgerNo=''){

		if (LedgerNo == '') {
			swal.fire('Error!','Terjadi Kesalahan','error');
			return false;
		}

		var JobNo = $('#aksi_approve_'+LedgerNo).attr('data-JobNo');
		var Alokasi = $('#aksi_approve_'+LedgerNo).attr('data-Alokasi');
		var NoPD = $('#aksi_approve_'+LedgerNo).attr('data-NoPD');
		var ApprovedByKT = $('#aksi_approve_'+LedgerNo).attr('data-ApprovedByKT');
		var ApprovedByKK = $('#aksi_approve_'+LedgerNo).attr('data-ApprovedByKK');
		var type_app = $('#aksi_approve_'+LedgerNo).attr('data-type_app');

		

		if (type_app == 'UNAPPROVE') {

			if (ApprovedByKT =='ada_KT' ) {

				swal.fire('Info!','sudah di approve oleh cost control','info');
				return false;
			}

			if (ApprovedByKK =='ada_KK') {
				swal.fire('Info!','sudah di approve oleh keuangan','info');
				return false;
			}

		}

		Swal.fire({
			title: 'Anda Yakin?',
			text: " "+type_app+" No. PD : "+NoPD+" ",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Save',
		}).then((result) => {
			
			jQuery.ajax({
				url: '<?=site_url("MY_PD/PD_LAPANGAN/aksi_approve")?>',
				type: 'POST',
				dataType: 'html',
				data: {
					'LedgerNo' : LedgerNo,
					'JobNo' : JobNo,
					'Alokasi' : Alokasi,
					'type_app' : type_app,
					'ApprovedByKT' : ApprovedByKT,
					'ApprovedByKK' : ApprovedByKK,
				},
				cache : false,
				beforeSend : function(){
					// $('#loading_row_'+LedgerNo).show();
					// $('#aksi_approve_'+LedgerNo).hide();
				},
				complete: function(xhr, textStatus) {
					// $('#loading_row_'+LedgerNo).hide();
					// $('#aksi_approve_'+LedgerNo).show();
					list_pd_lapangan();

				},
				success: function(data, textStatus, xhr) {

					if (data == 'ada_KT') {
						swal.fire('Info!','sudah di approve oleh cost control','info');
						return false;
					}

					if (data == 'ada_KK') {
						swal.fire('Info!','sudah di approve oleh keuangan','info');
						return false;
					}

					if (data == 'salah') {
						swal.fire('Error!','Terjadi Kesalahan','error');
						return false;
					}

					if (data == 'gagal') {
						swal.fire('Error!','data gagal di ubah','error');
						return false;
					}

					swal.fire('Success!','data telah diubah','success');
					return false;
				},
				error: function(xhr, textStatus, errorThrown) {
					swal.fire('Error!','Terjadi Kesalahan','error');
					return false;
				}
			});

			
		});

		return false;

	}


	function btn_pdf(LedgerNo=''){

		if (LedgerNo == '') {
			swal.fire('Error!','Terjadi Kesalahan','error');
			return false;
		}

		var JobNo = $('#btn_pdf_'+LedgerNo).attr('data-JobNo');
		var Alokasi = $('#btn_pdf_'+LedgerNo).attr('data-Alokasi');
		var NoPD = $('#btn_pdf_'+LedgerNo).attr('data-NoPD');
		var ApprovedByAK = $('#btn_pdf_'+LedgerNo).attr('data-ApprovedByAK');
		var print_dari = 'Lapangan';

		var dari_tgl = $('#btn_pdf_'+LedgerNo).attr('data-dari_tgl');
		var sampai_tgl = $('#btn_pdf_'+LedgerNo).attr('data-sampai_tgl');
		dari_tgl = (dari_tgl == '') ? 0 : dari_tgl;
		sampai_tgl = (sampai_tgl == '') ? 0 : sampai_tgl;


		var url = "<?=site_url('MY_PD/PD_PDF_ALL/ExpPDF_PD')?>"+'/'+LedgerNo+'/'+JobNo+'/'+Alokasi+'/'+NoPD+'/'+dari_tgl+'/'+sampai_tgl;

		
		if (ApprovedByAK == 'APPROVE' || ApprovedByAK == null ) {
			swal.fire('Info!','Approve No. PD : '+NoPD+' terlebih dahulu','info');
			return false;
		}

		window.open(url, '_blank');

	}


	

</script>