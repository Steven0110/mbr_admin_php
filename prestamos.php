<?php
include 'head.php';

$storeIDs = array();
$storeCodes = array();
$storeNames = array();
$storeDate  =array();
//"SELECT ID, code, name FROM STORES ";
$storesQuery = "SELECT t1.ID_PRESTAMO ID, t1.ID_PROYECTO ID_PROYECTO,DATE_FORMAT(t1.CREATED_AT, '%Y-%m-%d %H:%i') created,t2.NOMBRE name FROM prestamos t1 INNER JOIN empleados t2 ON t1.ID_EMPLEADO = t2.ID_EMPLEADO GROUP BY t1.ID_PRESTAMO";
/*
if ($_SESSION["role"] != 'Y') {
	$storesQuery.= "WHERE ID = '".$_SESSION["store"]."' ";
}*/
$storesResult = $db->query($storesQuery);
while ($storesRow = $storesResult->fetch()) {
	$storeIDs[] = $storesRow["ID"];
	$storeCodes[] = $storesRow["ID_PROYECTO"];
	$storeDate[] = $storesRow["created"];
	$storeNames[] = $storesRow["name"];
}
?>

<script type="text/javascript" charset="utf8" src="js/dt/js/jquery.dataTables.min.js"></script>

<div class="sectionTitle">PRESTAMOS ACTIVOS</div>
<div class="sButtons"><a href="newPrestamo.php" class="sButton">REGISTRAR PRESTAMO</a></div>

<div class="searchDiv">
Mostrar registros del empleado: <select id="selectW" class="" name="">
    <?php
	echo "<option value='0'>Todos</option>";
	foreach ($storeIDs as $i => $storeID) {
		echo "<option value='".$storeID."'>".$storeNames[$i]."</option>";
	}
	?>
</select><br>
Busqueda por
<input type="radio" name="searchBy[]" class="searchBy" value="ID" checked> Prestamo
<input type="radio" name="searchBy[]" class="searchBy" value="PRESTAMO"> Proyecto
<input type="radio" name="searchBy[]" class="searchBy" value="EMPLEADO"> Empleado

</div>

<table id="inflowList" class="display" cellspacing="0" cellpadding="0" width="100%">
    <thead>
        <tr>
            <th>Prestamo</th>
            <th>Proyecto</th>
            <th>Fecha creacion</th>
            <th>Empleado</th>
            <th width="25px">Ver</th>
        </tr>
    </thead>
</table>

<div class="sButtons"><a href="newPrestamo.php" class="sButton">REGISTRAR PRESTAMO</a></div>

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
			url: "includes/prestamosList.php?wh="+$("#selectW").val()+"&by="+$(".searchBy:checked").val(),
			type: "post"
		},
		"order": [[2, 'desc']]//,
        //"displayLength": 25
		//"drawCallback": function ( settings ) {
        //    var api = this.api();
        //    var rows = api.rows( {page:'current'} ).nodes();
        //    var last=null;
 
        //    api.column(0, {page:'current'} ).data().each( function ( group, i ) {
        //        if ( last !== group ) {
        //            $(rows).eq( i ).before(
        //                '<tr class="group"><td colspan="4">'+group+'</td></tr>'
        //            );
        //            last = group;
        //        }
        //    });
        //}
	});
	
	$("#searchParam").prop("placeholder", $(".searchBy:checked").val());
	
	var sendValues = function() {
		$("#searchParam").prop("placeholder", $(".searchBy:checked").val());
		table.ajax.url("includes/prestamosList.php?wh="+$("#selectW").val()+"&by="+$(".searchBy:checked").val()).load();
		
	};
	
	$("#selectW").on("change", sendValues);
	$(".searchBy").on("change", sendValues);
});

</script>
    
<?php include 'footer.php'; ?>