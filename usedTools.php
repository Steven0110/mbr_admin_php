<?php
include 'head.php';
//almacenes
$storeIDs = array();
$storeNames = array();
//CODIGO DE HERRAMIENTAS
$storesQuery = "SELECT ID, code, name FROM STORES ";
if ($_SESSION["role"] != 'Y') {
	$storesQuery.= "WHERE ID = '".$_SESSION["store"]."' ";
}
$storesQuery.= "ORDER BY ID ASC";
$storesResult = $db->query($storesQuery);
while ($storesRow = $storesResult->fetch()) {
	$storeIDs[] = $storesRow["ID"];
	$storeCodes[] = $storesRow["code"];
	$storeNames[] = $storesRow["name"];
}
?>

<script type="text/javascript" charset="utf8" src="js/dt/js/jquery.dataTables.min.js"></script>

<div class="sectionTitle">SALIDAS DE HERRAMIENTA</div>
<div class="sButtons"><a href="newOutflow.php" class="sButton">REGISTRAR SALIDA</a></div>

<div class="searchDiv">
Mostrar salidas del almac&eacute;n <select id="selectW" class="" name="">
    <?php
	if ($_SESSION["role"] == 'Y') {
		echo "<option value='0'>Todos</option>";
	}
	foreach ($storeIDs as $i => $storeID) {
		echo "<option value='".$storeID."'>".$storeNames[$i]."</option>";
	}
	?>
</select><br>
Busqueda por
<input type="radio" name="searchBy[]" class="searchBy" value="FOLIO" checked> Folio
<input type="radio" name="searchBy[]" class="searchBy" value="FECHA"> Fecha
<input type="radio" name="searchBy[]" class="searchBy" value="EMPLEADO"> Empleado

</div>

<table id="inflowList" class="display" cellspacing="0" cellpadding="0" width="100%">
    <thead>
        <tr>
            <th>Almacén</th>
            <th>Folio</th>
            <th>Fecha</th>
            <th>Persona</th>
            <th width="25px">Ver</th>
        </tr>
    </thead>
</table>

<div class="sButtons"><a href="newOutflow.php" class="sButton">REGISTRAR SALIDA</a></div>

<script>
$(document).ready(function() {
	var table = $('#inflowList').DataTable({
		"aoColumnDefs": [
			{'bSortable': false, 'aTargets': [4] },
			{'className': 'dt-center', 'aTargets': [4] }//,
			//{'visible': false, 'aTargets': [0] }
		],
		"scrollX": true,
		"processing": true,
		"serverSide": true,
		"ajax": {
			url: "includes/outflowList.php?wh="+$("#selectW").val()+"&by="+$(".searchBy:checked").val(),
			type: "post"
		},
		"order": [[2, 'desc']]//,
	});
	
	$("#searchParam").prop("placeholder", $(".searchBy:checked").val());
	
	var sendValues = function() {
		$("#searchParam").prop("placeholder", $(".searchBy:checked").val());
		table.ajax.url("includes/outflowList.php?wh="+$("#selectW").val()+"&by="+$(".searchBy:checked").val()).load();
		
	};
	
	$("#selectW").on("change", sendValues);
	$(".searchBy").on("change", sendValues);
});

</script>
    
<?php include 'footer.php'; ?>