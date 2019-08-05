<?php
include 'head.php';

$storeIDs = array();
$storeNames = array();

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

<div class="sectionTitle">TRANSFERENCIAS</div>
<div class="sButtons"><a href="newTransfer.php" class="sButton">REGISTRAR TRANSFERENCIA</a></div>

<div class="searchDiv">
Mostrar transferencias al almac&eacute;n <select id="selectW" class="" name="">
    <?php
	echo "<option value='0'>Todos</option>";
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
            <th>Almacén Origen</th>
            <th>Almacén Destino</th>
            <th>Folio</th>
            <th>Fecha</th>
            <th>Realiz&oacute;</th>
            <th width="25px">Ver</th>
        </tr>
    </thead>
</table>

<div class="sButtons"><a href="newTransfer.php" class="sButton">REGISTRAR TRANSFERENCIA</a></div>

<script>
$(document).ready(function() {
	var table = $('#inflowList').DataTable({
		"aoColumnDefs": [
			{'bSortable': false, 'aTargets': [5] },
			{'className': 'dt-center', 'aTargets': [5] }//,
			//{'visible': false, 'aTargets': [0] }
		],
		"scrollX": true,
		"processing": true,
		"serverSide": true,
		"ajax": {
			url: "includes/transferList.php?wh="+$("#selectW").val()+"&by="+$(".searchBy:checked").val(),
			type: "post"
		},
		"order": [[3, 'desc']]
	});
	
	$("#searchParam").prop("placeholder", $(".searchBy:checked").val());
	
	var sendValues = function() {
		$("#searchParam").prop("placeholder", $(".searchBy:checked").val());
		table.ajax.url("includes/transferList.php?wh="+$("#selectW").val()+"&by="+$(".searchBy:checked").val()).load();
		
	};
	
	$("#selectW").on("change", sendValues);
	$(".searchBy").on("change", sendValues);
});

</script>
    
<?php include 'footer.php'; ?>