<style type="text/css">
	.row_saya {
		font-size: 10px !important;
		font-family: Arial, Helvetica, sans-serif;
	}

	.row_saya table td{
		padding-top: 5px;
		padding-bottom: 5px;
	}

	.row_saya input[type="text"] {
		font-size: 10px !important;
	}


</style>
<div class="content-wrapper">

	<section class="content-header">
		<h5>
			<label class="col-lg col-form-label"><?php echo $judul ?> </label>
			<p></p>
		</h5>
		<label for="inputName" class="col-sm col-form-label"><?=$dataPD['JobNo'].' - '.$dataPD['JobNm']?></label><br>
		<small><label for="inputName" class="col-sm col-form-label"><?=$dataPD['Lokasi']?></label></small>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $myflag ?></li>
		</ol>
	</section>

	<section class="content-header">
		<button onclick="history.back()" class="btn btn-sm btn-default" ><i class="fa fa-arrow-left"></i> Back</button>
	</section>

	<section class="content-header">
		<?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>
		<?=($status_NoPJ =='') ? '' : $status_NoPJ;  ?>

		<?=($status_PjApprovedByAK =='') ? '' : $status_PjApprovedByAK;  ?>
		<?=($status_PjApprovedByKT =='') ? '' : $status_PjApprovedByKT;  ?>
		<?=($status_PjApprovedByKK =='') ? '' : $status_PjApprovedByKK;  ?>


		<div class="box">

			<div class="box-body">

				<div class="row">

					<form id="form_pj_saya" data-LedgerNo="<?=$dataPD['LedgerNo']?>" data-JobNo="<?=$dataPD['JobNo']?>" data-Alokasi="<?=$dataPD['Alokasi']?>" data-NoPD="<?=$dataPD['NoPD']?>" data-myflag="<?=$myflag?>" data-btn_dimatikan="<?=($btn_dimatikan == '') ? 0 : 1  ?>">



						<div class="col-sm-6 row_saya">

							<table width="100%" border="0" >
								<tr>
									<th style="width:80px;">No. PD</th>
									<td style="width:10px;">:</td>
									<td >
										<input type="text" class="form-control input-sm" value="<?=$dataPD['NoPD']?>" disabled>
									</td>
									<th>No. BAP/Ref Lapangan : </th>
									<td>
										<input type="text" class="form-control input-sm" name="NoRef" id="NoRef" placeholder="No.Ref" value="<?=$dataPD['NoRef']?>" disabled>
									</td>
								</tr>
							</table>


							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Job No</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" class="form-control input-sm" value="<?= $dataPD['JobNo'] ?> - <?= $dataPD['JobNm'] ?>" disabled>
									</td>
								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Tgl Permintaan</th>
									<td style="width:10px;">:</td>
									<td style="width: 200px;">
										<input type="date" class="form-control input-sm" name="TglPD" value="<?=$dataPD['TglPD']?>" disabled>
									</td>
									<td>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="checkbox" name="Jaminan" id="Jaminan" value="1" <?= ($dataPD['Jaminan'] == 1) ? 'checked' : ''    ?>  disabled>
											<label class="form-check-label" for="Jaminan">PD Jaminan</label>
										</div>

									</td>

								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Deskripsi</th>
									<td style="width:10px;">:</td>
									<td>
										<textarea name="Deskripsi" id="Deskripsi" class="form-control input-sm" placeholder="Isi Deskripsi" disabled><?=$dataPD['Deskripsi']?></textarea>
									</td>


								</tr>
							</table>


							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Periode</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="date" name="PrdAwal" id="PrdAwal" title="Periode Awal" value="<?= $dataPD['PrdAwal'] ?>" class="form-control input-sm" disabled required>
									</td>
									<td style="width:10px !important;">s.d.</td>
									<td>
										<input type="date" name="PrdAkhir" id="PrdAkhir" title="Periode Akhir" value="<?= $dataPD['PrdAkhir'] ?>" class="form-control input-sm" disabled required>
									</td>
									<td>Minggu Ke: </td>
									<td style="width:50px !important;">
										<input type="text" name="Minggu" id="Minggu" class="form-control input-sm" value="<?=$dataPD['Minggu']?>" disabled >
									</td>
								</tr>
							</table>


							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Alokasi</th>
									<td style="width:10px;">:</td>
									<td>
										<select class="form-control input-sm select2" id="AlokasiGabung" disabled  required>
											<option value="">-- Pilih Alokasi --</option>
											<?php
											foreach (list_job_alokasi() as $data_alokasi) : ?>
												<option value="<?=$data_alokasi['Alokasi']?>" <?=($data_alokasi['Alokasi'] == $dataPD['Alokasi']) ? 'selected' : '' ?> ><?=$data_alokasi['Alokasi'].' - '.$data_alokasi['Keterangan']?></option>
											<?php endforeach; ?>



										</select>
									</td>

									<td style="width:70px;">Tipe Form: </td>
									<td>
										<?php
										$data_tipe_form = $this->db->select('TipeForm,Keterangan as KeteranganTipeForm')->where('TipeForm',$dataPD['TipeForm'])->where('Alokasi',$dataPD['Alokasi'])->get('TipeForm')->row_array();
										?>
										<input type="" name="TipeForm" value="<?=$dataPD['TipeForm'].' - '.$data_tipe_form['KeteranganTipeForm']?>" class="form-control input-sm" disabled>
									</td>

								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;"></th>
									<td style="width:10px;">&nbsp;&nbsp;</td>
									<td style="font-size:10px !important;text-align: center;background-color:#3498db;color:white;">
										BUKTI PENDUKUNG
									</td>
								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;"></th>
									<td style="width:10px;">&nbsp;</td>
									<td>
										<div class="form-check form-check-inline" style="font-size: 10px;">
											<input class="form-check-input" type="checkbox" id="INV" value="INV" name="BuktiPendukung[]" <?= (CekBuktiPendukung($dataPD['BuktiPendukung'], 'INV') == TRUE) ? 'checked' : '' ?> <?=$btn_dimatikan?>>
											<label class="form-check-label" for="INV">Invoice/Kwitansi</label>
										</div><br>

										<div class="form-check form-check-inline" style="font-size: 10px;">
											<input class="form-check-input" type="checkbox" id="SJ" value="SJ" name="BuktiPendukung[]" <?= (CekBuktiPendukung($dataPD['BuktiPendukung'], 'SJ') == TRUE) ? 'checked' : '' ?> <?=$btn_dimatikan?>>
											<label class="form-check-label" for="SJ">Surat Jalan/Tanda Terima Lapangan</label>
										</div><br>

										<div class="form-check form-check-inline" style="font-size: 10px;">
											<input class="form-check-input" type="checkbox" id="PO" value="PO" name="BuktiPendukung[]" <?= (CekBuktiPendukung($dataPD['BuktiPendukung'], 'PO') == TRUE) ? 'checked' : '' ?> <?=$btn_dimatikan?>>
											<label class="form-check-label" for="PO">Copy PO</label>
										</div>


									</td>
									<td>
										<div class="form-check form-check-inline" style="font-size: 10px;">
											<input class="form-check-input" type="checkbox" id="FP" value="FP" name="BuktiPendukung[]" <?= (CekBuktiPendukung($dataPD['BuktiPendukung'], 'FP') == TRUE) ? 'checked' : '' ?> <?=$btn_dimatikan?>>
											<label class="form-check-label" for="FP">Faktur Pajak</label>
										</div><br>

										<div class="form-check form-check-inline" style="font-size: 10px;">
											<input class="form-check-input" type="checkbox" id="BAP" value="BAP" name="BuktiPendukung[]" <?= (CekBuktiPendukung($dataPD['BuktiPendukung'], 'BAP') == TRUE) ? 'checked' : '' ?> <?=$btn_dimatikan?>>
											<label class="form-check-label" for="BAP">Berita Acara Pembayaran</label>
										</div><br>

										<div class="form-check form-check-inline" style="font-size: 10px;">
											<input class="form-check-input" type="checkbox" id="BAOP" value="BAOP" name="BuktiPendukung[]" <?= (CekBuktiPendukung($dataPD['BuktiPendukung'], 'BAOP') == TRUE) ? 'checked' : '' ?> <?=$btn_dimatikan?>>
											<label class="form-check-label" for="BAOP">Berita Acara Opname Pekerjaan</label>
										</div>
									</td>
								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Keterangan</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="Keterangan" id="Keterangan" placeholder="Keterangan" class="form-control input-sm" value="<?= $dataPD['Keterangan'] ?>" <?=$btn_dimatikan?> >
									</td>
								</tr>
							</table>

							<?php if ($myflag !=='' && $myflag =='epj_keu') { ?>
								<table width="100%" border="0">
									<tr>
										<th style="width:80px;">Tgl PJ</th>
										<td style="width:10px;">:</td>
										<td>

											<input type="hidden" name="JobNo" value="<?=$dataPD['JobNo']?>">
											<input type="hidden" name="Alokasi" value="<?=$dataPD['Alokasi']?>">
											<input type="hidden" name="LedgerNo" value="<?=$dataPD['LedgerNo']?>">
											<input type="hidden" name="NoPD" value="<?=$dataPD['NoPD']?>">

											<input type="date" name="TglPJ" id="TglPJ" placeholder="Tanggal PJ" class="form-control input-sm" value="<?= $dataPD['TglPJ'] ?>" <?=$btn_dimatikan?> required>
										</td>
									</tr>
								</table>
							<?php } ?>

						</div>

						<div class="col-sm-6 row_saya">

							<table width="100%" border="0">
								<tr>
									<td style="font-size:10px !important;text-align: center;background-color:#e74c3c;color:white;">
										BAYAR KEPADA
									</td>
								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">No. Kontrak/PO</th>
									<td style="width:10px;">:</td>
									<td class="atur_no_ko">
										<input type="text" class="form-control input-sm" name="NoKO" value="<?= $dataPD['NoKO'] ?>" disabled required>
									</td>
								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">No. Tagihan</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="NoTagihan" id="NoTagihan" class="form-control input-sm" placeholder="No. Tagihan" value="<?= $dataPD['NoTagihan'] ?>" disabled>
									</td>

								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">ID</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="VendorId" id="VendorId" class="form-control input-sm" placeholder="ID"  disabled>
									</td>

								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Nama</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="Nama" id="Nama" class="form-control input-sm" placeholder="Nama" value="<?= $dataPD['Nama'] ?>"  disabled>
									</td>

								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Alamat</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="Alamat" id="Alamat" class="form-control input-sm" placeholder="Alamat" value="<?= $dataPD['Alamat'] ?>"  disabled>
									</td>

								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Telepon</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="Telepon" id="Telepon" class="form-control input-sm" placeholder="Telepon" value="<?= $dataPD['Telepon'] ?>"  disabled>
									</td>

								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">NPWP</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="NPWP" id="NPWP" class="form-control input-sm" placeholder="NPWP" value="<?= $dataPD['NPWP'] ?>"  disabled>
									</td>

								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<td style="font-size:11px !important;text-align: center;background-color:#27ae60;color:white;">
										REKENING PENERIMA
									</td>
								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">No. Rekening</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="NoRek" id="NoRek" class="form-control input-sm" placeholder="No. Rekening" value="<?= $dataPD['NoRek'] ?>" disabled >
									</td>
								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Atas Nama</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="text" name="AtasNama" id="AtasNama" class="form-control input-sm" placeholder="Atas Nama" value="<?= $dataPD['AtasNama'] ?>" disabled>
									</td>
								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<th style="width:80px;">Bank</th>
									<td style="width:10px;">:</td>
									<td>
										<input type="" name="Bank" value="<?=$dataPD['Bank']?>" class="form-control input-sm" disabled>
									</td>
								</tr>
							</table>

						</div>

						<div class="col-sm-12 row_saya">
							<hr style="border:1px solid black;">


							<button type="submit" id="btn_simpan_pj" class="btn btn-success btn-md pull-right" <?=$btn_dimatikan?> >Update</button>

							<button class="btn btn-xs" style="background-color:#7f8c8d;" onclick="history.back()"><i class="fa fa-arrow-left"></i> Back</button>
							<hr>
						</div>

						<div class="col-sm-12 row_saya">

							<table width="100%" border="0">
								<tr>
									<td style="font-size:14px !important;text-align: center;background-color:#636e72;color:white;">
										Entry Uraian
									</td>
								</tr>
								<tr>
									<td>
										<button type="button" class="btn btn-md bg-gray" id="IsiUraianApabila_PD" data-LedgerNo="<?=$dataPD['LedgerNo']?>" data-NoPD="<?=$dataPD['NoPD']?>" <?=$btn_dimatikan?> >Isi Uraian Apabila = PD</button>
									</td>
								</tr>
							</table>



							<div class="tampil_uraian_pj"></div>



						</div>

					</form>




				</div>

			</div>

			<div class="overlay loading_pj" style="display: none;">
				<i class="fa fa-refresh fa-spin"></i>
			</div>

		</div>


	</section>

