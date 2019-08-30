<?php
include 'head.php';

$outID = $_REQUEST["outID"];

$myQuery = $db->query("SELECT T1.ID, T1.code, T2.name store, CONCAT(T3.first, ' ', T3.last) emp, DATE_FORMAT(T1.created_at, '%d-%m-%Y %T') created, T1.remarks FROM outflows T1 INNER JOIN stores T2 ON T1.storeID = T2.ID INNER JOIN crew T3 ON T1.empID = T3.ID WHERE T1.ID = '$outID'");
$row = $myQuery->fetch();
$remarks = $row["remarks"];
$code = $row["code"];

$queryNext = "SELECT MIN(ID) nextID FROM outflows WHERE storeID = '".$_SESSION["store"]."' AND ID > '$outID'";
$resultNext = $db->query($queryNext);
$rowNext = $resultNext->fetch();
$nextID = $rowNext["nextID"];

$queryPrev = "SELECT MAX(ID) prevID FROM outflows WHERE storeID = '".$_SESSION["store"]."' AND ID < '$outID'";
$resultPrev = $db->query($queryPrev);
$rowPrev = $resultPrev->fetch();
$prevID = $rowPrev["prevID"];
?>

<div class="sectionTitle">
    <a href="outflowDetail.php?outID=<?php echo $prevID; ?>"><i class="fa fa-arrow-left prev" aria-hidden="true"></i></a>
    SALIDA <?php echo $code; ?>
    <a href="outflowDetail.php?outID=<?php echo $nextID; ?>"><i class="fa fa-arrow-right next" aria-hidden="true"></i></a>
</div>

<div class="format">
<form>
<table width="100%" border="0" cellspacing="20px" cellpadding="0">
  <tbody>
    <tr>
      <td width="50%">Almacén<br>
      	<div style="margin-top:10px"><input type="text" value="<?php echo $row['store']; ?>" disabled class='inputText'></div>
      </td>
      <td width="50%">Empleado<br>
        <div style="margin-top:10px"><input type="text" value="<?php echo $row['emp']; ?>" disabled class='inputText'></div>
        </td>
    </tr>
    <tr>
    	<td>Fecha de creación<br><div style="margin-top:10px"><input type="text" value="<?php echo $row['created']; ?>" disabled class='inputText'></div></td>
        <td></td>
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
        	$myQuery = $db->query("SELECT T2.ID, T2.code, T2.name, T1.qty FROM outln T1 JOIN product T2 ON T1.prodCode = T2.code WHERE T1.outID = '$outID'");
			
			while($row = $myQuery->fetch()){			
				echo "
					<div class='item'>
						<table class='itemTable' width='100%' cellpadding='0' cellspacing='10px'>
							<tr>
								<td width='76px'><input type='text' value='".$row["qty"]."' disabled class='inputText' style='width:70px !important'></td>
								<td width='250px'><input type='text' value='".$row["code"]."' disabled class='inputText'></td>
								<td><input type='text' value='".$row["name"]."' disabled class='inputText'></td>
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
      <td align="left"><a  class="formButton blueB" href="salida.php?outID=<?php echo $outID; ?>" target="_blank">Crear PDF</a></td>
      <td align="right"><button type="button" class="formButton blueB" onClick="getback();">Regresar</button></td>
    </tr>
  </tbody>
</table>
</form>
</div>
<script>
	function getback() {
		window.location.href = 'outflows.php';
	}
</script>

    
<?php include 'footer.php'; ?>