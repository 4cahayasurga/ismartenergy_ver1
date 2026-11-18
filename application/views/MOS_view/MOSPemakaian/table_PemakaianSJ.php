<style type="text/css">
	#tblSJ{
		font-family: sans-serif;
		font-size: 11px;
	}

	#tblSJ thead tr th{
		padding-top:7px !important;
		padding-bottom:7px !important;
		background-color: #bdc3c7;
		vertical-align: top;
	}

	#tblSJ tbody tr td{
		padding-top:7px !important;
		padding-bottom:7px !important;
		vertical-align: top;
		border-bottom:1px solid silver;
	}

</style>
<div class="row">

	<div class="col-sm-12" style="padding-top: 15px;">
		<div class="table-responsive">
			<table class="table table-hover table-condensed" id="tblSJ"  border="0" >
				<thead>
					<tr>
						<th class="text-center" width="30px" >No.</th>
						<th width="80px" >Kode RAP</th>
						<th wid class="text-center">Uraian</th>
						<th class="text-right" width="130px">Volume KO / PO</th>
						<th class="text-right" width="150px">Volume <br>Yang Sudah Diterima</th>
						<th class="text-right" width="130px">Volume Terpakai</th>
						<th class="text-right">Input Pemakaian</th>
						<th class="text-center"></th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$SisaStok = 0;

					foreach ($MyData as $row): 

						$SisaStok = $row->VolTerima - $row->VolTerpakai;
						?>
						<tr>
							<td class="text-center"><?=$row->LedgerNo?></td>
							<td><?=$row->KdRAP?></td>
							<td><?=$row->Uraian?></td>
							<td class="text-right"><?=VolKetik($row->VolKO)?></td>
							<td class="text-right"><?=VolKetik($row->VolTerima)?></td>
							<td class="text-right"><?=VolKetik($row->VolTerpakai)?></td>
							<td class="text-right" width="160px">

								<input type="text" name="tambahPemakaian[]" class="text-right form-control input-sm KetikVol VolTerpakaiInput" id="VolTerpakaiInputID<?=$row->NoUrut?>"
								data-VolTerimaCek="<?=$row->VolTerima?>" data-SisaStok="<?=$SisaStok?>" data-NoUrut="<?=$row->NoUrut?>" value="0"  size="9"><br>

								<span style="font-size:8px !important;display:none;" class="text-red text-bold" id="peringatan<?=$row->NoUrut?>" >*VOLUME LEBIH BESAR DARI STOK</span>
							</td>
							<td class="text-center">

								<input type="hidden" name="NoUrut[]" id="NoUrutID<?=$row->NoUrut?>" value="<?=$row->NoUrut?>">
								<input type="hidden" name="NoSJ[]" value="<?=$row->NoSJ?>" id="NoSJID<?=$row->NoUrut?>">
								<input type="hidden" name="VolTerpakai[]" value="<?=VolKetik($row->VolTerpakai)?>" id="VolTerpakaiID<?=$row->NoUrut?>">

								<button class="btn btn-sm btn-primary btn_update" id="BtnUpdateID<?=$row->NoUrut?>"	
									data-NoUrut="<?=$row->NoUrut?>" 
									data-NoSJ="<?=$row->NoSJ?>"
									data-VolTerpakai="<?=VolKetik($row->VolTerpakai)?>"
									>UPDATE</button>
								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>


	<script src="<?=base_url('assets/swal2_terbaru.js')?>"></script>

	<script type="text/javascript">
		$(function(){


			$('.btn_update').click(function(){
				var NoUrut = $(this).attr('data-NoUrut');
				var NoSJ = $(this).attr('data-NoSJ');
				var VolTerpakaiInputID = HapusKomaVol($('#VolTerpakaiInputID'+NoUrut).val());
				var VolTerpakai = $(this).attr('data-VolTerpakai');

				if (VolTerpakaiInputID == 0) {
					Swal.fire(
						'Alert!',
						'input tidak boleh Nol (0)',
						'error'
						)
					return false;
				}

				VolTerpakaiInput = $('#VolTerpakaiInputID'+NoUrut).val();

				$.ajax({
					url: '<?=site_url("MOS/FrmUsageMOS/SavePemakaian")?>',
					type: 'post',
					dataType : 'html',
					data: {
						NoUrut : NoUrut,
						VolTerpakaiInput : VolTerpakaiInput,
						NoSJ : NoSJ,
						VolTerpakai : VolTerpakai

					},
					beforeSend : function(){
						$(this).attr('disabled',true).text('Loading...');
					},
					complete : function(){
						$(this).attr('disabled',false).text('UPDATE');
					},
					success: function (data) {
						alert(data);
					},error : function(){
						$.toast({
							text: 'error',
							position: 'top-right',
							icon : 'error'
						})
					}
				});



			});


			$('#tblSJ').DataTable({
				"paging"	: false,
			// "scrollY"	: "700px",
			// "scrollX"	: false,

				"columnDefs":
				[ 
					{ "targets": 1, "orderable": true, "searchable": true },
					{ "targets": 2, "orderable": false, "searchable": true },
					{ "targets": 3, "orderable": false, "searchable": false },
					{ "targets": 4, "orderable": false, "searchable": false},
					{ "targets": 5, "orderable": false, "searchable": false },
					{ "targets": 6, "orderable": false , "searchable": false},
					{ "targets": 7, "orderable": false, "searchable": false },
					]
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

			$('.VolTerpakaiInput').focus(function(){
				if (this.value == 0) {
					$(this).val('');
				}

			});


			$('.VolTerpakaiInput').focusout(function(){
				if (this.value == '') {
					$(this).val(0);
				}

				var NoUrut = $(this).attr('data-NoUrut');
				var VolTerpakaiInput = HapusKomaVol(this.value);
				var VolTerimaCek = $(this).attr('data-VolTerimaCek');
				var SisaStok = $(this).attr('data-SisaStok');

				if (VolTerpakaiInput > parseFloat(SisaStok)) {
				// console.log('lebih besar');
					$('#peringatan'+NoUrut).show();
					$('#BtnUpdateID'+NoUrut).attr('disabled',true);
				}else{
				// console.log('tidak');
					$('#peringatan'+NoUrut).hide();
					$('#BtnUpdateID'+NoUrut).attr('disabled',false);
				}

			});

			function HapusKomaVol(nilai) {
				return (nilai.replace(/,/g, ''));
			}

		});
	</script>
