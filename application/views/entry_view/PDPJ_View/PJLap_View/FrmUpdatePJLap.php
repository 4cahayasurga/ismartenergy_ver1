<?php
error_reporting(E_ALL);
?>
<style type="text/css">
	.huruf8{
		font-size: 9px !important;
		font-family:  Arial, "Helvetica Neue", Helvetica, sans-serif;
	}
	.huruf10{
		font-size: 10px !important;
		font-family:  Arial, "Helvetica Neue", Helvetica, sans-serif;
	}

	.huruf11{
		font-size: 11px !important;
		font-family:  Arial, "Helvetica Neue", Helvetica, sans-serif;
	}

	.huruf12{
		font-size: 12px !important;
	}

	.huruf13{
		font-size: 13px !important;
	}

	.huruf14{
		font-size: 14px !important;
	}
	.huruf15{
		font-size: 15px !important;
	}
	.jarak{
		padding-top: 5px !important;
		padding-bottom: 5px !important;
	}

	.jarak_baru{
		padding-top: 10px !important;
	}

	.tengah{
		vertical-align:middle;
	}

	#Form_PJLapangan{
		font-size: 13px !important;
	}

	#mytable thead tr th{
		font-size: 12px !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-weight: bold;
	}
	#mytable tbody tr td{
		font-size: 12px !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}

	#mytable tfoot{
		font-size: 13px !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-weight: bold;
	}

	.marginatas5{
		margin-top: 5px !important;
	}
	

</style>

