<?php
include 'mailVars.php';
require 'mail.php';
session_start();
if(isset($_POST['submit']) and isset($_SESSION['logged']) and $_SESSION['logged']){

  require_once('recaptchalib.php');
  $siteKey = "6LcXGfwSAAAAACkoABhkFZWun5IgorYz0qgysE0K";
  $secret = "6LcXGfwSAAAAABnW3eS2I_xUIPCHTJp-3L52E4bX";
  // reCAPTCHA supported 40+ languages listed here: https://developers.google.com/recaptcha/docs/language
  $lang = "en";

  // The response from reCAPTCHA
  $resp = null;
  // The error code from reCAPTCHA, if any
  $error = null;

  $reCaptcha = new ReCaptcha($secret);

  // Was there a reCAPTCHA response?
  if ($_POST["g-recaptcha-response"]) {
      $resp = $reCaptcha->verifyResponse(
          $_SERVER["REMOTE_ADDR"],
          $_POST["g-recaptcha-response"]
      );
  }
  if ($resp != null && $resp->success) {

  include "dbinfo.php";

  $name = "".$dbHost . "\\" . $dbInstance . ",1433";
	try {
    $conn = new PDO( "mysql:host=$dbHost;dbname=$dbInstance", $dbRW, $dbRWPw);
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	}
	catch (Exception $e) {
		die( print_r( $e->getMessage(), true));
	}
  $rows = $conn->query("SELECT COUNT(*) FROM requests")->fetchColumn();

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
        header("Location: index.php?err=" . $_FILES["image"]["error"]+2);
        echo "error";
        exit;
      } else {
        echo "moving";
        move_uploaded_file($_FILES["image"]["tmp_name"], "../images/".($rows+1).".".$extension);
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
      header("Location: index.php?err=1");
      exit;
    }
  }
  $dbQuery .= $fields . ") VALUES " . $values . ");";

  echo $dbQuery;
  $result = $conn->query($dbQuery);
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
    header("Location: /parts/request.php?err=1&txt=" . $resp->$errorCodes[0]);
    exit;
  }
} else {    //If the form button wasn't submitted go to the index page
    header("Location: ../parts/request.php");
    exit;
}
?>
