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
</head>
<body>
<?php if (login_check($mysqli) == true) : ?>
<!-- Main Body Starts Here -->
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
      <li class="active"><a href="dealreg.php">Submit a Deal Reg</a></li>
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
		<h1>Register a New Deal</h1>
		<p>
			Additional Cisco distribution partners are available through <a href="https://sso.cisco.com/autho/forms/CDClogin.html">Cisco's
				CCW</a> (US Only) <br /> <br />
		</p><span id="j_id0:regPanel">
<form method="post" action="includes/register_deal.php">
<span id="j_id0:regForm:j_id43" class="control-group">
					<label class="control-label" for="lead_owner">Submit a deal for</label>
					<div class="controls"><span class="lookupInput"><input id="lead_ownerD" maxlength="255" name="lead_ownerD" size="20" type="text" value="<?php echo htmlentities($_SESSION['username']); ?>" disabled /><input id="lead_owner" maxlength="255" name="lead_owner" size="20" type="hidden" value="<?php echo htmlentities($_SESSION['username']); ?>" />
                    <input id="lead_ownerID" maxlength="10" name="lead_ownerID" size="10" type="hidden" value="<?php echo htmlentities($_SESSION['user_id']); ?>" /></span>
					</div>
					<div class="clearfix"></div></span><span id="j_id0:regForm:j_id46" class="control-group">
					<label class="control-label">Interested Product Families
						<abbr title="required">*</abbr>
					</label>

					<div class="controls">
						<table>
								<tr>
									<td><input type="checkbox" name="checkboxInt[]" value="MR" /></td>
									<td><label>MR - Wireless</label></td>
								</tr>
								<tr>
									<td><input type="checkbox" name="checkboxInt[]" value="MX" /></td>
									<td><label>MX - Security</label></td>
								</tr>
								<tr>
									<td><input type="checkbox" name="checkboxInt[]" value="MS" /></td>
									<td><label>MS - Switching</label></td>
								</tr>
								<tr>
									<td><input type="checkbox" name="checkboxInt[]" value="SME" /></td>
									<td><label>SME - Mobile Device Management</label></td>
								</tr>
						</table>
					</div>

					<div class="clearfix"></div></span><span id="j_id0:regForm:j_id59" class="control-group">
					<label class="control-label" for="sale_value">Estimated Sale Amount (List Price)</label>
					<div class="controls"><input  id="sale_value" name="sale_value" id="sale_value" size="20" type="text" required />
					</div>
					<div class="clearfix"></div></span><span id="j_id0:regForm:j_id62" class="control-group">
					<label class="control-label" for="company_name">Customer Company</label>
					<div class="controls"><input  id="company_name" maxlength="255" name="company_name" size="20" type="text" required />
					</div>
					<div class="clearfix"></div></span><span id="j_id0:regForm:j_id65" class="control-group">
					<label class="control-label" for="company_industry">Customer Industry</label>
					<div class="controls">
                    <?php
                    $myQuery3 = mysql_query("SELECT value, text FROM industry ORDER BY id");
					echo "<select id='company_industry' name='company_industry'>";
					while($row3 = mysql_fetch_array($myQuery3)){
						echo "<option value='".$row3["value"]."'>".$row3["text"]."</option>";
					};
					echo "</select>";
					?>
					</div>
					<div class="clearfix"></div></span><span id="j_id0:regForm:j_id68" class="control-group">
					<label class="control-label" for="company_size">Customer Company Size</label>
					<div class="controls">
                    <?php
                    $myQuery4 = mysql_query("SELECT value, text FROM comp_size ORDER BY id");
					echo "<select id='company_size' name='company_size'>";
					while($row4 = mysql_fetch_array($myQuery4)){
						echo "<option value='".$row4["value"]."'>".$row4["text"]."</option>";
					};
					echo "</select>";
					?>
					</div>
					<div class="clearfix"></div></span><span id="j_id0:regForm:j_id71" class="control-group">
					<label class="control-label" for="customer_first">Customer First Name</label>
					<div class="controls"><input  id="customer_first" maxlength="40" name="customer_first" size="20" type="text" required />
					</div>
					<div class="clearfix"></div></span><span id="j_id0:regForm:j_id74" class="control-group">
					<label class="control-label" for="customer_last">Customer Last Name</label>
					<div class="controls"><input  id="customer_last" maxlength="80" name="customer_last" size="20" type="text" required />
					</div>
					<div class="clearfix"></div></span><span id="j_id0:regForm:j_id77" class="control-group">
					<label class="control-label" for="customer_email">Customer Email</label>
					<div class="controls"><input  id="customer_email" maxlength="80" name="customer_email" size="20" type="text" required />
					</div>
					<div class="clearfix"></div></span><span id="j_id0:regForm:j_id80" class="control-group">
					<label class="control-label" for="customer_phone">Customer Phone</label>
					<div class="controls"><input  id="customer_phone" maxlength="40" name="customer_phone" size="20" type="text" required />
					</div>
					<div class="clearfix"></div></span><span id="j_id0:regForm:j_id83" class="control-group">
					<label class="control-label" for="customer_street">Customer Street Address</label>
					<div class="controls"><textarea  id="customer_street" maxlength="255" name="customer_street" type="text" wrap="soft" required></textarea>
					</div>
					<div class="clearfix"></div></span><span id="j_id0:regForm:j_id86" class="control-group">
					<label class="control-label" for="customer_city">Customer City</label>
					<div class="controls"><input  id="customer_city" maxlength="40" name="customer_city" size="20" type="text" required />
					</div><div class="clearfix"></div>
				</span>

				
				<div class="control-group">
					<label class="control-label" for="customer_country">Customer Country</label>
					<div class="controls">
