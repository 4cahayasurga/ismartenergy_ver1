<?php
error_reporting(E_ALL)
?>

<style type="text/css" media="screen">
	.gaya_huruf{
		font-size: 12px !important;
		font-family: sans-serif;
	}

	select{
		font-size: 13px !important;
		font-family: sans-serif;
	}

	input[type="text"]{
		font-size: 12px !important;
		font-family: sans-serif;
	}
	input[type="date"]{
		font-size: 12px !important;
		font-family: sans-serif;
	}
	input[type="checkbox"]{
		font-size: 12px !important;
		font-family: sans-serif;
	}
	.myFont{
		font-size:13px !important;
	}

	#formTambahUraian, #formEditUraian{
		font-family: sans-serif;
		font-size: 12px !important;
	}

	#formTambahUraian, #formEditUraian select{
		font-family: sans-serif;
		font-size: 12px !important;
	}
	#formTambahUraian, #formEditUraian textarea{
		font-family: sans-serif;
		font-size: 12px !important;
	}

	.marginatas{
		margin-top:5px !important;
	}

</style>
<div class="content-wrapper">

	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6 ">
					<h3><?=strtoupper($judul)?></h3>
					<a href="<?=site_url('Entry/PDPJ/FrmPDKeu/view/'.$JobNo.'/'.$Alokasi)?>" class="btn btn-danger btn-sm" title="Kembali ke halaman sebelumnya" ><i class="fa fa-arrow-left"></i> BACK</a>
				</div>
				<div class="col-sm-6 text-right">
					<!-- <a href="<?=site_url('Entry/PDPJ/FrmPDKeu/view/'.$JobNo.'/'.$Alokasi)?>" class="btn btn-danger btn-sm" title="Kembali ke halaman sebelumnya" ><i class="fa fa-arrow-left"></i> BACK</a> -->
				</div>
			</div>
		</div>
	</section>

	<section class="content">

		<span class="text-red loading_atas" style="display: none;" ><i class="fa fa-spinner"></i> Loading...</span>

		<form id="MyForm" data-urlkembali="<?=site_url('Entry/PDPJ/FrmPDKeu/view/'.$JobNo.'/'.$Alokasi)?>">

			<div class="box">
				<div class="box-body">
					<div class="row">

						<div class="col-sm-6">
							<div class="table-responsive">
								<table width="100%" style="border-collapse:collapse;" border="0">
									<tr>
										<td width="100px" class="gaya_huruf">No PD</td>
										<td width="10px" class="text-center gaya_huruf">:</td>
										<td width="200px" class="gaya_huruf">
											<input type="text" name="NoPD" id="NoPD" class="form-control form-control-sm gaya_huruf" value="<?=$no_urut_pd['KodeUrutPd']?>" readonly required>
										</td>

										<td width="130px" class="gaya_huruf">&nbsp;&nbsp;No. BAP/Ref Lapangan</td>
										<td width="10px" class="text-center gaya_huruf">:</td>
										<td width="200px" class="gaya_huruf">
											<input type="text" name="NoRef" id="NoRef" class="form-control form-control-sm gaya_huruf" readonly>
										</td>

										<td></td>
									</tr>
								</table>
								<table width="100%" style="border-collapse:collapse;margin-top:5px;" border="0">
									<tr>
										<td width="100px" class="gaya_huruf">Job No</td>
										<td width="10px" class="text-center gaya_huruf">:</td>
										<td width="300px" class="gaya_huruf">
											<input type="text" value="<?=$JobNo.' - '.$JobNm?>" class="form-control form-control-sm gaya_huruf" disabled >
											<input type="hidden" name="JobNo" id="JobNo" value="<?=$JobNo?>" class="form-control form-control-sm gaya_huruf"  >
										</td>
										<td></td>
									</tr>
								</table>
								<table width="100%" style="border-collapse:collapse;margin-top:5px;" border="0">
									<tr>
										<td width="100px" class="gaya_huruf">Tgl Permintaan</td>
										<td width="10px" class="text-center gaya_huruf">:</td>
										<td width="150px" class="gaya_huruf">
											<input type="date" name="TglPD" id="TglPD" class="form-control form-control-sm gaya_huruf" value="<?=date('Y-m-d')?>" required>
										</td>

										<td width="130px" class="gaya_huruf" style="vertical-align: middle;padding-top:10px;padding-left:5px;">
											<input type="checkbox" id="Jaminan" name="Jaminan" value="1" >
											<label for="Jaminan"> PD Jaminan</label>
										</td>
										<td></td>
									</tr>
								</table>
								<table width="100%" style="border-collapse:collapse;margin-top:5px;" border="0">
									<tr>
										<td width="100px" class="gaya_huruf">Deskripsi</td>
										<td width="10px" class="text-center gaya_huruf">:</td>
										<td width="500px" class="gaya_huruf">
											<input type="text" name="Deskripsi" id="Deskripsi" class="form-control form-control-sm gaya_huruf" placeholder="Deskripsi"  >
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" style="border-collapse:collapse;" border="0" class="marginatas">
									<tr>
										<td width="100px" class="gaya_huruf">Periode</td>
										<td width="10px" class="text-center gaya_huruf">:</td>
										<td width="200px" class="gaya_huruf">
											<input type="date" name="PrdAwal" id="PrdAwal" class="form-control form-control-sm gaya_huruf" value="<?=$PrdAwal?>" required >
										</td>

										<td width="50px" class="gaya_huruf text-center">s.d</td>
										<td width="200px" class="gaya_huruf">
											<input type="date" name="PrdAkhir" id="PrdAkhir" class="form-control form-control-sm gaya_huruf" value="<?=$PrdAkhir?>" required >
										</td>
										<td width="70px" class="gaya_huruf">&nbsp;&nbsp;Minggu Ke</td>
										<td width="80px" class="gaya_huruf">
											<input type="text" name="Minggu" id="Minggu" class="form-control form-control-sm gaya_huruf" value="<?=$Minggu?>" required >
										</td>
										<!-- <td></td> -->
									</tr>
								</table>


								<table width="100%" style="border-collapse:collapse;" border="0" class="marginatas">
									<tr>
										<td width="100px" class="gaya_huruf">Alokasi</td>
										<td width="10px" class="text-center gaya_huruf">:</td>
										<td width="200px" class="gaya_huruf">
											<input type="text" class="form-control form-control-sm gaya_huruf" value="<?=$Alokasi.' - '.$KeteranganAlokasi?>"  disabled>

											<input type="hidden" name="Alokasi" id="Alokasi" value="<?=$Alokasi?>">

										</td>

										<td width="80px" class="gaya_huruf">&nbsp;&nbsp;&nbsp;Tipe Form</td>
										<td width="10px" class="text-center gaya_huruf">:</td>
										<td width="" class="gaya_huruf">
											<select name="TipeForm" id="TipeForm" class="form-control form-control-sm gaya_huruf" required>
												<option value="">-- Pilih --</option>
												<?php
												foreach ($dataTipeForm as $dataTipe) :

													if ($dataTipe->TipeForm =='05B') {
														continue;
													}
													?>
													<option value="<?=$dataTipe->TipeForm?>"><?=$dataTipe->TipeForm.' - '.$dataTipe->Keterangan?></option>
												<?php endforeach; ?>
											</select>
										</td>

										<!-- <td></td> -->
									</tr>
								</table>


								<table width="100%" style="border-collapse:collapse;margin-top:5px;" border="0">
									<tr>
										<td width="100px" class="gaya_huruf"></td>
										<td width="10px" class="text-center gaya_huruf"></td>
										<td width="" class="gaya_huruf bg-gray text-center" style="padding-top:5px;padding-bottom:5px;">
											Bukti Pendukung
										</td>
									</tr>
								</table>

								<table width="100%" style="border-collapse:collapse;margin-top:5px;" border="0">
									<tr>
										<td width="100px" class="gaya_huruf"></td>
										<td width="10px" class="text-center gaya_huruf"></td>
										<td width="220px" class="gaya_huruf" >
											<input type="checkbox" id="INV" name="BuktiPendukung[]" value="INV">
											<label for="INV">Invoice</label>
										</td>
										<td width="200px" class="gaya_huruf" >
											<input type="checkbox" id="FP" name="BuktiPendukung[]" value="FP">
											<label for="FP">Faktur Pajak</label>
										</td>
										<td></td>
									</tr>
									<tr>
										<td width="100px" class="gaya_huruf"></td>
										<td width="10px" class="text-center gaya_huruf"></td>
										<td width="100px" class="gaya_huruf" >
											<input type="checkbox" id="SJ" name="BuktiPendukung[]" value="SJ">
											<label for="SJ">Surat Jalan/Tanda Terima Lapangan</label>
										</td>
										<td width="100px" class="gaya_huruf" >
											<input type="checkbox" id="BAP" name="BuktiPendukung[]" value="BAP">
											<label for="BAP">Berita Acara Pembayaran</label>
										</td>
										<td></td>
									</tr>
									<tr>
										<td width="100px" class="gaya_huruf"></td>
										<td width="10px" class="text-center gaya_huruf"></td>
										<td width="100px" class="gaya_huruf" >
											<input type="checkbox" id="PO" name="BuktiPendukung[]" value="PO">
											<label for="PO">Copy PO</label>
										</td>
										<td width="100px" class="gaya_huruf" >
											<input type="checkbox" id="BAOP" name="BuktiPendukung[]" value="BAOP">
											<label for="BAOP">Berita Acara Opname Pekerjaan</label>
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" style="border-collapse:collapse;margin-top:5px;" border="0">
									<tr>
										<td width="100px" class="gaya_huruf">Keterangan</td>
										<td width="10px" class="text-center gaya_huruf">:</td>
										<td width="500px" class="gaya_huruf">
											<input type="text" name="Keterangan" id="Keterangan" class="form-control form-control-sm gaya_huruf" placeholder="Keterangan"  >
										</td>
										<td></td>
									</tr>
								</table>

							</div>
						</div>

						<div class="col-sm-6">
							<div class="table-responsive">
								<table width="100%" style="border-collapse:collapse;margin-top:5px;" border="0">
									<tr>
										<td width="" class="gaya_huruf bg-gray text-center" style="padding-top:5px;padding-bottom:5px;">
											Bayar Kepada
										</td>
									</tr>
								</table>


								<table width="100%" style="border-collapse:collapse;margin-top:2px;" border="0" class="marginatas">
									<tr>
										<td width="100px" class="gaya_huruf">No. Kontrak/PO</td>
										<td width="10px" class="text-center gaya_huruf">:</td>
										<td width="" class="gaya_huruf">
											<select name="NoKO" id="NoKO" class="form-control form-control-sm select2" required>
												<option value="">-- Pilih No. Kontrak --</option>
											</select>
										</td>
									</tr>
								</table>

								<table width="100%" style="border-collapse:collapse;margin-top:2px;" border="0" class="marginatas" >
									<tr>
										<td width="100px" class="gaya_huruf">No. Tagihan</td>
										<td width="10px" class="text-center gaya_huruf">:</td>
										<td width="" class="gaya_huruf">
											<input type="text" name="NoTagihan" id="NoTagihan" class="form-control form-control-sm gaya_huruf" placeholder="No Tagihan" >
										</td>
									</tr>
								</table>

								<table width="100%" style="border-collapse:collapse;margin-top:2px;" border="0" class="marginatas">
									<tr>
										<td width="100px" class="gaya_huruf">ID</td>
										<td width="10px" class="text-center gaya_huruf">:</td>
										<td width="" class="gaya_huruf">
											<input type="text"  class="form-control form-control-sm gaya_huruf" disabled>
										</td>
									</tr>
								</table>

								<table width="100%" style="border-collapse:collapse;margin-top:2px;" border="0" class="marginatas" >
									<tr>
										<td width="100px" class="gaya_huruf">Nama</td>
										<td width="10px" class="text-center gaya_huruf">:</td>
										<td width="" class="gaya_huruf">
											<input type="text" name="Nama" id="Nama" class="form-control form-control-sm gaya_huruf" placeholder="Nama" >
										</td>
									</tr>
								</table>

								<table width="100%" style="border-collapse:collapse;margin-top:2px;" border="0" class="marginatas">
									<tr>
										<td width="100px" class="gaya_huruf">Alamat</td>
										<td width="10px" class="text-center gaya_huruf">:</td>
										<td width="" class="gaya_huruf">
											<input type="text" name="Alamat" id="Alamat" class="form-control form-control-sm gaya_huruf" placeholder="Alamat" >
										</td>
									</tr>
								</table>

								<table width="100%" style="border-collapse:collapse;margin-top:2px;" border="0" class="marginatas">
									<tr>
										<td width="100px" class="gaya_huruf">Telepon</td>
										<td width="10px" class="text-center gaya_huruf">:</td>
										<td width="" class="gaya_huruf">
											<input type="text" name="Telepon" id="Telepon" class="form-control form-control-sm gaya_huruf" placeholder="No. Telepon" >
										</td>
									</tr>
								</table>

								<table width="100%" style="border-collapse:collapse;margin-top:2px;" border="0" class="marginatas">
									<tr>
										<td width="100px" class="gaya_huruf">NPWP</td>
										<td width="10px" class="text-center gaya_huruf">:</td>
										<td width="" class="gaya_huruf">
											<input type="text" name="NPWP" id="NPWP" class="form-control form-control-sm gaya_huruf" placeholder="NPWP" >
										</td>
									</tr>
								</table>


								<table width="100%" style="border-collapse:collapse;margin-top:5px;" border="0">
									<tr>
										<td width="" class="gaya_huruf bg-gray text-center" style="padding-top:5px;padding-bottom:5px;">
											Rekening Penerima
										</td>
									</tr>
								</table>

								<table width="100%" style="border-collapse:collapse;margin-top:2px;" border="0" class="marginatas">
									<tr>
										<td width="100px" class="gaya_huruf">No. Rekening</td>
										<td width="10px" class="text-center gaya_huruf">:</td>
										<td width="" class="gaya_huruf">
											<input type="text" name="NoRek" id="NoRek" class="form-control form-control-sm gaya_huruf" placeholder="No. Rekening" >
										</td>
									</tr>
								</table>

								<table width="100%" style="border-collapse:collapse;margin-top:2px;" border="0" class="marginatas">
									<tr>
										<td width="100px" class="gaya_huruf">Atas Nama</td>
										<td width="10px" class="text-center gaya_huruf">:</td>
										<td width="" class="gaya_huruf">
											<input type="text" name="AtasNama" id="AtasNama" class="form-control form-control-sm gaya_huruf" placeholder="Atas Nama" >
										</td>
									</tr>
								</table>

								<table width="100%" style="border-collapse:collapse;margin-top:2px;" border="0" class="marginatas">
									<tr>
										<td width="100px" class="gaya_huruf">Bank</td>
										<td width="10px" class="text-center gaya_huruf">:</td>
										<td width="" class="gaya_huruf">
											<select name="Bank" id="Bank" class="form-control form-control-sm select2 gaya_huruf">
												<option value="">-- Pilih --</option>
												<?php foreach ($ListBank as $row): ?>
													<option value="<?=$row->Bank?>"><?=$row->Bank?></option>
												<?php endforeach ?>
											</select>
										</td>
									</tr>
								</table>


							</div>
						</div>

						<p></p>
						<div class="col-sm-12 marginatas">
							<table width="100%" style="border-collapse:collapse;margin-top:2px;" border="0">
								<tr>
									<td class="text-left">
										<button type="button" class="btn btn-primary btn-md btn_tambah_uraian">TAMBAH URAIAN</button>
										<button type="button" class="btn btn-link btn-md" onclick="tampilkan_tbl_PdDtl()"><u>REFRESH TABLE </u></button>
									</td>
									<td class="text-right">
										<a href="<?=site_url('Entry/PDPJ/FrmPDKeu/view/'.$JobNo.'/'.$Alokasi)?>" class="btn btn-danger btn-md" title="Kembali ke halaman sebelumnya" > BATAL</a>
										<input type="hidden" name="StatusTambah" value="<?=$StatusTambah?>">
										<input type="hidden" name="status_kode" value="<?=$no_urut_pd['status_kode']?>">
										<button type="submit" class="btn btn-primary btn-md btnSimpanPD">SIMPAN</button>
									</td>
								</tr>
							</table>
							
						</div>

					</div>
				</div>

				

			</div>

			<i class="fa fa-refresh fa-spin loading_tbl" style="display: none;"></i>



			<div id="table_detail_disini">

			</div>

		</form>

	</section>