<div class="content-wrapper">

	<section class="content-header">
		<h1><?=$judul?><br></h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active"><?=$judul?></li>
		</ol>
	</section>

	<section class="content">
		<?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>
		<div class="box box-primary">
			<!-- ==START BOX HEADER -->
			<div class="box-header">

				<div class="row">
					<form id="FormUpdate_PJLapangan" data-JobNo="<?=$JobNo?>" data-Alokasi="<?=$Alokasi?>" data-NoPD="<?=$dataPD['NoPD']?>" data-NoKO="<?=$dataPD['NoKO']?>">

						<!-- == START FORM SEBELAH KIRI == -->
						<div class="col-sm-6">
							<table width="100%" border="0" class="huruf12">
								<tr>
									<td width="100px" >No. PD</td>
									<td width="10px" class="text-center">:</td>
									<td width="320px">
										<input type="text" class="form-control input-md" value="<?=$dataPD['NoPD']?>" readonly >
									</td>
									<td width="100px" >&nbsp;&nbsp;&nbsp;No. PJ</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-md" value="<?=$dataPD['NoPJ']?>" readonly >
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas5">
								<tr>
									<td width="100px" >Job No</td>
									<td width="10px" class="text-center">:</td>
									<td width="320px">
										<input type="text" class="form-control input-md" value="<?=$dataPD['JobNo'].' - '.$dataPD['JobNm']?>" readonly >
									</td>
									<td width="100px" >&nbsp;&nbsp;&nbsp;No. Ref Lapangan</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-md" value="<?=$dataPD['NoRef']?>" readonly >
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas5">
								<tr>
									<td width="100px" >Tgl Permintaan</td>
									<td width="10px" class="text-center">:</td>
									<td width="200px">
										<input type="date" id="TglPD" class="form-control input-md" value="<?=$dataPD['TglPD']?>" readonly >
									</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas5">
								<tr>
									<td width="100px" >Deskripsi</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-md" value="<?=$dataPD['Deskripsi']?>" readonly >
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas5">
								<tr>
									<td width="100px" >Periode</td>
									<td width="10px" class="text-center">:</td>
									<td width="200px">
										<input type="date" class="form-control input-md" value="<?=$dataPD['PrdAwal']?>" readonly >
									</td>
									<td width="30px" class="text-center" >s.d</td>
									<td width="150px">
										<input type="date" class="form-control input-md" value="<?=$dataPD['PrdAkhir']?>" readonly >
									</td>
									<td width="70px" >&nbsp;&nbsp;&nbsp;Minggu Ke</td>
									<td width="10px" class="text-center">:</td>
									<td width="70px">
										<input type="text" class="form-control input-md" value="<?=$dataPD['Minggu']?>" readonly >
									</td>
									<td></td>
								</tr>
							</table>


							<table width="100%" border="0" class="huruf12 marginatas5">
								<tr>
									<td width="100px" >Alokasi</td>
									<td width="10px" class="text-center">:</td>
									<td width="320px">
										<input type="text" class="form-control input-md" value="<?=$dataPD['Alokasi'].' - '.$dataPD['KeteranganAlokasi']?>" readonly >
									</td>
									<td width="100px" >&nbsp;&nbsp;&nbsp;Tipe Form</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-md" value="<?=$dataPD['TipeForm'].' - '.$dataPD['KeteranganTipeForm']?>" readonly >
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas5">
								<tr>
									<td width="100px" ></td>
									<td width="10px" class="text-center"></td>
									<td class="text-center bg-gray disabled color-pallete huruf13" style="padding-top:5px;padding-bottom:5px;">Bukti Pendukung</td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas5">
								<tr>
									<td width="100px" ></td>
									<td width="10px" class="text-center"></td>
									<td width="230px">

										<input type="checkbox" id="INV" name="BuktiPendukung[]" value="INV" <?= (CekBuktiPendukung_Kedua($dataPD['BuktiPendukung'], 'INV') == TRUE) ? 'checked' : '' ?> >
										<label for="INV">Invoice/Kwitansi</label>
									</td>
									<td width="200px">
										<input type="checkbox" id="FP" name="BuktiPendukung[]" value="FP" <?= (CekBuktiPendukung_Kedua($dataPD['BuktiPendukung'], 'FP') == TRUE) ? 'checked' : '' ?> >
										<label for="FP">FP</label>
									</td>
									<td></td>
								</tr>

								<!-- === BATAS == -->
								<tr>
									<td width="100px" ></td>
									<td width="10px" class="text-center"></td>
									<td width="230px">
										<input type="checkbox" id="SJ" name="BuktiPendukung[]" value="SJ" <?= (CekBuktiPendukung_Kedua($dataPD['BuktiPendukung'], 'SJ') == TRUE) ? 'checked' : '' ?> >
										<label for="SJ">Surat Jalan/Tanda Terima Lapangan</label>
									</td>
									<td width="200px">
										<input type="checkbox" id="BAP" name="BuktiPendukung[]" value="BAP" <?= (CekBuktiPendukung_Kedua($dataPD['BuktiPendukung'], 'BAP') == TRUE) ? 'checked' : '' ?> >
										<label for="BAP">Berita Acara Pembayaran</label>
									</td>
									<td></td>
								</tr>

								<!-- === BATAS == -->
								<tr>
									<td width="100px" ></td>
									<td width="10px" class="text-center"></td>
									<td width="230px">
										<input type="checkbox" id="PO" name="BuktiPendukung[]" value="PO" <?= (CekBuktiPendukung_Kedua($dataPD['BuktiPendukung'], 'PO') == TRUE) ? 'checked' : '' ?> >
										<label for="PO">Copy PO</label>
									</td>
									<td width="200px">
										<input type="checkbox" id="BAOP" name="BuktiPendukung[]" value="BAOP" <?= (CekBuktiPendukung_Kedua($dataPD['BuktiPendukung'], 'BAOP') == TRUE) ? 'checked' : '' ?> >
										<label for="BAOP">Berita Acara Opname Pekerjaan</label>
									</td>
									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas5">
								<tr>
									<td width="100px" >Keterangan</td>
									<td width="10px" class="text-center">:</td>
									<td width="300px">
										<input type="text"  name="Keterangan" class="form-control input-md " value="<?=$dataPD['Keterangan']?>" placeholder="Isi Disini" <?=$matikan_input?>  >
									</td>

									<td></td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas5">
								<tr>
									<td width="100px" >Tanggal PJ</td>
									<td width="10px" class="text-center">:</td>
									<td width="300px">
										<input type="date" id="TglPJ" name="TglPJ" class="form-control input-md " value="<?=$dataPD['TglPJ']?>" required <?=$matikan_input?> >
									</td>
									<td width="100px" ><small class="text-red huruf14">&nbsp;&nbsp;*wajib diisi</small></td>
									<td></td>
								</tr>
							</table>
						</div>
						<!-- == END FORM SEBELAH KIRI == -->

						<!-- == START FORM SEBELAH KANAN == -->
						<div class="col-sm-4">
							
							<table width="100%" border="0" class="huruf12">
								<tr>
									<td class="text-center bg-gray disabled color-pallete huruf13" style="padding-top:5px;padding-bottom:5px;">Bayar Kepada</td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas5">
								<tr>
									<td width="80px" >No. Kontrak/PO</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-md" value="<?=$dataPD['NoKO']?>" readonly >
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas5">
								<tr>
									<td width="80px" >No. Tagihan</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-md" value="<?=$dataPD['NoTagihan']?>" readonly >
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas5">
								<tr>
									<td width="80px" >ID</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-md" value="" readonly >
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas5">
								<tr>
									<td width="80px" >Nama</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-md" value="<?=$dataPD['Nama']?>" readonly >
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas5">
								<tr>
									<td width="80px" >Alamat</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-md" value="<?=$dataPD['Alamat']?>" readonly >
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas5">
								<tr>
									<td width="80px" >Telepon</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-md" value="<?=$dataPD['Telepon']?>" readonly >
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas5">
								<tr>
									<td width="80px" >NPWP</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-md" value="<?=$dataPD['NPWP']?>" readonly >
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12">
								<tr>
									<td class="text-center bg-gray disabled color-pallete huruf13" style="padding-top:5px;padding-bottom:5px;">Rekening Penerima</td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas5">
								<tr>
									<td width="80px" >No. Rekening</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-md" value="<?=$dataPD['NoRek']?>" readonly >
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas5">
								<tr>
									<td width="80px" >Atas Nama</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-md" value="<?=$dataPD['AtasNama']?>" readonly >
									</td>
								</tr>
							</table>

							<table width="100%" border="0" class="huruf12 marginatas5">
								<tr>
									<td width="80px" >Bank</td>
									<td width="10px" class="text-center">:</td>
									<td>
										<input type="text" class="form-control input-md" value="<?=$dataPD['Bank']?>" readonly >
									</td>
								</tr>
							</table>



						</div>
						<!-- == END FORM SEBELAH KANAN == -->
						
						<div class="col-sm-10">
							<hr style=" border: 1px solid black;">
							<table width="100%" border="0" class="huruf12 marginatas5 ">
								<tr>
									<td class="text-right">

										<input type="hidden" name="NoPD" value="<?=$dataPD['NoPD']?>">
										<input type="hidden" name="JobNo" value="<?=$dataPD['JobNo']?>">
										<input type="hidden" name="Alokasi" value="<?=$dataPD['Alokasi']?>">

										<button type="submit" class="btn btn-primary" id="btnSIMPAN" <?=$cekApprove?> >SIMPAN</button>&nbsp;&nbsp;
										<a href="<?=site_url('Entry/PDPJ/PJLap_C/index/'.$JobNo.'/'.$Alokasi)?>" class="btn btn-default">BATAL</a>
									</td>
								</tr>
							</table>
						</div>
					</form>
				</div>
				
			</div>
			<!-- ==END BOX HEADER -->

			<!-- ==START BOX BODY -->
			<div class="box-body" >

				<div class="row">
					<div class="col-sm-10">
						<table width="100%" border="0">
							<tr>
								<td style="padding-top:5px;padding-bottom:5px;" class="bg-gray text-center huruf15">ENTRY URAIAN</td>
							</tr>
						</table>

						<table width="100%" border="0">
							<tr>
								<th style="padding-top:5px;padding-bottom:5px;">
									<button type="button" class="btn btn-default" onclick="refreshtable()" <?=$cekApprove?> >REFRESH TABLE URAIAN <i class="fa fa-refresh"></i></button>
									<button type="button" class="btn btn-danger " onClick="btnTambahUraian()" <?=$btnTambahUraian?> <?=$cekApprove?>>TAMBAH</button>
									<button type="button" class="btn btn-primary" onclick="btnUpdateUraian('<?=$NoPD?>')" id="btnUpdateUraianID" <?=$cekApprove?>>ISI URAIAN APABILA = PD</button>
								</th>
							</tr>
						</table>
					</div>
				</div>
				<br>
				<div id="tableEntryUraian" ></div>
			</div>
			<!-- END BOX BODY -->
			<div class="overlay hidden"  id="loadingtable" >
				<i class="fa fa-refresh fa-spin"></i>
			</div>
			
		</div>
	</section>

