<?php
include_once "includes/mysqlconn.php";

require('fpdf/mc_table_venta.php');

$salID = $_REQUEST["salID"];
$pdf = new PDF_MC_Table();
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',8, 'C');



//Datos entrega
$query1 = "SELECT T1.ID, T2.ID storeID, T2.name store, CONCAT(T3.first, ' ', T3.last) emp, DATE_FORMAT(T1.created_at, '%d-%m-%Y %T') created_at, T1.remarks FROM sales T1 INNER JOIN stores T2 ON T1.storeID = T2.ID INNER JOIN crew T3 ON T1.empID = T3.ID WHERE T1.ID = '$salID'";
$result1 = $db->query($query1);
$row1 = $result1->fetch();

$storeID = $row1["storeID"];
$remarks= $row1["remarks"];
	
	
	
// Lineas
$queryTran = "SELECT T2.ID, T1.prodCode, T2.name, T1.qty, T1.price, (T1.price * T1.qty) import FROM salln T1 INNER JOIN product T2 ON T1.prodCode = T2.code WHERE T1.salID = '$salID'";
$resultTran = $db->query($queryTran);
$pdf->SetDrawColor(255,255,255);
$total = 0;
while($rowTran = $resultTran->fetch()) {
	$pdf->SetWidths(array(16,40,90,25,25));
	$pdf->SetAligns(array('','','','R','R'));
	$pdf->Row(array($rowTran["qty"],$rowTran["prodCode"],$rowTran["name"],"$ ".$rowTran["price"],"$ ".$rowTran["import"]));
	$total += $rowTran["import"];
}
$pdf->SetDrawColor(0,0,0);


////////////////////////////////////////////////////////////////////

$pdf->Cell(196,5,'',0,1,'');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(156,5,'',0,0);
$pdf->Cell(40,5,'Total',1,1,'C');
$pdf->SetFont('Arial','B',14);
$pdf->Cell(156,10,'',0,0);
$pdf->Cell(40,10,'$ '.number_format($total, 2,'.',','),1,1,'R');


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
$pdf->Cell(58, 5,'Elabora:',0,0, 'L');
$pdf->Cell(40, 5,'',0,0, 'L');
$pdf->Cell(58, 5,'Autoriza:',0,0, 'L');
$pdf->Cell(20, 5,'',0,1,'L');
$pdf->Cell(20, 20,'',0,0,'L');
$pdf->Cell(58, 20,'','B',0, 'L');
$pdf->Cell(40, 20,'',0,0, 'L');
$pdf->Cell(58, 20,'','B',0, 'L');
$pdf->Cell(20, 20,'',0,1, 'L');
$pdf->Cell(20, 5,'',0,0, 'L');
$pdf->Cell(58, 5,'',0,0, 'C');
$pdf->Cell(40, 5,'',0,0, 'L');
$pdf->Cell(58, 5,$_SESSION['nameTo'],0,0, 'C');
$pdf->Cell(20, 5,'',0,1,'L');
$pdf->Cell(20, 5,'',0,0, 'L');
$pdf->Cell(58, 5,'Fecha y hora:',0,0, 'L');
$pdf->Cell(40, 5,'',0,0, 'L');
$pdf->Cell(58, 5,'Fecha y hora:',0,0, 'L');
$pdf->Cell(20, 5,'',0,1,'L');
$pdf->SetFont('Arial','',8);

$pdf->Output();
?>