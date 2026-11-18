<style>
	.font12{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size : 12px;
	}
	.font14{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size : 14px;
	}
	.garisMerah{
		border-color:red;
	}

</style>
<div class="row">


	<div class="col-sm-12">
		<div class="table-responsive">
			<table width="100%">
				<thead>
					<tr>
						<th class="bg-gray" height="30px">PENERIMAAN PO</th>
					</tr>
				</thead>
			</table>
			
			
			<table class="table table-borderless table-striped table-hover table-condensed" width="100%" border="0"  style="margin-top:5px;" id="tblsaya">
				<thead>
					<tr>
						<th style="border-bottom:2px solid silver;border-top:2px solid silver;padding-top:10px;padding-bottom:10px;">No.</th>
						<th style="border-bottom:2px solid silver;border-top:2px solid silver;padding-top:10px;padding-bottom:10px;">Kode RAP</th>
						<th style="border-bottom:2px solid silver;border-top:2px solid silver;padding-top:10px;padding-bottom:10px;">Uraian</th>
						<th style="border-bottom:2px solid silver;border-top:2px solid silver;padding-top:10px;padding-bottom:10px;">Satuan</th>
						<th width="100px" class="text-right" style="border-bottom:2px solid silver;border-top:2px solid silver;padding-top:10px;padding-bottom:10px;">Volume PO</th>
						<th width="120px" class="text-right" style="border-bottom:2px solid silver;border-top:2px solid silver;padding-top:10px;padding-bottom:10px;">Volume Terima</th>
						<th width="100px" class="text-right" style="border-bottom:2px solid silver;border-top:2px solid silver;padding-top:10px;padding-bottom:10px;">Outstanding</th>
						<th class="text-center" style="border-bottom:2px solid silver;border-top:2px solid silver;padding-top:10px;padding-bottom:10px;">
						</th>
					</tr>
				</thead>

				<tbody style="border-bottom:1px solid silver;">
					<?php 
					$Vol_Yang_Sudah_Diterima = 0;
					foreach($dataKO as $mydata) : 

						// $queryCekVolSudahDiterima = $this->db->query("SELECT *")

						// $Vol_Yang_Sudah_Diterima = 

					?>



						<tr class="trid<?=$mydata->NoUrut?>"  data-NoUrut="<?=$mydata->NoUrut?>" data-VolPO="<?=VolKetik($mydata->Vol)?>" >
							<td><?=$mydata->NoUrut?></td>
							<td><?=$mydata->KdRAP?></td>
							<td><?=$mydata->Uraian?></td>
							<td><?=$mydata->Uom?></td>
							<td  class="text-right">
								<input type="text" name="VolPO[]" id="VolPOID<?=$mydata->NoUrut?>" value="<?=VolKetik($mydata->Vol)?>" class="form-control input-sm text-right" readonly  >
							</td>
							<td class="mytd" data-NoUrut="<?=$mydata->NoUrut?>" class="text-right">

								<input type="hidden" name="VolPOTest[]" value="<?=VolKetik($mydata->Vol)?>" disabled  >

								<input type="text" name="VolTerima[]" value="0" class="form-control input-sm KetikVol VolTerimaClass text-right" id="VolTerimaID<?=$mydata->NoUrut?>"  data-VolPO="<?=$mydata->Vol?>" data-NolRapih="0" autocomplete="off"  >

								<input type="hidden" name="NoUrut[]" value="<?=$mydata->NoUrut?>" class="form-control input-sm"  >
								<input type="hidden" name="KdRAP[]" value="<?=$mydata->KdRAP?>" class="form-control input-sm"  >
								<input type="hidden" name="Alokasi[]" value="<?=$mydata->Alokasi?>" class="form-control input-sm"  >
								<input type="hidden" name="Uraian[]" value="<?=$mydata->Uraian?>" class="form-control input-sm"  >
								<input type="hidden" name="Uom[]" value="<?=$mydata->Uom?>" class="form-control input-sm"  >
								<input type="hidden" name="HrgSatuan[]" value="<?=$mydata->HrgSatuan?>" class="form-control input-sm"  >


							</td>
							<td class="text-right">
								<input type="text" class="form-control input-sm text-right OTClass<?=$mydata->NoUrut?> " value="0"  disabled>
							</td>
							<td>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>


			</table>

		</div>
	</div>

	<script type="text/javascript">
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

			$('.VolTerimaClass').focus(function(){
				if ($(this).val() == 0) {
					$(this).val('');
				}

			});

			$('.VolTerimaClass').focusout(function(){
				var NolRapih = $(this).attr('data-NolRapih');
				if ($(this).val() == '') {
					$(this).val(NolRapih);
				}

			});

			// $( ".VolTerimaClass" ).on( "keypress", function( event ) {
			// 	var ID = $(this).attr('data-NoUrut');


			// 	$('#VolPOID'+ID).val(HapusKoma(this.value));
			// 	console.log(this.value);
			// 	// console.log(ID);
			// 	// $.print( msg, "html" );
			// 	// $.print( event );
			// } );


		});

		function HapusKoma(nilai) {
			return (nilai.replace(/,/g, ''));
		}


	</script>
