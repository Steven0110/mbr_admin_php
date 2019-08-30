<?php

include 'head.php';

$salID = $_REQUEST["salID"];

$myQuery = $db->query("SELECT T1.ID, T1.code, T2.name store, CONCAT(T3.first, ' ', T3.last) emp, DATE_FORMAT(T1.created_at, '%d-%m-%Y %T') created, T1.remarks FROM sales T1 INNER JOIN stores T2 ON T1.storeID = T2.ID INNER JOIN crew T3 ON T1.empID = T3.ID WHERE T1.ID = '$salID'");
$row = $myQuery->fetch();
$remarks = $row["remarks"];
$code = $row["code"];

$queryNext = "SELECT MIN(ID) nextID FROM sales WHERE ID > '$salID'";
$resultNext = $db->query($queryNext);
$rowNext = $resultNext->fetch();
$nextID = $rowNext["nextID"];

$queryPrev = "SELECT MAX(ID) prevID FROM sales WHERE ID < '$salID'";
$resultPrev = $db->query($queryPrev);
$rowPrev = $resultPrev->fetch();
$prevID = $rowPrev["prevID"];
?>

<div class="sectionTitle">
    <a href="saleDetail.php?salID=<?php echo $prevID; ?>"><i class="fa fa-arrow-left prev" aria-hidden="true"></i></a>
    VENTA <?php echo $code; ?>
    <a href="saleDetail.php?salID=<?php echo $nextID; ?>"><i class="fa fa-arrow-right next" aria-hidden="true"></i></a>
</div>

<div class="format">
<form>
<table width="100%" border="0" cellspacing="20px" cellpadding="0">
  <tbody>
    <tr>
      <td width="50%">Almacén<br>
      	<div style="margin-top:10px"><input type="text" value="<?php echo $row['store']; ?>" disabled class='inputText'></div>
      </td>
      <td width="50%">Empleado<br>
        <div style="margin-top:10px"><input type="text" value="<?php echo $row['emp']; ?>" disabled class='inputText'></div>
        </td>
    </tr>
    <tr>
    	<td>Fecha de venta<br><div style="margin-top:10px"><input type="text" value="<?php echo $row['created']; ?>" disabled class='inputText'></div></td>
        <td></td>
    </tr>
    <tr>
      <td colspan="2">Partidas<br>
        <div id="itemContainer">
        	<div class='itemListHead'>
				<table class='itemTable' width='100%' cellpadding='0' cellspacing='10px'>
					<tr>
                    	<td class="tdQuant">Cantidad</td>
                    	<td class="tdCode">C&oacute;digo</td>
                        <td class="tdProd">Producto</td>
                        <td class="tdPrice">Precio ($)</td>
                        <td class="tdImport">Importe</td>
                    </tr>
				</table>
			</div>
        	<?php
        	$myQuery = $db->query("SELECT T2.ID, T2.code, T2.name, T1.qty, T1.price FROM salln T1 JOIN product T2 ON T1.prodCode = T2.code WHERE T1.salID = '$salID'");
			
			while($row = $myQuery->fetch()){
				$nPrice = $row["qty"] * $row["price"];		
				echo "
					<div class='item'>
						<table class='itemTable' width='100%' cellpadding='0' cellspacing='10px'>
							<tr>
								<td class='tdQuant'><input type='text' value='".$row["qty"]."' disabled class='inputText'></td>
								<td class='tdCode'><input type='text' value='".$row["code"]."' disabled class='inputText'></td>
								<td class='tdProd'><input type='text' value='".$row["name"]."' disabled class='inputText'></td>
								<td class='tdPrice' align='right'><span style='float:left'>$</span><div class='priceDiv'>".$row["price"]."</div></td>
								<td class='tdImport' align='right'><span style='float:left'>$</span><div class='importDiv'>".number_format($nPrice, 2, ".", ",")."</div></td>
							</tr>
						</table>
					</div>
				";
			};
			?>
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
                <div class="total"><span style="float:left">$</span><div id="totalMount">0.00</div></div>
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
                <li><a class="formButton blueB" href="venta.php?salID=<?php echo $salID; ?>" target="_blank"><i class='fa fa-file-pdf-o' aria-hidden='true'></i> PDF</a></li>
    		</ul>
        </td>
    </tr>
  </tbody>
</table>
</form>
</div>
<script>
var calcTotal = function () {
	var total = 0;
	$(".importDiv").each(function() {
        total += parseFloat($(this).html().replace(",",""));
    });
	$("#totalMount").html(localeString(total.toFixed(2)));
};

$(document).ready(function() {
	calcTotal();
});

function getback() {
	window.history.back();
}
</script>

    
<?php include 'footer.php'; ?>