<style type="text/css">
	#mytable{
		font-family: sans-serif;
		font-size: 11px;
		
	}
	#mytable tr th{
		padding-top: 10px;
		padding-bottom: 10px;
		border-top:1px solid;
		border-bottom:1px solid;
/*		background-color: #95a5a6;*/
/*		color : white;*/
	}
	#mytable tr td{
		padding-top: 5px;
		padding-bottom: 5px;
	}
	.border_red{
		border:2px solid red;
	}
</style>
<div class="table-responsive">
	<table class="table table-hover table-striped table-condensed" id="mytable">
		<thead>
			<tr class="bg-gray">
				<th class="text-center" width="3%">No</th>
				<th class="text-left" width="7%">Kode RAP</th>
				<th class="text-left">Uraian</th>
				<th class="text-center" width="3%">Sat</th>
				<th class="text-right" width="5%">Vol</th>
				<th class="text-right" width="10%">Vol Sudah Masuk</th>
				<th class="text-right" width="10%">Vol Masuk</th>
				<th class="text-center" width="22%">Keterangan</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($dataKO as $row) : ?>
				<tr>
					<td class="text-center" ><?=$row->NoUrut?></td>
					<td><?=$row->KdRAP?></td>
					<td><?=$row->Uraian?></td>
					<td class="text-center"><?=$row->Uom?></td>
					<td class="text-right"><?=VolKetik($row->Vol)?></td>
					<td class="text-right"><?=VolKetik($row->VolSudahMasuk)?></td>
					<td class="text-right">

						<input type="text" name="VolMasuk[]" class="form-control input-sm text-right VolMasuk KetikVol" id="VolMasukID<?=$row->NoUrut?>" data-NoUrut="<?=$row->NoUrut?>" data-VolDariKO="<?=($row->Vol - $row->VolSudahMasuk)?>" value="0" autocomplete="off" style="width: 100px;" <?=(($row->Vol - $row->VolSudahMasuk) == 0) ? 'readonly' : ''?>>

						<span class="text-red" id="peringatan<?=$row->NoUrut?>" style="display: none;padding-left:7px;font-size:8px;">*VOLUME MELEBIHI KO</span>
						<input type="text" id="peringatan_aktif<?=$row->NoUrut?>" value="0" name="peringatan_aktif[]" data-NoUrut="<?=$row->NoUrut?>"  style="display: none;" disabled>


						<input type="hidden" name="NoUrut[]" value="<?=$row->NoUrut?>">
						<input type="hidden" name="KdRAP[]" value="<?=$row->KdRAP?>">
						<input type="hidden" name="Alokasi[]" value="<?=$row->Alokasi?>">
						<input type="hidden" name="Uraian[]" value="<?=$row->Uraian?>">
						<input type="hidden" name="Uom[]" value="<?=$row->Uom?>">
						<input type="hidden" name="HrgSatuan[]" value="<?=$row->HrgSatuan?>">
						<input type="hidden" name="VolKO[]" value="<?=$row->Vol?>">


					</td>
					<td>
						<textarea name="Keterangan[]" class="form-control input-sm" placeholder="Keterangan Lain" rows="2" style="width:100%;"></textarea>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<script type="text/javascript">

	function HapusKomaVol(nilai) {
		return (nilai.replace(/,/g, ''));
	}

	$(function(){

		$('#mytable').DataTable({
			'paging' : false,
			"columnDefs": [
				{ "orderable": true, "targets": 0 },
				{ "orderable": false, "targets": 1 },
				{ "orderable": false, "targets": 2 },
				{ "orderable": false, "targets": 3 },
				{ "orderable": false, "targets": 4 },
				{ "orderable": false, "targets": 5 },
				{ "orderable": false, "targets": 6 },

			],

		});

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

		$('.VolMasuk').focus(function(){
			if (this.value == 0) {
				$(this).val('');
			}
			
		});


		$('.VolMasuk').focusout(function(){
			if (this.value == '') {
				$(this).val(0);
			}

			var NoUrut = $(this).attr('data-NoUrut');
			var VolMasuk = HapusKomaVol(this.value);
			var VolDariKO = $(this).attr('data-VolDariKO');

			if (VolMasuk > parseFloat(VolDariKO) ) {
				$(this).addClass('border_red');
				$('#peringatan'+NoUrut).show();
				$('#peringatan_aktif'+NoUrut).val(1);
			}else{
				$(this).removeClass('border_red');
				$('#peringatan'+NoUrut).hide();
				$('#peringatan_aktif'+NoUrut).val(0);
			}

			
		});
	});
</script>