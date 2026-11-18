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
                <td style="font-size: 12px !important;" class="text-bold text-right atur_ukuran"><?=$JobNo.' - '.$JobNm.'<br />'.$Lokasi?></td>
            </tr>
            <tr>
                <td>
                    <!-- <button class="btn btn-sm bg-gray" style="background-color:#7f8c8d;" onclick="history.back()"><i class="fa fa-arrow-left"></i> Back</button> -->
                    <a href="<?=site_url('job/sub_job/'.$JobNo)?>" class="btn btn-sm bg-gray"  ><i class="fa fa-arrow-left"></i> Back</a>
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

                    <div class="col-sm-4">

                        <table border="0" width="100%">
                            <tr>
                                <th style="padding-top:5px;padding-bottom:5px;" width="100px" class="atur_ukuran" >Job. No</th>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <input type="text" class="form-control input-sm atur_ukuran" value="<?=$JobNo.' - '.$JobNm?>" disabled="disabled">
                                </td>
                            </tr>

                            <tr>
                                <th style="padding-top:5px;padding-bottom:5px;" width="100px" class="atur_ukuran">Alokasi</th>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <select name="Alokasi" id="Alokasi"  class="form-control input-sm select2 atur_ukuran" required>
                                        <option value=""></option>
                                        <?php foreach ($data_alokasi as $alokasi) : ?>
                                            <option value="<?= $alokasi->Alokasi ?>" <?=($alokasi->Alokasi ==  $Alokasi ) ? 'selected' : '' ?> ><?= $alokasi->Alokasi ?> - <?= $alokasi->Keterangan ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                        </table>

                    </div>

                    <div class="col-sm-4">

                        <table border="0" width="100%">
                            <tr>
                                <th style="padding-top:5px;padding-bottom:5px;" width="100px" class="atur_ukuran">Tanggal PD</th>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <!-- <label for="" class="atur_ukuran">Dari Tanggal</label> -->
                                    <input type="date" class="form-control form-control-sm atur_ukuran" id="dari_tgl"  name="dari_tgl" value="<?= $dari_tgl ?>" required>
                                    
                                </td>
                                <td style="padding-top:5px;padding-bottom:5px;text-align: center;padding-left: 5px;padding-right: 5px;">
                                    s.d
                                </td>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <!-- <label for="" class="atur_ukuran">Sampai Tanggal</label> -->
                                    <input type="date" class="form-control form-control-sm atur_ukuran" id="sampai_tgl" name="sampai_tgl" value="<?= $sampai_tgl ?>" required>
                                </td>
                            </tr>
                        </table>

                        <table border="0" width="100%">
                            <tr>
                                <th style="padding-top:5px;padding-bottom:5px;" width="100px" class="atur_ukuran">Tanggal PJ</th>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <!-- <label for="" class="atur_ukuran">Dari Tanggal</label> -->
                                    <input type="date" class="form-control form-control-sm atur_ukuran" id="dari_tgl_pj"  name="dari_tgl_pj" value="<?= $dari_tgl_pj ?>" required>
                                    
                                </td>
                                <td style="padding-top:5px;padding-bottom:5px;text-align: center;padding-left: 5px;padding-right: 5px;">
                                    s.d
                                </td>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <!-- <label for="" class="atur_ukuran">Sampai Tanggal</label> -->
                                    <input type="date" class="form-control form-control-sm atur_ukuran" id="sampai_tgl_pj" name="sampai_tgl_pj" value="<?= $sampai_tgl_pj ?>" required>
                                </td>
                            </tr>
                        </table>

                    </div>

                    <div class="col-sm-12">
                        <table border="0" width="100%">
                            <tr>
                                <th style="padding-top:5px;padding-bottom:5px;" width="100px"></th>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <input type="hidden" id="MyJobNo" value="<?= $JobNo ?>">
                                    <button type="submit" name="btn_cari" id="btn_cari" class="btn btn-sm bg-purple"><i class="fa fa-search"></i> Lihat</button>
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

    $('#dari_tgl,#sampai_tgl').change(function(){
    	pencarian();
    });

    $('#dari_tgl_pj,#sampai_tgl_pj').change(function(){
        pencarian();
    });



});

  function cek_printPD(LedgerNo){
    if (LedgerNo=='') {
        return false;
    }

    var PjApprovedByAK = $('#print_ID_'+LedgerNo).attr('data-PjApprovedByAK');
    var NoPD = $('#print_ID_'+LedgerNo).attr('data-NoPD');
    var NoPJ = $('#print_ID_'+LedgerNo).attr('data-NoPJ');
    var urlsaya = $('#print_ID_'+LedgerNo).attr('data-urlsaya');

    // alert(NoPJ);
    // return false;

    if (PjApprovedByAK == '' || PjApprovedByAK == null ) {
        swal.fire('Info!','Approve No. PJ '+NoPJ+' terlebih dahulu','info');
        return false;
    }

    window.open(urlsaya,'_blank');

    
    // alert(urlsaya);

}

  function btn_print_PD(LedgerNo=''){
    var ID = $('#btn_print_PD_'+LedgerNo);
    var NoPD = ID.attr('data-NoPD');
    var JobNo = ID.attr('data-JobNo');
    var Alokasi = ID.attr('data-Alokasi');
    var PjApprovedByAK = ID.attr('data-PjApprovedByAK');
    var url_print = ID.attr('data-url_print');

    if (PjApprovedByAK == '' || PjApprovedByAK == null) {
        swal("Info", "Approve PD terlebih dahulu", "info");
        return false;
    }
    window.open(url_print, '_blank');

}

