<label>Kode RAP</label>
<select name="KdRAP" id="KdRAP" class="form-control form-control-sm" required>
	<option value="">-- Pilih Salah Satu --</option>
	<?php
	if ($alokasi->num_rows() > 0) {
		foreach ($alokasi->result() as $optRap) { ?>
			<option data-Job="<?= $optRap->JobNo ?>" data-Alo="<?= $optRap->Alokasi ?>" value="<?= $optRap->KdRAP ?>"><?= $optRap->KdRAP . ' - ' . $optRap->Uraian ?></option>

	<?php
		}
	} ?>
</select>

<input type="hidden" name="VolRAP" id="VolRAP">
<input type="hidden" name="HrgSatuanRAP" id="HrgSatuanRAP">
<input type="hidden" name="TotalRAP" id="TotalRAP">
<input type="hidden" name="Total" id="Total">

<script type="text/javascript">
	$(function() {
		$('#KdRAP').select2({
			width: '100%'
		})


		$('#KdRAP').on('change', function() {
			var JobNoMy = $('option:selected', this).attr('data-Job');
			var AlokasiMy = $('option:selected', this).attr('data-Alo');
			var KdRAP = this.value;
			$.ajax({
				type: "POST",
				url: "<?= site_url('Spr/getFromRAP') ?>",
				data: {
					'JobNo': JobNoMy,
					'Alokasi': AlokasiMy,
					'KdRAP': KdRAP

				},
				dataType: "JSON",
				success: function(response) {
					$('#VolRAP').val(response.Vol);
					$('#HrgSatuanRAP').val(response.HrgSatuan);
					$('#TotalRAP').val(response.Total);
					DoHitung()
				}
			});
		})

	})
</script>