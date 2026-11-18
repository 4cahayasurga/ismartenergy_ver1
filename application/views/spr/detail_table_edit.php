<?php
if ($spr->num_rows() > 0) {
    // print_rr($spr);
    // exit;

    $notif = '';
    $TotalRAP = 0;
    $TotalSPR = 0;
    $persenRAP = 0;
    $persenSPR = 0;
    foreach ($spr->result() as $data) {

        $TotalRAP = $data->TotalRAP;
        $TotalSPR = $data->Total;

        // 80%  RAP 
        $persenRAP = ($TotalRAP * 80) / 100;
        $persenSPR = ($TotalSPR * 80) / 100;




        if ($persenSPR >= $persenRAP) {
            $notif = 'style="background-color: #2ecc71;color:white; "';
        }

        if ($TotalSPR == $TotalRAP) {
            $notif = 'style="background-color: #f1c40f;color:white; "';
        }

        if ($TotalSPR > $TotalRAP) {
            $notif = 'style="background-color: #e74c3c;color:white; "';
        }


?>
        <tr <?= $notif ?>>
            <td><?= $data->NoUrut ?></td>
            <td><?= $data->KdRAP ?></td>
            <td><?= $data->Uraian ?></td>
            <td><?= floatval($data->Vol) ?></td>
            <td><?= $data->Uom ?></td>
            <td class="text-center">

                <a href="javascript:void(0)" class="btn btn-xs btn-default" title="Edit Detail" onclick="editDetailSPR('<?= $data->NoUrut ?>')"><i class="fa fa-pencil"></i></a>
                <a href="javascript:void(0)" class="btn btn-xs btn-default" title="Hapus Detail" onclick="hapusDetailSPR('<?= $data->NoUrut ?>')"><i class="fa fa-trash"></i></a>
            </td>


        </tr>
    <?php } ?>




<?php } ?>