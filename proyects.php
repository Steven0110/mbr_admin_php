<?php
include 'head.php';
require('includes/mysqlconn.php');
$proyectIDs = array();
$proyectCodes = array();
$proyectNames = array();
//bring all proyects from db
//$storesQuery = "SELECT ID, proyect_name, client FROM PROYECTS ";
$proyectQuery = "SELECT ID Folio, proyectname, client FROM proyects ";

$proyectQuery.= "ORDER BY ID ASC";
$proyectResult = $db->prepare($proyectQuery);
$proyectResult->execute();
while ($proyectRow = $proyectResult->fetch()) {
	$proyectIDs[] = $proyectRow["Folio"];
	$proyectCodes[] = $proyectRow["proyectname"];
	$proyectNames[] = $proyectRow["client"];
}
?>

<script type="text/javascript" charset="utf8" src="js/dt/js/jquery.dataTables.min.js"></script>

<div class="sectionTitle">PROYECTOS</div>
<div class="sButtons"><a href="newProyect.php" class="sButton">NUEVO PROYECTO</a></div>

<div class="searchDiv"><!--
Mostrar proyectos <select id="selectW" class="" name="">
    <?php
	/*echo "<option value='0'>Todos</option>";
	foreach ($proyectIDs as $i => $proyectID) {
		echo "<option value='".$proyectID."'>".$proyectNames[$i]."</option>";
	}*/
	?>
</select><br>-->
Busqueda por
<input type="radio" name="searchBy[]" class="searchBy" value="FOLIO" checked> Folio
<input type="radio" name="searchBy[]" class="searchBy" value="PROYECTO"> Proyecto
<input type="radio" name="searchBy[]" class="searchBy" value="CLIENTE"> Cliente

</div>

<table id="inflowList" class="display" cellspacing="0" cellpadding="0" width="100%">
    <thead>
        <tr>
            <th>Folio</th>
            <th>Proyecto</th>
            <th>Cliente</th>
            <th width="25px">Ver</th>
        </tr>
    </thead>
</table>

<div class="sButtons"><a href="newProyect.php" class="sButton">NUEVO PROYECTO</a></div>

<script>
$(document).ready(function() {
	var table = $('#inflowList').DataTable({
		"aoColumnDefs": [
			{'bSortable': false, 'aTargets': [3] },
			{'className': 'dt-center', 'aTargets': [3] }//,
			//{'visible': false, 'aTargets': [0] }
		],
		"scrollX": true,
		"processing": true,
		"serverSide": true,
		"ajax": {
			url: "includes/proyectList.php?wh="+$("#selectW").val()+"&by="+$(".searchBy:checked").val(),
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
	
	$("#searchParam").prop("placeholder", "CODIGO");
	
	var sendValues = function() {
		$("#searchParam").prop("placeholder", $(".searchBy:checked").val());
		table.ajax.url("includes/proyectList.php?wh="+$("#selectW").val()+"&by="+$(".searchBy:checked").val()).load();
	};
	
	$("#selectW").on("change", sendValues);
	$(".searchBy").on("change", sendValues);
});

</script>
    
<?php include 'footer.php'; ?>