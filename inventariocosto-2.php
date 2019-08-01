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

//Lineas inventario

$query = "SELECT T1.code, T1.name product";
if ($_SESSION["role"] == 'Y') {
	$query.= ", T1.price, (SELECT SUM(qty) FROM PRDL T4 WHERE T4.prodCode = T1.code) total";
}
$query.= " FROM PRODUCT T1 INNER JOIN CAT T2 ON T1.catID = T2.ID ORDER BY T1.name ASC";
$result = mysql_query($query);
$registros = mysql_num_rows ($result);

if ($registros > 0) {
   require_once 'PHPExcel/Classes/PHPExcel.php';
   $objPHPExcel = new PHPExcel();
   
   //Informacion del excel
   $objPHPExcel->
    getProperties()
        ->setCreator("intranet")
        ->setLastModifiedBy("intranet USYM")
        ->setTitle("Inventario USYM")
        ->setSubject("Inventario")
        ->setDescription("Documento generado con PHPExcel")
        ->setCategory("Confidencial");    

   $e = 2;   
  while($row = mysql_fetch_object($result)){
   
       
      $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', "Codigo")
            ->setCellValue('B1', "Descripcion")
            ->setCellValue('C1', "Costo")
            ->setCellValue('D1', "Total")
            ->setCellValueExplicit('A'.$e, $row->code,PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('B'.$e, $row->product)
            ->setCellValue('C'.$e, $row->price)
            ->setCellValue('D'.$e, $row->total);
            
      $e++;
      
      
      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
  
	


$storesQuery = "SELECT ID, code FROM STORES ";
if ($_SESSION["role"] != 'Y') {
	$storesQuery.= "WHERE ID = '".$_SESSION["store"]."' ";
}
$storesQuery.= "ORDER BY ID ASC";
$storesResult = mysql_query($storesQuery);
$storeCount = mysql_num_rows ($storesResult);

if ($storeCount > 0){

$s = 'E';   
  while($row = mysql_fetch_object($storesResult)){
   
       $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($s.'1', $row->code);
                        
      
    for($n=0; $n<1; $n++) {
      ++$s;
      
    	  }
    		}
		}
	
	
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
foreach ($storeIDs as $i => $storeID) {
	$byStoreqry = "SELECT qty FROM PRDL WHERE prodCode = '".$row["code"]."' AND storeID = '".$storeID."' ORDER BY storeID ASC";
	$resByStore = mysql_query($byStoreqry);
	
					
	  
while($registro = mysql_fetch_assoc($resByStore)) {
		$cantsuc = array_push($row, $rowByStore["qty"]);
		}
       
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('E2', $cantsuc);
      
    	 }
	}
/*
	
	
      
      $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
		};
*/
    }


header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="inventario.xlsx"');
header('Cache-Control: max-age=0');

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save('php://output');
exit;
mysql_close();


?>