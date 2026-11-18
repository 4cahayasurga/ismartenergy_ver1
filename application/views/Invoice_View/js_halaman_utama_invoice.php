<script type="text/javascript">
	$(document).ready(function(){
		$('.select2').select2();

		if ($('#FormInvoice #JobNo option:selected').val()!='') {
			tableInvoice();
		}
		
		$(document).on('submit','#FormInvoice',function(event){
			event.preventDefault();
			tableInvoice();
		});

		$(document).on('change','#FormInvoice',function(){
			tableInvoice();
		});

		// button HAPUS
		$(document).on('click','.btnHapus',function(){
			var JobNo 	= $('#FormInvoice #JobNo option:selected').val();
			var NoKO 	= $(this).attr('data-NoKO');
			var InvNo 	= $(this).attr('data-InvNo');
			var url 	= "<?=site_url('Entry/Procurement/Invoice/CekStatusInvoice')?>";
			var LedgerNo 	= $(this).attr('data-LedgerNo');
			var NoSJ 	= $(this).attr('data-NoSJ');
			NoSJ 		= NoSJ.toString();

			$.ajax({
				url: url,
				type: 'POST',
				dataType: 'html',
				data: {JobNo, NoKO, InvNo},
				beforeSend : function(){
					$(this).attr('disabled',true);
				}
			})
			.done(function(data) {
				$(this).attr('disabled',true);

				if (data!='lanjut') {
					alert(data);
					return false;
				}
				
				var isGood=confirm('Confirm delete?');
				if (isGood) {
					$.ajax({
						url : "<?=site_url('Entry/Procurement/Invoice/HapusInvoice')?>",
						type : 'POST',
						dataType : 'html',
						data : {JobNo, NoKO, InvNo, NoSJ, LedgerNo},
					}).done(function(data) {
						
						if(data=='berhasil'){
							tableInvoice();
							toastr.success('Deleted');
						}else{
							alert(data);
							return false;
						}

					}).fail(function() {
						alert('terjadi kesalahan');
					});
				}
			})
			.fail(function() {
				alert('something wrong');
			});
			
		});

	});


	function tableInvoice(){

		var JobNo = $('#FormInvoice #JobNo option:selected').val();

		if (JobNo=='') {
			alert('belum pilih job');
			return false;
		}

		$.ajax({
			url: "<?=site_url('Entry/Procurement/Invoice/get_tableInvoice')?>",
			type: "POST",
			dataType : "html",
			data: $('#FormInvoice').serialize(),
			beforeSend : function(){
				$('#btnFilter').attr('disabled',true);
				$('#tampil_table_invoice').html('');
			},
			complete : function(){
				$('#btnFilter').attr('disabled',false);
			},
			success: function (data) {
				// alert(data);
				$('#tampil_table_invoice').html(data);
			},
			error : function(){
				alert('something wrong');
			}
		});
	}

</script>