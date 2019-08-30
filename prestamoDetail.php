<?php include 'head.php';

$infID = $_REQUEST["infID"];

$myQuery = $db->query("SELECT t1.ID_PRESTAMO prestamo, t4.proyectname proyect, t2.NOMBRE emp, t1.ID_HERRAMIENTA, t3.name store, t1.ID_STORE storeID, t1.CREATED_AT created, t1.REMARKS remarks, t1.STATUS FROM prestamos t1 INNER JOIN empleados t2 ON t1.ID_EMPLEADO = t2.ID_EMPLEADO INNER JOIN stores t3 ON t1.ID_STORE = t3.ID INNER JOIN proyects t4 ON t1.ID_PROYECTO = t4.ID WHERE t1.ID_PRESTAMO = '$infID'");
$row = $myQuery->fetch();
$remarks = $row["remarks"];
$folio = $row["created"]."  ID= ".$row["prestamo"];
$storeID = $row["storeID"];

$queryNext = "SELECT MIN(ID_PRESTAMO) nextID FROM prestamos WHERE ID_PRESTAMO > '$infID' AND ID_STORE <> 0";
$resultNext = $db->query($queryNext);
$rowNext = $resultNext->fetch();
$nextID = $rowNext["nextID"];

$queryPrev = "SELECT MAX(ID_PRESTAMO) prevID FROM prestamos WHERE ID_PRESTAMO < '$infID' AND ID_STORE <> 0";
$resultPrev = $db->query($queryPrev);
$rowPrev = $resultPrev->fetch();
$prevID = $rowPrev["prevID"];
/*-------------PARA actualizar registros de inflows
$queryprueba = "SELECT MAX(ID) max FROM INFLOWS";
$RESULTADO = $db->query($queryprueba);
$rowprueba = $RESULTADO->fetch();

for ($i = 0; $i<$rowprueba["max"];$i++)
{
  $queryprueba = "UPDATE INFLOWS SET employee = '6c87e7f9-96f7-4cf2-a08d-331c026d406d' WHERE ID = '$i' ";
  $RESULTADO = $db->query($queryprueba);
}
*/
?>

<div class="sectionTitle">
    <a href="prestamoDetail.php?infID=<?php echo $prevID; ?>"><i class="fa fa-arrow-left prev" aria-hidden="true"></i></a>
    ENTRADA <?php echo $folio; ?>
    <a href="prestamoDetail.php?infID=<?php echo $nextID; ?>"><i class="fa fa-arrow-right next" aria-hidden="true"></i></a>
</div>

<div class="format">
<form>
<table width="100%" border="0" cellspacing="20px" cellpadding="0">
  <tbody>
    <tr>
      <td width="50%">Almacén<br>
      	<div style="margin-top:10px"><input type="text" value="<?php echo $row['store']; ?>" disabled class='inputText'></div>
      </td>
    </tr>
    <tr>
      <td width="50%">Proyecto<br>
        <div style="margin-top:10px"><input type="text" value="<?php echo $row['proyect']; ?>" disabled class='inputText'></div>
        </td>
      <td width="50%">Empleado<br>
        <div style="margin-top:10px"><input type="text" value="<?php echo $row['emp']; ?>" disabled class='inputText'></div>
        </td>
    </tr>
    <tr>
    	<td>Fecha de creación<br><div style="margin-top:10px"><input type="text" value="<?php echo $row['created']; ?>" disabled class='inputText'></div></td>
        <td>Empleado a quien se prestó<br>
          <div style="margin-top:10px"><input type="text" value="<?php echo $row['emp']; ?>" disabled class='inputText'></div>
        </td>
    </tr>
    <tr>
      <td colspan="2">Partidas<br>
        <div id="itemContainer">
        	<div class='itemListHead'>
				<table class='itemTable' width='100%' cellpadding='0' cellspacing='10px'>
					<tr>
                    	<td width="76px">Cantidad</td>
                    	<td width="250px">Código</td>
						<td>Producto</td>
					</tr>
				</table>
			</div>
        	<?php
        	$myQuery = $db->query("SELECT t1.qty qty,t2.code prodCode, t2.name name FROM prestamos t1 INNER JOIN product t2 on t1.ID_HERRAMIENTA = t2.code WHERE t1.ID_PRESTAMO = '$infID'");
			
			while($row = $myQuery->fetch()){			
				echo "
					<div class='item'>
						<table class='itemTable' width='100%' cellpadding='0' cellspacing='10px'>
							<tr>
								<td width='76px'><input type='text' value='".$row["qty"]."' disabled class='inputText' style='width:70px !important'></td>
								<td width='250px'><input type='text' value='".$row["prodCode"]."' disabled class='inputText'></td>
								<td><input type='text' value='".$row["name"]."' disabled class='inputText'></td>
                <td width='30px'><i class='fa fa-trash-o remove' aria-hidden='true'></i></td>\
							</tr>
						</table>
					</div>
				";
			};
			?>
        </div>
	  </td>
    </tr>
    <tr>
      <td colspan="2">Comentarios<br><textarea disabled><?php echo $remarks; ?></textarea></td>
    </tr>
    <tr>
    	<td colspan="2">
        	<ul id="buttonBar">
            	<li><button type='button' class='formButton blueB' onClick="getback();"><i class='fa fa-hand-o-left' aria-hidden='true'></i> Regresar</button></li>
              <li><a href="includes/toolRtn.php?infID=<?php echo $infID; ?>&storeID=<?php echo $storeID; ?>" class="formButton blueB"><i class='fa ' aria-hidden='true'></i> Cerrar Prestamo</a></li>
                <li><a class="formButton blueB" href="prestamoPDF.php?infID=<?php echo $infID; ?>" target="_blank"><i class='fa fa-file-pdf-o' aria-hidden='true'></i> PDF</a></li>
    		  </ul>
      </td>
    </tr>
  </tbody>
</table>
</form>
</div>
<script>
function getback() {
	window.history.back();
}

</script>
    
<?php include 'footer.php'; ?>