<?php
include 'head.php';

$vendorIDs = array();
$vendorNames = array();

$vendorQuery = "SELECT ID, name FROM vendor ORDER BY name ASC";
$vendorResult = $db->query($vendorQuery);
while ($vendorRow = $vendorResult->fetch()) {
	$vendorIDs[] = $vendorRow["ID"];
	$vendorNames[] = $vendorRow["name"];
}

$storeIDs = array();
$storeNames = array();

$storesQuery = "SELECT ID, code, name FROM stores ";
if ($_SESSION["role"] != 'Y') {
	$storesQuery.= "WHERE ID = '".$_SESSION["store"]."' ";
}
$storesQuery.= "ORDER BY ID ASC";
$storesResult = $db->query($storesQuery);
while ($storesRow = $storesResult->fetch()) {
	$storeIDs[] = $storesRow["ID"];
	$storeCodes[] = $storesRow["code"];
	$storeNames[] = $storesRow["name"];
}
?>


<script type="text/javascript" charset="utf8" src="js/dt/js/jquery.dataTables.min.js"></script>


<div class="sectionTitle">FALTANTE POR PROVEEDOR</div>
<form action="faltante.php" method="post" target="_blank">
<div class="searchDiv">
Selecciona al menos un proveedor <select multiple id="selectW" class="" name="vendors[]" style="height:200px">
    <?php
	echo "<option value='' selected disabled>Selecciona...</option>";
	foreach ($vendorIDs as $i => $vendorID) {
		echo "<option value='".$vendorID."'>".$vendorNames[$i]."</option>";
	}
	?>
</select>
</div>

<div class="sButtons">
<button class="sButton" id="createOrder" type="submit">GENERAR INFORME</button>
</div>
</form>


    
<?php include 'footer.php'; ?>