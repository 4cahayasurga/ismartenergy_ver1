<style>
    .mykonten {
        font-family: Arial, Helvetica, sans-serif !important;
        font-size: x-small !important;
    }

    .ukuran_select2 {
        font-size: 10px !important;
        font-family: Arial, Helvetica, sans-serif;

    }

</style>
<div class="content-wrapper">
    <section class="content-header">
        <h2><?=$judul?></h2>
        <p></p>

        <ol class="breadcrumb">
            <li><a href="<?= site_url('dashboard')  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?=$judul?></li>
        </ol>

    </section>

    <section class="content-header">

        <button class="btn btn-xs" style="background-color: orange;" onclick="history.back()"><i class="fa fa-arrow-left"></i> Back</button>


    </section>

    <section class="content">
        <?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>
        <?php
        $my_jobno = '';
        $my_alokasi = '';
        if (isset($_SESSION['sesi'])) {
            $my_jobno = $this->session->flashdata('JobNo');
            $my_alokasi = $this->session->flashdata('Alokasi');
        } else {
            unset($_SESSION['sesi']);
            unset($_SESSION['JobNo']);
            unset($_SESSION['Alokasi']);
        }
        ?>
        <div class="box box-success">

            <div class="box-header with-border">
                <!-- <h1 class="box-title mykonten"><?=$judul?></h1> -->

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>
            <div class="box-body">

                <div class="row">
                    <div class="col-sm-6">

                        <table border="0" width="100%">
                            <tr>
                                <th style="padding-top:5px;padding-bottom:5px;" width="100px">JobNo</th>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <select name="JobNo" id="MyJobNo" onchange="pencarian()" class="form-control input-sm select2" required>
                                        <option value="">-- Pilih Salah Satu</option>
                                        <?php foreach ($Job as $job) : ?>
                                            <option value="<?= $job->JobNo ?>" <?=($job->JobNo ==  $my_alokasi ) ? 'selected' : '' ?> ><?= $job->JobNo ?> - <?= $job->JobNm ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                        </table>

                        <table border="0" width="100%">
                            <tr>
                                <th style="padding-top:5px;padding-bottom:5px;" width="100px"></th>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <label for="">Dari Tanggal</label>
                                    <input type="date" class="form-control form-control-sm" id="dari_tgl" onchange="pencarian()" name="dari_tgl" value="<?= $dari_tgl ?>" required>
                                </td>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    s.d
                                </td>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <label for="">Sampai Tanggal</label>
                                    <input type="date" class="form-control form-control-sm" id="sampai_tgl" onchange="pencarian()" name="sampai_tgl" value="<?= $sampai_tgl ?>" required>
                                </td>
                            </tr>
                        </table>


                        <table border="0" width="100%">
                            <tr>
                                <th style="padding-top:5px;padding-bottom:5px;" width="100px"></th>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <!-- <input type="text" id="MyJobNo" value="<?= $pdpj->JobNo ?>"> -->
                                    <button type="submit" name="btn_cari" id="btn_cari" class="btn btn-xs bg-purple"><i class="fa fa-print"></i> PRINT</button>
                                </td>
                            </tr>
                        </table>



                    </div>

                </div>




            </div>
            <div class="overlay loadingku" style="display: none;">
                <i class="fa fa-refresh fa-spin"></i>
            </div>


        </div>

        <div class="tampil_table"></div>


    </section>


</div>




<script>

  $(document).ready(function() {

    aksi_halaman_terbuka();

    $('.select2').select2({
        'theme': 'classic',
        'width': '100%',
    });

    $('#btn_cari').click(function() {
        pencarian();
    })

})


  function aksi_halaman_terbuka() {

    var JobNo = $('#JobNo :selected').val();
    var Alokasi = $('#Alokasi :selected').val();
    var dari_tanggal = $('#dari_tanggal').val();
    var sampai_tanggal = $('#sampai_tanggal').val();
    if (Alokasi !== '') {
        pencarian();
    }
}


function pencarian() {
    // alert('ok');
    // return false;



    var JobNo = $('#MyJobNo').val();
    // var Alokasi = $('#Alokasi :selected').val();
    var dari_tgl = $('#dari_tgl').val();
    var sampai_tgl = $('#sampai_tgl').val();
// alert(JobNo);

 //    if (Alokasi == '') {
 //     $.toast({

 //         text: 'Alokasi masih kosong',
 //         position: 'bottom-right',
 //         stack: false,
 //         hideAfter: 1000,
 //         icon: 'info',

 //     })
 //     return false;
 // }

 if (dari_tgl == '' || sampai_tgl == '') {
   $.toast({
       text: 'Tanggal masih kosong',
       position: 'bottom-right',
       stack: false,
       hideAfter: 1000,
       icon: 'info',

   })
   return false;
}


$.ajax({
    type: "post",
    url: "<?= site_url('Permintaan_dana_lapangan/dapat_list_pd') ?>",
    data: {
        'JobNo': JobNo,
        'Alokasi': '',
        'dari_tgl': dari_tgl,
        'sampai_tgl': sampai_tgl,
    },
    dataType: "html",
    beforeSend: function() {
        $('.loadingku').show();
    },
    success: function(data) {
        $('.loadingku').hide();
        $('.tampil_table').html('');
        $('.tampil_table').html(data);

    },
    error: function() {
        $('.loadingku').hide();
    }
});

}

function ubah_status(LedgerNo, value_status_pd) {

    Swal.fire({
        title: 'Anda Yakin?',
        text: "perubahan data tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Lakukan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.value) {

            $.ajax({
                type: "post",
                url: "<?= site_url('Permintaan_dana_lapangan/aksi_approve') ?>",
                data: {
                    'LedgerNo': LedgerNo,
                    'value_status_pd': value_status_pd
                },
                dataType: "html",
                beforeSend: function() {
                    $('.loading_table').show();
                },
                success: function(data) {
                    $('.loading_table').hide();

                    if (data == 'approve_kt_ada') {
                        $.toast({
                            text: 'PD sudah approve oleh KT',
                            position: 'bottom-right',
                            stack: false,
                            hideAfter: 1000,
                            icon: 'info',

                        });
                        return false;
                    }

                    if (data == 'gagal') {
                        $.toast({
                            text: 'gagal mengubah data',
                            position: 'bottom-right',
                            stack: false,
                            hideAfter: 1000,
                            icon: 'error',

                        });
                        return false;
                    }

                    if (data == 'berhasil') {
                        $.toast({
                            text: 'berhasil mengubah data',
                            position: 'bottom-right',
                            stack: false,
                            hideAfter: 1000,
                            icon: 'success',

                        })
                        pencarian();
                        return false;
                    }

                },
                error: function() {
                    $('.loading_table').hide();
                    $.toast({
                        text: 'Terjadi Kesalahan',
                        position: 'bottom-right',
                        stack: false,
                        hideAfter: 1000,
                        icon: 'error',

                    });
                    return false;
                }
            });


        }
    })



}

</script>
