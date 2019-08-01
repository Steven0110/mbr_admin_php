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
<div class="rightCol">
    <div class="formContainer">
    <form method="post" action="includes/register_client.php">
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td colspan="2">Cliente<br><input type="text" name="customer" id="customer" required /></td>
      </tr>
      <tr>
        <td>Persona de contacto<br><input type="text" name="first" id="first" placeholder="Nombre" required />
        	</td>
        <td>&nbsp;<br><input type="text" name="last" id="last" placeholder="Apellido(s)" required /></td>
      </tr>
      <tr>
        <td>Tel&eacute;fono<br><input type="text" name="phone" id="phone" placeholder="Principal" required /><br><input type="text" name="alt_phone" id="alt_phone" placeholder="Alternativo" />
        	</td>
        <td>Correo electr&oacute;nico<br><input type="text" name="email" id="email" placeholder="Principal" required /><br><input type="text" name="alt_email" id="alt_email" placeholder="Alternativo" /></td>
      </tr>
      <tr>
        <td colspan="2">Notas<br><textarea name="notes" id="notes" rows="10"></textarea></td>
        </tr>
      <tr>
        <td><button class="cancelBtn" name="cancel" type="reset">Limpiar</button></td>
        <td><button class="okBtn" name="register" type="submit">Registrar</button></td>
      </tr>
    </table>
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