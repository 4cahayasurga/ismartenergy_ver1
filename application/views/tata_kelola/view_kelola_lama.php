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

                            <div class="col-sm-12">
                                <hr style="border:1px solid silver;">
                                <table width="100%" border="0">
                                    <tr>
                                        <td style="width:50px;">
                                            <div class="custom-control custom-checkbox">
                                              <input type="checkbox" class="custom-control-input" id="chkLeader" value="1">
                                              <label class="custom-control-label" for="chkLeader">Leader</label>
                                          </div>

                                      </td>
                                      <td style="width:200px;padding-left:5px;padding-right: 5px;">
                                        <div class="form-group">
                                            <label>Nama</label>
                                            <input type="text" class="form-control input-sm" name="NamaLeader" id="NamaLeader" placeholder="Isi Nama" disabled >
                                        </div>
                                    </td>
                                    <td style="width:200px;padding-left:5px;padding-right: 5px;">
                                        <div class="form-group">
                                            <label>Share %</label>
                                            <input type="text" class="form-control input-sm" name="PersenLeader" id="PersenLeader" placeholder="Isi Share %" disabled>
                                        </div>
                                    </td>
                                    <td style="width:200px;padding-left:5px;padding-right: 5px;">
                                        <div class="form-group">
                                            <label>Bruto (Rp) </label>
                                            <input type="text" class="form-control input-sm" name="BrutoLeader" id="BrutoLeader" placeholder="Isi Bruto (Rp)" disabled>
                                        </div>
                                    </td>
                                    <td style="width:200px;padding-left:5px;padding-right: 5px;">
                                        <div class="form-group">
                                            <label>Nama Bank </label>
                                            <input type="text" class="form-control input-sm" name="BankLeader" id="BankLeader" placeholder="Isi Nama Bank" disabled>
                                        </div>
                                    </td>
                                    <td style="width:200px;padding-left:5px;padding-right: 5px;">
                                        <div class="form-group">
                                            <label>No. Rekening </label>
                                            <input type="text" class="form-control input-sm" name="NoRekLeader" id="NoRekLeader" placeholder="Isi No. Rekening" disabled>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6"><hr style="border:1px solid silver;"></td>
                                </tr>

                            </table>
                        </div>

                        <div class="col-sm-12">
                            <table width="100%" border="0" id="dynamic_field">

                                <tr>

                                    <td style="width:80px;">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input chkMember" data-id="1" name="chkMember[]" value="1">
                                            <label class="custom-control-label" >Member 1</label>
                                            <input type="hidden" name="norut_member[]" class="norut_member" value="1">
                                        </div>

                                    </td>


                                    <td style="width:200px;padding-left:5px;padding-right: 5px;">
                                        <div class="form-group">
                                            <label>Nama</label>
                                            <input type="text" class="form-control input-sm NamaMember" id="NamaMember1"  name="NamaMember[]"  placeholder="Isi Nama" disabled>
                                        </div>


                                    </td>

                                    <td style="width:200px;padding-left:5px;padding-right: 5px;">
                                        <div class="form-group">
                                            <label>Share %</label>
                                            <input type="text" class="form-control input-sm PersenMember" id="PersenMember1" name="PersenMember[]"  placeholder="Isi Share %" disabled>
                                        </div>
                                    </td>


                                    <td style="width:200px;padding-left:5px;padding-right: 5px;">
                                        <div class="form-group">
                                            <label>Bruto (Rp) </label>
                                            <input type="text" class="form-control input-sm BrutoMember" id="BrutoMember1" name="BrutoMember[]"  placeholder="Isi Bruto (Rp)" disabled>
                                        </div>
                                    </td>


                                    <td style="width:200px;padding-left:5px;padding-right: 5px;">
                                        <div class="form-group">
                                            <label>Nama Bank </label>
                                            <input type="text" class="form-control input-sm BankMember" id="BankMember1" name="BankMember[]"  placeholder="Isi Nama Bank" disabled>
                                        </div>
                                    </td>


                                    <td style="width:200px;padding-left:5px;padding-right: 5px;">
                                        <div class="form-group">
                                            <label>No. Rekening </label>
                                            <input type="text" class="form-control input-sm NoRekMember" id="NoRekMember1" name="NoRekMember[]" placeholder="Isi No. Rekening" disabled>
                                        </div>
                                    </td>


                                    <td style="text-align:center;width:10px;">
                                        <button type="button" class="btn btn-sm btn-success"  name="add" id="add"><i class="fa fa-plus" ></i></button>
                                    </td>

                                </tr>

                            </table> 
                        </div>

                        <div class="col-sm-12">
                            <hr style="border:1px solid silver;"><br>
                            <button type="submit" class="btn btn-xs pull-right " style="background-color: #27ae60;color:white;">Update Data <i class="fa fa-floppy-o "></i></button>
                        </div>

                    </div>



                </div>


                <div class="overlay loadingku" style="display: none;">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>

            </div>
        </div>






        <input type="hidden" name="JobNo" value="<?=$dapat_data_job['JobNo']?>">

    </form>




