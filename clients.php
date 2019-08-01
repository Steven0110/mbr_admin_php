<?php
include_once 'head.php';
?>
<body>
<?php if (login_check($mysqli) == true) : ?>
<?php
include_once 'header.php';
?>

<div id="pageContainer">

<?php
$myQuery = mysql_query("SELECT ID, name, cnt_first, cnt_last, phone, email FROM CLIENTS ORDER BY ID");
			
echo "<table border='0' cellpadding='0' cellspacing='0' class='recordsTable'>
<thead>
<tr>
<th class='tableHeader'>ID</th>
<th class='tableHeader'>Cliente</th>
<th class='tableHeader'>Contacto</th>
<th class='tableHeader'>Tel&eacute;fono</th>
<th class='tableHeader'>Email</th>
<th class='tableHeader'>&nbsp;</th>
</tr>
</thead>
<tbody>";
while($row = mysql_fetch_array($myQuery)){
    echo "<tr>
    <td>".$row["ID"]."</td>
    <td>".$row["name"]."</td>
	<td>".$row["cnt_first"]." ".$row["cnt_last"]."</td>
    <td>".$row["phone"]."</td>
    <td>".$row["email"]."</td>
    <td><a href='http://cronos.idited.com/clientedit.php?clientID=".$row["ID"]."'><img src='http://cronos.idited.com/images/icon_edit.gif' style='max-width:16px;'></a></td>
    </tr>";
};
echo "</tbody></table>";
?>

</div>

<?php else : ?>

No pasa

<?php endif; ?>

<?php
include_once 'footer.php';
?>