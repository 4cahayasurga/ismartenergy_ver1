<form id="formsumda">

    <!-- <span class="you-loading" style="display:;padding-bottom:10px;">Loading...</span> -->
    <div class="form-group row">

        <div class="col-xs-6">
            <label for="">Alokasi</label>
            <input type="text" name="Alokasi" id="Alokasi" class="form-control input-sm" value="<?= $alokasi_sumda ?>" readonly>
        </div>
        <div class="col-xs-6">
            <label for="">Versi</label>
            <input type="text" name="Versi" id="Versi" class="form-control input-sm" value="<?= $versi_sumda ?>" readonly>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-xs-3">
            <label for="">Tipe</label>
            <select name="Tipe" id="Tipe" class="form-control input-sm" required>
                <option value="">-- Pilih Salah Satu --</option>
                <option value="Header">Header</option>
                <option value="Detail">Detail</option>
                <option value="Sumda">Sumda</option>
                <option value="Detail Sumda">Detail Sumda</option>

            </select>
        </div>
    </div>

    <div class="form-group row">
        <div id="stageHeader" class="col-xs-12" hidden>
            <label for="">Header</label>
            <select name="Header" id="Header" class="form-control input-sm">
                <option value="">Pilih Salah Satu</option>
                <?php
                foreach ($header as $head) { ?>
                    <option value="<?= $head->KdRAP ?>"><?= $head->KdRAP . ' - ' . $head->Uraian ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <div id="stageDetail" class="col-xs-12" hidden>
            <label for="">Detail</label>
            <select name="Detail" id="Detail" class="form-control input-sm">
                <option value="">Pilih Salah Satu</option>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <div id="stageSumda" class="col-xs-12" hidden>
            <label for="">Sumda</label>
            <select name="Sumda" id="Sumda" class="form-control input-sm">
                <option value="">Pilih Salah Satu</option>
            </select>
        </div>    
    </div>

    <div class="form-group row">

        <div class="col-xs-12">
            <label for="">Kode RAP</label>
            <input type="text" name="KdRAP" id="KdRAP" class="form-control input-sm" placeholder="Kode RAP" required>
        </div>
    </div>

    <div class="form-group row">

        <div class="col-xs-12">
            <label for="">Uraian Pekerjaan</label>
            <textarea name="Uraian" id="Uraian" class="form-control input-sm" placeholder="Uraian" required></textarea>
        </div>
    </div>

    <div class="form-group row">

        <div class="col-xs-12">
            <label for="">Satuan</label>
            <input type="text" name="Uom" id="Uom" class="form-control input-sm" placeholder="Satuan">
        </div>
    </div>

    <div class="form-group row">

        <div class="col-xs-12">
            <label for="">Volume</label>
            <input type="text" name="Vol" id="Vol" class="form-control input-sm" placeholder="0.000" value="1" onkeypress="return hanyaAngka(event)">
        </div>
    </div>



    <div class="form-group row">

        <div class="col-xs-12">
            <label for="">Harga Satuan</label>
            <input type="text" name="HrgSatuan" id="HrgSatuan" class="form-control input-sm uang" placeholder="0.000" value="0">
        </div>
    </div>

    <div class="form-group row">

        <div class="col-xs-12">
            <label for="">Harga SATUAN (RAB)</label>
            <input type="text" name="HrgRAB" id="HrgRAB" class="form-control input-sm uang" placeholder="0.000" value="0">
        </div>
    </div>

    <div class="form-group row">

        <div class="col-xs-12">
            <input type="hidden" name="JobNo" id="JobNo" value="<?= $JobNo ?>">
            <button type="submit" class="btn bg-maroon btn-flat pull-right" id="simpan">Simpan</button>
        </div>
    </div>

</form>

<script>
    $(function() {
        $('#Vol').attr('readonly', true);
        $('#HrgSatuan').attr('readonly', true);
        $('#HrgRAB').attr('readonly', true);
        $('#Uom').attr('readonly', true);

        $('.uang').mask('000,000,000,000,000', {
            reverse: true
        });

        $('#Header').select2({
            width: '100%'
        });
		
        $('#Detail').select2({
            width: '100%'
        });

        $('#Sumda').select2({
            width: '100%'
        });

        $('#Tipe').change(function() {
            Tipe(this.value)

            if (this.value == "Header") {
                document.getElementById("stageHeader").hidden = true;
                document.getElementById("stageDetail").hidden = true;
                document.getElementById("stageSumda").hidden = true;                
            }

            if (this.value == "Detail") {
                document.getElementById("stageHeader").hidden = false;
                document.getElementById("stageDetail").hidden = true;
                document.getElementById("stageSumda").hidden = true;                
            }

            if (this.value == "Sumda") {
                document.getElementById("stageHeader").hidden = false;
                document.getElementById("stageDetail").hidden = false;
                document.getElementById("stageSumda").hidden = true;                
            }

            if (this.value == "Detail Sumda") {
                document.getElementById("stageHeader").hidden = false;
                document.getElementById("stageDetail").hidden = false;
                document.getElementById("stageSumda").hidden = false;                
            }

        })

		$('#Header').change(function(){ 
			// set parameter
			var Alokasi = $('#Alokasi').val();
			var Versi = $('#Versi').val();
			var Header = $(this).val();

			// setup option select
			$.ajax({
				url : "<?php echo site_url('Sumda/getDetail');?>",
				method : "POST",
				data : {
					"JobNo": JobNo,
					"Alokasi": Alokasi,
					"Versi": Versi,
					"Header": Header					
				},
				async : true,
				dataType : 'json',
				success: function(data){
					var html = '';
					var i;
					html += '<option value="0">Pilih Salah Satu</option>';
					for(i=0; i<data.length; i++){
						html += '<option value='+data[i].KdRAP+'>'+data[i].KdRAP+' - '+data[i].Uraian+'</option>';
					}
					$('#Detail').html(html);

				}
			});
			return false;
        }); 

		$('#Detail').change(function(){ 
			// set parameter
			var Alokasi = $('#Alokasi').val();
			var Versi = $('#Versi').val();
			var Detail = $(this).val();

			// setup option select
			$.ajax({
				url : "<?php echo site_url('Sumda/getSumda');?>",
				method : "POST",
				data : {
					"JobNo": JobNo,
					"Alokasi": Alokasi,
					"Versi": Versi,
					"Header": Detail					
				},
				async : true,
				dataType : 'json',
				success: function(data){
					var html = '';
					var i;
					html += '<option value="0">Pilih Salah Satu</option>';
					for(i=0; i<data.length; i++){
						html += '<option value='+data[i].KdRAP+'>'+data[i].KdRAP+' - '+data[i].Uraian+'</option>';
					}
					$('#Sumda').html(html);

				}
			});
			return false;
        }); 

        $('#formsumda').submit(function(e) {
            e.preventDefault();
            $('#simpan').removeAttr('disabled').text('Simpan');

            var formData = new FormData(this);
			
            $.ajax({
                    url: '<?= site_url('Sumda/simpanSumda') ?>',
                    type: 'POST',
                    dataType: 'HTML',
                    data: $(this).serialize(),
                    beforeSend: function() {
                        // $('.you-loading').show();
                        $('#simpan').attr('disabled', 'disabled').text('Loading...');
                    }
                })
                .done(function(response) {

                    $('#simpan').removeAttr('disabled').text('Simpan');
                    $('#my-modal-sumda').modal('hide');
                    // console.log(response);

                    // return false;

                    if (response == 'success') {
                        $.toast({
                            heading: 'Berhasil',
                            // text: 'Data Telah Ditambahkan',
                            position: 'bottom-right',
                            showHideTransition: 'slide',
                            icon: 'success',
                            hideAfter: 1000
                        })
                        return false;
                    }

                    $.toast({
                        heading: 'Gagal Menambahkan Data',
                        // text: 'Data Telah Ditambahkan',
                        position: 'bottom-right',
                        showHideTransition: 'slide',
                        icon: 'info',
                        hideAfter: 1000
                    })
                    return false;

                })
                .fail(function() {
                    $.toast({
                        heading: 'Terjadi Kesalahan',
                        // text: 'Data Telah Ditambahkan',
                        position: 'bottom-right',
                        showHideTransition: 'slide',
                        icon: 'error',
                        hideAfter: 1000
                    })
                    return false;
                })
                .always(function() {
                    // console.log("complete");
                });

        })

    })

    function Tipe() {
		var Tipe = $('#Tipe').val();
		if (Tipe == "Header") {
			$('#HrgSatuan').attr('readonly', true);
			$('#Vol').attr('readonly', true);
			$('#Uom').attr('readonly', true);
			$('#HrgRAB').attr('readonly', true);
		}
		else {
			$('#HrgSatuan').attr('readonly', false);
			$('#Vol').attr('readonly', false);
			$('#Uom').attr('readonly', false);
			$('#HrgRAB').attr('readonly', false);			
		}

		// reset option select
		$("#Detail option[value='']").remove();
		$("#Detail option[value='0']").remove();
		$("#Detail").prepend("<option value='0' selected>Pilih Salah Satu</option>");

		$("#Sumda option[value='']").remove();
		$("#Sumda option[value='0']").remove();
		$("#Sumda").prepend("<option value='0' selected>Pilih Salah Satu</option>");
    }	
</script>
