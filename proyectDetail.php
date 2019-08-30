<?php
include 'head.php';

$outID = $_REQUEST["outID"];

$myQuery = $db->query("SELECT * FROM proyects WHERE ID = '$outID'");
$row = $myQuery->fetch();

$queryNext = "SELECT MIN(ID) nextID FROM proyects WHERE ID > '$outID'";
$resultNext = $db->query($queryNext);
$rowNext = $resultNext->fetch();
$nextID = $rowNext["nextID"];

$queryPrev = "SELECT MAX(ID) prevID FROM proyects WHERE ID < '$outID'";
$resultPrev = $db->query($queryPrev);
$rowPrev = $resultPrev->fetch();
$prevID = $rowPrev["prevID"];
?>

<div class="sectionTitle">
    <a href="proyectDetail.php?outID=<?php echo $prevID; ?>"><i class="fa fa-arrow-left prev" aria-hidden="true"></i></a>
    PROYECTO "<?php echo $row["proyectname"]; ?>"
    <a href="proyectDetail.php?outID=<?php echo $nextID; ?>"><i class="fa fa-arrow-right next" aria-hidden="true"></i></a>
</div>

<div class="format">
<form>
<table width="100%" border="0" cellspacing="20px" cellpadding="0">
  <tbody>
      <tr>
          <td colspan="2">Nombre de Proyecto<br>
            <div style="margin-top:10px"><input type="text" value="<?php echo $row['proyectname']; ?>" disabled class="inputText" required></div>
          </td>
      </tr>
      <tr>
        <td width="50%">Fecha de inicio<br>
            <div style="margin-top:10px"><input type="date" value="<?php echo $row['fchinicio']; ?>" disabled class="inputText" required></div>
        </td>
        <td width="50%">Fecha de termino<br>
            <div style="margin-top:10px"><input type="date" value="<?php echo $row['fchterm']; ?>" disabled class="inputText" required></div>
        </td>
      </tr>
<!------------------COSTOS/GANANCIAS------------------>
    <tr>
        <td width="50%">Costo del proyceto<br>
            <div style="margin-top:10px"><input type="text" value="<?php echo $row['proyectcst']; ?>" disabled class="inputText" required></div>
        </td>
        <td width="50%">Ganancia<br>
            <div style="margin-top:10px"><input value="<?php echo $row['ganancia']; ?>" disabled class="inputText" type="number" min="0" step="any" required></div>
        </td>
    </tr>
<!------------------CLIENTE------------------>
    <tr>
      <td colspan="2">Cliente<br>
            <div style="margin-top:10px"><input type="text" value="<?php echo $row['client']; ?>" disabled class="inputText" ></div>
        </td>
        <tr>
        <td width="50%">Persona de contacto<br>
            <div style="margin-top:10px"><input type="text" value="<?php echo $row['cntct_prsn']; ?>" disabled class="inputText"></div>
        </td>
    
          <td width="50%">Numero de cliente<br>
            <div style="margin-top:10px"><input value="<?php echo $row['client_num']; ?>" disabled class="inputText"></div>
        </td>
            </tr>
      <tr>
          <td width="50%">Correo de contacto<br>
            <div style="margin-top:10px"><input type="email" value="<?php echo $row['correo']; ?>" disabled class="inputText"></div>
        </td>
      </tr>
<!------------------Clientes------------------>
    <tr>
      <td colspan="2">Comentarios<br>
          <textarea value="<?php echo $row["coments"]; ?>" disabled></textarea>
        </td>
    </tr>
    <!--<tr>
      <td colspan="2">Existencias<br>
        <div id="itemContainer">
        	<div class='itemHeader'>
				<table class='itemTable' width='100%' cellpadding='0' cellspacing='10px'>
					<tr>
                    	<td>Almacén</td>
						<td width="130px">Existencia</td>
						<td width="76px">Min</td>
                        <td width="76px">Max</td>
					</tr>
				</table>
			</div>
        	<?php
        	$myQuery = $db->query("SELECT ID, CONCAT(name, ' (', ID,')') store FROM stores ORDER BY name");
			
			while($row = $myQuery->fetch()){			
				echo "
					<div class='item'>
						<table class='itemTable' width='100%' cellpadding='0' cellspacing='10px'>
							<tr>
								<td>".$row["store"]."<input type='hidden' name='storeID[]' value='".$row['ID']."'></td>
								<td width='130px'>0</td>
								<td width='76px'><input type='number' min='0' id='min' name='min[]' class='inputText' style='width:70px !important' required></td>
								<td width='76px'><input type='number' min='0' id='max' name='max[]' class='inputText' style='width:70px !important' required></td>
							</tr>
						</table>
					</div>
				";
			};
			?>
        </div>
	  </td>
    </tr>-->
    <tr>
      <td align="left"><button type="button" class="formButton redB" onClick="cancel();">REGRESAR</button></td>
      <!--<td align="right"><button type="submit" class="formButton blueB" id="saveButton">Guardar</button></td>-->
    </tr>
  </tbody>
</table>
</form>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$("#saveButton").dblclick(function(e){
			e.preventDefault();
		});
		$("#cat").select2();
		$("#vendor").select2();
	});
	
	function cancel() {
		window.location.href = "proyects.php";
	}
</script>    <tr>
      <!--<td align="left"><a  class="formButton blueB" href="salida.php?outID=<?php echo $outID; ?>" target="_blank">Crear PDF</a></td>
      <td align="right"><button type="button" class="formButton blueB" onClick="getback();">Regresar</button></td>
    </tr>
  </tbody>
</table>
</form>
</div>-->
    
<script>
	function getback() {
		window.location.href = 'proyects.php';
	}
</script>
    
<?php include 'footer.php'; ?>