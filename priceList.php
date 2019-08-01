<?php include 'head.php'; ?>

<div class="sectionTitle">LISTA DE PRECIOS</div>
<div class="sButtons"><a href="listaPrecios.php" class="formButton blueB" target="_blank"><i class='fa fa-file-pdf-o' aria-hidden='true'></i> PDF</a></div>
<div class="searchDiv">Buscar <input type="search" id="searchInput" class="inputText" style="width:300px"></div>
<table id="priceList" width="100%" border="0" cellspacing="0" cellpadding="0" class="dataTable">
  <thead>
    <tr>
      <td width="200px">C&oacute;digo</td>
      <td>Nombre</td>
      <td>Categor&iacute;a</td>
      <td>Proveedor</td>
      <td>Precio</td>
    </tr>
  </thead>
  <tbody>
    <?php
		$myQuery = mysql_query("SELECT T1.ID, T1.code, T1.name product, T3.name vendor, T1.price, T2.catName cat FROM PRODUCT T1 JOIN CAT T2 ON T1.catID = T2.ID JOIN VENDOR T3 ON T1.vendorID = T3.ID WHERE T1.active = 'Y' ORDER BY T1.name ASC");
		while($row = mysql_fetch_array($myQuery)){			
			echo "
				<tr>
				  <td>".$row["code"]."</td>
				  <td>".$row["product"]."</td>
				  <td>".$row["cat"]."</td>
				  <td>".$row["vendor"]."</td>
				  <td>$".$row["price"]."</td>
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
</script>
    
<?php include 'footer.php'; ?>