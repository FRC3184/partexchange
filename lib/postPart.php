<?php
require 'mail.php';
require "dbinfo.php";
session_start();
if(isset($_SESSION['logged']) and $_SESSION['logged']){


  require_once('recaptchalib.php');
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



  $name = "".$dbHost . "\\" . $dbInstance . ",1433";
  try {
    $conn = new PDO( "mysql:host=$dbHost;dbname=$dbInstance", $dbRW, $dbRWPw);
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
  }
  catch (Exception $e) {
    die( print_r( $e->getMessage(), true));
  }

  $dbQuery = "INSERT INTO requests ";
  $values = "(";
  $fields = "(";
  $comma = FALSE;
  echo sizeof($_FILES);

  $values .= "'".$_SESSION["teamID"]."', NOW(), ";
  $fields .="request_teamID, request_date, ";

  if (strlen($_POST['shortDesc']) > 0) {
    $values .= $conn->quote($_POST['shortDesc']);
    $fields .= "description";
    $comma = TRUE;
  }
  else {
    header("Location: ../parts/request.php?err=0");
    exit;
  }
  if (strlen($_POST['longDesc']) > 0) {
    if ($comma) {
      $values .= ",";
      $fields .= ",";
    }
    $values .= $conn->quote($_POST['longDesc']);
    $fields .= "long_description";

    $comma = TRUE;
  }
  if (strlen($_POST['partURL']) > 0) {
    if ($comma) {
      $values .= ",";
      $fields .= ",";
    }
    $values .= $conn->quote($_POST['partURL']);
    $fields .= "site_url";
    $comma = TRUE;
  }
  if (file_exists($_FILES['image']['tmp_name']) || is_uploaded_file($_FILES['image']['tmp_name'])) {
    $allowedExts = array("gif", "jpeg", "jpg", "png");
    $temp = explode(".", $_FILES["image"]["name"]);
    $extension = end($temp);

    if ((($_FILES["image"]["type"] == "image/gif")
        || ($_FILES["image"]["type"] == "image/jpeg")
        || ($_FILES["image"]["type"] == "image/jpg")
        || ($_FILES["image"]["type"] == "image/pjpeg")
        || ($_FILES["image"]["type"] == "image/x-png")
        || ($_FILES["image"]["type"] == "image/png"))
        && in_array($extension, $allowedExts)) {
      if ($_FILES["image"]["error"] > 0) {
        header("Location: ../parts/request.php?err=1");
        echo "error";
        exit;
      } else {

        if ($comma) {
          $values .= ",";
          $fields .= ",";
        }
        $values .= $conn->quote($extension);
        $fields .= "image_ext";
        $comma = TRUE;
      }
    } else {
      echo "bad file";
      header("Location: ../parts/request.php?err=1");
      exit;
    }
  }
  $dbQuery .= $fields . ") VALUES " . $values . ");";

  echo $dbQuery;
  $result = $conn->query($dbQuery);
  move_uploaded_file($_FILES["image"]["tmp_name"], "../images/".($conn->lastInsertId()).".".$extension);
  $email = setupMail();
  $email->setFrom("part-notify@parts.blazerobotics.org");
  foreach ($mailRecipients as $addr) {
    $email->addAddress($addr);
  }
  $email->isHTML(true);

  $email->Subject = "Team " . $_SESSION['teamID'] . " requested a part: " . $_POST['shortDesc'];
  $email->Body = $_POST['longDesc'] . '<br /><a href="parts.blazerobotics.org/parts/part.php?id=' . ($conn->lastInsertId()) . '">Click here to view</a>';

  $res = send($email);
  if ($res) {
    die($res);
  }

  header("Location: ../parts/index.php");
  }
  else {
    header("Location: /parts/request.php?err=2&txt=" . $resp->$errorCodes[0]);
    exit;
  }
} else {    //If the form button wasn't submitted go to the index page
    header("Location: ../parts/request.php");
    exit;
}
?>
