<div class="row">

    <div class="col-xs-6 row ">
        <span style="text-decoration: underline;color:red;">**Harap mencantumkan tanda tangan & nama jelas</span>
    </div>
    <div class="col-xs-6 row">
        <span style="text-decoration: underline;color:red;padding-left:5px;">**Harap mencantumkan tanda tangan & nama jelas</span>
    </div>

    <div class="col-xs-6 row ">

        <div class="col-xs-3 row">

            <table border="1" style="border-collapse: collapse;" width="100%">
                <tr>
                    <td colspan="2" class="text-center">Dibuat Oleh**</td>
                </tr>
                <tr>
                    <td class="text-center" style="vertical-align:bottom;padding-top:50px;width:80px;">Ad. Keu Project</td>
                    <td class="text-center" style="vertical-align:bottom;padding-top:50px;width:80px;">Ka. Pro</td>
                </tr>
            </table>
        </div>

        <div class="col-xs-3 ">
            <table border="1" width="100%" style="border-collapse: collapse;">
                <tr>
                    <td colspan="2" style="text-align:center;">Diverifikasi Oleh**</td>

                </tr>
                <tr>
                    <td class="text-center" style="vertical-align:bottom;padding-top:50px;width:80px;">Project Control</td>
                    <td class="text-center" style="vertical-align:bottom;padding-top:50px;width:80px;">Keuangan</td>
                </tr>
            </table>
        </div>

        <div class="col-xs-3 row">
            <table border="1" width="100%" style="border-collapse: collapse;">
                <tr>
                    <td colspan="2" style="text-align:center;">Disetujui Oleh**</td>

                </tr>
                <tr>
                    <td class="text-center" style="vertical-align:bottom;padding-top:50px;width:80px;">Dir. Pmr & Opr</td>
                    <td class="text-center" style="vertical-align:bottom;padding-top:50px;width:80px;">Dir. Keu</td>
                </tr>
            </table>
        </div>

        <div class="col-xs-2 row" style="padding-left:5px;padding-right:5px;">
            <table border="1" width="100%" style="font-size:8px;border-collapse: collapse;">
                <tr>
                    <td style="text-align:center;">Dibayar Oleh**</td>

                </tr>
                <tr>
                    <td class="text-center" style="vertical-align:bottom;padding-top:35px;width:80px;height: 50px;">Kasir</td>
                </tr>
            </table>
        </div>


    </div>

    <div class=" col-xs-6 row">

        <div class="col-xs-3 row" style="padding-left:5px;">

            <table border="1" width="100%" style="border-collapse: collapse;">
                <tr>
                    <td colspan="2" class="text-center">Dibuat Oleh**</td>
                </tr>
                <tr>
                    <td class="text-center" style="vertical-align:bottom;padding-top:50px;width:80px;">Ad. Keu Project</td>
                    <td class="text-center" style="vertical-align:bottom;padding-top:50px;width:80px;">Ka. Pro</td>
                </tr>
            </table>
        </div>

        <div class="col-xs-3 ">
            <table border="1" width="100%" style="border-collapse: collapse;">
                <tr>
                    <td colspan="2" style="text-align:center;">Diverifikasi Oleh**</td>

                </tr>
                <tr>
                    <td class="text-center" style="vertical-align:bottom;padding-top:50px;width:80px;">Project Control</td>
                    <td class="text-center" style="vertical-align:bottom;padding-top:50px;width:80px;">Keuangan</td>
                </tr>
            </table>
        </div>

        <div class="col-xs-3 row">
            <table border="1" width="100%" style="border-collapse: collapse;">
                <tr>
                    <td colspan="2" style="text-align:center;">Disetujui Oleh**</td>

                </tr>
                <tr>
                    <td class="text-center" style="vertical-align:bottom;padding-top:50px;width:80px;">Dir. Pmr & Opr</td>
                    <td class="text-center" style="vertical-align:bottom;padding-top:50px;width:80px;">Dir. Keu</td>
                </tr>
            </table>
        </div>

        <div class="col-xs-2 row" style="padding-left:5px;">
            <table border="1" width="100%" style="font-size:8px;" style="border-collapse: collapse;">
                <tr>
                    <td style="text-align:center;font-size:8px;">Dibayar Oleh**</td>

                </tr>
                <tr>
                    <td class="text-center" style="vertical-align:bottom;padding-top:35px;width:80px;font-size:8px;">Kasir</td>
                </tr>
            </table>
        </div>

    </div>

