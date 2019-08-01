<?php
session_start();
if($_SESSION["authenticated_user"]) {
	header("Location: main.php");
	die();
}
?>
<!DOCTYPE HTML>

<html>
<head>

<meta HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE" />
<meta content="text/html; charset=UTF-8" http-equiv="content-type" />
<title>U&ntilde;as Sal&oacute;n y M&aacute;s - Intranet</title>
<link href="/resource/1353549783000/fav_ico" rel="icon" type="image/x-icon" />
<link href="/resource/1353549783000/fav_ico" rel="shortcut icon" type="image/x-icon" />
<style>
html {
	height:100%;
}
body {
	background:url(images/stripes-back.jpg) #FFFFFF center center;
	color:#FFF;
	margin:0;
	padding:0;
	font-size:14px;
	font-family:Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif;
}
a {
	color:#FFF;
	text-decoration:none;
}
.loginLogo {
	display:block;
	width:400px;
	max-width:90%;
}
.login_message {
	margin-bottom:20px;
	font-size:40px;
}
.login_button {
	background-color:#000;
	color:#FFF;
	padding:10px;
	width:100%;
	box-sizing:border-box;
	border:1px solid #c54695;
	font-size:24px;
	margin-top:20px;
	font-family:Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif;
	cursor:pointer;
}
input {
	width: 100%;
	margin: 10px auto;
	font-size: 30px;
	border:none;
	background-color: rgba(255,255,255,.85);
	font-family:Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif;
}
#header {
	margin-top:0;
	margin-bottom:60px;
	border-top:6px solid #c54695;
	border-bottom:6px solid #c54695;
	padding:10px 0;
	background-color:#000000;
}
#content {
	border: 4px solid #000;
	margin: 60px auto;
	display: table;
	padding: 25px;
	background:url(images/leather-back.jpg) #c54695 center center;
	background-size:15%;
	text-align:center;
	font-size:20px
}
#form {
	margin-top:10px;
}
#footer {
	font-size:12px;
	padding:10px 10px 24px 10px;
	background-color:#000;
	position:absolute;
	box-sizing:border-box;
	bottom:0;
	width:100%;
}
</style>
    </head>
    <body>
<div id="header">
    <div align="center">
        <img src="images/logo.png" class="loginLogo" />
    </div>
</div>
    
<div id="content">
    <div class="grid_4" id="article" style="display:block; float:none; margin:0px auto; width:400px;">
        <div class="login_message">BIENVENIDO</div>
            

                      
    <div id="form">
        <form action="includes/process_login.php" method="post" name="login_form" class="loginForm">                              
        <div style="background-color: #FCF8E3;">
            <span id="error"></span>
        </div> 
        <div>
            <label for="username">
            Usuario</label><br><input type="text" name="username" id="username" autofocus />
        </div>
        
        <div>
            <label id="passwordLabel" for="password">
            Contraseña</label><br><input type="password" name="password" id="password" />
        </div>
        
        
        <div>
            <button type="submit" class="login_button">ENTRAR</button>
        </div>
        </form> 
    </div>

</div>

</div>
    
<div id="footer">
    <div style="position:absolute; left:10px;">
        Uñas Salón y Más &copy; 2016
    </div>
    <div style="position:absolute; right:10px;">
        <a href="http://idited.com">idited.com</a>
    </div>
</div>
        
</body>
</html>