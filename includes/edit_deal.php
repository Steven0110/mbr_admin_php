<?php
$dbhost = 'localhost';
$dbuser = 'idited_cronos';
$dbpass = 'Cr0n0$';

$conn = mysql_connect($dbhost, $dbuser, $dbpass);

if(! $conn )
{
  die('Could not connect: ' . mysql_error());
}

$lead_id = $_POST['lead_id'];
$lead_ownerID = $_POST['lead_ownerID'];
$checkboxInt = join(", ", $_POST['checkboxInt']);
$sale_value = $_POST['sale_value'];
$company_name = $_POST['company_name'];
$company_industry = $_POST['company_industry'];
$company_size = $_POST['company_size'];
$customer_first = $_POST['customer_first'];
$customer_last = $_POST['customer_last'];
$customer_email = $_POST['customer_email'];
$customer_phone = $_POST['customer_phone'];
$customer_street = $_POST['customer_street'];
$customer_city = $_POST['customer_city'];
$customer_country = $_POST['customer_country'];
$lead_locations = $_POST['lead_locations'];
$lead_stage = $_POST['lead_stage'];
$lead_history = $_POST['lead_history'];
$lead_status = $_POST['lead_status'];

$sql = "UPDATE oprt SET CHAN_ID = '$lead_ownerID', PRODUCT = '$checkboxInt', AMOUNT = '$sale_value', C_COMPANY = '$company_name', C_INDUSTRY = '$company_industry', C_SIZE = '$company_size', C_FIRST = '$customer_first', C_LAST = '$customer_last', C_EMAIL = '$customer_email', C_PHONE = '$customer_phone', C_STREET = '$customer_street', C_CITY = '$customer_city', C_COUNTRY = '$customer_country', LOCATION = '$lead_locations', STAGE = '$lead_stage', EFFORT = '$lead_history', STATUS = '$lead_status' WHERE ID = $lead_id";

mysql_select_db('idited_cronos');
$retval = mysql_query( $sql, $conn );
if(! $retval )
{
  die('Could not modify data: ' . mysql_error());
}
header('Location: ../myDealRegistrations.php');
mysql_close($conn);
?>