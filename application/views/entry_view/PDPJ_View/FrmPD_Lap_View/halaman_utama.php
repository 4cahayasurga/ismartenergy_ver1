<?php
error_reporting(E_ALL);
?>
<style type="text/css">
  body{
    font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
  }
  .huruf8{
    font-size: 9px !important;
/*    font-family:  sans-serif;*/
}
.huruf10{
  font-size: 10px !important;
/*    font-family:  sans-serif;*/
}

.huruf11{
  font-size: 11px !important;
/*    font-family:  sans-serif;*/
}

.huruf12{
  font-size: 12px !important;
/*    font-family:  sans-serif;*/
}

.huruf13{
  font-size: 13px !important;
/*    font-family:  sans-serif;*/
}

.huruf14{
  font-size: 14px !important;
/*    font-family:  sans-serif;*/
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

</style>

<div class="content-wrapper">

  <section class="content-header">

  </section>

  <section class="content">

    <?=($this->session->userdata('message')) ? $this->session->userdata('message') : '' ?>
    <div class="box">
      <div class="box-header bg-blue color-pallete ">
        <h5 class="box-title "><?=$title?></h5>

        <!-- <div class="box-tools">
          <button type="button" class="btn btn-tool" data-box-widget="collapse" title="Collapse">
            <i class="fas fa-minus"></i>
          </button>
          <button type="button" class="btn btn-tool" data-box-widget="remove" title="Remove">
            <i class="fas fa-times"></i>
          </button>
        </div> -->
      </div>
      <div class="box-body">

        <div class="row">

          <div class="col-sm-12" style="border: 0px solid;">

            <div class="table-responsive">

              <form id="FormCariPD">

                <table  id="tbl_form" border="0" style="border-collapse: collapse;" width="100%">
                  <tr>
                    <td class="huruf12 jarak" width="100px" >Job No.</td>
                    <td class="huruf12 text-center jarak" width="10px">:</td>
                    <td class="huruf14 jarak" style="padding-left: 5px;"  width="30%">
                      <select name="JobNo" class="form-control form-control-sm select2 huruf14" required>
                        <option value="">-- Pilih --</option>

                        <?php foreach ($ListJob as $row): ?>
                          <option value="<?=$row->JobNo?>" <?=($JobNo !='' && ($JobNo == $row->JobNo)) ? 'selected' : '' ?>  ><?=$row->JobNo.' - '.$row->JobNm?></option>
                        <?php endforeach ?>
                      </select>
                    </td>
                    <td></td>
                    <td></td>
                  </tr>

                  <tr>
                    <td class="huruf12 jarak" width="100px" >Alokasi</td>
                    <td class="huruf12 text-center" width="10px">:</td>
                    <td class="huruf14" style="padding-left: 5px;" width="30%">
                      <select name="Alokasi" class="form-control form-control-sm   huruf14" required>
                        <option value="">-- Pilih --</option>
                        <?php foreach ($ListAlokasi as $row): ?>
                          <option value="<?=$row->Alokasi?>" <?=($Alokasi !='' && ($Alokasi == $row->Alokasi)) ? 'selected' : '' ?> ><?=$row->Alokasi.' - '.$row->Keterangan?></option>
                        <?php endforeach ?>
                      </select>
                    </td>
                    <td width="30%" style="padding-left: 10px;">
                      <button type="submit" class="btn btn-primary btn-sm btn_cari" >SEARCH <i class="fa fa-search"></<i></i></button> 
                      <input type="checkbox" class="huruf8" name="PDMIX" value="1" id="PDMIX" style="display:none;">
                      <label for="PDMIX" class="huruf10" style="display:none;"> Tampilkan hanya PDMIX</label>
                    </td>
                    <td></td>
                  </tr>
                  <tr>
                    <td class="huruf13 jarak_baru" width="100px" ></td>
                    <td class="huruf13 text-center jarak_baru" width="10px"></td>
                    <td class="huruf13 jarak_baru" style="padding-left: 5px;" width="30%">
                     
                    </td>
                    <td width="30%" style="padding-left: 10px;">

                    </td>
                    <td></td>
                  </tr>
                </table>
              </form>
            </div>



          </div> <!-- col-sm-12 -->


        </div> <!-- row -->

        <div id="tampil_disini">

        </div>


      </div>
    </div>

  </section>

</div>
<script src="<?=base_url('assets/select2_baru.js')?>"></script>
<!-- <script src="<?=base_url('assets/swal2_terbaru.js')?>"></script> -->
<script type="text/javascript">
  $(document).ready(function(){

    if($('#FormCariPD select[name="JobNo"]').val() !='' && $('#FormCariPD select[name="Alokasi"]').val() !=''  ){
      CariDataPD(); 
    }

    $('#FormCariPD select[name="JobNo"]').change(function(){
      if($('#FormCariPD select[name="Alokasi"]').val() !=''){
        CariDataPD();
      }
    })

    $('#FormCariPD select[name="Alokasi"]').change(function(){
      if($('#FormCariPD select[name="JobNo"]').val() !=''){
        CariDataPD();
      }
    })

    $('#FormCariPD #PDMIX').change(function() {
      if($('#FormCariPD select[name="JobNo"]').val() !='' && $('#FormCariPD select[name="Alokasi"]').val() !=''  ){
        CariDataPD(); 
      }
    });

    $('.select2').select2({ dropdownCssClass: "huruf12 ", placeholder : '-- Pilih --' });

    $('#FormCariPD').submit(function(e){
      e.preventDefault();

      CariDataPD();

    })

    // $('.btn_tambahPD').
  });

  function CariDataPD(){

    var JobNo = $('#FormCariPD select[name="JobNo"]').val();
    var Alokasi = $('#FormCariPD select[name="Alokasi"]').val();
    

    jQuery.ajax({
      url: '<?=site_url("Entry/PDPJ/FrmPDLap/dapatkan_tablePD")?>',
      type: 'POST',
      dataType: 'html',
      data: $('#FormCariPD').serialize(),
      beforeSend : function(){
        $('.btn_cari').text('Loading...').attr('disabled',true);
      },
      complete: function(xhr, textStatus) {
        $('.btn_cari').text('SEARCH').attr('disabled',false);

      },
      success: function(data, textStatus, xhr) {
        $('#tampil_disini').html('');
        $('#tampil_disini').html(data);
      },
      error: function(xhr, textStatus, errorThrown) {
        alert('error');
      }
    });
    
  }

  function refreshTablePD(){
    CariDataPD();
  }

</script>