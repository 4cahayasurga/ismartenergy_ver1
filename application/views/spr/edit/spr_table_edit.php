<div class="table-responsive">


    <table class="table table-bordered table-striped table-hover table-condesed" id="istable">
        <thead>
            <tr>
                <th style="background-color: #7ed6df;">No.</th>
                <th style="background-color: #7ed6df;">Kode RAP</th>
                <th style="background-color: #7ed6df;">Uraian</th>
                <th style="background-color: #7ed6df;">Vol</th>
                <th style="background-color: #7ed6df;">Satuan</th>
                <th style="background-color: #7ed6df;" class="text-center">#</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $NoUrutBuatan = 1;
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

                    if ($TotalSPR < $TotalRAP) {
                        $notif = '';
                    }


                    ?>
                    <tr <?= $notif ?>>
                        <td><?= $NoUrutBuatan++ ?></td>
                        <td><?= $data->KdRAP ?></td>
                        <td><?= $data->Uraian ?></td>
                        <td><?= floatval($data->Vol) ?></td>
                        <td><?= $data->Uom ?></td>
                        <td class="text-center">

                            <a href="javascript:void(0)" class="btn btn-xs btn-default" id="EditDetailEdit_<?= $data->NoUrut ?>" title="Edit Detail" onClick="EditDetailEdit(<?=$data->NoUrut?>)" data-NoSPR="<?= $data->NoSPR ?>"  data-NoUrut="<?= $data->NoUrut ?>"  data-Alokasi="<?= $data->Alokasi ?>"  data-KdRAP="<?= $data->KdRAP ?>"><i class="fa fa-pencil"></i></a>

                            <a href="javascript:void(0)" class="btn btn-xs btn-default" onClick="HapusDetailEdit(<?=$data->NoUrut?>)" id="HapusDetailEdit_<?=$data->NoUrut?>" title="Hapus Detail" data-NoSPR="<?= $data->NoSPR ?>" data-NoUrut="<?= $data->NoUrut ?>" data-Alokasi="<?= $data->Alokasi ?>" data-KdRAP="<?= $data->KdRAP ?>"><i class="fa fa-trash"></i></a>


                        </td>


                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>




    <script>
        $(function() {
            $('#istable').DataTable();
            $('#simpan').removeAttr('disabled');

            


        });

        function EditDetailEdit(NoUrut){
            NoSPR = $('#istable tbody tr td #EditDetailEdit_'+NoUrut).attr('data-NoSPR');
            Alokasi = $('#istable tbody tr td #EditDetailEdit_'+NoUrut).attr('data-Alokasi');
            KdRAP = $('#istable tbody tr td #EditDetailEdit_'+NoUrut).attr('data-KdRAP');

            swal({
                title: 'Perhatian',
                text: 'Menambah, Merubah atau Menghapus Detail Harus Mengklik "Save" Pada Halaman ini  ',
                
                showCancelButton: true,
                confirmButtonText: 'Ya, Mengerti',
                cancelButtonText: 'Tidak Jadi',
            }).then(
            result => {
                if (result.value) {
                    $('#MyModal').modal('show');
                    $('#MyModalTitle').text('Edit Detail');


                    $.ajax({
                        type: "POST",
                        url: "<?= site_url('Spr/getFormEditDetailEdit') ?>",
                        data: {
                            'NoSPR': NoSPR,
                            'NoUrut': NoUrut,
                            'Alokasi': Alokasi,
                            'KdRAP': KdRAP,
                        },
                        dataType: "HTML",
                        success: function(response) {
                            $('#MyModalBody').html(response);
                        }
                    });
                } else {
                    return false;
                }
            });

        }

        function HapusDetailEdit(NoUrut){
            NoSPR = $('#istable tbody tr td #HapusDetailEdit_'+NoUrut).attr('data-NoSPR');
            Alokasi = $('#istable tbody tr td #HapusDetailEdit_'+NoUrut).attr('data-Alokasi');
            KdRAP = $('#istable tbody tr td #HapusDetailEdit_'+NoUrut).attr('data-KdRAP');

            swal({
                title: 'Perhatian',
                text: 'Menambah, Merubah atau Menghapus Detail Harus Mengklik "Save" Pada Halaman ini  ',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Ya, Mengerti',
                cancelButtonText: 'Tidak Jadi',
            }).then(
            result => {
                if (result.value) {

                    Swal({
                        title: 'Anda yakin?',
                        text: "Apakah yakin ingin menghapus data ini?",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Yakin',
                        cancelButtonColor: '#d33',

                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                url: "<?= site_url('Spr/hapus_detail_edit') ?>",
                                type: "POST",
                                data: {
                                    'NoSPR': NoSPR,
                                    'NoUrut': NoUrut,
                                    'Alokasi': Alokasi,
                                    'KdRAP': KdRAP,
                                },
                                success: function(response) {
                                    $.toast({
                                        heading: 'Berhasil Dihapus',
                                        position: 'bottom-right',
                                        showHideTransition: 'slide',
                                        icon: 'success',
                                        hideAfter: 100,
                                        afterHidden: function() {
                                            show_detail_spr();
                                        }
                                    });

                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    alert('Error deleting data');
                                }
                            });
                        }
                    });
                } else {
                    return false;
                }
            });

        }

    </script>

    <?php } ?>