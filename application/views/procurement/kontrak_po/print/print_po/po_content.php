<?php
$mpdfConfig = array(
    'mode' => 'utf-8',
    'format' => 'A4',
    'margin_header' => 5,
    'margin_footer' => 5,
    'orientation' => 'P',
    'setAutoBottomMargin' => 'stretch',
    'setAutoTopMargin' => 'stretch',

);

$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$stylesheet = file_get_contents('css/bootstrap-3.3.7.min.css');
$stylesheet_baru = file_get_contents('po_style.css');

$Company = $this->config->item('Company');

$mpdf->SetTitle(' '.$Company.' ||  KONTRAK');
$mpdf->use_kwt = true;

$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($stylesheet_baru, \Mpdf\HTMLParserMode::HEADER_CSS);

$mpdf->SetHTMLHeader($this->load->view('procurement/kontrak_po/print/print_po/po_header', NULL, TRUE), 'O');
$mpdf->SetHTMLFooter($this->load->view('procurement/kontrak_po/print/print_po/po_footer', NULL, TRUE), 'O');

$mpdf->shrink_tables_to_fit = 1;
$batas = 0;
$nor = 1;

$html = '';

$html .= '
<div class="row atur" style="border:0px solid black;padding-top:5px;">

    <table width="100%" border="0" style="border-collapse:collapse;border-bottom:1px solid black;" class="atur">
        <thead  >
            <tr>
                <th align="center" width="5%"  style="border-bottom:1px solid black;padding-bottom:5px;">No</th>
                <th width="12%" style="padding-left:5px;"  style="border-bottom:1px solid black;padding-bottom:5px;">Kode</th>
                <th width="30%" style="padding-left:5px;"  style="border-bottom:1px solid black;padding-bottom:5px;">Uraian</th>
                <th align="right" width="7%" style="padding-right:5px;"  style="border-bottom:1px solid black;padding-bottom:5px;">Vol</th>
                <th align="left" width="7%" style="padding-left:5px;"  style="border-bottom:1px solid black;padding-bottom:5px;">Sat</th>
                <th align="center" width="20%"  style="border-bottom:1px solid black;padding-bottom:5px;">Harga Satuan (Rp)</th>
                <th align="right" width="20%"  style="border-bottom:1px solid black;padding-bottom:5px;">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody >';

$nomor_saya = 1;
$SubTotal = 0;
foreach ($detail as $data_detail) :
    $SubTotal += $data_detail['JumlahHarga'];

    $html .= '
            <tr >
                <td align="center" width="5%" style="padding-top:5px;" style="padding-bottom:5px;padding-top:5px;">' . $nomor_saya++ . '</td>
                <td width="12%" style="padding-left:5px;" style="padding-top:5px;" style="padding-bottom:5px;padding-top:5px;">' . strtoupper($data_detail['KdRAP']) . '</td>
                <td width="30%" style="padding-left:5px;" style="padding-top:5px;" style="padding-bottom:5px;padding-top:5px;">' . konversi_BR_ke_enter(strtoupper($data_detail['Uraian'])) . '</td>
                <td align="right" width="7%" style="padding-right:5px;" style="padding-top:5px;" style="padding-bottom:5px;padding-top:5px;">' . strtoupper(number_format($data_detail['Vol'])) . '</td>
                <td align="left" width="7%" style="padding-left:5px;" style="padding-top:5px;" style="padding-bottom:5px;padding-top:5px;">' . strtoupper($data_detail['Uom']) . '</td>
                <td align="center" width="20%" style="padding-top:5px;" style="padding-bottom:5px;padding-top:5px;">' . strtoupper(number_format($data_detail['HrgSatuan'])) . '</td>
                <td align="right" width="20%" style="padding-top:5px;" style="padding-bottom:5px;padding-top:5px;">' . strtoupper(number_format($data_detail['JumlahHarga'])) . '</td>
                
            </tr> ';
endforeach;

$dapat_diskon = $this->db->query(" SELECT * FROM KoHdr WHERE JobNo='$JobNo' AND NoKO='$NoKO' ")->row_array();
// print_rr($dapat_diskon);

