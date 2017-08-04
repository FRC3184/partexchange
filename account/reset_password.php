<?php
  require("../lib/database.php");
  $token = null;
  $conn = db_connect_rw();
  if (isset($_POST['token']) && isset($_POST['password'])) {
    $token = $_POST['token'];
    $password = $_POST['password'];

    $update_sql = $conn->prepare("UPDATE teams SET password=:password, salt=:salt WHERE teamId=:team");

    $token_sql = $conn->prepare("SELECT teamId FROM pass_reset WHERE token=:token AND expiration > NOW()");
    $token_sql->execute(array(":token" => $token));
    $res = $token_sql->fetchAll();
    if (count($res) !== 1) {
      $content = "Your reset link has expired. Please try again.";
    }
    else {
      $salt = random_bytes(32);
      $hashed_pass = hash("sha256", $salt + hash("sha256", $password));
      $teamId = $res[0][0];
      $update_sql->execute(array(":password" => $hashed_pass, ":salt" => $salt, ":team" => $teamId));

      $content = "Your password has been successfully reset. You may now log in.";
    }
  }
  elseif (isset($_GET['token'])) {
    global $token;
    $token = $_GET['token'];
    $content = "<form id='reset-pass' action='reset_password.php' method='post'>
                <input name='token' type='hidden' value='".$token."' />
                <div class='form-group'>
                  <input required class='form-control' name='password' id='password'
                  type='password' placeholder='Enter new password' />
                </div>
                <div class='form-group'>
                  <input required data-match='#password' data-match-error='Passwords must match'
                  class='form-control' name='verify_pass' id='verify_pass'
                  type='password' placeholder='Repeat password' />
                  <div class='help-block with-errors'></div>
                </div>
                <div class='form-group'>
                  <input name='submt' type='submit' value='Reset Password' class='btn btn-primary' />
                </div>
                </form>";
  }
  else {
    header("Location: index.php");
    die();
  }
?>

<!DOCTYPE html>
<html>
<head>

  <title>Reset Password</title>

  <?php include("../lib/head.html"); ?>
</head>
<body>
  <?php include '../lib/navbar.php'; ?>

  <div class="panel panel-primary">
    <div class="panel-heading">
      <h3 class="panel-title">Reset Password</h3>
    </div>
    <div class="panel-body">
      <?php echo $content; ?>
    </div>
  </div>


  <?php include '../lib/foot.html'; ?>
  <script type="text/javascript" src="/js/validator.min.js"></script>
  <script type="text/javascript">
    $("#reset-pass").validator();
  </script>
</body>
</html>
