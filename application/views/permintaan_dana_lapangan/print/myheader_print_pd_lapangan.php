<div class="row">

    <table id="mytable">
        <tr>
            <th style="text-align: left;text-decoration:underline;">Permintaan Dana / Pertanggungjawab Dana</th>
            <th style="text-align: right;"><?= $gabung ?> {PAGENO} of {nbpg}</th>
        </tr>
    </table>
</div>
<br>

<div class="row">

    <div class="col-xs-5 row">

        <table class="row" id="iniTable">

            <tr>
                <td class="iniTD"><span>*</span>No. PD : </td>
                <td class="garis"><?= $data['NoPD'] ?></td>
                <td class="iniTDBawah">No. Ref. Lap : </td>
                <td class="garis"><?= $data['NoRef'] ?></td>
            </tr>

            <tr>
                <td class="iniTD"><span>*</span>Job No : </td>
                <td class="garis"><?= $data['JobNo'] ?></td>
                <td><span>*</span>Tgl Permintaan : </td>
                <td class="garis"><?= TglPrint($data['TglPD']) ?></td>
            </tr>

            <tr>
                <td class="iniTD"><span>*</span>Nm Proyek : </td>
                <td colspan="3" class="garis"><?= $data['NmProyek'] ?></td>
            </tr>

            <tr>
                <td class="iniTD"><span>*</span> Deskripsi : </td>
                <td colspan="3" class="garis"><?= $data['Deskripsi'] ?></td>
            </tr>

            <tr>
                <td class="iniTD"><span>*</span>Periode : </td>
                <td class="garis"><?= TglPrint($data['PrdAwal']) ?></td>
                <td>s.d. : </td>
                <td class="garis"><?= TglPrint($data['PrdAkhir']) ?></td>
            </tr>

            <tr>
                <td class="iniTD"><span>*</span>Minggu ke : </td>
                <td class="garis"><?= $data['Minggu'] ?></td>
                <td><span>*</span>Alokasi</td>
                <td class="garis"><?= $data['Alokasi'] ?></td>
            </tr>


        </table>
    </div>

    <div class="col-xs-5 row">
        <table width="100%" style="padding-top:2px;padding-bottom:2px;">

            <tr>
                <th colspan="4" class="text-dark text-center bg-info">Bayar Kepada</th>
            </tr>

            <tr>
                <td width="20%"><span>*</span>No.Kontrak/PO : </td>
                <td class="garis"><?= $data['NoKO'] ?></td>
                <td><span>*</span>Nama : </td>
                <td class="garis"><?= strtoupper($data['Nama']) ?></td>
            </tr>

            <tr>
                <td>No. Tagihan : </td>
                <td class="garis"><?= $data['NoTagihan'] ?></td>
                <td><span>*</span>Alamat : </td>
                <td class="garis"><?= $data['Alamat'] ?></td>
            </tr>

            <tr>
                <td>ID : </td>
                <td class="garis">-</td>
                <td><span>*</span>Telepon : </td>
                <td class="garis"><?= $data['Telepon'] ?></td>
            </tr>

            <tr>
                <td></td>
                <td></td>
                <td>NPWP : </td>
                <td class="garis"><?= $data['NPWP'] ?></td>
            </tr>

            <tr>
                <td colspan="2" height="20px" style="vertical-align:bottom;"><span>*</span>No. PJ : <span style="border-bottom:1px solid black;width:20%;"><?=($NoPJ_cek == 0) ? '' : $data['NoPJ']   ?></span></td>
                <td colspan="2" height="20px" style="vertical-align:bottom;"><span>*</span>Tgl. PJ : <span style="border-bottom:1px solid black;width:20%;"><?=($NoPJ_cek == 0) ? '' : TanggalSaya($data['TglPJ'])   ?></span></td>
            </tr>


        </table>
    </div>

    <div class="col-xs-1 row" style="padding-left:3px;">



        <table width="100%">
            <tr>
                <td class="text-center" style="height:30px;vertical-align: bottom;">Tipe Form</td>
            </tr>
            <tr>
                <td class="text-center" style="font-size:12px;border:1px solid black;height: 50px;"><b><?= $data['TipeForm'] ?></b></td>
            </tr>
        </table>
        <!-- <div class="box" style="vertical-align: middle;vertical-align: middle !important;">
			<div class="box-header">
				<div class="box-title"><?= $data['TipeForm'] ?></div>
			</div>
		</div> -->

    </div>

</div>