</div>

<div class="modal fade" id="ModalTambahUraian" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<form id="formTambahUraian" >

				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Form Entry Uraian</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">

					<div class="table-responsive">

						<table border="0" style="border-collapse: collapse;" width="100%">
							<tr>
								<td width="100px">Alokasi</td>
								<td width="5px" class="text-center">:</td>
								<td width="300px">
									<input type="text" class="form-control form-control-sm gaya_huruf" placeholder="Alokasi" value="<?=$Alokasi.' - '.$KeteranganAlokasi?>" disabled>

								</td>
								<td></td>

							</tr>
						</table>

						<table border="0" style="border-collapse: collapse;margin-top:3px;" width="100%">
							<tr>
								<td width="100px">Kode RAP</td>
								<td width="5px" class="text-center">:</td>
								<td width="300px">
									<select name="KdRAP" id="KdRAP" class="form-control form-control-sm" required>
										<option value="">-- Pilih Salah Satu --</option>
										<?php foreach($ListRAP as $row){ ?>
											<option value="<?=$row->KdRAP?>" data-Tipe="<?=$row->Tipe?>" ><?=$row->KdRAP.' - '.$row->Uraian?></option>
										<?php } ?>
									</select>
								</td>
								<td></td>

							</tr>
						</table>

						<table border="0" style="border-collapse: collapse;margin-top:3px;" width="100%">
							<tr>
								<td width="100px" style="vertical-align: top;">Uraian</td>
								<td width="5px" class="text-center">:</td>
								<td width="300px">
									<textarea name="Uraian" id="Uraian" class="form-control form-control-sm gaya_huruf" placeholder="Uraian" required></textarea>
								</td>
								<td></td>

							</tr>
						</table>

						<table border="0" style="border-collapse: collapse;margin-top:3px;" width="100%">
							<tr>
								<td width="100px">Volume</td>
								<td width="5px" class="text-center">:</td>
								<td width="100px">
									<input type="text" name="Vol" id="Vol" class="form-control form-control-sm gaya_huruf" placeholder="Volume" value="0" required>
								</td>
								<td></td>

							</tr>
						</table>

						<table border="0" style="border-collapse: collapse;margin-top:3px;" width="100%">
							<tr>
								<td width="100px">Satuan</td>
								<td width="5px" class="text-center">:</td>
								<td width="100px">
									<input type="text" name="Uom" id="Uom" class="form-control form-control-sm gaya_huruf" placeholder="Satuan">
								</td>
								<td></td>

							</tr>
						</table>

						<table border="0" style="border-collapse: collapse;margin-top:3px;" width="100%">
							<tr>
								<td width="100px">Harga Satuan</td>
								<td width="5px" class="text-center">:</td>
								<td width="100px">
									<input type="text" name="HrgSatuan" id="HrgSatuan" class="form-control form-control-sm gaya_huruf" placeholder="Harga Satuan" value="0" required>
								</td>
								<td></td>

							</tr>
						</table>
						<hr>
						<table border="0" style="border-collapse: collapse;margin-top:3px;" width="100%">
							<tr >
								<td class="text-right"  >

									<input type="hidden" name="NoPD" value="<?=$no_urut_pd['KodeUrutPd']?>">
									

									<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">TUTUP</button>
									<button type="submit" class="btn btn-primary btn-sm btn_simpan_uraian">SIMPAN URAIAN</button>

								</td>

							</tr>
						</table>


					</div>

				</div>


			</form>
		</div>
	</div>
