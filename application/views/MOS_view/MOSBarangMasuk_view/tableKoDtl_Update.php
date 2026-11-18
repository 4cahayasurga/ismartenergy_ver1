<style>
	.font12{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size : 12px;
	}
	.font14{
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size : 14px;
	}

</style>
<div class="row">
	<div class="col-sm-12">
		<div class="table-responsive">
			<table width="100%" border="0" class="table table-bordered table-striped table-hover font12" id="TblKO">
				<thead>
					<tr>
						<th class="text-center" width="150px"></th>
						<th class="text-center">No.</th>
						<th class="text-center">Kode RAP</th>
						<th class="text-left" >Uraian</th>
						<th class="text-right">Volume</th>
						<th class="text-left">Satuan</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($dataKO as $row): ?>
						<tr id="coba<?=$row->NoUrut?>">
							<td class="text-center" width="150px">

								<button type="button" class="btn btn-link btn-sm font14 btn_pilih" data-VolAwal="<?=UangTerbaru($row->Vol)?>" data-NoUrut="<?=$row->NoUrut?>" id="pilih<?=$row->NoUrut?>" ><u>Select</u></button>

								<button type="button" class="btn btn-link btn-sm font14 btn_edit" data-VolAwal="<?=UangTerbaru($row->Vol)?>" data-NoUrut="<?=$row->NoUrut?>" id="edit<?=$row->NoUrut?>" style="display: none;" <?=$nonaktif?> ><u>Update</u></button>

								<button type="button" class="btn btn-link btn-sm font14 btn_cancel" id="cancel<?=$row->NoUrut?>" data-NoUrut="<?=$row->NoUrut?>" data-VolAwal="<?=UangTerbaru($row->Vol)?>" style="display:none;" ><u>Cancel</u></button>

							</td>
							<td class="text-center"><?=$row->NoUrut?></td>
							<td class="text-center"><?=$row->KdRAP?></td>
							<td class="text-left" ><?=$row->Uraian?></td>
							<td class="text-right">
								<p class="VolTxt<?=$row->NoUrut?>" id="VolTxt<?=$row->NoUrut?>"><?=UangTerbaru($row->Vol)?></p>
								
								<!-- <input type="hidden" name="NoKO[]" value="<?=$row->NoKO?>"> -->
								<input type="hidden" name="NoUrut[]" value="<?=$row->NoUrut?>" class="form-control" readonly >
								<input type="hidden" name="Alokasi[]" value="<?=$row->Alokasi?>" class="form-control" readonly>
								<input type="hidden" name="KdRAP[]" value="<?=$row->KdRAP?>" class="form-control" readonly>
								<input type="hidden" name="Uraian[]" value="<?=$row->Uraian?>" class="form-control" readonly>
								<input type="hidden" name="Uom[]" value="<?=$row->Uom?>" class="form-control" readonly>
								<input type="hidden" name="HrgSatuan[]" value="<?=$row->HrgSatuan?>" class="form-control" readonly>
								<input type="hidden" name="JobNo_Dtl[]" value="<?=$JobNo?>" class="form-control" readonly>



								<input type="text" name="Vol[]"  class="form-control input-sm font12 ketik_uang VolUbahClass" id="VolUbahID<?=$row->NoUrut?>" value="<?=UangTerbaru($row->Vol)?>" style="display: none;" readonly <?=$nonaktif?>>

								<input type="text" class="form-control input-sm font12 ketik_uang VolInputanClass" id="VolInputanID<?=$row->NoUrut?>" value="<?=UangTerbaru($row->Vol)?>" style="display: none;" <?=$nonaktif?>>

								
								<input type="text" name="VolAsli" class="form-control input-sm font12 VolAsliClass" id="VolAsliID<?=$row->NoUrut?>" value="<?=UangTerbaru($row->VolAsli)?>" style="display: none;" readonly <?=$nonaktif?>>

								<!-- <input type="text" name="Vol[]" id="VolInput<?=$row->NoUrut?>" class="form-control input-sm font12 ketik_uang"  value="<?=UangTerbaru(0)?>" style="display:none;">

									<input type="text" id="VolAsli<?=$row->NoUrut?>" value="<?=UangTerbaru($row->Vol)?>" style="display:none;" > -->
								</td>
								<td class="text-left"><?=$row->Uom?></td>
							</tr>
						<?php endforeach ?>
						
					</tbody>	
				</table>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		$(function(){

			$('#TblKO').DataTable();

			var format = function(num){
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
				return("" + formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
			};

			$(".ketik_uang").keyup(function(e){
				$(this).val(format($(this).val()));
			});


			$('.btn_pilih').click(function(){
				NoUrut = $(this).attr('data-NoUrut');
				// console.log(NoUrut);

			// $('#VolUbahID'+NoUrut).show();
			$('#VolInputanID'+NoUrut).show();
			$('#VolTxt'+NoUrut).hide();
			// $('#VolAsliID'+NoUrut).show();

			$('#edit'+NoUrut).show();
			$('#cancel'+NoUrut).show();
			$(this).hide();


		});

			$('.btn_cancel').click(function(){
				NoUrut = $(this).attr('data-NoUrut');
			// alert(NoUrut);

			// $('#VolUbahID'+NoUrut).val(0);
			// $('#VolInputanID'+NoUrut).val(0);


			$('#VolUbahID'+NoUrut).hide();
			$('#VolInputanID'+NoUrut).hide();
			$('#VolTxt'+NoUrut).show();
			$('#VolAsliID'+NoUrut).hide();

			$('#edit'+NoUrut).hide();
			$('#pilih'+NoUrut).show();
			$(this).hide();


		});

			$('.btn_edit').click(function(){
				NoUrut = $(this).attr('data-NoUrut');
				VolAsliID = $('#VolAsliID'+NoUrut).val();


				VolInputan = $('#VolInputanID'+NoUrut).val();

				if (HapusKoma(VolInputan) > HapusKoma(VolAsliID)) {
					alert('Volume yg diinput melebihi volume PO.');
					return false;
				}
			// return false;

			$('#VolUbahID'+NoUrut).val(VolInputan);
			$('#VolTxt'+NoUrut).text(VolInputan);

			$('#VolUbahID'+NoUrut).hide();
			$('#VolInputanID'+NoUrut).hide();
			$('#VolTxt'+NoUrut).show();
			$('#VolAsliID'+NoUrut).hide();
			
			$('#cancel'+NoUrut).hide();
			$('#pilih'+NoUrut).show();
			$(this).hide();


		});



		});

		function HapusKoma(nilai) {
			return (nilai.replace(/,/g, ''));
		}


	</script>
