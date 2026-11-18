<html>
<head>
	<style>
		@page {
			margin-top : 0.5cm;
			margin-left : 0.5cm;
			margin-right : 14.50cm;
			margin-bottom : 7mm;
		}
		body{
			font-size: 12px;
			font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		}

		#container {
			min-height:100%;
			position:relative;
		}
		#header {
			/*border-bottom: 1px solid;*/
		}
		#body {
			padding-left: 5px;
			padding-bottom:60px;
			border: 0px solid;
		}
		#footer {
			position:absolute;
			bottom:0;
			width:100%;
			/*height:60px;*/
		}
		#tbl_header_dua{
			font-size: 12px;

		}
		#tbl_header_dua tr td{
			font-size: 10px;
			padding-top: 5px;
			/*padding-bottom: 5px;*/
		}
		textarea{
			font-size: 12px;
			font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		}
	</style>
</head>

<?php
$nama_company = ($data_memo->ImageCompany == NULL || $data_memo->ImageCompany == '' ) ? 'NON.jpg' : $data_memo->ImageCompany;

if ($nama_company !='NON.jpg') {

	$cek = substr($nama_company,-4);
	if ($cek!='.jpg'   ) {
		$nama_company = $nama_company.'.jpg';

	}
}


$nama_company = (file_exists('LogoPT/'.$nama_company)) ? $nama_company : 'NON.jpg';
$path =   base_url('LogoPT/'.$nama_company);
// $path =  ($nama_company=='MDH')  ? base_url('LogoPT/logoMinarta.jpeg') : base_url('LogoPT/'.$nama_company);

$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
?>


<body style="border:1px solid;">

	<div id="container">

		<!-- ======= HEADER =========== -->

		<div id="header">

			<table style="height: 18mm;width: 100%;border-collapse: collapse;" border="0">
				<tr>
					<td style="width:7.4cm !important;text-align: center;font-size:14px;font-weight: bold;text-decoration: underline;padding-top:3px;padding-bottom:3px;padding-left: 60px;">&nbsp;MEMO INTERNAL</td>
					<td style="text-align:right;padding-top:3px;padding-bottom:3px;">
						<!-- <img src="<?=$base64?>" style="width: 4cm;height: 100%;max-height: 15mm;"> -->
						<img src="<?=$base64?>" style="height: 100%;width: 5.5cm;">
					</td>
				</tr>
			</table>

			<table height="1.7cm" border="0" width="100%" id="tbl_header_dua" style="border-collapse: collapse;border: 1px solid silver;padding-left: 5px;padding-right: 5px;">
				<tr>
					<td width="10%">&nbsp;Tanggal</td>
					<td style="width:2%;text-align: center;">:</td>
					<td width="60%">&nbsp;<?=TanggalSaya($data_memo->TglMemo)?></td>
					<td rowspan="3" style="vertical-align:top;text-align: right;padding-left: 30px;padding-right: 10px;">
						<table border="0" width="100%" style="border-collapse: collapse;text-align: right;border:1px solid;">
							<tr>
								<th style="text-align:center;padding-top: 5px;padding-bottom: 5px;">No Memo</th>

							</tr>
							<tr>
								<td style="text-align:center;padding-top: 5px;padding-bottom: 5px;"><?=$data_memo->NoMemo?></td>
							</tr>
						</table>

					</td>
				</tr>
				<tr>
					<td width="10%">&nbsp;Kepada</td>
					<td style="width:2%;text-align: center;">:</td>
					<td>&nbsp;<?=$data_memo->NmKaryawan?></td>
				</tr>
				<tr>
					<td width="10%">&nbsp;Perihal</td>
					<td style="width:2%;text-align: center;">:</td>
					<td>&nbsp;<?=$data_memo->Perihal?></td>
				</tr>
			</table>

		</div>


		<!-- ======= END HEADER =========== -->

		<!-- ======= BODY =========== -->
		<div id="body">
			<table width="100%" style="padding-top:7px;font-size: 10px !important;">
				<tr>
					<td height="9cm" style="font-size: 10px;vertical-align: top;"><?=nl2br($data_memo->Remark)?></td>
				</tr>
			</table>
			

		</div>
		<!-- ======= END BODY =========== -->


		<!-- ======= FOOTER =========== -->
		<div id="footer" >
			<table width="100%" border="0" style="font-size: 10px !important;padding-left:10px;">
				<tr>
					<td style="height:3cm;vertical-align: top;"></td>
					<td style="text-align:right;height:3cm;vertical-align: top;padding-right: 50px;">Hormat Kami,</td>

				</tr>
				<tr>
					<td>Tembusan : </td>
					<td style="text-align:right;padding-right: 50px;font-weight: bold;"><?=dapat_nama_karyawan($data_memo->Pembuat)?> </td>

				</tr>
			</table>
			<table width="100%" border="0" style="font-size: 10px !important;padding-left:10px;height: <?=((empty($Tembusan))) ? '2cm' : '' ?>">
				<?php
				if(!empty($data_memo->Tembusan)){

					$tags = explode(',',$data_memo->Tembusan);
					$nomor = 1;
					$array = array();
					foreach($tags as $key) { 
						$myarray[] = $key;
						?> 
						<tr>
							<td><?=$nomor++.' '.dapat_nama_karyawan($key)?> </td>

						</tr>
						<?php 
						if ($nomor >= 6) {
							break;
						}
					} 
				}
				
				?>
				
			</table>
			<table width="100%">
				<tr>
					<td style="text-align:right;font-size: 10px;">Page 1 of 1</td>
				</tr>
			</table>
		</div>
		<!-- ======= END FOOTER =========== -->
	</div>

</body>
</html>