</div>

<div class="row ">

    <div class="col-xs-6 row" style="padding-top:5px;">

        <table width="100%">
            <tr>
                <th class=" text-center bg-info">Detail Pembayaran</th>
            </tr>
            <tr>
                <td class="text-center">*Tgl Pembayaran:  <span><?=TanggalSaya($tglBayarPJ)?></span> </td>
            </tr>
        </table>

        <table width="100%" style="border-collapse: collapse;" style="font-size:8px;">

            <tr>
                <th class=" text-center bg-info" colspan="7">Pembayaran Via</th>
            </tr>

            <tr>
                <td style="width: 80px;"><label><input type="checkbox" class="form-control" <?=($pj_bayar['CaraBayar'] == 'CASH') ? 'checked="checked"' : '' ?>  ><i class="fa fa-checked"></i> Tunai Setor</label>
                </td>
                <td colspan="2" style="border-right:2px solid #7f8c8d;">Sumber Kas : </td>
                <td width="100px" style="border-right:2px solid #7f8c8d">
                    <label><input type="checkbox" class="form-control" <?=($pj_bayar['CaraBayar'] == 'TRF') ? 'checked="checked"' : '' ?>> Transfer</label>
                </td>
                <td colspan="3">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="form-control" <?=($pj_bayar['CaraBayar'] == 'CG') ? 'checked="checked"' : '' ?>> Cheque/Giro
                        </label>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="form-control" <?=($pj_bayar['SumberKas'] == 'JKT') ? 'checked="checked"' : '' ?>> JKT
                        </label>
                    </div>
                </td>
                <td>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="form-control" <?=($pj_bayar['SumberKas'] == 'MKS') ? 'checked="checked"' : '' ?> > MKS
                        </label>
                    </div>
                </td>
                <td style="border-right:2px solid #7f8c8d;">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="form-control" <?=($pj_bayar['SumberKas'] == 'LAP') ? 'checked="checked"' : '' ?>> LAP
                        </label>
                    </div>
                </td>

                <td style="border-right:2px solid #7f8c8d">Jenis Trf : </td>
                <td style="width:75px">No. Cheque/Giro : </td>
                <td colspan="2">-</td>

            </tr>

            <tr>
                <td>Penerima : </td>
                <td style="border-right:2px solid #7f8c8d" colspan="2"><?=($pj_bayar['CaraBayar'] == 'CASH') ? $pj_bayar['NmPenerimaTunai'] : '' ?></td>
                <td style="border-right:2px solid #7f8c8d"><?=($pj_bayar['CaraBayar'] == 'TRF') ? $pj_bayar['JenisTrf'] : '' ?></td>
                <td>Tgl. Cheque/Giro : <?=($pj_bayar['CaraBayar'] == 'CG') ? TanggalSaya($pj_bayar['TglCG']) : '' ?> </td>
                <td colspan="2"><?=($pj_bayar['CaraBayar'] == 'CG') ? $pj_bayar['NoCG'] : '' ?></td>
            </tr>

        </table>

        <table width="100%" style="font-size:8px;">

            <tr class="text-center">
                <th style="width:30% !important;" class="text-center bg-info" colspan="2">Rekening Pengirim</th>
                <th style="width:70% !important;" class="text-center bg-info" colspan="2">Rekening Penerima</th>
            </tr>
            <?php
            $NoRek_Kirim = '';
            $AtasNama_Kirim = '';
            $BankKirim = '';

            $RekId = $pj_bayar['RekId'];
            if ($pj_bayar['CaraBayar'] == 'TRF' ) {
                    $query  = $this->db->query("SELECT * FROM Rekening WHERE RekId='$RekId'  ")->row_array();
                    $NoRek_Kirim = $query['NoRek'];
                    $AtasNama_Kirim = $query['AtasNama'];
                    $BankKirim = $query['Bank'];
            }
            ?>
            <tr>
                <td style="width:10% !important;">No. Rek : </td>
                <td style="border-bottom:1px solid black;"><?=$NoRek_Kirim?></td>
                <td style="width:10% !important;">No. Rek</td>
                <td style="border-bottom:1px solid black;"><?= $data['NoRek'] ?></td>
            </tr>


            <tr>
                <td style="width:10% !important;">Atas Nama : </td>
                <td style="border-bottom:1px solid black;"><?=$AtasNama_Kirim?></td>
                <td style="width:10% !important;">Atas Nama</td>
                <td style="border-bottom:1px solid black;"><?= strtoupper($data['AtasNama']) ?></td>
            </tr>

            <tr>
                <td style="width:10% !important;">Bank</td>
                <td style="border-bottom:1px solid black;"><?=$BankKirim?></td>
                <td style="width:10% !important;">Bank</td>
                <td style="border-bottom:1px solid black;"><?= strtoupper($data['Bank']) ?></td>
            </tr>

        </table>

    </div>

    <div class="col-xs-6 row" style="padding-top:5px;">

        <table width="100%" style="font-size:9px;padding-left:5px;">

            <tr>
                <th colspan="2" class="text-left bg-info">Bukti Pendukung</th>
            </tr>

            <tr>
                <td>
                    <input type="checkbox" class="form-control form-control-xs" <?= (CekBuktiPendukung($data['BuktiPendukung'], 'INV') == TRUE) ? 'checked="checked"' : '' ?>></i><span> Invoice/Kwitansi</span>
                </td>
                <td>
                    <input type="checkbox" class="form-control form-control-xs" <?= (CekBuktiPendukung($data['BuktiPendukung'], 'FP') == TRUE) ? 'checked="checked"' : '' ?>><span> Faktur Pajak</span>
                </td>
            </tr>

            <tr>
                <td>
                    <input type="checkbox" class="form-control form-control-xs" <?= (CekBuktiPendukung($data['BuktiPendukung'], 'SJ') == TRUE) ? 'checked="checked"' : '' ?>><span> Surat Jalan/Tanda Terima Lapangan</span>
                </td>
                <td>
                    <input type="checkbox" class="form-control form-control-xs" <?= (CekBuktiPendukung($data['BuktiPendukung'], 'BAP') == TRUE) ? 'checked="checked"' : '' ?>><span> Berita Acara Pembayaran</span>
                </td>
            </tr>

            <tr>
                <td>
                    <input type="checkbox" class="form-control form-control-xs" <?= (CekBuktiPendukung($data['BuktiPendukung'], 'PO') == TRUE) ? 'checked="checked"' : '' ?>><span> Copy PO</span>
                </td>
                <td>
                    <input type="checkbox" class="form-control form-control-xs" <?= (CekBuktiPendukung($data['BuktiPendukung'], 'BAOP') == TRUE) ? 'checked="checked"' : '' ?>><span> Berita Acara Opname Pekerjaan</span>
                </td>
            </tr>

            <tr>
                <td colspan="2" height="100px" style="vertical-align:bottom;text-align: right;">
                    <div class="box">
                        <div class="box-body">
                            <h6 style="padding-right:50px;"><b><?= $data['NoPD'] ?></b></h6>
                        </div>
                    </div>

                </td>
            </tr>

        </table>
    </div>
</div>