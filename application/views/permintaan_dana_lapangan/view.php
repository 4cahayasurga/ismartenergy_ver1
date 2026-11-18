<?php

// validasi url untuk JobNo, alokasi, dari_tgl dan sampai tgl
$JobNo_uri = ($this->uri->segment(3) == FALSE) ? '' : $this->uri->segment(3);
$Alokasi_uri = ($this->uri->segment(4) == FALSE) ? '' : $this->uri->segment(4);
$dari_tgl_uri = ($this->uri->segment(5) == FALSE) ? '' : $this->uri->segment(5);
$sampai_tgl_uri = ($this->uri->segment(6) == FALSE) ? '' : $this->uri->segment(6);

$dari_tgl =  ($dari_tgl_uri !='') ? $dari_tgl_uri : $dari_tgl;
$sampai_tgl =  ($sampai_tgl_uri !='') ? $sampai_tgl_uri : $sampai_tgl;

$UrlPengenal = $this->uri->segment(1);


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

    .swal2-popup {
        font-size: 1.2rem !important;
        font-family: Geneva, Verdana, "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
    }

</style>

<div class="content-wrapper ">
    <section class="content-header">

        <table width="100%" border="0">
            <tr>
                <td style="font-size: 14px !important;" class="text-bold atur_ukuran"><?=(isset($judul)) ? $judul : '' ?></td>
                <td style="font-size: 12px !important;" class="text-bold text-right atur_ukuran"><?=$pdpj->JobNo.' - '.$pdpj->JobNm.'<br />'.$pdpj->Lokasi?></td>
            </tr>
            <tr>
                <td>
                    <!-- <button class="btn btn-sm bg-gray" style="background-color:#7f8c8d;" onclick="history.back()"><i class="fa fa-arrow-left"></i> Back</button> -->

                    <a href="<?=site_url('job/sub_job/'.$pdpj->JobNo)?>" class="btn btn-sm bg-gray"  ><i class="fa fa-arrow-left"></i> Back</a>

                </td>
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

                        <table border="0" width="100%">
                            <tr>
                                <th style="padding-top:5px;padding-bottom:5px;" width="100px"></th>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <label for="" class="atur_ukuran">Dari Tanggal</label>
                                    <input type="date" class="form-control form-control-sm atur_ukuran" id="dari_tgl"  name="dari_tgl" value="<?= $dari_tgl ?>" required>
                                    
                                </td>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    s.d
                                </td>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <label for="" class="atur_ukuran">Sampai Tanggal</label>
                                    <input type="date" class="form-control form-control-sm atur_ukuran" id="sampai_tgl" name="sampai_tgl" value="<?= $sampai_tgl ?>" required>
                                </td>
                            </tr>
                        </table>


                        <table border="0" width="100%">
                            <tr>
                                <th style="padding-top:5px;padding-bottom:5px;" width="100px"></th>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <input type="hidden" id="MyJobNo" value="<?= $pdpj->JobNo ?>">
                                    <input type="hidden" id="UrlPengenal" value="<?=$UrlPengenal?>" >
                                    <!-- <button type="submit" name="btn_cari" id="btn_cari" class="btn btn-sm bg-purple"><i class="fa fa-search"></i> Lihat</button> -->

                                    <button type="submit" name="btn_cari" id="btn_cari" class="btn btn-md bg-blue atur_ukuran" style="font-size:11px !important;"  ><i class="fa fa-search"></i> TAMPILKAN</button>
                                    <button class="btn btn-md btn-default btn_loading" style="font-size:12px !important;display:none" disabled><i class="fa fa-spinner"></i> Loading...</button>

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

    aksi_halaman_terbuka();

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

    $('#dari_tgl, #sampai_tgl').change(function(){
        if ($('#Alokasi :selected').val() !='') {
            pencarian();
        }
        
    });




});

  function cek_printPD(LedgerNo){
    if (LedgerNo=='') {
        return false;
    }

    var ApprovedByAK = $('#print_ID_'+LedgerNo).attr('data-ApprovedByAK');
    var NoPD = $('#print_ID_'+LedgerNo).attr('data-NoPD');
    var urlsaya = $('#print_ID_'+LedgerNo).attr('data-urlsaya');

    if (ApprovedByAK == '' || ApprovedByAK == null ) {
        swal.fire('Info!','Approve PD : '+NoPD+' terlebih dahulu','info');
        return false;
    }

    window.open(urlsaya,'_blank');

    
    // alert(urlsaya);

}

