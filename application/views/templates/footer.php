<style>
	.notify {
		font-size: 14px;
		border-radius: 10px;
		animation: gerak 4s;
	}

	@keyframes gerak {
		0% {
			transform: translateX(-20px);
		}

		25% {
			transform: translateX(20px);
		}

		50% {
			transform: translateX(-20px);
		}

		75% {
			transform: translateX(20px);
		}

		100% {
			transform: translateX(-20px);
		}
	}
</style>
<footer class="main-footer" data-useridlogin="<?= $this->session->userdata('MIS_LOGGED_ID') ?>">
	<div class="pull-right hidden-xs">
		<b>Version</b> 1.1
	</div>
	<strong>Copyright &copy; 2026 <a href="#">IT-IFPRO</a>.</strong> All rights
	reserved.
</footer>


<aside class="control-sidebar control-sidebar-dark">

	<ul class="nav nav-tabs nav-justified control-sidebar-tabs">
		<li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
		<li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
	</ul>

	<div class="tab-content">

		<div class="tab-pane" id="control-sidebar-home-tab">
			<h3 class="control-sidebar-heading">Recent Activity</h3>
			<ul class="control-sidebar-menu">
				<li>
					<a href="javascript:void(0)">
						<i class="menu-icon fa fa-birthday-cake bg-red"></i>

						<div class="menu-info">
							<h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

							<p>Will be 23 on April 24th</p>
						</div>
					</a>
				</li>
				<li>
					<a href="javascript:void(0)">
						<i class="menu-icon fa fa-user bg-yellow"></i>

						<div class="menu-info">
							<h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

							<p>New phone +1(800)555-1234</p>
						</div>
					</a>
				</li>
				<li>
					<a href="javascript:void(0)">
						<i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

						<div class="menu-info">
							<h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

							<p>nora@example.com</p>
						</div>
					</a>
				</li>
				<li>
					<a href="javascript:void(0)">
						<i class="menu-icon fa fa-file-code-o bg-green"></i>

						<div class="menu-info">
							<h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

							<p>Execution time 5 seconds</p>
						</div>
					</a>
				</li>
			</ul>


			<h3 class="control-sidebar-heading">Tasks Progress</h3>
			<ul class="control-sidebar-menu">
				<li>
					<a href="javascript:void(0)">
						<h4 class="control-sidebar-subheading">
							Custom Template Design
							<span class="label label-danger pull-right">70%</span>
						</h4>

						<div class="progress progress-xxs">
							<div class="progress-bar progress-bar-danger" style="width: 70%"></div>
						</div>
					</a>
				</li>
				<li>
					<a href="javascript:void(0)">
						<h4 class="control-sidebar-subheading">
							Update Resume
							<span class="label label-success pull-right">95%</span>
						</h4>

						<div class="progress progress-xxs">
							<div class="progress-bar progress-bar-success" style="width: 95%"></div>
						</div>
					</a>
				</li>
				<li>
					<a href="javascript:void(0)">
						<h4 class="control-sidebar-subheading">
							Laravel Integration
							<span class="label label-warning pull-right">50%</span>
						</h4>

						<div class="progress progress-xxs">
							<div class="progress-bar progress-bar-warning" style="width: 50%"></div>
						</div>
					</a>
				</li>
				<li>
					<a href="javascript:void(0)">
						<h4 class="control-sidebar-subheading">
							Back End Framework
							<span class="label label-primary pull-right">68%</span>
						</h4>

						<div class="progress progress-xxs">
							<div class="progress-bar progress-bar-primary" style="width: 68%"></div>
						</div>
					</a>
				</li>
			</ul>


		</div>

		<div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>

		<div class="tab-pane" id="control-sidebar-settings-tab">
			<form method="post">
				<h3 class="control-sidebar-heading">General Settings</h3>

				<div class="form-group">
					<label class="control-sidebar-subheading">
						Report panel usage
						<input type="checkbox" class="pull-right" checked>
					</label>

					<p>
						Some information about this general settings option
					</p>
				</div>


				<div class="form-group">
					<label class="control-sidebar-subheading">
						Allow mail redirect
						<input type="checkbox" class="pull-right" checked>
					</label>

					<p>
						Other sets of options are available
					</p>
				</div>

				<div class="form-group">
					<label class="control-sidebar-subheading">
						Expose author name in posts
						<input type="checkbox" class="pull-right" checked>
					</label>

					<p>
						Allow the user to show his name in blog posts
					</p>
				</div>


				<h3 class="control-sidebar-heading">Chat Settings</h3>

				<div class="form-group">
					<label class="control-sidebar-subheading">
						Show me as online
						<input type="checkbox" class="pull-right" checked>
					</label>
				</div>


				<div class="form-group">
					<label class="control-sidebar-subheading">
						Turn off notifications
						<input type="checkbox" class="pull-right">
					</label>
				</div>


				<div class="form-group">
					<label class="control-sidebar-subheading">
						Delete chat history
						<a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
					</label>
				</div>

			</form>
		</div>

	</div>
