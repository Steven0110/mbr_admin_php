<?php include 'head.php'; ?>

<script type="text/javascript" charset="utf8" src="js/dt/js/jquery.dataTables.min.js"></script>
<div class="sectionTitle">LISTA DE PRECIOS</div>
<form method="post" action="listaPrecios.php" target="_blank">
	<div class="sButtons"><input type="submit" class="formButton blueB" target="_blank" value="CREAR PDF"></div>
	<div class="searchDiv"><!--Buscar <input type="search" id="searchInput" class="inputText" style="width:300px">-->
	Selecciona la lista<select id="selectW" class="" name="list">
			<option value='0'>Lista 1</option>
		    <option value='1'>Lista 2</option>
	        <option value='2'>Lista 3</option>
	</select><br>
	</div>
	<table id="priceList" width="100%" border="0" cellspacing="0" cellpadding="0" class="dataTable">
	  <thead>
	    <tr>
	      <td width="200px">C&oacute;digo</td>
	      <td>Nombre</td>
	      <td>Codigo</td>
	      <td>Precio</td>
	      <td>Referencia</td>
	    </tr>
	  </thead>
	</table>
<div class="sButtons"><input type="submit" class="formButton blueB" target="_blank" value="CREAR PDF"></div>
</form>
<script>
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

