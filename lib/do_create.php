<?php
if (!empty($_POST)) {
  require_once('recaptchalib.php');
  require("database.php");
  // reCAPTCHA supported 40+ languages listed here: https://developers.google.com/recaptcha/docs/language
  $lang = "en";

  // The response from reCAPTCHA
  $resp = null;
  // The error code from reCAPTCHA, if any
  $error = null;

  $reCaptcha = new ReCaptcha($recap_secret);

  // Was there a reCAPTCHA response?
  if ($_POST["g-recaptcha-response"]) {
      $resp = $reCaptcha->verifyResponse(
          $_SERVER["REMOTE_ADDR"],
          $_POST["g-recaptcha-response"]
      );
  }
  if ($resp != null && $resp->success) {



    //Verify passwords
    if (strcmp($_POST['password1'], $_POST['password2']) !== 0) {
        header("Location: /account/create.php?err=0");
        exit;
    }
    //Verify email
    if (preg_match("/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,9}|[0-9]{1,3})(\]?)$/", $_POST["email"]) !== 1) {
        header("Location: /account/create.php?err=1");
        exit;
    }

    $conn = db_connect_rw();

    //Verify team number
    if (preg_match("/\d+/", $_POST["teamNumber"]) !== 1) {
      header("Location: /account/create.php?err=2");
      exit;
    }
    $count_sql = $conn->prepare("SELECT COUNT(*) FROM teams WHERE teamId=:team");
    $count_sql->execute(array(":team" => $_POST['teamNumber']));
    if ($count_sql->fetchColumn() == 1) {
      header("Location: /account/create.php?err=3");
      exit;
    }

    require('region.php');
    //Verify district/region
    $region = $_POST['region'];
    if (!isValidRegion($region)) {
      header("Location: /account/create.php?err=5");
      exit;
    }
    $salt = random_bytes(32);
    $hashed_pass = hash("sha256", $salt + hash("sha256", $_POST['password1']));
    $query = $conn->prepare("INSERT INTO teams (teamId, teamName, email, password, region, salt)
                             VALUES (:id, :name, :email, :password, :region, :salt)");
    $query->execute(array(":id" => $_POST['teamNumber'],
                          ":name" => $_POST['teamName'],
                          ":email" => $_POST['email'],
                          ":password" => $hashed_pass,
                          ":region" => $region,
                          ":salt" => $salt));
    header("Location: /account/login.php");
  }
  else {
    header("Location: /account/create.php?err=4&txt=" . $resp->$errorCodes[0]);
    exit;
  }
} else {
  header("Location: /account/create.php");
}
