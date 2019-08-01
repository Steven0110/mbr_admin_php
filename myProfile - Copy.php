<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

include 'includes/myRegs.php';
 
sec_session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Channel Portal</title>
<link rel="shortcut icon" href="https://meraki.secure.force.com/favicon.ico" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/JavaScript" src="js/sha512.js"></script> 
<script type="text/JavaScript" src="js/forms.js"></script>
<?php if (login_check($mysqli) == true) : ?>
<!-- Main Body Starts Here -->
<a href="#skiplink" class="navSkipLink zen-skipLink zen-assistiveText">Skip to main content</a>
<link href="css/style.css" rel="stylesheet" type="text/css" />

<div class="pageContainer"> <!-- This div is closed in the footer -->
  <div id="header">
    <div class="container_12">
      <div class="grid_4">
        <a class="brand" href="http://channels.supportmeraki.com/dealreg.php">
          <img width="200" id="MerakiLogo" src="images/cisco_meraki_logo.png" style="margin-top:15px;"/>
        </a>
      </div>
      <div class="grid_8 nav_container">
        <div id="actions">
          <ul class="unstyled">
            <li>
              <a href="http://dashboard.meraki.com" target="_blank">Dashboard</a>
            </li>
            <li>
              <a href="includes/myProfile.php">My Profile</a>
            </li>
            <li>
              <a href="includes/logout.php">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
<div class="bodyDiv brdPalette brandPrimaryBrd container_12">
<div id="bd_l"></div><div id="bd_r"></div><div class="bgdPalette brandPrimaryBgr" id="motifCurve">
<div class="bgdPalette brandPrimaryBgr" id="mc_l"></div><div class="bgdPalette brandPrimaryBgr" id="mc_r"></div></div><div id="bd_b">
<div id="bd_bl"></div><div id="bd_br"></div></div><table class="outerNoSidebar" id="bodyTable" border="0" cellspacing="0" cellpadding="0"><tr><td class="noSidebarCell">
<!-- Start page content table -->
<span id="j_id0:j_id1">
  <ul class="nav nav-pills">
      <li class=""><a href="dealreg.php">Submit a Deal Reg</a></li>
      <li class=""><a href="myDealRegistrations.php">My Deal Regs</a></li>
      <?php
		//execute the SQL query to discover if user is admin
		$myQuery = mysql_query("SELECT role FROM members WHERE id = ".htmlentities($_SESSION['user_id'])."");
		while($row = mysql_fetch_array($myQuery)){
			$role = $row["role"];
		};
		if ($role == 1) {
			echo "<li class=''><a href='register.php'>Channel Reg</a></li>";
		};
		?>
  </ul>
  <div class="clearfix"></div></span><span id="j_id0:j_id9">
  <ul class="unstyled">
      <li class="bulletin">The MR72 802.11ac outdoor AP and ANT-2x external antennae are orderable as of Jan 12! Visit our <a href="https://meraki.cisco.com/products/wireless/mr72" target="_blank">MR72 product page</a> for more info.</li>
  </ul></span>

	<div class="clear"></div>

	<div class="grid_5 search">
		<h1>About your Meraki Rep</h1><span class="repTypeDescription"><p><b>Your Meraki Rep is MBR Hosting S.A. de C.V.</b></p>
        <p><img src="images/Logo_MBR.jpg" width="100px"></p>
        <p>Cerro de la Estrella 156, P.B., Col. Campestre Churubusco, Del. Coyoac&aacute;n, M&eacute;xico D.F., Tels. (55) 5363 4040, (55) 4336 7060</p>
Your Meraki rep territories are based on geography and end customer account size, and then further divided between Public Sector (PS) and Territory accounts, summarized as follows:</p>
<ul> 
<li><b>Inside PS: </b>Government under 1,000 employees and education under 4,000 students</li> 
<li><b>Inside Territory: </b>Commercial and government under 1,000 employees and education under 4,000 students</li> 
</ul>
<ul> 
<li><b>Field PS: </b>Government over 1,000 employees and education over 4,000 students</li> 
<li><b>Field Territory: </b>Commercial over 1,000 employees and education over 4,000 students</li> 
</ul><p>If you need further information about Territories & Customers, feel free to contact with your Meraki Rep.</p></span>
	</div>

	<div class="grid_6 prefix_1">
    <h1>My Profile Info</h1>
		<div class="well light">        
            <p style="font-size:12px">
            Your username and email can't be modified.<br>
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
                <?php
					//execute the SQL query and return records
					$myQuery = mysql_query("SELECT id, first, last, username, email, company, phone, password, salt, role FROM members WHERE username = '".htmlentities($_SESSION['username'])."'");
					$row = mysql_fetch_array($myQuery);
				?>
                <label for="username">Username</label><input type='text' 
                name='username' 
                id='username' value="<?php echo htmlentities($_SESSION['username']); ?>" disabled /><br>
            <label for="email">Email</label><input type="text" name="email" id="email" value="<?php echo $row["email"]; ?>" disabled /><br>
            <label for="first">First name</label><input type='text' 
                name='first' 
                id='first' value="<?php echo $row["first"]; ?>" required /><br>
                <label for="last">Last name</label><input type='text' 
                name='last' 
                id='last' value="<?php echo $row["last"]; ?>" required /><br>
                <label for="company">Company</label><input type='text' 
                name='company' 
                id='company' value="<?php echo $row["company"]; ?>" required /><br>
                <label for="phone">Phone number</label><input type='text' 
                name='phone' 
                id='phone' value="<?php echo $row["phone"]; ?>" required /><br>
            <label for="password">Password</label><input type="password"
                             name="password" 
                             id="password" /><br>
            <label for="confirmpwd">Confirm password</label><input type="password" 
                                     name="confirmpwd" 
                                     id="confirmpwd" /><br>
            <input class="btn" type="submit" value="Register" 
                   onclick="return regformhash(this.form,
                                   this.form.username,
                                   this.form.email,
                                   this.form.password,
                                   this.form.confirmpwd);" /> 
        </form>
        
                  </div>


	<p>Your Meraki Rep: MBR Hosting S.A. de C.V.</p>

<!-- End page content -->
</td>
</tr>
</table>
</div>  <div style="clear:both"></div>
  <div class="footer">
    <div class="container_12">
      <div class='wish_box_container'>
          <ul class="unstyled horiz lined">
            <li>&copy; Meraki Inc. 2014</li>
            <li><a href="mailto:channels@supportmeraki.com?subject=Help!&amp;body=I need some help with the Partner Portal…">Need Help?</a></li>
            <li><a href="http://meraki.com">meraki.com</a></li>
          </ul><!-- /horiz -->
      </div>
    </div>
  </div>

</div> <!-- This div is opened in the header -->
<?php else : ?>
<style>
body {
	background:url(images/backgroundTextureGrey.jpg);
	margin:100px 0;
	color:#FFF;
	font-family:Tahoma, Geneva, sans-serif;
	font-weight:normal;
	font-size:16px;
}
a {
	text-decoration:none;
	color:#78be20;
}
table {
	width:100%;
	border:none;
}
table tr td {
	text-align:center;
	vertical-align:middle;
}
</style>
</head>

<body>
<table>
<tr>
<td><img src="images/cisco_meraki_logo.png" width="250px"></td>
</tr>
<tr>
<td style="padding:200px;">You are not authorized to access this page. Please <a href="index.php">login</a></td>
</tr>
</table>
<?php endif; ?>

</body>
</html>

