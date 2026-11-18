<table width="100%" border="0" style="border-collapse:collapse;" class="huruf12 jarak">
	<tr>
		<th xtyle="text-decoration:underline;">Informasi Pengiriman</th>
	</tr>
</table>

<table width="100%" border="0" style="border-collapse:collapse;" class="huruf12 jarak">
	<tr>
		<td width="120px">Nama PIC Logistik</td>
		<td width="10px" style="text-align: center;">:</td>
		<td width="300px" style="border-bottom: 1px solid;"><?=$rowSPR->NamaKirim?></td>
		<td style="width:50px;"></td>
		<td width="120px">Telepon / HP</td>
		<td width="10px" style="text-align: center;">:</td>
		<td width="330px" style="border-bottom: 1px solid;"><?=$rowSPR->TeleponKirim?></td>
		<td></td>
	</tr>
</table>

<table width="100%" border="0" style="border-collapse:collapse;" class="huruf12 jarak">
	<tr>
		<td width="120px" style="vertical-align: top;">Waktu Pengiriman</td>
		<td width="10px" style="text-align: center;vertical-align: top;">:</td>
		<td width="300px" style="vertical-align: top;border-bottom: 1px solid;"><?=tgl_baru($rowSPR->TglKirim)?></td>
		<td style="width:50px;"></td>
		<td width="120px" style="vertical-align: top;">Alamat Dikirim</td>
		<td width="10px" style="text-align: center;vertical-align: top;">:</td>
		<td width="330px" style="vertical-align: top;border-bottom: 1px solid;"><?=$rowSPR->AlamatKirim?></td>
		<td></td>
	</tr>
</table>

<div class="col-xs-8 row" style="margin-top: 15px;">
	<table width="100%" border="0" style="border-collapse:collapse;" class="huruf12 jarak">
		<tr>
			<td style="text-align: center;" colspan="2">Diajukan oleh :</td>
			<td style="text-align: center;" colspan="2">Disetujui oleh :</td>
		</tr>
		<tr>
			<td style="text-align: left;height: 50px;vertical-align: top;padding-left:50px;">Tgl.</td>
			<td style="text-align: left;height: 50px;vertical-align: top;padding-left:50px;">Tgl.</td>
			<td style="text-align: left;height: 50px;vertical-align: top;padding-left:50px;">Tgl.</td>
			<td style="text-align: left;height: 50px;vertical-align: top;padding-left:50px;">Tgl.</td>
		</tr>

		<tr>
			<td style="text-align: left;vertical-align: top;padding-left:50px;">(...................)</td>
			<td style="text-align: left;vertical-align: top;padding-left:50px;">(...................)</td>
			<td style="text-align: left;vertical-align: top;padding-left:50px;">(...................)</td>
			<td style="text-align: left;vertical-align: top;padding-left:50px;">(...................)</td>
		</tr>

		<tr>
			<td style="text-align: left;vertical-align: top;padding-left:50px;">QSpv / yg meminta</td>
			<td style="text-align: left;vertical-align: top;padding-left:50px;">Kepala Pelaksana</td>
			<td style="text-align: left;vertical-align: top;padding-left:50px;">Kepala Commercial</td>
			<td style="text-align: left;vertical-align: top;padding-left:50px;">Kepala Proyek</td>
		</tr>

	</table>

	
</div>
<div class="col-xs-3"></div>

<!-- <table width="100%" border="1" style="border-collapse:collapse;" class="huruf12 jarak">
	<tr>
		<td style="text-align: center;width:30%;" colspan="2">Diajukan oleh :</td>
		<td style="text-align: center;width:30%;" colspan="2">Disetujui oleh :</td>
		<td rowspan="4">ss</td>
	</tr>
	<tr>
		<td>Tgl.</td>
		<td>Tgl.</td>
		<td>Tgl.</td>
		<td>Tgl.</td>
	</tr>
	<tr>
		<td height="50px"></td>
		<td height="50px"></td>
		<td height="50px"></td>
		<td height="50px"></td>
	</tr>
	
	<tr>
		<td width="200px">(.............)</td>
		<td >(.............)</td>
		<td>(.............)</td>
		<td>(.............)</td>
	</tr>
</table> -->