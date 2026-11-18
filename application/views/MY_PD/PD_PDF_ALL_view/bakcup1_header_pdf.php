
<table border="0" width="100%" style="border-collapse: collapse;" class="ukuran"  >
    <tr>
        <td class="untuk_teks_pd" style="font-weight:bold;text-decoration: underline;">Permintaan Dana / Pertanggungjawaban Dana</td>
        <td class="text-right"><?= $gabung ?> Page {PAGENO} of {nbpg}</td>
    </tr>
</table>
<p></p>

<div class="col-xs-4 row"  >
    <table border="0" width="100%" style="border-collapse: collapse;" class="ukuran"  >
        <tr>
            <td class="padding_atas ukuran" width="18%">*No PD :</td>
            <td class="padding_atas border_bawah_tampil ukuran"><?=$dataPD['NoPD']?></td>
            <td class="padding_atas padding_kiri ukuran" width="25%">No. Ref Lap :</td>
            <td class="padding_atas border_bawah_tampil ukuran"><?=$dataPD['NoRef']?></td>
        </tr>
        <tr>
            <td class="padding_atas ukuran" width="18%">*Job No :</td>
            <td class="padding_atas border_bawah_tampil ukuran"><?=$dataPD['JobNo']?></td>
            <td class="padding_atas padding_kiri ukuran" width="25%">*Tgl Permintaan :</td>
            <td class="padding_atas border_bawah_tampil ukuran"><?=TanggalSaya($dataPD['TglPD'])?></td>
        </tr>
    </table>

    <table border="0" width="100%" style="border-collapse: collapse;" class="ukuran"  >
        <tr>
            <td class="ukuran" width="18%">*Nm Proyek :</td>
            <td class="padding_atas border_bawah_tampil ukuran"><?=$JobNm?></td>
        </tr>
    </table>

    <table border="0" width="100%" style="border-collapse: collapse;" class="ukuran"  >
        <tr>
            <td class="padding_atas ukuran" width="18%">*Periode :</td>
            <td class="padding_atas border_bawah_tampil ukuran"><?=TanggalSaya($dataPD['PrdAwal'])?></td>
            <td class="padding_atas padding_kiri ukuran" width="25%" >s.d</td>
            <td class="padding_atas border_bawah_tampil ukuran"><?=TanggalSaya($dataPD['PrdAkhir'])?></td>
        </tr>
        <tr>
            <td class="padding_atas ukuran" width="18%">*Minggu :</td>
            <td class="padding_atas border_bawah_tampil ukuran"><?=$dataPD['Minggu']?></td>
            <td class="padding_atas padding_kiri ukuran" width="25%">*Alokasi :</td>
            <td class="padding_atas border_bawah_tampil ukuran"><?=$dataPD['Alokasi']?></td>
        </tr>
    </table>

</div>

<div class="col-xs-6" >

    <table border="0" width="100%" style="border-collapse: collapse;" class="ukuran"  >
        <tr>
            <td class="text-center" style="background-color:#95a5a6;font-weight: bold;">Bayar Kepada</td>
        </tr>

    </table>

    <table border="0" width="100%" style="border-collapse: collapse;" class="ukuran"  >
        <tr>
            <td class="padding_atas ukuran" width="16%">*No. Kontrak/PO :</td>
            <td class="padding_atas border_bawah_tampil ukuran" ><?=$dataPD['NoKO']?></td>
            <td class="padding_atas padding_kiri ukuran" width="12%" >*Nama :</td>
            <td class="padding_atas border_bawah_tampil ukuran"><?=$dataPD['Nama']?></td>
        </tr>

        <tr>
            <td class="padding_atas ukuran" width="16%">No. Tagihan :</td>
            <td class="padding_atas border_bawah_tampil ukuran" ><?=($dataPD['NoTagihan'] == '' || $dataPD['NoTagihan'] == null ) ? '-' :  $dataPD['NoTagihan'] ?></td>
            <td class="padding_atas padding_kiri ukuran" width="12%" >*Alamat :</td>
            <td class="padding_atas border_bawah_tampil ukuran"><?=$dataPD['Alamat']?></td>
        </tr>

        <tr>
            <td class="padding_atas ukuran" width="16%">ID :</td>
            <td class="padding_atas border_bawah_tampil ukuran" >-</td>
            <td class="padding_atas padding_kiri ukuran" width="12%" >*Telepon :</td>
            <td class="padding_atas border_bawah_tampil ukuran"><?=$dataPD['Telepon']?></td>
        </tr>

        <tr>
            <td class="padding_atas ukuran" width="16%"></td>
            <td class="padding_atas  ukuran" ></td>
            <td class="padding_atas padding_kiri ukuran" width="12%" >NPWP :</td>
            <td class="padding_atas border_bawah_tampil ukuran"><?=($dataPD['NPWP'] == '' || $dataPD['NPWP'] == null ) ? '-' : $dataPD['NPWP']  ?></td>
        </tr>

    </table>

    <table border="0" width="100%" style="border-collapse: collapse;" class="ukuran"  >
        <tr>
            <td width="16%"></td>
            <td class="padding_atas ukuran" width="8%">*No. PJ :</td>
            <td class="padding_atas border_bawah_tampil ukuran" ><?=($dataPD['NoPJ'] == '' || $dataPD['NoPJ'] == null ) ? '-' : $dataPD['NoPJ']  ?></td>
            <td class="padding_atas ukuran" width="8%">*Tgl PJ :</td>
            <td class="padding_atas border_bawah_tampil ukuran" ><?=($dataPD['NoPJ'] == '' || $dataPD['NoPJ'] == null ) ? '-' : ($dataPD['TglPJ'] == '' || $dataPD['TglPJ'] == null ) ? '-' : TanggalSaya($dataPD['TglPJ'])  ?></td>
        </tr>
    </table>

</div>

<div class="col-xs-1">
    <table width="80%" border="0">
        <tr> 
            <td class="text-center untuk_tipe_form" style="height:40px;vertical-align: bottom;">Tipe Form</td>
        </tr>
        <tr>
            <td class="text-center" style="font-size:12px;border:1px solid black;height: 30px;width: 30px;"><b><?= $dataPD['TipeForm'] ?></b></td>
        </tr>
    </table>
</div>


<!-- <table><tr><td></td></tr></table>
<table><tr><td></td></tr></table>

<table border="0" width="100%" class="ukuran">
    <tr>
        <td width="5%" >Job No</td>
        <td width="1%" align="center" >:</td>
        <td style="border-bottom: 1px solid black;padding-left:5px;" class="ukuran"><?=$JobNo.' - '.$JobNm ?></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    
</table>

<table border="0" width="100%" class="ukuran" >
    <tr>
        <td width="5%" >Alokasi</td>
        <td width="1%" align="center" >:</td>
        <td  width="2%" style="border-bottom: 1px solid black;padding-left:5px;"><?=$Alokasi?></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    
</table>


<table style=""><tr><td></td></tr></table>
-->
