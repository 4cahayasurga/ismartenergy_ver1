<style type="text/css">
	.font10{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size : 10px;
	}
	.font11{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size : 11px;
	}
	.font12{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size : 12px;
	}
	.font13{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size: 13px;
	}
	.font14{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size : 14px;
	}
	.font16{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size : 16px;
	}
	.jarak5{
		padding-top: 5px;
		padding-bottom: 5px;
	}
	#tbl_search tr td{
		padding-top: 5px;
		padding-bottom: 5px;
	}
	.swal2-popup {
		font-size: 1.4rem !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}
	.swal-wide{
		width:550px !important;
	}

	.atur_jarak{
		padding-top: 3px;
		padding-bottom: 3px;
	}
</style>

<?php
$JobNm = $this->db->select('JobNm')->where('JobNo',$dataPD->JobNo)->get('Job')->row()->JobNm;

$KeteranganAlokasi = $this->db->select('Keterangan')->where('Alokasi',$dataPD->Alokasi)->get('Alokasi')->row()->Keterangan;

$KeteranganTipeForm = $this->db->query("SELECT Keterangan FROM TipeForm WHERE TipeForm='$dataPD->TipeForm' AND Alokasi='$dataPD->Alokasi' ")->row()->Keterangan;



?>

<div class="content-wrapper">

	<section class="content">
		<?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>
		<table>
			<tr>
				<td class="font14"><?=($judul) ? strtoupper($judul) : 'Judul belum diatur'  ?></td>
			</tr>
		</table>

		<br>


		<form id="FrmSimpanPJ">
			
			<div class="box box-primary">

				<div class="box-body">

					<div class="col-sm-6 row">

						<div class="table-responsive">

							<table width="100%" border="0" style="border-collapse: collapse;" class="font10" >

								<tr>
									<td width="12%" class="atur_jarak">No PD</td>
									<td width="1%" style="padding-left:3px;padding-right: 3px;text-align:center;" class="atur_jarak" >:</td>
									<td width="25%" class="atur_jarak">
										<input type="text" class="form-control input-sm font10" value="<?=$dataPD->NoPD?>" disabled>
										<input type="hidden" name="NoPD" id="NoPD" class="form-control input-sm font10" value="<?=$dataPD->NoPD?>" readonly>


									</td>
									<td width="10%" class="atur_jarak"></td>
									<td width="6%" class="atur_jarak" >No.	 PJ</td>
									<td width="1%" style="padding-left:3px;padding-right: 3px;text-align:center;" class="atur_jarak" >:</td>
									<td width="25%" class="atur_jarak">
										<input type="text"  class="form-control input-sm font10" value="<?=$dataPD->NoPJ?>" disabled>

										<input type="hidden" name="NoPJ" id="NoPJ" class="form-control input-sm font10" value="<?=$dataPD->NoPJ?>" readonly>

									</td>
									<td class="atur_jarak"></td>
								</tr>



								<tr>
									<td width="12%" class="atur_jarak">Job No</td>
									<td width="1%" style="padding-left:3px;padding-right: 3px;text-align:center;" class="atur_jarak" >:</td>
									<td width="25%" class="atur_jarak" >

										<input type="text" class="form-control input-sm font10" value="<?=$dataPD->JobNo.' - '.$JobNm?>" disabled>

										<input type="hidden" id="JobNo" name="JobNo" class="form-control input-sm font10" value="<?=$dataPD->JobNo?>">

									</td>
									<td width="10%" class="atur_jarak" ></td>
									<td width="13%" class="atur_jarak" >No. Ref Lapangan</td>
									<td width="1%" style="padding-left:3px;padding-right: 3px;text-align:center;" class="atur_jarak" >:</td>
									<td width="25%" class="atur_jarak" >

										<input type="text" name="NoRef" id="NoRef" class="form-control input-sm font10" value="<?=$dataPD->NoRef?>" disabled>
									</td>
									<td></td>
								</tr>

								<tr>
									<td width="12%" class="atur_jarak">Tgl Permintaan</td>
									<td width="1%" style="padding-left:3px;padding-right: 3px;text-align:center;" class="atur_jarak" >:</td>
									<td width="25%" class="atur_jarak" >

										<input type="date" id="TglPD" name="TglPD" class="form-control input-sm font10" width="25%" value="<?=$dataPD->TglPD?>" required >

									</td>
									<td width="10%" class="atur_jarak" ></td>
									<td width="13%" class="atur_jarak" ></td>
									<td width="1%" style="padding-left:3px;padding-right: 3px;text-align:center;" class="atur_jarak" ></td>
									<td width="25%" class="atur_jarak" ></td>
									<td></td>
								</tr>

								<tr>
									<td width="12%" class="atur_jarak">Deskripsi</td>
									<td width="1%" style="padding-left:3px;padding-right: 3px;text-align:center;" class="atur_jarak" >:</td>
									<td  class="atur_jarak" colspan="5" >

										<input type="text" id="Deskripsi" name="Deskripsi" class="form-control input-sm font10" value="<?=$dataPD->Deskripsi?>" disabled>

									</td>
								</tr>


							</table>


							<table width="100%" border="0" style="border-collapse: collapse;" class="font10" >

								<tr>
									<td width="10%" class="atur_jarak">Periode</td>
									<td width="1%" style="padding-left:3px;padding-right: 3px;text-align:center;" class="atur_jarak" >:</td>
									<td width="27%" class="atur_jarak" >

										<input type="date" class="form-control input-sm font10" name="PrdAwal" id="PrdAwal" value="<?=$dataPD->PrdAwal?>" disabled>

									</td>
									<td width="5%" class="atur_jarak" style="padding-left:3px !important;padding-right: :3px !important;text-align: center;">s.d</td>
									<td width="25%" class="atur_jarak" >
										<input type="date" class="form-control input-sm font10" name="PrdAkhir" id="PrdAkhir" value="<?=$dataPD->PrdAkhir?>" disabled>
									</td>
									<td width="10%" class="atur_jarak" style="padding-left:3px !important;padding-right: :3px !important;">Minggu Ke :</td>
									<td width="7%">
										<input type="text" name="Minggu" id="Minggu" class="form-control input-sm  font10" value="<?=$dataPD->Minggu?>" disabled>
									</td>

								</tr>

							</table>

							<table width="100%" border="0" style="border-collapse: collapse;" class="font10" >

								<tr>
									<td width="12%" class="atur_jarak">Alokasi</td>

									<td width="1%" style="padding-left:3px;padding-right: 3px;text-align:center;" class="atur_jarak" >:</td>

									<td width="30%" class="atur_jarak" >

										<input type="text" class="form-control input-sm font10" value="<?=$dataPD->Alokasi.' - '.$KeteranganAlokasi?>" disabled>

										<input type="hidden" class="form-control input-sm font10" name="Alokasi" id="Alokasi" value="<?=$dataPD->Alokasi?>">
									</td>

									<td width="15%" class="atur_jarak" style="padding-left:10px;">Tipe Form</td>

									<td  class="atur_jarak" >

										<input type="text" class="form-control input-sm font10" value="<?=$dataPD->TipeForm.' - '.$KeteranganTipeForm?>" disabled>

										<input type="hidden" class="form-control input-sm font10" name="TipeForm" id="TipeForm" value="<?=$dataPD->TipeForm?>">

									</td>
								</tr>

							</table>

							<table width="100%" border="0" style="border-collapse: collapse;height: 20px;;" class="font10" >

								<tr>
									<td width="12%" class="atur_jarak"></td>

									<td width="1%" style="padding-left:3px;padding-right: 3px;text-align:center;" class="atur_jarak" ></td>
									<td class="text-center atur_jarak bg-gray" style="padding-top:5px;padding-bottom:5px;" >Bukti Pendukung</td>
								</tr>
							</table>

							<table width="100%" border="0" style="border-collapse: collapse;height: 20px;;" class="font10" >

								<tr>
									<td width="12%" class="atur_jarak"></td>

									<td width="1%" style="padding-left:3px;padding-right: 3px;text-align:center;" class="atur_jarak" ></td>
									<td width="50%">
										<input type="checkbox" name="BuktiPendukung[]" value="INV" id="INV"  
										<?= (CekBuktiPendukung($dataPD->BuktiPendukung, 'INV') == TRUE) ? 'checked' : '' ?> >
										<label style="font-weight: normal;" for="INV">Invoice</label>
									</td>
									<td>
										<input type="checkbox" name="BuktiPendukung[]" value="FP" id="FP"  
										<?= (CekBuktiPendukung($dataPD->BuktiPendukung, 'FP') == TRUE) ? 'checked' : '' ?> >
										<label style="font-weight: normal;" for="FP">Faktur Pajak</label>
									</td>
									<td></td>
								</tr>

								<tr>
									<td width="12%" class="atur_jarak"></td>

									<td width="1%" style="padding-left:3px;padding-right: 3px;text-align:center;" class="atur_jarak" ></td>
									<td width="50%">
										<input type="checkbox" name="BuktiPendukung[]" value="SJ" id="SJ" 
										<?= (CekBuktiPendukung($dataPD->BuktiPendukung, 'SJ') == TRUE) ? 'checked' : '' ?> >
										<label style="font-weight: normal;" for="SJ">Surat Jalan/Tanda Terima Lapangan</label>
									</td>
									<td>
										<input type="checkbox" name="BuktiPendukung[]" value="BAP" id="BAP"   
										<?= (CekBuktiPendukung($dataPD->BuktiPendukung, 'BAP') == TRUE) ? 'checked' : '' ?> >
										<label style="font-weight: normal;" for="BAP">Berita Acara Pembayaran</label>
									</td>
									<td></td>
								</tr>

								<tr>
									<td width="12%" class="atur_jarak"></td>

									<td width="1%" style="padding-left:3px;padding-right: 3px;text-align:center;" class="atur_jarak" ></td>
									<td width="50%">
										<input type="checkbox" name="BuktiPendukung[]" value="PO" id="PO"  
										<?= (CekBuktiPendukung($dataPD->BuktiPendukung, 'PO') == TRUE) ? 'checked' : '' ?>>
										<label style="font-weight: normal;" for="PO">Copy PO</label>
									</td>
									<td>
										<input type="checkbox" name="BuktiPendukung[]" value="BAOP" id="BAOP" 
										<?= (CekBuktiPendukung($dataPD->BuktiPendukung, 'BAOP') == TRUE) ? 'checked' : '' ?> >
										<label style="font-weight: normal;" for="BAOP">Berita Acara Opname Pekerjaan</label>
									</td>
									<td></td>
								</tr>



							</table>

							<table width="100%" border="0" style="border-collapse: collapse;" class="font10" >

								<tr>
									<td width="12%" class="atur_jarak">Keterangan</td>

									<td width="1%" style="padding-left:3px;padding-right: 3px;text-align:center;" class="atur_jarak" >:</td>

									<td width="40%" class="atur_jarak" >
										<input type="Keterangan" class="form-control input-sm font10" name="Keterangan" id="Keterangan" value="<?=$dataPD->Keterangan?>" <?=$disabledText?>>
									</td>
									<td width="15%" class="atur_jarak" style="padding-left:10px;"></td>
									<td  class="atur_jarak" ></td>
								</tr>

							</table>

							<table width="100%" border="0" style="border-collapse: collapse;" class="font10" >

								<tr>
									<td width="12%" class="atur_jarak">Tgl PJ</td>

									<td width="1%" style="padding-left:3px;padding-right: 3px;text-align:center;" class="atur_jarak" >:</td>

									<td width="40%" class="atur_jarak" >
										<input type="date" class="form-control input-sm font10" name="TglPJ" id="TglPJ" value="<?=$dataPD->TglPJ?>" required <?=$disabledText?>>
									</td>
									<td width="15%" class="atur_jarak" style="padding-left:10px;"></td>
									<td  class="atur_jarak" ></td>
								</tr>

							</table>


						</div>

					</div>

					<div class="col-sm-6">

						<table width="100%" border="0" style="border-collapse: collapse;height: 20px;;" class="font10" >

							<tr>
								<td class="text-center atur_jarak bg-gray" style="padding-top:5px;padding-bottom:5px;" >Bayar Kepada</td>
							</tr>
						</table>

						<table width="100%" border="0" style="border-collapse: collapse;" class="font10" >

							<tr>
								<td width="15%" class="atur_jarak">No.Kontrak/PO</td>
								<td width="2%" style="padding-left:3px;padding-right: 3px;text-align:center;" class="atur_jarak" >:</td>
								<td class="text-center atur_jarak">

									<input type="text" class="form-control input-sm font10" name="NoKO" id="NoKO" value="<?=$dataPD->NoKO?>" disabled>

								</td>
							</tr>
						</table>


						<table width="100%" border="0" style="border-collapse: collapse;" class="font10" >
							<tr>
								<td width="15%" class="atur_jarak">No. Tagihan</td>
								<td width="2%" style="padding-left:3px;padding-right: 3px;text-align:center;" class="atur_jarak" >:</td>
								<td class="text-center atur_jarak">

									<input type="text" class="form-control input-sm font10" name="NoTagihan" id="NoTagihan" value="<?=$dataPD->NoTagihan?>" disabled>

								</td>
							</tr>
						</table>

						<table width="100%" border="0" style="border-collapse: collapse;" class="font10" >
							<tr>
								<td width="15%" class="atur_jarak">ID</td>
								<td width="2%" style="padding-left:3px;padding-right: 3px;text-align:center;" class="atur_jarak" >:</td>
								<td class="text-center atur_jarak">

									<input type="text" class="form-control input-sm font10" value="" disabled>

								</td>
							</tr>
						</table>

						<table width="100%" border="0" style="border-collapse: collapse;" class="font10" >
							<tr>
								<td width="15%" class="atur_jarak">Nama</td>
								<td width="2%" style="padding-left:3px;padding-right: 3px;text-align:center;" class="atur_jarak" >:</td>
								<td class="text-center atur_jarak">

									<input type="text" class="form-control input-sm font10" name="Nama" id="Nama" value="<?=$dataPD->Nama?>" disabled>

								</td>
							</tr>
						</table>

						<table width="100%" border="0" style="border-collapse: collapse;" class="font10" >
							<tr>
								<td width="15%" class="atur_jarak">Alamat</td>
								<td width="2%" style="padding-left:3px;padding-right: 3px;text-align:center;" class="atur_jarak" >:</td>
								<td class="text-center atur_jarak">

									<input type="text" class="form-control input-sm font10" name="Alamat" id="Alamat" value="<?=$dataPD->Alamat?>" disabled>

								</td>
							</tr>
						</table>

						<table width="100%" border="0" style="border-collapse: collapse;" class="font10" >
							<tr>
								<td width="15%" class="atur_jarak">Telepon</td>
								<td width="2%" style="padding-left:3px;padding-right: 3px;text-align:center;" class="atur_jarak" >:</td>
								<td class="text-center atur_jarak">

									<input type="text" class="form-control input-sm font10" name="Telepon" id="Telepon" value="<?=$dataPD->Telepon?>" disabled>

								</td>
							</tr>
						</table>

						<table width="100%" border="0" style="border-collapse: collapse;" class="font10" >
							<tr>
								<td width="15%" class="atur_jarak">NPWP</td>
								<td width="2%" style="padding-left:3px;padding-right: 3px;text-align:center;" class="atur_jarak" >:</td>
								<td class="text-center atur_jarak">

									<input type="text" class="form-control input-sm font10" name="NPWP" id="NPWP" value="<?=$dataPD->NPWP?>" disabled>

								</td>
							</tr>
						</table>




						<table width="100%" border="0" style="border-collapse: collapse;height: 20px;;" class="font10" >

							<tr>
								<td class="text-center atur_jarak bg-gray" style="padding-top:5px;padding-bottom:5px;" >Rekening Penerima</td>
							</tr>
						</table>

						<table width="100%" border="0" style="border-collapse: collapse;" class="font10" >
							<tr>
								<td width="15%" class="atur_jarak">No. Rekening</td>
								<td width="2%" style="padding-left:3px;padding-right: 3px;text-align:center;" class="atur_jarak" >:</td>
								<td class="text-center atur_jarak">

									<input type="text" class="form-control input-sm font10" name="NoRek" id="NoRek" value="<?=$dataPD->NoRek?>" disabled>

								</td>
							</tr>
						</table>

						<table width="100%" border="0" style="border-collapse: collapse;" class="font10" >
							<tr>
								<td width="15%" class="atur_jarak">Atas Nama</td>
								<td width="2%" style="padding-left:3px;padding-right: 3px;text-align:center;" class="atur_jarak" >:</td>
								<td class="text-center atur_jarak">
									<input type="text" class="form-control input-sm font10" name="AtasNama" id="AtasNama" value="<?=$dataPD->AtasNama?>" disabled >
								</td>
							</tr>
						</table>

						<table width="100%" border="0" style="border-collapse: collapse;" class="font10" >
							<tr>
								<td width="15%" class="atur_jarak">Bank</td>
								<td width="2%" style="padding-left:3px;padding-right: 3px;text-align:center;" class="atur_jarak" >:</td>
								<td class="text-center atur_jarak">

									<input type="text" class="form-control input-sm font10" name="Bank" id="Bank" value="<?=$dataPD->Bank?>" disabled>

								</td>
							</tr>
						</table>




					</div>


				</div>

				<div class="box-footer" style="border-top:1px solid;"   >

					<table width="100%">
						<tr>
							<td class="text-right">

								<button type="button" class="btn btn-default btn-md" onclick="history.back()">Batal</button>

									<button type="submit" class="btn btn-primary btn-md <?=($dataPD->PjApprovedByAK ==NULL) ? '' : 'hidden' ?>" id="btn_simpan"  >Simpan</button>
						
							</td>
							<td width="3%"></td>
						</tr>
					</table>

					<p></p>

					<table width="100%" class="font14" style="height: 25px;">
						<tr>
							<td class="text-center bg-gray">Entry Uraian</td>
						</tr>
					</table>
					<p></p>
					<table width="100%" class="font14" style="height: 25px;">
						<tr>
							<td class="text-left">
								<button type="button" class="btn btn-default btn-sm <?=$disabledButton?>" onclick="TampilkanTableUraian()" >Refresh Table <i class="fa fa-refresh"></i></button>

								<button type="button" class="btn btn-default btn-sm btn_UraianSamaDenganPD <?=$disabledButton?>" onclick="UraianSamaDenganPD()" >Isi Uraian Apabila = PD</button>
							</td>
						</tr>
					</table>

				</div>



			</div>

			<div id="loading_TableUraian" hidden>
				<span>Loading... <i class="fa fa-spin fa-spinner"></i></span>
			</div>

			
			<div id="TableUraian_tampil"></div>
			

		</form>

		

		

		<div id="myModal" class="modal fade" role="dialog">
			<div class="modal-dialog" style="width:1100px;">

				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h6 class="modal-title font14">Data Entry</h6>
					</div>
					<form id="FrmSelectUraian">
						<div class="modal-body">


							<div class="row" style="border:0px solid;">
								<div class="col-sm-6">
									
									<table class="font12" width="100%" border="0" >
										<tr>
											<td><u>Permintaan Dana</td></u>
										</tr>
									</table>
									<p></p>

									<table class="font10" width="100%" border="0" >
										<tr>
											<td width="5%" class="atur_jarak">No.</td>
											<td class="text-center atur_jarak" width="2%">:</td>
											<td width="80px" class="atur_jarak">
												<input type="text" name="NoUrut" id="NoUrut" class="form-control input-sm font10 atur_jarak" placeholder="No Urut" readonly>

												<!-- <input type="text" name="NoPD" class="form-control input-sm font10 atur_jarak" placeholder="No Urut" readonly> -->

											</td>
											<td></td>
										</tr>

										<tr>
											<td width="5%" class="atur_jarak">Kode RAP</td>
											<td class="text-center atur_jarak" width="2%">:</td>
											<td colspan="2" class="atur_jarak">
												<input type="text" name="KdRAP" id="KdRAP" class="form-control input-sm font10 atur_jarak" placeholder="Kode RAP" disabled>
											</td>
										</tr>

										<tr>
											<td width="5%" class="atur_jarak">Uraian</td>
											<td class="text-center atur_jarak" width="2%">:</td>
											<td colspan="2" class="atur_jarak">
												<textarea name="Uraian" id="Uraian" class="form-control input-sm font10" placeholder="Uraian" rows="4" disabled></textarea>
											</td>
											<!-- <td></td> -->
										</tr>

										<tr>
											<td width="5%" class="atur_jarak"  >Volume</td>
											<td class="text-center atur_jarak" width="2%">:</td>
											<td width="150px" class="atur_jarak">
												<input type="text" name="Vol" id="Vol" class="form-control input-sm font10" disabled>
											</td>
											<td></td>
										</tr>


										<tr>
											<td width="5%" class="atur_jarak"  >Satuan</td>
											<td class="text-center atur_jarak" width="2%">:</td>
											<td width="150px" class="atur_jarak">
												<input type="text" name="Uom" id="Uom" class="form-control input-sm font10" disabled>
											</td>
											<td></td>
										</tr>

										<tr>
											<td width="15%" class="atur_jarak"  >Harga Satuan</td>
											<td class="text-center atur_jarak" width="2%">:</td>
											<td width="150px" class="atur_jarak">
												<input type="text" name="HrgSatuan" id="HrgSatuan" class="form-control input-sm font10" disabled>
											</td>
											<td></td>
										</tr>



									</table>

								</div>

								<div class="col-sm-6">

									<table class="font12" width="100%" border="0" >
										<tr>
											<td><u>Pertanggungjawaban Dana</td></u>
										</tr>
									</table>
									<p></p>

									<table class="font10" width="100%" border="0" >
										<tr>
											<td width="5%" class="atur_jarak">No.</td>
											<td class="text-center atur_jarak" width="2%">:</td>
											<td width="80px" class="atur_jarak">
												<input type="text"  id="PjNoUrut" class="form-control input-sm font10 atur_jarak" placeholder="No Urut" disabled>
											</td>
											<td></td>
										</tr>

										<tr>
											<td width="5%" class="atur_jarak">Kode RAP</td>
											<td class="text-center atur_jarak" width="2%">:</td>
											<td colspan="2" class="atur_jarak">
												<input type="text" id="PjKdRAP" class="form-control input-sm font10 atur_jarak" placeholder="Kode RAP" disabled>
											</td>
										</tr>

										<tr>
											<td width="5%" class="atur_jarak">Uraian</td>
											<td class="text-center atur_jarak" width="2%">:</td>
											<td colspan="2" class="atur_jarak">
												<textarea name="PjUraian" id="PjUraian" class="form-control input-sm font10" placeholder="Uraian" rows="4" required=""></textarea>
											</td>
											<!-- <td></td> -->
										</tr>

										<tr>
											<td width="5%" class="atur_jarak"  >Volume</td>
											<td class="text-center atur_jarak" width="2%">:</td>
											<td width="150px" class="atur_jarak">
												<input type="text" name="PjVol" id="PjVol" class="form-control input-sm font10" required >
											</td>
											<td></td>
										</tr>


										<tr>
											<td width="5%" class="atur_jarak"  >Satuan</td>
											<td class="text-center atur_jarak" width="2%">:</td>
											<td width="150px" class="atur_jarak">
												<input type="text" id="PjUom" class="form-control input-sm font10" disabled>
											</td>
											<td></td>
										</tr>

										<tr>
											<td width="15%" class="atur_jarak"  >Harga Satuan</td>
											<td class="text-center atur_jarak" width="2%">:</td>
											<td width="150px" class="atur_jarak">
												<input type="text" name="PjHrgSatuan" id="PjHrgSatuan" class="form-control input-sm font10" required>
											</td>
											<td></td>
										</tr>



									</table>

								</div>



							</div>


						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default text-left" data-dismiss="modal">Batal</button>
							
							<?php if ( $dataPD->PjApprovedByAK == NULL): ?>

								<button type="submit" class="btn btn-primary btn_update "  >Update</button>
							<?php endif ?>


							
						</div>
					</form>
				</div>

			</div>
		</div>

	</section>


