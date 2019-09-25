<?php include 'head.php';

$infID = $_REQUEST["infID"];

$myQuery = $db->query("SELECT t1.ID_PRESTAMO prestamo, t4.proyectname proyect, t2.NOMBRE emp, t1.ID_HERRAMIENTA, t3.name store, t1.ID_STORE storeID, t1.CREATED_AT created,t1.END_DATE finish,t1.CLOSED_AT closed, t1.REMARKS remarks, t1.STATUS FROM prestamos t1 INNER JOIN empleados t2 ON t1.ID_EMPLEADO = t2.ID_EMPLEADO INNER JOIN stores t3 ON t1.ID_STORE = t3.ID INNER JOIN proyects t4 ON t1.ID_PROYECTO = t4.ID WHERE t1.ID_PRESTAMO = '$infID'");
$row = $myQuery->fetch();
$remarks = $row["remarks"];
$folio = $row["created"]."  ID= ".$row["prestamo"];
$storeID = $row["storeID"];

$queryNext = "SELECT MIN(ID_PRESTAMO) nextID FROM prestamos WHERE ID_PRESTAMO > '$infID' AND ID_STORE <> 0";
$resultNext = $db->query($queryNext);
$rowNext = $resultNext->fetch();
$nextID = $rowNext["nextID"];

$queryPrev = "SELECT MAX(ID_PRESTAMO) prevID FROM prestamos WHERE ID_PRESTAMO < '$infID' AND ID_STORE <> 0";
$resultPrev = $db->query($queryPrev);
$rowPrev = $resultPrev->fetch();
$prevID = $rowPrev["prevID"];
/*-------------PARA actualizar registros de inflows
$queryprueba = "SELECT MAX(ID) max FROM INFLOWS";
$RESULTADO = $db->query($queryprueba);
$rowprueba = $RESULTADO->fetch();

for ($i = 0; $i<$rowprueba["max"];$i++)
{
  $queryprueba = "UPDATE INFLOWS SET employee = '6c87e7f9-96f7-4cf2-a08d-331c026d406d' WHERE ID = '$i' ";
  $RESULTADO = $db->query($queryprueba);
}
*/
?>

<div class="sectionTitle">
    <a href="prestamoDetail.php?infID=<?php echo $prevID; ?>"><i class="fa fa-arrow-left prev" aria-hidden="true"></i></a>
    PRESTAMO <?php echo $folio; ?>
    <a href="prestamoDetail.php?infID=<?php echo $nextID; ?>"><i class="fa fa-arrow-right next" aria-hidden="true"></i></a>
</div>

<div class="format">
<form>
<table width="100%" border="0" cellspacing="20px" cellpadding="0">
  <tbody>
    <tr>
      <td width="50%">Almacén<br>
      	<div style="margin-top:10px"><input type="text" value="<?php echo $row['store']; ?>" disabled class='inputText'></div>
      </td>
    </tr>
    <tr>
      <td width="50%">Proyecto<br>
        <div style="margin-top:10px"><input type="text" value="<?php echo $row['proyect']; ?>" disabled class='inputText'></div>
        </td>
      <td width="50%">Creado Por<br>
        <div style="margin-top:10px"><input type="text" value="<?php echo $row['emp']; ?>" disabled class='inputText'></div>
        </td>
    </tr>
    <tr>
    	<td>Fecha de creación<br><div style="margin-top:10px"><input type="date" value="<?php echo $row['created']; ?>" disabled class='inputText'></div></td>
        <td>Empleado a quien se prestó<br>
          <div style="margin-top:10px"><input type="text" value="<?php echo $row['emp']; ?>" disabled class='inputText'></div>
        </td>
    </tr>
    <tr>
      <td width="50%">Fecha Limite de Entrega<br>>
        <div style="margin-top:10px"><input type="date" value="<?php echo $row['finish']; ?>" disabled class="inputText" required></div>
      </td>
      <td width="50%">Fecha de termino<br>
            <div style="margin-top:10px"><input type="date" value="<?php echo $row['closed']; ?>" disabled class="inputText" required></div>
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
      <td colspan="2">Comentarios<br><textarea disabled><?php echo $remarks; ?></textarea></td>
    </tr>
    <tr>
    	<td colspan="2">
        	<ul id="buttonBar">
            	<li><button type='button' class='formButton blueB' onClick="getback();"><i class='fa fa-hand-o-left' aria-hidden='true'></i> Regresar</button></li>
              <li><a href="includes/toolRtn.php?infID=<?php echo $infID; ?>&storeID=<?php echo $storeID; ?>" class="formButton blueB"><i class='fa ' aria-hidden='true'></i> Cerrar Prestamo</a></li>
                <li><a class="formButton blueB" href="prestamoPDF.php?infID=<?php echo $infID; ?>" target="_blank"><i class='fa fa-file-pdf-o' aria-hidden='true'></i> PDF</a></li>
    		  </ul>
      </td>
    </tr>
  </tbody>
</table>
</form>
</div>
<script>
function getback() {
	window.history.back();
}

var itemLine = "<div class='item'>\
      <table class='itemTable' width='100%' cellpadding='0' cellspacing='10px'>\
        <tr>\
          <td class='tdQuant'><input type='number' id='quant' name='quant[]' readonly required class='inputText quant' min='0' value='0'></td>\
          <td class='tdCode'><input type='text' class='inputText prodCode' name='prodCode[]' id='prodCode' readonly required></td>\
          <td class='tdProd'>\
            <input id='product' name='product[]' class='inputText itemProduct' readonly required>\
          </td>\
          <td class='tdPrice' align='right'><span style='float:left'>$</span><div class='priceDiv'>0.00</div></td>\
          <td class='tdImport' align='right'><span style='float:left'>$</span><div class='importDiv'>0.00</div></td>\
          <td class='tdExistq' <input type='number' id='existq' name='existq[]' required class='inputText existq' </td>\
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
    var storeID = $("#storeID").val();
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
    var storeID = $("#storeID").val();
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
    nPrice = parseFloat($(".priceDiv:eq("+i+")").html().replace(",","")) * $(".quant:eq("+i+")").val();
    $(".importDiv:eq("+i+")").html(localeString(nPrice.toFixed(2)));
      
  }
}

$(document).ready(function() {
  getLines();
  calcTotal();
});

<?php

$queryOrder = "SELECT t1.qty qty,t2.code code, t2.name name, t2.price price FROM prestamos t1 INNER JOIN product t2 on t1.ID_HERRAMIENTA = t2.code WHERE t1.ID_PRESTAMO = '$infID'";

$resultOrder = $db->query($queryOrder);
while ($rowOrder = $resultOrder->fetch()) {
    /*if(intval( $rowOrder["orderq"] ) <= 0)
        continue;*/
  $quants[] = utf8_encode($rowOrder["qty"]);
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
    
<?php include 'footer.php'; ?>