</aside>

<div class="control-sidebar-bg"></div>
</div>

<script src="<?php echo base_url() ?>assets/bower_components/jquery-ui/jquery-ui.min.js"></script>

<script>
	$.widget.bridge('uibutton', $.ui.button);
</script>

<script>
	(function() {
		function syncAppFooter() {
			var content = document.querySelector('.content-wrapper');
			if (content) content.style.removeProperty('min-height');
		}

		function scheduleFooterSync() {
			window.requestAnimationFrame(syncAppFooter);
		}

		document.addEventListener('DOMContentLoaded', scheduleFooterSync);
		window.addEventListener('load', scheduleFooterSync);
		window.addEventListener('resize', scheduleFooterSync);
		document.addEventListener('click', function(event) {
			if (event.target.closest('.sidebar-menu a, [data-widget="tree"]')) {
				window.setTimeout(syncAppFooter, 360);
			}
		});

		var sidebarMenu = document.querySelector('.sidebar-menu');
		if (sidebarMenu && window.MutationObserver) {
			new MutationObserver(scheduleFooterSync).observe(sidebarMenu, {
				subtree: true,
				attributes: true,
				attributeFilter: ['class', 'style']
			});
		}
	})();
</script>

<script src="<?php echo base_url() ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<script src="<?php echo base_url() ?>assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>

<script src="<?php echo base_url() ?>assets/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>

<script src="<?php echo base_url() ?>assets/bower_components/moment/min/moment.min.js"></script>
<script src="<?php echo base_url() ?>assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>

<script src="<?php echo base_url() ?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

<script src="<?php echo base_url() ?>assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>

<script src="<?php echo base_url() ?>assets/bower_components/fastclick/lib/fastclick.js"></script>

<script src="<?php echo base_url() ?>assets/dist/js/adminlte.min.js"></script>

<!-- <script src="<?= base_url(); ?>assets/bower_components/select2/dist/js/select2.min.js"></script> -->
<script src="<?= base_url('assets/select2_baru.js') ?>"></script>


<script src="<?= base_url(); ?>assets/bower_components/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url(); ?>assets/bower_components/datatables/dataTables.bootstrap.js"></script>
<script src="<?= base_url(); ?>assets/bower_components/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script>
	$(function() {
		if (!$.fn.DataTable) return;
		$('.js-app-datatable').each(function() {
			if ($.fn.DataTable.isDataTable(this)) return;
			var orderColumn = Number($(this).attr('data-order-column') || 0);
			var orderDirection = $(this).attr('data-order-direction') || 'asc';
			$(this).DataTable({
				pageLength: 10,
				lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
				searching: true,
				ordering: true,
				order: [[orderColumn, orderDirection]],
				info: true,
				autoWidth: false,
				scrollX: true,
				responsive: false,
				language: {
					search: 'Cari:',
					lengthMenu: 'Tampilkan _MENU_ data',
					info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
					paginate: { previous: 'Sebelumnya', next: 'Berikutnya' },
					emptyTable: 'Belum ada data'
				}
			});
		});
	});
