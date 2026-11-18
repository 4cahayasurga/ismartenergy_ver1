<?php
$MyLogo = 'LogoPT/logoMinarta.jpeg';

if (file_exists('assets/doc/' . $data_po['Logo_baru'])) {
	if ($data_po['Logo_baru'] != NULL || $data_po['Logo_baru'] != '') {
		$MyLogo = 'assets/doc/' . $data_po['Logo_baru'];
	}
}

?>

<div class="row" style="border:0px solid black;">

	<table width="100%" border="0">
		<tr>
			<th width="70%" class="atur" style="height:30px;max-height: 30px !important;vertical-align: top;" rowspan="2">
				<img src="<?= base_url($MyLogo) ?>" style="border:0px solid;width: 200px;max-width: 500px;height: 50px;max-height: 60px;" alt="">
			</th>
			<td class="atur" align="right" style="vertical-align: top;">
				<small>Printed On &nbsp;&nbsp;&nbsp; <?= date('d-M-Y') ?> &nbsp;&nbsp;&nbsp; <?= date('H:m') ?>&nbsp;&nbsp;&nbsp; Page {PAGENO} of {nbpg}
				</small>

			</td>
		</tr>
		<tr>
			<td class="atur" align="right" style="vertical-align: bottom;"><br><br><small style="font-size: 10px;"><b><u>Purchase Order</u></b></small> </td>
		</tr>
	</table>

	<table width="100%" border="0">
		<tr>
			<td class="atur" style="width: 50% !important;">

				<table width="100%" border="0">
					<tr>
						<th class="atur" style="background-color:#ecf0f1;">Penerima PO</th>
					</tr>
				</table>

				<table width="100%" border="0">
					<tr>
						<td class="atur" width="25%;">ID </td>
						<td width="2">:</td>
						<td class="atur" style="border-bottom:1px solid black"><?= strtoupper($data_po['VendorId']) ?></td>
					</tr>
					<tr>
						<td class="atur" width="25%;" style="padding-top:5px;">Nama </td>
						<td width="2">:</td>
						<td class="atur" style="border-bottom:1px solid black;padding-top:5px;"><?= strtoupper($data_po['VendorNm']) ?></td>
					</tr>
					<tr>
						<td class="atur" width="25%;" style="padding-top:5px;">Kategori </td>
						<td width="2">:</td>
						<td class="atur" style="border-bottom:1px solid black"><?= strtoupper($data_po['KategoriId']) ?></td>
					</tr>
					<tr>
						<td class="atur" width="25%;" style="padding-top:5px;">Kategori Vendor </td>
						<td width="2">:</td>
						<td class="atur" style="border-bottom:1px solid black"><?= strtoupper($data_po['KategoriVendor']) ?></td>
					</tr>
					<tr>
						<td class="atur" width="25%;" style="padding-top:5px;">Alamat </td>
						<td width="2">:</td>
						<td class="atur" style="border-bottom:1px solid black"><?= strtoupper($data_po['Alamat']) ?></td>
					</tr>
					<tr>
						<td class="atur" width="25%;" style="padding-top:5px;">Telepon </td>
						<td width="2">:</td>
						<td class="atur" style="border-bottom:1px solid black"><?= strtoupper($data_po['Telepon1']) ?></td>
					</tr>
					<tr>
						<td class="atur" width="25%;" style="padding-top:5px;">NPWP </td>
						<td width="2">:</td>
						<td class="atur" style="border-bottom:1px solid black"><?= strtoupper($data_po['NPWP']) ?></td>
					</tr>
					<tr>
						<td class="atur" width="25%;" style="padding-top:5px;">UP </td>
						<td width="2">:</td>
						<td class="atur" style="border-bottom:1px solid black"><?= strtoupper($data_po['ContactPerson']) ?></td>
					</tr>
				</table>

			</td>

			<td class="atur" style="width: 50% !important;vertical-align:top;">

				<table width="100%" border="0">
					<tr>
						<td class="atur" width="30%;" style="padding-top:5px;">No SPR </td>
						<td width="2">:</td>
						<td class="atur" style="border-bottom:1px solid black"><?= strtoupper($data_po['SprNama'])  ?></td>
					</tr>
					<tr>
						<td class="atur" width="30%;" style="padding-top:5px;">Tgl PO </td>
						<td width="2">:</td>
						<td class="atur" style="border-bottom:1px solid black"><?= date('d-M-Y', strtotime($data_po['TglKO']))   ?></td>
					</tr>
					<tr>
						<td class="atur" width="30%;" style="padding-top:5px;">No PO </td>
						<td width="2">:</td>
						<td class="atur" style="border-bottom:1px solid black"><?= strtoupper($data_po['NoKO'])  ?><?= $AddendumKe ?></td>
					</tr>
					<tr>
						<td class="atur" width="30%;" style="padding-top:5px;">Job No </td>
						<td width="2">:</td>
						<td class="atur" style="border-bottom:1px solid black"><?= strtoupper($data_po['JobNo']) . ' - ' . strtoupper($data_po['JobNm'])  ?></td>
					</tr>
					<tr>
						<td class="atur" width="30%;" style="padding-top:5px;">Sistem Pembayaran </td>
						<td width="2">:</td>
						<td class="atur" style="border-bottom:1px solid black"><?= strtoupper($data_po['SystemPembayaran']) ?></td>
					</tr>
				</table>

				<table width="100%" border="0" style="padding-top:5px;">
					<tr>
						<th class="atur" style="background-color:#ecf0f1;">Lokasi Pengiriman</th>
					</tr>
				</table>

				<table width="100%" border="0">
					<tr>
						<td class="atur" width="30%;" style="padding-top:5px;">Alamat : </td>
						<td width="2">:</td>
						<td class="atur" style="border-bottom:1px solid black"><?= strtoupper($data_po['AlamatKirim'])  ?></td>
					</tr>
					<tr>
						<td class="atur" width="30%;" style="padding-top:5px;">Nama : </td>
						<td width="2">:</td>
						<td class="atur" style="border-bottom:1px solid black"><?= strtoupper($data_po['NamaKirim'])  ?></td>
					</tr>
					<tr>
						<td class="atur" width="30%;" style="padding-top:5px;">Telepon : </td>
						<td width="2">:</td>
						<td class="atur" style="border-bottom:1px solid black"><?= strtoupper($data_po['TeleponKirim'])  ?></td>
					</tr>
					<tr>
						<td class="atur" width="30%;" style="padding-top:5px;"></td>
						<td width="2"></td>
						<td class="atur" style="text-align: right;">
							<label><input type="checkbox" class="form-control input-sm" checked="checked"> Material Approval&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
							<label><input type="checkbox" class="form-control input-sm" checked="checked">RAP</label>
						</td>
					</tr>
				</table>


			</td>
		</tr>
	</table>

	<table width="100%" border="0">
		<tr>
			<td style="background-color:#ecf0f1;text-align:center;font-size:10px;font-weight:bold;">ORDER LIST</td>
		</tr>
	</table>




</div>
