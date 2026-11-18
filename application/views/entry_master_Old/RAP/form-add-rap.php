
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
                    <td style="font-size:14px;" width="50%">RAP -- FORM TAMBAH DATA</td>
                    <td style="font-size: 12px;color:blue;color:blue;text-align:right;" width="50%"><?= $JobNo . ' - ' . $JobNm ?></td>
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
                        <select name="Tipe" id="Tipe" class="form-control input-sm select2" required>
                            <option value="">-- Pilih salah satu --</option>
                            <option value="Header">Header</option>
                            <option value="Detail">Detail</option>
                        </select>
                    </td>

                    <td width="130px" style="padding-left:10px;">Header</td>
                    <td width="10px" align="center">:</td>
                    <td width="35%">
                        <select name="Header" id="Header" class="form-control input-sm select2" required>
                            <option value="0">Top Header</option>
                            <?php
                            foreach ($header_rap as $myheader) : ?>
                                <option value="<?= $myheader['KdRAP'] ?>"><?= $myheader['KdRAP'] . ' - ' . $myheader['Uraian'] ?></option>
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
                        <input type="text" class="form-control input-sm" value="<?= $Alokasi . ' - ' . $Keterangan ?>" disabled>

                    </td>
                    <td></td>
                </tr>
            </table>

            <table border="0" width="100%" style="margin-top:10px;">
                <tr>
                    <td width="130px">Kode RAP</td>
                    <td width="10px" align="center">:</td>
                    <td width="25%">
                        <input type="text" class="form-control input-sm" name="KdRAP" id="KdRAP" placeholder="Kode RAP" autocomplete="off" required>
                    </td>
                    <td></td>
                </tr>
            </table>

            <table border="0" width="100%" style="margin-top:10px;">
                <tr>
                    <td width="130px">Uraian Pekerjaan</td>
                    <td width="10px" align="center">:</td>
                    <td width="25%">
                        <textarea class="form-control input-sm" placeholder="Uraian Pekerjaan" name="Uraian" id="Uraian"   required></textarea>
                        <!-- <input type="text" class="form-control input-sm" name="Uraian" id="Uraian" placeholder="Uraian Pekerjaan" required> -->
                    </td>
                    <td></td>
                </tr>
            </table>

            <table border="0" width="100%" style="margin-top:10px;">
                <tr>
                    <td width="130px">Satuan</td>
                    <td width="10px" align="center">:</td>
                    <td width="25%">
                        <input type="text" class="form-control input-sm" name="Uom" id="Uom" placeholder="Satuan" autocomplete="off">
                    </td>
                    <td></td>
                </tr>
            </table>

            <table border="0" width="100%" style="margin-top:10px;">
                <tr>
                    <td width="130px">Volume</td>
                    <td width="10px" align="center">:</td>
                    <td width="25%">
                        <input type="text" class="form-control input-sm" name="Vol" id="Vol"  placeholder="0" autocomplete="off">
                    </td>
                    <td></td>
                </tr>
            </table>



            <table border="0" width="100%" style="margin-top:10px;">
                <tr>
                    <td width="130px">Harga Satuan</td>
                    <td width="10px" align="center">:</td>
                    <td width="25%">
                        <input type="text" class="form-control input-sm uang" name="HrgSatuan" id="HrgSatuan" placeholder="0" autocomplete="off">
                    </td>
                    <td></td>
                </tr>
            </table>

            <table border="0" width="100%" style="margin-top:10px;">
                <tr>
                    <td width="130px">Harga Satuan (RAB)</td>
                    <td width="10px" align="center">:</td>
                    <td width="25%">
                        <input type="text" class="form-control input-sm uang" name="HrgRAB" id="HrgRAB" placeholder="0" autocomplete="off">
                    </td>
                    <td></td>
                </tr>
            </table>

            <table border="0" width="100%" style="margin-top:10px;">
                <tr>
                    <td align="right">


                        
                        <input type="hidden" class="form-control input-sm" name="Alokasi" id="Alokasi" placeholder="Alokasi" value="<?= $Alokasi ?>">
                        <input type="hidden" class="form-control input-sm" name="JobNo" id="JobNo" placeholder="Job Nomor" value="<?= $JobNo ?>">

                        <input type="hidden" class="form-control input-sm" name="Versi" id="Versi"  value="<?= $Versi ?>">
                        <hr style="border:1px solid black;">

                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-md btn-primary btn_simpan_rap" id="btn_simpan_rap">Simpan</button>
                    </td>

                </tr>
            </table>


        </div>

    </form>
