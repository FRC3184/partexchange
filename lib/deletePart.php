<?php
include("dbinfo.php");
session_start();

if (isset($_SESSION['logged']) and $_SESSION['logged'] and isset($_GET['id']) and isset($_SESSION['teamID'])) {
  try {
    $conn = new PDO( "mysql:host=$dbHost;dbname=$dbInstance", $dbRW, $dbRWPw);
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
  }
  catch (Exception $e) {
    die( print_r( $e->getMessage(), true));
  }
  $dbQuery = "DELETE FROM requests WHERE idrequests=".$conn->quote($_GET['id']) .
             " AND (request_teamID=".$_SESSION['teamID']." OR " .$_SESSION['level'].">=1)";
  $conn->query($dbQuery);
}
header("Location: /parts/");
?>
