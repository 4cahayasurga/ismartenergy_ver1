<style type="text/css">
	.huruf_12{
		font-size: 10px !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}
	.jarak{
		margin-top: 5px !important;
		margin-bottom: 5px !important;
	}
	.select2-container.select2-container-disabled .select2-choice {
		background-color: #ddd;
		border-color: #a8a8a8;
	}
</style>

<div class="content-wrapper">

	<div class="content-header">
		<h1 class="text-bold"><?=($judul) ? $judul : '' ?></h1>
		<p>No : <?=$NoPD?></p>
	</div>

	<div class="content">
		<?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>
		<div class="row">

			<div class="col-sm-12">

				<div class="box box-primary">
					<div class="box-header">
						<h3 class="box-title text-bold">Detail PD</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>

						</div>
					</div>
					<div class="box-body">

						<div class="col-sm-6 row">

							<table border="0" width="100%" class="jarak">
								<tr>
									<td width="13%" class="huruf_12">No. PD</td>
									<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
									<td width="35%" class="huruf_12">
										<input type="text" placeholder="No. PD" class="form-control input-sm huruf_12" width="30%" value="<?=$mydataPD->NoPD?>" disabled>
									</td>
									<td width="2%"></td>
									<td width="10%" class="huruf_12">No. PJ</td>
									<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
									<td width="35%" class="huruf_12">
										<input type="text" placeholder="No. PJ"  class="form-control input-sm huruf_12" width="30%" value="<?=$NoPJ?>" disabled>
									</td>
									<td></td>
								</tr>
							</table>

							<table border="0" width="100%" class="jarak">
								<tr>
									<td width="13%" class="huruf_12">Job No.</td>
									<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
									<td width="35%" class="huruf_12">
										<input type="text" placeholder="Job No" class="form-control input-sm huruf_12" width="30%" value="<?=$mydataPD->JobNo.' - '.$mydataPD->JobNm?>" disabled>
									</td>
									<td width="2%"></td>
									<td width="10%" class="huruf_12">No. Ref Lapangan</td>
									<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
									<td width="35%" class="huruf_12">
										<input type="text" placeholder="No. Ref Lapangan"  class="form-control input-sm huruf_12" width="30%" value="<?=$mydataPD->NoRef?>" disabled>
									</td>
									<td></td>
								</tr>
							</table>

							<table border="0" width="100%" class="jarak">
								<tr>
									<td width="13%" class="huruf_12">Tgl Permintaan</td>
									<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
									<td width="35%" class="huruf_12">
										<input type="date"  class="form-control input-sm huruf_12" width="30%" value="<?=$mydataPD->TglPD?>" disabled>
									</td>
									<td width="2%"></td>
									<td width="20%" class="huruf_12">
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="checkbox" id="Jaminan" value="1" disabled="disabled" <?=($mydataPD->Jaminan == '1') ? 'checked' : '' ?>>
											<label class="form-check-label" for="Jaminan">PD Jaminan</label>
										</div>
									</td>
									<td></td>
								</tr>
							</table>

							<table border="0" width="100%" class="jarak">
								<tr>
									<td width="13%" class="huruf_12">Deskripsi</td>
									<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
									<td  class="huruf_12">
										<input type="text" placeholder="Deskripsi"  class="form-control input-sm huruf_12" value="<?=$mydataPD->Deskripsi?>" disabled>
									</td>
								</tr>
							</table>

							<table border="0" width="100%" class="jarak">
								<tr>
									<td width="13%" class="huruf_12">Periode</td>
									<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
									<td class="huruf_12" >
										<input type="date" class="form-control input-sm huruf_12"  value="<?=$mydataPD->PrdAwal?>" disabled>
									</td>
									<td width="5%" class="text-center" style="padding-left:3px;padding-right:3px;text-align: center;">s.d</td>
									<td  class="huruf_12" >
										<input type="date" class="form-control input-sm huruf_12" value="<?=$mydataPD->PrdAwal?>" disabled>
									</td>
									<td width="2%" class="text-center" style="padding-left:3px;padding-right:3px;text-align: center;"></td>
									<td width="9%" class="huruf_12">Minggu Ke</td>
									<td  class="huruf_12" style="width:7%;">
										<input type="text" class="form-control input-sm huruf_12" value="<?=$mydataPD->Minggu?>" disabled>
									</td>
									<td></td>
								</tr>
							</table>


							<table border="0" width="100%" class="jarak">
								<tr>
									<td width="13%" class="huruf_12">Alokasi</td>
									<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
									<td width="35%" class="huruf_12">
										<input type="text" placeholder="Alokasi" class="form-control input-sm huruf_12" width="30%" value="<?=$mydataPD->Alokasi.' - '.$mydataPD->AlokasiKeterangan?>" disabled>
									</td>
									<td width="2%"></td>
									<td width="10%" class="huruf_12">Tipe Form</td>
									<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
									<td width="35%" class="huruf_12">
										<input type="text" placeholder="TipeForm"  class="form-control input-sm huruf_12" width="30%" value="<?=$mydataPD->TipeForm.' - '.$mydataPD->TipeFormKeterangan?>" disabled>
									</td>
									<td></td>
								</tr>
							</table>


							<table border="0" width="100%" class="jarak">
								<tr>
									<td width="13%" class="huruf_12"></td>
									<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;"></td>
									<td class="bg-blue text-center">Bukti Pendukung</td>

								</tr>
							</table>

							<table width="100%" border="0">
								<tr>
									<td width="13%"></td>
									<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;"></td>
									<td>
										<div class="form-check form-check-inline" style="font-size: 11px;">
											<input class="form-check-input" type="checkbox" id="INV" value="INV" name="BuktiPendukung[]" <?= (CekBuktiPendukung($mydataPD->BuktiPendukung, 'INV') == TRUE) ? 'checked' : '' ?> disabled >
											<label class="form-check-label" for="INV">Invoice/Kwitansi</label>
										</div>

										<div class="form-check form-check-inline" style="font-size: 11px;">
											<input class="form-check-input" type="checkbox" id="SJ" value="SJ" name="BuktiPendukung[]" <?= (CekBuktiPendukung($mydataPD->BuktiPendukung, 'SJ') == TRUE) ? 'checked' : '' ?> disabled >
											<label class="form-check-label" for="SJ">Surat Jalan/Tanda Terima Lapangan</label>
										</div>

										<div class="form-check form-check-inline" style="font-size: 11px;">
											<input class="form-check-input" type="checkbox" id="PO" value="PO" name="BuktiPendukung[]" <?= (CekBuktiPendukung($mydataPD->BuktiPendukung, 'PO') == TRUE) ? 'checked' : '' ?> disabled >
											<label class="form-check-label" for="PO">Copy PO</label>
										</div>


									</td>
									<td>
										<div class="form-check form-check-inline" style="font-size: 11px;">
											<input class="form-check-input" type="checkbox" id="FP" value="FP" name="BuktiPendukung[]" <?= (CekBuktiPendukung($mydataPD->BuktiPendukung, 'FP') == TRUE) ? 'checked' : '' ?> disabled >
											<label class="form-check-label" for="FP">Faktur Pajak</label>
										</div>

										<div class="form-check form-check-inline" style="font-size: 11px;">
											<input class="form-check-input" type="checkbox" id="BAP" value="BAP" name="BuktiPendukung[]" <?= (CekBuktiPendukung($mydataPD->BuktiPendukung, 'BAP') == TRUE) ? 'checked' : '' ?> disabled >
											<label class="form-check-label" for="BAP">Berita Acara Pembayaran</label>
										</div>

										<div class="form-check form-check-inline" style="font-size: 11px;">
											<input class="form-check-input" type="checkbox" id="BAOP" value="BAOP" name="BuktiPendukung[]" <?= (CekBuktiPendukung($mydataPD->BuktiPendukung, 'BAOP') == TRUE) ? 'checked' : '' ?> disabled >
											<label class="form-check-label" for="BAOP">Berita Acara Opname Pekerjaan</label>
										</div>
									</td>
								</tr>
							</table>

							<table border="0" width="100%" class="jarak">
								<tr>
									<td width="13%" class="huruf_12">Keterangan</td>
									<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
									<td  class="huruf_12">
										<input type="text" placeholder="Keterangan"  class="form-control input-sm huruf_12" value="<?=$mydataPD->Keterangan?>" disabled>
									</td>
								</tr>
							</table>

							<table border="0" width="100%" class="jarak">
								<tr>
									<td width="13%" class="huruf_12">Tgl PJ</td>
									<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
									<td  class="huruf_12">
										<input type="date" class="form-control input-sm huruf_12"  value="<?=$mydataPD->TglPJ?>" disabled>
									</td>
								</tr>
							</table>




						</div>

						<div class="col-sm-6 ">
							<table border="0" width="100%" class="jarak">
								<tr>
									<td width="13%" class="huruf_12"></td>
									<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;"></td>
									<td class="bg-red text-center">Bayar Kepada</td>

								</tr>
							</table>

							<table border="0" width="100%" class="jarak">
								<tr>
									<td width="13%" class="huruf_12">No. Kontrak/PO</td>
									<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
									<td  class="huruf_12">
										<input type="text" placeholder="No.Kontrak/PO"  class="form-control input-sm huruf_12" value="<?=$mydataPD->NoKO?>" disabled>
									</td>
								</tr>
							</table>

							<table border="0" width="100%" class="jarak">
								<tr>
									<td width="13%" class="huruf_12">No. Tagihan</td>
									<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
									<td  class="huruf_12">
										<input type="text" placeholder="No. Tagihan"  class="form-control input-sm huruf_12" value="<?=$mydataPD->NoTagihan?>" disabled>
									</td>
								</tr>
							</table>


							<table border="0" width="100%" class="jarak">
								<tr>
									<td width="13%" class="huruf_12">Nama</td>
									<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
									<td  class="huruf_12">
										<input type="text" placeholder="Nama"  class="form-control input-sm huruf_12" value="<?=$mydataPD->Nama?>" disabled>
									</td>
								</tr>
							</table>

							<table border="0" width="100%" class="jarak">
								<tr>
									<td width="13%" class="huruf_12">Alamat</td>
									<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
									<td  class="huruf_12">
										<input type="text" placeholder="Alamat"  class="form-control input-sm huruf_12" value="<?=$mydataPD->Alamat?>" disabled>
									</td>
								</tr>
							</table>

							<table border="0" width="100%" class="jarak">
								<tr>
									<td width="13%" class="huruf_12">Telepon</td>
									<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
									<td  class="huruf_12">
										<input type="text" placeholder="Telepon"  class="form-control input-sm huruf_12" value="<?=$mydataPD->Telepon?>" disabled>
									</td>
								</tr>
							</table>

							<table border="0" width="100%" class="jarak">
								<tr>
									<td width="13%" class="huruf_12">NPWP</td>
									<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
									<td  class="huruf_12">
										<input type="text" placeholder="NPWP"  class="form-control input-sm huruf_12" value="<?=$mydataPD->NPWP?>" disabled>
									</td>
								</tr>
							</table>


							

						</div>

					</div>

				</div>

			</div>
		</div>


		<div class="row">
			<div class="col-sm-12">
				<div class="box box-danger">
					<div class="box-header">
						<div class="box-title text-bold">Form Pembayaran</div>
					</div>
					<div class="box-body">
						<form action="<?=base_url('FrmPayment_PD/AksiSimpanPembayaranPD')?>" method="POST">
							<div class="col-sm-6 row">
								<table border="0" width="100%" class="jarak">
									<tr>
										<td width="13%" class="huruf_12">Tgl Bayar</td>
										<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
										<td  class="huruf_12">
											<input type="date" name="TglByr" id="TglByr" class="form-control input-sm huruf_10" value="<?=date('Y-m-d')?>" required>
										</td>
									</tr>
								</table>
								<table border="0" width="100%" class="jarak">
									<tr>
										<td width="13%" class="huruf_12">Keterangan</td>
										<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
										<td  class="huruf_12">
											<input type="text" placeholder="Keterangan" name="Keterangan" id="Keterangan" class="form-control input-sm huruf_10" value="" required>
											<!-- <select name="Keterangan" id="Keterangan" class="form-control input-sm huruf_10" >
												<option value="">-- Pilih Keterangan --</option>
												<option value="BIAYA ADMIN" selected>BIAYA ADMIN</option>
												<option value="PINDAH DANA">PINDAH DANA</option>
												<option value="BUNGA">BUNGA</option>
												<option value="LAINNYA">LAINNYA</option>
												<option value="CUT OFF">CUT OFF SALDO</option>
											</select> -->
										</td>
									</tr>
								</table>

								<table border="0" width="100%" class="jarak" style="border-top:1px solid silver;border-bottom:1px solid silver;">
									<tr>
										<td width="13%" class="huruf_12 text-bold">Sub Total</td>
										<td width="2%" class="huruf_12 text-bold" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
										<td  class="huruf_12 text-bold">
											<input type="text" class="form-control input-sm huruf_10 text-bold" value="<?=UbahUangKhususDecimal($TotalPD)?>" disabled>
										</td>
									</tr>
								</table>

								<table border="0" width="100%" class="jarak" style="border-top:1px solid silver;border-bottom:1px solid silver;">
									<tr>
										<td width="13%" class="huruf_12 text-bold">Saldo Sebelumnya</td>
										<td width="2%" class="huruf_12 text-bold" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
										<td  class="huruf_12 text-bold">
											<input type="text" class="form-control input-sm huruf_10 text-bold" value="<?=UbahUangKhususDecimal($Saldo)?>" readonly >
										</td>
									</tr>
								</table>

								<table border="0" width="100%" class="jarak" style="border-top:1px solid silver;border-bottom:1px solid silver;">
									<tr>
										<td width="13%" class="huruf_12 text-bold">Total Permintaan</td>
										<td width="2%" class="huruf_12 text-bold" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
										<td  class="huruf_12 text-bold">
											<input type="text" name="TotalPembayaran" id="TotalPembayaran" class="form-control input-sm huruf_10 text-bold" value="<?=UbahUangKhususDecimal($TotalPembayaran)?>" readonly>
										</td>
									</tr>
								</table>

								<table border="0" width="100%" class="jarak">
									<tr>
										<td width="13%" class="huruf_12"></td>
										<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;"></td>
										<td class="bg-gray text-center">Rekening Pengirim</td>

									</tr>
								</table>


								<table border="0" width="100%" class="jarak">
									<tr>
										<td width="13%" class="huruf_12">No. Rekening</td>
										<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
										<td  class="huruf_12">
											<select name="NoRekPengirim" id="NoRekPengirim" class="form-control input-sm huruf_10 select2" readonly  >
												<option value=""  >-- Pilih Salah Satu --</option>
												<?php foreach ($ListRekening as $row): ?>
													<option value="<?=$row->RekId?>" data-Bank="<?=$row->Bank?>" data-NoRek="<?=$row->NoRek?>" data-AtasNama="<?=$row->AtasNama?>" ><?=$row->NoRek.' - '.$row->AtasNama.' - '.$row->Bank?></option>
												<?php endforeach ?>
											</select>
										</td>
									</tr>
								</table>

								<table border="0" width="100%" class="jarak">
									<tr>
										<td width="13%" class="huruf_12">Atas Nama</td>
										<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
										<td  class="huruf_12">
											<input type="text" name="AtasNamaPengirim" id="AtasNamaPengirim" class="form-control input-sm huruf_10" placeholder="Atas Nama" readonly >
										</td>
									</tr>
								</table>

								<table border="0" width="100%" class="jarak">
									<tr>
										<td width="13%" class="huruf_12">Bank</td>
										<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
										<td  class="huruf_12">
											<input type="text" name="BankPengirim" id="BankPengirim" class="form-control input-sm huruf_10" placeholder="Bank" readonly >
										</td>
									</tr>
								</table>


							</div>

							<div class="col-sm-6">
								<table border="0" width="100%" class="jarak">
									<tr>
										<td width="13%" class="huruf_12">Via</td>
										<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
										<td  class="huruf_12">
											<select name="Via" id="Via" class="form-control input-sm huruf_10" required>
												<option value="">-- Pilih Via --</option>
												<option value="CASH">Tunai/Setor</option>
												<option value="TRF">Transfer</option>
												<option value="CG">Cheque/Giro</option>
												<option value="NOT_PAID">Tidak Dibayar</option>
											</select>
										</td>
									</tr>
								</table>
								<table border="0" width="100%" class="jarak">
									<tr>
										<td width="13%" class="huruf_12">Jenis Transfer</td>
										<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
										<td  class="huruf_12">
											<select name="JenisTransfer" id="JenisTransfer" class="form-control input-sm huruf_10" readonly >
												<option value="">-- Pilih Jenis Transfer --</option>
												<option value="Kliring"	>Kliring</option>
												<option value="Online">Online</option>
												<option value="RTGS">RTGS</option>
												<option value="Sesama BNI">Sesama BNI</option>
												<option value="Auto Debit">Auto Debit</option>
											</select>
										</td>
									</tr>
								</table>
								<table border="0" width="100%" class="jarak">
									<tr>
										<td width="13%" class="huruf_12">No. Cheque/Giro</td>
										<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
										<td  class="huruf_12">
											<input type="text" name="NoCG" id="NoCG" class="form-control input-sm huruf_10" placeholder="Isi Disini" value="" readonly >
										</td>
									</tr>
								</table>
								<table border="0" width="100%" class="jarak">
									<tr>
										<td width="13%" class="huruf_12">Sumber Kas</td>
										<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
										<td  class="huruf_12">
											<select name="SumberKas" id="SumberKas" class="form-control input-sm huruf_10"  readonly >
												<option value="">-- Pilih Sumber Kas --</option>
												<option value="JKT" >JAKARTA</option>
												<option value="MKS">LAPANGAN</option>
												<option value="LAP">RTGS</option>
											</select>
										</td>
									</tr>
								</table>
								<table border="0" width="100%" class="jarak">
									<tr>
										<td width="13%" class="huruf_12">Nama Penerima Tunai</td>
										<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
										<td  class="huruf_12">
											<input type="text" name="NamaPenerimaTunai" id="NamaPenerimaTunai" class="form-control input-sm huruf_10" placeholder="Isi Disini" value="" readonly >
										</td>
									</tr>
								</table>

								<table border="0" width="100%" class="jarak">
									<tr>
										<td width="13%" class="huruf_12"></td>
										<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;"></td>
										<td class="bg-orange text-center">Rekening Penerima</td>

									</tr>
								</table>

								<table border="0" width="100%" class="jarak">
									<tr>
										<td width="13%" class="huruf_12">No. Rekening</td>
										<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
										<td  class="huruf_12">
											<input type="text" name="NoRekPenerima" id="NoRekPenerima" class="form-control input-sm huruf_10" placeholder="No. Rekening" value="<?=$mydataPD->NoRek?>" >
										</td>
									</tr>
								</table>

								<table border="0" width="100%" class="jarak">
									<tr>
										<td width="13%" class="huruf_12">Atas Nama</td>
										<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
										<td  class="huruf_12">
											<input type="text" name="AtasNamaPenerima" id="AtasNamaPenerima" class="form-control input-sm huruf_10" placeholder="Atas Nama" value="<?=$mydataPD->AtasNama?>" >
										</td>
									</tr>
								</table>

								<table border="0" width="100%" class="jarak">
									<tr>
										<td width="13%" class="huruf_12">Bank</td>
										<td width="2%" class="huruf_12" style="padding-left:3px;padding-right:3px;text-align: center;">:</td>
										<td  class="huruf_12">
											<select name="BankPenerima" id="BankPenerima" class="form-control input-sm huruf_10 select2">
												<option value="">-- Pilih Salah Satu --</option>
												<?php foreach ($ListBank as $row): ?>
													<option value="<?=$row->Bank?>" <?=($mydataPD->Bank == $row->Bank) ? 'selected' : ''?>><?=$row->Bank?></option>
												<?php endforeach ?>
											</select>
										</td>
									</tr>
								</table>


							</div>

							<div class="col-sm-12 row">
								<table border="0" width="100%" class="jarak">
									<tr>

										<td class="text-right" style="padding-right:10px !important;">
											<input type="hidden" name="NoPD" value="<?=$NoPD?>">
											<input type="hidden" name="JobNo" value="<?=$mydataPD->JobNo?>">
											<input type="hidden" name="Alokasi" value="<?=$mydataPD->Alokasi?>">
											<input type="hidden" name="TipeForm" value="<?=$mydataPD->TipeForm?>">
											<input type="hidden" name="NoKO" value="<?=$mydataPD->NoKO?>">
											<input type="hidden" name="TipePembayaran" value="<?=$TipePembayaran?>">
											<button type="submit" class="btn btn-md btn-primary pull-right" id="BtnSimpanPembayaran">Simpan</button>
											<!-- <button type="button" class="btn btn-default btn-md" id="BtnLoading" style="display:none;">Loading...<i class="fa fa-spinner fa-spin"></i></button> -->
										</td>
										<td class="text-right" width="50px">
											<a href="<?=site_url('FrmPayment_PD/view/'.$TipePembayaran)?>" class="btn btn-danger pull-right" id="BtnBatalPembayaran">Batal</button>
										</td>
										<td width="12px"></td>
									</tr>
								</table>
							</div>

						</form>
						
					</div>

				</div>
			</div>


		</div>

	</div>

