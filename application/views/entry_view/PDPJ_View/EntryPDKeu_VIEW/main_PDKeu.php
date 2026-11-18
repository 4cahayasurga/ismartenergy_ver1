<style type="text/css">
	div .content-wrapper{
		font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
	}

	.huruf12{
		font-size: 12px !important;
	}
	.huruf13{
		font-size: 13px !important;
	}
	.huruf14{
		font-size: 14px !important;
	}
	
	.margin_atas_5{
		margin-top: 5px;
	}
	

	.huruf13{
		font-size: 13px !important;
	}
	.huruf14{
		font-size: 14px !important;
	}

	

	.select2-container{ width: 100% !important; }

	.tengah_ngambang{
		vertical-align: middle;
	}


</style>
<div class="content-wrapper">

	<section class="content-header">
		<h1><u><?=$title?></u></h1>
	</section>
	<section class="content">

		<div class="box box-primary">

			<div class="box-header">

				<div class="row">

					<div class="col-sm-6">
						<?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>
						<div class="table-responsive">
							<form id="formPDKeuangan">
								<table width="100%" border="0" class="huruf14">
									<tr>
										<td width="100px">Job No.</td>
										<td width="20px" class="text-center">:</td>
										<td>
											<select name="JobNo" id="JobNo" class="form-control select2 ">
												<option value="">:: Pilih Salah Satu ::</option>
												<?php foreach ($ListJob as $row): ?>
													<option value="<?=$row->JobNo?>" <?=($row->JobNo==$JobNo_Redirect) ? 'selected' : ''  ?> ><?=$row->JobNo?> - <?=$row->JobNm?></option>
												<?php endforeach ?>
											</select>
										</td>
									</tr>
								</table>

								<table width="100%" border="0" class="huruf14 margin_atas_5" >
									<tr>
										<td width="100px">Alokasi.</td>
										<td width="20px" class="text-center">:</td>
										<td width="300px">
											<select name="Alokasi" id="Alokasi" class="form-control">
												<option value="">:: Pilih Salah Satu ::</option>
												<?php foreach ($ListAlokasi as $row): ?>
													<option value="<?=$row->Alokasi?>" <?=($row->Alokasi==$Alokasi_Redirect) ? 'selected' : ''  ?> ><?=$row->Alokasi?> - <?=$row->Keterangan?></option>
												<?php endforeach ?>
											</select>
										</td>
										<td>
											&nbsp;&nbsp;&nbsp;
											<input type="checkbox" name="CB_PD_MIX" id="CB_PD_MIX" value="1">
											<label for="CB_PD_MIX" style="font-weight:normal;">Tampilkan hanya PDMIX</label>
										</td>
									</tr>
								</table>

								<table width="100%" border="0" class="huruf14 margin_atas_5" >
									<tr>
										<td width="100px"></td>
										<td width="20px" class="text-center"></td>
										<td width="300px">
											<button type="submit" class="btn btn-md btn-primary" id="btnTampilkan">TAMPILKAN</button>
										</td>
										<td></td>
									</tr>
								</table>

							</form>
						</div>
					</div>
					
				</div>

			</div>
			<div class="box-body">
				
			</div>
			<div class="box-footer">
				
			</div>

			<div class="overlay" id="loading_halaman" style="display:none;">
				<i class="fa fa-refresh fa-spin"></i>
			</div>

		</div>

		<div id="tampil_table_PD_Keuangan">
			
		</div>

	</section>
</div>

<div class="clearfix"></div>