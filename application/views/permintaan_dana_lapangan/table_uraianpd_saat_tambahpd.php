<table width="100%">
	<tr>
		<th class="text-center" style="background-color:#95a5a6;font-size: 12px;">URAIAN PERMINTAAN DANA</th>
	</tr>
</table>

<div class="col-sm-12" style="border:1px solid #95a5a6;margin-top:5px;font-size:12px;">

	<button class="btn btn-sm btn-danger btn_tambah" style="margin-top: 5px;">Tambah</button>
	<table class="table table-bordered table-striped table-hover table-condesed" style="font-size:11px;margin-top: 5px;">
		<thead>
			<tr style="background-color:#27ae60;">
				<th width="5%" class="text-center">No.</th>
				<th width="10%" class="text-center">#</th>
				<th width="8%">Kode RAP</th>
				<th width="25%">Uraian</th>
				<th width="8%" class="text-center">Vol</th>
				<th width="8%" class="text-center">Sat</th>
				<th width="15%" class="text-center">Harga Satuan</th>
				<th width="15%" class="text-center">Jumlah Harga</th>
			</tr>
		</thead>
		<tbody>
			<?php

			if ($data_uraian->num_rows() == 0) {
				echo '
				<tr>
				<td colspan="8">data tidak ditemukan</td>
				</tr>';
				die;
			}
			?>


			<?php
			$no = 1;
			$SubTotal = 0;
			foreach ($data_uraian->result_array() as $data_uraian) : ?>
				<?php
				$SubTotal += $data_uraian['JumlahHarga'];
				?>

				<tr>
					<td width="5%" class="text-center"><?= $no++ ?></td>
					<td width="10%" class="text-center">
						<a href="javascript:void(0)" class="btn btn-xs btn_hapus" data-nopd="<?= $data_uraian['NoPD'] ?>" data-norut="<?= $data_uraian['NoUrut'] ?>" style="background-color: #e74c3c;color:white;"><i class="fa fa-trash"></i> Hapus</a>
						<a href="javascript:void(0)" class="btn btn-xs btn_edit" style="background-color: #f39c12;color:white;"><i class="fa fa-pencil"></i> Ubah</a>
					</td>
					<td width="8%"><?= $data_uraian['KdRAP'] ?></td>
					<td width="25%"><?= strtoupper($data_uraian['Uraian']) ?></td>
					<td width="8%" class="text-center"><?= HilangkanDecimal($data_uraian['Vol']) ?></td>
					<td width="8%" class="text-center"><?= strtoupper($data_uraian['Uom']) ?></td>
					<td width="15%" class="text-center"><?= number_format($data_uraian['HrgSatuan']) ?></td>
					<td width="15%" class="text-center"><?= number_format($data_uraian['JumlahHarga']) ?></td>
				</tr>

			<?php endforeach; ?>
		</tbody>
	</table>

	<?php
	$SaldoSebelumnya = 0;
	$TotalPermintaan = $SubTotal - $SaldoSebelumnya;
	?>

	<table width="100%" border="0" style="font-size: 12px!important;">
		<tr>
			<th style="font-size: 12px!important;padding-right: 10px;" width="85%" class="text-right">Sub Total</th>
			<th style="font-size: 12px!important;" class="text-right">
				<input type="text" class="form-control input-sm" style="font-size: 12px!important;text-align: right;" value="<?= number_format($SubTotal) ?>" disabled>
			</th>
		</tr>
	</table>

	<table width="100%" border="0">
		<tr>
			<th width="80%" class="text-right" style="padding-right: 10px;">Saldo Sebelumnya</th>
			<th width="10%" class="text-right" style="padding-top: 10px;padding-right: 10px;">
				<input type="text" class="form-control input-sm" style="font-size: 12px!important;" disabled>
			</th>
			<th class="text-right" style="padding-top: 10px;">
				<input type="text" class="form-control input-sm" style="font-size: 12px!important;text-align: right;" value="<?= number_format($SaldoSebelumnya) ?>" disabled>
			</th>
		</tr>
	</table>

	<table width="100%" border="0" style="font-size: 12px!important;">
		<tr>
			<th style="font-size: 12px!important;padding-right: 10px;" width="85%" class="text-right">Total Permintaan</th>
			<th style="font-size: 12px!important;padding-top: 10px;padding-bottom: 10px;" class="text-right">
				<input type="text" class="form-control input-sm" style="font-size: 12px!important;text-align: right;" value="<?= number_format($TotalPermintaan) ?>" disabled>
			</th>
		</tr>
	</table>

</div>

<script type="text/javascript">
	$('.btn_hapus').click(function() {
		var NoPD = $(this).attr('data-nopd');
		var NoUrut = $(this).attr('data-norut');
		// alert(NoPD);

		Swal.fire({
			title: 'Anda Yakin?',
			text: "data tidak dapat dikembalikan!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, hapus!',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.value) {

				$.ajax({
					type: "POST",
					url: "<?= site_url('Permintaan_dana_lapangan/hapus_uraian_pd') ?>",
					data: {
						'NoUrut': NoUrut,
						'NoPD': NoPD
					},
					dataType: "html",
					beforeSend: function() {
						$('.loading_table_uraian_dan_invoice').show();
					},
					success: function(data) {
						$('.loading_table_uraian_dan_invoice').hide();
						// alert(data);
						// return false;

						if (data == 'berhasil') {
							$.toast({
								text: 'data telah dihapus',
								position: 'bottom-right',
								stack: false,
								hideAfter: 1000,
								icon: 'success',

							})
						}

						if (data == 'gagal') {
							$.toast({
								text: 'data gagal dihapus',
								position: 'bottom-right',
								stack: false,
								hideAfter: 1000,
								icon: 'error',

							})
						}

						dapat_table_uraian_dan_invoice();

					},
					error: function() {
						$('.loading_table_uraian_dan_invoice').hide();
						$.toast({
							text: 'Terjadi Kesalahan',
							position: 'bottom-right',
							stack: false,
							hideAfter: 1000,
							icon: 'error',

						})
					}
				});


			}
		});



	})
</script>