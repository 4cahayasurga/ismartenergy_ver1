
<style type="text/css">
    #table_PdDtl.dataTable tbody tr:hover {
        background-color: #bdc3c7;
        color: black;
        font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
        font-size: 18px !important;
        font-weight: bold;

    }

    #table_PdDtl.dataTable tbody tr:hover > .sorting_1 {
        background-color: #3498db
        color: black;
        /*font-size: 18px !important;*/
        /*font-weight: bold;*/
    }
    .atur_padding_table{
        padding-top: 10px !important;
        padding-bottom: 10px !important;
        vertical-align: middle;
    }
    .atur_ukuran {
        font-size: 10px !important;
        font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
    }
</style>
<div class="table-responsive">


    <table class="table table-bordered table-striped table-hover table-condensed atur_ukuran" id="table_PdDtl" >
        <thead>
            <tr class="ukuran_select2 bg-gray">
                <th class="atur_padding_table text-center"  style="text-align:center !important;width: 5% !important;">
                    <input type="checkbox" id="check-all" style="text-align:center;">
                </th>
                <th class="text-center" width="5%" style="padding-bottom: 10px !important;" >#</th>
                <th style="text-align:center;" class="atur_padding_table" width="3%" >No.</th>
                <th style="text-align:left;width: 100px !important;" class="atur_padding_table" >Kode RAP</th>
                <th style="text-align:left;" class="atur_padding_table" >Uraian</th>
                <th style="text-align:right;width: 50px;" class="atur_padding_table" >Vol</th>
                <th style="text-align:left;width: 5%;" class="atur_padding_table" >&nbsp;&nbsp;&nbsp;Sat</th>
                <th style="text-align:right;width: 130px;" class="atur_padding_table" >Harga Satuan (Rp)</th>
                <th style="text-align:right;width: 10%;" class="atur_padding_table" >Jumlah Harga (Rp)</th>
            </tr>
        </thead>
        <tbody>

            <?php
            if ($query_dapat_pddtl->num_rows() == 0) {
                echo '<tr><td colspan="8">Data tidak ditemukan</td></tr>';
                exit;
            }

            ?>

            <?php 
            $no =1;
            $SubTotal=0;
            $NoUrut=0;

            foreach ($query_dapat_pddtl->result_array() as $data_pddtl) :
                $SubTotal += $data_pddtl['Total'];
                $NoUrut = $data_pddtl['NoUrut'];
                ?>
                <tr class="atur_ukuran">
                    <td class="atur_padding_table text-center"  style="text-align:center !important;width: 5%;">

                        <input type="checkbox" class="check-item atur_ukuran atur_padding_table text-center" style="text-align:center;" data-NoUrut="<?=$data_pddtl['NoUrut']?>"  >

                    </td>
                    <td style="text-align:center;" class="atur_padding_table atur_ukuran">

                        <a href="javascript:void(0)" class="btn btn-sm btn-warning atur_ukuran" title="Edit Uraian" onclick="edit_uraian(<?=$NoUrut?>)"><i class="fa fa-edit"></i></a>
                    </td>
                    <td style="text-align:center;" class="atur_ukuran atur_padding_table"><?=$NoUrut?></td>
                    <td style="text-align:center;width: 100px !important;" class="atur_ukuran atur_padding_table" ><?=$data_pddtl['KdRAP']?></td>
                    <td class="atur_ukuran atur_padding_table" ><?=$data_pddtl['Uraian']?></td>
                    <td class="atur_ukuran atur_padding_table" style="text-align:right;"><?=uang_koma_genap_decimal_kedua($data_pddtl['Vol'])?></td>
                    <td class="atur_ukuran atur_padding_table" >&nbsp;&nbsp;&nbsp;<?=$data_pddtl['Uom']?></td>
                    <td  class="atur_ukuran atur_padding_table" style="text-align:right;"><?=uang_koma_genap_decimal_kedua($data_pddtl['HrgSatuan'])?></td>
                    <td   class="atur_ukuran atur_padding_table" style="text-align:right;"><?=uang_koma_genap_decimal_kedua($data_pddtl['Total'])?></td>
                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>
    <br><br>
    
    <table width="100%" border="0">
        <tr>
            <th style="text-align:right;font-size: 12px !important;" class="atur_ukuran">Sub Total </th>
            <th style="text-align:right;width: 2%;"></th>
            <th style="text-align:right;width: 20%;">
                <!-- <input type="text" name="SubTotal" id="SubTotal" value="<?=uang_koma_genap_decimal($SubTotal)?>" class="form-control input-sm atur_ukuran" style="text-align:right;font-size: 12px !important;" readonly   > -->
                <!-- <input type="text" name="SubTotal" id="SubTotal" value="<?=$SubTotal?>" class="form-control input-sm atur_ukuran" style="text-align:right;font-size: 12px !important;" readonly   > -->

                <input type="text"id="SubTotal_hanya_utk_view" value="<?=number_format($SubTotal, 2, '.', ',')?>" class="form-control input-sm ukuran_pada_table_kedua" style="text-align:right;font-size: 12px !important;"  readonly   >

                <input type="hidden" id="SubTotal" value="<?=$SubTotal?>" class="form-control input-sm ukuran_pada_table_kedua" style="text-align:right;font-size: 12px !important;" readonly   >

            </th>
        </tr>
    </table>


    <table width="100%" border="0" style="margin-top:10px !important;" >
        <tr>
            <th style="text-align:right;font-size: 12px !important;">Saldo sebelumnya dari No</th>
            <th style="text-align:right;width: 20%;padding-left:10px;padding-right:10px;">
                <select id="pilih_NoPJSebelumnya" class="form-control input-sm atur_ukuran" style="font-size:12px !important;">
                    <option value="">-- Pilih --</option>

                    <!-- <option value="dd">-- Pilih --</option> -->

                    <?php
                    foreach($dapat_NoPJSebelumnya as $data_NoPJSebelumnya) : ?>
                        <option value="<?=$data_NoPJSebelumnya['NoPJ']?>" ><?=$data_NoPJSebelumnya['NoPJ']?></option>
                    <?php endforeach; ?>
                </select>
            </th>
            <th style="text-align:right;width: 20%;">
                <!-- <input type="text" id="Saldo" name="Saldo" class="form-control input-sm atur_ukuran" style="text-align:right;font-size: 12px !important;" readonly   > -->

                <input type="text" id="Saldo_view_saja" class="form-control input-sm ukuran_pada_table_kedua" style="text-align:right;font-size: 12px !important;" value="0" disabled   >
                <input type="hidden" id="Saldo" name="Saldo" class="form-control input-sm ukuran_pada_table_kedua" style="text-align:right;font-size: 12px !important;" value="0" readonly   >
                <button id="loading_pj_sebelumnya" class="btn btn-default btn-md disabled" style="display:none;"   ><i class="fa fa-spinner"></i></button>
            </th>
        </tr>
    </table>

    <table width="100%" border="0" style="margin-top:10px !important;" >
        <tr>
            <th style="text-align:right;font-size: 12px !important;" class="atur_ukuran" >Total Permintaan </th>
            <th style="text-align:right;width: 2%;"></th>
            <th style="text-align:right;width: 20%;" class="atur_ukuran">
                <!-- <input type="text" id="TotalPermintaan" name="TotalPermintaan"  class="form-control input-sm" style="text-align:right;font-size: 12px !important;" readonly> -->

                <input type="text" id="TotalPermintaan" name="TotalPermintaan"  class="form-control input-sm ukuran_pada_table_kedua" style="text-align:right;font-size: 12px !important;" value="<?=number_format($SubTotal, 2, '.', ',')?>" readonly>

            </th>
        </tr>
    </table>
    <br><br>
