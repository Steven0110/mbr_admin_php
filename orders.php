<?php
include 'head.php';

$storeIDs = array();
$storeNames = array();

$storesQuery = "SELECT ID, code, name FROM stores ORDER BY ID ASC";
$storesResult = $db->query($storesQuery);
while ($storesRow = $storesResult->fetch()) {
	$storeIDs[] = $storesRow["ID"];
	$storeCodes[] = $storesRow["code"];
	$storeNames[] = $storesRow["name"];
}
?>

<script type="text/javascript" charset="utf8" src="js/dt/js/jquery.dataTables.min.js"></script>

<div class="sectionTitle">PEDIDOS</div>

<div class="searchDiv">
Mostrar pedidos del almac&eacute;n <select id="selectW" class="" name="">
    <?php
	echo "<option value='0'>Todos</option>";
	foreach ($storeIDs as $i => $storeID) {
		echo "<option value='".$storeID."'>".$storeNames[$i]."</option>";
	}
	?>
</select> Estatus <select id="selectS" class="" name="">
<option value="0">Todos</option>
<option value="Y">Abiertos</option>
<option value="N">Cerrados</option>
<option value="C">Cancelados</option>
</select><br>
Busqueda por
<input type="radio" name="searchBy[]" class="searchBy" value="FOLIO" checked> Folio
<input type="radio" name="searchBy[]" class="searchBy" value="FECHA"> Fecha
<input type="radio" name="searchBy[]" class="searchBy" value="EMPLEADO"> Empleado

</div>

<table id="orderList" class="display" cellspacing="0" cellpadding="0" width="100%">
    <thead>
        <tr>
            <th>Almacén</th>
            <th>Folio</th>
            <th>Fecha</th>
            <th>Elabor&oacute;</th>
            <th>Estatus</th>
            <th width="25px">Ver</th>
        </tr>
    </thead>
</table>

<div class="sButtons">
<button class="sButton" id="globalOrder">PEDIDO GLOBAL</button>
<button class="sButton" id="orderGenerator">GENERADOR DE PEDIDOS</button>
</div>


<script>
$(document).ready(function() {
	var table = $('#orderList').DataTable({
		"aoColumnDefs": [
			{'bSortable': false, 'aTargets': [5] },
			{'className': 'dt-center', 'aTargets': [5] }//,
			//{'visible': false, 'aTargets': [0] }
		],
		"scrollX": true,
		"processing": true,
		"serverSide": true,
		"ajax": {
			url: "includes/ordersList.php?wh="+$("#selectW").val()+"&status="+$("#selectS").val()+"&by="+$(".searchBy:checked").val(),
			type: "post"
		},
		"order": [[2, 'desc']],
		"drawCallback": function () {
            if($(".status:contains('Abierto')").length < 1) {
				$("#globalOrder").prop("disabled", true).prop("title", "No existen pedidos por surtir");
			}
        }
	});
	
	$("#searchParam").prop("placeholder", $(".searchBy:checked").val());
	
	var sendValues = function() {
		$("#searchParam").prop("placeholder", $(".searchBy:checked").val());
		table.ajax.url("includes/ordersList.php?wh="+$("#selectW").val()+"&status="+$("#selectS").val()+"&by="+$(".searchBy:checked").val()).load();
	};
	
	$("#selectW").on("change", sendValues);
	$("#selectS").on("change", sendValues);
	$(".searchBy").on("change", sendValues);
	
	$("#orderGenerator").click(function() {
		window.location.href = "order.php";
	});
	$("#globalOrder").click(function() {
		window.open("pedidoGlobal.php");
	});
});
</script>
    
<?php include 'footer.php'; ?>