<?php
                    $myQuery5 = mysql_query("SELECT value, text FROM countries ORDER BY text");
					echo "<select id='customer_country' name='customer_country' size='1'>";
					while($row5 = mysql_fetch_array($myQuery5)){
						echo "<option value='".$row5["value"]."'>".$row5["text"]."</option>";
					};
					echo "</select>";
					?>
                    <span id="j_id0:regForm:j_id93"></span>
					</div>
				<div class="clearfix"></div></div><span id="j_id0:regForm:state"></span><span id="j_id0:regForm:zip"></span><span id="j_id0:regForm:distributor"></span><span id="j_id0:regForm:j_id120" class="control-group">
					<label class="control-label">Install Locations</label>
					<div class="controls"><textarea  id="lead_locations" maxlength="32768" name="lead_locations" rows="3" type="text" wrap="soft" required></textarea>
					</div>
					<div class="clearfix"></div></span><span id="j_id0:regForm:j_id123" class="control-group">
					<label class="control-label" for="lead_stage">Stage of Sales Cycle</label>
					<div class="controls">
                    <?php
                    $myQuery6 = mysql_query("SELECT value, text FROM lead_stages ORDER BY id");
					echo "<select id='lead_stage' name='lead_stage'>";
					while($row6 = mysql_fetch_array($myQuery6)){
						echo "<option value='".$row6["value"]."'>".$row6["text"]."</option>";
					};
					echo "</select>";
					?>
					</div>
					<div class="clearfix"></div></span><span id="j_id0:regForm:j_id126" class="control-group">
					<label class="control-label" for="lead_history">Partner Sales Efforts to Date</label>
					<div class="controls"><textarea  id="lead_history" maxlength="32000" name="lead_history" rows="3" type="text" wrap="soft" required></textarea>
					</div>
					<div class="clearfix"></div></span>

				
				<div class="control-group form-button">
					<div class="controls">
						<input class="btn" id="submitButton" name="submitButton" value="Submit Deal for Approval" type="submit" />
						
					</div>
				</div><span id="j_id0:regForm:j_id131">

<style>
  .hidden{
   		display:none;
  }
</style>

</span><img id="loading" src="images/loading.gif" class="hidden" /></span>
<div id="j_id0:regForm:j_id139"></div>
</form></span>
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
<script>
$(document).ready(function() {
$("#sale_value").keydown(function(e){
		var keyPressed;
		if (!e) var e = window.event;
		if (e.keyCode) keyPressed = e.keyCode;
		else if (e.which) keyPressed = e.which;
		var hasDecimalPoint = (($(this).val().split('.').length-1)>0);
		if ( keyPressed == 46 || keyPressed == 8 ||((keyPressed == 190||keyPressed == 110)&&(!hasDecimalPoint)) || keyPressed == 9 || keyPressed == 27 || keyPressed == 13 ||
				 // Allow: Ctrl+A
				(keyPressed == 65 && e.ctrlKey === true) ||
				 // Allow: home, end, left, right
				(keyPressed >= 35 && keyPressed <= 39)) {
					 // let it happen, don't do anything
					 return;
			}
			else {
				// Ensure that it is a number and stop the keypress
				if (e.shiftKey || (keyPressed < 48 || keyPressed > 57) && (keyPressed < 96 || keyPressed > 105 )) {
					e.preventDefault();
				}
			}
	
	  });
});//doc ready
</script>
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

