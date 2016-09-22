<?php
session_start();
if (isset($_GET['id']) and isset($_SESSION['logged']) and $_SESSION['logged'] and $_SESSION['level'] >= 1) {
  include "../lib/dbinfo.php";

  $name = "".$dbHost . "\\" . $dbInstance . ",1433";
  try {
    $conn = new PDO( "mysql:host=$dbHost;dbname=$dbInstance", $dbRW, $dbRWPw);
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
  }
  catch (Exception $e) {
    die( print_r( $e->getMessage(), true));
  }
  $query = "UPDATE requests SET ";
  $comma = FALSE;

  if (isset($_GET['verified'])) {
    $query .= 'verified=' . $conn->quote($_GET['verified']);
    $comma = TRUE;
  }
  $query .= ' WHERE idrequests='.$conn->quote($_GET['id']);
  $conn->query($query);

  header("Location: part.php?id=" . $_GET['id']);
  exit;
}
else {
  header("Location: /account/login.php");
  exit;
}
?>