$checked = '';

$INV = (CekBuktiPendukung($data_po['SyaratPembayaran'], 'INV') == TRUE) ? "checked='checked' " : '';
$FP = (CekBuktiPendukung($data_po['SyaratPembayaran'], 'FP') == TRUE) ? "checked='checked' " : '';
$SJ = (CekBuktiPendukung($data_po['SyaratPembayaran'], 'SJ') == TRUE) ? "checked='checked' " : '';
$PO = (CekBuktiPendukung($data_po['SyaratPembayaran'], 'PO') == TRUE) ? "checked='checked' " : '';
$BAP = (CekBuktiPendukung($data_po['SyaratPembayaran'], 'BAP') == TRUE) ? "checked='checked' " : '';
$BAOP = (CekBuktiPendukung($data_po['SyaratPembayaran'], 'BAOP') == TRUE) ? "checked='checked' " : '';
$K3 = ($data_po['K3'] == 1) ? 'checked="checked"' : '';


$SubTotal_new = 0;
$SubTotal_new = $SubTotal;
$DiscAmount = 0;
$PPN = 0;
$GrandTotal = 0;

$DiscPercentage = $dapat_diskon['DiscPercentage'] / 100;
$DiscAmount = $DiscPercentage * $SubTotal_new;
$PPN = ($SubTotal_new / ppn_baru_decimal()) * ppn_baru_angka();

if ($dapat_diskon['OverridePPN'] == 1) {
    $PPN = 0;
}

$GrandTotal = ($SubTotal_new - $DiscAmount) + $PPN;


