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
      <li><a href="dealreg.php">Submit a Deal Reg</a></li>
      <li><a href="myDealRegistrations.php">My Deal Regs</a></li>
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
		<h1>Edit a Deal</h1>
		<span id="j_id0:regPanel">
	<?php
		$oprt = $_REQUEST["oprt"];
	?>
<form method="post" action="includes/edit_deal.php">
<span id="j_id0:regForm:j_id43" class="control-group">                    
					<span id="j_id0:regForm:j_id59" class="control-group">
					<label class="control-label" for="lead_id">Deal ID</label>
					<div class="controls"><input id="lead_idD" name="lead_idD" size="20" type="text" value="<?php echo $oprt; ?>" disabled/><input id="lead_id" name="lead_id" size="20" type="hidden" value="<?php echo $oprt; ?>" />
					</div>
                    <div class="clearfix"></div></span>
					<label class="control-label" for="lead_ownerID">Deal Owner</label>
					<div class="controls"><span class="lookupInput">
                    <?php
					//execute the SQL query and return records
					$myQuery = mysql_query("SELECT T1.ID, T1.DATE, T1.PRODUCT, T1.AMOUNT, T1.C_COMPANY, T1.C_INDUSTRY, T1.C_SIZE, T1.C_FIRST, T1.C_LAST, T1.C_EMAIL, T1.C_PHONE, T1.C_STREET, T1.C_CITY, T1.C_COUNTRY, T1.LOCATION, T1.STAGE, T1.EFFORT, T1.CHAN_ID, CONCAT(T2.first, ' ', T2.last) AS CHANNEL, T1.STATUS FROM oprt AS T1 INNER JOIN members AS T2 ON T1.CHAN_ID = T2.id WHERE T1.ID = '$oprt'");
					$row = mysql_fetch_array($myQuery);
		
                    $myQuery2 = mysql_query("SELECT id, CONCAT(first, ' ', last) AS CHANNEL FROM members ORDER BY CHANNEL");
					echo "<select id='lead_ownerID' name='lead_ownerID'>";
					while($row2 = mysql_fetch_array($myQuery2)){
						if ($row["CHAN_ID"] == $row2["id"])
						{
							$selected = 'selected="selected"';
						}
						else
						{
							$selected = '';
						}
						echo "<option value='".$row2["id"]."' ".$selected.">".$row2["CHANNEL"]."</option>";
					};
					echo "</select>";
					?>
                    </span>
					</div>
					<div class="clearfix"></div></span><span id="j_id0:regForm:j_id46" class="control-group">
					<label class="control-label">Interested Product Families
						<abbr title="required">*</abbr>
					</label>

					<div class="controls">
                    <?php
					$products = $row["PRODUCT"];
					$posMR = strpos($products, 'MR');
					$posMX = strpos($products, 'MX');
					$posMS = strpos($products, 'MS');
					$posSME = strpos($products, 'SME');
					?>
						<table>
								<tr>
									<td><input type="checkbox" name="checkboxInt[]" value="MR" <?php if ($posMR !== false) {echo "checked";} ?> /></td>
									<td><label>MR - Wireless</label></td>
								</tr>
								<tr>
									<td><input type="checkbox" name="checkboxInt[]" value="MX" <?php if ($posMX !== false) {echo "checked";} ?> /></td>
									<td><label>MX - Security</label></td>
								</tr>
								<tr>
									<td><input type="checkbox" name="checkboxInt[]" value="MS" <?php if ($posMS !== false) {echo "checked";} ?> /></td>
									<td><label>MS - Switching</label></td>
								</tr>
								<tr>
									<td><input type="checkbox" name="checkboxInt[]" value="SME" <?php if ($posSME !== false) {echo "checked";} ?> /></td>
									<td><label>SME - Mobile Device Management</label></td>
								</tr>
						</table>
					</div>

					<div class="clearfix"></div></span>
                    <span id="j_id0:regForm:j_id59" class="control-group">
					<label class="control-label" for="sale_value">Estimated Sale Amount (List Price)</label>
					<div class="controls"><input  id="sale_value" name="sale_value" size="20" type="text" value="<?php echo $row["AMOUNT"]; ?>" required />
					</div>
					<div class="clearfix"></div></span>
                    <span id="j_id0:regForm:j_id62" class="control-group">
					<label class="control-label" for="company_name">Customer Company</label>
					<div class="controls"><input  id="company_name" maxlength="255" name="company_name" size="20" type="text" value="<?php echo $row["C_COMPANY"]; ?>" required />
					</div>
					<div class="clearfix"></div></span><span id="j_id0:regForm:j_id65" class="control-group">
					<label class="control-label" for="company_industry">Customer Industry</label>
					<div class="controls">
                    <?php
                    $myQuery3 = mysql_query("SELECT value, text FROM industry ORDER BY id");
					echo "<select id='company_industry' name='company_industry'>";
					while($row3 = mysql_fetch_array($myQuery3)){
						if ($row["C_INDUSTRY"] == $row3["value"])
						{
							$selected = 'selected="selected"';
						}
						else
						{
							$selected = '';
						}
						echo "<option value='".$row3["value"]."' ".$selected.">".$row3["text"]."</option>";
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
						if ($row["C_SIZE"] == $row4["value"])
						{
							$selected = 'selected="selected"';
						}
						else
						{
							$selected = '';
						}
						echo "<option value='".$row4["value"]."' ".$selected.">".$row4["text"]."</option>";
					};
					echo "</select>";
					?>
					</div>
					<div class="clearfix"></div></span><span id="j_id0:regForm:j_id71" class="control-group">
					<label class="control-label" for="customer_first">Customer First Name</label>
					<div class="controls"><input  id="customer_first" maxlength="40" name="customer_first" size="20" type="text" value="<?php echo $row["C_FIRST"]; ?>" required />
					</div>
					<div class="clearfix"></div></span><span id="j_id0:regForm:j_id74" class="control-group">
					<label class="control-label" for="customer_last">Customer Last Name</label>
					<div class="controls"><input  id="customer_last" maxlength="80" name="customer_last" size="20" type="text" value="<?php echo $row["C_LAST"]; ?>" required />
					</div>
					<div class="clearfix"></div></span><span id="j_id0:regForm:j_id77" class="control-group">
					<label class="control-label" for="customer_email">Customer Email</label>
					<div class="controls"><input  id="customer_email" maxlength="80" name="customer_email" size="20" type="text" value="<?php echo $row["C_EMAIL"]; ?>" required />
					</div>
					<div class="clearfix"></div></span><span id="j_id0:regForm:j_id80" class="control-group">
					<label class="control-label" for="customer_phone">Customer Phone</label>
					<div class="controls"><input  id="customer_phone" maxlength="40" name="customer_phone" size="20" type="text" value="<?php echo $row["C_PHONE"]; ?>" required />
					</div>
					<div class="clearfix"></div></span><span id="j_id0:regForm:j_id83" class="control-group">
					<label class="control-label" for="customer_street">Customer Street Address</label>
					<div class="controls"><textarea  id="customer_street" maxlength="255" name="customer_street" type="text" wrap="soft" required><?php echo $row["C_STREET"]; ?></textarea>
					</div>
					<div class="clearfix"></div></span><span id="j_id0:regForm:j_id86" class="control-group">
					<label class="control-label" for="customer_city">Customer City</label>
					<div class="controls"><input  id="customer_city" maxlength="40" name="customer_city" size="20" type="text" value="<?php echo $row["C_CITY"]; ?>" required />
					</div><div class="clearfix"></div>
				</span>

				
				<div class="control-group">
					<label class="control-label" for="customer_country">Customer Country</label>
					<div class="controls">
                    <?php
                    $myQuery5 = mysql_query("SELECT value, text FROM countries ORDER BY text");
					echo "<select id='customer_country' name='customer_country' size='1'>";
					while($row5 = mysql_fetch_array($myQuery5)){
						if ($row["C_COUNTRY"] == $row5["value"])
						{
							$selected = 'selected="selected"';
						}
						else
						{
							$selected = '';
						}
						echo "<option value='".$row5["value"]."' ".$selected.">".$row5["text"]."</option>";
					};
					echo "</select>";
					?>
