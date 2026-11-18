<?php
$disab = '';
$info = '';
if ($pdkeu->ApprovedByAK != '') {
    $disab = 'disabled';
    $info = '
    <div class="col-sm-12 row">
    <div class="alert alert-info alert-dismissible text-black">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-info"></i> INFORMASI</h4>
                PD Sudah Dikunci, Unapprove PD untuk Melakukan Edit
              </div></div>';
}
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <label class="col-lg col-form-label"><?php echo $judul ?> </label>
            <p></p>

            <label for="inputName" class="col-sm col-form-label"><?php echo $pdkeu->JobNo ?></label> - <label for="inputName" class="col-sm col-form-label" id="JobNo"><?php echo $pdkeu->JobNm ?></label>
            <label for="inputName" class="col-sm col-form-label"><?php echo $pdkeu->Lokasi ?></label>
        </h1>
    </section>

    <section class="content-header">
        <!-- <a href="<?= site_url('job/sub_job/' . $mos->JobNo) ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a> -->
        <!-- <a href="#save" data-toggle="modal" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</a> -->
    </section>

    <!-- <Section class="content"> -->
    <section class="content-header">
        <?= $info ?>
        <div class="row">



            <div class="col-md-12 connectedSortable">
                <div class="box box-solid bg-blue-gradient">
                    <div class="box-header">
                        <h3 class="box-title">Entry PD</h3>
                        <div class="pull-right box-tools">
                            <button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer text-black">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box-body">
                                    <div class="box box-warning">
                                        <div class="box-header with-border">
                                            <h1 class="box-title">Entry Permintaan Dana</h1>

                                            <div class="box-tools pull-right">
                                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            <form id="FormEPD">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <input type="text" name="NoPD" id="NoPD" class="form-control" placeholder="No PD" value="<?= $pdkeu->NoPD ?>" readonly <?= $disab ?>>
                                                            <!-- <input type="text" name="status_kode" value=""> -->
                                                            <p></p>
                                                            <input type="text" name="NoRef" id="NoRef" class="form-control" placeholder="No. BAP/Ref Lapangan" value="<?= $pdkeu->NoRef ?>" <?= $disab ?>>
                                                            <p></p>
                                                            <input type="text" name="JobNo" id="JobNo" class="form-control" placeholder="JobNo" value="<?= $pdkeu->JobNo ?>" readonly <?= $disab ?>>
                                                            <p></p>
                                                            <label class="form-control"><input type="date" name="tglPermintaan" id="TglPermintaan" title="Tgl Permintaan" value="<?= $pdkeu->TglPD ?>" <?= $disab ?>>&nbsp Tgl Permintaan</label>
                                                            <p></p>
                                                            <label class="form-control"><input type="checkbox" id="ckPdJaminan" name="pd_jaminan" value="1" <?= ($pdkeu->Jaminan != 0) ? 'checked' : '' ?> <?= $disab ?>> &nbsp PD Jaminan</label>
                                                            <p></p>
                                                            <textarea name="Deskripsi" id="Deskripsi" cols="30" rows="3" class="form-control" placeholder="Deskripsi" <?= $disab ?>><?= $pdkeu->Deskripsi ?></textarea>
                                                            <p></p>
                                                            <label class="form-control"><input type="date" name="periode" id="periode" style="width: 30%;" title="Periode" value="<?= $pdkeu->PrdAwal ?>" <?= $disab ?>>
                                                                s/d <input type="date" name="periode2" id="periode2" style="width: 30%;" title="sampai" value="<?= $pdkeu->PrdAkhir ?>" <?= $disab ?>>Minggu ke &nbsp;<input type="text" name="mingguke" id="mingguke" style="width: 20%;" value="<?= $pdkeu->Minggu ?>" <?= $disab ?>></label>
                                                            <p></p>

                                                            <div class="form-group row">
                                                                <div class="col-sm-3">
                                                                    <input type="text" name="alokasi" id="alokasi" class="form-control" placeholder="Alokasi" value="<?= $pdkeu->Alokasi ?>" readonly <?= $disab ?>>
                                                                </div>
                                                                <div class="col-sm-9">
                                                                    <select name="tipeform" id="tipeform" class="form-control" disabled>
                                                                        <option value="0">--Pilih Type Form--</option>
                                                                        <?php
                                                                        foreach (GetTipeForm($pdkeu->Alokasi)->result() as $tipeform) { ?>

                                                                            <option value="<?= $tipeform->TipeForm ?>" <?= ($pdkeu->TipeForm == $tipeform->TipeForm) ? 'selected' : '' ?>><?= $tipeform->tipe_form_detail ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="box-header with-border">
                                                                <center style="background-color: cornflowerblue">
                                                                    <h1 class="box-title">Bukti Pendukung</h1>
                                                                </center>
                                                                <hr>
                                                                <div class="checkbox">
                                                                    <div class="form-group">

                                                                        <label>
                                                                            <input type="checkbox" name="BuktiPendukung[]" value="INV" <?= (CekBuktiPendukung($pdkeu->BuktiPendukung, 'INV') == TRUE) ? 'checked' : '' ?> <?= $disab ?>>
                                                                            Invoice/Kwitansi
                                                                        </label>
                                                                        &nbsp;
                                                                        &nbsp;
                                                                        &nbsp;
                                                                        &nbsp;
                                                                        &nbsp;
                                                                        &nbsp;
                                                                        &nbsp;
                                                                        &nbsp;
                                                                        &nbsp;
                                                                        &nbsp;
                                                                        &nbsp;
                                                                        <label>
                                                                            <input type="checkbox" name="BuktiPendukung[]" value="SJ" <?= (CekBuktiPendukung($pdkeu->BuktiPendukung, 'SJ') == TRUE) ? 'checked' : '' ?> <?= $disab ?>>
                                                                            Surat Jalan/Tanda Terima Lapangan
                                                                        </label>
                                                                        &nbsp;
                                                                        &nbsp;
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>
                                                                            <input type="checkbox" name="BuktiPendukung[]" value="PO" <?= (CekBuktiPendukung($pdkeu->BuktiPendukung, 'PO') == TRUE) ? 'checked' : '' ?> <?= $disab ?>>
                                                                            Copy PO
                                                                        </label>
                                                                        &nbsp;
                                                                        &nbsp;
                                                                        &nbsp;
                                                                        &nbsp;
                                                                        &nbsp;
                                                                        &nbsp;
                                                                        &nbsp;
                                                                        &nbsp;
                                                                        &nbsp;
                                                                        &nbsp;
                                                                        &nbsp;
                                                                        &nbsp;
                                                                        &nbsp;
                                                                        &nbsp;
                                                                        &nbsp;
                                                                        &nbsp;
                                                                        &nbsp;
                                                                        &nbsp;
                                                                        &nbsp;
                                                                        <label>
                                                                            <input type="checkbox" name="BuktiPendukung[]" value="FP" <?= (CekBuktiPendukung($pdkeu->BuktiPendukung, 'FP') == TRUE) ? 'checked' : '' ?> <?= $disab ?>>
                                                                            Faktur Pajak
                                                                        </label>
                                                                        &nbsp;
                                                                        &nbsp;
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>
                                                                            <input type="checkbox" name="BuktiPendukung[]" value="BAP" <?= (CekBuktiPendukung($pdkeu->BuktiPendukung, 'BAP') == TRUE) ? 'checked' : '' ?> <?= $disab ?>>
                                                                            Berita Acara Pembayaran
                                                                        </label>
                                                                        &nbsp;
                                                                        &nbsp;
                                                                        <label>
                                                                            <input type="checkbox" name="BuktiPendukung[]" value="BAOP" <?= (CekBuktiPendukung($pdkeu->BuktiPendukung, 'BAOP') == TRUE) ? 'checked' : '' ?> <?= $disab ?>>
                                                                            Berita Acara Opname Pekerjaan
                                                                        </label>
                                                                        &nbsp;
                                                                        &nbsp;
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <input type="text" name="Keterangan" id="keterangan" class="form-control" placeholder="Keterangan" value="<?= $pdkeu->Keterangan ?>" <?= $disab ?>>
                                                            <p></p>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="box-header with-border">
                                                                <center style="background-color: cyan">
                                                                    <h1 class="box-title"> Bayar Kepada</h1>
                                                                </center>
                                                                <p></p>
                                                                <!-- <select name="NoKO" id="NoKO" class="form-control" required>
                                                                    <option value=""></option>
                                                                </select> -->
                                                                <input type="text" name="NoKO" id="NoKO" class="form-control" value="<?= $pdkeu->NoKO ?>" readonly <?= $disab ?>>
                                                                <p></p>
                                                                <input type="text" name="NoTagihan" id="Notagihan" class="form-control" placeholder="No. Tagihan" value="<?= $pdkeu->NoTagihan ?>" <?= $disab ?>>
                                                                <p></p>
                                                                <input type="text" name="Id" id="VendorId" class="form-control" placeholder="ID Vendor" readonly <?= $disab ?>>
                                                                <p></p>
                                                                <input type="text" name="Nama" id="nama" class="form-control" placeholder="Nama Vendor" readonly <?= $disab ?>>
                                                                <p></p>
                                                                <input type="text" name="Telp" id="telp" class="form-control" placeholder="No Telepon" readonly <?= $disab ?>>
                                                                <p></p>
                                                                <input type="text" name="Npwp" id="npwp" class="form-control" placeholder="NPWP" readonly <?= $disab ?>>
                                                                <p></p>
                                                                <textarea name="Alamat" id="Alamat" class="form-control" placeholder="Alamat" readonly <?= $disab ?>></textarea>
                                                            </div>
                                                            <div class="box-header with-border">
                                                                <center style="background-color: cornflowerblue;">
                                                                    <h1 class="box-title"> Rekening Penerima</h1>
                                                                </center>
                                                                <p></p>
                                                                <input type="text" name="Norek" id="norek" class="form-control" placeholder="No. Rekening" value="<?= $pdkeu->NoRek ?>" <?= $disab ?>>
                                                                <p></p>
                                                                <input type="text" name="Anbank" id="anbank" class="form-control" placeholder="Atas Nama" value="<?= $pdkeu->AtasNama ?>" <?= $disab ?>>
                                                                <p></p>
                                                                <input type="text" name="Bank" id="Bank" class="form-control" placeholder="Bank" value="<?= $pdkeu->Bank ?>" <?= $disab ?>>
                                                                <!-- <select name="Bank" id="bank" class="form-control">
																				<option value="0">--Pilih Nama Bank--</option>

																			</select> -->
                                                                <p></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <a href="<?= site_url('ApprovePd/view/' . $pdkeu->JobNo) ?>" class="btn btn-danger btn-sm float-right" type="submit" name="BatalEPD" style="float: right;">
                                                    BATAL
                                                </a>
                                                <p></p>
                                                <button class="btn btn-success btn-sm float-right" type="submit" name="simpanEPD" id="simpanEPD" style="float: right;" <?= $disab ?>>
                                                    SIMPAN
                                                </button>
                                            </form>

                                            <!-- <h4 class="mt-5 ">Custom Content Above</h4> -->
                                            <ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="custom-content-above-home-tab" data-toggle="pill" href="#custom-content-above-home" role="tab" aria-controls="custom-content-above-home" aria-selected="true">Uraian PD</a>
                                                </li>
                                            </ul>
                                        </div>


                                    </div>

                                    <div class="modal-content">
                                        <div class="box box-success">
                                            <div class="box-header with-border">
                                                <center>
                                                    <h1 class="box-title">URAIAN PERMINTAAN DANA</h1>
                                                </center>

                                                <div class="box-tools pull-right">
                                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                                                </div>
                                            </div>
                                            <p></p>
                                            <!-- <a onclick="AddUraianPD()" href="javascript: void(0)" class="btn btn-warning"><i class="fa fa-plus"></i> TAMBAH</a> -->
                                            <p></p>
                                            <div class="tab-content" id="custom-content-above-tabContent">
                                                <div class="tab-pane show active" id="custom-content-above-home" role="tabpanel" aria-labelledby="custom-content-above-home-tab">
                                                    <div id="table_data">

                                                    </div>


                                                </div>

                                            </div>

                                        </div>

                                        <div class="modal fade" role="dialog" id="my-modal">
                                            <div class="modal-dialog" id="my-modal-dialog">
                                                <div class="modal-content">

                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title" id="my-modal-title"></h4>

                                                    </div>
                                                    <span class="my-loading" style="display: none;">Loading....</span>
                                                    <div class="modal-body" id="my-modal-body">

                                                    </div>

                                                </div>
                                            </div>
                                        </div>



                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <script>
        $(document).ready(function() {

            var JobNo = '<?= $pdkeu->JobNo ?>';
            var NoPD = '<?= $pdkeu->NoPD ?>';

            detailtable(JobNo, NoPD)

            $('#NoKO').attr('disabled', 'disabled');

            $('#tipeform').select2({
                width: "100%"
            });

            $('#NoKO').select2({
                width: "100%",
                placeholder: "Pilih Kontrak / PO"
            });

        })

        function detailtable(JobNo, NoPD) {
            $.ajax({
                type: "POST",
                url: "<?= site_url('Pd/getDetailUraianPD') ?>",
                data: {
                    JobNo: JobNo,
                    NoPD: NoPD
                },
                dataType: "HTML",
                success: function(response) {
                    $('#table_data').html(response);
                }
            });
        }

        $('#FormEPD').on("submit", function(e) {
            var TotalPD = $('#TotalPD').val();

            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '<?= site_url('Pd/do_edit_pd') ?>',
                data: $(this).serialize() + "&TotalPD=" + TotalPD,
                dataType: "JSON",
                success: function(response) {
                    if (response.status == 'success') {
                        $.toast({
                            heading: 'Berhasil',
                            text: 'Berhasil Di Edit',
                            position: 'bottom-right',
                            stack: false,
                            hideAfter: 1000,
                            icon: 'success',
                            beforeHide: function() {
                                window.location.href = "<?= site_url('Admin/pd/' . $pdkeu->JobNo) ?>";
                            }
                        })
                    }
                }
            });

        })

        // $("#FormEPD").on("submit", function(event) {
        //     event.preventDefault();
        //     $.post("<?= site_url('Pd/do_edit_pd') ?>", $(this.serialize()))
        //         .done(function(data) {
        //             console.log(data);

        //         });

        // });
    </script>
</div>

</section>

</section>
