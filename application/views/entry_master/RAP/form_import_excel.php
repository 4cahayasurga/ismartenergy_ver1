<div class="col-sm-12">
	<div class="table-responsive">

		<form id="formExcel" data-JobNo="<?= $JobNo ?>" data-Alokasi="<?= $Alokasi ?>" data-Versi="<?= $Versi ?>" data-Versi_tipe="<?= $Versi_tipe ?>">
			<table border="0" width="100%">
				<tr>
					<td>Import Excel</td>
					<td>:</td>
					<td>
						<input type="file" name="BahanExcel" id="BahanExcel" class="form-control" accept=".xls, .xlsx" required>
						<span><a href="<?=site_url('Entry_master/ENT_RAP/dapatkan_TemplateExcel')?>" class="btn btn-sm btn-link"  >Download Template Excel</a> </span>
					</td>
				</tr>
			</table>

			<table border="0" width="100%">
				<tr>
					<td></td>
					<td></td>
					<td>
						<input type="hidden" name="JobNo" value="<?=$JobNo?>">
						<input type="hidden" name="Alokasi" value="<?=$Alokasi?>">
						<input type="hidden" name="Versi" value="<?=$Versi?>">
						<input type="hidden" name="Versi_tipe" value="<?=$Versi_tipe?>">

						<button type="submit" class="btn btn-primary btn-md btn_import">Import</button>
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>

<script type="text/javascript">
	$(function(){


		$('#formExcel').submit(function(e){
			e.preventDefault();
			var JobNo = $(this).attr('data-JobNo');
			// alert(JobNo);
			// return false;

			var formData = new FormData(this);
			$.ajax({
				url: "<?= base_url('Entry_master/ENT_RAP/cobacoba') ?>",
				type: "POST",
				dataType: "HTML",
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				async: true,
				cache: false,
				beforeSend: function() {
					$('.btn_import').attr('disabled',true).text('Loading.....');
				},
				complete: function(xhr, textStatus) {
					$('.btn_import').attr('disabled',false).text('Import');
					$('#ModalExportExcel').modal('hide');
				},

				success: function(data) {
					window.location.href="<?=site_url('Entry_master/ENT_RAP/index/')?>"+JobNo
					// alert(data);
					// location.reload();

					// console.log(data);
					

				},
				error: function(xhr, textStatus, errorThrown) {
					$.toast({
						text: 'terjadi kesalahan',
						position: 'bottom-right',
						stack: false,
						hideAfter: 1000,
						icon: 'error',

					});
					return false;
				}
			})


		})


	})
</script>