<span id="j_id0:regForm:j_id93"></span>
					</div>
				<div class="clearfix"></div></div><span id="j_id0:regForm:state"></span><span id="j_id0:regForm:zip"></span><span id="j_id0:regForm:distributor"></span><span id="j_id0:regForm:j_id120" class="control-group">
					<label class="control-label">Install Locations</label>
					<div class="controls"><textarea  id="lead_locations" maxlength="32768" name="lead_locations" rows="3" type="text" wrap="soft" required><?php echo $row["LOCATION"]; ?></textarea>
					</div>
					<div class="clearfix"></div></span><span id="j_id0:regForm:j_id123" class="control-group">
					<label class="control-label" for="lead_stage">Stage of Sales Cycle</label>
					<div class="controls">
                    <?php
                    $myQuery6 = mysql_query("SELECT value, text FROM lead_stages ORDER BY id");
					echo "<select id='lead_stage' name='lead_stage'>";
					while($row6 = mysql_fetch_array($myQuery6)){
						if ($row["STAGE"] == $row6["value"])
						{
							$selected = 'selected="selected"';
						}
						else
						{
							$selected = '';
						}
						echo "<option value='".$row6["value"]."' ".$selected.">".$row6["text"]."</option>";
					};
					echo "</select>";
					?>
					</div>
					<div class="clearfix"></div></span><span id="j_id0:regForm:j_id126" class="control-group">
					<label class="control-label" for="lead_history">Partner Sales Efforts to Date</label>
					<div class="controls"><textarea  id="lead_history" maxlength="32000" name="lead_history" rows="3" type="text" wrap="soft" required><?php echo $row["EFFORT"]; ?></textarea>
					</div>
					<div class="clearfix"></div></span>

				
				<span id="j_id0:regForm:j_id131">
                <div class="clearfix"></div></span><span id="j_id0:regForm:j_id123" class="control-group">
					<label class="control-label" for="lead_status">Deal Status</label>
					<div class="controls">
                    <?php
                    $myQuery7 = mysql_query("SELECT value, text FROM lead_status ORDER BY id");
					echo "<select id='lead_status' name='lead_status'><option value='' selected></option>";
					while($row7 = mysql_fetch_array($myQuery7)){
						if ($row["STATUS"] == $row7["value"])
						{
							$selected = 'selected="selected"';
						}
						else
						{
							$selected = '';
						}
						echo "<option value='".$row7["value"]."' ".$selected.">".$row7["text"]."</option>";
					};
					echo "</select>";
					?>
					</div>
					<div class="clearfix"></div></span>
                    <div class="control-group form-button">
					<div class="controls">
						<input class="btn" id="submitButton" name="submitButton" value="Save Deal" type="submit" />
                        <input class="btn" style="background-color:#F00;" value="Cancel" type="button" onclick="javascript:location.href='http://channels.supportmeraki.com/myDealRegistrations.php'" />
						
					</div>
				</div>

<style>
  .hidden{
   		display:none;
  }
</style>

</form>
</span>
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

