<style type="text/css">
	.tbl_example{
		font-size: 11px;
		font-family: sans-serif;
		margin-top: 10px;
		width: 70%;
		border-collapse: collapse;
		border:1px;
	}
	.tbl_example thead tr th{
		border-top: 1px solid ;
		border-bottom: 1px solid ;
		padding-top: 10px;
		padding-bottom: 10px;
		background-color: #34495e;
		color:white;
		border-left:1px solid;
		padding-left:5px;
		padding-right:5px;

	}

	.tbl_example tbody{
		border-bottom: 1px solid ;
	}
	.tbl_example tbody tr td{
		padding-top: 3px;
		padding-bottom: 3px;
		font-size: 10px;
	}
	.border_red{
		/*border-top:2px solid red;
		border-bottom:2px solid red;
		border-left:2px solid red;
		border-right:2px solid red;*/
		border:2px solid red;
	}

	.border_silver{
		border-top:2px solid silver;
		border-bottom:2px solid silver;
		border-left:2px solid silver;
		border-right:2px solid silver;
	}
</style>
<div class="table-responsive">

	<table border="0" style="border-collapse:collapse;" <?=($NoSJSebelumnya == '') ? 'hidden' : 'hidden' ?> >
		<tr>
			<td>
				No MOS Sebelumnya : <span style="text-decoration:underline;color:blue;"><?=$NoSJSebelumnya?></span>
				<input type="hidden" name="NoSJSebelumnya" value="<?=$NoSJSebelumnya?>" <?=($NoSJSebelumnya == '') ? 'disabled="disabled"' : '' ?>>
			</td>

		</tr>
	</table>

	<table  class="tbl_example" >
		<thead>
			<tr>
				<th class="text-center" width="3%">No</th>
				<th class="text-center" width="7%" >Kode RAP</th>
				<th class="text-center">Uraian</th>
				<th class="text-left">Satuan</th>
				<th class="text-right" width="100px">Vol KO</th>
				<th class="text-right" width="150px">Vol<br>Yang Sudah Diterima</th>
				<th class="text-right" width="100px">Vol Terima</th>
				<th style="display:;background-color: white;" class="kepalaTH" width="150px" ></th>
			</tr>
		</thead>
		<tbody>
			<?php 

			$DisabledData = '';
			foreach ($ArrayKO as $row ){ 

				if ($row->VolKO == $row->VolTerima) {
					$DisabledData = 'disabled="disabled"';
				}else{
					$DisabledData = '';
				}
			?>
				<tr>
					<td class="text-center"><?=$row->NoUrut?></td>
					<td><?=$row->KdRAP?></td>
					<td><?=$row->Uraian?></td>
					<td class="text-left"><?=$row->Uom?></td>
					<td class="text-right" style="padding-left:10px;">
						<input type="text" name="VolKO[]" value="<?=VolKetik($row->VolKO)?>"  class="form-control input-sm text-right KetikVol" <?=$DisabledData?>  readonly>
					</td>
					<td class="text-right" style="padding-left:10px;">
						<input type="text" name="VolYangSudahDiterima[]" value="<?=VolKetik($row->VolTerima)?>"  class="form-control input-sm text-right KetikVol" <?=$DisabledData?>  readonly>
					</td>
					<td class="text-right" style="padding-left:10px;">

						<input type="hidden" name="NoUrut[]" value="<?=$row->NoUrut?>" <?=$DisabledData?> >
						<input type="hidden" name="KdRAP[]" value="<?=$row->KdRAP?>" <?=$DisabledData?>>
						<input type="hidden" name="Alokasi[]" value="<?=$row->Alokasi?>" <?=$DisabledData?>>
						<input type="hidden" name="Uraian[]" value="<?=$row->Uraian?>" <?=$DisabledData?>>
						<input type="hidden" name="Uom[]" value="<?=$row->Uom?>" <?=$DisabledData?>>
						<input type="hidden" name="HrgSatuan[]" value="<?=$row->HrgSatuan?>" <?=$DisabledData?>>

						<input type="text" name="VolTerima[]" value="0" data-NoUrut="<?=$row->NoUrut?>" data-VolKOIni="<?=($row->VolKO - $row->VolTerima)?>" data-VolYangSudahDiterima="<?=$row->VolTerima?>"  class="form-control input-sm text-right KetikVol VolTerima" id="VolTerimaID<?=$row->NoUrut?>" autocomplete="off" <?=$DisabledData?>  >
					</td>
					<td style="display:;" class="text-red" style="font-size: 9px !important;">
						<span class="text-red" id="peringatan<?=$row->NoUrut?>" style="display: none;padding-left:7px;">*VOLUME MELEBIHI KO</span>
						<input type="text" id="peringatan_aktif<?=$row->NoUrut?>" value="0" name="peringatan_aktif[]" data-NoUrut="<?=$row->NoUrut?>"  style="display: none;" disabled>

					</td>
					
				</tr>
			<?php } ?>
		</tbody>
		
	</table>
</div>

<script type="text/javascript">

	function NoSJSebelumnyaAda(){
		var NoSJSebelumnya = $('input[name="NoSJSebelumnya"]').val();
		var NoSJOtomatis = $('#FrmSimpanMOS').attr('data-NoSJOtomatis');

		if (NoSJSebelumnya !='') {
			$('input[name="NoSJ"]').val(NoSJSebelumnya);
		}else{
			$('input[name="NoSJ"]').val(NoSJOtomatis);
		}
	}


	$(function(){

		var formatVol = function(num){
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
			return("" + formatted + ((parts) ? "." + parts[1].substr(0, 3) : ""));
		};

		$(".KetikVol").keyup(function(e){
			$(this).val(formatVol($(this).val()));

		});

		$('.VolTerima').focus(function(){
			if (this.value == 0) {
				$(this).val('');
			}
			
		});

	
		$('.VolTerima').focusout(function(){
			if (this.value == '') {
				$(this).val(0);
			}

			var NoUrut = $(this).attr('data-NoUrut');
			var NilaiIni = HapusKomaVol(this.value);
			var VolKOIni = $(this).attr('data-VolKOIni');

			if (NilaiIni > parseFloat(VolKOIni) ) {
				$(this).addClass('border_red');
				$('#peringatan'+NoUrut).show();
				$('#peringatan_aktif'+NoUrut).val(1);
			}else{
				$(this).removeClass('border_red');
				$('#peringatan'+NoUrut).hide();
				$('#peringatan_aktif'+NoUrut).val(0);
			}

			
		});

		function HapusKomaVol(nilai) {
			return (nilai.replace(/,/g, ''));
		}

		

	});
</script>