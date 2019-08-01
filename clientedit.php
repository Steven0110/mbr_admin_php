<?php
include_once 'head.php';
?>
<body>
<?php if (login_check($mysqli) == true) : ?>
<?php
include_once 'header.php';
?>

<div id="pageContainer">
<div class="leftCol"><span class="title">Registro de clientes</span>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam a metus libero. Pellentesque id aliquet massa, id sollicitudin purus. Aliquam eu nunc fringilla, gravida augue eu, semper ante. Duis a malesuada libero. Aenean hendrerit et purus ac condimentum. Mauris id diam cursus, pellentesque leo quis, volutpat ante. Suspendisse eleifend pulvinar tortor, sed sodales justo mollis nec. Nunc a arcu in orci facilisis auctor eget ac turpis. Mauris quis ullamcorper odio, eu luctus ligula. Vestibulum at lorem tristique, elementum massa a, ultricies odio. Maecenas tristique enim et felis rhoncus porttitor.</p>
<p>Curabitur interdum egestas massa et tempor. Nulla gravida porttitor nisi in vestibulum. Duis suscipit, nisl accumsan placerat accumsan, leo ante mollis elit, vitae adipiscing nisl nisl et augue. Etiam pharetra vel tortor nec vulputate. Nullam aliquet pharetra laoreet. Aenean mollis purus eu orci rutrum, nec laoreet leo auctor. Proin id tincidunt turpis, a venenatis arcu. Fusce dapibus nisl quis augue sagittis tempor. Donec feugiat diam at tortor semper, et sodales felis pulvinar.</p>
</div>
<?php
$clientID = $_REQUEST["clientID"];

//execute the SQL query and return records
$myQuery = mysql_query("SELECT name, cnt_first, cnt_last, phone, alt_phone, email, alt_email, notes FROM CLIENTS WHERE ID = '$clientID'");
$row = mysql_fetch_array($myQuery);
?>
<div class="rightCol">
    <div class="formContainer">
    <form method="post" action="includes/edit_client.php">
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td colspan="2">Cliente <?php echo $clientID; ?><input id="clientID" name="clientID" type="hidden" value="<?php echo $clientID; ?>" /><br><input type="text" name="customer" id="customer" value="<?php echo $row["name"]; ?>" required /></td>
      </tr>
      <tr>
        <td>Persona de contacto<br><input type="text" name="first" id="first" placeholder="Nombre" value="<?php echo $row["cnt_first"]; ?>" required />
        	</td>
        <td>&nbsp;<br><input type="text" name="last" id="last" placeholder="Apellido(s)" value="<?php echo $row["cnt_last"]; ?>" required /></td>
      </tr>
      <tr>
        <td>Tel&eacute;fono<br><input type="text" name="phone" id="phone" placeholder="Principal" value="<?php echo $row["phone"]; ?>" required /><br><input type="text" name="alt_phone" id="alt_phone" placeholder="Alternativo" value="<?php echo $row["alt_phone"]; ?>" />
        	</td>
        <td>Correo electr&oacute;nico<br><input type="text" name="email" id="email" placeholder="Principal" value="<?php echo $row["email"]; ?>" required /><br><input type="text" name="alt_email" id="alt_email" placeholder="Alternativo" value="<?php echo $row["alt_email"]; ?>" /></td>
      </tr>
      <tr>
        <td colspan="2">Notas<br><textarea name="notes" id="notes" rows="10"><?php echo $row["notes"]; ?></textarea></td>
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
    document.location.href = "clients.php";
});
</script>
<?php else : ?>

No pasa

<?php endif; ?>

<?php
include_once 'footer.php';
?>