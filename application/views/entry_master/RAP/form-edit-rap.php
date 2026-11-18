
<style type="text/css">
    #form_RAP{
        font-size: 12px;
        /*font-weight: bold;*/
    }
</style>


<div class="row">

    <form id="form_RAP">




        <div class="col-sm-12">

            <table border="0" width="100%">
                <tr>
                    <td style="font-size:14px;" width="50%">RAP -- FORM UPDATE DATA</td>
                    <td style="font-size: 14px;color:blue;color:blue;text-align:right;" width="50%"><?= $MyRAP['JobNo'].' - '.$MyRAP['JobNm']?></td>
                </tr>
                <tr>
                    <td style="border-bottom:1px solid black;" colspan="2"></td>
                </tr>
            </table>

            <br>

            <table border="0" width="100%">
                <tr>
                    <td width="130px">Tipe</td>
                    <td width="10px" align="center">:</td>
                    <td width="25%">

                        <select name="Tipe" id="Tipe" class="form-control input-sm select2" <?=($RAP_status==1) ? 'disabled="disabled"' : 'disabled="disabled"' ?>  required>
                            <option value="">-- Pilih salah satu --</option>
                            <option value="Header" <?=($MyRAP['Tipe'] == 'Header' ) ? 'selected' : '' ?> >Header</option>
                            <option value="Detail" <?=($MyRAP['Tipe'] == 'Detail' ) ? 'selected' : '' ?> >Detail</option>
                        </select>
                    </td>

                    <td width="130px" style="padding-left:10px;">Header</td>
                    <td width="10px" align="center">:</td>
                    <td width="35%">
                        <select name="Header" id="Header" class="form-control input-sm select2" <?=($RAP_status==1) ? 'disabled="disabled"' : 'disabled="disabled"' ?> required>
                            <option value="0">Top Header</option>
                            <?php foreach($list_header as $data_header ): ?>
                                <option value="<?=$data_header['KdRAP']?>" <?=($data_header['KdRAP'] == $MyRAP['Header']) ? 'selected' : '' ?>  ><?=$data_header['KdRAP'].' - '.$data_header['Uraian']?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td></td>
                </tr>
            </table>

            <table border="0" width="100%" style="margin-top:10px;">
                <tr>
                    <td width="130px">Alokasi</td>
                    <td width="10px" align="center">:</td>
                    <td width="25%">
                        <input type="text" class="form-control input-sm" value="<?=$MyRAP['Alokasi'].' - '.$MyRAP['AlokasiKeterangan']?>"  disabled>

                    </td>
                    <td></td>
                </tr>
            </table>

            <table border="0" width="100%" style="margin-top:10px;">
                <tr>
                    <td width="130px">Kode RAP</td>
                    <td width="10px" align="center">:</td>
                    <td width="25%">
                        <input type="text" class="form-control input-sm" name="KdRAP" id="KdRAP" placeholder="Kode RAP" autocomplete="off" value="<?=$MyRAP['KdRAP']?>" <?=($RAP_status==1) ? 'disabled="disabled"' : 'disabled="disabled"' ?> required>
                    </td>
                    <td></td>
                </tr>
            </table>


            <table border="0" width="100%" style="margin-top:10px;">
                <tr>
                    <td width="130px">Uraian Pekerjaan</td>
                    <td width="10px" align="center">:</td>
                    <td width="25%">
                        <textarea class="form-control input-sm" placeholder="Uraian Pekerjaan" name="Uraian" id="Uraian" required><?=$MyRAP['Uraian']?></textarea>
                    </td>
                    <td></td>
                </tr>
            </table>

            <table border="0" width="100%" style="margin-top:10px;">
                <tr>
                    <td width="130px">Satuan</td>
                    <td width="10px" align="center">:</td>
                    <td width="25%">
                        <input type="text" class="form-control input-sm" name="Uom" id="Uom" placeholder="Satuan"  autocomplete="off" value="<?=$MyRAP['Uom']?>"  <?=($RAP_status==1) ? 'disabled="disabled"' : '' ?> >
                    </td>
                    <td></td>
                </tr>
            </table>

            <table border="0" width="100%" style="margin-top:10px;">
                <tr>
                    <td width="130px">Volume</td>
                    <td width="10px" align="center">:</td>
                    <td width="25%">
                        <input type="text" class="form-control input-sm " name="Vol" id="Vol"  placeholder="0" autocomplete="off" value="<?=number_format((float)($MyRAP['Vol']), 3, '.', '')?>" <?=($RAP_status==1) ? 'disabled="disabled"' : '' ?> >
                    </td>
                    <td></td>
                </tr>
            </table>



            <table border="0" width="100%" style="margin-top:10px;">
                <tr>
                    <td width="130px">Harga Satuan</td>
                    <td width="10px" align="center">:</td>
                    <td width="25%">
                        <input type="text" class="form-control input-sm " name="HrgSatuan" id="HrgSatuan" placeholder="0" autocomplete="off" value="<?=number_format((float)($MyRAP['HrgSatuan']), 2, '.', '')?>" <?=($RAP_status==1) ? 'disabled="disabled"' : '' ?> >
                    </td>
                    <td></td>
                </tr>
            </table>

            <!-- <table border="0" width="100%" style="margin-top:10px;">
                <tr>
                    <td width="130px">PPN Satuan (Rp)</td>
                    <td width="10px" align="center">:</td>
                    <td width="25%">
                        <input type="text" class="form-control input-sm " name="PPN" id="PPN" placeholder="0" value="<?=  number_format((float)(($MyRAP['PPN'] == '' || $MyRAP['PPN'] == NULL ) ? 0 : $MyRAP['PPN']), 2, '.', '')?> <?=($RAP_status==1) ? 'disabled="disabled"' : '' ?> " autocomplete="off">
                        <span style="font-style: italic; color: red;">*Jika Kosong Ketik NOL (0)</span>
                    </td>
                    <td></td>
                </tr>
            </table> -->

            <table border="0" width="100%" style="margin-top:10px;">
                <tr>
                    <td width="130px">Harga Satuan (RAB)</td>
                    <td width="10px" align="center">:</td>
                    <td width="25%">
                        <input type="text" class="form-control input-sm " name="HrgRAB" id="HrgRAB" placeholder="0" autocomplete="off" value="<?=number_format((float)($MyRAP['HrgRAB']), 2, '.', '')?>" <?=($RAP_status==1) ? 'disabled="disabled"' : '' ?> >
                    </td>
                    <td></td>
                </tr>
            </table>

            <table border="0" width="100%" style="margin-top:10px;">
                <tr>
                    <td align="right">


                        <hr style="border:1px solid black;">

                        

                        <?php
                        if($MyRAP['Tipe'] == 'Header'){ ?>
                            <input type="hidden" name="JobNo" value="<?=$MyRAP['JobNo']?>">
                            <input type="hidden" name="KdRAP" value="<?=$MyRAP['KdRAP']?>">
                            <input type="hidden" name="Versi" value="<?=$MyRAP['Versi']?>">
                            <input type="hidden" name="NoUrut" value="<?=$MyRAP['NoUrut']?>">
                            <input type="hidden" name="Alokasi" value="<?=$MyRAP['Alokasi']?>">
                            <input type="hidden" name="Tipe" value="<?=$MyRAP['Tipe']?>">

                            
                            <input type="hidden" name="Header" value="<?=$MyRAP['Header']?>">
                            <input type="hidden" name="Uom" value="<?=$MyRAP['Uom']?>">
                            <input type="hidden" name="Vol" value="<?=$MyRAP['Vol']?>">
                            <input type="hidden" name="HrgSatuan" value="<?=$MyRAP['HrgSatuan']?>">
                            <input type="hidden" name="HrgRAB" value="<?=$MyRAP['HrgRAB']?>">
                            <input type="hidden" name="PPN" value="<?=$MyRAP['PPN']?>">
                            <input type="hidden" name="LedgerNo" value="<?=$MyRAP['LedgerNo']?>">
                        <?php } ?>


                        <?php
                        if($MyRAP['Tipe'] == 'Detail'){ ?>

                            <input type="hidden" name="JobNo" value="<?=$MyRAP['JobNo']?>">
                            <input type="hidden" name="KdRAP" value="<?=$MyRAP['KdRAP']?>">
                            <input type="hidden" name="Versi" value="<?=$MyRAP['Versi']?>">
                            <input type="hidden" name="NoUrut" value="<?=$MyRAP['NoUrut']?>">
                            <input type="hidden" name="Alokasi" value="<?=$MyRAP['Alokasi']?>">
                            <input type="hidden" name="Tipe" value="<?=$MyRAP['Tipe']?>">

                            
                            <input type="hidden" name="Header" value="<?=$MyRAP['Header']?>">
                            <input type="hidden" name="LedgerNo" value="<?=$MyRAP['LedgerNo']?>">

                        <?php } ?>



                        


                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-md btn-primary" id="klik_edit_rap">Update</button>
                    </td>

                </tr>
            </table>

            

        </div>

    </form>
