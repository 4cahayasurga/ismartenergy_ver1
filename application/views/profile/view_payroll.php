

<div class="box" style="border:none;">

  <div class="box-body">

      <table width="100%" border="0">
        <tr>
          <td style="width:10%;">Periode</td>
          <td style="text-align:center;width: 2%;">:</td>
          <td style="width:20%;">
            <input  name="Periode" id="Periode" class="form-control input-sm date-picker" placeholder="-- Pilih Periode --" autocomplete="off"  />
          </td>
          <td></td>
        </tr>
        <tr>
          <td style="width:10%;"></td>
          <td style="text-align:center;width: 2%;"></td>
          <td style="width:20%;">
            <p></p>
            <input type="hidden" name="NIK" value="<?=$NIK?>">
            <button type="button" class="btn btn-sm btn-success btn_tampil_payroll" >Tampilkan</button>
            <button type="button" class="btn btn-sm btn-danger btn_loading" disabled="disabled" style="display:none;" >Loading...</button>

          </td>
          <td></td>
        </tr>

      </table>

      <p></p>

      

    </div>

  </div>

  <div class="tampil_payroll"></div>

  <script type="text/javascript">
    $(function(){

      $('.date-picker').datepicker({
        format: "m-yyyy",
        viewMode: "months", 
        minViewMode: "months",
        autoclose : true
      });

      $('.btn_tampil_payroll').click(function(){

        var Periode = $('#Periode').val();
        var NIK = $('input[name="NIK"]').val();
        // alert(NIK);
        // return false;


        if (Periode == '') {
          $.toast({
            text: 'isi periode terlebih dahulu',
            position: 'top-right',
            stack: false,
            hideAfter: 2000,
            icon: 'info',

          });
          return false;
        }

        jQuery.ajax({
          url: "<?=site_url('Kepegawaian/tampil_payroll_periode')?>",
          type: 'POST',
          dataType: 'HTML',
          data: {
            'Periode' : Periode, 
            'NIK' : NIK 
          },
          beforeSend : function(){
            $('.tampil_payroll').html('');
            $('.btn_tampil_payroll').hide();
            $('.btn_loading').show();
          },
          complete: function(xhr, textStatus) {
            $('.btn_tampil_payroll').show();
            $('.btn_loading').hide();
          },
          success: function(data, textStatus, xhr) {
            // alert(data);
            $('.tampil_payroll').html(data);

          },
          error: function(xhr, textStatus, errorThrown) {
            $.toast({
              text: 'Terjadi Kesalahan',
              position: 'top-right',
              stack: false,
              hideAfter: 2000,
              icon: 'error',

            });
            return false;
          }
        });

      });



    });
  </script>