<table class="tbl_inv" border="0">
	<tr>
		<td class="judul_atas"><?=$judul_atas?></td>
		<td class="text-right"><?= $gabung ?> Page {PAGENO} of {nbpg}</td>
	</tr>
</table>
<br>


<table class="tbl_inv" border="0">
	<tr>
		<td width="5%">Tgl Entry</td>
		<td width="1%" class="text-center" >:</td>
		<td width="15%" style="text-decoration: underline;"><?=TanggalSaya($dari_tgl).' s.d '.TanggalSaya($sampai_tgl)?></td>
		<td></td>
	</tr>
</table>

<table class="tbl_inv" border="0">
	<tr>
		<td width="5%">Job No</td>
		<td width="1%" class="text-center" >:</td>
		<td width="15%" style="text-decoration: underline;" ><?=($JobNo=='AllJob') ? 'All' : $JobNo.' - '.$JobNm ?></td>
		<td></td>
	</tr>
</table>

<table class="tbl_inv" border="0">
	<tr>
		<td width="5%">Vendor</td>
		<td width="1%" class="text-center" >:</td>
		<td width="15%" style="text-decoration: underline;" ><?=($VendorId=='AllVendor') ? 'All' : $VendorId ?></td>
		<td></td>
	</tr>
</table>

<table class="tbl_inv" style="page-break-inside: avoid !important;" border="0">
	<tr>
		<td width="5%">Sort</td>
		<td width="1%" class="text-center" >:</td>
		<td width="15%" style="text-decoration: underline;" ><?=($SortBy=='ByVendor') ? 'By Vendor' : 'By Project' ?></td>
		<td></td>
	</tr>
</table>

<!-- 
<table width="100%" border="0" style="border-collapse: collapse;" class="set_tulisan ukuran" >
	<tr>
		<td class="text-left"><u><h5><?=$judul?></h5></u></td>
		<td class="text-right">
			<small class="ukuran"><?= $gabung ?> Page {PAGENO} of {nbpg}</small>
		</td>
	</tr>
</table>

<table width="100%" border="0" style="border-collapse: collapse;" >
	<tr>
		<td style="font-size:10px;width: 50px;padding-top:5px;padding-bottom:5px;">Type</td>
		<td style="font-size:10px;width: 10px;text-align: center;">:</td>
		<td style="font-size:10px;">Already Paid</td>
	</tr>
</table>

<table width="100%" border="0" style="border-collapse: collapse;page-break-inside: avoid !important" >

	<tr>
		<td style="font-size:10px;width: 50px;padding-bottom:5px;">Periode</td>
		<td style="font-size:10px;width: 10px;text-align: center;">:</td>
		<td style="font-size:10px;"><?=tgl_baru($dariTgl).' s.d '.tgl_baru($sampaiTgl)?></td>

		<td></td>
	</tr>
	
</table>

 -->