</div>

<script>
    $(function(){

        $('.money').mask('000,000,000,000,000.00', {reverse: true});

        $('.uang').mask("#,##0", {
            reverse: true
        });

        $('.select2').select2({
            'width' : '100%'
        });

        $('#form_RAP').submit(function(e){
            e.preventDefault();

            jQuery.ajax({
                url: '<?= site_url('Entry_master/ENT_RAP/Aksi_Edit_RAP') ?>',
                type: 'POST',
                dataType: 'HTML',
                data: $(this).serialize(),
                beforeSend: function() {
                    $('#klik_edit_rap').attr('disabled',true).text('Loading...');
                },
                complete:function(){
                 $('#modal_add_rap').hide();
                 $("#modal_add_rap").data('bs.modal').isShown = false;
                 $('body').removeClass('modal-open');
                 $('.modal-backdrop').remove();
                 $('#modal_add_rap').removeClass("in");
                 $('#modal_add_rap').attr('aria-hidden', "true");
                 segarkan_data();
             },success: function(data, textStatus, xhr) {
                $('#klik_edit_rap').attr('disabled',false).text('Update');
                
                // alert(data);
                // return false;
                if (data == 'berhasil') {

                    $.toast({
                        text: 'Data telah diubah',
                        position: 'bottom-right',
                        stack: false,
                        hideAfter: 3000,
                        icon: 'success',

                    });
                    return false;



                }else{
                    $.toast({
                        text: 'terjadi kesalahan',
                        position: 'bottom-right',
                        stack: false,
                        hideAfter: 3000,
                        icon: 'error',

                    });
                    return false;
                }



            },
            error: function(xhr, textStatus, errorThrown) {
                $('#klik_edit_rap').attr('disabled',false).text('Update');
                $.toast({
                    text: 'terjadi kesalahan',
                    position: 'bottom-right',
                    stack: false,
                    hideAfter: 3000,
                    icon: 'error',

                });
                return false;
            }
        });

        });

    })
</script>

