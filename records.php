<?php
include_once 'head.php';
?>
<body>
<?php if (login_check($mysqli) == true) : ?>
<?php
include_once 'header.php';
?>

<div id="pageTop">
    <div class="leftCol">
    	<span class="title">Servicios registrados</span>
    </div>
    <div class="rightCol">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="notesTable formContainer">
      <tr>
        <td colspan="2"><strong>Sem&aacute;foro estatus de servicio:</strong></td>
      </tr>
      <tr>
        <td width="20px"><div class="semaphore" style="background-color:#82bc00; border:1px solid #427a00;"></div></td>
        <td>Activo</td>
      </tr>
      <tr>
        <td><div class="semaphore" style="background-color:#ffd900; border:1px solid #eab200;"></div></td>
        <td>Pr&oacute;ximo a vencer</td>
      </tr>
      <tr>
        <td><div class="semaphore" style="background-color:#62259d; border:1px solid #300068;"></div></td>
        <td>En periodo de gracia</td>
      </tr>
      <tr>
        <td><div class="semaphore" style="background-color:#e2231a; border:1px solid #890000;"></div></td>
        <td>Vencido</td>
      </tr>
    </table>
    </div>
</div>

<div id="pageContainer">
<?php
$sort = $_REQUEST["sort"];

switch ($sort) {
	case 0: 
		$orderBy = "ORDER BY T1.ID ASC"; 
		break;
	case 1: 
		$orderBy = "ORDER BY T1.ID DESC"; 
		break;
	case 2: 
		$orderBy = "ORDER BY T1.customer ASC"; 
		break;
	case 3: 
		$orderBy = "ORDER BY T1.customer DESC"; 
		break;
	case 4: 
		$orderBy = "ORDER BY T1.website ASC"; 
		break;
	case 5: 
		$orderBy = "ORDER BY T1.website DESC"; 
		break;
	case 6: 
		$orderBy = "ORDER BY T1.service ASC"; 
		break;
	case 7: 
		$orderBy = "ORDER BY T1.service DESC"; 
		break;
	case 8: 
		$orderBy = "ORDER BY T1.init ASC"; 
		break;
	case 9: 
		$orderBy = "ORDER BY T1.init DESC"; 
		break;
	case 10: 
		$orderBy = "ORDER BY T1.close ASC"; 
		break;
	case 11: 
		$orderBy = "ORDER BY T1.close DESC"; 
		break;
	case 12: 
		$orderBy = "ORDER BY T1.crew ASC"; 
		break;
	case 13: 
		$orderBy = "ORDER BY T1.crew DESC"; 
		break;
	case 14: 
		$orderBy = "ORDER BY T1.status ASC"; 
		break;
	case 15: 
		$orderBy = "ORDER BY T1.status DESC"; 
		break;
	default:
		$orderBy = "ORDER BY T1.ID ASC";
		break;
}

$myQuery = mysql_query("SELECT T1.ID, T1.customer, T1.website, T1.service, T1.init, T1.close, T1.crew, T1.status, T2.hex_color, T2.border FROM SERVICE AS T1 INNER JOIN SRV_STATUS AS T2 ON T1.status = T2.ID $orderBy");
			
echo "<table border='0' cellpadding='0' cellspacing='0' class='recordsTable'>
<thead>
<tr>
<th class='tableHeader'>ID <a href='?sort=0'>&#9650;</a> <a href='?sort=1'>&#9660;</a></th>
<th class='tableHeader'>Cliente <a href='?sort=2'>&#9650;</a> <a href='?sort=3'>&#9660;</a></th>
<th class='tableHeader'>Website URL <a href='?sort=4'>&#9650;</a> <a href='?sort=5'>&#9660;</a></th>
<th class='tableHeader'>Plan de servicio <a href='?sort=6'>&#9650;</a> <a href='?sort=7'>&#9660;</a></th>
<th class='tableHeader'>Inicia <a href='?sort=8'>&#9650;</a> <a href='?sort=9'>&#9660;</a></th>
<th class='tableHeader'>Termina <a href='?sort=10'>&#9650;</a> <a href='?sort=11'>&#9660;</a></th>
<th class='tableHeader'>Crew member <a href='?sort=12'>&#9650;</a> <a href='?sort=13'>&#9660;</a></th>
<th class='tableHeader'>Estatus <a href='?sort=14'>&#9650;</a> <a href='?sort=15'>&#9660;</a></th>
<th class='tableHeader'>&nbsp;</th>
</tr>
</thead>
<tbody>";
while($row = mysql_fetch_array($myQuery)){
	$initDate = new DateTime($row["init"]);
	$endDate = new DateTime($row["close"]);
    echo "<tr>
    <td>".$row["ID"]."</td>
    <td>".$row["customer"]."</td>
	<td>".$row["website"]."</td>
    <td>".$row["service"]."</td>
    <td>".$initDate->format('d-m-Y')."</td>
    <td>".$endDate->format('d-m-Y')."</td>
    <td>".$row["crew"]."</td>
	<td><div class='semaphore' style='background-color:#".$row["hex_color"]."; border:1px solid #".$row["border"].";'></div></td>
    <td><a href='http://cronos.idited.com/recordedit.php?record=".$row["ID"]."'><img src='http://cronos.idited.com/images/icon_edit.gif' style='max-width:16px;'></a></td>
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