<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

include 'includes/myRegs.php';
 
sec_session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>My Deal Regs ~ Channel Portal</title>
<link class="component" href="/faces/a4j/s/3_3_3.Finalorg/richfaces/renderkit/html/css/table.xcss/DATB/eAEz2rRkV-jyGdIAEoQD8g__" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="https://meraki.secure.force.com/favicon.ico" />
</head>
<body>
<?php if (login_check($mysqli) == true) : ?>
<!-- Main Body Starts Here -->
<a href="#skiplink" class="navSkipLink zen-skipLink zen-assistiveText">Skip to main content</a>
<link href="css/style.css" rel="stylesheet" type="text/css" />

<link href="/resource/0000000000000/fav_ico" rel="icon" type="image/x-icon" />
<link href="/resource/0000000000000/fav_ico" rel="shortcut icon" type="image/x-icon" />


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
<div id="bd_l"></div><div id="bd_r"></div><div class="bgdPalette brandPrimaryBgr" id="motifCurve"><div class="bgdPalette brandPrimaryBgr" id="mc_l"></div><div class="bgdPalette brandPrimaryBgr" id="mc_r"></div></div><div id="bd_b">
<div id="bd_bl"></div><div id="bd_br"></div></div><table class="outerNoSidebar" id="bodyTable" border="0" cellspacing="0" cellpadding="0"><tr><td class="noSidebarCell">
<!-- Start page content table -->
  <div class="grid_5">
    <h1>My Deal Registrations</h1>
  </div>
  <div class="grid_7"><span id="j_id0:j_id2">
  <ul class="nav nav-pills">
      <li class=""><a href="dealreg.php">Submit a Deal Reg</a></li>
      <li class="active"><a href="myDealRegistrations.php">My Deal Regs</a></li>
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
  <div class="clearfix"></div></span>
  </div><span id="j_id0:j_id11"></span>
  <div class="grid_12">
    <div class="search">
<!--form id="j_id0:j_id38" name="j_id0:j_id38" method="post" action="/MyDealRegistrations" enctype="application/x-www-form-urlencoded">
<input type="hidden" name="j_id0:j_id38" value="j_id0:j_id38" />
<input id="j_id0:j_id38:search" type="text" name="j_id0:j_id38:search" onkeypress="return filterEnterEvent(event);" />&nbsp;&nbsp;<input class="btn" id="j_id0:j_id38:searchButton" name="j_id0:j_id38:searchButton" onclick="A4J.AJAX.Submit('j_id0:j_id38',event,{'status':'j_id0:j_id38:j_id41:j_id42:busy','oncomplete':function(request,event,data){initSort()},'similarityGroupingId':'j_id0:j_id38:searchButton','parameters':{'j_id0:j_id38:searchButton':'j_id0:j_id38:searchButton'} } );return false;" value="Search" type="button" />
<script id="j_id0:j_id38:j_id40" type="text/javascript">doSearchAF=function(){A4J.AJAX.Submit('j_id0:j_id38',null,{'status':'j_id0:j_id38:j_id41:j_id42:busy','oncomplete':function(request,event,data){initSort()},'similarityGroupingId':'j_id0:j_id38:j_id40','parameters':{'j_id0:j_id38:j_id40':'j_id0:j_id38:j_id40'} } )};
</script>

<img id="j_id0:j_id38:j_id41:j_id42:loading" src="/resource/1424107548000/loading_gif" class="hidden" /></span>
        	<div id="pageNav" style="float:right; margin-top:20px "><a href="#" id="j_id0:j_id38:j_id45" name="j_id0:j_id38:j_id45" onclick="A4J.AJAX.Submit('j_id0:j_id38',event,{'status':'j_id0:j_id38:j_id41:j_id42:busy','similarityGroupingId':'j_id0:j_id38:j_id45','parameters':{'j_id0:j_id38:j_id45':'j_id0:j_id38:j_id45'} } );return false;">Previous</a><span id="j_id0:j_id38:PageNumbers" style="margin-left: 10px; margin-right: 10px"> Page 1 of 1</span><a href="#" id="j_id0:j_id38:j_id47" name="j_id0:j_id38:j_id47" onclick="A4J.AJAX.Submit('j_id0:j_id38',event,{'status':'j_id0:j_id38:j_id41:j_id42:busy','similarityGroupingId':'j_id0:j_id38:j_id47','parameters':{'j_id0:j_id38:j_id47':'j_id0:j_id38:j_id47'} } );return false;">Next</a>
			</div><div id="j_id0:j_id38:j_id73"></div>
