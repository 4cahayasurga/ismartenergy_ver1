<link rel="stylesheet" href="<?=base_url() ?>assets/mycss/PD_baru.css">




<div class="col-xs-6 row" >

	<p class="utk_P" style="padding-bottom:5px;" >** Harap mencantumkan tanda tangan & nama jelas</p>

	<div class="col-xs-3 row">

		<table  id="tbl_saya" border="1">
			<tr>
				<td class="text-center ukuran_delapan" colspan="2" >Dibuat oleh**</td>
			</tr>

			<tr>
				<td style="text-align:center;vertical-align:bottom;height:50px;width:50%;" class="ukuran_delapan"  >Ad Keu Project</td>
				<td style="text-align:center;vertical-align:bottom;" class="ukuran_delapan" >Ka Pro</td>
			</tr>

		</table>

	</div>

	<div class="col-xs-3">

		<table  id="tbl_saya" border="1">
			<tr>
				<td class="text-center ukuran_delapan" colspan="2" >Diverifikasi oleh**</td>
			</tr>

			<tr>
				<td style="text-align:center;vertical-align:bottom;height:50px;width:50%;" class="ukuran_delapan"  >Project Control</td>
				<td style="text-align:center;vertical-align:bottom;" class="ukuran_delapan" >Keuangan</td>
			</tr>

		</table>

	</div>

	<div class="col-xs-3">

		<table  id="tbl_saya" border="1">
			<tr>
				<td class="text-center ukuran_delapan" colspan="2" >Disetujui oleh**</td>
			</tr>

			<tr>
				<td style="text-align:center;vertical-align:bottom;height:50px;width:50%;" class="ukuran_delapan"  >Dir. Pmr & Opr</td>
				<td style="text-align:center;vertical-align:bottom;" class="ukuran_delapan" >Dir. Keu</td>
			</tr>

		</table>

	</div>
	

	<div class="col-3 ">

		<table  id="tbl_saya" border="1">
			<tr>
				<td class="text-center ukuran_delapan">Dibayar oleh**</td>
			</tr>

			<tr>
				<td style="text-align:center;vertical-align:bottom;height:50px;width:50%;" class="ukuran_delapan"  >Kasir</td>
				
			</tr>

		</table>

	</div>

</div>

<div class="col-xs-5">
	
	<p class="utk_P" style="padding-bottom:5px;" >** Harap mencantumkan tanda tangan & nama jelas</p>

	<div class="col-xs-3 row">

		<table  id="tbl_saya" border="1">
			<tr>
				<td class="text-center ukuran_delapan" colspan="2" >Dibuat oleh**</td>
			</tr>

			<tr>
				<td style="text-align:center;vertical-align:bottom;height:50px;width:50%;" class="ukuran_delapan"  >Ad Keu Project</td>
				<td style="text-align:center;vertical-align:bottom;" class="ukuran_delapan" >Ka Pro</td>
			</tr>

		</table>

	</div>

	<div class="col-xs-3">

		<table  id="tbl_saya" border="1">
			<tr>
				<td class="text-center ukuran_delapan" colspan="2" >Diverifikasi oleh**</td>
			</tr>

			<tr>
				<td style="text-align:center;vertical-align:bottom;height:50px;width:50%;" class="ukuran_delapan"  >Project Control</td>
				<td style="text-align:center;vertical-align:bottom;" class="ukuran_delapan" >Keuangan</td>
			</tr>

		</table>

	</div>

	<div class="col-xs-3">

		<table  id="tbl_saya" border="1">
			<tr>
				<td class="text-center ukuran_delapan" colspan="2" >Disetujui oleh**</td>
			</tr>

			<tr>
				<td style="text-align:center;vertical-align:bottom;height:50px;width:50%;" class="ukuran_delapan"  >Dir. Pmr & Opr</td>
				<td style="text-align:center;vertical-align:bottom;" class="ukuran_delapan" >Dir. Keu</td>
			</tr>

		</table>

	</div>

</div>


<!-- =====================batas================================= -->