</div>

<script>
    $(function() {

        $('.uang').mask("#,##0", {
            reverse: true
        });

        $('.select2').select2({
            'width' : '100%'
        });
        // $("#Header").select2({ dropdownCssClass: "myFont" });

        $('#Vol').attr('readonly',true);
        $('#Uom').attr('readonly',true);
        $('#HrgSatuan').attr('readonly',true);
        $('#HrgRAB').attr('readonly',true);

        $('#Tipe').change(function() {
            var Tipe = this.value;

            if(Tipe == 'Detail'){
                $('#Vol').attr('readonly',false);
                $('#Uom').attr('readonly',false);
                $('#HrgSatuan').attr('readonly',false);
                $('#HrgRAB').attr('readonly',false);
                return false;
            }

            if(Tipe == 'Header'){
                $('#Vol').attr('readonly',true);
                $('#Uom').attr('readonly',true);
                $('#HrgSatuan').attr('readonly',true);
                $('#HrgRAB').attr('readonly',true);
                return false;
            }

            if(Tipe == ''){
                $('#Vol').attr('readonly',true);
                $('#Uom').attr('readonly',true);
                $('#HrgSatuan').attr('readonly',true);
                $('#HrgRAB').attr('readonly',true);
            }
        });

        $('#form_RAP').submit(function(e){

            e.preventDefault();



            jQuery.ajax({
                url: '<?= site_url('Entry_master/ENT_RAP/aksi_simpan_RAP') ?>',
                type: 'POST',
                dataType: 'HTML',
                data: $(this).serialize(),
                beforeSend: function() {
                    $('#btn_simpan_rap').attr('disabled',true).text('Loading...');
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
                    $('#btn_simpan_rap').attr('disabled',false).text('Simpan');

                    // alert(data);
                    // return false;
                    



                    if (data == 'berhasil') {
                        $.toast({
                            text: 'data telah ditambahkan',
                            position: 'bottom-right',
                            stack: false,
                            hideAfter: 3000,
                            icon: 'success',

                        });
                        return false;
                    }

                    if (data == 'gagal') {
                        $.toast({
                            text: 'terjadi kesalahan',
                            position: 'bottom-right',
                            stack: false,
                            hideAfter: 3000,
                            icon: 'error',

                        });
                        return false;
                    }


                    if (data == 'rap_sudah_ada') {
                        $.toast({
                            text: 'Kode RAP sudah terpakai',
                            position: 'bottom-right',
                            stack: false,
                            hideAfter: 3000,
                            icon: 'info',

                        });
                        return false;
                    }


                    if (data == 'detail_dilarang_top_header') {
                        $.toast({
                            text: 'Tipe Detail tidak dapat menggunakan Top Header',
                            position: 'bottom-right',
                            stack: false,
                            hideAfter: 3000,
                            icon: 'info',

                        });
                        return false;
                    }




                },
                error: function(xhr, textStatus, errorThrown) {
                    $('#btn_simpan_rap').attr('disabled',false).text('Simpan');
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

function validasi_inputan() {
    var Tipe = $('#Tipe').val();
    var Header = $('#Header').val();
    var KdRAP = $('#KdRAP').val();

    var Uraian = $('#Uraian').val();
    var Uom = $('#Uom').val();
    var Vol = $('#Vol').val();

    var HrgSatuan = $('#HrgSatuan').val();
    var HrgRAB = $('#HrgRAB').val();





        // alert(Header);
    }
</script>