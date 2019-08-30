<?php
include_once "includes/mysqlconn.php";

require('fpdf/mc_table.php');

$tranID = $_GET["tranID"];
$pdf = new PDF_MC_Table();
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Arial','B',8,'C');
//Table with 20 rows and 4 columns
$pdf->SetWidths(array(16,25,80,75));
$pdf->Row(array("Cantidad",utf8_decode("Código"),utf8_decode("Producto"),"Notas"));
$pdf->SetFont('Arial','',8, 'C');



//Datos entrega
$query1 = "SELECT T1.ID, T1.code, T1.orStore orStoreID, T1.dsStore dsStoreID, T2.name orStore, T3.name dsStore, T1.created_at, T1.empID, T1.remarks FROM transfers T1 JOIN stores T2 ON T1.orStore = T2.ID JOIN stores T3 ON T1.dsStore = T3.ID WHERE T1.ID = '$tranID'";
$result1 = $db->query($query1);
$row1 = $result1->fetch();

$orStoreID = $row1["orStoreID"];
$dsStoreID = $row1["dsStoreID"];
$remarks= $row1["remarks"];
	
	
	
// Lineas de cotización
$queryTran = "SELECT T2.ID, T1.prodCode, T2.name, T2.price, T1.qty FROM trln T1 INNER JOIN product T2 ON T1.prodCode = T2.code WHERE T1.tranID = '$tranID'";
$resultTran = $db->query($queryTran);
$pdf->SetDrawColor(255,255,255);
while($rowTran = $resultTran->fetch()) {
	$importe = 0;
	$importe = $rowTran["price"] * $rowTran["qty"];
	$pdf->SetWidths(array(16,25,80,75));
	$pdf->Row(array($rowTran["qty"],$rowTran["prodCode"],$rowTran["name"],""));
}
$pdf->SetDrawColor(0,0,0);


////////////////////////////////////////////////////////////////////

$queryFrom = "SELECT T1.first, T1.last FROM crew T1 JOIN stores T2 ON T1.storeID = T2.ID WHERE T2.ID = '$orStoreID' LIMIT 1";
$resultFrom = $db->query($queryFrom);
$rowFrom = $resultFrom->fetch();
$nameFrom = $rowFrom["first"]." ".$rowFrom["last"];

$queryTo = "SELECT T1.first, T1.last FROM crew T1 JOIN stores T2 ON T1.storeID = T2.ID WHERE T2.ID = '$dsStoreID' LIMIT 1";
$resultTo = $db->query($queryTo);
$rowTo = $resultTo->fetch();
$nameTo = $rowTo["first"]." ".$rowTo["last"];

$queryTotal = "SELECT SUM(T2.price * T1.qty) total FROM trln T1 INNER JOIN product T2 ON T1.prodCode = T2.code WHERE T1.tranID = '$tranID'";
$resultTotal = $db->query($queryTotal);
$rowTotal = $resultTotal->fetch();
$total = $rowTotal["total"];
$subtotal = $total / 1.16;
$iva = $total - $subtotal;

$pdf->Cell(196,5,' ',0,1);	

$pdf->Cell(148);
//////////////////////////////////////////////////////////////////////

$pdf->Ln(2);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(196,.1,'',0,1,'',true);
$pdf->SetFont('Arial','B',8);
// Page number
$pdf->Cell(30,6,'Comentarios: ',0,1,'L');
$pdf->SetFont('Arial','',8);
$pdf->MultiCell(190, 8, $remarks,0,'L', false);
$pdf->Cell(196,.1,'',0,1,'',true);
$pdf->SetFont('Arial','B',8);
$pdf->Ln(8);
$pdf->Cell(20, 5,'',0,0, 'L');
$pdf->Cell(58, 5,'Entrega:',0,0, 'L');
$pdf->Cell(40, 5,'',0,0, 'L');
$pdf->Cell(58, 5,'Recibe:',0,0, 'L');
$pdf->Cell(20, 5,'',0,1,'L');
$pdf->Cell(20, 20,'',0,0,'L');
$pdf->Cell(58, 20,'','B',0, 'L');
$pdf->Cell(40, 20,'',0,0, 'L');
$pdf->Cell(58, 20,'','B',0, 'L');
$pdf->Cell(20, 20,'',0,1, 'L');
$pdf->Cell(20, 5,'',0,0, 'L');
$pdf->Cell(58, 5,$nameFrom,0,0, 'C');
$pdf->Cell(40, 5,'',0,0, 'L');
$pdf->Cell(58, 5,$nameTo,0,0, 'C');
$pdf->Cell(20, 5,'',0,1,'L');
$pdf->Cell(20, 5,'',0,0, 'L');
$pdf->Cell(58, 5,'Fecha y hora:',0,0, 'L');
$pdf->Cell(40, 5,'',0,0, 'L');
$pdf->Cell(58, 5,'Fecha y hora:',0,0, 'L');
$pdf->Cell(20, 5,'',0,1,'L');
$pdf->SetFont('Arial','',8);

$pdf->Output();
?>