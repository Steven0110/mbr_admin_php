<?php
include 'head.php';

$storeIDs = array();
$storeNames = array();

$storesQuery = "SELECT ID, code, name FROM STORES WHERE ID <> 100 ORDER BY name ASC";
$storesResult = mysql_query($storesQuery);
while ($storesRow = mysql_fetch_assoc($storesResult)) {
	$storeIDs[] = $storesRow["ID"];
	$storeCodes[] = $storesRow["code"];
	$storeNames[] = $storesRow["name"];
}
?>

<script type="text/javascript" charset="utf8" src="js/dt/js/jquery.dataTables.min.js"></script>

<div class="sectionTitle">COBROS</div>

<div class="searchDiv">
Mostrar cobros del sal&oacute;n <select id="selectW" class="" name="">
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
            <th>Almacén</th>
            <th>Folio</th>
            <th>Fecha</th>
            <th>Realiz&oacute;</th>
            <th width="25px">Ver</th>
        </tr>
    </thead>
</table>

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
			url: "includes/paymentsList.php?wh="+$("#selectW").val()+"&by="+$(".searchBy:checked").val(),
			type: "post"
		},
		"order": [[2, 'desc']]
	});
	
	$("#searchParam").prop("placeholder", $(".searchBy:checked").val());
	
	var sendValues = function() {
		$("#searchParam").prop("placeholder", $(".searchBy:checked").val());
		table.ajax.url("includes/paymentsList.php?wh="+$("#selectW").val()+"&by="+$(".searchBy:checked").val()).load();
		
	};
	
	$("#selectW").on("change", sendValues);
	$(".searchBy").on("change", sendValues);
});

</script>
    
<?php include 'footer.php'; ?>