<table width="100%" border="0" style="border-collapse:collapse;" class="jarak10">
	<tr>
		<th style="text-decoration: underline;"><h4>SURAT PERMINTAAN MATERIAL/ALAT</h4></th>
		<td style="vertical-align: top;text-align: right;font-size: 10px;"><?= $gabung ?> Page {PAGENO} of {nbpg}</td>
	</tr>
	
</table>


<table width="100%" border="0" style="border-collapse:collapse;" class="huruf12 jarak">
	<tr>
		<td width="120px">No SPR :</td>
		<td width="300px" style="border-bottom:1px solid;"><?=$rowSPR->NoSPR?></td>
		<td style="width:50px;"></td>
		<td width="150px">Tanggal SPR :</td>
		<td width="300px" style="border-bottom:1px solid;"><?=tgl_baru($rowSPR->TglSPR)?></td>
		<td></td>
	</tr>
	
</table>

<table width="100%" border="0" style="border-collapse:collapse;" class="huruf12 jarak">
	<tr>
		<td width="120px">Kepada :</td>
		<td width="300px" style="border-bottom:1px solid;"><?=$rowSPR->Kepada?></td>
		<td style="width:50px;"></td>
		<td width="150px">Proyek / Sub-Div :</td>
		<td width="300px" style="border-bottom:1px solid;"><?=$rowSPR->JobNo.' - '.$rowSPR->JobNm?></td>
		<td></td>
	</tr>
</table>

<table width="100%" border="0" style="border-collapse:collapse;page-break-inside: avoid !important;" class="huruf12 jarak">
	
	<tr>
		<td width="120px">Untuk Pekerjaan :</td>
		<td width="300px" style="border-bottom:1px solid;"><?=$rowSPR->UtkPekerjaan?></td>
		<td></td>
	</tr>


</table>