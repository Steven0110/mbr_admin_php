<?php include 'head.php'; ?>


<div class="sectionTitle">LISTA DE PRECIOS</div>
<div class="sButtons"><a href="listaPrecios.php" class="formButton blueB" target="_blank"><i class='fa fa-file-pdf-o' aria-hidden='true'></i> PDF</a></div>
<div class="searchDiv">Buscar <input type="search" id="searchInput" class="inputText" style="width:300px">
Selecciona la lista<select id="selectW" class="" name="">
		<option value='0'>Lista 1</option>
	    <option value='1'>Lista 2</option>
        <option value='2'>Lista 3</option>
</select><br>
</div>
<table id="priceList" width="100%" border="0" cellspacing="0" cellpadding="0" class="dataTable">
  <thead>
    <tr>
      <td width="200px">C&oacute;digo</td>
      <td>Nombre</td>
      <td>Codigo</td>
      <td>Precio</td>
      <td>Referencia</td>
    </tr>
  </thead>
  <tbody>
    <?php
      /*LISTA 1 DE PRECIOS*/
		$myQuery = $db->query("SELECT t1.ID id, t1.REFERENCE code, t1.CODE code1, t1.NAME product, t1.PRICESELL price,t1.MARCA vendor FROM products t1 ORDER BY t1.ID ASC");
		while($row = $myQuery->fetch()){			
			echo "
				<tr>
				  <td>".$row["code"]."</td>
				  <td>".utf8_decode($row["product"])."</td>
				  <td>".$row["code1"]."</td>
				  <td>$".$row["price"]."</td>
                  <td>".$row["vendor"]."</td>
				</tr>
			";
		};
	?>
  </tbody>
</table>
<div class="sButtons"><a href="listaPrecios.php" class="formButton blueB" target="_blank"><i class='fa fa-file-pdf-o' aria-hidden='true'></i> PDF</a></div>
<script>
$('#priceList').filterTable({
	inputSelector: '#searchInput'
});
    
var sendValues = function() {
    //llamamos a includes/priceLists.php para qeu haga la busqueda de valores de las otras listas,segun vayan seleccionando
		table.ajax.url("includes/priceLists.php?wh="+$("#selectW").val()).load();
	};
    
$("#selectW").on("change", sendvalues);
</script>
    
<?php include 'footer.php'; ?>