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
									<td class="bg-gray active color-palette text-center text-bold huruf12" style="padding-top: 5px;padding-bottom: 5px;">Penerima PO</td>
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
										<textarea class="form-control input-sm huruf12" readonly><?=$dataVendor->Alamat?></textarea>
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

							<table width="100%" border="0" class="huruf12 marginatas3">
								<tr>
									<td width="60px">UP</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-sm huruf12" value="<?=$dataVendor->ContactPerson?>" readonly>
									</td>
								</tr>
							</table>


							
						</div>
					</div>

					<div class="col-sm-4">
						<div class="table-responsive">
							<table width="100%" border="0" class="huruf12 marginatas3">
								<tr>
									<td width="90px">Tgl PO</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-sm huruf12" value="<?=tgl_baru($dataPO->TglKO)?>" readonly>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas3">
								<tr>
									<td width="90px">No. Kontrak</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-sm huruf12" value="<?=$dataPO->NoKO?>" readonly>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas3">
								<tr>
									<td width="90px">Job No</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-sm huruf12" value="<?=$dataPO->JobNo.' - '.$dataPO->JobNm?>" readonly>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas3">
								<tr>
									<td width="90px">No SPR</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-sm huruf12" value="<?=($dataPO->NoSPR == NULL || $dataPO->NoSPR == '') ? '' : $dataPO->NoSPR ?>" readonly>
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

					<div class="col-sm-4">

						<div class="table-responsive">
							<table width="100%" border="0" style="margin-bottom:5px;">
								<tr>
									<td class="bg-gray active color-palette text-center text-bold huruf12" style="padding-top: 5px;padding-bottom: 5px;">Lokasi Pengiriman</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas3">
								<tr>
									<td width="60px">Nama</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-sm huruf12" value="<?=$dataPO->NamaKirim?>" readonly>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas3">
								<tr>
									<td width="60px">Alamat</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<textarea class="form-control input-sm huruf12" disabled><?=$dataPO->AlamatKirim?></textarea>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas3">
								<tr>
									<td width="60px">Telepon</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-sm huruf12" value="<?=$dataPO->TeleponKirim?>" readonly>
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas3">
								<tr>
									<td width="60px">Tgl Pengiriman</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-sm huruf12" value="<?=($dataPO->TglPengiriman == NULL || $dataPO->TglPengiriman=='') ? '' : $dataPO->TglPengiriman ?>" readonly>
									</td>
								</tr>
							</table>

							<hr style="border: 1px solid black;">
							<table width="100%" border="0" class="huruf12 marginatas3">
								<tr>
									<td></td>
									<td class="text-right" width="150px"><input type="checkbox" id="cek" value="1" <?=($dataPO->MaterialApproval==1) ? 'checked' : '' ?> disabled> Material Approval </td>
									<td class="text-right" width="50px"><input type="checkbox" id="cek" value="1" <?=($dataPO->RAP==1) ? 'checked' : '' ?> disabled> RAP </td>
									
								</tr>
							</table>

						</div>
					</div>

				</div>

				<!-- == START ORDER LIST == -->
				<p></p>

				<div class="row">
					<div class="col-sm-10">
						<div class="table-responsive">
							<table width="100%" border="0">
								<tr>
									<th class="text-center bg-gray disabled color-palette" style="padding-top:3px;padding-bottom:3px;">O R D E R L I S T</th>
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
				<!-- == END ORDER LIST == -->

				<div class="row">
					<div class="col-sm-7">

					</div>
					<div class="col-sm-10">
						<div class="table-responsive">

							<table width="100%" border="0" >
								<tr>
									<td></td>
									<td width="100px">Disc (%)</td>
									<td width="200px" class="text-right">
										<input type="text" class="form-control input-sm huruf12 text-right" value="<?=$DiscPersen?>"  readonly>
									</td>
									<td width="10px"></td>
									<td width="200px" class="text-right">
										<input type="text" class="form-control input-sm huruf12 text-right" value="<?=$DiscNominal?>"  readonly>
									</td>
									<td width="100px" style="padding-left: 3px !important;"></td>
								</tr>
							</table>



							<table width="100%" border="0" class="marginatas5" >
								<tr>
									<td></td>
									<td width="100px"></td>
									<td width="200px" class="text-center">PPN</td>
									<td width="10px"></td>
									<td width="200px" class="text-right">
										<input type="text" class="form-control input-sm huruf12 text-right" value="<?=$PPN?>" readonly>
									</td>
									<td width="100px" style="padding-left: 3px !important;"><input type="checkbox" value="1" <?=($dataPO->OverridePPN==1) ? 'checked' : ''?> disabled>Override PPN</td>
								</tr>
							</table>

							<table width="100%" border="0" class="marginatas5" >
								<tr>
									<td></td>
									<td width="100px"></td>
									<td width="200px" class="text-center">Grand Total</td>
									<td width="10px"></td>
									<td width="200px" class="text-right">
										<input type="text" class="form-control input-sm huruf12 text-right" value="<?=$GrandTotal?>" readonly>
									</td>
									<td width="100px" style="padding-left: 3px !important;"></td>
								</tr>
							</table>

							<table width="100%" border="0" class="marginatas5" >
								<tr>
									<td></td>
									<td width="100px"></td>
									<td width="200px" class="text-center">Mengurangi KO</td>
									<td width="10px"></td>
									<td width="200px" class="text-right">
										<input type="text" class="form-control input-sm huruf12 text-right" value="<?=$dataPO->PotongKO?>" readonly>
									</td>
									<td width="100px" style="padding-left: 3px !important;"></td>
								</tr>
							</table>

						</div>
					</div>
				</div>


			</div>
			<div class="box-body">

			</div>
		</div>
	</div>

</div>

<div class="clearfix"></div>