</div>


<script type="text/javascript">
	$(function(){

		// $('#NoRekPengirim').select2({
		// 	'readonly' : true,
		// 	'width' : '100%'
		// });

		// $('.js-example-basic-single').select2({
		// 	disabled: true
		// });

		$('.select2').select2({
			'width' : '100%',

		});

		var format = function(num){
			var str = num.toString().replace("$", ""), parts = false, output = [], i = 1, formatted = null;
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
			return(formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
		};

		$('#Via').change(function(){
			kosongkan_awal_page();
			ValidasiPembayaran_Via();
		});

		$('select[name="NoRekPengirim"]').change(function(){

			var NoRekPengirim = $('option:selected', this).attr('data-NoRek');
			var AtasNamaPengirim = $('option:selected', this).attr('data-AtasNama');
			var BankPengirim = $('option:selected', this).attr('data-Bank');

			$('#AtasNamaPengirim').val('');
			$('#BankPengirim').val('');

			$('#AtasNamaPengirim').val(AtasNamaPengirim);
			$('#BankPengirim').val(BankPengirim);
		});

		$("#TotalPD").keyup(function(e){
			$(this).val(format($(this).val()));
		});
	});
	function kosongkan_awal_page(){
		$('select[id="JenisTransfer"]').val('');
		$('#NoCG').val('');
		$('select[id="SumberKas"]').val('');

		$('#NamaPenerimaTunai').val('');

		//Rekening Pengirim
		$('select[id="NoRekPengirim"]').val('');
		$('select[id="AtasNamaPengirim"]').val('');
		$('select[id="BankPengirim"]').val('');


	}

	function ValidasiPembayaran_Via(){

		var Via = $("#Via option:selected").val();

		if (Via == 'CASH') {

			$('select[id="JenisTransfer"]').val('').change().attr('readonly',true).attr('required',false);
			$('#NoCG').val('').attr('readonly',true).attr('required',false);

			$('select[id="SumberKas"]').val('').attr('readonly',false).attr('required',true);
			$('#NamaPenerimaTunai').val('').attr('readonly',false).attr('required',true);

			$('select[id="NoRekPengirim"]').val('').attr('readonly',true).attr('required',false).trigger('change');
			$('input[id="AtasNamaPengirim"]').val('').attr('readonly',true);
			$('input[id="BankPengirim"]').val('').attr('readonly',true);

			// $('#TotalPD').val(0);
		}

		if (Via == 'TRF') {
			$('select[id="JenisTransfer"]').val('Online').change().attr('readonly',false).attr('required',true);
			$('#NoCG').val('').attr('readonly',true).attr('required',false);

			$('select[id="SumberKas"]').val('').attr('readonly',true).attr('required',false);
			$('#NamaPenerimaTunai').val('').attr('readonly',true).attr('required',false);

			$('select[id="NoRekPengirim"]').val('').attr('readonly',false).attr('required',true).trigger('change');
			$('input[id="AtasNamaPengirim"]').val('').attr('readonly',true);
			$('input[id="BankPengirim"]').val('').attr('readonly',true);

			// $('#TotalPD').val(0);
		}

		if (Via == 'CG') {
			$('select[id="JenisTransfer"]').val('').change().attr('readonly',true).attr('required',false);
			$('#NoCG').val('').attr('readonly',false).attr('required',true);

			$('select[id="SumberKas"]').val('').attr('readonly',true).attr('required',false);
			$('#NamaPenerimaTunai').val('').attr('readonly',true).attr('required',false);

			$('select[id="NoRekPengirim"]').val('').attr('readonly',false).attr('required',false).trigger('change');
			$('input[id="AtasNamaPengirim"]').val('').attr('readonly',true);
			$('input[id="BankPengirim"]').val('').attr('readonly',true);

			// $('#TotalPD').val(0);
		}

		if (Via == 'NOT_PAID' || Via == '') {
			$('select[id="JenisTransfer"]').val('').change().attr('readonly',true).attr('required',false);
			$('#NoCG').val('').attr('readonly',true).attr('required',false);

			$('select[id="SumberKas"]').val('').attr('readonly',true).attr('required',false);
			$('#NamaPenerimaTunai').val('').attr('readonly',true).attr('required',false);

			$('select[id="NoRekPengirim"]').val('').attr('readonly',true).attr('required',false).trigger('change');
			$('input[id="AtasNamaPengirim"]').val('').attr('readonly',true);
			$('input[id="BankPengirim"]').val('').attr('readonly',true);

			// $('#TotalPD').val(0);
		}




	}
</script>