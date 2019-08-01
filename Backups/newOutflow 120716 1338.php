<?php include 'head.php'; ?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/js/select2.min.js"></script>

<div class="sectionTitle">REGISTRAR SALIDA</div>

<div class="format">
<form method="post" action="includes/createNewOuflow.php">
<table width="100%" border="0" cellspacing="20px" cellpadding="0">
  <tbody>
    <tr>
      <td width="50%">Almacén<br>
      	<div style="margin-top:10px"><select id="store" name="store" style="margin-top:10px;">
        	<?php
			$myQuery = mysql_query("SELECT ID, CONCAT(name, ' (', ID, ')') name FROM STORES");
			while($row = mysql_fetch_array($myQuery)){			
				echo "<option value='".$row["ID"]."'>".$row["name"]."</option>";
			};
			?>
        </select></div>
      </td>
      <td width="50%">Empleado<br>
        <div style="margin-top:10px"><select id="employee" name="employee">
        	<?php
			$myQuery = mysql_query("SELECT ID, CONCAT(first, ' ', last, ' (', ID, ')') name FROM CREW ORDER BY name");
			while($row = mysql_fetch_array($myQuery)){			
				echo "<option value='".$row["ID"]."'>".$row["name"]."</option>";
			};
			?>
        </select></div>
        </td>
    </tr>
    <tr>
      <td colspan="2">Partidas<br>
        <div id="itemContainer">
        	<table class="itemListHead" cellpadding="0" cellspacing="10px" width="100%">
            	<thead>
                	<tr>
                    	<td width="250px">Código</td>
                        <td>Producto</td>
                        <td width="76px">Cantidad</td>
                        <td width="30px"></td>
                    </tr>
                </thead>
            </table>
            <div class='item'>
            	<table class='itemTable' width='100%' cellpadding='0' cellspacing='10px'>
                	<tr>	
                    	<td width="250px"><input type="text" id="code" name="code" class="inputText"></td>
                        <td>
                        	<select id='product' name='product[]' class='itemProduct'>
                            	<option value='' disabled>Selecciona...</option>
								<?php
                                $myQuery = mysql_query("SELECT ID, name FROM PRODUCT ORDER BY name");
                                while($row = mysql_fetch_array($myQuery)){			
                                    echo "<option value='".$row["ID"]."'>".$row["name"]."</option>";
                                };
                                ?>
                            </select>
                        </td>
                        <td width='76px'><input type='number' id='quant' name='quant[]' required class='inputText' style='width:70px !important'></td>
                        <td width='30px'></td>
                    </tr>
                </table>
            </div>
        </div>
        <div onClick="addItem();" class="formButton greenB">Agregar partida</div>
	  </td>
    </tr>
    <tr>
      <td colspan="2">Comentarios<br><textarea id="remarks" name="remarks" maxlength="256"></textarea></td>
    </tr>
    <tr>
      <td align="left"><button type="button" class="formButton redB" onClick="cancel();">Cancelar</button></td>
      <td align="right"><button type="submit" class="formButton blueB">Guardar</button></td>
    </tr>
  </tbody>
</table>
</form>
</div>

<script type="text/javascript">
	function addItem() {
		$("#itemContainer").append("<div class='item'>\
            	<table class='itemTable' width='100%' cellpadding='0' cellspacing='10px'>\
                	<tr>\
						<td width='250px'><input type='text' class='inputText'></td>\
                        <td>\
                        	<select id='product' name='product[]' class='itemProduct'>\
                            	<option value='' disabled>Selecciona...</option>\
								<?php
                                $myQuery = mysql_query("SELECT ID, name FROM PRODUCT ORDER BY name");
                                while($row = mysql_fetch_array($myQuery)){			
                                    echo "<option value='".$row["ID"]."'>".$row["name"]."</option>";
                                };
                                ?>
                            </select>\
                        </td>\
                        <td width='76px'><input type='number' id='quant' name='quant[]' required class='inputText' style='width:70px !important'></td>\
                        <td width='30px'><img src='images/remove-icon.png' class='remove'></td>\
                    </tr>\
                </table>\
            </div>");
		$(".itemProduct").select2();
	}
	
	$(document).on('click', '.remove', function() {
		$(this).closest('.item').remove();
	});
	
	$(document).ready(function() {
		$("#store").select2();
		$("#employee").select2();
		$(".itemProduct").select2();
	});
	
	function cancel() {
		window.location.href = 'outflows.php';
	}
</script>
    
<?php include 'footer.php'; ?>