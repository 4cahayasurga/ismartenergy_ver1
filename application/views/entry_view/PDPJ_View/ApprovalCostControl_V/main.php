<?php
error_reporting(0);
?>
<style type="text/css">
  .huruf8{
    font-size: 9px !important;
    font-family:  sans-serif;
  }
  .huruf10{
    font-size: 10px !important;
    font-family:  sans-serif;
  }

  .huruf11{
    font-size: 11px !important;
    font-family:  sans-serif;
  }

  .huruf12{
    font-size: 12px !important;
  }

  .huruf13{
    font-size: 13px !important;
  }

  .huruf14{
    font-size: 14px !important;
  }
  .huruf15{
    font-size: 15px !important;
  }
  .jarak{
    padding-top: 5px !important;
    padding-bottom: 5px !important;
  }

  .jarak_baru{
    padding-top: 10px !important;
    /* padding-bottom: 5px !important; */
  }

  .select2-container--default .select2-results>.select2-results__options{
    min-height: 300px !important;
    max-height: 400px !important;
  }
  .tengah{
    vertical-align:middle;
  }



</style>

<div class="content-wrapper">

  <section class="content-header">
    <!-- <h3><?=$title?></h3> -->
    <a href="<?=site_url('JobList')?>" class="btn btn-md btn-danger"><i class="fa fa-arrow-left"></i> BACK</a>
  </section>

  <section class="content">


    <div class="box">
      <div class="box-header bg-gray ">
        <h5 class="box-title text-bold"><?=$title?></h5>

      </div>
      <div class="box-body">
        <div class="row">
          <div class="col-sm-4 "><?=($this->session->flashdata('pesan')) ? $this->session->flashdata('pesan') : '' ?></div>
        </div>
        <div class="row">

          <div class="col-sm-12" style="border: 0px solid;">

            <form action="<?=site_url('Entry/PDPJ/ApprovalPDCostControl_C/index')?>" method="POST">
              <table width="50%" style="border-collapse: collapse;">
                <tr>
                  <td width="100px">Job No</td>
                  <td width="10px" class="text-center">:</td>
                  <td>
                    <select name="JobNo" class="form-control select2" required>
                      <option value="">-- Pilih --</option>
                      <?php foreach ($ListJob as $row): ?>
                        <option value="<?=$row->JobNo?>" <?=($JobNo !='' && ($JobNo == $row->JobNo)) ? 'selected' : '' ?>  ><?=$row->JobNo.' - '.$row->JobNm?></option>
                      <?php endforeach ?>
                    </select>
                  </td>
                </tr>
              </table>

              <table width="50%" style="border-collapse: collapse;margin-top: 10px;">
                <tr>
                  <td width="100px">Alokasi</td>
                  <td width="10px" class="text-center">:</td>
                  <td>
                    <select name="Alokasi" class="form-control form-control-sm select2" required>
                      <option value="">-- Pilih --</option>
                      <?php foreach ($ListAlokasi as $row): ?>
                        <option value="<?=$row->Alokasi?>" <?=($Alokasi !='' && ($Alokasi == $row->Alokasi)) ? 'selected' : '' ?> ><?=$row->Alokasi.' - '.$row->Keterangan?></option>
                      <?php endforeach ?>
                    </select>
                  </td>
                </tr>
              </table>

              <table width="50%" style="border-collapse: collapse;margin-top: 10px;">
                <tr>
                  <td width="100px">Periode</td>
                  <td width="10px" class="text-center">:</td>
                  <td width="200px">
                    <input type="date" name="PrdAwal" class="form-control" value="<?=$PrdAwal?>" >
                  </td>
                  <td width="30px" class="text-center">s.d</td>
                  <td width="200px">
                    <input type="date" name="PrdAkhir" class="form-control" value="<?=$PrdAkhir?>" >
                  </td>
                  <td></td>
                </tr>
              </table>

              <table width="50%" style="border-collapse: collapse;margin-top: 10px;">
                <tr>
                  <td width="100px"></td>
                  <td width="10px" class="text-center"></td>
                  <td>
                   <button type="submit" class="btn btn-primary btn-md btn_cari huruf15" >CARI <i class="fa fa-search"></<i></i></button>
                 </td>
               </tr>
             </table>

             <br>
             <div class="table-responsive">
              <table width="100%">
                <tr>
                 <td class="text-bold text-right" style="font-size: 16px !important;">SUM TOTAL = <u><?=number_format($SumTotalPD)?></u></td>
               </tr>
             </table>
             <table id="mytable" class="table table-striped table-hover table-condensed table-bordered huruf13" style="margin-top: 20px;">
              <thead>
                <tr class="bg-gray color-palette">
                  <th></th>
                  <th class="text-center tengah">No PD</th>
                  <th class="text-center tengah">No Ref Lapangan</th>
                  <th class="text-center tengah" width="80px">Tgl PD</th>
                  <th class="text-center tengah">Alokasi</th>
                  <th class="text-center tengah">Tipe Form</th>
                  <th class="text-center tengah">No KO</th>
                  <th class="text-center tengah">Deskripsi</th>
                  <th class="text-right tengah" width="80px">Total PD (Rp)</th>
                  <th class="text-center tengah">Approved By (Cost Control)</th>
                  <th class="text-center tengah">Approved On</th>
                  <th class="text-center tengah" width="300px">

                  </th>
                </tr>
              </thead>
              <tbody>
                <?php
                  // if (isset($ListTable)) {
                foreach ($ListTable as $row) { 
                  $TxtApprove = ($row->ApprovedByKT == NULL || $row->ApprovedByKT == '') ? 'APPROVE' : 'UNAPPROVE';
                  $TxtUrl = ($row->NoRef == NULL || $row->ApprovedByKT == '') ? 'FrmPDKeu' : 'FrmPDLap';
                  $ValidasiPrint = ($row->NoRef == NULL || $row->NoRef=='') ? 'KEU' : '';
                  ?>
                  <tr>
                    <th><?=ShowKodeAman($row->NoPD)?></th>
                    <td><?=ShowKodeAman($row->NoPD)?></td>
                    <td><?=ShowKodeAman($row->NoRef)?></td>
                    <td><?=ShowKodeAman(tgl_baru($row->TglPD))?></td>
                    <td><?=ShowKodeAman($row->Alokasi)?></td>
                    <td><?=ShowKodeAman($row->TipeForm)?></td>
                    <td><?=ShowKodeAman($row->NoKO)?></td>
                    <td><?=ShowKodeAman($row->Deskripsi)?></td>
                    <td class="text-right"><?=ShowKodeAman(UbahDecimal_Nol_Dua_Tanpa_Koma($row->TotalPD))?></td>
                    <td><?=ShowKodeAman($row->ApprovedByKT)?></td>
                    <td><?=ShowKodeAman(tgl_baru($row->TimeApprovedKT))?></td>
                    <td class="text-center">

                      <a href="<?=site_url('Entry/PDPJ/'.$TxtUrl.'/FrmSelectPD/'.$row->JobNo.'/'.$row->Alokasi.'/'.$row->NoPD)?>" type="button" class="btn btn-md btn-link huruf15" >SELECT</a>

                      <a href="<?=site_url('Entry/PDPJ/ApprovalPDCostControl_C/ProsesApprove/'.$row->NoPD.'/'.$JobNo.'/'.$Alokasi.'/'.$TxtApprove)?>" type="button" class="btn btn-md btn-link huruf15" onClick="return confirm('Anda Yakin?')" ><?=$TxtApprove?></a>

                      <a type="button" href="javascript:void(0);" data-url="<?=site_url('MY_PD/PD_PDF_ALL/ExpPDF_PD/'.$row->LedgerNo.'/'.$row->JobNo.'/'.$row->Alokasi.'/'.$row->NoPD.'/0/0/'.$ValidasiPrint)?>" onClick="CekPrint(<?=$row->LedgerNo?>)" data-TxtApprove="<?=$TxtApprove?>" id="btnPrintID<?=$row->LedgerNo?>" type="button" class="btn btn-md btn-link huruf15" >PRINT</a>

                    </td>
                  </tr>
                  <?php
                    // }
                }
                ?>
              </tbody>
            </table>
          </div>



        </div> 


      </div> 

    </div>
  </div>

</section>

</div>
<script src="<?=base_url('assets/select2_baru.js')?>"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('.select2').select2();
    $('#mytable').DataTable({
      columnDefs: [
        { targets: [0], visible: false},

        { targets: [4,5,8,9,10,11], orderable : false},
        // { targets: '_all', visible: false }
        ],
      "order": [[ 0, 'desc' ]]
    });
  })
  function CekPrint(LedgerNo){
    // alert('ss');

    var url = $('#btnPrintID'+LedgerNo).attr('data-url');
    var TxtApprove = $('#btnPrintID'+LedgerNo).attr('data-TxtApprove');
    // alert(url)
    if (TxtApprove == 'APPROVE') {
      toastr.info('Info', 'Approve terlebih dahulu');
      return false;
    }
    window.open(url);
  }
</script>
