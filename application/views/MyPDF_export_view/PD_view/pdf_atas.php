
<table style="width:100%;border:0px solid;border-collapse: collapse;">
	<tr>
		<td style="text-align:left;font-size: 14px;text-decoration: underline;font-weight: bold;" class="ukuran">Permintaan Dana / Pertanggungjawaban Dana</td>
		<td style="text-align:right;" class="ukuran"><?= $gabung ?> Page {PAGENO} of {nbpg}</td>
		<td></td>
	</tr>
</table>
<p></p>

<div class="row" >

	<div class="col-12">
		
		<div class="col-xs-4">

			<table class="table_seratus_persen" border="0" style="margin-bottom:5px;">
				<tr >
					<td style="width:15% !important;">*No PD :</td>
					<td style="border-bottom: 1px solid;width: 30% !important;"><?=$dataPD->NoPD?></td>
					<td style="width: 3%;"></td>
					<td style="width:20% !important;">No.Ref. Lap :</td>
					<td style="border-bottom: 1px solid;"><?=($dataPD->NoRef == '') ? '-' : ''?></td>
				</tr>
			</table>

			<table class="table_seratus_persen" border="0" style="margin-bottom:5px;">
				<tr>
					<td style="width:15% !important;">*Job No :</td>
					<td style="border-bottom: 1px solid;width: 30% !important;"><?=$dataPD->NoPD?></td>
					<td style="width: 3%;"></td>
					<td style="width:20% !important;">*Tgl.Permintaan :</td>
					<td style="border-bottom: 1px solid;"><?=($dataPD->TglPD == '') ? '-' : TanggalSaya($dataPD->TglPD)?></td>
				</tr>
			</table>

			<table class="table_seratus_persen" border="0" style="margin-bottom:5px;">
				<?php
				$query = $this->db->query("SELECT TOP 1 JobNm FROM Job WHERE JobNo='$dataPD->JobNo' ")->row();
				$JobNm = $query->JobNm;
				?>
				<tr>
					<td style="width:15% !important;">*Nm Proyek:</td>
					<td style="border-bottom: 1px solid;"><?=$JobNm?></td>
				</tr>
			</table>

			<table class="table_seratus_persen" border="0" style="margin-bottom:5px;">
				<tr>
					<td style="width:15% !important;">*Deskripsi:</td>
					<td style="border-bottom: 1px solid;"><?=$dataPD->Deskripsi?></td>
				</tr>
			</table>


			<table class="table_seratus_persen" border="0" style="margin-bottom:5px;">
				<tr >
					<td style="width:15% !important;">*Periode :</td>
					<td style="border-bottom: 1px solid;width: 30% !important;"><?=TanggalSaya($dataPD->PrdAwal)?></td>
					<td style="width: 3%;"></td>
					<td style="width:20% !important;">s.d </td>
					<td style="border-bottom: 1px solid;"><?=($dataPD->PrdAkhir == '') ? '-' : TanggalSaya($dataPD->PrdAkhir)?></td>
				</tr>
			</table>


			<table class="table_seratus_persen" border="0" style="margin-bottom:5px;">
				<tr >
					<td style="width:15% !important;">*Minggu Ke :</td>
					<td style="border-bottom: 1px solid;width: 30% !important;"><?=$dataPD->Minggu?></td>
					<td style="width: 3%;"></td>
					<td style="width:20% !important;">Alokasi :</td>
					<td style="border-bottom: 1px solid;"><?=($dataPD->Alokasi == '') ? '-' : $dataPD->Alokasi?></td>
				</tr>
			</table>


		</div>

		<div class="col-xs-7">
			
			<table class="table_seratus_persen" border="0" style="margin-bottom:5px;">
				<tr>
					<td style="text-align:center;background-color: silver;font-weight: bold;padding-top:3px;padding-bottom:3px;">Bayar Kepada</td>
				</tr>
			</table>

			<table class="table_seratus_persen" border="0" style="margin-bottom:5px;">
				<?php 

				$VendorNm = '';
				$Alamat = '';
				$Telepon = '';
				$NPWP = '';

				if (substr($dataPD->NoKO, -3) != 'INT') {

					$get_KO_dan_Vendor = $this->db->query("SELECT B.VendorNm, B.Alamat, ISNULL(NULL,B.Telepon1) AS 'Telepon1', ISNULL(NULL,B.Telepon2) AS 'Telepon2', ISNULL(NULL,B.Telepon3) AS 'Telepon3'  FROM KoHdr A LEFT JOIN Vendor B ON A.VendorId=B.VendorId WHERE NoKO='$dataPD->NoKO'");

					if ($get_KO_dan_Vendor->num_rows() > 0 ) {

						$row_ko = $get_KO_dan_Vendor->row();
						$VendorNm = $row_ko->VendorNm;
						$Alamat = $row_ko->Alamat;
						$Telepon = $row_ko->Telepon1;
						$NPWP = $row_ko->NPWP;

					}else{

						$VendorNm = '';
						$Alamat = '';
						$Telepon = '';
						$NPWP = '';
					}

				}else{

					$VendorNm = '';
					$Alamat = '';
					$Telepon = '';
					$NPWP = '';

				}

				

				
				?>
				<tr >
					<td style="width:16% !important;" class="jarak">*No. Kontak/PO :</td>
					<td style="border-bottom: 1px solid;width: 30% !important;" ><?=($dataPD->NoKO == '') ? '-' : $dataPD->NoKO?></td>
					<td style="width: 3%;"></td>
					<td style="width:10% !important;" class="jarak">*Nama :</td>
					<td style="border-bottom: 1px solid;"><?=($VendorNm == '') ? '-' : $VendorNm?></td>
					<td rowspan="4" style="width:1%"></td>
					<td style="width:10%;text-align: center;font-weight: bold;font-size: 10px;">TIPE FORM</td>
				</tr>

				<tr>
					<td style="width:16% !important;" class="jarak">No.Tagihan :</td>
					<td style="border-bottom: 1px solid;width: 30% !important;"><?=($dataPD->NoTagihan == '') ? '-' : $dataPD->NoTagihan?></td>
					<td style="width: 3%;"></td>
					<td style="width:10% !important;" class="jarak">*Alamat :</td>
					<td style="border-bottom: 1px solid;"><?=($Alamat == '') ? '-' : $Alamat?></td>
					<td rowspan="3" style="width:10%;text-align: center;font-size: 12px;vertical-align: top;"><?=($dataPD->TipeForm == '') ? '-' : $dataPD->TipeForm?></td>
				</tr>

				<tr>
					<td style="width:16% !important;" class="jarak">ID :</td>
					<td style="border-bottom: 1px solid;width: 30% !important;">-</td>
					<td style="width: 3%;"></td>
					<td style="width:10% !important;" class="jarak">*Telepon :</td>
					<td style="border-bottom: 1px solid;"><?=($Telepon== '') ? '-' : $Telepon?></td>
				</tr>

					<tr>
					<td style="width:16% !important;" class="jarak"></td>
					<td style="border-bottom: 0px solid;width: 30% !important;"></td>
					<td style="width: 3%;"></td>
					<td style="width:10% !important;" class="jarak">NPWP :</td>
					<td style="border-bottom: 1px solid;"><?=($NPWP== '') ? '-' : $NPWP?></td>
				</tr>


			</table>


			<table class="table_seratus_persen" border="0" style="margin-bottom:5px;">
				<tr >
					<td style="width:50% !important;">*No PJ : <?=($dataPD->NoPJ =='' || $dataPD->NoPJ == NULL ) ? '-' : $dataPD->NoPJ;  ?></td>
					<td style="width:50% !important;">*Tgl PJ : <?=($dataPD->TglPJ =='' || $dataPD->TglPJ == NULL ) ? '-' : TanggalSaya($dataPD->TglPJ) ?></td>
					
				</tr>
			</table>

		
		</div>


	</div>
</div>

<table style="page-break-inside: avoid !important;"></table>

