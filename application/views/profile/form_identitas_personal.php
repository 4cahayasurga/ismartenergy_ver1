
<form id="form_identitas_personal" method="POST" action="<?=site_url('Kepegawaian/aksi_ubah_karyawan')?>" >

	<div class="table-responsive" style="font-size:10px;font-family: 'Gill Sans', 'Gill Sans MT', Calibri, sans-serif;">


		<!-- //KTP MULAI -->
		<table width="100%" border="0" style="border-collapse:collapse;">
			<tr>
				<td style="text-align:center;padding-top:5px;padding-bottom: 5px;background-color: #16a085;color:white;">KTP</td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 5px !important;">

			<tr>
				<td style="width:10%;padding-top: 10px !important;">KTP</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="text" name="KTP"  class="form-control input-sm" value="<?=$KTP?>" placeholder="No. KTP" >
				</td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 5px !important;">

			<tr>
				<td style="width:10%">Berlaku Dari</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="date" name="PrdAwal_KTP"  class="form-control input-sm" value="<?=$PrdAwal_KTP?>">
				</td>
				<td style="width:3%;text-align: center;">s/d</td>
				<td>
					<input type="date" name="PrdAkhir_KTP"  class="form-control input-sm" value="<?=$PrdAkhir_KTP?>">
				</td>
			</tr>

		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top:5px !important;">

			<tr>
				<td style="width:10%;padding-top: 10px !important;">Diterbitkan Oleh</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="text" name="DiterbitkanOleh_KTP"  class="form-control input-sm" value="<?=$DiterbitkanOleh_KTP?>" placeholder="Diterbitkan Oleh">
				</td>
			</tr>
		</table>


		<!-- //PASSPOR MULAI -->
		<table width="100%" border="0" style="border-collapse:collapse;">
			<tr>
				<td style="text-align:center;padding-top:5px;padding-bottom: 5px;background-color: #27ae60;color:white;">PASSPOR</td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 5px !important;">

			<tr>
				<td style="width:10%;padding-top: 10px !important;">PASSPOR</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="text" name="PASSPOR"  class="form-control input-sm" value="<?=$PASSPOR?>" placeholder="No. PASSPOR" >
				</td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 5px !important;">

			<tr>
				<td style="width:10%">Berlaku Dari</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="date" name="PrdAwal_PASSPOR"  class="form-control input-sm" value="<?=$PrdAwal_PASSPOR?>">
				</td>
				<td style="width:3%;text-align: center;">s/d</td>
				<td>
					<input type="date" name="PrdAkhir_PASSPOR"  class="form-control input-sm" value="<?=$PrdAkhir_PASSPOR?>">
				</td>
			</tr>

		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top:5px !important;">

			<tr>
				<td style="width:10%;padding-top: 10px !important;">Diterbitkan Oleh</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="text" name="DiterbitkanOleh_PASSPOR"  class="form-control input-sm" value="<?=$DiterbitkanOleh_PASSPOR?>" placeholder="Diterbitkan Oleh">
				</td>
			</tr>
		</table>



		<!-- //NPWP MULAI -->
		<table width="100%" border="0" style="border-collapse:collapse;">
			<tr>
				<td style="text-align:center;padding-top:5px;padding-bottom: 5px;background-color: #2980b9;color:white;">NPWP</td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 5px !important;">

			<tr>
				<td style="width:10%;padding-top: 10px !important;">NPWP</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="text" name="NPWP"  class="form-control input-sm" value="<?=$NPWP?>" placeholder="No. NPWP" >
				</td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 5px !important;">

			<tr>
				<td style="width:10%">Berlaku Dari</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="date" name="PrdAwal_NPWP"  class="form-control input-sm" value="<?=$PrdAwal_NPWP?>">
				</td>
				<td style="width:3%;text-align: center;">s/d</td>
				<td>
					<input type="date" name="PrdAkhir_NPWP"  class="form-control input-sm" value="<?=$PrdAkhir_NPWP?>">
				</td>
			</tr>

		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top:5px !important;">

			<tr>
				<td style="width:10%;padding-top: 10px !important;">Diterbitkan Oleh</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="text" name="DiterbitkanOleh_NPWP"  class="form-control input-sm" value="<?=$DiterbitkanOleh_NPWP?>" placeholder="Diterbitkan Oleh">
				</td>
			</tr>
		</table>


		<!-- //KK MULAI -->
		<table width="100%" border="0" style="border-collapse:collapse;">
			<tr>
				<td style="text-align:center;padding-top:5px;padding-bottom: 5px;background-color: #8e44ad;color:white;">KK</td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 5px !important;">

			<tr>
				<td style="width:10%;padding-top: 10px !important;">KK</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="text" name="KK"  class="form-control input-sm" value="<?=$KK?>" placeholder="No. KK" >
				</td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 5px !important;">

			<tr>
				<td style="width:10%">Berlaku Dari</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="date" name="PrdAwal_KK"  class="form-control input-sm" value="<?=$PrdAwal_KK?>">
				</td>
				<td style="width:3%;text-align: center;">s/d</td>
				<td>
					<input type="date" name="PrdAkhir_KK"  class="form-control input-sm" value="<?=$PrdAkhir_KK?>">
				</td>
			</tr>

		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top:5px !important;">

			<tr>
				<td style="width:10%;padding-top: 10px !important;">Diterbitkan Oleh</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="text" name="DiterbitkanOleh_KK"  class="form-control input-sm" value="<?=$DiterbitkanOleh_KK?>" placeholder="Diterbitkan Oleh">
				</td>
			</tr>
		</table>


		<!-- //SIM_A MULAI -->
		<table width="100%" border="0" style="border-collapse:collapse;">
			<tr>
				<td style="text-align:center;padding-top:5px;padding-bottom: 5px;background-color: #2c3e50;color:white;">SIM A</td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 5px !important;">

			<tr>
				<td style="width:10%;padding-top: 10px !important;">SIM A</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="text" name="SIM_A"  class="form-control input-sm" value="<?=$SIM_A?>" placeholder="No. SIM A" >
				</td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 5px !important;">

			<tr>
				<td style="width:10%">Berlaku Dari</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="date" name="PrdAwal_SIM_A"  class="form-control input-sm" value="<?=$PrdAwal_SIM_A?>">
				</td>
				<td style="width:3%;text-align: center;">s/d</td>
				<td>
					<input type="date" name="PrdAkhir_SIM_A"  class="form-control input-sm" value="<?=$PrdAkhir_SIM_A?>">
				</td>
			</tr>

		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top:5px !important;">

			<tr>
				<td style="width:10%;padding-top: 10px !important;">Diterbitkan Oleh</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="text" name="DiterbitkanOleh_SIM_A"  class="form-control input-sm" value="<?=$DiterbitkanOleh_SIM_A?>" placeholder="Diterbitkan Oleh">
				</td>
			</tr>
		</table>

		<!-- //SIM_B MULAI -->
		<table width="100%" border="0" style="border-collapse:collapse;">
			<tr>
				<td  style="text-align:center;padding-top:5px;padding-bottom: 5px;background-color: #f39c12;color:white;">SIM B</td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 5px !important;">

			<tr>
				<td style="width:10%;padding-top: 10px !important;">SIM B</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="text" name="SIM_B"  class="form-control input-sm" value="<?=$SIM_B?>" placeholder="No. SIM B" >
				</td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 5px !important;">

			<tr>
				<td style="width:10%">Berlaku Dari</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="date" name="PrdAwal_SIM_B"  class="form-control input-sm" value="<?=$PrdAwal_SIM_B?>">
				</td>
				<td style="width:3%;text-align: center;">s/d</td>
				<td>
					<input type="date" name="PrdAkhir_SIM_B"  class="form-control input-sm" value="<?=$PrdAkhir_SIM_B?>">
				</td>
			</tr>

		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top:5px !important;">

			<tr>
				<td style="width:10%;padding-top: 10px !important;">Diterbitkan Oleh</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="text" name="DiterbitkanOleh_SIM_B"  class="form-control input-sm" value="<?=$DiterbitkanOleh_SIM_B?>" placeholder="Diterbitkan Oleh">
				</td>
			</tr>
		</table>


		<!-- //SIM_C MULAI -->
		<table width="100%" border="0" style="border-collapse:collapse;">
			<tr>
				<td style="text-align:center;padding-top:5px;padding-bottom: 5px;background-color: #c0392b;color:white;">SIM C</td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 5px !important;">

			<tr>
				<td style="width:10%;padding-top: 10px !important;">SIM C</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="text" name="SIM_C"  class="form-control input-sm" value="<?=$SIM_C?>" placeholder="No. SIM C" >
				</td>
			</tr>
		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top: 5px !important;">

			<tr>
				<td style="width:10%">Berlaku Dari</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="date" name="PrdAwal_SIM_C"  class="form-control input-sm" value="<?=$PrdAwal_SIM_C?>">
				</td>
				<td style="width:3%;text-align: center;">s/d</td>
				<td>
					<input type="date" name="PrdAkhir_SIM_C"  class="form-control input-sm" value="<?=$PrdAkhir_SIM_C?>">
				</td>
			</tr>

		</table>

		<table width="100%" border="0" style="border-collapse:collapse;margin-top:5px !important;">

			<tr>
				<td style="width:10%;padding-top: 10px !important;">Diterbitkan Oleh</td>
				<td style="width:1%;text-align: center;">:</td>
				<td>
					<input type="text" name="DiterbitkanOleh_SIM_C"  class="form-control input-sm" value="<?=$DiterbitkanOleh_SIM_C?>" placeholder="Diterbitkan Oleh">
				</td>
			</tr>
		</table>



		<p></p>
		<table width="100%">
			<tr>
				<th style="padding-top:10px;border-top:1px solid black;text-align: right;">
					<!-- <button type="submit" class="btn btn-md btn-primary" style="display:none;">Simpan</button> -->
					<input type="hidden" name="NIK" value="<?=$NIK?>" >
					<button type="submit" class="btn btn-md btn-primary" style="display:;">Simpan</button>
				</th>
			</tr>
		</table>





	</div>

</form>