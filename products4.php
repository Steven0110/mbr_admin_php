<?php
include 'head.php';

$storeIDs = array();
$storeNames = array();

$storesQuery = "SELECT ID, code, name FROM STORES ";
if ($_SESSION["role"] != 'Y') {
	$storesQuery.= "WHERE ID = '".$_SESSION["store"]."' ";
}
$storesQuery.= "ORDER BY ID ASC";
$storesResult = mysql_query($storesQuery);
while ($storesRow = mysql_fetch_assoc($storesResult)) {
	$storeIDs[] = $storesRow["ID"];
	$storeCodes[] = $storesRow["code"];
	$storeNames[] = $storesRow["name"];
}

?>


<script type="text/javascript" charset="utf8" src="js/dt/js/jquery.dataTables.min.js"></script>


<div class="sectionTitle">INVENTARIO</div>

<div class='sButtons'>
<a class="sButton" href="products3.php">REPORTE</a>
<?php
if($_SESSION["role"] == 'Y') {
	echo "<a href='newProduct.php' class='sButton'>NUEVO PRODUCTO</a>";
}
?>
</div>

<div class="searchDiv">
Mostrar existencias del almac&eacute;n <select id="selectW" class="" name="">
    <?php
	if($_SESSION["role"] == "Y") {
		echo "<option value='0'>Global</option>";
	}
	foreach ($storeIDs as $i => $storeID) {
		echo "<option value='".$storeID."'>".$storeNames[$i]."</option>";
	}
	?>
</select><br>
Busqueda por
<input type="radio" name="searchBy[]" class="searchBy" value="CODIGO" checked> C&oacute;digo
<input type="radio" name="searchBy[]" class="searchBy" value="NOMBRE"> Nombre
<input type="radio" name="searchBy[]" class="searchBy" value="CATEGORIA"> Categor&iacute;a
<?php
if ($_SESSION["role"] == "Y") {
	echo "<input type='radio' name='searchBy[]' class='searchBy' value='PROVEEDOR'> Proveedor";
}
?>

</div>

<table id="prodList" class="display" cellspacing="0" cellpadding="0" width="100%">
    <thead>
        <tr>
            <th>C&oacute;digo</th>
            <th>Nombre</th>
            <th>Categor&iacute;a</th>
            <?php
			if($_SESSION["role"] == "Y") {
				echo "<th>Proveedor</th>";
			}
			?>
            <th>Existencia</th>
            <?php
			if($_SESSION["role"] == "Y") {
				echo "<th>Precio</th>";
			}
			?>
            <th>Por recibir</th>
            <?php
			if($_SESSION["role"] == "Y") {
				echo "<th width='25px'>Ver</th>";
			}
			?>
        </tr>
    </thead>
</table>

<div class="sButtons">
<a class="sButton" href="products3.php">REPORTE</a>
<?php
if($_SESSION["role"] == 'Y') {
	echo "<a href='newProduct.php' class='sButton'>NUEVO PRODUCTO</a>";
}
?>
</div>

<script>
$(document).ready(function() {
	var table = $('#prodList').DataTable({
		"order": [[ 1, "asc" ]],
		<?php
		if ($_SESSION["role"] == "Y") {
		?>
		"aoColumnDefs": [
			{'bSortable': false, 'aTargets': [7] },
			{'className': 'dt-center', 'aTargets': [7] }
		],
		<?php
		}
		?>
		"scrollX": true,
		"processing": true,
		"serverSide": true,
		"ajax": {
			url: "includes/productList1.php?by=CODIGO",
			type: "post"
		}
	});
	
	$("#searchParam").prop("placeholder", "CODIGO");
	
	var sendValues = function() {
		$("#searchParam").prop("placeholder", $(".searchBy:checked").val());
		table.ajax.url("includes/productList1.php?wh="+$("#selectW").val()+"&by="+$(".searchBy:checked").val()).load();
		
	};
	
	$("#selectW").on("change", sendValues);
	$(".searchBy").on("change", sendValues);
});


</script>
    
<?php include 'footer.php'; ?>