<div class="col-xs-6 row">

	<table width="100%" border="0" style="padding-top:10px;">
		<tr>
			<th style="background-color:#95a5a6;font-weight: normal;text-align: center;" class="ukuran_delapan">Detail Pembayaran</th>
		</tr>
	</table>

	<table width="100%" border="0">
		<tr>
			<td width="40%"></td>
			<td class="ukuran_delapan text-left" width="15%" >* Tgl Pembayaran : </td>
			<td class="text-left ukuran_delapan" width="20%" style="border-bottom: 1px solid;" ><?=cek_pj_saya($NoPJ,TanggalSaya($tglBayarPJ))?></td>
			<td ></td>
		</tr>
	</table>

	<table width="100%" border="0">
		<tr>
			<th style="background-color:#95a5a6;font-weight: normal;text-align: center;" class="ukuran_delapan">Pembayaran Via</th>
		</tr>
	</table>

	<table width="100%" border="0">
		<tr>
			
			<!-- ============== tunai ====================== -->
			<td width="40%">

				<table width="100%" border="0" class="ukuran_delapan">
					<tr>
						<td><label><input type="checkbox" class="form-control ukuran_delapan" <?=cek_pj_saya($NoPJ,($pj_bayar['CaraBayar'] == 'CASH') ? 'checked="checked"' : '') ?>  ><i class="fa fa-checked"></i> Tunai Setor</label></td>
						<td>Sumber Kas : </td>
					</tr>
				</table>

				<table width="100%" border="0" class="ukuran_delapan">
					<tr>
						<td><label><input type="checkbox" class="form-control ukuran_delapan" <?=cek_pj_saya($NoPJ,($pj_bayar['SumberKas'] == 'JKT') ? 'checked="checked"' : '') ?>  ><i class="fa fa-checked"></i> JKT</label></td>
						<td><label><input type="checkbox" class="form-control ukuran_delapan" <?=cek_pj_saya($NoPJ,($pj_bayar['SumberKas'] == 'MKS') ? 'checked="checked"' : '') ?>  ><i class="fa fa-checked"></i> MKS</label></td>
						<td><label><input type="checkbox" class="form-control ukuran_delapan" <?=cek_pj_saya($NoPJ,($pj_bayar['SumberKas'] == 'LAP') ? 'checked="checked"' : '') ?>  ><i class="fa fa-checked"></i> LAP</label></td>
					</tr>
				</table>

				<table width="100%" border="0" class="ukuran_delapan">
					<tr>
						<td width="30%">Penerima : </td>
						<td style="border-bottom:1px solid;"><?=cek_pj_saya($NoPJ,($pj_bayar['CaraBayar'] == 'CASH') ? $pj_bayar['NmPenerimaTunai'] : '-') ?></td>
					</tr>
				</table>

			</td>

			<!-- ===================================== transfer ========================================= -->
			
			<td width="30%">

				<table width="100%" border="0" class="ukuran_delapan">
					<tr>
						<td><label><input type="checkbox" class="form-control ukuran_delapan" <?=cek_pj_saya($NoPJ,($pj_bayar['CaraBayar'] == 'TRF') ? 'checked="checked"' : '') ?>  ><i class="fa fa-checked"></i> Transfer</label></td>
					</tr>
				</table>

				<table width="100%" border="0" class="ukuran_delapan">
					<tr>
						<td>Jenis Trf : </td>
					</tr>
				</table>



				<table width="100%" border="0" class="ukuran_delapan">
					<tr>
						<td style="border-bottom:1px solid;"><?=cek_pj_saya($NoPJ,($pj_bayar['CaraBayar'] == 'TRF') ? $pj_bayar['JenisTrf'] : '-') ?></td>
					</tr>
				</table>

			</td>

			<!-- ===================================== Cheque ========================================= -->

			<td width="30%">

				<table width="100%" border="0" class="ukuran_delapan">
					<tr>
						<td><label><input type="checkbox" class="form-control ukuran_delapan" <?=cek_pj_saya($NoPJ,($pj_bayar['CaraBayar'] == 'CG') ? 'checked="checked"' : '') ?>  ><i class="fa fa-checked"></i> Cheque/Giro</label></td>
					</tr>
				</table>

				<table width="100%" border="0" class="ukuran_delapan">
					<tr>
						<td width="50%">No. Cheque/Giro :</td>
						<td style="border-bottom:1px solid;"><?=cek_pj_saya($NoPJ,($pj_bayar['CaraBayar'] == 'CG') ? $pj_bayar['NoCG'] : '-') ?></td>
					</tr>
				</table>

				<table width="100%" border="0" class="ukuran_delapan">
					<tr>
						<td width="50%">Tgl. Cheque/Giro :</td>
						<td style="border-bottom:1px solid;"><?=cek_pj_saya($NoPJ,($pj_bayar['CaraBayar'] == 'CG') ? TanggalSaya($pj_bayar['TglBayar']) : '-') ?></td>
					</tr>
				</table>

			</td>

		</tr>
	</table>

	<?php
	$NoRek_Kirim = '';
	$AtasNama_Kirim = '';
	$BankKirim = '';

	$RekId = $pj_bayar['RekId'];
	if ($pj_bayar['CaraBayar'] == 'TRF' ) {
		$query  = $this->db->query("SELECT * FROM Rekening WHERE RekId='$RekId'  ")->row_array();
		$NoRek_Kirim = $query['NoRek'];
		$AtasNama_Kirim = $query['AtasNama'];
		$BankKirim = $query['Bank'];
	}
	?>
	<table width="100%" border="0">
		<tr>
			<td width="50%" class="text-center ukuran_delapan"  style="background-color:#95a5a6;font-weight: normal;text-align: center;">Rekening Pengirim</td>
			<td width="50%" class="text-center ukuran_delapan"  style="background-color:#95a5a6;font-weight: normal;text-align: center;">Rekening Penerima</td>
		</tr>

		<tr>

			=================== rek pengirim ===================================
			<td width="50">

				<table width="100%" border="0">
					<tr>
						<td width="20%"  class="ukuran_delapan" >No. Rek : </td>
						<td style="border-bottom: 1px solid;" class="ukuran_delapan"><?=cek_pj_saya($NoPJ,$NoRek_Kirim)?></td>
					</tr>
					<tr>
						<td width="20%"  class="ukuran_delapan" >Atas Nama : </td>
						<td style="border-bottom: 1px solid;" class="ukuran_delapan"><?=cek_pj_saya($NoPJ,$AtasNama_Kirim)?></td>
					</tr>
					<tr>
						<td width="20%"  class="ukuran_delapan" >Bank : </td>
						<td style="border-bottom: 1px solid;" class="ukuran_delapan"><?=cek_pj_saya($NoPJ,$BankKirim)?></td>
					</tr>
				</table>

			</td>

			<!-- =============================== rek penerima ===================================== -->
			<td width="50%">
				
				<table width="100%" border="0">
					<tr>
						<td width="20%"  class="ukuran_delapan" >No. Rek : </td>
						<td style="border-bottom: 1px solid;" class="ukuran_delapan"><?=$dataPD['NoRek']  ?></td>
					</tr>
					<tr>
						<td width="20%"  class="ukuran_delapan" >Atas Nama : </td>
						<td style="border-bottom: 1px solid;" class="ukuran_delapan"><?=$dataPD['AtasNama'] ?></td>
					</tr>
					<tr>
						<td width="20%"  class="ukuran_delapan" >Bank : </td>
						<td style="border-bottom: 1px solid;" class="ukuran_delapan"><?=$dataPD['Bank']?></td>
					</tr>
				</table>

			</td>

		</tr>
	</table>



