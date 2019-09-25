<?php include 'head.php'; ?>

<div class="sectionTitle">USUARIOS</div>

<div id="userDiv"></div>

<div class="sectionTitle">ALMACENES</div>

<div id="storeDiv"></div>

<div class="sectionTitle">ALMACENES</div>

<div id="vendorDiv"></div>

<script>
$(document).ready(function() {
	$.post("users.php", function(data) {
		$("#userDiv").html(data);
	});
	$.post("stores.php", function(data) {
		$("#storeDiv").html(data);
	});
	$.post("vendor.php", function(data) {
		$("#vendorDiv").html(data);
	});
});

</script>

    
<?php include 'footer.php'; ?>