</section>

</div>

<script type="text/javascript">

    $(function(){

        validasi_KSO();

        var i=1;  
        var html_member='';

        $('#add').click(function(){  
         i++;  

         $('#dynamic_field').append(''+
            '<tr id="row'+i+'">'+

            '<td style="width:80px;">'+
            '<div class="custom-control custom-checkbox">'+
            '<input type="checkbox" class="custom-control-input chkMember" data-id="'+i+'" name="chkMember[]" value="1">'+
            '<label class="custom-control-label"> Member '+i+'</label>'+
            '<input type="hidden" name="norut_member[]" class="norut_member" value="'+i+'">'+
            '</div>'+
            '</td>'+

            '<td style="width:200px;padding-left:5px;padding-right: 5px;">'+
            '<div class="form-group">'+
            '<label>Nama</label>'+
            '<input type="text" class="form-control input-sm NamaMember" id="NamaMember'+i+'" name="NamaMember[]"  placeholder="Isi Nama" disabled>'+
            '</div>'+
            '</td>'+

            '<td style="width:200px;padding-left:5px;padding-right: 5px;">'+
            '<div class="form-group">'+
            '<label>Share %</label>'+
            '<input type="text" class="form-control input-sm PersenMember" id="PersenMember'+i+'" name="PersenMember[]"  placeholder="Isi Share %" disabled>'+
            '</div>'+
            '</td>'+

            '<td style="width:200px;padding-left:5px;padding-right: 5px;">'+
            '<div class="form-group">'+
            '<label>Bruto (Rp)</label>'+
            '<input type="text" class="form-control input-sm BrutoMember" id="BrutoMember'+i+'" name="BrutoMember[]"  placeholder="Isi Bruto (Rp)" disabled>'+
            '</div>'+
            '</td>'+

            '<td style="width:200px;padding-left:5px;padding-right: 5px;">'+
            '<div class="form-group">'+
            '<label>Nama Bank</label>'+
            '<input type="text" class="form-control input-sm BankMember" id="BankMember'+i+'" name="BankMember[]"  placeholder="Isi Nama Bank" disabled>'+
            '</div>'+
            '</td>'+

            '<td style="width:200px;padding-left:5px;padding-right: 5px;">'+
            '<div class="form-group">'+
            '<label>No. Rekening</label>'+
            '<input type="text" class="form-control input-sm NoRekMember" id="NoRekMember'+i+'" name="NoRekMember[]" placeholder="Isi No. Rekening" disabled>'+
            '</div>'+
            '</td>'+


            '<td>'+
            '<button type="button" name="remove" id="'+i+'" class="btn btn-danger btn-sm btn_remove"><i class="fa fa-trash"></i></button>'+
            '</td>'+
            '</tr>');  

     });



        $('#form_saya').submit(function(e){
            e.preventDefault();

            if ($("#chkLeader").is(':checked') == 0 ) {

                $.toast({
                    heading: 'Error',
                    text: 'Pilih leader dan isi data terlebih dahulu',
                    icon: 'error',
                    loader: true,
                })

                return false;
            }

            $.ajax({
                url: '<?=site_url('TataKelola/updata_data_kso')?>',
                type: 'POST',
                dataType: 'html',
                data: $(this).serialize(),
                beforeSend : function(){
                    $('.overlay').show();
                },
            })
            .done(function(data) {
                $('.overlay').hide();
                alert(data);
            })
            .fail(function() {
                $('.overlay').hide();
            })
            .always(function() {

            });

        })



        $('#chkLeader').change(function() {
            if ($(this).is(':checked')) {
                $('#NamaLeader').val('').attr('disabled',false).attr('required',true);
                $('#PersenLeader').val('').attr('disabled',false).attr('required',true);
                $('#BrutoLeader').val('').attr('disabled',false).attr('required',true);
                $('#BankLeader').val('').attr('disabled',false).attr('required',true);
                $('#NoRekLeader').val('').attr('disabled',false).attr('required',true);

            }else{
                $('#NamaLeader').val('').attr('disabled',true).attr('required',false);
                $('#PersenLeader').val('').attr('disabled',true).attr('required',false);
                $('#BrutoLeader').val('').attr('disabled',true).attr('required',false);
                $('#BankLeader').val('').attr('disabled',true).attr('required',false);
                $('#NoRekLeader').val('').attr('disabled',true).attr('required',false);
            }

        });


    // $('.chkMember').change(function(){
    //     var id = $(this).attr('data-id');
    //     if ($(this).is(':checked')) {

    //         $('#NamaMember'+id).val('').attr('disabled',false).attr('required',true);
    //         $('#PersenMember'+id).val('').attr('disabled',false).attr('required',true);
    //         $('#BrutoMember'+id).val('').attr('disabled',false).attr('required',true);
    //         $('#BankMember'+id).val('').attr('disabled',false).attr('required',true);
    //         $('#NoRekMember'+id).val('').attr('disabled',false).attr('required',true);

    //     }else{
    //         $('#NamaMember'+id).val('').attr('disabled',true).attr('required',false);
    //         $('#PersenMember'+id).val('').attr('disabled',true).attr('required',false);
    //         $('#BrutoMember'+id).val('').attr('disabled',true).attr('required',false);
    //         $('#BankMember'+id).val('').attr('disabled',true).attr('required',false);
    //         $('#NoRekMember'+id).val('').attr('disabled',true).attr('required',false);

    //     }
    // })


})

