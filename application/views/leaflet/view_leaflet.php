<div class="col-md-12">
	<div class="box box-success">
		<div class="box-header with-border">
			<div class="box-body">

				<?php
				foreach ($data->result() as $data) { 
					?>


					<div class="col-sm-4">

						<div class="panel panel-default">
							<div class="panel-heading">
								<iframe src="<?=site_url('assets/doc/'.$data->leaflet_file)?>" width="100%" style="height:100%;"></iframe>
							</div>
							<div class="panel-body">
								<button type="button" class="btn bg-purple" id="PerbesarPdf" onClick="ShowPdf(<?=$data->LedgerNo?>)"><i class="fa fa-search-plus"></i> Perbesar</button>

								<button type="button" class="btn btn-danger pull-right" id="HapusPdf" onClick="HapusPdf(<?=$data->LedgerNo?>)"><i class="fa fa-trash"></i> Hapus</button>

							</div>
						</div>

					</div>


				<?php } ?>

			</div>
		</div>
	</div>                        
</div>


<div class="modal fade" id="ModalShowDokumen">
	<div class="modal-dialog modal-lg" style="width:90% !important;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body" id="ModalBodyShowDokumen">

			</div>

		</div>
	</div>
</div>


<script type="text/javascript">
	function ShowPdf(LedgerNo){
		$.post("<?= base_url('Leaflet/ShowPdf') ?>", {
			'LedgerNo': LedgerNo
		})
		.done(function(data) {
			$('#ModalShowDokumen').modal('show');
			$('#ModalBodyShowDokumen').html(data);
		})
		.fail(function() {
			alert('Ada yang salah');
		});
	}


	function HapusPdf(LedgerNo) {
		Swal({
			title: 'Anda yakin?',
			text: "Apakah yakin ingin menghapus data ini?",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			confirmButtonText: 'Yakin',
			cancelButtonColor: '#d33',

		}).then((result) => {
			if (result.value) {
				$.ajax({
					url: "<?= base_url('Leaflet/HapusLeaflet') ?>/",
					data: {
						'LedgerNo': LedgerNo
					},
					type: "POST",
					dataType: "HTML",
					success: function(response) {
						// alert(response)
						$.toast({
							heading: 'Berhasil Dihapus',
							position: 'bottom-right',
							showHideTransition: 'slide',
							icon: 'info',
							hideAfter: 1000,
							afterHidden : function(){
								mytable();
								HapusForm();
							}
						})
					},
					error: function(jqXHR, textStatus, errorThrown) {
						alert('Error deleting data');
					}
				});
			}
		});
	}
	
</script>

