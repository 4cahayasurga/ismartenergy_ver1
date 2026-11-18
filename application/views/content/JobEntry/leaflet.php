
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <label class="col-lg col-form-label"><?php echo $judul ?> </label><p></p>
            <label for="inputName" type="input" name="JobNo" class="col-sm col-form-label" id="JobNo"><?php echo $leaflet->JobNo ?></label> - <label for="inputName" name="JobNm" class="col-sm col-form-label" id="JobNo"><?php echo $leaflet->JobNm ?></label>
            <br><small><label for="inputName" class="col-sm col-form-label" id="JobNo"><?php echo $leaflet->Lokasi ?></label>

            </h1>
            <ol class="breadcrumb">
                <li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
                <li class="active"><?php echo $judul ?></li>
            </ol>
        </section>

        <section class="content-header">
            <a href="<?= site_url('job/sub_job/'.$leaflet->JobNo) ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
            <!-- <a href="#save" data-toggle="modal" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</a> -->
        </section>   

        <!-- <Section class="content"> -->
            <section class="content-header">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-warning">
                            <div class="box-header with-border">
                                <div class="box-body"> 

                                    <form class="form-horizontal" id="myform" >
                                        <div class="form-group">
                                            <label class="control-label col-sm-2">Input File PDF LEAFLET Proyek</label>
                                            <div class="col-sm-8">
                                                <input type="file" id="leaflet" name="leaflet" accept="application/pdf" class="form-control" required >
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="hidden" name="JobNo" id="JobNo" value="<?=$leaflet->JobNo?>" >
                                                <button type="submit" class="btn btn-primary" id="my-upload"><i class="fa fa-save"></i> Upload</button>
                                            </div>
                                        </div>
                                    </form>


                                </div>
                            </div>
                        </div>                        
                    </div>
                    <input type="hidden" name="JobNoNew" id="JobNoNew" value="<?=$leaflet->JobNo?>" >
                    <div id="tampil"></div>

                    


                </section>          

                <script>
                        function HapusForm(){
                            $('#myform')[0].reset();
                        }
                        function mytable(){
                            var JobNo = $('#JobNoNew').val();
                            $.ajax({
                                url: '<?=site_url('Leaflet/getTable')?>',
                                type: 'POST',
                                dataType: 'HTMl',
                                data: {
                                    'type' : 'tampil',
                                    'JobNo' : JobNo
                                },
                            })
                            .done(function(data) {
                                // $('#mytable').show();
                                $('#tampil').html(data);
                                

                            })
                            .fail(function() {

                            })
                            .always(function() {

                            });
                            
                        }

                        $(function(){
                            mytable();
                            HapusForm();
                            $('#myform').submit(function(e){
                                e.preventDefault();
                                var JobNo = $('#JobNo').val();

                                var formData = new FormData(this);
                                $.ajax({
                                    url: '<?= site_url('Leaflet/lakukan_simpan') ?>',
                                    type: 'POST',
                                    dataType: 'HTML',
                                    data: formData,
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    // data: {'type' : 'myupload', 'JobNo' : JobNo},
                                    beforeSend : function(){
                                        $('#my-upload').html(' <i class="fa fa-spinner"></i> Loading..');
                                    }
                                })
                                .done(function(data) {
                                    $('#my-upload').html(' <i class="fa fa-save"></i> Upload');
                                    mytable();
                                    HapusForm();
                                    // alert(data);

                                    if (data == 'gagalupload') {

                                        $.toast({
                                            heading: 'Gagal Mengupload File',
                                            position: 'bottom-right',
                                            showHideTransition: 'slide',
                                            icon: 'info',
                                            hideAfter: 1000
                                        })
                                        return false;

                                    }

                                    if (data == 'kosong') {

                                        $.toast({
                                            heading: 'File Masih Kosong',
                                            position: 'bottom-right',
                                            showHideTransition: 'slide',
                                            icon: 'info',
                                            hideAfter: 1000
                                        })
                                        return false;

                                    }

                                    if (data == '1') {

                                        $.toast({
                                            heading: 'Berhasil',
                                            position: 'bottom-right',
                                            showHideTransition: 'slide',
                                            icon: 'success',
                                            hideAfter: 1000
                                        })

                                        return false;

                                    }else if (data == '0') {
                                        $.toast({
                                            heading: 'Terjadi Kesalahan',
                                            position: 'bottom-right',
                                            showHideTransition: 'slide',
                                            icon: 'error',
                                            hideAfter: 1000
                                        })
                                        return false;
                                    }


                                })
                                .fail(function() {

                                })
                                .always(function() {

                                });
                                
                            })

                            
                        })
                    </script>
                </div>
            </section>

        </section>



        <!-- </Section> -->





