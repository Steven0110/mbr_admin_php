<?php
include_once 'head.php';
?>
<body>
<?php if (login_check($mysqli) == true) : ?>
<?php
include_once 'header.php';
?>

<div id="pageContainer">
<div class="leftCol"><span class="title">Modificaci&oacute;n de registro</span>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam a metus libero. Pellentesque id aliquet massa, id sollicitudin purus. Aliquam eu nunc fringilla, gravida augue eu, semper ante. Duis a malesuada libero. Aenean hendrerit et purus ac condimentum. Mauris id diam cursus, pellentesque leo quis, volutpat ante. Suspendisse eleifend pulvinar tortor, sed sodales justo mollis nec. Nunc a arcu in orci facilisis auctor eget ac turpis. Mauris quis ullamcorper odio, eu luctus ligula. Vestibulum at lorem tristique, elementum massa a, ultricies odio. Maecenas tristique enim et felis rhoncus porttitor.</p>
<p>Curabitur interdum egestas massa et tempor. Nulla gravida porttitor nisi in vestibulum. Duis suscipit, nisl accumsan placerat accumsan, leo ante mollis elit, vitae adipiscing nisl nisl et augue. Etiam pharetra vel tortor nec vulputate. Nullam aliquet pharetra laoreet. Aenean mollis purus eu orci rutrum, nec laoreet leo auctor. Proin id tincidunt turpis, a venenatis arcu. Fusce dapibus nisl quis augue sagittis tempor. Donec feugiat diam at tortor semper, et sodales felis pulvinar.</p>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="notesTable formContainer">
      <tr>
        <td colspan="2"><strong>Sem&aacute;foro estatus de servicio:</strong></td>
      </tr>
      <tr>
        <td width="20px"><div class="semaphore" style="background-color:#82bc00; border:1px solid #427a00;"></div></td>
        <td>Activo</td>
      </tr>
      <tr>
        <td><div class="semaphore" style="background-color:#ffd900; border:1px solid #eab200;"></div></td>
        <td>Pr&oacute;ximo a vencer</td>
      </tr>
      <tr>
        <td><div class="semaphore" style="background-color:#62259d; border:1px solid #300068;"></div></td>
        <td>En periodo de gracia</td>
      </tr>
      <tr>
        <td><div class="semaphore" style="background-color:#e2231a; border:1px solid #890000;"></div></td>
        <td>Vencido</td>
      </tr>
    </table>
</div>
<?php
	$record = $_REQUEST["record"];
	
	//execute the SQL query and return records
	$myQuery = mysql_query("SELECT T1.ID, T1.customer, T1.website, T1.invoice, T1.price, T1.currency, T1.service, T1.init, T1.close, T1.crew, T1.status, T1.remarks, T2.hex_color, T2.border FROM SERVICE AS T1 INNER JOIN SRV_STATUS AS T2 ON T1.status = T2.ID WHERE T1.ID = '$record'");
	$row = mysql_fetch_array($myQuery);
	
	$initDate = new DateTime($row["init"]);
	$endDate = new DateTime($row["close"]);
?>
<div class="rightCol">
    <div class="formContainer">
    <form method="post" action="includes/edit_record.php">
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td align="left" style="font-size:16px; font-weight:bold">Registro No. <?php echo $record; ?><input type="hidden" value="<?php echo $record; ?>" name="record" /></td>
        <td align="right">Status<div class='semaphore' style='background-color:#<?php echo $row["hex_color"]; ?>; border:1px solid #<?php echo $row["border"]; ?>; float:right; margin-left:10px;'></div></td>
      </tr>
      <tr>
        <td colspan="2">Cliente<br><input type="text" name="customer" id="customer" value="<?php echo $row["customer"]; ?>" disabled /></td>
      </tr>
      <tr>
        <td colspan="2">Website URL<br><input type="text" name="website" id="website" value="<?php echo $row["website"]; ?>" disabled /></td>
        </tr>
      <tr>
      <tr>
        <td colspan="2">Plan de servicio<br><input type="text" name="service" id="service" value="<?php echo $row["service"]; ?>" disabled /></td>
      </tr>
      <tr>
        <td>Fecha de inicio<br><input type="text" name="init" id="init" value="<?php echo $initDate->format('d-m-Y'); ?>" disabled />
        	</td>
        <td>Fecha de fin<br><input type="text" name="close" id="close" value="<?php echo $endDate->format('d-m-Y'); ?>" disabled /></td>
      </tr>
      <tr>
        <td colspan="2">Tiempo transcurrido<br><input type="text" name="elapsed" value="<?php 
		$startDate = new DateTime($row["init"]);
		$endDate   = new DateTime('now');
		$interval  = $endDate->diff($startDate);
		echo $interval->format('%y a&ntilde;os, %m meses, %d d&iacute;as'); ?>" disabled/></td>
      </tr>
      <tr>
        <td colspan="2">Crew member<br><input type="text" name="crew" value="<?php echo $row["crew"]; ?>" disabled/></td>
      </tr>
      <tr>
        <td colspan="2">Factura relacionada<br><input type="text" name="invoice" value="<?php echo $row["invoice"]; ?>" disabled/></td>
      </tr>
      <tr>
        <td>Precio de venta ($)<br><input type="text" name="price" id="price" value="<?php echo $row["price"]; ?>" disabled />
        </td>
        <td>Moneda<br><input type="text" name="currency" id="currency" value="<?php echo $row["currency"]; ?>" disabled />
        </td>
      </tr>
      <tr>
        <td colspan="2">Comentarios<br><textarea name="remarks" rows="10"><?php echo $row["remarks"]; ?></textarea></td>
        </tr>
      <tr>
        <td><button class="cancelBtn" name="cancel" id="cancel" type="button">Cancelar</button></td>
        <td><button class="okBtn" name="update" type="submit">Actualizar</button></td>
      </tr>
    </table>
    </form>
    </div>
</div>
</div>
<script>
$("#cancel").click(function()
{
    document.location.href = "records.php";
});
</script>
<?php else : ?>

No pasa

<?php endif; ?>

<?php
include_once 'footer.php';
?>