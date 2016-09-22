<?php
if (isset($_POST['username'])) {
  include "dbinfo.php";
  include "vars.php";

  if (!$logged) {
    header("Location: part.php?id=".$_POST['id']."&err=0");
  }

  $name = "".$dbHost . "\\" . $dbInstance . ",1433";
  try {
    $conn = new PDO( "mysql:host=$dbHost;dbname=$dbInstance", $dbRW, $dbRWPw);
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
  }
  catch (Exception $e) {
    die( print_r( $e->getMessage(), true));
  }
  //Selects the database
  $sql = $conn->query("SELECT * FROM requests
                       WHERE request_teamID='".$_SESSION['teamID']."' AND
                       idrequests=".$conn->quote($_POST['id']).";");
  if ($conn->query("SELECT COUNT(*) FROM requests
                    WHERE request_teamID='".$_SESSION['teamID']."' AND
                    idrequests=".$conn->quote($_POST['id']))->fetchColumn() != 1){
    header("Location: part.php?id=".$_POST['id']."&err=1");
  }
  $conn->query("UPDATE requests SET supply_team_id='".$_POST['username']."', fulfilled_date=NOW() WHERE idrequests='".$_POST['id']."';");
  header("Location: ../parts/part.php?id=".$_POST['id']);

} else {    //If the form button wasn't submitted go to the index page
  header("Location: ../parts/index.php");
  exit;
}
?>
