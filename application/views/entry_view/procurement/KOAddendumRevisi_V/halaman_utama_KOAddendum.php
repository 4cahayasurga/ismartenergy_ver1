<style type="text/css">

    .huruf12{
        font-size: 12px !important;
    }
    .huruf14{
        font-size: 14px !important;
    }
    .huruf15{
        font-size: 15px !important;
    }
    .huruf16{
        font-size: 16px !important;
    }
    .border_merah{
        border:1px solid red;
    }
    .border_biasa{
        border:1px solid;
    }
</style>
<div class="content-wrapper">

    <section class="content-header">
        <h1 style="text-decoration: underline;"><?=$judul?></h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?=$judul?></li>
        </ol>
        <br>
        <a href="<?=site_url('Job/sub_job/'.$JobNo)?>" class="btn btn-md btn-danger "><i class="fa fa-arrow-left"></i> BACK</a>
    </section>

    <div class="content">
        <div class="box box-primary">

            <!-- === start box header == -->
            <div class="box-header">
                <form id="MyForm">
                    <div class="row">
                        <div class="col-sm-12">
                            <?=(!$this->session->userdata('message')) ? '' : $this->session->userdata('message')?>
                            <div class="table-responsive">
                                <table width="100%" border="0">
                                    <tr>
                                        <th width="100px" >Job No</th>
                                        <td width="10px" class="text-center">:</td>
                                        <td width="500px">
                                            <select name="JobNo" id="JobNo" class="form-control select2" required>
                                                <option value="">:: Pilih salah satu ::</option>
                                                <?php foreach ($ListJob as $row): ?>
                                                    <option value="<?=$row->JobNo?>" <?=($JobNo == $row->JobNo) ? 'selected' : '' ?>  ><?=$row->JobNo.' - '.$row->JobNm?></option>
                                                <?php endforeach ?>
                                            </select>

                                        </td>
                                        <td></td>
                                    </tr>
                                </table>

                                <table width="100%" border="0" style="margin-top:5px;">
                                    <tr>
                                        <th width="100px" >No. KO</th>
                                        <td width="10px" class="text-center">:</td>
                                        <td width="250px">
                                            <select name="NoKO" id="NoKO" class="form-control select2" required>
                                                <option value="">:: Pilih salah satu ::</option>
                                            </select>
                                            <button class="btn btn-md btn-default disabled huruf14" id="btnLoading" style="display:none;"><i class="fa fa-refresh"></i> LOADING...</button>
                                        </td>
                                        <td></td>
                                    </tr>
                                </table>

                                <table width="100%" border="0" style="margin-top:5px;">
                                    <tr>
                                        <th width="100px" ></th>
                                        <td width="10px" class="text-center"></td>
                                        <td width="250px">
                                            <button type="submit" class="btn btn-md btn-default" id="btnTampilkan">TAMPILKAN</button>
                                        </td>
                                        <td></td>
                                    </tr>
                                </table>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- === end box header == -->

            <!-- === start box body == -->
            <div class="box-body">

                <div class="row" style="margin-bottom:10px;">
                    <div class="col-sm-6">
                        <table width="100%" border="0">
                            <tr>
                                <td width="100px"> <button class="btn btn-default btn-md" id="btnTambahAddendum" onclick="btnTambahAddendum()">TAMBAH ADDENDUM/REVISI</button></td>
                                <td width="170px" style="padding-left:10px;">Alasan Addendum/Revisi*  </td>
                                <td>
                                    <input type="text" name="AlasanAddendum" id="AlasanAddendum" class="form-control" placeholder="Ketik disini">
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>

                <div class="row" id="tblTiruan">
                    <div class="col-sm-12">
                        <div class="table-responsive">


                            <!-- == START ==  -->
                            <table border="0" width="100%">
                                <tr>
                                    <th class="text-center bg-gray-active color-palette huruf16" style="padding-top:5px;padding-bottom:5px;">CURRENT ADDENDUM/REVISI</th>
                                </tr>
                            </table>
                            <p></p>
                            <table style="width: 80% !important;" class="table table-bordered table-striped table-hover table-condensed huruf14">
                                <thead>
                                    <tr class="bg-light-blue  color-palette">
                                        <th class="text-center">No. KO</th>
                                        <th class="text-center" >Tgl KO</th>
                                        <th class="text-center" >Penerima Kontrak</th>
                                        <th class="text-center" >Tipe KO</th>
                                        <th class="text-center" >Addendum/Revisi Ke</th>
                                        <th class="text-center" >Total KO (Rp)</th>
                                        <th class="text-center">KO Approved By</th>
                                        <th class="text-center">KO Approved On</th>
                                        <th class="text-center" width="200px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-bold" colspan="8">Data tidak ditemukan</td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- == END == -->

                            <p></p>
                            <!-- == START ==  -->
                            <table border="0" width="100%">
                                <tr>
                                    <th class="text-center bg-gray-active color-palette huruf16" style="padding-top:5px;padding-bottom:5px;">HISTORY ADDENDUM/REVISI</th>
                                </tr>
                            </table>
                            <p></p>
                            <table style="width: 80% !important;" class="table table-bordered table-striped table-hover table-condensed huruf14">
                                <thead>
                                    <tr class="bg-light-blue  color-palette">
                                        <th class="text-center">No. KO</th>
                                        <th class="text-center">Tgl KO</th>
                                        <th class="text-center">Penerima Kontrak</th>
                                        <th class="text-center">Tipe KO</th>
                                        <th class="text-center">Addendum/Revisi Ke</th>
                                        <th class="text-center">Total KO (Rp)</th>
                                        <th class="text-center">KO Approved By</th>
                                        <th class="text-center">KO Approved On</th>
                                        <th class="text-center">Alasan Addendum</th>
                                        <th class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-bold" colspan="9">Data tidak ditemukan</td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- == END == -->


                        </div>
                    </div>
                </div>

                <div id="tampiltable_KOAddendum">

                </div>

            </div>
            <!-- === end box body == -->

        </div>
    </div>