</div>



<div class="col-xs-5">
	<table width="100%" border="0" style="padding-top:10px;">
		<tr>
			<th style="background-color:#95a5a6;font-weight: normal;text-align: center;" class="ukuran_delapan">Bukti Pendukung</th>
		</tr>
	</table>

	<table width="100%" border="0" class="ukuran_delapan">

		<tr>
			<td style="padding-top:5px;padding-bottom: 5px;">
				<label>
					<!-- <input type="checkbox" class="form-control ukuran_delapan" <?= cek_pj_saya($NoPJ,(CekBuktiPendukung($dataPD['BuktiPendukung'], 'INV') == TRUE) ? 'checked="checked"' : '') ?>  ><i class="fa fa-checked"></i> Invoice/Kwitansi -->
					<input type="checkbox" class="form-control ukuran_delapan" <?=(CekBuktiPendukung($dataPD['BuktiPendukung'], 'INV') == TRUE) ? 'checked="checked"' : '' ?>  ><i class="fa fa-checked"></i> Invoice/Kwitansi
				</label>
			</td>

			<td style="padding-top:5px;padding-bottom: 5px;">
				<label>
					<!-- <input type="checkbox" class="form-control ukuran_delapan"<?=cek_pj_saya($NoPJ, (CekBuktiPendukung($dataPD['BuktiPendukung'], 'FP') == TRUE) ? 'checked="checked"' : '') ?>  ><i class="fa fa-checked"></i> Faktur Pajak -->

					<input type="checkbox" class="form-control ukuran_delapan" <?=(CekBuktiPendukung($dataPD['BuktiPendukung'], 'FP') == TRUE) ? 'checked="checked"' : '' ?>  ><i class="fa fa-checked"></i> Faktur Pajak
				</label>
			</td>
		</tr>

		<tr>
			<td style="padding-top:5px;padding-bottom: 5px;">
				<label>
					<!-- <input type="checkbox" class="form-control ukuran_delapan" <?=cek_pj_saya($NoPJ, (CekBuktiPendukung($dataPD['BuktiPendukung'], 'SJ') == TRUE) ? 'checked="checked"' : '') ?>  ><i class="fa fa-checked"></i> Surat Jalan/Tanda Terima Lapangan -->

					<input type="checkbox" class="form-control ukuran_delapan" <?=(CekBuktiPendukung($dataPD['BuktiPendukung'], 'SJ') == TRUE) ? 'checked="checked"' : '' ?>  ><i class="fa fa-checked"></i> Surat Jalan/Tanda Terima Lapangan
				</label>
			</td>

			<td style="padding-top:5px;padding-bottom: 5px;">
				<label>
					<!-- <input type="checkbox" class="form-control ukuran_delapan" <?=cek_pj_saya($NoPJ, (CekBuktiPendukung($dataPD['BuktiPendukung'], 'BAP') == TRUE) ? 'checked="checked"' : '') ?>  ><i class="fa fa-checked"></i> Berita Acara Pembayaran -->

					<input type="checkbox" class="form-control ukuran_delapan" <?=(CekBuktiPendukung($dataPD['BuktiPendukung'], 'BAP') == TRUE) ? 'checked="checked"' : '' ?>  ><i class="fa fa-checked"></i> Berita Acara Pembayaran
				</label>
			</td>
		</tr>


		<tr>
			<td style="padding-top:5px;padding-bottom: 5px;">
				<label>
					<!-- <input type="checkbox" class="form-control ukuran_delapan" <?=cek_pj_saya($NoPJ, (CekBuktiPendukung($dataPD['BuktiPendukung'], 'PO') == TRUE) ? 'checked="checked"' : '') ?>  ><i class="fa fa-checked"></i> Copy PO -->

					<input type="checkbox" class="form-control ukuran_delapan" <?=(CekBuktiPendukung($dataPD['BuktiPendukung'], 'PO') == TRUE) ? 'checked="checked"' : '' ?>  ><i class="fa fa-checked"></i> Copy PO
				</label>
			</td>

			<td style="padding-top:5px;padding-bottom: 5px;">
				<label>
					<!-- <input type="checkbox" class="form-control ukuran_delapan" <?=cek_pj_saya($NoPJ, (CekBuktiPendukung($dataPD['BuktiPendukung'], 'BAOP') == TRUE) ? 'checked="checked"' : '') ?>  ><i class="fa fa-checked"></i> Berita Acara Opname Pekerjaan -->

					<input type="checkbox" class="form-control ukuran_delapan" <?=(CekBuktiPendukung($dataPD['BuktiPendukung'], 'BAOP') == TRUE) ? 'checked="checked"' : '' ?>  ><i class="fa fa-checked"></i> Berita Acara Opname Pekerjaan
				</label>
			</td>
		</tr>

	</table>


	<table width="100%" border="0">

		<tr>
			<th class="text-right" height="100px" style="vertical-align:bottom;" >
				

				<table border="0" width="100%" style="border-collapse: collapse;margin-bottom: 20px;font-size: 12px !important;" >
					<tr style="font-size: 12px !important;">
						<td width="50%" ></td>
						<td  class="text-center" style="padding-top:5px;padding-bottom:5px;font-size: 12px !important;font-weight: bold;border:1px solid;" ><?=$NoPD?></td>
					</tr>
				</table>

			</th>
		</tr>

	</table>

</div>


<!-- <div class="col-xs-5"> -->

	<!-- <table border="0" width="100%" style="border-collapse: collapse;" class="ukuran"  >
        <tr>
            <td class="text-center" style="background-color:#95a5a6;font-weight: bold;">Bayar Kepada</td>
        </tr>

    </table> -->

    <!-- </div> -->


