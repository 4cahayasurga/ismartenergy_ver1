<style type="text/css">
    body{
        font-family: sans-serif;
        font-size : 12px !important;
    }
    =
</style>

<div class="content-wrapper">


    <section class="content-header">
        <h6><b><?=$judul?></b></h6>
        <p></p>

        <label  class="col-sm col-form-label ubahlabel"><?=$dapat_data_job['JobDetail']?></label>
        <br>
        <label for="inputName" class="col-sm col-form-label"><?=$dapat_data_job['Lokasi']?></label>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('dashboard')  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?=$judul?></li>
        </ol>
        <br>
        <button class="btn btn-xs" style="background-color: #95a5a6;color:white;" onclick="history.back()"><i class="fa fa-arrow-left"></i> Kembali</button>
    </section>

    <section class="content">

        <form id="form_saya">

            <div class="col-12">

                <div class="box">
                    

                    <div class="box-header">
                        <small>DATA KSO</small>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                        </div>
                    </div>

                    <div class="box-body">

                        <!-- <div class="row">
                            <div class="col-sm-2">
                                
                            </div>
                        </div> -->


                        <div class="row">

                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>KSO</label>
                                    <select class="form-control input-sm" name="KSO" id="KSO" onchange="validasi_KSO()">
                                        <option value="0" <?=($dapat_data_job['KSO'] == 0 ) ? 'selected' : ''  ?> >No</option>
                                        <option value="1" <?=($dapat_data_job['KSO'] == 1 ) ? 'selected' : ''  ?>  >Yes</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group">

                                    <label>Own</label>
                                    <select class="form-control input-sm" name="Own" id="Own">
                                        <option value="">Kosong</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                    </select>

                                    
                                </div>

                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Type Managerial</label>
                                    <select class="form-control input-sm" name="TipeManagerial" id="TipeManagerial">
                                        <option value="" <?=($dapat_data_job['TipeManajerial'] == NULL ) ? 'selected' : ''  ?> >Kosong</option>
                                        <option value="Single" <?=($dapat_data_job['TipeManajerial'] == 'Single' ) ? 'selected' : ''  ?> >Single</option>
                                        <option value="JO Partial" <?=($dapat_data_job['TipeManajerial']  == 'JO Partial' ) ? 'selected' : ''  ?>>JO Partial</option>
                                        <option value="JO Integrated" <?=($dapat_data_job['TipeManajerial']  == 'JO Integrated'  ) ? 'selected' : ''  ?>>JO Integrated</option>
                                    </select>
                                </div>

                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>No. Rekening KSO</label>
                                    <input type="text" class="form-control input-sm" name="NoRekKSO" id="NoRekKSO" placeholder="Isi No. Rekening KSO" value="<?=$dapat_data_job['NoRekKSO']?>" required >
                                </div>
                            </div>



                        </div>
                    </div>


                </div>

            </div>

        </form>

    </section>

</div>