function lihat(LedgerNo=''){
    var url_go = $('#lihat_pd_'+LedgerNo).attr('data-url_go');
    var url_back = $('#lihat_pd_'+LedgerNo).attr('data-url_back');

    window.location.href=url_go;
    // alert(url_go+' 0 '+url_back);
}


function aksi_halaman_terbuka() {
    var JobNo = $('#JobNo :selected').val();
    var Alokasi = $('#Alokasi :selected').val();
    var dari_tanggal = $('#dari_tanggal').val();
    var sampai_tanggal = $('#sampai_tanggal').val();
    if (Alokasi !== '') {
        pencarian();
    }
}

function btn_print_PD(LedgerNo=''){
    var ID = $('#btn_print_PD_'+LedgerNo);
    var NoPD = ID.attr('data-NoPD');
    var JobNo = ID.attr('data-JobNo');
    var Alokasi = ID.attr('data-Alokasi');
    var ApprovedByAK = ID.attr('data-ApprovedByAK');
    var url_print = ID.attr('data-url_print');

    if (ApprovedByAK == '' || ApprovedByAK == null) {
        swal("Info", "Approve PD terlebih dahulu", "info");
        return false;
    }
    window.open(url_print, '_blank');

    // alert(NoPD+' - '+JobNo+' - '+Alokasi+' - '+ApprovedByAK);
}

function tampil_loading(){
    $('#btn_cari').hide();
    $('.btn_loading').show();
}

function tutup_loading(){
    $('#btn_cari').show();
    $('.btn_loading').hide();
}

function pencarian() {


    var JobNo = $('#MyJobNo').val();
    var Alokasi = $('#Alokasi :selected').val();
    var dari_tgl = $('#dari_tgl').val();
    var sampai_tgl = $('#sampai_tgl').val();

    var UrlPengenal = $('#UrlPengenal').val();

    // alert(UrlPengenal);


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

       })
       return false;
   }

   jQuery.ajax({
     url: '<?= site_url('Permintaan_dana_lapangan/dapat_list_pd') ?>',
     type: 'POST',
     dataType: 'HTML',
     cache : false,
     data: {
        'JobNo': JobNo,
        'Alokasi': Alokasi,
        'dari_tgl': dari_tgl,
        'sampai_tgl': sampai_tgl,
        'UrlPengenal' : UrlPengenal
    },
    beforeSend: function() {
        tampil_loading();
    },
    complete : function(){
        tutup_loading();
    },
    success: function(data, textStatus, xhr) {
        $('.loadingku').hide();
        $('.tampil_table').html('');
        $('.tampil_table').html(data);
    },
    error: function(xhr, textStatus, errorThrown) {
        Swal.fire('Alert!','terjadi kesalahan','error');

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

                    if (data == 'approve_kk_ada') {
                        $.toast({
                            text: 'PD sudah approve oleh KK',
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


function btn_Approve(LedgerNo=''){
    if(LedgerNo == ''){
        return false;
    }

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

            jQuery.ajax({
                url: '<?= site_url('Permintaan_dana_lapangan/aksi_Approve_PDKeuangan') ?>',
                type: 'POST',
                dataType: 'HTML',
                data: {
                    'LedgerNo': LedgerNo,
                    
                },
                async: true,
                cache : false,
                beforeSend : function(){
                    $('.loadingku').show();
                },
                complete: function(xhr, textStatus) {
                    $('.loadingku').hide();
                    pencarian();
                },
                success: function(data, textStatus, xhr) {

                    if (data == 'berhasil') {
                        $.toast({
                            text: 'berhasil mengubah data',
                            position: 'bottom-right',
                            stack: false,
                            hideAfter: 1000,
                            icon: 'success',

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


                    if (data == 'KT_ada') {
                        $.toast({
                            text: 'UNAPPROVE KT terlebih dahulu',
                            position: 'bottom-right',
                            stack: false,
                            hideAfter: 3000,
                            icon: 'info',

                        });
                        return false;
                    }

                    if (data == 'KK_ada') {
                        $.toast({
                            text: 'UNAPPROVE KK terlebih dahulu',
                            position: 'bottom-right',
                            stack: false,
                            hideAfter: 3000,
                            icon: 'info',

                        });
                        return false;
                    }
                    



                },
                error: function(xhr, textStatus, errorThrown) {
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