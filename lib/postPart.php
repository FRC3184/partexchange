<?php
require 'mail.php';
require "database.php";
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
  if ($resp != NULL && $resp->success) {

  $conn = db_connect_rw();
  $long_desc = NULL;
  $part_url = NULL;
  $image_ext = NULL;

  if (strlen($_POST['shortDesc']) > 0) {
    $desc = $_POST['shortDesc'];
  }
  else {
    header("Location: ../parts/request.php?err=0");
    exit;
  }
  if (strlen($_POST['longDesc']) > 0) {
    $long_desc = $_POST['longDesc'];
  }
  if (strlen($_POST['partURL']) > 0) {
    $part_url = $_POST['partURL'];
  }
  $extension = NULL;
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

        $image_ext = $extension;
      }
    } else {
      echo "bad file";
      header("Location: ../parts/request.php?err=1");
      exit;
    }
  }

  $insert_sql = $conn->prepare("INSERT INTO requests
                                (request_teamID, request_date, description, long_description, site_url, image_ext)
                                VALUES (:team, NOW(), :description, :long_desc, :url, :image_ext)");

  $insert_sql->execute(array(":team" => $_SESSION['teamID'], ":description" => $desc, ":long_desc" => $long_desc,
                             ":url" => $part_url, ":image_ext" => $image_ext));
  if ($extension != NULL) {
    move_uploaded_file($_FILES["image"]["tmp_name"], "../images/".($conn->lastInsertId()).".".$extension);
  }

  $find_recipients_sql = $conn->prepare("SELECT email FROM teams WHERE 
                                         region=(SELECT region FROM teams WHERE teamId=:req_team) 
                                         AND gets_emails=1 AND teamId<>:req_team");
  $find_recipients_sql->execute(array(":req_team" => $_SESSION['teamID']));
  while ($team = $find_recipients_sql->fetch(PDO::FETCH_ASSOC)) {
    echo $team;
    $email = setupMail();
    $email->addAddress($team['email']);
    $email->isHTML(true);
    $email->Subject = "Team " . $_SESSION['teamID'] . " requested a part: " . $_POST['shortDesc'];
    $email->Body = $_POST['longDesc'] . '<br /><a href="parts.blazerobotics.org/parts/part.php?id=' . ($conn->lastInsertId()) . '">Click here to view</a>' . $unsubscribe_info;
    $res = send($email);
    if ($res) {
      die($res);
    }
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
