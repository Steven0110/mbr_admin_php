<?php
include "includes/mysqlconn.php";

$ID = $_REQUEST["vendorID"];
$name = "";

if(isset($ID)) {
	$queryStore = "SELECT * FROM vendor WHERE ID = '$ID'";
	$resultStore = $db->query($queryStore);
	$rowStore = $resultStore->fetch();

	$name = $rowStore["name"];
}
?>

<div class="searchDiv">Buscar <input type="search" id="searchInput" class="inputText" style="width:300px"></div>

<table id="vendorList" width="100%" border="0" cellspacing="0" cellpadding="0" class="dataTable">
  <thead>
    <tr>
      <td>ID</td>
      <td>Nombre</td>
      <td width="53px">Editar</td>
    </tr>
  </thead>
  <tbody>
    <?php
		$myQuery = $db->query("SELECT * FROM vendor ORDER BY ID ASC");
		while($row = $myQuery->fetch()){			
			echo "
				<tr>
				  <td>".$row["ID"]."</td>
				  <td>".utf8_encode($row["name"])."</td>
				  <td><img src='images/icon_edit.gif' class='viewDetails vn' data-id='".$row["ID"]."'></td>
				</tr>
			";
		};
	?>
  </tbody>
</table>

<div class="sectionTitle">NUEVO PROVEEDOR</div>


<div class="format">
<form id="vendorForm">
<table width="100%" border="0" cellspacing="20px" cellpadding="0">
  <tbody>
    <tr>
      <td width="50%">Código<br>
      	<div style="margin-top:10px"><input type="text" id="vendorID" name="vendorID" class="inputText" readonly value="<?php echo utf8_encode($ID); ?>" required><input type="hidden" id="vendorID" name="vendorID" value="<?php echo $ID; ?>"></div>
      </td>
      <td width="50%">Nombre<br>
        <div style="margin-top:10px"><input type="text" id="name" name="name" class="inputText" maxlength="15" required value="<?php echo utf8_encode($name); ?>"></div>
        </td>
    </tr>
    <tr>
      <td align="left"><button type="button" class="formButton redB" id="cancelVNBT">Cancelar</button></td>
      <td align="right"><button type="button" class="formButton blueB" id="saveVendor">Guardar</button></td>
    </tr>
  </tbody>
</table>
</form>
</div>

<script>
// Submit Form
$().ready(function() {
	$("#vendorForm").validate({
		rules: {
			name: {
				required: true
			},
			code: {
				required: true
			}
		},
		submitHandler: function(form) {
			$.post('includes/vendor.php', $("#vendorForm").serialize())
			.done(function(data) {
				$.post("vendor.php", function(data) {
					$("#vendorDiv").html(data + "<div class='inMessage'>Cambios guardados con &eacute;xito<div class='inCloseMessage'>[Aceptar]</div></div>");
				});
			})
			.fail(function(data) {
				console.log(data);
			});
			event.preventDefault();
		}
	});
	if ($("#code").val() != "") {
		$("#code").prop("readonly", true);
	}
});
$("#cancelVNBT").click(function () {
	$.post("vendor.php?vendorID=", function(data) {
		$("#vendorDiv").html(data);
	});
});
$("#saveVendor").click(function () {
	$("#vendorForm").submit();
});
$(".inCloseMessage").click(function() {
	$(this).closest(".inMessage").remove();
});

$('#vendorList').filterTable({
	inputSelector: '#searchInput'
});

$(".vn").click(function() {
	$.post("vendor.php?vendorID=" + $(this).data("id"), function(data) {
		$("#vendorDiv").html(data);
	});
});
</script>