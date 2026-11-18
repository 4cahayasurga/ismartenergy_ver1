<style>
    .box1 {
        width: 10px;
        height: 10px;
        background-color: #2ecc71;
    }

    .box2 {
        width: 10px;
        height: 10px;
        background-color: #f1c40f;
    }

    .box3 {
        width: 10px;
        height: 10px;
        background-color: #e74c3c;
    }
</style>
<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <label class="col-lg col-form-label"><?php echo $judul ?> </label>
            <p></p>
            <label class="col-sm col-form-label"><?php echo $spr->JobNo ?></label> - <label><?php echo $spr->JobNm ?></label>
            <br><small><label class="col-sm col-form-label"><?php echo $spr->Lokasi ?></label>

        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?php echo $judul ?></li>
        </ol>
    </section>

    <section class="content-header">

        <a href="<?= site_url('Job/spr/' . $spr->JobNo) ?>" class="btn btn-warning btn-xs"><i class="fa fa-arrow-left"></i> Kembali</a>
        <!-- <a href="#save" data-toggle="modal" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</a> -->

    </section>

    <section class="content">
        <div class="box">
            <div class="box-body">

                <form id="myform">

                    <div class="col-xs-12">

                        <div class="box">
                            <div class="box-header bg-purple-gradient">
                                <h6>INFORMASI DASAR</h6>
                            </div>
                            <div class="box">

                                <div class="col-xs-6 row">
                                    <label>Job No</label>
                                    <input type="text" class="form-control form-control-sm" value="<?php echo $spr->JobNo . " - " . $spr->JobNm ?>" disabled>
                                    <label>No SPR</label>
                                    <input type="text" name="NoSpr" id="NoSpr" class="form-control form-control-sm" disabled>
                                    <label>Tgl SPR</label>
                                    <input type="date" name="TglSpr" id="TglSpr" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>" required="required">
                                </div>

                                <div class="col-xs-6">
                                    <label>Kepada</label>
                                    <input type="text" name="Kepada" id="Kepada" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" required="required">
                                    <label>Tgl Penggunaan</label>
                                    <input type="Date" name="TglPenggunaan" id="TglPenggunaan form-control-sm" class="form-control" required="required">
                                    <label>Untuk Pekerjaan</label>
                                    <textarea class="form-control form-control-sm" name="UtkPekerjaan" id="UtkPekerjaan" oninput="this.value = this.value.toUpperCase()"></textarea>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-header bg-blue-gradient">
                                <!-- <h6 class="box-title" style="text-align: center;">Informasi Dasar</h6> -->
                                <h6>INFORMASI PENGIRIMAN</h6>
                            </div>
                            <div class="box">

                                <div class="col-xs-6 row">
                                    <label>Nama PIC Logistik</label>
                                    <input type="text" name="NamaKirim" id="NamaKirim" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                    <label>Telepon / HP</label>
                                    <input type="text" name="TeleponKirim" id="TeleponKirim" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" required="required">
                                </div>


                                <div class="col-xs-6">
                                    <label>Alamat Dikirim</label>
                                    <textarea class="form-control form-control-sm" name="AlamatKirim" id="AlamatKirim" oninput="this.value = this.value.toUpperCase()"></textarea>
                                    <label>Waktu Pengiriman</label>
                                    <input type="Date" name="TglKirim" id="TglKirim" class="form-control form-control-sm" required="required">
                                </div>

                            </div>
                        </div>


                        <!-- <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal">Close</button> -->
                    </div>

                    <div class="col-xs-12" style="padding-bottom:10px;padding-top:10px;">
                        <input type="hidden" name="JobNo" id="JobNo" value="<?= $spr->JobNo ?>">
                        <input type="hidden" name="NoSPR" id="NoSPR" value="<?= $NoSPR ?>">
                        <button type="submit" id="simpan" class="btn btn-primary pull-right">Save</button>
                    </div>
                </form>

                <div class="col-xs-12">
                    <div class="box">
                        <br>
                        <a href="javascript:void(0)" class="btn btn-flat btn-danger btn-sm" data-JobNo="<?= $spr->JobNo ?>" data-NoSPR="<?= $NoSPR ?>" id="tambah-detail">TAMBAH</a><br><br>
                        <span class="text-red my-loading" style="display:none;">Loading...</span>

                        <div class="box-body">
                            <table class="table">
                                <tr>
                                    <td>
                                        <div class="box1"></div>Permintaan Anda MELEBIHI 80% Dari RAP
                                    </td>
                                    <td>
                                        <div class="box2"></div>Permintaan Anda Sudah Batas Maximal RAP
                                    </td>
                                    <td>
                                        <div class="box3"></div>Permintaan Anda Melebihi RAP
                                    </td>
                                </tr>
                            </table>

                            <div class="detail-table"></div>

                            


                        </div>
                    </div>
                    <!-- <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal">Close</button> -->
                </div>




            </div>
        </div>
    </section>

</div>

<div class="modal fade" id="MyModal">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header modal-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="MyModalTitle"></h4>
            </div>
            <div class="modal-body" id="MyModalBody">

            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        
        show_detail_spr();
        mulai();

        $('#myform').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                    url: '<?= site_url('Spr/simpanSpr') ?>',
                    type: 'POST',
                    dataType: 'JSON',
                    data: $(this).serialize(),
                    beforeSend: function() {
                        $('#simpan').attr('disabled', 'disabled').text('Loading...');
                    }
                })
                .done(function(response) {
                    $('#simpan').removeAttr('disabled').text('Save');
                    if (response.status == 'success') {
                        $.toast({
                            heading: 'Berhasil',
                            position: 'bottom-right',
                            showHideTransition: 'slide',
                            icon: 'success',
                            hideAfter: 100,
                            afterHidden: function() {
                                window.location.href = response.url;
                            }
                        })
                    }
                })
                .fail(function() {
                    console.log("error");
                })
                .always(function() {
                    console.log("complete");
                });




        })

        $('#tambah-detail').click(function() {

            var JobNo = $(this).attr('data-JobNo');
            var NoSPR = $(this).attr('data-NoSPR');

            $('#MyModal').modal('show');
            $('#MyModalTitle').text('Data Entry Detail');

            $.ajax({
                type: "POST",
                url: "<?= site_url('Spr/GetModal') ?>",
                data: {
                    'JobNo': JobNo,
                    'NoSPR': NoSPR,
                    'type': 'tambah'
                },
                dataType: "HTML",
                beforeSend: function() {
                    $('#tambah-detail').text('Loading...');
                },
                success: function(response) {
                    $('#tambah-detail').text('Tambah');
                    $('#MyModalBody').html(response);
                }
            });
        })

    })




    function mulai() {
        $('#simpan').attr('disabled', 'disabled');

        $.ajax({
                url: '<?= site_url("Spr/cekTemp") ?>',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    'id': 'id'

                },
            })
            .done(function(response) {
                if (response.cek > 0) {
                    $('#simpan').removeAttr('disabled');
                }
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });


    }

    function show_detail_spr() {

        var NoSPRSem = "<?= $NoSPR ?>";
        var JobNoSem = "<?= $spr->JobNo ?>";
        $.ajax({
                url: '<?= site_url("Spr/detail_table") ?>',
                type: 'POST',
                dataType: 'HTML',
                data: {
                    'JobNo': JobNoSem,
                    'NoSPR': NoSPRSem
                },
                beforeSend: function() {
                    $('.my-loading').show();
                }
            })
            .done(function(response) {
                $('.my-loading').hide();
                $('.detail-table').html(response);

            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                mulai()
            });


    }
</script>