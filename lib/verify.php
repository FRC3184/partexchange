<?php
if (isset($_POST['submit'])) {
  require("database.php");

  $conn = db_connect_access();

  $usr = $conn->quote($_POST['username']);
  $salt_sql = $conn->prepare("SELECT salt FROM teams WHERE teamId=:team");
  if (!$salt_sql->execute(array(":team" => $_POST['username']))) {
    header("Location: /account/login.php?err=nomatch");
    exit;
  }
  $salt = $salt_sql->fetchColumn();
  $pas = hash('sha256', $salt + hash("sha256", $_POST['password']));
  $sql = $conn->prepare("SELECT COUNT(*), teamId, teamName, level FROM teams WHERE teamId=:team AND password=:password");
  $sql->execute(array(":team" => $_POST['username'], ":password" => $pas));
  $row = $sql->fetch();
  if ($row['0'] == 1) {
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
