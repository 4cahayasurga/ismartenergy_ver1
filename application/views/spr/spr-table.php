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
        <tbody >

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

                    if ($TotalSPR < $TotalRAP) {
                        $notif = '';
                    }


                    ?>
                    <tr <?= $notif ?>>
                        <td><?= $data->NoUrut ?></td>
                        <td><?= $data->KdRAP ?></td>
                        <td><?= $data->Uraian ?></td>
                        <td><?= floatval($data->Vol) ?></td>
                        <td><?= $data->Uom ?></td>
                        <td class="text-center">

                            <a href="javascript:void(0)" class="btn btn-xs btn-default EditDetail" title="Edit Detail" id="EditDetail_<?= $data->NoUrut ?>" data-NoSPR="<?= $data->NoSPR ?>" data-NoUrut="<?= $data->NoUrut ?>" data-Alokasi="<?= $data->Alokasi ?>" data-KdRAP="<?= $data->KdRAP ?>"><i class="fa fa-pencil"></i></a>
                            <a href="javascript:void(0)" class="btn btn-xs btn-default HapusDetail" title="Hapus Detail" id="HapusDetail_<?= $data->NoUrut ?>" data-NoSPR="<?= $data->NoSPR ?>" data-NoUrut="<?= $data->NoUrut ?>" data-Alokasi="<?= $data->Alokasi ?>" data-KdRAP="<?= $data->KdRAP ?>"><i class="fa fa-trash"></i></a>
                        </td>


                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>




    <script>
        $(function() {

            $('#istable').DataTable();

            $('.HapusDetail').click(function() {

                var NoSPR = $(this).attr('data-NoSPR');
                var NoUrut = $(this).attr('data-NoUrut');
                var Alokasi = $(this).attr('data-Alokasi');
                var KdRAP = $(this).attr('data-KdRAP');

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
                            url: "<?= site_url('Spr/hapus_detail') ?>",
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
            })

            $('.EditDetail').click(function() {
                $('#MyModal').modal('show');
                $('#MyModalTitle').text('Edit Detail');

                var NoSPR = $(this).attr('data-NoSPR');
                var NoUrut = $(this).attr('data-NoUrut');
                var Alokasi = $(this).attr('data-Alokasi');
                var KdRAP = $(this).attr('data-KdRAP');

                $.ajax({
                    type: "POST",
                    url: "<?= site_url('Spr/getFormEditDetail') ?>",
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

            })

        })

        function EditDetail(NoUrut, NoSPR) {
            $('#MyModal').modal('show');
            $('#MyModalTitle').text('Edit Detail');
            $.ajax({
                type: "POST",
                url: "<?= site_url('Spr/getFormEditDetail') ?>",
                data: {
                    'NoUrut': NoUrut,
                    'NoSPR': NoSPR
                },
                dataType: "HTML",
                success: function(response) {
                    $('#MyModalBody').html(response);
                }
            });
        }
    </script>

    <?php } ?>