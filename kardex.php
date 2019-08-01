<?php
include 'head.php';

$prodCode = $_REQUEST["prodCode"];
$storeID = $_REQUEST["storeID"];
$prodID = $_REQUEST["prodID"];

$queryInv = "SELECT T1.qty, T2.code, T2.name, T3.name store FROM PRDL T1 JOIN PRODUCT T2 ON T1.prodCode = T2.code JOIN STORES T3 ON T1.storeID = T3.ID WHERE T1.prodCode = '$prodCode' AND T1.storeID = $storeID";
$resultInv = mysql_query($queryInv);
$rowInv = mysql_fetch_assoc($resultInv);
$quantInv = $rowInv["qty"];
$store = $rowInv["store"];
$product = $rowInv["name"];

$queryNext = "SELECT ID, code FROM PRODUCT WHERE ID = (SELECT MIN(ID) FROM PRODUCT WHERE ID > '$prodID')";
$resultNext = mysql_query($queryNext);
$rowNext = mysql_fetch_assoc($resultNext);
$nextID = $rowNext["ID"];
$nextCode = $rowNext["code"];

$queryPrev = "SELECT ID, code FROM PRODUCT WHERE ID = (SELECT MAX(ID) FROM PRODUCT WHERE ID < '$prodID')";
$resultPrev = mysql_query($queryPrev);
$rowPrev = mysql_fetch_assoc($resultPrev);
$prevID = $rowPrev["ID"];
$prevCode = $rowPrev["code"];

?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/js/select2.min.js"></script>

<div class="sectionTitle">KARDEX</div>

<form onSubmit="this.firstname.required = true;  this.lastname.required = true;  this.phone.optional = true;  this.zip.min = 0;  this.zip.max = 99999;  return verify(this);">
</form>

<div class="format" style="margin-top:20px">
<table width="100%" border="0" cellspacing="20px" cellpadding="0">
	<tr>
    	<td></td>
    	<td>Código</td>
        <td>Artículo</td>
        <td></td>
        <td width="350px">Almacén</td>
    </tr>
    <tr>
    	<td style="font-size:18px"><a href="kardex.php?prodCode=<?php echo $prevCode; ?>&storeID=<?php echo $storeID; ?>&prodID=<?php echo $prevID; ?>"><i class="fa fa-arrow-left prev" aria-hidden="true"></i></a></td>
        <td><input type="text" id="prodCode" name="prodCode" value="<?php echo $prodCode; ?>" class="inputText" readonly></td>
        <td><input type="text" id="prodName" name="prodName" value="<?php echo $product; ?>" class="inputText" readonly></td>
        <td style="font-size:18px"><a href="kardex.php?prodCode=<?php echo $nextCode; ?>&storeID=<?php echo $storeID; ?>&prodID=<?php echo $nextID; ?>"><i class="fa fa-arrow-right next" aria-hidden="true"></i></a></td>
        <td width="350px"><select id="store" name="store" style="margin-top:10px;" required>
            <option value="" selected disabled>Selecciona...</option>
        	<?php
			$myQuery = mysql_query("SELECT ID, CONCAT(name, ' (', ID, ')') name FROM STORES");
			while($row = mysql_fetch_array($myQuery)){
				echo "<option value='".$row["ID"]."'>".$row["name"]."</option>";
			};
			?>
        </select>
    </tr>
</table>
</div>

<table id="priceList" width="100%" border="0" cellspacing="0" cellpadding="0" class="dataTable">
  <thead>
    <tr>
      <td>Fecha</td>
      <td>Movimiento</td>
      <td>Folio</td>
      <td>Realizado por</td>
      <td>Entra</td>
      <td>Sale</td>
      <td>Existencia</td>
      <td>Ver</td>
    </tr>
  </thead>
  <tbody>
    <?php
		$quant = 0;
		echo "
			<tr>
			  <td>-</td>
			  <td>EXISTENCIA INICIAL</td>
			  <td></td>
			  <td></td>
			  <td></td>
			  <td></td>
			  <td>".$quant."</td>
			  <td></td>
			</tr>
		";
		$myQuery = "SELECT TT1.created_at, TT1.type, TT1.code, TT1.ID, TT1.name, TT1.qtyIn, TT1.qtyOut FROM (
