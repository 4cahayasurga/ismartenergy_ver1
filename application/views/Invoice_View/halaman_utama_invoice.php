<?php
error_reporting(E_ALL);
?>
<style type="text/css">
	body{
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
	.huruf15{
		font-size: 15px !important;
	}
	.huruf16{
		font-size: 16px !important;
	}

	.marginatas5{
		margin-top: 5px !important;
	}
	.myFont{
		font-size:12px !important;
	}
	
</style>


<div class="content-wrapper">

	<section class="content-header">
		<h1><u><?=$title?></u></h1>
	</section>

	<section class="content">

		<div class="box box-primary"  >
			<div class="box-header">
				
				<div class="row">
					<div class="col-sm-12">
						<?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : ''?>
						<div class="table-responsive">
							<form id="FormInvoice" >

								<table width="100%" border="0" class="huruf13">
									<tr>
										<td width="100px">Job No</td>
										<td width="10px" class="text-center">:</td>
										<td width="500px">
											<select id="JobNo" name="JobNo" width="400px" class="form-control input-sm select2 huruf13" >
												<option value="">:: Pilih Job ::</option>
												<?php foreach ($ListJob as $row): ?>
													<option value="<?=$row->JobNo?>" <?=($JobNo==$row->JobNo) ? 'selected' : '' ?> ><?=$row->JobNo.' - '.$row->JobNm?></option>
												<?php endforeach ?>
											</select>
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" border="0" class="marginatas5 huruf12">
									<tr>
										<td width="100px"></td>
										<td width="10px" class="text-center"></td>
										<td width="150px">
											<button type="submit" class="btn btn-default" id="btnFilter">FILTER</button>
										</td>
										<td></td>
									</tr>
								</table>



							</form>

						</div>
					</div>
				</div>

			</div>

			<div class="box-body" id="tampil_table_invoice" >

			</div>
			
		</div>


		

	</section>
</div>

<div class="clearfix"></div>
