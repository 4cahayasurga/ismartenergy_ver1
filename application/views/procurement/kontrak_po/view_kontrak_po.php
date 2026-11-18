<div class="content-wrapper">
	<section class="content-header">
		<h1 class="text-bold" style="text-decoration: underline;"><?php echo $judul ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('welcome/index') ?>"><i class=" fa fa-dashboard"></i> Home</a></li>
			<li class="active"><?php echo $judul ?></li>
		</ol>
		<button onclick="history.back()" class="btn btn-md btn-link" style="background-color:#34495e;color:white;margin-top: 3px;"><i class="fa fa-arrow-left"></i> BACK</button>
	</section>

	<section class="content-header">
		
	</section>

	<section class="content-header">
		<?= (!empty($this->session->flashdata('message'))) ? $this->session->flashdata('message') : ''  ?>

		<div class="box box-primary">
			<div class="box-header">
				<div class="row">
					<div class="col-sm-12">
						<form id="form_ko">

							<div class="col-sm-12">

								<table width="100%" border="0">
									<tr>
										<th width="100px">Job. No</th>
										<td width="10px" class="text-center">:</td>
										<td width="500px">
											<select name="JobNo" id="JobNo" class="form-control input-sm select2" onchange="tampil_table(this.value)" required>
												<option value="">-- Pilih salah satu --</option>
												<?php
												foreach (dapat_list_job() as $data_job) :
													?>
													<option value="<?= $data_job['JobNo'] ?>" <?= ($JobNo == $data_job['JobNo']) ? 'selected' : ''  ?>><?= $data_job['JobNo'] . ' - ' . $data_job['JobNm'] ?></option>

												<?php endforeach; ?>
											</select>
										</td>
										<td></td>
									</tr>
								</table>

								<table width="100%" border="0" style="margin-top:5px;">

									<tr>
										<th width="100px"></th>
										<td width="10px" class="text-center"></td>
										<td width="500px">
											<button type="button" class="btn btn-primary btn-md" id="btn_cari">TAMPILKAN</button>
										</td>
										<td></td>
									</tr>

								</table>

							</div>

						</form>
					</div>
				</div>
			</div>
			<div class="box-body">
				<div class="tampil_table"></div>
			</div>
			<div class="overlay loadingsaya" style="display: none;">
				<i class="fa fa-refresh fa-spin"></i>
			</div>
		</div>





	</section>
	<div class="clearfix"></div>
</div>


<script type="text/javascript">


	$(document).ready(function(){

		if ($('#JobNo').val() !=='') {
			tampil_table();
		}

		$('.select2').select2({
			'width': '100%',
		});

		$('#btn_cari').click(function() {
			tampil_table();
		})



	});

	function tampil_table(JobNo = '') {
		if (JobNo == '') {
			JobNo = $('#JobNo').val();
		}

		if (JobNo == '') {
			alert('Job masih kosong');
			return false;
		}

		$.ajax({
			url: '<?= site_url('Kontrak_po/dapat_tampil_table') ?>',
			type: 'post',
			dataType: 'html',
			data: {
				'JobNo': JobNo
			},
			cache : false,
			beforeSend: function() {
				$('#JobNo').attr('disabled',true);
				$('#btn_cari').attr('disabled',true);
				$('.tampil_table').html('');
			},complete : function(){
				$('#JobNo').attr('disabled',false);
				$('#btn_cari').attr('disabled',false);
			}
		})
		.done(function(data) {
			
			$('.tampil_table').html(data);
			
		})
		.fail(function() {
			alert('Error,Terjadi Kesalahan');
			return false;
		});

	}

	function lakukan_print(LedgerNo = '', ApprovedBy = '', Tipe_ko = '') {
		if (LedgerNo == '') {
			return false;
		}

		if (ApprovedBy == '') {
			// $.toast({
			// 	text: 'Approve Terlebih Dahulu',
			// 	position: 'bottom-right',
			// 	stack: false,
			// 	hideAfter: 2000,
			// 	icon: 'error',

			// });
			alert('Approve Terlebih Dahulu');
			return false;
		}

		if (Tipe_ko == 'KONTRAK') {
			var myurl = "<?= site_url('Kontrak_po/myprint_ko/') ?>" + LedgerNo;
			window.open(myurl, '_blank');
			return false;
		}

		if (Tipe_ko == 'PO') {
			var myurl = "<?= site_url('Kontrak_po/po_print/') ?>" + LedgerNo;
			window.open(myurl, '_blank');
			return false;
		}


	}

	function btnSelect_PO(LedgerNo=''){
		// alert(LedgerNo);
		var NoKO = $('#btnSelect_PO'+LedgerNo).attr('data-NoKO');
		var JobNo = $('#btnSelect_PO'+LedgerNo).attr('data-JobNo');
		var CanceledBy = $('#btnSelect_PO'+LedgerNo).attr('data-CanceledBy');
		var ApprovedBy = $('#btnSelect_PO'+LedgerNo).attr('data-ApprovedBy');
		var ClosedBy = $('#btnSelect_PO'+LedgerNo).attr('data-ClosedBy');

		if (CanceledBy!='') {
			alert(NoKO+' sudah di-batalkan');
			return false;
		}

		if (ApprovedBy=='') {
			window.location.href="<?=site_url('Entry/Procurement/FrmEntryPO_C/PODetail/UPD/')?>"+JobNo+'/'+NoKO;
		}else{
			window.location.href="<?=site_url('Entry/Procurement/FrmEntryPO_C/PODetail/SEE_KO/')?>"+JobNo+'/'+NoKO;
		}

	}

	function btnSelect_KONTRAK(LedgerNo){
		
		// if (LedgerNo=='') {
		// 	alert('Ledger tidak diketahui');
		// 	return false;
		// }
		// alert(LedgerNo);
		var NoKO = $('#btnSelect_KONTRAK'+LedgerNo).attr('data-NoKO');
		var JobNo = $('#btnSelect_KONTRAK'+LedgerNo).attr('data-JobNo');
		var CanceledBy = $('#btnSelect_KONTRAK'+LedgerNo).attr('data-CanceledBy');
		var ApprovedBy = $('#btnSelect_KONTRAK'+LedgerNo).attr('data-ApprovedBy');
		var ClosedBy = $('#btnSelect_KONTRAK'+LedgerNo).attr('data-ClosedBy');
		var UrlRedirect = $('#btnSelect_KONTRAK'+LedgerNo).attr('data-UrlRedirect');

		if (CanceledBy!='') {
			alert(NoKO+' sudah di-batalkan');
			return false;
		}

		window.location.href=UrlRedirect;
		// alert(LedgerNo);
		// return false;
		// return false;

		// window.location.href="<?=site_url('Kontrak_po/detail_ko/')?>"+LedgerNo;;
		// <?=site_url('Kontrak_po/detail_ko/'.$row->LedgerNo.'/'.$row->KategoriId.'/'.$JobNo)?>
		// window.location.href="<?=site_url('Kontrak_po/detail_ko/')?>"+LedgerNo;
		// alert(LedgerNo);
		// window.location.href="<?=site_url('Entry/Procurement/FrmEntryPO_C/PODetail/UPD/')?>"+JobNo+'/'+NoKO;

		// if (ApprovedBy=='') {
		// 	window.location.href="<?=site_url('Entry/Procurement/FrmEntryPO_C/PODetail/UPD/')?>"+JobNo+'/'+NoKO;
		// }else{
		// 	window.location.href="<?=site_url('Entry/Procurement/FrmEntryPO_C/PODetail/SEE_KO/')?>"+JobNo+'/'+NoKO;
		// }

	}
</script>