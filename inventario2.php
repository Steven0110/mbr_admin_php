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

$query = "SELECT code, name FROM PRODUCT ORDER BY name ASC";
$resultado = mysql_query($query, $dbhandle);
$registros = mysql_num_rows ($resultado);
 
 if ($registros > 0) {
   require_once 'PHPExcel/Classes/PHPExcel.php';
   $objPHPExcel = new PHPExcel();
   
   //Informacion del excel
   $objPHPExcel->
    getProperties()
        ->setCreator("ingenieroweb.com.co")
        ->setLastModifiedBy("ingenieroweb.com.co")
        ->setTitle("Exportar excel desde mysql")
        ->setSubject("Inventario")
        ->setDescription("Documento generado con PHPExcel")
        ->setCategory("code");    

   $i = 2;    
   while ($registro = mysql_fetch_object ($resultado)) {
       
      $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', "Codigo")
            ->setCellValue('B1', "Descripcion")
            ->setCellValueExplicit('A'.$i, $registro->code,PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('B'.$i, $registro->name);
            
      $i++;
      
      
      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
   }
}
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="inventario.xlsx"');
header('Cache-Control: max-age=0');

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save('php://output');
exit;
mysql_close();
?>