</script>
<script src="<?= base_url() ?>assets/toast/js/jquery.toast.js"></script>
<script src="<?= base_url() ?>assets/sweetalert2/sweetalert2.min.js"></script>
<script src="<?= base_url() ?>assets/jquery.mask.min.js"></script>
<script src="<?= base_url() ?>assets/toastr.js"></script>
<script type="<?= base_url('assets/mycanvas.js') ?>"></script>
<!-- toastify -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<?php (isset($js_saya)) ? $this->load->view($js_saya) : '' ?>
<script>
	function hanyaAngka(event) {
		var angka = (event.which) ? event.which : event.keyCode
		if (angka != 46 && angka > 31 && (angka < 48 || angka > 57))
			return false;
		return true;
	}

	function ppn_baru_decimal_js() {
		var PPN_baru = parseFloat(1.11);
		return PPN_baru;
	}

	function ppn_baru_angka_js() {
		var PPN_baru = parseFloat(0.11);
		return PPN_baru;
	}

	function ubah_uang_js(number, decimals, dec_point, thousands_sep) {
		number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
		var n = !isFinite(+number) ? 0 : +number,
			prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
			sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
			dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
			s = '',
			toFixedFix = function(n, prec) {
				var k = Math.pow(10, prec);
				return '' + Math.round(n * k) / k;
			};
		s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
		if (s[0].length > 3) {
			s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
		}
		if ((s[1] || '').length < prec) {
			s[1] = s[1] || '';
			s[1] += new Array(prec - s[1].length + 1).join('0');
		}
		return s.join(dec);
	}
	var UbahKeUang = function(num) {
		var str = num.toString().replace("", ""),
			parts = false,
			output = [],
			i = 1,
			formatted = null;
		if (str.indexOf(".") > 0) {
			parts = str.split(".");
			str = parts[0];
		}
		str = str.split("").reverse();
		for (var j = 0, len = str.length; j < len; j++) {
			if (str[j] != ",") {
				output.push(str[j]);
				if (i % 3 == 0 && j < (len - 1)) {
					output.push(",");
				}
				i++;
			}
		}
		formatted = output.reverse().join("");
		return ("" + formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
	};
</script>
<!-- notifikasi -->
<script>
	window.refreshNotificationBadge = function() {
		var legacyCount = $('#menu-notif > li:not(.application-notification)').length;
		var count = legacyCount + Number($('#menu-notif').attr('data-application-count') || 0);
		$('#totalNotifHeader').text(count).toggle(count > 0);
	};

	$(function() {
		var userid = $('.main-footer').data('useridlogin');
		// cekSudahVotePelamar harus last function 
		// Notifikasi rekrutmen tidak dipanggil pada portal pipanisasi 1.1.


		// cekSudahVotePelamar harus last function 
		function cekSudahVotePelamar(userid) {
			$.ajax({
				url: "<?= site_url('FrmPelamarPosting/cekHasVote') ?>",
				method: "POST",
				data: {
					userid
				},
				success: function(result) {
					if (result < 1) {
						$('#menu-notif').append(`
							<li class="legacy-notification">
								<a href="<?= site_url('FrmPelamarPosting') ?>">
									<h3 class="text-blue">
										• Menu Pilih Pelamar
									</h3>
								</a>
							</li>
						`);
						var url = "<?= base_url('assets/dist/img/MDH.png') ?>"
						Toastify({
							className: "notify",
							avatar: url,
							text: "Anda belum melakukan Voting pada menu Pilih Pelamar",
							duration: 5000,
							destination: "<?= site_url('FrmPelamarPosting') ?>",
							newWindow: false,
							close: true,
							gravity: "bottom", // `top` or `bottom`
							position: "right", // `left`, `center` or `right`
							stopOnFocus: true, // Prevents dismissing of toast on hover
							style: {
								background: "linear-gradient(to right, #00b09b, #96c93d)",
							},
							onClick: function() {} // Callback after click
						}).showToast();
					}
					window.refreshNotificationBadge();
				}
			})
		}



	})
</script>
<script>
	$(function() {
		function escapeHtml(value) {
			return $('<div>').text(value == null ? '' : value).html();
		}

		function loadApplicationNotifications() {
			$.ajax({
				url: "<?= site_url('Notification_center') ?>",
				method: "GET",
				dataType: "json",
				cache: false,
				success: function(result) {
					$('#menu-notif .application-notification').remove();
					$('#menu-notif').attr('data-application-count', Number(result.count || 0));
					$.each(result.items || [], function(_, item) {
						$('#menu-notif').append(
							'<li class="application-notification">' +
								'<a href="' + escapeHtml(item.url) + '">' +
									'<h4 style="white-space:normal;margin:0 0 4px;"><i class="fa fa-' + escapeHtml(item.icon) + ' text-blue"></i> ' + escapeHtml(item.title) + '</h4>' +
									'<p style="white-space:normal;margin-bottom:3px;">' + escapeHtml(item.message) + '</p>' +
									'<small class="text-muted">' + escapeHtml(item.time || '') + '</small>' +
								'</a>' +
							'</li>'
						);
					});
					if (!(result.items || []).length) {
						$('#menu-notif').append('<li class="application-notification"><a href="#"><p class="text-center text-muted">Tidak ada notifikasi aktif</p></a></li>');
					}
					window.refreshNotificationBadge();
				}
			});
		}

		loadApplicationNotifications();
		window.setInterval(loadApplicationNotifications, 30000);
	});
</script>

<link rel="stylesheet" href="<?= base_url('assets/css/modern-select.css').'?v='.filemtime(FCPATH.'assets/css/modern-select.css') ?>">
<script src="<?= base_url('assets/js/modern-select.js').'?v='.filemtime(FCPATH.'assets/js/modern-select.js') ?>"></script>

<script src="<?= base_url('assets/js/ver11-ui.js').'?v='.filemtime(FCPATH.'assets/js/ver11-ui.js') ?>"></script>
