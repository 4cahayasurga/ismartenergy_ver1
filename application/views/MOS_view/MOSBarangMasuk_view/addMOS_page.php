<style type="text/css">
	.ukuran12{
		font-size: 12px;
	}
	body {
		font-size: 12px;
		font-family: sans-serif;
	}

</style>
<div class="content-wrapper">

	<section class="content">

		<div class="row">
			<div class="col-sm-12">
				<div class="box box-danger">
					<div class="box-header">
						<h5><?=($judul) ? strtoupper($judul) : 'Judul belum diatur'  ?></h5>
					</div>
					<div class="box-body">
						<div class="box-body">

							<form id="FrmSimpanMOS" >

								<div class="col-sm-4">
									<table width="100%" border="1" style="border-collapse:collapse;">
										<tr>
											<td>Job No</td>
											<td class="text-center">:</td>
											<td>
												<input type="text" value="<?=$JobNo.' - '.$JobNm?>" class="form-control input-sm">

											</td>
										</tr>
									</table>
								</div>

								<input type="hidden" name="JobNo" value="<?=$JobNo?>">
							</form>

						</div>
					</div>
				</div>
			</div>
		</div>

	</section>

</div>