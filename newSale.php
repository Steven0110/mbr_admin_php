<?php
session_start();
include "head.php";
?>

<div class="sectionTitle">VENTA MOSTRADOR</div>

<div class="format">
<form method="post" action="includes/createNewSale.php">
<table width="100%" border="0" cellspacing="20px" cellpadding="0">
  <tbody>
    <tr>
      <td colspan="2">Partidas<br>
        <div id="itemContainer">
        	<table class="itemListHead" cellpadding="0" cellspacing="10px" width="100%">
            	<thead>
                	<tr>
                    	<td class="tdQuant">Cantidad</td>
                    	<td class="tdCode">C&oacute;digo</td>
                        <td class="tdProd">Producto</td>
                        <td class="tdPrice">Precio ($)</td>
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
                <div class="total"><span style="float:left">$</span><div id="totalMount">0.00</div></div>
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
					<td class='tdPrice' align='right'><input type='number' id='price' name='price[]' class='inputText price' min='0' step='any' required></td>\
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
		$.ajax({
			type: "GET",
			url: "includes/prodDetails.php?by=code&param="+encodeURI(code)+"&storeID=100",
			dataType: "json",
			cache: false,
			success: function(prodName){
				if (prodName["name"] != null && prodName["name"] != "") {
					$(".itemProduct:eq("+ind+")").val(prodName["name"]);
					$(".price:eq("+ind+")").prop("placeholder", prodName["price"]);
					$(".price:eq("+ind+")").val(prodName["price"]);
					$(".quant:eq("+ind+")").prop("max", prodName["quant"]);
					var nPrice = 0;
					nPrice = $(".price:eq("+ind+")").val() * $(".quant:eq("+ind+")").val();
					$(".importDiv:eq("+ind+")").html(localeString(nPrice.toFixed(2)));
					calcTotal();
				}
			}
		});
	}
};

// Get Code when Product is selected

var getCodeFromProd = function(ind) {
	if ($(".itemProduct:eq("+ind+")").val().length >= 3) {
		var name = $(".itemProduct:eq("+ind+")").val();
		$.ajax({
			type: "GET",
			url: "includes/prodDetails.php?by=name&param="+encodeURI(name)+"&storeID=100",
			dataType: "json",
			cache: false,
			success: function(prodCode){
				if (prodCode["code"] != null && prodCode["code"] != "") {
					$(".prodCode:eq("+ind+")").val(prodCode["code"]).after(function() {
                        getProdFromCode(ind);
                    });
				}
			}
		});
	}
};

var addItem = function() {
	$("#itemContainer").append(itemLine);
	$(".prodCode:last-child").focus();
	$("#saveButton").prop("disabled", false);
};

addItem();

$(document).on("input", ".prodCode", function() {
	var ind = $(".prodCode").index(this);
	$(".prodCode:eq("+ind+")").autocomplete({
		minLength: 3,
		source: "includes/searchProd.php?by=code",
		select: function(event, ui) {
			var origEvent = event;
			while (origEvent.originalEvent !== undefined) {
				origEvent = origEvent.originalEvent;
			}
			if (origEvent.type == "click") {
				$(".prodCode:eq("+ind+")").val(ui.item.value);
			} else {
				$(".prodCode:eq("+ind+")").val(ui.item.value);
			}
			getProdFromCode(ind);
		},
		close: function() {
			getProdFromCode(ind);
		}
	});
	var input = $(this);
	var start = input[0].selectionStart;
	$(this).val(function (_, val) {
		return val.toUpperCase();
	});
	input[0].selectionStart = input[0].selectionEnd = start;
	if ($(".prodCode:eq("+ind+")").val().length >= 3) {
		getProdFromCode(ind);
	}
});

$(document).on("input", ".itemProduct", function() {
	var ind = $(".itemProduct").index(this);
	$(".itemProduct").autocomplete({
		minLength: 3,
        source: "includes/searchProd.php?by=name",
		select: function(event, ui) {
			var origEvent = event;
			while (origEvent.originalEvent !== undefined) {
				origEvent = origEvent.originalEvent;
			}
			if (origEvent.type == "click") {
				$(".itemProduct:eq("+ind+")").val(ui.item.value);
			} else {
				$(".itemProduct:eq("+ind+")").val(ui.item.value);
			}
			getCodeFromProd(ind);
		},
		close: function() {
			getCodeFromProd(ind);
		}
    });
	if ($(".prodCode:eq("+ind+")").val().length >= 3) {
		getCodeFromProd(ind);
	}
});

$(document).on("input", ".quant", function() {
	var ind = $(".quant").index(this);
	var nPrice = 0;
	nPrice = $(".price:eq("+ind+")").val() * $(".quant:eq("+ind+")").val();
	$(".importDiv:eq("+ind+")").html(localeString(nPrice.toFixed(2)));
	calcTotal();
});

$(document).on("input", ".price", function() {
	var ind = $(".price").index(this);
	var nPrice = 0;
	nPrice = $(".price:eq("+ind+")").val() * $(".quant:eq("+ind+")").val();
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

$("#addItemBT").on('click', addItem);
		
$("#cancelBT").click(function () {
	window.history.back();
});
</script>

<?php
include "footer.php";
?>