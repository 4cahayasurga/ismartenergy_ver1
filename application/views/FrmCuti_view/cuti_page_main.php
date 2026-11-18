<style type="text/css">
    .swal2-popup {
        font-size: 1.4rem !important;
        font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
    }
    .dibaca{
        background-color: gray;

    }
    .mykonten {
        font-family: Arial, Helvetica, sans-serif !important;
        font-size: 10px !important;
    }

    .atur_td {
        width: 100px;
    }

    .huruf_12{
        font-size: 12px !important;
        font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
    }


    #example .active_table {
    }

    .jarak_atas{
        margin-top: 5px !important;
    }

</style>
<div class="content-wrapper">

    <section class="content-header">
        <h4 style="font-weight: bold;"><?= (isset($judul)) ? strtoupper($judul) : '' ?></h4>

        <ol class="breadcrumb">
            <li class="active text-bold huruf_12" ><?=$this->session->userdata('MIS_LOGGED_NIK').' - '.$this->session->userdata('MIS_LOGGED_NAME')?></li>
        </ol>
    </section>

    <section class="content-header">

        <button class="btn btn-sm btn-danger" onclick="history.back()"><i class="fa fa-arrow-left"></i> Back</button>


    </section>

        <section class="content">
            <?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>

        <div class="row">
            <div class="col-sm-12">
                <div class="box box-primary">
                    <div class="box-body">

                            <ul class="nav nav-tabs" role="tablist">
                                <li class="active">
                                    <a href="#your_request" role="tab" data-toggle="tab">
                                        Your Request Cuti
                                    </a>
                                </li>
                                
                                <li >
                                    <a href="#approve_cuti" role="tab" data-toggle="tab">
                                        Approve Request (Atasan)
                                    </a>
                                </li>

                                <li>
                                    <a href="#approved_atasan" role="tab" data-toggle="tab">Approved (Atasan)</a>
                                </li>

                                <li>
                                    <a href="#rejected_atasan" role="tab" data-toggle="tab">Rejected (Atasan)</a>
                                </li>

                                <li>
                                    <a href="#approved_SDM" role="tab" data-toggle="tab">Approved (SDM)</a>
                                </li>

                                <li>
                                    <a href="#rejected_SDM" role="tab" data-toggle="tab">Rejected (SDM)</a>
                                </li>
                                
                            </ul>

                            <div class="tab-content">

                                <div class="tab-pane fade " id="approve_cuti" >

                                    <div class="table-responsive">
                                        <br>

                                        <table class="table table-bordered table-striped table-hover" id="mytable_permintaan_cuti" style="white-space: nowrap;" >
                                            <thead>
                                                <tr >
                                                    <th rowspan="2" style="vertical-align:middle;"></th>
                                                    <th rowspan="2" style="vertical-align:middle;" >NIK</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Nama</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Dari Tanggal</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Sampai Tanggal</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Tipe Request</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Karyawan Pengganti</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Deskripsi</th>
                                                    <th class="text-center bg-gray disabled color-palette"colspan="3" style="vertical-align:middle;text-align: center;background-color:#d2d6de;" >Atasan</th>
                                                    <th colspan="3" class="text-center bg-blue disabled color-palette" style="vertical-align:middle;text-align: center;" >SDM</th>
                                                </tr>
                                                <tr>
                                                    <th>Status</th>
                                                    <th>Approve/Reject By</th>
                                                    <th>Time Approve/Reject</th>
                                                    <th>Status</th>
                                                    <th>Approve/Reject By</th>
                                                    <th>Time Approve/Reject</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php 
                                                $LedgerNo = NULL;
                                                $DiBaca =NULL;
                                                foreach ($data_approve_cuti as $myrow): 
                                                    $LedgerNo = $myrow->LedgerNo;
                                                    $DiBaca = $myrow->DiBaca;
                                                    ?>
                                                    <tr>
                                                        <td style="text-align:center;">

                                                            <button onclick="btn_Approve('<?=$LedgerNo?>')" class="btn btn-success btn-md approve<?=$LedgerNo?>"><i class="fa fa-check"></i> APPROVE</button>

                                                            <button onclick="btn_Reject('<?=$LedgerNo?>')" class="btn btn-warning btn-md rejct<?=$LedgerNo?>"><i class="fa fa-ban"></i> REJECT</button>

                                                            <button style="display:none;" class="btn btn-default btn-sm disabled loading<?=$LedgerNo?>"><i class="fa fa-refresh"></i></button>

                                                        </td>
                                                        <td><?=$myrow->NIK?></td>
                                                        <td><?=$myrow->NamaRequest?></td>

                                                        <td><?=TanggalSaya($myrow->DrTgl)?></td>
                                                        <td><?=TanggalSaya($myrow->SpTgl)?></td>
                                                        <td><?=$myrow->Status?></td>
                                                        <td><?=$myrow->KarPeng?></td>
                                                        <!-- <td><?=$myrow->NIK?></td> -->
                                                        <td><?=$myrow->Remark?></td>

                                                        <td><?=$myrow->HeadAction?></td>
                                                        <td><?=$myrow->HeadUserAction?></td>
                                                        <td><?=atur_null(date('d-M-Y H:i', strtotime($myrow->HeadTimeAction)))?></td>

                                                        <td><?=$myrow->SDMAction?></td>
                                                        <td><?=$myrow->SDMUserAction?></td>
                                                        <td><?=atur_null(date('d-M-Y H:i', strtotime($myrow->SDMTimeAction)))?></td>
                                                        <!-- <td><?=$myrow->HeadAction?></td> -->
                                                    </tr>
                                                <?php endforeach ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <script src="<?=base_url('assets/swal2.js')?>"></script>

                                </div>

                                <div class="tab-pane fade active in" id="your_request">

                                    <p></p>

                                    <table width="100%" border="0" style="margin-bottom:10px;">
                                        <tr>
                                            <td width="8%">
                                                <button class="btn btn-md btn-primary" id="btn_add_request" data-NIK="<?=$NIK?>" >Entry Request</button>
                                            </td>
                                            <td width="10%">
                                                <span class="label bg-purple" style="font-size:16px;" >Sisa Cuti = <?=$sisa_cuti?></span>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"><hr></td>
                                        </tr>
                                    </table>

                                    <div class="table-responsive">

                                        <table id="example" style="white-space:nowrap;" class="table table-striped table-bordered table-striped table-condensed" width="100%" >
                                            <thead>
                                                <tr>
                                                    <th rowspan="2" style="vertical-align:middle;width: 13% !important;"></th>
                                                    <th rowspan="2" style="vertical-align:middle;width: 10% !important;" >Dari Tanggal</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Sampai Tanggal</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Tipe Request</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Karyawan Pengganti</th>
                                                    <th colspan="3" class="text-center bg-gray disabled color-palette" style="background-color:#d2d6de;">Atasan</th>
                                                    <th colspan="3" class="text-center bg-blue disabled color-palette"  >SDM</th>
                                                </tr>
                                                <tr>
                                                    <!-- ==== ATASAN  -->
                                                    <th>Status</th>
                                                    <th>Approve/Reject Oleh</th>
                                                    <th>Waktu Approve/Reject</th>
                                                    <!-- ==== END ATASAN  -->

                                                    <!-- ==== SDM  -->
                                                    <th>Status</th>
                                                    <th>Approve/Reject Oleh</th>
                                                    <th>Waktu Approve/Reject</th>
                                                    <!-- ==== END SDM  -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($data_cuti as $row_cuti) : ?>

                                                    <tr>
                                                        <td class="text-center">
                                                            <button class="btn btn-warning btn-md btn_edit" onclick="edit_request('<?=$row_cuti->LedgerNo?>')" ><i class="fa fa-pencil"></i> EDIT</button>
                                                            <button class="btn btn-danger btn-md btn_hapus" onclick="hapus_cuti_request('<?=$row_cuti->LedgerNo?>')" ><i class="fa fa-trash"></i> HAPUS</button>

                                                        </td>
                                                        <td><?=TanggalSaya($row_cuti->DrTgl)?></td>
                                                        <td><?=TanggalSaya($row_cuti->SpTgl)?></td>
                                                        <td><?=$row_cuti->Status?></td>
                                                        <td><?=$row_cuti->KarPeng?></td>

                                                        <!-- ==== ATASAN  -->
                                                        <td><?=$row_cuti->HeadAction?></td>
                                                        <td><?=$row_cuti->HeadUserAction?></td>
                                                        <td><?=atur_null(date('d-M-Y H:i', strtotime($row_cuti->HeadTimeAction)))?></td>
                                                        <!-- ==== END ATASAN  -->

                                                        <!-- ==== SDM  -->
                                                        <td><?=$row_cuti->SDMAction?></td>
                                                        <td><?=$row_cuti->SDMUserAction?></td>
                                                        <td><?=atur_null(date('d-M-Y H:i', strtotime($row_cuti->SDMTimeAction)))?></td>
                                                        <!-- ==== END SDM  -->
                                                    </tr>

                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>



                                    </div>

                                    <script src="<?=base_url('assets/swal2.js')?>"></script>

                                    <script type="text/javascript">

                                        function hapus_cuti_request(LedgerNo){

                                            if (confirm('Anda yakin ingin menghapus data ini?')) {

                                                jQuery.ajax({
                                                    url: '<?=site_url('FrmCuti/hapus_cuti_request')?>',
                                                    type: 'POST',
                                                    dataType: 'JSON',
                                                    cache : false,
                                                    data: {LedgerNo : LedgerNo},
                                                    beforeSend : function(){
                                                        $('.btn_hapus').attr('disabled',true).text('Loading...');
                                                    },
                                                    success: function(data, textStatus, xhr) {
                                                        $('.btn_hapus').attr('disabled',false).text('HAPUS');


                                                        if (data.flag == 'berhasil') {
                                                            swal.fire('Sukses!','data berhasil dihapus','success');
                                                            setTimeout("location.reload()",1500);
                                                            return false;
                                                        }else if(data.flag == 'gagal'){
                                                            swal.fire('Error!','data gagal dihapus','error');
                                                            return false;
                                                        }else{
                                                            swal.fire('Info!',data.pesan,'info');
                                                            return false;
                                                        }

                                                    },
                                                    error: function(xhr, textStatus, errorThrown) {
                                                        swal.fire('Error!','Terjadi Kesalahan','error');
                                                        return false;
                                                    }
                                                });

                                            }


                                        }

                                        function edit_request(LedgerNo=''){
                                            $('#modal_body_saya').html('');
                                            jQuery.ajax({
                                                url: '<?=site_url('FrmCuti/tampilkan_form_edit_cuti')?>',
                                                type: 'POST',
                                                dataType: 'html',
                                                cache : false,
                                                data: {LedgerNo : LedgerNo},
                                                beforeSend : function(){
                                                    $('.btn_edit').attr('disabled',true).text('Loading...');
                                                },
                                                success: function(data, textStatus, xhr) {
                                                    $('.btn_edit').attr('disabled',false).text('EDIT');
                                                    $('#modal_body_saya').html(data);
                                                    $('#modal_saya').modal('show');
                                                },
                                                error: function(xhr, textStatus, errorThrown) {
                                                    swal.fire('Error!','Terjadi Kesalahan','error');
                                                    return false;
                                                }
                                            });
                                        }


                                        $(function(){
                                            $('#example').DataTable();

                                            $("tbody tr").on("click", function() {
                                                $(this).find("td").each(function(i) {
                                                    $(this).toggleClass('active_table');
                                                });
                                            });


                                        })
                                    </script>

                                </div>


                                <!-- ====== ATASAN ============ -->

                                <div class="tab-pane fade" id="approved_atasan">
                                    <p></p>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover" id="approved_table"  style="white-space: nowrap;" >
                                            <thead>
                                                <tr >
                                                    <th rowspan="2" style="vertical-align:middle;" >NIK</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Nama</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Dari Tanggal</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Sampai Tanggal</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Tipe Request</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Karyawan Pengganti</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Deskripsi</th>
                                                    <th class="text-center bg-gray disabled color-palette"colspan="3" style="vertical-align:middle;text-align: center;background-color:#d2d6de;" >Atasan</th>
                                                    <th colspan="3" class="text-center bg-blue disabled color-palette" style="vertical-align:middle;text-align: center;" >SDM</th>
                                                </tr>
                                                <tr>
                                                    <th>Status</th>
                                                    <th>Approve/Reject By</th>
                                                    <th>Time Approve/Reject</th>
                                                    <th>Status</th>
                                                    <th>Approve/Reject By</th>
                                                    <th>Time Approve/Reject</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php 
                                                foreach ($approved_cuti as $row_approved): ?>
                                                    <tr>
                                                        <td><?=$row_approved->NIK?></td>
                                                        <td><?=$row_approved->NamaRequest?></td>

                                                        <td><?=TanggalSaya($row_approved->DrTgl)?></td>
                                                        <td><?=TanggalSaya($row_approved->SpTgl)?></td>
                                                        <td><?=$row_approved->Status?></td>
                                                        <td><?=$row_approved->KarPeng?></td>

                                                        <td><?=$row_approved->Remark?></td>

                                                        <td><?=$row_approved->HeadAction?></td>
                                                        <td><?=$row_approved->HeadUserAction?></td>
                                                        <td><?=atur_null(date('d-M-Y H:i', strtotime($row_approved->HeadTimeAction)))?></td>

                                                        <td><?=$row_approved->SDMAction?></td>
                                                        <td><?=$row_approved->SDMUserAction?></td>
                                                        <td><?=atur_null(date('d-M-Y H:i', strtotime($row_approved->SDMTimeAction)))?></td>
                                                    </tr>
                                                <?php endforeach ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>


                                <div class="tab-pane fade" id="rejected_atasan">
                                    <p></p>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover" id="rejected_table"  style="white-space: nowrap;" >
                                            <thead>
                                                <tr >
                                                    <th rowspan="2" style="vertical-align:middle;" >NIK</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Nama</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Dari Tanggal</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Sampai Tanggal</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Tipe Request</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Karyawan Pengganti</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Deskripsi</th>
                                                    <th class="text-center bg-gray disabled color-palette"colspan="3" style="vertical-align:middle;text-align: center;background-color:#d2d6de;" >Atasan</th>
                                                    <th colspan="3" class="text-center bg-blue disabled color-palette" style="vertical-align:middle;text-align: center;" >SDM</th>
                                                </tr>
                                                <tr>
                                                    <th>Status</th>
                                                    <th>Approve/Reject By</th>
                                                    <th>Time Approve/Reject</th>
                                                    <th>Status</th>
                                                    <th>Approve/Reject By</th>
                                                    <th>Time Approve/Reject</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php 
                                                foreach ($reject_cuti as $row_rejected): ?>
                                                    <tr>
                                                        <td><?=$row_rejected->NIK?></td>
                                                        <td><?=$row_rejected->NamaRequest?></td>

                                                        <td><?=TanggalSaya($row_rejected->DrTgl)?></td>
                                                        <td><?=TanggalSaya($row_rejected->SpTgl)?></td>
                                                        <td><?=$row_rejected->Status?></td>
                                                        <td><?=$row_rejected->KarPeng?></td>

                                                        <td><?=$row_rejected->Remark?></td>

                                                        <td><?=$row_rejected->HeadAction?></td>
                                                        <td><?=$row_rejected->HeadUserAction?></td>
                                                        <td><?=atur_null(date('d-M-Y H:i', strtotime($row_rejected->HeadTimeAction)))?></td>

                                                        <td><?=$row_rejected->SDMAction?></td>
                                                        <td><?=$row_rejected->SDMUserAction?></td>
                                                        <td><?=atur_null(date('d-M-Y H:i', strtotime($row_rejected->SDMTimeAction)))?></td>
                                                    </tr>
                                                <?php endforeach ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                                <!-- ============ END ATASAN ================== -->

                                <!-- ============= SDM =================== -->
                                <div class="tab-pane fade" id="approved_SDM">
                                    <p></p>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover" id="approved_table_SDM"  style="white-space: nowrap;" >
                                            <thead>
                                                <tr >
                                                    <th rowspan="2" style="vertical-align:middle;" >NIK</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Nama</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Dari Tanggal</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Sampai Tanggal</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Tipe Request</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Karyawan Pengganti</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Deskripsi</th>
                                                    <th class="text-center bg-gray disabled color-palette"colspan="3" style="vertical-align:middle;text-align: center;background-color:#d2d6de;" >Atasan</th>
                                                    <th colspan="3" class="text-center bg-blue disabled color-palette" style="vertical-align:middle;text-align: center;" >SDM</th>
                                                </tr>
                                                <tr>
                                                    <th>Status</th>
                                                    <th>Approve/Reject By</th>
                                                    <th>Time Approve/Reject</th>
                                                    <th>Status</th>
                                                    <th>Approve/Reject By</th>
                                                    <th>Time Approve/Reject</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php 
                                                foreach ($approved_SDM as $SDM_approved): ?>
                                                    <tr>
                                                        <td><?=$SDM_approved->NIK?></td>
                                                        <td><?=$SDM_approved->NamaRequest?></td>

                                                        <td><?=TanggalSaya($SDM_approved->DrTgl)?></td>
                                                        <td><?=TanggalSaya($SDM_approved->SpTgl)?></td>
                                                        <td><?=$SDM_approved->Status?></td>
                                                        <td><?=$SDM_approved->KarPeng?></td>

                                                        <td><?=$SDM_approved->Remark?></td>

                                                        <td><?=$SDM_approved->HeadAction?></td>
                                                        <td><?=$SDM_approved->HeadUserAction?></td>
                                                        <td><?=atur_null(date('d-M-Y H:i', strtotime($SDM_approved->HeadTimeAction)))?></td>

                                                        <td><?=$SDM_approved->SDMAction?></td>
                                                        <td><?=$SDM_approved->SDMUserAction?></td>
                                                        <td><?=atur_null(date('d-M-Y H:i', strtotime($SDM_approved->SDMTimeAction)))?></td>
                                                    </tr>
                                                <?php endforeach ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>


                                <div class="tab-pane fade" id="rejected_SDM">
                                    <p></p>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover" id="rejected_table_SDM"  style="white-space: nowrap;" >
                                            <thead>
                                                <tr >
                                                    <th rowspan="2" style="vertical-align:middle;" >NIK</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Nama</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Dari Tanggal</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Sampai Tanggal</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Tipe Request</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Karyawan Pengganti</th>
                                                    <th rowspan="2" style="vertical-align:middle;" >Deskripsi</th>
                                                    <th class="text-center bg-gray disabled color-palette"colspan="3" style="vertical-align:middle;text-align: center;background-color:#d2d6de;" >Atasan</th>
                                                    <th colspan="3" class="text-center bg-blue disabled color-palette" style="vertical-align:middle;text-align: center;" >SDM</th>
                                                </tr>
                                                <tr>
                                                    <th>Status</th>
                                                    <th>Approve/Reject By</th>
                                                    <th>Time Approve/Reject</th>
                                                    <th>Status</th>
                                                    <th>Approve/Reject By</th>
                                                    <th>Time Approve/Reject</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php 
                                                foreach ($rejected_SDM as $SDM_rejected): ?>
                                                    <tr>
                                                        <td><?=$SDM_rejected->NIK?></td>
                                                        <td><?=$SDM_rejected->NamaRequest?></td>

                                                        <td><?=TanggalSaya($SDM_rejected->DrTgl)?></td>
                                                        <td><?=TanggalSaya($SDM_rejected->SpTgl)?></td>
                                                        <td><?=$SDM_rejected->Status?></td>
                                                        <td><?=$SDM_rejected->KarPeng?></td>

                                                        <td><?=$SDM_rejected->Remark?></td>

                                                        <td><?=$SDM_rejected->HeadAction?></td>
                                                        <td><?=$SDM_rejected->HeadUserAction?></td>
                                                        <td><?=atur_null(date('d-M-Y H:i', strtotime($SDM_rejected->HeadTimeAction)))?></td>

                                                        <td><?=$SDM_rejected->SDMAction?></td>
                                                        <td><?=$SDM_rejected->SDMUserAction?></td>
                                                        <td><?=atur_null(date('d-M-Y H:i', strtotime($SDM_rejected->SDMTimeAction)))?></td>
                                                    </tr>
                                                <?php endforeach ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>


                                <!-- ================= END SDM ===================== -->


                            </div>


                        </div>
                    </div>
                </div>
            </div>

        </section>

    </div>


    <div class="modal modal-default fade" id="modal_saya">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body" id="modal_body_saya">

                </div>
            </div>
        </div>
    </div>


    <div class="modal modal-default fade" id="modal_entry_cuti">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-blue">
                    <h4>Entry Request Cuti</h4>
                </div>
                <div class="modal-body">
                    <form method="POST" action="<?=site_url('FrmCuti/add_cuti')?>" >
                        <div class="table-responsive">


                            <table width="100%" border="0">
                                <tr>
                                    <td style="width:15%;">Tipe Request</td>
                                    <td style="width:1%;padding-left:5px:padding-right:5px;text-align: center;" >:</td>
                                    <td>
                                        <select name="TipeRequest" id="TipeRequest" class="form-control" required>
                                            <option value="">--- Pilih --</option>
                                            <!-- <option value="Masuk">Masuk</option> -->
                                            <option value="Cuti">Cuti</option>
                                            <!-- <option value="Cuti 1/2 Hari">Cuti 1/2 Hari</option> -->
                                            <!-- <option value="Dinas Proyek">Dinas Proyek</option> -->
                                            <option value="Sakit">Sakit</option>
                                            <option value="Cuti Melahirkan">Cuti Melahirkan</option>
                                            <option value="Cuti Menikah">Cuti Menikah</option>
                                        </select>
                                    </td>
                                    <!-- <td></td> -->
                                </tr>
                            </table>

                            <table width="100%" border="0" class="jarak_atas" >
                                <tr>
                                    <td style="width:15%;">Periode</td>
                                    <td style="width:1%;padding-left:5px:padding-right:5px;text-align: center;" >:</td>
                                    <td >
                                        <input type="date" name="DrTgl" id="DrTgl" class="form-control" required>
                                    </td>
                                    <td style="width:3%;padding-left:10px:padding-right:10px;text-align: center;" >s.d</td>
                                    <td >
                                        <input type="date" name="SpTgl" id="SpTgl" class="form-control" required>
                                    </td>
                                </tr>
                            </table>

                            <table width="100%" border="0" class="jarak_atas">
                                <tr>
                                    <td style="width:15%;">Deskripsi</td>
                                    <td style="width:1%;padding-left:5px:padding-right:5px;text-align: center;" >:</td>
                                    <td >
                                        <textarea name="Deskripsi" id="Deskripsi" class="form-control" placeholder="Isi Disini"></textarea>
                                    </td>
                                </tr>
                            </table>

                            <table width="100%" border="0" class="jarak_atas">
                                <tr>
                                    <td style="width:15%;">Karyawan Pengganti</td>
                                    <td style="width:1%;padding-left:5px:padding-right:5px;text-align: center;" >:</td>
                                    <td >
                                        <select name="KaryawanPengganti" id="KaryawanPengganti" class="form-control select2" style="font-size: 12px !important;">
                                            <option value="">-- Pilih --</option>
                                            <?php foreach($dataKaryawanPengganti as $row) : ?>
                                                <option value="<?=$row->Nama?>"><?=$row->Nama?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                            </table>

                            <table width="100%" border="0" class="jarak_atas" style="border-top:1px solid silver;">
                                <tr>
                                    <td style="width:15%;padding-top:10px;"><button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button></td>
                                    <td style="width:1%;padding-left:5px:padding-right:5px;text-align: center;" ></td>
                                    <td style="text-align: right;">

                                        <input type="hidden" name="NIK" value="<?=$NIK?>">
                                        <button type="submit" class="btn btn-primary btn-md" id="btn_save">Save</button>

                                    </td>
                                </tr>
                            </table>


                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(function(){

            $('.select2').select2({
                width:'100%'
            });

            $('#mytable_permintaan_cuti').DataTable();
            $('#approved_table').DataTable();
            $('#rejected_table').DataTable();



            $('#approved_table_SDM').DataTable();
            $('#rejected_table_SDM').DataTable();

            $('#btn_add_request').click(function(){
                bersihkan_isi_form();
                $('#modal_entry_cuti').modal('show');
            });
        });

        function add_request(){
            bersihkan_isi_form();
            $('#modal_saya').modal('show');
        }

        function bersihkan_isi_form(){
            $("select[name=TipeRequest]").val('');
            $("input[name=DrTgl]").val('');
            $("input[name=SpTgl]").val('');

            $("textarea[name=Deskripsi]").val('');
            $("select[name=KaryawanPengganti]").val('');

        }

        function table_cuti(){

            var flag = 'cuti';
            var NIK = $('#my_box_body').attr('data-NIK');

            jQuery.ajax({
                url: '<?=site_url('FrmCuti/table_cuti')?>',
                type: 'POST',
                dataType: 'html',
                cache : false,
                data: {flag : flag, NIK : NIK},
                beforeSend : function(){
                    $('#refresh_table').attr('disabled',true).text('Loading...');
                    $('#my_box_body').html('');
                },
                success: function(data, textStatus, xhr) {
                 $('#refresh_table').attr('disabled',false).text('Refresh Table');
                 $('#my_box_body').html(data);
             },
             error: function(xhr, textStatus, errorThrown) {
                swal.fire('Error!','Terjadi Kesalahan','error');
                return false;
            }
        });

        }


        function btn_Approve(LedgerNo=''){
            if (LedgerNo == '') {
                Swal.fire('Error!','','error');
                return false;
            }

            if (confirm('Anda yakin ingin approve data ini?')) {
                jQuery.ajax({
                    url: '<?=site_url("FrmCuti/ApproveAtasan")?>',
                    type: 'POST',
                    dataType: 'html',
                    data: {LedgerNo: LedgerNo, flag : 'Approve'},
                    beforeSend: function() {
                        $('.loading'+LedgerNo).show();
                        $('.approve'+LedgerNo).hide();
                    },
                    success: function(data, textStatus, xhr) {
                        $('.loading'+LedgerNo).hide();
                        $('.approve'+LedgerNo).show();

                        if (data == 'sudah_approve') {
                            Swal.fire('Info!','Tidak bisa di rubah. Request sudah di approve.','info');
                            return false;
                        }

                        if (data == 'sudah_reject') {
                            Swal.fire('Info!','Tidak bisa di rubah. Request sudah di reject.','info');
                            return false;
                        }

                        if (data == 'berhasil') {
                            Swal.fire('Sukses','Data sudah di approve.','success');
                        // location.reload();
                        setTimeout("location.reload()",1500);
                        return false;
                    }

                    if (data == 'error' || data == 'gagal') {
                        Swal.fire('Error!','Error.','error');
                        return false;
                    }

                },
                error: function(xhr, textStatus, errorThrown) {
                    Swal.fire('Error!','','error');
                    return false;
                }
            });
            } 

        }

        function btn_Reject(LedgerNo=''){
            if (LedgerNo == '') {
                Swal.fire('Error!','','error');
                return false;
            }

            if (confirm('Anda yakin ingin reject data ini?')) {

                jQuery.ajax({
                    url: '<?=site_url("FrmCuti/ApproveAtasan")?>',
                    type: 'POST',
                    dataType: 'html',
                    data: {LedgerNo: LedgerNo, flag : 'Reject'},
                    beforeSend: function() {
                        $('.loading'+LedgerNo).show();
                        $('.approve'+LedgerNo).hide();
                    },
                    success: function(data, textStatus, xhr) {
                        $('.loading'+LedgerNo).hide();
                        $('.approve'+LedgerNo).show();

                        if (data == 'sudah_approve') {
                            Swal.fire('Info!','Tidak bisa di rubah. Request sudah di approve.','info');
                            return false;
                        }

                        if (data == 'sudah_reject') {
                            Swal.fire('Info!','Tidak bisa di rubah. Request sudah di reject.','info');
                            return false;
                        }

                        if (data == 'berhasil') {
                            Swal.fire('Sukses','Data sudah di reject.','success');
                        // location.reload();
                        setTimeout("location.reload()",1500);
                        return false;
                    }

                    if (data == 'error' || data == 'gagal') {
                        Swal.fire('Error!','Error.','error');
                        return false;
                    }

                },
                error: function(xhr, textStatus, errorThrown) {
                    Swal.fire('Error!','','error');
                    return false;
                }
            });

            }

        }




    </script>
