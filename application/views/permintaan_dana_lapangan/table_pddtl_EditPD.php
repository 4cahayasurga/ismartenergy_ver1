<style type="text/css">
	#table_PdDtl.dataTable tbody tr:hover {
		background-color: #bdc3c7;
		color: black;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		font-size: 18px !important;
		font-weight: bold;

	}

	#table_PdDtl.dataTable tbody tr:hover > .sorting_1 {
		background-color: #3498db
		color: black;
		/*font-size: 18px !important;*/
		/*font-weight: bold;*/
	}
	.atur_padding_table{
		padding-top: 10px !important;
		padding-bottom: 10px !important;
		vertical-align: middle;
	}
	.ukuran_pada_table{
		font-size: 10px !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}
	.ukuran_pada_table_kedua{
		font-size: 10px !important;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	}
</style>

<div class="table-responsive">

	<input type="hidden" id="JobNo" value="<?=$JobNo?>">
	<input type="hidden" id="TipeForm" value="<?=$TipeForm?>">
	<table class="table table-bordered table-striped table-hover table-condensed ukuran_pada_table" id="table_PdDtl" >
		<thead>
			<tr class="ukuran_select2 bg-gray ukuran_pada_table">
				<th class="atur_padding_table"  style="text-align:center !important;width: 2%;">
					<input type="checkbox" id="check-all" style="text-align:center;margin-left: 15px !important;">
				</th>
				<th class="text-center ukuran_pada_table atur_padding_table" width="3%">#</th>
				<th class="atur_padding_table ukuran_pada_table text-center" width="3%" >No.</th>
				<th style="text-align:left;width: 100px;" class="atur_padding_table ukuran_pada_table" >Kode RAP</th>
				<th style="text-align:left;" class="atur_padding_table ukuran_pada_table" >Uraian</th>
				<th style="text-align:right;" class="atur_padding_table" width="5%" >Vol</th>
				<th style="text-align:left;width: 5%;"  width="5%" class="atur_padding_table ukuran_pada_table" width="8%" >&nbsp;&nbsp;&nbsp;Sat</th>
				<th style="text-align:right;width: 10%;" class="atur_padding_table ukuran_pada_table" width="8%" >Harga Satuan (Rp)</th>
				<th style="text-align:right;width: 10%;" class="atur_padding_table ukuran_pada_table" width="8%" >Jumlah Harga (Rp)</th>
			</tr>
		</thead>
		<tbody>


			<?php 
			$no =1;
			$SubTotal=0;
			$NoUrut=0;


			foreach ($query_dapat_pddtl->result_array() as $data_pddtl) :
				$SubTotal += $data_pddtl['Total'];
				$NoUrut = $data_pddtl['NoUrut'];
				?>
				<tr class="ukuran_pada_table">
					<td style="text-align:center;" class="ukuran_pada_table"><input type="checkbox" class="check-item " data-NoUrut="<?=$data_pddtl['NoUrut']?>"  ></td>
					<td style="text-align:center;">
						<a href="javascript:void(0)" class="btn btn-sm btn-warning ukuran_pada_table" title="Edit Uraian" onclick="edit_uraian(<?=$NoUrut?>)"><i class="fa fa-edit"></i></a>
					</td>
					<td style="text-align:center;" class="ukuran_pada_table text-center" width="5%" ><?=$no++?></td>
					<td style="text-align:left;" class="ukuran_pada_table" ><?=$data_pddtl['KdRAP']?></td>
					<td class="ukuran_pada_table" ><?=$data_pddtl['Uraian']?></td>
					<td class="ukuran_pada_table" style="text-align:right;"><?=uang_koma_genap_decimal_kedua($data_pddtl['Vol'])?></td>
					<td class="ukuran_pada_table"><?=$data_pddtl['Uom']?></td>
					<td class="ukuran_pada_table" style="text-align:right;"><?=uang_koma_genap_decimal_kedua($data_pddtl['HrgSatuan'])?></td>
					<td class="ukuran_pada_table" style="text-align:right;"><?=uang_koma_genap_decimal_kedua($data_pddtl['Total'])?></td>
				</tr>

			<?php endforeach; ?>

		</tbody>

	</table>

	<br><br>

	<table width="100%" border="0">
		<tr>
			<th style="text-align:right;" class="ukuran_select2 ukuran_pada_table_kedua">Sub Total </th>
			<th style="text-align:right;width: 2%;"></th>
			<th style="text-align:right;width: 20%;">
				<input type="text"id="SubTotal_hanya_utk_view" value="<?=number_format($SubTotal, 2, '.', ',')?>" class="form-control input-sm ukuran_pada_table_kedua" style="text-align:right;" readonly   >

				<input type="hidden" id="SubTotal" value="<?=$SubTotal?>" class="form-control input-sm ukuran_pada_table_kedua" style="text-align:right;" readonly   >

			</th>
		</tr>
	</table>


	<table width="100%" border="0" style="margin-top:10px !important;" class="ukuran_pada_table_kedua" >
		<tr>
			<th style="text-align:right;" class="ukuran_pada_table_kedua">Saldo Sebelumnya dari No</th>
			<th style="text-align:right;width: 20%;padding-left:10px;padding-right:10px;" class="ukuran_pada_table_kedua">
				<select id="pilih_NoPJSebelumnya" name="NoPJSebelumnya" class="form-control input-sm ukuran_pada_table_kedua" >
					<option value="">-- Pilih --</option>
					<?php
					if ($NoPJSebelumnya !='' OR $NoPJSebelumnya != NULL) { ?>
						<option value="<?=$NoPJSebelumnya?>" selected><?=$NoPJSebelumnya?></option>
					<?php } ?>
					<?php
					foreach($dapat_NoPJSebelumnya as $data_NoPJSebelumnya) : ?>
						<option value="<?=$data_NoPJSebelumnya['NoPJ']?>" <?=($data_NoPJSebelumnya['NoPJ'] == $NoPJSebelumnya) ? 'selected' : ''   ?> ><?=$data_NoPJSebelumnya['NoPJ']?></option>
					<?php endforeach; ?>
				</select>


			</th>
			<th style="text-align:right;width: 20%;">
				<!-- <input type="text" id="Saldo" name="Saldo" value="<?=$dataUraian['Saldo']?>" class="form-control input-sm ukuran_pada_table_kedua" style="text-align:right;" readonly   > -->

				<input type="text" id="Saldo_view_saja" class="form-control input-sm ukuran_pada_table_kedua" style="text-align:right;" value="<?=number_format($dataUraian['Saldo'], 2, '.', ',')?>" disabled   >
				<input type="hidden" id="Saldo" name="Saldo" class="form-control input-sm ukuran_pada_table_kedua" style="text-align:right;" value="<?=$dataUraian['Saldo']?>" readonly   >

			</th>
		</tr>
	</table>

	<?php

	$TotalPermintaan = $SubTotal - $dataUraian['Saldo'];
	?>

	<table width="100%" border="0" style="margin-top:10px !important;" >
		<tr>
			<th style="text-align:right;" class="ukuran_pada_table_kedua">Total Permintaan </th>
			<th style="text-align:right;width: 2%;"></th>
			<th style="text-align:right;width: 20%;">
				<!-- <input type="text" id="TotalPermintaan" name="TotalPermintaan"  class="form-control" style="text-align:right;font-size: 14px !important;" value="<?=new_decimal($dataUraian['TotalPD'])?>" readonly> -->
				<input type="text" id="TotalPermintaan" name="TotalPermintaan"  class="form-control input-sm ukuran_pada_table_kedua" style="text-align:right;" value="<?=number_format($TotalPermintaan, 2, '.', ',')?>" readonly>

			</th>
		</tr>
	</table>

	<br><br>