</div>

<div id="ModalPJLap_EntryUraian" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title text-bold">Data Entry </h4>
			</div>
			<div class="modal-body">
				<form id="FormPJLap_EntryUraian">
					<div class="row">
						<!-- == START FORM KIRI == -->
						<div class="col-sm-6">
							<div class="table-responsive">
								<table width="100%" border="0">
									<tr>
										<th style="font-size: 16px !important;"><u>Permintaan Dana</u></th>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas5">
									<tr>
										<td width="110px">No</td>
										<td width="20px" class="text-center">:</td>
										<td>
											<div class="col-xs-3 row">
												<input type="text" id="NoUrut" name="NoUrut" class="form-control" readonly  >
											</div>
											
										</td>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas5">
									<tr>
										<td width="110px">Kode RAP</td>
										<td width="20px" class="text-center">:</td>
										<td>
											<input type="text" id="KdRAP" class="form-control input-md"  disabled>
										</td>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas5">
									<tr>
										<td width="110px">Uraian</td>
										<td width="20px" class="text-center">:</td>
										<td >
											<textarea id="Uraian" class="form-control input-md" placeholder="Isi Disini" disabled></textarea>
										</td>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas5">
									<tr>
										<td width="110px">Volume</td>
										<td width="20px" class="text-center">:</td>
										<td>
											<input type="text" id="Vol" class="form-control input-md" autocomplete="off"  disabled>
										</td>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas5">
									<tr>
										<td width="110px">Satuan</td>
										<td width="20px" class="text-center">:</td>
										<td>
											<input type="text" id="Uom" class="form-control input-md"  disabled>
										</td>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas5">
									<tr>
										<td width="110px">Harga Satuan (Rp) </td>
										<td width="20px" class="text-center">:</td>
										<td>
											<input type="text" id="HrgSatuan" class="form-control input-md" autocomplete="off" disabled>
										</td>
									</tr>
								</table>

								

							</div>
						</div>
						<!-- == END FORM KIRI == -->

						<!-- == START FORM KANAN == -->
						<div class="col-sm-6">
							<div class="table-responsive">
								<table width="100%" border="0">
									<tr>
										<th style="font-size: 16px !important;"><u>Pertanggungjawaban Dana</u></th>
									</tr>
								</table>


								<table width="100%" border="0" class="marginatas5">
									<tr>
										<td width="110px">No</td>
										<td width="20px" class="text-center">:</td>
										<td>
											<div class="col-xs-3 row">
												<input type="text" id="NoUrut" class="form-control"  disabled>
											</div>
											
										</td>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas5">
									<tr>
										<td width="110px">Kode RAP</td>
										<td width="20px" class="text-center">:</td>
										<td>
											<input type="text" id="KdRAP" class="form-control input-md"  disabled>
										</td>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas5">
									<tr>
										<td width="110px">Uraian</td>
										<td width="20px" class="text-center">:</td>
										<td >
											<textarea id="PjUraian" name="PjUraian" class="form-control input-md" placeholder="Isi Disini" required ></textarea>
										</td>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas5">
									<tr>
										<td width="110px">Volume</td>
										<td width="20px" class="text-center">:</td>
										<td>
											<input type="text" id="PjVol" name="PjVol" class="form-control input-md" required  >
										</td>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas5">
									<tr>
										<td width="110px">Satuan</td>
										<td width="20px" class="text-center">:</td>
										<td>
											<input type="text" id="Uom" class="form-control input-md"  disabled>
										</td>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas5">
									<tr>
										<td width="110px">Harga Satuan (Rp) </td>
										<td width="20px" class="text-center">:</td>
										<td>
											<input type="text" id="PjHrgSatuan" name="PjHrgSatuan" class="form-control input-md"  required>
										</td>
									</tr>
								</table>

								


							</div>
						</div>
						<!-- == START FORM KANAN == -->
						<div class="col-sm-12 text-right">
							<hr style="border: 1px solid black;">
							<input type="hidden" name="NoPD" id="NoPD" >

							<button type="submit" class="btn btn-primary btn-md" <?=$cekApprove?>>SIMPAN</button>
							<button type="button" class="btn btn-default"  data-dismiss="modal">BATAL</button>
						</div>

					</div>


					
				</form>
			</div>

		</div>

	</div>
