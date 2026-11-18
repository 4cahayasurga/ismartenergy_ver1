<style type="text/css">
	#mytable{
		font-size: 10px;
		font-family: "Arial Rounded MT Bold", "Helvetica Rounded", Arial, sans-serif;
		/*border: 1px solid;*/
	}
	#mytable tr th{
		padding-top: 10px;
		padding-bottom: 10px;
		padding-left: 10px !important;
		/*padding-right: 10px !important;*/
		/*padding-left: 2px;*/
	}
	#mytable tr td{
		padding-top: 10px !important;
		padding-bottom: 10px !important; 
		padding-left: 10px !important;
		/*padding-right: 10px !important;*/
	}
</style>

<div class="row">
	<div class="col-sm-12">
		<div class="table-responsive">
			<table width="100%" border="0" style="border-collapse:collapse" id="mytable">
				<tr style="border-bottom:1px solid">
					<th style="">No</th>
					<th>Invoice</th>
					<th>No PD</th>
					<th style="text-align:right;width: 50%;" >Payment Amount (Rp)</th>
					<th style="text-align:center;">Tgl Bayar</th>
				</tr>

				<?php
				$nomor_urut = 1;
				foreach($invoice_detail as $mydetail) : ?>

					<tr>
						<td><?=$nomor_urut++?></td>
						<td><?=$mydetail->InvNo?></td>
						<td><?=$mydetail->NoPD?></td>
						<td style="text-align:right;" ><?=UangBiasa($mydetail->PaymentAmount)?></td>
						<td style="text-align:center;"><?=tgl_baru($mydetail->TglBayar)?></td>
					</tr>

				<?php endforeach; ?>
				
			</table>
			<p></p>
			<table width="100%" border="0" >
				<tr style="text-align:right !important;">
					<td >
						<button type="button" class="btn btn-md btn-danger pull-right" data-dismiss="modal">Tutup</button>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>