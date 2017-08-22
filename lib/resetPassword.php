<?php
include('database.php');
include('mail.php');

if (isset($_GET['team'])) {
  $token = uniqid();
  $team = $_GET['team'];
  $conn = db_connect_rw();

  $find_sql = $conn->prepare("SELECT COUNT(*), email FROM teams WHERE teamId=:team");
  $find_sql->execute(array(":team" => $team));
  $result = $find_sql->fetch();
  if ($result[0] != 1) {
    $content = "Couldn't find that team number";
  }
  else {
    $token_sql = $conn->prepare("INSERT INTO pass_reset (token, teamId, expiration) VALUES
                                 (:token, :team, NOW() + INTERVAL 15 MINUTE)");
    $token_sql->execute(array(":token" => $token, ":team" => $team));

    $email = setupMail();
    $email->setFrom("noreply@parts.blazerobotics.org");
    $email->addAddress($result['email']);
    $email->isHTML(true);
    $email->Subject = "Password reset request for team " . $team;
    $email->Body = "A password reset request was generated for team ".$team." on the Blaze Robotics parts exchange. If you did not generate this request, you can safely ignore this email.<br /><br /><a href='https://parts.blazerobotics.org/account/reset_password.php?token=".$token."'>Reset your password</a>";
    $res = send($email);
    if ($res) {
      die($res);
    }
    $content = "Check your inbox for a link to reset your password. This link will be valid for 15 minutes.";
  }
} else {
  $content = "Please enter a team number.";
}
?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/vars.php"); ?>
<!DOCTYPE html>
<html>
<head>

  <title>Reset Password</title>

  <?php include("head.html"); ?>
</head>
<body>
  <?php include 'navbar.php'; ?>

  <div class="panel panel-primary">
    <div class="panel-heading">
      <h3 class="panel-title">Reset Password</h3>
    </div>
    <div class="panel-body">
      <?php echo $content; ?>
    </div>
  </div>
  <?php include 'foot.html'; ?>
</body>
</html>