</div>


<div class="modal fade" id="ModalEditUraian" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<form id="formEditUraian" >

				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Form Edit Uraian</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">

					<div class="table-responsive">

						<table border="0" style="border-collapse: collapse;" width="100%">
							<tr>
								<td width="100px">No Urut</td>
								<td width="5px" class="text-center">:</td>
								<td width="100px">
									<input type="text" class="form-control form-control-sm gaya_huruf" name="NoUrut" id="NoUrut" placeholder="No Urut" readonly>

								</td>
								<td></td>

							</tr>
						</table>

						<table border="0" style="border-collapse: collapse;" width="100%">
							<tr>
								<td width="100px">Alokasi</td>
								<td width="5px" class="text-center">:</td>
								<td width="300px">
									<input type="text" class="form-control form-control-sm gaya_huruf" placeholder="Alokasi" value="<?=$Alokasi.' - '.$KeteranganAlokasi?>" disabled>

								</td>
								<td></td>

							</tr>
						</table>

						<table border="0" style="border-collapse: collapse;margin-top:3px;" width="100%">
							<tr>
								<td width="100px">Kode RAP</td>
								<td width="5px" class="text-center">:</td>
								<td width="300px">
									<input type="text" name="KdRAP" id="KdRAP" class="form-control form-control-sm" placeholder="Kode RAP" readonly required>
								</td>
								<td></td>

							</tr>
						</table>

						<table border="0" style="border-collapse: collapse;margin-top:3px;" width="100%">
							<tr>
								<td width="100px" style="vertical-align: top;">Uraian</td>
								<td width="5px" class="text-center">:</td>
								<td width="300px">
									<textarea name="Uraian" id="Uraian" class="form-control form-control-sm gaya_huruf" placeholder="Uraian" required></textarea>
								</td>
								<td></td>

							</tr>
						</table>

						<table border="0" style="border-collapse: collapse;margin-top:3px;" width="100%">
							<tr>
								<td width="100px">Volume</td>
								<td width="5px" class="text-center">:</td>
								<td width="100px">
									<input type="text" name="Vol" id="Vol" class="form-control form-control-sm gaya_huruf" placeholder="Volume" value="0" required>
								</td>
								<td></td>

							</tr>
						</table>

						<table border="0" style="border-collapse: collapse;margin-top:3px;" width="100%">
							<tr>
								<td width="100px">Satuan</td>
								<td width="5px" class="text-center">:</td>
								<td width="100px">
									<input type="text" name="Uom" id="Uom" class="form-control form-control-sm gaya_huruf" placeholder="Satuan">
								</td>
								<td></td>

							</tr>
						</table>

						<table border="0" style="border-collapse: collapse;margin-top:3px;" width="100%">
							<tr>
								<td width="100px">Harga Satuan</td>
								<td width="5px" class="text-center">:</td>
								<td width="100px">
									<input type="text" name="HrgSatuan" id="HrgSatuan" class="form-control form-control-sm gaya_huruf" placeholder="Harga Satuan" value="0" required>
								</td>
								<td></td>

							</tr>
						</table>
						<hr>
						<table border="0" style="border-collapse: collapse;margin-top:3px;" width="100%">
							<tr >
								<td class="text-right"  >

									<input type="hidden" name="NoPD" value="<?=$no_urut_pd['KodeUrutPd']?>">
									

									<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">TUTUP</button>
									<button type="submit" class="btn btn-primary btn-sm btn_update_uraian">UPDATE URAIAN</button>

								</td>

							</tr>
						</table>


					</div>

				</div>


			</form>
		</div>
	</div>
