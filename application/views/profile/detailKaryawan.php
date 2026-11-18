  <style>
  	#table_nama {
  		font-size: 10px;
  		font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;

  	}

  	input[name=myradio] {
  		display: none;
  	}

  	input[name=myradio]:not(:checked)+label {
  		background-color: gray;
  	}
  </style>

  <div class="content-wrapper">

  	<section class="content-header">
  		<h4 style="font-weight: bold;"><?= (isset($judul)) ? strtoupper($judul) : '' ?></h4>

  		<ol class="breadcrumb">
  			<li><a href="<?= site_url('dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
  			<li class="active"><?= (isset($judul)) ? $judul : '' ?></li>
  		</ol>
  	</section>

  	<section class="content-header">

  		<button class="btn btn-xs" style="background-color:#7f8c8d;" onclick="history.back()"><i class="fa fa-arrow-left"></i> Back</button>

  	</section>



  	<section class="content">
  		<?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>


  		<div class="row">

  			<div class="col-sm-2">


  				<div class="box box-primary">

  					<div class="box-body box-profile">

  						<img class="profile-user-img img-responsive img-circle" src="<?= file_get_contents(alamat_server_dan_local_dan_folder('server') . 'Akses_get_file/dapat_foto/' . $mydata['NIK'] . '/1') ?>" alt="User profile picture">

  						<h3 class="profile-username text-center"><?= $mydata['Nama'] ?></h3>
  						<p class="text-muted text-center"><?= $mydata['Jabatan'] ?></p>


  						<div class="table-responsive">

  							<table class="table table-borderless" width="100%" border="0" style="border-collapse: collapse;" id="table_nama">

  								<tr>
  									<td width="100px">NIK</td>
  									<td><?= $mydata['NIK'] ?></td>
  								</tr>

  								<tr>
  									<td>Tempat, Tgl Lahir</td>
  									<td><?= $mydata['TmpLahir'] . ', ' . TanggalSaya($mydata['TglLahir']) ?></td>
  								</tr>
  								<tr>
  									<td>Warga Negara</td>
  									<td><?= $mydata['WN'] . '(' . ($mydata['WN'] == 'WNI') ? "INDONESIA" : "" . ')' ?></td>
  								</tr>
  								<tr>
  									<td>Status Pernikahan</td>
  									<td><?= $mydata['StsNikah'] ?></td>
  								</tr>
  								<tr>
  									<td>Agama</td>
  									<td><?= $mydata['Agama'] ?></td>
  								</tr>
  								<tr>
  									<td>Alamat KTP</td>
  									<td><?= $mydata['Alamat'] ?></td>
  								</tr>
  								<tr>
  									<td>Provinsi</td>
  									<td><?= $mydata['Provinsi'] ?></td>
  								</tr>
  								<tr>
  									<td>Kota</td>
  									<td><?= $mydata['Kota'] ?></td>
  								</tr>
  								<tr>
  									<td>Email</td>
  									<td><?= $mydata['Email'] ?></td>
  								</tr>
  							</table>

  						</div>

  					</div>

  				</div>


  			</div>

  			<div class="col-md-10">

  				<div class="box box-primary">

  					<div class="box-header">

  						<input type="radio" name="myradio" id="radio_1" value="identitas_personal" <?=($pilihan_menu_redirect == '' OR $pilihan_menu_redirect == 'identitas_personal' ) ? 'checked' : '' ?> >
  						<label for="radio_1" class="btn-lg btn-primary" style="font-size: 10px;">Identitas Personal</label>

  						<input type="radio" name="myradio" id="radio_2" value="data_pribadi" <?=($pilihan_menu_redirect !='' && $pilihan_menu_redirect == 'data_pribadi' ) ? 'checked' : '' ?> >
  						<label for="radio_2" class="btn-lg btn-primary" style="font-size: 10px;">Data Pribadi</label>

  						<input type="radio" name="myradio" id="radio_3" value="data_keluarga" <?=($pilihan_menu_redirect !='' && $pilihan_menu_redirect == 'data_keluarga' ) ? 'checked' : '' ?> >
  						<label for="radio_3" class="btn-lg btn-primary" style="font-size: 10px;">Data Keluarga</label>

  						<input type="radio" name="myradio" id="radio_4" value="data_emergency_contact" <?=($pilihan_menu_redirect !='' && $pilihan_menu_redirect == 'data_emergency_contact' ) ? 'checked' : '' ?> >
  						<label for="radio_4" class="btn-lg btn-primary" style="font-size: 10px;">Emergency Contact</label>

  						<input type="radio" name="myradio" id="radio_5" value="dataPendidikan" <?=($pilihan_menu_redirect !='' && $pilihan_menu_redirect == 'dataPendidikan' ) ? 'checked' : '' ?> >
  						<label for="radio_5" class="btn-lg btn-primary" style="font-size: 10px;">Data Pendidikan</label>

  						<input type="radio" name="myradio" id="radio_6" value="data_ketrampilan" <?=($pilihan_menu_redirect !='' && $pilihan_menu_redirect == 'data_ketrampilan' ) ? 'checked' : '' ?> >
  						<label for="radio_6" class="btn-lg btn-primary" style="font-size: 10px;">Data Ketrampilan</label>

  						<input type="radio" name="myradio" id="radio_7" value="data_riwayat_pekerjaan" <?=($pilihan_menu_redirect !='' && $pilihan_menu_redirect == 'data_riwayat_pekerjaan' ) ? 'checked' : '' ?> >
  						<label for="radio_7" class="btn-lg btn-primary" style="font-size: 10px;">Data Riwayat Pekerjaan</label>

  						<input type="radio" name="myradio" id="radio_8" value="data_posisi_pekerjaan" <?=($pilihan_menu_redirect !='' && $pilihan_menu_redirect == 'data_posisi_pekerjaan' ) ? 'checked' : '' ?> >
  						<label for="radio_8" class="btn-lg btn-primary" style="font-size: 10px;">Data Posisi Pekerjaan</label>

  						<input type="radio" name="myradio" id="radio_9" value="data_dokumen_pendukung" <?=($pilihan_menu_redirect !='' && $pilihan_menu_redirect == 'data_dokumen_pendukung' ) ? 'checked' : '' ?>  >
  						<label for="radio_9" class="btn-lg btn-primary" style="font-size: 10px;">Dokumen Pendukung</label>

  						<input type="radio" name="myradio" id="radio_10" value="data_payroll" <?=($pilihan_menu_redirect !='' && $pilihan_menu_redirect == 'data_payroll' ) ? 'checked' : '' ?>>
  						<label for="radio_10" class="btn-lg btn-primary" style="font-size: 10px;">Payroll</label>

  						<input type="radio" name="myradio" id="radio_11" value="Cuti" <?=($pilihan_menu_redirect !='' && $pilihan_menu_redirect == 'Cuti' ) ? 'checked' : '' ?>>
  						<label for="radio_11" class="btn-lg btn-primary" style="font-size: 10px;">Cuti</label>

  						<input type="radio" name="myradio" id="radio_12" value="Memo" <?=($pilihan_menu_redirect !='' && $pilihan_menu_redirect == 'Memo' ) ? 'checked' : '' ?> >
  						<label for="radio_12" class="btn-lg btn-primary" style="font-size: 10px;">Memo</label>


  					</div>

  					<div class="box-body" id="tampil_form">

  					</div>

  					<div class="overlay loadingku" style="display: none;">
  						<i class="fa fa-refresh fa-spin"></i>
  					</div>

  				</div>

  				<div id="tampil_khusus_payroll">

  				</div>

  			</div>

  		</div>





  	</section>

  </div>


  <div class="modal fade" id="modal_keluarga">
  	<div class="modal-dialog modal-md">
  		<div class="modal-content">


  			<div class="modal-body" id="modal_keluarga_body">


  			</div>
  		</div>

  	</div>

  </div>

  <script type="text/javascript">
  	$(function() {

  		$('input[type=radio][name=myradio]').change(function() {
  			pilihan_menu();
  		});
  		pilihan_menu();

  	});

  	function pilihan_menu() {

  		var myradio = $('input[name="myradio"]:checked').val();

  		$.ajax({
  				url: "<?= site_url('Kepegawaian/pilihan_menu') ?>",
  				type: 'POST',
  				dataType: 'html',
  				data: {
  					flag: 'profile',
  					myradio: myradio
  				},
  				beforeSend: function() {
  					$('.loadingku').show();
  					$('#tampil_form').html('');

  					if ($('#radio_10').is(':checked')) {
  						$('#tampil_khusus_payroll').html('');
  					} else {
  						$('#tampil_form').html('');
  					}


  				}
  			})
  			.done(function(data) {
  				$('.loadingku').hide();



  				if ($('#radio_10').is(':checked')) {
  					$('#tampil_khusus_payroll').html(data);
  					$('#tampil_form').html('');
  				} else {
  					$('#tampil_khusus_payroll').html('');
  					$('#tampil_form').html(data);
  				}

  			})
  			.fail(function() {
  				$('.loadingku').hide();
  				$.toast({
  					text: 'Terjadi Kesalahan',
  					position: 'bottom-right',
  					stack: false,
  					hideAfter: 1000,
  					icon: 'error',

  				});
  				return false;
  			});

  	}
  </script>
