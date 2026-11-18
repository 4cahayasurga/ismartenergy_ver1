<?php

// validasi url untuk JobNo, alokasi, dari_tgl dan sampai tgl
$JobNo_uri = ($this->uri->segment(3) == FALSE) ? '' : $this->uri->segment(3);
$Alokasi_uri = ($this->uri->segment(4) == FALSE) ? '' : $this->uri->segment(4);
$dari_tgl_uri = ($this->uri->segment(5) == FALSE) ? '' : $this->uri->segment(5);
$sampai_tgl_uri = ($this->uri->segment(6) == FALSE) ? '' : $this->uri->segment(6);

$dari_tgl =  ($dari_tgl_uri !='') ? $dari_tgl_uri : $dari_tgl;
$sampai_tgl =  ($sampai_tgl_uri !='') ? $sampai_tgl_uri : $sampai_tgl;

?>
<style>
    .mykonten {
        /*font-family: Arial, Helvetica, sans-serif !important;*/
        font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
        font-size: x-small !important;
        font-size: 10px;
    }

    .ukuran_select2 {
        font-size: 10px !important;
        font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
        /*font-family: Arial, Helvetica, sans-serif;*/
    }

    .atur_ukuran{
        font-size: 10px !important;
        font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
    }

</style>

<div class="content-wrapper">
    <section class="content-header">

        <table width="100%" border="0">
            <tr>
                <td style="font-size: 14px !important;" class="text-bold atur_ukuran"><?=(isset($judul)) ? $judul : '' ?></td>
                <td style="font-size: 12px !important;" class="text-bold text-right atur_ukuran"><?=$pdpj->JobNo.' - '.$pdpj->JobNm.'<br />'.$pdpj->Lokasi?></td>
            </tr>
            <tr>
                <td><button class="btn btn-sm bg-gray" style="background-color:#7f8c8d;" onclick="history.back()"><i class="fa fa-arrow-left"></i> Back</button></td>
                <td></td>
            </tr>
        </table>

    </section>

    <section class="content">
        <?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>

        <div class="box box-success">


            <div class="box-body">

                <div class="row">
                    <div class="col-sm-6">

                        <table border="0" width="100%">
                            <tr>
                                <th style="padding-top:5px;padding-bottom:5px;" width="100px" class="atur_ukuran" >Job. No</th>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <input type="text" class="form-control input-sm atur_ukuran" value="<?=$pdpj->JobNo.' - '.$pdpj->JobNm?>" disabled="disabled">
                                </td>
                            </tr>

                            <tr>
                                <th style="padding-top:5px;padding-bottom:5px;" width="100px" class="atur_ukuran">Alokasi</th>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <select name="Alokasi" id="Alokasi"  class="form-control input-sm select2 atur_ukuran" required>
                                        <option value=""></option>
                                        <?php foreach ($data_alokasi as $alokasi) : ?>
                                            <option value="<?= $alokasi->Alokasi ?>" <?=($alokasi->Alokasi ==  $Alokasi_uri ) ? 'selected' : '' ?> ><?= $alokasi->Alokasi ?> - <?= $alokasi->Keterangan ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                        </table>

                        <!-- <table border="0" width="100%">
                            <tr>
                                <th style="padding-top:5px;padding-bottom:5px;" width="100px">Alokasi</th>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <select name="Alokasi" id="Alokasi" onchange="pencarian()" class="form-control input-sm select2" required>
                                        <option value="">-- Pilih Salah Satu</option>
                                        <?php foreach ($data_alokasi as $alokasi) : ?>
                                            <option value="<?= $alokasi->Alokasi ?>" ><?= $alokasi->Alokasi ?> - <?= $alokasi->Keterangan ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                        </table> -->

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
                                    <input type="hidden" id="MyJobNo" value="<?= $pdpj->JobNo ?>">
                                    <button type="submit" name="btn_cari" id="btn_cari" class="btn btn-xs bg-purple"><i class="fa fa-search"></i> Lihat</button>
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



<script src="<?=base_url('assets/select2_baru.js')?>"></script>
<script>

  $(document).ready(function() {

    $('.select2').select2({
        'placeholder' : '-- Pilih Alokasi --',
        'width': '100%',
        'dropdownCssClass': "ukuran_select2"
    });

    $('#btn_cari').click(function() {
        pencarian();
    });

    $('#Alokasi').change(function(){
        pencarian();
    });



});


  function pencarian() {


    var JobNo = $('#MyJobNo').val();
    var Alokasi = $('#Alokasi :selected').val();
    var dari_tgl = $('#dari_tgl').val();
    var sampai_tgl = $('#sampai_tgl').val();


    if (Alokasi == '') {
        $.toast({

           text: 'Alokasi masih kosong',
           position: 'bottom-right',
           stack: false,
           hideAfter: 1000,
           icon: 'info',

       })
        return false;
    }

    if (dari_tgl > sampai_tgl  ) {
       $.toast({
           text: 'Tanggal tidak sesuai ',
           position: 'top-right',
           stack: false,
           hideAfter: 3000,
           icon: 'info',
        });
       return false;
   }


   $.ajax({
    type: "post",
    url: "<?= site_url('Approve_permintaan_dana_keuangan/dapat_list_pd') ?>",
    data: {
        'JobNo': JobNo,
        'Alokasi': Alokasi,
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

function approve_KK(LedgerNo, value_status_pd,NoPD) {
    var JobNo = $('#MyJobNo').val();
    var Alokasi = $('#Alokasi :selected').val();



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
                url: "<?= site_url('Approve_permintaan_dana_keuangan/aksi_approve_keuangan') ?>",
                data: {
                    'LedgerNo': LedgerNo,
                    'value_status_pd': value_status_pd,
                    'JobNo' : JobNo,
                    'Alokasi' : Alokasi,
                    'NoPD' : NoPD
                },
                dataType: "html",
                beforeSend: function() {
                    $('.loading_table').show();
                },
                complete : function(){
                    $('.loading_table').hide();
                },
                success: function(data) {

                    if (data == 'ada_pembayaran') {
                        swal("Info", "Sudah ada pembayaran kasir", "info");
                        return false;
                    }



                    if (data == 'gagal') {
                        swal("Gagal", "gagal saat mengubah data", "error");
                        return false;
                    }

                    if (data == 'berhasil') {
                        $.toast({
                            text: 'berhasil mengubah data',
                            position: 'bottom-right',
                            stack: false,
                            hideAfter: 3000,
                            icon: 'success',

                        })
                        pencarian();
                        return false;
                    }


                },
                error: function() {
                    $.toast({
                        text: 'Terjadi Kesalahan',
                        position: 'bottom-right',
                        stack: false,
                        hideAfter: 3000,
                        icon: 'error',

                    });
                    return false;
                }
            });


        }
    })



}

function lihat_pd(LedgerNo=0, PD_flag='', NoPD='', JobNo='', Alokasi=''){

    if (LedgerNo == 0) {
        alert('terjadi kesalahan');
        return false;
    }
    PD_flag = (PD_flag == 0) ? 'DARI_KEUANGAN' : 'DARI_LAPANGAN';

    if (PD_flag == 'DARI_LAPANGAN') {
        window.location.href="<?=site_url('Permintaan_dana_lapangan/ViewPDLapangan/')?>"+LedgerNo;
    }else if(PD_flag == 'DARI_KEUANGAN'){
        window.location.href="<?=site_url('Permintaan_dana_keuangan/detail_pd/')?>"+LedgerNo+'/'+NoPD+'/'+JobNo+'/'+Alokasi;
    }


}




</script>