 <?php
	$NIKnew = $this->session->userdata('MIS_LOGGED_NIK');
	$querynew = $this->db->where('NIK', $NIKnew)->get('Karyawan')->row_array();

	error_reporting(0);
	$fotonew = '';
	if (isset($querynew['Foto'])) {

		if ($fotonew !== NULL || $fotonew !== '') {
			$fotonew = 'assets/foto_karyawan/' . $querynew['Foto'];
		} else {
			$fotonew = 'assets/dist/img/default_foto.jpg';
		}
	} else {
		$fotonew = 'assets/dist/img/default_foto.jpg';
	}


	$NIK_header = $this->session->userdata('MIS_LOGGED_NIK');
	$user_role_sidebar = $this->session->userdata('USER_ROLE') ? $this->session->userdata('USER_ROLE') : 'CUSTOMER';

	?>


 <aside class="main-sidebar">

 	<section class="sidebar">
 		<div class="user-panel" style="padding-top: 50px;">
 			<div class="pull-left image">
 				<img src="<?= site_url('assets/foto_karyawan/user.jpg') ?>" class="img-circle" alt="User Image">
 				<p></p>
 			</div>
 			<div class="pull-left info" style="height: 100px;">
 				<p><?= $this->session->userdata('MIS_LOGGED_NAME'); ?></p>
 				<a href="#"><i class="fa fa-circle text-success"></i></a>
 			</div>
 		</div>

 		<ul class="sidebar-menu" data-widget="tree">
 			<li class="header">MAIN NAVIGATION</li>
			<li class="<?= ($this->uri->segment(1) == 'User_profile') ? 'active' : '' ?>">
				<a href="<?= site_url('User_profile') ?>"><i class="fa fa-user"></i>
					<span>Profile</span>
				</a>
			</li>
			<?php if ($user_role_sidebar === 'CUSTOMER') : ?>
				<li class="<?= ($this->uri->segment(2) == 'customer_dashboard') ? 'active' : '' ?>">
					<a href="<?= site_url('Spbb/customer_dashboard') ?>"><i class="fa fa-dashboard"></i>
						<span>Dashboard Customer</span>
					</a>
				</li>
				<li class="<?= ($this->uri->segment(2) == 'customer_order') ? 'active' : '' ?>">
					<a href="<?= site_url('Spbb/customer_order') ?>"><i class="fa fa-shopping-cart"></i>
						<span>Pesan melalui SPBB</span>
					</a>
				</li>
				<li class="<?= ($this->uri->segment(2) == 'customer_pipeline') ? 'active' : '' ?>">
					<a href="<?= site_url('Spbb/customer_pipeline') ?>"><i class="fa fa-exchange"></i>
						<span>Pesan melalui Pipanisasi</span>
					</a>
				</li>
				<li class="<?= ($this->uri->segment(2) == 'customer_vessels') ? 'active' : '' ?>">
					<a href="<?= site_url('Spbb/customer_vessels') ?>"><i class="fa fa-ship"></i>
						<span>Master Kapal & Kuota</span>
					</a>
				</li>
				<li class="<?= ($this->uri->segment(2) == 'customer_documents') ? 'active' : '' ?>">
					<a href="<?= site_url('Spbb/customer_documents') ?>"><i class="fa fa-folder-open"></i>
						<span>Dokumen Legalitas</span>
					</a>
				</li>
				<li class="<?= ($this->uri->segment(2) == 'customer_users') ? 'active' : '' ?>">
					<a href="<?= site_url('Spbb/customer_users') ?>"><i class="fa fa-user-plus"></i>
						<span>Pengajuan User</span>
					</a>
				</li>
				<li class="<?= ($this->uri->segment(2) == 'customer_history') ? 'active' : '' ?>">
					<a href="<?= site_url('Spbb/customer_history') ?>"><i class="fa fa-file-text-o"></i>
						<span>Riwayat & Invoice</span>
					</a>
				</li>
			<?php elseif ($user_role_sidebar === 'OPERATOR') : ?>
				<li class="<?= $this->uri->segment(1) == 'Spbb_operator' ? 'active' : '' ?>">
					<a href="<?= site_url('Spbb_operator') ?>"><i class="fa fa-th-large"></i>
						<span>Dashboard Operator</span>
					</a>
				</li>
				<li>
					<a href="<?= site_url('Spbb_operator#antrean') ?>"><i class="fa fa-list"></i>
						<span>Antrean Pengisian</span>
					</a>
				</li>
				<li>
					<a href="<?= site_url('Spbb_operator#rekap') ?>"><i class="fa fa-bar-chart"></i>
						<span>Rekap per Shift</span>
					</a>
				</li>
			<?php else : ?>
				<li class="<?= ($this->uri->segment(2) == 'index' || $this->uri->segment(2) == '') ? 'active' : '' ?>">
					<a href="<?= site_url('welcome/index') ?>"><i class="fa fa-home"></i>
						<span>Dashboard SPBU</span>
						<span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>
					</a>
				</li>
				<li class="<?= ($this->uri->segment(2) == 'Dashboard2') ? 'active' : '' ?>">
					<a href="<?= site_url('welcome/Dashboard2') ?>"><i class="fa fa-tachometer"></i>
						<span>Dashboard SPBB</span>
						<span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>
					</a>
				</li>
				<li class="<?= ($this->uri->segment(2) == 'Dashboard3') ? 'active' : '' ?>">
					<a href="<?= site_url('welcome/Dashboard3') ?>"><i class="fa fa-line-chart"></i>
						<span>Stock Movement</span>
						<span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>
					</a>
				</li>
				<?php if ($user_role_sidebar === 'PEMILIK_SPBB') : ?>
					<li class="<?= ($this->uri->segment(1) == 'Spbb' && $this->uri->segment(2) == 'owner') ? 'active' : '' ?>">
						<a href="<?= site_url('Spbb/owner') ?>"><i class="fa fa-check-square-o"></i>
							<span>Pesanan SPBB</span>
						</a>
					</li>
					<li class="<?= ($this->uri->segment(1) == 'Spbb' && $this->uri->segment(2) == 'owner_pipeline') ? 'active' : '' ?>">
						<a href="<?= site_url('Spbb/owner_pipeline') ?>"><i class="fa fa-exchange"></i>
							<span>Pesanan Pipanisasi</span>
						</a>
					</li>
					<li class="<?= ($this->uri->segment(1) == 'Spbb_pertamina') ? 'active' : '' ?>">
						<a href="<?= site_url('Spbb_pertamina') ?>"><i class="fa fa-file-text"></i>
							<span>Order BBM PERTAMINA Patra Niaga</span>
						</a>
					</li>
					<li class="<?= ($this->uri->segment(1) == 'Spbb_receipt') ? 'active' : '' ?>">
						<a href="<?= site_url('Spbb_receipt') ?>"><i class="fa fa-truck"></i>
							<span>Penerimaan BBM</span>
						</a>
					</li>
				<?php endif; ?>
			<?php endif; ?>
			

			 <li class="header" style="display:none;">MENU BASIC</li>

			<?php if ($this->session->userdata('USER_ROLE') === 'SUPERADMIN' || strtolower($this->session->userdata('MIS_LOGGED_ID')) === 'admin') : ?>
				<li class="header">SUPERADMIN</li>
				<li class="<?= ($this->uri->segment(1) == 'Superadmin_registration') ? 'active' : '' ?>">
					<a href="<?= site_url('Superadmin_registration') ?>"><i class="fa fa-user-plus"></i>
						<span>Approval Registrasi</span>
						<span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>
					</a>
				</li>
			<?php endif; ?>





 		<!-- === START ISMARTENERGI === -->
		<?php if (!in_array($user_role_sidebar, array('CUSTOMER', 'OPERATOR'))) : ?>
 		<li class="header">MASTER DATA</li>
 		<li class="treeview <?= ($this->uri->segment(2) == 'MasterData') ? 'active menu-open' : ''  ?>">
 			<a href="#"><i class="fa fa-folder-open"></i>
 				<span>Master Data</span>
 				<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
 			</a>

 			<ul class="treeview-menu">
				<!-- <li class="treeview <?= ($this->uri->segment(3) == 'Property') ? 'active menu-open' : ''  ?>">
					<a href="#"><i class="fa fa-building"></i>
						<span>Property</span>
						<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
					</a>

					<ul class="treeview-menu">
						<li class="treeview">
							<li class="<?= ($this->uri->segment(4) == 'Building') ? 'active' : ''  ?>">
								<a href="<?= site_url('Tenancy/MasterData/Property/Building') ?>"><i class="fa fa-circle-o"></i>
									Buildings
								</a>
							</li>
							<li class="<?= ($this->uri->segment(4) == 'Unit') ? 'active' : ''  ?>">
								<a href="<?= site_url('Tenancy/MasterData/Property/Unit') ?>"><i class="fa fa-circle-o"></i>
									Unit Types
								</a>
							</li>
						</li>
					</ul>
				</li> -->

				<li class="<?= ($this->uri->segment(4) == 'Unit_Facility') ? 'active' : ''  ?>">
					<a href="<?= site_url('Spbb_material') ?>"><i class="fa fa-circle-o"></i>
						DATA MATERIAL
					</a>
				</li>

				<li class="<?= ($this->uri->segment(4) == 'Unit_Facility') ? 'active' : ''  ?>">
					<a href="<?= site_url('Ist/Basic_Data/DataSPBB') ?>"><i class="fa fa-circle-o"></i>
						DATA SPBB
					</a>
				</li>
				<li class="<?= ($this->uri->segment(4) == 'Cost_Category') ? 'active' : ''  ?>">
					<a href="<?= site_url('Ist/Basic_Data/JbtQuota') ?>"><i class="fa fa-circle-o"></i>
						KUOTA JBT
					</a>
				</li>
				<li class="<?= ($this->uri->segment(1) == 'Spbb_user_access') ? 'active' : ''  ?>">
					<a href="<?= site_url('Spbb_user_access') ?>"><i class="fa fa-circle-o"></i>
						PENGATURAN AKSES USER
					</a>
				</li>
				
 			</ul>
 		</li>
		<?php endif; ?>

		<?php if (!in_array($user_role_sidebar, array('CUSTOMER', 'OPERATOR'))) : ?>
		<li class="header">TRANSAKSI</li>
 		<li class="treeview <?= in_array($this->uri->segment(1), array('Spbb', 'Spbb_pertamina', 'Spbb_receipt')) || $this->uri->segment(2) == 'Import_spbb' ? 'active menu-open' : '' ?>">
 			<a href="#"><i class="fa fa-folder-open"></i>
 				<span>DATA TRANSAKSI</span>
 				<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
 			</a>

			<ul class="treeview-menu">
				<?php if ($user_role_sidebar === 'PEMILIK_SPBB') : ?>
				<li class="treeview <?= in_array($this->uri->segment(1), array('Spbb', 'Spbb_pertamina', 'Spbb_receipt')) ? 'active menu-open' : '' ?>">
					<a href="#"><i class="fa fa-circle-o"></i>
						OPERASIONAL TRANSAKSI
						<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
					</a>
					<ul class="treeview-menu">
						<li class="<?= $this->uri->segment(1) == 'Spbb' && $this->uri->segment(2) == 'owner' ? 'active' : '' ?>">
							<a href="<?= site_url('Spbb/owner#approval-pesanan') ?>"><i class="fa fa-check-square-o"></i>
								APPROVAL PESANAN CUSTOMER
							</a>
						</li>
						<li class="<?= $this->uri->segment(1) == 'Spbb_pertamina' ? 'active' : '' ?>">
							<a href="<?= site_url('Spbb_pertamina') ?>"><i class="fa fa-file-text-o"></i>
								ORDER BBM PERTAMINA PATRA NIAGA
							</a>
						</li>
						<li class="<?= $this->uri->segment(1) == 'Spbb_receipt' ? 'active' : '' ?>">
							<a href="<?= site_url('Spbb_receipt') ?>"><i class="fa fa-truck"></i>
								PENERIMAAN BBM
							</a>
						</li>
					</ul>
				</li>
				<?php endif; ?>

				<li class="treeview <?= $this->uri->segment(2) == 'Import_spbb' ? 'active menu-open' : '' ?>">
					<a href="#"><i class="fa fa-file-excel-o"></i>
						IMPORT EXCEL STOCK
						<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
					</a>
					<ul class="treeview-menu">
						<li class="<?= $this->uri->segment(3) == 'DataSupply' ? 'active' : '' ?>">
							<a href="<?= site_url('Ist/Import_spbb/DataSupply') ?>"><i class="fa fa-circle-o"></i>
								STOCK MASUK (SUPPLY)
							</a>
						</li>
						<li class="<?= $this->uri->segment(3) == 'DataDelivery' ? 'active' : '' ?>">
							<a href="<?= site_url('Ist/Import_spbb/DataDelivery') ?>"><i class="fa fa-circle-o"></i>
								PENJUALAN / STOCK KELUAR
							</a>
						</li>
					</ul>
				</li>
				<li class="<?= $this->uri->segment(1) == 'Welcome' && $this->uri->segment(2) == 'Dashboard3' ? 'active' : '' ?>">
					<a href="<?= site_url('Welcome/Dashboard3') ?>"><i class="fa fa-line-chart"></i>
						DASHBOARD STOCK MOVEMENT
					</a>
				</li>

			</ul>

		
 		</li>
		<?php endif; ?>
 		<!-- === END ISMARTENERGI === -->


 		<!-- === START INVENTORY === -->
 		<li class="header hidden">INVENTORY IFPRO</li>
 		<li class="treeview <?= ($this->uri->segment(2) == 'Inventory') ? 'active menu-open' : ''  ?> hidden">
 			<a href="#"><i class="fa fa-pencil-square-o"></i>
 				<span>Inventory</span>
 				<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
 			</a>

 			<ul class="treeview-menu">
			 	<li class="treeview <?= ($this->uri->segment(2) == 'Inventory' && $this->uri->segment(3) == 'MasterData') ? 'active menu-open' : ''  ?>">
					<a href="#"><i class="fa fa-folder-open"></i>
						<span>Master Data</span>
						<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
					</a>

					<ul class="treeview-menu">
						<li class="<?= ($this->uri->segment(2) == 'Inventory' && $this->uri->segment(3) == 'MasterData' && $this->uri->segment(4) == 'Common_Code') ? 'active' : ''  ?>">
							<a href="<?= site_url('Tenancy/Inventory/MasterData/Common_Code') ?>"><i class="fa fa-circle-o"></i>
								Common Codes
							</a>
						</li>

						<li class="<?= ($this->uri->segment(2) == 'Inventory' && $this->uri->segment(3) == 'MasterData' && $this->uri->segment(4) == 'Payment_Gate') ? 'active' : ''  ?>">
							<a href="<?= site_url('Tenancy/Inventory/MasterData/Payment_Gate') ?>"><i class="fa fa-circle-o"></i>
								Payment Gate
							</a>
						</li>

						<li class="<?= ($this->uri->segment(2) == 'Inventory' && $this->uri->segment(3) == 'MasterData' && $this->uri->segment(4) == 'Payment_Method') ? 'active' : ''  ?>">
							<a href="<?= site_url('Tenancy/Inventory/MasterData/Payment_Method') ?>"><i class="fa fa-circle-o"></i>
								Payment Method
							</a>
						</li>

						<li class="<?= ($this->uri->segment(2) == 'Inventory' && $this->uri->segment(3) == 'MasterData' && $this->uri->segment(4) == 'Payload_Acquirer') ? 'active' : ''  ?>">
							<a href="<?= site_url('Tenancy/Inventory/MasterData/Payload_Acquirer') ?>"><i class="fa fa-circle-o"></i>
								Payload Acquirer
							</a>
						</li>
						
						<li>
							<a href="<?= site_url('Tenancy/MasterData/Property/Building') ?>"><i class="fa fa-circle-o"></i>
								Area/Buildings
							</a>
						</li>

						<li class="<?= ($this->uri->segment(2) == 'Inventory' && $this->uri->segment(3) == 'MasterData' && $this->uri->segment(4) == 'Departement') ? 'active' : ''  ?>">
							<a href="<?= site_url('Tenancy/Inventory/MasterData/Departement') ?>"><i class="fa fa-circle-o"></i>
								Departements
							</a>
						</li>

						<li class="<?= ($this->uri->segment(3) == 'Bank') ? 'active' : ''  ?>">
							<a href="<?= site_url('Tenancy/MasterData/Bank') ?>"><i class="fa fa-circle-o"></i>
								Banks
							</a>
						</li>

						<li class="<?= ($this->uri->segment(2) == 'Inventory' && $this->uri->segment(3) == 'MasterData' && $this->uri->segment(4) == 'Measure') ? 'active' : ''  ?>">
							<a href="<?= site_url('Tenancy/Inventory/MasterData/Measure') ?>"><i class="fa fa-circle-o"></i>
								UOM
							</a>
						</li>
					</ul>
				</li>

				<li class="treeview <?= ($this->uri->segment(2) == 'Inventory' && $this->uri->segment(3) == 'Selling') ? 'active menu-open' : ''  ?>">
					<a href="#"><i class="fa fa-tag"></i>
						<span>Selling</span>
						<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
					</a>

					<ul class="treeview-menu">
						<li class="<?= ($this->uri->segment(2) == 'Inventory' && $this->uri->segment(3) == 'Selling' && $this->uri->segment(4) == 'POS') ? 'active' : ''  ?>">
							<a href="<?= site_url('Tenancy/Inventory/Selling/POS') ?>"><i class="fa fa-circle-o"></i>
								Point of Sales
							</a>
						</li>

						<li class="<?= ($this->uri->segment(2) == 'Inventory' && $this->uri->segment(3) == 'Selling' && $this->uri->segment(4) == 'Customer') ? 'active' : ''  ?>">
							<a href="<?= site_url('Tenancy/Inventory/Selling/Customer') ?>"><i class="fa fa-circle-o"></i>
								Customers
							</a>
						</li>
					</ul>
				</li>

				<li class="treeview <?= ($this->uri->segment(2) == 'Inventory' && $this->uri->segment(3) == 'Buying') ? 'active menu-open' : ''  ?>">
					<a href="#"><i class="fa fa-shopping-cart"></i>
						<span>Buying</span>
						<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
					</a>

					<ul class="treeview-menu">
						<li class="<?= ($this->uri->segment(2) == 'Inventory' && $this->uri->segment(3) == 'Buying' && $this->uri->segment(4) == 'Supplier') ? 'active' : ''  ?>">
							<a href="<?= site_url('Tenancy/Inventory/Buying/Supplier') ?>"><i class="fa fa-circle-o"></i>
							Suppliers
							</a>
						</li>
					</ul>
				</li>

				<li class="treeview <?= ($this->uri->segment(2) == 'Inventory' && $this->uri->segment(3) == 'Stock') ? 'active menu-open' : ''  ?>">
					<a href="#"><i class="fa fa-cubes"></i>
						<span>Stock</span>
						<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
					</a>

					<ul class="treeview-menu">
						<li class="<?= ($this->uri->segment(2) == 'Inventory' && $this->uri->segment(3) == 'Stock' && $this->uri->segment(4) == 'Incoming') ? 'active' : ''  ?>">
							<a href="<?= site_url('Tenancy/Inventory/Stock/Incoming') ?>"><i class="fa fa-circle-o"></i>
								Incoming
							</a>
						</li>

						<li class="<?= ($this->uri->segment(2) == 'Inventory' && $this->uri->segment(3) == 'Stock' && $this->uri->segment(4) == 'Outgoing') ? 'active' : ''  ?>">
							<a href="<?= site_url('Tenancy/Inventory/Stock/Outgoing') ?>"><i class="fa fa-circle-o"></i>
								Outgoing
							</a>
						</li>

						<li class="<?= ($this->uri->segment(2) == 'Inventory' && $this->uri->segment(3) == 'Stock' && $this->uri->segment(4) == 'Stock_Entry') ? 'active' : ''  ?>">
							<a href="<?= site_url('Tenancy/Inventory/Stock/Stock_Entry') ?>"><i class="fa fa-circle-o"></i>
								Stock Entries
							</a>
						</li>

						<li class="<?= ($this->uri->segment(2) == 'Inventory' && $this->uri->segment(3) == 'Stock' && $this->uri->segment(4) == 'FrmInventory') ? 'active' : ''  ?>">
							<a href="<?= site_url('Tenancy/Inventory/Stock/FrmInventory') ?>"><i class="fa fa-circle-o"></i>
								Items
							</a>
						</li>
					</ul>
				</li>

				<li class="treeview <?= ($this->uri->segment(2) == 'Inventory' && $this->uri->segment(3) == 'Report') ? 'active menu-open' : ''  ?>">
					<a href="#"><i class="fa fa-bar-chart"></i>
						<span>Report</span>
						<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
					</a>

					<ul class="treeview-menu">
						<li class="<?= ($this->uri->segment(2) == 'Inventory' && $this->uri->segment(3) == 'Report' && $this->uri->segment(4) == 'Stock_Ledger') ? 'active' : ''  ?>">
							<a href="<?= site_url('Tenancy/Inventory/Report/Stock_Ledger/index') ?>"><i class="fa fa-circle-o"></i>
								Stock Ledger
							</a>
						</li>
					</ul>
				</li>
				
 				<li class="treeview">
					<!-- <li>
						<a href="<?= site_url('Tenancy/Inventory/FrmInventory') ?>"><i class="fa fa-circle-o"></i>
							Input Data Inventory
						</a>
					</li>
					<li>
						<a href="<?= site_url('Entry/Procurement/FrmDrp') ?>"><i class="fa fa-circle-o"></i>
							Transaksi Barang
						</a>
					</li>				 
					<li <?= ($this->session->userdata('PecahToken')->RAP == 0) ? 'hidden' : '' ?>>
						<a href=""><i class="fa fa-circle-o"></i>
							APRROVAL DRP
						</a>
					</li> -->






					<li class="treeview " hidden>
						<a href="#">
							<i class="fa fa-circle-o"></i>PROCUREMENT
							<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
						</a>
						<ul class="treeview-menu">
							<li style="display:;" <?= ($this->session->userdata('PecahToken')->EntrySPR == 0) ? 'hidden' : '' ?>><a href="<?= base_url('FrmSPR/view/') ?>"><i class="fa fa-circle-o"></i> SP2B/J </a>
							</li>

							<li style="display:;" <?= ($this->session->userdata('PecahToken')->ApprovalSPR == 0) ? 'hidden' : '' ?>><a href="<?= base_url('Approved_spr/view/') ?>"><i class="fa fa-circle-o"></i> Approval SP2B/J</a></li>

							<li <?= ($this->session->userdata('PecahToken')->KO == 0) ? 'hidden' : '' ?>>
								<a href="<?= site_url('Kontrak_po') ?>"><i class="fa fa-circle-o"></i> Kontrak/Purchase Order</a>
							</li>

							<li style="display:;" <?= ($this->session->userdata('PecahToken')->ApprovalKO == 0) ? 'hidden' : '' ?>><a href="<?= site_url('Approval_ko') ?>"><i class="fa fa-circle-o"></i> Approval <br>Kontrak/Purchase Order</a></li>

							<li style="display:;" <?= ($this->session->userdata('PecahToken')->Akses_MOS_In_Out == 0) ? 'hidden' : '' ?>><a href="<?= site_url('MOS/FrmEntryMOS/view/') ?>"><i class="fa fa-circle-o"></i> Entry MOS</a></li>

							<li style="display:;" <?= ($this->session->userdata('PecahToken')->Akses_ApprovalMOS == 0) ? 'hidden' : '' ?>><a href="<?= site_url('MOS/FrmApproveMOS/view/') ?>"><i class="fa fa-circle-o"></i> Approval MOS</a></li>

							<li style="display:;" <?= ($this->session->userdata('PecahToken')->Invoice == 0) ? 'hidden' : '' ?>><a href="<?= site_url('Entry/Procurement/Invoice/index/') ?>"><i class="fa fa-circle-o"></i> Invoice</a></li>

							<li style="display:;" <?= ($this->session->userdata('PecahToken')->KOAddendum == 0) ? 'hidden' : '' ?>><a href="<?= site_url('Entry/Procurement/KOAddendumRevisi_C') ?>"><i class="fa fa-circle-o"></i> Ko Addendum/Revisi</a></li>

							<li style="display:;" <?= ($this->session->userdata('PecahToken')->ClosingKO == 0) ? 'hidden' : '' ?>><a href="<?= site_url('Entry/Procurement/FrmClosingKO_C/index/') ?>"><i class="fa fa-circle-o"></i> Penutupan KO</a></li>

							<li style="display:;" <?= ($this->session->userdata('PecahToken')->TrackingKO == 0) ? 'hidden' : '' ?>><a href="<?= site_url('Entry/Procurement/TrackingKO_C/index/') ?>"><i class="fa fa-circle-o"></i> Tracking KO</a></li>

							<li style="display:;" <?= ($this->session->userdata('PecahToken')->CancelPO == 0) ? 'hidden' : '' ?>><a href="<?= site_url('Entry/Procurement/PembatalanKO_C/index/') ?>"><i class="fa fa-circle-o"></i> Pembatalan KO</a></li>

						</ul>
					</li>

					<li class="treeview " hidden>
						<a href="#"><i class="fa fa-circle-o"></i>PD/PJ<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
						</a>
						<ul class="treeview-menu">

							<li <?= ($this->session->userdata('PecahToken')->PD == 0) ? 'hidden' : '' ?>><a href="<?= site_url('Entry/PDPJ/EntryPDLap_C/') ?>"><i class="fa fa-circle-o"></i> Entry Permintaan Dana <br>(Lapangan)</a></li>

							<li <?= ($this->session->userdata('PecahToken')->PDFinance == 0) ? 'hidden' : '' ?>><a href="<?= site_url('Entry/PDPJ/EntryPDKeu_C/') ?>"><i class="fa fa-circle-o"></i> Entry Permintaan Dana <br>(Keuangan)</a></li>

							<li <?= ($this->session->userdata('PecahToken')->ApprovalPD_KT == 0) ? 'hidden' : '' ?>><a href="<?= site_url('Entry/PDPJ/FrmApprovalPD_KT') ?>"><i class="fa fa-circle-o"></i> Approval Permintaan Dana <br>(Cost Control)</a></li>

							<li <?= ($this->session->userdata('PecahToken')->ApprovalPD_KK == 0) ? 'hidden' : '' ?>><a href="<?= site_url('Entry/PDPJ/FrmApprovalPD_KK') ?>"><i class="fa fa-circle-o"></i> Approval Permintaan Dana <br>(Keuangan)</a></li>

							<li style="display:;" <?= ($this->session->userdata('PecahToken')->RejectPD == 0) ? 'hidden' : '' ?>><a href="<?= site_url('Entry/PDPJ/RejectPD_C') ?>"><i class="fa fa-circle-o"></i> Reject Permintaan Dana</a></li>

							<li <?= ($this->session->userdata('PecahToken')->PJ == 0) ? 'hidden' : '' ?>><a href="<?= site_url('FrmPJLap/view/') ?>"><i class="fa fa-circle-o"></i> Entry Pertanggungjawaban <br>Dana (Lapangan)</a></li>

							<li <?= ($this->session->userdata('PecahToken')->PJFinance == 0) ? 'hidden' : '' ?>><a href="<?= site_url('Entry/PDPJ/EntryPJKeu/') ?>"><i class="fa fa-circle-o"></i> Entry Pertanggungjawaban <br>Dana (Keuangan)</a></li>

							<li style="display:;" <?= ($this->session->userdata('PecahToken')->ApprovalPJ_KT == 0) ? 'hidden' : '' ?>><a href="<?= site_url('Approval_PDPJ/Approval_PJ_CostControl/Pj_cost_control_data_list/') ?>"><i class="fa fa-circle-o"></i> Approval Pertanggungjawaban <br>Dana (Cost Control)</a></li>

							<li style="display:none;" <?= ($this->session->userdata('PecahToken')->ApprovalPJ == 0) ? 'hidden' : '' ?>>
								<a href="#"><i class="fa fa-circle-o"></i> Approval Pertanggungjawaban <br>Dana (Keuangan)</a>
							</li>

							<li style="display:;" <?= ($this->session->userdata('PecahToken')->EntryRKD == 0) ? 'hidden' : '' ?>>
								<a href="#"><i class="fa fa-circle-o"></i> Entry RKD</a>
							</li>

							<li <?= ($this->session->userdata('PecahToken')->PayPD == 0) ? 'hidden' : '' ?>>
								<a href="<?= site_url('FrmPayment_PD/view') ?>"><i class="fa fa-circle-o"></i> Pembayaran Permintaan <br>Dana</a>
							</li>

							<li style="display:;" <?= ($this->session->userdata('PecahToken')->GiroMundur == 0) ? 'hidden' : '' ?>><a href="#"><i class="fa fa-circle-o"></i> Cek/Giro Mundur</a>
							</li>

						</ul>
					</li>


					<!-- <li style="display:;" <?= ($this->session->userdata('PecahToken')->RPPM == 0) ? 'hidden' : '' ?>><a href="<?= base_url('Menu_entry/Progress_fisik/list_data_PF/') ?>"><i class="fa fa-circle-o"></i> Progress Fisik</a></li> -->

					<!-- <li style="display:;" <?= ($this->session->userdata('PecahToken')->RPPM1 == 0) ? 'hidden' : '' ?>><a href="<?= base_url('BasicData/FrmRPPM_C/index/') ?>"><i class="fa fa-circle-o"></i> RPPM</a></li> -->

					<!-- <li style="display:;" <?= ($this->session->userdata('PecahToken')->RencanaTermin == 0) ? 'hidden' : '' ?>><a href="<?= base_url('Job/dipa/') ?>"><i class="fa fa-circle-o"></i> DIPA / Rencana Termin</a></li> -->

					<!-- <li style="display:;" <?= ($this->session->userdata('PecahToken')->Termin == 0) ? 'hidden' : '' ?>><a href="<?= base_url('DipaTermin/PenerimaanTermin/list_termin/') ?>"><i class="fa fa-circle-o"></i> Penerimaan Termin</a></li> -->

					<!-- <li style="display:none;" <?= ($this->session->userdata('PecahToken')->ApprovalTermin == 0) ? 'hidden' : '' ?>><a href="#"><i class="fa fa-circle-o"></i> Approval Penerimaan Termin</a></li> -->

					<!-- <li style="display:none;" <?= ($this->session->userdata('PecahToken')->LogKasir == 0) ? 'hidden' : '' ?>><a href="#"><i class="fa fa-circle-o"></i> Log Kasir</a></li> -->

					<!-- <li style="display:none;" <?= ($this->session->userdata('PecahToken')->OtherIncome == 0) ? 'hidden' : '' ?>><a href="#"><i class="fa fa-circle-o"></i> Penerimaan Lain-Lain</a></li> -->

					<!-- <li style="display:none;" <?= ($this->session->userdata('PecahToken')->EntryYAD == 0) ? 'hidden' : '' ?>><a href="#"><i class="fa fa-circle-o"></i> Entry YAD</a></li> -->

					<li style="display:none;" <?= ($this->session->userdata('PecahToken')->EntryPlafond == 0) ? 'hidden' : '' ?>><a href="#"><i class="fa fa-circle-o"></i> Entry Permohonan Plafond</a></li>

					<li style="display:none;" <?= ($this->session->userdata('PecahToken')->GiroMundur == 0) ? 'hidden' : '' ?>><a href=""><i class="fa fa-circle-o"></i> Entry Rencana Pendapatan Lain-lain/Biaya</a></li>

					<li style="display:none;" <?= ($this->session->userdata('PecahToken')->RencanaCF == 0) ? 'hidden' : '' ?>><a href=""><i class="fa fa-circle-o"></i> Entry Rencana Pendapatan Lain-lain/Biaya</a></li>

					<li style="display:none;" <?= ($this->session->userdata('PecahToken')->ApprovalRencanaCF == 0) ? 'hidden' : '' ?>><a href=""><i class="fa fa-circle-o"></i> Approval Rencana Pendapatan Lain-lain/Biaya</a></li>


					<li style="display:none;" class="treeview">
						<a href="#"><i class="fa fa-circle-o"></i>Aset<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
						</a>
						<ul class="treeview-menu">

							<li <?= ($this->session->userdata('PecahToken')->Aset == 0) ? 'hidden' : '' ?>><a href="#"><i class="fa fa-circle-o"></i> Jenis Aset</a></li>

							<li <?= ($this->session->userdata('PecahToken')->Aset == 0) ? 'hidden' : '' ?>><a href="#"><i class="fa fa-circle-o"></i> Daftar Aset</a></li>

							<li <?= ($this->session->userdata('PecahToken')->PinjamAset == 0) ? 'hidden' : '' ?>><a href="#"><i class="fa fa-circle-o"></i> Peminjaman Aset</a></li>

						</ul>

					</li>

					<li class="treeview hidden">
						<a href="#">
							<i class="fa fa-circle-o"></i>Keuangan<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
						</a>
						<ul class="treeview-menu">
							<li <?= ($this->session->userdata('PecahToken')->PayPD == 0) ? 'hidden' : '' ?>>
								<a href="<?= site_url('FrmPayment_PD/view') ?>"><i class="fa fa-circle-o"></i> Pembayaran PD
								</a>
							</li>
						</ul>
					</li>

					<!-- <li <?= ($this->session->userdata('PecahToken')->PIC_Project == 0) ? 'hidden' : '' ?>>
						<a href="<?= site_url('TeamPC_C/index') ?>"><i class="fa fa-user"></i>
							<span>PIC PROJECT</span>
						</a>
					</li> -->
				</li>
 			</ul>
 		</li>
 		<!-- === END INVENTORY === -->






 		<li class="treeview" style="display:none;">
 			<a href="#">
 				<i class="fa fa-edit"></i> <span>Procurement</span>
 				<span class="pull-right-container">
 					<i class="fa fa-angle-left pull-right"></i>
 				</span>
 			</a>
 			<ul class="treeview-menu">
 				<li class="treeview">

 				<li <?= ($this->session->userdata('PecahToken')->ApprovalKO == 0) ? 'hidden' : '' ?>><a href="#"><i class="fa fa-circle-o"></i>Approve Kontrak / Purchase Order</a></li>

 				<li <?= ($this->session->userdata('PecahToken')->ApprovalSPR == 0) ? 'hidden' : '' ?>><a href="#"><i class="fa fa-circle-o"></i>Approve Surat Permintaan Material/Alat</a></li>

 				<li <?= ($this->session->userdata('PecahToken')->QuerySPR == 0) ? 'hidden' : '' ?>><a href="#"><i class="fa fa-circle-o"></i>Daftar Surat Permintaan Material/Alat</a></li>

 				<li <?= ($this->session->userdata('PecahToken')->Invoice == 0) ? 'hidden' : '' ?>><a href="#"><i class="fa fa-circle-o"></i>Invoice</a></li>

 				<li <?= ($this->session->userdata('PecahToken')->KOAddendum == 0) ? 'hidden' : '' ?>><a href="#"><i class="fa fa-circle-o"></i>KO Addendum</a></li>

 				<li <?= ($this->session->userdata('PecahToken')->KO == 0) ? 'hidden' : '' ?>><a href="#"><i class="fa fa-circle-o"></i>Kontrak/Purchase Order</a></li>

 				<li <?= ($this->session->userdata('PecahToken')->ClosingKO == 0) ? 'hidden' : '' ?>><a href="#"><i class="fa fa-circle-o"></i>Penutupan Kontrak</a></li>

 				<li <?= ($this->session->userdata('PecahToken')->ApprTrackingKOvalKO == 0) ? 'hidden' : '' ?>><a href="#"><i class="fa fa-circle-o"></i>Tracking KO</a></li>

 				<li><a href="#"><i class="fa fa-circle-o"></i>Approve Material OnSite</a></li>

 				<li><a href="#"><i class="fa fa-circle-o"></i>Pembatalan KO</a></li>

 		</li>

 		</ul>

 		</li>

 		<li class="treeview" style="display:none;">
 			<a href="#">
 				<i class="fa fa-edit"></i> <span>PD / PJ</span>
 				<span class="pull-right-container">
 					<i class="fa fa-angle-left pull-right"></i>
 				</span>
 			</a>
 			<ul class="treeview-menu">
 				<li class="treeview">

 				<li <?= ($this->session->userdata('PecahToken')->ApprovalPD_KT == 0) ? 'hidden' : '' ?>><a href="#"><i class="fa fa-circle-o"></i>Approval Permintaan Dana (Cost Control)</a></li>

 				<li <?= ($this->session->userdata('PecahToken')->ApprovalPD_KK == 0) ? 'hidden' : '' ?>><a href="#"><i class="fa fa-circle-o"></i>Approval Permintaan Dana (Keuangan)</a></li>

 				<li <?= ($this->session->userdata('PecahToken')->ApprovalPJ_KT == 0) ? 'hidden' : '' ?>><a href="#"><i class="fa fa-circle-o"></i>Approval Pertanggungjawaban Dana (Cost Control)</a></li>

 				<li <?= ($this->session->userdata('PecahToken')->ApprovalPJ == 0) ? 'hidden' : '' ?>><a href="#"><i class="fa fa-circle-o"></i>Approval Pertanggungjawaban Dana (Keuangan) </a></li>

 				<li <?= ($this->session->userdata('PecahToken')->GiroMundur == 0) ? 'hidden' : '' ?>><a href="#"><i class="fa fa-circle-o"></i>Cek/Giro Mundur </a></li>

 				<li <?= ($this->session->userdata('PecahToken')->PD == 0) ? 'hidden' : '' ?>><a href="#"><i class="fa fa-circle-o"></i>Entry Permintaan Dana (Lapangan)</a></li>

 				<li <?= ($this->session->userdata('PecahToken')->PDFinance == 0) ? 'hidden' : '' ?>><a href="#"><i class="fa fa-circle-o"></i>Entry Permintaan Dana (Keuangan)</a></li>

 				<li <?= ($this->session->userdata('PecahToken')->PayPD == 0) ? 'hidden' : '' ?>><a href="#"><i class="fa fa-circle-o"></i>Pembayaran Permintaan Dana (Kasir)</a></li>

 				<li <?= ($this->session->userdata('PecahToken')->RejectPD == 0) ? 'hidden' : '' ?>><a href="#"><i class="fa fa-circle-o"></i>Reject Permintaan Dana</a></li>

 				<li <?= ($this->session->userdata('PecahToken')->PJ == 0) ? 'hidden' : '' ?>><a href="#"><i class="fa fa-circle-o"></i>Entry Pertanggungjawaban Dana (Lapangan)</a></li>

 				<li <?= ($this->session->userdata('PecahToken')->PJFinance == 0) ? 'hidden' : '' ?>><a href="#"><i class="fa fa-circle-o"></i>Entry Pertanggungjawaban Dana (Keuangan)</a></li>

 				<li <?= ($this->session->userdata('PecahToken')->EntryRKD == 0) ? 'hidden' : '' ?>><a href="#"><i class="fa fa-circle-o"></i>Entry RKD</a></li>

 		</li>

 		</ul>
 		</li>

 		<li class="treeview" style="display:none;">
 			<a href="#">
 				<i class="fa fa-edit"></i> <span>Operasi</span>
 				<span class="pull-right-container">
 					<i class="fa fa-angle-left pull-right"></i>
 				</span>
 			</a>
 			<ul class="treeview-menu">
 				<li class="treeview">

 				<li <?= ($this->session->userdata('PecahToken')->Rap == 0) ? 'hidden' : '' ?>>
 					<a href="#"><i class="fa fa-circle-o"></i>RAP</a>
 				</li>

 				<li <?= ($this->session->userdata('PecahToken')->RPPM1 == 0) ? 'hidden' : '' ?>>
 					<a href="#"><i class="fa fa-circle-o"></i>Progress Fisik / EMON</a>
 				</li>

 				<li <?= ($this->session->userdata('PecahToken')->Termin == 0) ? 'hidden' : '' ?>>
 					<a href="#"><i class="fa fa-circle-o"></i>Penerimaan Termin</a>
 				</li>

 				<li <?= ($this->session->userdata('PecahToken')->ApprovalTermin == 0) ? 'hidden' : '' ?>>
 					<a href="#"><i class="fa fa-circle-o"></i>Approval Penerimaan Termin</a>
 				</li>

 				<li <?= ($this->session->userdata('PecahToken')->LogKasir == 0) ? 'hidden' : '' ?>>
 					<a href="#"><i class="fa fa-circle-o"></i>Log Kasir</a>
 				</li>

 				<li <?= ($this->session->userdata('PecahToken')->RPPM == 0) ? 'hidden' : '' ?>>
 					<a href="#"><i class="fa fa-circle-o"></i>RPPM</a>
 				</li>

 				<li <?= ($this->session->userdata('PecahToken')->RencanaTermin == 0) ? 'hidden' : '' ?>>
 					<a href="#"><i class="fa fa-circle-o"></i>Rencana Termin</a>
 				</li>

 				<li <?= ($this->session->userdata('PecahToken')->OtherIncome == 0) ? 'hidden' : '' ?>>
 					<a href="#"><i class="fa fa-circle-o"></i>Penerimaan Lain-Lain</a>
 				</li>

 				<li <?= ($this->session->userdata('PecahToken')->EntryYAD == 0) ? 'hidden' : '' ?>>
 					<a href="#"><i class="fa fa-circle-o"></i>Entry YAD</a>
 				</li>

 		</li>

 		</ul>
 		</li>




 		<li class="header" style="display:none;">ENTRIES</li>
 		<li class="treeview" style="display:none;">
 			<a href="#"><i class="fa fa-edit"></i> <span>Entry Basic Menu</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
 			<ul class="treeview-menu">
 				<li class="treeview">
 					<a href="#"><i class="fa fa-circle-o"></i>PROCUREMENT<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
 					<ul class="treeview-menu">
 						<li <?= ($this->session->userdata('PecahToken')->Vendor == 0) ? 'hidden' : '' ?>><a href="<?= site_url('Vendor_new') ?>"><i class="fa fa-circle-o"></i> Vendor</a></li>
 						<li <?= ($this->session->userdata('PecahToken')->ApprovalKO == 0) ? 'hidden' : '' ?>><a href="<?= site_url('Approval_ko') ?>"><i class="fa fa-circle-o"></i> Approval Kontrak /<br> Purchase Order</a></li>

 						<li <?= ($this->session->userdata('PecahToken')->KO == 0) ? 'hidden' : '' ?>><a href="<?= site_url('Kontrak_po') ?>"><i class="fa fa-circle-o"></i> KONTRAK / PO</a></li>

 					</ul>
 				</li>

 				<li class="treeview">
 					<a href="#"><i class="fa fa-circle-o"></i>Keuangan<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
 					</a>
 					<ul class="treeview-menu">
 						<li <?= ($this->session->userdata('PecahToken')->PayPD == 0) ? 'hidden' : '' ?>><a href="<?= site_url('FrmPayment_PD/view') ?>"><i class="fa fa-circle-o"></i> Pembayaran PD</a></li>

 					</ul>
 				</li>

 				<li <?= ($this->session->userdata('PecahToken')->PIC_Project == 0) ? 'hidden' : '' ?>><a href="<?= site_url('TeamPC_C/index') ?>"><i class="fa fa-user"></i><span>PIC PROJECT</span></li>

 				<li class="treeview" hidden>
 					<a href="#"><i class="fa fa-circle-o"></i>ASSET
 						<span class="pull-right-container">
 							<i class="fa fa-angle-left pull-right"></i>
 						</span>
 					</a>
 					<ul class="treeview-menu">
 						<li><a href="#"><i class="fa fa-circle-o"></i> Jenis Aset</a></li>
 						<li><a href="#"><i class="fa fa-circle-o"></i> Daftar Aset</a></li>
 						<li><a href="#"><i class="fa fa-circle-o"></i> Peminjaman Aset</a></li>
 					</ul>
 				</li>

 			</ul>
 		</li>

		<?php if (!in_array($user_role_sidebar, array('CUSTOMER', 'OPERATOR'))) : ?>
 		<li class="header">REPORTS</li>

 		<li class="treeview">
 			<a href="#">
 				<i class="fa fa-bar-chart-o"></i> <span>Menu Report</span>
 				<span class="pull-right-container">
 					<i class="fa fa-angle-left pull-right"></i>
 				</span>
 			</a>
 			<ul class="treeview-menu">

 				<li class="treeview">
					<li><a href="<?= site_url('Ist/Report/SalesRealization') ?>">
							<i class="fa fa-circle-o"></i><span>Sales Realization</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-right pull-right"></i>
							</span>
						</a>
					</li>
 				</li>

				<li class="treeview">
					<li class="<?= $this->uri->segment(2) == 'Import_spbb' && $this->uri->segment(3) == 'DataDelivery' ? 'active' : '' ?>">
						<a href="<?= site_url('Ist/Import_spbb/DataDelivery') ?>">
							<i class="fa fa-circle-o"></i><span>Data Penjualan SPBB</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-right pull-right"></i>
							</span>
						</a>
					</li>
 				</li>

 		<li class="treeview hidden">
 		<li hidden><a href="<?= site_url('Report/MenuRptPDPJ') ?>">
 				<i class="fa fa-circle-o"></i><span>PD/PJ</span>
 				<span class="pull-right-container">
 					<i class="fa fa-angle-right pull-right"></i>
 				</span>
 			</a>
 		</li>
 		</li>

 		<li class="treeview hidden">
 		<li hidden><a href="<?= site_url('Report/MenuRptPO') ?>">
 				<i class="fa fa-circle-o"></i><span>PROCUREMENT</span>
 				<span class="pull-right-container">
 					<i class="fa fa-angle-right pull-right"></i>
 				</span>
 			</a>
 		</li>
 		</li>

 		<li class="treeview hidden">
 			<a href="#"><i class="fa fa-circle-o"></i> ACCOUNTING DAN FINANCE
 				<span class="pull-right-container">
 					<i class="fa fa-angle-left pull-right"></i>
 				</span>
 			</a>
 			<ul class="treeview-menu">
 				<li <?= ($this->session->userdata('PecahToken')->SaldoRek == 0) ? 'hidden' : '' ?>><a href="<?= site_url('Report/SaldoRek') ?>"><i class="fa fa-circle-o"></i>Saldo Rekening</a></li>

 			</ul>
 		</li>
 		</ul>
 		</li>

 		<!-- START PELAMAR -->
 		<li class="header hidden" <?= ($this->session->userdata('PecahToken')->MenuPelamar == 0) ? 'hidden' : '' ?>>PELAMAR</li>
 		<li class="treeview hidden" <?= ($this->session->userdata('PecahToken')->MenuPelamar == 0) ? 'hidden' : '' ?>>
 			<a href="#">
 				<i class="fa fa-user"></i> <span>Menu Pelamar</span>
 				<span class="pull-right-container">
 					<i class="fa fa-angle-left pull-right"></i>
 				</span>
 			</a>
 			<ul class="treeview-menu">

 				<li><a href="<?= site_url('FrmReqKaryawan') ?>"><i class="fa fa-user"></i> Request Karyawan</a></li>
 				<li><a href="<?= site_url('FrmPelamarPosting') ?>"><i class="fa fa-user"></i> Pilih Pelamar</a></li>
 			</ul>
 		</li>
 		<!-- END PELAMAR -->

 		<!-- Start Forum -->
 		<li class="header hidden">Forum</li>
 		<li class="hidden">
 			<a href="<?= site_url('ForumDiskusi') ?>">
 				<i class="fa fa-comments" aria-hidden="true"></i><span>Forum Diskusi</span>
 				<span class="pull-right-container">
 					<i class="fa fa-angle-right pull-right"></i>
 				</span>
 			</a>
 		</li>
 		<!-- End Forum -->

 		<li class="header">TOOLS</li>
 		<li class="treeview">
 			<a href="#"><i class="fa fa-gears"></i><span>TOOLS</span>
 				<span class="pull-right-container">
 					<i class="fa fa-angle-left pull-right"></i>
 				</span>
 			</a>
 			<ul class="treeview-menu">
 				<li <?= ($this->session->userdata('PecahToken')->TrackPD == 0) ? 'hidden' : '' ?>><a href="<?= site_url('FrmTrackPD/view') ?>"><i class="fa fa-circle-o"></i> Tracking PD</a></li>
 				<li <?= ($this->session->userdata('PecahToken')->ChangePasswd == 0) ? 'hidden' : '' ?>><a href="#"><i class="fa fa-circle-o"></i> Change Password</a></li>
 				<li hidden><a href="#"><i class="fa fa-circle-o"></i> Broadcast Notification</a></li>
 			</ul>
 		</li>






 		<!-- ==================== CUTI / KEHADIRAN ============================== -->
 		<!-- <li class="header">Cuti / Kehadiran</li>
		<li class="treeview">
		<a href="#"><i class="fa fa-list"></i><span>Cuti</span>
			<span class="pull-right-container">
			<i class="fa fa-angle-left pull-right"></i>
			</span>
		</a>
		<ul class="treeview-menu">
			<li ><a href="<?= site_url('FrmCuti') ?>"><i class="fa fa-circle-o"></i> Data Cuti</a></li>
		</ul>
		</li> -->
 		<!-- ==================== END CUTI / KEHADIRAN ============================== -->

 		<li style="display:none;">
 			<a href="<?= site_url('LogoList') ?>">
 				<i class="fa fa-wrench"></i> <span>Setting Logo</span>
 				<span class="pull-right-container">
 					<!-- <small class="label pull-right bg-green">new</small> -->
 				</span>
 			</a>
 		</li>

		<?php endif; ?>

 		<li><a href="<?php echo base_url('Auth/logout') ?>"><i class="fa fa-sign-out"></i><span>LOG OUT</span> </a></li>
 		</ul>
 	</section>
 	<!-- /.sidebar -->
 </aside>
