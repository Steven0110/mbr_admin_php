<?php
include "head.php";

$pmnID = $_REQUEST["pmnID"];

$queryPmnt = "SELECT T1.ID, T1.code, T1.created_at, T1.storeID, T3.name storeName, CONCAT(T2.first, ' ', T2.last) emp, T1.active, T1.fromDate, T1.toDate FROM payment T1 JOIN crew T2 ON T1.empID = T2.ID JOIN stores T3 ON T1.storeID = T3.ID WHERE T1.ID = $pmnID";
$resultPmnt = $db->query($queryPmnt);
$rowPmnt = $resultPmnt->fetch();

$code = $rowPmnt["code"];
$store = $rowPmnt["storeID"];
$storeName = $rowPmnt["storeName"];
$emp = $rowPmnt["emp"];
$fromDate = $rowPmnt["fromDate"];
$toDate = $rowPmnt["toDate"];
/*$fromDateQ = date('Y-m-d 00:00:00', strtotime(str_replace('/', '-', $_POST["fromDate"])));
$toDateQ = date('Y-m-d 23:59:59', strtotime(str_replace('/', '-', $_POST["toDate"])));
*/
?>
<form method="post" id="pmntForm">
<div class="sectionTitle">
    PAGO <?php echo $code; ?>
</div>

<div class="format">
<table width="100%" border="0" cellspacing="20px" cellpadding="0">
<tr>
	<td width="50%">Salón</td>
    <td>Fecha de documento</td>
</tr>
<tr>
	<td><input type="text" class="inputText" readonly value="<?php echo $storeName; ?>"></td>
    <td><input type="text" class="inputText" readonly value="<?php echo $rowPmnt["created_at"]; ?>"></td>
</tr>
<tr>
	<td width="50%">Desde el:</td>
    <td>Hasta el:</td>
</tr>
<tr>
	<td><input type="text" class="inputText" readonly value="<?php echo $fromDate; ?>"></td>
    <td><input type="text" class="inputText" readonly value="<?php echo $toDate; ?>"></td>
</tr>
<tr>
	<td width="50%">Realizado por</td>
    <td>Estatus</td>
</tr>
<tr>
	<td><input type="text" class="inputText" readonly value="<?php echo $emp; ?>"></td>
    <td><input type="text" class="inputText" readonly value="<?php echo $rowPmnt["active"]; ?>"></td>
</tr>
<tr>
	<td colspan="2">Entregas</td>
</tr>
<tr>
	<td colspan="2">
    	<div id="itemContainer">
            <table class="itemListHead" cellpadding="0" cellspacing="10px" width="100%">
                <thead>
                    <tr>
                        <td class="tdFolio">Folio</td>
                        <td class="tdDate">Fecha</td>
                        <td class="tdEmp">Realizó</td>
                        <td class="tdValue">Monto</td>
                        <td class="tdView"></td>
                    </tr>
                </thead>
            </table>
            <table class='itemTable' id='trnTable' width='100%' cellpadding='0' cellspacing='10px'>
            	<?php
				$queryTransfers = "SELECT T1.ID, T1.code, CONCAT(T2.first, ' ', T2.last) emp, T1.created_at, (SELECT SUM((T3.qty * T4.price)) FROM trln T3 JOIN product T4 ON T3.prodCode = T4.code WHERE T3.tranID = T1.ID) value FROM transfers T1 JOIN crew T2 ON T1.empID = T2.ID WHERE T1.orStore = 100 AND T1.dsStore = $store AND T1.account = 'Y' AND pmntCode = '$code' ORDER BY T1.created_at ASC";
				$resultTransfers = $db->query($queryTransfers);
				while ($rowTransfers = $resultTransfers->fetch()) {
					echo "<tr class='item'>
					<td class='tdFolio'>".$rowTransfers["code"]."</td><input type='hidden' name='tranCode[]' value='".$rowTransfers["code"]."'>
					<td class='tdDate'>".$rowTransfers["created_at"]."</td>
					<td class='tdEmp'>".$rowTransfers["emp"]."</td>
					<td class='tdValue' align='right'><span style='float:left'>$</span><div class='priceDiv tranValue'>".$rowTransfers["value"]."</div></td>
					<td class='tdView' align='right'><a href='transferDetail.php?tranID=".$rowTransfers["ID"]."'><i class='fa fa-eye' aria-hidden='true'></i></a></td>
				</tr>";
				}
				?>
			</table>
            <table class="itemListHead" cellpadding="0" cellspacing="10px" width="100%">
                <thead>
                    <tr>
                        <td class="tdFolio"></td>
                        <td class="tdDate"></td>
                        <td class="tdEmp" align="right">Total de entregas</td>
                        <td class="tdValue" align="right"><span style='float:left'>$</span><div class='tranTotal'></div></td>
                        <td class="tdView"></td>
                    </tr>
                </thead>
            </table>
        </div>
    </td>