</div>

<!-- == START MODAL TAMBAH URAIAN == -->
<!-- == modal tambah uraian hanya bisa di gunakan oleh Alokasi C == -->
<div id="ModalTambahUraian" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">

		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title text-bold">Data Entry </h4>
			</div>
			<div class="modal-body">
				<form id="FormTambahUraian">

					<div class="row">
						
						<div class="col-sm-12">
							<div class="table-responsive">
								<table width="100%" border="0" class="marginatas5">
									<tr>
										<td width="110px">No</td>
										<td width="20px" class="text-center">:</td>
										<td>
											<div class="col-xs-3 row">
												<input type="text" id="NoUrut_Tambah" name="NoUrut_Tambah" class="form-control input-md" disabled  required>
											</div>
											
										</td>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas5">
									<tr>
										<td width="110px">Kode RAP</td>
										<td width="20px" class="text-center">:</td>
										<td>
											<select name="KdRAP_Tambah" id="KdRAP_Tambah" class="form-control input-md" required>
												<option value="C.00">Pengembalian Saldo</option>
											</select>
										</td>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas5">
									<tr>
										<td width="110px">Uraian</td>
										<td width="20px" class="text-center">:</td>
										<td >
											<textarea id="Uraian_Tambah" name="Uraian_Tambah" class="form-control input-md" placeholder="Isi Disini" required></textarea>
										</td>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas5">
									<tr>
										<td width="110px">Volume</td>
										<td width="20px" class="text-center">:</td>
										<td>
											<input type="text" id="Vol_Tambah" name="Vol_Tambah" class="form-control input-md"  placeholder="Isi Disini" required>
										</td>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas5">
									<tr>
										<td width="110px">Satuan</td>
										<td width="20px" class="text-center">:</td>
										<td>
											<input type="text" id="Uom_Tambah" name="Uom_Tambah" class="form-control input-md" placeholder="Isi Disini" >
										</td>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas5">
									<tr>
										<td width="110px">Harga Satuan (Rp) </td>
										<td width="20px" class="text-center">:</td>
										<td>
											<input type="text" id="HrgSatuan_Tambah" name="HrgSatuan_Tambah" class="form-control input-md" placeholder="Isi Disini" required>
										</td>
									</tr>
								</table>

								

							</div>
						</div>

						<div class="col-sm-12 text-right">
							<hr style="border: 1px solid black;">

							<button type="submit" class="btn btn-primary btn-md" id="btnSimpanTambahUraian" <?=$cekApprove?>>SIMPAN</button>
							<button type="button" class="btn btn-default"  data-dismiss="modal">BATAL</button>
						</div>

					</div>
					
				</form>
			</div>

		</div>

	</div>
