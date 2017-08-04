<?php
include "../lib/vars.php";
if (!$logged) {
  //header("Location: login.php");
  echo "Not logged in";
}

if (!empty($_POST)) {
  include "../lib/database.php";
  $conn = db_connect_rw();
  $old_values_sql = $conn->prepare("SELECT email, twitter, website, zipcode, gets_emails, has_profile_pic, password, salt
                                    FROM teams WHERE teamId=:team");
  $old_values_sql->execute(array(':team' => $_SESSION['teamID']));
  $old_values = $old_values_sql->fetch();

  $email = $old_values['email'];
  $twitter = $old_values['twitter'];
  $website = $old_values['website'];
  $zipcode = $old_values['zipcode'];
  $gets_emails = $old_values['gets_emails'];
  $has_profile_pic = $old_values['has_profile_pic'];
  $password = $old_values['password'];
  $salt = $old_values['salt'];
  if (strlen($_POST['email']) > 0) {
    //Verify email
    if (preg_match("/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,9}|[0-9]{1,3})(\]?)$/", $_POST["email"]) !== 1) {
      header("Location: index.php?err=0");
      exit;
    }
    $email = $_POST['email'];
  }
  if (strlen($_POST['twitter']) > 0) {
    $twitter = $_POST['twitter'];
  }
  if (strlen($_POST['website']) > 0) {
    $website = $_POST['website'];
  }
  if (strlen($_POST['zipcode']) > 0) {
    $zipcode = $_POST['zipcode'];
  }

  //Gets emails
  $gets_emails = isset($_POST['gets_emails']) ? 1 : 0;

  if (file_exists($_FILES['picture']['tmp_name']) || is_uploaded_file($_FILES['picture']['tmp_name'])) {
    $allowedExts = array("gif", "jpeg", "jpg", "png");
    $temp = explode(".", $_FILES["picture"]["name"]);
    $extension = end($temp);

    if ((($_FILES["picture"]["type"] == "image/gif")
        || ($_FILES["picture"]["type"] == "image/jpeg")
        || ($_FILES["picture"]["type"] == "image/jpg")
        || ($_FILES["picture"]["type"] == "image/pjpeg")
        || ($_FILES["picture"]["type"] == "image/x-png")
        || ($_FILES["picture"]["type"] == "image/png"))
        && in_array($extension, $allowedExts)) {
      if ($_FILES["picture"]["error"] > 0) {
        header("Location: index.php?err=" . $_FILES["picture"]["error"]+2);
        echo "error";
        exit;
      } else {
        echo "moving";
        move_uploaded_file($_FILES["picture"]["tmp_name"], "../profile/".$_SESSION["teamID"].".png");
        $has_profile_pic = 1;
      }
    } else {
      echo "bad file";
      header("Location: index.php?err=1");
      exit;
    }
  }
  if (strlen($_POST['newpass']) > 0) {
    if ($_POST['newpass'] != $_POST['confirmpass']) {
			header("Location: index.php?err=2");
      exit;
    }
    $pas_verify = hash("sha256", $salt + hash("sha256", $_POST['oldpass']));
    if ($pas_verify === $password) {
      $salt = random_bytes(32);
      $password = hash("sha256", $salt + hash("sha256", $_POST['newpass']));
    }
    else {
      header("Location: index.php?err=2");
      exit;

    }
  }

  $update_sql = $conn->prepare("UPDATE teams SET
                                email=:email,
                                twitter=:twitter,
                                website=:website,
                                zipcode=:zipcode,
                                gets_emails=:gets_emails,
                                has_profile_pic=:has_profile_pic,
                                password=:password,
                                salt=:salt WHERE teamId=:team");
  $update_sql->execute(array(':email' => $email,
                             ':twitter' => $twitter,
                             ':website' => $website,
                             ':zipcode' => $zipcode,
                             ':gets_emails' => $gets_emails,
                             ':has_profile_pic' => $has_profile_pic,
                             ':password' => $password,
                             ':salt' => $salt,
                             ':team' => $_SESSION['teamID']));
  header("Location: index.php");
  exit;


} else {    // If the form button wasn't submitted go to the index page
  header("Location: index.php");
  exit;
}
?>
