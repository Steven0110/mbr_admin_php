<?php
include 'head.php';

$storeIDs = array();
$storeNames = array();

$storesQuery = "SELECT ID, code FROM stores ";
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
</table>

<div class='sButtons'>
<a class='sButton' href='inventario.php' target='_blank'>CREAR PDF</a>
</div>

<script>
/*$('#prodList').filterTable({
	inputSelector: '#searchInput'
});*/
$(document).ready(function() {
	var table = $('#priceList').DataTable({
		"aoColumnDefs": [
			{'bSortable': false, 'aTargets': [4] },
			{'className': 'dt-center', 'aTargets': [4] }//,
			//{'visible': false, 'aTargets': [0] }
		],
		"scrollX": true,
		"processing": true,
		"serverSide": true,
		"ajax": {
			url: "includes/priceLists.php?ls="+$("#selectW").val()+"&by="+$(".searchBy:checked").val(),
			type: "post"
		},
		"order": [[2, 'desc']]//,
	});
	$("#searchParam").prop("placeholder", $(".searchBy:checked").val());
	
	var sendValues = function() {
		$("#searchParam").prop("placeholder", "NOMBRE");
		table.ajax.url("includes/priceLists.php?ls="+$("#selectW").val()+"&by=NOMBRE").load();
		
	};
	
	$("#selectW").on("change", sendValues);
	$(".searchBy").on("change", sendValues);
});
</script>
    
<?php include 'footer.php'; ?>