</div>


<script type="text/javascript">
	$(function(){

		var format = function(num){
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


		$("#HrgSatuan").keyup(function(e){
			$(this).val(format($(this).val()));
		});

		$("#Vol").keyup(function(e){
			$(this).val(format($(this).val()));
		});

		$("#PjVol").keyup(function(e){
			$(this).val(format($(this).val()));
		});

		$("#PjHrgSatuan").keyup(function(e){
			$(this).val(format($(this).val()));
		});

	});
</script>

<script>
	$(function(){

		TampilkanTableUraian('HalamanMulai');

		$( "#FrmSimpanPJ" ).submit(function( event ) {

			event.preventDefault();

			TglPD = $('#TglPD').val();
			TglPJ = $('#TglPJ').val();

			TotalPJ = $('input[name="TotalPJ"]').val();
			Saldo = $('input[name="Saldo"]').val();


			JobNo = $('input[name="JobNo"]').val();
			Alokasi = $('input[name="Alokasi"]').val();

			
			fields = $("input[name='BuktiPendukung[]']").serializeArray(); 
			if (fields.length === 0) 
			{ 
				Swal.fire(
					'Info!',
					'Belum pilih bukti pendukung.',
					'info'
					)
				return false;
			} 

			if (TglPJ < TglPD) {
				Swal.fire(
					'Info!',
					'Tgl PJ lebih kecil dari Tgl Permintaan.',
					'info'
					)
				return false;
			}

			if (TglPD == '') {
				Swal.fire(
					'Info!',
					'Tgl permintaan belum di-isi.',
					'info'
					)
				return false;
			}

			var data_save = $('#FrmSimpanPJ').serializeArray();
			data_save.push({ name: "NoPD", value: NoPD });
			data_save.push({ name: "Saldo", value: Saldo });
			

			

			jQuery.ajax({
				url: '<?=site_url('FrmPJLap/SimpanPJ')?>',
				type: 'POST',
				dataType: 'html',
				data: $(this).serialize(),
				beforeSend : function(){
					$('#btn_simpan').text('Loading...').attr('disabled',true);

				},
				complete: function(xhr, textStatus) {
					$('#btn_simpan').text('Simpan').attr('disabled',false);
				},
				success: function(data, textStatus, xhr) {
					// alert(data);
					// console.log(data);

					if (data == 'berhasil') {

						$.toast({
							text: 'Berhasil di update',
							icon : 'success',
							hideAfter: 1000
						})

						window.location.href="<?=site_url('FrmPJLap/view/')?>"+JobNo+'/'+Alokasi+'/1';

					} else if(data == 'gagal'){
						Swal.fire(
							'Failed!',
							'Gagal di update',
							'error'
							)
					}else{
						alert('error');
					}

					

				},
				error: function(xhr, textStatus, errorThrown) {
					alert('error');
				}
			});
			
		});

		$( "#FrmSelectUraian" ).submit(function( event ) {

			event.preventDefault();

			NoPD = $('input[name="NoPD"]').val();
			RemarkOverrideSaldo = $('input[name="RemarkOverrideSaldo"]').val();
			Saldo = $('input[name="Saldo"]').val();
			// alert(NoPD);
			// return false;

			var data_save = $('#FrmSelectUraian').serializeArray();
			data_save.push({ name: "NoPD", value: NoPD });
			data_save.push({ name: "RemarkOverrideSaldo", value: RemarkOverrideSaldo });
			data_save.push({ name: "Saldo", value: Saldo });

			jQuery.ajax({
				url: '<?=site_url('FrmPJLap/Update_UraianPJ')?>',
				type: 'POST',
				dataType: 'html',
				data: data_save,
				beforeSend : function(){
					$('.btn_update').text('Loading...').attr('disabled',true);
					
				},
				complete: function(xhr, textStatus) {
					$('.btn_update').text('Update').attr('disabled',false);
					
				},
				success: function(data, textStatus, xhr) {

					// console.log(data);
					// alert(data);
					// return false;

					if (data == 'berhasil') {
						Swal.fire(
							'Sukses',
							'Berhasil di update',
							'success'
							)
					} else if(data == 'gagal'){
						Swal.fire(
							'Failed!',
							'Gagal di update',
							'error'
							)
					}else{
						alert('error');
					}
					// console.log(data);

					// alert(data);
					TampilkanTableUraian();

					$('#myModal').modal('hide');
					$('#FrmSelectUraian')[0].reset();
					$('#NoUrut').val('');
					$('#KdRAP').val('');
					$('#Uraian').val('');
					$('#Vol').val('');
					$('#Uom').val('');
					$('#HrgSatuan').val('');


					$('#PjNoUrut').val('');
					$('#PjKdRAP').val('');
					$('#PjUraian').val('');
					$('#PjVol').val('');
					$('#PjUom').val('');
					$('#PjHrgSatuan').val('');

				},
				error: function(xhr, textStatus, errorThrown) {
					alert('error');
				}
			});
			
		});




	});

	function TampilkanTableUraian(flag=''){

		// flag berguna untuk pengaturan data PdDtl untuk di olah atau di pindahkan sementara
		NoPD = $('#NoPD').val();
		JobNo = $('#JobNo').val();



		jQuery.ajax({
			url: '<?=site_url('FrmPJLap/TampilkanTableUraian')?>',
			type: 'POST',
			dataType: 'html',
			data: {'NoPD' : NoPD, flag : flag, JobNo : JobNo},
			beforeSend : function(){
				$('#btn_simpan').text('Loading...').attr('disabled',true);
				$('#loading_TableUraian').show();
				$('#TableUraian_tampil').html('');
			},
			complete: function(xhr, textStatus) {
				$('#btn_simpan').text('Simpan').attr('disabled',false);
				$('#loading_TableUraian').hide();
			},
			success: function(data, textStatus, xhr) {
				$('#TableUraian_tampil').html(data);
			},
			error: function(xhr, textStatus, errorThrown) {
				alert('error');
			}
		});


	}


	function UraianSamaDenganPD(){
		NoPD = $('#NoPD').val();

		// alert(NoPD);
		// return false;

		jQuery.ajax({
			url: '<?=site_url('FrmPJLap/UraianSamaDenganPD')?>',
			type: 'POST',
			dataType: 'html',
			data: {'NoPD' : NoPD},
			beforeSend : function(){
				$('.btn_UraianSamaDenganPD').text('Loading...').attr('disabled',true);
			},
			complete: function(xhr, textStatus) {
				$('.btn_UraianSamaDenganPD').text('Isi Uraian Apabila = PD').attr('disabled',false);

			},
			success: function(data, textStatus, xhr) {
				// $('#TableUraian_tampil').html(data);
				// alert(data);
				TampilkanTableUraian();
			},
			error: function(xhr, textStatus, errorThrown) {
				alert('error');
			}
		});


	}

	function SelectUraian(NoUrut=''){
		if (NoUrut == '') {
			alert('error');
			return false;
		}

		$('#FrmSelectUraian')[0].reset();
		$('#NoUrut').val('');
		$('#KdRAP').val('');
		$('#Uraian').val('');
		$('#Vol').val('');
		$('#Uom').val('');
		$('#HrgSatuan').val('');


		$('#PjNoUrut').val('');
		$('#PjKdRAP').val('');
		$('#PjUraian').val('');
		$('#PjVol').val('');
		$('#PjUom').val('');
		$('#PjHrgSatuan').val('');

		NoPD = $('#BtnSelect_'+NoUrut).attr('data-NoPD');
		NoUrut = $('#BtnSelect_'+NoUrut).attr('data-NoUrut');
		KdRAP = $('#BtnSelect_'+NoUrut).attr('data-KdRAP');
		UraianKdRAP = $('#BtnSelect_'+NoUrut).attr('data-UraianKdRAP');
		Uraian = $('#BtnSelect_'+NoUrut).attr('data-Uraian');
		Vol = $('#BtnSelect_'+NoUrut).attr('data-Vol');
		Uom = $('#BtnSelect_'+NoUrut).attr('data-Uom');
		HrgSatuan = $('#BtnSelect_'+NoUrut).attr('data-HrgSatuan');


		PjUraian = $('#BtnSelect_'+NoUrut).attr('data-PjUraian');
		PjVol = $('#BtnSelect_'+NoUrut).attr('data-PjVol');
		PjHrgSatuan = $('#BtnSelect_'+NoUrut).attr('data-PjHrgSatuan');


		$('#myModal').modal('show');

		$('#NoUrut').val(NoUrut);
		$('#KdRAP').val(KdRAP+' - '+UraianKdRAP);
		$('#Uraian').val(Uraian);
		$('#Vol').val(Vol);
		$('#Uom').val(Uom);
		$('#HrgSatuan').val(HrgSatuan);

		$('#PjNoUrut').val(NoUrut);
		$('#PjKdRAP').val(KdRAP+ ' - '+UraianKdRAP);
		$('#PjUraian').val(PjUraian);
		$('#PjVol').val(PjVol);
		$('#PjUom').val(Uom);
		$('#PjHrgSatuan').val(PjHrgSatuan);


		// jQuery.ajax({
		// 	url: '<?=site_url('FrmPJLap/FormUraian')?>',
		// 	type: 'POST',
		// 	dataType: 'html',
		// 	data: {NoUrut : NoUrut, NoPD : NoPD },
		// 	beforeSend : function(){
		// 		$('#BtnSelect_'+NoUrut).text('Loading...').attr('disabled',true);

		// 	},
		// 	complete: function(xhr, textStatus) {
		// 		$('#BtnSelect_'+NoUrut).text('SELECT').attr('disabled',false);
		// 	},
		// 	success: function(data, textStatus, xhr) {
		// 		// alert(data);
		// 		$('#myModal').modal('show');
		// 	},
		// 	error: function(xhr, textStatus, errorThrown) {
		// 		alert('error');
		// 	}
		// });


	}

	function ResetModal(){
		$('#FrmSelectUraian')[0].reset();
	}

</script>
