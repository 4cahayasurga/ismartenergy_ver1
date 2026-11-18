<style type="text/css">
	#content_wrap{
		font-size: 10px;
		font-family: Roboto, Tahoma, sans-serif;
	}

	.judul{
		font-size: 12px;
		font-family: Roboto, Tahoma, sans-serif;
	}
	.ukuran{
		font-size: 10px !important;
		font-family: Roboto, Tahoma, sans-serif;
	}
	.ukuran_select2{
		font-size: 10px !important;
		font-family: Roboto, Tahoma, sans-serif;
	}
	input[type="text"]{
		font-size: 10px !important;
		font-family: Roboto, Tahoma, sans-serif;
	}
	input[type="date"]{
		font-size: 10px !important;
		font-family: Roboto, Tahoma, sans-serif;
	}
	input[type="checkbox"]{
		font-size: 10px !important;
		font-family: Roboto, Tahoma, sans-serif;
	}
	input[type="radio"]{
		font-size: 10px !important;
		font-family: Roboto, Tahoma, sans-serif;
	}
	textarea{
		font-size: 10px !important;
		font-family: Roboto, Tahoma, sans-serif;
	}
	select {
		font-size: 10px !important;
		font-family: Roboto, Tahoma, sans-serif;
	}
	.swal2-popup {
		font-size: 1.2rem !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
	}
</style>
<div class="content-wrapper" id="content_wrap">

	<section class="content">

		<table style="width:100%;border-collapse: collapse;margin-bottom: 10px !important;" border="0" >
			<tr>
				<th class="judul"><?=($judul) ? $judul : ''   ?></th>
				<th class="judul text-right text-primary"><?=($job_detail) ? $job_detail : ''   ?></th>
			</tr>
		</table>



		<div class="box box-body box-widget">
			
			<div class="col-sm-6">
				
				<table width="100%" border="1" style="border-collapse:collapse;">
					<tr>
						<td width="10%">No. PD</td>
						<td width="1%" class="text-center">:</td>
						<td>
							<input type="text" class="form-control input-sm" value="<?=$NoPD?>" >
						</td>
						<td width="3%"></td>
						<td>No. BAP/Ref Lapangan</td>
						<td width="1%" class="text-center">:</td>
						<td>
							<input type="text" name="NoRef" id="NoRef" class="form-control input-sm" value="<?=$NoRef?>" >
						</td>
					</tr>
				</table>

				<table width="100%" border="1" style="border-collapse:collapse;">
					<tr>
						<td width="10%">Job.No</td>
						<td width="1%" class="text-center">:</td>
						<td width="50%">
							<input type="text" class="form-control input-sm" value="<?=$JobNo.' - '.$JobNm?>" >
						</td>
						<td></td>
					</tr>
				</table>

				<table width="100%" border="1" style="border-collapse:collapse;">
					<tr>
						<td width="10%">Tgl. Permintaan</td>
						<td width="1%" class="text-center">:</td>
						<td width="50%">
							<input type="date" class="form-control input-sm" name="TglPD" id="TglPD" value="<?= date('Y-m-d') ?>" >
						</td>
						<td>
							&nbsp;&nbsp;&nbsp;<input class="form-check-input" type="checkbox" name="Jaminan" id="Jaminan" value="1">
							<label class="form-check-label" for="Jaminan">PD Jaminan</label>
						</td>
					</tr>
				</table>

				<table width="100%" border="1" style="border-collapse:collapse;">
					<tr>
						<td width="10%">Deskripsi</td>
						<td width="1%" class="text-center">:</td>
						<td>
							<input type="text" class="form-control input-sm" value="" placeholder="Deskripsi" >
						</td>
					</tr>
				</table>


				<table width="100%" border="1" style="border-collapse:collapse;">
					<tr>
						<td width="10%">Periode</td>
						<td width="1%" class="text-center">:</td>
						<td width="50%">
							<input type="date" class="form-control input-sm" name="PrdAwal" id="PrdAwal" value="<?= date('Y-m-d') ?>" >
						</td>
						<td>
							&nbsp;&nbsp;&nbsp;<input class="form-check-input" type="checkbox" name="Jaminan" id="Jaminan" value="1">
							<label class="form-check-label" for="Jaminan">PD Jaminan</label>
						</td>
					</tr>
				</table>

			</div>


			<div class="col-sm-6 bg-blue">
				
			</div>

		</div>


	</section>

</div>

<script src="<?=base_url('assets/select2_baru.js')?>"></script>

