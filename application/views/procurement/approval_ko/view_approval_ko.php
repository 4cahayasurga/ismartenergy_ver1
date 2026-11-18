<style type="text/css">
    .marginatas5{
        margin-top: 5px;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h3>
            <label class="col-lg col-form-label"><?php echo $judul ?> </label>
            <p></p>
        </h3>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?php echo $judul ?></li>
        </ol>
    </section>

    <section class="content-header">
        <button onclick="history.back()" class="btn btn-md btn-link" style="background-color:red;color:white;"><i class="fa fa-arrow-left"></i> Back</button>
    </section>

    <section class="content-header">
        <?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>
        <div class="row" id="MOD-IN">

            <div class="col-sm-12">

                <div class="box">

                    <div class="box-header">

                        <form id="myform">


                            <div class="table-responsive">

                                <table border="0" width="100%">
                                    <tr>
                                        <th width="100px">Tgl KO.</th>
                                        <td width="20px" class="text-center">:</td>
                                        <td width="200px">
                                            <input type="date" name="PrdAwal" id="PrdAwal" class="form-control" value="<?=$PrdAwal?>">
                                        </td>
                                        <td width="50px" class="text-center">s.d</td>
                                        <td width="200px">
                                            <input type="date" name="PrdAkhir" id="PrdAkhir" class="form-control" value="<?=$PrdAkhir?>">
                                        </td>
                                        <td></td>
                                    </tr>
                                </table>

                                <table border="0" width="100%" class="marginatas5">
                                    <tr>
                                        <th width="100px">Tipe</th>
                                        <td width="20px" class="text-center">:</td>
                                        <td width="400px">
                                            <label class="radio-inline">
                                                <input type="radio" name="Tipe" value="belum_approve" checked>Yang Belum Di Approve
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="Tipe" value="sudah_approve"> Yang Sudah Di Approve
                                            </label>
                                        </td>
                                        
                                        <td></td>
                                    </tr>
                                </table>

                                <table border="0" width="100%" class="marginatas5">
                                    <tr>
                                        <th width="100px"></th>
                                        <td width="20px" class="text-center"></td>
                                        <td width="400px"><button type="submit" class="btn btn-primary btn-md">Tampilkan</button></td>

                                        <td></td>
                                    </tr>
                                </table>


                            </div>

                        </form>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                        </div>

                    </div>

                </div>
                <!-- <div class="tbl_pilihan_ko"></div> -->
            </div>

            <div id="TampilDisini"></div>



        </div>




    </section>

    <div class="modal fade" id="modal_approve">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button class="close" data-dismiss="modal"><span>&times;</span></button>
                    <center>
                        <h4 class="modal-title" id="myModalLabel">Approval Confirmation</h4>
                    </center>
                </div>
                <form id="form_approve">
                    <div class="modal-body" id="modal_body_approve">




                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_unapprove">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button class="close" data-dismiss="modal"><span>&times;</span></button>
                    <center>
                        <h4 class="modal-title" id="myModalLabel">UNAPPROVED!!!</h4>
                    </center>
                </div>
                <form id="form_unapprove">
                    <div class="modal-body" id="modal_body_unapprove">




                    </div>
                </form>
            </div>
        </div>
    </div>


</div>

<script type="text/javascript">
    var UrlTetap = "<?=site_url('Approval_ko')?>";
    $(document).ready(function(){

        getDataApprovalKO();

        $('#myform').submit(function(event){
            event.preventDefault();
            getDataApprovalKO();
        });

        $('#myform input[name="Tipe"]').change(function(){
            getDataApprovalKO();
        });

    });

    function getDataApprovalKO(){

        if ($('#myform #PrdAwal').val()=='' || $('#myform #PrdAwal').val()=='') {
            alert('periode masih kosong');
            return false;
        }

        if ($('#myform #PrdAwal').val() >  $('#myform #PrdAwal').val()) {
            alert('periode awal lebih besar dari periode akhir');
            return false;
        }

        $('#TampilDisini').html('');

        $.post(UrlTetap+'/getDataApprovalKO', $('#myform').serialize(), function(response) {
            $('#TampilDisini').html(response);
        });
    }

    function btnSelect_PO(LedgerNo=''){
        var NoKO = $('#btnSelect_PO'+LedgerNo).attr('data-NoKO');
        var JobNo = $('#btnSelect_PO'+LedgerNo).attr('data-JobNo');
        var CanceledBy = $('#btnSelect_PO'+LedgerNo).attr('data-CanceledBy');
        var ApprovedBy = $('#btnSelect_PO'+LedgerNo).attr('data-ApprovedBy');
        var ClosedBy = $('#btnSelect_PO'+LedgerNo).attr('data-ClosedBy');

        if (CanceledBy!='') {
            alert(NoKO+' sudah di-batalkan');
            return false;
        }

        if (ApprovedBy=='') {
            window.location.href="<?=site_url('Entry/Procurement/FrmEntryPO_C/PODetail/UPD/')?>"+JobNo+'/'+NoKO;
        }else{
            window.location.href="<?=site_url('Entry/Procurement/FrmEntryPO_C/PODetail/SEE_KO/')?>"+JobNo+'/'+NoKO;
        }

    }

    function btnSelect_KONTRAK(LedgerNo){

        var NoKO = $('#btnSelect_KONTRAK'+LedgerNo).attr('data-NoKO');
        var JobNo = $('#btnSelect_KONTRAK'+LedgerNo).attr('data-JobNo');
        var CanceledBy = $('#btnSelect_KONTRAK'+LedgerNo).attr('data-CanceledBy');
        var ApprovedBy = $('#btnSelect_KONTRAK'+LedgerNo).attr('data-ApprovedBy');
        var ClosedBy = $('#btnSelect_KONTRAK'+LedgerNo).attr('data-ClosedBy');
        var UrlRedirect = $('#btnSelect_KONTRAK'+LedgerNo).attr('data-UrlRedirect');

        if (CanceledBy!='') {
            alert(NoKO+' sudah di-batalkan');
            return false;
        }
        window.location.href=UrlRedirect;
    }

    function lakukan_print(LedgerNo = '', ApprovedBy = '', Tipe_ko = '') {
        if (LedgerNo == '') {
            return false;
        }

        if (ApprovedBy == '') {
            alert('Approve Terlebih Dahulu');
            return false;
        }

        if (Tipe_ko == 'KONTRAK') {
            var myurl = "<?= site_url('Kontrak_po/myprint_ko/') ?>" + LedgerNo;
            window.open(myurl, '_blank');
            return false;
        }

        if (Tipe_ko == 'PO') {
            var myurl = "<?= site_url('Kontrak_po/po_print/') ?>" + LedgerNo;
            window.open(myurl, '_blank');
            return false;
        }


    }


    function CekStatusKO(LedgerNo,Aksi){
        var NoKO = $('#btnApprove'+LedgerNo).attr('data-NoKO');
        var VendorNm = $('#btnApprove'+LedgerNo).attr('data-VendorNm');

        var txt = (Aksi=='Approve') ? 'Approve' : 'Unapprove';
        $.post(UrlTetap+'/CekStatusKO', {LedgerNo,Aksi,NoKO}, function(response) {
            if (response.flag!='lanjut') {
                alert(response.pesan);
                return false;
            }else{
                if (confirm('Anda yakin ingin '+txt+' data berikut?'+'\n'+NoKO+' - '+VendorNm)) {
                    AksiApproveUnapprove(LedgerNo,Aksi,NoKO);
                }
            }
        },'json');

    }

    function AksiApproveUnapprove(LedgerNo,Aksi,NoKO){
        $.post(UrlTetap+'/AksiApproveUnapprove', {LedgerNo,Aksi,NoKO}, function(response) {
            if (response=='berhasil') {
                toastr.success('', 'Success');
                getDataApprovalKO();
                return false;
            }else{
                alert(response);
            }
        });
    }
</script>
