
<div class="row">
	<div id="col-sm-12">
		<div class="box box-primary">
			<div class="box-header">
				<h3>List Data</h3>
			</div>
			<div class="box-body">

				<div class="table-responsive" style="display:;">
					<table border="1" class="table table-striped table-hover table-condensed table-bordered">
						<thead>
							<tr>
								<td colspan="4"></td>
								<td colspan="2" class="text-center">RAP</td>
								<td colspan="2" class="text-center">Total KO dlm periode Ini</td>
								<td colspan="2" class="text-center">Total KO</td>
							</tr>
							<tr>
								<th>Job No</th>
								<th>Job Name</th>
								<th>Netto Porsi Kontrak (Rp)</th>
								<th>Tanggal PHO</th>

								<!-- RAP -->
								<th class="text-right">Rp</th>
								<th class="text-right">%</th>
								<!-- end RAP -->

								<!-- KO2 (TOTAL KO dalam periode ini) -->
								<th class="text-right">Rp</th>
								<th class="text-right">%</th>
								<!-- END KO2 (TOTAL KO dalam periode ini) -->

								<!-- KO2 (TOTAL KO) -->
								<th class="text-right">Rp</th>
								<th class="text-right">%</th>
								<!-- END KO2 (TOTAL KO) -->


								<!-- BLE2 (TOTAL Pembayaran KO dlm periode ini) -->
								<th class="text-right">Rp</th>
								<th class="text-right">%</th>
								<!-- END BLE2 (TOTAL Pembayaran KO dlm periode ini -->

								<!-- BLE1 (TOTAL Pembayaran KO ) -->
								<th class="text-right">Rp</th>
								<th class="text-right">%</th>
								<!-- END BLE2 (TOTAL Pembayaran KO -->

								<!-- Saldo KO -->
								<th class="text-right">Rp</th>
								<th class="text-right">%</th>
								<!-- END Saldo KO -->

								<!-- Saldo KO -->
								<th class="text-right">Total Hutang Invoice (Rp)</th>
								<!-- END Saldo KO -->


							</tr>
						</thead>
						<tbody>
							<?php

							$QueryPanggilJob = $this->db->query("SELECT *,  
								CASE
								WHEN PersenKSO=0 THEN 100
								ELSE PersenKSO
								END AS 'KSOPersen'
								FROM Job WHERE JobNo IN ('$JobNo') ORDER BY JobNo DESC")->result();



							$KO1 	=0;
							$KO2 	=0;
							$BLE1 	=0;
							$BLE2 	=0;
							$Invoice1 =0;
							$Invoice2 =0;
							$TtlInv=0;
							$RAP=0;
							$NettKontrak =0;

							$TglPHO = '1900-01-01';


							$MyArray_KO = array();

							$SaldoKO = 0;
							$SaldoKO_Persen= 0;

							foreach($QueryPanggilJob as $dataJob) :

								$TglPHO = $dataJob->TglPHO;
								

								// if ($TglPHO <= date('Y-m-d') ) {
								// 	echo "PHO / PEMELIHARAAN ";
								// }else{
								// 	echo "PHO RENCANA TAHUN ".date('Y',strtotime($TglPHO));
								// }

								$MyQuery_KO = $this->db->query("SELECT KoHdr.*, Vendor.VendorNm FROM KoHdr, Vendor WHERE JobNo='$dataJob->JobNo'AND ApprovedBy IS NOT NULL AND CanceledBy IS NULL AND KoHdr.VendorId=Vendor.VendorId")->result_array();

								foreach($MyQuery_KO as $dataKO){

									$NoKO 	= $dataKO['NoKO'];


									// ===== TOTAL KO =====
									if ($dataKO['ClosedBy'] == NULL || $dataKO['ClosedBy'] == '') {
										$KO1 += ($dataKO['SubTotal'] + $dataKO['PPN']) - $dataKO['DiscAmount'];
									}else{
										$CekBLE = $this->db->query("SELECT ISNULL(SUM(BLE.Amount),0) AS 'SumBLE' FROM BLE, KoHdr WHERE BLE.NoKO=KoHdr.NoKO AND KoHdr.NoKO='$NoKO' ");

										if ($CekBLE->num_rows() > 0) {
											$row_BLE = $CekBLE->row_array();
											$KO1 += $row_BLE['SumBLE'];
										}
									}
									// ===== END TOTAL KO =====

									// ======= TOTAL KO dlm periode ini  (KO2) ======
									if ($dataKO['TglKO'] >= $DariTgl AND $dataKO['TglKO'] <= $SampaiTgl ) :

										if ($dataKO['ClosedBy'] == NULL || $dataKO['ClosedBy'] == '') {
											$KO2 += ($dataKO['SubTotal'] + $dataKO['PPN']) - $dataKO['DiscAmount'];
										}else{
											$CekBLE = $this->db->query("SELECT ISNULL(SUM(BLE.Amount),0) AS 'SumBLE' FROM BLE, KoHdr WHERE BLE.NoKO=KoHdr.NoKO AND KoHdr.NoKO='$dataKO[NoKO]' ");

											if ($CekBLE->num_rows() > 0) {
												$row_BLE = $CekBLE->row_array();
												$KO2 += $row_BLE['SumBLE'];
											}
										}


									endif;
									// ===== END TOTAL KO dlm periode ini  (KO2) =====

								}



								$QueryRAP = $this->db->query("SELECT ISNULL(SUM(Vol*HrgSatuan),0) AS 'RAP' FROM RAP WHERE JobNo='$dataJob->JobNo' And Alokasi='B' AND Tipe='Detail'");
								if ($QueryRAP->num_rows() > 0 ) {
									$row = $QueryRAP->row_array();
									$RAP = $row['RAP'];
								}

								// ===== SEPUTAR BLE (Pembayaran) ======
								$BLE_Query = $this->db->query("SELECT BLE.Amount, BLE.TglBayar FROM BLE, KoHdr WHERE BLE.JobNo='$dataJob->JobNo' AND BLE.NoKO=KoHdr.NoKO AND KoHdr.ApprovedBy IS NOT NULL And KoHdr.CanceledBy IS NULL")->result_array();

								foreach($BLE_Query as $BLEData) :
									// == Total pembayaran KO ===
									$BLE1 += $BLEData['Amount'];

									// ==== Total pembayaran KO dlm periode ====
									if ($BLEData['TglBayar'] >='$DariTgl' AND $BLEData['TglBayar'] <='$SampaiTgl' ) {
										$BLE2 += $BLEData['Amount'];
									}

								endforeach;



								// ===== SEPUTAR Invoice (Saldo KO) ======
								$QueryInvoice = $this->db->query("
									SELECT ISNULL(SUM(Invoice.Total),0) AS 'Invoice1' FROM Invoice, KoHdr 
									WHERE Invoice.JobNo='$dataJob->JobNo' AND Invoice.NoKO=KoHdr.NoKO AND KoHdr.ApprovedBy IS NOT NULL AND KoHdr.CanceledBy IS NULL 
									AND KoHdr.ClosedBy IS NULL AND Invoice.InvDate>='$DariTgl' AND Invoice.InvDate<='$SampaiTgl' ");
								if ($QueryInvoice->num_rows() > 0 ) {
									$row = $QueryInvoice->row_array();
									$Invoice1 = $row['Invoice1'];
								}

								$QueryTtlInv = $this->db->query("SELECT ISNULL(SUM(Invoice.Total),0) AS 'TtlInv' FROM Invoice, KoHdr
									WHERE Invoice.JobNo='$dataJob->JobNo'
									AND Invoice.NoKO=KoHdr.NoKO AND KoHdr.ApprovedBy IS Not NULL AND KoHdr.CanceledBy IS NULL
									AND KoHdr.ClosedBy IS NULL");
								if ($QueryTtlInv->num_rows() > 0 ) {
									$row = $QueryTtlInv->row_array();
									$TtlInv = $row['TtlInv'];
								}


								$QueryInvoice2 = $this->db->query("SELECT ISNULL(SUM(InvPD.PaymentAmount),0)  AS 'Invoice2' FROM BLE, InvPD, KoHdr 
									WHERE InvPD.JobNo='$dataJob->JobNo' And BLE.NoPD=InvPD.NoPD 
									And InvPD.NoKO=KoHdr.NoKO And KoHdr.ClosedBy Is NULL And KoHdr.CanceledBy Is NULL 
									And KoHdr.ApprovedBy Is Not NULL");
								if ($QueryInvoice2->num_rows() > 0 ) {
									$row = $QueryInvoice2->row_array();
									$Invoice2 = $TtlInv - $row['Invoice2'];
								}
								// ===== END SEPUTAR Invoice (Saldo KO)  ======

								$NettKontrak = (($dataJob->Netto * $dataJob->KSOPersen ) / 100);
								// $Ttl

								

								$NettKontrak = ($NettKontrak / 1000);
								$NettKontrak = round($NettKontrak);

								$RAP = ($RAP / 1000);
								$RAP = round($RAP);

								$KO2 = ($KO2 / 1000);
								$KO2 = round($KO2);

								$KO1 = ($KO1 / 1000);
								$KO1 = round($KO1);

								$BLE1 = ($BLE1 / 1000);
								$BLE1 = round($BLE1);

								$BLE2 = ($BLE2 / 1000);
								$BLE2 = round($BLE2);

								$Invoice2 = ($Invoice2 / 1000);
								$Invoice2 = round($Invoice2);

								$Invoice1 = ($Invoice1 / 1000);
								$Invoice1 = round($Invoice1);

								$SaldoKO = ($KO1 - $BLE1);
								$SaldoKO = ($SaldoKO / 1000);

								$RAP =  ($RAP?:1);
								$RAP_Persen = ( $RAP /  $NettKontrak ) * 100;

								$KO1 = ($KO1?:1);
								$KO1_Persen = ($KO1 / $RAP ) * 100;

								$KO2 = ($KO2?:1);
								$KO2_Persen = ($KO2 / $RAP ) * 100;

								$BLE1 = ($BLE1?:1);
								$BLE1_Persen = ($BLE1 / $RAP ) * 100;

								$BLE2 = ($BLE2?:1);
								$BLE2_Persen = ($BLE2 / $RAP ) * 100;

								$SaldoKO = ($SaldoKO?:1);
								$SaldoKO_Persen = ($SaldoKO_Persen / $RAP ) * 100;



								$Invoice2 = ($Invoice2?:1);
								$Invoice2_Persen = ($Invoice2 / $RAP ) * 100;




								// $RAP = round($RAP,PHP_ROUND_HALF_UP);
								// $RAP = ($RAP / 1000);
								// $RAP = number_format($RAP);

								// $KO1 = round($KO1,PHP_ROUND_HALF_UP);
								// $KO1 = round($KO1);
								// $KO1 = ($KO1 / 1000);
								// $KO1 = round($KO1);
								// $KO1 = number_format($KO1);

								// $NettKontrak = round
								// $NettKontrak = number_format($NettKontrak);
								// $KO1 = round($KO1,PHP_ROUND_HALF_UP);


								// $RAP = ($RAP / 1000);
								// $RAP = ($RAP?:1);

								// $KO1 = ($KO1 / 1000);
								// $KO1 = ($KO1?:1);

								// $KO2 = ($KO2 / 1000);
								// $KO2 = ($KO2?:1);

								// $BLE1 = ($BLE1 / 1000);
								// $BLE1 = ($BLE1?:1);

								// $BLE2 = ($BLE2 / 1000);
								// $BLE2 = ($BLE2?:1);

								// $NettKontrak = ($NettKontrak / 1000);
								// $NettKontrak = ($NettKontrak?:1);


								?>

								<tr>
									<td><?=$dataJob->JobNo?></td>
									<td><?=$dataJob->JobNm?></td>
									<td class="text-right"><?=number_format($NettKontrak)?></td>
									<td><?=tgl_baru($TglPHO)?></td>

									<td class="text-right"><?=number_format($RAP)?></td>
									<td class="text-right"><?=uang_koma_genap_decimal_kedua($RAP_Persen).'%'?></td>


									<td class="text-right"><?=number_format($KO2)?></td>
									<td class="text-right"><?=uang_koma_genap_decimal_kedua($KO2_Persen).'%'?></td>

									<td class="text-right"><?=number_format($KO1)?></td>
									<td class="text-right"><?=uang_koma_genap_decimal_kedua($KO1_Persen).'%'?></td>

									<td class="text-right"><?=number_format($BLE2)?></td>
									<td class="text-right"><?=uang_koma_genap_decimal_kedua($BLE1_Persen).'%'?></td>

									<td class="text-right"><?=number_format($BLE1)?></td>
									<td class="text-right"><?=uang_koma_genap_decimal_kedua($BLE2_Persen).'%'?></td>

									<td class="text-right"><?=number_format($SaldoKO)?></td>
									<td class="text-right"><?=uang_koma_genap_decimal_kedua($SaldoKO_Persen).'%'?></td>

									<td class="text-right"><?=number_format($Invoice2)?></td>

								</tr>


							<?php endforeach; ?>

						</tbody>
					</table>
				</div>


			</div>
		</div>
	</div>
</div>