</div>

<div class="clearfix"></div>
<script src="<?=base_url('assets/select2_baru.js')?>"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.select2').select2();

        if ($('#MyForm #JobNo option:selected').val() !='') {
            JobNo_Berganti();
        }

        $('#MyForm #JobNo').change(function(){
            JobNo_Berganti();
        });

        $('#MyForm #NoKO').change(function(){
            if ($('#MyForm #JobNo').val() !='') {
                tampiltable_KOAddendum();
            }
        })


        $('#MyForm').submit(function(e){
            e.preventDefault();
            var JobNo = $('#MyForm #JobNo option:selected').val();
            var NoKO = $('#MyForm #NoKO option:selected').val();

            if (JobNo=='') {
                return false;
            }

            if (NoKO=='') {
                return false;
            }

            tampiltable_KOAddendum();
        })
    });

    function tampiltable_KOAddendum(){
        var JobNo = $('#MyForm #JobNo option:selected').val();
        var NoKO = $('#MyForm #NoKO option:selected').val();

        if (JobNo=='') {
            alert('Job masih kosong');
            return false;
        }

        if (NoKO=='') {
            alert('No. KO masih kosong');
            return false;
        }

        $.ajax({
            url: "<?=site_url('Entry/Procurement/KOAddendumRevisi_C/tampiltable_KOAddendum')?>",
            type: "POST",
            dataType: "HTML",
            data: {JobNo : JobNo, NoKO : NoKO},
            beforeSend : function(){
                $('#MyForm #btnTampilkan').attr('disabled',true);
                $('#tampiltable_KOAddendum').html('');
            }, complete : function(){
                $('#MyForm #btnTampilkan').attr('disabled',false);
            }
        })
        .done(function(data) {
            // alert(data);
            $('#tblTiruan').hide();
            $('#tampiltable_KOAddendum').show();
            $('#tampiltable_KOAddendum').html(data);
        })
        .fail(function() {
            alert('Error');
            return false;
        });
    }

    function JobNo_Berganti(){
        var JobNo = $('#MyForm #JobNo option:selected').val();

        if (JobNo == '') {
            return false;
        }

        $.ajax({
            url: "<?=site_url('Entry/Procurement/KOAddendumRevisi_C/Get_ListNoKO')?>",
            type: "POST",
            dataType: "HTML",
            data: {JobNo : JobNo},
            beforeSend : function(){
                $('#NoKO').next(".select2-container").hide();
                $('#MyForm #btnLoading').show();
                $('#MyForm #JobNo').attr('disabled',true);
                // $('#tampiltable_KOAddendum').html('');
            }, complete : function(){
                $('#NoKO').next(".select2-container").show();
                $('#MyForm #btnLoading').hide();
                $('#MyForm #JobNo').attr('disabled',false);
            }
        })
        .done(function(data) {
            $('#MyForm #NoKO').html(data);
            if ($('#MyForm #NoKO option:selected').val() !='') {
                tampiltable_KOAddendum();
            }
            
        })
        .fail(function() {
            alert('Error');
            return false;
        });
        
    }

    function btnTambahAddendum(){
        var JobNo = $('#MyForm #JobNo option:selected').val();
        var NoKO = $('#MyForm #NoKO option:selected').val();

        // alert(NoKO)

        if (JobNo=='') {
            alert('Job masih kosong');
            return false;
        }

        if (NoKO=='') {
            alert('No. KO masih kosong');
            return false;
        }

        var AlasanAddendum = $('#AlasanAddendum').val();
        if (AlasanAddendum=='') {
            $('#AlasanAddendum').css("border", "1px solid red");
            $('#AlasanAddendum').focus();
            return false;
        }

        $.ajax({
            url: "<?=site_url('Entry/Procurement/KOAddendumRevisi_C/TambahAddendum')?>",
            type: "POST",
            dataType: "JSON",
            data: {
                JobNo : JobNo, 
                NoKO : NoKO, 
                AlasanAddendum : AlasanAddendum
            },
            beforeSend : function(){
                $('#btnTambahAddendum').attr('disabled',true).text('LOADING...');
            }
        })
        .done(function(data) {
            $('#btnTambahAddendum').attr('disabled',false).text('TAMBAH ADDENDUM/REVISI');
            if (data.flag=='berhasil') {
                window.location.href="<?=site_url('Entry/Procurement/KOAddendumRevisi_C/index/')?>"+JobNo;
                return false;
            }else{
                alert(data.pesan);
            }

            // alert(data.pesan);
        })
        .fail(function() {
            alert('Error');
        });
        
    }


</script>