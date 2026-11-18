<style>
	.mykonten {
		font-family: Arial, Helvetica, sans-serif !important;
		font-size: x-small !important;
	}

	.ukuran_select2 {
		font-size: 10px !important;
		font-family: Arial, Helvetica, sans-serif;

	}


	.ubah_tulisan{
		color:white !important;
		text-align: left !important;
		font-size: 14px !important;
		font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif !important;
		padding-top:10px !important;
		 text-transform: uppercase !important;
	}

</style>
<div class="content-wrapper">
	<section class="content-header">
		<h6><?=$judul?></h6>
		<p></p>

		<label class="col-sm col-form-label"><?=$datajob['JobDetail']?></label>
		<br>
		<small>
			<label><?=$datajob['Lokasi']?></label>
		</small>
		<ol class="breadcrumb">
		</ol>
	</section>

	<section class="content-header">

		<button class="btn btn-xs" style="background-color:#7f8c8d;" onclick="history.back()"><i class="fa fa-arrow-left"></i> Back</button>

	</section>

	<section class="content">

		<?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>

		<div class="row">

			<div class="col-sm-12" style="font-size: 12px;font-family: TimesNewRoman, 'Times New Roman', Times, Baskerville, Georgia, serif;">

				<table style="width:100%;border:0px solid;border-collapse: collapse;">
					<tr>
						<td style="background-color: #2ecc71;color:black;text-align: center;padding-top:5px;padding-bottom:5px;">MENU BASIC DATA</td>
					</tr>
				</table>

				<div class="row">
					
					<div class="col-xs-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->Alokasi == 0) ? 'hidden' : '' ?>>

						<div class="small-box bg-maroon-active color-palette">
							<a >
								<div class="inner">
									<h3 class="ubah_tulisan">ALOKASI</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->Termin == 0) ? 'hidden' : '' ?> >

						<div class="small-box bg-orange color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">BANK</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->Job == 0) ? 'hidden' : '' ?>  >

						<div class="small-box bg-purple-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">JOB</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->NonJob == 0) ? 'hidden' : '' ?>>

						<div class="small-box bg-teal-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">NON PROJECT</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->Kategori == 0) ? 'hidden' : '' ?>>

						<div class="small-box bg-navy-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">KATEGORI</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->NPWP == 0) ? 'hidden' : '' ?>>

						<div class="small-box bg-gray-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">NPWP</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->Proposal == 0) ? 'hidden' : '' ?>>

						<div class="small-box bg-red-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">PROPOSAL</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->RekPengirim == 0) ? 'hidden' : '' ?>>

						<div class="small-box bg-yellow-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">REK.PENGIRIM</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->TemplateTender == 0) ? 'hidden' : '' ?>>

						<div class="small-box bg-green-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">TEMPLATE TENDER</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->Vendor == 0) ? 'hidden' : '' ?>>

						<div class="small-box bg-aqua-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">VENDOR</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->TipeForm == 0) ? 'hidden' : '' ?>>

						<div class="small-box bg-maroon-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">TIPE FORM</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

				</div>
				


			</div>

			<div class="col-sm-12" style="font-size: 12px;font-family: TimesNewRoman, 'Times New Roman', Times, Baskerville, Georgia, serif;">

				<table style="width:100%;border:0px solid;border-collapse: collapse;">
					<tr>
						<td style="background-color: #3498db;color:black;text-align: center;padding-top:5px;padding-bottom:5px;">MENU PROCUREMENT</td>
					</tr>
				</table>

				<div class="row">
					
					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->QuerySPR == 0) ? 'hidden' : '' ?>>

						<div class="small-box bg-maroon-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">Daftar Surat Permintaan Material/Alat</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->ApprovalSPR == 0) ? 'hidden' : '' ?>>

						<div class="small-box bg-orange-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">Approve Surat Permintaan Material/Alat</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px; <?=($this->session->userdata('PecahToken')->KO == 0) ? 'hidden' : '' ?>">

						<div class="small-box bg-purple-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">Kontrak/Purchase Order</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->ApprovalKO == 0) ? 'hidden' : '' ?>>

						<div class="small-box bg-teal-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">Approve Kontrak/Purchase Order</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->Invoice == 0) ? 'hidden' : '' ?>>

						<div class="small-box bg-navy-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan"> Invoice </h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->ClosingKO == 0) ? 'hidden' : '' ?>>

						<div class="small-box bg-gray-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan"> Penutupan Kontrak </h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;display: none;" <?=($this->session->userdata('PecahToken')->ApprTrackingKOvalKO == 0) ? 'hidden' : '' ?>>

						<div class="small-box bg-red-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">Tracking KO</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;display: none;" <?=($this->session->userdata('PecahToken')->KOAddendum == 0) ? 'hidden' : '' ?>>

						<div class="small-box bg-yellow-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">KO Addendum</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>


				</div>
				


			</div>

			<div class="col-sm-12" style="font-size: 12px;font-family: TimesNewRoman, 'Times New Roman', Times, Baskerville, Georgia, serif;">

				<table style="width:100%;border:0px solid;border-collapse: collapse;">
					<tr>
						<td style="background-color: #e74c3c;color:black;text-align: center;padding-top:5px;padding-bottom:5px;">MENU PD / PJ</td>
					</tr>
				</table>

				<div class="row">
					
					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->ApprovalPD_KT == 0) ? 'hidden' : '' ?> >

						<div class="small-box bg-maroon-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">Approval Permintaan Dana (Cost Control)</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->ApprovalPD_KK == 0) ? 'hidden' : '' ?> >

						<div class="small-box bg-orange-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">Approval Permintaan Dana (Keuangan)</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;"  <?=($this->session->userdata('PecahToken')->ApprovalPJ_KT == 0) ? 'hidden' : '' ?> >

						<div class="small-box bg-purple-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">Approval Pertanggungjawaban Dana (Cost Control)</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->ApprovalPJ == 0) ? 'hidden' : '' ?> >

						<div class="small-box bg-teal-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">Approval Pertanggungjawaban Dana (Keuangan) </h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->GiroMundur == 0) ? 'hidden' : '' ?> >

						<div class="small-box bg-navy-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">Cek/Giro Mundur </h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->PD == 0) ? 'hidden' : '' ?> >

						<div class="small-box bg-gray-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">Entry Permintaan Dana (Lapangan)</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->PDFinance == 0) ? 'hidden' : '' ?> >

						<div class="small-box bg-red-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">Entry Permintaan Dana (Keuangan) </h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->PayPD == 0) ? 'hidden' : '' ?> >

						<div class="small-box bg-green-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan"> Pembayaran Permintaan Dana (Kasir) </h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->RejectPD == 0) ? 'hidden' : '' ?> >

						<div class="small-box bg-aqua-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">Reject Permintaan Dana</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->PJ == 0) ? 'hidden' : '' ?> >

						<div class="small-box bg-light-blue-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">Entry Pertanggungjawaban Dana (Lapangan)</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->PJFinance == 0) ? 'hidden' : '' ?> >

						<div class="small-box bg-maroon-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">Entry Pertanggungjawaban Dana (Keuangan)</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->EntryRKD == 0) ? 'hidden' : '' ?> >

						<div class="small-box bg-purple-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">Entry RKD</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>


				</div>
				


			</div>

			<div class="col-sm-12" style="font-size: 12px;font-family: TimesNewRoman, 'Times New Roman', Times, Baskerville, Georgia, serif;">

				<table style="width:100%;border:0px solid;border-collapse: collapse;">
					<tr>
						<td style="background-color: #f1c40f;color:black;text-align: center;padding-top:5px;padding-bottom:5px;">MENU OPERASI</td>
					</tr>
				</table>

				<div class="row">
					
					<div class="col-lg-3 col-xs-6" style="padding-top:10px;"  <?=($this->session->userdata('PecahToken')->Rap == 0) ? 'hidden' : '' ?> >

						<div class="small-box bg-maroon-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">RAP</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->RPPM1 == 0) ? 'hidden' : '' ?> >

						<div class="small-box bg-orange-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">Progress Fisik / EMON</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->Termin == 0) ? 'hidden' : '' ?> >

						<div class="small-box bg-orange-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">Penerimaan Termin</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->ApprovalTermin == 0) ? 'hidden' : '' ?> >

						<div class="small-box bg-purple-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">Approval Penerimaan Termin</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->LogKasir == 0) ? 'hidden' : '' ?> >

						<div class="small-box bg-teal-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">Log Kasir</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->RPPM == 0) ? 'hidden' : '' ?> >

						<div class="small-box bg-navy-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">RPPM</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->RencanaTermin == 0) ? 'hidden' : '' ?> >

						<div class="small-box bg-gray-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">Rencana Termin</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->OtherIncome == 0) ? 'hidden' : '' ?> >

						<div class="small-box bg-red-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">Penerimaan Lain-Lain</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>

					<div class="col-lg-3 col-xs-6" style="padding-top:10px;" <?=($this->session->userdata('PecahToken')->EntryYAD == 0) ? 'hidden' : '' ?> >

						<div class="small-box bg-green-active color-palette">
							<a href="#" >
								<div class="inner">
									<h3 class="ubah_tulisan">Entry YAD</h3>
									<p></p>
								</div>
								<div class="icon">

								</div>
							</a>
							<a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
						</div>

					</div>


				</div>
				


			</div>

			

		</div>


	</section>
</div>


