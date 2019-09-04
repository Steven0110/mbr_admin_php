<?php
include "head.php";

$orderID = $_REQUEST["ordID"];

$queryOrder = "SELECT T1.ID, T1.code, T1.created_at, T1.closed_at, T1.storeID, T3.name store, T1.empID, CONCAT(T2.first, ' ', T2.last) emp, T1.remarks, T1.active FROM orders T1 JOIN crew T2 ON T1.empID = T2.ID JOIN stores T3 ON T1.storeID = T3.ID WHERE T1.ID = $orderID";
$resultOrder = $db->query($queryOrder);
$rowOrder = $resultOrder->fetch();

$folio = $rowOrder["code"];

$queryNext = "SELECT MIN(ID) nextID FROM orders WHERE ID > '$orderID' AND storeID <> 0";
$resultNext = $db->query($queryNext);
$rowNext = $resultNext->fetch();
$nextID = $rowNext["nextID"];

$queryPrev = "SELECT MAX(ID) prevID FROM orders WHERE ID < '$orderID' AND storeID <> 0";
$resultPrev = $db->query($queryPrev);
$rowPrev = $resultPrev->fetch();
$prevID = $rowPrev["prevID"];

$status = "";
switch ($rowOrder["active"]) {
	case "Y":
		$status = "Abierto";
		break;
	case "N":
		$status = "Cerrado";
		break;
	case "C":
		$status = "Cancelado";
		break;
}

// Go to inflow
if ($rowOrder["active"] == "N") {
	if ($rowOrder["storeID"] == 100) {
		$queryRelDoc = "SELECT ID FROM inflows WHERE baseOrd = '$folio'";
		$resultRelDoc = $db->query($queryRelDoc);
		$rowRelDoc = $resultRelDoc->fetch();
		$relDoc = $rowRelDoc["ID"];
	} else {
		$queryRelDoc = "SELECT ID FROM transfers WHERE baseOrd = '$folio'";
		$resultRelDoc = $db->query($queryRelDoc);
		$rowRelDoc = $resultRelDoc->fetch();
		$relDoc = $rowRelDoc["ID"];
	}
}
?>

<div class="overlay">
    <div class="confirmMessage">
            <table width="100%">
                <tr>
                    <td id="question" colspan="2" align="center" style="font-size:18px; color:#c54695">&iquest;Est&aacute; seguro de crear la <span class="mov"></span>?</td>
                </tr>
                <tr>
                    <td colspan="2" id="explain">Si confirma, se crear&aacute; la <span class="mov"></span> solicitada, se cerrar&aacute; el pedido y se afectar&aacute; directamente al inventario.</td>
                </tr>
                <tr>
                    <td align="left"><button type="button" class="formButton redB" onClick="javascript:$(this).closest('.overlay').fadeOut('fast')"><i class='fa fa-times' aria-hidden='true'></i> Cancelar</button></td>
                    <td align="right"><button type="button" class="formButton greenB" id="createRelated"><i class='fa fa-check' aria-hidden='true'></i> Aceptar</button></td>
                </tr>
            </table>
    </div>
</div>



<div class="sectionTitle">
	<a href="viewOrder.php?ordID=<?php echo $prevID; ?>"><i class="fa fa-arrow-left prev" aria-hidden="true"></i></a>
    PEDIDO <?php echo $folio; ?>
    <a href="viewOrder.php?ordID=<?php echo $nextID; ?>"><i class="fa fa-arrow-right next" aria-hidden="true"></i></a>
</div>

