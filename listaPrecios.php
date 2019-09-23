<?php
include_once "includes/mysqlconn.php";
require('fpdf/mc_table_lp.php');

$query = $_SESSION['priceListQR'];
$pdf = new PDF_MC_Table();
$pdf->AddPage();
$pdf->AliasNbPages();
	
/*$list = $_POST['list'];

switch ($list) {
	case "0":
		$query = "SELECT t1.ID id, t1.REFERENCE ref, t1.CODE code, t1.NAME product, t1.PRICESELL price,t1.MARCA vendor FROM products t1 ORDER BY t1.ID ASC";
		break;
	case "1":
		$query = "SELECT t1.ID id, t1.REFERENCE ref, t1.CODE code, t1.NAME product, t1.PRICESELL2 price,t1.MARCA vendor FROM products t1 ORDER BY t1.ID ASC";
		break;
	case "2":
		$query = "SELECT t1.ID id, t1.REFERENCE ref, t1.CODE code, t1.NAME product, t1.PRICESELL3 price,t1.MARCA vendor FROM products t1 ORDER BY t1.ID ASC";
		break;
	}*/

$result = $db->query($query);
$pdf->SetDrawColor(255,255,255);
while($row = $result->fetch()) {
	$pdf->SetWidths(array(30,86,30,35,15));
	$pdf->SetAligns(array('','','','R','R'));
	$pdf->SetDrawColor(210,210,210);
	if(isset($_POST['pcompra'])&&isset($_POST['pventa']))
	{
		$pdf->Row(array($row["ref"],utf8_decode($row["product"]),$row["code"],"$".$row["pricebuy"],"$".$row["price"]));	
	}
	else if(isset($_POST['pcompra']))
	{
		$pdf->Row(array($row["ref"],utf8_decode($row["product"]),$row["code"],"$".$row["pricebuy"]));	
	}
	else if(isset($_POST['pventa']))
	{
		$pdf->Row(array($row["ref"],utf8_decode($row["product"]),$row["code"],"$".$row["price"]));	
	}
	else{
		$pdf->Row(array($row["ref"],utf8_decode($row["product"]),$row["code"]));		
	}
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