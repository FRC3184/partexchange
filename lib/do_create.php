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

    //Get lat/long from TBA
    $teamId = $_POST['teamNumber'];
    $ch = curl_init("https://www.thebluealliance.com/api/v3/team/frc$teamId");
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-TBA-Auth-Key: $tba_key"));
    $tba_resp = curl_exec($ch);
    $tba_resp_code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);
    if ($tba_resp_code == 200) {
      $tba_data = json_decode($tba_resp, TRUE);
      $lat = $tba_data['lat'];
      $lng = $tba_data['lng'];
      //Get lat/long from Google because TBA doesn't actually have it.
      if ($lat == null or $lng == null) {
        $city = $tba_data['city'];
        $state_prov = $tba_data['state_prov'];
        $country = $tba_data['country'];
        $addr = "$city, $state_prov, $country";
        $encoded_addr = urlencode($addr);
        echo "Using address lookup<br/>";
        $ch = curl_init("https://maps.googleapis.com/maps/api/geocode/json?address=$encoded_addr&key=$maps_key");
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $maps_resp = curl_exec($ch);
        $maps_resp_code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);
        if ($maps_resp_code == 200) {
          $location = json_decode($maps_resp, TRUE)['results'][0]['geometry']['location'];
          $lat = $location['lat'];
          $lng = $location['lng'];
        }
        else {
          echo "Failed to get lat/long from Google. Error code $maps_resp_code";
          exit;
        }
      }

      //Auto-populate other info from tBA
      $teamName = $tba_data['nickname'];
      
    }
    else {
      echo "Failed to get data from TBA. Error code $tba_resp_code";
      exit;
    }

    $salt = random_bytes(32);
    $hashed_pass = hash("sha256", $salt + hash("sha256", $_POST['password1']));
    $query = $conn->prepare("INSERT INTO teams (teamId, teamName, email, password, region, salt, lat, lng)
                             VALUES (:id, :name, :email, :password, :region, :salt, :lat, :lng)");
    $query->execute(array(":id" => $teamId,
                          ":name" => $teamName,
                          ":email" => $_POST['email'],
                          ":password" => $hashed_pass,
                          ":region" => $region,
                          ":salt" => $salt,
                          ":lat" => $lat,
                          ":lng" => $lng));
    session_start();
    $_SESSION['teamID'] = $teamId;
    $_SESSION['teamName'] = $teamName;
    $_SESSION['logged'] = TRUE;
    $_SESSION['level'] = 0; // Default level. Only changed manually.
    header("Location: /account/");
  }
  else {
    header("Location: /account/create.php?err=4&txt=" . $resp->$errorCodes[0]);
    exit;
  }
} else {
  header("Location: /account/create.php");
}
