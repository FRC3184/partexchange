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

  // Somehow necessary. I don't know why.
  function fix($id) {
    return str_replace("''", "'", $id);
  }

  $query = "SELECT * FROM requests WHERE idrequests=".fix($conn->quote($_POST['id'])) .
           " AND (request_teamID=".$_SESSION['teamID']." OR " .$_SESSION['level'].">=1)";
  $result = $conn->query($query);
  if (sizeof($result) != 1) {
    header("Location: /parts/part.php?id=".$_POST['id']."&err=1");
  }
  $conn->query("UPDATE requests SET supply_team_id=".$conn->quote($_POST['username']).", fulfilled_date=NOW() WHERE idrequests=".
                $conn->quote($_POST['id']));
  header("Location: ../parts/part.php?id=".$_POST['id']);

} else {    //If the form button wasn't submitted go to the index page
  header("Location: ../parts/index.php");
  exit;
}
?>
