<div class="table-responsive">

							<table id="mytable" class="table table-striped table-hover table-condensed table-bordered huruf13" style="margin-top: 20px;">
								<thead>
									<tr class="bg-gray color-palette">
										<th></th>
										<th class="text-center tengah">No.</th>
										<th class="text-center tengah">No KO</th>
										<th class="text-center tengah" width="80px">Tgl KO</th>
										<th class="text-center tengah">Vendor</th>
										<th class="text-center tengah">Tipe KO</th>
										<th class="text-right right" width="80px">Total KO (Rp)</th>
										<th class="text-right right" width="120px">Telah Terbayar (Rp)</th>
										<th class="text-center tengah">Alasan Batal</th>
										<th class="text-center tengah">Canceled By By</th>
										<th class="text-center tengah">Canceled On</th>
										<th class="text-center tengah">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php

									$NoUrut = 1;
									$TotalKO = 0;
									foreach ($ListTable as $row) { 
										$TotalKO = ($row->SubTotal - $row->DiscAmount) + $row->PPN;
										$url_Validasi = '';
										if ($row->KategoriId=='KONTRAK') {
											$url_Validasi = site_url('Kontrak_po/detail_ko/'.$row->LedgerNo.'/KONTRAK/'.$row->JobNo);
										}else{
											$url_Validasi = site_url('Kontrak_po/detail_ko/'.$row->LedgerNo.'/PO/'.$row->JobNo);
										}

										$NoKO = ShowKodeAman($row->NoKO);
										$CanceledBy = ($row->CanceledBy !='' || $row->CanceledBy !=NULL ) ? 1 : 0;
										$ClosedBy = ($row->ClosedBy !='' || $row->ClosedBy !=NULL ) ? 1 : 0;
										$Pembayaran = $row->Pembayaran;
										?>
										<tr>
											<th><?=ShowKodeAman($row->NoKO)?></th>
											<td><?=$NoUrut++?></td>
											<th><?=ShowKodeAman($row->NoKO)?></th>
											<td><?=ShowKodeAman(tgl_baru($row->TglKO))?></td>
											<td><?=ShowKodeAman($row->VendorNm)?></td>
											<td><?=ShowKodeAman($row->KategoriId)?></td>
											<td class="text-right"><?=ShowKodeAman(UbahDecimal_Nol_Dua_Tanpa_Koma($TotalKO))?></td>
											<td class="text-right"><?=ShowKodeAman(UbahDecimal_Nol_Dua_Tanpa_Koma($row->PaymentAmount))?></td>
											<td><?=ShowKodeAman($row->CancelReason)?></td>
											<td><?=ShowKodeAman($row->CanceledBy)?></td>
											<td><?=ShowKodeAman(tgl_baru($row->TimeCancel))?></td>
											<td class="text-center" width="200px">
												<a href="<?=$url_Validasi?>" class="btn btn-link btn-md" style="font-size: 16px !important;" ><u>SELECT</u></a>

												<button class="btn btn-link btn-md" style="font-size: 16px !important;" onclick="btnCancel('<?=$NoKO?>',<?=$CanceledBy?>,<?=$ClosedBy?>,<?=$Pembayaran?>)"   ><u>CANCEL</u></button>

											</td>
										</tr>
										<?php
										
									}
									?>
								</tbody>
							</table>
						</div>