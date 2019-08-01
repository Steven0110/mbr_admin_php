<?php
include_once 'head.php';
?>
<body>
<?php if (login_check($mysqli) == true) : ?>
<?php
include_once 'header.php';
?>

<div id="pageContainer">
<div class="leftCol"><span class="title">Registro de servicios</span>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam a metus libero. Pellentesque id aliquet massa, id sollicitudin purus. Aliquam eu nunc fringilla, gravida augue eu, semper ante. Duis a malesuada libero. Aenean hendrerit et purus ac condimentum. Mauris id diam cursus, pellentesque leo quis, volutpat ante. Suspendisse eleifend pulvinar tortor, sed sodales justo mollis nec. Nunc a arcu in orci facilisis auctor eget ac turpis. Mauris quis ullamcorper odio, eu luctus ligula. Vestibulum at lorem tristique, elementum massa a, ultricies odio. Maecenas tristique enim et felis rhoncus porttitor.</p>
<p>Curabitur interdum egestas massa et tempor. Nulla gravida porttitor nisi in vestibulum. Duis suscipit, nisl accumsan placerat accumsan, leo ante mollis elit, vitae adipiscing nisl nisl et augue. Etiam pharetra vel tortor nec vulputate. Nullam aliquet pharetra laoreet. Aenean mollis purus eu orci rutrum, nec laoreet leo auctor. Proin id tincidunt turpis, a venenatis arcu. Fusce dapibus nisl quis augue sagittis tempor. Donec feugiat diam at tortor semper, et sodales felis pulvinar.</p>
</div>
<div class="rightCol">
    <div class="formContainer">
    <form method="post" action="includes/register_service.php">
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td colspan="2">Cliente<br>
        <?php
		$myQuery = mysql_query("SELECT name FROM CLIENTS ORDER BY name");
		echo "<select id='customer' name='customer'>
				<option value=''>Selecciona...</option>";
		while($row = mysql_fetch_array($myQuery)){
			echo "<option value='".$row["name"]."'>".$row["name"]."</option>";
		};
		echo "</select>";
		?>
        </td>
      </tr>
      <tr>
        <td colspan="2">Website URL<br><input type="text" name="website" id="website" required /></td>
        </tr>
      <tr>
      <tr>
        <td colspan="2">Plan de servicio<br>
        <?php
		$myQuery2 = mysql_query("SELECT 1 AS rank, ID, code, remarks FROM DOMAIN UNION ALL SELECT 2 AS rank, ID, code, remarks FROM HOSTING UNION ALL SELECT 3 AS rank, ID, code, remarks FROM MAINT UNION ALL SELECT 4 AS rank, ID, code, remarks FROM SEO UNION ALL SELECT 5 AS rank, ID, code, remarks FROM SOCIAL ORDER BY rank, ID");
		echo "<select id='service' name='service'>
				<option value=''>Selecciona...</option>";
		while($row2 = mysql_fetch_array($myQuery2)){
			echo "<option value='".$row2["code"]."'>".$row2["code"]."</option>";
		};
		echo "</select>";
		?>
        </td>
      </tr>
      <tr>
        <td>Fecha de inicio<br><input type="text" name="init" id="init" required />
        	</td>
        <td>Fecha de fin<br><input type="text" name="close" id="close" required /></td>
      </tr>
      <tr>
        <td colspan="2">Factura relacionada<br><input type="text" name="invoice"/></td>
      </tr>
      <tr>
        <td>Precio de venta ($)<br><input type="text" name="price" id="price" required />
        </td>
        <td>Moneda<br><select name="currency" id="currency">
        <option value="">Selecciona...</option>
        <option value="MXN">MXN</option>
        <option value="USD">USD</option>
        </select>
        </td>
      </tr>
      <tr>
        <td colspan="2">Comentarios<br><textarea name="remarks" rows="10"></textarea></td>
        </tr>
      <tr>
        <td><button class="cancelBtn" name="cancel" type="reset">Limpiar</button></td>
        <td><button class="okBtn" name="register" type="submit">Registrar</button></td>
      </tr>
    </table>
    <input type="hidden" name="crew" id="crew" value="<?php echo htmlentities($_SESSION['username']); ?>" />
    </form>
    </div>
</div>
</div>
<script>
  $(function() {
    $( "#init" ).datepicker({ dateFormat: 'yy-mm-dd' });
	$( "#close" ).datepicker({ dateFormat: 'yy-mm-dd' });
  });
</script>

<?php else : ?>

No pasa

<?php endif; ?>

<?php
include_once 'footer.php';
?>