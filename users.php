<?php
include "includes/mysqlconn.php";

$ID = $_REQUEST["userID"];
$first = "";
$last = "";
$email = "";
$store = "";
$username = "";
$password = "";
$admin = "";
$active = "";
$sales = "";

if(isset($ID)) {
	$queryUser = "SELECT * FROM CREW WHERE ID = '$ID'";
	$resultUser = $db->query($queryUser);
	$rowUser = $resultUser->fetch();
	
	$first = $rowUser["first"];
	$last = $rowUser["last"];
	$email = $rowUser["email"];
	$store = $rowUser["storeID"];
	$username = $rowUser["username"];
	$password = $rowUser["password"];
	$admin = $rowUser["role"];
	$active = $rowUser["active"];
	$sales = $rowUser["salesPrson"];
}
?>

<div class="searchDiv">Buscar <input type="search" id="searchInput" class="inputText" style="width:300px"></div>

<table id="userList" width="100%" border="0" cellspacing="0" cellpadding="0" class="dataTable">
  <thead>
    <tr>
      <td>ID</td>
      <td>Nombre</td>
      <td>Apellido</td>
      <td>Email</td>
      <td>Almac&eacute;n</td>
      <td>Usuario</td>
      <td width="53px">Editar</td>
    </tr>
  </thead>
  <tbody>
    <?php
		$myQuery = $db->query("SELECT T1.ID, T1.first, T1.last, T1.email, T2.name store, T1.username FROM CREW T1 JOIN STORES T2 ON T1.storeID = T2.ID ORDER BY T1.first ASC, T1.last ASC");
		while($row = $myQuery->fetch()){			
			echo "
				<tr>
				  <td>".$row["ID"]."</td>
				  <td>".utf8_encode($row["first"])."</td>
				  <td>".utf8_encode($row["last"])."</td>
				  <td>".$row["email"]."</td>
				  <td>".utf8_encode($row["store"])."</td>
				  <td>".$row["username"]."</td>
				  <td><img src='images/icon_edit.gif' class='viewDetails us' data-id='".$row["ID"]."'></td>
				</tr>
			";
		};
	?>
  </tbody>
</table>

<div class="sectionTitle">NUEVO USUARIO</div>


<div class="format">
<form id="userForm">
<table width="100%" border="0" cellspacing="20px" cellpadding="0">
  <tbody>
    <tr>
      <td width="50%">Nombre<br>
      	<div style="margin-top:10px"><input type="text" id="first" name="first" class="inputText" value="<?php echo utf8_encode($first); ?>" required><input type="hidden" id="userID" name="userID" value="<?php echo $ID; ?>"></div>
      </td>
      <td width="50%">Apellido<br>
        <div style="margin-top:10px"><input type="text" id="last" name="last" class="inputText" required value="<?php echo utf8_encode($last); ?>"></div>
        </td>
    </tr>
    <tr>
      <td width="50%">Email<br>
      	<div style="margin-top:10px"><input type="text" id="email" name="email" class="inputText" required value="<?php echo $email; ?>"></div>
      </td>
      <td width="50%">Almacén<br>
        <div style="margin-top:10px"><select id="store" name="store" style="margin-top:10px;">
        	<option value="" disabled selected>Selecciona...</option>
        	<?php
			$queryStore = "SELECT ID, code, name FROM STORES ORDER BY name ASC";
			$resultStore = $db->query($queryStore);
			while($rowStore = $resultStore->fetch()){
				$selOpt = "";
				if ($rowStore["ID"] == $store) {
					$selOpt = "selected";
				}
				echo "<option value='".$rowStore["ID"]."' ".$selOpt.">".utf8_encode($rowStore["name"])."</option>";
			};
			?>
        </select></div>
        </td>
    </tr>
    <tr>
      <td width="50%">Usuario<br>
      	<div style="margin-top:10px"><input type="text" id="username" name="username" class="inputText" required value="<?php echo $username; ?>"></div>
      </td>
      <td width="50%">Contraseña<br>
        <div style="margin-top:10px"><input type="password" id="password" name="password" class="inputText" required value="<?php echo $password; ?>"></div>
        </td>
    </tr>
    <tr>
      <td width="50%">Es administrador <input type="checkbox" name="admin" id="admin" <?php if($admin == 'Y') {echo "checked";}; ?>>
      </td>
      <td width="50%">Activo <input type="checkbox" name="active" id="active" <?php if($active == 'Y') {echo "checked";}; ?>><br>
      </td>
    </tr>
    <tr>
      <td width="50%">Es vendedor <input type="checkbox" name="sales" id="sales" <?php if($sales == 'Y') {echo "checked";}; ?>>
      </td>
      <td width="50%"><br>
      </td>
    </tr>
    <tr>
      <td align="left"><button type="button" class="formButton redB" id="cancelUSBT">Cancelar</button></td>
      <td align="right"><button type="button" class="formButton blueB" id="saveUser">Guardar</button></td>
    </tr>
  </tbody>
</table>
</form>
</div>

<script>
// Submit Form
$().ready(function() {
	$("#userForm").validate({
		rules: {
			first: {
				required: true
			},
			last: {
				required: true
			},
			email: {
				required: true,
				email: true
			},
			store: {
				required: true
			},
			username: {
				required: true
			},
			password: {
				required: true
			}
		},
		submitHandler: function(form) {
			$.post('includes/users.php', $("#userForm").serialize())
			.done(function(data) {
				$.post("users.php", function(data) {
					$("#userDiv").html(data + "<div class='inMessage'>Cambios guardados con &eacute;xito<div class='inCloseMessage'>[Aceptar]</div></div>");
				});
			})
			.fail(function(data) {
				console.log(data);
			});
			event.preventDefault();
		}
	});
});
$("#cancelUSBT").click(function () {
	$.post("users.php", function(data) {
		$("#userDiv").html(data);
	});
});
$("#saveUser").click(function () {
	$("#userForm").submit();
});
$(".inCloseMessage").click(function() {
	$(this).closest(".inMessage").remove();
});

$('#userList').filterTable({
	inputSelector: '#searchInput'
});

$(".us").click(function() {
	$.post("users.php?userID=" + $(this).data("id"), function(data) {
		$("#userDiv").html(data);
	});
});

$(document).ready(function() {
    
});
</script>