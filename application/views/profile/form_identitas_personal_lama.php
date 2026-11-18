

<div class="box">
	<div class="box-body">

		<!-- //KTP MULAI -->
		
		<table width="100%" border="0" style="border-collapse:collapse;">
			<tr>
				<td class="bg-primary" style="text-align:center;padding-top:5px;padding-bottom: 5px;">KTP</td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 5px !important;">
			
			<tr>
				<td style="width:10%;padding-top: 10px !important;">KTP</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="text" name="KTP"  class="form-control input-sm" value="<?=($dataIdentitasPersonal['JenisID'] == 'KTP') ? $dataIdentitasPersonal['NoID'] : '' ?>" placeholder="No. KTP"  required  >
				</td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 5px !important;">

			<tr>
				<td style="width:10%">Berlaku Dari</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="date" name="PrdAwal_KTP"  class="form-control input-sm" value="<?=($dataIdentitasPersonal['JenisID'] == 'KTP') ? $dataIdentitasPersonal['PrdAwal'] : '' ?>">
				</td>
				<td style="width:3%;text-align: center;">s/d</td>
				<td>
					<input type="date" name="PrdAkhir_KTP"  class="form-control input-sm" value="<?=($dataIdentitasPersonal['JenisID'] == 'KTP') ? $dataIdentitasPersonal['PrdAkhir'] : '' ?>">
				</td>
			</tr>

		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top:5px !important;">
			
			<tr>
				<td style="width:10%;padding-top: 10px !important;">Diterbitkan Oleh</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="text" name="DiterbitkanOleh_KTP"  class="form-control input-sm" value="<?=($dataIdentitasPersonal['JenisID'] == 'KTP') ? $dataIdentitasPersonal['DiterbitkanOleh'] : '' ?>" placeholder="Diterbitkan Oleh">
				</td>
			</tr>
		</table>


		<!-- //PASSPORT MULAI -->
		<p></p>
		<table width="100%" border="0" style="border-collapse:collapse;">
			<tr>
				<td class="bg-success" style="text-align:center;padding-top:5px;padding-bottom: 5px;">PASSPORT</td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 5px !important;">
			
			<tr>
				<td style="width:10%;padding-top: 10px !important;">PASSPORT</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="text" name="PASSPORT"  class="form-control input-sm" value="<?=($dataIdentitasPersonal['JenisID'] == 'PASSPORT') ? $dataIdentitasPersonal['NoID'] : '' ?>" placeholder="No. Passport"  required  >
				</td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 5px !important;">

			<tr>
				<td style="width:10%">Berlaku Dari</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="date" name="PrdAwal_PASSPORT"  class="form-control input-sm" value="<?=($dataIdentitasPersonal['JenisID'] == 'PASSPORT') ? $dataIdentitasPersonal['PrdAwal'] : '' ?>">
				</td>
				<td style="width:3%;text-align: center;">s/d</td>
				<td>
					<input type="date" name="PrdAkhir_PASSPORT"  class="form-control input-sm" value="<?=($dataIdentitasPersonal['JenisID'] == 'PASSPORT') ? $dataIdentitasPersonal['PrdAkhir'] : '' ?>">
				</td>
			</tr>

		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top:5px !important;">
			
			<tr>
				<td style="width:10%;padding-top: 10px !important;">Diterbitkan Oleh</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="text" name="DiterbitkanOleh_PASSPORT"  class="form-control input-sm" value="<?=($dataIdentitasPersonal['JenisID'] == 'PASSPORT') ? $dataIdentitasPersonal['DiterbitkanOleh'] : '' ?>" placeholder="Diterbitkan Oleh">
				</td>
			</tr>
		</table>


		<!-- //NPWP MULAI -->
		<p></p>
		<table width="100%" border="0" style="border-collapse:collapse;">
			<tr>
				<td class="bg-warning" style="text-align:center;padding-top:5px;padding-bottom: 5px;">NPWP</td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 5px !important;">
			
			<tr>
				<td style="width:10%;padding-top: 10px !important;">NPWP</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="text" name="NPWP"  class="form-control input-sm" value="<?=($dataIdentitasPersonal['JenisID'] == 'NPWP') ? $dataIdentitasPersonal['NoID'] : '' ?>" placeholder="No. NPWP"  required  >
				</td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 5px !important;">

			<tr>
				<td style="width:10%">Berlaku Dari</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="date" name="PrdAwal_NPWP"  class="form-control input-sm" value="<?=($dataIdentitasPersonal['JenisID'] == 'NPWP') ? $dataIdentitasPersonal['PrdAwal'] : '' ?>">
				</td>
				<td style="width:3%;text-align: center;">s/d</td>
				<td>
					<input type="date" name="PrdAkhir_NPWP"  class="form-control input-sm" value="<?=($dataIdentitasPersonal['JenisID'] == 'NPWP') ? $dataIdentitasPersonal['PrdAkhir'] : '' ?>">
				</td>
			</tr>

		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top:5px !important;">
			
			<tr>
				<td style="width:10%;padding-top: 10px !important;">Diterbitkan Oleh</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="text" name="DiterbitkanOleh_NPWP"  class="form-control input-sm" value="<?=($dataIdentitasPersonal['JenisID'] == 'NPWP') ? $dataIdentitasPersonal['DiterbitkanOleh'] : '' ?>" placeholder="Diterbitkan Oleh">
				</td>
			</tr>
		</table>

		<!-- //KK MULAI -->
		<p></p>
		<table width="100%" border="0" style="border-collapse:collapse;">
			<tr>
				<td class="bg-warning" style="text-align:center;padding-top:5px;padding-bottom: 5px;">KK</td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 5px !important;">
			
			<tr>
				<td style="width:10%;padding-top: 10px !important;">KK</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="text" name="KK"  class="form-control input-sm" value="<?=($dataIdentitasPersonal['JenisID'] == 'KK') ? $dataIdentitasPersonal['NoID'] : '' ?>" placeholder="No. KK"  required  >
				</td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 5px !important;">

			<tr>
				<td style="width:10%">Berlaku Dari</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="date" name="PrdAwal_KK"  class="form-control input-sm" value="<?=($dataIdentitasPersonal['JenisID'] == 'KK') ? $dataIdentitasPersonal['PrdAwal'] : '' ?>">
				</td>
				<td style="width:3%;text-align: center;">s/d</td>
				<td>
					<input type="date" name="PrdAkhir_KK"  class="form-control input-sm" value="<?=($dataIdentitasPersonal['JenisID'] == 'KK') ? $dataIdentitasPersonal['PrdAkhir'] : '' ?>">
				</td>
			</tr>

		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top:5px !important;">
			
			<tr>
				<td style="width:10%;padding-top: 10px !important;">Diterbitkan Oleh</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="text" name="DiterbitkanOleh_KK"  class="form-control input-sm" value="<?=($dataIdentitasPersonal['JenisID'] == 'KK') ? $dataIdentitasPersonal['DiterbitkanOleh'] : '' ?>" placeholder="Diterbitkan Oleh">
				</td>
			</tr>
		</table>


		<!-- //SIM_A MULAI -->
		<p></p>
		<table width="100%" border="0" style="border-collapse:collapse;">
			<tr>
				<td class="bg-warning" style="text-align:center;padding-top:5px;padding-bottom: 5px;">SIM_A</td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 5px !important;">
			
			<tr>
				<td style="width:10%;padding-top: 10px !important;">SIM_A</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="text" name="SIM_A"  class="form-control input-sm" value="<?=($dataIdentitasPersonal['JenisID'] == 'SIM_A') ? $dataIdentitasPersonal['NoID'] : '' ?>" placeholder="No. SIM_A"  required  >
				</td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 5px !important;">

			<tr>
				<td style="width:10%">Berlaku Dari</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="date" name="PrdAwal_SIM_A"  class="form-control input-sm" value="<?=($dataIdentitasPersonal['JenisID'] == 'SIM_A') ? $dataIdentitasPersonal['PrdAwal'] : '' ?>">
				</td>
				<td style="width:3%;text-align: center;">s/d</td>
				<td>
					<input type="date" name="PrdAkhir_SIM_A"  class="form-control input-sm" value="<?=($dataIdentitasPersonal['JenisID'] == 'SIM_A') ? $dataIdentitasPersonal['PrdAkhir'] : '' ?>">
				</td>
			</tr>

		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top:5px !important;">
			
			<tr>
				<td style="width:10%;padding-top: 10px !important;">Diterbitkan Oleh</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="text" name="DiterbitkanOleh_SIM_A"  class="form-control input-sm" value="<?=($dataIdentitasPersonal['JenisID'] == 'SIM_A') ? $dataIdentitasPersonal['DiterbitkanOleh'] : '' ?>" placeholder="Diterbitkan Oleh">
				</td>
			</tr>
		</table>



		<!-- //SIM_B MULAI -->
		<p></p>
		<table width="100%" border="0" style="border-collapse:collapse;">
			<tr>
				<td class="bg-warning" style="text-align:center;padding-top:5px;padding-bottom: 5px;">SIM_B</td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 5px !important;">
			
			<tr>
				<td style="width:10%;padding-top: 10px !important;">SIM_B</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="text" name="SIM_B"  class="form-control input-sm" value="<?=($dataIdentitasPersonal['JenisID'] == 'SIM_B') ? $dataIdentitasPersonal['NoID'] : '' ?>" placeholder="No. SIM_B"  required  >
				</td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 5px !important;">

			<tr>
				<td style="width:10%">Berlaku Dari</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="date" name="PrdAwal_SIM_B"  class="form-control input-sm" value="<?=($dataIdentitasPersonal['JenisID'] == 'SIM_B') ? $dataIdentitasPersonal['PrdAwal'] : '' ?>">
				</td>
				<td style="width:3%;text-align: center;">s/d</td>
				<td>
					<input type="date" name="PrdAkhir_SIM_B"  class="form-control input-sm" value="<?=($dataIdentitasPersonal['JenisID'] == 'SIM_B') ? $dataIdentitasPersonal['PrdAkhir'] : '' ?>">
				</td>
			</tr>

		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top:5px !important;">
			
			<tr>
				<td style="width:10%;padding-top: 10px !important;">Diterbitkan Oleh</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="text" name="DiterbitkanOleh_SIM_B"  class="form-control input-sm" value="<?=($dataIdentitasPersonal['JenisID'] == 'SIM_B') ? $dataIdentitasPersonal['DiterbitkanOleh'] : '' ?>" placeholder="Diterbitkan Oleh">
				</td>
			</tr>
		</table>


		<!-- //SIM_C MULAI -->
		<p></p>
		<table width="100%" border="0" style="border-collapse:collapse;">
			<tr>
				<td class="bg-warning" style="text-align:center;padding-top:5px;padding-bottom: 5px;">SIM_C</td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 5px !important;">
			
			<tr>
				<td style="width:10%;padding-top: 10px !important;">SIM_C</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="text" name="SIM_C"  class="form-control input-sm" value="<?=($dataIdentitasPersonal['JenisID'] == 'SIM_C') ? $dataIdentitasPersonal['NoID'] : '' ?>" placeholder="No. SIM_C"  required  >
				</td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 5px !important;">

			<tr>
				<td style="width:10%">Berlaku Dari</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="date" name="PrdAwal_SIM_C"  class="form-control input-sm" value="<?=($dataIdentitasPersonal['JenisID'] == 'SIM_C') ? $dataIdentitasPersonal['PrdAwal'] : '' ?>">
				</td>
				<td style="width:3%;text-align: center;">s/d</td>
				<td>
					<input type="date" name="PrdAkhir_SIM_C"  class="form-control input-sm" value="<?=($dataIdentitasPersonal['JenisID'] == 'SIM_C') ? $dataIdentitasPersonal['PrdAkhir'] : '' ?>">
				</td>
			</tr>

		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top:5px !important;">
			
			<tr>
				<td style="width:10%;padding-top: 10px !important;">Diterbitkan Oleh</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="text" name="DiterbitkanOleh_SIM_C"  class="form-control input-sm" value="<?=($dataIdentitasPersonal['JenisID'] == 'SIM_C') ? $dataIdentitasPersonal['DiterbitkanOleh'] : '' ?>" placeholder="Diterbitkan Oleh">
				</td>
			</tr>
		</table>

		





	</div>
</div>