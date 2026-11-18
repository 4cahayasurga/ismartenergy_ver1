
<style type="text/css">
	#form_RAP{
		font-size: 12px;
		/*font-weight: bold;*/
	}
</style>
<div class="row">

	<form id="form_versi">



		<div class="col-sm-12">

			<table border="0" width="100%">
				<tr>
					<td style="font-size:14px;" width="50%">RAP -- FORM TAMBAH VERSI</td>
					<td style="font-size: 12px;color:blue;color:blue;text-align:right;" width="50%"><?= $JobNo . ' - ' . $JobNm ?></td>
				</tr>
				<tr>
					<td style="border-bottom:1px solid black;" colspan="2"></td>
				</tr>
			</table>

			<br>

<!--             <div class="row">
            	<div class="col-sm-6">
            		<table border="0" width="100%">
            			<tr>
            				<td>Perubahan</td>
            			</tr>
            		</table>
            	</div>
            </div> -->

            <table border="0" style="border-collapse: collapse;" width="100%">
            	<tr>
            		<td style="width:150px;">Perubahan</td>
            		<td align="center" width="10px">:</td>
            		<td style="text-align:left;padding-left: 5px;">
            			<input type="radio" name="Perubahan" value="Major" required> Major
            			<input type="radio" name="Perubahan" value="Minor" required> Minor
            		</td>
            	</tr>
            </table>
            <br>


            <table border="0" style="border-collapse: collapse;" width="100%">
            	<tr>
            		<td style="width:150px;">Versi Terkini</td>
            		<td align="center" width="10px">:</td>
            		<td style="text-align:left;padding-left: 5px;">
            			<input type="text" name="Versi_terkini" id="Versi_terkini" class="form-control input-sm" value="<?=$Versi?>" required>
            		</td>
            	</tr>
            </table>
            <br>

            <table border="0" style="border-collapse: collapse;" width="100%">
            	<tr>
            		<td style="width:150px;">Rubah menjadi versi</td>
            		<td align="center" width="10px">:</td>
            		<td style="text-align:left;padding-left: 5px;">
            			<input type="text" name="RubahMenjadiVersi" id="RubahMenjadiVersi" class="form-control input-sm" readonly required>
            		</td>
            	</tr>
            </table>
            <br>	





            <table border="0" width="100%" style="margin-top:10px;">
            	<tr>
            		<td align="right">



            			<input type="hidden" class="form-control input-sm" name="Alokasi" id="Alokasi" placeholder="Alokasi" value="<?= $Alokasi ?>">
            			<input type="hidden" class="form-control input-sm" name="JobNo" id="JobNo" placeholder="Job Nomor" value="<?= $JobNo ?>">

            			<input type="hidden" class="form-control input-sm" name="Versi" id="Versi"  value="<?= $Versi ?>">
            			<hr style="border:1px solid black;">

            			<button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Batal</button>
            			<button type="submit" class="btn btn-md btn-primary btn_simpan_rap" id="btn_simpan_versi">Simpan</button>
            		</td>

            	</tr>
            </table>


        </div>

    </form>
</div>

<script type="text/javascript">
	$(function(){

		$("input[name='Perubahan']").change(function(){
			MajorMinor();
		});

		$('#form_versi').submit(function(e){
			e.preventDefault();

			

			jQuery.ajax({
				url: '<?=site_url("Entry_master/ENT_RAP/aksi_tambah_versi")?>',
				type: 'POST',
				dataType: 'html',
				data: $(this).serialize(),
				beforeSend : function(){
					$('#btn_simpan_versi').attr('disabled',true);
				},
				complete: function(xhr, textStatus) {
					$('#btn_simpan_versi').attr('disabled',false);
					$('#modal_versi').modal('hide');
					segarkan_data();
				},
				success: function(data, textStatus, xhr) {
					location.reload();
				},
				error: function(xhr, textStatus, errorThrown) {
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

		})


		
	})

	function MajorMinor(){
		var cari_versi = $("input[name='Perubahan']:checked").val();
		var Versi_terkini = $('#Versi_terkini').val();

		var huruf_depan = Versi_terkini.substring(1,0);
		var huruf_belakang = Versi_terkini.substring(2);

		if (cari_versi == 'Major') {
			huruf_depan = parseInt(huruf_depan) + 1;
		}

		if (cari_versi == 'Minor') {
			huruf_belakang = parseInt(huruf_belakang) + 1;
		}
		$('#RubahMenjadiVersi').val(huruf_depan+'.'+huruf_belakang);
	}
</script>