function aksi_halaman_terbuka(){
    if($('#Alokasi :selected').val() != ''){
        pencarian();
    }
}

function detail_PJLapangan(LedgerNo=''){
    if(LedgerNo == ''){
        return false;
    }

    var ID = $('#lihat_pj_lapangan_'+LedgerNo);
    var NoPD = ID.attr('data-NoPD');
    var NoPJ = ID.attr('data-NoPJ');

    var myurl = '/'+LedgerNo+'/epj_lap/'+NoPD+'/'+NoPJ+'/'+'1';


    jQuery.ajax({
      url: '<?=site_url('PDPJ/PJ_lapangan_entry_controller/detail_pj_lapangan')?>'+myurl,
      type: 'GET',
      dataType: 'html',
      cache : false,
      beforeSend : function(){
        $('#loading_pj_lapangan_'+LedgerNo).show();
        $('#lihat_pj_lapangan_'+LedgerNo).hide();
    },
    complete: function(xhr, textStatus) {
        $('#loading_pj_lapangan_'+LedgerNo).hide();
        $('#lihat_pj_lapangan_'+LedgerNo).show();
    },
    success: function(data, textStatus, xhr) {
        if (data == 'data_tidak_ada') {
            swal.fire('info','data tidak ditemukan','info');
            return false;
        }

        if (data == 'belum_bayar') {
            swal.fire('info','belum ada pembayaran untuk No '+NoPD+' ','info');
            return false;
        }

        window.location.href="<?=site_url('PDPJ/PJ_lapangan_entry_controller/detail_pj_lapangan')?>"+myurl;
    },
    error: function(xhr, textStatus, errorThrown) {
        swal.fire('error','terjadi kesalahan','error');
        return false;
    }
});
    

    

}

