<style>
	.mykonten {
		/*		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;*/
		/*		font-size: x-small !important;*/
		font-size: 12px;
	}

	.ukuran_select2 {
		font-size: 12px !important;
		/*		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;*/
		/*font-family: Arial, Helvetica, sans-serif;*/
	}

	.atur_ukuran {
		font-size: 13px !important;
		/*		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;*/
	}

	input[type="text"] {
		font-size: 13px !important;
		/*		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;*/
	}

	input[type="date"] {
		font-size: 13px !important;
		/*		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;*/
	}

	textarea {
		font-size: 13px !important;
		/*		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;*/
	}

	label {
		font-size: 13px !important;
		/*		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;*/
	}

	.huruf14 {
		font-size: 14px !important;
	}
</style>

<?php error_reporting(E_ALL); ?>

<div class="content-wrapper">

	<section class="content-header">
		<table width="100%" border="0">
			<tr>
				<td style="font-size: 18px !important;" class="text-bold atur_ukuran"><?= (isset($judul)) ? $judul : '' ?></td>
				<td style="font-size: 18px !important;" class="text-bold text-right atur_ukuran"><?= $datakontrak->JobNo . ' - ' . $datakontrak->JobNm . '<br />' . $datakontrak->Lokasi ?></td>
			</tr>
			<tr>
				<td>
					<a href="<?= site_url('job/sub_job/' . $datakontrak->JobNo) ?>" class="btn btn-md btn-warning text-black"><i class="fa fa-arrow-left"></i> MENU INPUTAN</a>
					<a href="<?= site_url('job/dipa/' . $datakontrak->JobNo) ?>" class="btn btn-md btn-primary text-white">NEXT TO DIPA & TERMIN &nbsp <i class="fa fa-arrow-right"></i></a>

				</td>
				<td style="text-align: right;padding-top: 10px !important;">
					<a href="<?= site_url('job/sub_job/' . $datakontrak->JobNo) ?>" class="btn btn-sm bg-gray hidden"><i class="fa fa-arrow-left"></i> Back</a>
				</td>
			</tr>
		</table>

	</section>


	<section class="content">
		<div class="container-fluid row">

			<?= ($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>

			<form action="<?= site_url('Job/updateDataKontrak'); ?>" method="post" id="FormDataKontrak">

				<div class="box box-warning">
					<div class="box-header with-border">
						<h1 class="box-title">Data Kontrak/Addendum</h1>

						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div>
					<div class="box-body">
						<?php
						$nilaikontrak = 0;
						$hitung_netto = 0;
						?>
						<?php foreach ($dataAddendum as $da) : ?>


							<?php
							$nilaikontrak = $da->Bruto;
							if (CariKataLoan($da->SumberDana) != 'LOAN') {
								$hitung_netto1 = $nilaikontrak / dapatkan_PPN();
								$hitung_netto = $hitung_netto1 * dapatkan_PPH();
								$nilaikontrak = $hitung_netto;
							} else {
								$nilaikontrak = $nilaikontrak;
							}

							?>

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>No Kontrak / Addendum</label>
										<input type="text" name="JobNo" id="JobNo" value="<?php echo $da->JobNo ?>" hidden>
										<input type="text" name="NoKontrak" value="<?php echo $da->NoKontrak ?>" id="NoKontrak" class="form-control">

										<label>Tahun Anggaran</label>
										<input type="text" name="TA" value="<?php echo $da->TahunAnggaran  ?>" id="TA" class="form-control" readonly>

										<label>HPS (Rp)</label>
										<input type="text" class="form-control" name="HPS" id="HPS" value="<?= number_format($da->HPS) ?>" >

										<label>Nilai Kontrak</label>
										<input type="text" class="form-control" name="NilaiKontrak" id="NilaiKontrak"  value="<?php echo number_format($da->Bruto) ?>" >

										<label>Pagu Bruto Kontrak</label>
										<input type="text" class="form-control" name="PaguBrutoKontrak" id="PaguBrutoKontrak" onkeypress="return hanyaAngka(event)" value="<?= number_format($da->PaguBruto) ?>">


										

										<label>Addendum Ke</label>
										<input type="text" name="JobNm" value="<?php echo $da->AddendumKe ?>" id="Addendumke" class="form-control" readonly>
										<label>Tgl Kontrak / Addendum</label>
										<input type="date" class="form-control" name="TglKontrak" id="TglKontrak" value="<?php echo $da->TglKontrak ?>">

										<label>Masa Pelaksanaan</label>
										<input type="number" class="form-control" name="MasaPelaksanaan" id="MasaPelaksanaan" value="<?php echo $da->Hari ?>">
										<label>Masa Pemeliharaan</label>
										<input type="number" class="form-control" name="MasaPemeliharaan" id="MasaPemeliharaan" value="<?php echo $da->Minggu ?>">
										<label>Keterangan Addendum</label>
										<textarea class="form-control" name="KeteranganAdd" rows="5"><?php echo $da->RemarkAddendum ?></textarea>


									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label>Tgl Mulai Kontrak</label>
										<input type="date" class="form-control" value="<?php echo $da->PrdAwal ?>" name="PrdAwal" id="PrdAwal">
										<label>Tgl Selesai Kontrak</label>
										<input type="date" class="form-control" name="PrdAkhir" id="PrdAkhir" value="<?php echo $da->PrdAkhir ?>">
										<label>Sumber Dana</label>
										<input type="text" class="form-control" name="Sumberdana" id="Sumberdana" value="<?php echo $da->SumberDana ?>">
										<label>Penawaran Netto (Rp) / Excl PPN</label>


										<!-- <input type="text" class="form-control" id="PPN_Rupiah" value="0" > -->
										<!-- <input type="text" class="form-control" id="PPh_Rupiah" value="0" > -->
										<input type="text" class="form-control" name="PenawaranNetto" id="PenawaranNetto" value="<?= number_format($nilaikontrak) ?>" >


										<label>Netto Kontrak (Rp) / Excl PPN + PPH </label>
										<input type="text" class="form-control" name="KontrakNetto" id="KontrakNetto" value="<?= number_format($nilaikontrak)  ?>" >

										<label>Pagu Netto Kontrak </label>
										<input type="text" class="form-control" name="PaguNettoKontrak" id="PaguNettoKontrak"  value="<?php echo uang_koma_genap_decimal($da->PaguNetto) ?>">

										<label>Bruto Kontrak </label>
										<input type="text" class="form-control" name="BrutoKontrak" id="BrutoKontrak"  value="<?php echo uang_koma_genap_decimal($da->Bruto) ?>" >


										<label>Ringkasan Lingkup Kerja</label>
										<textarea class="form-control" rows="5" name="RingPek" id="RingPek"><?php echo $da->RingkasanPekerjaan ?></textarea>
										<label>Nama PPK</label>
										<input type="text" Class="form-control" name="NamaPPK" rows="5" id="NamaPPK" value="<?php echo $da->NamaPPK; ?>">
										<label>Alamat Kantor PPK</label>
										<textarea class="form-control" rows="5" name="AlamatPPK" id="AlamatPPK"><?php echo $da->AlamatPPK; ?></textarea>
									</div>
								</div>






							</div>
						<?php endforeach; ?>
					</div>
					<div class="box-footer">
						<button type="submit" class="btn btn-primary"><i class="fa fa-save"> &nbsp UPDATE</i></button>
						<button type="button" data-target="#modaladdendum" data-toggle="modal" class="btn btn-success pull-right"><i class="fa fa-plus"> TAMBAH ADDENDUM </i></button>
					</div>
				</div>
			</form>

			<form action="<?= site_url('Job/tambahAddendum') ?>" method="post" id="FormModal_DataKontrak">
				<div class="modal fade" id="modaladdendum">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title">Input Data Addendum </h4>
								</div>
								<div class="box-body">
									<?php foreach ($dataAddendum as $da) : ?>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">

													<label>No Kontrak / Addendum</label>
													<input type="text" name="JobNo" id="JobNo_tambah" value="<?php echo $da->JobNo ?>" hidden>
													<input type="text" name="NoKontrak" value="<?php echo $da->NoKontrak ?>" id="NoKontrak" class="form-control">

													<label>Tahun Anggaran</label>
													<input type="text" name="TahunAnggaran" value="<?php echo $da->TahunAnggaran  ?>" id="TA" class="form-control">

													<label>HPS (Rp)</label>
													<input type="text" class="form-control" name="HPS" id="HPS"  value="<?= number_format($da->HPS) ?>">

													<!-- <input type="text" class="form-control" id="PPN_Rupiah"> -->
													<!-- <input type="text" class="form-control" id="PPh_Rupiah"> -->
													<label>Nilai Kontrak</label>
													<input type="text" class="form-control" name="Bruto" id="NilaiKontrak"  value="<?= number_format($da->Bruto)  ?>">

													<label>Addendum Ke</label>
													<input type="text" name="AddendumKe" value="<?php echo $da->AddendumKe + "1" ?>" id="Addendumke" class="form-control">

													<label>Tgl Mulai Kontrak</label>
													<input type="date" class="form-control" value="<?php echo $da->PrdAwal ?>" name="PrdAwal" id="PrdAwal">

													<label>Tgl Selesai Kontrak</label>
													<input type="date" class="form-control" name="PrdAkhir" id="TglAddendum2" value="<?php echo $da->PrdAkhir ?>">

													<label>Masa Pelaksanaan</label>
													<input type="spinner" class="form-control" name="Hari" id="MasaPelaksanaan" value="<?php echo $da->Hari ?>">

													<label>Keterangan Addendum</label>
													<textarea class="form-control" name="RemarkAddendum" rows="5"><?php echo $da->RemarkAddendum ?></textarea>


												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label>Tgl Kontrak / Addendum</label>
													<input type="date" class="form-control" name="TglKontrak" id="TglKontrak" value="<?php echo $da->TglKontrak ?>">
													<label>Sumber Dana</label>
													<input type="text" class="form-control" name="SumberDana" id="Sumberdana" value="<?php echo $da->SumberDana ?>">
													<label>Penawaran Netto (Rp) / Excl PPN</label>
													<input type="text" class="form-control" name="PenawaranNetto" id="PenawaranNetto" value="<?= number_format($da->Bruto / 1.1) ?>">
													<label>Netto Kontrak (Rp) / Excl PPN + PPH </label>
													<input type="text" class="form-control" name="KontrakNetto" id="KontrakNetto" value="<?= number_format($nilaikontrak) ?>">
													<label>Masa Pemeliharaan</label>
													<input type="spinner" class="form-control" name="Minggu" id="MasaPemeliharaan" value="<?php echo $da->Minggu ?>">
													<label>Ringkasan Lingkup Kerja</label>
													<textarea class="form-control" rows="5" name="RingkasanPekerjaan" id="RingPek"><?php echo $da->RingkasanPekerjaan ?></textarea>
													<label>Nama PPK</label>
													<input type="text" Class="form-control" name="NamaPPK" rows="5" id="NamaPPK" value="<?php echo $da->NamaPPK; ?>">
													<label>Alamat Kantor PPK</label>
													<textarea class="form-control" rows="5" name="AlamatPPK" id="AlamatPPK"><?php echo $da->AlamatPPK; ?></textarea>
												</div>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
								<div class="box-footer">
									<button type="submit" data-target="#modaladdendum" data-toggle="modal" class="btn btn-success pull-right"><i class="fa fa-paper-plane">&nbsp; TAMBAH ADDENDUM </i></button>
								</div>

							</div>
						</div>
					</div>
				</form>

				<br>

				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title" style="font-size: larger;">HISTORY ADDENDUM</h3>

						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div>
					<div class="box-body">

						<a href="<?= site_url('FrmHistoryAddendum/ExportExcel_HistoryAddendum/' . $datakontrak->JobNo) ?>" class="btn btn-md btn-default" id="ExportExcel_HistoryAddendum">Export To Excel <i class="fa fa-file-excel-o"></i></a>
						<hr>
						<div class="form-group">

							<div class="box-body table-responsive no-padding">
								<table class="table table-hover table-bordered table-striped table-condensed atur_ukuran" border="1" cellspacing="2" width="100%">

									<tr style="background-color: coral;">
										<th>No</th>
										<th>No Kontrak/Addendum</th>
										<th>Tgl Kontrak/Addendum</th>
										<th>Addendum Ke</th>
										<th>Pagu Bruto Kontrak (Rp)</th>
										<th>Pagu Netto Kontrak (Rp)</th>
										<th>Bruto Kontrak (Rp)</th>
										<th>Netto Kontrak (Rp)</th>
										<th>Tgl Mulai Kontrak</th>
										<th>Tgl Selesai kontrak</th>
										<th>Keterangan Addendum</th>
										<th></th>
									</tr>


									<?php
									$no = 1;
									$sum_PaguBrutoKontrak = 0;
									$sum_PaguNettoKontrak = 0;
									$sum_BrutoKontrak = 0;
									$sum_NettoKontrak = 0;

									foreach ($addendum as $jb) :

										$sum_PaguBrutoKontrak += (float) $jb->PaguBruto;
										$sum_PaguNettoKontrak += (float) $jb->PaguNetto;
										$sum_BrutoKontrak += (float) $jb->Bruto;
										$sum_NettoKontrak += (float) $jb->Netto;
										?>
										<tr>
											<td><?php echo $no++ ?></td>
											<td><?php echo $jb->NoKontrak; ?></td>
											<td><?php echo TglIndoGarisMiring_tanpa_waktu($jb->TglKontrak); ?></td>
											<td><?php echo $jb->AddendumKe; ?></td>
											<td class="text-right"><?php echo UbahGabunganUang($jb->PaguBruto); ?></td>
											<td class="text-right"><?php echo UbahGabunganUang($jb->PaguNetto); ?></td>
											<td class="text-right"><?php echo UbahGabunganUang($jb->Bruto); ?></td>
											<td class="text-right"><?php echo UbahGabunganUang($jb->Netto); ?></td>
											<td><?php echo TglIndoGarisMiring_tanpa_waktu($jb->PrdAwal); ?></td>
											<td><?php echo TglIndoGarisMiring_tanpa_waktu($jb->PrdAkhir); ?></td>
											<td><?php echo $jb->RemarkAddendum; ?></td>
											<td style="width: 7%" class="text-center">
												<a title="Detail" data-target="#modalEditaddendum<?php echo $jb->LedgerNo ?>" data-toggle="modal" class="btn btn-success btn-md"><i class="fa fa-pencil"></i></a>
												<a title="Delete" href="<?= site_url('Job/hapus_history_addendum/' . $jb->JobNo . '/' . $jb->LedgerNo) ?>" class="btn btn-danger btn-md" onclick="return confirm('Anda Yakin Ingin Menghapus Data Ini ??')"><i class="fa fa-trash"></i></a>
											</td>
										</tr>
										<?php
									endforeach;
									?>
									<tr class="bg-gray ">
										<th colspan="4">TOTAL</th>
										<th class="text-right"><?= number_format($sum_PaguBrutoKontrak) ?></th>
										<th class="text-right"><?= number_format($sum_PaguNettoKontrak) ?></th>
										<th class="text-right"><?= number_format($sum_BrutoKontrak) ?></th>
										<th class="text-right"><?= number_format($sum_NettoKontrak) ?></th>
										<th class="text-right" colspan="4"></th>
									</tr>
								</table>
							</div>

						</div>
					</div>
					<div class="box-footer">
					</div>
				</div>

				<?php foreach ($addendum as $jb) : ?>

					<form action="<?= site_url('Job/editAddendum') ?>" method="post" id="FormEditAddendum">
						<div class="modal fade" id="modalEditaddendum<?php echo $jb->LedgerNo ?>">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title">Input Data Addendum </h4>
										</div>
										<div class="box-body">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label>No Kontrak / Addendum</label>
														<input type="text" name="JobNo" id="JobNo_edit" value="<?php echo $jb->JobNo ?>" hidden>
														<input type="hidden" name="LedgerNo" id="LedgerNo" class="form-control" value="<?= $jb->LedgerNo ?>">
														<input type="text" name="NoKontrak" value="<?php echo $jb->NoKontrak ?>" id="NoKontrak" class="form-control">
														<label>HPS (Rp)</label>
														<input type="text" class="form-control" name="PaguBruto" id="PaguBruto"  value="<?= number_format($jb->PaguBruto) ?>">
														<label>Nilai Kontrak</label>
														<input type="text" class="form-control" name="Bruto" id="NilaiKontrak"  value="<?= number_format($jb->Bruto)  ?>">
														<label>Addendum Ke</label>
														<input type="text" name="AddendumKe" value="<?php echo $jb->AddendumKe ?>" id="Addendumke" class="form-control">
														<label>Pagu Netto</label>
														<input type="text" name="PaguNetto" value="<?php echo number_format($jb->PaguNetto) ?>" id="PaguNetto" class="form-control">
														<label>Masa Pelaksanaan</label>
														<input type="spinner" class="form-control" name="Hari" id="MasaPelaksanaan" value="<?php echo $jb->Hari ?>">
													</div>
												</div>

												<div class="col-md-6">
													<div class="form-group">
														<label>Tgl Kontrak / Addendum</label>
														<input type="date" class="form-control" name="TglKontrak" id="TglKontrak" value="<?php echo $jb->TglKontrak ?>">
														<label>Netto Kontrak (Rp) / Excl PPN + PPH </label>
														<input type="text" class="form-control" name="KontrakNetto" id="KontrakNetto" value="<?= number_format($jb->Netto) ?>">
														<label>Tgl Mulai Kontrak</label>
														<input type="date" class="form-control" value="<?php echo $jb->PrdAwal ?>" name="PrdAwal" id="PrdAwal">
														<label>Tgl Selesai Kontrak</label>
														<input type="date" class="form-control" name="PrdAkhir" id="PrdAkhir" value="<?php echo $jb->PrdAkhir ?>">
														<label>Keterangan Addendum</label>
														<textarea class="form-control" name="RemarkAddendum" rows="5"><?php echo $jb->RemarkAddendum ?></textarea>
													</div>
												</div>
											</div>
										</div>
										<div class="box-footer">
											<button type="submit" class="btn btn-primary"><i class="fa fa-save"> &nbsp UPDATE</i></button>
										</div>

									</div>
								</div>
							</div>
						</form>
					<?php endforeach; ?>



					<div class="box box-success">
						<div class="box-header with-border">
							<h1 class="box-title">Data Rekening Proyek</h1>

							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
							</div>
						</div>


						<div class="box-body">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Rekening Proyek</label>
										<input type="text" class="form-control " name="RekPro" id="RekPro" value="<?php echo $datakontrak->RekeningProyek; ?>">
									</div>

								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>No. NPWP Proyek</label>
										<input type="text" class="form-control" name="NpwpProyek" id="NpwpProyek" value="<?php echo $datakontrak->NPWPCompany ?>">
									</div>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<button type="button" data-target="#modal-DRP" data-toggle="modal" class="btn btn MD btn-success pull-right"><i class="fa fa-save"> TAMBAH REKENING / NPWP </i></button>
						</div>
					</div>


					<div class="modal fade" id="modal-DRP">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title">Input Data Rekening Proyek Dan NPWP Proyek</h4>
									</div>
									<div class="modal-body">
										<form action=" <?php echo base_url('Job/TambahRekNPWP') ?>" method="POST">
											<label>Job No</label>
											<input type="text" name="JobNo" id="JobNo" class="form-control" value="<?php echo $datakontrak->JobNo ?>" readonly>
											<label>Nama Proyek</label>
											<input type="text" name="JobNm" id="JobNm" class="form-control" value="<?php echo $datakontrak->JobNm ?>" readonly>
											<label>Atas Nama</label>
											<input type="text" name="NPWPName" id="NPWPName" class="form-control" value="<?php echo $datakontrak->NPWPName ?>">
											<label>Nomor NPWP</label>
											<input type="text" name="NPWPCompany" id="NPWPCompany" class="form-control" value="<?php echo $datakontrak->NPWPCompany ?>">
											<label>No Rekening Proyek</label>
											<input type="text" name="RekeningProyek" id="RekeningProyek" class="form-control" value="<?php echo $datakontrak->RekeningProyek ?>">
											<label>Nama Bank</label>
											<input type="text" name="BankProyek" id="BankProyek" class="form-control" value="<?php echo $datakontrak->BankProyek ?>">
											<label>Alamat NPWP</label>
											<textarea name="NPWPAddress" id="NPWPAddress" cols="10" rows="5" class="form-control"><?php echo $datakontrak->NPWPAddress ?></textarea>

										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
											<button type="submit" class="btn btn-primary">Save changes</button>
										</div>
									</form>
								</div>

							</div>
						</div>



						<div class="box box-warning">

							<div class="box-header with-border">
								<h3 class="box-title" style="font-size: larger;">JAMINAN</h3>

								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
									<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
								</div>
							</div>
							<div class="box-body">

								<form class="form-group" action="" method="post">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>1. Jaminan Pelaksanaan</label>
												<hr>
											</div>

											<div class="box-body">
												<?php foreach ($JaminanPelaksanaan as $jk) : ?>
													<div class="form-group">
														<label>No. Jaminan</label>
														<input type="text" class="form-control" name="NoJampel" id="NoJampel" value="<?php echo $jk->NoJaminan; ?>">
														<label>Nilai Jaminan</label>
														<input type="text" class="form-control" name="NilaiJampel" id="NilaiJampel" value="<?php echo $jk->NilaiJaminan; ?>">
														<label>Masa Berlaku</label>
														<input type="date" class="form-control" name="DtJampel" id="DtJampel" value="<?php echo $jk->MasaBerlaku; ?>">
														<label>Upload File Jaminan Pelaksanaan</label>
														<input type="text" class="form-control" name="Filejaminan" id="Filejaminan" value="<?php echo $jk->FileJaminan; ?>" />
													</div>
												<?php endforeach; ?>

											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label>2. Jaminan Uang Muka</label>
												<hr>
											</div>

											<div class="box-body">
												<?php foreach ($JaminanUangMuka as $jm) : ?>
													<div class="form-group">
														<label>No. Jaminan</label>
														<input type="text" class="form-control" name="NoJampel" id="NoJampel" value="<?php echo $jm->NoJaminan; ?>">
														<label>Nilai Jaminan</label>
														<input type="text" class="form-control" name="NilaiJampel" id="NilaiJampel" value="<?php echo $jm->NilaiJaminan; ?>">
														<label>Masa Berlaku</label>
														<input type="date" class="form-control" name="DtJampel" id="DtJampel" value="<?php echo $jm->MasaBerlaku; ?>">
														<label>Upload File Jaminan Pelaksanaan</label>
														<input type="text" class="form-control" name="Filejaminan" id="Filejaminan" value="<?php echo $jm->FileJaminan; ?>" />
													</div>
												<?php endforeach; ?>
											</div>
										</div>
										<br>

										<div class="col-md-6">
											<div class="form-group">
												<label>3. Jaminan Sisa Pelaksanaan</label>
												<hr>
											</div>

											<div class="box-body">
												<?php foreach ($JaminanSisPel as $jk) : ?>
													<div class="form-group">
														<label>No. Jaminan</label>
														<input type="text" class="form-control" name="NoJampel" id="NoJampel" value="<?php echo $jk->NoJaminan; ?>">
														<label>Nilai Jaminan</label>
														<input type="text" class="form-control" name="NilaiJampel" id="NilaiJampel" value="<?php echo $jk->NilaiJaminan; ?>">
														<label>Masa Berlaku</label>
														<input type="date" class="form-control" name="DtJampel" id="DtJampel" value="<?php echo $jk->MasaBerlaku; ?>">
														<label>Upload File Jaminan Pelaksanaan</label>
														<input type="text" class="form-control" name="Filejaminan" id="Filejaminan" value="<?php echo $jk->FileJaminan; ?>" />
													</div>
												<?php endforeach; ?>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label>4. Jaminan Pemeliharaan</label>
												<hr>
											</div>

											<div class="box-body">
												<?php foreach ($JaminanPemeliharaan as $jk) : ?>
													<div class="form-group">
														<label>No. Jaminan</label>
														<input type="text" class="form-control" name="NoJampel" id="NoJampel" value="<?php echo $jk->NoJaminan; ?>">
														<label>Nilai Jaminan</label>
														<input type="text" class="form-control" name="NilaiJampel" id="NilaiJampel" value="<?php echo $jk->NilaiJaminan; ?>">
														<label>Masa Berlaku</label>
														<input type="date" class="form-control" name="DtJampel" id="DtJampel" value="<?php echo $jk->MasaBerlaku; ?>">
														<label>Upload File Jaminan Pelaksanaan</label>
														<input type="text" class="form-control" name="Filejaminan" id="Filejaminan" value="<?php echo $jk->FileJaminan; ?>" />
													</div>
												<?php endforeach; ?>
											</div>

										</div>
										<div class="modal-footer">
											<button type="button" data-toggle="modal" data-target="#modal-Jaminan" class="btn btn-success btn-md pull-right"><i class="fa fa-plus">&nbsp TAMBAH DATA </i></button>
										</div>
									</div>
								</form>

							</div>


						</div>

						<form action="<?= site_url('Job/checklistAction') ?>" method="POST">
							<div class="box box-danger">
								<div class="box-header with-border">
									<h3 class="box-title" style="font-size: larger;">CEKLIST DOCUMENT PROYEK</h3>

									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
										<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
									</div>
								</div>
								<div class="box-body">
									<div class="row">

										<div class="col-md-6">
											<div class="form-group">
												<div class="box">
													<div class="box box-primary">
														<div class="box-header">
															<i class="ion ion-clipboard"></i>

															<h3 class="box-title">Cek List Team lapangan</h3>
														</div>
														<div class="box-body">
															<ul class="todo-list">
																<?php
																$checklist = ['Kontrak', 'Dokumen Addendum', 'Backup Addendum'];
																?>
																<input type="text" name="JobNo" id="inputJobNo" value="<?php echo $datakontrak->JobNo ?>" required="required" hidden>

																<div class="control-group after-add-more">
																	<ul class="todo-list">
																		<?php foreach ($checklist as $row => $value) : ?>
																			<?php $check = ''; ?>
																			<?php
																			foreach ($checkProjectFieldTeam as $r => $v) {
																				if ($v->item == $value) $check = 'checked';
																			}
																			?>

																			<li>
																				<span class="handle">
																					<i class="fa fa-ellipsis-v"></i>
																					<i class="fa fa-ellipsis-v"></i>
																				</span>
																				<input type="checkbox" <?= $check ?> name="Cekfield[]" value="<?= $value ?>">
																				<span class="text"><?= $value ?></span>
																			</li>
																		<?php endforeach ?>
																	</ul>
																</div>
															</ul>
														</div>
													</div>
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<div class="box">
													<div class="box box-primary">
														<div class="box-header">
															<i class="ion ion-clipboard"></i>

															<h3 class="box-title">Cek List Team PC</h3>
														</div>
														<div class="box-body">
															<ul class="todo-list">
																<?php
																$checklist = ['Kontrak', 'Dokumen Addendum', 'Backup Addendum'];
																?>
																<div class="control-group after-add-more">
																	<ul class="todo-list">
																		<?php foreach ($checklist as $row => $value) : ?>
																			<?php $check = ''; ?>
																			<?php
																			foreach ($checkProjectPCTeam as $r => $v) {
																				if ($v->item == $value) $check = 'checked';
																			}
																			?>

																			<li>
																				<span class="handle">
																					<i class="fa fa-ellipsis-v"></i>
																					<i class="fa fa-ellipsis-v"></i>
																				</span>
																				<input type="checkbox" <?= $check ?> name="Cekpc[]" value="<?= $value ?>">
																				<span class="text"><?= $value ?></span>
																			</li>
																		<?php endforeach ?>
																	</ul>
																</div>
															</ul>
														</div>
													</div>
												</div>
											</div>
										</div>

									</div>
								</div>
								<div class="box-footer">
									<button type="submit" class="btn btn-success pull-right"><i class="fa fa-save"> SIMPAN </i></button>
								</div>
							</div>
						</form>

						<form action="<?= site_url('Job/SimpanPHO') ?>" method="POST" enctype="multipart/form-data">
							<div class="box box-success">
								<div class="box-header with-border">
									<h3 class="box-title" style="font-size: larger;">DATA PHO</h3>

									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
										<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
									</div>
								</div>
								<div class="box-body">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>No. PHO</label>
												<input type="text" class="form-control" name="NoPHO" id="NoPHO" value="<?php echo $datakontrak->NoPHO ?>">

												<input type="text" name="JobNo" id="JobNo" value="<?php echo $datakontrak->JobNo ?>" hidden>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Tgl PHO</label>

												<input type="Date" class="form-control" name="TglPHO" id="TglPHO" value="<?php echo $datakontrak->TglPHO ?>">
											</div>
										</div>
									</div>
									<div class="box-body">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<div class="box-header">
														<i class="ion ion-clipboard"></i>

														<h5 class="box-title">Cek List Persyaratan PHO</h5>
													</div>
													<div class="box-body">
														<ul class="todo-list">
															<?php
															$DataPHO1 = ['Mc 100', 'Backup Quantitas', 'Asbuild Drawing', 'Backup Qualitas'];
															?>
															<div class="control-group after-add-more2">
																<ul class="todo-list">
																	<?php foreach ($DataPHO1 as $row => $value) : ?>
																		<?php $check = ''; ?>
																		<?php
																		foreach ($checkPHO1 as $r => $v) {
																			if ($v->item == $value) $check = 'checked';
																		}
																		?>

																		<li>
																			<span class="handle">
																				<i class="fa fa-ellipsis-v"></i>
																				<i class="fa fa-ellipsis-v"></i>
																			</span>
																			<input type="checkbox" <?= $check ?> name="CeklistPHO1[]" value="<?= $value ?>">
																			<span class="text"><?= $value ?></span>
																		</li>
																	<?php endforeach ?>
																</ul>
															</div>
														</ul>
													</div>
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<div class="box-header">
														<i class="ion ion-clipboard"></i>

														<h5 class="box-title">Cek List Persyaratan PHO</h5>
													</div>

													<div class="box-body">

														<ul class="todo-list">
															<?php
															$DataPHO2 = ['Laporan Harian ~ Mingguan ~ Bulanan', 'Jaminan Pemeliharaan', 'Documentasi', 'Surat Permohonan PHO', 'Laporan K3'];
															?>
															<div class="control-group after-add-more3">
																<ul class="todo-list">
																	<?php foreach ($DataPHO2 as $row => $value) : ?>
																		<?php $check = ''; ?>
																		<?php
																		foreach ($checkPHO2 as $r => $v) {
																			if ($v->item == $value) $check = 'checked';
																		}
																		?>

																		<li>
																			<span class="handle">
																				<i class="fa fa-ellipsis-v"></i>
																				<i class="fa fa-ellipsis-v"></i>
																			</span>
																			<input type="checkbox" <?= $check ?> name="CeklistPHO2[]" value="<?= $value ?>">
																			<span class="text"><?= $value ?></span>
																		</li>
																	<?php endforeach ?>
																</ul>
															</div>
														</ul>
													</div>
												</div>
											</div>
										</div>
										<label>Upload Berita Acara PHO</label>
										<input type="file" class="form-control" name="FilePHO" id="customFile" value="<?= $datakontrak->PHOFile ?>" />
									</div>
								</div>
								<div class="box-footer">
									<button type="submit" class="btn btn-success pull-right"><i class="fa fa-save"> SIMPAN </i></button>
								</div>
							</div>
						</form>

						<div class="box box-success">
							<div class="box-header with-border">
								<h3 class="box-title" style="font-size: larger;">DATA FHO</h3>

								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
									<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
								</div>
							</div>
							<div class="box-body">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>No. FHO</label>
											<input type="text" class="form-control" name="NoFHO" id="NoFHO" value="<?php echo $datakontrak->NoFHO ?>">
										</div>

									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Tgl FHO</label>
											<input type="Date" class="form-control" name="TglFHO" id="TglFHO" value="<?php echo $datakontrak->TglFHO ?>">
										</div>
									</div>
								</div>
								<div class="box-body">
									<div class="row">

										<div class="form-group">
											<div class="form-group">
												&nbsp;
												<label>Ceklist Persyaratan FHO :</label>
												&nbsp
												&nbsp
												<label>
													<input type="checkbox" name="checkboxFHO" value="Berita Acara FHO">
													Upload Berita Acara FHO
												</label>
												&nbsp
												&nbsp
												<br>
											</div>
										</div>
									</div>
									<label>Upload File FHO</label>
									<input type="text" name="FileFHO" class="form-control" id="customFile" value="<?php echo $datakontrak->FHOFile ?>">
									<label for="">Lanjutkan Notifikasi</label>
									<input type="text" name="NotifFHO" id="NotifFHO" class="form-control" value="<?php echo $datakontrak->EndNotification ?>" readonly>


								</div>
							</div>
							<div class="box-footer">
								<button type="button" data-target="#modal-FHO" data-toggle="modal" class="btn btn-success pull-right"><i class="fa fa-save"> UPDATE DATA PHO </i></button>
							</div>
						</div>

					</div>
				</section>

			</div>


			<!-- /.container-fluid -->
		</section>


	</div>
</div>
</div>
</div>
</div>
</section>



<!-- </Section> -->

</div>

<div class="modal fade" id="modal-FHO">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Update Data FHO</h4>
				</div>
				<div class="modal-body">
					<form action=" <?php echo base_url('Job/UpdateFHO') ?>" method="POST" enctype="multipart/form-data">
						<label>Job No</label>
						<input type="text" name="JobNo" id="JobNo" class="form-control" value="<?php echo $datakontrak->JobNo ?>" readonly>
						<label>Nama Proyek</label>
						<input type="text" name="JobNm" id="JobNm" class="form-control" value="<?php echo $datakontrak->JobNm ?>" readonly>
						<label>NO FHO</label>
						<input type="text" name="NoFHO" id="NoFHO" class="form-control" value="<?php echo $datakontrak->NoFHO ?>">
						<label>Tgl FHO</label>
						<input type="date" name="TglFHO" id="TglFHO" class="form-control" value="<?php echo $datakontrak->TglFHO ?>">

						<label>Ceklist Persyaratan FHO :</label>
						<label><input type="checkbox" name="checkboxFHO" id="checkboxFHO" class="form-control" value=""></label>
						<label>Upload File FHO</label>
						<input type="file" name="FileFHO" id="FileFHO" class="form-control" value="">
						<label>Lanjut Notifikasi</label>

						<select class="form-control" name="NotifLanjutTender" id="PesertaTender">
							<option value="0">Ya</option>
							<option value="1">Tidak</option>
						</select>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Save changes</button>
					</div>
				</form>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>

	<div class="modal fade" id="modal-Jaminan" ondrag="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Tambah Data Jaminan Pelaksanaan</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">

					<?php echo form_open_multipart('job/simpan_JaminanKontrak'); ?>

					<input type="text" name="JobNo" hidden="" value="<?php echo $datakontrak->JobNo ?>">
					<p hidden=""><?= $this->session->userdata('MIS_LOGGED_NAME'); ?></p>
					<div class="form-group">
						<label>Pilih Kategori Jaminan </label>
						<select class="form-control" name="NamaJaminan" id="NamaJaminan">
							<option value="">--Pilih Kategori Jaminan--</option>
							<option value="Jaminan Pelaksanaan">Jaminan Pelaksanaan</option>
							<option value="Jaminan Uang Muka">Jaminan Uang Muka</option>
							<option value="Jaminan Sisa Pelaksanaan">Jaminan Sisa Pelaksanaan</option>
							<option value="">Jaminan Pemeliharaan</option>
						</select>
					</div>
					<div class="form-group">
						<label>No. Jaminan</label>
						<input type="text" class="form-control" name="NoJaminan" id="NoJaminan" required="">
					</div>
					<div class="form-group">
						<label>Nilai Jaminan</label>
						<input type="text" class="form-control" name="NilaiJaminan" id="NilaiJaminan" required="">
					</div>
					<div class="form-group">
						<label>Masa Berlaku</label>
						<input type="date" class="form-control" name="MasaBerlaku" id="MasaBerlaku" required="">
					</div>
					<div class="form-group">
						<label>Upload File Jaminan Pelaksanaan</label>
						<input type="file" class="form-control" name="Filejaminan" id="Filejaminan" required="">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-success">Simpan</button>
				</div>

				<?php echo form_close();     ?>
			</div>
		</div>
	</div>



	<script type="text/javascript">
		function formatCurrency(total) {
			var neg = false;
			if (total < 0) {
				neg = true;
				total = Math.abs(total);
			}
			return (neg ? "-Rp." : 'Rp.') + parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
		}


		$('#PaguBrutoKontrak').keyup(function() {

			var hitung = 0;
			var PPN = 0.11;
			var PPH = 0.97;
			var HitungPagu_DenganPPH = 0;

			if ($("#Sumberdana").val() != 'LOAN') {
				var PaguBrutoKontrak = parseFloat(this.value);
				var dapatPPN = parseFloat(this.value) * 0.11;
				var dapatPPH = PaguBrutoKontrak - (parseFloat(this.value) * 0.97);

				hitung = PaguBrutoKontrak - dapatPPN - dapatPPH;
			} else {
				hitung = parseFloat(this.value);
			}

		// hitung = (hitung !=0) ? 0 : hitung;


		// $('#pph_coba').val(dapatPPH);
		// $('#ppn_coba').val(dapatPPN);
			$('#PaguNettoKontrak').val(hitung);
		// $('#PaguNettoKontrak').val(hitung);
		});
	</script>


	<script type="text/javascript">
		$(document).ready(function() {
			$(".add-more").click(function() {
				var html = $(".copy").html();
				$(".after-add-more").after(html);
			});

		// saat tombol remove dklik control group akan dihapus 
			$("body").on("click", ".remove", function() {
				$(this).parents(".control-group").remove();
			});
		});
	</script>

	<script type="text/javascript">
		$(document).ready(function() {
			$(".add-more1").click(function() {
				var html = $(".copy").html();
				$(".after-add-more1").after(html);
			});

		// saat tombol remove dklik control group akan dihapus 
			$("body").on("click", ".remove", function() {
				$(this).parents(".control-group").remove();
			});
		});
	</script>

	<script type="text/javascript">
		$(document).ready(function() {
			$(".add-more2").click(function() {
				var html = $(".copy").html();
				$(".after-add-more2").after(html);
			});

		// saat tombol remove dklik control group akan dihapus 
			$("body").on("click", ".remove", function() {
				$(this).parents(".control-group").remove();
			});
		});
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			$(".add-more3").click(function() {
				var html = $(".copy").html();
				$(".after-add-more3").after(html);
			});

		// saat tombol remove dklik control group akan dihapus 
			$("body").on("click", ".remove", function() {
				$(this).parents(".control-group").remove();
			});
		});
	</script>



	<!-- <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
		// toDecimal(PenawaranNetto);
			$("#PenawaranNetto, #ppn").keyup(function() {
				var penawaran = $("#PenawaranNetto").val();
				penawaran = parseInt(penawaran.replace(/,/g, ''));
				var ppn = $("#ppn").val();

				var total = parseInt(penawaran) * parseInt(ppn);
				$("#KontrakNetto").val(total);
			});
		});

		function bruto(ev, tenderIndex = null) {
		// toDecimal(ev);
			if ($("#SumberDana").val() != 'LOAN') {
				var bruto = $(ev).val();
				bruto = parseInt(bruto.replace(/,/g, ''));
				var netto = 0;
				if (bruto > 0) {
					netto = (bruto * 10) / 100;
				}
				$("#PenawaranNetto" + tenderIndex).val(numeral(netto).format('0,0'));
			} else {
				$("#PenawaranNetto" + tenderIndex).val($(ev).val());
			}
		}
	</script>
 -->