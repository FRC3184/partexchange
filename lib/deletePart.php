<?php
require("database.php");
session_start();

if (isset($_SESSION['logged']) and $_SESSION['logged'] and isset($_GET['id']) and isset($_SESSION['teamID'])) {
  $conn = db_connect_rw();
  $sql = $conn->prepare("DELETE FROM requests WHERE idrequests=:id AND (request_teamID=:team OR :level>=1)");
  $sql->execute(array(':id' => $_GET['id'], 'team' => $_SESSION['teamID'], ':level' => $_SESSION['level']));
}
header("Location: /parts/");
?>
