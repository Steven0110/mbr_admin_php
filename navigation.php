<?php

?>
<div id="main-logo"></div>
<ul class="mainNav">
    <li><a href="main.php">GENERAL</a></li>
    <?php
	if ($_SESSION["role"] == 'Y') {
        echo ("<li><a href='proyects.php'>PROYECTOS</a></li>");
        echo ("<li><a href='prestamos.php'>HERRAMIENTAS</a></li>");
        echo ("<li><a href='inflows.php'>ENTRADAS</a></li>");
        echo ("<li><a href='outflows.php'>SALIDAS</a></li>");
	}
	?>
    <li><a href="products2.php">INVENTARIO</a></li>
    <?php
	if ($_SESSION["sales"] == 'Y') {
        echo ("<li><a href='transfers.php'>TRANSFERENCIAS</a></li>");
		echo "<li><a href='sales.php'>VENTAS</a></li>";
        echo "<li><a href='orders.php'>PEDIDOS</a></li>";
	}
	if ($_SESSION["role"] == 'Y') {
		echo "<li><a href='priceList.php'>LISTA DE PRECIOS</a></li>";
		echo "<li><a href='supplier.php'>FALTANTES</a></li>";
		echo "<li><a href='payments.php'>COBROS</a></li>";
		echo "<li><a href='genDebt.php'>EDO DE CUENTA</a></li>";
		echo ("<li><a href='admin.php'>ADMINISTRACI&Oacute;N</a></li>");
	}
	?>
</ul>