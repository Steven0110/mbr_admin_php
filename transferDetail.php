<?php include 'head.php';

$tranID = $_REQUEST["tranID"];

$myQuery = $db->query("SELECT T1.ID, T1.code, T2.name orStore, T3.name dsStore, T1.created_at, T1.empID, T1.remarks FROM TRANSFERS T1 JOIN STORES T2 ON T1.orStore = T2.ID JOIN STORES T3 ON T1.dsStore = T3.ID WHERE T1.ID = '$tranID'");
$row = $myQuery->fetch();
$remarks = $row["remarks"];

$queryNext = "SELECT MIN(ID) nextID FROM TRANSFERS WHERE ID > '$tranID'";
$resultNext = $db->query($queryNext);
$rowNext = $resultNext->fetch();
$nextID = $rowNext["nextID"];

$queryPrev = "SELECT MAX(ID) prevID FROM TRANSFERS WHERE ID < '$tranID'";
$resultPrev = $db->query($queryPrev);
$rowPrev = $resultPrev->fetch();
$prevID = $rowPrev["prevID"];
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/js/select2.min.js"></script>

<div class="sectionTitle">
    <a href="transferDetail.php?tranID=<?php echo $prevID; ?>"><i class="fa fa-arrow-left prev" aria-hidden="true"></i></a>
    TRANSFERENCIA <?php echo $row["code"]; ?>
    <a href="transferDetail.php?tranID=<?php echo $nextID; ?>"><i class="fa fa-arrow-right next" aria-hidden="true"></i></a>
</div>

<div class="format">
<form>
<table width="100%" border="0" cellspacing="20px" cellpadding="0">
  <tbody>
    <tr>
      <td width="50%">Almacén origen<br>
      	<div style="margin-top:10px">
        	<input type="text" value="<?php echo $row['orStore']; ?>" name="orStore" readonly class='inputText'></div>
      </td>
      <td width="50%">Almacén destino<br>
      	<div style="margin-top:10px">
        	<input type="text" value="<?php echo $row['dsStore']; ?>" name="dsStore" readonly class='inputText'></div>
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
        	$myQuery = $db->query("SELECT T2.ID, T1.prodCode, T2.name, T1.qty FROM TRLN T1 INNER JOIN PRODUCT T2 ON T1.prodCode = T2.code WHERE T1.tranID = '$tranID'");
			
			while($row = $myQuery->fetch()){			
				echo "
					<div class='item'>
						<table class='itemTable' width='100%' cellpadding='0' cellspacing='10px'>
							<tr>
								<td width='76px'><input type='text' value='".$row["qty"]."' disabled class='inputText' style='width:70px !important'></td>
								<td width='250px'><input type='text' value='".$row["prodCode"]."' disabled class='inputText'></td>
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
      <td colspan="2">Comentarios<br><textarea id="remarks" name="remarks" maxlength="256" readonly><?php echo $remarks; ?></textarea></td>
    </tr>
    <tr>
    	<td colspan="2">
        	<ul id="buttonBar">
            	<li><button type='button' class='formButton blueB' onClick="getback();"><i class='fa fa-hand-o-left' aria-hidden='true'></i> Regresar</button></li>
                <li><a class="formButton blueB" href="entrega.php?tranID=<?php echo $tranID; ?>" target="_blank"><i class='fa fa-file-pdf-o' aria-hidden='true'></i> PDF</a></li>
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