</tr>
<tr>
	<td colspan="2">Devoluciones</td>
</tr>
<tr>
	<td colspan="2">
    	<div id="itemContainer">
            <table class="itemListHead" cellpadding="0" cellspacing="10px" width="100%">
                <thead>
                    <tr>
                        <td class="tdFolio">Folio</td>
                        <td class="tdDate">Fecha</td>
                        <td class="tdEmp">Realizó</td>
                        <td class="tdValue">Monto</td>
                        <td class="tdView"></td>
                    </tr>
                </thead>
            </table>
            <table class='itemTable' id='devTable' width='100%' cellpadding='0' cellspacing='10px'>
            	<?php
				$queryDevs = "SELECT T1.ID, T1.code, CONCAT(T2.first, ' ', T2.last) emp, T1.created_at, (SELECT SUM((T3.qty * T4.price)) FROM trln T3 JOIN product T4 ON T3.prodCode = T4.code WHERE T3.tranID = T1.ID) value FROM transfers T1 JOIN crew T2 ON T1.empID = T2.ID WHERE T1.orStore = $store AND T1.dsStore = 100 AND T1.account = 'Y' AND pmntCode = '$code' ORDER BY T1.created_at ASC";
				$resultDevs = $db->query($queryDevs);
				while ($rowDevs = $resultDevs->fetch()) {
					echo "<tr class='item'>
					<td class='tdFolio'>".$rowDevs["code"]."</td><input type='hidden' name='tranCode[]' value='".$rowDevs["code"]."'>
					<td class='tdDate'>".$rowDevs["created_at"]."</td>
					<td class='tdEmp'>".$rowDevs["emp"]."</td>
					<td class='tdValue' align='right'><span style='float:left'>$</span><div class='priceDiv devValue'>".$rowDevs["value"]."</div></td>
					<td class='tdView' align='right'><a href='transferDetail.php?tranID=".$rowDevs["ID"]."'><i class='fa fa-eye' aria-hidden='true'></i></a></td>
				</tr>";
				}
				?>
			</table>
            <table class="itemListHead" cellpadding="0" cellspacing="10px" width="100%">
                <thead>
                    <tr>
                        <td class="tdFolio"></td>
                        <td class="tdDate"></td>
                        <td class="tdEmp" align="right">Total de devoluciones</td>
                        <td class="tdValue" align="right"><span style='float:left'>$</span><div class='devTotal'></div></td>
                        <td class="tdView"></td>
                    </tr>
                </thead>
            </table>
        </div>
    </td>
</tr>
<tr>
    <td colspan="2">
        <div id="itemContainer" style="width:20%; float:right">
            <table class="itemListHead" cellpadding="0" cellspacing="10px" width="100%">
                <thead>
                    <tr>
                        <td class="tdQuant">Saldo Total</td>
                    </tr>
                </thead>
            </table>
            <div class="total"><span style="float:left">$</span><div id="totalMount"></div></div>
        </div>
    </td>
</tr>
<tr>
    <td colspan="2">
        <ul id="buttonBar">
            <li><button type='button' class='formButton blueB' onClick="getback();"><i class='fa fa-hand-o-left' aria-hidden='true'></i> Regresar</button></li>
            <li><a class="formButton blueB" href="pago.php?pmnID=<?php echo $pmnID; ?>" target="_blank"><i class='fa fa-file-pdf-o' aria-hidden='true'></i> PDF</a></li>
        </ul>
    </td>
</tr>
</table>
</div>
</form>

<script>
var calcTotals = function() {
	var tranTotal = 0;
	$(".tranValue").each(function() {
		var value = $(this).text();
		value = value.replace(/,/g, '');
		// add only if the value is number
		if(!isNaN(value) && value.length != 0) {
			tranTotal += parseFloat(value);
		}
		$(".tranTotal").html(localeString(tranTotal.toFixed(2)));
	});
	var devTotal = 0;
	$(".devValue").each(function() {
		var value = $(this).text();
		value = value.replace(/,/g, '');
		// add only if the value is number
		if(!isNaN(value) && value.length != 0) {
			devTotal += parseFloat(value);
		}
		$(".devTotal").html(localeString(devTotal.toFixed(2)));
	});
	var total = tranTotal - devTotal;
	$("#totalMount").html(localeString(total.toFixed(2)));
};

$(document).ready(function() {
	calcTotals();
});

function getback() {
	window.history.back();
}
</script>

<?php
include "footer.php";
?>