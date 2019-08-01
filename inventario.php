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

require('fpdf/mc_table_inventario.php');

$storeIDs = array();
$storeNames = array();

$storesQuery = "SELECT ID, code FROM STORES ";
if ($_SESSION["role"] != 'Y') {
	$storesQuery.= "WHERE ID = '".$_SESSION["store"]."' ";
}
$storesQuery.= "ORDER BY ID ASC";
$storesResult = mysql_query($storesQuery);
while ($storesRow = mysql_fetch_assoc($storesResult)) {
	$storeIDs[] = $storesRow["ID"];
	$storeNames[] = $storesRow["code"];
}
$storesCount = count($storeIDs);

$colWidths = [25,70,10];
for($i=0; $i<$storesCount; $i++) {
	$cW = 119 / $storesCount;
	array_push($colWidths, $cW);
};
array_push($colWidths, 35);

$tranID = $_GET["tranID"];
$pdf = new PDF_MC_Table();
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',7,'C');


//Lineas inventario
$pdf->SetWidths($colWidths);

$query = "SELECT T1.code, T1.name product";
if ($_SESSION["role"] == 'Y') {
	$query.= ", (SELECT SUM(qty) FROM PRDL T4 WHERE T4.prodCode = T1.code) total";
}
$query.= " FROM PRODUCT T1 INNER JOIN CAT T2 ON T1.catID = T2.ID WHERE T1.active != 'N' ORDER BY T1.name ASC";
$result = mysql_query($query);
while($row = mysql_fetch_array($result)){
	foreach ($storeIDs as $i => $storeID) {
		$byStore = "SELECT qty FROM PRDL WHERE prodCode = '".$row["code"]."' AND storeID = '".$storeID."' ORDER BY storeID ASC";
		$resByStore = mysql_query($byStore);
		while($rowByStore = mysql_fetch_assoc($resByStore)) {
			array_push($row, $rowByStore["qty"]);
		};
	}
	$pdf->Row($row);
}

$pdf->SetDrawColor(0,0,0);

$pdf->Ln(2);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(259,.1,'',0,1,'',true);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(30,6,'Comentarios: ',0,1,'L');
$pdf->SetFont('Arial','',8);
$pdf->MultiCell(259, 10, $remarks,0,'L', false);
$pdf->Cell(259,.1,'',0,1,'',true);
$pdf->SetFont('Arial','B',8);
$pdf->Ln(8);
$pdf->Cell(51.5, 5,'',0,0, 'L');
$pdf->Cell(58, 5,utf8_decode('Realizó:'),0,0, 'L');
$pdf->Cell(40, 5,'',0,0, 'L');
$pdf->Cell(58, 5,'Valida:',0,0, 'L');
$pdf->Cell(51.5, 5,'',0,1,'L');
$pdf->Cell(51.5, 20,'',0,0,'L');
$pdf->Cell(58, 20,'','B',0, 'L');
$pdf->Cell(40, 20,'',0,0, 'L');
$pdf->Cell(58, 20,'','B',0, 'L');
$pdf->Cell(51.5, 20,'',0,1, 'L');
$pdf->Cell(51.5, 5,'',0,0, 'L');
$pdf->Cell(58, 5,$nameFrom,0,0, 'C');
$pdf->Cell(40, 5,'',0,0, 'L');
$pdf->Cell(58, 5,$nameTo,0,0, 'C');
$pdf->Cell(51.5, 5,'',0,1,'L');
$pdf->SetFont('Arial','',8);

$pdf->Output();
?>