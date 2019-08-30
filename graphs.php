<!-- Start Loop -->
<div class="sectionTitle">ALMAC&Eacute;N <?php echo $storeName[$i]; ?></div>
<div id="cusGraphs">
	<?php
	// Top products
		$topProducts = array();
		$tpSales = array();
		$myQuery = $db->query("SELECT T1.ID, T1.code, T1.name, IFNULL(SUM(T2.qty), 0) sales FROM product T1 LEFT JOIN outln T2 ON T1.code = T2.prodCode INNER JOIN outflows T3 ON T2.outID = T3.ID INNER JOIN stores T4 ON T3.storeID = T4.ID WHERE T1.active = 'Y' AND T4.code = '$storeCode[$i]' GROUP BY T1.code ORDER BY sales DESC LIMIT 10");
		while($row = $myQuery->fetch()){			
			$topProducts[] = utf8_encode($row['name']);
			$tpSales[] = $row['sales'];
		};
	?>
    <div id="<?php echo $storeCode[$i]; ?>_topProducts">
    <script>
	// Vars
	var js_<?php echo $storeCode[$i]; ?>topProducts = JSON.parse('<?php echo JSON_encode($topProducts);?>');
	var js_<?php echo $storeCode[$i]; ?>tpSales = JSON.parse('<?php echo JSON_encode($tpSales);?>');
	js_<?php echo $storeCode[$i]; ?>tpSales = js_<?php echo $storeCode[$i]; ?>tpSales.map(Number);
	
    	$(function () {
            // Top 10 Products
            $('#<?php echo $storeCode[$i]; ?>_topProducts').highcharts({
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
					categories: js_<?php echo $storeCode[$i]; ?>topProducts,
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
					name: '<?php echo $storeCode[$i]; ?>Outflows',
					data: js_<?php echo $storeCode[$i]; ?>tpSales,
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
			});
        });
	</script>
    </div>
    <div id="<?php echo $storeCode[$i]; ?>_inOutCompare">
    <?php
	// Construct days array
	date_default_timezone_set('America/Mexico_City');
	$currentDate = new DateTime('now');
	$startDate = clone $currentDate;
	$startDate->sub(new DateInterval("P30D"));
	$range = array();
	$rangeText = array();
	$rangeQuery = array();
	
	$interval = new DateInterval('P1D');
	
	$period = new DatePeriod(
         $startDate,
         $interval,
         $currentDate->add($interval)
    );
	
	foreach($period as $date) { 
        $range[] = $date;
		$rangeText[] = $date->format('d-m-y');
		$rangeQuery[] = $date->format('Y-m-d');
    }
	
	// Get Inflows
	$dayInflows = array();
	$dayInflowsArray = array();
	$myQuery = $db->query("SELECT COUNT(*) quant, T1.created_at, SUBSTRING_INDEX(SUBSTRING_INDEX(T1.created_at, ' ', 1), ' ', -1) as day, T1.storeID, T2.name FROM inflows T1 INNER JOIN stores T2 ON T1.storeID = T2.ID WHERE created_at >= '2016-01-25 00:00:00' AND T2.code = '$storeCode[$i]' GROUP BY day");
	while($row = $myQuery->fetch()){			
		$dayInflows[$row['day']] = $row['quant'];
	};
	
	// Get Outflows
	$dayOutflows = array();
	$dayOutflowsArray = array();
	$myQuery = $db->query("SELECT COUNT(*) quant, T1.created_at, SUBSTRING_INDEX(SUBSTRING_INDEX(T1.created_at, ' ', 1), ' ', -1) as day, T1.storeID, T2.name FROM outflows T1 INNER JOIN stores T2 ON T1.storeID = T2.ID WHERE created_at >= '2016-01-25 00:00:00' AND T2.code = '$storeCode[$i]' GROUP BY day");
	while($row = $myQuery->fetch()){			
		$dayOutflows[$row['day']] = $row['quant'];
	};
	
	foreach($rangeQuery as $day) {
		if(!isset($dayInflows[$day])) $dayInflows[$day] = 0;
		if(!isset($dayOutflows[$day])) $dayOutflows[$day] = 0;
	}
	
	for ($a = 0; $a < count($range); $a++) {
		$dayInflowsArray[$a] = $dayInflows[$rangeQuery[$a]];
		$dayOutflowsArray[$a] = $dayOutflows[$rangeQuery[$a]];
	}
	?>
    <script>
	// Vars
	var js_rangeText = JSON.parse('<?php echo JSON_encode($rangeText);?>');
	var js_<?php echo $storeCode[$i]; ?>inflows = JSON.parse('<?php echo JSON_encode($dayInflowsArray);?>');
	var js_<?php echo $storeCode[$i]; ?>outflows = JSON.parse('<?php echo JSON_encode($dayOutflowsArray);?>');
	
	js_<?php echo $storeCode[$i]; ?>inflows = js_<?php echo $storeCode[$i]; ?>inflows.map(Number);
	js_<?php echo $storeCode[$i]; ?>outflows = js_<?php echo $storeCode[$i]; ?>outflows.map(Number);
		
	$(function () {
		// Ins and Outs
		$('#<?php echo $storeCode[$i]; ?>_inOutCompare').highcharts({
			chart: {
				type: 'column',
				backgroundColor: '#000000',
				spacingTop: 0,
				spacingBottom: 0,
				zoomType: 'x'
			},
			title: {
				text: 'ENTRADAS Y SALIDAS ULTIMO MES',
				style: {
					color: '#FFFFFF'
				}
			},
			xAxis: {
				categories: js_rangeText,
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
					text: 'MOVIMIENTOS'
				}
			},
			series: [{
				name: 'Entradas',
				data: js_<?php echo $storeCode[$i]; ?>inflows,
				color: '#4e12ad',
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
			},
			{
				name: 'Salidas',
				data: js_<?php echo $storeCode[$i]; ?>outflows,
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
		});
	});
	
	</script>
    </div>
</div>
<!-- End Loop -->
