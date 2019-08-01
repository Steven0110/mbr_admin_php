<?php
include "includes/mysqlconn.php";

$ID = $_REQUEST["storeID"];
$code = "";
$name = "";
$phone = "";
$address = "";
$active = "";

if(isset($ID)) {
	$queryStore = "SELECT * FROM STORES WHERE ID = '$ID'";
	$resultStore = mysql_query($queryStore);
	$rowStore = mysql_fetch_assoc($resultStore);
	
	$code = $rowStore["code"];
	$last = $rowStore["last"];
	$name = $rowStore["name"];
	$phone = $rowStore["phone"];
	$address = $rowStore["address"];
	$active = $rowStore["active"];
}
?>

<div class="searchDiv">Buscar <input type="search" id="searchInput" class="inputText" style="width:300px"></div>

<table id="storeList" width="100%" border="0" cellspacing="0" cellpadding="0" class="dataTable">
  <thead>
    <tr>
      <td>ID</td>
      <td>Código</td>
      <td>Nombre</td>
      <td width="53px">Editar</td>
    </tr>
  </thead>
  <tbody>
    <?php
		$myQuery = mysql_query("SELECT * FROM STORES ORDER BY ID ASC");
		while($row = mysql_fetch_assoc($myQuery)){			
			echo "
				<tr>
				  <td>".$row["ID"]."</td>
				  <td>".utf8_encode($row["code"])."</td>
				  <td>".utf8_encode($row["name"])."</td>
				  <td><img src='images/icon_edit.gif' class='viewDetails st' data-id='".$row["ID"]."'></td>
				</tr>
			";
		};
	?>
  </tbody>
</table>

<div class="sectionTitle">NUEVO ALMACÉN</div>


<div class="format">
<form id="storeForm">
<table width="100%" border="0" cellspacing="20px" cellpadding="0">
  <tbody>
    <tr>
      <td width="50%">Nombre<br>
      	<div style="margin-top:10px"><input type="text" id="name" name="name" class="inputText" value="<?php echo utf8_encode($name); ?>" required><input type="hidden" id="storeID" name="storeID" value="<?php echo $ID; ?>"></div>
      </td>
      <td width="50%">Código<br>
        <div style="margin-top:10px"><input type="text" id="code" name="code" class="inputText" maxlength="3" required value="<?php echo utf8_encode($code); ?>"></div>
        </td>
    </tr>
    <tr>
      <td width="50%">Teléfono<br>
      	<div style="margin-top:10px"><input type="text" id="phone" name="phone" class="inputText" value="<?php echo utf8_encode($phone); ?>" required><input type="hidden" id="storeID" name="storeID" value="<?php echo $ID; ?>"></div>
      </td>
      <td width="50%">Dirección<br>
        <div style="margin-top:10px"><input type="text" id="address" name="address" class="inputText" maxlength="3" required value="<?php echo utf8_encode($address); ?>"></div>
        </td>
    </tr>
    <tr>
      <td width="50%">
      </td>
      <td width="50%">Activo <input type="checkbox" name="active" id="active" <?php if($active == 'Y') {echo "checked";}; ?>><br>
      </td>
    </tr>
    <tr>
      <td align="left"><button type="button" class="formButton redB" id="cancelSTBT">Cancelar</button></td>
      <td align="right"><button type="button" class="formButton blueB" id="saveStore">Guardar</button></td>
    </tr>
  </tbody>
</table>
</form>
</div>

<script>
// Submit Form
$().ready(function() {
	$("#storeForm").validate({
		rules: {
			name: {
				required: true
			},
			code: {
				required: true
			}
		},
		submitHandler: function(form) {
			$.post('includes/stores.php', $("#storeForm").serialize())
			.done(function(data) {
				$.post("stores.php", function(data) {
					$("#storeDiv").html(data + "<div class='inMessage'>Cambios guardados con &eacute;xito<div class='inCloseMessage'>[Aceptar]</div></div>");
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
$("#cancelSTBT").click(function () {
	$.post("stores.php?storeID=", function(data) {
		$("#storeDiv").html(data);
	});
});
$("#saveStore").click(function () {
	$("#storeForm").submit();
});
$(".inCloseMessage").click(function() {
	$(this).closest(".inMessage").remove();
});

$('#storeList').filterTable({
	inputSelector: '#searchInput'
});

$(".st").click(function() {
	$.post("stores.php?storeID=" + $(this).data("id"), function(data) {
		$("#storeDiv").html(data);
	});
});
</script>