</div>

<script src="<?=base_url('assets/select2_baru.js')?>"></script>

<script>
	$(function(){

		$('#NoKO,#Bank').select2({
			// placeholder : '-- Pilih No. Kontrak --',
			dropdownCssClass: "myFont" 
		});

		// $('#formTambahUraian select[name="KdRAP"]').select2();

		$('#TglPD').change(function(){
			HitungMinggu_PrdAawl_dan_PrdAkhir();
		});

		$('#TipeForm').change(function(){
			get_ListKO();
		});

		$('#NoKO').change(function(){
			CekVendor();
		});

		$('.btn_tambah_uraian').click(function(){
			var NoKO = $('#MyForm #NoKO').val();

			if(NoKO  == ''){
				Swal.fire(
					'Alert!',
					'No KO belum dipilih',
					'info'
					);

				return false;
			}

			$('#formTambahUraian')[0].reset();
			$('#formTambahUraian #KdRAP_Add').val('').trigger('change');
			$('#formTambahUraian #Uraian_Add').val('');
			$('#formTambahUraian #KdRAP_Add').val('');
			$('#formTambahUraian #Vol_Add').val(0);
			$('#formTambahUraian #Uom_Add').val('');
			$('#formTambahUraian #HrgSatuan_Add').val(0);
			$('#ModalTambahUraian').modal('show');
		});

		var formatVol = function(num){
			var str = num.toString().replace("", ""), parts = false, output = [], i = 1, formatted = null;
			if(str.indexOf(".") > 0) {
				parts = str.split(".");
				str = parts[0];
			}
			str = str.split("").reverse();
			for(var j = 0, len = str.length; j < len; j++) {
				if(str[j] != ",") {
					output.push(str[j]);
					if(i%3 == 0 && j < (len - 1)) {
						output.push(",");
					}
					i++;
				}
			}
			formatted = output.reverse().join("");
			return("" + formatted + ((parts) ? "." + parts[1].substr(0, 3) : ""));
		};


		var formatUang = function(num){
			var str = num.toString().replace("", ""), parts = false, output = [], i = 1, formatted = null;
			if(str.indexOf(".") > 0) {
				parts = str.split(".");
				str = parts[0];
			}
			str = str.split("").reverse();
			for(var j = 0, len = str.length; j < len; j++) {
				if(str[j] != ",") {
					output.push(str[j]);
					if(i%3 == 0 && j < (len - 1)) {
						output.push(",");
					}
					i++;
				}
			}
			formatted = output.reverse().join("");
			return("" + formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
		};

		$("#formTambahUraian input[name='Vol'], #formEditUraian input[name='Vol'] ").keyup(function(e){
			$(this).val(formatVol($(this).val()));

		});

		$("#formTambahUraian input[name='Vol'], #formEditUraian input[name='Vol']").focus(function(){
			if (this.value == 0) {
				$(this).val('');
			}
			
		});


		$("#formTambahUraian input[name='Vol'], #formEditUraian input[name='Vol']").focusout(function(){
			if (this.value == '') {
				$(this).val(0);
			}
		});

		$("#formTambahUraian input[name='HrgSatuan'], #formEditUraian input[name='HrgSatuan']").keyup(function(e){
			$(this).val(formatUang($(this).val()));

		});

		$("#formTambahUraian input[name='HrgSatuan'], #formEditUraian input[name='HrgSatuan']").focus(function(){
			if (this.value == 0) {
				$(this).val('');
			}
			
		});


		$("#formTambahUraian input[name='HrgSatuan'], #formEditUraian input[name='HrgSatuan']").focusout(function(){
			if (this.value == '') {
				$(this).val(0);
			}
		});

		$('#formTambahUraian').submit(function(event) {
			event.preventDefault();

			// $('#formTambahUraian #KdRAP').val();
			if ($('#formTambahUraian #KdRAP option:selected').attr('data-Tipe') == 'Header') {
				toastr.info('tidak bisa pilih rap dengan tipe header', 'Alert!');
				return false;
			}

			

			jQuery.ajax({
				url: '<?= site_url('Entry/PDPJ/FrmPDKeu/SimpanUraian') ?>',
				type: 'POST',
				dataType: 'HTML',
				data:$(this).serialize(),
				beforeSend : function(){
					$('.btn_simpan_uraian').attr('disabled',true);
				},
				complete: function(xhr, textStatus) {
					$('.btn_simpan_uraian').attr('disabled',false);
					tutup_modal();
				},
				success: function(data, textStatus, xhr) {

					if(data == 'berhasil'){
						toastr.success('Sukses', '');
					}else if(data == 'gagal'){
						toastr.info('Gagal', '');
					}else{
						toastr.error('Error', '');
					}
				},
				error: function(xhr, textStatus, errorThrown) {
					toastr.error('Error', '');
				}
			});
			
		});

		$('#MyForm').submit(function(event) {
			event.preventDefault();

			var urlkembali = $(this).attr('data-urlkembali');
			var TipeForm = $('#TipeForm option:selected').val();

			jQuery.ajax({
				url: '<?= site_url('Entry/PDPJ/FrmPDKeu/Aksi_Simpan_PD') ?>',
				type: 'POST',
				dataType: 'HTML',
				data: $(this).serialize(),
				beforeSend : function(){
					$('.btnSimpanPD').attr('disabled',true);
				},
				complete: function(xhr, textStatus) {
					$('.btnSimpanPD').attr('disabled',false);
				},
				success: function(data, textStatus, xhr) {

					// alert(data);
					// console.log(data);
					// return false;

					if (data == 'kelebihan_PD') {
						alert('total centang melebihi total PD');
						return false;
					}

					if (data == 'belum_pilih_invoice') {
						alert('Belum pilih Invoice');
						return false;
					}

					if (data == 'tipe_form_belum_pilih_invoice') {
						alert('Tipe Form '+TipeForm+' belum pilih invoice');
						return false;
					}



					if(data == 'uraian_kosong'){
						toastr.info('uraian belum diisi','Info!');
						return false;
					}

					if(data == 'kelebihan'){
						toastr.info('Total bayar invoice melebihi total invoice.','Info!');
						return false;
					}


					if(data == 'berhasil'){
						// toastr.success('Sukses', '');
						toastr.success('data telah tersimpan', 'Saved! ', {
							timeOut: 1000,
							preventDuplicates: true,
							positionClass: 'toast-top-center',

							onHidden: function() {
								window.location.href = urlkembali;
							}
						});

					}else if(data == 'gagal'){
						toastr.info('Gagal', '');
					}else{
						toastr.error('Error', '');
					}
				},
				error: function(xhr, textStatus, errorThrown) {
					toastr.error('Error', '');
				}
			});
			
		});

		$('#formEditUraian').submit(function(event) {
			event.preventDefault();

			if ($('#formEditUraian #KdRAP option:selected').attr('data-Tipe') == 'Header') {
				toastr.info('tidak bisa pilih rap dengan tipe header', 'Alert!');
				return false;
			}

			jQuery.ajax({
				url: '<?= site_url('Entry/PDPJ/FrmPDKeu/EditUraian') ?>',
				type: 'POST',
				dataType: 'HTML',
				data:$(this).serialize(),
				beforeSend : function(){
					$('.btn_update_uraian').attr('disabled',true);
				},
				complete: function(xhr, textStatus) {
					$('.btn_update_uraian').attr('disabled',false);
					tutup_modal_EditUraian();
				},
				success: function(data, textStatus, xhr) {

					// alert(data);
					// return false;

					if(data == 'berhasil'){
						toastr.success('Sukses', '');
					}else if(data == 'gagal'){
						toastr.info('Gagal', '');
					}else{
						toastr.error('Error', '');
					}
				},
				error: function(xhr, textStatus, errorThrown) {
					toastr.error('Error', '');
				}
			});
			
		});


	})

function HitungMinggu_PrdAawl_dan_PrdAkhir(){

	var TglPD = $('#TglPD').val();

	var JobNo = $('#JobNo').val();

	jQuery.ajax({
		url: '<?= site_url('Entry/PDPJ/FrmPDKeu/dapat_data_ketika_TglPD_berubah_js') ?>',
		type: 'POST',
		dataType: 'JSON',
		data: {
			'JobNo': JobNo,
			'TglPD' : TglPD,
		},
		beforeSend : function(){
			$('.loading_atas').show();
		},
		complete: function(xhr, textStatus) {
			$('.loading_atas').hide();
		},
		success: function(data, textStatus, xhr) {

			if (data.status == 'berhasil') {

				$('#PrdAwal').val(data.PrdAwal);
				$('#PrdAkhir').val(data.PrdAkhir);
				$('#Minggu').val(data.Minggu);

			}
		},
		error: function(xhr, textStatus, errorThrown) {
			toastr.error('Error', '');
		}
	});
}

function get_ListKO() {

	var JobNo = $('#JobNo').val();
	var NoPD = $('#NoPD').val();
	var Alokasi = $('#Alokasi').val();
	var TipeForm = $('#TipeForm :selected').val();

	$.ajax({
		type: "post",
		url: "<?= site_url('Entry/PDPJ/FrmPDKeu/get_ListKO') ?>",
		data: {
			'JobNo': JobNo,
			'NoPD': NoPD,
			'Alokasi': Alokasi,
			'TipeForm' : TipeForm,
		},
		dataType: "html",
		beforeSend: function() {
			$('#TipeForm').attr('disabled',true);
			$('#NoKO').html('');
		},
		complete : function(){
			$('#TipeForm').attr('disabled',false);
			CekVendor()
		},
		success: function(data) {
			if(data=='error'){
				toastr.error('Something wrong ?!', 'silahkan coba lagi');
				return false;
			}

			$('#NoKO').html(data);
		},
		error: function() {
			toastr.error('Error', '');
		}
	});

}

function CekVendor(){

	var NoKO = $('#NoKO option:selected').val();
	var VendorId = $('#NoKO option:selected').attr('data-VendorId');
	var JobNo = $('#NoKO option:selected').attr('data-JobNo');
	var Alokasi = $('#NoKO option:selected').attr('data-Alokasi');

	$.ajax({
		type: "post",
		url: "<?= site_url('Entry/PDPJ/FrmPDKeu/getVendor') ?>",
		data: {
			'NoKO': NoKO,
			'VendorId': VendorId,
			'JobNo' : JobNo,
			'Alokasi' : Alokasi
		},
		dataType: "json",
		beforeSend: function() {
			$('#NoKO').attr('disabled',true);
		},
		complete : function(){
			$('#NoKO').attr('disabled',false);
			pindah_data();
		},
		success: function(data) {
			$('#MyForm #Nama').val('');
			$('#MyForm #Alamat').val('');
			$('#MyForm #Telepon').val('');
			$('#MyForm #NPWP').val('');
			$('#MyForm #NoRek').val('');
			$('#MyForm #AtasNama').val('');
			$('#MyForm #Bank').val('').trigger('change');

			$('#MyForm #Nama').val(data.Nama);
			$('#MyForm #Alamat').val(data.Alamat);
			$('#MyForm #Telepon').val(data.Telepon);
			$('#MyForm #NPWP').val(data.NPWP);
			$('#MyForm #NoRek').val(data.NoRek);
			$('#MyForm #AtasNama').val(data.AtasNama);
			$('#MyForm #Bank').val(data.Bank).trigger('change');
		},
		error: function() {
			toastr.error('Error', '');
		}
	});
}

function pindah_data(){
	var NoPD = $('#NoPD').val();
	var JobNo = $('#JobNo').val();
	var Alokasi = $('#Alokasi').val();
	var NoKO = $('#NoKO').val();
	var VendorNm = $('#MyForm input[name="Nama"]').val();


	if (NoKO == '') {
		toastr.error('Alert!', 'No Kontrak masih kosong');
		return false;
	}

	jQuery.ajax({
		url: '<?= site_url('Entry/PDPJ/FrmPDKeu/pindah_data') ?>',
		type: 'POST',
		dataType: 'HTML',
		data: {
			'NoPD': NoPD,
			'NoKO' : NoKO,
			'JobNo': JobNo,
			'Alokasi' : Alokasi,
			'VendorNm' : VendorNm,
		},

		beforeSend : function(){
		},
		complete: function(xhr, textStatus) {
		},
		success: function(data, textStatus, xhr) {

			if(data == 'berhasil'){
				tampilkan_tbl_PdDtl();
			}else if(data == 'gagal'){
				toastr.info('Alert!', 'terjadi kesalahan');
			}else{
				toastr.error('Error', '');
			}
		},
		error: function(xhr, textStatus, errorThrown) {
			toastr.error('Error', '');
		}
	});

}


function tampilkan_tbl_PdDtl(TabKirim=''){

	var NoPD = $('#NoPD').val();
	var JobNo = $('#JobNo').val();
	var Alokasi = $('#Alokasi').val();
	var NoKO = $('#NoKO').val();
	var TipeForm = ($('#TipeForm option:selected').val()) ? $('#TipeForm option:selected').val() : 'kosong';
	var VendorNm = $('#MyForm input[name="Nama"]').val();


	if (NoKO == '') {
		toastr.error('Alert!', 'No Kontrak masih kosong');
		return false;
	}

	jQuery.ajax({
		url: '<?= site_url('Entry/PDPJ/FrmPDKeu/tampilkan_tbl_PdDtl') ?>',
		type: 'POST',
		dataType: 'HTML',
		data: {
			'NoPD': NoPD,
			'NoKO' : NoKO,
			'JobNo': JobNo,
			'Alokasi' : Alokasi,
			'TipeForm' : TipeForm,
			'TabKirim' : TabKirim,
			'VendorNm' : VendorNm,
		},
		beforeSend : function(){
			$('.loading_tbl').show();
			$("#table_detail_disini").html('');
		},
		complete: function(xhr, textStatus) {
			$('.loading_tbl').hide();
				// table_DataTable();
		},
		success: function(data, textStatus, xhr) {
			$('#table_detail_disini').html(data);
		},
		error: function(xhr, textStatus, errorThrown) {
			toastr.error('Error', '');
		}
	});
}

function table_DataTable(){
	$('#tblsaya').DataTable();
}

function tutup_modal(){
	$('#ModalTambahUraian').modal('hide');
	tampilkan_tbl_PdDtl();
}
function tutup_modal_EditUraian(){
	$('#ModalEditUraian').modal('hide');
	tampilkan_tbl_PdDtl();
}

</script>