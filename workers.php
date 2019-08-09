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