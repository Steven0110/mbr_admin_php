<?php include 'head.php'; include_once ('includes/mysqlconn.php');?>

<!--
<div class="sectionTitle">VISIÓN GENERAL</div>-->
<!-- General graphs -->
<!--
<div id="genGraphs">
    <div>
    	<?php
		$myQuery = $db->query("SELECT COUNT(*) quant FROM outflows");
		$row = $myQuery->fetch();
		$totCount = $row["quant"];
		$avgCount = $totCount / 8;
		
		//===== Data variables Detail Graphs

		// Rank Stores
		$stores = array();
		$outflows = array();
		$myQuery = $db->query("SELECT T1.ID, T1.code, T1.name, IFNULL(T2.quant, 0) sales FROM stores T1 LEFT JOIN (SELECT storeID, COUNT(*) quant FROM outflows GROUP BY storeID) T2 ON T1.ID = T2.storeID GROUP BY T1.ID ORDER BY sales DESC");
		while($row = $myQuery->fetch()){
			$stores[] = utf8_encode($row['name']);
			$outflows[] = $row['sales'];
		};
		$popStore = $stores[0];
		$unpStore = $stores[count($stores) - 1];
		
		// Top products
		/*
        $products = array();
		$pSales = array();
		$topProducts = array();
		$tpSales = array();
		$myQuery = mysql_query("SELECT T1.ID, T1.code, T1.name, IFNULL(SUM(T2.qty), 0) sales FROM PRODUCT T1 LEFT JOIN OUTLN T2 ON T1.code = T2.prodCode WHERE T1.active = 'Y' GROUP BY T1.ID ORDER BY sales DESC");
		while($row = mysql_fetch_array($myQuery)){
			$products[] = utf8_encode($row['name']);
			$pSales[] = $row['sales'];
			
			$popProd = $products[0];
			$unpProd = $products[count($products) - 1];
		};
		$topProducts = array_slice($products, 0, 10);
        */
		//print_r($topProducts);
		//exit;
		//$tpSales = array_slice($pSales, 0, 10);
		?>
        <div class="genIcon"><img src="images/total-outflows.png"></div>
        <div class="genLabel">SALIDAS TOTALES</div>
        <div class="genCount"><?php echo $totCount; ?></div>
	</div>
    <div>
        <div class="genIcon"><img src="images/popular-product.png"></div>
        <div class="genLabel">PRODUCTO MÁS POPULAR</div>
        <div class="genCount" title="<?php echo $popProd; ?>"><?php echo $popProd; ?></div>
	</div>
    <div>
        <div class="genIcon"><img src="images/popular-store.png"></div>
        <div class="genLabel">ALMACÉN MÁS POPULAR</div>
        <div class="genCount"><?php echo $popStore; ?></div>
	</div>
</div>
<div id="genGraphs">
    <div>
        <div class="genIcon"><img src="images/average-outflows.png"></div>
        <div class="genLabel">SALIDAS PROM. POR ALMACÉN</div>
        <div class="genCount"><?php echo $avgCount; ?></div>
	</div style="color:#2ebf10">
    <div>
        <div class="genIcon"><img src="images/unpopular-product.png"></div>
        <div class="genLabel">PRODUCTO MENOS POPULAR</div>
        <div class="genCount" title="<?php echo $unpProd; ?>"><?php echo $unpProd; ?></div>
	</div>
    <div>
        <div class="genIcon"><img src="images/unpopular-store.png"></div>
        <div class="genLabel">ALMACÉN MENOS POPULAR</div>
        <div class="genCount"><?php echo $unpStore; ?></div>
	</div>
</div>-->

<!-- Detail graphs -->

<script src="js/hc/highcharts.js"></script>
<script src="js/hc/modules/exporting.js"></script>
<script>
// Variables
// Rank Stores
var js_stores = JSON.parse('<?php echo JSON_encode($stores);?>');
var js_outflows = JSON.parse('<?php echo JSON_encode($outflows);?>');

js_outflows = js_outflows.map(Number);

// Top products
/*var js_topProducts = JSON.parse('<?php echo JSON_encode($topProducts);?>');
var js_tpSales = JSON.parse('<?php echo JSON_encode($tpSales);?>');

js_tpSales = js_tpSales.map(Number);
*/
$(function () {
	// Store Sales Rank
    $('#storesTotal').highcharts({
        chart: {
            type: 'bar',
			backgroundColor: '#000000',
			spacingTop: 0,
			spacingBottom: 0
        },
        title: {
            text: 'TOTAL DE SALIDAS POR ALMACÉN',
			style: {
				color: '#FFFFFF'
			}
        },
        xAxis: {
			categories: js_stores,
            type: 'category',
            labels: {
                style: {
					color: '#FFFFFF',
                    fontSize: '13px',
                    fontFamily: 'Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
				color: '#FFFFFF',
                text: 'Salidas'
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: '<b>{point.y} salidas</b>'
        },
        series: [{
			name: 'Outflows',
            data: js_outflows,
			color: '#3073c9',
            dataLabels: {
                enabled: true,
                color: '#FFFFFF',
                align: 'left',
                style: {
                    fontSize: '13px',
                    fontFamily: 'Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif',
					fontWeight: 'Normal'
                }
            }
        }]
    });
	// Top 10 Products
<<<<<<< HEAD
	/*$('#topProducts').highcharts({
=======
	/*
	$('#topProducts').highcharts({
>>>>>>> fac23f1ca158dc4909edaa74a6dc44cd5311e4a8
        chart: {
            type: 'bar',
			backgroundColor: '#000000',
			spacingTop: 0,
			spacingBottom: 0
        },
        title: {
            text: 'TOP 10 PRODUCTOS',
			style: {
				color: '#FFFFFF'
			}
        },
        xAxis: {
			categories: js_topProducts,
            type: 'category',
            labels: {
                style: {
					color: '#FFFFFF',
                    fontSize: '13px',
                    fontFamily: 'Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
				color: '#FFFFFF',
                text: 'Salidas'
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: '<b>{point.y} salidas</b>'
        },
        series: [{
			name: 'Outflows',
            data: js_tpSales,
			color: '#c54695',
            dataLabels: {
                enabled: true,
                color: '#FFFFFF',
                align: 'left',
                style: {
                    fontSize: '13px',
                    fontFamily: 'Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif',
					fontWeight: 'Normal'
                }
            }
        }]
    });*/
});
</script>
<!--
<div id="cusGraphs">
    <div id="storesTotal"></div>
    <div id="topProducts"></div>
</div>-->

<?php
$storeName = array();
$storeCode = array();

if ($_SESSION["role"] == 'Y') {
	$myQuery = $db->query("SELECT ID, code, name FROM stores ORDER BY name ASC");
} else {
	$myQuery = $db->query("SELECT ID, code, name FROM stores WHERE ID = '".$_SESSION["store"]."' ORDER BY code ASC");
}
while($row = $myQuery->fetch()){
	$storeName[] = $row['name'];
	$storeCode[] = $row['code'];
};

for ($i = 0; $i < count($storeName); $i++) {
	include 'graphs.php';
}

include 'footer.php';
?>