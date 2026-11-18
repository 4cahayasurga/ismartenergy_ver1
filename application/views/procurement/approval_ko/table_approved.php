<style>
    #mytable {
        font-size: 14px !important;
        font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
    }

    #mytable thead tr th {
        text-align: center;
    }

    .warna{
        color:White;
        background-color:#5D7B9D;
        font-weight:bold;
    }
</style>
<div class="col-sm-12">
    <div class="box box-warning">
        <div class="box-header with-border">
            <h4 class="text-bold">KO Sudah Approve</h4>
        </div>
        <div class="box-body">
            <div class="table-responsive">

                <table class="table table-bordered table-striped table-hover table-condesed" id="mytable">
                    <thead>
                        <tr class="warna">
                            <th>No</th>
                            <th width="270px">#</th>
                            <th>Job No.</th>
                            <th>Nama Proyek</th>
                            <th>No. KO</th>
                            <th>Tgl KO</th>
                            <th>Vendor</th>
                            <th>Tipe KO</th>
                            <th width="200px">Total KO (RP)</th>
                            <th>Approved By</th>
                            <th>Approved On</th>
                        </tr>
                        
                    </thead>
                    <tbody>
                        <?php 
                        $NoUrut = 1;
                        foreach ($dataKO as $row): ?>
                            <tr>
                                <td class="text-center"><?=$NoUrut++?></td>
                                <td class="text-center">
                                    <?php if ($row->KategoriId=='KONTRAK'): ?>

                                        <button type="button" class="btn btn-md btn-link text-bold" onclick="btnSelect_KONTRAK('<?=$row->LedgerNo?>')" id="btnSelect_KONTRAK<?=$row->LedgerNo?>" data-NoKO="<?=$row->NoKO?>" data-JobNo="<?=$row->JobNo?>" data-CanceledBy="<?=$row->CanceledBy?>" data-ApprovedBy="<?=$row->ApprovedBy?>" data-ClosedBy="<?=$row->ClosedBy?>" data-UrlRedirect="<?=site_url('Kontrak_po/detail_ko/'.$row->LedgerNo.'/'.$row->KategoriId.'/'.$row->JobNo)?>" title="DETAIL / EDIT" style="text-decoration: underline;">SELECT</button>

                                    <?php else: ?>  

                                        <button type="button" class="btn btn-md btn-link text-bold" onclick="btnSelect_PO('<?=$row->LedgerNo?>')" id="btnSelect_PO<?=$row->LedgerNo?>" data-NoKO="<?=$row->NoKO?>" data-JobNo="<?=$row->JobNo?>" data-CanceledBy="<?=$row->CanceledBy?>" data-ApprovedBy="<?=$row->ApprovedBy?>" data-ClosedBy="<?=$row->ClosedBy?>" title="DETAIL / EDIT" style="text-decoration: underline;">SELECT</button>

                                    <?php endif ?>

                                    <button type="button" class="btn btn-md btn-link text-bold" onclick="CekStatusKO('<?=$row->LedgerNo?>','Unapprove')" id="btnApprove<?=$row->LedgerNo?>" data-NoKO="<?=$row->NoKO?>" data-VendorNm="<?=$row->VendorNm?>" style="text-decoration: underline;">UNAPPROVE</button>

                                    <a href="javascript:void(0)" class="btn btn-md btn-link text-bold" OnClick="lakukan_print('<?=$row->LedgerNo?>','<?=$row->ApprovedBy?>','<?=$row->KategoriId?>')"  title="PRINT DATA" style="text-decoration: underline;">PRINT <i class="fa fa-print"></i></a>

                                </td>
                                <td><?=$row->JobNo?></td>
                                <td><?=$row->JobNm?></td>
                                <td><?=$row->NoKO?></td>
                                <td class="text-center"><?=tgl_baru($row->TglKO)?></td>
                                <td><?=$row->VendorNm?></td>
                                <td class="text-center"><?=$row->KategoriId?></td>
                                <td class="text-right"><?=number_format($row->TotalKO)?></td>
                                <td><?=$row->ApprovedBy?></td>
                                <td><?=tgl_baru_dengan_waktu($row->TimeApproved)?></td>

                            </tr>
                        <?php endforeach ?>
                    </tbody>
                    <tfoot>
                        <tr class="warna">
                            <th class="text-center" colspan="7"></th>
                            <th class="text-center" >Total:</th>
                            <th class="text-right"><?=number_format($TotalKO)?></th>
                            <th class="text-center"></th>
                            <th class="text-center"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function(){
        $('#mytable').DataTable({
            order : [[4,'desc']]
        });
    })
</script>
