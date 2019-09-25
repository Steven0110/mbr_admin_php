<?php
include_once "includes/mysqlconn.php";

require('fpdf/mc_table_prestamo.php');

$infID = $_GET["infID"];
$pdf = new PDF_MC_Table();
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Arial','B',8,'C');
//Table with 20 rows and 4 columns
$pdf->SetWidths(array(16,25,60,25,25,25));
$pdf->Row(array("Cantidad",utf8_decode("Código"),utf8_decode("Producto"),"Fecha Inicio","Fecha termino","Notas"));
$pdf->SetFont('Arial','',8, 'C');



//Datos entrega
$query1 = "SELECT t1.ID_PRESTAMO prestamo, t4.proyectname proyect, t2.NOMBRE emp, t1.ID_HERRAMIENTA, t3.name store, t1.ID_STORE storeID, t1.CREATED_AT created,t1.END_DATE fin, t1.REMARKS remarks, t1.STATUS FROM prestamos t1 INNER JOIN empleados t2 ON t1.ID_EMPLEADO = t2.ID_EMPLEADO INNER JOIN stores t3 ON t1.ID_STORE = t3.ID INNER JOIN proyects t4 ON t1.ID_PROYECTO = t4.ID WHERE t1.ID_PRESTAMO = '$infID'";
$result1 = $db->query($query1);
$row1 = $result1->fetch();

$dsStoreID = $row1["storeID"];
$remarks= $row1["remarks"];
	
// Lineas
$queryTran = "SELECT t1.qty qty,t2.code prodCode,t1.CREATED_AT created,t1.END_DATE fin, t2.name name, t1.REMARKS remarks FROM prestamos t1 INNER JOIN product t2 on t1.ID_HERRAMIENTA = t2.code WHERE t1.ID_PRESTAMO = '$infID'";
$resultTran = $db->query($queryTran);
$pdf->SetDrawColor(255,255,255);
while($rowTran = $resultTran->fetch()) {
	$pdf->SetWidths(array(16,25,60,25,25,25));
	$pdf->Row(array($rowTran["qty"],$rowTran["prodCode"],$rowTran["name"],$rowTran["created"],$rowTran["fin"],$rowTran["remarks"]));
}
$pdf->SetDrawColor(0,0,0);


////////////////////////////////////////////////////////////////////

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