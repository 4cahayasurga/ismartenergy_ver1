


<div class="box" style="border:0px;">

	<div class="box-body" id="box_body_addendum" style="border:0px;">

		<div class="row">

			<div class="col-sm-6">

				<table width="100%" border="0">
					<tr>
						<td width="20%">Addendum Ke : </td>
						<?php
						$AddendumLama = $row_ko['AddendumKe'];

						$AddendumBaru = $AddendumLama + 1;
						// if($data_ko->num_rows() == 0 ){
						// 	$AddendumBaru=0;
						// }


						?>
						<td>
							<input type="text" name="AddendumKe" id="AddendumBaru" class="form-control input-sm add_ke" placeholder="Isi Disini" value="<?=$AddendumBaru?>"  required readonly >

							<input type="hidden" id="AddendumLama" value="<?=$AddendumLama?>"  readonly >


						</td>

					</tr>
				</table>

			</div>

			<div class="col-sm-6">
				<table width="100%" border="0">
					<tr>
						<td width="21%" style="padding-right:5px;">Alasan Addendum : </td>
						<td>
							<input type="text" name="AlasanAddendum" id="AlasanAddendum" class="form-control input-sm" placeholder="Isi Disini" >
						</td>
					</tr>
				</table>
			</div>

			<div class="col-sm-12">
				<br>
				<input type="hidden" id="LedgerNo" value="<?=$LedgerNo?>" disabled>
				<input type="hidden" id="NoKO" value="<?=$NoKO?>" disabled>
				<button onclick="simpan_addendum()" class="btn btn-info btn-sm pull-right btn_simpan">Simpan</button>	
				<button  class="btn bg-secondary btn-sm pull-left" data-dismiss="modal" >Batal</button>
			</div>

		</div>


	</div>

	<div class="box-footer">
		<div class="table-responsive">
			<table class="table table-bordered table-striped table-condensed table-hover" id="table_ko">
				<thead>
					<tr>
						<th>No.</th>
						<th>No. KO</th>
						<th>Tgl KO</th>
						<th>Penerima Kontrak</th>
						<th>Tipe KO</th>
						<th>Addendum/ <br> Revisi Ke</th>
						<th>Total KO (Rp)</th>
						<th>KO Approved By</th>
						<th>KO Approved ON</th>
						<th>Keterangan</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if($data_ko->num_rows() == 0 ){

						echo ' <tr><td colspan="10" align="center">belum ada data</td></tr>';
						die;
					}
					?>

					<?php 
					$no=1;
					foreach($data_ko->result_array() as $data ) : ?>
						<tr>
							<td><?=$no++;?></td>
							<td><?=$data['NoKO']?></td>
							<td><?=TglIndoGarisMiring_dengan_waktu($data['TglKO'])?></td>
							<td><?=$data['VendorNm']?></td>
							<td><?=$data['KategoriId']?></td>
							<td><?=$data['AddendumKe']?></td>
							<td><?=UbahGabunganUang($data['SubTotal'])?></td>
							<td><?=strtoupper($data['ApprovedBy'])?></td>
							<td><?=TglIndoGarisMiring_tanpa_waktu($data['TimeApproved'])?></td>
							<td><?=$data['Keterangan']?></td>


						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		


	</div>
</div>




