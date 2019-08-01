<?php
include_once "includes/mysqlconn.php";

require('fpdf/mc_table_lp.php');

$infID = $_GET["infID"];
$pdf = new PDF_MC_Table();
$pdf->AddPage();
$pdf->AliasNbPages();
	
	
	
// Lineas
$query = "SELECT T1.ID, T1.code, T1.name product, T3.name vendor, T1.price, T2.catName cat FROM PRODUCT T1 JOIN CAT T2 ON T1.catID = T2.ID JOIN VENDOR T3 ON T1.vendorID = T3.ID WHERE T1.active = 'Y' ORDER BY T1.name ASC";
$result = mysql_query($query);
$pdf->SetDrawColor(255,255,255);
while($row = mysql_fetch_array($result)) {
	$pdf->SetWidths(array(30,86,30,35,15));
	$pdf->SetAligns(array('','','','','R'));
	$pdf->SetDrawColor(210,210,210);
	$pdf->Row(array($row["code"],$row["product"],$row["cat"],$row["vendor"],$row["price"]));
}
$pdf->SetDrawColor(0,0,0);


$pdf->Cell(196,5,'',0,1);	

$pdf->SetFont('Arial','B',8);
$pdf->Cell(196,.1,'',0,1,'',true);
$pdf->SetFont('Arial','B',8);
// Page number
$pdf->Cell(30,6,'Comentarios: ',0,1,'L');
$pdf->SetFont('Arial','',8);
$pdf->MultiCell(190, 10, '',0,'L', false);
$pdf->Cell(196,.1,'',0,1,'',true);

$pdf->Output();
?>