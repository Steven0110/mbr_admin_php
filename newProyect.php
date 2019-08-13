<?php include 'head.php'; ?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/js/select2.min.js"></script>

<div class="sectionTitle">NUEVO PROYECTO</div>

<div class="format">
<form method="post" action="includes/createNewProyect.php">
<table width="100%" border="0" cellspacing="20px" cellpadding="0">
  <tbody>
      <tr>
          <td colspan="2">Nombre de Proyecto<br>
            <div style="margin-top:10px"><input type="text" id="proyectname" name="proyectname" class="inputText" required></div>
          </td>
      </tr>
      <tr>
        <td width="50%">Fecha de inicio<br>
            <div style="margin-top:10px"><input type="date" id="fchinicio" name="fchinicio" class="inputText" required></div>
        </td>
        <td width="50%">Fecha de termino<br>
            <div style="margin-top:10px"><input type="date" id="fchterm" name="fchterm" class="inputText" required></div>
        </td>
      </tr>
<!------------------COSTOS/GANANCIAS------------------>
    <tr>
        <td width="50%">Costo del proyceto<br>
            <div style="margin-top:10px"><input type="text" id="proyectcst" name="proyectcst" class="inputText" required></div>
        </td>
        <td width="50%">Ganancia<br>
            <div style="margin-top:10px"><input id="ganancia" name="ganancia" class="inputText" type="number" min="0" step="any" required></div>
        </td>
    </tr>
<!------------------CLIENTE------------------>
    <tr>
      <td colspan="2">Cliente<br>
            <div style="margin-top:10px"><input type="text" id="client" name="client" class="inputText" required></div>
        </td>
        <tr>
        <td width="50%">Persona de contacto<br>
            <div style="margin-top:10px"><input type="text" id="cntct_prsn" name="cntct_prsn" class="inputText" required></div>
        </td>
    
          <td width="50%">Numero de cliente<br>
            <div style="margin-top:10px"><input id="client_num" name="client_num" class="inputText" type="number" min="0" step="any" required></div>
        </td>
            </tr>
      <tr>
          <td width="50%">Correo de contacto<br>
            <div style="margin-top:10px"><input type="email" id="email" name="email" class="inputText" min="0" step="any" required></div>
        </td>
      </tr>
<!------------------Clientes------------------>
    <tr>
      <td colspan="2">Comentarios<br><textarea id="remarks" name="remarks" maxlength="256"></textarea></td>
    </tr>-->
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
		window.location.href = "proyects.php";
	}
</script>
    
<?php include 'footer.php'; ?>