</div>

<section></section>



<div class="modal fade " id="modal_uraian_pj">

	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body" id="modal_body_uraian_pj">

			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		tampil_uraian_pj();

		$('#form_pj_saya').submit(function(e){
			e.preventDefault();
			var JobNo = $('#form_pj_saya').attr('data-JobNo');
			var Alokasi = $('#form_pj_saya').attr('data-Alokasi');



			jQuery.ajax({
				url: '<?=site_url("PDPJ/PJ_keuangan_entry_controller/simpan_pj_entry_keuangan_detail")?>',
				type: 'POST',
				dataType: 'html',
				data: $(this).serialize(),
				beforeSend : function(){
					$('#btn_simpan_pj').attr("disabled",true).text('Loading...');
				},
				complete: function(xhr, textStatus) {
					$('#btn_simpan_pj').attr("disabled",false).text('Update');
				},
				success: function(data, textStatus, xhr) {
					// alert(data);
					// console.log(data);
					// return false;
					if (data == 'berhasil') {

						window.location.href='<?=site_url('PDPJ/PJ_keuangan_entry_controller/pj_keuangan_list/')?>'+JobNo;
						return false;

					}else{

						$.toast({
							text: 'Gagal mengubah data',
							position: 'bottom-right',
							stack: false,
							hideAfter: 2000,
							icon: 'error',


						});
						return false;
					}
					// console.log(data);
				},
				error: function(xhr, textStatus, errorThrown) {
					$('#btn_simpan_pj').attr("disabled",false).text('Update');

					$.toast({
						text: 'Terjadi Kesalahan',
						position: 'bottom-right',
						stack: false,
						hideAfter: 3000,
						icon: 'error',

					});
					return false;
				}
			});


		});


		$('#IsiUraianApabila_PD').click(function(){
			var NoPD = $(this).attr('data-NoPD');
			var LedgerNo = $(this).attr('data-LedgerNo');
			
			jQuery.ajax({
				url: '<?=site_url("PDPJ/PJ_keuangan_entry_controller/aksi_Isi_Uraian_Apabila_PD")?>',
				type: 'POST',
				dataType: 'html',
				data: {
					'LedgerNo' : LedgerNo,
					'NoPD' : NoPD,
				},
				beforeSend : function(){
					$(this).attr("disabled",true).text('Loading...');
				},
				complete: function(xhr, textStatus) {
					$(this).attr("disabled",true).text('Isi Uraian Apabila = PD');
					tampil_uraian_pj();
				},
				success: function(data, textStatus, xhr) {
					// alert(data);
					if (data == 'berhasil') {

						$.toast({
							text: 'Berhasil mengubah data',
							position: 'bottom-right',
							stack: false,
							hideAfter: 2000,
							icon: 'success',
						});
						return false;

					}else{

						$.toast({
							text: 'Gagal mengubah data',
							position: 'bottom-right',
							stack: false,
							hideAfter: 2000,
							icon: 'error',


						});
						return false;
					}
				},
				error: function(xhr, textStatus, errorThrown) {
					$.toast({
						text: 'Terjadi Kesalahan',
						position: 'bottom-right',
						stack: false,
						hideAfter: 3000,
						icon: 'error',

					});
					return false;
				}
			});

		})


	})

	function tampil_uraian_pj(){
		var LedgerNo = $('#form_pj_saya').attr('data-LedgerNo');
		var JobNo = $('#form_pj_saya').attr('data-JobNo');
		var Alokasi = $('#form_pj_saya').attr('data-Alokasi');
		var NoPD = $('#form_pj_saya').attr('data-NoPD');
		var myflag = $('#form_pj_saya').attr('data-myflag');

		jQuery.ajax({
			url: '<?=site_url("PDPJ/PJ_keuangan_entry_controller/tampil_uraian_pj")?>',
			type: 'POST',
			dataType: 'html',
			data: {

				'myflag' : myflag,
				'LedgerNo' : LedgerNo,
				'JobNo' : JobNo,
				'Alokasi' : Alokasi,
				'NoPD' : NoPD,

			},
			beforeSend : function(){
				$('.loading_pj').show();
				$('.tampil_uraian_pj').html('');
			},
			complete: function(xhr, textStatus) {
			},
			success: function(data, textStatus, xhr) {
				$('.loading_pj').hide();
				// alert(data);
				$('.tampil_uraian_pj').html('');
				$('.tampil_uraian_pj').html(data);
			},
			error: function(xhr, textStatus, errorThrown) {
				$('.loading_pj').hide();
				$.toast({
					text: 'Terjadi Kesalahan',
					position: 'bottom-right',
					stack: false,
					hideAfter: 3000,
					icon: 'error',

				});
				return false;
			}
		});

	}

	function LihatUraian(NoUrut='',NoPD='',status_detail_PdDtl=''){
		var JobNo = $('#punyasaya').attr('data-JobNo');
		var Alokasi = $('#punyasaya').attr('data-Alokasi');

		var btn_dimatikan = $('#form_pj_saya').attr('data-btn_dimatikan');

		// alert(status_detail_PdDtl);
		// return false;


		jQuery.ajax({
			url: '<?=site_url("PDPJ/PJ_keuangan_entry_controller/dapat_uraian_per_row")?>',
			type: 'POST',
			dataType: 'html',
			data: {
				'NoUrut' : NoUrut,
				'NoPD' : NoPD,
				'JobNo' : JobNo,
				'Alokasi' : Alokasi,
				'btn_dimatikan' : btn_dimatikan,
				'status_detail_PdDtl' : status_detail_PdDtl

			},
			beforeSend : function(){
				$('#myuraian'+NoUrut).attr("disabled",true).text('Loading...');
				$('#modal_body_uraian_pj').html('');
			},
			complete: function(xhr, textStatus) {
				$('#modal_uraian_pj').modal('show');
			},
			success: function(data, textStatus, xhr) {
				$('#myuraian'+NoUrut).attr("disabled",false).text('Lihat');

				$('#modal_body_uraian_pj').html(data);

				// alert(data)
			},
			error: function(xhr, textStatus, errorThrown) {
				$('#myuraian'+NoUrut).attr("disabled",false).text('Lihat');
				$.toast({
					text: 'Terjadi Kesalahan',
					position: 'bottom-right',
					stack: false,
					hideAfter: 3000,
					icon: 'error',

				});
				return false;
			}
		});



	}
</script>