</div>

<script>
    $(document).ready(function(){

       $('#table_PdDtl').DataTable({
        scrollY:        "300px",
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        fixedColumns:   true
    });
   });



    $(function(){
        // cek_saldo_NoPJSebelumnya();
        // Hitung_TotalPermintaan();
        // dapatkan_Saldo_NoPJSebelumnya();
        $('#check-all').click(function(){
            if($(this).is(":checked")){
                $(".check-item").prop("checked", true);
            }else{
                $(".check-item").prop("checked", false);
            }
        });


        $('#pilih_NoPJSebelumnya').change(function(){
            // cek_saldo_NoPJSebelumnya();
            dapatkan_Saldo_NoPJSebelumnya();
        });

    });

    function dapatkan_Saldo_NoPJSebelumnya(){
        var NoPJSebelumnya = $('#pilih_NoPJSebelumnya').val();
        var SubTotal = $('#SubTotal').val();
        var TotalPermintaan = $('#TotalPermintaan').val();

        var JobNo = $('#JobNo').val();
        var TipeForm = $('#TipeForm').val();

        // alert(NoPJSebelumnya+' - '+SubTotal+' - '+TotalPermintaan+' - '+JobNo+' - '+TipeForm);
        // return false;

        jQuery.ajax({
          url : '<?=site_url("Permintaan_dana_keuangan/dapatkan_Saldo_NoPJSebelumnya")?>',
          type: 'POST',
          dataType: 'json',
          data: {NoPJSebelumnya: NoPJSebelumnya, SubTotal : SubTotal, TotalPermintaan : TotalPermintaan, JobNo : JobNo, TipeForm : TipeForm},
          beforeSend : function(){
            $('#loading_pj_sebelumnya').show();
            $('#Saldo_view_saja').hide();
            $('#Saldo').hide();
        },
        complete: function(xhr, textStatus) {
            $('#loading_pj_sebelumnya').hide();
            $('#Saldo_view_saja').show();
            $('#Saldo').show();
        },
        success: function(data, textStatus, xhr) {
            // console.log(data);
            // return false;
            $('#Saldo').val(data.Saldo);
            $('#Saldo_view_saja').val(data.Saldo_view_saja);
            $('#TotalPermintaan').val(data.TotalPermintaan);
            $('#SubTotal').val(data.SubTotal);
        },
        error: function(xhr, textStatus, errorThrown) {
            alert('error');
        }
    });
        
    }

    function cek_saldo_NoPJSebelumnya(){
        var NoPJSebelumnya = $('#pilih_NoPJSebelumnya').val();
        var SubTotal = $('#SubTotal').val();
        jQuery.ajax({
          url : '<?=site_url("Permintaan_dana_keuangan/dapat_saldo_NoPJSebelumnya_terbaru")?>',
          type: 'POST',
          dataType: 'JSON',
          data: {'NoPJSebelumnya' : NoPJSebelumnya,'SubTotal' : SubTotal},
          cache : false,
          success: function(data, textStatus, xhr) {
            $('#Saldo').val(data.Saldo);
            $('#TotalPermintaan').val(data.TotalPermintaan);
            $('#SubTotal').val(data.SubTotal);
            // alert(data.Saldo);
        }
    });

    }

    function Hitung_TotalPermintaan(){
        var SubTotal = $('#SubTotal').val();
        var Saldo = $('#Saldo').val();
        var Hitung = 0;


        SubTotal = HapusKoma(SubTotal);
        Saldo = HapusKoma(Saldo);

        var Hitung = parseFloat(SubTotal) - parseFloat(Saldo);
        Hitung = addCommas(Hitung.toFixed(2));
        $('#TotalPermintaan').val(Hitung);

    }

</script>