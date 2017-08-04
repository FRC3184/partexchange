<?php
session_start();
if (isset($_GET['id']) and isset($_SESSION['logged']) and $_SESSION['logged']) {
  include "../lib/database.php";

  $conn = db_connect_rw();
  $query = $conn->prepare("INSERT INTO flags (idrequests, teamId) VALUES (:id, :team)");
  try {
    $query->execute(array(":id" => $_GET['id'], ":team" => $_SESSION['teamID']));
  }
  catch (PDOException $e) {
    // Probably failed the unique test. Fail silently.
  }
  header("Location: ./index.php");
}
else {
  header("Location: /account/login.php");
  exit;
}
?>
