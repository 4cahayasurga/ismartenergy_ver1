<!-- <p></p> -->
<table width="100%" border="0" class="font12" style="margin-top:5px !important;">
	<tr>
		<td width="5%">No SJ.</td>
		<td class="text-center" width="1%">:</td>
		<td width="30%">
			<select name="NoSJ" id="NoSJ" class="form-control input-sm font12 select2">
				<option value="">-- Pilih --</option>
				<?php foreach ($mydata as $row): ?>
					<option value="<?=$row->NoSJ?>" ><?=$row->NoSJ?></option>
				<?php endforeach ?>
			</select>
		</td>
		<td></td>
	</tr>
</table>
<!-- <script type="text/javascript">
	$(function(){
		$('#NoSJ').select2({ dropdownCssClass: "font12" });
	});
</script> -->