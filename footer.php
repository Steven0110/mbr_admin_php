<div id="footer">
    &copy; U&ntilde;as Sal&oacute;n y M&aacute;s 2016
</div>

    </div>
</div>

<script>
$(".prodCode").on('input', function(evt) {
	var input = $(this);
	var start = input[0].selectionStart;
	$(this).val(function (_, val) {
		return val.toUpperCase();
	});
	input[0].selectionStart = input[0].selectionEnd = start;
});
	
$(document).ready(function(){
	  $("*").dblclick(function(e){
		    e.preventDefault();
	  });
});

//$("*[type='submit']").click(function() {
//	$(this).prop("disabled", true);
//})
</script>

</body>

</html>
<?php
mysql_close($dbhandle);
?>