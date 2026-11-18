

<div class="col-xs-12 row" style="padding-bottom:10px;">
    <table width="100%" border="0" style="border-collapse: collapse;" >
        <tr>
            <td class="atur" style="font-weight: bold;text-decoration: underline;">Permintaan Dana / Pertanggungjawaban Dana</td>
            <td style="font-size:9px;text-align: right;">
                <small>Printed On &nbsp;&nbsp;&nbsp; <?= date('d-M-Y') ?> &nbsp;&nbsp;&nbsp; <?= date('H:m') ?>&nbsp;&nbsp;&nbsp; Page {PAGENO} of {nbpg}</small>
            </td>
        </tr>
    </table>
</div>

<div class="row" style="border:0px solid black;">

    <div class="col-xs-4" >

        <table width="100%" border="0" style="border-collapse: collapse;" class="atur_lain">
            <tr >
                <td width="15%" class="atur_jarak">*No.PD : </td>
                <td style="border-bottom: 1px solid black;" ><?=$dataPD['NoPD']?></td>
                <td width="22%" style="padding-left:15px;">No. Ref Lap : </td>
                <td style="border-bottom: 1px solid black;"><?=($dataPD['NoRef'] == '' OR $dataPD['NoRef'] == NULL ) ? '-' : $dataPD['NoRef']  ?></td>

            </tr>


            <tr>
                <td width="15%"  class="atur_jarak">*Job No : </td>
                <td style="border-bottom: 1px solid black;"><?=$dataPD['JobNo']?></td>
                <td width="22%" style="padding-left:15px;">*Tgl Permintaan : </td>
                <td style="border-bottom: 1px solid black;"><?=tgl_baru_tahun_pendek($dataPD['TglPD'])?></td>

            </tr>
        </table>

        <table width="100%" border="0" style="border-collapse: collapse;" class="atur_lain">
            <tr>
                <td width="15%" class="atur_jarak">*Nm Proyek : </td>
                <td style="border-bottom: 1px solid black;"><?=$dataPD['JobNm']?></td>

            </tr>
            <tr>
                <td width="15%"  class="atur_jarak">*Deskripsi : </td>
                <td style="border-bottom: 1px solid black;"><?=($dataPD['Deskripsi'] == '' OR $dataPD['Deskripsi'] == NULL ) ? '-' : $dataPD['Deskripsi']  ?></td>

            </tr>
        </table>

        <table width="100%" border="0" style="border-collapse: collapse;" class="atur_lain">
            <tr >
                <td width="15%" class="atur_jarak">*Periode : </td>
                <td style="border-bottom: 1px solid black;" ><?=($dataPD['PrdAwal'] == '' OR $dataPD['PrdAwal'] == NULL ) ? '-' : tgl_baru_tahun_pendek($dataPD['PrdAwal'])  ?></td>
                <td width="20%" style="padding-left:15px;">s.d : </td>
                <td style="border-bottom: 1px solid black;"><?=($dataPD['PrdAkhir'] == '' OR $dataPD['PrdAkhir'] == NULL ) ? '-' : tgl_baru_tahun_pendek($dataPD['PrdAkhir'])  ?></td>

            </tr>


            <tr>
                <td width="15%"  class="atur_jarak">*Minggu Ke : </td>
                <td style="border-bottom: 1px solid black;"><?=($dataPD['Minggu'] == '' OR $dataPD['Minggu'] == NULL ) ? '-' : $dataPD['Minggu']  ?></td>
                <td width="20%" style="padding-left:15px;">*Alokasi : </td>
                <td style="border-bottom: 1px solid black;"><?=($dataPD['Alokasi'] == '' OR $dataPD['Alokasi'] == NULL ) ? '-' : $dataPD['Alokasi']  ?></td>

            </tr>
        </table>

       

    </div>

    <div class="col-xs-6 row">

        <table width="100%" border="1" style="border-collapse: collapse;" class="atur">

            <tr>
                <td style="width:100%;background-color: #bdc3c7;text-align: center;font-weight: bold;" >Bayar Kepada </td>
            </tr>



        </table>

        <table width="100%" border="0" style="border-collapse: collapse;" class="atur_lain">

            <tr >
                <td width="20%" class="atur_jarak" >*No. Kontrak/PO : </td>
                <td style="border-bottom: 1px solid black;" ><?=($dataPD['NoKO'] == '' OR $dataPD['NoKO'] == NULL ) ? '-' : $dataPD['NoKO']  ?></td>
                <td width="22%" style="padding-left:15px;">*Nama : </td>
                <td style="border-bottom: 1px solid black;"><?=($dataPD['Nama'] == '' OR $dataPD['Nama'] == NULL ) ? '-' : $dataPD['Nama']  ?></td>


            </tr>


            <tr>
                <td width="20%"  class="atur_jarak">No Tagihan : </td>
                <td style="border-bottom: 1px solid black;"><?=($dataPD['NoTagihan'] == '' OR $dataPD['NoTagihan'] == NULL ) ? '-' : $dataPD['NoTagihan']  ?></td>
                <td width="22%" style="padding-left:15px;">*Alamat : </td>
                <td style="border-bottom: 1px solid black;"><?=($dataPD['Alamat'] == '' OR $dataPD['Alamat'] == NULL ) ? '-' : $dataPD['Alamat']  ?></td>

            </tr>

            <tr>
                <td width="20%"  class="atur_jarak">ID : </td>
                <td style="border-bottom: 1px solid black;">-</td>
                <td width="22%" style="padding-left:15px;">*Telepon : </td>
                <td style="border-bottom: 1px solid black;"><?=($dataPD['Alamat'] == '' OR $dataPD['Alamat'] == NULL ) ? '-' : $dataPD['Alamat']  ?></td>

            </tr>

            <tr>
                <td width="20%" class="atur_jarak" style=""></td>
                <td></td>
                <td width="22%" style="padding-left:15px;padding-top: 5px;">*NPWP : </td>
                <td style="border-bottom: 1px solid black;"><?=($dataPD['NPWP'] == '' OR $dataPD['NPWP'] == NULL ) ? '-' : $dataPD['NPWP']  ?></td>

            </tr>



        </table>


    </div>

   <div class="col-xs-1" >
        <table width="100%" border="0">
            <tr>
                <td style="vertical-align: middle;text-align: center;font-size: 10px;vertical-align: bottom;" height="30px">Tipe Form</td>

            </tr>

            <tr>
                <td style="vertical-align: middle;text-align: center;font-size: 12px;">
                    <!-- <?=$dataPD['TipeForm']?> -->
                    <table border="1"  style="border-collapse:collapse;">
                        <tr>
                            <td width="50px" height="30px" style="font-weight: bold;"><?=$dataPD['TipeForm']?> </td>
                        </tr>
                    </table>        
                </td>
                
            </tr>
        </table>

    </div>


</div>
