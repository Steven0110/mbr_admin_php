<?php
include "head.php";

$store = $_GET["store"];
$fromDate = $_GET["fromDate"];
$toDate = $_GET["toDate"];
$fromDateQ = date('Y-m-d 00:00:00', strtotime(str_replace('/', '-', $_GET["fromDate"])));
$toDateQ = date('Y-m-d 23:59:59', strtotime(str_replace('/', '-', $_GET["toDate"])));

$queryStore = "SELECT name FROM STORES WHERE ID = $store";
$resultStore = mysql_query($queryStore);
$rowStore = mysql_fetch_assoc($resultStore);
$storeName = $rowStore["name"];
?>
<form action="includes/createNewPayment.php" method="post" id="pmntForm">
<div class="sectionTitle">
    ESTADO DE CUENTA <?php echo $storeName; ?><input type="hidden" name="storeID" value="<?php echo $store; ?>">
</div>

<div class="format">
<table width="100%" border="0" cellspacing="20px" cellpadding="0">
<tr>
	<td width="50%">Desde el:</td>
    <td>Hasta el:</td>
</tr>
<tr>
	<td><input type="text" class="inputText" readonly name="fromDate" value="<?php echo $fromDate; ?>"></td>
    <td><input type="text" class="inputText" readonly name="toDate" value="<?php echo $toDate; ?>"></td>
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
                        <td class="tdView"></td>
                    </tr>
                </thead>
            </table>
            <table class='itemTable' id='trnTable' width='100%' cellpadding='0' cellspacing='10px'>
            	<?php
				$queryTransfers = "SELECT T1.ID, T1.code, CONCAT(T2.first, ' ', T2.last) emp, T1.created_at, (SELECT SUM((T3.qty * T4.price)) FROM TRLN T3 JOIN PRODUCT T4 ON T3.prodCode = T4.code WHERE T3.tranID = T1.ID) value FROM TRANSFERS T1 JOIN CREW T2 ON T1.empID = T2.ID WHERE T1.orStore = 100 AND T1.dsStore = $store AND T1.created_at >= '$fromDateQ' AND T1.created_at <= '$toDateQ' AND T1.account = 'N' ORDER BY T1.created_at ASC";
				$resultTransfers = mysql_query($queryTransfers);
				while ($rowTransfers = mysql_fetch_assoc($resultTransfers)) {
					echo "<tr class='item'>
					<td class='tdFolio'>".$rowTransfers["code"]."</td><input type='hidden' name='tranCode[]' value='".$rowTransfers["code"]."'>
					<td class='tdDate'>".$rowTransfers["created_at"]."</td>
					<td class='tdEmp'>".$rowTransfers["emp"]."</td>
					<td class='tdValue' align='right'><span style='float:left'>$</span><div class='priceDiv tranValue'>".$rowTransfers["value"]."</div></td>
					<td class='tdView' align='right'><a href='transferDetail.php?tranID=".$rowTransfers["ID"]."'><i class='fa fa-eye' aria-hidden='true'></i></a></td>
					<td class='tdView'><i class='fa fa-trash-o remove' aria-hidden='true'></i></td>
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
                        <td class="tdValue" align="right"><span style='float:left'>$</span><div class='tranTotal'>0.00</div></td>
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
                        <td class="tdView"></td>
                    </tr>
                </thead>
            </table>
            <table class='itemTable' id='devTable' width='100%' cellpadding='0' cellspacing='10px'>
            	<?php
				$queryDevs = "SELECT T1.ID, T1.code, CONCAT(T2.first, ' ', T2.last) emp, T1.created_at, (SELECT SUM((T3.qty * T4.price)) FROM TRLN T3 JOIN PRODUCT T4 ON T3.prodCode = T4.code WHERE T3.tranID = T1.ID) value FROM TRANSFERS T1 JOIN CREW T2 ON T1.empID = T2.ID WHERE T1.orStore = $store AND T1.dsStore = 100 AND T1.created_at >= '$fromDateQ' AND T1.created_at <= '$toDateQ' AND T1.account = 'N' ORDER BY T1.created_at ASC";
				$resultDevs = mysql_query($queryDevs);
				while ($rowDevs = mysql_fetch_assoc($resultDevs)) {
					echo "<tr class='item'>
					<td class='tdFolio'>".$rowDevs["code"]."</td><input type='hidden' name='tranCode[]' value='".$rowDevs["code"]."'>
					<td class='tdDate'>".$rowDevs["created_at"]."</td>
					<td class='tdEmp'>".$rowDevs["emp"]."</td>
					<td class='tdValue' align='right'><span style='float:left'>$</span><div class='priceDiv devValue'>".$rowDevs["value"]."</div></td>
					<td class='tdView' align='right'><a href='transferDetail.php?tranID=".$rowDevs["ID"]."'><i class='fa fa-eye' aria-hidden='true'></i></a></td>
					<td class='tdView'><i class='fa fa-trash-o remove' aria-hidden='true'></i></td>
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
                        <td class="tdValue" align="right"><span style='float:left'>$</span><div class='devTotal'>0.00</div></td>
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
            <li><button type='submit' class='formButton blueB' id="savePmnt"><i class='fa fa-money' aria-hidden='true'></i> Generar cobro</button></li>
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
	if($("#devTable .item").length == 0 && $("#trnTable .item").length == 0) {
		$("#savePmnt").prop("disabled", true);
	}
	calcTotals();
});

$(document).on('click', '.remove', function() {
	$(this).closest('.item').remove();
	calcTotals();
	if($("#devTable .item").length == 0 && $("#trnTable .item").length == 0) {
		$("#savePmnt").prop("disabled", true);
	}
});

function getback() {
	window.history.back();
}
</script>

<?php
include "footer.php";
?>