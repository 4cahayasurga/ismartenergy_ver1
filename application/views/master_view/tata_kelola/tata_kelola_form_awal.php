<style type="text/css">
    .atur_ukuran_font{
        font-size: 12px !important;
        font-family: "Times New Roman", Times, serif;
    }



</style>

<div class="content-wrapper">

    <section class="content-header">
        <h4>
            <label class="col-lg col-form-label"><?=($judul) ? $judul : '' ?></label>
            <p></p>
        </h4>
        <label for="inputName" class="col-sm col-form-label"><?=$dataJob['JobNo']?></label> - <label for="inputName" class="col-sm col-form-label"><?=$dataJob['JobNm']?></label><br>
        <small><label for="inputName" class="col-sm col-form-label"><?=$dataJob['Lokasi']?></label></small>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?=($judul) ? $judul : '' ?></li>
        </ol>
    </section>

    <section class="content-header">
        <button onclick="history.back()" class="btn btn-sm btn-default" ><i class="fa fa-arrow-left"></i> Back</button>
    </section>

    <section class="content-header">
        <?= ($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>

        <div class="row" >

            <div class="col-sm-12">

                <div class="box box-danger">
                    <div class="box-header">
                        <div class="box-title">Data KSO</div>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                        </div>
                    </div>

                    <div class="box-body">

                        <form id="form_tatakelola" data-myflag="<?=$myflag?>">

                            <div class="row">

                                <div class="col-sm-12 atur_ukuran_font" >

                                    <div class="table-responsive">

                                        <table border="0" width="100%" style="border-collapse: collapse;">
                                            <tr>

                                                <td style="width:200px;">
                                                    <label>KSO</label>
                                                    <select class="form-control input-sm" name="KSO" id="KSO" required>
                                                        <option value="0" <?=($dataJob['KSO'] == 0) ? 'selected' : ''  ?>>No</option>
                                                        <option value="1" <?=($dataJob['KSO'] == 1) ? 'selected' : ''  ?>>Yes</option>
                                                    </select>
                                                </td>
                                                
                                                <td style="width:200px;padding-left: 10px;">
                                                    <label>Tipe Manajerial</label>

                                                    <select class="form-control input-sm" name="TipeManajerial" id="TipeManajerial" >

                                                       <option value="Single" <?=($dataJob['TipeManajerial'] == 'Single') ? 'selected' : '' ?> >Single</option> 

                                                       <option value="JO Integrated" <?=($dataJob['TipeManajerial'] == 'JO Integrated') ? 'selected' : '' ?> >JO Integrated</option>

                                                       <option value="JO Partial"  <?=($dataJob['TipeManajerial'] == 'JO Partial') ? 'selected' : '' ?>>JO Partial</option>



                                                   </select>
                                               </td>

                                               <td style="width:300px;padding-left: 10px;">
                                                <label>No. Rekening KSO</label>
                                                <input type="text" name="NoRekKSO" id="NoRekKSO" class="form-control input-sm" placeholder="Masukkan No. Rekening KSO" value="<?=$dataJob['NoRekKSO']?>">

                                            </td>
                                            <td></td>

                                        </tr>
                                    </table>

                                </div>


                            </div>

                            <!-- Leader -->
                            <div class="col-sm-6" style="padding-top:10px !important;">

                                <div class="table-responsive">
                                    <table border="0" width="100%" >
                                        <tr>
                                            <td style="background-color: #34495e;color:white;text-align: center;">Leader</td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="table-responsive atur_ukuran_font">

                                    <table border="0" width="100%" class="atur_ukuran_font">
                                        <tr>
                                            <td>
                                                <div class="radio">
                                                     <label>
                                                        <input type="radio" class="Own_check"  name="Own_check" id="Own_check" value="1" <?=($dataJob['Own'] == 1) ? 'checked' : '' ?>> Leader
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>

                                    <table border="0" width="100%" style="margin-top:5px !important;">
                                        <tr>
                                            <td>
                                                <label>Nama</label>
                                                <input type="text" name="Member1" id="Member1" class="form-control input-sm" placeholder="Nama" value="<?=$dataJob['Member1']?>">
                                            </td>
                                        </tr>
                                    </table>

                                    <table border="0" width="100%" style="margin-top:5px !important;">

                                        <tr >
                                            <td>
                                                <label>Share (%)</label>
                                                <input type="text" name="PersenShare1" id="PersenShare1" class="form-control input-sm money" placeholder="Masukkan Share (%)" min="1" max="100" value="<?=new_decimal($dataJob['PersenShare1'])?>">
                                            </td>
                                            <td style="padding-left:5px;">
                                                <label>Bruto (Rp)</label>
                                                <input type="text" name="BrutoShare1" id="BrutoShare1" class="form-control input-sm money" placeholder="Masukkan Bruto (Rp)" value="<?=new_decimal($dataJob['BrutoShare1'])?>">
                                            </td>
                                        </tr>
                                    </table>



                                    <table border="0" width="100%" style="margin-top:5px !important;">
                                        <tr>

                                            <td>
                                                <label>Atas Nama (Bank)</label>
                                                <input type="text" name="AtasNamaInduk" id="AtasNamaInduk" class="form-control input-sm" placeholder="Atas Nama (Bank)" value="<?=$dataJob['AtasNamaInduk']?>">
                                            </td>

                                            <td style="padding-left:5px;">
                                                <label>Nama Bank</label>
                                                <input type="text" name="BankInduk" id="BankInduk" class="form-control input-sm" placeholder="Nama Bank" value="<?=$dataJob['BankInduk']?>">
                                            </td>

                                            <td style="padding-left:5px;">
                                                <label>No. Rekening</label>
                                                <input type="text" name="NoRekInduk" id="NoRekInduk" class="form-control input-sm" placeholder="No. Rekening" value="<?=$dataJob['NoRekInduk']?>">
                                            </td>

                                        </tr>
                                    </table>

                                    <table border="0" width="100%" style="margin-top:5px !important;">
                                        <tr>
                                            <td>
                                                <label>Keterangan Lainnnya</label>
                                                <textarea name="KetShare1" id="KetShare1" class="form-control input-sm" placeholder="Masukkan Keterangan Lainnya"><?=$dataJob['KetShare1']?></textarea>
                                            </td>

                                        </tr>
                                    </table>

                                </div>

                            </div>
                            <!-- Akhir Leader -->


                            <!-- member -->
                            <div class="col-sm-6" style="padding-top:10px !important;">

                                <div class="table-responsive">
                                    <table border="0" width="100%" >
                                        <tr>
                                            <td style="background-color: #7f8c8d;color:white;text-align: center;">Member</td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="table-responsive atur_ukuran_font" >

                                    <table border="0" width="100%" class="atur_ukuran_font" >
                                        <tr>
                                            <td>
                                                <div class="radio">
                                                    
                                                    <label>
                                                        <!-- <input type="checkbox"  name="Member2_check" id="Member2_check" value="2" <?=($dataJob['Own'] == 2) ? 'checked' : '' ?>> Member -->
                                                        <input type="radio" class="Own_check"  name="Own_check" id="Own_check" value="2" <?=($dataJob['Own'] == 2) ? 'checked' : '' ?>> Member
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>

                                    <table border="0" width="100%" style="margin-top:5px !important;">
                                        <tr>
                                            <td>
                                                <label>Nama</label>
                                                <input type="text" name="Member2" id="Member2" class="form-control input-sm" placeholder="Nama" value="<?=$dataJob['Member2']?>" >
                                            </td>
                                        </tr>
                                    </table>

                                    <table border="0" width="100%" style="margin-top:5px !important;">

                                        <tr >
                                            <td>
                                                <label>Share (%)</label>
                                                <input type="text" name="PersenShare2" id="PersenShare2" class="form-control input-sm money" placeholder="Masukkan Share (%)" min="1" max="100" value="<?=new_decimal($dataJob['PersenShare2'])?>" >
                                            </td>
                                            <td style="padding-left:5px;">
                                                <label>Bruto (Rp)</label>
                                                <input type="text" name="BrutoShare2" id="BrutoShare2" class="form-control input-sm money" placeholder="Masukkan Bruto (Rp)" value="<?=new_decimal($dataJob['BrutoShare2'])?>">
                                            </td>
                                        </tr>
                                    </table>

                                    <table border="0" width="100%" style="margin-top:5px !important;">
                                        <tr>

                                            <td>
                                                <label>Atas Nama (Bank)</label>
                                                <input type="text" name="AtasNamaMember" id="AtasNamaMember" class="form-control input-sm" placeholder="Atas Nama (Bank)" value="<?=$dataJob['AtasNamaMember']?>">
                                            </td>

                                            <td style="padding-left:5px;">
                                                <label>Nama Bank</label>
                                                <input type="text" name="BankMember" id="BankMember" class="form-control input-sm" placeholder="Nama Bank" value="<?=$dataJob['BankMember']?>">
                                            </td>

                                            <td style="padding-left:5px;">
                                                <label>No. Rekening</label>
                                                <input type="text" name="NoRekMember" id="NoRekMember" class="form-control input-sm" placeholder="No. Rekening" value="<?=$dataJob['NoRekMember']?>">
                                            </td>

                                        </tr>
                                    </table>

                                    <table border="0" width="100%" style="margin-top:5px !important;">
                                        <tr>
                                            <td>
                                                <label>Keterangan Lainnnya</label>
                                                <textarea name="KetShare2" id="KetShare2" class="form-control input-sm" placeholder="Masukkan Keterangan Lainnya"><?=$dataJob['KetShare2']?></textarea>
                                            </td>

                                        </tr>
                                    </table>

                                </div>

                            </div>
                            <!-- akhir member -->

                            <div class="col-sm-12">
                                <hr style="border:1px solid black;">
                                <input type="hidden" name="JobNo" value="<?=$JobNo?>">
                                <input type="hidden" name="myflag" value="<?=$myflag?>">
                                <button type="submit" class="btn btn-primary btn-md pull-right" id="btn_update_tata_kelola"  <?=($dataJob['KSO'] == 0) ? 'style="display:none;"' : ''  ?> >Update</button>
                            </div>

                        </div>
                    </form>

                </div>

                <div class="overlay loadingsaya" style="display: none;">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>


            </div>
        </div>
    </div>

</section>
</div>

<section></section>

<script type="text/javascript">

    $(function(){
        $('.money').mask('000,000,000,000,000.00', {reverse: true});

        cek_KSO();
        $('#KSO').change(function(){
            cek_KSO();
        })

        $('#form_tatakelola').submit(function(e){
            e.preventDefault();
            jQuery.ajax({
              url: '<?=site_url("Master/TataKelola_new/aksi_ubah_tatakelola")?>',
              type: 'POST',
              dataType: 'html',
              data: $(this).serialize(),
              beforeSend : function(){
                $('.loadingsaya').show();
                $('#btn_update_tata_kelola').attr('disabled',true);
            },
            complete: function(xhr, textStatus) {
                $('.loadingsaya').hide();
                $('#btn_update_tata_kelola').attr('disabled',false);
            },
            success: function(data, textStatus, xhr) {
                // alert(data);
                // console.log(data);
                if (data == 'tidakboleh') {
                    $.toast({
                        text: 'KSO (Yes) Tipe Manajerial tidak boleh Single ',
                        position: 'top-right',
                        stack: false,
                        hideAfter: 2000,
                        icon: 'error',

                    });
                    return false;
                }

                location.reload();
            },
            error: function(xhr, textStatus, errorThrown) {
                $.toast({
                    text: 'Terjadi Kesalahan',
                    position: 'bottom-right',
                    stack: false,
                    hideAfter: 1000,
                    icon: 'error',

                });
                return false;
            }
        });
            
        })
    })

    function cek_KSO(){


        var KSO = $('#KSO').val();
        var TipeManajerial = $('#TipeManajerial').val();
        var NoRekKSO = $('#NoRekKSO').val();
        

        var Member1 = $('#Member1').val();
        var PersenShare1 = $('#PersenShare1').val();
        var BrutoShare1 = $('#BrutoShare1').val();
        var AtasNamaInduk = $('#AtasNamaInduk').val();
        var BankInduk = $('#BankInduk').val();
        var NoRekInduk = $('#NoRekInduk').val();
        var KetShare1 = $('#KetShare1').val();

        var Member2 = $('#Member2').val();
        var PersenShare2 = $('#PersenShare2').val();
        var BrutoShare2 = $('#BrutoShare2').val();
        var AtasNamaMember = $('#AtasNamaMember').val();
        var BankMember = $('#BankMember').val();
        var NoRekMember = $('#NoRekMember').val();
        var KetShare2 = $('#KetShare2').val();

        if (KSO == 0) {

            //leader
            $('.Own_check').attr('disabled', true);

            $('#Member1').attr('disabled',true);
            $('#PersenShare1').attr('disabled',true);
            $('#BrutoShare1').attr('disabled',true);

            $('#AtasNamaInduk').attr('disabled',true);
            $('#BankInduk').attr('disabled',true);
            $('#NoRekInduk').attr('disabled',true);

            $('#KetShare1').attr('disabled',true);
            //akhir leader

            //member
            $('#Member2').attr('disabled',true);
            $('#PersenShare2').attr('disabled',true);
            $('#BrutoShare2').attr('disabled',true);

            $('#AtasNamaMember').attr('disabled',true);
            $('#BankMember').attr('disabled',true);
            $('#NoRekMember').attr('disabled',true);

            $('#KetShare2').attr('disabled',true);
            //akhir member



             //required leader
             $('#Member1').attr('required',false);
             $('#PersenShare1').attr('required',false);
             $('#BrutoShare1').attr('required',true);

             $('#AtasNamaInduk').attr('required',false);
             $('#BankInduk').attr('required',false);
             $('#NoRekInduk').attr('required',false);

             $('#KetShare1').attr('required',false);
            // akhir required leader

            //required member
            $('#Member2').attr('required',false);
            $('#PersenShare2').attr('required',false);
            $('#BrutoShare2').attr('required',false);

            $('#AtasNamaMember').attr('required',false);
            $('#BankMember').attr('required',false);
            $('#NoRekMember').attr('required',false);

            $('#KetShare2').attr('required',false);
            // akhir required member

            return false;
        }

        if (KSO == 1) {


            //required leader
            $('.Own_check').attr('disabled', false);

            $('#Member1').attr('required',true);
            $('#PersenShare1').attr('required',true);
            $('#BrutoShare1').attr('required',true);

            $('#AtasNamaInduk').attr('required',true);
            $('#BankInduk').attr('required',true);
            $('#NoRekInduk').attr('required',true);

            $('#KetShare1').attr('required',true);
            // akhir required leader

            //required member

            $('#Member2').attr('required',true);
            $('#PersenShare2').attr('required',true);
            $('#BrutoShare2').attr('required',true);

            $('#AtasNamaMember').attr('required',true);
            $('#BankMember').attr('required',true);
            $('#NoRekMember').attr('required',true);

            $('#KetShare2').attr('required',true);
            // akhir required member




            //leader

            $('#Member1').attr('disabled',false);
            $('#PersenShare1').attr('disabled',false);
            $('#BrutoShare1').attr('disabled',false);

            $('#AtasNamaInduk').attr('disabled',false);
            $('#BankInduk').attr('disabled',false);
            $('#NoRekInduk').attr('disabled',false);

            $('#KetShare1').attr('disabled',false);
            //akhir leader

            //member

            $('#Member2').attr('disabled',false);
            $('#PersenShare2').attr('disabled',false);
            $('#BrutoShare2').attr('disabled',false);

            $('#AtasNamaMember').attr('disabled',false);
            $('#BankMember').attr('disabled',false);
            $('#NoRekMember').attr('disabled',false);

            $('#KetShare2').attr('disabled',false);
            //akhir member('disabled',false);


            return false;

        }

    }


</script>

