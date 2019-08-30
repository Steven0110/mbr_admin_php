<?php
include "head.php";
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/js/select2.min.js"></script>

<div class="sectionTitle">
    GENERA ESTADO DE CUENTA
</div>

<div class="format">
    <form action="accStatus.php" method="get" id="genForm">
    	<table width="100%" border="0" cellspacing="20px" cellpadding="0">
        	<tr>
            	<td colspan="2">Salón:</td>
            </tr>
        	<tr>
            	<td colspan="2">
                	<select id="store" name="store" style="margin-top:10px;" required>
                        <option value="" selected disabled>Selecciona...</option>
                        <?php
                        $myQuery = $db->query("SELECT ID, CONCAT(name, ' (', ID, ')') name FROM stores WHERE ID <> 100 ORDER BY name");
                        while($row = $myQuery->fetch()){
                            echo "<option value='".$row["ID"]."'>".$row["name"]."</option>";
                        };
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
            	<td>Desde:</td>
                <td>Hasta:</td>
            </tr>
            <tr>
            	<td><input type="text" id="fromDate" name="fromDate" class="inputText" required></td>
                <td><input type="text" id="toDate" name="toDate" class="inputText" required></td>
            </tr>
            <tr>
            	<td colspan="2">
                	<ul id="buttonBar">
                        <li><button type='submit' class='formButton blueB' id='cancelBT'><i class='fa fa-list' aria-hidden='true'></i> Generar</button></li>
                    </ul>
                </td>
            </tr>
        </table>
    </form>
</div>

<script>
$(document).ready(function() {
	$("#store").select2();
});

$("#fromDate").datepicker({
	numberOfMonths: 2,
	maxDate: '0',
	dateFormat: "dd/mm/yy",
	onClose: function(selectedDate) {
		$("#toDate").datepicker("option", "minDate", selectedDate);
		$("#toDate").datepicker("option", "maxDate", "0");
	}
});

$("#toDate").datepicker({
	numberOfMonths: 2,
	maxDate: '0',
	dateFormat: "dd/mm/yy",
	onClose: function(selectedDate) {
		$("#fromDate").datepicker("option", "maxDate", "0");
		$("#fromDate").datepicker("option", "maxDate", selectedDate);
	}
});
</script>

<?php
include "footer.php";
?>