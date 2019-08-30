<?php
session_start();

$timeout = 7200; // 2 hours
 if(isset($_SESSION['timeout'])) {
    $duration = time() - (int)$_SESSION['timeout'];
    if($duration > $timeout) {
        session_destroy();
        session_start();
    }
}
$_SESSION['timeout'] = time();

if($_SESSION["authenticated_user"] != true) {
	header("Location: index.php");
	die();
}

include_once "includes/mysqlconn.php";

require('fpdf/mc_table_faltante.php');

$selectedVendors = $_POST["vendors"];

$storeIDs = array();
$storeNames = array();

$storesQuery = "SELECT ID, code FROM stores WHERE active = 'Y' ";
if ($_SESSION["role"] != 'Y') {
	$storesQuery.= "AND ID = '".$_SESSION["store"]."' ";
}
$storesQuery.= "ORDER BY ID ASC";

$storesResult = $db->query($storesQuery);
while ($storesRow = $storesResult->fetch()) {
	$storeIDs[] = $storesRow["ID"];
	$storeNames[] = $storesRow["code"];
}
$storesCount = count($storeIDs);

$colWidths = [25,50,25,15];
for($i=0; $i<$storesCount; $i++) {
	$cW = 119 / $storesCount;
	array_push($colWidths, $cW);
};

array_push($colWidths, 10);
array_push($colWidths, 15);

//$tranID = $_GET["tranID"];
$pdf = new PDF_MC_Table();
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',7,'C');


//Lineas inventario
$pdf->SetWidths($colWidths);

//IF ((SELECT SUM(maxq - qty) FROM PRDL T4 WHERE T4.prodCode = T1.code) > 0, (SELECT SUM(maxq - qty) FROM PRDL T4 WHERE T4.prodCode = T1.code), 0) total
$total = 0;

// (SELECT SUM(maxq - qty) FROM PRDL T4 WHERE T4.prodCode = T1.code) > 0


$query = "SELECT T1.code, T1.name product, T2.name, T1.price FROM product T1 JOIN vendor T2 ON T1.vendorID = T2.ID WHERE T1.active = 'Y' AND (";

foreach ($selectedVendors as $v => $selectedVendor) {
	$query.= " T2.ID = ".$selectedVendor." OR ";
}

$query.= "T2.ID = ".$selectedVendors[0].") ORDER BY T2.name, T1.name ASC";
$grandTotal = 0;
$result = $db->query($query);
while($row = $result->fetch()){
	$total = 0;
	$price = $row["price"];
	foreach ($storeIDs as $i => $storeID) {
		$byStore = "SELECT (maxq - qty) qty FROM prdl WHERE prodCode = '".$row["code"]."' AND storeID = '".$storeID."' ORDER BY storeID ASC";
		$resByStore = $db->query($byStore);
		while($rowByStore = $resByStore->fetch()) {
			if ($rowByStore["qty"] < 0) {
				array_push($row, "0");
			} else {
				array_push($row, $rowByStore["qty"]);
				$total += $rowByStore["qty"];
			}
		};
	}

	array_push($row, $total);
	$import = $total * $price;
	$grandTotal += $import;
	array_push($row, number_format($import, 2));
	if ($row[4] == 0 && $row[5] == 0 && $row[6] == 0 && $row[7] == 0 && $row[8] == 0 && $row[9] == 0 && $row[10] == 0 && $row[11] == 0 && $row[12] == 0 && $row[13] == 0 && $row[14] == 0 && $row[15] == 0) {
		
	} else {
		$pdf->Row($row);
	}
}

$pdf->SetDrawColor(0,0,0);

$pdf->Cell(259,5,'',0,1,'');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(219,5,'',0,0);
$pdf->Cell(40,5,'Total',1,1,'C');
$pdf->SetFont('Arial','B',14);
$pdf->Cell(219,10,'',0,0);
$pdf->Cell(40,10,'$ '.number_format($grandTotal, 2,'.',','),1,1,'R');

$pdf->Ln(5);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(259,.1,'',0,1,'',true);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(30,6,'Comentarios: ',0,1,'L');
$pdf->SetFont('Arial','',8);
$pdf->MultiCell(259, 10,"hola" /*$remarks*/,0,'L', false);
$pdf->Cell(259,.1,'',0,1,'',true);
$pdf->SetFont('Arial','B',8);
$pdf->Ln(8);
$pdf->Cell(51.5, 5,'',0,0, 'L');
$pdf->Cell(58, 5,utf8_decode('Realiz贸:'),0,0, 'L');
$pdf->Cell(40, 5,'',0,0, 'L');
$pdf->Cell(58, 5,'Valida:',0,0, 'L');
$pdf->Cell(51.5, 5,'',0,1,'L');
$pdf->Cell(51.5, 20,'',0,0,'L');
$pdf->Cell(58, 20,'','B',0, 'L');
$pdf->Cell(40, 20,'',0,0, 'L');
$pdf->Cell(58, 20,'','B',0, 'L');
$pdf->Cell(51.5, 20,'',0,1, 'L');
$pdf->Cell(51.5, 5,'',0,0, 'L');
$pdf->Cell(58, 5,"name form"/*$nameFrom*/,0,0, 'C');
$pdf->Cell(40, 5,'',0,0, 'L');
$pdf->Cell(58, 5,"nameTo"/*$nameTo*/,0,0, 'C');
$pdf->Cell(51.5, 5,'',0,1,'L');
$pdf->SetFont('Arial','',8);

$pdf->Output();








?>