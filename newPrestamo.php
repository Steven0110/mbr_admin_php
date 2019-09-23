<?php include 'head.php'; 
$fecha =  date('Y-m-d');;
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/js/select2.min.js"></script>

<div class="sectionTitle">REGISTRAR PRESTAMO</div>

<div class="format">
<form method="post" action="includes/createNewPrestamo.php" id="prestamoform">
<table width="100%" border="0" cellspacing="20px" cellpadding="0">
  <tbody>
    <tr>
      <td width="50%">Almacén<br>
      	<div style="margin-top:10px">
        	<select id="store" name="store" style="margin-top:10px;" class="store" required>
            <option value="" selected disabled>Selecciona...</option>
        	<?php
			$myQuery = $db->query("SELECT ID, CONCAT(name, ' (', ID, ')') name FROM stores");
			while($row = $myQuery->fetch()){
				echo "<option value='".$row["ID"]."'>".$row["name"]."</option>";
			};
			?>
        </select></div>
      </td>
    </tr>
    <tr>
    	<td width="50%">Proyecto<br>
      	<div style="margin-top:10px">
        	<select id="proyect" name="proyect" style="margin-top:10px;" required>
            <option value="" selected disabled>Selecciona...</option>
        	<?php
			$myQuery = $db->query("SELECT ID, CONCAT(proyectname, ' (', ID, ')') name FROM proyects");
			while($row = $myQuery->fetch()){
				echo "<option value='".$row["ID"]."'>".$row["name"]."</option>";
			};
			?>
        </select></div>
      </td>
      <td width="50%">Empleados<br>
		<div style="margin-top:10px">
        	<select id="emp" name="emp" style="margin-top:10px;" required>
            <option value="" selected disabled>Selecciona...</option>
        	<?php
			$myQuery = $db->query("SELECT ID_EMPLEADO,NUMERO, CONCAT(NOMBRE, ' (',NUMERO,')') NOMBRE FROM empleados");
			while($row = $myQuery->fetch()){
				echo "<option value='".$row["ID_EMPLEADO"]."'>".$row["NOMBRE"]."</option>";
			};
			?>
        </select></div>
        </td>
    </tr>
    <tr>
        <td width="50%">Fecha de inicio<br>
            <div style="margin-top:10px"><input type="date" disabled id="fchinicio" name="fchinicio" class="inputText" 
            	value="<?php echo $fecha; ?>" required></div>
        </td>
        <td width="50%">Fecha de termino<br>
            <div style="margin-top:10px"><input type="date" id="fchterm" name="fchterm" class="inputText" required></div>
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
      <td align="left"><button type="button" class="formButton redB" onClick="cancel();">Cancelar</button></td>
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
					<td class='tdQuant'><input type='number' id='quant' name='quant[]' required class='inputText quant' min='0' value='1'></td>\
					<td class='tdCode'><input type='text' class='inputText prodCode' name='prodCode[]' id='prodCode' required></td>\
					<td class='tdProd'>\
						<input id='product' name='product[]' class='inputText itemProduct' required>\
					</td>\
					<td class='tdPrice' align='right'><span style='float:left'>$</span><div class='priceDiv'>0.00</div></td>\
					<td class='tdImport' align='right'><span style='float:left'>$</span><div class='importDiv'>0.00</div></td>\
					<td class='tdExistq' <input type='number' id='existq' name='existq[]' required class='inputText existq' </td>\
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
		var storeID = $("#store").val();
		$.ajax({
			type: "GET",
			url: "includes/prodDetails.php?by=code&param="+encodeURI(code)+"&storeID="+storeID,
			dataType: "json",
			cache: false,
			success: function(prodName){
				if (prodName["name"] != null && prodName["name"] != "") {
					$(".itemProduct:eq("+ind+")").val(prodName["name"]);
					$(".priceDiv:eq("+ind+")").html(localeString(prodName["price"]));
					var nPrice = 0;
					nPrice = parseFloat($(".priceDiv:eq("+ind+")").html().replace(",","")) * $(".quant:eq("+ind+")").val();
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
		var storeID = $("#store").val();
		$.ajax({
			type: "GET",
			url: "includes/prodDetails.php?by=name&param="+encodeURI(name)+"&storeID="+storeID,
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
	nPrice = parseFloat($(".priceDiv:eq("+ind+")").html().replace(",","")) * $(".quant:eq("+ind+")").val();
	$(".importDiv:eq("+ind+")").html(localeString(nPrice.toFixed(2)));
	calcTotal();
});

$(document).on('click', '.remove', function() {
	$(this).closest('.item').remove();
	if($("#itemContainer").children(".item").length == 0) {
		$("#saveButton").prop("disabled", true);
	}
});

$(document).on('keypress', function(e) {
	if(e.which == 13) {
		e.preventDefault();
		$("#addItemBT").click();
	}
});

$(document).ready(function() {
	<?php
	if ($_SESSION["role"] == 'Y') {
		echo "$('#store').select2();";
		echo "$('#proyect').select2();";
		echo "$('#emp').select2();";
	}
	?>
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
		nPrice = parseFloat($(".priceDiv:eq("+i+")").html().replace(",","")) * $(".quant:eq("+i+")").val();
		$(".importDiv:eq("+i+")").html(localeString(nPrice.toFixed(2)));
	    
	}
}



$(document).ready(function() {
	getLines();
	calcTotal();
});




$("#addItemBT").on('click', addItem);
		
function cancel() {
	window.location.href = 'prestamos.php';
}

//check stock from specific store
$("#prestamoform").submit(function(e){
	e.preventDefault()
	let form = $(e.target)
	console.log( form.serializeArray() )
	let store = form.serializeArray()[0].value
	let body = {
		"store": store,
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
				alert("No puedes generar este prestamo. El producto " + json.prodID + " tiene stock = " + json.stock)
			}else if(json.code == -1){
				console.log(json)
			}else{
				form.off("submit")
				form.submit()
			}
		}   
	})
	return false;

})
</script>
    
<?php include 'footer.php'; ?>