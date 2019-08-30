<?php
session_start();

$timeout = 7200; // 2 hours
if(isset($_SESSION['timeout'])) {
	$duration = time() - (int)$_SESSION['timeout'];
	if($duration > $timeout) {
		session_destroy();
		session_start();
	}
}
$_SESSION['timeout'] = time();

if($_SESSION["authenticated_user"] != true) {
	header("Location: index.php");
	die();
}

include_once "includes/mysqlconn.php";

?>
<!doctype html>
<html>
<head>
<meta charset="ISO-8859-1" http-equiv="content-type" content="text/html">
<title>ARQUITECTURA</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="js/dt/css/jquery.dataTables.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="js/jquery.filtertable.js"></script> <!-- Filter Table -->
<!-- Validate -->
<script src="js/jquery.validate.min.js"></script>
<script src="js/additional-methods.min.js"></script>
<!-- Add multiemail method -->
<script>
jQuery.validator.addMethod(
    "multiemail",
     function(value, element) {
         if (this.optional(element)) // return true on optional element 
             return true;
         var emails = value.split(/[;,]+/); // split element by , and ;
         valid = true;
         for (var i in emails) {
             value = emails[i];
             valid = valid &&
                     jQuery.validator.methods.email.call(this, $.trim(value), element);
         }
         return valid;
     },

    jQuery.validator.messages.email
);

function localeString(x, sep, grp) {
    var sx = (''+x).split('.'), s = '', i, j;
    sep || (sep = ','); // default seperator
    grp || grp === 0 || (grp = 3); // default grouping
    i = sx[0].length;
    while (i > grp) {
        j = i - grp;
        s = sep + sx[0].slice(j, i) + s;
        i = j;
    }
    s = sx[0].slice(0, i) + s;
    sx[0] = s;
    return sx.join('.')
}
</script>
<script src="https://use.fontawesome.com/2cc2963e4c.js"></script>
</head>

<body>

<div id="mainWrapper">
    <div id="navigation">
        <?php include_once 'navigation.php'; ?>
    </div>
    
    <div id="contentWrapper">
    <?php include_once 'header.php'; ?>