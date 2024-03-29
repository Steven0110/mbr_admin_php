<?php include 'head.php';

$infID = $_REQUEST["infID"];

$myQuery = $db->query("SELECT T1.ID, T1.code, T2.name store, CONCAT(T3.first, ' ', T3.last) emp, DATE_FORMAT(T1.created_at, '%d-%m-%Y %T') created, T1.remarks FROM inflows T1 INNER JOIN stores T2 ON T1.storeID = T2.ID INNER JOIN crew T3 ON T1.empID = T3.ID WHERE T1.ID = '$infID'");

$row = $myQuery->fetch();
$remarks = $row["remarks"];
$folio = $row["code"];

$queryNext = "SELECT MIN(ID) nextID FROM inflows WHERE ID > '$infID' AND storeID <> 0";

$resultNext = $db->query($queryNext);
$rowNext = $resultNext->fetch();
$nextID = $rowNext["nextID"];

$queryPrev = "SELECT MAX(ID) prevID FROM inflows WHERE ID < '$infID' AND storeID <> 0";

$resultPrev = $db->query($queryPrev);
$rowPrev = $resultPrev->fetch();
$prevID = $rowPrev["prevID"];
?>

<div class="sectionTitle">
    <a href="inflowDetail.php?infID=<?php echo $prevID; ?>"><i class="fa fa-arrow-left prev" aria-hidden="true"></i></a>
    ENTRADA <?php echo $folio; ?>
    <a href="inflowDetail.php?infID=<?php echo $nextID; ?>"><i class="fa fa-arrow-right next" aria-hidden="true"></i></a>
</div>

<div class="format">
<form>
<table width="100%" border="0" cellspacing="20px" cellpadding="0">
  <tbody>
    <tr>
      <td width="50%">Almac�n<br>
        <div style="margin-top:10px"><input type="text" value="<?php echo $row['store']; ?>" disabled class='inputText'></div>
      </td>
      <td width="50%">Empleado<br>
        <div style="margin-top:10px"><input type="text" value="<?php echo $row['emp']; ?>" disabled class='inputText'></div>
        </td>
    </tr>
    <tr>
      <td>Fecha de creaci�n<br><div style="margin-top:10px"><input type="text" value="<?php echo $row['created']; ?>" disabled class='inputText'></div></td>
        <td></td>
    </tr>
    <tr>
      <td colspan="2">Partidas<br>
        <div id="itemContainer">
          <div class='itemListHead'>
              <table class='itemTable' width='100%' cellpadding='0' cellspacing='10px'>
                <tr>
                            <td width="76px">Cantidad</td>
                            <td width="250px">C�digo</td>
                  <td>Producto</td>
                </tr>
              </table>
          </div>
          <?php
          $myQuery = $db->query("SELECT T2.ID, T1.prodCode, T2.name, T1.qty FROM inln T1 INNER JOIN product T2 ON T1.prodCode = T2.code WHERE T1.infID = '$infID'");

      
      while($row = $myQuery->fetch()){      
        echo "
          <div class='item'>
            <table class='itemTable' width='100%' cellpadding='0' cellspacing='10px'>
              <tr>
                <td width='76px'><input type='text' value='".$row["qty"]."' disabled class='inputText' style='width:70px !important'></td>
                <td width='250px'><input type='text' value='".$row["prodCode"]."' disabled class='inputText'></td>
                <td><input type='text' value='".$row["name"]."' disabled class='inputText'></td>
              </tr>
            </table>
          </div>
        ";
      };
      ?>
        </div>
    </td>
    </tr>
    <tr>
      <td colspan="2">Comentarios<br><textarea disabled><?php echo $remarks; ?></textarea></td>
    </tr>
    <tr>
      <td colspan="2">
          <ul id="buttonBar">
              <li><button type='button' class='formButton blueB' onClick="getback();"><i class='fa fa-hand-o-left' aria-hidden='true'></i> Regresar</button></li>
                <li><a class="formButton blueB" href="entrada.php?infID=<?php echo $infID; ?>" target="_blank"><i class='fa fa-file-pdf-o' aria-hidden='true'></i> PDF</a></li>
        </ul>
        </td>
    </tr>
  </tbody>
</table>
</form>
</div>
<script>
function getback() {
  window.history.back();
}
</script>
    
<?php include 'footer.php'; ?>