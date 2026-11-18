<style type="text/css">
	#mytable{
		font-family: sans-serif;
		font-size: 11px;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
		
	}
	#mytable tr th{
		padding-top: 10px;
		padding-bottom: 10px;
		border-top:1px solid;
		border-bottom:1px solid;
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
				<th class="text-right" width="5%">Vol KO</th>
				<th class="text-right" width="10%">Vol Total Barang Masuk</th>
				<th class="text-center" width="22%">Keterangan</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($dataSJ as $row) : ?>
				<tr>
					<td class="text-center" ><?=$row['NoSJ']?></td>
					<td><?=$row['KdRAP']?></td>
					<td><?=$row['Uraian']?></td>
					<td><?=$row['Uom']?></td>
					<td><?=$row['VolKO']?></td>
					<td>
						<input type="text" name="JumlahBarang[]" class="text-right VolTotal" data-NoUrut="<?=$row['NoUrut']?>" data-VolKO="<?=$row['VolKO']?>" value="<?=$row['JumlahBarang']?>" <?=($row['matikan_data']) ? $row['matikan_data'] : ''  ?>>

						<input type="hidden" name="NoUrut[]" class="text-right"  value="<?=$row['NoUrut']?>">

						<span class="text-red" id="peringatan<?=$row['NoUrut']?>" style="display: none;padding-left:7px;font-size:12px;">*VOLUME MELEBIHI KO</span>
						<input type="text" id="peringatan_aktif<?=$row['NoUrut']?>" value="0" name="peringatan_aktif[]" data-NoUrut="<?=$row['NoUrut']?>"  style="display: none;" disabled>

					</td>
					<td>
						<textarea name="Keterangan[]" class="form-control input-sm" placeholder="Keterangan Lain" rows="2" style="width:100%;" <?=($row['matikan_data']) ? $row['matikan_data'] : ''  ?>><?=$row['Keterangan']?></textarea>
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




		$(".VolTotal").keyup(function(e){
			$(this).val(formatVol($(this).val()));

		});

		$('.VolTotal').focus(function(){
			if (this.value == 0) {
				$(this).val('');
			}

		});


		$('.VolTotal').blur(function(){
			if (this.value == '') {
				$(this).val(0);
			}

			var NoUrut = $(this).attr('data-NoUrut');
			var VolTotal = HapusKomaVol(this.value);
			var VolKO = $(this).attr('data-VolKO');

			console.log(NoUrut+' - '+VolKO+' - '+VolTotal);

			if (VolTotal > parseFloat(VolKO) ) {
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