SELECT T2.created_at, 'ENTRADA' type, T2.code, T2.ID, CONCAT(T3.first, ' ', T3.last) name, T1.qty qtyIn, 0 qtyOut  FROM INLN T1 JOIN INFLOWS T2 ON T1.infID = T2.ID JOIN CREW T3 ON T2.empID = T3.ID WHERE T1.prodCode = '".$prodCode."' AND T2.storeID = ".$storeID." UNION ALL
SELECT T5.created_at, 'SALIDA' type, T5.code, T5.ID, CONCAT(T6.first, ' ', T6.last) name, 0 qtyIn, T4.qty qtyOut  FROM OUTLN T4 JOIN OUTFLOWS T5 ON T4.outID = T5.ID JOIN CREW T6 ON T5.empID = T6.ID WHERE T4.prodCode = '".$prodCode."' AND T5.storeID = ".$storeID." UNION ALL
SELECT T8.created_at, 'TRANSFERENCIA' type, T8.code, T8.ID, CONCAT(T9.first, ' ', T9.last) name, 0 qtyIn, T7.qty qtyOut  FROM TRLN T7 JOIN TRANSFERS T8 ON T7.tranID = T8.ID JOIN CREW T9 ON T8.empID = T9.ID WHERE T7.prodCode = '".$prodCode."' AND T8.orStore = ".$storeID." UNION ALL
SELECT T11.created_at, 'TRANSFERENCIA' type, T11.code, T11.ID, CONCAT(T12.first, ' ', T12.last) name, T10.qty qtyIn, 0 qtyOut  FROM TRLN T10 JOIN TRANSFERS T11 ON T10.tranID = T11.ID JOIN CREW T12 ON T11.empID = T12.ID WHERE T10.prodCode = '".$prodCode."' AND T11.dsStore = ".$storeID." UNION ALL
SELECT T14.created_at, 'VENTA' type, T14.code, T14.ID, CONCAT(T15.first, ' ', T15.last) name, 0 qtyIn, T13.qty qtyOut  FROM SALLN T13 JOIN SALES T14 ON T13.salID = T14.ID JOIN CREW T15 ON T14.empID = T15.ID WHERE T13.prodCode = '".$prodCode."' AND T14.storeID = ".$storeID.") TT1 ORDER BY TT1.created_at ASC";
		$myresult = mysql_query($myQuery);
		while($row = mysql_fetch_assoc($myresult)){
			$quant = $quant + $row["qtyIn"] - $row["qtyOut"];	
			$link = "";
			switch ($row["type"]) {
				case "ENTRADA":
					$link = "inflowDetail.php?infID=";
					break;
				case "SALIDA":
					$link = "outflowDetail.php?outID=";
					break;
				case "TRANSFERENCIA":
					$link = "transferDetail.php?tranID=";
					break;
				case "VENTA":
					$link = "saleDetail.php?salID=";
					break;
			}
			echo "
				<tr>
				  <td>".$row["created_at"]."</td>
				  <td>".$row["type"]."</td>
				  <td>".$row["code"]."</td>
				  <td>".$row["name"]."</td>
				  <td>".$row["qtyIn"]."</td>
				  <td>".$row["qtyOut"]."</td>
				  <td>".$quant."</td>
				  <td><a href='".$link.$row["ID"]."'><i class='fa fa-eye' aria-hidden='true'></i></a></td>
				</tr>
			";
		};
	?>
  </tbody>
</table>

<div class="format" style="margin-top:20px">
<table width="100%" border="0" cellspacing="20px" cellpadding="0">
	<tr>
    	<td>Existencia según Kardex</td>
        <td>Existencia según Inventario</td>
        <td>Diferencia</td>
    </tr>
    <tr>
    	<td style="font-size:30px"><?php echo $quant; ?></td>
        <?php
		$upQuery = "UPDATE PRDL SET qty = $quant WHERE prodCode = '$prodCode' AND storeID = $storeID";
		$upresult = mysql_query($upQuery);
        ?>
        <td style="font-size:30px"><?php echo $quantInv; ?></td>
        <td style="font-size:30px"><?php echo $quantInv - $quant; ?></td>
    </tr>
</table>
</div>

<ul id="buttonBar">
    <li><button type='button' class='formButton blueB' onClick="getback();"><i class='fa fa-hand-o-left' aria-hidden='true'></i> Regresar</button></li>
</ul>

<script>


$("#store").val(<?php echo $storeID; ?>);

$(document).ready(function() {
	$("#store").select2();
});

$("#store").on("change", function() {
	window.location.href = "kardex.php?prodCode=<?php echo $prodCode; ?>&storeID=" + $(this).val() +"&prodID=<?php echo $prodID; ?>";
});

function getback() {
	window.history.back();
}
</script>
    
<?php include 'footer.php'; ?>