</div>
<!-- == END MODAL TAMBAH URAIAN == -->

<div class="clearfix"></div>


<script src="<?=base_url('assets/select2_baru.js')?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		PindahData_PdDtl_To_PdDtl_temp();
		// tableEntryUraian();

		var formatUang_Nol_Dua = function(num){
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

		var cobacoba = function(num){
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
			return(""((parts) ? "." + parts[1].substr(0, 2) : ""));
		};

		$.fn.digits = function(){ 
			return this.each(function(){ 
				$(this).text( $(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") ); 
			})
		}

		// == START PENGETIKAN PjVol ==
		$("#FormPJLap_EntryUraian #PjVol").keyup(function (event) {
			if ((pointPos = this.value.indexOf('.')) >= 0)
				$(this).attr("maxLength", pointPos+3);
			else
				$(this).removeAttr("maxLength");
		});

		$('#FormPJLap_EntryUraian #PjVol').focus(function(){
			var nilai = HapusKoma(this.value);
			$(this).val(nilai);
		});

		$('#FormPJLap_EntryUraian #PjVol').blur(function(){
			$(this).val(formatUang_Nol_Dua($(this).val()));
		});
		// == END PENGETIKAN PjVol ==

		// == START PENGETIKAN PjHrgSatuan ==
		$("#FormPJLap_EntryUraian #PjHrgSatuan").keyup(function (event) {
			if ((pointPos = this.value.indexOf('.')) >= 0)
				$(this).attr("maxLength", pointPos+3);
			else
				$(this).removeAttr("maxLength");
		});

		$('#FormPJLap_EntryUraian #PjHrgSatuan').focus(function(){
			var nilai = HapusKoma(this.value);
			$(this).val(nilai);
		});

		$('#FormPJLap_EntryUraian #PjHrgSatuan').blur(function(){
			$(this).val( formatUang_Nol_Dua(Math.round($(this).val())));
			// $(this).val(formatUang_Nol_Dua($(this).val()));
		});
		// == END PENGETIKAN PjHrgSatuan ==

		$('#FormPJLap_EntryUraian').submit(function(event){
			event.preventDefault();
			jQuery.ajax({
				url: "<?=site_url('Entry/PDPJ/PJLap_C/Aksi_UpdateUraian')?>",
				type: 'POST',
				dataType: 'html',
				data: $(this).serialize(),
				cache : false,
				beforeSend : function(){
					$(this).attr('disabled',true);
				},
				complete: function(xhr, textStatus) {

				},
				success: function(data, textStatus, xhr) {

					if (data=='berhasil') {
						refreshtable();
						$('#ModalPJLap_EntryUraian').modal('hide');
						toastr.success('update sukses');
						return false;
					}

					$('#ModalPJLap_EntryUraian').modal('hide');
					toastr.error('update failed!');
					return false;
					
				},
				error: function(xhr, textStatus, errorThrown) {
					alert('Error, Terjadi Kesalahan Menampilkan Data');
					return false;
				}
			});
			
		});

		$('#FormUpdate_PJLapangan').submit(function(event){
			event.preventDefault();

			var TglPJ = $('#FormUpdate_PJLapangan #TglPJ');
			var TglPD = $('#FormUpdate_PJLapangan #TglPD');

			if (TglPJ=='') {
				alert('Tgl Permintaan belum di-isi');
				$('#TglPJ').focus();
				return false;
			}

			if (TglPJ < TglPD) {
				alert('Tgl PJ lebih kecil dari Tgl Permintaan.');
				$('#TglPJ').focus();
				return false;
			}

			var BuktiPendukung = $('input[name="BuktiPendukung[]"]:checked');
			BuktiPendukung.each(function(i, chkbox) {
			});

			if (BuktiPendukung.length==0) {
				alert('Belum pilih bukti pendukung.');
			}

			$("#mytable_uraian tbody tr").each(function () {
				
				var NoUrut = $(this).find("td.TxtNoUrut").text();
				var Uraian = $(this).find('td.TxtUraian').text();
				
				if (Uraian=='') {
					alert('No '+NoUrut+' belum isi PJ.');
					return false;
				}
			});

			var FormBaru = $(this).serializeArray();
			var JobNo = $('#FormUpdate_PJLapangan').attr('data-JobNo');
			var Alokasi = $('#FormUpdate_PJLapangan').attr('data-Alokasi');

			var sumTotalPJ = $('#mytable_uraian #sumTotalPJ').val();
			var Saldo = $('#Saldo').val();



			FormBaru.push({name: 'sumTotalPJ', value: sumTotalPJ});
			FormBaru.push({name: 'Saldo', value: Saldo});
			// alert(sumTotalPJ);	
			// return false;

			jQuery.ajax({
				url: "<?=site_url('Entry/PDPJ/PJLap_C/SimpanPJ_Aksi')?>",
				type: 'POST',
				dataType: 'html',
				data: FormBaru,
				cache : false,
				beforeSend : function(){
					$('#btnSIMPAN').attr('disabled',true);
				},
				complete: function(xhr, textStatus) {
					$('#btnSIMPAN').attr('disabled',false);
				},
				success: function(data, textStatus, xhr) {
					// alert(data);
					if (data=='PJkosong') {
						alert('Error while generate No PJ.');
						return false;
					}

					window.location.href="<?=site_url('Entry/PDPJ/PJLap_C/index/')?>"+JobNo+'/'+Alokasi;
					return false;
					
				},
				error: function(xhr, textStatus, errorThrown) {
					alert('Error, Terjadi Kesalahan saat mengelola Data');
					return false;
				}
			});


			
		});


		// == START PENGETIKAN PjVol SAAT TAMBAH ==
		$("#FormTambahUraian #Vol_Tambah").keyup(function (event) {
			if ((pointPos = this.value.indexOf('.')) >= 0)
				$(this).attr("maxLength", pointPos+3);
			else
				$(this).removeAttr("maxLength");
		});

		$('#FormTambahUraian #Vol_Tambah').focus(function(){
			var nilai = HapusKoma(this.value);
			$(this).val(nilai);
		});

		$('#FormTambahUraian #Vol_Tambah').blur(function(){
			$(this).val(formatUang_Nol_Dua($(this).val()));
		});
		// == END PENGETIKAN PjVol SAAT TAMBAH==



		// == START PENGETIKAN PjHrgSatuan SAAT TAMBAH ==
		$("#FormTambahUraian #HrgSatuan_Tambah").keyup(function (event) {
			if ((pointPos = this.value.indexOf('.')) >= 0)
				$(this).attr("maxLength", pointPos+10);
			else
				$(this).removeAttr("maxLength");
		});

		$('#FormTambahUraian #HrgSatuan_Tambah').focus(function(){
			var nilai = HapusKoma(this.value);
			$(this).val(nilai);
		});

		$('#FormTambahUraian #HrgSatuan_Tambah').blur(function(){
			$(this).val( formatUang_Nol_Dua(Math.round($(this).val())));
			// $(this).val(formatUang_Nol_Dua($(this).val()));
		});
		// == END PENGETIKAN PjHrgSatuan SAAT TAMBAH ==


		$('#FormTambahUraian').submit(function(event){
			event.preventDefault();

			if ($('#FormTambahUraian #Uraian_Tambah').val()=='') {
				alert('Uraian belum di-isi.');
				return false;
			}

			if ($('#FormTambahUraian #HrgSatuan_Tambah').val()=='' || $('#FormTambahUraian #HrgSatuan_Tambah').val()==0 ) {
				alert('Harga satuan belum di-isi.');
				return false;
			}

			var FormBaru = $(this).serializeArray();
			var NoPD = $('#FormUpdate_PJLapangan').attr('data-NoPD');
			FormBaru.push({name: 'NoPD', value: NoPD});

			jQuery.ajax({
				url: "<?=site_url('Entry/PDPJ/PJLap_C/Aksi_TambahUraian')?>",
				type: 'POST',
				dataType: 'html',
				data: FormBaru,
				cache : false,
				beforeSend : function(){
					$('#btnSimpanTambahUraian').attr('disabled',true);
				},
				complete: function(xhr, textStatus) {
					$('#btnSimpanTambahUraian').attr('disabled',false);
				},
				success: function(data, textStatus, xhr) {

					// alert(data)

					if (data=='berhasil') {
						refreshtable();
						$('#ModalTambahUraian').modal('hide');
						toastr.success('saved!');
						return false;
					}

					$('#ModalTambahUraian').modal('hide');
					toastr.error('failed!');
					return false;
					
				},
				error: function(xhr, textStatus, errorThrown) {
					alert('Error, Terjadi Kesalahan Menampilkan Data');
					return false;
				}
			});
			
		});

		
	});

function btnTambahUraian(){
	// alert('aaa');
	var JobNo = $('#FormUpdate_PJLapangan').attr('data-JobNo');
	var NoPD = $('#FormUpdate_PJLapangan').attr('data-NoPD');
	var Alokasi = $('#FormUpdate_PJLapangan').attr('data-Alokasi');

	if (Alokasi!='C') {
		return false;
	}

	$('#FormTambahUraian #Uraian_Tambah').val("");
	$('#FormTambahUraian #Vol_Tambah').val(1).attr('readonly',true);
	$('#FormTambahUraian #Uom_Tambah').val("Ls").attr('readonly',true);
	$('#FormTambahUraian #HrgSatuan_Tambah').val(0);

	$('#ModalTambahUraian').modal('show');
	
}

function HapusKoma(nilai) {
	return (nilai.replace(/,/g, ''));
}



function refreshtable(){
	tableEntryUraian();
}

function PindahData_PdDtl_To_PdDtl_temp(){

	var JobNo = $('#FormUpdate_PJLapangan').attr('data-JobNo');
	var Alokasi = $('#FormUpdate_PJLapangan').attr('data-Alokasi');
	var NoPD = $('#FormUpdate_PJLapangan').attr('data-NoPD');
	var NoKO = $('#FormUpdate_PJLapangan').attr('data-NoKO');

	jQuery.ajax({
		url: "<?=site_url('Entry/PDPJ/PJLap_C/PindahData_PdDtl_To_PdDtl_temp')?>",
		type: 'POST',
		dataType: 'html',
		cache : false,
		data: {JobNo : JobNo, Alokasi : Alokasi, NoPD : NoPD, NoKO : NoKO},
		beforeSend : function(){
			$('#loadingtable').removeClass('hidden');
		},
		complete: function(xhr, textStatus) {
			$('#loadingtable').addClass('hidden');
		},
		success: function(data, textStatus, xhr) {
				// alert(data)
			tableEntryUraian();
		},
		error: function(xhr, textStatus, errorThrown) {
			alert('Error, Terjadi Kesalahan Menampilkan Data');
			return false;
		}
	});
}


function tableEntryUraian(){

	var JobNo = $('#FormUpdate_PJLapangan').attr('data-JobNo');
	var Alokasi = $('#FormUpdate_PJLapangan').attr('data-Alokasi');
	var NoPD = $('#FormUpdate_PJLapangan').attr('data-NoPD');
	var NoKO = $('#FormUpdate_PJLapangan').attr('data-NoKO');

	jQuery.ajax({
		url: "<?=site_url('Entry/PDPJ/PJLap_C/tableEntryUraian')?>",
		type: 'POST',
		dataType: 'html',
		cache : false,
		data: {JobNo : JobNo, Alokasi : Alokasi, NoPD : NoPD, NoKO : NoKO},
		beforeSend : function(){
			$('#loadingtable').removeClass('hidden');
		},
		complete: function(xhr, textStatus) {
			$('#loadingtable').addClass('hidden');
		},
		success: function(data, textStatus, xhr) {
			$('#tableEntryUraian').html('');
			$('#tableEntryUraian').html(data);
			$('#mytable_uraian').DataTable({
				paging : false,
					// scrollY : false,
				columnDefs: [
					{ targets: [1,2,3,4,5,6,7], orderable : false},
					],
				order: [[0, 'asc']],
			});
		},
		error: function(xhr, textStatus, errorThrown) {
			alert('Error, Terjadi Kesalahan Menampilkan Data');
			return false;
		}
	});

}

function btnUpdateUraian(){

	var JobNo = $('#FormUpdate_PJLapangan').attr('data-JobNo');
	var Alokasi = $('#FormUpdate_PJLapangan').attr('data-Alokasi');
	var NoPD = $('#FormUpdate_PJLapangan').attr('data-NoPD');
	var NoKO = $('#FormUpdate_PJLapangan').attr('data-NoKO');

	if(NoPD==''){
		return false;
	}

	jQuery.ajax({
		url: "<?=site_url('Entry/PDPJ/PJLap_C/btnUpdateUraian')?>",
		type: 'POST',
		dataType: 'html',
		cache : false,
		data: {JobNo : JobNo, Alokasi : Alokasi, NoPD : NoPD, NoKO : NoKO},
		beforeSend : function(){
			$('#btnUpdateUraianID').addClass('disabled');
			$('#btnUpdateUraianID').text('Loading...');
		},
		complete: function(xhr, textStatus) {
			$('#btnUpdateUraianID').removeClass('disabled');
			$('#btnUpdateUraianID').text('ISI URAIAN APABILA = PD');
		},
		success: function(data, textStatus, xhr) {
			// alert(data);
			tableEntryUraian();

		},
		error: function(xhr, textStatus, errorThrown) {
			alert('Error, Terjadi Kesalahan Menampilkan Data');
			return false;
		}
	});



}

function btnSelectUraian(NoUrut=''){
	if (NoUrut=='') {
		return false;
	}

	var NoPD = $('#btnSelectUraianID'+NoUrut).attr('data-NoPD');
	var KdRAP_Lengkap = $('#btnSelectUraianID'+NoUrut).attr('data-KdRAP_Lengkap');

	var Uraian = $('#btnSelectUraianID'+NoUrut).attr('data-Uraian');
	var Vol = $('#btnSelectUraianID'+NoUrut).attr('data-Vol');
	var Uom = $('#btnSelectUraianID'+NoUrut).attr('data-Uom');
	var HrgSatuan = $('#btnSelectUraianID'+NoUrut).attr('data-HrgSatuan');

	var PjUraian = $('#btnSelectUraianID'+NoUrut).attr('data-PjUraian');
	var PjVol = $('#btnSelectUraianID'+NoUrut).attr('data-PjVol');
	var PjHrgSatuan = $('#btnSelectUraianID'+NoUrut).attr('data-PjHrgSatuan');

	$('#FormPJLap_EntryUraian #NoUrut').val('');
	$('#FormPJLap_EntryUraian #NoPD').val('');

	$('#FormPJLap_EntryUraian #KdRAP').val('');

	$('#FormPJLap_EntryUraian #Uraian').val('');
	$('#FormPJLap_EntryUraian #Vol').val('');
	$('#FormPJLap_EntryUraian #Uom').val('');
	$('#FormPJLap_EntryUraian #HrgSatuan').val('');

	$('#FormPJLap_EntryUraian #PjUraian').val('');
	$('#FormPJLap_EntryUraian #PjVol').val('');
	$('#FormPJLap_EntryUraian #PjHrgSatuan').val('');

	$('#FormPJLap_EntryUraian #NoUrut').val(NoUrut);
	$('#FormPJLap_EntryUraian #NoPD').val(NoPD);

	$('#FormPJLap_EntryUraian #KdRAP').val(KdRAP_Lengkap);

	$('#FormPJLap_EntryUraian #Uraian').val(Uraian);
	$('#FormPJLap_EntryUraian #Vol').val(Vol);
	$('#FormPJLap_EntryUraian #Uom').val(Uom);
	$('#FormPJLap_EntryUraian #HrgSatuan').val(HrgSatuan);

	$('#FormPJLap_EntryUraian #PjUraian').val(PjUraian);
	$('#FormPJLap_EntryUraian #PjVol').val(PjVol);
	$('#FormPJLap_EntryUraian #PjHrgSatuan').val(PjHrgSatuan);

	// alert(PjVol);

	$('#ModalPJLap_EntryUraian').modal('show');
}
</script>


