<?php
session_start();
include_once "mysqlconn.php";

$product = $_POST['product'];
$code = $_POST['code'];
$detail = $_POST['detail'];
$cat = $_POST['cat'];
$vendor = $_POST['vendor'];
$reference = $_POST['reference'];
$cost = $_POST['cost'];
$price = $_POST['price'];
$remarks = $_POST['remarks'];


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
   if($nombre_img == !NULL) echo "La imagen es demasiado grande ";
}


// Enter New Product to DB insertamos el archive binario que hemos obtenido y lo guardamos en la base de datos Mysql

$sql = "INSERT INTO product (ID, code, name,reference, detail, vendorID, catID, cost, price, inventory, remarks, image) VALUES (NULL, '$code', '$product','$reference' , '$detail', '$vendor', '$cat', '$cost', '$price', 0, '$remarks', '$nombre_img')";
$retval = $db->query($sql);	
if(!$retval)
{
  die('Could not enter data: ' . mysql_error());
}

// Enter Product Lines
foreach ($_POST['storeID'] as $key => $value) {
	$min = $_POST['min'][$key];
	$max = $_POST['max'][$key];
	$sql = "INSERT INTO prdl (ID, prodCode, storeID, qty, minq, maxq) VALUES (NULL, '$code', '$value', 0, '$min', '$max')";
	$retval = $db->query($sql);
	if(! $retval )
	{
	  die('Could not enter data: ' . mysql_error());
	}
}

header('Location: ../products.php');
?>