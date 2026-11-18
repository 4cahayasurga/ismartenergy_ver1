<style type="text/css">
    
    .ubah_ukuran {
        font-size: 10px !important;
        font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;

    }

   /* table .ubah_ukuran tbody tr td{
         margin-top: 10px !important;
    }*/

    .atur_th{
        padding-top: 10px !important;
        padding-bottom: 10px !important;
        vertical-align: middle;
    }

    #initable thead tr th {
        padding-top: 10px !important;
        padding-bottom: 10px !important;
        vertical-align: middle;
    }

    #initable tbody tr td {
        padding-top: 7px !important;
        padding-bottom: 7px !important;
        vertical-align: middle;
    }

   /* th {
        white-space: nowrap;
    }*/
</style>
<?php
$dari_tgl = ($dari_tgl == '') ? 0 : $dari_tgl;
$sampai_tgl = ($sampai_tgl == '') ? 0 : $sampai_tgl;
?>
<div class="box">

    <div class="box-body">


        <div class="table-responsive">

            <table border="0" width="100%" style="margin-bottom:10px !important;" >
                <td class="text-left">
                    <a href="<?= site_url('Laporan/Rkp_pembayaran/print_rkp_pembayaran/' . $JobNo . '/' . $dari_tgl . '/' . $sampai_tgl) ?>" target="_blank" class="btn btn-md btn-danger btn_print ubah_ukuran">PRINT PDF <i class="fa fa-print"></i></a>

                    <a href="<?= site_url('Laporan/Rkp_pembayaran/myexcel_RkpPembayaran/' . $JobNo . '/' . $dari_tgl . '/' . $sampai_tgl) ?>" target="_blank" class="btn btn-md btn-success ubah_ukuran">EXPORT EXCEL <i class="fa fa-file-excel-o"></i></a>

                </td>
                <td class="text-right " style="font-size: 18px !important;border-bottom:1px solid;background-color:lightgray;" width="8%"   ><?=uang_koma_genap_decimal($Ttl_bayar)?></td>
                <td width="50%"></td>
            </table>

            <table id="initable" border="1" width="100%" style="border-collapse: collapse;" class="ubah_ukuran table table-striped table-hover table-condensed table-bordered " >
                <thead>
                    <tr>

                        <th class="text-center"></th>
                        <th class="text-center"></th>
                        <th class="text-center">No</th>
                        <th>Tanggal</th>
                        <th>Form</th>
                        <th>No PD</th>
                        <th>Job Name</th>
                        <th>Nama Penerima</th>
                        <th>Rekening Penerima</th>
                        <th>Bank Penerima</th>
                        <th>Nilai Transfer</th>
                        <th>Bank Pengirim</th>
                        <th>No Cek/BG</th>
                        <th>Rekening Pengirim</th>
                        <th>No Kontrak/PO</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    foreach ($data_rkp as $mydata) :


                        ?>

                        <tr>
                            <td class="text-right">
                                <input type="text"  style="height:30px;width: 60px;">
                            </td>
                            <td class="text-left">
                                <input type="text"  style="height:30px;width: 60px;">
                            </td>
                            <td class="text-center"  ><?= $mydata['norut'] ?></td>
                            <td><?= date('d-M', strtotime($mydata['TglBayar'])) ?></td>
                            <td><?= $mydata['TipeForm'] ?></td>
                            <td><?= $mydata['NoPD'] ?></td>
                            <td><?= $mydata['JobNm'] ?></td>
                            <td><?= $mydata['NamaTerima']?></td>
                            <td><?= $mydata['NoRekTerima'];?></td>
                            <td><?= $mydata['BankTerima']; ?></td>
                            <td><?= uang_koma_genap_decimal($mydata['Amount']) ?></td>
                            <td><?= $mydata['BankKirim']; ?></td>
                            <td><?= $mydata['JenisTrf'] ?></td>
                            <td><?= $mydata['NoRekKirim']; ?></td>
                            <td><?= $mydata['NoKO'] ?></td>
                            <td><?= $mydata['Keterangan'] ?></td>
                        </tr>


                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td>Dibuat</td>
                        <td>Diperiksa</td>
                        <td>Disetujui</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="text"  style="height:30px;width: 60px;"></td>
                        <td><input type="text"  style="height:30px;width: 60px;"></td>
                        <td><input type="text"  style="height:30px;width: 60px;"></td>
                    </tr>
                </tfoot>
            </table>





        </div>



    </div>
</div>

<script type="text/javascript">
    $(function() {
        $('#initable').DataTable({
            "pageLength": 50,
             "lengthMenu": [50,100,200,300,500,1000],
             "ordering" : false
        });
    })
</script>