<div class="format">
<form method="post" id="orderForm">
<input type="hidden" name="ordCode" value="<?php echo $folio; ?>">
<table width="100%" border="0" cellspacing="20px" cellpadding="0">
  <tbody>
    <tr>
      <td width="50%">Sal&oacute;n<br>
      	<div style="margin-top:10px"><input type="text" value="<?php echo $rowOrder["store"]; ?>" readonly class='inputText'><input name="store" type="hidden" value="<?php echo $rowOrder['storeID']; ?>"></div>
      </td>
      <td width="50%">Elabor&oacute;<br>
      	<div style="margin-top:10px"><input type="text" value="<?php echo $rowOrder["emp"]; ?>" readonly class='inputText'><input name="empID" type="hidden" value="<?php echo $rowOrder['empID']; ?>"></div>
      </td>
    </tr>
    <tr>
      <td width="50%">Creado el:<br>
      	<div style="margin-top:10px"><input type="text" value="<?php echo $rowOrder["created_at"]; ?>" readonly class='inputText'></div>
      </td>
      <td width="50%">Cerrado el:<br>
      	<div style="margin-top:10px"><input type="text" value="<?php echo $rowOrder["closed_at"]; ?>" readonly class='inputText'></div>
      </td>
    </tr>
    <tr>
      <td width="50%">Estatus<br>
      	<div style="margin-top:10px"><input type="text" value="<?php echo $status; ?>" readonly class='inputText'></div>
      </td>
      <td width="50%"><br>
      	
      </td>
    </tr>
    <tr>
      <td colspan="2">Partidas<br>
        <div id="itemContainer">
        	<table class="itemListHead" cellpadding="0" cellspacing="10px" width="100%">
            	<thead>
                	<tr>
                    	<td class="tdQuant">Cantidad</td>
                    	<td class="tdCode">C&oacute;digo</td>
                        <td class="tdProd">Producto</td>
                        <td class="tdPrice">Precio</td>
                        <td class="tdImport">Importe</td>
                    </tr>
                </thead>
            </table>
            <!-- Item Lines -->
        </div>
	  </td>
    </tr>
    <tr>
    	<td colspan="2">
        	<div id="itemContainer" style="width:20%; float:right">
                <table class="itemListHead" cellpadding="0" cellspacing="10px" width="100%">
                    <thead>
                        <tr>
                            <td class="tdQuant">Total</td>
                        </tr>
                    </thead>
                </table>
                <div class="total"><span style="float:left">$</span><div id="totalMount"></div></div>
            </div>
        </td>
    </tr>
    <tr>
      <td colspan="2">Comentarios<br><textarea id="remarks" name="remarks" maxlength="256" readonly><?php echo $rowOrder["remarks"]; ?></textarea></td>
    </tr>
    <tr>
    	<td colspan="2">
        	<ul id="buttonBar">
            	<li><button type='button' class='formButton blueB' onClick="getback();"><i class='fa fa-hand-o-left' aria-hidden='true'></i> Regresar</button></li>
            	<li><button type='button' class='formButton redB' id='cancelBT'><i class='fa fa-ban' aria-hidden='true'></i> Cancelar pedido</button></li>
                <li><button type='button' class='formButton blueB' id='viewPDF'><i class='fa fa-file-pdf-o' aria-hidden='true'></i> PDF</button></li>
			<?php
            if($rowOrder["active"] == "Y") {
				echo "<li><button type='button' class='formButton blueB' id='olCreate'><i class='fa fa-clipboard' aria-hidden='true'></i> Crear <span class='mov'></span></button></li>";
            } else {
				echo "<li><button type='button' class='formButton blueB' id='viewRelated'><i class='fa fa-link' aria-hidden='true'></i> Ir a <span class='mov'></span></button></li>";
            }
            ?>
    		</ul>
        </td>
    </tr>
  </tbody>
</table>
</form>
</div>

<script type="text/javascript">
var itemLine = "<div class='item'>\
			<table class='itemTable' width='100%' cellpadding='0' cellspacing='10px'>\
				<tr>\
					<td class='tdQuant'><input type='number' id='quant' name='quant[]' readonly class='inputText quant' min='1' value='1'></td>\
					<td class='tdCode'><input type='text' class='inputText prodCode' name='prodCode[]' id='prodCode' readonly></td>\
					<td class='tdProd'>\
						<input id='product' name='product[]' class='inputText itemProduct' readonly>\
					</td>\
					<td class='tdPrice' align='right'><span style='float:left'>$</span><div class='priceDiv'>0.00</div></td>\
					<td class='tdImport' align='right'><span style='float:left'>$</span><div class='importDiv'>0.00</div></td>\
				</tr>\
			</table>\
		</div>";

var calcTotal = function () {
	var total = 0;
	$(".importDiv").each(function() {
        total += parseFloat($(this).html().replace(",",""));
    });
	$("#totalMount").html(localeString(total.toFixed(2)));
};

