<div class="content-wrapper">

    <section class="content-header">
        <h5>
            <label class="col-lg col-form-label"><?php echo $judul ?> </label>
            <small class="text-red"><br><?= $JobNo . ' - ' . $JobNm  ?> </small><br>
            <small><?= $Lokasi ?></small>

        </h5>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?php echo $judul ?></li>
        </ol>
    </section>

    <section class="content-header">
        <button onclick="history.back()" class="btn btn-sm btn-link" style="background-color:#34495e;color:white;"><i class="fa fa-arrow-left"></i> Back</button>

        <button onclick="request_list_ko_addendum()" class="btn btn-sm btn-default">Refresh Table</button>
    </section>

    <section class="content-header">
        <?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>

        <div class="row">

            <div class="col-sm-12">
                <div class="box">
                    <div class="box-body">
                        <div class=""></div>
                        <div class="table_saya">

                        </div>
                    </div>
                    <div class="overlay loadingsaya" style="display:none;">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div>
            </div>


            

        </div>


    </section>

</div>

<section>

</section>

<input type="hidden" name="JobNo" id="JobNo" value="<?= $JobNo ?>">


<div class="modal fade" id="modal_batal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <center>
                    <h3 class="modal-title" id="exampleModalLongTitle">PEMBATALAN PO / KO</h3>
                </center>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal_batal_body">

                <form id="form_saya">


                    <div class="col-sm-12 text-center">
                        <h4><label for="" class="judul_modal">Anda Yakin ingin membatalkan PO Berikut..?</label></h4>

                        <h5 class="h5_detail"><br></h5>
                        <h5 class="h5_total"></h5>
                        <label for="">Alasan Reject : </label><br>
                        <textarea name="AlasanBatal" id="AlasanBatal" placeholder="Isi Disini" cols="100" rows="5" required></textarea>
                        <br>
                        <input type="hidden" name="LedgerNo" id="my_ledgerno" readonly >
                        <button class="btn btn-md btn-primary pull-right btn_simpan" type="submit">Simpan</button>

                    </div>

                </form>

                
            </div>
            
        </div>
    </div>
</div>


<div class="modal fade" id="modal_hapus_pembatalan" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <center>
                    <h3 class="modal-title" id="exampleModalLongTitle">HAPUS PEMBATALAN PO / KO</h3>
                </center>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal_body_hapus_pembatalan">

                <form id="form_hapus_pembatalan">


                    <div class="col-sm-12 text-center">
                        <h4><label for="" class="judul_modal">Anda Yakin ingin Hapus Pembatalan PO Berikut..?</label></h4>

                        <h5 class="h5_detail"><br></h5>
                        <h5 class="h5_total"></h5>
                        <br>
                        <input type="hidden" name="LedgerNo" id="my_ledgerno_batal" readonly >
                        <button class="btn btn-md btn-primary pull-right btn_simpan" type="submit">Simpan</button>

                    </div>

                </form>

                
            </div>
            
        </div>
    </div>
</div>

<script>
    $(function() {
        request_list_pembatalan_ko();

        $('#form_saya').submit(function(e) {

            e.preventDefault();

            $.ajax({
                url: '<?=site_url('Pembatalan_ko/lakukan_cancel')?>',
                type: 'post',
                dataType: 'html',
                data: $(this).serialize(),
                beforeSend : function(){
                    $('.loadingsaya').show();   
                }
            })
            .done(function(data) {
                $('.loadingsaya').hide();
                request_list_pembatalan_ko();
                $('#modal_batal').modal('hide');


                if (data == 'berhasil') {
                    $.toast({
                        text: 'Berhasil',
                        position: 'bottom-right',
                        stack: false,
                        hideAfter: 1000,
                        icon: 'success',

                    });
                }else  {
                    $.toast({
                        text: 'Gagal',
                        position: 'bottom-right',
                        stack: false,
                        hideAfter: 1000,
                        icon: 'error',

                    });
                }

                
            })
            .fail(function() {
                $('.loadingsaya').hide();
                $.toast({
                    text: 'Terjadi Kesalahan',
                    position: 'bottom-right',
                    stack: false,
                    hideAfter: 1000,
                    icon: 'error',

                });
            })
            .always(function() {
            });
            
        });

        $('#form_hapus_pembatalan').submit(function(e) {

            e.preventDefault();

            $.ajax({
                url: '<?=site_url('Pembatalan_ko/hapus_pembatalan')?>',
                type: 'post',
                dataType: 'html',
                data: $(this).serialize(),
                beforeSend : function(){
                    $('.loadingsaya').show();   
                }
            })
            .done(function(data) {
                $('.loadingsaya').hide();

                // alert(data);
                // return false;
                request_list_pembatalan_ko();
                $('#modal_hapus_pembatalan').modal('hide');


                if (data == 'berhasil') {
                    $.toast({
                        text: 'Berhasil',
                        position: 'bottom-right',
                        stack: false,
                        hideAfter: 1000,
                        icon: 'success',

                    });
                }else  {
                    $.toast({
                        text: 'Gagal',
                        position: 'bottom-right',
                        stack: false,
                        hideAfter: 1000,
                        icon: 'error',

                    });
                }

                
            })
            .fail(function() {
                $('.loadingsaya').hide();
                $.toast({
                    text: 'Terjadi Kesalahan',
                    position: 'bottom-right',
                    stack: false,
                    hideAfter: 1000,
                    icon: 'error',

                });
            })
            .always(function() {
            });
            
        });

    })

    function request_list_pembatalan_ko() {

        var JobNo = $('#JobNo').val();
        $.ajax({
            url: '<?= site_url('Pembatalan_ko/request_list_pembatalan_ko') ?>',
            type: 'post',
            dataType: 'html',
            data: {
                'JobNo': JobNo
            },
            beforeSend: function() {
                $('.loadingsaya').show();
            },
            success: function(data) {
                $('.loadingsaya').hide();
                $('.table_saya').html(data);
            },
            error: function() {
                $('.loadingsaya').hide();
                $.toast({
                    text: 'Terjadi Kesalahan',
                    position: 'bottom-right',
                    stack: false,
                    hideAfter: 1000,
                    icon: 'error',

                });
            }
        });

    }

    function addCommas(nStr) {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }


    function btn_cancel(LedgerNo, JobNo, NoKO, VendorNm, SubTotal){
        var a = parseInt(SubTotal.replace(/\,(\d\d)$/, ".$1").replace(',',''));

        // $('.judul_modal').text('Anda Yakin ingin membatalkan PO Berikut..?');

        $('.h5_detail').text(NoKO+' - '+VendorNm);
        $('.h5_total').text('Total = Rp. '+ addCommas(a));
        $('#my_ledgerno').val(LedgerNo);

        $('#modal_batal').modal('show');
    }


    function hapus_cancel(LedgerNo, JobNo, NoKO, VendorNm, SubTotal){
        var a = parseInt(SubTotal.replace(/\,(\d\d)$/, ".$1").replace(',',''));

        // $('#judul_modal').text('Anda Yakin ingin Hapus Cancel PO Berikut..?');
        $('.h5_detail').text(NoKO+' - '+VendorNm);
        $('.h5_total').text('Total = Rp. '+ addCommas(a));
        $('#my_ledgerno_batal').val(LedgerNo);

        $('#modal_hapus_pembatalan').modal('show');
    }



</script>