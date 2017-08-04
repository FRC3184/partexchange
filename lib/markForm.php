<?php
if (isset($_POST['username'])) {
  require('database.php');
  require("vars.php");

  if (!$logged) {
    header("Location: part.php?id=".$_POST['id']."&err=0");
  }

  if (preg_match("/\d+/", $_POST["username"]) !== 1) {
    header("Location: /parts/part.php?id=".$_POST['id']."&err=2");
    exit;
  }

  $conn = db_connect_rw();

  $check_sql = $conn->prepare("SELECT COUNT(*) FROM requests WHERE idrequests=:id
                               AND (request_teamID=:team OR :level>=1)");
  $check_sql->execute(array(":id" => $_POST['id'], ":team" => $_SESSION['teamID'], ":level" => $_SESSION['level']));
  if ($check_sql->fetchColumn() != 1) {
    header("Location: /parts/part.php?id=".$_POST['id']."&err=1");
    exit;
  }
  $mark_sql = $conn->prepare("UPDATE requests SET supply_team_id=:team, fulfilled_date=NOW() WHERE idrequests=:id");
  $mark_sql->execute(array(":team" => $_POST['username'], ":id" => $_POST['id']));
  header("Location: ../parts/part.php?id=".$_POST['id']);

} else {    //If the form button wasn't submitted go to the index page
  header("Location: ../parts/index.php");
  exit;
}
?>
