<style type="text/css">

	.huruf12{
		font-size: 13px !important;
	}
	.huruf14{
		font-size: 14px !important;
	}
	.huruf15{
		font-size: 15px !important;
	}
	.huruf16{
		font-size: 16px !important;
	}
	.border_merah{
		border:1px solid red;
	}
	.border_biasa{
		border:1px solid;
	}

	.marginatas3{
		margin-top: 3px;
	}

	.marginatas5{
		margin-top: 5px;
	}
</style>
<div class="content-wrapper">

	<section class="content-header">
		<h1 style="text-decoration: underline;"><?=$judul?></h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?=$judul?></li>
		</ol>
		<br>
		<a href="<?=site_url('Entry/Procurement/KOAddendumRevisi_C/index/'.$JobNo)?>" class="btn btn-sm btn-danger "><i class="fa fa-arrow-left"></i> BACK</a>
	</section>

	<div class="content">
		<div class="box box-primary">

			<!-- === start box header == -->
			<div class="box-header">

				<div class="row">

					<div class="col-sm-4">
						<?=(!$this->session->userdata('message')) ? '' : $this->session->userdata('message')?>
						<div class="table-responsive">
							<table width="100%" border="0" style="margin-bottom:5px;">
								<tr>
									<td class="bg-gray active color-palette text-center text-bold huruf12" style="padding-top: 5px;padding-bottom: 5px;">Penerima Kontrak</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas3">
								<tr>
									<td width="60px">ID</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-sm huruf12" value="<?=$dataVendor->VendorNm?>" readonly>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas3">
								<tr>
									<td width="60px">Nama</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-sm huruf12" value="<?=$dataVendor->VendorNm?>" readonly>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas3">
								<tr>
									<td width="60px">Alamat</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-sm huruf12" value="<?=$dataVendor->Alamat?>" readonly>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas3">
								<tr>
									<td width="60px">Telepon</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-sm huruf12" value="<?=$dataVendor->Telepon1?>" readonly>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas3">
								<tr>
									<td width="60px">NPWP</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-sm huruf12" value="<?=$dataVendor->NPWP?>" readonly>
									</td>
								</tr>
							</table>


							
						</div>
					</div>

					<div class="col-sm-4">
						<div class="table-responsive">
							<table width="100%" border="0" class="huruf12 marginatas3">
								<tr>
									<td width="90px">Tgl Kontrak</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-sm huruf12" value="<?=tgl_baru($dataKO->TglKO)?>" readonly>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas3">
								<tr>
									<td width="90px">No. Kontrak</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-sm huruf12" value="<?=$dataKO->NoKO?>" readonly>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas3">
								<tr>
									<td width="90px">Job No</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-sm huruf12" value="<?=$dataKO->JobNo.' - '.$dataKO->JobNm?>" readonly>
									</td>
								</tr>
							</table>

							
							<table width="100%" border="0" class="huruf12 marginatas3">
								<tr>
									<td width="90px">Kategori</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-sm huruf12" value="<?=$dataVendor->Kategori?>" readonly>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas3">
								<tr>
									<td width="90px">Bidang Usaha</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-sm huruf12" value="<?=$dataVendor->BidangUsaha?>" readonly>
									</td>
								</tr>
							</table>

						</div>
					</div>

				</div>
				<p></p>

				<div class="row">
					<div class="col-sm-10">
						<div class="table-responsive">
							<table width="100%" border="0">
								<tr>
									<th class="text-center bg-gray disabled color-palette" style="padding-top:3px;padding-bottom:3px;">RINCIAN PEKERJAAN</th>
								</tr>
							</table>

							<table class="table table-bordered table-striped table-hover huruf12" width="100%" border="1" style="margin-top:7px;">
								<thead>
									<tr class="bg-primary  color-palette">
										<th class="text-center" width="20px">No</th>
										<th class="text-center" width="100px">Kode RAP</th>
										<th class="text-center">Uraian</th>
										<th class="text-center" width="100px">Vol</th>
										<th class="text-center" width="100px">Sat</th>
										<th class="text-center" width="150px">Hrg Satuan (Rp)</th>
										<th class="text-center" width="150px">Jumlah Harga (Rp)</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									foreach ($dataKoDtl as $dataKoDtl):

										?>
										<tr>
											<td class="text-center"><?=$dataKoDtl->NoUrut?></td>
											<td><?=$dataKoDtl->KdRAP?></td>
											<td><?=$dataKoDtl->Uraian?></td>
											<td class="text-right"><?=UbahDecimal_Nol_Tiga($dataKoDtl->Vol)?></td>
											<td class="text-center"><?=$dataKoDtl->Uom?></td>
											<td class="text-right"><?=number_format($dataKoDtl->HrgSatuan)?></td>
											<td class="text-right"><?=number_format($dataKoDtl->JumlahHarga)?></td>
											
										</tr>
									<?php endforeach ?>
								</tbody>
								<tfoot>
									<tr class="bg-primary color-palette">
										<th colspan="5" class="text-right">Sub Total</th>
										<th colspan="2" class="text-right"><?=number_format($SubTotal)?></th>
									</tr>
								</tfoot>
							</table>

						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-7">

					</div>
					<div class="col-sm-3">
						<div class="table-responsive">

							<table width="100%" border="0" >
								<tr>
									<td width="100px">Dikurangi KO</td>
									<td width="150px" class="text-right">
										<input type="text" class="form-control input-sm huruf12 text-right" value="<?=$PotongKO?>" readonly>
									</td>
								</tr>
							</table>

							<table width="100%" border="0"  style="margin-top:5px;">
								<tr>
									<td width="100px">Total Pengurang</td>
									<td width="150px" class="text-right">
										<input type="text" class="form-control input-sm huruf12 text-right" value="<?=number_format($TxtTtlPengurang)?>" readonly>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" style="margin-top:5px;">
								<tr>
									<td width="100px">Grand Total</td>
									<td width="150px" class="text-right">
										<input type="text" class="form-control input-sm huruf12 text-right" value="<?=number_format($GrandTotal)?>" readonly>
									</td>
								</tr>
							</table>

						</div>
					</div>
				</div>


			</div>
			<!-- === end box header == -->

			<!-- === start box body == -->
			<div class="box-body">

				<div class="row">

					<div class="col-sm-4">
						<div class="table-responsive">
							<table width="100%" border="0">
								<tr>
									<td>1.Wajib memenuhi persyaratan Kesehatan & Keselamatan Kerja (K3) <input type="checkbox" id="cek" value="1" checked disabled></td>
								</tr>
							</table>

							<table width="100%" border="0" class="marginatas5">
								<tr>
									<td>2. Syarat Teknis</td>
								</tr>
								<tr>
									<td><textarea class="form-control input-sm" rows="4"  disabled  ><?=$dataKO->SyaratTeknis?></textarea></td>
								</tr>
							</table>

							<table width="100%" border="0" class="marginatas5">
								<tr>
									<td>3. Jadwal Pengiriman</td>
								</tr>
								<tr>
									<td><textarea class="form-control input-sm" rows="4"  disabled  ><?=$dataKO->JadwalPengiriman?></textarea></td>
								</tr>
							</table>

							<table width="100%" border="0" class="marginatas5">
								<tr>
									<td>4. Jadwal Pembayaran</td>
								</tr>
								<tr>
									<td><textarea class="form-control input-sm" rows="5"  disabled  ><?=$dataKO->JadwalPembayaran?></textarea></td>
								</tr>
							</table>

						</div>
					</div>


					<div class="col-sm-4">
						<div class="table-responsive">


							<table width="100%" border="0">
								<tr>
									<td>5. Syarat Pembayaran</td>
								</tr>
								<tr>
									<td width="">
										<input type="checkbox" id="INV"  <?= (CekBuktiPendukung_Kedua($dataKO->SyaratPembayaran, 'INV') == TRUE) ? 'checked' : '' ?> disabled="disabled">
										<label for="INV" style="font-weight: normal;">Invoice/Kwitansi</label>
									</td>
									<td width="">
										<input type="checkbox" id="FP" <?= (CekBuktiPendukung_Kedua($dataKO->SyaratPembayaran, 'FP') == TRUE) ? 'checked' : '' ?> disabled="disabled" >
										<label for="FP" style="font-weight: normal;">Faktur Pajak</label>
									</td>
								</tr>
								<tr>
									<td width="">
										<input type="checkbox" id="SJ" <?= (CekBuktiPendukung_Kedua($dataKO->SyaratPembayaran, 'SJ') == TRUE) ? 'checked' : '' ?> disabled="disabled" >
										<label for="SJ" style="font-weight: normal;">Surat Jalan/Tanda Terima Lapangan</label>
									</td>
									<td width="">
										<input type="checkbox" id="BAP" <?= (CekBuktiPendukung_Kedua($dataKO->SyaratPembayaran, 'BAP') == TRUE) ? 'checked' : '' ?>  disabled="disabled" >
										<label for="BAP" style="font-weight: normal;">Berita Acara Pembayaran</label>
									</td>
								</tr>

								<tr>
									<td width="">
										<input type="checkbox" id="PO" <?= (CekBuktiPendukung_Kedua($dataKO->SyaratPembayaran, 'PO') == TRUE) ? 'checked' : '' ?>  disabled="disabled" >
										<label for="PO" style="font-weight: normal;">Copy PO</label>
									</td>
									<td width="">
										<input type="checkbox" id="BAOP" <?= (CekBuktiPendukung_Kedua($dataKO->SyaratPembayaran, 'BAOP') == TRUE) ? 'checked' : '' ?>  disabled="disabled" >
										<label for="BAOP" style="font-weight: normal;">Berita Acara Opname Pekerjaan</label>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="marginatas5">
								<tr>
									<td>6. Sanksi</td>
								</tr>
								<tr>
									<td><textarea class="form-control input-sm" rows="5"  disabled  ><?=$dataKO->Sanksi?></textarea></td>
								</tr>
							</table>

							<table width="100%" border="0" class="marginatas5">
								<tr>
									<td>7. Keterangan Lainnya</td>
								</tr>
								<tr>
									<td><textarea class="form-control input-sm" rows="5"  disabled  ><?=$dataKO->Keterangan?></textarea></td>
								</tr>
							</table>


						</div>
					</div>



				</div>

			</div>
			<!-- === end box body == -->

		</div>
	</div>

</div>

<div class="clearfix"></div>
<script src="<?=base_url('assets/select2_baru.js')?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.select2').select2(); 
	});
</script>