// Get order lines
var getLines = function() {
	var indexes = $("#itemContainer").children(".item").length;
	for (i = 0; i < indexes; i++) {
		$(".quant:eq("+i+")").val(jQuants[i]);
		$(".prodCode:eq("+i+")").val(jProdCodes[i]);
		$(".itemProduct:eq("+i+")").val(jItemProducts[i]);
		$(".priceDiv:eq("+i+")").html(localeString(jPrices[i]));
		var nPrice = 0;
		nPrice = $(".priceDiv:eq("+i+")").html() * $(".quant:eq("+i+")").val();
		$(".importDiv:eq("+i+")").html(localeString(nPrice.toFixed(2)));
	}
}

$(document).ready(function() {
	getLines();
	calcTotal();
});

<?php
if($rowOrder["active"] == "Y") {
?>
$("#remarks").prop("readonly", false);
<?php
}

if($rowOrder["active"] == "C") {
?>
$("#viewPDF").closest("li").remove();
$("#viewRelated").closest("li").remove();
<?php	
}

if ($rowOrder["active"] == "N" || $rowOrder["active"] == "C") {
?>
$("#cancelBT").closest("li").remove();
<?php
}

$queryLines = "SELECT T1.prodCode, T2.name, T2.price, T1.qty FROM ordl T1 JOIN product T2 ON T1.prodCode = T2.code WHERE T1.ordID = $orderID";
$resultLines = $db->query($queryLines);
while ($rowLines = $resultLines->fetch()) {
	$quants[] = utf8_encode($rowLines["qty"]);
	$codes[] = utf8_encode($rowLines["prodCode"]);
	$products[] = utf8_encode($rowLines["name"]);
	$prices[] = utf8_encode($rowLines["price"]);
?>
	$("#itemContainer").append(itemLine);
	var jQuants = <?php echo json_encode($quants); ?>;
	var jProdCodes = <?php echo json_encode($codes); ?>;
	var jItemProducts = <?php echo json_encode($products); ?>;
	var jPrices = <?php echo json_encode($prices); ?>;
<?php
}

if ($rowOrder["storeID"] == 100) {
?>
$(".mov").html("entrada");
$("#orderForm").attr("action", "includes/createNewInflow.php");
var url = "inflowDetail.php?infID=<?php echo $relDoc; ?>";
<?php
} else {
?>
$(".mov").html("transferencia");
$("#orderForm").attr("action", "includes/createNewTransfer.php");
var url = "transferDetail.php?tranID=<?php echo $relDoc; ?>";
<?php
}
?>

$("#cancelBT").click(function () {
	$(".overlay").fadeIn("fast");
	$("#orderForm").attr("action", "includes/cancelOrder.php");
	$("#question").html("&iquest;Est&aacute; seguro de cancelar el pedido?");
	$("#explain").html("Si confirma, se cancelar&aacute; el pedido, esta aci&oacute;n no se puede deshacer.");
});

$("#viewPDF").click(function () {
	window.open("pedido.php?ordID=<?php echo $orderID; ?>");
});

$("#olCreate").click(function () {
	$(".overlay").fadeIn("fast");
});

$("#createRelated").click(function () {
	//$("#orderForm").submit();
	
	let form = $("#orderForm")
	console.log( form.serializeArray() )
	let store = form.serializeArray()[0].value
	let body = {
		"store": "100",
		"products": [],
		"quantities": []
	}

	let formProducts = $(".itemTable tr .prodCode")
	for( let i = 0 ; i < formProducts.length ; i++ ){
		let line = $(formProducts[ i ])
		body.products.push( line.val() )
	}
	let formQuantities = $(".itemTable tr .quant")
	for( let i = 0 ; i < formQuantities.length ; i++ ){
		let q = $(formQuantities[ i ])
		body.quantities.push( q.val() )
	}
	console.log(body)
	$.ajax({
		"method": "POST",
		"data": JSON.stringify(body),
		"url": "includes/checkStock.php",
		"contentType": "application/json",
		"success": function(response){
			console.log(response)
			let json = JSON.parse(response)
			if(json.code == 0){
				alert("No puedes generar esta entrada. El producto " + json.prodID + " tiene stock = " + json.stock)
			}else if(json.code == -1){
				console.log(json)
			}else{
				form.submit()
			}
		}   
	})
});

$("#viewRelated").click(function () {
	window.location.href = url;
});
function getback() {
	window.history.back();
}
</script>

<?php
include "footer.php";
