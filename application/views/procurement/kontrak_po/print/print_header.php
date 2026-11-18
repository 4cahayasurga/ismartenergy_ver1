<div class="row">

	<table width="100%" border="0" style="text-align: right;">
		<tr>
			<td>Printed On</td>
			<td style="width:80px;"><?= date('d-M-Y') ?></td>
			<td style="width:50px;"><?= date('H:m') ?></td>
			<td style="width:80px;">Page {PAGENO} of {nbpg}</td>
		</tr>

	</table>

	<table width="100%" border="0" style="text-align: right;">
		<tr>
			<td>
				<h5><u>Summary Kontrak</u></h5>
			</td>
		</tr>
	</table>

</div>

<div class="row">

	<div class="col-xs-6 ">

		<table width="100%" border="0">
			<tr>
				<td style="background-color:#bdc3c7;padding-top:2px;padding-bottom:2px;"><b>Penerima Kontrak</b></td>
			</tr>
		</table>

		<table width="100%" border="0">

			<tr>
				<td style="padding-top:2px;padding-bottom:2px;" width="90px">ID</td>
				<td width="2">:</td>
				<td style="border-bottom:1px solid black;"><?= $data_ko['VendorId'] ?></td>
			</tr>

			<tr>
				<td style="padding-top:2px;padding-bottom:2px;">Nama </td>
				<td width="2">:</td>
				<td style="border-bottom:1px solid black;"><?= $data_ko['VendorNm'] ?></td>
			</tr>

			<tr>
				<td style="padding-top:2px;padding-bottom:2px;">Kategori </td>
				<td width="2">:</td>
				<td style="border-bottom:1px solid black;"><?= $data_ko['Kategori'] ?></td>
			</tr>

			<tr>
				<td style="padding-top:2px;padding-bottom:2px;">Sub Kategori </td>
				<td width="2">:</td>
				<td style="border-bottom:1px solid black;"><?= $data_ko['BidangUsaha'] ?></td>
			</tr>

			<tr>
				<td style="padding-top:2px;padding-bottom:2px;">Alamat </td>
				<td width="2">:</td>
				<td style="border-bottom:1px solid black;line-height: 1.5;"><?= $data_ko['Alamat'] ?></td>
			</tr>

			<tr>
				<td style="padding-top:2px;padding-bottom:2px;">Telepon </td>
				<td width="2">:</td>
				<td style="border-bottom:1px solid black;"><?= $data_ko['Telepon1'] ?></td>
			</tr>

			<tr>
				<td style="padding-top:2px;padding-bottom:2px;">NPWP </td>
				<td width="2">:</td>
				<td style="border-bottom:1px solid black;"><?= $data_ko['NPWP'] ?></td>
			</tr>

		</table>
	</div>

	<div class="col-xs-5 row ">
		<table width="100%" border="0">

			<tr>
				<td style="padding-top:2px;padding-bottom:2px;" width="90px">Tgl Kontrak</td>
				<td width="2">:</td>
				<td style="border-bottom:1px solid black;"><?= date('d-M-Y', strtotime($data_ko['TglKO'])) ?></td>
			</tr>

			<tr>
				<td style="padding-top:2px;padding-bottom:2px;">No Kontrak </td>
				<td width="2">:</td>
				<td style="border-bottom:1px solid black;"><?= $data_ko['NoKO'] ?><?= $AddendumKe ?></td>
			</tr>

			<tr>
				<td style="padding-top:2px;padding-bottom:2px;">Job No </td>
				<td width="2">:</td>
				<td style="border-bottom:1px solid black;"><?= $data_ko['JobNo'] . ' - ' . $data_ko['JobNm'] ?></td>
			</tr>

			<tr>
				<td style="padding-top:2px;padding-bottom:2px;">Kategori Vendor </td>
				<td width="2">:</td>
				<td style="border-bottom:1px solid black;"><?= $data_ko['KategoriVendor'] ?></td>
			</tr>

			<tr>
				<td style="padding-top:2px;padding-bottom:2px;">Sistem Pembayaran </td>
				<td width="2">:</td>
				<td style="border-bottom:1px solid black;"><?= $data_ko['SystemPembayaran'] ?></td>
			</tr>


		</table>
	</div>

</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box" style="background-color: #bdc3c7;text-align:center;">
			<small style="font-size:10px;"><b>RINCIAN PEKERJAAN</b></small>
		</div>
	</div>
</div>