</form-->

<style>
  .hidden{
   		display:none;
  }
</style>

    </div>
    <input id="lead_ownerID" maxlength="10" name="lead_ownerID" size="10" type="hidden" value="<?php echo htmlentities($_SESSION['user_id']); ?>" />
    <?php
		//execute the SQL query to discover if user is admin
		$myQuery = mysql_query("SELECT role FROM members WHERE id = ".htmlentities($_SESSION['user_id'])."");
		while($row = mysql_fetch_array($myQuery)){
			$role = $row["role"];
		};
		if ($role == 1) {
			//execute the SQL query and return records
			$myQuery = mysql_query("SELECT DATE, STAGE, ID, STATUS, C_COMPANY, C_FIRST, C_LAST, C_EMAIL, C_PHONE, LOCATION, AMOUNT, CHAN_ID FROM oprt");
			
			echo "<table class='tablesorter' id='j_id0:dealReg' border='0' cellpadding='0' cellspacing='0'>
			<colgroup span='10'></colgroup>
			<thead class=''>
			<tr>
			<th class='tableHeader' scope='col' colspan='1' id='j_id0:dealReg:j_id50header'><div id='j_id0:dealReg:j_id50header:sortDiv'>Created</div></th>
			<th class='tableHeader' scope='col' colspan='1' id='j_id0:dealReg:j_id51header'><div id='j_id0:dealReg:j_id51header:sortDiv'>Stage</div></th>
			<th class='tableHeader' scope='col' colspan='1' id='j_id0:dealReg:j_id52header'><div id='j_id0:dealReg:j_id52header:sortDiv'>Deal Reg #</div></th>
			<th class='tableHeader' scope='col' colspan='1' id='j_id0:dealReg:j_id53header'><div id='j_id0:dealReg:j_id53header:sortDiv'>Status</div></th>
			<th class='tableHeader' scope='col' colspan='1' id='j_id0:dealReg:j_id54header'><div id='j_id0:dealReg:j_id54header:sortDiv'>End Customer</div></th>
			<th class='tableHeader' scope='col' colspan='1' id='j_id0:dealReg:j_id55header'><div id='j_id0:dealReg:j_id55header:sortDiv'>Customer Contact</div></th>
			<th class='tableHeader' scope='col' colspan='1' id='j_id0:dealReg:j_id56header'><div id='j_id0:dealReg:j_id56header:sortDiv'>Meraki Rep</div></th>
			<th class='tableHeader' scope='col' colspan='1' id='j_id0:dealReg:j_id59header'><div id='j_id0:dealReg:j_id59header:sortDiv'>Estimated<br /># Nodes</div></th>
			<th class='tableHeader alignRight' scope='col' colspan='1' id='j_id0:dealReg:j_id62header'><div id='j_id0:dealReg:j_id62header:sortDiv'>Estimated<br />Sale</div></th>
			<th class='tableHeader' scope='col' colspan='1' id='j_id0:dealReg:j_id59header'><div id='j_id0:dealReg:j_id59header:sortDiv'>&nbsp;</div></th>
			</tr>
			</thead>
			<tbody id='j_id0:dealReg:tb'>";
			while($row = mysql_fetch_array($myQuery)){
				echo "<tr>
				<td id='j_id0:dealReg:0:j_id50' colspan='1'>".$row["DATE"]."</td>
				<td id='j_id0:dealReg:0:j_id51' colspan='1'>".$row["STAGE"]."</td>
				<td id='j_id0:dealReg:0:j_id52' colspan='1'>".$row["ID"]."</td>
				<td id='j_id0:dealReg:0:j_id53' colspan='1'>".$row["STATUS"]."</td>
				<td id='j_id0:dealReg:0:j_id54' colspan='1'>".$row["C_COMPANY"]."</td>
				<td id='j_id0:dealReg:0:j_id55' colspan='1'>".$row["C_FIRST"]." ".$row["C_LAST"]."; ".$row["C_EMAIL"]."; ".$row["C_PHONE"]."</td>
				<td id='j_id0:dealReg:0:j_id56' colspan='1'><a href='mailto:channels@supportmeraki.com'>MBR Hosting S.A. de C.V.</a></td>
				<td id='j_id0:dealReg:0:j_id59' colspan='1'>".$row["LOCATION"]."</td>
				<td class='alignRight' id='j_id0:dealReg:0:j_id62' colspan='1'>$".$row["AMOUNT"]."</td>
				<td><a href='http://channels.supportmeraki.com/dealedit.php?oprt=".$row["ID"]."'><img src='http://channels.supportmeraki.com/images/icon_edit.gif' style='max-width:16px;'></a></td>
				</tr>";
			};
			echo "</tbody></table>";
		}
		else {
			//execute the SQL query and return records
			$myQuery = mysql_query("SELECT DATE, STAGE, ID, STATUS, C_COMPANY, C_FIRST, C_LAST, C_EMAIL, C_PHONE, LOCATION, AMOUNT, CHAN_ID FROM oprt WHERE CHAN_ID = ".htmlentities($_SESSION['user_id'])."");
			
			echo "<table class='tablesorter' id='j_id0:dealReg' border='0' cellpadding='0' cellspacing='0'>
			<colgroup span='9'></colgroup>
			<thead class=''>
			<tr>
			<th class='tableHeader' scope='col' colspan='1' id='j_id0:dealReg:j_id50header'><div id='j_id0:dealReg:j_id50header:sortDiv'>Created</div></th>
			<th class='tableHeader' scope='col' colspan='1' id='j_id0:dealReg:j_id51header'><div id='j_id0:dealReg:j_id51header:sortDiv'>Stage</div></th>
			<th class='tableHeader' scope='col' colspan='1' id='j_id0:dealReg:j_id52header'><div id='j_id0:dealReg:j_id52header:sortDiv'>Deal Reg #</div></th>
			<th class='tableHeader' scope='col' colspan='1' id='j_id0:dealReg:j_id53header'><div id='j_id0:dealReg:j_id53header:sortDiv'>Status</div></th>
			<th class='tableHeader' scope='col' colspan='1' id='j_id0:dealReg:j_id54header'><div id='j_id0:dealReg:j_id54header:sortDiv'>End Customer</div></th>
			<th class='tableHeader' scope='col' colspan='1' id='j_id0:dealReg:j_id55header'><div id='j_id0:dealReg:j_id55header:sortDiv'>Customer Contact</div></th>
			<th class='tableHeader' scope='col' colspan='1' id='j_id0:dealReg:j_id56header'><div id='j_id0:dealReg:j_id56header:sortDiv'>Meraki Rep</div></th>
			<th class='tableHeader' scope='col' colspan='1' id='j_id0:dealReg:j_id59header'><div id='j_id0:dealReg:j_id59header:sortDiv'>Estimated<br /># Nodes</div></th>
			<th class='tableHeader alignRight' scope='col' colspan='1' id='j_id0:dealReg:j_id62header'><div id='j_id0:dealReg:j_id62header:sortDiv'>Estimated<br />Sale</div></th>
			</tr>
			</thead>
			<tbody id='j_id0:dealReg:tb'>";
			while($row = mysql_fetch_array($myQuery)){
				echo "<tr>
				<td id='j_id0:dealReg:0:j_id50' colspan='1'>".$row["DATE"]."</td>
				<td id='j_id0:dealReg:0:j_id51' colspan='1'>".$row["STAGE"]."</td>
				<td id='j_id0:dealReg:0:j_id52' colspan='1'>".$row["ID"]."</td>
				<td id='j_id0:dealReg:0:j_id53' colspan='1'>".$row["STATUS"]."</td>
				<td id='j_id0:dealReg:0:j_id54' colspan='1'>".$row["C_COMPANY"]."</td>
				<td id='j_id0:dealReg:0:j_id55' colspan='1'>".$row["C_FIRST"]." ".$row["C_LAST"]."; ".$row["C_EMAIL"]."; ".$row["C_PHONE"]."</td>
				<td id='j_id0:dealReg:0:j_id56' colspan='1'><a href='mailto:channels@supportmeraki.com'>MBR Hosting S.A. de C.V.</a></td>
				<td id='j_id0:dealReg:0:j_id59' colspan='1'>".$row["LOCATION"]."</td>
				<td class='alignRight' id='j_id0:dealReg:0:j_id62' colspan='1'>$".$row["AMOUNT"]."</td>
				</tr>";
			};
			echo "</tbody></table>";
		};
	?>
            
    
        <p>Your Meraki Rep: MBR Hosting S.A. de C.V.</p>
  </div>

</div>


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

</div>  <!-- This div is opened in the header -->
 <div id="wish_spacer_box"></div>
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
</table><?php endif; ?>
</body>
</html>