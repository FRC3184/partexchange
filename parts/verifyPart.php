<?php
session_start();
if (isset($_GET['id']) and isset($_SESSION['logged']) and $_SESSION['logged'] and $_SESSION['level'] >= 1) {
  include "../lib/database.php";

  $conn = db_connect_rw();
  $query = $conn->prepare("UPDATE requests SET verified=1 WHERE idrequests=:id");
  $query->execute(array(":id" => $_GET['id']));
  header("Location: ./index.php");
}
else {
  header("Location: /account/login.php");
  exit;
}
?>
