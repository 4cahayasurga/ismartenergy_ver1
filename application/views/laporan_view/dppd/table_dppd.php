<style type="text/css">
	.ukuran {
		font-size: 10px !important;
		font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
		/*white-space: nowrap;*/
	}

	.buat_nowrap {
		white-space: nowrap;
	}

	.jarak_atas_bawah {
		padding-top: 7px !important;
		padding-bottom: 7px !important;
	}

	.jarak_atas_bawah_td {
		padding-top: 5px !important;
		padding-bottom: 5px !important;
	}
</style>

<div class="box">

	<div class="box-header text-right">
		<div class="btn-group">
			<button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fa fa-file-excel-o" style="margin-right: 10px;"></i>Export Excel <span class="caret" style="margin-left: 10px;"></span>
			</button>
			<ul class="dropdown-menu">
				<li><a href="<?= site_url('Laporan/Lap_DPPD/exportExcelWithHeader/' . $JobNo . '/' . $Alokasi . '/' . $dari_tgl . '/' . $sampai_tgl) ?>">With Header</a></li>
				<li><a href="<?= site_url('Laporan/Lap_DPPD/exportExcelDetailOnly/' . $JobNo . '/' . $Alokasi . '/' . $dari_tgl . '/' . $sampai_tgl) ?>">Detail Only</a></li>
			</ul>
		</div>
		<a class="btn btn-danger btn-sm btn_create_pdf" data-JobNo="<?= $JobNo ?>" data-Alokasi="<?= $Alokasi ?>" data-dari_tgl="<?= $dari_tgl ?>" data-sampai_tgl="<?= $sampai_tgl ?>" data-all_periode="<?= $all_periode ?>" href="<?= site_url('Laporan/Lap_DPPD/createPDF_RptDPPD/' . $JobNo . '/' . $Alokasi . '/' . $dari_tgl . '/' . $sampai_tgl . '/' . $all_periode) ?>" target="_blank">Print PDF <i class="fa fa-print"></i></a>

		<button class="btn btn-sm btn-danger huruf_ukuran disabled" id="loading_pdf" style="display: none;">Loading... <i class="fa fa-spinner fa-spin"></i> </button>
	</div>

	<?php
	$dari_tgl = ($dari_tgl == '') ? 0 : $dari_tgl;
	$sampai_tgl = ($sampai_tgl == '') ? 0 : $sampai_tgl;
	?>


	<div class="box-body">

		<div class="table-responsive">

			<table width="100%" border="0" style="border-collapse:collapse;" class="table table-striped table-hover table-condensed">

				<tr>
					<th colspan="7"></th>
					<th>Sum Total PD:<br><?= uang_koma_genap_decimal($sumtotalpd) ?></th>
					<th colspan="2">Sum Total Bayar:<br><?= uang_koma_genap_decimal($sumtotalbayar) ?></th>
					<th colspan="7"></th>
				</tr>
				<tr class="ukuran bg-blue">
					<th>No PD</th>
					<th>Tgl PD</th>
					<th style="white-space:nowrap;width: 10% !important;">Deskripsi</th>
					<th>Prd Awal</th>
					<th>Prd Akhir</th>
					<th>No KO</th>
					<th>Nama</th>
					<th style="text-align:right;">Total PD</th>
					<th style="text-align:right;">Total Bayar</th>
					<th>Tgl Bayar</th>
					<th>Via</th>
					<th>Rek Pengirim</th>
					<th>Rek Penerima</th>
					<th>No PJ</th>
					<th>Tgl PJ</th>
					<th style="text-align:right;">Total PJ</th>
					<th>Tipe Form</th>
				</tr>


				<?php

				foreach ($myarray as $arr) :
					$nor = 1;
					$nor_pj = 1;


				?>

					<tr class="ukuran">
						<td><?= $arr['NoPD'] ?></td>
						<td><?= tgl_baru($arr['TglPD']) ?></td>
						<td><?= $arr['Deskripsi'] ?></td>
						<td><?= tgl_baru($arr['PrdAwal']) ?></td>
						<td><?= tgl_baru($arr['PrdAkhir']) ?></td>
						<td><?= $arr['NoKO'] ?></td>
						<td><?= $arr['Nama'] ?></td>
						<td style="text-align:right;"><?= uang_koma_genap_decimal($arr['TotalPD']) ?></td>
						<td style="text-align:right;"><?= uang_koma_genap_decimal($arr['TotalBayar']) ?></td>
						<td><?= tgl_baru($arr['TglBayar']) ?></td>
						<td><?= $arr['CaraBayar'] ?></td>
						<td><?= $arr['RekId'] ?></td>
						<td><?= $arr['RekPenerima'] ?></td>
						<td><? $arr['NoPJ'] ?></td>
						<td><?= tgl_baru($arr['TglPJ']) ?></td>
						<td style="text-align:right;"><?= uang_koma_genap_decimal($arr['TotalPJ']) ?></td>
						<td><?= $arr['TipeForm'] ?></td>
					</tr>

					<?php
					$query_detail = $this->db->select('*, (Vol * HrgSatuan) AS TotalPD_detail ,(PjVol * PjHrgSatuan) AS TotalPJ_detail  ')->where('NoPD', $arr['NoPD'])->get('PdDtl');

					if ($query_detail->num_rows() > 0) : ?>
						<tr>
							<td colspan="16" style="padding-top:10px;padding-bottom:10px;padding-left: 15px;">
								<table style="border-collapse: collapse;width: 100%;border:1px solid;" class="">
									<tr class="ukuran text-bold ">
										<td width="1%" class="jarak_atas_bawah"></td>
										<td width="2%" class="text-center jarak_atas_bawah" style="border-bottom:1px solid;">No</td>
										<td width="5px"></td>
										<td style="border-bottom:1px solid;" class="jarak_atas_bawah">KdRAP&nbsp;&nbsp;&nbsp;</td>
										<td width="5px"></td>
										<td style="border-bottom:1px solid;" class="jarak_atas_bawah">Uraian</td>
										<td width="5px"></td>
										<td style="border-bottom:1px solid;" class="text-right jarak_atas_bawah">Vol</td>
										<td width="5px"></td>
										<td style="border-bottom:1px solid;" class="jarak_atas_bawah">&nbsp;&nbsp;&nbsp;Uom</td>
										<td width="5px"></td>
										<td style="border-bottom:1px solid;width: 7%;" class="text-right jarak_atas_bawah">Hrg Satuan</td>
										<td width="5px"></td>
										<td style="border-bottom:1px solid;padding-right:10px !important;" class="text-right jarak_atas_bawah">Sub Total</td>

										<td width="50px;"></td>
										<td width="50px;"></td>

										<td style="border-bottom:1px solid;">Uraian PJ</td>
										<td width="5px"></td>
										<td style="border-bottom:1px solid;" class="text-right">Vol</td>
										<td width="5px"></td>

										<td style="border-bottom:1px solid;">&nbsp;&nbsp;&nbsp;Uom</td>
										<td width="5px"></td>
										<td style="border-bottom:1px solid;width: 7%;" class="text-right">Hrg Satuan</td>
										<td width="5px"></td>
										<td style="border-bottom:1px solid;padding-right:10px !important;" class="text-right">Sub Total</td>
									</tr>

									<?php foreach ($query_detail->result() as $data_detail) : ?>
										<tr class="ukuran">
											<td width="1%" class="jarak_atas_bawah_td"></td>
											<td width="2%" class="text-center jarak_atas_bawah_td"><?= $nor++ ?></td>
											<td width="5px"></td>
											<td class="jarak_atas_bawah_td"><?= $data_detail->KdRAP ?></td>
											<td width="5px"></td>
											<td class="jarak_atas_bawah_td"><?= $data_detail->Uraian ?></td>
											<td width="5px"></td>
											<td class="text-right jarak_atas_bawah_td"><?= new_decimal($data_detail->Vol) ?></td>
											<td width="5px"></td>
											<td class="jarak_atas_bawah_td">&nbsp;&nbsp;&nbsp;<?= $data_detail->Uom ?></td>
											<td width="5px"></td>
											<td class="text-right"><?= uang_koma_genap_decimal($data_detail->HrgSatuan) ?></td>
											<td width="5px"></td>
											<td class="text-right" style="padding-right:10px !important;"><?= uang_koma_genap_decimal($data_detail->TotalPD_detail) ?></td>

											<td width="50px;"></td>
											<td width="50px;"></td>


											<td><?= $data_detail->PjUraian ?></td>
											<td width="5px"></td>
											<td class="text-right"><?= new_decimal($data_detail->PjVol) ?></td>
											<td width="5px"></td>
											<td>&nbsp;&nbsp;&nbsp;<?= $data_detail->Uom ?></td>
											<td width="5px"></td>
											<td class="text-right"><?= uang_koma_genap_decimal($data_detail->PjHrgSatuan) ?></td>
											<td width="5px"></td>
											<td style="padding-right:10px !important;" class="text-right jarak_atas_bawah_td"><?= uang_koma_genap_decimal($data_detail->TotalPJ_detail) ?></td>
										</tr>
									<?php endforeach; ?>


								</table>
							</td>
						</tr>



					<?php endif; ?>






				<?php endforeach; ?>


			</table>

		</div>

	</div>
