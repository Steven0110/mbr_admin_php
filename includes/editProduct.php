<?php

session_start();
include "mysqlconn.php";

$prodID = $_POST['prodID'];
$name = $_POST['name'];
$code = $_POST['code'];
$detail = $_POST['detail'];
$cat = $_POST['cat'];
$vendor = $_POST['vendor'];
$cost = $_POST['cost'];
$price = $_POST['price'];
$remarks = $_POST['remarks'];
if(isset($_POST["active"])) {
	$active = 'Y';
} else {
	$active = 'N';
}

// Recibo los datos de la imagen
$nombre_img = $_FILES['imagen']['name'];
$tipo = $_FILES['imagen']['type'];
$tamano = $_FILES['imagen']['size'];
 
//Si existe imagen y tiene un tamaño correcto
if (($nombre_img == !NULL) && ($_FILES['imagen']['size'] <= 200000))
{
   //indicamos los formatos que permitimos subir a nuestro servidor
   if (($_FILES["imagen"]["type"] == "image/gif")
   || ($_FILES["imagen"]["type"] == "image/jpeg")
   || ($_FILES["imagen"]["type"] == "image/jpg")
   || ($_FILES["imagen"]["type"] == "image/png"))
   {
      // Ruta donde se guardarán las imágenes que subamos
      $directorio = $_SERVER['DOCUMENT_ROOT'].'/mbr_admin_php/images/toolimages/uploads/';
      // Muevo la imagen desde el directorio temporal a nuestra ruta indicada anteriormente
      move_uploaded_file($_FILES['imagen']['tmp_name'],$directorio.$nombre_img);
    }
    else
    {
       //si no cumple con el formato
       echo "No se puede subir una imagen con ese formato ";
    }
}
else
{
   //si existe la variable pero se pasa del tamaño permitido
   if($nombre_img == !NULL)
   {
   	echo "La imagen es demasiado grande ";/*
   	echo '<script language="javascript">;
			alert("La imagen es demasiado grande ");
	 	  </script>';*/
   }
}

// Write to DB

$sql = "UPDATE product SET name = '$name', code = '$code', detail = '$detail', catID = $cat, vendorID = $vendor, cost = $cost, price = $price, active = '$active', remarks = '$remarks', image = '$nombre_img' WHERE ID = $prodID";

$retval = $db->query($sql);
if(! $retval )
{
  die('Could not modify data: ' . mysql_error());
}

// Edit Product Lines
foreach ($_POST['storeID'] as $key => $value) {
	$min = $_POST['min'][$key];
	$max = $_POST['max'][$key];
	$sql = "UPDATE prdl SET minq = '$min', maxq = '$max' WHERE storeID = $value AND prodCode = '$code'";
	
	$retval = $db->query($sql);
	if(! $retval )
	{
	  die('Could not enter data: ' . mysql_error());
	}
}


header("Location: ../prodDetail.php?prodID=".$prodID);
?>