<?php
session_start();
include "head.php";

$storeID = $_REQUEST["storeID"];
$by = $_REQUEST["by"];
$term = $_REQUEST["term"];
?>

<div class="sectionTitle">NUEVO PEDIDO</div>

<div class="format">
<form method="post" action="includes/createNewOrder.php">
<table width="100%" border="0" cellspacing="20px" cellpadding="0">
  <tbody>
    <tr>
      <td width="50%">Sal&oacute;n<br>
      	<?php
		$queryStore = "SELECT ID, name FROM STORES WHERE ID = $storeID";
		$resultStore = mysql_query($queryStore);
		$rowStore = mysql_fetch_assoc($resultStore);
		?>
      	<div style="margin-top:10px"><input type="text" value="<?php echo $rowStore['name']; ?>" readonly class='inputText'><input name="storeID" type="hidden" value="<?php echo $rowStore['ID']; ?>"></div>
      </td>
      <td width="50%">
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
                        <td class="tdTrash"></td>
                    </tr>
                </thead>
            </table>
            <!-- Item Lines -->
        </div>
        <button type="button" id="addItemBT" class="formButton greenB">Agregar partida</button></div>
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
      <td colspan="2">Comentarios<br><textarea id="remarks" name="remarks" maxlength="256"></textarea></td>
    </tr>
    <tr>
      <td align="left"><button type="button" class="formButton redB" id="cancelBT">Cancelar</button></td>
      <td align="right"><button type="submit" class="formButton blueB" id="saveButton">Guardar</button></td>
    </tr>
  </tbody>
</table>
</form>
</div>

<script type="text/javascript">
var itemLine = "<div class='item'>\
			<table class='itemTable' width='100%' cellpadding='0' cellspacing='10px'>\
				<tr>\
					<td class='tdQuant'><input type='number' id='quant' name='quant[]' required class='inputText quant' min='1' value='1'></td>\
					<td class='tdCode'><input type='text' class='inputText prodCode' name='prodCode[]' id='prodCode' required></td>\
					<td class='tdProd'>\
						<input id='product' name='product[]' class='inputText itemProduct' required>\
					</td>\
					<td class='tdPrice' align='right'><span style='float:left'>$</span><div class='priceDiv'>0.00</div></td>\
					<td class='tdImport' align='right'><span style='float:left'>$</span><div class='importDiv'>0.00</div></td>\
					<td class='tdTrash' align='right'><i class='fa fa-trash-o remove' aria-hidden='true'></i></td>\
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


// Get Product when Code is inserted

var getProdFromCode = function(ind) {
	if ($(".prodCode:eq("+ind+")").val().length >= 3) {
		var code = $(".prodCode:eq("+ind+")").val();
		if (code != "") {
			$.ajax({
				type: "GET",
				url: "includes/prodDetails.php?by=code&param="+encodeURI(code),
				dataType: "json",
				cache: false,
				success: function(prodName){
					if (prodName["name"] != null && prodName["name"] != "") {
						$(".itemProduct:eq("+ind+")").val(prodName["name"]);
						$(".priceDiv:eq("+ind+")").html(localeString(prodName["price"]));
						var nPrice = 0;
						nPrice = $(".priceDiv:eq("+ind+")").html() * $(".quant:eq("+ind+")").val();
						$(".importDiv:eq("+ind+")").html(localeString(nPrice.toFixed(2)));
						calcTotal();
					}
				}
			});
		}
	}
};

// Get Code when Product is selected

var getCodeFromProd = function(ind) {
	if ($(".itemProduct:eq("+ind+")").val().length >= 3) {
		var name = $(".itemProduct:eq("+ind+")").val();
		if (name != "") {
			$.ajax({
				type: "GET",
				url: "includes/prodDetails.php?by=name&param="+encodeURI(name),
				dataType: "json",
				cache: false,
				success: function(prodCode){
					if (prodCode["code"] != null && prodCode["code"] != "") {
						$(".prodCode:eq("+ind+")").val(prodCode["code"]).trigger("focusout");
					}
				}
			});
		}
	}
};

var addItem = function() {
	$("#itemContainer").append(itemLine);
	$(".prodCode:last-child").focus();
	$("#saveButton").prop("disabled", false);
};

var algo = function(ind) {
	alert(ind);
};

$(document).on("input", ".prodCode", function() {
	var ind = $(".prodCode").index(this);
	$(".prodCode:eq("+ind+")").autocomplete({
		minLength: 3,
		source: "includes/searchProd.php?by=code",
		select: function() {
			getProdFromCode(ind)
		}
	});
	var input = $(this);
	var start = input[0].selectionStart;
	$(this).val(function (_, val) {
		return val.toUpperCase();
	});
	input[0].selectionStart = input[0].selectionEnd = start;
	getProdFromCode(ind);
});

$(document).on("input", ".itemProduct", function() {
	var ind = $(".itemProduct").index(this);
	$(".itemProduct").autocomplete({
		minLength: 3,
        source: "includes/searchProd.php?by=name",
		select: function() {
			getCodeFromProd(ind)
		}
    });
	getCodeFromProd(ind);
});

$(document).on("input", ".quant", function() {
	var ind = $(".quant").index(this);
	var nPrice = 0;
	nPrice = $(".priceDiv:eq("+ind+")").html() * $(".quant:eq("+ind+")").val();
	$(".importDiv:eq("+ind+")").html(localeString(nPrice.toFixed(2)));
	calcTotal();
});

$(document).on("click", ".remove", function() {
	$(this).closest('.item').remove();
	calcTotal();
	if($("#itemContainer").children(".item").length == 0) {
		$("#saveButton").prop("disabled", true);
	}
});

$(document).on("keypress", function(e) {
	if(e.which == 13) {
		e.preventDefault();
		$("#addItemBT").click();
	}
});


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
$queryOrder = "SELECT T1.code, T1.name, T3.name vendor, T1.price, (T2.maxq - T2.qty) orderq FROM PRODUCT T1 JOIN PRDL T2 ON T1.code = T2.prodCode JOIN VENDOR T3 ON T1.vendorID = T3.ID WHERE T1.active = 'Y' AND (T2.maxq - T2.qty) > 0 AND T2.storeID = $storeID";

if ($term != "" && $term != NULL) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	switch ($by) {
		case "CODIGO":
			$queryOrder.= " AND T1.code LIKE '%".$term."%'";
			break;
		case "NOMBRE":
			$queryOrder.= " AND T1.name LIKE '%".$term."%'";
			break;
		case "PROVEEDOR":
			$queryOrder.= " AND T3.name LIKE '%".$term."%'";
			break;
	}
}

$resultOrder = mysql_query($queryOrder);
while ($rowOrder = mysql_fetch_assoc($resultOrder)) {
	$quants[] = utf8_encode($rowOrder["orderq"]);
	$codes[] = utf8_encode($rowOrder["code"]);
	$products[] = utf8_encode($rowOrder["name"]);
	$prices[] = utf8_encode($rowOrder["price"]);
?>
	$("#itemContainer").append(itemLine);
	var jQuants = <?php echo json_encode($quants); ?>;
	var jProdCodes = <?php echo json_encode($codes); ?>;
	var jItemProducts = <?php echo json_encode($products); ?>;
	var jPrices = <?php echo json_encode($prices); ?>;
<?php
}
?>



$("#addItemBT").on('click', addItem);
		
$("#cancelBT").click(function () {
	window.history.back();
});
</script>

<?php
include "footer.php";
?>