<style>
    .mykonten {
        font-size: x-small !important;
        font-size: 10px;
        font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
    }

    .ukuran_select2 {
        font-size: 10px !important;
        font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
    }

    .atur_ukuran{
        font-size: 10px !important;
        font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
    }
    .swal2-popup {
        font-size: 1.2rem !important;
        font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
    }
</style>

<div class="content-wrapper">

    <section class="content-header">
        <table width="100%" border="0">
            <tr>
                <td style="font-size: 14px !important;" class="text-bold atur_ukuran"><?=(isset($judul)) ? $judul : '' ?></td>
                <td style="font-size: 12px !important;" class="text-bold text-right atur_ukuran text-red"><?=$JobNo.' - '.$JobNm.'<br />'.$Lokasi?></td>
            </tr>
            <tr>
                <td>
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
                    <div class="col-sm-6">

                        <table border="0" width="100%" class="atur_ukuran">
                            <tr>
                                <th style="padding-top:5px;padding-bottom:5px;" width="100px" class="atur_ukuran">Job. No</th>
                                <td style="padding-top:5px;padding-bottom:5px;" class="atur_ukuran">
                                    <input type="text" class="form-control input-sm atur_ukuran" value="<?=$JobNo.' - '.$JobNm?>" disabled="disabled">
                                </td>
                            </tr>

                            <tr>
                                <th style="padding-top:5px;padding-bottom:5px;" width="100px" class="atur_ukuran">Alokasi</th>
                                <td style="padding-top:5px;padding-bottom:5px;" class="atur_ukuran">
                                    <select name="Alokasi" id="Alokasi" onchange="pencarian()" class="form-control input-sm select2 atur_ukuran" required>
                                        <option value="" class="atur_ukuran">-- Pilih Salah Satu</option>
                                        <?php foreach ($data_alokasi as $alokasi) : ?>
                                            <option value="<?= $alokasi->Alokasi ?>" class="atur_ukuran" <?=($alokasi->Alokasi ==  $Alokasi ) ? 'selected' : '' ?> ><?= $alokasi->Alokasi ?> - <?= $alokasi->Keterangan ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                        </table>

                        <table border="0" width="100%">
                            <tr>
                                <th style="padding-top:5px;padding-bottom:5px;" width="100px" class="atur_ukuran">Periode</th>
                                <td style="padding-top:5px;padding-bottom:5px;" class="atur_ukuran">
                                    <!-- <label for="">Dari Tanggal</label> -->
                                    <input type="date" class="form-control form-control-sm atur_ukuran" id="dari_tgl" onchange="pencarian()" name="dari_tgl" value="<?= $dari_tgl ?>" required>
                                </td>
                                <td style="padding-top:5px;padding-bottom:5px;" class="atur_ukuran">
                                    &nbsp;&nbsp;&nbsp;s.d
                                </td>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <!-- <label for="">Sampai Tanggal</label> -->
                                    <input type="date" class="form-control form-control-sm atur_ukuran" id="sampai_tgl" onchange="pencarian()" name="sampai_tgl" value="<?= $sampai_tgl ?>" required>
                                </td>
                            </tr>
                        </table>


                        <table border="0" width="100%">
                            <tr>
                                <th style="padding-top:5px;padding-bottom:5px;" width="100px"></th>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <input type="hidden" id="MyJobNo" value="<?= $JobNo ?>">
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