$(document).on('click', '.btn_remove', function(){  
    var button_id = $(this).attr("id");   
    $('#row'+button_id+'').remove();  
});

$(document).on('change', '.chkMember', function(){  
    var button_id = $(this).attr("data-id");

    if ($(this).prop('checked')){
        $('#NamaMember'+button_id).val('').attr('disabled',false).attr('required',true);
        $('#PersenMember'+button_id).val('').attr('disabled',false).attr('required',true);
        $('#BrutoMember'+button_id).val('').attr('disabled',false).attr('required',true);
        $('#BankMember'+button_id).val('').attr('disabled',false).attr('required',true);
        $('#NoRekMember'+button_id).val('').attr('disabled',false).attr('required',true);

    }else{
        $('#NamaMember'+button_id).val('').attr('disabled',true).attr('required',false);
        $('#PersenMember'+button_id).val('').attr('disabled',true).attr('required',false);
        $('#BrutoMember'+button_id).val('').attr('disabled',true).attr('required',false);
        $('#BankMember'+button_id).val('').attr('disabled',true).attr('required',false);
        $('#NoRekMember'+button_id).val('').attr('disabled',true).attr('required',false);
    }
});


function validasi_KSO(){
    var KSO = $('#KSO :selected').val();
    row_sekitar_kso(KSO);
    
}


function row_sekitar_kso(KSO){
        //KSO hanya ada 0 dan 1

        //jika 0 maka
        if(KSO==0){
            $('#Own').val('').change().prop('disabled',true).prop('required',false);
            $('#TipeManagerial').val('').change().prop('disabled',true).prop('required',false);
            $('#NoRekKSO').val('').prop('disabled',true).prop('required',false);
        }else{
            $('#Own').val('').change().prop('disabled',false).prop('required',true);
            $('#TipeManagerial').val('').change().prop('disabled',false).prop('required',true);
            $('#NoRekKSO').val('').prop('disabled',false).prop('required',true);
        }
        
    }




    


</script>


