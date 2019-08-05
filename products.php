<?php
include 'head.php';

$storeIDs = array();
$storeNames = array();

$storesQuery = "SELECT ID, code FROM STORES ";
if ($_SESSION["role"] != 'Y') {
	$storesQuery.= "WHERE ID = '".$_SESSION["store"]."' ";
}
$storesQuery.= "ORDER BY ID ASC";
$storesResult = $db->query($storesQuery);
while ($storesRow = $storesResult->fetch()) {
	$storeIDs[] = $storesRow["ID"];
	$storeNames[] = $storesRow["code"];
}

?>

<div class="sectionTitle">REPORTE DE INVENTARIO</div>

<div class='sButtons'>

	<?php 
		if($_SESSION["role"] == 'Y') {
echo "<a class='sButton' href='inventariocosto.php' target='_blank'>CREAR XLS</a>";
} ?>
<a class='sButton' href='inventario.php' target='_blank'>CREAR PDF</a>
</div>

<div class="searchDiv">Buscar <input type="search" id="searchInput" class="inputText" style="width:300px"></div>

<table id="prodList" width="100%" border="0" cellspacing="0" cellpadding="0" class="dataTable">
  <thead>
    <tr>
      <td width="200px">C&oacute;digo</td>
      <td>Nombre</td>
      <td>Categor&iacute;a</td>
		<?php
        if($_SESSION["role"] == 'Y') {
			echo "<td>Proveedor</td>
					<td>Existencia</td>";
        }
        foreach ($storeIDs as $i => $storeID) {
			echo "<td>".$storeNames[$i]."</td>";
        }
        if($_SESSION["role"] == 'Y') {
			echo "<td width='53px'>Ver</td>";
        }
        ?>
    </tr>
  </thead>
  <tbody>
    <?php
		$myQuery = $db->query("SELECT T1.ID, T1.code, T1.name product, T2.catName cat, T3.name vendor, (SELECT SUM(qty) FROM PRDL T4 WHERE T4.prodCode = T1.code) qty FROM PRODUCT T1 INNER JOIN CAT T2 ON T1.catID = T2.ID INNER JOIN VENDOR T3 ON T1.vendorID = T3.ID ORDER BY T1.name ASC");
		while($row = $myQuery->fetch()){			
			echo "
				<tr>
				  <td width='200px'>".$row["code"]."</td>
				  <td>".$row["product"]."</td>
				  <td>".$row["cat"]."</td>";
			if($_SESSION["role"] == 'Y') {
				echo "<td>".$row["vendor"]."</td>
						<td>".$row["qty"]."</td>";
			}
			foreach ($storeIDs as $i => $storeID) {
				$byStore = "SELECT qty FROM PRDL WHERE prodCode = '".$row["code"]."' AND storeID = '".$storeID."'";
				$resByStore = $db->query($byStore);
				$rowByStore = $resByStore->fetch();
				echo "<td>".$rowByStore["qty"]."</td>";
			}
			if($_SESSION["role"] == 'Y') {
				echo "<td><a href='prodDetail.php?prodID=".$row["ID"]."'><img src='images/view-icon.png' width='40'></a></td>
				</tr>";
			}
		};
	?>
  </tbody>
</table>

<div class='sButtons'>
<a class='sButton' href='inventario.php' target='_blank'>CREAR PDF</a>
</div>

<script>
$('#prodList').filterTable({
	inputSelector: '#searchInput'
});
</script>
    
<?php include 'footer.php'; ?>