function pencarian(){

  var JobNo = $('#MyJobNo').val();
  var Alokasi = $('#Alokasi :selected').val();
  var dari_tgl = $('#dari_tgl').val();
  var sampai_tgl = $('#sampai_tgl').val();

  var dari_tgl_pj = $('#dari_tgl_pj').val();
  var sampai_tgl_pj = $('#sampai_tgl_pj').val();


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



$.ajax({
    type: "post",
    url: "<?= site_url('PDPJ/PJ_lapangan_entry_controller/dapat_list_pj_lapangan') ?>",
    data: {
        'JobNo': JobNo,
        'Alokasi': Alokasi,
        'dari_tgl': dari_tgl,
        'sampai_tgl': sampai_tgl,

        'dari_tgl_pj': dari_tgl_pj,
        'sampai_tgl_pj': sampai_tgl_pj,
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

function aksi_approve_pj(LedgerNo,flag_status_approve,NoPD){

    Swal.fire({
        title: 'Anda Yakin?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Lakukan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.value) {



            var status_nama = (flag_status_approve == 0) ? 'APPROVE' : 'UNAPPROVE';

            // alert('ok');
            // return false;

            $.ajax({
                url: "<?=site_url('PDPJ/PJ_lapangan_entry_controller/aksi_approve_pj_lapangan')?>",
                type: 'post',
                dataType : 'JSON',
                data: {
                    'LedgerNo' : LedgerNo,
                    'flag_status_approve' : flag_status_approve,
                    'NoPD' : NoPD
                }, beforeSend : function(){
                    $('.loadingsaya').show();

                },complete : function(){
                    pencarian();
                },success: function (data) {
                    $('.loadingsaya').hide();

                    // alert(data.status);
                    // return false;

                    if (data.status == 'data_tidak_ada') {
                        swal.fire('Info!',data.pesan,'info');
                        return false;
                        // $.toast({
                        //     text: data.pesan,
                        //     position: 'top-right',
                        //     stack: false,
                        //     hideAfter: 3000,
                        //     icon: 'error',

                        // });
                        // return false;
                    }

                    if (data.status == 'belum_bayar') {
                        swal.fire('Info!',data.pesan,'info');
                        return false;
                        // $.toast({
                        //     text: data.pesan,
                        //     position: 'top-right',
                        //     stack: false,
                        //     hideAfter: 3000,
                        //     icon: 'error',

                        // });
                        // return false;
                    }

                    if (data.status == 'pj_masih_kosong') {
                        swal.fire('Info!',data.pesan,'info');
                        return false;
                        // $.toast({
                        //     text: data.pesan,
                        //     position: 'top-right',
                        //     stack: false,
                        //     hideAfter: 3000,
                        //     icon: 'error',

                        // });
                        // return false;
                    }

                    if (data.status == 'sudah_approvedKT') {
                        swal.fire('Info!',data.pesan,'info');
                        return false;
                        // $.toast({
                        //     text: data.pesan,
                        //     position: 'top-right',
                        //     stack: false,
                        //     hideAfter: 3000,
                        //     icon: 'error',

                        // });
                        // return false;
                    }

                    if (data.status == 'sudah_approvedKK') {
                        swal.fire('Info!',data.pesan,'info');
                        return false;
                        // $.toast({
                        //     text: data.pesan,
                        //     position: 'top-right',
                        //     stack: false,
                        //     hideAfter: 3000,
                        //     icon: 'error',

                        // });
                        // return false;
                    }

                    if (data.status == 'berhasil') {
                        swal.fire('Info!',data.pesan,'info');
                        return false;
                        // $.toast({
                        //     text: data.pesan,
                        //     position: 'top-right',
                        //     stack: false,
                        //     hideAfter: 3000,
                        //     icon: 'success',

                        // });
                        // return false;
                    }

                    if (data.status == 'gagal') {
                        swal.fire('Info!',data.pesan,'info');
                        return false;
                        // $.toast({
                        //     text: data.pesan,
                        //     position: 'top-right',
                        //     stack: false,
                        //     hideAfter: 3000,
                        //     icon: 'success',

                        // });
                        // return false;
                    }


                    return false;


                },error : function(){
                    $('.loadingsaya').hide();
                    $.toast({
                        text: 'Terjadi Kesalahan',
                        position: 'top-right',
                        stack: false,
                        hideAfter: 3000,
                        icon: 'error',

                    });
                    return false;
                }
            });

}

});

}




</script>