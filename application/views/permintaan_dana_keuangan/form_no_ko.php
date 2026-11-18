<?php
$query_tambahan = "";
$PDDari = 'KEUANGAN';

if ($PDDari == "KEUANGAN" && $TipeForm == '04B') {
	$query_tambahan = "AND KategoriId='KONTRAK' ";
}else if($PDDari == "KEUANGAN" && $TipeForm == '05B'){
	$query_tambahan = "AND KategoriId='PO' ";
}


$query_teks = "SELECT NoKO,VendorId FROM KoHdr WHERE JobNo='$JobNo' AND ApprovedBy IS NOT NULL AND CanceledBy IS NULL AND ClosedBy IS NULL ".$query_tambahan."  ";
$data =  $this->db->query($query_teks)->result();
?>


<select name="NoKO" id="NoKO" class="form-control form-control-sm select2 NoKO_baru" onchange="dapat_vendor()"  required>
	<option value="<?=$internal?>"  data-vendor="" data-JobNo="<?=$JobNo?>" data-Alokasi="<?=$Alokasi?>" selected><?=$internal?></option>

	<?php foreach ($data as $mydata) : ?>

		<option value="<?=$mydata->NoKO?>" data-vendor="<?=$mydata->VendorId?>" data-JobNo="<?=$JobNo?>" data-Alokasi="<?=$Alokasi?>" ><?=$mydata->NoKO?></option>

	<?php endforeach; ?>
</select>

<script type="text/javascript">
	$('#NoKO').select2({
		'placeholder' : '-- Pilih No. KO --',
		'width': '100%',
		'dropdownCssClass': "ukuran_select2"
	});
</script>
