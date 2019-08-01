<?php
include_once 'includes/register.inc.php';
include_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Secure Login: Registration Form</title>
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script>
        <link class="user" href="css/style.css" rel="stylesheet" type="text/css" />
        <style type="text/css">
		body {
			background-color:rgb(238, 238, 238);
		}
			#header
			{
				background: #000 url("images/header_bg.jpg") repeat 0 0;
				height: 150px;
				margin-bottom: 18px;
				width: 100%;
				box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25), 0 -1px 0 rgba(0, 0, 0, 0.1) inset;
			}
    </style>
    </head>
    <body>
    <div id="header">
    <div class="container_12">
        <div class="grid_4 push_4">
            <a class="brand" href="/" style="display:block; margin:48px auto 0;"><img id="loginPage:LoginTemplate:j_id13:j_id14:MerakiLogo" src="images/cisco_meraki_logo.png" style="display:block;" width="250" />
            </a>
        </div>

    </div>
        </div>
        
        
        
        <div class="container_12" id="content">
      <div class="grid_4" id="article" style="display:block; float:none; margin:0px auto; width:300px;">
             <h1>Register with us</h1>
            

                      
          <div class="well light">
          <?php
        if (!empty($error_msg)) {
            echo $error_msg;
        }
        ?>
        
            <p style="font-size:12px">
            Usernames may contain only digits, upper and lower case letters and underscores.<br>
            Emails must have a valid email format.<br>
            Passwords must be at least 6 characters long.<br>
            Passwords must contain:</p>
                <ul style="font-size:12px">
                    <li>At least one uppercase letter (A..Z)</li>
                    <li>At least one lower case letter (a..z)</li>
                    <li>At least one number (0..9)</li>
                </ul>
            <p style="font-size:12px">Your password and confirmation must match exactly.</p>
        <form action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>" 
                method="post" 
                name="registration_form" class="loginForm">
                <label for="first">First name</label><input type='text' 
                name='first' 
                id='first' required /><br>
                <label for="last">Last name</label><input type='text' 
                name='last' 
                id='last' required /><br>
                <label for="company">Company</label><input type='text' 
                name='company' 
                id='company' required /><br>
                <label for="phone">Phone number</label><input type='text' 
                name='phone' 
                id='phone' required /><br>
            <label for="username">Username</label><input type='text' 
                name='username' 
                id='username' /><br>
            <label for="email">Email</label><input type="text" name="email" id="email" /><br>
            <label for="password">Password</label><input type="password"
                             name="password" 
                             id="password"/><br>
            <label for="confirmpwd">Confirm password</label><input type="password" 
                                     name="confirmpwd" 
                                     id="confirmpwd" /><br>
            <input type="button" 
                   value="Register" 
                   onclick="return regformhash(this.form,
                                   this.form.username,
                                   this.form.email,
                                   this.form.password,
                                   this.form.confirmpwd);" /> 
        </form>
        
                  </div>
      </div>
        </div>
    
        <div class="container_12" id="footer">
    <div class="grid_2">
        &copy; Meraki Inc. 2014
    </div>
    <div class="grid_4">
        <a href="http://meraki.com" title="Meraki Home Site">meraki.com</a>
    </div>
    
        </div>
        
        
        <!-- Registration form to be output if the POST variables are not
        set or if the registration script caused an error. -->
       
        
    </body>
</html>