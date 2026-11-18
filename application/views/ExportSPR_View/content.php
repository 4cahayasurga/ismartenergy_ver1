<?php

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
$stylesheet_new = file_get_contents('mycss/spr_css.css');


$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$mpdf->SetTitle('SURAT PERMINTAAN MATERIAL/ALAT || '.$this->config->item('Company'));
$mpdf->use_kwt = true;


$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($stylesheet_new, \Mpdf\HTMLParserMode::HEADER_CSS);

$mpdf->SetHTMLHeader($this->load->view('ExportSPR_View/header.php', NULL, TRUE), 'O');
$mpdf->SetHTMLFooter($this->load->view('ExportSPR_View/footer.php', NULL, TRUE));



$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$nor = 1;
$html = '';

// batas
$no = 1;

$html .='
<table width="100%" border="0" style="border-collapse:collapse;">
	<thead>
	<tr>
		<td style="text-align:center;width:5%;border-bottom:1px solid;padding-bottom:5px;" class="huruf12">No</td>
		<td style="width:11%;border-bottom:1px solid;padding-bottom:5px;" class="huruf12">Kode RAP</td>
		<td class="huruf12" style="border-bottom:1px solid;padding-bottom:5px;">Uraian</td>
		<td style="text-align:right;width:11%;border-bottom:1px solid;padding-bottom:5px;" class="huruf12">Vol</td>
		<td style="text-align:center;width:8%;border-bottom:1px solid;padding-bottom:5px;" class="huruf12">Satuan</td>
	</tr>
	<thead>
	<tbody>';

foreach($detailSPR as $row) :

$html.='
<tr>
	<td style="text-align:center;padding-top:2px;padding-bottom:2px;vertical-align:top;" class="huruf12">'.$row->NoUrut.'</td>
	<td class="huruf12" style="padding-top:2px;padding-bottom:2px;vertical-align:top;">'.$row->KdRAP.'</td>
	<td class="huruf12" style="padding-top:2px;padding-bottom:2px;vertical-align:top;">'.$row->Uraian.'</td>
	<td style="text-align:right;padding-top:2px;padding-bottom:2px;vertical-align:top;" class="huruf12">'.UangTerbaru($row->Vol).'</td>
	<td style="text-align:center;padding-top:2px;padding-bottom:2px;vertical-align:top;" class="huruf12">'.$row->Uom.'</td>
</tr>';

endforeach;

$html .='
	</tbody>
</table>';



$mpdf->WriteHTML($html);
$mpdf->Output('SURAT PERMINTAAN MATERIAL - ' . date('ymdHis') . '.pdf', 'I');
