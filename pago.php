<?php
include_once "includes/mysqlconn.php";

require('fpdf/mc_pago.php');

$pmnID = $_REQUEST["pmnID"];

$queryPmnt = "SELECT T1.ID, T1.code, T1.created_at, T1.storeID, T3.name storeName, CONCAT(T2.first, ' ', T2.last) emp, T1.active, T1.fromDate, T1.toDate FROM PAYMENT T1 JOIN CREW T2 ON T1.empID = T2.ID JOIN STORES T3 ON T1.storeID = T3.ID WHERE T1.ID = $pmnID";
$resultPmnt = mysql_query($queryPmnt);
$rowPmnt = mysql_fetch_assoc($resultPmnt);

$code = $rowPmnt["code"];
$store = $rowPmnt["storeID"];
$storeName = $rowPmnt["storeName"];
$emp = $rowPmnt["emp"];
$fromDate = $rowPmnt["fromDate"];
$toDate = $rowPmnt["toDate"];
$fromDateQ = date('Y-m-d 00:00:00', strtotime(str_replace('/', '-', $_POST["fromDate"])));
$toDateQ = date('Y-m-d 23:59:59', strtotime(str_replace('/', '-', $_POST["toDate"])));

$pdf = new PDF_MC_Table();
$pdf->AddPage();
$pdf->AliasNbPages();

$pdf->SetFont('Arial','B',8,'C');
$pdf->Cell(60,6,'Entregas',0,1,'L');
//Table with 20 rows and 4 columns
$pdf->SetWidths(array(30,50,91,25));
$pdf->Row(array("Folio","Fecha",utf8_decode("Realizó"),"Monto"));
$pdf->SetFont('Arial','',8, 'C');
	
	
// Entregas
$queryTransfers = "SELECT T1.ID, T1.code, CONCAT(T2.first, ' ', T2.last) emp, T1.created_at, (SELECT SUM((T3.qty * T4.price)) FROM TRLN T3 JOIN PRODUCT T4 ON T3.prodCode = T4.code WHERE T3.tranID = T1.ID) value FROM TRANSFERS T1 JOIN CREW T2 ON T1.empID = T2.ID WHERE T1.orStore = 100 AND T1.dsStore = $store AND T1.account = 'Y' AND pmntCode = '$code' ORDER BY T1.created_at ASC";
$resultTransfers = mysql_query($queryTransfers);
$pdf->SetDrawColor(255,255,255);
$tranTotal = 0;
while ($rowTransfers = mysql_fetch_assoc($resultTransfers)) {
	$pdf->SetWidths(array(30,50,91,25));
	$pdf->SetAligns(array('','','','R'));
	$pdf->Row(array($rowTransfers["code"],$rowTransfers["created_at"],utf8_decode($rowTransfers["emp"]),"$ ".$rowTransfers["value"]));
	$tranTotal += $rowTransfers["value"];
}
$pdf->SetFont('Arial','B',8,'C');
$pdf->Cell(171,6,'Total de entregas',0,0,'R');
$pdf->SetFont('Arial','',8,'C');
$pdf->Cell(25,6,'$ '.number_format($tranTotal, 2,'.',','),0,1,'R');
$pdf->Cell(196,5,'',0,1,'');

$pdf->SetFont('Arial','B',8,'C');
$pdf->Cell(60,6,'Devoluciones',0,1,'L');

//Table with 20 rows and 4 columns
$pdf->SetDrawColor(0,0,0);
$pdf->SetWidths(array(30,50,91,25));
$pdf->Row(array("Folio","Fecha",utf8_decode("Realizó"),"Monto"));
$pdf->SetFont('Arial','',8, 'C');
	
	
// Devoluciones
$queryDevs = "SELECT T1.ID, T1.code, CONCAT(T2.first, ' ', T2.last) emp, T1.created_at, (SELECT SUM((T3.qty * T4.price)) FROM TRLN T3 JOIN PRODUCT T4 ON T3.prodCode = T4.code WHERE T3.tranID = T1.ID) value FROM TRANSFERS T1 JOIN CREW T2 ON T1.empID = T2.ID WHERE T1.orStore = $store AND T1.dsStore = 100 AND T1.account = 'Y' AND pmntCode = '$code' ORDER BY T1.created_at ASC";
$resultDevs = mysql_query($queryDevs);
$pdf->SetDrawColor(255,255,255);
$devTotal = 0;
while ($rowDevs = mysql_fetch_assoc($resultDevs)) {
	$pdf->SetWidths(array(30,50,91,25));
	$pdf->SetAligns(array('','','','R'));
	$pdf->Row(array($rowDevs["code"],$rowDevs["created_at"],utf8_decode($rowDevs["emp"]),"$ ".$rowDevs["value"]));
	$devTotal += $rowDevs["value"];
}
$pdf->SetFont('Arial','B',8,'C');
$pdf->Cell(171,6,'Total de devoluciones',0,0,'R');
$pdf->SetFont('Arial','',8,'C');
$pdf->Cell(25,6,'$ '.number_format($devTotal, 2,'.',','),0,1,'R');

$pdf->SetDrawColor(0,0,0);


////////////////////////////////////////////////////////////////////

$total = $tranTotal - $devTotal;
$pdf->Cell(196,5,'',0,1,'');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(156,5,'',0,0);
$pdf->Cell(40,5,'Pago Total',1,1,'C');
$pdf->SetFont('Arial','B',14);
$pdf->Cell(156,10,'',0,0);
$pdf->Cell(40,10,'$ '.number_format($total, 2,'.',','),1,1,'R');


$pdf->Cell(148);
//////////////////////////////////////////////////////////////////////

$pdf->Ln(2);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(196,.1,'',0,1,'',true);
$pdf->SetFont('Arial','B',8);

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