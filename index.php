<?php
session_start();
if(isset($_SESSION['authenticated_user'])) {
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
<link href="resource/1353549783000/fav_ico" rel="icon" type="image/x-icon" />
<link href="resource/1353549783000/fav_ico" rel="shortcut icon" type="image/x-icon" />
<style>
@font-face{
	font-family: "Lato-Thin";
	src: url("css/fonts/lato/Lato-Thin.ttf") format("truetype");
}
@font-face{
	font-family: "Lato-Regular";
	src: url("css/fonts/lato/Lato-Regular.ttf") format("truetype");
}
@font-face{
	font-family: "Lato-Light";
	src: url("css/fonts/lato/Lato-Light.ttf") format("truetype");
}
@font-face{
	font-family: "Lato-Bold";
	src: url("css/fonts/lato/Lato-Bold.ttf") format("truetype");
}
@font-face{
	font-family: "Lato-Black";
	src: url("css/fonts/lato/Lato-Black.ttf") format("truetype");
}
@font-face{
	font-family: "Lato-Heavy";
	src: url("css/fonts/lato/Lato-Heavy.ttf") format("truetype");
}
@font-face{
	font-family: "Lato-Medium";
	src: url("css/fonts/lato/Lato-Medium.ttf") format("truetype");
}
html {
	height:100%;
}
body {
	color:#FFF;
	margin:0;
	padding:0;
	font-size:14px;
	font-family:Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif;
	background-image: url("images/bg-blue.jpg");
	background-size: cover;
	background-repeat: no-repeat;
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
	position: relative;
	margin-bottom: 40px;
	font-size:40px;
	font-family: Lato-Bold;
	letter-spacing: 2px;
	margin-top: 20px;
}
.login_message:before {
	position: absolute;
	content: '';
	height: 2px;
	width: 40px;
	display: inline-block;
	bottom: -15px;
	margin-left: auto;
	margin-right: auto;
	left: 0;
	right: 0;
	background-color: white;
}
.login_button {
	background-color: #13407a;
	border-radius: 7px;
	color:#FFF;
	padding:10px;
	width:100%;
	box-sizing:border-box;
	border:1px solid black;
	font-size:24px;
	margin-top:20px;
	font-family: Lato-Medium;
	letter-spacing: 2px;
	cursor:pointer;
	transition: 0.2s linear all;
}
.login_button:hover{
	transition: 0.2s linear all;
	filter: brightness(120%);
}
label {
	float: left;
	margin-top: 15px;
	font-family: Lato-Bold;
}
input {
	width: 100%;
	margin: 10px auto;
	font-size: 25px;
	border:none;
	background-color: rgba(255,255,255,.85);
	font-family: Lato-Regular;
	border-radius: 5px;
}
#header {
	margin-top:0;
	margin-bottom:60px;
	border-top:6px solid gray;
	border-bottom:6px solid gray;
	padding:10px 0;
	background-color:#000000;
}
#content {
	border-radius: 10px;
	border: 1px solid #000;
	margin: 100px auto;
	display: table;
	padding: 25px;
	background: rgba(0, 0, 0, 0.5);
	background-size:15%;
	text-align:center;
	font-size:20px;
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
.logo-container{
	background-color: white;
	border-radius: 10px 10px 0 0;
	margin-left: -25px;
	margin-right: -25px;
	margin-top: -25px;
}
.login-logo{
	max-width: 80%;
	margin-top: 10px;
	margin-bottom: 10px;
}
</style>
    </head>
    <body>
    
<div id="content">
    <div class="grid_4" id="article" style="display:block; float:none; margin:0px auto; width:400px;">
    	<div class="logo-container">
    		<img src="images/logo.png" class="login-logo" alt="">
    	</div>
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
        Arquitectura Bekman &copy; 2019
    </div>
    <div style="position:absolute; right:10px;">
        <a href="http://idited.com">idited.com</a>
    </div>
</div>
        
</body>
</html>