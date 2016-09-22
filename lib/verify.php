<?php
if (isset($_POST['submit'])) {
  include "dbinfo.php";

  $name = "".$dbHost . "\\" . $dbInstance . ",1433";
  try {
    $conn = new PDO( "mysql:host=$dbHost;dbname=$dbInstance", $dbAccess, $dbAccessPw);
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
  }
  catch (Exception $e) {
    die( print_r( $e->getMessage(), true));
  }
  $usr = $conn->quote($_POST['username']);
  $pas = hash('sha256', $_POST['password']);
  $sql = $conn->query("SELECT * FROM teams WHERE teamId=".$usr." AND password='".$pas."';");
  if ($conn->query("SELECT COUNT(*) FROM teams WHERE teamId=".$usr." AND password='".$pas."';")->fetchColumn() == 1) {
    $row = $sql->fetch(PDO::FETCH_ASSOC);
    session_start();
    $_SESSION['teamID'] = $row['teamId'];
    $_SESSION['teamName'] = $row['teamName'];
    $_SESSION['logged'] = TRUE;
    $_SESSION['level'] = $row['level'];
    header("Location: ../index.php"); // Modify to go to the page you would like
    exit;
  } else {
    echo "fail";
    header("Location: /account/login.php?err=nomatch");
    exit;
  }
} else {    //If the form button wasn't submitted go to the login page
  header("Location: login.php");
  exit;
}
?>