<script type="text/javascript">
    $(document).ready(function(){
        // toastr.info('Job masih kosong');
        //     return false;
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
            var dari_tgl = $('#dari_tgl').val();
            var sampai_tgl = $('#sampai_tgl').val();
            var Alokasi = $('#Alokasi :selected').val();

            if (dari_tgl != '' && sampai_tgl != '') {

                if ( Alokasi !='') {
                    pencarian();
                }

            }

            return false;
        });


    });

    function tampil_loading(){
        $('#btn_cari').hide();
        $('.btn_loading').show();
    }

    function tutup_loading(){
        $('#btn_cari').show();
        $('.btn_loading').hide();
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
            });
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
        url: "<?= site_url('Permintaan_dana_keuangan/dapat_list_pd') ?>",
        data: {
            'JobNo': JobNo,
            'Alokasi': Alokasi,
            'dari_tgl': dari_tgl,
            'sampai_tgl': sampai_tgl,
        },
        dataType: "html",
        cache : false,
        beforeSend: function() {
            tampil_loading();
        },
        complete : function(){
            tutup_loading();

        },
        success: function(data) {
            $('.tampil_table').html(data);
        },
        error: function() {
            swal.fire('error','Terjadi Kesalahan','error');
            return false;
        }
    });

 }

 function aksi_pdf(LedgerNo=''){

    if (LedgerNo == '') {
        return false;
    }

    var ID = $('#aksi_pdf_'+LedgerNo);
    var NoPD = ID.attr('data-NoPD');
    var JobNo = ID.attr('data-JobNo');
    var Alokasi = ID.attr('data-Alokasi');
    var ApprovedByAK = ID.attr('data-ApprovedByAK');
    var url_saya = "<?=site_url("Permintaan_dana_keuangan/print_pd_keuangan/")?>"+LedgerNo+'/'+NoPD+'/'+JobNo+'/'+Alokasi;

    $('#aksi_loading_'+LedgerNo).show();
    ID.hide();

    ApprovedByAK = (ApprovedByAK == null || ApprovedByAK == '' ) ? 'kosong_ak' : ApprovedByAK;

    if (ApprovedByAK == 'kosong_ak') {
        $('#aksi_loading_'+LedgerNo).hide();
        ID.show();
        swal.fire('info','Approve PD terlebih dahulu','info');
        return false;

    }

    $('#aksi_loading_'+LedgerNo).hide();
    ID.show();
    window.open(url_saya,'_blank');


}

function btn_Approve(LedgerNo='',NoPD=''){

    if(LedgerNo == ''){
        return false;
    }

    var ID = $('#id_btn_approve_'+LedgerNo);
    var NoPD = ID.attr('data-NoPD');
    var ApprovedByAK = ID.attr('data-ApprovedByAK');
    var ApprovedByKT = ID.attr('data-ApprovedByKT');
    var ApprovedByKK = ID.attr('data-ApprovedByKK');



    if (ApprovedByAK == 'UNAPPROVE'){
        
        if (ApprovedByKT != 'kosong_KT'){
            Swal.fire('Info!','sudah di-approved oleh cost control','info');
            return false;
        }

        if (ApprovedByKK != 'kosong_KK'){
            Swal.fire('Info!','sudah di-approved oleh Keuangan','info');
            return false;
        }
    }


    Swal.fire({
        title: 'Anda Yakin?',
        text: " "+ApprovedByAK+" PD "+NoPD+" ?  ",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.value) {

            jQuery.ajax({
                url: '<?= site_url('Permintaan_dana_keuangan/aksi_Approve_PDKeuangan') ?>',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    'LedgerNo': LedgerNo,
                    'NoPD' : NoPD,
                    'ApprovedByAK' : ApprovedByAK

                },
                async: true,
                cache : false,
                beforeSend : function(){
                    $('#aksi_loading_'+LedgerNo).show();
                    $('#id_btn_approve_'+LedgerNo).hide();
                },
                complete: function(xhr, textStatus) {
                    $('#aksi_loading_'+LedgerNo).hide();
                    $('#id_btn_approve_'+LedgerNo).show();
                    pencarian();
                },
                success: function(data, textStatus, xhr) {

                    $.toast({
                        text: data.pesan,
                        position: 'top-right',
                        stack: false,
                        hideAfter: 3000,
                        icon: data.warna,

                    });
                    
                    
                },
                error: function(xhr, textStatus, errorThrown) {
                    Swal.fire('Error!','Terjadi Kesalahan','error');

                    return false;
                }
            });


        }
    })




}


</script>