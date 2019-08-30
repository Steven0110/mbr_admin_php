<div id="footer">
<<<<<<< HEAD
    ARQUITECTURA 2019
=======
    Arquitectura Bekman &copy; 2019
>>>>>>> ea9b9e88c8d78990808217bcfda7ebd9e524f1a7
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
//mysql_close($dbhandle);
?>