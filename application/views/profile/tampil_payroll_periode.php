<style type="text/css">

	.huruf_16{
		font-size: 16px !important;
		font-weight: bold;
		font-family: "Arial Rounded MT Bold", "Helvetica Rounded", Arial, sans-serif;
	}
	.huruf_14{
		font-size: 14px !important;
		font-weight: bold;
		font-family: "Arial Rounded MT Bold", "Helvetica Rounded", Arial, sans-serif;
	}
	.huruf{
		font-size: 12px !important;
		font-family: "Arial Rounded MT Bold", "Helvetica Rounded", Arial, sans-serif;
	}

</style>




<div class="box" style="border:none;">

	  <?php
  if (count($info_payroll) < 1 ) {
    echo '<h1>data tidak ditemukan</h1>';
    exit;
  }
  ?>

	<div class="box-body">

		<table width="100%" border="0" style="display:;" >
			<tr>
				<td style="border-bottom:1px solid;padding-bottom:5px;">
					<a href="<?=site_url('Kepegawaian/pdf_payroll/'.$NIK.'/'.$PrdBulan.'/'.$PrdTahun)?>" target="_blank" class="btn btn-danger btn-sm" >Print PDF <i class="fa fa-print"></i></a>
				</td>
			</tr>
		</table>

		<table width="100%" border="0" style="display:;" >

			<tr>
				<td style="width:20%;text-align: center;border-bottom:1px solid;padding-bottom:5px;">

					<img class="img-responsive col-xs-12"  style="max-width: 200px;max-height: 200px;text-align: center !!important;padding-top: 20px !important;padding-bottom: 20px !important;"  src="<?=$LogoPT?>"  />

				</td>
				<td style="width:80%;text-align: center;border-bottom:1px solid;" class="huruf_16">SLIP GAJI <?=$BulanKata.' '.$PrdTahun?></td>
			</tr>

			<tr>
				<td style="width:100%;padding-top: 5px;padding-bottom: 5px;color:red;font-weight: bold;" class="huruf" colspan="2"><?=$NamaKaryawan?></td>
			</tr>

		</table>

		<table width="100%" border="0" style="display:;">

			<tr>
				<td style="width:50%;padding-top: 3px;padding-bottom: 3px;" class="huruf"><?=$info_payroll['Jabatan']?> / NIK : <?=$info_payroll['NIK']?></td>
				<td style="width:50%;padding-top: 3px;padding-bottom: 3px;" class="huruf">Gaji Pokok</td>
			</tr>
			<tr>
				<td style="width:50%;padding-top: 3px;padding-bottom: 3px;color:black;font-weight: bold;" class="huruf_14"><?=strtoupper($NamaPT)?></td>
				<td style="width:50%;padding-top: 3px;padding-bottom: 3px;color:black;font-weight: bold;" class="huruf_14"><?='Rp '.UangBiasa($info_payroll['GajiPokok'])?></td>
			</tr>

		</table>

		<div class="row" style="display:;">

			<div class="col-md-6">

				<div class="col-sm-12" >

					<table width="100%" border="1" class="huruf">
						<tr>
							<td style="padding-top: 3px;padding-bottom: 3px;background-color: #95a5a6;color:red;border:1px solid red;" >TUNJANGAN</td>
						</tr>
					</table>

					<p></p>
					<table width="100%" border="0" class="huruf">

						<tr>
							<td style="width: 35%;padding-top: 3px;padding-bottom: 3px;padding-left:10px !important;" >Tunjangan Kesehatan</td>
							<td style="width: 2%;text-align: center;">:</td>
							<td style="text-align:right;"><?='Rp '.UangBiasa($info_payroll['TunjanganKesehatan'])?></td>
						</tr>

						<tr>
							<td style="width: 35%;padding-top: 3px;padding-bottom: 3px;padding-left:10px !important;" >Tunjangan Jabatan</td>
							<td style="width: 2%;text-align: center;" >:</td>
							<td style="text-align:right;" ><?='Rp '.UangBiasa($info_payroll['TunjanganJabatan'])?></td>
						</tr>

						<tr>
							<td style="width: 35%;padding-top: 3px;padding-bottom: 3px;padding-left:10px !important;" >Tunjangan Operasional</td>
							<td style="width: 2%;text-align: center;" >:</td>
							<td style="text-align:right;" ><?='Rp '.UangBiasa($info_payroll['TunjanganOperasional'])?></td>
						</tr>

					</table>


				</div>

			</div>

			<div class="col-md-6">

				<div class="col-sm-12">

					<table width="100%" border="1" class="huruf">
						<tr>
							<td style="padding-top: 3px;padding-bottom: 3px;background-color: #95a5a6;color:red;border:1px solid red;" >&nbsp;POTONGAN</td>
						</tr>
					</table>
					<p></p>

					<table width="100%" border="0" class="huruf">

						<tr>
							<td style="width: 35%;padding-top: 3px;padding-bottom: 3px;padding-left:10px !important;" >PPh 21</td>
							<td style="width: 2%;text-align: center;">:</td>
							<td style="text-align:right;"><?='Rp '.UangBiasa($info_payroll['PPh21'])?></td>
						</tr>

						<tr>
							<td style="width: 35%;padding-top: 3px;padding-bottom: 3px;padding-left:10px !important;">Jaminan Hari Tua</td>
							<td style="width: 2%;text-align: center;">:</td>
							<td style="text-align:right;"><?='Rp '.UangBiasa($info_payroll['JHTEmployee'])?></td>
						</tr>

						<tr>
							<td style="width: 35%;padding-top: 3px;padding-bottom: 3px;padding-left:10px !important;">Jaminan Pensiun</td>
							<td style="width: 2%;text-align: center;">:</td>
							<td style="text-align:right;"><?='Rp '.UangBiasa($info_payroll['JPEmployee'])?></td>
						</tr>

						<tr>
							<td style="width: 35%;padding-top: 3px;padding-bottom: 3px;padding-left:10px !important;">Potongan Kedisplinan</td>
							<td style="width: 2%;text-align: center;">:</td>
							<td style="text-align:right;"><?='Rp '.UangBiasa($info_payroll['PotonganKedisiplinan'])?></td>
						</tr>

						<tr>
							<td style="width: 35%;padding-top: 3px;padding-bottom: 3px;padding-left:10px !important;">Potongan THP</td>
							<td style="width: 2%;text-align: center;">:</td>
							<td style="text-align:right;"><?='Rp '.UangBiasa($info_payroll['PotonganTHP'])?></td>
						</tr>

					</table>
					<p></p>

				</div>


			</div>

			<div class="col-sm-12">
				<table width="100%" border="0" class="huruf_14">
					<tr>
						<th class="huruf_14" style="padding-top: 3px;padding-bottom: 3px;background-color: #95a5a6;color:red;">&nbsp;&nbsp;&nbsp;&nbsp;TAKE HOME PAY</th>
						<th class="huruf_14" style="padding-top: 3px;padding-bottom: 3px;text-align: right;background-color: #95a5a6;color:red;"><?='Rp '.UangBiasa($info_payroll['GajiNetto'])?>&nbsp;&nbsp;&nbsp;&nbsp;</th>
					</tr>
				</table>
			</div>


		</div>


	</div>

</div>