$html .= '
        </tbody>
    </table>
    <br>
    <table width="100%" border="0" style="border-collapse:collapse;" class="atur">
        <tr>
            <th align="left" width="60%" style="padding-top:5px;font-style:italic;">Terbilang : ' . ucwords(terbilang($Hitung_GrandTotal)) . '</th>
            <td align="right" width="20%" style="padding-top:5px;">Sub Total :</td>
            <td align="right" width="20%" style="padding-top:5px;">' . number_format($TxtSubTotal) . '</td>
        </tr>
    </table>

    <table width="100%" border="0" style="border-collapse:collapse;" class="atur">
        <tr>
            <td align="right" width="80%" style="padding-top:5px;">Disc :</td>
            <td align="right" width="20%" style="padding-top:5px;">' . number_format($TxtDiscNominal) . '</td>
        </tr>
    </table>

    <table width="100%" border="0" style="border-collapse:collapse;" class="atur">
        <tr>
            <td align="right" width="80%" style="padding-top:5px;">PPN :</td>
            <td align="right" width="20%" style="padding-top:5px;">' .  number_format($TxtPPN) . '</td>
        </tr>
    </table>

    <table width="100%" border="0" style="border-collapse:collapse;" class="atur">
        <tr>
            <th align="right" width="80%" style="padding-top:5px;">Grand Total :</th>
            <th align="right" width="20%" style="padding-top:5px;" style="border-bottom:1px solid black;">' . number_format($Hitung_GrandTotal) . '</th>
        </tr>
    </table>

    <br>
    <table width="100%" border="0" style="border-collapse:collapse;" class="atur">
        <tr>
            <td style="background-color:#ecf0f1;text-align:left;font-size:10px;font-weight:bold;">Persyaratan & Sanski</td>
        </tr>
    </table>

    <div class="col-xs-6 row">
        <table width="100%" border="0" style="border-collapse:collapse;" class="atur">
            <tr>
                <td>1. Wajib memenuhi persyaratan Kesehatan & Keselamatan Kerja (K3) <input type="checkbox" class="form-control input-sm" ' . $K3 . ' ></td>
			</tr>
            <tr>
				<td style="padding-top:10px;">2. Syarat Teknis</td>
			</tr>

            <tr >
				<td style="padding-left:10px;border-bottom:1px solid black;padding-top:5px;line-height: 1.5;vertical-align:top;height:90px;" ><small style="padding-left:20px;">' . konversi_BR_ke_enter(strtoupper($data_po['SyaratTeknis'])) . '</small></td>
			</tr>

            <tr>
				<td style="padding-top:10px;">3. Jadwal Pengiriman</td>
			</tr>

            <tr>
				<td style="padding-left:10px;border-bottom:1px solid black;line-height: 1.5;vertical-align:top;height:90px;"><small style="padding-left:20px;padding-top:5px;">' . konversi_BR_ke_enter(strtoupper($data_po['JadwalPengiriman'])) . '</small></td>
			</tr>

            <tr>
				<td style="padding-top:10px;">4. Jadwal Pembayaran</td>
			</tr>

			<tr>
				<td style="padding-left:10px;border-bottom:1px solid black;line-height: 1.5;vertical-align:top;height:90px;"><small style="padding-left:20px;padding-top:5px;line-height: 1.5;">' . konversi_BR_ke_enter(strtoupper($data_po['JadwalPembayaran'])) . '</small></td>
			</tr>
    
        </table>

    </div>


    <div class="col-xs-5 row">
        <table width="100%" border="0" style="border-collapse:collapse;" class="atur">

        <tr>
            <td style="padding-top:10px;" colspan="2">5. Syarat Pembayaran</td>
        </tr>


        <tr>
            <td style="padding-top:5px;padding-bottom:5px;padding-left:11px;">
                <label><input type="checkbox" class="form-control input-sm"  style="font-size: 9px;" ' . $INV . ' > Invoice/Kwitansi</label>
            </td>
            <td>
                <label><input type="checkbox" class="form-control input-sm"  style="font-size: 9px;" ' . $FP . '> Faktur/Pajak</label>
            </td>
        </tr>

        <tr>
            <td style="padding-top:5px;padding-bottom:5px;padding-left:11px;">
                <label><input type="checkbox" class="form-control input-sm"  style="font-size: 9px;" ' . $SJ . '> Surat Jalan/Tanda Terima Lapangan</label>
            </td>
            <td>
                <label><input type="checkbox" class="form-control input-sm"  style="font-size: 9px;" ' . $PO . '> Copy PO</label>
            </td>
        </tr>

        <tr>
            <td colspan="2" style="padding-top:5px;padding-bottom:5px;padding-left:11px;">
                <label><input type="checkbox" class="form-control input-sm" style="font-size: 9px;" ' . $BAP . '> Berita Acara Pembayaran</label>
            </td>
            
        </tr>

        <tr >
            <td colspan="2" style="padding-top:5px;padding-bottom:5px;padding-left:11px;height:30px;vertical-align:top;" >
                <label><input type="checkbox" class="form-control input-sm" style="font-size: 9px;" ' . $BAOP . '> Berita Acara Opname Pekerjaan</label>

            </td>
        </tr>
        


        </table>

        <table width="100%" border="0" style="border-collapse:collapse;" class="atur">
			<tr>
				<td style="padding-top:10px;">6. Sanksi</td>
			</tr>
			<tr>
				<td style="padding-left:10px;border-bottom:1px solid black;padding-top:5px;line-height: 1.5;vertical-align:top;height:100px;"><small style="padding-left:20px;">' . konversi_BR_ke_enter(strtoupper($data_po['Sanksi'])) . '</small></td>
			</tr>

			<tr>
				<td style="padding-top:10px;">7. Keterangan Lainnya</td>
			</tr>
			<tr>
				<td style="padding-left:10px;border-bottom:1px solid black;padding-top:5px;line-height: 1.5;vertical-align:top;height:90px;"><small style="padding-left:20px;">' . konversi_BR_ke_enter(strtoupper($data_po['Keterangan'])) . '</small></td>
			</tr>

		</table>

    </div>



</div>';


$mpdf->WriteHTML($html);
// $mpdf->Output('MDH-PURCHASE-ORDER-' . date('ymdHis') . '.pdf', 'I');
$mpdf->Output(' '.$Company.' -PURCHASE-ORDER-' . date('ymdHis') . '.pdf', 'I');