</div>





<!-- <script type="text/javascript">
	$(function() {
		
		$('.btn_create_pdf').click(function(){

			

			var JobNo = $(this).attr('data-JobNo');
			var Alokasi = $(this).attr('data-Alokasi');
			var dari_tgl = $(this).attr('data-dari_tgl');
			var sampai_tgl = $(this).attr('data-sampai_tgl');
			var all_periode = $(this).attr('data-all_periode');

			jQuery.ajax({
				url: '<?= site_url('Laporan/Lap_DPPD/cek_pdf_dppd') ?>',
				type: 'POST',
				dataType: 'html',
				cache : false,
				data: {
					JobNo: JobNo,
					Alokasi: Alokasi,
					dari_tgl: dari_tgl,
					sampai_tgl: sampai_tgl,
					all_periode: all_periode,
				},
				beforeSend : function(){
					$('.btn_create_pdf').attr('disabled',true).hide();
					$('#loading_pdf').show();
				},
				complete: function(xhr, textStatus) {
					$('.btn_create_pdf').attr('disabled',false).show();
					$('#loading_pdf').hide();
				},
				success: function(data, textStatus, xhr) {
					// window.locat
					// alert(data);
					var myurl = "<?= site_url('Laporan/Lap_DPPD/createPDF_RptDPPD') ?>";
					window.open(myurl, '_blank');
				},
				error: function(xhr, textStatus, errorThrown) {
					$.toast({
						text: 'Terjadi Kesalahan',
						position: 'top-right',
						stack: false,
						hideAfter: 3000,
						icon: 'error',
					});
					return false;
				}
			});

		});

	});

	
</script> -->