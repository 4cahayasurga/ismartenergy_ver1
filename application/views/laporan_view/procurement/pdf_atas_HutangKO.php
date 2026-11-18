
<table width="100%" border="0" style="border-collapse: collapse;" class="set_tulisan" >
	<tr>
		<td class="text-left"><u><h5>REKAP HUTANG KO</h5></u></td>
		<td class="text-right">
			<small><?= $gabung ?> Page {PAGENO} of {nbpg}</small>
		</td>
	</tr>
</table>

<table width="100%" border="0" style="border-collapse: collapse;page-break-inside: avoid !important" >

	<tr>
		<td style="font-size:10px;width: 50px;">Job</td>
		<td style="font-size:10px;width: 10px;text-align: center;">:</td>
		<td style="font-size:10px;"><?=$JobNm?></td>
	</tr>

	<tr>
		<td style="font-size:10px;width: 50px;">Periode</td>
		<td style="font-size:10px;width: 10px;text-align: center;">:</td>
		<td style="font-size:10px;"><?=tgl_baru_tahun_pendek($DariTgl).' s.d '.tgl_baru_tahun_pendek($SampaiTgl)?></td>
	</tr>

</table>