</div>


<script>
	$(function(){

		$('#table_PdDtl').DataTable({
			pageLength: 50,
			lengthMenu: [50,100,200,300,500,1000],
			fixedColumns:   true,
			pagging : true,
			scrollY:        "300px",
			scrollX:        true,
			scrollCollapse: true,
		});

		// dapatkan_Saldo_NoPJSebelumnya();
            // Hitung_TotalPermintaan();
            $('#check-all').click(function(){
            	if($(this).is(":checked")){
            		$(".check-item").prop("checked", true);
            	}else{
            		$(".check-item").prop("checked", false);
            	}
            });


            $('#pilih_NoPJSebelumnya').change(function(){
            	dapatkan_Saldo_NoPJSebelumnya();
            });

        });

	function dapatkan_Saldo_NoPJSebelumnya(){
		var NoPJSebelumnya = $('#pilih_NoPJSebelumnya').val();
		var SubTotal = $('#SubTotal').val();
		var TotalPermintaan = $('#TotalPermintaan').val();

		var JobNo = $('#JobNo').val();
		var TipeForm = $('#TipeForm').val();


        // alert(NoPJSebelumnya+' - '+SubTotal+' - '+TotalPermintaan);

        jQuery.ajax({
        	url : '<?=site_url("Permintaan_dana_lapangan/dapatkan_Saldo_NoPJSebelumnya")?>',
        	type: 'POST',
        	dataType: 'json',
        	data: {NoPJSebelumnya: NoPJSebelumnya, SubTotal : SubTotal, TotalPermintaan : TotalPermintaan, JobNo : JobNo, TipeForm : TipeForm},
        	beforeSend : function(){
        		$('#loading_pj_sebelumnya').show();
        		$('#pilih_NoPJSebelumnya').hide();
        	},
        	complete: function(xhr, textStatus) {
        		$('#loading_pj_sebelumnya').hide();
        		$('#pilih_NoPJSebelumnya').show();
        	},
        	success: function(data, textStatus, xhr) {
        		$('#Saldo').val(data.Saldo);
        		$('#Saldo_view_saja').val(data.Saldo_view_saja);
        		$('#TotalPermintaan').val(data.TotalPermintaan);
        		$('#SubTotal').val(data.SubTotal);
        	},
        	error: function(xhr, textStatus, errorThrown) {
        		alert('error');
        	}
        });
        
    }

    function cek_saldo_NoPJSebelumnya(){
    	var NoPJSebelumnya = $('#pilih_NoPJSebelumnya').val();
    	var SubTotal = $('#SubTotal').val();

    	jQuery.ajax({
    		url : '<?=site_url("Permintaan_dana_lapangan/dapat_saldo_NoPJSebelumnya_terbaru")?>',
    		type: 'POST',
    		dataType: 'JSON',
    		data: {'NoPJSebelumnya' : NoPJSebelumnya,'SubTotal' : SubTotal},
    		cache : false,
    		success: function(data, textStatus, xhr) {
    			$('#Saldo').val(data.Saldo);
    			$('#TotalPermintaan').val(data.TotalPermintaan);
    			$('#SubTotal').val(data.SubTotal);
    		}
    	});
    }

    function cek_saldo_NoPJSebelumnya_lama(){
    	var NoPJSebelumnya = $('#pilih_NoPJSebelumnya').val();
    	jQuery.post('<?=site_url("Permintaan_dana_lapangan/dapat_saldo_NoPJSebelumnya")?>', {'NoPJSebelumnya' : NoPJSebelumnya}, function(data, textStatus, xhr) {
    		$('#Saldo').val(data);
    		Hitung_TotalPermintaan();
    	});
    }

    function Hitung_TotalPermintaan(){
    	var SubTotal = $('#SubTotal').val();
    	var Saldo = $('#Saldo').val();
    	var Hitung = 0;


    	SubTotal = HapusKoma(SubTotal);
    	Saldo = HapusKoma(Saldo);

    	var Hitung = parseFloat(SubTotal) - parseFloat(Saldo);
    	Hitung = addCommas(Hitung.toFixed(2));
    	$('#TotalPermintaan').val(Hitung);

    }




</script>

