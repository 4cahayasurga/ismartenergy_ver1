<?php
ini_set("pcre.backtrack_limit", "5000000");
$mpdfConfig = array(
	'mode' => 'utf-8',
	'format' => 'A4',
	'orientation' => 'L',
	'setAutoBottomMargin' => 'stretch',
	'setAutoTopMargin' => 'stretch',
	'margin-left' => 0,
	'margin-right' => 0,

);
$stylesheet = file_get_contents('css/bootstrap-3.3.7.min.css');
$stylesheet_new = file_get_contents('mycss/dppd_baru.css');



$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$mpdf->SetTitle('REPORT (PD/PJ) - LAPORAN DPPD || '.$this->Company.' ');
$mpdf->use_kwt = true;


$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($stylesheet_new, \Mpdf\HTMLParserMode::HEADER_CSS);

$mpdf->SetHTMLHeader($this->load->view('laporan_view/dppd/myprint_folder/pdf_atas_dppd', NULL, TRUE), 'O');
$mpdf->SetHTMLFooter($this->load->view('laporan_view/dppd/myprint_folder/pdf_bawah_dppd', NULL, TRUE));



$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$nor = 1;
$html = '';

// batas
$no = 1;
$dari_tgl = ($dari_tgl == '') ? 0 : $dari_tgl;
$sampai_tgl = ($sampai_tgl == '') ? 0 : $sampai_tgl;
$html .='
<table class="atur" id="tbl_saya" >
	<thead>
		<tr >
			<th   >No PD</th>
			<th  >Tgl PD</th>
			<th   style="width: 10% !important;" >Deskripsi</th>
			<th  >Prd Awal</th>
			<th  >Prd Akhir</th>
			<th  >No KO</th>
			<th  >Nama</th>
			<th  style="text-align:right;">Total PD</th>
			<th    >Tgl Bayar</th>
			<th  >Via</th>
			<th   >Rek Pengirim</th>
			<th   >Rek Penerima</th>
			<th  >No PJ</th>
			<th  >Tgl PJ</th>
			<th   style="text-align:right;">Total PJ</th>
			<th  >Tipe Form</th>
		</tr>
	</thead>
	<tbody>';

	foreach($myarray as $arr) : 
		$nor = 1;
		$nor_pj = 1;

		$html.='
		<tr class="ukuran">
			<td>'.$arr['NoPD'].'</td>
			<td>'.tgl_baru($arr['TglPD']).'</td>
			<td>'.$arr['Deskripsi'].'</td>
			<td>'.tgl_baru($arr['PrdAwal']).'</td>
			<td>'.tgl_baru($arr['PrdAkhir']).'</td>
			<td>'.$arr['NoKO'].'</td>
			<td>'.$arr['Nama'].'</td>
			<td style="text-align:right;">'.uang_koma_genap_decimal($arr['TotalPD']).'</td>
			<td>'.tgl_baru($arr['TglBayar']).'</td>
			<td>'.$arr['CaraBayar'].'</td>
			<td>'.$arr['RekPenerima'].'</td>
			<td>'.$arr['RekId'].'</td>
			<td>'.$arr['NoPJ'].'</td>
			<td>'.tgl_baru($arr['TglPJ']).'</td>
			<td style="text-align:right;" >'.uang_koma_genap_decimal($arr['TotalPJ']).'</td>
			<td>'.$arr['TipeForm'].'</td>

		</tr>';

		$query_detail = $this->db->select('*, (Vol * HrgSatuan) AS TotalPD_detail ,(PjVol * PjHrgSatuan) AS TotalPJ_detail  ')->where('NoPD',$arr['NoPD'])->get('PdDtl');

		if ($query_detail->num_rows() > 0 ) :

			$html .='
			<tr>
				<td colspan="16" style="padding-top:10px;padding-bottom:10px;padding-left: 15px;">

					<table style="border-collapse: collapse;width: 100%;border:1px solid;">

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
							<td style="border-bottom:1px solid;padding-right:10px !important;" class="text-right jarak_atas_bawah" >Sub Total</td>

							<td width="50px;"></td>
							<td width="50px;"></td>

							<td style="border-bottom:1px solid;" >Uraian PJ</td>
							<td width="5px"></td>
							<td style="border-bottom:1px solid;" class="text-right">Vol</td>
							<td width="5px"></td>

							<td style="border-bottom:1px solid;" >&nbsp;&nbsp;&nbsp;Uom</td>
							<td width="5px"></td>
							<td style="border-bottom:1px solid;width: 7%;" class="text-right">Hrg Satuan</td>
							<td width="5px"></td>
							<td style="border-bottom:1px solid;padding-right:10px !important;" class="text-right">Sub Total&nbsp;&nbsp;</td>

						</tr>';

						foreach($query_detail->result() as $data_detail) : 
							$html .='

								<tr class="ukuran">
									<td width="1%" class="jarak_atas_bawah_td"></td>
									<td width="2%" class="text-center jarak_atas_bawah_td" >'.$nor++.'</td>
									<td width="5px"></td>
											<td class="jarak_atas_bawah_td">'.$data_detail->KdRAP.'</td>
											<td width="5px"></td>
											<td class="jarak_atas_bawah_td">'.$data_detail->Uraian.'</td>
											<td width="5px"></td>
											<td class="text-right jarak_atas_bawah_td">'.new_decimal($data_detail->Vol).'</td>
											<td width="5px"></td>
											<td class="jarak_atas_bawah_td" >&nbsp;&nbsp;&nbsp;'.$data_detail->Uom.'</td>
											<td width="5px"></td>
											<td class="text-right">'.uang_koma_genap_decimal($data_detail->HrgSatuan).'</td>
											<td width="5px"></td>
											<td class="text-right" style="padding-right:10px !important;">'.uang_koma_genap_decimal($data_detail->TotalPD_detail).'</td>

											<td width="50px;"></td>
											<td width="50px;"></td>


											<td >'.$data_detail->PjUraian.'</td>
											<td width="5px"></td>
											<td  class="text-right">'.new_decimal($data_detail->PjVol).'</td>
											<td width="5px"></td>
											<td  >&nbsp;&nbsp;&nbsp;'.$data_detail->Uom.'</td>
											<td width="5px"></td>
											<td class="text-right">'.uang_koma_genap_decimal($data_detail->PjHrgSatuan).'</td>
											<td width="5px"></td>
											<td style="padding-right:10px !important;" class="text-right jarak_atas_bawah_td">'.uang_koma_genap_decimal($data_detail->TotalPJ_detail).'&nbsp;&nbsp;</td>
								</tr>

							';
						endforeach;



					$html .='</table>

				</td>
			</tr>';

		endif;

	endforeach;

		
	


$html .='
	</tbody>
</table>';



$mpdf->WriteHTML($html);

$mpdf->Output('REPORT (PD/PJ) - LAPORAN DPPD - ' . date('ymdHis') . '.pdf', 'D');
