<?php include 'head.php'; ?>

<div class="sectionTitle">REGISTRAR TRANSFERENCIA</div>

<div class="format">
<form method="post" action="includes/createNewTransfer.php" id="newTransfer">
<table width="100%" border="0" cellspacing="20px" cellpadding="0">
  <tbody>
    <tr>
      <td width="50%">Almacén origen<br>
      	<div style="margin-top:10px">
        	<select id="orStore" name="orStore" style="margin-top:10px;" required>
                <option value="-1" selected disabled>Selecciona...</option>
                <?php
                $myQuery = $db->query("SELECT ID, CONCAT(name, ' (', ID, ')') name FROM STORES");
                while($row = $myQuery->fetch()){
                	echo "<option value='".$row["ID"]."'>".$row["name"]."</option>";
                };
                ?>
        	</select></div>
      </td>
      <td width="50%">Almacén destino<br>
      	<div style="margin-top:10px">
        	<select id="dsStore" name="dsStore" style="margin-top:10px;" required>
            	<option value="" selected disabled>Primero selecciona origen</option>
            </select></div>
        </td>
    </tr>
    <tr>
      <td colspan="2">Partidas<br>
        <div id="itemContainer">
        	<table class="itemListHead" cellpadding="0" cellspacing="10px" width="100%">
            	<thead>
                	<tr>
                    	<td width="76px">Cantidad</td>
                    	<td width="250px">Código</td>
                        <td>Producto</td>
                        <td width="30px"></td>
                    </tr>
                </thead>
            </table>
            <!-- Item Lines -->
        </div>
        <button type="button" id="addItemBT" class="formButton greenB">Agregar partida</button></div>
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


$("#newTransfer").submit(function(e){
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
				alert("No puedes generar esta entrada. El producto " + json.prodID + " tiene stock = " + json.stock)
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


var itemLine = "<div class='item'>\
			<table class='itemTable' width='100%' cellpadding='0' cellspacing='10px'>\
				<tr>\
					<td width='76px'><input type='number' id='quant' name='quant[]' required class='inputText quant' style='width:70px !important' min='1' value='1'></td>\
					<td width='250px'><input type='text' class='inputText prodCode' name='prodCode[]' id='prodCode' required></td>\
					<td>\
						<input id='product' name='product[]' class='inputText itemProduct' required>\
					</td>\
					<td width='30px'><i class='fa fa-trash-o remove' aria-hidden='true'></i></td>\
				</tr>\
			</table>\
		</div>";

// Get Product when Code is inserted

var getProdFromCode = function(ind) {
	var code = $(".prodCode:eq("+ind+")").val();
	var quantity = $(".quant:eq("+ind+")").val();
	var storeID = $("#orStore").val();
	if(storeID != null){
		if (code.length >= 3) {
			$.ajax({
				type: "GET",
				url: "includes/prodDetails.php?by=code&param="+encodeURI(code)+"&storeID="+storeID,
				dataType: "json",
				cache: false,
				success: function(prodName){
					console.log((Number(prodName["quant"]) - Number(quantity)));
					//if(prodName["quant"] != 0){
						if(Number(prodName["quant"]) - Number(quantity) < 0){
							alert("Advertencia. No hay suficientes existencias en el almacen para satisfacer la transferencia: Stock en almacen: " + prodName["quant"] + ", Stock minimo requerido: " + quantity);
						}else{
							if (prodName["name"] != null && prodName["name"] != "") {
								$(".itemProduct:eq("+ind+")").val(prodName["name"]);
								$(".quant:eq("+ind+")").prop("max", prodName["quant"]);
							}
						}
					//}
				}
			});
		}
	}else{
		alert("Por favor, escoge primero un almacen de origen");
	}
};


// Get Code when Product is selected

var getCodeFromProd = function(ind) {
	var name = $(".itemProduct:eq("+ind+")").val();
	var quantity = $(".itemProduct:eq("+ind+")").val();
	var storeID = $("#orStore").val();
	//console.log(storeID);
	if(storeID != null){

		if (name.length >= 3) {
			$.ajax({
				type: "GET",
				url: "includes/prodDetails.php",
				data: {
					'by': 'name',
					'param': encodeURI(name),
					'storeID': storeID
				},
				dataType: "json",
				cache: false,
				success: function(prodCode){
					if(prodCode["quant"] - quantity < 0){
						//alert("No hay suficientes existencias en el almacén para satisfacer la transferencia");
					}
					if (prodCode["code"] != null && prodCode["code"] != "") {
						$(".prodCode:eq("+ind+")").val(prodCode["code"]).after(function() {
	                        getProdFromCode(ind);
	                    });
					}
				}
			});
		}
	}else{
		//alert("Por favor, escoge un almacén de origen");
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
			//getProdFromCode(ind);
		}
	});
	var input = $(this);
	var start = input[0].selectionStart;
	$(this).val(function (_, val) {
		return val.toUpperCase();
	});
	input[0].selectionStart = input[0].selectionEnd = start;
	if ($(".prodCode:eq("+ind+")").val().length >= 3) {
		//getProdFromCode(ind);
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

var stores = "<?php
	//echo "<option value=''>Selecciona...</option>";
	$myQuery = $db->query("SELECT ID, CONCAT(name, ' (', ID, ')') name FROM STORES WHERE ID = 100");
	while($row = $myQuery->fetch()){
		echo "<option value='".$row["ID"]."'>".$row["name"]."</option>";
	};
	?>";
//console.log(stores);
$(document).on('change', '#orStore', function() {
	orStore = $(this).val();
	if (orStore == 100) {
		$("#dsStore").empty().append(stores);
	} else {
		$("#dsStore").empty().append("<option value='100' selected>Principal (100)</option>");
	}
});

$(document).ready(function() {
	$(document).on('change', '.quant', function(e){
		var ind = $(".prodCode").index(this);
		console.log(ind);
		console.log(e.currentTarget);
		getProdFromCode(ind);
	});
});


$("#addItemBT").on('click', addItem);
		
function cancel() {
	window.location.href = 'transfers.php';
}
</script>
    
<?php include 'footer.php'; ?>