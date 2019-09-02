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


<div class="sectionTitle">GENERADOR DE PEDIDOS</div>

<div class="searchDiv">
Mostrar existencias del almac&eacute;n <select id="selectW" class="" name="">
    <?php
	if($_SESSION["role"] == "Y") {
		echo "<option value='' selected disabled>Selecciona...</option>";
	}
	foreach ($storeIDs as $i => $storeID) {
		echo "<option value='".$storeID."'>".$storeNames[$i]." (".$storeID.")</option>";
	}
	?>
</select><br>
Busqueda por
<input type="radio" name="searchBy[]" class="searchBy" value="CODIGO" checked> C&oacute;digo
<input type="radio" name="searchBy[]" class="searchBy" value="NOMBRE"> Nombre
<input type="radio" name="searchBy[]" class="searchBy" value="PROVEEDOR"> Proveedor

</div>

<table id="prodList" class="display" cellspacing="0" cellpadding="0" width="100%">
    <thead>
        <tr>
            <th width="150px">C&oacute;digo</th>
            <th>Nombre</th>
            <th width="150px">Proveedor</th>
            <th width="70px">Precio</th>
            <th width="50px">M&iacute;nimo</th>
            <th width="50px">M&aacute;ximo</th>
            <th width="50px">Existencia</th>
            <th width="50px">Faltante</th>
            <th width="50px">P.P.R.</th>
            <th width="70px">Importe</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th colspan="8" align="right">Total:</th>
            <th colspan="2"></th>
        </tr>
    </tfoot>
</table>

<div class="sButtons">
<button class="sButton" id="createOrder">CREAR PEDIDO</button>
<button class="sButton" id="createOrderexist">CREAR PEDIDO C/EXIST</button>
</div>

<script>
$(document).ready(function() {
	var table = $('#prodList').DataTable({
		"footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === "string" ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === "number" ?
                        i : 0;
            };
			
			var info = this.api().ajax.json();
			
            // Total over all pages
			if (info.totalCost == null) {
				total = 0;
			} else {
				total = Number(info.totalCost);
			}
 
            // Total over this page
            pageTotal = api
                .column( 9, { page: "current"} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 9 ).footer() ).html(
                "<strong>$" + localeString(total.toFixed(2)) + "</strong> ($" + localeString(pageTotal.toFixed(2)) + ")"
            );
        },
		"order": [[ 1, "asc" ]],
		"aoColumnDefs": [
			{'className': 'dt-right', 'aTargets': [3,9] }
		],
		"scrollX": true,
		"processing": true,
		"serverSide": true,
		"ajax": {
			url: "includes/orderList.php?wh=0&by=CODIGO",
			type: "post"
		},
		stateSave: true,
		stateDuration: 600
	});
	
	$("#searchParam").prop("placeholder", $(".searchBy:checked").val());
	
	var sendValues = function() {
		$("#searchParam").prop("placeholder", $(".searchBy:checked").val());
		table.ajax.url("includes/orderList.php?wh="+$("#selectW").val()+"&by="+$(".searchBy:checked").val()).load();
	};
	
	$("#selectW").on("change", sendValues);
	$(".searchBy").on("change", sendValues);
	
	if ($("#selectW").val()) {
		sendValues();
	};
});

$("#createOrder").click(function() {
	window.location.href = "storeOrder.php?storeID=" + $("#selectW").val() + "&by=" + $(".searchBy:checked").val() + "&term=" + $("#searchParam").val();
});
$("#createOrderexist").click(function() {
	window.location.href = "storeOrderexist.php?storeID=" + $("#selectW").val() + "&by=" + $(".searchBy:checked").val() + "&term=" + $("#searchParam").val();
});
</script>
    
<?php include 'footer.php'; ?>