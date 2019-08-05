<?php include 'head.php'; ?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/js/select2.min.js"></script>

<div class="sectionTitle">REGISTRAR PRODUCTO</div>

<div class="format">
<form method="post" action="includes/createNewProduct.php">
<table width="100%" border="0" cellspacing="20px" cellpadding="0">
  <tbody>
    <tr>
      <td width="50%">Nombre<br>
      	<div style="margin-top:10px"><input type="text" id="product" name="product" class="inputText" required></div>
      </td>
      <td width="50%">Código<br>
        <div style="margin-top:10px"><input type="text" id="code" name="code" class="inputText"></div>
        </td>
    </tr>
    <tr>
      <td colspan="2">Descripción<br>
      	<div style="margin-top:10px"><input type="text" id="detail" name="detail" class="inputText"></div>
      </td>
    </tr>
    <tr>
      <td width="50%">Categoría<br>
      	<div style="margin-top:10px"><select id="cat" name="cat" style="margin-top:10px;" required>
        	<option value="" disabled selected>Selecciona...</option>
        	<?php
			$myQuery = $db->query("SELECT ID, CONCAT(catName, ' (', ID, ')') catName FROM CAT ORDER BY catName");
			while($row = $myQuery->fetch()){			
				echo "<option value='".$row["ID"]."'>".$row["catName"]."</option>";
			};
			?>
        </select></div>
      </td>
      <td width="50%">Proveedor<br>
        <div style="margin-top:10px"><select id="vendor" name="vendor" required>
        	<option value="" disabled selected>Selecciona...</option>
        	<?php
			$myQuery = $db->query("SELECT ID, CONCAT(name, ' (', ID, ')') name FROM VENDOR ORDER BY name");
			while($row = $myQuery->fetch()){			
				echo "<option value='".$row["ID"]."'>".$row["name"]."</option>";
			};
			?>
        </select></div>
        </td>
    </tr>
    <tr>
      <td width="50%">Costo ($)<br>
      	<div style="margin-top:10px"><input type="number" id="cost" name="cost" class="inputText" min="0" step="any" required></div>
      </td>
      <td width="50%">Precio de venta ($)<br>
        <div style="margin-top:10px"><input type="number" id="price" name="price" class="inputText" min="0" step="any" required></div>
        </td>
    </tr>
    <tr>
      <td colspan="2">Comentarios<br><textarea id="remarks" name="remarks" maxlength="256"></textarea></td>
    </tr>
    <tr>
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
        	$myQuery = $db->query("SELECT ID, CONCAT(name, ' (', ID,')') store FROM STORES ORDER BY name");
			
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
    </tr>
    <tr>
      <td align="left"><button type="button" class="formButton redB" onClick="cancel();">Cancelar</button></td>
      <td align="right"><button type="submit" class="formButton blueB" id="saveButton">Guardar</button></td>
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
		window.location.href = "products2.php";
	}
</script>
    
<?php include 'footer.php'; ?>